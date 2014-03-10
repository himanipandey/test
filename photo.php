<?php
    //error_reporting(E_ALL);
    ini_set('display_errors','1');
    include("smartyConfig.php");
    include("appWideConfig.php");
    include("dbConfig.php");
    include("includes/configs/configs.php");
    include("common/function.php");
    include("s3upload/s3_config.php");
    require_once "$_SERVER[DOCUMENT_ROOT]/includes/db_query.php";
    AdminAuthentication();
//echo "<pre>";
///print_r($_REQUEST);
//echo "<pre>";
//print_r($_FILES);
//die;
    if ( isset( $_REQUEST['upImg'] ) && $_REQUEST['upImg'] == 1 ) {
        //echo "<pre>"; print_r( $_REQUEST ); print_r( $_FILES ); die();
        $city     = !empty( $_REQUEST['cityId'] ) ? $_REQUEST['cityId'] : 0;
        $suburb   = !empty( $_REQUEST['suburbId'] ) ? $_REQUEST['suburbId'] : 0;
        $locality = !empty( $_REQUEST['localityId'] ) ? $_REQUEST['localityId'] : 0;
        $imgCategory = !empty( $_REQUEST['imgCategory'] ) ? $_REQUEST['imgCategory'] : '';
        $imgDisplayName = !empty( $_REQUEST['imgDisplayName'] ) ? $_REQUEST['imgDisplayName'] : '';
        $imgDescription = !empty( $_REQUEST['imgDescription'] ) ? $_REQUEST['imgDescription'] : '';

        if ( $city ) {
            $smarty->assign( 'cityId', $city );
        }
        if ( $suburb ) {
            $smarty->assign( 'suburbId', $suburb );
        }
        if ( $locality ) {
            $smarty->assign( 'localityId', $locality );
        }
        if ( $imgCategory ) {
            $smarty->assign( 'imgCategory', $imgCategory );
        }
        if ( $imgDisplayName ) {
            $smarty->assign( 'imgDisplayName', $imgDisplayName );
        }
        if ( $imgDescription ) {
            $smarty->assign( 'imgDescription', $imgDescription );
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
            
            $addedImgIdArr = array();
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
                        $dest = 'locality/'.$imgName;
                        $source = $newImagePath.$dest;
                        $s3upload = new ImageUpload($source, array("s3" => $s3,
                            "image_path" => $dest, "object" => "locality","object_id" => $areaId,
                            "image_type" => "other"));
                        $s3upload->upload();
                        $thumb->resize( $__thumbWidth, $__thumbHeight );
                        $thumb->save($newImagePath.'locality/thumb_'.$imgName, $imgType);
                        $dest = 'locality/thumb_'.$imgName;
                        $source = $newImagePath.$dest;
                        $s3upload = new S3Upload($s3, $bucket, $source, $dest);
                        $s3upload->upload();
                        //  add image to DB
                          mysql_close();
                         proptigerDB();
                         $qry = "select * from proptiger.Image order by id desc limit 1";
                         $res = mysql_query($qry) or die(mysql_error());
                         $data = mysql_fetch_assoc($res);
                        $serviceImgId = $data['id'];
                        mysql_close();
                        include("dbConfig.php");
                        $addedImgIdArr[] = addImageToDB( $columnName, $areaId, $imgName,
                                $imgCategory, $imgDisplayName, $imgDescription,$serviceImgId );
                      
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
    else if($_REQUEST['updateDelete']) {   //code for image update or delete
        
        foreach($_REQUEST['img_id'] as $ImgID) {
            $imgCat = "imgCate_".$ImgID;
            $imgCategory = $_REQUEST[$imgCat];
            $imgNm = "imgName_".$ImgID;
            $imgName = $_REQUEST[$imgNm];
            $imgDes = "imgDesc_".$ImgID;
            $imgDesc = $_REQUEST[$imgDes][0];
            $updateDel = "updateDelete_".$ImgID;
            $updateDelete = $_REQUEST[$updateDel];
            $imgServiceId = "img_service_id_".$ImgID;
            $imgSevice = $_REQUEST[$imgServiceId][0];
            
            $qryCityLocSub = "select * from cms.locality_image where image_id = $ImgID";
            $resCityLocSub = mysql_query($qryCityLocSub) or die(mysql_error());
            $dataCityLocSub = mysql_fetch_assoc($resCityLocSub);
            $city     = !empty( $dataCityLocSub['CITY_ID'] ) ? $dataCityLocSub['CITY_ID'] : 0;
            $suburb   = !empty( $dataCityLocSub['SUBURB_ID'] ) ? $dataCityLocSub['SUBURB_ID'] : 0;
            $locality = !empty( $dataCityLocSub['LOCALITY_ID'] ) ? $_REQUEST['LOCALITY_ID'] : 0;
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
            $imgCategory = !empty( $_REQUEST[$imgCat][0] ) ? $_REQUEST[$imgCat][0] : '';
            $imgDisplayName = !empty( $_REQUEST[$imgNm][0] ) ? $_REQUEST[$imgNm][0] : '';
            $imgDescription = !empty( $imgDesc ) ? $imgDesc : '';
            
            $imgUpDel = "updateDelete_".$ImgID;
            if($_REQUEST[$imgUpDel][0] == 'up'){ //if wants to update image
                $errMsg = "";
                $imgId = 'img_'.$ImgID;
                $IMG = $_FILES[$imgId];                
                $uploadStatus = array();
                if ( $errMsg == "" ) {
                    //  add images to DB and to public_html folder
                    $imageCount = count( $IMG['name'][0] );

                    if($IMG['name'][0] != '') {
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
                                    $dest = 'locality/'.$imgName;
                                    $source = $newImagePath.$dest;
                                    $s3upload = new ImageUpload($source, array("s3" => $s3,
                                        "image_path" => $dest, "object" => "locality","object_id" => $areaId,
                                        "image_type" => "other"));
                                    $s3upload->upload();
                                    $thumb->resize( $__thumbWidth, $__thumbHeight );
                                    $thumb->save($newImagePath.'locality/thumb_'.$imgName, $imgType);
                                    $dest = 'locality/thumb_'.$imgName;
                                    $source = $newImagePath.$dest;
                                    $s3upload = new S3Upload($s3, $bucket, $source, $dest);
                                    $s3upload->upload();
                                    //  add image to DB
                                      mysql_close();
                                     proptigerDB();
                                     $qry = "select * from proptiger.Image order by id desc limit 1";
                                     $res = mysql_query($qry) or die(mysql_error());
                                     $data = mysql_fetch_assoc($res);
                                    $serviceImgId = $data['id'];
                                    mysql_close();
                                    include("dbConfig.php");
                                   $qryUpdate = "update locality_image set 
                                        IMAGE_CATEGORY = '".$imgCategory."',
                                        IMAGE_DESCRIPTION = '".$imgDescription."',
                                        IMAGE_DISPLAY_NAME = '".$imgDisplayName."',
                                        SERVICE_IMAGE_ID = $serviceImgId,
                                        IMAGE_NAME = '".$imgName."'
                                     WHERE IMAGE_ID = $ImgID";
                                    $resImg = mysql_query($qryUpdate) or die(mysql_error());
                                    //$addedImgIdArr[] = addImageToDB( $columnName, $areaId, $imgName,
                                        //    $imgCategory, $imgDisplayName, $imgDescription,$serviceImgId );
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
                    }else{
                        $qryUpdate = "update locality_image set 
                            IMAGE_CATEGORY = '".$imgCategory."',
                            IMAGE_DESCRIPTION = '".$imgDescription."',
                            IMAGE_DISPLAY_NAME = '".$imgDisplayName."'
                         WHERE IMAGE_ID = $ImgID";
                         $resImg = mysql_query($qryUpdate) or die(mysql_error());
                         $uploadStatus['img'][$ImgID] = "updated";
                    }
                }
                else {
                    $message = array(
                        'type' => 'error',
                        'content' => $errMsg
                    );
                }
                $smarty->assign( 'message', $message );
          }elseif($_REQUEST[$imgUpDel][0] == 'del') {    //if wants to delete image
               
              //  delete image from DB
                mysql_close();
               proptigerDB();
               $qryUp = "update proptiger.Image set active = 0 where id = $imgSevice";
               $resUp = mysql_query($qryUp) or die(mysql_error());
               if($resUp) {
                mysql_close();
                include("dbConfig.php");
                $qryUpdate = "delete from locality_image WHERE SERVICE_IMAGE_ID = $imgSevice";
                $resImg = mysql_query($qryUpdate) or die(mysql_error());
               }
          }
        }
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

function proptigerDB() {
    $db = mysql_connect('180.179.212.223', DB_PROJECT_USER, DB_PROJECT_PASS);
    $dblink = mysql_select_db('proptiger', $db);
}
?>
