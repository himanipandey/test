<?php
	set_time_limit(0);
	ini_set("memory_limit","256M");
	include("ftp.new.php");
	$ErrorMsg='';
	$image_id=0;
	$postArr = array(); // array to store image data to send with http request
	$fileEndName = array();
	$watermark_path = "images/pt_shadow1.png";
	 $projectId = $_GET['projectId'];
	$projectDetail	= ProjectDetail($projectId);
	$smarty->assign("ProjectDetail", $projectDetail);
	//$ImageDataListingArr = allProjectImages($projectId);
	//get image path from image service
	$objectType = "project";
	$ImageDataListingArr = array(); //Image data from Image service
	
    $objectId = $projectId;
    
    $url = ImageServiceUpload::$image_upload_url."?objectType=$objectType&objectId=".$objectId;
    $content = file_get_contents($url);
    $imgPath = json_decode($content);
   
 

    foreach($imgPath->data as $k=>$v){
    	
	    	$data = array();
	        $data['SERVICE_IMAGE_ID'] = $v->id;
	        $data['objectType'] = $v->imageType->objectType->type;
	        $data['objectId'] = $v->objectId; 
	        
	        
	        $arr = preg_split('/(?=[A-Z])/',$v->imageType->type);
	        $str = ucfirst (implode(" ",$arr));
	        if($str=='Main' || $str=='Amenities' || $str=='Main Other')
	        	$data['PLAN_TYPE_MAIN'] = "Project Image";
	        
	        if($str=='Main')
	        	$data['PLAN_TYPE'] = "Elevation";
	        else
	       	 $data['PLAN_TYPE'] = $str;
	         
	        if ($data['PLAN_TYPE_MAIN']=="Project Image" && ($v->priority==0 || $v->priority==null) )
	        	$data['display_order'] = 5;
	        else
	        	$data['display_order'] = $v->priority;
	        $data['TITLE'] = $v->title;
	        $data['IMAGE_DESCRIPTION'] = $v->description;
	        $data['SERVICE_IMAGE_ID'] = $v->id;
	        $data['SERVICE_IMAGE_PATH'] = $v->absolutePath;
	      
	        if(isset($v->takenAt)){
	        	$t = $v->takenAt/1000;
				$data['tagged_month'] =  date("Y-m-d", $t);
		    }
		   
		    
	        $str = trim(trim($v->jsonDump, '{'), '}');
	        $towerarr = explode(":", $str);
	        if(trim($towerarr[1],"\"") == "null")
	        	$data['tower_id']==null;
	        else
	       		$data['tower_id'] = (int)trim($towerarr[1],"\"");
	       //var_dump($data['tower_id']);
	        $data['PROJECT_ID'] = $v->objectId;
	        $data['STATUS'] = $v->active;
	       $data['alt_text'] = $v->altText;
	        array_push($ImageDataListingArr, $data);
    	
    }
    	
	


	$builderDetail	= fetch_builderDetail($projectDetail[0]['BUILDER_ID']);
//print'<pre>';
//print_r($ImageDataListingArr);
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
					 
	$towerDetail_object	=	ResiProjectTowerDetails::find("all", array("conditions" => "project_id = {$projectId}"));
    $towerDetail        =   array();
    foreach($towerDetail_object as $s){
        $s = $s->to_array();
        foreach($s as $key=>$value){
            $s[strtoupper($key)] = $value;
            unset($s[$key]);
        }
            array_push($towerDetail, $s);
    }    
     $smarty->assign("towerDetail", $towerDetail);
      
    //display order
    $display_order_div = array();
    for($cmt=1;$cmt<=5;$cmt++){
		$display_order_div[$cmt] =  $cmt ;
    }
    //print_r($_REQUEST['chk_name']);
    $smarty->assign("display_order_div", $display_order_div);
   
			 
		/*if( isset($_REQUEST['title']) &&  !array_filter($_REQUEST['title'], empty_test) )
	    {
	      $ErrorMsg["title"] = "Please enter Image Title.";
	    }*/
  	if(isset($_POST['btnSave']) && empty($_REQUEST['chk_name']) )
	{
	  $ErrorMsg["checkbox"] = "Please select edit or delete action.";
	}
	//checking for display orders unqueness
//	print'<pre>';
//print_r($_REQUEST);die();
	foreach($_REQUEST['chk_name'] as $k=>$v)
	{
		if($v != '')
		{
			//$temp_arr = array(); 

			if($v == 'edit_img'){
				$itype = $_REQUEST['PType'][$k];
				if( $itype == 'Elevation' || $itype == 'Amenities' || $itype == 'Main Other'){
					if(!array_key_exists($_REQUEST['PType'][$k], $temp_arr))
						$temp_arr[$_REQUEST['PType'][$k]] = array();
					if(trim($_REQUEST['txtdisplay_order'][$k]) == ''){
						$ErrorMsg["display_order"] = "Please enter Display Order."; 
					}
					else{
									
					  	if(array_key_exists($_REQUEST['txtdisplay_order'][$k], $temp_arr[$_REQUEST['PType'][$k]])){
						  $ErrorMsg["display_order"] = "Display order for an Image Type must be unique.";				  
					  	}
					  	else {//checking duplicacy
							$ext_vlinks = checkDuplicateDisplayOrder($projectId, $_REQUEST['txtdisplay_order'][$k],$_REQUEST['PType'][$k], $_REQUEST['service_image_id'][$k]);

							if($ext_vlinks){
								 $ErrorMsg["display_order"] = "Display order '".$_REQUEST['txtdisplay_order'][$k]."' already exist for Image Type: ".$_REQUEST['PType'][$k]." .";

							}
					  	}
					  	if($_REQUEST['txtdisplay_order'][$k] != 5)
						$temp_arr[$_REQUEST['PType'][$k]][$_REQUEST['txtdisplay_order'][$k]] = $_REQUEST['txtdisplay_order'][$k];
					 	
					}
				}
				if(trim($_REQUEST['title'][$k]) == ''){
					$ErrorMsg["ptype"] = "Please enter Image Title."; 
				}
				if($_REQUEST['PType'][$k] == "Construction Status" && trim($_REQUEST['img_date'.$k]) =='')
						$ErrorMsg["tagged_date"] = "Please select Tagged Date."; 
					
				if($_REQUEST['PType'][$k] == 'Cluster Plan'){

					if($_REQUEST['txtTowerId'][$k] == "" || $_REQUEST['txtTowerId'][$k] < 0){
						$ErrorMsg["cluster"] = "Please select a Tower for every Cluster Plan.";
						
					}
				}


			}

		}
		
	} 
	if( $projectId == '') 
	{
	  $ErrorMsg["projectId"] = "Please select Project name.";
	}

	


	$source=array();
	$dest=array();
	 /*********edit images code start here*******************/
	if (isset($_POST['btnSave']) && !is_array($ErrorMsg)) 
	{
			
		$image_update_flag = 0;
		$smarty->assign("projectId", $projectId);		
		$folderName = $projectDetail[0]['PROJECT_NAME'];
		
		/***********Folder name**********/
		$builderNamebuild = explode("/",$builderDetail['BUILDER_IMAGE']);

		/********************************/		
		$BuilderName = $builderNamebuild[1];
		$ProjectName = str_replace(" ","-",$projectDetail[0]['PROJECT_NAME']);	 
		
		$arrValue = array();
		$arrTitle = array();
		$arrTaggedDate = array();
		$arrTowerId = array();
		$arrDisplayOrder = array();
		//print("<pre>");
		//print_r($_REQUEST);//die();

		
		foreach($_REQUEST['chk_name'] as $k=>$v)
		{ 

			if($v != '')
			{ 
				if($v == 'delete_img'){
					/********delete image from db if checked but not browes new image*********/
                    $service_image_id = $_REQUEST['service_image_id'][$k];
                   	$params = array(
                        "service_image_id" => $service_image_id,
                        "delete" => "yes",
                    );
					$unitImageArr = array();
					$unitImageArr['params'] = $params;
					$unitImageArr['img'] = "";
	        		$unitImageArr['objectId'] = $projectId;
	        		$unitImageArr['objectType'] = "project";
	        		$unitImageArr['newImagePath'] = $newImagePath;
                    $postArr[$k] = $unitImageArr;
                    /*$deleteVal = deleteFromImageService("project", $projectId, $service_image_id);
					$qry	=	"DELETE FROM ".PROJECT_PLAN_IMAGES." 
                                                                 WHERE 
                                                                       PROJECT_ID = '".$projectId."'
                                                                       AND PLAN_TYPE = '".$_REQUEST['PType'][$k]."'
                                                                       AND SERVICE_IMAGE_ID = '".$service_image_id."'";
					$res	=	mysql_query($qry);*/		
												
				}
				else if($v == 'edit_img')
				{
										
					//////////////////////////////////
						$arrTitle[$k] = $_REQUEST['title'][$k];
						
						if(isset($_REQUEST['img_date'.$k]) && !null == $_REQUEST['img_date'.$k]){
							$tagged_date = substr($_REQUEST['img_date'.$k],0,7);
							$arrTaggedDate[$k] = $tagged_date."-01T00:00:00Z";
						}
						else	$arrTaggedDate[$k] = NULL; //"0000-00-00T00:00:00Z"
						if( $_REQUEST['txtTowerId'][$k]=="")
							$arrTowerId[$k] = null;
						else
							$arrTowerId[$k] = $_REQUEST['txtTowerId'][$k];
						$arrDisplayOrder[$k] = $_REQUEST['txtdisplay_order'][$k];
						$service_image_id = $_REQUEST["service_image_id"][$k];
					

						if($_REQUEST['PType'][$k]=="Construction Status" || $_REQUEST['PType'][$k]=="Cluster Plan")
							$jsonDump = array(
	                        	"tower_id" => $arrTowerId[$k],
	                        );
						else $jsonDump = null;
						if($_REQUEST['PType'][$k]=="Construction Status" )
							$taggedDate = $arrTaggedDate[$k];
						else
							$taggedDate = null;

					if($_FILES['img']['name'][$k] == ''){
						$params = array(
	                        "image" => $file,
	                        "priority" => $arrDisplayOrder[$k],
	                        "title" => $arrTitle[$k],
	                        "service_image_id" => $service_image_id,
	                        "tagged_date" => $taggedDate,
	                        "update" => "update",
	                        "jsonDump" => $jsonDump
	                    );
						$unitImageArr = array();
						$unitImageArr['params'] = $params;
						$unitImageArr['img'] = "";
		        		$unitImageArr['objectId'] = $projectId;
		        		$unitImageArr['objectType'] = "project";
		        		$unitImageArr['newImagePath'] = $newImagePath;
	                    $postArr[$k] = $unitImageArr;
	                    //  add images to image service

	                    
	                   /* $returnArr = writeToImageService(  "", "project", $projectId, $params, $newImagePath);
	                    //print_r($returnArr);
	                    $serviceResponse = $returnArr['serviceResponse'];
		                if($serviceResponse){
		                    $image_id = $serviceResponse["service"]->response_body->data->id;
							$add_tower = '';//die();
										
							if(is_numeric($arrTowerId[$k])){
							   if($arrTowerId[$k] > 0)
								$add_tower = "TAGGED_MONTH = '".$arrTaggedDate[$k]."', TOWER_ID = $arrTowerId[$k], ";
							   else
								$add_tower = "TAGGED_MONTH = '".$arrTaggedDate[$k]."',TOWER_ID = NULL,";
							}else
								$add_tower = "TAGGED_MONTH = '".$arrTaggedDate[$k]."', TOWER_ID = NULL, ";
								
							//$dbpath = explode("/images_new",$img_path);
							$qry	=	"UPDATE ".PROJECT_PLAN_IMAGES." 
										SET 
											
											TITLE	   = '".$arrTitle[$k]."',
											".$add_tower."
											DISPLAY_ORDER = '".$arrDisplayOrder[$k]."',
											SERVICE_IMAGE_ID   = ".$_REQUEST["service_image_id"][$k]."
										WHERE PROJECT_ID = '".$projectId."' AND SERVICE_IMAGE_ID = '".$_REQUEST["service_image_id"][$k]."'";
							$res	=	mysql_query($qry); //die($qry);
							continue;
						}
						else {
							//echo $returnArr['error'];
							$ErrorMsg["ImgError"] .= "Problem in Update for Image No ".($k+1);
							continue;
						}*/

                        
						

						
					}


					else 
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

									$ErrorMsg["ImgError"] .= "The word ".$imgNamePart." should be part of image name at end.";	//echo $ErrorMsg["ImgError"];//die("here");
								}
							}


						}
						$arrValue[$k] = $_FILES['img']['name'][$k];
						
						//$val = $_FILES['img']['name'][$k];
						
					}
				}
			}
		}		//////////////////////////////////					
									
			
					
			
		$image_id=0; 
		
			
					/*if(count($arrValue) == 0)
					{
						$ErrorMsg["blankerror"] = "Please select atleast one image.";	
					}*/
					if(is_array($ErrorMsg)) {
						//break;

					} 
						
					else
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

						
						foreach($arrValue as $k=>$val)
						{
							//print("<pre>");
		//print_r($arrValue);
							$unitImageArr = array();
							
							$img_path		=	$newImagePath.$BuilderName."/".strtolower($ProjectName)."/" . $val;
							$createFolder	=	$newImagePath.$BuilderName."/".strtolower($ProjectName);
							//$oldpath		=	$_REQUEST['property_image_path'][$k];
			                $service_image_id = $_REQUEST["service_image_id"][$k];

							//unlink($oldpath);

							$txtlocationplan 	= move_uploaded_file($_FILES["img"]["tmp_name"][$k], $img_path);
			                //$s3upload = new S3Upload($s3, $bucket, $img_path, str_replace($newImagePath, "", $img_path));
			                //$s3upload->upload();
			                $extra_path = $BuilderName."/".strtolower($ProjectName)."/";
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
			                $img['error'] = $_FILES["img"]["error"][$k];
			                $img['type'] = $_FILES["img"]["type"][$k];
			                $img['name'] = $_FILES["img"]["name"][$k];
			                $img['tmp_name'] = $_FILES["img"]["tmp_name"][$k];	
			                $unitImageArr['img'] = $img;
			        		$unitImageArr['objectId'] = $projectId;
			        		$unitImageArr['objectType'] = "project";
			        		$unitImageArr['newImagePath'] = $newImagePath;
			                $altText = $BuilderName." ".strtolower($ProjectName)." ".$arrTitle[$k];
			               
							if ($handle = opendir($createFolder))
							{
									rewinddir($handle);							
									while (false !== ($file = readdir($handle)))
									{		//echo "tower".$arrTowerId[$k]."tagged_date".strtotime($arrTaggedDate[$k]);						
									/************Working for location plan***********************/
										if(strstr($file,'loc-plan'))
										{
											if(strstr($file,$val))
											{											
												

												
												$params = array(
							                        "image_type" => "location_plan",
							                        "folder" => $extra_path,
							                        "count" => "location_plan".$k,
							                        "image" => $file,
							                        "priority" => $arrDisplayOrder[$k],
							                        "title" => $arrTitle[$k],
							                        "active" => "1",
							                        "service_image_id" => $service_image_id,
							                        "altText" => $altText,
							                        "update" => "update",
							                        
							                    );
												$unitImageArr['params'] = $params;
						                     	$fileEndName[$k] = "loc-plan";
						                     	$postArr[$k] = $unitImageArr;

							                    //  add images to image service

							                    
							                    /*$returnArr = writeToImageService(  $img, "project", $projectId, $params, $newImagePath);
							                    
							                    $serviceResponse = $returnArr['serviceResponse'];
								            if(empty($serviceResponse["service"]->response_body->error->msg)){
							                    $image_id = $serviceResponse["service"]->response_body->data->id;
												//$image_id = $image_id->id;

							                    $image = new SimpleImage();
											$path=$createFolder."/".$file;
											$image->load($path);
                                            $imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('loc-plan','loc-plan-bkp',$file);
											$image->save($imgdestpath);

											/**********Working for watermark*******************/
							                /*    $img = new Zubrag_watermark($path);
											$img->ApplyWatermark($watermark_path);
											$img->SaveAsFile($path);
											$img->Free();
											/************Resize and large to small*************/
											/*$image->resize(485,320);
											$newimg	=	str_replace('loc-plan','loc-plan-rect-img',$file);
                                            $imgdestpath = $createFolder."/".$newimg;
											$image->save($imgdestpath);

											/**********Working for watermark*******************/
										    // Image path
											/*$image_path =$createFolder."/".$newimg;
											// Where to save watermarked image
											$imgdestpath = $createFolder."/".$newimg;
											// Watermark image
											$img = new Zubrag_watermark($image_path);
											$img->ApplyWatermark($watermark_path);
											$img->SaveAsFile($imgdestpath);
                                            /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                            $s3upload->upload();*/
											/*$img->Free();
											/************Resize and large to small*************/
											/*$image->resize(95,65);
											$newimg	=	str_replace('loc-plan','loc-plan-sm-rect-img',$file);
                                            $imgdestpath = $createFolder."/".$newimg;
											$image->save($imgdestpath);
                                            /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                            $s3upload->upload();
											$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
											$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

											/************Resize and large to thumb*************/
											/*$image->resize(77,70);
											$newimg	=	str_replace('loc-plan','loc-plan-thumb',$file);
                                            $imgdestpath = $createFolder."/".$newimg;
											$image->save($imgdestpath);

											}
											else {
												$strErr = " Error in uploading Image No".($key+1)." ";
												$ErrorMsg["ImgError"] .= $strErr.$serviceResponse["service"]->response_body->error->msg."<br>";
												$image_id=0;
												break 1;
											}*/


														
												
										}																						
									}
									/************Working for layout plan***********************/
										if(strstr($file,'layout-plan'))
										{
											if(strstr($file,$val))
											{						
												/*$image = new SimpleImage();
												$path=$createFolder."/".$file;
												$image->load($path);
												$imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('layout-plan','layout-plan-bkp',$file);
												$image->save($imgdestpath);*/


												$params = array(
							                        "image_type" => "layout_plan",
							                        "folder" => $extra_path,
							                        "count" => "layout_plan".$k,
							                        "image" => $file,
							                        "priority" => $arrDisplayOrder[$k],
							                        "title" => $arrTitle[$k],
							                        "active" => "1",
							                        "service_image_id" => $service_image_id,
							                   		 "altText" => $altText,
							                        "update" => "update",
							                       
							                    );
							                    $unitImageArr['params'] = $params;
						                     	$fileEndName[$key] = "layout-plan";
						                     	$postArr[$k] = $unitImageArr;


							                    //  add images to image service

							                    
							                   /* $returnArr = writeToImageService(  $img, "project", $projectId, $params, $newImagePath);
							                    //print_r($returnArr);
							                    $serviceResponse = $returnArr['serviceResponse'];
								             if(empty($serviceResponse["service"]->response_body->error->msg)){
							                    $image_id = $serviceResponse["service"]->response_body->data->id;
												//$image_id = $image_id->id;


							                    /**********Working for watermark*******************/
											/*$img = new Zubrag_watermark($path);
											$img->ApplyWatermark($watermark_path);
											$img->SaveAsFile($path);
                                            /*$s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                            $s3upload->upload();*/
											/*$img->Free();
											/************Resize and large to small*************/
											/*$image->resize(485,320);
											$newimg	=	str_replace('layout-plan','layout-plan-rect-img',$file);
                                            $imgdestpath = $createFolder."/".$newimg;
											$image->save($imgdestpath);
                                            /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
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
                                            /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                            $s3upload->upload();*/
											/*$img->Free();
											/************Resize and large to small*************/
											/*$image->resize(95,65);
											$newimg	=	str_replace('layout-plan','layout-plan-sm-rect-img',$file);
                                            $imgdestpath = $createFolder."/".$newimg;
											$image->save($imgdestpath);
                                            /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                            $s3upload->upload();
											$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
											$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

											/************Resize and large to thumb*************/
											/*$image->resize(77,70);
											$newimg	=	str_replace('layout-plan','layout-plan-thumb',$file);
                                            $imgdestpath = $createFolder."/".$newimg;
											$image->save($imgdestpath);


											}
											else {
												$strErr = " Error in uploading Image No".($key+1)." ";
												$ErrorMsg["ImgError"] .= $strErr.$serviceResponse["service"]->response_body->error->msg."<br>";
												$image_id=0;
												break 1;
											}

                                                
												$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('layout-plan','layout-plan-bkp',$file);
												$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('layout-plan','layout-plan-bkp',$file);*/
												
											}																							
										}
										/************Working for site plan***********************/
										if(strstr($file,'site-plan'))
										{
											if(strstr($file,$val))
											{						
												/*$image = new SimpleImage();
												$path=$createFolder."/".$file;
												$image->load($path);
												$imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('site-plan','site-plan-bkp',$file);
												$image->save($imgdestpath);*/


												$params = array(
							                        "image_type" => "site_plan",
							                        "folder" => $extra_path,
							                        "count" => "site_plan".$k,
							                        "image" => $file,
							                        "priority" => $arrDisplayOrder[$k],
							                        "title" => $arrTitle[$k],
							                        "active" => "1",
							                        "service_image_id" => $service_image_id,
							                        "altText" => $altText,
							                        "update" => "update",
							                        
							                    );

												$unitImageArr['params'] = $params;
						                     	$fileEndName[$key] = "site-plan";
						                     	$postArr[$k] = $unitImageArr;
							                    //  add images to image service

							                    
							                   /* $returnArr = writeToImageService(  $img, "project", $projectId, $params, $newImagePath);
							                    //print_r($returnArr);
							                    $serviceResponse = $returnArr['serviceResponse'];
							                if(empty($serviceResponse["service"]->response_body->error->msg)){
							                    $image_id = $serviceResponse["service"]->response_body->data->id;
												//$image_id = $image_id->id;

												/**********Working for watermark*******************/
											/*$img = new Zubrag_watermark($path);
											$img->ApplyWatermark($watermark_path);
											$img->SaveAsFile($path);
                                            /*$s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                            $s3upload->upload();*/
											/*$img->Free();
											/************Resize and large to small*************/
											/*$image->resize(485,320);
											$newimg	=	str_replace('site-plan','site-plan-rect-img',$file);
                                            $imgdestpath = $createFolder."/".$newimg;
											$image->save($imgdestpath);
                                            /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
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
                                            /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                            $s3upload->upload();*/
											/*$img->Free();
											/************Resize and large to small*************/
											/*$image->resize(95,65);
											$newimg	=	str_replace('site-plan','site-plan-sm-rect-img',$file);
                                            $imgdestpath = $createFolder."/".$newimg;
											$image->save($imgdestpath);
                                            /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                            $s3upload->upload();
											$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
											$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

											/************Resize and large to thumb*************/
											/*$image->resize(77,70);
											$newimg	=	str_replace('site-plan','site-plan-thumb',$file);
                                            $imgdestpath = $createFolder."/".$newimg;
											$image->save($imgdestpath);
											}
											else {
												$strErr = " Error in uploading Image No".($key+1)." ";
												$ErrorMsg["ImgError"] .= $strErr.$serviceResponse["service"]->response_body->error->msg."<br>";
												$image_id=0;
												break 1;
											}

                                               
												$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('site-plan','site-plan-bkp',$file);
												$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('site-plan','site-plan-bkp',$file);
												*/
											}																						
										}
										/************Working for master plan***********************/
										if(strstr($file,'master-plan'))
										{
											if(strstr($file,$val))
											{				
												/*$image = new SimpleImage();
												$path=$createFolder."/".$file;
												$image->load($path);
                                                $imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('master-plan','master-plan-bkp',$file);
												$image->save($imgdestpath);*/


												$params = array(
							                        "image_type" => "master_plan",
							                        "folder" => $extra_path,
							                        "count" => "master_plan".$k,
							                        "image" => $file,
							                        "priority" => $arrDisplayOrder[$k],
							                        "title" => $arrTitle[$k],
							                        "active" => "1",
							                        "service_image_id" => $service_image_id,
							                        "altText" => $altText,
							                        "update" => "update",
							                        
							                    );
												$unitImageArr['params'] = $params;
						                     	$fileEndName[$key] = "master-plan";
						                     	$postArr[$k] = $unitImageArr;

							                    //  add images to image service

							                    
							                    /*$returnArr = writeToImageService(  $img, "project", $projectId, $params, $newImagePath);
							                    //print_r($returnArr);
							                    $serviceResponse = $returnArr['serviceResponse'];
							                if(empty($serviceResponse["service"]->response_body->error->msg)){
							                    $image_id = $serviceResponse["service"]->response_body->data->id;
												//$image_id = $image_id->id;

												$img = new Zubrag_watermark($path);
											$img->ApplyWatermark($watermark_path);
											$img->SaveAsFile($path);
                                            /*$s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                            $s3upload->upload();*/
											/*$img->Free();
											/************Resize and large to small*************/
											/*$image->resize(485,320);
											$newimg	=	str_replace('master-plan','master-plan-rect-img',$file);
                                            $imgdestpath = $createFolder."/".$newimg;
											$image->save($imgdestpath);
                                            /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
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
                                            /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                            $s3upload->upload();*/
											/*$img->Free();
											/************Resize and large to small*************/
											/*$image->resize(95,65);
											$newimg	=	str_replace('master-plan','master-plan-sm-rect-img',$file);
                                            $imgdestpath = $createFolder."/".$newimg;
											$image->save($imgdestpath);
                                            /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                            $s3upload->upload();
											$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
											$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

											/************Resize and large to thumb*************/
											/*$image->resize(77,70);
											$newimg	=	str_replace('master-plan','master-plan-thumb',$file);
                                            $imgdestpath = $createFolder."/".$newimg;
											$image->save($imgdestpath);

											}
											else {
												$strErr = " Error in uploading Image No".($key+1)." ";
												$ErrorMsg["ImgError"] .= $strErr.$serviceResponse["service"]->response_body->error->msg."<br>";
												$image_id=0;
												break 1;
											}
                                               
												$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('master-plan','master-plan-bkp',$file);
												$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('master-plan','master-plan-bkp',$file);*/
												
											}																						
										}
										/************Working for cluster plan***********************/
										if(strstr($file,'cluster-plan'))
										{
											if(strstr($file,$val))
											{						
												/*$image = new SimpleImage();
												$path=$createFolder."/".$file;
												$image->load($path);
												$imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('cluster-plan','cluster-plan-bkp',$file);
												$image->save($imgdestpath);*/
												if($arrTitle[$key]==null || empty($arrTitle[$key]))
                                            		$altText = $BuilderName." ".strtolower($ProjectName)." Cluster Plan";

												$params = array(
							                        "image_type" => "cluster_plan",
							                        "folder" => $extra_path,
							                        "count" => "cluster_plan".$k,
							                        "image" => $file,
							                        "priority" => $arrDisplayOrder[$k],
							                        "title" => $arrTitle[$k],
							                        "active" => "1",
							                        "service_image_id" => $service_image_id,
							                        "altText" => $altText,
							                        "update" => "update",
							                        "jsonDump" => array(
							                        	"tower_id" => $arrTowerId[$k],
							                        )
							                    );
												$unitImageArr['params'] = $params;
						                     	$fileEndName[$key] = "cluster-plan";
						                     $postArr[$k] = $unitImageArr;

							                    //  add images to image service

							                    
							                    /*$returnArr = writeToImageService(  $img, "project", $projectId, $params, $newImagePath);
							                    //print_r($returnArr);
							                    $serviceResponse = $returnArr['serviceResponse'];
							                if(empty($serviceResponse["service"]->response_body->error->msg)){
							                    $image_id = $serviceResponse["service"]->response_body->data->id;
												//$image_id = $image_id->id;

												/**********Working for watermark*******************/
											/*$img = new Zubrag_watermark($path);
											$img->ApplyWatermark($watermark_path);
											$img->SaveAsFile($path);
                                            /*$s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                            $s3upload->upload();*/
											/*$img->Free();
											/************Resize and large to small*************/
											/*$image->resize(485,320);
											$newimg	=	str_replace('cluster-plan','cluster-plan-rect-img',$file);
                                            $imgdestpath = $createFolder."/".$newimg;
											$image->save($imgdestpath);
                                            /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
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
                                            /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                            $s3upload->upload();*/
											/*$img->Free();
											/************Resize and large to small*************/
											/*$image->resize(95,65);
											$newimg	=	str_replace('cluster-plan','cluster-plan-sm-rect-img',$file);
                                            $imgdestpath = $createFolder."/".$newimg;
											$image->save($imgdestpath);
                                            /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                            $s3upload->upload();
											$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
											$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

											/************Resize and large to thumb*************/
											/*$image->resize(77,70);
											$newimg	=	str_replace('cluster-plan','cluster-plan-thumb',$file);
                                            $imgdestpath = $createFolder."/".$newimg;
											$image->save($imgdestpath);
											}
											else {
												$strErr = " Error in uploading Image No".($key+1)." ";
												$ErrorMsg["ImgError"] .= $strErr.$serviceResponse["service"]->response_body->error->msg."<br>";
												$image_id=0;
												break 1;
											}

                                                
												$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('cluster-plan','cluster-plan-bkp',$file);
												$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('cluster-plan','cluster-plan-bkp',$file);										
												/**********Working for watermark*******************/									
												
										}																						
									}
									/************Working for construction plan***********************/
									if(strstr($file,'const-status'))
									{
										if(strstr($file,$val))
										{										
											/*$image = new SimpleImage();
											$path=$createFolder."/".$file;
											$image->load($path);
                                            $imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('const-status','const-status-bkp',$file);
											$image->save($imgdestpath);*/


												$params = array(
							                        "image_type" => "construction_status",
							                        "folder" => $extra_path,
							                        "count" => "construction_status".$k,
							                        "image" => $file,
							                        "priority" => $arrDisplayOrder[$k],
							                        "title" => $arrTitle[$k],
							                        "active" => "1",
							                        "service_image_id" => $service_image_id,
							                        "tagged_date" =>  $arrTaggedDate[$k],
							                        "update" => "update",
							                        "jsonDump" => array(
							                        	"tower_id" => $arrTowerId[$k],
							                        ),
							                         "altText" => $altText,
							                    );
												$unitImageArr['params'] = $params;
						                     	$fileEndName[$key] = "const-status";
						                     	$postArr[$k] = $unitImageArr;

							                    //  add images to image service

							                    
							                  /*  $returnArr = writeToImageService(  $img, "project", $projectId, $params, $newImagePath);
							                    //print_r($returnArr);
							                    $serviceResponse = $returnArr['serviceResponse'];
							                if(empty($serviceResponse["service"]->response_body->error->msg)){
							                    $image_id = $serviceResponse["service"]->response_body->data->id;
												//$image_id = $image_id->id;

							                    /**********Working for watermark*******************/
										/*$img = new Zubrag_watermark($path);
										$img->ApplyWatermark($watermark_path);
										$img->SaveAsFile($path);
                                        /*$s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                        $s3upload->upload();*/
										/*$img->Free();
										/************Resize and large to small*************/
										/*$image->resize(485,320);
										$newimg	=	str_replace('const-status','const-status-rect-img',$file);
                                        $imgdestpath = $createFolder."/".$newimg;
										$image->save($imgdestpath);
                                        /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
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
                                        /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                        $s3upload->upload();*/
										/*$img->Free();
										/************Resize and large to small*************/
										/*$image->resize(95,65);
										$newimg	=	str_replace('const-status','const-status-sm-rect-img',$file);
                                        $imgdestpath = $createFolder."/".$newimg;
										$image->save($imgdestpath);
                                        /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                        $s3upload->upload();
										$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
										$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
										/************Resize and large to small*************/
										/*$image->resize(125,78);
										$newimg	=	str_replace('const-status','const-status-small',$file);
                                        $imgdestpath = $createFolder."/".$newimg;
										$image->save($imgdestpath);
                                        /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                        $s3upload->upload();
										$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
										$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

										/************Resize and large to thumb*************/
										/*$image->resize(77,70);
										$newimg	=	str_replace('const-status','const-status-thumb',$file);
                                        $imgdestpath = $createFolder."/".$newimg;
										$image->save($imgdestpath);


											}
											else {
												$strErr = " Error in uploading Image No".($key+1)." ";
												$ErrorMsg["ImgError"] .= $strErr.$serviceResponse["service"]->response_body->error->msg."<br>";
												$image_id=0;
												break 1;
											}

                                           
											$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('const-status','const-status-bkp',$file);
											$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('const-status','const-status-bkp',$file);	
											*/
										}																							
									}
									/************Working for Payment plan***********************/
									if(strstr($file,'payment-plan'))
									{
										if(strstr($file,$val))
										{								
											/*$image = new SimpleImage();
											$path=$createFolder."/".$file;
											$image->load($path);
                                            $imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('payment-plan','payment-plan-bkp',$file);
											$image->save($imgdestpath);*/


												$params = array(
							                        "image_type" => "payment_plan",
							                        "folder" => $extra_path,
							                        "count" => "payment_plan".$k,
							                        "image" => $file,
							                        "priority" => $arrDisplayOrder[$k],
							                        "title" => $arrTitle[$k],
							                        "active" => "1",
							                        "service_image_id" => $service_image_id,
							                     	 "altText" => $altText,
							                        "update" => "update",
							                        
							                    );

												$unitImageArr['params'] = $params;
						                     	$fileEndName[$key] = "payment-plan";
						                     	$postArr[$k] = $unitImageArr;
							                    //  add images to image service

							                    
							                    /*$returnArr = writeToImageService(  $img, "project", $projectId, $params, $newImagePath);
							                    //print_r($returnArr);
							                    $serviceResponse = $returnArr['serviceResponse'];
							                if(empty($serviceResponse["service"]->response_body->error->msg)){
							                    $image_id = $serviceResponse["service"]->response_body->data->id;
												//$image_id = $image_id->id;

							                    /**********Working for watermark*******************/
										/*$img = new Zubrag_watermark($path);
										$img->ApplyWatermark($watermark_path);
										$img->SaveAsFile($path);
                                        /*$s3upload = new S3Upload($s3, $bucket, $path, str_replace($newImagePath, "", $path));
                                        $s3upload->upload();*/
										/*$img->Free();
										$image_path =$createFolder."/".$newimg;
										// Where to save watermarked image
										$imgdestpath = $createFolder."/".$newimg;
										/************Resize and large to small*************/
										//echo $image->getWidth($imgdestpath);
										/*if($image->getWidth($imgdestpath)>630)
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
                                        /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                        $s3upload->upload();
										$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
										$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;

										/************Resize and large to thumb*************/
										/*$image->resize(77,70);
										$newimg	=	str_replace('payment-plan','payment-plan-thumb',$file);
                                        $imgdestpath = $createFolder."/".$newimg;
										$image->save($imgdestpath);


											}
											else {
												$strErr = " Error in uploading Image No".($key+1)." ";
												$ErrorMsg["ImgError"] .= $strErr.$serviceResponse["service"]->response_body->error->msg."<br>";
												$image_id=0;
												break 1;
											}

                                          
											$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('payment-plan','payment-plan-bkp',$file);
											$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('payment-plan','payment-plan-bkp',$file);	
											*/
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


												$params = array(
							                        "image_type" => "specification",
							                        "folder" => $extra_path,
							                        "count" => "specification".$k,
							                        "image" => $file,
							                        "priority" => $arrDisplayOrder[$k],
							                        "title" => $arrTitle[$k],
							                        "active" => "1",
							                        "service_image_id" => $service_image_id,
							                        "tagged_date" =>  $arrTaggedDate[$k],
							                        "update" => "update",
							                        "jsonDump" => array(
							                        	"tower_id" => $arrTowerId[$k],
							                        )
							                    );


							                    //  add images to image service

							                    
							                    $returnArr = writeToImageService(  $img, "project", $projectId, $params, $newImagePath);
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

                                         
											$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('specification','specification-bkp',$file);
											$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('specification','specification-bkp',$file);
											
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


												$params = array(
							                        "image_type" => "price_list",
							                        "folder" => $extra_path,
							                        "count" => "price_list".$k,
							                        "image" => $file,
							                        "priority" => $arrDisplayOrder[$k],
							                        "title" => $arrTitle[$k],
							                        "active" => "1",
							                        "service_image_id" => $service_image_id,
							                        "tagged_date" =>  $arrTaggedDate[$k],
							                        "update" => "update",
							                        "jsonDump" => array(
							                        	"tower_id" => $arrTowerId[$k],
							                        )
							                    );


							                    //  add images to image service

							                    
							                    $returnArr = writeToImageService(  $img, "project", $projectId, $params, $newImagePath);
							                    //print_r($returnArr);
							                    $serviceResponse = $returnArr['serviceResponse'];
								                if(empty($serviceResponse["service"]->response_body->error->msg)){
							                    $image_id = $serviceResponse["service"]->response_body->data->id;
												//$image_id = $image_id->id;
											}
											else {
												$ErrorMsg["ImgError"] = $serviceResponse["service"]->response_body->error->msg;
												break 2;
											}

                                           
											$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('price-list','price-list-bkp',$file);
											$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('price-list','price-list-bkp',$file);
											
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


												$params = array(
							                        "image_type" => "application_form",
							                        "folder" => $extra_path,
							                        "count" => "application_form".$k,
							                        "image" => $file,
							                        "priority" => $arrDisplayOrder[$k],
							                        "title" => $arrTitle[$k],
							                        "active" => "1",
							                        "service_image_id" => $service_image_id,
							                        "tagged_date" =>  $arrTaggedDate[$k],
							                        "update" => "update",
							                        "jsonDump" => array(
							                        	"tower_id" => $arrTowerId[$k],
							                        )
							                    );


							                    //  add images to image service

							                    
							                    $returnArr = writeToImageService(  $img, "project", $projectId, $params, $newImagePath);
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

                                           
											$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('app-form','app-form-bkp',$file);
											$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('app-form','app-form-bkp',$file);
											
										}																													
									}
									/************Working for large***********************/
										if(strstr($file,'large'))
										{
											if(strstr($file,$val))
											{

												/*$image = new SimpleImage();
												$path	=	$createFolder."/".$file;
												$image->load($path);
												/************Working for large Img Backup***********************/
												/*$image = new SimpleImage();					
												$image->load($path);
                                                $imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('large','large-bkp',$file);
												$image->save($imgdestpath);*/
												if($_REQUEST['PType'][$k]=="Elevation") $image_type="elevation";	
											if($_REQUEST['PType'][$k]=="Amenities") $image_type="amenities";
											if($_REQUEST['PType'][$k]=="Main Other") $image_type="main_other";

												$params = array(
							                        "image_type" => $image_type,
							                        "folder" => $extra_path,
							                        "count" => "project_image".$k,
							                        "image" => $file,
							                        "priority" => $arrDisplayOrder[$k],
							                        "title" => $arrTitle[$k],
							                        "active" => "1",
							                        "service_image_id" => $service_image_id,
							                        "altText" => $altText,
							                        "update" => "update",
							                       
							                    );
												$unitImageArr['params'] = $params;
						                     	$fileEndName[$key] = "large";
						                     	$postArr[$k] = $unitImageArr;

							                    //  add images to image service

							                    
							                /*    $returnArr = writeToImageService(  $img, "project", $projectId, $params, $newImagePath);
							                    //print_r($returnArr);
							                    $serviceResponse = $returnArr['serviceResponse'];
							                if(empty($serviceResponse["service"]->response_body->error->msg)){
							                    $image_id = $serviceResponse["service"]->response_body->data->id;
												//echo " he no error :".$image_id;

							                    /************Resize and large to small*************/
											/*$image->resize(485,320);
											$newimg	=	str_replace('large','large-rect-img',$file);
                                            $imgdestpath = $createFolder."/".$newimg;
											$image->save($imgdestpath);
                                            /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                            $s3upload->upload();
											$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newimg;
											$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newimg;
											/**********Working for watermark*******************/
											// Image path
											/*$image_path = $createFolder."/".$file;
											$imgdestpath = $createFolder."/".$file;
											$img = new Zubrag_watermark($image_path);
											$img->ApplyWatermark($watermark_path);
											$img->SaveAsFile($imgdestpath);
                                           /* $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                            $s3upload->upload();*/
											/*$img->Free();
											/*********update project table for samall image***********/
											/*$pathProject	=	"/".$BuilderName."/".strtolower($ProjectName);
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
											/**********Working for watermark*******************/
											// Image path
											/*$image_path = $createFolder."/".$newimg;
											// Where to save watermarked image
											$imgdestpath = $createFolder."/".$newimg;
											// Watermark image
											$img = new Zubrag_watermark($image_path);
											$img->ApplyWatermark($watermark_path);
											$img->SaveAsFile($imgdestpath);
                                            /* $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                             $s3upload->upload();*/
											/*$img->Free();
											/************Resize and rect small img*************/
											/*$image->resize(95,65);
											$newsmrect	=	str_replace('large','large-sm-rect-img',$file);
                                            $imgdestpath = $createFolder."/".$newsmrect;
											$image->save($imgdestpath);
                                            /* $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
                                             $s3upload->upload();
											$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newsmrect;
											$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newsmrect;

											/************Resize and thumb*************/
											/*$image->resize(77,70);
											$newsmrect	=	str_replace('large','large-thumb',$file);
                                            $imgdestpath = $createFolder."/".$newsmrect;
											$image->save($imgdestpath);

											}
											else {
												$strErr = " Error in uploading Image No".($key+1)." ";
												$ErrorMsg["ImgError"] .= $strErr.$serviceResponse["service"]->response_body->error->msg."<br>";
												$image_id=0;
												//echo " he error :".$image_id;
												break 1;
											}

                                               
												$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace('large','large-bkp',$file);
												$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace('large','large-bkp',$file);
												/************Resize and large to small*************/
												
											}	
										 }
							
							
										/*$add_tower = '';
													
										if(is_numeric($arrTowerId[$k])){
										   if($arrTowerId[$k] > 0)
											$add_tower = "TAGGED_MONTH = '".$arrTaggedDate[$k]."', TOWER_ID = $arrTowerId[$k], ";
										   else
											$add_tower = "TAGGED_MONTH = '".$arrTaggedDate[$k]."',TOWER_ID = NULL,";
										}else
											$add_tower = "TAGGED_MONTH = '".$arrTaggedDate[$k]."', TOWER_ID = NULL, ";
											
										$dbpath = explode("/images_new",$img_path);
										//echo "id beforsql:".$image_id;
										if($image_id>0){
											$qry	=	"UPDATE ".PROJECT_PLAN_IMAGES." 
														SET 
															PLAN_IMAGE = '".$dbpath[1]."',
															TITLE	   = '".$arrTitle[$k]."',
															".$add_tower."
															DISPLAY_ORDER = '".$arrDisplayOrder[$k]."',
															SERVICE_IMAGE_ID   = ".$image_id."
														WHERE PROJECT_ID = '".$projectId."'  AND PLAN_TYPE = '".$_REQUEST['PType'][$k]."' AND SERVICE_IMAGE_ID = '".$_REQUEST['service_image_id'][$k]."'";
											$res	=	mysql_query($qry); //echo $qry;//die($qry);
										}
										$image_id=0; //die();
										if($flag==1)
										{
											$builderfolder=strtolower($BuilderName);
											$destBuilderFolder = '';
											$sourceBuilderFolder = $newImagePath.$builderfolder;
											//$result = upload_file_to_img_server_using_ftp($sourceBuilderFolder,$destBuilderFolder,4);
										
										}				
										if($projectFolderCreated==1)
										{
											$builderfolder=strtolower($BuilderName);
											$projectNameFolder=strtolower($ProjectName);					
											$destProjectFolder = '';
											$sourceProjectFolder = $newImagePath.$builderfolder."/.".$projectNameFolder;
											//$result = upload_file_to_img_server_using_ftp($sourceProjectFolder,$destProjectFolder,4);					

										}		*/					
										
							//$result = upload_file_to_img_server_using_ftp($source,$dest,1);
						
							
								}
							}

						}
					//} //remove it
						} 
					
					}
					

		 
	if(empty($ErrorMsg)){
		$serviceResponse = writeToImageService($postArr);
		//print("<pre>");var_dump($serviceResponse);die();
		//$serviceResponse = json_decode($serviceResponse);
		//print'<pre>';   print_r($serviceResponse);//die();				                  	
        foreach ($serviceResponse as $k => $v) {
        	
	        if(empty($v->error->msg)){
	            $image_id = $v->data->id;

	            $file = $_FILES['img']['name'][$k];
	            //$tmp_path = $tmpDir.$file;
	            $createFolder	=	$newImagePath.$BuilderName."/".strtolower($ProjectName);
	            $img_path = $createFolder."/".$file;
	            //echo $tmp_path; echo $img_path.$image_id;// die();
	            //rename($tmp_path, $img_path);
	            //unlink($tmp_path);
	            $f = $fileEndName[$k];
	            $image = new SimpleImage();
				$path	=	$createFolder."/".$file;
				$image->load($path);
				/************Working for large Img Backup***********************/
				$image = new SimpleImage();
				$image->load($path);
	            $imgdestpath = $newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace($f,$f.'-bkp',$file);
				$image->save($imgdestpath);

				$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/". str_replace($f,$f.'-bkp',$file);
				$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/". str_replace($f,$f.'-bkp',$file);
				
				/************Resize and large to small*************/
				$image->resize(485,320);
				$newimg	=	str_replace($f,$f.'-rect-img',$file);
	            $imgdestpath = $createFolder."/".$newimg;
				$image->save($imgdestpath);
	            /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
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
	           /* $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
	            $s3upload->upload();*/
				$img->Free();
				/*********update project table for samall image***********/
				if($_REQUEST['PType'][$k]=="Elevation"){
					$pathProject	=	"/".$BuilderName."/".strtolower($ProjectName);

					$qry	=	"UPDATE ".RESI_PROJECT." SET PROJECT_SMALL_IMAGE = '".$pathProject."/".str_replace('-large','-small',$file)."'
								 WHERE PROJECT_ID = '".$projectId."'";	//die("here");
					$res	=	mysql_query($qry);
					$image->resize(206,108);
					$newrect	=	str_replace('large','small',$file);
		            $imgdestpath = $createFolder."/".$newrect;
					$image->save($imgdestpath);
				}
	            /*$s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
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
	            /* $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
	             $s3upload->upload();*/
				$img->Free();
				/************Resize and rect small img*************/
				$image->resize(95,65);
				$newsmrect	=	str_replace($f,$f.'-sm-rect-img',$file);
	            $imgdestpath = $createFolder."/".$newsmrect;
				$image->save($imgdestpath);
	            /* $s3upload = new S3Upload($s3, $bucket, $imgdestpath, str_replace($newImagePath, "", $imgdestpath));
	             $s3upload->upload();
				$source[]=$newImagePath.$BuilderName."/".strtolower($ProjectName)."/".$newsmrect;
				$dest[]="public_html/images_new/".$BuilderName."/".strtolower($ProjectName)."/".$newsmrect;

				/************Resize and thumb*************/
				$image->resize(77,70);
				$newsmrect	=	str_replace($f,$f.'-thumb',$file);
	            $imgdestpath = $createFolder."/".$newsmrect;
				$image->save($imgdestpath);


				$add_tower = '';
													
				if(is_numeric($arrTowerId[$k])){
				   if($arrTowerId[$k] > 0)
					$add_tower = "TAGGED_MONTH = '".$arrTaggedDate[$k]."', TOWER_ID = $arrTowerId[$k], ";
				   else
					$add_tower = "TAGGED_MONTH = '".$arrTaggedDate[$k]."',TOWER_ID = NULL,";
				}else
					$add_tower = "TAGGED_MONTH = '".$arrTaggedDate[$k]."', TOWER_ID = NULL, ";
					
				$dbpath = explode("/images_new",$img_path);
				//echo "id beforsql:".$image_id;
				if($postArr[$k]['params']['delete']=="yes"){
					//echo "in delete";
					$qry	=	"DELETE FROM ".PROJECT_PLAN_IMAGES." 
                                                                 WHERE 
                                                                       PROJECT_ID = '".$projectId."'
                                                                       AND PLAN_TYPE = '".$_REQUEST['PType'][$k]."'
                                                                       AND SERVICE_IMAGE_ID = '".$_REQUEST['service_image_id'][$k]."'";
					
					$res	=	mysql_query($qry);
				}

				if($image_id>0){
					if($image_id==$_REQUEST['service_image_id'][$k]){
						$qry	=	"UPDATE ".PROJECT_PLAN_IMAGES." 
									SET 
										TITLE	   = '".$arrTitle[$k]."',
										".$add_tower."
										DISPLAY_ORDER = '".$arrDisplayOrder[$k]."',
										SERVICE_IMAGE_ID   = ".$image_id."
									WHERE PROJECT_ID = '".$projectId."'  AND PLAN_TYPE = '".$_REQUEST['PType'][$k]."' AND SERVICE_IMAGE_ID = '".$_REQUEST['service_image_id'][$k]."'";
						$res	=	mysql_query($qry); 
					}
					else{
						$qry	=	"UPDATE ".PROJECT_PLAN_IMAGES." 
									SET 
										PLAN_IMAGE = '".$dbpath[1]."',
										TITLE	   = '".$arrTitle[$k]."',
										".$add_tower."
										DISPLAY_ORDER = '".$arrDisplayOrder[$k]."',
										SERVICE_IMAGE_ID   = ".$image_id."
									WHERE PROJECT_ID = '".$projectId."'  AND PLAN_TYPE = '".$_REQUEST['PType'][$k]."' AND SERVICE_IMAGE_ID = '".$_REQUEST['service_image_id'][$k]."'";
						$res	=	mysql_query($qry); //echo $qry;//die($qry);
					}
				}
				$image_id=0;


	        }
	        else {
				$strErr = " Error in uploading Image No ".($k+1)." ";
				$ErrorMsg["ImgError"] .= $strErr.$v->error->msg."<br>";
				//$file = $_FILES["txtlocationplan"]["name"][$k];
	            //$tmp_path = $tmpDir.$file;
				//unlink($tmp_path);
				
			}
		}

	}
		//die("here");
		if(empty($ErrorMsg)){	
		//die("here");	
			if($preview == 'true')
				header("Location:show_project_details.php?projectId=".$projectId);
			else
				header("Location:ProjectList.php?projectId=".$projectId);
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
