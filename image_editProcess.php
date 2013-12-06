<?php
		set_time_limit(0);
		ini_set("memory_limit","256M");
		include("ftp.new.php");
		$ErrorMsg='';

		$watermark_path = 'pt_shadow1.png';
		 $projectId = $_GET['projectId'];
		$projectDetail	= ProjectDetail($projectId);
		$smarty->assign("ProjectDetail", $projectDetail);
		$ImageDataListingArr = allProjectImages($projectId);
		$builderDetail	= fetch_builderDetail($projectDetail[0]['BUILDER_ID']);

		$smarty->assign("ImageDataListingArr", $ImageDataListingArr);
		$count =0;
		$count+=count($ImageDataListingArr);
		if(!isset($_REQUEST['preview']))
			$_REQUEST['preview'] = '';
		$preview = $_REQUEST['preview'];
		$smarty->assign("preview", $preview);
		 $smarty->assign("count", $count);
		 $path = "";
		 $smarty->assign("path", $path);
		 
			 
		if( isset($_REQUEST['title']) &&  !array_filter($_REQUEST['title']) )
	    {
	      $ErrorMsg["title"] = "Please enter Image Title.";
	    }
		
		 /*********edit images code start here*******************/
			$source=array();
			$dest=array();

			if (isset($_POST['btnSave']) && !is_array($ErrorMsg)) 
			{
				$smarty->assign("projectId", $projectId);		
				$folderName = $projectDetail[0]['PROJECT_NAME'];
				
				/***********Folder name**********/
				$builderNamebuild = explode("/",$builderDetail['BUILDER_IMAGE']);

				/********************************/		
				$BuilderName = $builderNamebuild[1];
				$ProjectName = str_replace(" ","-",$projectDetail[0]['PROJECT_NAME']);	
				
				$arrValue = array();
				$arrTitle = array();
				
				foreach($_REQUEST['chk_name'] as $k=>$v)
				{
	                                  
					if($v != '')
					{

						if(!in_array(strtolower($_FILES['img']['type'][$k]), $arrImg))
						{
							$ErrorMsg["ImgError"] = "You can upload only ".ucwords(implode(" / ",$arrImg))." images.";
						}
						
						foreach($arrType  as $planType=>$imgNamePart)
						{
							if($_REQUEST['PType'][$k] == $planType)
							{
								if(!preg_match("/-".$imgNamePart."\.[a-z]{3,4}$/", $_FILES['img']['name'][$k]) && $_FILES['img']['name'][$k] != '')
						{
									$ErrorMsg["ImgError"] = "The word ".$imgNamePart." should be part of image name at end.";	
							}
						}
							}
						
						if($_FILES['img']['name'][$k] != '')
						{
							$arrValue[$k] = $_FILES['img']['name'][$k];
							$arrTitle[$k] = $_REQUEST['title'][$k];
						}
						else
						{
							/********delete image from db if checked but not browes new image*********/
                            $service_image_id = $_REQUEST['service_image_id'][$k];
                            $s3upload = new ImageUpload(NULL, array("service_image_id" => $service_image_id));
                            $response = $s3upload->delete();
							$qry	=	"DELETE FROM ".PROJECT_PLAN_IMAGES." 
                                                                         WHERE 
                                                                               PROJECT_ID = '".$projectId."'
                                                                               AND PLAN_TYPE = '".$_REQUEST['PType'][$k]."'
                                                                               AND PLAN_IMAGE = '".$_REQUEST['property_image_path'][$k]."'";
							$res	=	mysql_query($qry);		
							
							header("Location:image_edit.php?projectId=$projectId&edit=edit");
						}
					}
				}

				/*if(count($arrValue) == 0)
				{
					$ErrorMsg["blankerror"] = "Please select atleast one image.";	
				}*/
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
							$img_path       =   $newImagePath."/".$val;
							//$img_path		=	$newImagePath.$BuilderName."/".strtolower($ProjectName)."/" . $val;
							$createFolder	=	$newImagePath.$BuilderName."/".strtolower($ProjectName);
							$oldpath		=	$_REQUEST['property_image_path'][$key];
                            $service_image_id = $_REQUEST["service_image_id"][$key];

							//unlink($oldpath);
                            echo $img_path."<br>";
							$txtlocationplan 	= move_uploaded_file($_FILES["img"]["tmp_name"][$key], $img_path) or die("Can't");
                            
                            die;                                                        
                            $s3upload = new S3Upload($s3, $bucket, $img_path, str_replace($newImagePath, "", $img_path));
                            $s3upload->upload();
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

									if ($handle = opendir($createFolder))
									{
											rewinddir($handle);							
											while (false !== ($file = readdir($handle)))
											{								
											/************Working for location plan***********************/
												if(strstr($file,'loc-plan'))
												{
													if(strstr($file,$val))
													{											
														$image = new SimpleImage();
														$path=$createFolder."/".$file;
														$image->load($path);
                                                        $imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('loc-plan','loc-plan-bkp',$file);
														$image->save($imgdestpath);
                                                        $s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                                            "image_path" => str_replace($newImagePath, "", $imgdestpath),
                                                            "object" => "project", "object_id" => $projectId,
                                                            "image_type" => "location_plan",
                                                            "service_image_id" => $service_image_id));
                                                        $response = $s3upload->update();
                                                        // Image id updation (next three lines could be written in single line but broken
                                                        // in three lines due to limitation of php 5.3)
                                                        $image_id = $response["service"]->data();
                                                        $image_id = $image_id->id;
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('loc-plan','loc-plan-bkp',$file);
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('loc-plan','loc-plan-bkp',$file);		
														/**********Working for watermark*******************/
														$img = new Zubrag_watermark($path);
														$img->ApplyWatermark($watermark_path);
														$img->SaveAsFile($path);
                                                        $s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                                        $s3upload->upload();
														$img->Free(); 
														/************Resize and large to small*************/
														$image->resize(485,320);
														$newimg	=	str_replace('loc-plan','loc-plan-rect-img',$file);
                                                        $imgdestpath = $createFolder."/".$newimg;
														$image->save($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														/**********Working for watermark*******************/
														// Image path
														$image_path =$createFolder."/".$newimg;
														// Where to save watermarked image
														$imgdestpath = $createFolder."/".$newimg;
														// Watermark image
														$img = new Zubrag_watermark($image_path);
														$img->ApplyWatermark($watermark_path);
														$img->SaveAsFile($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$img->Free();  				 						
														/************Resize and large to small*************/
														$image->resize(95,65);
														$newimg	=	str_replace('loc-plan','loc-plan-sm-rect-img',$file);
                                                        $imgdestpath = $createFolder."/".$newimg;
														$image->save($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

														/************Resize and large to thumb*************/
														$image->resize(77,70);
														$newimg	=	str_replace('loc-plan','loc-plan-thumb',$file);
                                                        $imgdestpath = $createFolder."/".$newimg;
														$image->save($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
												}																						
											}
											/************Working for layout plan***********************/
												if(strstr($file,'layout-plan'))
												{
													if(strstr($file,$val))
													{						
														$image = new SimpleImage();
														$path=$createFolder."/".$file;
														$image->load($path);
														$imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('layout-plan','layout-plan-bkp',$file);
														$image->save($imgdestpath);
                                                        $s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                                            "image_path" => str_replace($newImagePath, "", $imgdestpath),
                                                            "object" => "project", "object_id" => $projectId,
                                                            "image_type" => "layout_plan",
                                                            "service_image_id" => $service_image_id));
                                                        $response = $s3upload->update();
                                                        // Image id updation (next three lines could be written in single line but broken
                                                        // in three lines due to limitation of php 5.3)
                                                        $image_id = $response["service"]->data();
                                                        $image_id = $image_id->id;
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('layout-plan','layout-plan-bkp',$file);
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('layout-plan','layout-plan-bkp',$file);
														/**********Working for watermark*******************/									
														$img = new Zubrag_watermark($path);
														$img->ApplyWatermark($watermark_path);
														$img->SaveAsFile($path);
                                                        $s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                                        $s3upload->upload();
														$img->Free(); 
														/************Resize and large to small*************/
														$image->resize(485,320);
														$newimg	=	str_replace('layout-plan','layout-plan-rect-img',$file);
                                                        $imgdestpath = $createFolder."/".$newimg;
														$image->save($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														/**********Working for watermark*******************/
														// Image path
														$image_path =$createFolder."/".$newimg;
														// Where to save watermarked image
														$imgdestpath = $createFolder."/".$newimg;
														// Watermark image
														$img = new Zubrag_watermark($image_path);
														$img->ApplyWatermark($watermark_path);
														$img->SaveAsFile($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$img->Free();  				 						
														/************Resize and large to small*************/
														$image->resize(95,65);
														$newimg	=	str_replace('layout-plan','layout-plan-sm-rect-img',$file);
                                                        $imgdestpath = $createFolder."/".$newimg;
														$image->save($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

														/************Resize and large to thumb*************/
														$image->resize(77,70);
														$newimg	=	str_replace('layout-plan','layout-plan-thumb',$file);
                                                        $imgdestpath = $createFolder."/".$newimg;
														$image->save($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
													}																							
												}
												/************Working for site plan***********************/
												if(strstr($file,'site-plan'))
												{
													if(strstr($file,$val))
													{						
														$image = new SimpleImage();
														$path=$createFolder."/".$file;
														$image->load($path);
														$imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('site-plan','site-plan-bkp',$file);
														$image->save($imgdestpath);
                                                        $s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                                            "image_path" => str_replace($newImagePath, "", $imgdestpath),
                                                            "object" => "project", "object_id" => $projectId,
                                                            "image_type" => "site_plan",
                                                            "service_image_id" => $service_image_id));
                                                        $response = $s3upload->update();
                                                        // Image id updation (next three lines could be written in single line but broken
                                                        // in three lines due to limitation of php 5.3)
                                                        $image_id = $response["service"]->data();
                                                        $image_id = $image_id->id;
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('site-plan','site-plan-bkp',$file);
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('site-plan','site-plan-bkp',$file);
														/**********Working for watermark*******************/
														$img = new Zubrag_watermark($path);
														$img->ApplyWatermark($watermark_path);
														$img->SaveAsFile($path);
                                                        $s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                                        $s3upload->upload();
														$img->Free(); 
														/************Resize and large to small*************/
														$image->resize(485,320);
														$newimg	=	str_replace('site-plan','site-plan-rect-img',$file);
                                                        $imgdestpath = $createFolder."/".$newimg;
														$image->save($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														/**********Working for watermark*******************/
														// Image path
														$image_path =$createFolder."/".$newimg;
														// Where to save watermarked image
														$imgdestpath = $createFolder."/".$newimg;
														// Watermark image
														$img = new Zubrag_watermark($image_path);
														$img->ApplyWatermark($watermark_path);
														$img->SaveAsFile($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$img->Free();  				 						
														/************Resize and large to small*************/
														$image->resize(95,65);
														$newimg	=	str_replace('site-plan','site-plan-sm-rect-img',$file);
                                                        $imgdestpath = $createFolder."/".$newimg;
														$image->save($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

														/************Resize and large to thumb*************/
														$image->resize(77,70);
														$newimg	=	str_replace('site-plan','site-plan-thumb',$file);
                                                        $imgdestpath = $createFolder."/".$newimg;
														$image->save($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
													}																						
												}
												/************Working for master plan***********************/
												if(strstr($file,'master-plan'))
												{
													if(strstr($file,$val))
													{				
														$image = new SimpleImage();
														$path=$createFolder."/".$file;
														$image->load($path);
                                                        $imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('master-plan','master-plan-bkp',$file);
														$image->save($imgdestpath);
                                                        $s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                                            "image_path" => str_replace($newImagePath, "", $imgdestpath),
                                                            "object" => "project", "object_id" => $projectId,
                                                            "image_type" => "master_plan",
                                                            "service_image_id" => $service_image_id));
                                                        $response = $s3upload->update();
                                                        // Image id updation (next three lines could be written in single line but broken
                                                        // in three lines due to limitation of php 5.3)
                                                        $image_id = $response["service"]->data();
                                                        $image_id = $image_id->id;
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('master-plan','master-plan-bkp',$file);
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('master-plan','master-plan-bkp',$file);
														/**********Working for watermark*******************/
														$img = new Zubrag_watermark($path);
														$img->ApplyWatermark($watermark_path);
														$img->SaveAsFile($path);
                                                        $s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                                        $s3upload->upload();
														$img->Free(); 
														/************Resize and large to small*************/
														$image->resize(485,320);
														$newimg	=	str_replace('master-plan','master-plan-rect-img',$file);
                                                        $imgdestpath = $createFolder."/".$newimg;
														$image->save($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														/**********Working for watermark*******************/
														// Image path
														$image_path =$createFolder."/".$newimg;
														// Where to save watermarked image
														$imgdestpath = $createFolder."/".$newimg;
														// Watermark image
														$img = new Zubrag_watermark($image_path);
														$img->ApplyWatermark($watermark_path);
														$img->SaveAsFile($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$img->Free();  				 						
														/************Resize and large to small*************/
														$image->resize(95,65);
														$newimg	=	str_replace('master-plan','master-plan-sm-rect-img',$file);
                                                        $imgdestpath = $createFolder."/".$newimg;
														$image->save($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

														/************Resize and large to thumb*************/
														$image->resize(77,70);
														$newimg	=	str_replace('master-plan','master-plan-thumb',$file);
                                                        $imgdestpath = $createFolder."/".$newimg;
														$image->save($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
													}																						
												}
												/************Working for cluster plan***********************/
												if(strstr($file,'cluster-plan'))
												{
													if(strstr($file,$val))
													{						
														$image = new SimpleImage();
														$path=$createFolder."/".$file;
														$image->load($path);
														$imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('cluster-plan','cluster-plan-bkp',$file);
														$image->save($imgdestpath);
                                                        $s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                                            "image_path" => str_replace($newImagePath, "", $imgdestpath),
                                                            "object" => "project", "object_id" => $projectId,
                                                            "image_type" => "cluster_plan",
                                                            "service_image_id" => $service_image_id));
                                                        $response = $s3upload->update();
                                                        // Image id updation (next three lines could be written in single line but broken
                                                        // in three lines due to limitation of php 5.3)
                                                        $image_id = $response["service"]->data();
                                                        $image_id = $image_id->id;
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('cluster-plan','cluster-plan-bkp',$file);
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('cluster-plan','cluster-plan-bkp',$file);										
														/**********Working for watermark*******************/									
														$img = new Zubrag_watermark($path);
														$img->ApplyWatermark($watermark_path);
														$img->SaveAsFile($path);
                                                        $s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                                        $s3upload->upload();
														$img->Free(); 
														/************Resize and large to small*************/
														$image->resize(485,320);
														$newimg	=	str_replace('cluster-plan','cluster-plan-rect-img',$file);
                                                        $imgdestpath = $createFolder."/".$newimg;
														$image->save($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														/**********Working for watermark*******************/
														// Image path
														$image_path =$createFolder."/".$newimg;
														// Where to save watermarked image
														$imgdestpath = $createFolder."/".$newimg;
														// Watermark image
														$img = new Zubrag_watermark($image_path);
														$img->ApplyWatermark($watermark_path);
														$img->SaveAsFile($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$img->Free();  				 						
														/************Resize and large to small*************/
														$image->resize(95,65);
														$newimg	=	str_replace('cluster-plan','cluster-plan-sm-rect-img',$file);
                                                        $imgdestpath = $createFolder."/".$newimg;
														$image->save($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

														/************Resize and large to thumb*************/
														$image->resize(77,70);
														$newimg	=	str_replace('cluster-plan','cluster-plan-thumb',$file);
                                                        $imgdestpath = $createFolder."/".$newimg;
														$image->save($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
												}																						
											}
											/************Working for construction plan***********************/
											if(strstr($file,'const-status'))
											{
												if(strstr($file,$val))
												{										
													$image = new SimpleImage();
													$path=$createFolder."/".$file;
													$image->load($path);
                                                    $imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('const-status','const-status-bkp',$file);
													$image->save($imgdestpath);
                                                    $s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                                        "image_path" => str_replace($newImagePath, "", $imgdestpath),
                                                        "object" => "project", "object_id" => $projectId,
                                                        "image_type" => "construction_status",
                                                        "service_image_id" => $service_image_id));
                                                    $response = $s3upload->update();
                                                    // Image id updation (next three lines could be written in single line but broken
                                                    // in three lines due to limitation of php 5.3)
                                                    $image_id = $response["service"]->data();
                                                    $image_id = $image_id->id;
													$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('const-status','const-status-bkp',$file);
													$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('const-status','const-status-bkp',$file);	
													/**********Working for watermark*******************/
													$img = new Zubrag_watermark($path);
													$img->ApplyWatermark($watermark_path);
													$img->SaveAsFile($path);
                                                    $s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                                    $s3upload->upload();
													$img->Free(); 
													/************Resize and large to small*************/
													$image->resize(485,320);
													$newimg	=	str_replace('const-status','const-status-rect-img',$file);
                                                    $imgdestpath = $createFolder."/".$newimg;
													$image->save($imgdestpath);
                                                    $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                    $s3upload->upload();
													$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
													$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
													/**********Working for watermark*******************/
													// Image path
													$image_path =$createFolder."/".$newimg;
													// Where to save watermarked image
													$imgdestpath = $createFolder."/".$newimg;
													// Watermark image
													$img = new Zubrag_watermark($image_path);
													$img->ApplyWatermark($watermark_path);
													$img->SaveAsFile($imgdestpath);
                                                    $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                    $s3upload->upload();
													$img->Free();  				 						
													/************Resize and large to small*************/
													$image->resize(95,65);
													$newimg	=	str_replace('const-status','const-status-sm-rect-img',$file);
                                                    $imgdestpath = $createFolder."/".$newimg;
													$image->save($imgdestpath);
                                                    $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                    $s3upload->upload();
													$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
													$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
													/************Resize and large to small*************/
													$image->resize(125,78);
													$newimg	=	str_replace('const-status','const-status-small',$file);
                                                    $imgdestpath = $createFolder."/".$newimg;
													$image->save($imgdestpath);
                                                    $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                    $s3upload->upload();
													$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
													$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

													/************Resize and large to thumb*************/
													$image->resize(77,70);
													$newimg	=	str_replace('const-status','const-status-thumb',$file);
                                                    $imgdestpath = $createFolder."/".$newimg;
													$image->save($imgdestpath);
                                                    $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                    $s3upload->upload();
													$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
													$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
												}																							
											}
											/************Working for Payment plan***********************/
											if(strstr($file,'payment-plan'))
											{
												if(strstr($file,$val))
												{								
													$image = new SimpleImage();
													$path=$createFolder."/".$file;
													$image->load($path);
                                                    $imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('payment-plan','payment-plan-bkp',$file);
													$image->save($imgdestpath);
                                                    $s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                                        "image_path" => str_replace($newImagePath, "", $imgdestpath),
                                                        "object" => "project", "object_id" => $projectId,
                                                        "image_type" => "payment_plan",
                                                        "service_image_id" => $service_image_id));
                                                    $response = $s3upload->update();
                                                    // Image id updation (next three lines could be written in single line but broken
                                                    // in three lines due to limitation of php 5.3)
                                                    $image_id = $response["service"]->data();
                                                    $image_id = $image_id->id;
													$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('payment-plan','payment-plan-bkp',$file);
													$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('payment-plan','payment-plan-bkp',$file);	
													/**********Working for watermark*******************/
													$img = new Zubrag_watermark($path);
													$img->ApplyWatermark($watermark_path);
													$img->SaveAsFile($path);
                                                    $s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                                    $s3upload->upload();
													$img->Free(); 								
													$image_path =$createFolder."/".$newimg;
													// Where to save watermarked image
													$imgdestpath = $createFolder."/".$newimg;
													/************Resize and large to small*************/						
													//echo $image->getWidth($imgdestpath);
													if($image->getWidth($imgdestpath)>630)
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
													$newimg	=	str_replace('payment-plan','payment-plan-rect-img',$file);
                                                    $imgdestpath = $createFolder."/".$newimg;
													$image->save($imgdestpath);
                                                    $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                    $s3upload->upload();
													$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
													$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

													/************Resize and large to thumb*************/
													$image->resize(77,70);
													$newimg	=	str_replace('payment-plan','payment-plan-thumb',$file);
                                                    $imgdestpath = $createFolder."/".$newimg;
													$image->save($imgdestpath);
                                                    $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                    $s3upload->upload();
													$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
													$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
												}																							
											}
											/************Working for Specification***********************/
											if(strstr($file,'specification'))
											{
												if(strstr($file,$val))
												{								
													$image = new SimpleImage();
													$path=$createFolder."/".$file;
													$image->load($path);
                                                    $imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('specification','specification-bkp',$file);
													$image->save($imgdestpath);
                                                    $s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                                        "image_path" => str_replace($newImagePath, "", $imgdestpath),
                                                        "object" => "project", "object_id" => $projectId,
                                                        "image_type" => "specification",
                                                        "service_image_id" => $service_image_id));
                                                    $response = $s3upload->update();
                                                    // Image id updation (next three lines could be written in single line but broken
                                                    // in three lines due to limitation of php 5.3)
                                                    $image_id = $response["service"]->data();
                                                    $image_id = $image_id->id;
													$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('specification','specification-bkp',$file);
													$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('specification','specification-bkp',$file);
													/**********Working for watermark*******************/
													$img = new Zubrag_watermark($path);
													$img->ApplyWatermark($watermark_path);
													$img->SaveAsFile($path);
                                                    $s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                                    $s3upload->upload();
													$img->Free(); 								
													$image_path =$createFolder."/".$newimg;
													// Where to save watermarked image
													$imgdestpath = $createFolder."/".$newimg;
													// Watermark image
													$img = new Zubrag_watermark($image_path);
													$img->ApplyWatermark($watermark_path);
													$img->SaveAsFile($imgdestpath);
                                                    $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                    $s3upload->upload();
													$img->Free();  				 						
													/************Resize and large to small*************/						
													//echo $image->getWidth($imgdestpath);
													if($image->getWidth($imgdestpath)>630)
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

													/************Resize and large to thumb*************/
													$image->resize(77,70);
													$newimg	=	str_replace('specification','specification-thumb',$file);
                                                    $imgdestpath = $createFolder."/".$newimg;
													$image->save($imgdestpath);
                                                    $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                    $s3upload->upload();
													$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
													$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
												}																													
											}
											/************Working for Price List***********************/
											if(strstr($file,'price-list'))
											{
												if(strstr($file,$val))
												{								
													$image = new SimpleImage();
													$path=$createFolder."/".$file;
													$image->load($path);
                                                    $imgdestpath = SERVER_PATH."/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('price-list','price-list-bkp',$file);
													$image->save($imgdestpath);
                                                    $s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                                        "image_path" => str_replace(SERVER_PATH."/images_new/", "", $imgdestpath),
                                                        "object" => "project", "object_id" => $projectId,
                                                        "image_type" => "price_list",
                                                        "service_image_id" => $service_image_id));
                                                    $response = $s3upload->update();
                                                    // Image id updation (next three lines could be written in single line but broken
                                                    // in three lines due to limitation of php 5.3)
                                                    $image_id = $response["service"]->data();
                                                    $image_id = $image_id->id;
													$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('price-list','price-list-bkp',$file);
													$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('price-list','price-list-bkp',$file);
													/**********Working for watermark*******************/
													$img = new Zubrag_watermark($path);
													$img->ApplyWatermark($watermark_path);
													$img->SaveAsFile($path);
                                                    $s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                                    $s3upload->upload();
													$img->Free();
													$image_path =$createFolder."/".$newimg;
													// Where to save watermarked image
													$imgdestpath = $createFolder."/".$newimg;
													// Watermark image
													$img = new Zubrag_watermark($image_path);
													$img->ApplyWatermark($watermark_path);
													$img->SaveAsFile($imgdestpath);
                                                    $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                    $s3upload->upload();
													$img->Free();  				 						
													/************Resize and large to small*************/						
													//echo $image->getWidth($imgdestpath);
													if($image->getWidth($imgdestpath)>630)
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

													/***********resize thumb**********/	
													$image->resize(77,70);
													$newimg	=	str_replace('price-list','price-list-thumb',$file);
                                                    $imgdestpath = $createFolder."/".$newimg;
													$image->save($imgdestpath);
                                                    $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                    $s3upload->upload();
													$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
													$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
												}																													
											}
											/************Working for Application Form***********************/
											if(strstr($file,'app-form'))
											{
												if(strstr($file,$val))
												{								
													$image = new SimpleImage();
													$path=$createFolder."/".$file;
													$image->load($path);
                                                    $imgdestpath = SERVER_PATH."/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('app-form','app-form-bkp',$file);
													$image->save($imgdestpath);
                                                    $s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                                        "image_path" => str_replace(SERVER_PATH."/images_new/", "", $imgdestpath),
                                                        "object" => "project", "object_id" => $projectId,
                                                        "image_type" => "application_form",
                                                        "service_image_id" => $service_image_id));
                                                    $response = $s3upload->update();
                                                    // Image id updation (next three lines could be written in single line but broken
                                                    // in three lines due to limitation of php 5.3)
                                                    $image_id = $response["service"]->data();
                                                    $image_id = $image_id->id;
													$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('app-form','app-form-bkp',$file);
													$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('app-form','app-form-bkp',$file);
													/**********Working for watermark*******************/
													$img = new Zubrag_watermark($path);
													$img->ApplyWatermark($watermark_path);
													$img->SaveAsFile($path);
                                                    $s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                                    $s3upload->upload();
													$img->Free(); 								
													$image_path =$createFolder."/".$newimg;
													// Where to save watermarked image
													$imgdestpath = $createFolder."/".$newimg;
													// Watermark image
													$img = new Zubrag_watermark($image_path);
													$img->ApplyWatermark($watermark_path);
													$img->SaveAsFile($imgdestpath);
                                                    $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                    $s3upload->upload();
													$img->Free();  				 						
													/************Resize and large to small*************/						
													//echo $image->getWidth($imgdestpath);
													if($image->getWidth($imgdestpath)>630)
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

													/***********resize thumb**********/	
													$image->resize(77,70);
													$newimg	=	str_replace('app-form','app-form-thumb',$file);
                                                    $imgdestpath = $createFolder."/".$newimg;
													$image->save($imgdestpath);
                                                    $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                    $s3upload->upload();
													$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
													$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
												}																													
											}
											/************Working for large***********************/
												if(strstr($file,'large'))
												{
													if(strstr($file,$val))
													{
														$image = new SimpleImage();
														$path	=	$createFolder."/".$file;
														$image->load($path);
														/************Working for large Img Backup***********************/
														$image = new SimpleImage();					
														$image->load($path);
                                                        $imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('large','large-bkp',$file);
														$image->save($imgdestpath);
                                                        $s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                                            "image_path" => str_replace($newImagePath, "", $imgdestpath),
                                                            "object" => "project", "object_id" => $projectId,
                                                            "image_type" => "project_image",
                                                            "service_image_id" => $service_image_id));
                                                        $response = $s3upload->update();
                                                        // Image id updation (next three lines could be written in single line but broken
                                                        // in three lines due to limitation of php 5.3)
                                                        $image_id = $response["service"]->data();
                                                        $image_id = $image_id->id;
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('large','large-bkp',$file);
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('large','large-bkp',$file);
														/************Resize and large to small*************/
														$image->resize(485,320);
														$newimg	=	str_replace('large','large-rect-img',$file);
                                                        $imgdestpath = $createFolder."/".$newimg;
														$image->save($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
														/**********Working for watermark*******************/
														// Image path
														$image_path = $createFolder."/".$file;
														$imgdestpath = $createFolder."/".$file;
														$img = new Zubrag_watermark($image_path);
														$img->ApplyWatermark($watermark_path);
														$img->SaveAsFile($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$img->Free();							
														/*********update project table for samall image***********/
														$pathProject	=	"/".$BuilderName."/".strtolower($ProjectName);
														$qry	=	"UPDATE ".RESI_PROJECT." SET PROJECT_SMALL_IMAGE = '".$pathProject."/".str_replace('-large','-small',$file)."'
																	 WHERE PROJECT_ID = '".$projectId."'";	//die("here");
														$res	=	mysql_query($qry);								
														$image->resize(206,108);
														$newrect	=	str_replace('large','small',$file);
                                                        $imgdestpath = $createFolder."/".$newrect;
														$image->save($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newrect;
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newrect;
														/**********Working for watermark*******************/
														// Image path
														$image_path = $createFolder."/".$newimg;
														// Where to save watermarked image
														$imgdestpath = $createFolder."/".$newimg;
														// Watermark image
														$img = new Zubrag_watermark($image_path);
														$img->ApplyWatermark($watermark_path);
														$img->SaveAsFile($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$img->Free();	
														/************Resize and rect small img*************/
														$image->resize(95,65);
														$newsmrect	=	str_replace('large','large-sm-rect-img',$file);
                                                        $imgdestpath = $createFolder."/".$newsmrect;
														$image->save($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newsmrect;
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newsmrect;	

														/************Resize and thumb*************/
														$image->resize(77,70);
														$newsmrect	=	str_replace('large','large-thumb',$file);
                                                        $imgdestpath = $createFolder."/".$newsmrect;
														$image->save($imgdestpath);
                                                        $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                                        $s3upload->upload();
														$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newsmrect;
														$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newsmrect;	
													}	
												 }
										}
									}
									$dbpath = explode("/images_new",$img_path);
									$qry	=	"UPDATE ".PROJECT_PLAN_IMAGES." 
												SET 
													PLAN_IMAGE = '".$dbpath[1]."',
													TITLE	   = '".$arrTitle[$key]."',
													SERVICE_IMAGE_ID   = ".$image_id."
												WHERE PROJECT_ID = '".$projectId."'  AND PLAN_TYPE = '".$_REQUEST['PType'][$key]."' AND PLAN_IMAGE = '".$oldpath."'";
									$res	=	mysql_query($qry);

								if($flag==1)
								{
									$builderfolder=strtolower($BuilderName);
									$destBuilderFolder = '';
									$sourceBuilderFolder = $newImagePath.$builderfolder;
									$result = upload_file_to_img_server_using_ftp($sourceBuilderFolder,$destBuilderFolder,4);
								
								}				
								if($projectFolderCreated==1)
								{
									$builderfolder=strtolower($BuilderName);
									$projectNameFolder=strtolower($ProjectName);					
									$destProjectFolder = '';
									$sourceProjectFolder = $newImagePath.$builderfolder."/.".$projectNameFolder;
									$result = upload_file_to_img_server_using_ftp($sourceProjectFolder,$destProjectFolder,4);					

								}							
											
								$result = upload_file_to_img_server_using_ftp($source,$dest,1);
								if($preview == 'true')
									header("Location:show_project_details.php?projectId=".$projectId);
								else
									header("Location:ProjectList.php?projectId=".$projectId);
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
		 	
?>
