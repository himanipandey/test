<?php
	set_time_limit(0);
	ini_set("memory_limit","256M");
	include("ftp.new.php");
	$floorPlanOptionsArr = array();
	$villApartment = array();
	$plot = array();
	$commercial = array();
	$uploadedArr = array();
	$apartmentArr = array("Floor Plan", "Simplex", "Duplex", "Penthouse", "Triplex");
	$villaArray = array("Basement Floor", "Stilt Floor", "Ground Floor", "First Floor", "Second Floor", "Third Floor", "Terrace Floor");
	$duplex = array("Lower Level Duplex Plan", "Upper Level Duplex Plan");
	$penthouse = array("Lower Level Penthouse Plan", "Upper Level Penthouse Plan");
	$triplex = array("Ground Floor Plan", "First Floor Plan", "Second Floor Plan");
	$ground_floor = array("Lower Ground Floor Plan", "Upper Ground Floor Plan");

	$watermark_path = 'images/pt_shadow1.png';
	$projectId				=	$_REQUEST['projectId'];
    $projectDetail = ResiProject::virtual_find($projectId);
    $projectDetail = array($projectDetail->to_custom_array());
	$builderDetail			= ResiBuilder::find($projectDetail[0]['BUILDER_ID']);
    $builderDetail = $builderDetail->to_custom_array();
	$ProjectOptionDetail	=	ProjectOptionDetail($projectId);



	foreach ($ProjectOptionDetail as $k => $v) {
		$objectType = "property";
		$image_type = "floor_plan";
	    $objectId = $v['OPTIONS_ID'];
	    
	    $url = ImageServiceUpload::$image_upload_url."?objectType=$objectType&objectId=".$objectId;
	    //echo $url;
	    $content = file_get_contents($url);
	    $imgPath = json_decode($content);
	    
	    $arr = array();
	    foreach($imgPath->data as $k1=>$v1){
				array_push($arr, $v1->title);
		}
		$uploadedArr[$k] = implode("-", $arr);
		if($v['OPTION_TYPE']=='Apartment'){
			$floorPlanOptionsArr[$k] = $apartmentArr;
			$villApartment[$k] = "yes";
			
		}
		else if($v['OPTION_TYPE']=='Villa'){
			$floorPlanOptionsArr[$k] = $villaArray;
			$villApartment[$k] = "yes";
		}
		else if($v['OPTION_TYPE']=='Plot'){
			unset($ProjectOptionDetail[$k]);
			$plot[$k] = "yes";
		}
			
		else if($v['OPTION_TYPE']=='commercial')
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
	if(isset($_GET['edit']))
	{
		$smarty->assign("edit_projct", $projectId);
	}
	
	$flag					=	0;
	$projectFolderCreated	=	0;
	$optionId				=	'';
	$insertlist				=	'';
	$ErrorMsg1				=   '';
	if(($_POST['btnSave'] == "Next") || ($_POST['btnSave'] == "Submit") || ($_POST['Next'] == "Add More"))
	{
		/*************Add new project type if projectid is blank*********************************/
	   //print("<pre>");var_dump($_REQUEST); die();
	   if($optionId == '') 
	   {
		$flgins	=	0;
		foreach($_REQUEST['floor_name'] AS $key=>$val)
		{
			//die($_REQUEST['floor_name'][$key]);
			if($val != '')
				
			if($_REQUEST['floor_name'][$key] != '' && $_REQUEST['floor_name'][$key] != "0")
			{
	   
	   	//echo strtolower($_FILES["imgurl"]["type"][$key]);
	   		  if($_FILES['imgurl']['name'][$key] != '')
	   		  {
	   		  	$flgins	=	1;	
				if(!in_array(strtolower($_FILES["imgurl"]["type"][$key]), $arrImg))
				{
					$ErrorMsg1 = "You can upload only jpg / jpeg gif png images.";//die("here");
				}   
				else if(!preg_match("/-floor-plan\.[a-z]{3,4}$/", $_FILES["imgurl"]["name"][$key]))
				{
					$ErrorMsg1 = "The word 'floor-plan' should be part of image name at end.";
				}
				else
				{
					$floor_name					=	$_REQUEST['floor_name'][$key];
					$option_id					=	$_REQUEST['option_id'][$key];
						/*********************code for floor plan add***************************/
						if ($_FILES["imgurl"]["type"][$key])
						{
							$builderNamebuild		=	explode("/",$builderDetail['BUILDER_IMAGE']);
							$BuilderName			=	$builderNamebuild[1];	
							$ProjectName			=	str_replace(" ","-",$projectDetail[0]['PROJECT_NAME']);	
							$imgurl1				=	$_FILES["imgurl"]["name"][$key];
							$foldlowe				=	strtolower($BuilderName);
							$newdirlow				=	$newImagePath.$foldlowe;
							if((!is_dir($newdirlow)))
							{
								$lowerdir			=	strtolower($BuilderName);
								$newdir				=	$newImagePath.$lowerdir;
								mkdir($newdir, 0777);
								$flag=1;
							}
							
							/****************project folder check**********/
							$newdirpro		=	$newImagePath.$BuilderName."/".$ProjectName;
							$foldname		=	strtolower($ProjectName);
							$andnewdirpro	=	 $newImagePath.$BuilderName."/".$foldname;
							if((!is_dir($newdirpro)) && (!is_dir($andnewdirpro)))
							{
								
								$lowerpro			=	strtolower($ProjectName);
								$ndirpro			=	$newImagePath.strtolower($BuilderName)."/".$lowerpro;
								mkdir($ndirpro, 0777);
								$projectFolderCreated=1;
								$createFolder		=	$ndirpro;//die("here");
								$img_path			=	$ndirpro."/".$_FILES["imgurl"]["name"][$key];//die("here");
							}
							else {
								
								$img_path			=	$newImagePath.$BuilderName."/".strtolower($ProjectName)."/" . $_FILES["imgurl"]["name"][$key];
								$createFolder		=	$newImagePath.strtolower($BuilderName)."/".strtolower($ProjectName);
								
							}				
							/**************************project folder check*********/
							$projecttbl				=	"/".strtolower($BuilderName)."/".strtolower($ProjectName);
							$flrplan				=	strstr($_FILES["imgurl"]["name"][$key],'floor-plan');
							$extra_path = strtolower($BuilderName)."/".strtolower($ProjectName)."/";
							if(!strstr($_FILES["imgurl"]["name"][$key],'floor-plan'))
							{
								 $flgimg	=	1;
							}
							if($flrplan != '')
							{
								$txtlocationplan 	= move_uploaded_file($_FILES["imgurl"]["tmp_name"][$key], "".$createFolder."/" . $imgurl1);
                                //$s3upload = new S3Upload($s3, $bucket, "".$createFolder."/" .$imgurl1, $projecttbl."/".$imgurl1 );
                                //$s3upload->upload();

								if(!$txtlocationplan)
								{
									$ErrorMsg1 .= "Problem in Image Upload Please Try Again.";
									break;
								}
								else
								{
								$source[]			=	$newImagePath.$BuilderName."/".strtolower($ProjectName)."/" .  $_FILES["imgurl"]["name"][$key];
								$dest[]				=	$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". $_FILES["imgurl"]["name"][$key];
								$imgurl8 			= $projecttbl."/".$imgurl1;
								

								$img = array();
			                $img['error'] = $_FILES["imgurl"]["error"][$key];
			                $img['type'] = $_FILES["imgurl"]["type"][$key];
			                $img['name'] = $_FILES["imgurl"]["name"][$key];
			                $img['tmp_name'] = $_FILES["imgurl"]["tmp_name"][$key];	
									/*************Resize images code***************************/
								if ($handle = opendir($createFolder))
								{
								while (false !== ($file = readdir($handle)))
								{
									if(strstr($file,'floor-plan'))
									{
										/************Working for floor plan***********************/
										if(strstr($file,$_FILES["imgurl"]["name"][$key]))
										{
											$image = new SimpleImage();
											$path=$createFolder."/".$file;
											$image->load($path);
											$local_path = $BuilderName."/".strtolower($ProjectName)."/". str_replace('floor-plan','floor-plan-bkp',$file);
                                            $absolute_path = $newImagePath.$local_path;
											$image->save($newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('floor-plan','floor-plan-bkp',$file));

											$params = array(
							                        "image_type" => "floor_plan",
							                        "folder" => $extra_path,
							                        "count" => "floor_plan".$key,
							                        "image" => $file,
							                        "title" => $floor_name,
							                        
							                       
							                );


							                    //  add images to image service

							                    
						                    $returnArr = writeToImageService(  $img, "option", $option_id, $params, $newImagePath);
						                    //print_r($returnArr);
						                    $serviceResponse = $returnArr['serviceResponse'];
							                if(empty($serviceResponse["service"]->response_body->error->msg)){
						                    $image_id = $serviceResponse["service"]->response_body->data->id;
											//$image_id = $image_id->id;
											}
											else {
												$strErr = " Error in uploading Image No".($key+1)." ";
												$ErrorMsg["ImgError"] .= $strErr.$serviceResponse["service"]->response_body->error->msg."<br>";

												break 1;
											}

                                            /*$s3upload = new ImageUpload($absolute_path,array("s3" => $s3,
                                                "image_path" => $local_path, "object" => "option",
                                                "image_type" => "floor_plan", "object_id" => $option_id));
                                            $response = $s3upload->upload();
                                            // Image id updation (next three lines could be written in single line but broken
                                            // in three lines due to limitation of php 5.3)
                                            $image_id = $response["service"]->data();
                                            $image_id = $image_id->id;*/
											/*$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/" .str_replace('floor-plan','floor-plan-bkp',$file);
											$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('floor-plan','floor-plan-bkp',$file);
												/**********Working for watermark*******************/
												$image_path = $path;
												// Where to save watermarked image
												$imgdestpath = $path;
												// Watermark image
												$img = new Zubrag_watermark($image_path);
												$img->ApplyWatermark($watermark_path);
												$img->SaveAsFile($imgdestpath);
                                                /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath,"",$imgdestpath));
                                                $s3upload->upload();*/
												$img->Free();  				 						

												/************Resize and rect img*************/
												$image->resize(485,320);
												$newrect	=	str_replace('floor-plan','floor-plan-rect-img',$file);
												$image->save($createFolder."/".$newrect);

												/*$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newrect;
												$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newrect;

												/**********Working for watermark*******************/
											// Image path
												$image_path =$createFolder."/".$newrect;

												// Where to save watermarked image
												$imgdestpath = $createFolder."/".$newrect;

												// Watermark image
												$img = new Zubrag_watermark($image_path);
												$img->ApplyWatermark($watermark_path);
												$img->SaveAsFile($imgdestpath);
                                                /*$s3upload =new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath,"",$imgdestpath));
                                                $s3upload->upload();*/
												$img->Free();

												/************Resize and large to small*************/
												$image->resize(95,65);
												$newimg	=	str_replace('floor-plan','floor-plan-sm-rect-img',$file);
                                                /*$s3upload =new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath,"",$imgdestpath));
                                                $s3upload->upload();*/
                                                $imgdestpath = $createFolder."/".$newimg;
												$image->save($imgdestpath);
                                                /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath,"",$imgdestpath));
                                                $s3upload->upload();
												$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
												$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

												/************Resize and large to small*************/
												$image->resize(80,36);
												$newimg	=	str_replace('floor-plan','floor-plan-small',$file);
                                                $imgdestpath = $createFolder."/".$newimg;
												$image->save($imgdestpath);
                                                /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath,"",$imgdestpath));
                                                $s3upload->upload();
												$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
												$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

												/************Resize and large to thumb*************/
												$image->resize(77,70);
												$newimg	=	str_replace('floor-plan','floor-plan-thumb',$file);
                                                $imgdestpath = $createFolder."/".$newimg;
                                                $image->save($imgdestpath);
                                                /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath,"",$imgdestpath));
                                                $s3upload->upload();
												$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
												$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;*/
											}
										}
									} 
								}
								if($image_id>0){
									 $insertlist.=	 "('$option_id', '$floor_name','$imgurl8','1', $image_id),";
									 $image_id=0;
								}

							}
							}
								
						}
					}
						/*********************end code for floor plan add************************/
				  }

				}
				else
				{
					if ($_FILES["imgurl"]["type"][$key])
					{
						 $ErrorMsg1	.=	'You can not enter image without floor name';
						 $flgins = 1;
					}
				}
			}
		}
		
		if($flgins == 0)
		{
			 $ErrorMsg1	.=	'Please select atleast one floor plan Image';
		}
		if($ErrorMsg1 == '' AND $insertlist != '' )
		{
			
			$qry	 =  "INSERT INTO ".RESI_FLOOR_PLANS." (OPTION_ID,NAME,IMAGE_URL,DISPLAY_ORDER,SERVICE_IMAGE_ID) VALUES ";
			$str	 = $qry.$insertlist;
			$fullQry =  substr($str,0,-1);
			$res	 =	mysql_query($fullQry) or die(mysql_error());
			$lastid  =  mysql_insert_id();	
			if($res && empty($ErrorMsg))
			{				
				if($_POST['Next'] == 'Add More')
				{
					if($_GET['edit'] != '')
					{
						header("Location:add_apartmentFloorPlan.php?projectId=".$projectId."&edit=edit");
					}
					else
					{
						header("Location:add_apartmentFloorPlan.php?projectId=".$projectId);
					}
				}
				else if($_POST['btnSave'] == "Submit")
					header("Location:ProjectList.php?projectId=".$projectId);
				else
					header("Location:project_other_price.php?projectId=".$projectId);
			}
		}
		
	}

	else if($_POST['btnExit'] == "Exit")
	{
		  header("Location:ProjectList.php?projectId=".$projectId);
	}

	else if($_POST['Skip'] == "Skip")
	{
		  header("Location:project_other_price.php?projectId=".$projectId);
	}

	$smarty->assign("ErrorMsg1", $ErrorMsg1."<br>".$ErrorMsg['ImgError']);

?>
