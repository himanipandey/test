<?php

//error_reporting(E_ALL);
ini_set('display_errors', '1');
set_time_limit(0);
ini_set("memory_limit", "256M");
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("includes/db_query.php");
require_once("common/function.php");
include("imageService/image_upload.php");
include_once("SimpleImage.php");
require_once "$_SERVER[DOCUMENT_ROOT]/includes/db_query.php";
AdminAuthentication();


if (isset($_REQUEST['upImg']) && $_REQUEST['upImg'] == 1) {

    $city = !empty($_REQUEST['cityId']) ? $_REQUEST['cityId'] : 0;
    $suburb = !empty($_REQUEST['suburbId']) ? $_REQUEST['suburbId'] : 0;
    $locality = !empty($_REQUEST['localityId']) ? $_REQUEST['localityId'] : 0;
    $landmark = !empty($_REQUEST['landmarkId']) ? $_REQUEST['landmarkId'] : 0;
    $landmarkName = !empty($_REQUEST['landmarkName']) ? $_REQUEST['landmarkName'] : 0;
    $checkbx = $_REQUEST['cb'];
    if ($checkbx == "0")
        $imgCategory = $_REQUEST['lmkImgCategory'];
    else if ($checkbx == "1")
        $imgCategory = !empty($_REQUEST['imgCategory']) ? $_REQUEST['imgCategory'] : 'other';

    $imgDisplayName = !empty($_REQUEST['imgDisplayName']) ? $_REQUEST['imgDisplayName'] : '';
    $imgDescription = !empty($_REQUEST['imgDescription']) ? $_REQUEST['imgDescription'] : null;
    $displayPriority = !empty($_REQUEST['displayPriority']) ? $_REQUEST['displayPriority'] : '999';
    $imgDisplayName = trim($imgDisplayName);
    //echo strlen($imgDisplayName);echo "<br>";
    $imgDisplayName = preg_replace("/\s+/", " ", $imgDisplayName);
    //echo strlen($imgDisplayName); die();
    //die($imgDisplayName);
    if ($city) {
        $smarty->assign('cityId', $city);
    }
    if ($suburb) {
        $smarty->assign('suburbId', $suburb);
    }
    if ($locality) {
        $smarty->assign('localityId', $locality);
    }
    if ($landmark) {
        $smarty->assign('landmarkId', $landmark);
        $smarty->assign('landmarkName', $landmarkName);
    }

    if ($imgCategory) {
        $smarty->assign('imgCategory', $imgCategory);
    }
    if ($imgDisplayName) {
        $smarty->assign('imgDisplayName', $imgDisplayName);
    }
    if ($imgDescription) {
        $smarty->assign('imgDescription', $imgDescription);
    }
    if ($displayPriority) {
        $smarty->assign('displayPriority', $displayPriority);
    }

    $errMsg = "";
    $columnName = "";
    if ($city || $suburb || $locality || $landmark) {

        if ($landmark > 0) {
            $columnName = "LANDMARK_ID";
            $areaType = 'landmark';
            $areaId = $landmark;
        } elseif ($locality > 0) {
            $columnName = "LOCALITY_ID";
            $areaType = 'locality';
            $areaId = $locality;
        } elseif ($suburb > 0) {
            $columnName = "SUBURB_ID";
            $areaType = 'suburb';
            $areaId = $suburb;
        } else {
            $columnName = "CITY_ID";
            $areaType = 'city';
            $areaId = $city;
        }
    } else {
        $errMsg = "Please select the area type (Locality/Suburb/City)";
    }

    $IMG = $_FILES['img'];

    $uploadStatus = array();

    if ($errMsg == "") {
        $imageCount = count($IMG['name']);
        //echo $imageCount;
        for ($__imgCnt = 0; $__imgCnt < $imageCount; $__imgCnt++) {
            //echo "here0";
            if ($IMG['error'][$__imgCnt] == 0) {

                $extension = explode("/", $IMG['type'][$__imgCnt]);
                $extension = $extension[count($extension) - 1];
                $imgType = "";
                if (strtolower($extension) == "jpg" || strtolower($extension) == "jpeg") {
                    $imgType = IMAGETYPE_JPEG;
                } elseif (strtolower($extension) == "gif") {
                    $imgType = IMAGETYPE_GIF;
                } elseif (strtolower($extension) == "png") {
                    $imgType = IMAGETYPE_PNG;
                } else {
                    //  unknown format !!
                }
                if ($imgType == "") {
                    $uploadStatus[$IMG['name'][$__imgCnt]] = "format not supported";
                } else {

                    //  no error



                    $img = array();
                    $img['error'] = $IMG['error'][$__imgCnt];
                    $img['type'] = $IMG['type'][$__imgCnt];
                    $img['name'] = $IMG['name'][$__imgCnt];
                    $img['tmp_name'] = $IMG['tmp_name'][$__imgCnt];

                    $imgName = $areaType . "_" . $areaId . "_" . $__imgCnt . "_" . time() . "." . strtolower($extension);

//                    $dest = $newImagePath . "locality/" . $imgName;
//                    $move = move_uploaded_file($IMG['tmp_name'][$__imgCnt], $dest);
//                    $thumb = new SimpleImage();
//                    $__width = "592";
//                    $__height = "444";
//                    $__thumbWidth = "91";
//                    $__thumbHeight = "68";
//                    //$imgName = $areaType."_".$areaId."_".$__imgCnt."_".time().".".strtolower( $extension );
//                    $thumb->load($dest);
//                    //$thumb->resize( $__width, $__height );
//                    //$thumb->save($newImagePath.'locality/'.$imgName, $imgType);
//                    $thumb->resize($__thumbWidth, $__thumbHeight);
//                    $thumb->save($newImagePath . 'locality/thumb_' . $imgName, $imgType);
                    //die();

                    $postArr = array();
                    $unitImageArr = array();

                    $tmp = array();
                    $tmp['image'] = "@" . $img['tmp_name'];
                    $tmp['objectId'] = $areaId;
                    $tmp['objectType'] = $areaType;
                    $tmp['imageType'] = $imgCategory;
                    $tmp['priority'] = $displayPriority;
                    $tmp['description'] = $imgDescription;
                    $tmp['title'] = $imgDisplayName;
                    $tmp['altText'] = $imgDisplayName;
                    $tmp['column_name'] = $columnName;
                    $unitImageArr['upload_from_tmp'] = "yes";
                    $unitImageArr['url'] = IMAGE_SERVICE_URL;
                    $unitImageArr['method'] = "POST";
                    $unitImageArr['params'] = $tmp;
                    $postArr[] = $unitImageArr;

//                    print "<pre>" . print_r($_FILES, 1) . "</pre>";
//                    die;
//
                    $params = array(
                        "priority" => $displayPriority,
                        "description" => $imgDescription,
                        "image_type" => $imgCategory,
                        "title" => $imgDisplayName,
                        "column_name" => $columnName,
                        "folder" => "locality/",
                        "image" => $imgName,
                        "count" => $__imgCnt,
                        "altText" => $imgDisplayName,
                    );
//                    
//                    $unitImageArr['img'] = $img;
//                    $unitImageArr['objectId'] = $areaId;
//                    $unitImageArr['objectType'] = $areaType;
//                    $unitImageArr['newImagePath'] = $newImagePath;
//                    $unitImageArr['params'] = $params;
//                    $postArr[] = $unitImageArr;



                    $response = writeToImageService($postArr);

                    //print "<pre>".print_r($response,1)."</pre>"; die;
                    /**/
                    //print_r($response);  print_r($postArr); //die("here0");
                    foreach ($response as $k => $v) {
                        if (!empty($v->error->msg)) {
                            $uploadStatus[$IMG['name'][$__imgCnt]] = $v->error->msg;
                        } else {
                            //echo "here";     // add to database
                            $addedImgIdArr[] = addImageToDB($params['column_name'], $areaId, $imgName, $params['image_type'], $params['title'], $params['description'], $v->data->id, $params['priority']);

                            $uploadStatus[$IMG['name'][$__imgCnt]] = "uploaded";
                        }
                    }

                    //  add images to image service
                }
            } else {
                $uploadStatus[$IMG['name'][$__imgCnt]] = "Error#" . $IMG['error'][$__imgCnt];
            }
        }



        $str = "";
        foreach ($uploadStatus as $__imgName => $__statusMsg) {
            if ($str) {
                $str .= "; " . $__imgName . " : " . $__statusMsg;
            } else {
                $str = $__imgName . " : " . $__statusMsg;
            }
        }
        $message = array(
            'type' => 'success-msg',
            'content' => $str
        );
        /* if ( count( $addedImgIdArr ) ) {
          $imgData = getPhotoById( $addedImgIdArr );
          if ( count( $imgData ) ) {
          $smarty->assign( 'uploadedImage', $imgData );
          }
          } */
    } else {
        $message = array(
            'type' => 'error',
            'content' => $errMsg
        );
    }
    $smarty->assign('message', $message);
} else if ($_REQUEST['updateDelete']) {   //code for image update or delete
    $thumb = new SimpleImage();
    $postArr = array();

    //echo "<pre>"; print_r($_REQUEST);die;
    foreach ($_REQUEST['img_id'] as $k => $ImgID) {
        $imgCat = "imgCate_" . $ImgID;
        $imgCategory = $_REQUEST[$imgCat][0];
        $imgNm = "imgName_" . $ImgID;
        $imgName = $_REQUEST[$imgNm][0];
        $imgDes = "imgDesc_" . $ImgID;
        $imgDesc = $_REQUEST[$imgDes][0];
        $imgPrior = "priority_" . $ImgID;
        $imgPriority = $_REQUEST[$imgPrior][0];
        $updateDel = "updateDelete_" . $ImgID;
        $updateDelete = $_REQUEST[$updateDel][0];
        $imgServiceId = "img_service_id_" . $ImgID;
        $imgSevice = $ImgID;

        $imgCityId = $_REQUEST['city_id'];
        $imgLocalityId = $_REQUEST['locality_id'];
        $imgSuburbId = $_REQUEST['suburb_id'];
        $imgLandmarkId = $_REQUEST['landmark_id'];
        //echo "<pre>";
        //print_r($_REQUEST);die;
        $city = !empty($imgCityId) ? $imgCityId : 0;
        $locality = !empty($imgLocalityId) ? $imgLocalityId : 0;
        $suburb = !empty($imgSuburbId) ? $imgSuburbId : 0;
        $landmark = !empty($imgLandmarkId) ? $imgLandmarkId : 0;
        if ($landmark > 0) {
            $columnName = "LANDMARK_ID";
            $areaType = 'landmark';
            $areaId = $landmark;
        }
        if ($city || $suburb || $locality) {
            if ($locality > 0) {
                $columnName = "LOCALITY_ID";
                $areaType = 'locality';
                $areaId = $locality;
            } elseif ($suburb > 0) {
                $columnName = "SUBURB_ID";
                $areaType = 'suburb';
                $areaId = $suburb;
            } else {
                $columnName = "CITY_ID";
                $areaType = 'city';
                $areaId = $city;
            }
        }
        $imgCategory = !empty($_REQUEST[$imgCat][0]) ? $_REQUEST[$imgCat][0] : '';
        $imgDisplayName = !empty($_REQUEST[$imgNm][0]) ? $_REQUEST[$imgNm][0] : '';
        $imgDescription = !empty($imgDesc) ? $imgDesc : null;
        $imagePriority = !empty($imgPriority) ? $imgPriority : '';

        $imgUpDel = "updateDelete_" . $ImgID;
        if ($_REQUEST[$imgUpDel][0] == 'up') { //if wants to update image
            $errMsg = "";
            $imgId = 'img_' . $ImgID;
            $IMG = $_FILES[$imgId];
            $uploadStatus = array();
            if ($errMsg == "") {
                //  add images to DB and to public_html folder
                $imageCount = 1; //die;

                if ($IMG['name'][0] != '') {

                    $addedImgIdArr = array();
                    for ($__imgCnt = 0; $__imgCnt < $imageCount; $__imgCnt++) {
                        if ($IMG['error'][$__imgCnt] == 0) {
                            $extension = explode("/", $IMG['type'][$__imgCnt]);
                            $extension = $extension[count($extension) - 1];
                            $imgType = "";
                            if (strtolower($extension) == "jpg" || strtolower($extension) == "jpeg") {
                                $imgType = IMAGETYPE_JPEG;
                            } elseif (strtolower($extension) == "gif") {
                                $imgType = IMAGETYPE_GIF;
                            } elseif (strtolower($extension) == "png") {
                                $imgType = IMAGETYPE_PNG;
                            } else {
                                //  unknown format !!
                            }
                            if ($imgType == "") {
                                $uploadStatus[$IMG['name'][$__imgCnt]] = "format not supported";
                            } else {

                                //  no error
//                                $__width = "592";
//                                $__height = "444";
//                                $__thumbWidth = "91";
//                                $__thumbHeight = "68";
//                                $imgName = $areaType . "_" . $areaId . "_" . $__imgCnt . "_" . time() . "." . strtolower($extension);
//
//                                $dest = $newImagePath . "locality/" . $imgName;
//                                $move = move_uploaded_file($IMG['tmp_name'][$__imgCnt], $dest);
//                                $thumb = new SimpleImage();
//                                $__width = "592";
//                                $__height = "444";
//                                $__thumbWidth = "91";
//                                $__thumbHeight = "68";
//                                //$imgName = $areaType."_".$areaId."_".$__imgCnt."_".time().".".strtolower( $extension );
//                                $thumb->load($dest);
                                //$thumb->save($newImagePath.'locality/'.$imgName, $imgType);
                                /* $dest = 'locality/'.$imgName;
                                  $source = $newImagePath.$dest;
                                  $s3upload = new ImageUpload($source, array("s3" => $s3,
                                  "image_path" => $dest, "object" => $areaType,"object_id" => $areaId,
                                  "image_type" => strtolower($imgCategory),
                                  "service_extra_params" => array("priority"=>$imagePriority,"title"=>$imgDisplayName,"description"=>$imgDescription)));
                                  $serviceResponse =  $s3upload->upload(); */
//                                $thumb->resize($__thumbWidth, $__thumbHeight);
//                                $thumb->save($newImagePath . 'locality/thumb_' . $imgName, $imgType);
                                /* $dest = 'locality/thumb_'.$imgName;
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
                                  } */


                                $img = array();
                                $img['error'] = $IMG['error'][$__imgCnt];
                                $img['type'] = $IMG['type'][$__imgCnt];
                                $img['name'] = $IMG['name'][$__imgCnt];
                                $img['tmp_name'] = $IMG['tmp_name'][$__imgCnt];

                                $postArr = array();
                                $unitImageArr = array();

                                $tmp = array();
                                $tmp['image'] = "@" . $img['tmp_name'];
                                $tmp['objectId'] = $areaId;
                                $tmp['objectType'] = $areaType;
                                $tmp['imageType'] = $imgCategory;
                                $tmp['priority'] = $imagePriority;
                                $tmp['description'] = $imgDescription;
                                $tmp['title'] = $imgDisplayName;
                                $tmp['altText'] = $imgDisplayName;
                                $tmp['column_name'] = $columnName;
                                $tmp['service_image_id'] = $ImgID;
                                $tmp['update'] = "yes";
                                $unitImageArr['url'] = IMAGE_SERVICE_URL . "/" . $ImgID;
                                $unitImageArr['upload_from_tmp'] = "yes";
                                $unitImageArr['url'] = IMAGE_SERVICE_URL;
                                $unitImageArr['method'] = "POST";
                                $unitImageArr['params'] = $tmp;
                                $postArr[$k] = $unitImageArr;

//                                $params = array(
//                                    "folder" => "locality/",
//                                    "image" => $imgName,
//                                    "count" => $__imgCnt,
//                                    "priority" => $imagePriority,
//                                    "title" => $imgDisplayName,
//                                    "description" => $imgDescription,
//                                    "service_image_id" => $ImgID,
//                                    "update" => "update",
//                                    "image_type" => $imgCategory,
//                                    "altText" => $imgDisplayName,
//                                );
//
//                                $unitImageArr = array();
//                                $unitImageArr['img'] = $img;
//                                $unitImageArr['objectId'] = $areaId;
//                                $unitImageArr['objectType'] = $areaType;
//                                $unitImageArr['newImagePath'] = $newImagePath;
//                                $unitImageArr['params'] = $params;
//                                $postArr[$k] = $unitImageArr;
                                //$response   = writeToImageService( $postArr);
                                //  add images to image service
                                //$imgName = $areaType."_".$areaId."_".$__imgCnt."_".time().".".strtolower( $extension ); 
                                //$returnArr = writeToImageService(  $img, $areaType, $areaId, $params, $newImagePath);
                                //die("here");
                                /* $serviceResponse = $returnArr['serviceResponse'];
                                  if(!empty($serviceResponse["service"]->response_body->error->msg)){
                                  $uploadStatus[ $IMG['name'][ $__imgCnt ] ] = $serviceResponse["service"]->response_body->error->msg;
                                  }
                                  else{
                                  $image_id = $serviceResponse["service"]->response_body->data->id;
                                  //deleteFromImageService($areaType, $areaId, );
                                  // add to database
                                  $qryUpdate = "update locality_image set
                                  IMAGE_CATEGORY = '".$imgCategory."',
                                  IMAGE_DESCRIPTION = '".$imgDescription."',
                                  IMAGE_DISPLAY_NAME = '".$imgDisplayName."',
                                  SERVICE_IMAGE_ID = '".$image_id."',
                                  IMAGE_NAME = '".$imgName."'
                                  WHERE SERVICE_IMAGE_ID = $ImgID";
                                  $resImg = mysql_query($qryUpdate) or die(mysql_error());
                                  //echo $qryUpdate;die();
                                  $uploadStatus[ $IMG['name'][ $__imgCnt ] ] = "uploaded";
                                  } */
                            }
                        } else {
                            $uploadStatus[$IMG['name'][$__imgCnt]] = "Error#" . $IMG['error'][$__imgCnt];
                        }
                    }
                } else {
                    /* $arrPost = array();
                      $arrPost['priority'] = $imagePriority;
                      $arrPost['title'] = $imgDisplayName;
                      $arrPost['description'] = $imgDescription;
                      $arrPost['image_type'] = strtolower($imgCategory); */

                    $postArr = array();
                    $unitImageArr = array();

                    $tmp = array();
                    $tmp['image'] = '';
                    $tmp['objectId'] = $areaId;
                    $tmp['objectType'] = $areaType;
                    $tmp['imageType'] = $imgCategory;
                    $tmp['priority'] = $imagePriority;
                    $tmp['description'] = $imgDescription;
                    $tmp['title'] = $imgDisplayName;
                    $tmp['altText'] = $imgDisplayName;
                    $tmp['column_name'] = $columnName;
                    $tmp['service_image_id'] = $ImgID;
                    $tmp['update'] = "yes";
                    $unitImageArr['url'] = IMAGE_SERVICE_URL . "/" . $ImgID;
                    $unitImageArr['upload_from_tmp'] = "yes";
                    $unitImageArr['url'] = IMAGE_SERVICE_URL;
                    $unitImageArr['method'] = "POST";
                    $unitImageArr['params'] = $tmp;
                    $postArr[$k] = $unitImageArr;
                    
                    
                }
            } else {
                $message = array(
                    'type' => 'error',
                    'content' => $errMsg
                );
            }
            $smarty->assign('message', $message);
        } elseif ($_REQUEST[$imgUpDel][0] == 'del') {    //if wants to delete image
            $params = array(
                "service_image_id" => $imgSevice,
                "delete" => "yes",
            );
            $unitImageArr = array();

            $unitImageArr['objectId'] = $areaId;
            $unitImageArr['objectType'] = $areaType;

            $unitImageArr['params'] = $params;
            $postArr[$k] = $unitImageArr;
            //$response   = writeToImageService( $postArr);
            //$response = deleteFromImageService($areaType, $areaId, $imgSevice);
            //$qryUpdate = "delete from locality_image WHERE SERVICE_IMAGE_ID = $imgSevice";
            //$resImg = mysql_query($qryUpdate) or die(mysql_error());
        }
    }


    $serviceResponse = writeToImageService($postArr);
    //print'<pre>';   print_r($postArr);//die();    
    foreach ($serviceResponse as $k => $v) {
        $image_id = $v->data->id;
        $imgCategory = $postArr[$k]['params']['image_type'];
        $imgDescription = $postArr[$k]['params']['description'];
        $imgDisplayName = $postArr[$k]['params']['title'];

        $ImgID = $postArr[$k]['params']['service_image_id'];
        if (empty($v->error->msg)) {

            if ($postArr[$k]['params']['delete'] == "yes") {
                $qryUpdate = "delete from locality_image WHERE SERVICE_IMAGE_ID = $ImgID";
                $resImg = mysql_query($qryUpdate) or die(mysql_error());
                $uploadStatus[$imgDisplayName] = "deleted";
            } else if (empty($postArr[$k]['img'])) {
                $qryUpdate = "update locality_image set 
                            IMAGE_CATEGORY = '" . $imgCategory . "',
                            IMAGE_DESCRIPTION = '" . $imgDescription . "',
                            IMAGE_DISPLAY_NAME = '" . $imgDisplayName . "'   
                         WHERE SERVICE_IMAGE_ID = $ImgID";
                $resImg = mysql_query($qryUpdate) or die(mysql_error());
                $uploadStatus[$imgDisplayName] = "updated";
            } else if ($image_id > 0) {
                $imgName = $postArr[$k]['params']['image'];
                $qryUpdate = "update locality_image set 
                    IMAGE_CATEGORY = '" . $imgCategory . "',
                    IMAGE_DESCRIPTION = '" . $imgDescription . "',
                    IMAGE_DISPLAY_NAME = '" . $imgDisplayName . "',
                    SERVICE_IMAGE_ID = '" . $image_id . "',
                    IMAGE_NAME = '" . $imgName . "'    
                 WHERE SERVICE_IMAGE_ID = $ImgID";
                $resImg = mysql_query($qryUpdate) or die(mysql_error());
                //echo $qryUpdate;die();
                $uploadStatus[$imgName] = "uploaded";
            }
        } else {
            $uploadStatus[$imgDisplayName] = $v->error->msg;
        }
    }

    $str = "";
    foreach ($uploadStatus as $__imgName => $__statusMsg) {
        if ($str) {
            $str .= "; " . $__imgName . " : " . $__statusMsg;
        } else {
            $str = $__imgName . " : " . $__statusMsg;
        }
    }
    $message = array(
        'type' => 'success-msg',
        'content' => $str
    );
    $smarty->assign('message', $message);
}



$response = getListing();   //  get City List

$cityList = !empty($response['city']) ? $response['city'] : "";

if (is_array($cityList) && count($cityList)) {
    $smarty->assign('cityList', $cityList);
}
$smarty->assign('photoCSS', 1);
$localityType = ImageServiceUpload::$image_types;
$smarty->assign('localityType', $localityType['locality']);
$landmarkType = ImageServiceUpload::$image_types;
$smarty->assign('landmarkType', $landmarkType['landmark']);

$smarty->display(PROJECT_ADD_TEMPLATE_PATH . "header.tpl");
$smarty->display(PROJECT_ADD_TEMPLATE_PATH . "upload-photo.tpl");
$smarty->display(PROJECT_ADD_TEMPLATE_PATH . "footer.tpl");
?>
