<?php
set_time_limit(0);
ini_set("memory_limit","256M");

$projectplansid = $_REQUEST['projectplansid'];
$watermark_path = SERVER_PATH.'/images/watermark/pt_shadow1.png';
//echo $_REQUEST['suburb'];die("here");

//echo "<pre>";
//print_r($_REQUEST);
//echo "<br><br>";

//echo "<pre>";
//print_r($_FILES);


//echo "<br><br><pre>";
//print_r($arrValue);
//echo count($arrValue);
//die;

$smarty->assign("projectplansid", $projectplansid);

if ($_POST['btnSave'] == "Save") 
{

	echo "<pre>";
	print_r($_POST);
	die("ss");
	
	$projectId			=	trim($_POST['projectId']);
	
	
	$smarty->assign("projectId", $projectId);
	
	
			/***********Folder name**********/
			$qry			=	"SELECT PROJECT_NAME,BUILDER_NAME,BUILDER_ID FROM ".PROJECT." WHERE PROJECT_ID = '".$projectId."'";
			$res			=	mysql_query($qry,$db);
			$data			=	mysql_fetch_array($res);
			$folderName	=	$data['PROJECT_NAME'];
			
			/***********Folder name**********/
			$qrybuild			=	"SELECT BUILDER_IMAGE FROM ".RESI_BUILDER." WHERE BUILDER_ID = '".$data['BUILDER_ID']."'";
			$resbuild			=	mysql_query($qrybuild,$db);
			$databuild			=	mysql_fetch_array($resbuild);
			$builderNamebuild		=	explode("/",$databuild['BUILDER_IMAGE']);
		
			/********************************/		
			$BuilderName		=	$builderNamebuild[1];
			$ProjectName		=	str_replace(" ","-",$data['PROJECT_NAME']);	
	
	$arrValue = array();
	$arrTitle = array();
	if ($imageid=='')
	{
	 	
	  	foreach($_FILES['txtlocationplan']['name'] as $k=>$v)
		{
			if($v != '')
			{
				if($_REQUEST['PType'] == 'Location Plan')
				{
					if(!strstr($v,'loc-plan'))
					{
						$ErrorMsg["txtlocationplan"] = "The word 'loc-plan' should be part of image name.";	
					}
				}
				else if($_REQUEST['PType'] == 'Layout Plan')
				{
					if(!strstr($v,'layout-plan'))
					{
						$ErrorMsg["txtlayoutplan"] = "The word 'layout-plan' should be part of image name.";	
					}
				}
				else if($_REQUEST['PType'] == 'Site Plan')
				{
					if(!strstr($v,'site-plan'))
					{
						$ErrorMsg["txtsiteplan"] = "The word 'site-plan' should be part of image name.";	
					}
				}
				else if($_REQUEST['PType'] == 'Master Plan')
				{
					if(!strstr($v,'master-plan'))
					{
						$ErrorMsg["txtmasterplan"] = "The word 'master-plan' should be part of image name.";	
					}
				}
				else if($_REQUEST['PType'] == 'Project Image')
				{
					if(!strstr($v,'-large'))
					{
						$ErrorMsg["imglarge"] = "The word '-large' should be part of image name.";	
					}
				}
				else if($_REQUEST['PType'] == 'Cluster Plan')
				{
					if(!strstr($v,'cluster-plan'))
					{
						$ErrorMsg["txtclusterplan"] = "The word 'cluster-plan' should be part of image name.";	
					}
				}
				else if(($_REQUEST['PType'] == 'Construction Status') || ($_REQUEST['PType'] == 'Construction Plan'))
				{
					if(!strstr($v,'const-status'))
					{
						$ErrorMsg["txtconstructionplan"] = "The word 'const-status' should be part of image name.";	
					}
				}
				else if($_REQUEST['PType'] == 'Payment Plan')
				{
					if(!strstr($v,'payment-plan'))
					{
						$ErrorMsg["txtpaymentplan"] = "The word 'payment-plan' should be part of image name.";	
					}
				}

				$arrValue[$k] = $v;
				$arrTitle[$k] = $_REQUEST['title'][$k];
			}
		}

	    if(count($arrValue) == 0)
	    {
			$ErrorMsg["blankerror"] = "Please select atleast one image.";	
	    }
		else if( $projectId == '') 
	    {
	      $ErrorMsg["projectId"] = "Please select Project name.";
	    }
	} 		
	
	
	if(is_array($ErrorMsg)) {
		// Do Nothing
	} 
	else if ($imageid == '')
	{
		/*******************Update location,site,layout and master plan from db and also from table*********/
			//$createFolder		=	SERVER_PATH.'/images/'.$BuilderName."/".$ProjectName;
			
			$foldlowe	=	strtolower($BuilderName);
			$newdirlow	=	SERVER_PATH."/images/".$foldlowe;
			if((!is_dir($newdir)) && (!is_dir($newdirlow)))
			{
				$lowerdir	=	strtolower($BuilderName);
				$newdir		=	SERVER_PATH."/images/".$lowerdir;
				mkdir($newdir, 0777);
			}
			
			/****************project folder check**********/
			$newdirpro		=	SERVER_PATH."/images/".$BuilderName."/".$ProjectName;
			$foldname		=	strtolower($ProjectName);
			$andnewdirpro	=	 SERVER_PATH."/images/".$BuilderName."/".$foldname;

			foreach($arrValue as $key=>$val)
			{
				if((!is_dir($newdirpro)) && (!is_dir($andnewdirpro)))
				{
					
					$lowerpro	=	strtolower($ProjectName);
					$ndirpro		=	SERVER_PATH."/images/".$BuilderName."/".$lowerpro;
					mkdir($ndirpro, 0777);
					$createFolder	=	$ndirpro;//die("here");
					
					$img_path	=	$ndirpro."/".$val;//die("here");
				}
				else
				{
					
					$img_path		=	SERVER_PATH."/images/".$BuilderName."/".strtolower($ProjectName)."/" . $val;
					$createFolder	=	SERVER_PATH.'/images/'.$BuilderName."/".strtolower($ProjectName);
				}

				
				 $selqry	=	"SELECT PLAN_IMAGE FROM ".PROJECT_PLAN_IMAGES." WHERE PROJECT_ID = '".$projectId."' AND PLAN_TYPE = '".$_REQUEST['PType']."' AND PLAN_IMAGE = '".$img_path."'";
				$selres	=	mysql_query($selqry);
				if(mysql_num_rows($selres)>0)
				{
					$data			=	mysql_fetch_array($selres);
					$path_loc		=	SERVER_PATH."/images/".$data['PLAN_IMAGE'];
					touch($path_loc);
					unlink($path_loc);
					
					$qry	=	"UPDATE ".PROJECT_PLAN_IMAGES." 
										SET PLAN_IMAGE = '".str_replace("..//images/","/",$img_path)."'  
										WHERE PROJECT_ID = '".$projectId."'  AND PLAN_TYPE = '".$_REQUEST['PType']."' AND PLAN_IMAGE = '".$val."'";
					$res	=	mysql_query($qry);
				}
				else
				{
				 	 $qryinsert = "INSERT INTO ".PROJECT_PLAN_IMAGES."
									SET PLAN_IMAGE		=	'".str_replace("..//images/","/",$img_path)."',
										PROJECT_ID		=	'".$projectId."',
										PLAN_TYPE		=	'".$_REQUEST['PType']."',
										BUILDER_ID		=	'".$data['BUILDER_ID']."',
										TITLE			=	'".$arrTitle[$key]."',
										SUBMITTED_BY	=	'".$_SESSION['adminId']."',
										SUBMITTED_DATE	=	now()";
					$resinsert	=	mysql_query($qryinsert);

					/*****************insertion in project plan table***************/
					
					$fieldname = '';
					if($_REQUEST['PType'] == 'Location Plan')
					{
						$fieldname	=	'LOCATION_PLAN';
					}
					else if($_REQUEST['PType'] == 'Layout Plan')
					{
						$fieldname	=	'LAYOUT_PLAN';
					}
					else if($_REQUEST['PType'] == 'Site Plan')
					{
						$fieldname	=	'SITE_PLAN';
					}
					else if($_REQUEST['PType'] == 'Master Plan')
					{
						$fieldname	=	'MASTER_PLAN';
					}
					else if($_REQUEST['PType'] == 'Construction Status')
					{
						$fieldname	=	'CONSTRUCTION_PLAN';
					}
					else if($_REQUEST['PType'] == 'Payment Plan')
					{
						$fieldname	=	'PAYMENT_PLAN';
					}

					$smallImg	=	$fieldname."_SMALL";
					$imgSml		=	str_replace(".jpg","-sm.jpg",str_replace("..//images/","/",$img_path));


					if($fieldname != '')
					{
						$qryProjectPlan	=	"SELECT PROJECT_ID FROM RESI_PROJECT_PLANS WHERE PROJECT_ID = '".$projectId."'";
						$resProjectPlan	=	mysql_query($qryProjectPlan);
						if(mysql_num_rows($resProjectPlan)>0)
						{
							
							$qryUpdate  = "UPDATE RESI_PROJECT_PLANS SET 
										$fieldname = '".str_replace("..//images/","/",$img_path)."',
										$smallImg='".$imgSml."' WHERE PROJECT_ID = '".$projectId."'";
							$resUpdate	=	mysql_query($qryUpdate);
						}
						else
						{
							$qryInsert  = "INSERT INTO RESI_PROJECT_PLANS SET 
											$fieldname	=	'".str_replace("..//images/","/",$img_path)."',
											$smallImg	=	'".$imgSml."',
											PROJECT_ID	=	'".$projectId."'";
							$resInsert	=	mysql_query($qryInsert);
						}
					}
					/*****************end insertion in project plan table***************/		
				}

				$txtlocationplan 	= move_uploaded_file($_FILES["txtlocationplan"]["tmp_name"][$key], $img_path);
				
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
									
										$image->save(SERVER_PATH."/images/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('loc-plan','loc-plan-bkp',$file));
								
									/**********Working for watermark*******************/
							
									$img = new Zubrag_watermark($path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($path);
									$img->Free(); 

										/************Resize and large to small*************/
									$image->resize(485,320);
									$newimg	=	str_replace('loc-plan','loc-plan-rect-img',$file);
									$image->save($createFolder."/".$newimg);

									/**********Working for watermark*******************/
								// Image path
									$image_path =$createFolder."/".$newimg;

									// Where to save watermarked image
									$imgdestpath = $createFolder."/".$newimg;

									// Watermark image
									$img = new Zubrag_watermark($image_path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($imgdestpath);
									$img->Free();  				 						

									/************Resize and large to small*************/
								$image->resize(95,65);
								$newimg	=	str_replace('loc-plan','loc-plan-sm-rect-img',$file);
								$image->save($createFolder."/".$newimg);
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
									
										$image->save(SERVER_PATH."/images/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('layout-plan','layout-plan-bkp',$file));
								
									/**********Working for watermark*******************/
							
									$img = new Zubrag_watermark($path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($path);
									$img->Free(); 

										/************Resize and large to small*************/
									$image->resize(485,320);
									$newimg	=	str_replace('layout-plan','layout-plan-rect-img',$file);
									$image->save($createFolder."/".$newimg);

									/**********Working for watermark*******************/
								// Image path
									$image_path =$createFolder."/".$newimg;

									// Where to save watermarked image
									$imgdestpath = $createFolder."/".$newimg;

									// Watermark image
									$img = new Zubrag_watermark($image_path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($imgdestpath);
									$img->Free();  				 						

									/************Resize and large to small*************/
								$image->resize(95,65);
								$newimg	=	str_replace('layout-plan','layout-plan-sm-rect-img',$file);
								$image->save($createFolder."/".$newimg);
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
									
										$image->save(SERVER_PATH."/images/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('site-plan','site-plan-bkp',$file));
								
									/**********Working for watermark*******************/
							
									$img = new Zubrag_watermark($path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($path);
									$img->Free(); 

										/************Resize and large to small*************/
									$image->resize(485,320);
									$newimg	=	str_replace('site-plan','site-plan-rect-img',$file);
									$image->save($createFolder."/".$newimg);

									/**********Working for watermark*******************/
								// Image path
									$image_path =$createFolder."/".$newimg;

									// Where to save watermarked image
									$imgdestpath = $createFolder."/".$newimg;

									// Watermark image
									$img = new Zubrag_watermark($image_path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($imgdestpath);
									$img->Free();  				 						

									/************Resize and large to small*************/
								$image->resize(95,65);
								$newimg	=	str_replace('site-plan','site-plan-sm-rect-img',$file);
								$image->save($createFolder."/".$newimg);
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
									
										$image->save(SERVER_PATH."/images/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('master-plan','master-plan-bkp',$file));
								
									/**********Working for watermark*******************/
							
									$img = new Zubrag_watermark($path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($path);
									$img->Free(); 

										/************Resize and large to small*************/
									$image->resize(485,320);
									$newimg	=	str_replace('master-plan','master-plan-rect-img',$file);
									$image->save($createFolder."/".$newimg);

									/**********Working for watermark*******************/
								// Image path
									$image_path =$createFolder."/".$newimg;

									// Where to save watermarked image
									$imgdestpath = $createFolder."/".$newimg;

									// Watermark image
									$img = new Zubrag_watermark($image_path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($imgdestpath);
									$img->Free();  				 						

									/************Resize and large to small*************/
								$image->resize(95,65);
								$newimg	=	str_replace('master-plan','master-plan-sm-rect-img',$file);
								$image->save($createFolder."/".$newimg);
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
									
										$image->save(SERVER_PATH."/images/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('cluster-plan','cluster-plan-bkp',$file));
								
									/**********Working for watermark*******************/
							
									$img = new Zubrag_watermark($path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($path);
									$img->Free(); 

										/************Resize and large to small*************/
									$image->resize(485,320);
									$newimg	=	str_replace('cluster-plan','cluster-plan-rect-img',$file);
									$image->save($createFolder."/".$newimg);

									/**********Working for watermark*******************/
								// Image path
									$image_path =$createFolder."/".$newimg;

									// Where to save watermarked image
									$imgdestpath = $createFolder."/".$newimg;

									// Watermark image
									$img = new Zubrag_watermark($image_path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($imgdestpath);
									$img->Free();  				 						

									/************Resize and large to small*************/
								$image->resize(95,65);
								$newimg	=	str_replace('cluster-plan','cluster-plan-sm-rect-img',$file);
								$image->save($createFolder."/".$newimg);
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
								
								$image->save(SERVER_PATH."/images/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('const-status','const-status-bkp',$file));
							
								/**********Working for watermark*******************/
						
								$img = new Zubrag_watermark($path);
								$img->ApplyWatermark($watermark_path);
								$img->SaveAsFile($path);
								$img->Free(); 

									/************Resize and large to small*************/
								$image->resize(485,320);
								$newimg	=	str_replace('const-status','const-status-rect-img',$file);
								$image->save($createFolder."/".$newimg);

								/**********Working for watermark*******************/
							// Image path
								$image_path =$createFolder."/".$newimg;

								// Where to save watermarked image
								$imgdestpath = $createFolder."/".$newimg;

								// Watermark image
								$img = new Zubrag_watermark($image_path);
								$img->ApplyWatermark($watermark_path);
								$img->SaveAsFile($imgdestpath);
								$img->Free();  				 						

								/************Resize and large to small*************/
								$image->resize(95,65);
								$newimg	=	str_replace('const-status','const-status-sm-rect-img',$file);
								$image->save($createFolder."/".$newimg);

								/************Resize and large to small*************/
								$image->resize(125,78);
								$newimg	=	str_replace('const-status','const-status-small',$file);
								$image->save($createFolder."/".$newimg);
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
								
								$image->save(SERVER_PATH."/images/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('payment-plan','payment-plan-bkp',$file));
							
								/**********Working for watermark*******************/
						
								$img = new Zubrag_watermark($path);
								$img->ApplyWatermark($watermark_path);
								$img->SaveAsFile($path);
								$img->Free(); 

								
								/**********Working for watermark*******************/
							// Image path
								$image_path =$createFolder."/".$newimg;

								// Where to save watermarked image
								$imgdestpath = $createFolder."/".$newimg;

								// Watermark image
								$img = new Zubrag_watermark($image_path);
								$img->ApplyWatermark($watermark_path);
								$img->SaveAsFile($imgdestpath);
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
								$newimg	=	str_replace('payment-plan','payment-plan-rect-img',$file);
								$image->save($createFolder."/".$newimg);
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
									
									$image->save(SERVER_PATH."/images/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('large','large-bkp',$file));

								/************Resize and large to small*************/
								$image->resize(485,320);
								$newimg	=	str_replace('large','large-rect-img',$file);
								$image->save($createFolder."/".$newimg);
								
									/**********Working for watermark*******************/
									// Image path
										$image_path = $createFolder."/".$file;

										// Where to save watermarked image
										$imgdestpath = $createFolder."/".$file;

									 // Watermark image
									$img = new Zubrag_watermark($image_path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($imgdestpath);
									$img->Free();								
								
								/*********update project table for samall image***********/
									$pathProject	=	"/".$BuilderName."/".strtolower($ProjectName);
									$qry	=	"UPDATE ".PROJECT." SET PROJECT_SMALL_IMAGE = '".$pathProject."/".str_replace('-large.jpg','-small.jpg',$file)."'
												 WHERE PROJECT_ID = '".$projectId."'";	//die("here");
									$res	=	mysql_query($qry);			       									
									
									$image->resize(206,108);
									$newrect	=	str_replace('large','small',$file);
									$image->save($createFolder."/".$newrect);

									/**********Working for watermark*******************/
									// Image path
										$image_path = $createFolder."/".$newimg;

										// Where to save watermarked image
										$imgdestpath = $createFolder."/".$newimg;

									// Watermark image
									$img = new Zubrag_watermark($image_path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($imgdestpath);
									$img->Free();	


								/*************Resize and -large to blank *************/
									/*$image->resize(255,190);
									$newimg_mid	=	str_replace('-large','',$file);
								$image->save($createFolder."/". $newimg_mid);*/
													
							 /**********Working for watermark*******************/
									/*
									// Image path
										$image_path = $createFolder."/". $newimg_mid;

										// Where to save watermarked image
										$imgdestpath = $createFolder."/". $newimg_mid;

									 // Watermark image
									$img = new Zubrag_watermark($image_path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($imgdestpath);
									$img->Free();
									*/

									/************Resize and rect small img*************/
									$image->resize(95,65);
   			 						$newsmrect	=	str_replace('large','large-sm-rect-img',$file);
   									$image->save($createFolder."/".$newsmrect);
   									
									/**********Working for watermark*******************/
									/*
										// Image path
										$image_path = $createFolder."/".$newsmrect;

										// Where to save watermarked image
										$imgdestpath = $createFolder."/".$newsmrect;

									// Watermark image
									$img = new Zubrag_watermark($image_path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($imgdestpath);
									$img->Free();	
									*/
									
								}	
						 }


					 }	
 				}

			header("Location:project_image.php?page=1&sort=all");
		}
	
	} 
}
else if($_POST['btnExit'] == "Exit")
{
      header("Location:project_image.php?page=1&sort=all");
}
 
 $smarty->assign("ErrorMsg", $ErrorMsg);
 /***************Project dropdown*************/
 $Project	=	array();
 	$qry	=	"SELECT ID,PROPERTY_NAME,BUILDER_NAME FROM ".CRAWLER_PROJECT." ORDER BY BUILDER_NAME ASC";
 	$res	=	mysql_query($qry);
 	
 		while ($dataArr = mysql_fetch_array($res))
		 {
			$project=explode(",",$dataArr['PROPERTY_NAME']);
			 $dataArr['PROPERTY_NAME']=$project[0];
			array_push($Project, $dataArr);
		 }
		 $smarty->assign("Project", $Project);
?>

