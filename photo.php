<?php
    //error_reporting(E_ALL);
    ini_set('display_errors','1');
    include("smartyConfig.php");
    include("appWideConfig.php");
    include("dbConfig.php");
    include("includes/configs/configs.php");
    include("common/function.php");
    
    $accessPhoto = '';
    if( $imageAuth == false )
       $accessPhoto = "No Access";
    $smarty->assign("accessPhoto",$accessPhoto);
    
    require_once "$_SERVER[DOCUMENT_ROOT]/includes/db_query.php";
    AdminAuthentication();

    $selectedAreaType = array(
        'locality' => '',
        'suburb' => '',
        'city' => ''
    );
    $areaId = "";

    if ( isset( $_REQUEST['upImg'] ) && $_REQUEST['upImg'] == 1 ) {
        $errMsg = "";
        $columnName = "";
        $areaType = isset( $_REQUEST['areaType'] ) ? trim( $_REQUEST['areaType'] ) : "";
        if ( in_array( $areaType, array( 'city', 'locality', 'suburb' ) ) ) {
            $selectedAreaType[ $areaType ] = "selected";
            $columnName = strtoupper( $areaType )."_ID";
            if ( isset( $_REQUEST['areaId'] ) && $_REQUEST['areaId'] > 0 ) {
                $areaId = trim( $_REQUEST['areaId'] );
            }
            else {
                $errMsg = "Please select a ".$_REQUEST['areaType'];
            }
        }
        else {
            $errMsg = "Please select the area type (Locality/Suburb/City)";
        }

        $IMG = $_FILES['img'];
        $uploadStatus = array();
        if ( $errMsg == "" ) {
            //  add images to DB and to public_html folder
            $imageCount = count( $IMG['name'] );
            include("SimpleImage.php");
            $thumb = new SimpleImage();
            for( $__imgCnt = 0; $__imgCnt < $imageCount; $__imgCnt++ ) {
                if ( $IMG['error'][ $__imgCnt ] == 0 ) {
                    $extension = explode( "/", $IMG['type'][ $__imgCnt ] );
                    $extension = $extension[ count( $extension ) - 1 ];
                    $imgType = "";
                    if ( strtolower( $extension ) == "jpg" || strtolower( $extension ) == "jpeg" ) {
                        $imgType = IMAGETYPE_JPEG;
                    }
                    elseif ( strtolower( $extension ) == "gif" ) {
                        $imgType = IMAGETYPE_GIF;
                    }
                    elseif ( strtolower( $extension ) == "png" ) {
                        $imgType = IMAGETYPE_PNG;
                    }
                    else {
                        //  unknown format !!
                    }
                    if ( $imgType == "" ) {
                        $uploadStatus[ $IMG['name'][ $__imgCnt ] ] = "format not supported";
                    }
                    else {
                        //  no error
                        $__width = "592";
                        $__height = "444";
                        $__thumbWidth = "91";
                        $__thumbHeight = "68";
                        $imgName = $areaType."_".$areaId."_".$__imgCnt."_".time().".".strtolower( $extension );
                        $thumb->load( $IMG['tmp_name'][ $__imgCnt ] );
                        $thumb->resize( $__width, $__height );
                        $thumb->save($newImagePath.'locality/'.$imgName, $imgType);
                        $thumb->resize( $__thumbWidth, $__thumbHeight );
                        $thumb->save($newImagePath.'locality/thumb_'.$imgName, $imgType);
                        //  add image to DB
                        addImageToDB( $columnName, $areaId, $imgName );
                        $uploadStatus[ $IMG['name'][ $__imgCnt ] ] = "uploaded";
                    }
                }
                else {
                    $uploadStatus[ $IMG['name'][ $__imgCnt ] ] = "Error#".$IMG['error'][ $__imgCnt ];
                }
            }
            $str = "";
            foreach( $uploadStatus as $__imgName => $__statusMsg ) {
                if ( $str ) {
                    $str .= "; ".$__imgName." : ".$__statusMsg;
                }
                else {
                    $str = $__imgName." : ".$__statusMsg;
                }
            }
            $message = array(
                'type' => 'success-msg',
                'content' => $str
            );
        }
        else {
            $message = array(
                'type' => 'error',
                'content' => $errMsg
            );
        }
        $smarty->assign( 'message', $message );
    }

    $areaType = isset( $_REQUEST['areaType'] ) ? trim( $_REQUEST['areaType'] ) : "";

    $data = getListing( $areaType );
    if ( is_array( $data ) && count( $data ) ) {
        $smarty->assign( 'areaList', $data );
    }
    $smarty->assign( 'selectedAreaType', $selectedAreaType );
    $smarty->assign( 'areaId', $areaId );

    $smarty->assign( 'photoCSS', 1 );

    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."upload-photo.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");


?>
