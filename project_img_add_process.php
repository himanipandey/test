<?php
	set_time_limit(0);
	ini_set("memory_limit","256M");
	include("ftp.new.php");
	$ErrorMsg='';
	//$projectplansid = $_REQUEST['projectplansid'];
	$watermark_path = "images/pt_shadow1.png";
	$projectId = $_REQUEST['projectId'];
	$projectDetail	= ProjectDetail($projectId);
	$smarty->assign("ProjectDetail", $projectDetail);

        $linkShowHide = 0;
        if( isset($_REQUEST['auth']) ) {
            $linkShowHide = 1;
        }
        $smarty->assign("linkShowHide", $linkShowHide);
	$builderDetail = fetch_builderDetail($projectDetail[0]['BUILDER_ID']);
	if(isset($_REQUEST['edit']))
		$edit_project = $projectId;
	else
		$edit_project = $projectId;
	$smarty->assign("edit_project", $edit_project);
	//echo $projectplansid = $edit_project;

	$watermark_path = 'images/pt_shadow1.png';
	$source=array();
	$dest=array();
//$smarty->assign("projectplansid", $projectplansid);
if (isset($_POST['Next']))
{
	$smarty->assign("projectId", $projectId);
	$folderName		=	$projectDetail[0]['PROJECT_NAME'];

	/***********Folder name**********/
	$builderNamebuild		=	explode("/",$builderDetail['BUILDER_IMAGE']);

	/********************************/
	$BuilderName		=	$builderNamebuild[1];
	$ProjectName		=	str_replace(" ","-",$projectDetail[0]['PROJECT_NAME']);

	$arrValue = array();
	$arrTitle = array();
	  	foreach($_FILES['txtlocationplan']['name'] as $k=>$v)
		{
			if($v != '')
			{
				if(!in_array(strtolower($_FILES['txtlocationplan']['type'][$k]), $arrImg))
				{
					$ErrorMsg["ImgError"] = "You can upload only ".ucwords(implode(" / ",$arrImg))." images.";
				}

				foreach($arrType  as $planType=>$imgNamePart)
					{
					if($_REQUEST['PType'] == $planType)
					{
                                            if(!preg_match("/-".$imgNamePart."\.[a-z]{3,4}$/", $v))
                                            {
                                               $ErrorMsg["ImgError"] = "The word ".$imgNamePart." should be part of image name at end.";
                                            }
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
            else if( $_REQUEST['PType'] == '')
	    {
	      $ErrorMsg["ptype"] = "Please select project type.";
	    }
            $smarty->assign("PType", $_REQUEST['PType']);
	if(is_array($ErrorMsg)) {
		// Do Nothing
	}
	else
	{
		$flag=0;
		$projectFolderCreated=0;
		/*******************Update location,site,layout and master plan from db and also from table*********/
			$foldlowe	=	strtolower($BuilderName);
			$newdirlow	=	$newImagePath.$foldlowe;
			if((!is_dir($newdirlow)))
			{
				$lowerdir	=	strtolower($BuilderName);
				$newdir		=	$newImagePath."".$lowerdir;
				mkdir($newdir, 0777);
				$flag=1;
			}
			/****************project folder check**********/
			$newdirpro		=	$newImagePath.$BuilderName."/".$ProjectName;
			$foldname		=	strtolower($ProjectName);
			$andnewdirpro	=	 $newImagePath.$BuilderName."/".$foldname;
			foreach($arrValue as $key=>$val)
			{
				if((!is_dir($newdirpro)) && (!is_dir($andnewdirpro)))
				{
					$lowerpro	=	strtolower($ProjectName);
					$ndirpro		=	$newImagePath.$BuilderName."/".$lowerpro;
					mkdir($ndirpro, 0777);
					$projectFolderCreated=1;
					$createFolder	=	$ndirpro;//die("here");

					$img_path	=	$ndirpro."/".$val;//die("here");
				}
				else
				{
					$img_path		=	$newImagePath.$BuilderName."/".strtolower($ProjectName)."/" . $val;
					$createFolder	=	$newImagePath.$BuilderName."/".strtolower($ProjectName);
				}

				$txtlocationplan 	= move_uploaded_file($_FILES["txtlocationplan"]["tmp_name"][$key], $img_path);
                $s3upload = new S3Upload($s3, $bucket, $img_path, str_replace($newImagePath, "", $img_path));
                $s3upload->upload();

				if(!$txtlocationplan)
					{
					$ErrorMsg["ImgError"] = "Problem in Image Upload Please Try Again.";
					break;
				}
				else
				{

				$source[]= opendir.$BuilderName."/".strtolower($ProjectName)."/" . $val;
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
                                                "image_type" => "location_plan"));
                                            $response = $s3upload->upload();
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
                                                "image_type" => "layout_plan"));
                                            $response = $s3upload->upload();
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
                                                "image_type" => "site_plan"));
                                            $response = $s3upload->upload();
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
                                                "image_type" => "master_plan"));
                                            $response = $s3upload->upload();
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
                                                "image_type" => "cluster_plan"));
                                            $response = $s3upload->upload();
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
                                            "image_type" => "construction_status"));
                                        $response = $s3upload->upload();
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
                                            "image_type" => "payment_plan"));
                                        $response = $s3upload->upload();
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
                                            "image_type" => "specification"));
                                        $response = $s3upload->upload();
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
                                        $imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('price-list','price-list-bkp',$file);
										$image->save($imgdestpath);
                                        $s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                            "image_path" => str_replace($newImagePath, "", $imgdestpath),
                                            "object" => "project", "object_id" => $projectId,
                                            "image_type" => "price_list"));
                                        $response = $s3upload->upload();
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
                                        $imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('app-form','app-form-bkp',$file);
										$image->save($imgdestpath);
                                        $s3upload = new ImageUpload($imgdestpath, array("s3" => $s3,
                                            "image_path" => str_replace($newImagePath, "", $imgdestpath),
                                            "object" => "project", "object_id" => $projectId,
                                            "image_type" => "application_form"));
                                        $response = $s3upload->upload();
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
                                                "image_type" => "project_image"));
                                            $response = $s3upload->upload();
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
							$imgDbPath = explode("/images_new",$img_path);
							$selqry	=	"SELECT PLAN_IMAGE FROM ".PROJECT_PLAN_IMAGES." WHERE PROJECT_ID = '".$projectId."' AND PLAN_TYPE = '".$_REQUEST['PType']."' AND PLAN_IMAGE = '".$imgDbPath[1]."'";
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
                                                        SERVICE_IMAGE_ID   = ".$image_id."
													WHERE PROJECT_ID = '".$projectId."'  AND PLAN_TYPE = '".$_REQUEST['PType']."' AND PLAN_IMAGE = '".$val."'";
								$res	=	mysql_query($qry);
							}
							else
							{
							 	 $qryinsert = "INSERT INTO ".PROJECT_PLAN_IMAGES."
												SET PLAN_IMAGE		=	'".$imgDbPath[1]."',
													PROJECT_ID		=	'".$projectId."',
													PLAN_TYPE		=	'".$_REQUEST['PType']."',
													    BUILDER_ID		=	'".$builderDetail['BUILDER_ID']."',
													SERVICE_IMAGE_ID        =    ".$image_id.",
													TITLE			=	'".$arrTitle[$key]."',
													SUBMITTED_DATE	=	now()";
								$resinsert	=	mysql_query($qryinsert);
							}
					if($flag==1)
					{
						$builderfolder=strtolower($BuilderName);
						$destBuilderFolder = '';
						$sourceBuilderFolder = "public_html/images_new/$builderfolder";
						$result = upload_file_to_img_server_using_ftp($sourceBuilderFolder,$destBuilderFolder,4);

					}
					if($projectFolderCreated==1)
					{
						$builderfolder=strtolower($BuilderName);
						$projectNameFolder=strtolower($ProjectName);
						$destProjectFolder = '';
						$sourceProjectFolder = "public_html/images_new/$builderfolder/$projectNameFolder";
						$result = upload_file_to_img_server_using_ftp($sourceProjectFolder,$destProjectFolder,4);

					}

					$result = upload_file_to_img_server_using_ftp($source,$dest,1);
			if($_POST['Next'] == 'Add More')
				header("Location:project_img_add.php?projectId=".$projectId);
			else if($_POST['Next'] == 'Save')
				header("Location:ProjectList.php?projectId=".$projectId);
			else
				header("Location:add_specification.php?projectId=".$projectId);
				}
		}

	}
}
else if(isset($_POST['Skip']))
{
      header("Location:add_specification.php?projectId=".$projectId);
}
else if(isset($_POST['exit']))
{
	 header("Location:ProjectList.php?projectId=".$projectId);
}


 $smarty->assign("ErrorMsg", $ErrorMsg);
 /***************Project dropdown*************/
 $Project	=	array();
 	$qry	=	"SELECT PROJECT_ID,PROJECT_NAME,BUILDER_NAME FROM ".RESI_PROJECT." ORDER BY BUILDER_NAME ASC";
 	$res	=	mysql_query($qry);

 		while ($dataArr = mysql_fetch_array($res))
		 {
			array_push($Project, $dataArr);
		 }
		 $smarty->assign("Project", $Project);
?>
