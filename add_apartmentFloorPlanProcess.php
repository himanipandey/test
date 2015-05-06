<?php

	set_time_limit(0);
	ini_set("memory_limit","256M");
	include("ftp.new.php");
	$floorPlanOptionsArr = array();
	$villApartment = array();
	$plot = array();
	$commercial = array();
	$uploadedArr = array(); // array of titles ALREADY uploaded in image service 
//	$apartmentArr = array("Floor Plan", "Duplex", "Penthouse", "Triplex", "3D Floor Plan", "Panorama");
	$apartmentArr = array("Floor Plan", "Duplex", "Penthouse", "Triplex", "Panorama");
	$villaArray = array("Basement Floor", "Stilt Floor", "Ground Floor", "First Floor", "Second Floor", "Third Floor", "Terrace Floor", "Floor Plan", "Panorama");
	$duplex = array("Lower Level Duplex Plan", "Upper Level Duplex Plan", "Terrace Floor Plan", "Duplex Floor Plan");
	$penthouse = array("Lower Level Penthouse Plan", "Upper Level Penthouse Plan", "Penthouse Floor Plan", "Terrace Floor Plan");
	$triplex = array("Lower Level Floor", "Medium Level Floor", "Upper Level Floor", "Terrace Floor Plan");
	$ground_floor = array("Lower Ground Floor Plan", "Upper Ground Floor Plan", "Ground Floor Plan");
	//$ground_floor = array("Lower Ground Floor Plan", "Upper Ground Floor Plan", "Ground Floor Plan");


	// used to differentialte image types with doc types, 3D Floor Plan not added because its actually an image type
	$documentTypeArr = array("Panorama", "3D Floor Plan");

	$watermark_path = 'images/pt_shadow1.png';
	$projectId				=	$_REQUEST['projectId'];
    $projectDetail = ResiProject::virtual_find($projectId);
    $projectDetail = array($projectDetail->to_custom_array());
	$builderDetail			= ResiBuilder::find($projectDetail[0]['BUILDER_ID']);
    $builderDetail = $builderDetail->to_custom_array();
	$ProjectOptionDetail	=	getAllProjectOptionsExceptPlot($projectId);



	foreach ($ProjectOptionDetail as $k => $v) {
		$objectType = "property";
		$image_type = "floor_plan";
	    $objectId = $v['OPTION_ID'];
	    
	    $url = ImageServiceUpload::$image_upload_url."?objectType=$objectType&objectId=".$objectId;
	    $a_3d_url = DOC_SERVICE_URL."?objectType=$objectType&objectId=".$objectId;
	    //echo $url;
	    $content = file_get_contents($url);
	    $imgPath = json_decode($content);
	    
	    $arr = array();
	    foreach($imgPath->data as $k1=>$v1){
				array_push($arr, $v1->title);
		}
		$arr1 = array();
		$a_3d_content = file_get_contents($a_3d_url);
	    $a_3d_Path = json_decode($a_3d_content);
	    foreach($a_3d_Path->data as $k1=>$v1){
				array_push($arr1, $v1->description);
		}

		$uploadedArr[$k] = implode("-", $arr);
		$uploadedArr3D[$k] = implode("-", $arr1);
		if($v['UNIT_TYPE']=='Apartment'){
			$floorPlanOptionsArr[$k] = $apartmentArr;
			$villApartment[$k] = "yes";
			
		}
		else if($v['UNIT_TYPE']=='Villa'){
			$floorPlanOptionsArr[$k] = $villaArray;
			$villApartment[$k] = "yes";
		}
		else if($v['UNIT_TYPE']=='Plot'){
			unset($ProjectOptionDetail[$k]);
			$plot[$k] = "yes";
		}
			
		else if($v['UNIT_TYPE']=='commercial')
			$commercial[$k] = "yes";

	}
	//print("<pre>");
	//print_r($uploadedArr);
	




	$smarty->assign("projectId", $projectId);
	$smarty->assign("ProjectOptionDetail",$ProjectOptionDetail);
	$smarty->assign("ProjectDetail", $projectDetail);
	$smarty->assign("floorPlanOptionsArr", $floorPlanOptionsArr);
	$smarty->assign("villApartment", $villApartment);
	$smarty->assign("plot", $plot);
	$smarty->assign("commercial", $commercial);
	$smarty->assign("duplex", $duplex);
	$smarty->assign("triplex", $triplex);
	$smarty->assign("penthouse", $penthouse);
	$smarty->assign("ground_floor", $ground_floor);
	$smarty->assign("uploadedStr", $uploadedArr);
	$smarty->assign("uploadedStr3D", $uploadedArr3D);
	if(isset($_GET['edit']))
	{
		$smarty->assign("edit_projct", $projectId);
	}
	
	$flag					=	0;
	$projectFolderCreated	=	0;
	$optionId				=	'';
	$insertlist				=	'';
	$ErrorMsg1				=   '';

	$postArr = array(); // array to store image data to send with http request
	$fileEndName = array();
	//print("<pre>");var_dump($_REQUEST); die();



if (($_POST['btnSave'] == "Next") || ($_POST['btnSave'] == "Submit") || ($_POST['Next'] == "Add More")) {
    /*     * ***********Add new project type if projectid is blank******************************** */
//    prd($_REQUEST);
    if ($optionId == '') {
        $flgins = 0;
        foreach ($_REQUEST['floor_name'] AS $key => $val) {
            //die($_REQUEST['floor_name'][$key]);
            if ($val != '')
                //print "<pre>".print_r($_REQUEST['floor_name'],1)."</pre>"; die;
                if ($_REQUEST['floor_name'][$key] != '' && $_REQUEST['floor_name'][$key] != "0") {

                    //echo strtolower($_FILES["imgurl"]["type"][$key]);
                    if ($_FILES['imgurl']['name'][$key] != '') {
                        $flgins = 1;
                        if(!in_array(strtolower($_FILES["imgurl"]["type"][$key]), $arrImg) && !in_array($_REQUEST['floor_name'][$key], $documentTypeArr)) {
                            $ErrorMsg1 = "You can upload only jpg / jpeg gif png images."; //die("here");
                        }
//                        else if (!preg_match("/-floor-plan\.[a-z]{3,4}$/", $_FILES["imgurl"]["name"][$key])) {
//                            $ErrorMsg1 = "The word 'floor-plan' should be part of image name at end.";
//                        } 
                        else {
                            $floor_name = $_REQUEST['floor_name'][$key];
                            $option_id = $_REQUEST['option_id'][$key];
                            $imageType = $_REQUEST['image_type'][$key];
                            /*                             * *******************code for floor plan add************************** */
                            if ($_FILES["imgurl"]["type"][$key]) {
                                $builderNamebuild = explode("/", $builderDetail['BUILDER_IMAGE']);
                                $BuilderName = $builderNamebuild[1];
                                $ProjectName = str_replace(" ", "-", $projectDetail[0]['PROJECT_NAME']);
                                $imgurl1 = $_FILES["imgurl"]["name"][$key];
                                $foldlowe = strtolower($BuilderName);
                                $newdirlow = $newImagePath . $foldlowe;
//                                if ((!is_dir($newdirlow))) {
//                                    $lowerdir = strtolower($BuilderName);
//                                    $newdir = $newImagePath . $lowerdir;
//                                    mkdir($newdir, 0777);
//                                    $flag = 1;
//                                }

                                /*                                 * **************project folder check********* */
                                $newdirpro = $newImagePath . $BuilderName . "/" . $ProjectName;
                                $foldname = strtolower($ProjectName);
                                $andnewdirpro = $newImagePath . $BuilderName . "/" . $foldname;
//                                if ((!is_dir($newdirpro)) && (!is_dir($andnewdirpro))) {
//
//                                    $lowerpro = strtolower($ProjectName);
//                                    $ndirpro = $newImagePath . strtolower($BuilderName) . "/" . $lowerpro;
//                                    mkdir($ndirpro, 0777);
//                                    $projectFolderCreated = 1;
//                                    $createFolder = $ndirpro; //die("here");
//                                    $img_path = $ndirpro . "/" . $_FILES["imgurl"]["name"][$key]; //die("here");
//                                } else {
//
//                                    $img_path = $newImagePath . $BuilderName . "/" . strtolower($ProjectName) . "/" . $_FILES["imgurl"]["name"][$key];
//                                    $createFolder = $newImagePath . strtolower($BuilderName) . "/" . strtolower($ProjectName);
//                                }
                                /*                                 * ************************project folder check******** */
                                $projecttbl = "/" . strtolower($BuilderName) . "/" . strtolower($ProjectName);
                                //$flrplan = strstr($_FILES["imgurl"]["name"][$key], 'floor-plan');
                                $extra_path = strtolower($BuilderName) . "/" . strtolower($ProjectName) . "/";
                                if (!strstr($_FILES["imgurl"]["name"][$key], 'floor-plan')) {
                                    $flgimg = 1;
                                }
                                if ($_FILES["imgurl"]["name"][$key]) {
                                    //$txtlocationplan = move_uploaded_file($_FILES["imgurl"]["tmp_name"][$key], "" . $createFolder . "/" . $imgurl1);
                                    //$s3upload = new S3Upload($s3, $bucket, "".$createFolder."/" .$imgurl1, $projecttbl."/".$imgurl1 );
                                    //$s3upload->upload();

                                    $source[] = $newImagePath . $BuilderName . "/" . strtolower($ProjectName) . "/" . $_FILES["imgurl"]["name"][$key];
                                    $dest[] = $newImagePath . $BuilderName . "/" . strtolower($ProjectName) . "/" . $_FILES["imgurl"]["name"][$key];
                                    $imgurl8 = $projecttbl . "/" . $imgurl1;

                                    //list($width, $height) = getimagesize($img['tmp_name']);
                                    if(in_array($_REQUEST['floor_name'][$key], $documentTypeArr)){
					                	$width = "1053";
					                	$height =  "600";
					                }
					                else{
					                	list($width, $height) = getimagesize($createFolder."/" . $imgurl1);
					                }
                                    $media_extra_attributes = array("width" => $width, "height" => $height);
                                    $media_extra_attributes = json_encode($media_extra_attributes);
                                    //$media_extra_attributes =  "{'width':".$width.", 'height':".$height."}";
                                    //$media_extra_attributes = '{"width":1053, "height":600}';

                                    /*                                     * **********Working for floor plan********************** */


                                    if ($floor_name == "3D Floor Plan" || ($imageType=="3D"))
                                        $image_type = "3DFloorPlan";
                                    else if ($floor_name == "Panorama")
                                        $image_type = "Panoramic";
                                    else
                                        $image_type = "floorPlan";

                                    $img = array();
                                    $img['error'] = $_FILES["imgurl"]["error"][$key];
                                    $img['type'] = $_FILES["imgurl"]["type"][$key];
                                    $img['name'] = $_FILES["imgurl"]["name"][$key];
                                    $img['tmp_name'] = $_FILES["imgurl"]["tmp_name"][$key];

                                    $altText = $BuilderName . " " . strtolower($ProjectName) . " " . "Floor Plan" . " " . $floor_name;

                                    $tmp = array();
                                    $tmp['image'] = "@" . $img['tmp_name'];
                                    $tmp['objectId'] = $option_id;
                                    $tmp['objectType'] = "property";
                                    $tmp['imageType'] = $image_type;
                                    $tmp['title'] = $floor_name;
                                    $tmp['description'] = $floor_name;
                                    $tmp['altText'] = $altText;

                                    if(in_array($floor_name, $documentTypeArr) || ($imageType=="3D")){
                                        list($imgwidth, $imgheight) = getimagesize($img['tmp_name']);
                                        $extraAttr = array("width"=>$imgwidth,"height"=>$imgheight);
                                        if($_REQUEST['json_dump'][$key] !=""){
                                            $extraAttr["svg"] = $_REQUEST['json_dump'][$key];
                                        }
                                    	$tmp['stringMediaExtraAttributes'] = json_encode($extraAttr);
                                    	$tmp['documentType'] = $image_type;
                                    	$tmp['file'] = "@" . $img['tmp_name']. ';filename=' . $img['name']. ';type=' . $img['type'];
                                    	unset($tmp['image']);
                                    	unset($tmp['imageType']);
                                    	unset($tmp['title']);
                                    	unset($tmp['altText']);
                                    }

                                    $unitImageArr = array();
                                    $unitImageArr['upload_from_tmp'] = "yes";
                                    $unitImageArr['method'] = "POST";
                                    $unitImageArr['url'] = IMAGE_SERVICE_URL;
                                    $unitImageArr['params'] = $tmp;
                                    $postArr[$key] = $unitImageArr;
                                }
                            }
                        }
                        /*                         * *******************end code for floor plan add*********************** */
                    }
                } else {
                    if ($_FILES["imgurl"]["type"][$key]) {
                        $ErrorMsg1 .= 'You can not enter image without floor name';
                        $flgins = 1;
                    }
                }
        }
        $serviceResponse = writeToImageService($postArr);
        //print("<pre>");var_dump($serviceResponse);die();
        //$serviceResponse = json_decode($serviceResponse);
      //  print'<pre>'.print_r($serviceResponse,1);die;				                  	

        foreach ($serviceResponse as $k => $v) {

            if (empty($v->error->msg)) {
                $image_id = $v->data->id;
                if ($floor_name == "3D Floor Plan") {
                    $image_id = $v->id;
                }

                $file = $_FILES["imgurl"]["tmp_name"][$key];

                $img_path = $createFolder . "/" . $file;
                //echo $tmp_path; echo $img_path.$image_id;// die();
                $image = new SimpleImage();
                $path = $createFolder . "/" . $file;
                $image->load($path);
                $local_path = $BuilderName . "/" . strtolower($ProjectName) . "/" . str_replace('floor-plan', 'floor-plan-bkp', $file);
                $absolute_path = $newImagePath . $local_path;
                $image->save($newImagePath . $BuilderName . "/" . strtolower($ProjectName) . "/" . str_replace('floor-plan', 'floor-plan-bkp', $file));


                /*                 * ********Working for watermark****************** */
                $image_path = $path;
                // Where to save watermarked image
                $imgdestpath = $path;
                // Watermark image
                $img = new Zubrag_watermark($image_path);
                $img->ApplyWatermark($watermark_path);
                $img->SaveAsFile($imgdestpath);
                /* $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath,"",$imgdestpath));
                  $s3upload->upload(); */
                $img->Free();

                /*                 * **********Resize and rect img************ */
                $image->resize(485, 320);
                $newrect = str_replace('floor-plan', 'floor-plan-rect-img', $file);
                $image->save($createFolder . "/" . $newrect);

                /* $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newrect;
                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newrect;

                  /**********Working for watermark****************** */
                // Image path
                $image_path = $createFolder . "/" . $newrect;

                // Where to save watermarked image
                $imgdestpath = $createFolder . "/" . $newrect;

                // Watermark image
                $img = new Zubrag_watermark($image_path);
                $img->ApplyWatermark($watermark_path);
                $img->SaveAsFile($imgdestpath);
                /* $s3upload =new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath,"",$imgdestpath));
                  $s3upload->upload(); */
                $img->Free();

                /*                 * **********Resize and large to small************ */
                $image->resize(95, 65);
                $newimg = str_replace('floor-plan', 'floor-plan-sm-rect-img', $file);
                /* $s3upload =new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath,"",$imgdestpath));
                  $s3upload->upload(); */
                $imgdestpath = $createFolder . "/" . $newimg;
                $image->save($imgdestpath);
                /* $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath,"",$imgdestpath));
                  $s3upload->upload();
                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

                  /************Resize and large to small************ */
                $image->resize(80, 36);
                $newimg = str_replace('floor-plan', 'floor-plan-small', $file);
                $imgdestpath = $createFolder . "/" . $newimg;
                $image->save($imgdestpath);
                /* $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath,"",$imgdestpath));
                  $s3upload->upload();
                  $source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
                  $dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

                  /************Resize and large to thumb************ */
                $image->resize(77, 70);
                $newimg = str_replace('floor-plan', 'floor-plan-thumb', $file);
                $imgdestpath = $createFolder . "/" . $newimg;
                $image->save($imgdestpath);
                $option_id = $postArr[$k]['objectId'];
                $floor_name = $postArr[$k]['params']['title'];
                $imgurl8 = "/" . strtolower($BuilderName) . "/" . strtolower($ProjectName) . "/" . $_FILES["imgurl"]["name"][$k];
                if ($image_id > 0) {
                    $qry = "INSERT INTO " . RESI_FLOOR_PLANS . " (OPTION_ID,NAME,IMAGE_URL,DISPLAY_ORDER,SERVICE_IMAGE_ID) VALUES ('$option_id', '$floor_name','$imgurl8', '0' , $image_id)";

                    $res = mysql_query($qry) or die(mysql_error());
                }
                $image_id = 0;  //echo $qry; die("here");
            } else {
                $strErr = " Error in uploading Image No " . ($k + 1) . " ";
                //$ErrorMsg["ImgError"] .= $strErr.$v->error->msg."<br>";
                //$strErr = " Error in uploading Image No".($key+1)." ";
                //$ErrorMsg["ImgError"] .= $strErr.$serviceResponse["service"]->response_body->error->msg."<br>";
                $strErr1 = $v->error->msg;
                //echo $strErr1;
                $pos = strpos($strErr1, "property id-");
                if ($pos >= 0) {
                    $dupOptId = substr($strErr1, $pos + 12, 7);
                    $dupProjId = getProjectFromOption($dupOptId);
                    $insert_string = ", Project Id-" . $dupProjId . " ";
                    $strErr1 = substr_replace($strErr1, $insert_string, $pos + 19, 0);
                }
                $ErrorMsg["ImgError"] .= $strErr . $strErr1 . "<br>";

                //die("here1");
            }
        }
    }

    if ($flgins == 0) {
        $ErrorMsg1 .= 'Please select atleast one floor plan Image';
    }
    if ($ErrorMsg1 == '' AND empty($ErrorMsg)) {


        if ($_POST['Next'] == 'Add More') {
            if ($_GET['edit'] != '') {
                header("Location:add_apartmentFloorPlan.php?projectId=" . $projectId . "&edit=edit");
            } else {
                header("Location:add_apartmentFloorPlan.php?projectId=" . $projectId);
            }
        } else if ($_POST['btnSave'] == "Submit")
            header("Location:ProjectList.php?projectId=" . $projectId);
        else
            header("Location:project_other_price.php?projectId=" . $projectId);
    }
}

else if ($_POST['btnExit'] == "Exit") {
    header("Location:ProjectList.php?projectId=" . $projectId);
} else if ($_POST['Skip'] == "Skip") {
    header("Location:project_other_price.php?projectId=" . $projectId);
}

$smarty->assign("ErrorMsg1", $ErrorMsg1 . "<br>" . $ErrorMsg['ImgError']);
?>
