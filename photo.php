<?php
    //error_reporting(E_ALL);
    ini_set('display_errors','1');
    include("smartyConfig.php");
    include("appWideConfig.php");
    include("dbConfig.php");
    include("includes/configs/configs.php");
    include("common/function.php");
    require_once "$_SERVER[DOCUMENT_ROOT]/includes/db_query.php";
    AdminAuthentication();

    if ( isset( $_REQUEST['upImg'] ) && $_REQUEST['upImg'] == 1 ) {
        //echo "<pre>"; print_r( $_REQUEST ); print_r( $_FILES ); die();
        $city     = !empty( $_REQUEST['cityId'] ) ? $_REQUEST['cityId'] : 0;
        $suburb   = !empty( $_REQUEST['suburbId'] ) ? $_REQUEST['suburbId'] : 0;
        $locality = !empty( $_REQUEST['localityId'] ) ? $_REQUEST['localityId'] : 0;

        if ( $city ) {
            $smarty->assign( 'cityId', $city );
        }
        if ( $suburb ) {
            $smarty->assign( 'suburbId', $suburb );
        }
        if ( $locality ) {
            $smarty->assign( 'localityId', $locality );
        }

        $errMsg = "";
        $columnName = "";
        if ( $city || $suburb || $locality ) {
            if ( $locality > 0 ) {
                $columnName = "LOCALITY_ID";
                $areaType = 'locality';
                $areaId = $locality;
            }
            elseif ( $suburb > 0 ) {
                $columnName = "SUBURB_ID";
                $areaType = 'suburb';
                $areaId = $suburb;
            }
            else {
                $columnName = "CITY_ID";
                $areaType = 'city';
                $areaId = $city;
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
            $addedImgIdArr = array();
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
                        $addedImgIdArr[] = addImageToDB( $columnName, $areaId, $imgName );
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
            if ( count( $addedImgIdArr ) ) {
                $imgData = getPhotoById( $addedImgIdArr );
                if ( count( $imgData ) ) {
                    $smarty->assign( 'uploadedImage', $imgData );
                }
            }
        }
        else {
            $message = array(
                'type' => 'error',
                'content' => $errMsg
            );
        }
        $smarty->assign( 'message', $message );
    }

    $response = getListing();   //  get City List

    $cityList = !empty( $response['city'] ) ? $response['city'] : "";

    if ( is_array( $cityList ) && count( $cityList ) ) {
        $smarty->assign( 'cityList', $cityList );
    }

    $smarty->assign( 'photoCSS', 1 );

    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."upload-photo.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");


?>
