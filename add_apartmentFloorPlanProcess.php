<?php
	set_time_limit(0);
	ini_set("memory_limit","256M");
	include("ftp.new.php");
	$watermark_path = 'images/pt_shadow1.png';
	$projectId				=	$_REQUEST['projectId'];
	$projectDetail			=	ProjectDetail($projectId);
	$builderDetail			=	fetch_builderDetail($projectDetail[0]['BUILDER_ID']);
	$ProjectOptionDetail	=	ProjectOptionDetail($projectId);

	$smarty->assign("projectId", $projectId);
	$smarty->assign("ProjectOptionDetail",$ProjectOptionDetail);
	$smarty->assign("ProjectDetail", $projectDetail);
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
	   if($optionId == '') 
	   {
		$flgins	=	0;
		foreach($_REQUEST['floor_name'] AS $key=>$val)
		{
			
			if($val != '')
				$flgins	=	1;	
			if($_REQUEST['floor_name'][$key] != '')
			{
	   
				if(!in_array(strtolower($_FILES["imgurl"]["type"][$key]), $arrImg))
				{
					$ErrorMsg1 = "You can upload only jpg / jpeg gif png images.";
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
							if(!strstr($_FILES["imgurl"]["name"][$key],'floor-plan'))
							{
								 $flgimg	=	1;
							}
							if($flrplan != '')
							{
								$txtlocationplan 	= move_uploaded_file($_FILES["imgurl"]["tmp_name"][$key], "".$createFolder."/" . $imgurl1);

								if(!$txtlocationplan)
								{
									$ErrorMsg1 = "Problem in Image Upload Please Try Again.";
									break;
								}
								else
								{
								$source[]			=	$newImagePath.$BuilderName."/".strtolower($ProjectName)."/" .  $_FILES["imgurl"]["name"][$key];
								$dest[]				=	$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". $_FILES["imgurl"]["name"][$key];
								$imgurl8 			= $projecttbl."/".$imgurl1;
									
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
												
											$image->save($newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('floor-plan','floor-plan-bkp',$file));
											$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/" .str_replace('floor-plan','floor-plan-bkp',$file);
											$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('floor-plan','floor-plan-bkp',$file);
												/**********Working for watermark*******************/
												$image_path = $path;
												// Where to save watermarked image
												$imgdestpath = $path;
												// Watermark image
												$img = new Zubrag_watermark($image_path);
												$img->ApplyWatermark($watermark_path);
												$img->SaveAsFile($imgdestpath);
												$img->Free();  				 						

												/************Resize and rect img*************/
												$image->resize(485,320);
												$newrect	=	str_replace('floor-plan','floor-plan-rect-img',$file);
												$image->save($createFolder."/".$newrect);

												$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newrect;
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
												$img->Free();  				 						

												/************Resize and large to small*************/
												$image->resize(95,65);
												$newimg	=	str_replace('floor-plan','floor-plan-sm-rect-img',$file);
												$image->save($createFolder."/".$newimg);
												$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
												$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

												/************Resize and large to small*************/
												$image->resize(80,36);
												$newimg	=	str_replace('floor-plan','floor-plan-small',$file);
												$image->save($createFolder."/".$newimg);
												$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
												$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

												/************Resize and large to thumb*************/
												$image->resize(77,70);
												$newimg	=	str_replace('floor-plan','floor-plan-thumb',$file);
												$image->save($createFolder."/".$newimg);
												$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
												$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
											}		
										}
									} 
								}
									 $insertlist.=	 "('$option_id', '$floor_name','$imgurl8','1'),";
							}
							}
								
						}
					}
						/*********************end code for floor plan add************************/
				}
				else
				{
					if ($_FILES["imgurl"]["type"][$key])
					{
						 $ErrorMsg1	=	'You can not enter image without floor name';
					}
				}
			}
		}
		
		if($flgins == 0)
		{
			 $ErrorMsg1	=	'Please select atleast one floor plan name';
		}
		if($ErrorMsg1 == '' AND $insertlist != '')
		{
			
			$qry	 =  "INSERT INTO ".RESI_FLOOR_PLANS." (OPTION_ID,NAME,IMAGE_URL,DISPLAY_ORDER) VALUES ";
			$str	 = $qry.$insertlist;
			$fullQry =  substr($str,0,-1);
			$res	 =	mysql_query($fullQry) or die(mysql_error());
			$lastid  =  mysql_insert_id();	
			if($res)
			{
				audit_insert($lastid,'insert','resi_floor_plans',$projectId);

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

	$smarty->assign("ErrorMsg1", $ErrorMsg1);

?>
