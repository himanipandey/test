<?php
$builderid = $_REQUEST['builderid'];

include("ftp.new.php");
$watermark_path = 'pt_shadow1.png';

//echo $_REQUEST['suburb'];die("here");
$smarty->assign("builderid", $builderid);
$ProjectList = project_list($builderid);
$smarty->assign("ProjectList", $ProjectList);
if ($_POST['btnExit'] == "Exit")
{
	header("Location:BuilderList.php");
}

if ($_POST['btnSave'] == "Save")
{
	
	$txtBuilderName			=	trim($_POST['txtBuilderName']);
	$txtBuilderDescription	=	trim($_POST['txtBuilderDescription']);
	$txtBuilderUrlOld		=	trim($_POST['txtBuilderUrlOld']);
	$DisplayOrder			=	trim($_POST['DisplayOrder']);
	$txtMetaTitle			=	trim($_POST['txtMetaTitle']);
	$txtMetaKeywords		=	trim($_POST['txtMetaKeywords']);
	$txtMetaDescription		=	trim($_POST['txtMetaDescription']);
	$img					=	trim($_POST['img']);
	$oldbuilder				=	trim($_POST['oldbuilder']);
	$imgedit				=	trim($_POST['imgedit']);
	$address				=	trim($_POST['address']);
	$city					=	trim($_POST['city']);
	$pincode				=	trim($_POST['pincode']);
	$ceo					=	trim($_POST['ceo']);
	$employee				=	trim($_POST['employee']);
	$established			=	trim($_POST['established']);

	$employee				=	trim($_POST['employee']);
	$delivered_project		=	trim($_POST['delivered_project']);
	$area_delivered			=	trim($_POST['area_delivered']);
	$ongoing_project		=	trim($_POST['ongoing_project']);
	$website				=	trim($_POST['website']);
	$revenue				=	trim($_POST['revenue']);
	$debt					=	trim($_POST['debt']);
	
	$smarty->assign("txtBuilderName", $txtBuilderName);
	$smarty->assign("txtBuilderDescription", $txtBuilderDescription);
	$smarty->assign("txtBuilderUrlOld", $txtBuilderUrlOld);
	$smarty->assign("DisplayOrder", $DisplayOrder);
	$smarty->assign("txtMetaTitle", $txtMetaTitle);
	$smarty->assign("txtMetaKeywords", $txtMetaKeywords);
	$smarty->assign("txtMetaDescription", $txtMetaDescription);	
	$smarty->assign("img", $img);	
	$smarty->assign("oldval", $oldbuilder);	
	$smarty->assign("imgedit", $imgedit);	
	$smarty->assign("address", $address);	
	$smarty->assign("city", $city);	
	$smarty->assign("pincode", $pincode);	
	$smarty->assign("ceo", $ceo);	
	$smarty->assign("employee", $employee);
	$smarty->assign("established", $established);
	$smarty->assign("employee", $employee);	
	$smarty->assign("delivered_project", $delivered_project);	
	$smarty->assign("area_delivered", $area_delivered);	
	$smarty->assign("ongoing_project", $ongoing_project);	
	$smarty->assign("website", $website);	
	$smarty->assign("revenue", $revenue);
	$smarty->assign("debt", $debt);

	if(!preg_match('/^[a-zA-z0-9 ]+$/', $txtBuilderName)){
		$ErrorMsg["txtBuilderName"] = "Special characters are not allowed";
	 }
	
	 if( $txtBuilderName == '') 
	   {
	     $ErrorMsg["txtBuilderName"] = "Please enter Builder name.";
	   }
	if( $txtBuilderDescription == '') 
	   {
	     $ErrorMsg["txtBuilderDescription"] = "Please enter Builder description.";
	   }   
	
	if( $DisplayOrder == '') 
	   {
	     $ErrorMsg["DisplayOrder"] = "Please enter Builder Display Order.";
	   }
	if( $txtMetaTitle == '') 
	   {
	     $ErrorMsg["txtMetaTitle"] = "Please enter Builder meta title.";
	   }
	if( $txtMetaKeywords == '') 
	   {
	     $ErrorMsg["txtMetaKeywords"] = "Please enter Builder meta keywords.";
	   }
	if( $txtMetaDescription == '') 
	   {
	     $ErrorMsg["txtMetaDescription"] = "Please enter Builder meta description.";
	   } 

	//  die; 
	if($_FILES['txtBuilderImg']['type'] != '')
	{
		if(!in_array(strtolower($_FILES['txtBuilderImg']['type']), $arrImg))
		{
			$ErrorMsg["ImgError"] = "You can upload only jpg / jpeg gif png images.";
		} 
	}  
	 
		/*******code for no of contacts*******/
	$contactArr	=	array();
	foreach($_REQUEST['contact_name'] as $k=>$v)
	{
		$contactArr['Name'][] = $v;
		if($_REQUEST['contact_ph'][$k] !='')
			$contactArr['Phone'][] = $_REQUEST['contact_ph'][$k];
		if($_REQUEST['contact_email'][$k] !='')
			$contactArr['Email'][] = $_REQUEST['contact_email'][$k];

		$key = "projects_".($k+1);
		$contactArr['Projects'][] = implode($_REQUEST[$key],"#");

	}
	$url = urlCreaationDynamic('b-',$txtBuilderName);
	if(is_array($ErrorMsg)) {
		// Do Nothing
	} 	
	else if ($builderid == '')
	{		
		if (($_FILES["txtBuilderImg"]["type"]))
   		{   
   	
  			$name			=	$_FILES["txtBuilderImg"]["name"];
			$foldername		=	str_replace(' ','-',strtolower($txtBuilderName));
			$createFolder	=	 $newImagePath.$foldername;
			mkdir($createFolder, 0777);
			$return 			=	 move_uploaded_file($_FILES["txtBuilderImg"]["tmp_name"], "".$createFolder."/" . $name);

			//die("cc");
			if($return)
			{				
				$imgurl		=	"/".$foldername."/".$name;
				
				InsertBuilder($txtBuilderName, $txtBuilderDescription, $url,$DisplayOrder,$txtMetaTitle,$txtMetaKeywords,$txtMetaDescription,$imgurl,$address,$city,$pincode,$ceo,$employee,$established,$delivered_project,$area_delivered,$ongoing_project,$website,$revenue,$debt,$contactArr);			
				$createFolder = $newImagePath.$foldername;
				if ($handle = opendir($createFolder))
 				{
       					rewinddir($handle);
     						while (false !== ($file = readdir($handle)))
     						{
								/************Working for large***********************/
							
								if(strstr($file,$_FILES["txtBuilderImg"]["name"]))
								{
									
									$image = new SimpleImage();
									$path	=	$createFolder."/".$file;
									$image->load($path);
									
									/************Working for large Img Backup***********************/
									$image = new SimpleImage();
								
										$image->load($path);

									$image->resize(477,247);

									$image->save($createFolder."/". str_replace('.jpg','-rect.jpg',$file));

								/************Resize and large to small*************/
									$image->resize(95,65);
									$newimg	=	str_replace('.jpg','-sm-rect.jpg',$file);
									$image->save($createFolder."/".$newimg);
									
									$image->resize(80,36);
									$newimg	=	str_replace('.jpg','-thumb.jpg',$file);
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
								}	
							} 
					
					header("Location:BuilderList.php");
				}	

				//header("Location:BuilderList.php?page=1&sort=all");
			}	
			else 
			{
				$ErrorMsg['ImgError'] = "Please insert image";
			}
			
		}
		else {
				$ErrorMsg['ImgError'] = "Please insert image";
		}	

	}
	else
	{
		
		$name			=	$_FILES["txtBuilderImg"]["name"];
		$cutpath		=	explode("/",$imgedit);
		$newfold		=	$newImagePath.$cutpath[1];
			
		if (($_FILES["txtBuilderImg"]["type"]))
   		{
			
   			$return 	=	 move_uploaded_file($_FILES["txtBuilderImg"]["tmp_name"], "".$newfold."/" . $name);

			if($return)
			{
				$imgurl		=	"/".$cutpath[1]."/".$name;
				$rt = UpdateBuilder($txtBuilderName, $txtBuilderDescription, $url,$DisplayOrder,$txtMetaTitle,$txtMetaKeywords,$txtMetaDescription,$imgurl,$builderid,$address,$city,$pincode,$ceo,$employee,$established,$delivered_project,$area_delivered,$ongoing_project,$website,$revenue,$debt,$contactArr);
				if($rt)
				{
					insertUpdateInRedirectTbl($url,$txtBuilderUrlOld);
					if($url != $txtBuilderUrlOld)
						updateProjectUrl($id,$tblName,$builderName);
					header("Location:BuilderList.php?page=1&sort=all");
				}
				else
					$ErrorMsg['dataInsertionError'] = "Please try again there is a problem";
				/*************Resize images code***************************/
				$createFolder = $newImagePath.$cutpath[1];//die;
				if ($handle = opendir($createFolder))
 				{
   					rewinddir($handle);
					while (false !== ($file = readdir($handle)))
					{
						/************Working for large***********************/
						
						if(strstr($file,$_FILES["txtBuilderImg"]["name"]))
						{
							$image = new SimpleImage();
							$path	=	$createFolder."/".$file;
							$image->load($path);
							
							/************Working for large Img Backup***********************/
							$image->resize(477,247);


							$image->save($newImagePath.$cutpath[1]."/". str_replace('.jpg','-rect.jpg',$file));

						/************Resize and large to small*************/
							$image->resize(95,65);
							$newimg	=	str_replace('.jpg','-sm-rect.jpg',$file);
							$image->save($newImagePath.$cutpath[1]."/".$newimg);
						
							/**********Working for watermark*******************/
							// Image path
								$image_path =$newImagePath.$cutpath[1]."/".$file;

								// Where to save watermarked image
								$imgdestpath = $newImagePath.$cutpath[1]."/".$file;

							 // Watermark image
							$img = new Zubrag_watermark($image_path);
							$img->ApplyWatermark($watermark_path);
							$img->SaveAsFile($imgdestpath);
							$img->Free();
						}	
					}	 		
				
				header("Location:BuilderList.php");
 				}	
			}	
			else 
			{
				$ErrorMsg['img'] = "Please insert image";
			}
			
		}
		else 
		{
			$return = UpdateBuilder($txtBuilderName, $txtBuilderDescription, $url,$DisplayOrder,$txtMetaTitle,$txtMetaKeywords,$txtMetaDescription,$imgedit,$builderid,$address,$city,$pincode,$ceo,$employee,$established,$delivered_project,$area_delivered,$ongoing_project,$website,$revenue,$debt,$contactArr);
			if($return)
			{
				insertUpdateInRedirectTbl($url,$txtBuilderUrlOld);
				if($url != $txtBuilderUrlOld)
					updateProjectUrl($builderid,'builder',$txtBuilderName);
				header("Location:BuilderList.php?page=1&sort=all");
			}
			else
				$ErrorMsg['dataInsertionError'] = "Please try again there is a problem";	
		}
		
	}
	$smarty->assign("ErrorMsg", $ErrorMsg);
}
else if($builderid	!= '')
{
	$qryedit	=	"SELECT * FROM ".RESI_BUILDER." WHERE BUILDER_ID = '".$builderid."'";
	$resedit	=	mysql_query($qryedit);
	$dataedit	=	mysql_fetch_array($resedit);

	$smarty->assign("txtBuilderName", $dataedit['BUILDER_NAME']);
	$smarty->assign("txtBuilderDescription", $dataedit['DESCRIPTION']);
	$smarty->assign("txtBuilderUrl", $dataedit['URL']);
	$smarty->assign("txtBuilderUrlOld", $dataedit['URL']);
	$smarty->assign("DisplayOrder", $dataedit['DISPLAY_ORDER']);
	$smarty->assign("txtMetaTitle", $dataedit['META_TITLE']);
	$smarty->assign("txtMetaKeywords", $dataedit['META_KEYWORDS']);
	$smarty->assign("txtMetaDescription", $dataedit['META_DESCRIPTION']);
	$smarty->assign("img", $dataedit['BUILDER_IMAGE']);
	$smarty->assign("imgedit", $dataedit['BUILDER_IMAGE']);
	$smarty->assign("oldval", $dataedit['BUILDER_NAME']);
	$smarty->assign("address", $dataedit['ADDRESS']);
	$smarty->assign("city", $dataedit['CITY']);
	$smarty->assign("pincode", $dataedit['PINCODE']);
	$smarty->assign("ceo", $dataedit['CEO_MD_NAME']);
	$smarty->assign("employee", $dataedit['TOTAL_NO_OF_EMPL']);
	$smarty->assign("established", $dataedit['ESTABLISHED_DATE']);

	$smarty->assign("delivered_project", $dataedit['TOTAL_NO_OF_DELIVERED_PROJECT']);
	$smarty->assign("area_delivered", $dataedit['AREA_DELIVERED']);
	$smarty->assign("ongoing_project", $dataedit['ONGOING_PROJECTS']);
	$smarty->assign("website", $dataedit['WEBSITE']);
	$smarty->assign("revenue", $dataedit['REVENUE']);
	$smarty->assign("debt", $dataedit['DEBT']);

	$arrContact = BuilderContactInfo($builderid);
	$smarty->assign("Contact", count($arrContact));
	$smarty->assign("arrContact", $arrContact);

}	
 
 
 /*****************City Data************/
	$CityDataArr	=	array();
 	
 	$qry	=	"SELECT CITY_ID,LABEL FROM ".CITY." WHERE ACTIVE = 1 ORDER BY LABEL ASC";
 	$res = mysql_query($qry,$db);
 	
 	while($data	=	mysql_fetch_array($res))
 	{
 		$CityDataArr[]	=	$data;		
 	}
 	$smarty->assign("CityDataArr", $CityDataArr);
 
 
   /***************Project dropdown*************/
 	$Project	=	array();
 	$qry	=	"SELECT PROJECT_ID,PROJECT_NAME FROM ".PROJECT." ORDER BY PROJECT_NAME ASC";
 	$res	=	mysql_query($qry);
 	
 	while ($dataArr = mysql_fetch_array($res))
	{
		array_push($Project, $dataArr);
	}
	$smarty->assign("Project", $Project);
	
	/*****************Builder Data************/
 $BuilderDataArr = array();
 $qry	=	"SELECT BUILDER_ID,BUILDER_NAME FROM ".RESI_BUILDER." ORDER BY BUILDER_NAME ASC";
 $res = mysql_query($qry,$db);
 while($data	=	mysql_fetch_array($res))
 {
 	$BuilderDataArr[]	=	$data;		
 }
 $smarty->assign("BuilderDataArr", $BuilderDataArr);
 
 
 

  /*****************City Data************/
 $CityDataArr	=	array();
 $qry	=	"SELECT CITY_ID,LABEL FROM ".CITY;
 $res = mysql_query($qry,$db);
 while($data	=	mysql_fetch_array($res))
 {
 	$CityDataArr[]	=	$data;		
 }
 $smarty->assign("CityDataArr", $CityDataArr);
?>
