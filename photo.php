<?php
    //error_reporting(E_ALL);
    ini_set('display_errors','1');
    include("smartyConfig.php");
    include("appWideConfig.php");
    include("dbConfig.php");
    include("includes/configs/configs.php");
    include("includes/db_query.php");
    include("common/function.php");
    include("imageService/image_upload.php");
    require_once "$_SERVER[DOCUMENT_ROOT]/includes/db_query.php";
    AdminAuthentication();

if ( isset( $_REQUEST['upImg'] ) && $_REQUEST['upImg'] == 1 ) {
        $city     = !empty( $_REQUEST['cityId'] ) ? $_REQUEST['cityId'] : 0;
        $suburb   = !empty( $_REQUEST['suburbId'] ) ? $_REQUEST['suburbId'] : 0;
        $locality = !empty( $_REQUEST['localityId'] ) ? $_REQUEST['localityId'] : 0;
        $landmark = !empty( $_REQUEST['landmarkId'] ) ? $_REQUEST['landmarkId'] : 0;
        $landmarkName = !empty( $_REQUEST['landmarkName'] ) ? $_REQUEST['landmarkName'] : 0;
        $imgCategory = !empty( $_REQUEST['imgCategory'] ) ? $_REQUEST['imgCategory'] : 'other';
        $imgDisplayName = !empty( $_REQUEST['imgDisplayName'] ) ? $_REQUEST['imgDisplayName'] : '';
        $imgDescription = !empty( $_REQUEST['imgDescription'] ) ? $_REQUEST['imgDescription'] : '';
        $displayPriority = !empty( $_REQUEST['displayPriority'] ) ? $_REQUEST['displayPriority'] : '999';

       //die($imgDisplayName);
        if ( $city ) {
            $smarty->assign( 'cityId', $city );
        }
        if ( $suburb ) {
            $smarty->assign( 'suburbId', $suburb );
        }
        if ( $locality ) {
            $smarty->assign( 'localityId', $locality );
        }
        if ( $landmark ) {
            $smarty->assign( 'landmarkId', $landmark );
            $smarty->assign( 'landmarkName', $landmarkName );
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
        if ( $city || $suburb || $locality || $landmark) {

            if ( $landmark > 0 ) {
                $columnName = "LANDMARK_ID";
                $areaType = 'landmark';
                $areaId = $landmark;
            }
            elseif ( $locality > 0 ) {
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
            $imageCount = count( $IMG['name'] );
            for( $__imgCnt = 0; $__imgCnt < $imageCount; $__imgCnt++ ) {
                if ( $IMG['error'][ $__imgCnt ] == 0 ) {
                    $img = array();
                    $img['error'] = $IMG['error'][ $__imgCnt ];
                    $img['type'] = $IMG['type'][ $__imgCnt ];
                    $img['name'] = $IMG['name'][ $__imgCnt ];
                    $img['tmp_name'] = $IMG['tmp_name'][ $__imgCnt ];
                    $extension = explode( "/", $img['type'] );
                    $extension = $extension[ count( $extension ) - 1 ];
                    $imgName = $areaType."_".$areaId."_".$__imgCnt."_".time().".".strtolower( $extension ); 

                    $dest       =   $newImagePath."locality/".$imgName;
                    $move       =   move_uploaded_file($IMG['tmp_name'][ $__imgCnt ],$dest);

                    $params = array(
                        "priority" => $displayPriority,
                        "description" => $imgDescription,
                        "image_type" => $imgCategory,
                        "title" => $imgDisplayName,
                        "column_name" => $columnName,
                        "folder" => "locality/",
                        "image" => $imgName,
                        "count" => $__imgCnt,
                        
                    );
                    //  add images to image service
            
                    
                    $returnArr = writeToImageService(  $img, $areaType, $areaId, $params, $newImagePath);
                      //die("here");
                    $serviceResponse = $returnArr['serviceResponse'];
                    if($returnArr['error']){
                        $uploadStatus[ $IMG['name'][ $__imgCnt ] ] = $returnArr['error'];
                    }
                    else{
                        // add to database
                        $addedImgIdArr[] = addImageToDB( $params['column_name'], $areaId, $imgName,
                            $params['image_type'], $params['title'], $params['description'],$returnArr['serviceResponse']['service']->response_body->data->id,$params['priority'] );
                  
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
            /*if ( count( $addedImgIdArr ) ) {
                $imgData = getPhotoById( $addedImgIdArr );
                if ( count( $imgData ) ) {
                    $smarty->assign( 'uploadedImage', $imgData );
                }
            }*/
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
        //hiecho "<pre>"; print_r($_REQUEST);die;
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
            $imgLandmarkId = $_REQUEST['landmark_id'];
            //echo "<pre>";
            //print_r($_REQUEST);die;
            $city     = !empty( $imgCityId ) ? $imgCityId : 0;
            $locality   = !empty( $imgLocalityId ) ? $imgLocalityId : 0;
            $suburb = !empty( $imgSuburbId ) ? $imgSuburbId : 0;
            $landmark = !empty( $imgLandmarkId ) ? $imgLandmarkId : 0;
            if($landmark > 0){
                $columnName = "LANDMARK_ID";
                $areaType = 'landmark';
                $areaId = $landmark;
            }
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
                $uploadStatus = array();
                if ( $errMsg == "" ) {
                    //  add images to DB and to public_html folder
                 $imageCount = 1;//die;

                    if($IMG['name'][0] != '') {
                       
                        $addedImgIdArr = array();
                        for( $__imgCnt = 0; $__imgCnt < $imageCount; $__imgCnt++ ) {
                            if ( $IMG['error'][ $__imgCnt ] == 0 ) {
                                /*$extension = explode( "/", $IMG['type'][ $__imgCnt ] );
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
                                        "image_path" => $dest, "object" => $areaType,"object_id" => $areaId,
                                        "image_type" => strtolower($imgCategory),
                                        "service_extra_params" => array("priority"=>$imagePriority,"title"=>$imgDisplayName,"description"=>$imgDescription)));
                                    $serviceResponse =  $s3upload->upload();
                                    /*$thumb->resize( $__thumbWidth, $__thumbHeight );
                                    $thumb->save($newImagePath.'locality/thumb_'.$imgName, $imgType);
                                    $dest = 'locality/thumb_'.$imgName;
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
                                    $uploadStatus[ $IMG['name'][0] ] = "uploaded";
                                 //   header("Location:photo.php");
                                }*/


                                $img = array();
                                $img['error'] = $IMG['error'][ $__imgCnt ];
                                $img['type'] = $IMG['type'][ $__imgCnt ];
                                $img['name'] = $IMG['name'][ $__imgCnt ];
                                $img['tmp_name'] = $IMG['tmp_name'][ $__imgCnt ];
                                

                                $params = array(
                                     "folder" => "locality/",
                                    "count" => $__imgCnt,
                                    "priority" => $imagePriority,
                                    "title" => $imgDisplayName,
                                    "description" => $imgDescription,
                                    "service_image_id" => $ImgID,
                                    "update" => "update",
                                    "image_type" => $imgCategory
                                    
                                );
                                //  add images to image service
                        
                                $imgName = $areaType."_".$areaId."_".$__imgCnt."_".time().".".strtolower( $extension ); 
                                $returnArr = writeToImageService(  $img, $areaType, $areaId, $params, $newImagePath);
                                  //die("here");
                                $serviceResponse = $returnArr['serviceResponse'];
                                if($returnArr['error']){
                                    $uploadStatus[ $IMG['name'][ $__imgCnt ] ] = $returnArr['error'];
                                }
                                else{
                                    //deleteFromImageService($areaType, $areaId, );
                                    // add to database
                                    $qryUpdate = "update locality_image set 
                                        IMAGE_CATEGORY = '".$imgCategory."',
                                        IMAGE_DESCRIPTION = '".$imgDescription."',
                                        IMAGE_DISPLAY_NAME = '".$imgDisplayName."',
                                        SERVICE_IMAGE_ID = ".$serviceResponse['service']->response_body->data->id.",
                                        IMAGE_NAME = '".$imgName."'    
                                     WHERE IMAGE_ID = $ImgID";
                                    $resImg = mysql_query($qryUpdate) or die(mysql_error());
                                    
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
                        /* $arrPost = array();
                         $arrPost['priority'] = $imagePriority;
                         $arrPost['title'] = $imgDisplayName;
                         $arrPost['description'] = $imgDescription;
                         $arrPost['image_type'] = strtolower($imgCategory);*/
                         
                         $params = array(
                            
                            
                            "priority" => $imagePriority,
                            "title" => $imgDisplayName,
                            "description" => $imgDescription,
                            "service_image_id" => $ImgID,
                            "update" => "update",
                            "image_type" => $imgCategory
                           
                        );

                         $returnArr = writeToImageService(  "", $areaType, $areaId, $params, $newImagePath);

                        /* $url = ImageServiceUpload::$image_upload_url."/".$imgSevice;
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
                        curl_close ($ch);*/

                        $qryUpdate = "update locality_image set 
                            IMAGE_CATEGORY = '".$imgCategory."',
                            IMAGE_DESCRIPTION = '".$imgDescription."',
                            IMAGE_DISPLAY_NAME = '".$imgDisplayName."'   
                         WHERE SERVICE_IMAGE_ID = $ImgID";
                         $resImg = mysql_query($qryUpdate) or die(mysql_error());
                         $uploadStatus[$imgName] = "updated";
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
                
                $response = deleteFromImageService($areaType, $areaId, $imgSevice);

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
    $localityType = ImageServiceUpload::$image_types;
    $smarty->assign( 'localityType', $localityType['locality'] );
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."upload-photo.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
?>
