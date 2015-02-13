<?php

	set_time_limit(0);
	ini_set("memory_limit","256M");

	include("ftp.new.php");
	$ErrorMsg='';

	
	$watermark_path = "images/pt_shadow1.png";
	
        
    //$smarty->assign("imagetype", $_REQUEST['imagetype']);


    $listingId = $_REQUEST['listingId'];






    
	    $image_types = ImageServiceUpload::$image_types;
	    $listing_image_types = $image_types['listing'];
	    $smarty->assign("listing_image_types", $listing_image_types);


	    

	    
	    
	    //display order
	    $display_order_div = "<select name='txtdisplay_order[]' id='display_order_dropdown'>";
	    for($cmt=1;$cmt<=5;$cmt++){
			if($cmt == 5)
				$display_order_div .="<option value='$cmt' selected >$cmt</option>";
			else
				$display_order_div .="<option value='$cmt'>$cmt</option>";
		}
	    $display_order_div .= "</select>";
	    $smarty->assign("display_order_div", $display_order_div);
	            
		

		$watermark_path = 'images/pt_shadow1.png';
		$source=array();
		$dest=array();



	//edit section- get images
		$objectType = "listing";
		$ImageDataListingArr = array(); //Image data from Image service
		
	    $objectId = $listingId;
	    
	    $url = ImageServiceUpload::$image_upload_url."?objectType=$objectType&objectId=".$objectId;
	    $content = file_get_contents($url);
	    $imgPath = json_decode($content);
	//print("<pre>");   
	 //var_dump($imgPath);

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
		         
		        if ( $v->priority==0 || $v->priority==null )
		        	$data['display_order'] = 5;
		        else
		        	$data['display_order'] = $v->priority;
		        $data['TITLE'] = $v->title;
		        $data['IMAGE_DESCRIPTION'] = $v->description;
		        $data['SERVICE_IMAGE_ID'] = $v->id;
		        $data['SERVICE_IMAGE_PATH'] = $v->absolutePath;
		      
		        
			   
			    
		        $str = trim(trim($v->jsonDump, '{'), '}');
		        $towerarr = explode(":", $str);
		        if(trim($towerarr[1],"\"") == "null")
		        	$data['tower_id']==null;
		        else
		       		$data['tower_id'] = (int)trim($towerarr[1],"\"");
		       //var_dump($data['tower_id']);
		        $data['LISTING_ID'] = $v->objectId;
		        $data['STATUS'] = $v->active;
		       $data['alt_text'] = $v->altText;
		        array_push($ImageDataListingArr, $data);
	    	
	    }
	    //print_r($ImageDataListingArr);
	    $smarty->assign("ImageDataListingArr", $ImageDataListingArr);


	    $display_order_div_edit = array();
	    for($cmt=1;$cmt<=5;$cmt++){
			$display_order_div_edit[$cmt] =  $cmt ;
	    }
	    //print_r($_REQUEST['chk_name']);
	    $smarty->assign("display_order_div_edit", $display_order_div_edit);


if($_POST['listing_edit']=='yes'){



	    if(isset($_POST['btnSave']) && empty($_REQUEST['chk_name']) )
	{
	  $ErrorMsgEdit["checkbox"] = "Please select edit or delete action.";
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
				//echo "1";
				$itype = $_REQUEST['PType'][$k];
				
				if(!array_key_exists($_REQUEST['PType'][$k], $temp_arr))
					$temp_arr[$_REQUEST['PType'][$k]] = array();
				if(trim($_REQUEST['txtdisplay_order'][$k]) == ''){
					$ErrorMsgEdit["display_order"] = "Please enter Display Order."; 
				}
				else{
						//echo "2";		
				  	if(array_key_exists($_REQUEST['txtdisplay_order'][$k], $temp_arr[$_REQUEST['PType'][$k]])){
					  $ErrorMsgEdit["display_order"] = "Display order for an Image Type must be unique.";				  
				  	}
				  	else {//checking duplicacy
						$ext_vlinks = checkDuplicateDisplayOrderListing($listingId, $_REQUEST['txtdisplay_order'][$k],$_REQUEST['PType'][$k], $_REQUEST['service_image_id'][$k]);

						if($ext_vlinks){
							 $ErrorMsgEdit["display_order"] = "Display order '".$_REQUEST['txtdisplay_order'][$k]."' already exist for Image Type: ".$_REQUEST['PType'][$k]." .";

						}
				  	}
				  	if($_REQUEST['txtdisplay_order'][$k] != 5)
					$temp_arr[$_REQUEST['PType'][$k]][$_REQUEST['txtdisplay_order'][$k]] = $_REQUEST['txtdisplay_order'][$k];
				 	
				}
				
				if(trim($_REQUEST['title'][$k]) == ''){
					$ErrorMsgEdit["ptype"] = "Please enter Image Title."; 
				}
				


			}

		}
		
	} 
	if( $listingId == '') 
	{
	  $ErrorMsgEdit["listingId"] = "Please select Listing.";
	}

	//echo "3";

//print_r($ErrorMsgEdit);
	$source=array();
	$dest=array();

	//image edit

	if (isset($_POST['btnSave']) && !is_array($ErrorMsgEdit)) 
		{
				//echo "4";
			$image_update_flag = 0;
			 
			
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
	                   	

	                     $tmp['image'] = "";
				        		$tmp['objectId'] = $listingId;
				        		$tmp['objectType'] = "listing";
				        		$tmp['service_image_id'] = $service_image_id;
				        		$unitImageArr['upload_from_tmp'] = "yes";
				        		$unitImageArr['method'] = "DELETE";
				        		$unitImageArr['url'] = IMAGE_SERVICE_URL."/".$service_image_id;
				        		$unitImageArr['params'] = $tmp;
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
							//echo "5";				
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
							//$altText = $BuilderName." ".strtolower($ProjectName)." ".$arrTitle[$k];
						if($_FILES['img']['name'][$k] == ''){
							

		                    $tmp['image'] = "";
				        		$tmp['objectId'] = $listingId;
				        		$tmp['objectType'] = "listing";
				        		$tmp['priority'] = $arrDisplayOrder[$k];
				        		$tmp['title'] = $arrTitle[$k];
				        		$tmp['altText'] = $arrTitle[$k];
				        		$tmp['service_image_id'] = $service_image_id;
				        		$unitImageArr['upload_from_tmp'] = "yes";
				        		$unitImageArr['method'] = "POST";
				        		$unitImageArr['url'] = IMAGE_SERVICE_URL."/".$service_image_id;
				        		$unitImageArr['params'] = $tmp;
				        		$postArr[$k] = $unitImageArr;
		                

	                        //echo "6";
							

							
						}


						else 
						{  
							//echo "7";
							if(!in_array(strtolower($_FILES['img']['type'][$k]), $arrImg))
							{
							  $ErrorMsgEdit["ImgError"] = "You can upload only ".ucwords(implode(" / ",$arrImg))." images.";
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
							$ErrorMsgEdit["blankerror"] = "Please select atleast one image.";	
						}*/
						if(is_array($ErrorMsgEdit)) {
							//break;

						} 
							
						else
						{  
							$flag=0;
						
			/*******************Update location,site,layout and master plan from db and also from table*********/	
							
					
						//print_r($arrValue);//die;	

							
							foreach($arrValue as $k=>$val)
							{
								//print("<pre>");
			//print_r($arrValue);
								$unitImageArr = array();
								
								
				                $service_image_id = $_REQUEST["service_image_id"][$k];

								//unlink($oldpath);

								//$txtlocationplan 	= move_uploaded_file($_FILES["img"]["tmp_name"][$k], $img_path);
				               

								$img = array();
				                $img['error'] = $_FILES["img"]["error"][$k];
				                $img['type'] = $_FILES["img"]["type"][$k];
				                $img['name'] = $_FILES["img"]["name"][$k];
				                $img['tmp_name'] = $_FILES["img"]["tmp_name"][$k];	
				                
				                $tmp['image'] = "@".$img['tmp_name'];
				        		$tmp['objectId'] = $listingId;
				        		$tmp['objectType'] = "listing";
				        		$tmp['imageType'] = $_REQUEST['PType'][$k];
				        		$tmp['priority'] = $arrDisplayOrder[$k];
				        		$tmp['title'] = $arrTitle[$k];
				        		$tmp['altText'] = $arrTitle[$k];
				        		$tmp['service_image_id'] = $service_image_id;
				        		$tmp['update'] = "yes";
				        		$unitImageArr['upload_from_tmp'] = "yes";
				        		$unitImageArr['method'] = "POST";
				        		$unitImageArr['url'] = IMAGE_SERVICE_URL."/".$service_image_id;
				        		$unitImageArr['params'] = $tmp;
				        		$postArr[$k] = $unitImageArr;							
													
							} 
						
						}
						

			 
		if(empty($ErrorMsgEdit)){
			$serviceResponse = writeToImageService($postArr);
			//print("<pre>");var_dump($serviceResponse);die();
			//$serviceResponse = json_decode($serviceResponse);
			//print'<pre>';   print_r($serviceResponse);//die();				                  	
	        foreach ($serviceResponse as $k => $v) {
	        	
		        if(empty($v->error->msg)){
		            $image_id = $v->data->id;

					
					$image_id=0;


		        }
		        else {
					$strErr = " Error in uploading Image No ".($k+1)." ";
					$ErrorMsgEdit["ImgError"] .= $strErr.$v->error->msg."<br>";
					//$file = $_FILES["txtlocationplan"]["name"][$k];
		            //$tmp_path = $tmpDir.$file;
					//unlink($tmp_path);
					
				}
			}

		}
			//die("here");
			if(empty($ErrorMsgEdit)){	
			//die("here");	
				if($preview == 'true')
					header("Location:listing_img_add.php?listingId=".$listingId);
				else
					header("listing_img_add.php?listingId=".$listingId);
			}

		}

	$smarty->assign("ErrorMsgEdit", $ErrorMsgEdit);
}







// image add 
else{
	if (isset($_POST['Next']))
	{
		$smarty->assign("projectId", $projectId);
		//print("<pre>");
		//print_r($_POST);  
		//print_r($_FILES);
		//die();
		

		$arrValue = array();
		$arrTitle = array();
		$arrTaggedDate = array();
		$arrTowerId = array();
		$arrDisplayOrder = array();
		//print("<pre>");
		//print_r($_FILES['txtlocationplan']);
		 //echo "start:".microtime(true)."<br>";
		  	foreach($_FILES['txtlocationplan']['name'] as $k=>$v)
			{
				if($v != '')
				{
					if(!in_array(strtolower($_FILES['txtlocationplan']['type'][$k]), $arrImg))
					{
						$ErrorMsg["ImgError"] = "You can upload only ".ucwords(implode(" / ",$arrImg))." images.";
					}

					

					$arrValue[$k] = $v;
					$arrTitle[$k] = $_REQUEST['title'][$k];

					

				
					//echo $arrTowerId[$k]; die();
					$arrDisplayOrder[$k] = $_REQUEST['txtdisplay_order'][$k+1];
					//die($arrTaggedDate[$k].$arrTowerId[$k]);
				}
			}
			
			if(count($arrValue) == 0)
		    {
			$ErrorMsg["blankerror"] = "Please select atleast one image.";
		    }
	        else if( $listingId == '')
		    {
		      $ErrorMsg["listingId"] = "Please select a Listing.";
		    }
	        else if( $_REQUEST['PType'] == '')
		    {
		      $ErrorMsg["ptype"] = "Please select Image type.";
		    }else if( !array_filter($_REQUEST['title']))
		    {
		      $ErrorMsg["ptype"] = "Please enter Image Title.";
		    }
			    
		     

		    //checking uniqness display order of elevation images
		    
				$count = 1;
				$temp_arr = array();
				
				while($count <= $_REQUEST['img']){
					
					if(trim($_REQUEST['txtdisplay_order'][$count]) == ''){
						$ErrorMsg["ptype"] = "Please enter Display Order."; break;
					}else{

					  if(array_key_exists($_REQUEST['txtdisplay_order'][$count], $temp_arr)){
						  $ErrorMsg["ptype"] = "Display order must be unique."; break;				  
					  }else {//checking duplicacy
							$ext_vlinks = checkDuplicateDisplayOrderListing($listingId, $_REQUEST['txtdisplay_order'][$count], $_REQUEST['PType']);
							if($ext_vlinks){
								 $ErrorMsg["ptype"] = "Display order '".$_REQUEST['txtdisplay_order'][$count]."' already exist."; break;
							}
					  }
					  if($_REQUEST['txtdisplay_order'][$count] != 5)
						$temp_arr[$_REQUEST['txtdisplay_order'][$count]] = $_REQUEST['txtdisplay_order'][$count];
					}


					$count++;
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
				
				//echo "dir:".$lowerdir.":new:".$newdir;
				/****************project folder check**********/
				
				//print_r($arrValue);
				//echo "loop-start:".microtime(true)."<br>";


				$postArr = array(); // array to store image data to send with http request


				foreach($arrValue as $key=>$val)
				{
					$unitImageArr = array();
					
					$image_type = $_REQUEST['PType'];
					//echo $sorce;
					//$txtlocationplan 	= move_uploaded_file($_FILES["txtlocationplan"]["tmp_name"][$key], $img_path);
					$img = array();
	                $img['error'] = $_FILES["txtlocationplan"]["error"][$key];
	                $img['type'] = $_FILES["txtlocationplan"]["type"][$key];
	                $img['name'] = $_FILES["txtlocationplan"]["name"][$key];
	                $img['tmp_name'] = $_FILES["txtlocationplan"]["tmp_name"][$key];

	                $tmp['image'] = "@".$img['tmp_name'];
	        		$tmp['objectId'] = $listingId;
	        		$tmp['objectType'] = "listing";
	        		$tmp['imageType'] = $image_type;
	        		$tmp['priority'] = $arrDisplayOrder[$key];
	        		$tmp['title'] = $arrTitle[$key];
	        		$tmp['altText'] = $arrTitle[$key];
	        		$unitImageArr['upload_from_tmp'] = "yes";
	        		$unitImageArr['method'] = "POST";
	        		$unitImageArr['url'] = IMAGE_SERVICE_URL;
	        		$unitImageArr['params'] = $tmp;
	        		array_push($postArr, $unitImageArr);
					
				}				
							
			//print("<pre>");var_dump($postArr);//die();
			$serviceResponse = writeToImageService($postArr);
			//print("<pre>");var_dump($serviceResponse);die();
			//$serviceResponse = json_decode($serviceResponse);
			//print'<pre>';   print_r($serviceResponse);//die();				                  	
	        foreach ($serviceResponse as $k => $v) {
	        	
		        if(empty($v->error->msg)){
		            $image_id = $v->data->id;

		            

					
					
					$image_id=0;

		        }
		        else {
					$strErr = " Error in uploading Image No ".($k+1)." ";
					$ErrorMsg["ImgError"] .= $strErr.$v->error->msg."<br>";
					$file = $_FILES["txtlocationplan"]["name"][$k];
		            $tmp_path = $tmpDir.$file;
					unlink($tmp_path);
					
				}
			}

			//die("here0");
			if(empty($ErrorMsg)){
				if($_POST['Next'] == 'Add More')
						header("Location:project_img_add.php?projectId=".$projectId);
				else if($_POST['Next'] == 'Save')
						header("Location:ProjectList.php?projectId=".$projectId);
				else
					header("Location:add_specification.php?projectId=".$projectId);
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
}
?>
