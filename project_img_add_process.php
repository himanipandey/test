<?php

set_time_limit(0);
ini_set("memory_limit", "256M");

include("ftp.new.php");
$ErrorMsg = '';

//$projectplansid = $_REQUEST['projectplansid'];
$watermark_path = "images/pt_shadow1.png";
$projectId = $_REQUEST['projectId'];
$projectDetail = ProjectDetail($projectId);
$smarty->assign("ProjectDetail", $projectDetail);

$linkShowHide = 0;
if (isset($_REQUEST['auth'])) {
    $linkShowHide = 1;
}
$smarty->assign("linkShowHide", $linkShowHide);

$smarty->assign("imagetype", $_REQUEST['imagetype']);

$sec_image_types = ImageServiceUpload::$sec_image_types;
$sec_image_types = $sec_image_types['project']['project_image'];
$smarty->assign("sec_image_types", $sec_image_types);
//print_r($sec_image_types);


$Amenities = AmenitiesMaster::arrAmenitiesMaster();
$smarty->assign("amenities", $Amenities);


//tower dropdown
$towerDetail_object = ResiProjectTowerDetails::find("all", array("conditions" => "project_id = {$projectId}"));
$towerDetail = array();
$tower_div = "<select name= 'txtTowerId[]' id='tower_dropdown' onchange='tower_change(this)'>";
$tower_div .="<option value='Select'>--Select Tower--</option>";

foreach ($towerDetail_object as $s) {
    $s = $s->to_array();
    foreach ($s as $key => $value) {
        $s[strtoupper($key)] = $value;
        unset($s[$key]);
    }
    $tower_div .="<option value='" . $s['TOWER_ID'] . "' >" . $s['TOWER_NAME'] . "</option>";
}
if (count($towerDetail_object) < 1)
    $tower_div .= "<option value='0'>Other</option>";
$tower_div .= "</select>";
$smarty->assign("towerDetailDiv", $tower_div);

//display order
$display_order_div = "<select name='txtdisplay_order[]' id='display_order_dropdown'>";
for ($cmt = 1; $cmt <= 5; $cmt++) {
    if ($cmt == 5)
        $display_order_div .="<option value='$cmt' selected >$cmt</option>";
    else
        $display_order_div .="<option value='$cmt'>$cmt</option>";
}
$display_order_div .= "</select>";
$smarty->assign("display_order_div", $display_order_div);

$builderDetail = fetch_builderDetail($projectDetail[0]['BUILDER_ID']);
if (isset($_REQUEST['edit']))
    $edit_project = $projectId;
else
    $edit_project = $projectId;
$smarty->assign("edit_project", $edit_project);
//echo $projectplansid = $edit_project;

$watermark_path = 'images/pt_shadow1.png';
$source = array();
$dest = array();
//$smarty->assign("projectplansid", $projectplansid);
if (isset($_POST['Next'])) {
    $smarty->assign("projectId", $projectId);
    $folderName = $projectDetail[0]['PROJECT_NAME'];

    /*     * *********Folder name********* */
    $builderNamebuild = explode("/", $builderDetail['BUILDER_IMAGE']);

    /*     * ***************************** */
    $BuilderName = $builderNamebuild[1];
    $ProjectName = str_replace(" ", "-", $projectDetail[0]['PROJECT_NAME']);

    $arrValue = array();
    $arrTitle = array();
    $arrTaggedDate = array();
    $arrTowerId = array();
    $arrDisplayOrder = array();
    //print("<pre>");
    //print_r($_FILES['txtlocationplan']);
    //echo "start:".microtime(true)."<br>";
    foreach ($_FILES['txtlocationplan']['name'] as $k => $v) {
        if ($v != '') {
            if (!in_array(strtolower($_FILES['txtlocationplan']['type'][$k]), $arrImg)) {
                $ErrorMsg["ImgError"] = "You can upload only " . ucwords(implode(" / ", $arrImg)) . " images.";
            }

//            foreach ($arrType as $planType => $imgNamePart) {
//                if ($_REQUEST['PType'] == $planType) {
//                    if (!preg_match("/-" . $imgNamePart . "\.[a-z]{3,4}$/", $v)) {
//                        $ErrorMsg["ImgError"] = "The word " . $imgNamePart . " should be part of image name at end.";
//                    }
//                }
//            }

            $arrValue[$k] = $v;
            $arrTitle[$k] = $_REQUEST['title'][$k];

            if (isset($_REQUEST['img_date' . ($k + 1)]) && !null == $_REQUEST['img_date' . ($k + 1)]) {

                $tagged_date = substr($_REQUEST['img_date' . ($k + 1)], 0, 7);
                $arrTaggedDate[$k] = $tagged_date . "-01T00:00:00Z";

                //$arrTaggedDate[$k] = null;
            } else
                $arrTaggedDate[$k] = null;

            //echo $arrTaggedDate[$k]
            if ($_REQUEST['txtTowerId'][$k + 1] == "Select")
                $arrTowerId[$k] = null;
            else
                $arrTowerId[$k] = $_REQUEST['txtTowerId'][$k + 1];
            //echo $arrTowerId[$k]; die();
            $arrDisplayOrder[$k] = $_REQUEST['txtdisplay_order'][$k + 1];
            //die($arrTaggedDate[$k].$arrTowerId[$k]);
        }
    }

    if (count($arrValue) == 0) {
        $ErrorMsg["blankerror"] = "Please select atleast one image.";
    } else if ($projectId == '') {
        $ErrorMsg["projectId"] = "Please select Project name.";
    } else if ($_REQUEST['PType'] == '') {
        $ErrorMsg["ptype"] = "Please select project type.";
    } else if (!array_filter($_REQUEST['title'])) {
        $ErrorMsg["ptype"] = "Please enter Image Title.";
    }

    //validations for tagged months
    if ($_REQUEST['PType'] == 'Construction Status') {
        $count = 1;
        while ($count <= $_REQUEST['img']) {
            if ($_REQUEST['img_date' . $count] == '')
                $ErrorMsg["ptype"] = "Please enter Tagged Date.";
            $count++;
        }
    }
    //print_r($_REQUEST['txtTowerId']); die();
    if ($_REQUEST['PType'] == 'Cluster Plan') {
        $count = 1;
        while ($count <= $_REQUEST['img']) {

            if ($_REQUEST['txtTowerId'][$count] == "Select" || $_REQUEST['txtTowerId'][$count] < 0) {
                $ErrorMsg["ptype"] = "Please select a Tower for every Cluster Plan.";
            }
            $count++;
        }
    }

    //checking uniqness display order of elevation images
    if ($_REQUEST['PType'] == 'Elevation' || $_REQUEST['PType'] == 'Amenities' || $_REQUEST['PType'] == 'Main Other') {
        $count = 1;
        $temp_arr = array();

        while ($count <= $_REQUEST['img']) {

            if (trim($_REQUEST['txtdisplay_order'][$count]) == '') {
                $ErrorMsg["ptype"] = "Please enter Display Order.";
                break;
            } else {

                if (array_key_exists($_REQUEST['txtdisplay_order'][$count], $temp_arr)) {
                    $ErrorMsg["ptype"] = "Display order must be unique.";
                    break;
                } else {//checking duplicacy
                    $ext_vlinks = checkDuplicateDisplayOrder($projectId, $_REQUEST['txtdisplay_order'][$count], $_REQUEST['PType']);
                    if ($ext_vlinks) {
                        $ErrorMsg["ptype"] = "Display order '" . $_REQUEST['txtdisplay_order'][$count] . "' already exist.";
                        break;
                    }
                }
                if ($_REQUEST['txtdisplay_order'][$count] != 5)
                    $temp_arr[$_REQUEST['txtdisplay_order'][$count]] = $_REQUEST['txtdisplay_order'][$count];
            }

            if ($_REQUEST['PType'] == 'Amenities') {
                if ($_REQUEST['SType'][$count] == '') {
                    $ErrorMsg["stype"] = "Please enter an Amenities Type.";
                }
            }

            $count++;
        }
    }
    $smarty->assign("PType", $_REQUEST['PType']);
    if (is_array($ErrorMsg)) {
        // Do Nothing
    } else {

        $flag = 0;
        $projectFolderCreated = 0;
        /*         * *****************Update location,site,layout and master plan from db and also from table******** */
        $foldlowe = strtolower($BuilderName);
        $newdirlow = $newImagePath . $foldlowe;
        if ((!is_dir($newdirlow))) {
            $lowerdir = strtolower($BuilderName);
            $newdir = $newImagePath . "" . $lowerdir;

            mkdir($newdir, 0777);
            $flag = 1;
        }
        //echo "dir:".$lowerdir.":new:".$newdir;
        /*         * **************project folder check********* */
        $newdirpro = $newImagePath . $BuilderName . "/" . $ProjectName;
        $foldname = strtolower($ProjectName);
        $andnewdirpro = $newImagePath . $BuilderName . "/" . $foldname;
        //print_r($arrValue);
        //echo "loop-start:".microtime(true)."<br>";


        $postArr = array(); // array to store image data to send with http request
        $fileEndName = array();

        print "<pre>arr:".print_r($arrValue,1)."</pre>";

        foreach ($arrValue as $key => $val) {
            $unitImageArr = array();


            //echo "iter-start:".microtime(true)."<br>";
            if ((!is_dir($newdirpro)) && (!is_dir($andnewdirpro))) {
                $lowerpro = strtolower($ProjectName);
                $ndirpro = $newImagePath . $BuilderName . "/" . $lowerpro;
                mkdir($ndirpro, 0777);
                $projectFolderCreated = 1;
                $createFolder = $ndirpro; //die("here");

                $img_path = $ndirpro . "/" . $val; //die("here");
            } else {
                $img_path = $newImagePath . $BuilderName . "/" . strtolower($ProjectName) . "/" . $val;
                $createFolder = $newImagePath . $BuilderName . "/" . strtolower($ProjectName);
            }


            $extra_path = $BuilderName . "/" . strtolower($ProjectName) . "/";
            $tmpDir = $newImagePath . "tmp/";
            if (!is_dir($tmpDir)) {
                mkdir($tmpDir, 0777);
            }
            $tmp_path = $tmpDir . $val;
            //echo $sorce;
            $txtlocationplan = move_uploaded_file($_FILES["txtlocationplan"]["tmp_name"][$key], $img_path);
            //$txtlocationplan 	= move_uploaded_file($_FILES["txtlocationplan"]["tmp_name"][$key], $tmp_path);

            /* $files = glob($tmpDir.'*'); // get all file names
              foreach($files as $file){ // iterate files

              if(strstr($file,$val))
              {
              rename($file, $img_path);
              unlink($file);
              }
              }
             */
            if (!$txtlocationplan) {
                $ErrorMsg["ImgError"] .= "Problem in Image Upload Please Try Again.";
                break;
            } else {

                $source[] = opendir . $BuilderName . "/" . strtolower($ProjectName) . "/" . $val;
                $dest[] = "public_html/images_new/" . $BuilderName . "/" . strtolower($ProjectName) . "/" . $val;

                $projecttbl = "/" . $BuilderName . "/" . strtolower($ProjectName);
                $img = array();
                $img['error'] = $_FILES["txtlocationplan"]["error"][$key];
                $img['type'] = $_FILES["txtlocationplan"]["type"][$key];
                $img['name'] = $_FILES["txtlocationplan"]["name"][$key];
                $img['tmp_name'] = $_FILES["txtlocationplan"]["tmp_name"][$key];
                $unitImageArr['img'] = $img;
                $unitImageArr['objectId'] = $projectId;
                $unitImageArr['objectType'] = "project";
                $unitImageArr['newImagePath'] = $newImagePath;
                //print_r($arrTitle); die();
                //echo $img_path;
                //unlink($img_path); die();
                //die();
                $altText = $BuilderName . " " . strtolower($ProjectName) . " " . $arrTitle[$key];
                if ($handle = opendir($createFolder)) {
                    rewinddir($handle);
                    $file = '';
                    //while (false !== ($file = readdir($handle))) {

                        /*                         * **********Working for location plan********************** */
                        if (strstr($file, 'loc-plan')) {
                            if (strstr($file, $val)) {

                                /* $image = new SimpleImage();
                                  $path=$createFolder."/".$file;
                                  $image->load($path);
                                  $imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('loc-plan','loc-plan-bkp',$file);
                                  $image->save($imgdestpath); */



                                $params = array(
                                    "image_type" => "location_plan",
                                    "folder" => $extra_path, //"tmp/",
                                    "count" => "location_plan" . $key,
                                    "image" => $file,
                                    "priority" => $arrDisplayOrder[$key],
                                    "title" => $arrTitle[$key],
                                    "altText" => $altText,
                                );
                                $unitImageArr['params'] = $params;
                                $fileEndName[$key] = "loc-plan";
                                $postArr[$key] = $unitImageArr;

                                //  add images to image service


                                /* $returnArr = writeToImageService(  $img, "project", $projectId, $params, $newImagePath);
                                  //print_r($returnArr);
                                  $serviceResponse = $returnArr['serviceResponse'];
                                  if(empty($serviceResponse["service"]->response_body->error->msg)){
                                  $image_id = $serviceResponse["service"]->response_body->data->id;
                                  //$image_id = $image_id->id;
                                  //move_uploaded_file($tmp_path, $img_path);

                                  /*$s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                  "image_path" => str_replace($newImagePath, "", $imgdestpath),
                                  "object" => "project", "object_id" => $projectId,
                                  "image_type" => "location_plan"));
                                  $response = $s3upload->upload(); */
                                // Image id updation (next three lines could be written in single line but broken
                                // in three lines due to limitation of php 5.3)
                                //$image_id = $response["service"]->data();
                                //$image_id = $image_id->id;
                                /* $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('loc-plan','loc-plan-bkp',$file);
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('loc-plan','loc-plan-bkp',$file);



                                  /**********Working for watermark****************** */
                                /* $img = new Zubrag_watermark($path);
                                  $img->ApplyWatermark($watermark_path);
                                  $img->SaveAsFile($path);
                                  /*$s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                  $s3upload->upload(); */
                                /* $img->Free();
                                  /************Resize and large to small************ */
                                /* $image->resize(485,320);
                                  $newimg	=	str_replace('loc-plan','loc-plan-rect-img',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  /**********Working for watermark****************** */
                                // Image path
                                /* $image_path =$createFolder."/".$newimg;
                                  // Where to save watermarked image
                                  $imgdestpath = $createFolder."/".$newimg;
                                  // Watermark image
                                  $img = new Zubrag_watermark($image_path);
                                  $img->ApplyWatermark($watermark_path);
                                  $img->SaveAsFile($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload(); */
                                /* $img->Free();
                                  /************Resize and large to small************ */
                                /* $image->resize(95,65);
                                  $newimg	=	str_replace('loc-plan','loc-plan-sm-rect-img',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

                                  /************Resize and large to thumb************ */
                                /* $image->resize(77,70);
                                  $newimg	=	str_replace('loc-plan','loc-plan-thumb',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg; */

                                /* }
                                  else {
                                  $strErr = " Error in uploading Image No".($key+1)." ";
                                  $ErrorMsg["ImgError"] .= $strErr.$serviceResponse["service"]->response_body->error->msg."<br>";
                                  unlink($img_path);

                                  break 1;
                                  } */
                            }
                        }
                        /*                         * **********Working for layout plan********************** */
                        if (strstr($file, 'layout-plan')) {
                            if (strstr($file, $val)) {
                                /* $image = new SimpleImage();
                                  $path=$createFolder."/".$file;
                                  $image->load($path);
                                  $imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('layout-plan','layout-plan-bkp',$file);
                                  $image->save($imgdestpath); */

                                $params = array(
                                    "image_type" => "layout_plan",
                                    "folder" => $extra_path, //"tmp/",
                                    "count" => "layout_plan" . $key,
                                    "image" => $file,
                                    "priority" => $arrDisplayOrder[$key],
                                    "title" => $arrTitle[$key],
                                    "altText" => $altText,
                                );

                                $unitImageArr['params'] = $params;
                                $fileEndName[$key] = "layout-plan";
                                $postArr[$key] = $unitImageArr;
                                //  add images to image service


                                /* $returnArr = writeToImageService(  $img, "project", $projectId, $params, $newImagePath);
                                  // print_r($returnArr);
                                  $serviceResponse = $returnArr['serviceResponse'];
                                  if(empty($serviceResponse["service"]->response_body->error->msg)){
                                  $image_id = $serviceResponse["service"]->response_body->data->id;
                                  //$image_id = $image_id->id;


                                  /*$s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                  "image_path" => str_replace($newImagePath, "", $imgdestpath),
                                  "object" => "project", "object_id" => $projectId,
                                  "image_type" => "layout_plan"));
                                  $response = $s3upload->upload();
                                  // Image id updation (next three lines could be written in single line but broken
                                  // in three lines due to limitation of php 5.3)
                                  $image_id = $response["service"]->data();
                                  $image_id = $image_id->id; */
                                /* $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('layout-plan','layout-plan-bkp',$file);
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('layout-plan','layout-plan-bkp',$file);
                                  /**********Working for watermark****************** */
                                /* $img = new Zubrag_watermark($path);
                                  $img->ApplyWatermark($watermark_path);
                                  $img->SaveAsFile($path);
                                  /*$s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                  $s3upload->upload(); */
                                /* $img->Free();
                                  /************Resize and large to small************ */
                                /* $image->resize(485,320);
                                  $newimg	=	str_replace('layout-plan','layout-plan-rect-img',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  /**********Working for watermark****************** */
                                // Image path
                                /* $image_path =$createFolder."/".$newimg;
                                  // Where to save watermarked image
                                  $imgdestpath = $createFolder."/".$newimg;
                                  // Watermark image
                                  $img = new Zubrag_watermark($image_path);
                                  $img->ApplyWatermark($watermark_path);
                                  $img->SaveAsFile($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload(); */
                                /* $img->Free();
                                  /************Resize and large to small************ */
                                /* $image->resize(95,65);
                                  $newimg	=	str_replace('layout-plan','layout-plan-sm-rect-img',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

                                  /************Resize and large to thumb************ */
                                /* $image->resize(77,70);
                                  $newimg	=	str_replace('layout-plan','layout-plan-thumb',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg; */
                                /* }
                                  else {
                                  $strErr = " Error in uploading Image No".($key+1)." ";
                                  $ErrorMsg["ImgError"] .= $strErr.$serviceResponse["service"]->response_body->error->msg."<br>";
                                  unlink($img_path);
                                  break 1;
                                  } */
                            }
                        }
                        /*                         * **********Working for site plan********************** */
                        if (strstr($file, 'site-plan')) {
                            if (strstr($file, $val)) {
                                /* $image = new SimpleImage();
                                  $path=$createFolder."/".$file;
                                  $image->load($path);
                                  $imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('site-plan','site-plan-bkp',$file);
                                  $image->save($imgdestpath); */

                                $params = array(
                                    "image_type" => "site_plan",
                                    "folder" => $extra_path, //"tmp/",
                                    "count" => "site_plan" . $key,
                                    "image" => $file,
                                    "priority" => $arrDisplayOrder[$key],
                                    "title" => $arrTitle[$key],
                                    "altText" => $altText,
                                );

                                $unitImageArr['params'] = $params;
                                $fileEndName[$key] = "site-plan";
                                $postArr[$key] = $unitImageArr;
                                //  add images to image service


                                /* $returnArr = writeToImageService(  $img, "project", $projectId, $params, $newImagePath);

                                  $serviceResponse = $returnArr['serviceResponse'];
                                  if(empty($serviceResponse["service"]->response_body->error->msg)){
                                  $image_id = $serviceResponse["service"]->response_body->data->id;
                                  //$image_id = $image_id->id;





                                  /*$s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                  "image_path" => str_replace($newImagePath, "", $imgdestpath),
                                  "object" => "project", "object_id" => $projectId,
                                  "image_type" => "site_plan"));
                                  $response = $s3upload->upload();
                                  // Image id updation (next three lines could be written in single line but broken
                                  // in three lines due to limitation of php 5.3)
                                  $image_id = $response["service"]->data();
                                  $image_id = $image_id->id; */
                                /* $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('site-plan','site-plan-bkp',$file);
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('site-plan','site-plan-bkp',$file);
                                  /**********Working for watermark****************** */
                                /* $img = new Zubrag_watermark($path);
                                  $img->ApplyWatermark($watermark_path);
                                  $img->SaveAsFile($path);
                                  /*$s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                  $s3upload->upload(); */
                                /* $img->Free();
                                  /************Resize and large to small************ */
                                /* $image->resize(485,320);
                                  $newimg	=	str_replace('site-plan','site-plan-rect-img',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  /**********Working for watermark****************** */
                                // Image path
                                /* $image_path =$createFolder."/".$newimg;
                                  // Where to save watermarked image
                                  $imgdestpath = $createFolder."/".$newimg;
                                  // Watermark image
                                  $img = new Zubrag_watermark($image_path);
                                  $img->ApplyWatermark($watermark_path);
                                  $img->SaveAsFile($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload(); */
                                /* $img->Free();
                                  /************Resize and large to small************ */
                                /* $image->resize(95,65);
                                  $newimg	=	str_replace('site-plan','site-plan-sm-rect-img',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

                                  /************Resize and large to thumb************ */
                                /* $image->resize(77,70);
                                  $newimg	=	str_replace('site-plan','site-plan-thumb',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg; */
                                /* }
                                  else {
                                  $strErr = " Error in uploading Image No".($key+1)." ";
                                  $ErrorMsg["ImgError"] .= $strErr.$serviceResponse["service"]->response_body->error->msg."<br>";
                                  unlink($img_path);
                                  break 1;
                                  } */
                            }
                        }
                        /*                         * **********Working for master plan********************** */

                        //$_FILES["txtlocationplan"]["tmp_name"][$key]
                        //if (strstr($file, 'master-plan')) {
                        if ($_REQUEST['PType'] == 'Master Plan') {
                            if ($_FILES["txtlocationplan"]["tmp_name"][$key]) {

                                $img = array();
                                $img['error'] = $_FILES["txtlocationplan"]["error"][$key];
                                $img['type'] = $_FILES["txtlocationplan"]["type"][$key];
                                $img['name'] = $_FILES["txtlocationplan"]["name"][$key];
                                $img['tmp_name'] = $_FILES["txtlocationplan"]["tmp_name"][$key];
                                
                                //print "<pre>".$key.print_r($_FILES["txtlocationplan"]["tmp_name"],1)."</pre>";
                                //print ""
                                $tmp = array();
                                $tmp['image'] = "@" . $img['tmp_name'];
                                $tmp['objectId'] = $projectId;
                                $tmp['objectType'] = "project";
                                $tmp['imageType'] = 'masterPlan';
                                //$tmp['priority'] = $arrDisplayOrder[$key];
                                $tmp['title'] = $arrTitle[$key];
                                $tmp['altText'] = $altText;
                                //$tmp['service_image_id'] = $service_image_id;
                                //$tmp['update'] = "yes";
                                unset($unitImageArr['img']);
                                unset($unitImageArr['newImagePath']);
                                unset($unitImageArr['objectId']);
                                unset($unitImageArr['objectType']);
                                
                                $unitImageArr['upload_from_tmp'] = "yes";
                                $unitImageArr['method'] = "POST";
                                $unitImageArr['url'] = IMAGE_SERVICE_URL."/".$service_image_id;
                                $unitImageArr['params'] = $tmp;                                
                                $postArr[0] = $unitImageArr;

                                //print "<pre>files: ".print_r($postArr,1)."</pre>"; die;
//                                $params = array(
//                                    "image_type" => "master_plan",
//                                    "folder" => $extra_path, //"tmp/",
//                                    "count" => "master_plan" . $key,
//                                    "image" => $file,
//                                    "priority" => $arrDisplayOrder[$key],
//                                    "title" => $arrTitle[$key],
//                                    "altText" => $altText,
//                                );
//
//                                $unitImageArr['params'] = $params;
//                                $fileEndName[$key] = "master-plan";
//                                $postArr[$key] = $unitImageArr;
//                                
//                                
                                //  add images to image service
                                //print "<pre>sss: ".print_r($postArr,1)."</pre>"; die;

                                /* $returnArr = writeToImageService(  $img, "project", $projectId, $params, $newImagePath);
                                  //print_r($returnArr);
                                  $serviceResponse = $returnArr['serviceResponse'];
                                  if(empty($serviceResponse["service"]->response_body->error->msg)){
                                  $image_id = $serviceResponse["service"]->response_body->data->id;
                                  //$image_id = $image_id->id;

                                  /*$s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                  "image_path" => str_replace($newImagePath, "", $imgdestpath),
                                  "object" => "project", "object_id" => $projectId,
                                  "image_type" => "master_plan"));
                                  $response = $s3upload->upload();
                                  // Image id updation (next three lines could be written in single line but broken
                                  // in three lines due to limitation of php 5.3)
                                  $image_id = $response["service"]->data();
                                  $image_id = $image_id->id; */
                                /* $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('master-plan','master-plan-bkp',$file);
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('master-plan','master-plan-bkp',$file);
                                  /**********Working for watermark****************** */
                                /* $img = new Zubrag_watermark($path);
                                  $img->ApplyWatermark($watermark_path);
                                  $img->SaveAsFile($path);
                                  /*$s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                  $s3upload->upload(); */
                                /* $img->Free();
                                  /************Resize and large to small************ */
                                /* $image->resize(485,320);
                                  $newimg	=	str_replace('master-plan','master-plan-rect-img',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  /**********Working for watermark****************** */
                                // Image path
                                /* $image_path =$createFolder."/".$newimg;
                                  // Where to save watermarked image
                                  $imgdestpath = $createFolder."/".$newimg;
                                  // Watermark image
                                  $img = new Zubrag_watermark($image_path);
                                  $img->ApplyWatermark($watermark_path);
                                  $img->SaveAsFile($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload(); */
                                /* $img->Free();
                                  /************Resize and large to small************ */
                                /* $image->resize(95,65);
                                  $newimg	=	str_replace('master-plan','master-plan-sm-rect-img',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

                                  /************Resize and large to thumb************ */
                                /* $image->resize(77,70);
                                  $newimg	=	str_replace('master-plan','master-plan-thumb',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg; */
                                /* }
                                  else {
                                  $strErr = " Error in uploading Image No".($key+1)." ";
                                  $ErrorMsg["ImgError"] .= $strErr.$serviceResponse["service"]->response_body->error->msg."<br>";
                                  unlink($img_path);
                                  break 1;
                                  } */
                            }
                        }
                        /*                         * **********Working for cluster plan********************** */
                        if (strstr($file, 'cluster-plan')) {
                            if (strstr($file, $val)) {
                                /* $image = new SimpleImage();
                                  $path=$createFolder."/".$file;
                                  $image->load($path);
                                  $imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('cluster-plan','cluster-plan-bkp',$file);
                                  $image->save($imgdestpath); */


                                if ($arrTitle[$key] == null || empty($arrTitle[$key]))
                                    $altText = $BuilderName . " " . strtolower($ProjectName) . " Cluster Plan";
                                $params = array(
                                    "image_type" => "cluster_plan",
                                    "folder" => $extra_path, //"tmp/",
                                    "count" => "cluster_plan" . $key,
                                    "image" => $file,
                                    "priority" => $arrDisplayOrder[$key],
                                    "title" => $arrTitle[$key],
                                    "jsonDump" => array(
                                        "tower_id" => $arrTowerId[$key],
                                    ),
                                    "altText" => $altText,
                                );

                                $unitImageArr['params'] = $params;
                                $fileEndName[$key] = "cluster-plan";
                                $postArr[$key] = $unitImageArr;
                                //  add images to image service


                                /* $returnArr = writeToImageService(  $img, "project", $projectId, $params, $newImagePath);

                                  $serviceResponse = $returnArr['serviceResponse'];
                                  if(empty($serviceResponse["service"]->response_body->error->msg)){
                                  $image_id = $serviceResponse["service"]->response_body->data->id;
                                  //$image_id = $image_id->id;

                                  /*$s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                  "image_path" => str_replace($newImagePath, "", $imgdestpath),
                                  "object" => "project", "object_id" => $projectId,
                                  "image_type" => "cluster_plan"));
                                  $response = $s3upload->upload();
                                  // Image id updation (next three lines could be written in single line but broken
                                  // in three lines due to limitation of php 5.3)
                                  $image_id = $response["service"]->data();
                                  $image_id = $image_id->id; */
                                /* $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('cluster-plan','cluster-plan-bkp',$file);
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('cluster-plan','cluster-plan-bkp',$file);
                                  /**********Working for watermark****************** */
                                /* $img = new Zubrag_watermark($path);
                                  $img->ApplyWatermark($watermark_path);
                                  $img->SaveAsFile($path);
                                  /*$s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                  $s3upload->upload(); */
                                /* $img->Free();
                                  /************Resize and large to small************ */
                                /* $image->resize(485,320);
                                  $newimg	=	str_replace('cluster-plan','cluster-plan-rect-img',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  /**********Working for watermark****************** */
                                // Image path
                                /* $image_path =$createFolder."/".$newimg;
                                  // Where to save watermarked image
                                  $imgdestpath = $createFolder."/".$newimg;
                                  // Watermark image
                                  $img = new Zubrag_watermark($image_path);
                                  $img->ApplyWatermark($watermark_path);
                                  $img->SaveAsFile($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload(); */
                                /* $img->Free();
                                  /************Resize and large to small************ */
                                /* $image->resize(95,65);
                                  $newimg	=	str_replace('cluster-plan','cluster-plan-sm-rect-img',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

                                  /************Resize and large to thumb************ */
                                /* $image->resize(77,70);
                                  $newimg	=	str_replace('cluster-plan','cluster-plan-thumb',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg; */
                                /* }
                                  else {
                                  $strErr = " Error in uploading Image No".($key+1)." ";
                                  $ErrorMsg["ImgError"] .= $strErr.$serviceResponse["service"]->response_body->error->msg."<br>";
                                  unlink($img_path);
                                  break 1;
                                  } */
                            }
                        }
                        /*                         * **********Working for construction plan********************** */
                        if (strstr($file, 'const-status')) {
                            if (strstr($file, $val)) {
                                /* $image = new SimpleImage();
                                  $path=$createFolder."/".$file;
                                  $image->load($path);
                                  $imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('const-status','const-status-bkp',$file);
                                  $image->save($imgdestpath); */

                                $params = array(
                                    "image_type" => "construction_status",
                                    "folder" => $extra_path, //"tmp/",
                                    "count" => "construction_status" . $key,
                                    "image" => $file,
                                    "priority" => $arrDisplayOrder[$key],
                                    "title" => $arrTitle[$key],
                                    "tagged_date" => $arrTaggedDate[$key],
                                    "jsonDump" => array(
                                        "tower_id" => $arrTowerId[$key],
                                    ),
                                    "altText" => $altText,
                                );
                                $unitImageArr['params'] = $params;
                                $fileEndName[$key] = "const-status";
                                $postArr[$key] = $unitImageArr;
                                //  add images to image service


                                /* $returnArr = writeToImageService(  $img, "project", $projectId, $params, $newImagePath);

                                  $serviceResponse = $returnArr['serviceResponse'];

                                  if(empty($serviceResponse["service"]->response_body->error->msg)){

                                  $image_id = $serviceResponse["service"]->response_body->data->id;
                                  //$image_id = $image_id->id;



                                  /*$s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                  "image_path" => str_replace($newImagePath, "", $imgdestpath),
                                  "object" => "project", "object_id" => $projectId,
                                  "image_type" => "construction_status"));
                                  $response = $s3upload->upload();
                                  // Image id updation (next three lines could be written in single line but broken
                                  // in three lines due to limitation of php 5.3)
                                  $image_id = $response["service"]->data();
                                  $image_id = $image_id->id; */
                                /* $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('const-status','const-status-bkp',$file);
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('const-status','const-status-bkp',$file);
                                  /**********Working for watermark****************** */
                                /* $img = new Zubrag_watermark($path);
                                  $img->ApplyWatermark($watermark_path);
                                  $img->SaveAsFile($path);
                                  /*$s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                  $s3upload->upload(); */
                                /* $img->Free();
                                  /************Resize and large to small************ */
                                /* $image->resize(485,320);
                                  $newimg	=	str_replace('const-status','const-status-rect-img',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  /**********Working for watermark****************** */
                                // Image path
                                /* $image_path =$createFolder."/".$newimg;
                                  // Where to save watermarked image
                                  $imgdestpath = $createFolder."/".$newimg;
                                  // Watermark image
                                  $img = new Zubrag_watermark($image_path);
                                  $img->ApplyWatermark($watermark_path);
                                  $img->SaveAsFile($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload(); */
                                /* $img->Free();
                                  /************Resize and large to small************ */
                                /* $image->resize(95,65);
                                  $newimg	=	str_replace('const-status','const-status-sm-rect-img',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  /************Resize and large to small************ */
                                /* $image->resize(125,78);
                                  $newimg	=	str_replace('const-status','const-status-small',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

                                  /************Resize and large to thumb************ */
                                /* $image->resize(77,70);
                                  $newimg	=	str_replace('const-status','const-status-thumb',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg; */
                                /* }
                                  else {
                                  $strErr = " Error in uploading Image No".($key+1)." ";
                                  $ErrorMsg["ImgError"] .= $strErr.$serviceResponse["service"]->response_body->error->msg."<br>";
                                  unlink($img_path);
                                  break 1;
                                  } */
                            }
                        }

                        /*                         * **********Working for Payment plan********************** */
                        if (strstr($file, 'payment-plan')) {
                            if (strstr($file, $val)) {
                                /* $image = new SimpleImage();
                                  $path=$createFolder."/".$file;
                                  $image->load($path);
                                  $imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('payment-plan','payment-plan-bkp',$file);
                                  $image->save($imgdestpath); */

                                $params = array(
                                    "image_type" => "payment_plan",
                                    "folder" => $extra_path, //"tmp/",
                                    "count" => "payment_plan" . $key,
                                    "image" => $file,
                                    "priority" => $arrDisplayOrder[$key],
                                    "title" => $arrTitle[$key],
                                    "altText" => $altText,
                                );
                                $unitImageArr['params'] = $params;
                                $fileEndName[$key] = "payment-plan";
                                $postArr[$key] = $unitImageArr;
                                //  add images to image service


                                /* $returnArr = writeToImageService(  $img, "project", $projectId, $params, $newImagePath);

                                  $serviceResponse = $returnArr['serviceResponse'];
                                  if(empty($serviceResponse["service"]->response_body->error->msg)){
                                  $image_id = $serviceResponse["service"]->response_body->data->id;
                                  //$image_id = $image_id->id;



                                  /*$s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                  "image_path" => str_replace($newImagePath, "", $imgdestpath),
                                  "object" => "project", "object_id" => $projectId,
                                  "image_type" => "payment_plan"));
                                  $response = $s3upload->upload();
                                  // Image id updation (next three lines could be written in single line but broken
                                  // in three lines due to limitation of php 5.3)
                                  $image_id = $response["service"]->data();
                                  $image_id = $image_id->id; */
                                /* $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('payment-plan','payment-plan-bkp',$file);
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('payment-plan','payment-plan-bkp',$file);
                                  /**********Working for watermark****************** */
                                /* $img = new Zubrag_watermark($path);
                                  $img->ApplyWatermark($watermark_path);
                                  $img->SaveAsFile($path);
                                  /*$s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                  $s3upload->upload(); */
                                /* $img->Free();
                                  $image_path =$createFolder."/".$newimg;
                                  // Where to save watermarked image
                                  $imgdestpath = $createFolder."/".$newimg;
                                  /************Resize and large to small************ */
                                //echo $image->getWidth($imgdestpath);
                                /* if($image->getWidth($imgdestpath)>630)
                                  {
                                  $returnVal = scaleDimensions($image->getWidth($imgdestpath), $image->getHeight($imgdestpath), '620', '1200');
                                  $widht =  $returnVal[0];
                                  $height = $returnVal[1];
                                  }
                                  else
                                  {
                                  $widht =  $image->getWidth($imgdestpath);
                                  $height = $image->getHeight($imgdestpath);
                                  }
                                  //print_r($returnVal);
                                  /*$image->resize($widht,$height);
                                  $newimg	=	str_replace('payment-plan','payment-plan-rect-img',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

                                  /************Resize and large to thumb************ */
                                /* $image->resize(77,70);
                                  $newimg	=	str_replace('payment-plan','payment-plan-thumb',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg; */
                                /* }
                                  else {
                                  $strErr = " Error in uploading Image No".($key+1)." ";
                                  $ErrorMsg["ImgError"] .= $strErr.$serviceResponse["service"]->response_body->error->msg."<br>";
                                  unlink($img_path);
                                  break 1;
                                  } */
                            }
                        }
                        /*                         * **********Working for Specification********************** */
                        if (strstr($file, 'specification')) {
                            if (strstr($file, $val)) {
                                $image = new SimpleImage();
                                $path = $createFolder . "/" . $file;
                                $image->load($path);
                                $imgdestpath = $newImagePath . $BuilderName . "/" . strtolower($ProjectName) . "/" . str_replace('specification', 'specification-bkp', $file);
                                $image->save($imgdestpath);

                                $params = array(
                                    "image_type" => "specification",
                                    "folder" => $extra_path,
                                    "count" => "specification" . $key,
                                    "image" => $file,
                                    "priority" => $arrDisplayOrder[$key],
                                    "title" => $arrTitle[$key],
                                    "tagged_date" => $arrTaggedDate[$key],
                                    "jsonDump" => array(
                                        "tower_id" => $arrTowerId[$key],
                                    )
                                );
                                //  add images to image service


                                $returnArr = writeToImageService($img, "project", $projectId, $params, $newImagePath);

                                $serviceResponse = $returnArr['serviceResponse'];
                                if (empty($serviceResponse["service"]->response_body->error->msg)) {
                                    $image_id = $serviceResponse["service"]->response_body->data->id;
                                    //$image_id = $image_id->id;
                                } else {
                                    $ErrorMsg["ImgError"] = $serviceResponse["service"]->response_body->error->msg;
                                    break 2;
                                }


                                /* $s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                  "image_path" => str_replace($newImagePath, "", $imgdestpath),
                                  "object" => "project", "object_id" => $projectId,
                                  "image_type" => "specification"));
                                  $response = $s3upload->upload();
                                  // Image id updation (next three lines could be written in single line but broken
                                  // in three lines due to limitation of php 5.3)
                                  $image_id = $response["service"]->data();
                                  $image_id = $image_id->id; */
                                $source[] = $newImagePath . $BuilderName . "/" . strtolower($ProjectName) . "/" . str_replace('specification', 'specification-bkp', $file);
                                $dest[] = "public_html/images_new/" . $BuilderName . "/" . strtolower($ProjectName) . "/" . str_replace('specification', 'specification-bkp', $file);
                                /*                                 * ********Working for watermark****************** */
                                /* $img = new Zubrag_watermark($path);
                                  $img->ApplyWatermark($watermark_path);
                                  $img->SaveAsFile($path);
                                  $s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                  $s3upload->upload(); */
                                $img->Free();
                                /*                                 * **********Resize and large to small************ */
                                //echo $image->getWidth($imgdestpath);
                                /* if($image->getWidth($imgdestpath)>630)
                                  {
                                  $returnVal = scaleDimensions($image->getWidth($imgdestpath), $image->getHeight($imgdestpath), '620', '1200');
                                  $widht =  $returnVal[0];
                                  $height = $returnVal[1];
                                  }
                                  else
                                  {
                                  $widht =  $image->getWidth($imgdestpath);
                                  $height = $image->getHeight($imgdestpath);
                                  }
                                  //print_r($returnVal);
                                  $image->resize($widht,$height);
                                  $newimg	=	str_replace('specification','specification-rect-img',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

                                  /************Resize and large to thumb************ */
                                /* $image->resize(77,70);
                                  $newimg	=	str_replace('specification','specification-thumb',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg; */
                            }
                        }
                        /*                         * **********Working for Price List********************** */
                        if (strstr($file, 'price-list')) {
                            if (strstr($file, $val)) {
                                $image = new SimpleImage();
                                $path = $createFolder . "/" . $file;
                                $image->load($path);
                                $imgdestpath = $newImagePath . $BuilderName . "/" . strtolower($ProjectName) . "/" . str_replace('price-list', 'price-list-bkp', $file);
                                $image->save($imgdestpath);

                                $params = array(
                                    "image_type" => "price_list",
                                    "folder" => $extra_path,
                                    "count" => "price_list" . $key,
                                    "image" => $file,
                                    "priority" => $arrDisplayOrder[$key],
                                    "title" => $arrTitle[$key],
                                    "tagged_date" => $arrTaggedDate[$key],
                                );
                                //  add images to image service


                                $returnArr = writeToImageService($img, "project", $projectId, $params, $newImagePath);

                                $serviceResponse = $returnArr['serviceResponse'];
                                if (empty($serviceResponse["service"]->response_body->error->msg)) {
                                    $image_id = $serviceResponse["service"]->response_body->data->id;
                                    //$image_id = $image_id->id;
                                } else {
                                    $ErrorMsg["ImgError"] = $serviceResponse["service"]->response_body->error->msg;
                                    break 2;
                                }


                                /* $s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                  "image_path" => str_replace($newImagePath, "", $imgdestpath),
                                  "object" => "project", "object_id" => $projectId,
                                  "image_type" => "price_list"));
                                  $response = $s3upload->upload();
                                  // Image id updation (next three lines could be written in single line but broken
                                  // in three lines due to limitation of php 5.3)
                                  $image_id = $response["service"]->data();
                                  $image_id = $image_id->id; */
                                $source[] = $newImagePath . $BuilderName . "/" . strtolower($ProjectName) . "/" . str_replace('price-list', 'price-list-bkp', $file);
                                $dest[] = "public_html/images_new/" . $BuilderName . "/" . strtolower($ProjectName) . "/" . str_replace('price-list', 'price-list-bkp', $file);
                                /*                                 * ********Working for watermark****************** */
                                /* $img = new Zubrag_watermark($path);
                                  $img->ApplyWatermark($watermark_path);
                                  $img->SaveAsFile($path);
                                  $s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                  $s3upload->upload(); */
                                $img->Free();
                                /*                                 * **********Resize and large to small************ */
                                //echo $image->getWidth($imgdestpath);
                                /* if($image->getWidth($imgdestpath)>630)
                                  {
                                  $returnVal = scaleDimensions($image->getWidth($imgdestpath), $image->getHeight($imgdestpath), '620', '1200');
                                  $widht =  $returnVal[0];
                                  $height = $returnVal[1];
                                  }
                                  else
                                  {
                                  $widht =  $image->getWidth($imgdestpath);
                                  $height = $image->getHeight($imgdestpath);
                                  }
                                  //print_r($returnVal);
                                  $image->resize($widht,$height);
                                  $newimg	=	str_replace('price-list','price-list-rect-img',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

                                  /***********resize thumb********* */
                                /* $image->resize(77,70);
                                  $newimg	=	str_replace('price-list','price-list-thumb',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg; */
                            }
                        }
                        /*                         * **********Working for Application Form********************** */
                        if (strstr($file, 'app-form')) {
                            if (strstr($file, $val)) {
                                $image = new SimpleImage();
                                $path = $createFolder . "/" . $file;
                                $image->load($path);
                                $imgdestpath = $newImagePath . $BuilderName . "/" . strtolower($ProjectName) . "/" . str_replace('app-form', 'app-form-bkp', $file);
                                $image->save($imgdestpath);

                                $params = array(
                                    "image_type" => "application_form",
                                    "folder" => $extra_path,
                                    "count" => "application_form" . $key,
                                    "image" => $file,
                                    "priority" => $arrDisplayOrder[$key],
                                    "title" => $arrTitle[$key],
                                    "tagged_date" => $arrTaggedDate[$key],
                                    "jsonDump" => array(
                                        "tower_id" => $arrTowerId[$key],
                                    )
                                );
                                //  add images to image service


                                $returnArr = writeToImageService($img, "project", $projectId, $params, $newImagePath);

                                $serviceResponse = $returnArr['serviceResponse'];
                                if ($serviceResponse) {
                                    $image_id = $serviceResponse["service"]->response_body->data->id;
                                    //$image_id = $image_id->id;
                                } else {

                                    $ErrorMsg["ImgError"] = "Problem in Image Upload Please Try Again.";
                                    break;
                                }

                                /* $s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                  "image_path" => str_replace($newImagePath, "", $imgdestpath),
                                  "object" => "project", "object_id" => $projectId,
                                  "image_type" => "application_form"));
                                  $response = $s3upload->upload();
                                  // Image id updation (next three lines could be written in single line but broken
                                  // in three lines due to limitation of php 5.3)
                                  $image_id = $response["service"]->data();
                                  $image_id = $image_id->id; */
                                $source[] = $newImagePath . $BuilderName . "/" . strtolower($ProjectName) . "/" . str_replace('app-form', 'app-form-bkp', $file);
                                $dest[] = "public_html/images_new/" . $BuilderName . "/" . strtolower($ProjectName) . "/" . str_replace('app-form', 'app-form-bkp', $file);
                                /*                                 * ********Working for watermark****************** */
                                /* $img = new Zubrag_watermark($path);
                                  $img->ApplyWatermark($watermark_path);
                                  $img->SaveAsFile($path);
                                  $s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                  $s3upload->upload();
                                  $img->Free();
                                  /************Resize and large to small************ */
                                //echo $image->getWidth($imgdestpath);
                                /* if($image->getWidth($imgdestpath)>630)
                                  {
                                  $returnVal = scaleDimensions($image->getWidth($imgdestpath), $image->getHeight($imgdestpath), '620', '1200');
                                  $widht =  $returnVal[0];
                                  $height = $returnVal[1];
                                  }
                                  else
                                  {
                                  $widht =  $image->getWidth($imgdestpath);
                                  $height = $image->getHeight($imgdestpath);
                                  }
                                  //print_r($returnVal);
                                  $image->resize($widht,$height);
                                  $newimg	=	str_replace('app-form','app-form-rect-img',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

                                  /***********resize thumb********* */
                                /* $image->resize(77,70);
                                  $newimg	=	str_replace('app-form','app-form-thumb',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg; */
                            }
                        }
                        /*                         * **********Working for large********************** */
                        if (strstr($file, 'large')) {
                            if (strstr($file, $val)) {

                                if ($_REQUEST['PType'] == "Elevation")
                                    $image_type = "elevation";
                                if ($_REQUEST['PType'] == "Amenities")
                                    $image_type = "amenities";
                                if ($_REQUEST['PType'] == "Main Other")
                                    $image_type = "main_other";

                                $params = array(
                                    "image_type" => $image_type,
                                    "folder" => $extra_path, //"tmp/",
                                    "count" => "project_image" . $key,
                                    "image" => $file,
                                    "priority" => $arrDisplayOrder[$key],
                                    "title" => $arrTitle[$key],
                                    "altText" => $altText,
                                );
                                $unitImageArr['params'] = $params;
                                $fileEndName[$key] = "large";
                                $postArr[$key] = $unitImageArr;
                                //die("here");
                                //  add images to image service
                                /* $post = array(
                                  'image' => "@".$path,
                                  'objectType' => 'project',
                                  'objectId' => '500055',
                                  'imageType' => 'main',
                                  'priority' => 5,
                                  'title' => 'Project Image',
                                  'description' => '',
                                  'altText' => 'amrapali silicon-city Project Image'); */




                                //$returnArr = writeToImageService(  $img, "project", $projectId, $params, $newImagePath);
                                //  $serviceResponse = $returnArr['serviceResponse'];
                                //$serviceResponse = "";
                                /* if(!empty($serviceResponse["service"]->response_body->error->msg)){
                                  $image_id = $serviceResponse["service"]->response_body->data->id;
                                  //$image_id = $image_id->id;

                                  //move_uploaded_file($tmp_path, $img_path);

                                  /*$s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                  "image_path" => str_replace($newImagePath, "", $imgdestpath),
                                  "object" => "project", "object_id" => $projectId,
                                  "image_type" => "project_image","service_extra_params" => array("priority" => $arrDisplayOrder[$key])));


                                  $response = $s3upload->upload();

                                  // Image id updation (next three lines could be written in single line but broken
                                  // in three lines due to limitation of php 5.3)
                                  $image_id = $response["service"]->data();
                                  $image_id = $image_id->id; */

                                /* rename($tmp_path, $img_path);
                                  unlink($tmp_path);

                                  $image = new SimpleImage();
                                  $path	=	$createFolder."/".$file;
                                  $image->load($path);
                                  /************Working for large Img Backup********************** */
                                /* $image = new SimpleImage();
                                  $image->load($path);
                                  $imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('large','large-bkp',$file);
                                  $image->save($imgdestpath);

                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('large','large-bkp',$file);
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('large','large-bkp',$file);

                                  /************Resize and large to small************ */
                                /* $image->resize(485,320);
                                  $newimg	=	str_replace('large','large-rect-img',$file);
                                  $imgdestpath = $createFolder."/".$newimg;
                                  $image->save($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                                  /**********Working for watermark****************** */
                                // Image path
                                /* $image_path = $createFolder."/".$file;
                                  $imgdestpath = $createFolder."/".$file;
                                  $img = new Zubrag_watermark($image_path);
                                  $img->ApplyWatermark($watermark_path);
                                  $img->SaveAsFile($imgdestpath);
                                  /* $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload(); */
                                /* $img->Free();
                                  /*********update project table for samall image********** */
                                /* $pathProject	=	"/".$BuilderName."/".strtolower($ProjectName);
                                  $qry	=	"UPDATE ".RESI_PROJECT." SET PROJECT_SMALL_IMAGE = '".$pathProject."/".str_replace('-large','-small',$file)."'
                                  WHERE PROJECT_ID = '".$projectId."'";	//die("here");
                                  $res	=	mysql_query($qry);
                                  $image->resize(206,108);
                                  $newrect	=	str_replace('large','small',$file);
                                  $imgdestpath = $createFolder."/".$newrect;
                                  $image->save($imgdestpath);
                                  /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newrect;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newrect;
                                  /**********Working for watermark****************** */
                                // Image path
                                /* $image_path = $createFolder."/".$newimg;
                                  // Where to save watermarked image
                                  $imgdestpath = $createFolder."/".$newimg;
                                  // Watermark image
                                  $img = new Zubrag_watermark($image_path);
                                  $img->ApplyWatermark($watermark_path);
                                  $img->SaveAsFile($imgdestpath);
                                  /* $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload(); */
                                /* $img->Free();
                                  /************Resize and rect small img************ */
                                /* $image->resize(95,65);
                                  $newsmrect	=	str_replace('large','large-sm-rect-img',$file);
                                  $imgdestpath = $createFolder."/".$newsmrect;
                                  $image->save($imgdestpath);
                                  /* $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newsmrect;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newsmrect;

                                  /************Resize and thumb************ */
                                /* $image->resize(77,70);
                                  $newsmrect	=	str_replace('large','large-thumb',$file);
                                  $imgdestpath = $createFolder."/".$newsmrect;
                                  $image->save($imgdestpath);
                                  /* $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                  $s3upload->upload();
                                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newsmrect;
                                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newsmrect; */
                                /* }
                                  /*else {
                                  $strErr = " Error in uploading Image No ".($key+1)." ";
                                  $ErrorMsg["ImgError"] .= $strErr.$serviceResponse["service"]->response_body->error->msg."<br>";
                                  unlink($tmp_path);
                                  break 1;
                                  } */
                            }
                        }


                        /* $add_tower = '';

                          if($arrTowerId[$key] > 0)
                          $add_tower = " TOWER_ID = $arrTowerId[$key], ";

                          $imgDbPath = explode("/images_new",$img_path); */
                        /* $selqry	=	"SELECT PLAN_IMAGE FROM ".PROJECT_PLAN_IMAGES." WHERE PROJECT_ID = '".$projectId."' AND PLAN_TYPE = '".$_REQUEST['PType']."' AND PLAN_IMAGE = '".$imgDbPath[1]."'";
                          $selres	=	mysql_query($selqry);
                          if(mysql_num_rows($selres)>0)
                          {
                          $data			=	mysql_fetch_array($selres);
                          $path_loc		=	$newImagePath.$data['PLAN_IMAGE'];
                          touch($path_loc);
                          unlink($path_loc);
                          $qry	=	"UPDATE ".PROJECT_PLAN_IMAGES."
                          SET
                          PLAN_IMAGE = '".$imgDbPath[1]."',
                          TITLE	   = '".$arrTitle[$key]."',
                          SERVICE_IMAGE_ID   = ".$image_id.",
                          ".$add_tower."
                          DISPLAY_ORDER = '".$arrDisplayOrder[$key]."',
                          TAGGED_MONTH = '".$arrTaggedDate[$key]."'
                          WHERE PROJECT_ID = '".$projectId."'  AND PLAN_TYPE = '".$_REQUEST['PType']."' AND PLAN_IMAGE = '".$val."'";
                          $res	=	mysql_query($qry) or die(mysql_error());

                          } */

                        //else
                        //{
                        /* if($image_id>0)
                          {
                          $qryinsert = "INSERT INTO ".PROJECT_PLAN_IMAGES."
                          SET PLAN_IMAGE		=	'".$imgDbPath[1]."',
                          PROJECT_ID		=	'".$projectId."',
                          PLAN_TYPE		=	'".$_REQUEST['PType']."',
                          BUILDER_ID		=	'".$builderDetail['BUILDER_ID']."',
                          SERVICE_IMAGE_ID        =    ".$image_id.",
                          TITLE			=	'".$arrTitle[$key]."',
                          DISPLAY_ORDER = '".$arrDisplayOrder[$key]."',
                          TAGGED_MONTH = '".$arrTaggedDate[$key]."',
                          ".$add_tower."
                          SUBMITTED_DATE	=	now()";
                          //echo "query".$qryinsert;
                          $resinsert	=	mysql_query($qryinsert) or die(mysql_error());


                          }
                          $image_id=0; */
                    }
               // }

                if ($flag == 1) {
                    $builderfolder = strtolower($BuilderName);
                    $destBuilderFolder = '';
                    $sourceBuilderFolder = "public_html/images_new/$builderfolder";
                    //$result = upload_file_to_img_server_using_ftp($sourceBuilderFolder,$destBuilderFolder,4);
                }
                if ($projectFolderCreated == 1) {
                    $builderfolder = strtolower($BuilderName);
                    $projectNameFolder = strtolower($ProjectName);
                    $destProjectFolder = '';
                    $sourceProjectFolder = "public_html/images_new/$builderfolder/$projectNameFolder";
                    //$result = upload_file_to_img_server_using_ftp($sourceProjectFolder,$destProjectFolder,4);
                }

//echo "iter-end:".microtime(true)."<br>";
                //$result = upload_file_to_img_server_using_ftp($source,$dest,1);
                //image_idPOST['Next'] == 'Save')
            }
        }




        $serviceResponse = writeToImageService($postArr);
        //print("<pre>");var_dump($serviceResponse);die();
        //$serviceResponse = json_decode($serviceResponse);
        
        print "<pre>".print_r($serviceResponse,1)."</pre>";
        die;
				                  	
        foreach ($serviceResponse as $k => $v) {

            if (empty($v->error->msg)) {
                $image_id = $v->data->id;

                $file = $_FILES["txtlocationplan"]["name"][$k];
                $tmp_path = $tmpDir . $file;
                $img_path = $createFolder . "/" . $file;
                //echo $tmp_path; echo $img_path.$image_id;// die();
                //rename($tmp_path, $img_path);
                //unlink($tmp_path);
                $f = $fileEndName[$k];
                $image = new SimpleImage();
                $path = $createFolder . "/" . $file;
                $image->load($path);
                /*                 * **********Working for large Img Backup********************** */
                $image = new SimpleImage();
                $image->load($path);
                $imgdestpath = $newImagePath . $BuilderName . "/" . strtolower($ProjectName) . "/" . str_replace($f, $f . '-bkp', $file);
                $image->save($imgdestpath);

                $source[] = $newImagePath . $BuilderName . "/" . strtolower($ProjectName) . "/" . str_replace($f, $f . '-bkp', $file);
                $dest[] = "public_html/images_new/" . $BuilderName . "/" . strtolower($ProjectName) . "/" . str_replace($f, $f . '-bkp', $file);

                /*                 * **********Resize and large to small************ */
                $image->resize(485, 320);
                $newimg = str_replace($f, $f . '-rect-img', $file);
                $imgdestpath = $createFolder . "/" . $newimg;
                $image->save($imgdestpath);
                /* $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                  $s3upload->upload();
                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                  /**********Working for watermark****************** */
                // Image path
                $image_path = $createFolder . "/" . $file;
                $imgdestpath = $createFolder . "/" . $file;
                $img = new Zubrag_watermark($image_path);
                $img->ApplyWatermark($watermark_path);
                $img->SaveAsFile($imgdestpath);
                /* $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                  $s3upload->upload(); */
                $img->Free();
                /*                 * *******update project table for samall image********** */
                if ($_REQUEST['PType'] == "Elevation") {
                    $pathProject = "/" . $BuilderName . "/" . strtolower($ProjectName);

                    $qry = "UPDATE " . RESI_PROJECT . " SET PROJECT_SMALL_IMAGE = '" . $pathProject . "/" . str_replace('-large', '-small', $file) . "'
								 WHERE PROJECT_ID = '" . $projectId . "'"; //die("here");
                    $res = mysql_query($qry);
                    $image->resize(206, 108);
                    $newrect = str_replace('large', 'small', $file);
                    $imgdestpath = $createFolder . "/" . $newrect;
                    $image->save($imgdestpath);
                }
                /* $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                  $s3upload->upload();
                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newrect;
                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newrect;
                  /**********Working for watermark****************** */
                // Image path
                $image_path = $createFolder . "/" . $newimg;
                // Where to save watermarked image
                $imgdestpath = $createFolder . "/" . $newimg;
                // Watermark image
                $img = new Zubrag_watermark($image_path);
                $img->ApplyWatermark($watermark_path);
                $img->SaveAsFile($imgdestpath);
                /* $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                  $s3upload->upload(); */
                $img->Free();
                /*                 * **********Resize and rect small img************ */
                $image->resize(95, 65);
                $newsmrect = str_replace($f, $f . '-sm-rect-img', $file);
                $imgdestpath = $createFolder . "/" . $newsmrect;
                $image->save($imgdestpath);
                /* $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                  $s3upload->upload();
                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newsmrect;
                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newsmrect;

                  /************Resize and thumb************ */
                $image->resize(77, 70);
                $newsmrect = str_replace($f, $f . '-thumb', $file);
                $imgdestpath = $createFolder . "/" . $newsmrect;
                $image->save($imgdestpath);

                $add_tower = '';

                if ($arrTowerId[$k] > 0)
                    $add_tower = " TOWER_ID = $arrTowerId[$k], ";

                $imgDbPath = explode("/images_new", $img_path);


                if ($image_id > 0) {
                    $qryinsert = "INSERT INTO " . PROJECT_PLAN_IMAGES . "
									SET PLAN_IMAGE		=	'" . $imgDbPath[1] . "',
										PROJECT_ID		=	'" . $projectId . "',
										PLAN_TYPE		=	'" . $_REQUEST['PType'] . "',
										BUILDER_ID		=	'" . $builderDetail['BUILDER_ID'] . "',
										SERVICE_IMAGE_ID        =    " . $image_id . ",
										TITLE			=	'" . $arrTitle[$k] . "', 
										DISPLAY_ORDER = '" . $arrDisplayOrder[$k] . "',
										TAGGED_MONTH = '" . $arrTaggedDate[$k] . "',
										" . $add_tower . "
										SUBMITTED_DATE	=	now()";
                    //echo "query".$qryinsert;
                    $resinsert = mysql_query($qryinsert) or die(mysql_error());

                    //}
                }
                $image_id = 0;
            } else {
                $strErr = " Error in uploading Image No " . ($k + 1) . " ";
                $ErrorMsg["ImgError"] .= $strErr . $v->error->msg . "<br>";
                $file = $_FILES["txtlocationplan"]["name"][$k];
                $tmp_path = $tmpDir . $file;
                unlink($tmp_path);
            }
        }

        //die("here0");
        if (empty($ErrorMsg)) {
            if ($_POST['Next'] == 'Add More')
                header("Location:project_img_add.php?projectId=" . $projectId);
            else if ($_POST['Next'] == 'Save')
                header("Location:ProjectList.php?projectId=" . $projectId);
            else
                header("Location:add_specification.php?projectId=" . $projectId);
        }
    }
}
else if (isset($_POST['Skip'])) {
    header("Location:add_specification.php?projectId=" . $projectId);
} else if (isset($_POST['exit'])) {
    header("Location:ProjectList.php?projectId=" . $projectId);
}


$smarty->assign("ErrorMsg", $ErrorMsg);
/* * *************Project dropdown************ */
$Project = array();
$qry = "SELECT PROJECT_ID,PROJECT_NAME,BUILDER_NAME FROM " . RESI_PROJECT . " ORDER BY BUILDER_NAME ASC";
$res = mysql_query($qry);

while ($dataArr = mysql_fetch_array($res)) {
    array_push($Project, $dataArr);
}
$smarty->assign("Project", $Project);
?>
