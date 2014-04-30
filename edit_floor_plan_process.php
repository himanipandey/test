<?php
		set_time_limit(0);
		ini_set("memory_limit","256M");
		include("ftp.new.php");
		$ErrorMsg='';

		$watermark_path = 'images/pt_shadow1.png';
		 $projectId = $_GET['projectId'];
		$projectDetail = ProjectDetail($projectId);
		$smarty->assign("ProjectDetail", $projectDetail);
		$ImageDataListings = allProjectFloorImages($projectId);
		
		$builderDetail	= fetch_builderDetail($projectDetail[0]['BUILDER_ID']);

		

		// get image path from image service
	//print'<pre>';
	//print_r($ImageDataListings);
		$ImageDataListingArr = array();
		$optionsArr = getAllProjectOptions($projectId);
		
		foreach ($optionsArr as $k1 => $v1) {
			$objectType = "property";
			
			
			$image_type = "floor_plan";
		    $objectId = $v1['OPTION_ID'];
		    
		    $url = ImageServiceUpload::$image_upload_url."?objectType=$objectType&objectId=".$objectId;
		    //echo $url;
		    $content = file_get_contents($url);
		    $imgPath = json_decode($content);
		    $data = array();
			foreach($imgPath->data as $k=>$v){
			    $data = array();
			    $data['OPTION_ID'] = $v1['OPTION_ID'];
			    $data['UNIT_TYPE'] = $v1['UNIT_TYPE'];
			    $data['SIZE'] = $v1['SIZE'];
			    $data['UNIT_NAME'] = $v1['UNIT_NAME'];
		        $data['SERVICE_IMAGE_ID'] = $v->id;
		        //$data['objectType'] = $v->imageType->objectType->type;
		        //$data['objectId'] = $v->objectId; 
		        $arr = preg_split('/(?=[A-Z])/',$v->imageType->type);
		        $str = ucfirst (implode(" ",$arr));
		        $data['PLAN_TYPE'] = "View ".$str;
		        $data['DISPLAY_ORDER'] = $v->priority;
		        $data['IMAGE_DESCRIPTION'] = $v->description;
		        $data['IMAGE_URL'] = $v->absolutePath;
		        $data['NAME'] = $v->title;
		        
		        $data['STATUS'] = $v->active;
		        //if(isset($v->createdAt))//if($v->created_at)
		        //	$data['tagged_month'] = gmdate("Y-m-d", $v->createdAt);
		        //else
		        	//$data['tagged_month'] = gmdate("Y-m-d", time());

		         //$str = trim(trim($v->jsonDump, '{'), '}');
		        //$towerarr = explode(":", $str);
		        //$data['tower_id'] = (int)trim($towerarr[1],"\"");
		       //var_dump($data['tower_id']);
		        
		       // echo $data['tower_id'];
		        //echo $data['tower_id'].$data['tagged_month']."<br>";
		        //print_r($v->jsonDump);
		        array_push($ImageDataListingArr, $data);

			}

		}
		

		$smarty->assign("ImageDataListingArr", $ImageDataListingArr);
		//$smarty->assign("img_path", $img_path);

		$count+=count($ImageDataFloorArr);
		$count+=count($ImageDataListingArr);

		
		 $smarty->assign("countPropImages", $countPropImages);
		 $smarty->assign("count", $count);
		 $path = "";
		 $smarty->assign("path", $path);
		 
		 /*********edit images code start here*******************/
			$source=array();
			$dest=array();

			$preview = $_REQUEST['preview'];
			$smarty->assign("preview", $preview);
			
		 if( isset($_REQUEST['title']) &&  array_filter($_REQUEST['title'], empty_test) )
	      {
	        $ErrorMsg["title"] = "Please enter Image Title.";
	      }
	      //print_r($ErrorMsg);

			if (isset($_POST['btnSave'])  && !is_array($ErrorMsg)) 
			{
				$smarty->assign("projectId", $projectId);		
				$folderName		=	$projectDetail[0]['PROJECT_NAME'];
				
				/***********Folder name**********/
				$builderNamebuild		=	explode("/",$builderDetail['BUILDER_IMAGE']);

				/********************************/		
				$BuilderName		=	$builderNamebuild[1];
				$ProjectName		=	str_replace(" ","-",$projectDetail[0]['PROJECT_NAME']);	
				
				$arrValue		= array();
				$arrTitle		= array();
				$arrplanId		= array();
				$arrOptionId	= array();
				
				foreach($_REQUEST['chk_name'] as $k=>$v)
				{
	
					if($v != '')
					{
						
						
						if($_FILES['img']['name'][$k] != '')
						{
							if(!in_array(strtolower($_FILES["img"]["type"][$k]), $arrImg))
							{
								$ErrorMsg['ImgError'] = "You can upload only jpg / jpeg gif png images.";
							} 
							else if(!preg_match("/-floor-plan\.[a-z]{3,4}$/", $_FILES["img"]["name"][$k]))
							{
								$ErrorMsg['ImgError'] = "The word 'floor-plan' should be part of image name at end.";
							}
                                                    $arrValue[$k]	= $_FILES['img']['name'][$k];
                                                    $arrTitle[$k]	= $_REQUEST['title'][$k];
                                                    $arrplanId[$k]	= $_REQUEST['plan_id'][$k];
                                                    $arrOptionId[$k]= $_REQUEST['option_id'][$k];
						}
						else
						{
                                                   /********delete image from db if checked but not browes new image*********/
                               $service_image_id = $_REQUEST['service_image_id'][$k];
                               //echo $service_image_id; 
                                                    $qry	=	"DELETE FROM ".RESI_FLOOR_PLANS." 
                                                                        WHERE 
                                                                                SERVICE_IMAGE_ID	= '".$service_image_id."'
                                                                                AND OPTION_ID	= '".$_REQUEST['option_id'][$k]."'";
                                                    $res	=	mysql_query($qry);
							/********delete image from db if checked but not browes new image*********/
                            //$service_image_id = $_REQUEST['service_image_id'][$k];

                   
                    		$deleteVal = deleteFromImageService("option", $arrOptionId[$k], $service_image_id);
                            //$s3upload = new ImageUpload(NULL, array("service_image_id" => $service_image_id));
                            //$s3upload->delete();
                            header("Location:edit_floor_plan.php?projectId=$projectId&edit=edit");
						}
					}
				}

				if( $projectId == '') 
				{
				  $ErrorMsg["projectId"] = "Please select Project name.";
				}
					
				if(is_array($ErrorMsg)) {

					// Do Nothing
				} 
				else if ($imageid == '')
				{
					$flag=0;
					
					/*******************Update location,site,layout and master plan from db and also from table*********/
						$builderPath = $newImagePath.strtolower($BuilderName);
							
						if(!is_dir($builderPath))
						{
							$builder	=	strtolower(str_replace(" ","-",$builderPath));
							mkdir($builder, 0777);
						}

						$proDir = $newImagePath.strtolower(str_replace(" ","-",$BuilderName))."/".strtolower(str_replace(" ","-",$ProjectName));
						if(!is_dir($proDir))
						{	
							mkdir($proDir, 0777);
						}

						foreach($arrValue as $key=>$val)
						{
							
							$img_path = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/" . $val;
							$createFolder = $newImagePath.$BuilderName."/".strtolower($ProjectName);
							$oldpath = $_REQUEST['property_image_path'][$key]; 
                            $service_image_id = $_REQUEST['service_image_id'][$key];
                            $extra_path = strtolower($BuilderName)."/".strtolower($ProjectName)."/";
							$txtlocationplan 	= move_uploaded_file($_FILES["img"]["tmp_name"][$key], $img_path);
                            //$s3upload = new S3Upload($s3, $bucket, $img_path, str_replace($newImagePath,"",$img_path));
                            //$s3upload->upload();
							if(!$txtlocationplan)
							{
								$ErrorMsg["ImgError"] = "Problem in Image Upload Please Try Again.";
								break;
							}
							else
							{
							$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/" . $val;
							$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$val;
							
							$projecttbl			=	"/".$BuilderName."/".strtolower($ProjectName);

								$img = array();
			                $img['error'] = $_FILES["img"]["error"][$key];
			                $img['type'] = $_FILES["img"]["type"][$key];
			                $img['name'] = $_FILES["img"]["name"][$key];
			                $img['tmp_name'] = $_FILES["img"]["tmp_name"][$key];
									if ($handle = opendir($createFolder))
									{
											rewinddir($handle);							
											while (false !== ($file = readdir($handle)))
											{								
											/************Working for location plan***********************/
												if(strstr($file,'floor-plan'))
												{
													if(strstr($file,$val))
													{											
														$image = new SimpleImage();
														$path=$createFolder."/".$file;
														$image->load($path);
                                                        $imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('floor-plan','floor-plan-bkp',$file);
														$image->save($imgdestpath);


														$params = array(
										                        "image_type" => "floor_plan",
										                        "folder" => $extra_path,
										                        "count" => "floor_plan".$key,
										                        "image" => $file,
										                        "priority" => 1,
										                        "title" => $arrTitle[$k],
										                        "active" => "1",
										                        "update" => "update",
										                        "service_image_id" => $service_image_id
										                );


										                    //  add images to image service

										                    
										                    $returnArr = writeToImageService(  $img, "option", $arrOptionId[$k], $params, $newImagePath);
										                    //print_r($returnArr);
										                    $serviceResponse = $returnArr['serviceResponse'];
											                    if($serviceResponse){
											                    $image_id = $serviceResponse["service"]->response_body->data->id;
																//$image_id = $image_id->id;
															}
															else {
																//echo $returnArr['error'];
																$ErrorMsg["ImgError"] = "Problem in Image Update Please Try Again.";
																break;
															}

                                                        /*$s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                                            "image_path" => str_replace($newImagePath,"",$imgdestpath),
                                                            "object" => "option", "image_type" => "floor_plan",
                                                            "object_id" => $arrOptionId[$key], "service_image_id" => $service_image_id));
                                                        $response = $s3upload->update();
                                                        // Image id updation (next three lines could be written in single line but broken
                                                        // in three lines due to limitation of php 5.3)
                                                        /*$image_id = $response["service"]->data();
                                                        $image_id = $image_id->id;
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('floor-plan','floor-plan-bkp',$file);
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('floor-plan','floor-plan-bkp',$file);		
														/**********Working for watermark*******************/
														/*$img = new Zubrag_watermark($path);
														$img->ApplyWatermark($watermark_path);
														$img->SaveAsFile($path);
                                                        $s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath,"",$path));
                                                        $s3upload->upload();
														$img->Free(); 
														/************Resize and large to small*************/
														/*$image->resize(485,320);
														$newimg	=	str_replace('floor-plan','floor-plan-rect-img',$file);
                                                        $imgdestpath = $createFolder."/".$newimg;
														$image->save($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath,"",$imgdestpath));
                                                        $s3upload->upload();
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														/**********Working for watermark*******************/
														// Image path
														/*$image_path =$createFolder."/".$newimg;
														// Where to save watermarked image
														$imgdestpath = $createFolder."/".$newimg;
														// Watermark image
														$img = new Zubrag_watermark($image_path);
														$img->ApplyWatermark($watermark_path);
														$img->SaveAsFile($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath,"",$imgdestpath));
                                                        $s3upload->upload();
														$img->Free();  				 						
														/************Resize and large to small*************/
														/*$image->resize(95,65);
														$newimg	=	str_replace('floor-plan','floor-plan-sm-rect-img',$file);
                                                        $imgdestpath = $createFolder."/".$newimg;
														$image->save($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath,"",$imgdestpath));
                                                        $s3upload->upload();
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

														/************Resize and large to thumb*************/
														/*$image->resize(77,70);
														$newimg	=	str_replace('floor-plan','floor-plan-thumb',$file);
                                                        $imgdestpath = $createFolder."/".$newimg;
														$image->save($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath,"",$imgdestpath));
                                                        $s3upload->upload();
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;*/
												}																						
											}
											
										}
									}

								if($flag==1)
								{
									$builderfolder=strtolower($BuilderName);
									$destBuilderFolder = '';
									$sourceBuilderFolder = "public_html/images_new/$builderfolder";
//									$result = upload_file_to_img_server_using_ftp($sourceBuilderFolder,$destBuilderFolder,4);
								
								}				
								if($projectFolderCreated==1)
								{
									$builderfolder=strtolower($BuilderName);
									$projectNameFolder=strtolower($ProjectName);					
									$destProjectFolder = '';
									$sourceProjectFolder = "public_html/images_new/$builderfolder/$projectNameFolder";
//									$result = upload_file_to_img_server_using_ftp($sourceProjectFolder,$destProjectFolder,4);

								}							
											
//								$result = upload_file_to_img_server_using_ftp($source,$dest,1);
								
								$imgPathDb = explode("images_new",$img_path);
								$qry = "UPDATE ".RESI_FLOOR_PLANS." 
                                                                        SET 
                                                                                IMAGE_URL = '".$imgPathDb[1]."',
                                                                                NAME	  = '".$arrTitle[$key]."',
                                                                                SERVICE_IMAGE_ID = '".$image_id."'
                                                                        WHERE 
                                                                                SERVICE_IMAGE_ID = '".$service_image_id."'
                                                                        AND 
                                                                                OPTION_ID	= '".$arrOptionId[$key]."'";
								
								$res = mysql_query($qry);
							
								if($res)
								{
								if($preview == 'true')
									header("Location:show_project_details.php?projectId=".$projectId);
								else
									header("Location:ProjectList.php?projectId=".$projectId);	
								}
							}
					}
				
				} 
			}
			else if(isset($_POST['btnExit']))
			{
				  if($preview == 'true')
					header("Location:show_project_details.php?projectId=".$projectId);
				else
					header("Location:ProjectList.php?projectId=".$projectId);
			}

			 $smarty->assign("ErrorMsg", $ErrorMsg);
		 /*******************************************************/
	function empty_test($val) {
	    return empty($val);
	}	 	
?>
