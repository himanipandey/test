<?php
set_time_limit(0);
ini_set("memory_limit","256M");
include("../../dbConfig.php");

$projectplansid = $_REQUEST['projectplansid'];
$watermark_path = OFFLINE_PROJECT_INTERNET_PATH.'images/watermark/pt_shadow1.png';


$smarty->assign("projectplansid", $projectplansid);

if ($_POST['btnSave'] == "Save") 
{
  

 
	$projectListingId			=	trim($_POST['projectId']);
	$projectId			=	trim($_POST['proptigerID']);
	
	$newFloorArray1 = array();
	$newFloorImagePathArray1 = array();
	$newFloorArray2=array();
	$smarty->assign("projectId", $projectId);
	
	
			/***********Folder name**********/
			$qry			=	"SELECT PROJECT_NAME,BUILDER_NAME,BUILDER_ID FROM ".PROJECT." WHERE PROJECT_ID = '".$projectId."'";
			$res			=	mysql_query($qry,$db);
			$data			=	mysql_fetch_array($res);
			$folderName		=	$data['PROPERTY_NAME'];
			
			/***********Folder name**********/
			$qrybuild			=	"SELECT BUILDER_IMAGE FROM ".RESI_BUILDER." WHERE BUILDER_ID = '".$data['BUILDER_ID']."'";
			$resbuild			=	mysql_query($qrybuild,$db);
			$databuild			=	mysql_fetch_array($resbuild);
			$builderNamebuild		=	explode("/",$databuild['BUILDER_IMAGE']);
		
			/********************************/		
			$BuilderName		=	$builderNamebuild[1];
			 $ProjectName		=	str_replace(" ","-",$data['PROJECT_NAME']);	


		
	
	
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
			$cnt = 0;

			//CREATED FOLDER
			$createFolder	=	OFFLINE_PROJECT_IMAGE_SAVE_PATH.$BuilderName."/".strtolower($ProjectName);


			
			
			foreach($_REQUEST['property_type'] as $key=>$val)
			{
				
							$projectType=$val;

							
							if($_REQUEST['projectTypeId'][$key]!='')
							{
								$newFloorArray2[] =$_REQUEST['projectTypeId'][$key];

							}
							
							if($projectType=='Project Image' || $projectType=='Master Plan' || $projectType=='Location Plan' || $projectType=='Layout Plan' || $projectType=='Cluster Plan' || $projectType=='Site Plan' )
							{
								$planType=$projectType;
								$projectImage=	$_REQUEST['property_image_path'][$key];
								$propertyImage =explode('/',$projectImage);
								$imageExt=$propertyImage[2];

								$path = $imageExt;
								$path = explode('.',$path);
								$path = end($path);


								$extension_pos = strrpos($imageExt, '.'); // find position of the last dot, so where the extension starts

								if($planType=='Project Image')
								{
									$newImageName = substr($imageExt, 0, $extension_pos) . '-large' . substr($filename, $extension_pos);
								}
								else if($planType=='Location Plan')
								{
									$newImageName = substr($imageExt, 0, $extension_pos) . '-loc-plan' . substr($filename, $extension_pos);

								}

								else if($planType=='Master Plan')
								{
									$newImageName = substr($imageExt, 0, $extension_pos) . '-master-plan' . substr($filename, $extension_pos);

								}
								else if($planType=='Layout Plan')
								{
									$newImageName = substr($imageExt, 0, $extension_pos) . '-layout-plan' . substr($filename, $extension_pos);

								}
								else if($planType=='Cluster Plan')
								{
									$newImageName = substr($imageExt, 0, $extension_pos) . '-cluster-plan' . substr($filename, $extension_pos);

								}
								else if($planType=='Site Plan')
								{
									$newImageName = substr($imageExt, 0, $extension_pos) . '-site-plan' . substr($filename, $extension_pos);

								}

								$newImageName = $newImageName.".".$path;

								



								
								
							$img_path2	=  OFFLINE_PROJECT_IMAGE_SAVE_PATH.$BuilderName."/".strtolower($ProjectName)."/".$newImageName ;
							$img_path	= "/".$BuilderName."/".strtolower($ProjectName)."/".$newImageName ;
							$img_pathchk	= "../images/".$BuilderName."/".strtolower($ProjectName)."/".$newImageName ;
								
							$offlineImagePath=OFFLINE_PROJECT_IMAGE_PATH.$projectImage;

							
								
							copy($offlineImagePath,$img_path2);


/*******************************************************************************Resizing and WaterMarking Project Image***************************************/								
							if($projectType=='Project Image')
							{
					
							if ($handle = opendir($createFolder))
 							{
								rewinddir($handle);
							while (false !== ($file = readdir($handle)))
							{
								 $file=$img_path2;
								

								/***********master plan start here**************************/


									$image = new SimpleImage();

									
									$path=$file;
									$image->load($path);
									
								
									
										$image->save(str_replace('large','large-bkp',$file));
										//$image->save(SERVER_PATH."/images/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('large','large-bkp',$file));



									$image->resize(485,320);
									$newimg	=	str_replace('large','large-rect-img',$file);
									$image->save($newimg);
								
									/**********Working for watermark*******************/
							
									$img = new Zubrag_watermark($path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($path);
									$img->Free(); 
										
										
									//$pathProject	=	"/".$BuilderName."/".strtolower($ProjectName);
									
									$pathProject	=	"/".$BuilderName."/".strtolower($ProjectName);

									

								
									$qry	=	"UPDATE ".PROJECT." SET PROJECT_SMALL_IMAGE = '".$pathProject."/".str_replace('-large.jpg','-small.jpg',$newImageName)."'
												 WHERE PROJECT_ID = '".$projectId."'";	//die("here");

								
									$res	=	mysql_query($qry);	
									
									$image->resize(206,108);
									$newrect	=	str_replace('large','small',$file);
									$image->save($newrect);






									/************Resize and large to small*************/
									$image->resize(485,320);
									$newimg	=	str_replace('master-plan','master-plan-rect-img',$file);
									$newimg	=	str_replace('large','large-rect-img',$file);
									$image->save($newimg);

								// Image path
										$image_path = $newimg;

										// Where to save watermarked image
										$imgdestpath = $newimg;

									// Watermark image
									$img = new Zubrag_watermark($image_path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($imgdestpath);
									$img->Free();	


									/************Resize and rect small img*************/
									$image->resize(95,65);
   			 						$newsmrect	=	str_replace('large','large-sm-rect-img',$file);
   									$image->save($newsmrect);


								  }
								}
							}

/***********************************************Project Image Resizing and watermarking ends here*****************************************************************/








/*******************************************************************************Resizing and WaterMarking Master Plan Images***************************************/								
							if($projectType=='Master Plan')
							{
					
							if ($handle = opendir($createFolder))
 							{
								rewinddir($handle);
							while (false !== ($file = readdir($handle)))
							{
								 $file=$img_path2;
								

								/***********master plan start here**************************/


									$image = new SimpleImage();

									
									$path=$file;
									$image->load($path);
									
								
									
										$image->save(str_replace('master-plan','master-plan-bkp',$file));
								
									/**********Working for watermark*******************/
							
									$img = new Zubrag_watermark($path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($path);
									$img->Free(); 

									/************Resize and large to small*************/
									$image->resize(485,320);
									$newimg	=	str_replace('master-plan','master-plan-rect-img',$file);
									$image->save($newimg);

									/**********Working for watermark*******************/
								// Image path
									$image_path = $newimg;
								
									// Where to save watermarked image
									$imgdestpath = $newimg;
						// Watermark image
									$img = new Zubrag_watermark($image_path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($imgdestpath);
									$img->Free();  				 						

									/************Resize and large to small*************/
								$image->resize(95,65);
								$newimg	=	str_replace('master-plan','master-plan-sm-rect-img',$file);
								$image->save($newimg);

							/***********master plan end here***************************/


								  }
								}
							}

/***********************************************Master Plan Resizing and watermarking ends here*****************************************************************/





/*******************************************************************************Resizing and WaterMarking Layout Plan Images***************************************/								
							if($projectType=='Layout Plan')
							{
					
							if ($handle = opendir($createFolder))
 							{
								rewinddir($handle);
							while (false !== ($file = readdir($handle)))
							{
								 $file=$img_path2;
								

								/***********master plan start here**************************/


									$image = new SimpleImage();

									
									$path=$file;
									$image->load($path);
									
								
									
										$image->save(str_replace('layout-plan','layout-plan-bkp',$file));
								
									/**********Working for watermark*******************/
							
									$img = new Zubrag_watermark($path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($path);
									$img->Free(); 

									/************Resize and large to small*************/
									$image->resize(485,320);
									$newimg	=	str_replace('layout-plan','layout-plan-rect-img',$file);
									$image->save($newimg);

									/**********Working for watermark*******************/
								// Image path
									$image_path = $newimg;
								
									// Where to save watermarked image
									$imgdestpath = $newimg;
						// Watermark image
									$img = new Zubrag_watermark($image_path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($imgdestpath);
									$img->Free();  				 						

									/************Resize and large to small*************/
								$image->resize(95,65);
								$newimg	=	str_replace('layout-plan','layout-plan-sm-rect-img',$file);
								$image->save($newimg);

							/***********master plan end here***************************/


								  }
								}
							}

/***********************************************Layout Plan Resizing and watermarking ends here*****************************************************************/



/*******************************************************************************Resizing and WaterMarking Site Plan Images***************************************/								
							if($projectType=='Site Plan')
							{
					
							if ($handle = opendir($createFolder))
 							{
								rewinddir($handle);
							while (false !== ($file = readdir($handle)))
							{
								 $file=$img_path2;
								

								/***********master plan start here**************************/


									$image = new SimpleImage();

									
									$path=$file;
									$image->load($path);
									
								
									
										$image->save(str_replace('site-plan','site-plan-bkp',$file));
								
									/**********Working for watermark*******************/
							
									$img = new Zubrag_watermark($path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($path);
									$img->Free(); 

									/************Resize and large to small*************/
									$image->resize(485,320);
									$newimg	=	str_replace('site-plan','site-plan-rect-img',$file);
									$image->save($newimg);

									/**********Working for watermark*******************/
								// Image path
									$image_path = $newimg;
								
									// Where to save watermarked image
									$imgdestpath = $newimg;
						// Watermark image
									$img = new Zubrag_watermark($image_path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($imgdestpath);
									$img->Free();  				 						

									/************Resize and large to small*************/
								$image->resize(95,65);
								$newimg	=	str_replace('site-plan','site-plan-sm-rect-img',$file);
								$image->save($newimg);

							/***********master plan end here***************************/


								  }
								}
							}

/***********************************************Site Plan Resizing and watermarking ends here*****************************************************************/




/*******************************************************************************Resizing and WaterMarking Cluster Plan Images***************************************/								
							if($projectType=='Cluster Plan')
							{
					
							if ($handle = opendir($createFolder))
 							{
								rewinddir($handle);
							while (false !== ($file = readdir($handle)))
							{
								 $file=$img_path2;
								

								/***********master plan start here**************************/


									$image = new SimpleImage();

									
									$path=$file;
									$image->load($path);
									
								
									
										$image->save(str_replace('cluster-plan','cluster-plan-bkp',$file));
								
									/**********Working for watermark*******************/
							
									$img = new Zubrag_watermark($path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($path);
									$img->Free(); 

									/************Resize and large to small*************/
									$image->resize(485,320);
									$newimg	=	str_replace('cluster-plan','cluster-plan-rect-img',$file);
									$image->save($newimg);

									/**********Working for watermark*******************/
								// Image path
									$image_path = $newimg;
								
									// Where to save watermarked image
									$imgdestpath = $newimg;
						// Watermark image
									$img = new Zubrag_watermark($image_path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($imgdestpath);
									$img->Free();  				 						

									/************Resize and large to small*************/
								$image->resize(95,65);
								$newimg	=	str_replace('cluster-plan','cluster-plan-sm-rect-img',$file);
								$image->save($newimg);

							/***********master plan end here***************************/


								  }
								}
							}

/***********************************************Cluster Plan Resizing and watermarking ends here*****************************************************************/





/***********************************************Resizing and WaterMarking Location Plan Images****************************************************************/								
							if($projectType=='Location Plan')
							{
					
							if ($handle = opendir($createFolder))
 							{
								rewinddir($handle);
							while (false !== ($file = readdir($handle)))
							{
								 $file=$img_path2;
								

								/***********master plan start here**************************/


									$image = new SimpleImage();

									
									$path=$file;
									$image->load($path);
									
								
									
										$image->save(str_replace('loc-plan','loc-plan-bkp',$file));
										//$image->save(SERVER_PATH."/images/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('loc-plan','loc-plan-bkp',$file));
								
									/**********Working for watermark*******************/
							
									$img = new Zubrag_watermark($path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($path);
									$img->Free(); 

									/************Resize and large to small*************/
									$image->resize(485,320);

									//$newimg	=	str_replace('master-plan','master-plan-rect-img',$file);
									$newimg	=	str_replace('loc-plan','loc-plan-rect-img',$file);
									$image->save($newimg);

									/**********Working for watermark*******************/
								// Image path
									$image_path = $newimg;
								
									// Where to save watermarked image
									$imgdestpath = $newimg;
						// Watermark image
									$img = new Zubrag_watermark($image_path);
									$img->ApplyWatermark($watermark_path);
									$img->SaveAsFile($imgdestpath);
									$img->Free();  				 						

									/************Resize and large to small*************/
								$image->resize(95,65);
								//$newimg	=	str_replace('master-plan','master-plan-sm-rect-img',$file);
								$newimg	=	str_replace('loc-plan','loc-plan-sm-rect-img',$file);
								$image->save($newimg);

							/***********master plan end here***************************/


								  }
								}
							}

/********************************************************************************************************************************************************/







								
								$plantitle=$_REQUEST['property_title'][$key];

								 $qryinsert = "INSERT INTO ".PROJECT_PLAN_IMAGES."
												SET PLAN_IMAGE		=	'".str_replace("..//images/","/",$img_path)."',
													PROJECT_ID		=	'".$projectId."',
													PLAN_TYPE		=	'".$planType."',
													BUILDER_ID		=	'".$data['BUILDER_ID']."',
													TITLE			=	'".$plantitle."',
													SUBMITTED_BY	=	'".$_SESSION['adminId']."',
													SUBMITTED_DATE	=	now()";
								$resinsert	=	mysql_query($qryinsert);

								


							}
							if($projectType=='Floor Plan')
							{
								
								$planType=$projectType;
								$projectImage=	$_REQUEST['property_image_path'][$key];
								$propertyImage =explode('/',$projectImage);
								$imageExt2=$propertyImage[2];

								$path2 = $imageExt2;
								$path2 = explode('.',$path2);
								$path2 = end($path2);


								$extension_pos2 = strrpos($imageExt2, '.'); // find position of the last dot, so where the extension starts

								if($planType=='Floor Plan')
								{
									$newImageName2 = substr($imageExt2, 0, $extension_pos2) . '-floor-plan' . substr($filename, $extension_pos2);
								}
								
								$newImageName2 = $newImageName2.".".$path2;



								$img_path	= "/".$BuilderName."/".strtolower($ProjectName)."/".$newImageName2 ;
								$img_path2	=  OFFLINE_PROJECT_IMAGE_SAVE_PATH.$BuilderName."/".strtolower($ProjectName)."/".$newImageName2 ;
								 $offlineImagePath=OFFLINE_PROJECT_IMAGE_PATH.$projectImage;


								 /***********************************************************************FOR PROJECT IMAGE********************************************************************************/
									$newFloorArray1[]=$_REQUEST['property_title'][$key];
									$newFloorImagePathArray1[]=$img_path;
									
									

						



				copy($offlineImagePath,$img_path2);







/*********************************************************************************floor plan resize and watermark*******************************************/
					if($projectType=='Floor Plan')
							{
					
							if ($handle = opendir($createFolder))
 							{
								rewinddir($handle);
									while (false !== ($file2 = readdir($handle)))
									{
										 $file2=$img_path2;
										

										/***********master plan start here**************************/


											$image = new SimpleImage();
											$path2=$file2;

											$path2;
											//die("dd");
											$image->load($path2);
											
												$image->save(str_replace('floor-plan','floor-plan-bkp',$file2));

												//$image->save(SERVER_PATH."/images/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('floor-plan','floor-plan-bkp',$file));
										
											/**********Working for watermark*******************/
									
											$img = new Zubrag_watermark($path2);
											$img->ApplyWatermark($watermark_path);
											$img->SaveAsFile($path2);
											$img->Free(); 


											
											/************Resize and large to small*************/
											$image->resize(485,320);
											$newimg	=	str_replace('floor-plan','floor-plan-rect-img',$file2);
											$image->save($newimg2);

											/**********Working for watermark*******************/
										// Image path
											$image_path2 = $newimg2;

											// Where to save watermarked image
											$imgdestpath2 = $newimg2;

										// Watermark image
														$img = new Zubrag_watermark($image_path2);
														$img->ApplyWatermark($watermark_path);
														$img->SaveAsFile($imgdestpath2);
														$img->Free();  				 						

														/************Resize and large to small*************/
														$image->resize(95,65);
														$newimg	=	str_replace('floor-plan','floor-plan-sm-rect-img',$file2);
														$image->save($newimg2);

														/************Resize and large to small*************/
														$image->resize(80,36);
														$newimg	=	str_replace('floor-plan','floor-plan-small',$file2);
														$image->save($newimg2);



									/***********master plan end here***************************/


										  }
								}
						    }






/************************************************************************************************************************************************************************/

								
								
							
								
								
								

								
								$cnt++;

							}	



		}

	

						if(!empty($newFloorArray1))
						{

							foreach($newFloorArray1 as $key=>$val)
							{
												$plantitle=$val;
												 $typeID=$newFloorArray2[$key];

												 $qryinsert = "INSERT INTO ".FLOOR_PLANS."
																SET TYPE_ID		=	'".$typeID."',
																	NAME		=	'View Floor Plan',
																	IMAGE_URL		=	'".$newFloorImagePathArray1[$key]."',
																	DISPLAY_ORDER		=	'".($key+1)."'
																	";
													$resinsert	=	mysql_query($qryinsert);

							}

						}

						if(isset($_REQUEST['proptiger_floorplanId']))
						{
							foreach($_REQUEST['proptiger_floorplanId'] as $key=>$val)
							{
									
									$Sql 		= "DELETE FROM ".FLOOR_PLANS." WHERE FLOOR_PLAN_ID  = '".$val."'";
									$ExecSql 	= mysql_query($Sql);

							}

						}

						if(isset($_REQUEST['proptiger_planId']))
						{
							foreach($_REQUEST['proptiger_planId'] as $key=>$val)
							{
									
									$Sql 		= "DELETE FROM ".PROJECT_PLAN_IMAGES." WHERE PROJECT_PLAN_ID  = '".$val."'";
									$ExecSql 	= mysql_query($Sql);

							}

						}


						

						 $Sql = "UPDATE ".CRAWLER_PROJECT." SET
						IMAGE_UPLOADED_STATUS  	      	= '1'
						
						where       ID  =  '".$projectListingId."'";
					$ExecSql = mysql_query($Sql) or die(mysql_error().' Error in function UpdateAdmin()');

//die("www");
			header("Location:projectplansadd.php");
		
	

	} 
}

 $smarty->assign("ErrorMsg", $ErrorMsg);
 /***************Project dropdown*************/
 $Project	=	array();
 	$qry	=	"SELECT CP.ID,CP.PROPTIGER_PROJECT_ID,CP.PROPERTY_NAME,CP.BUILDER_NAME FROM ".CRAWLER_PROJECT." AS CP ,".PROJECT." AS P WHERE CP.IMAGE_UPLOADED_STATUS='0' AND  P.PROJECT_ID =CP.PROPTIGER_PROJECT_ID ORDER BY BUILDER_NAME ASC";
 	$res	=	mysql_query($qry);


 	
 		while ($dataArr = mysql_fetch_array($res))
		 {
			$project=explode(",",$dataArr['PROPERTY_NAME']);
			 $dataArr['PROPERTY_NAME']=$project[0];
			array_push($Project, $dataArr);
		 }
		 $smarty->assign("Project", $Project);
?>
