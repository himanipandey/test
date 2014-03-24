<?php
    //error_reporting(E_ALL);
    ini_set('display_errors','1');
    include("smartyConfig.php");
    include("appWideConfig.php");
    include("dbConfig.php");
    include("includes/configs/configs.php");
    include("includes/db_query.php");
    include("common/function.php");

    include("s3upload/s3_config.php");
    require_once "$_SERVER[DOCUMENT_ROOT]/includes/db_query.php";
    AdminAuthentication();
//echo "<pre>";
//print_r($_REQUEST);

if ( isset( $_REQUEST['upImg'] ) && $_REQUEST['upImg'] == 1 ) {
        //echo "<pre>"; print_r( $_REQUEST ); print_r( $_FILES ); die();
        $city     = !empty( $_REQUEST['cityId'] ) ? $_REQUEST['cityId'] : 0;
        $suburb   = !empty( $_REQUEST['suburbId'] ) ? $_REQUEST['suburbId'] : 0;
        $locality = !empty( $_REQUEST['localityId'] ) ? $_REQUEST['localityId'] : 0;
        $imgCategory = !empty( $_REQUEST['imgCategory'] ) ? $_REQUEST['imgCategory'] : 'other';
        $imgDisplayName = !empty( $_REQUEST['imgDisplayName'] ) ? $_REQUEST['imgDisplayName'] : '';
        $imgDescription = !empty( $_REQUEST['imgDescription'] ) ? $_REQUEST['imgDescription'] : '';
        $displayPriority = !empty( $_REQUEST['displayPriority'] ) ? $_REQUEST['displayPriority'] : '999';

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
        if ( $displayPriority ) {
            $smarty->assign( 'displayPriority', $displayPriority );
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
                            "image_type" => strtolower($imgCategory),
                            "service_extra_params" => 
                                array("priority"=>$displayPriority,"title"=>$imgDisplayName,"description"=>$imgDescription)));
                       $serviceResponse =  $s3upload->upload();
                       
                        $thumb->resize( $__thumbWidth, $__thumbHeight );
                        $thumb->save($newImagePath.'locality/thumb_'.$imgName, $imgType);
                        $dest = 'locality/thumb_'.$imgName;
                        $source = $newImagePath.$dest;
                        $s3upload = new S3Upload($s3, $bucket, $source, $dest);
                        $s3upload->upload();
                        
                        //  add image to DB
                        $addedImgIdArr[] = addImageToDB( $columnName, $areaId, $imgName,
                                $imgCategory, $imgDisplayName, $imgDescription,$serviceResponse['service']->response_body->data->id,$displayPriority );
                      
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
         include("SimpleImage.php");
         $thumb = new SimpleImage();
        //echo "<pre>"; print_r($_REQUEST);die;
        foreach($_REQUEST['img_id'] as $ImgID) {
            $imgCat = "imgCate_".$ImgID;
            $imgCategory = $_REQUEST[$imgCat][0];
            $imgNm = "imgName_".$ImgID;
            $imgName = $_REQUEST[$imgNm][0];
            $imgDes = "imgDesc_".$ImgID;
            $imgDesc = $_REQUEST[$imgDes][0];
            $imgPrior = "priority_".$ImgID;
            $imgPriority = $_REQUEST[$imgPrior][0];
            $updateDel = "updateDelete_".$ImgID;
            $updateDelete = $_REQUEST[$updateDel][0];
            $imgServiceId = "img_service_id_".$ImgID;
            $imgSevice = $ImgID;
            
            $imgCityId = $_REQUEST['city_id'];
            $imgLocalityId = $_REQUEST['locality_id'];
            $imgSuburbId = $_REQUEST['suburb_id'];
            
            $city     = !empty( $imgCityId ) ? $imgCityId : 0;
            $locality   = !empty( $imgLocalityId ) ? $imgLocalityId : 0;
            $suburb = !empty( $imgSuburbId ) ? $imgSuburbId : 0;
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
            $imagePriority = !empty( $imgPriority ) ? $imgPriority : '';
            
            $imgUpDel = "updateDelete_".$ImgID;
            if($_REQUEST[$imgUpDel][0] == 'up'){ //if wants to update image
                $errMsg = "";
                $imgId = 'img_'.$ImgID;
                $IMG = $_FILES[$imgId]; 
              //  echo count($_FILES)."----";
                $uploadStatus = array();
                if ( $errMsg == "" ) {
                    //  add images to DB and to public_html folder
                 $imageCount = 1;//die;

                    if($IMG['name'][0] != '') {
                       
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
                                //echo $imgType."---->Here<br>";
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
                                    
                                    $thumb->save($newImagePath.$areaType.'/'.$imgName, $imgType);
                                    $dest = $areaType.'/'.$imgName;
                                    $source = $newImagePath.$dest;
                                    //echo $imagePriority."==>".$imgDisplayName."==>".$imgDescription;die;
                                    $s3upload = new ImageUpload($source, array("s3" => $s3,
                                        "image_path" => $dest, "object" => $areaType,"object_id" => $areaId,
                                        "image_type" => strtolower($imgCategory),
                                        "service_extra_params" => array("priority"=>$imagePriority,"title"=>$imgDisplayName,"description"=>$imgDescription)));
                                    $serviceResponse =  $s3upload->upload();
                                    $thumb->resize( $__thumbWidth, $__thumbHeight );
                                    $thumb->save($newImagePath.'locality/thumb_'.$imgName, $imgType);
                                    $dest = $areaType.'/thumb_'.$imgName;
                                    $source = $newImagePath.$dest;
                                    $s3upload = new S3Upload($s3, $bucket, $source, $dest);
                                    $s3upload->upload();
                                    //  add image to DB
                                    $qryUpdate = "update locality_image set 
                                        IMAGE_CATEGORY = '".$imgCategory."',
                                        IMAGE_DESCRIPTION = '".$imgDescription."',
                                        IMAGE_DISPLAY_NAME = '".$imgDisplayName."',
                                        SERVICE_IMAGE_ID = ".$serviceResponse['service']->response_body->data->id.",
                                        IMAGE_NAME = '".$imgName."'    
                                     WHERE IMAGE_ID = $ImgID";
                                    $resImg = mysql_query($qryUpdate) or die(mysql_error());
                                    $s3upload = new ImageUpload(NULL, array("service_image_id" => $imgSevice));
                                    $response = $s3upload->delete();
                                    //$addedImgIdArr[] = addImageToDB( $columnName, $areaId, $imgName,
                                        //    $imgCategory, $imgDisplayName, $imgDescription,$serviceImgId );
                                    $uploadStatus[ $IMG['name'][0] ] = "uploaded";
                                 //   header("Location:photo.php");
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
                         $arrPost = array();
                         $arrPost['priority'] = $imagePriority;
                         $arrPost['title'] = $imgDisplayName;
                         $arrPost['description'] = $imgDescription;
                         $arrPost['image_type'] = strtolower($imgCategory);
                         $url = ImageServiceUpload::$image_upload_url."/".$imgSevice;
                         $ch = curl_init();
                         $method = 'POST';
                        curl_setopt($ch, CURLOPT_URL,$url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_VERBOSE, 1);
                        curl_setopt($ch, CURLOPT_HEADER, 1);
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,$method);
                        if($method == "POST" || $method == "PUT")
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $arrPost);
                        $response= curl_exec($ch);
                        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                        $response_header = substr($response, 0, $header_size);
                        $response_body = json_decode(substr($response, $header_size));
                        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        curl_close ($ch);
                        $qryUpdate = "update locality_image set 
                            IMAGE_CATEGORY = '".$imgCategory."',
                            IMAGE_DESCRIPTION = '".$imgDescription."',
                            IMAGE_DISPLAY_NAME = '".$imgDisplayName."'   
                         WHERE SERVICE_IMAGE_ID = $ImgID";
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
                $s3upload = new ImageUpload(NULL, array("service_image_id" => $imgSevice));
                $response = $s3upload->delete();

                 $qryUpdate = "delete from locality_image WHERE SERVICE_IMAGE_ID = $imgSevice";
                 $resImg = mysql_query($qryUpdate) or die(mysql_error());
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
?>
