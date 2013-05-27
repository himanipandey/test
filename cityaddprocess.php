<?php

$cityid = $_REQUEST['cityid'];
$smarty->assign("cityid", $cityid);

if(isset($_POST['btnExit'])){
	header("Location:CityList.php?page=1&sort=all");
}

if (isset($_POST['btnSave'])) {

		$txtCityName			=	trim($_POST['txtCityName']);
		$txtCityUrlOld			=	trim($_POST['txtCityUrlOld']);
		$DisplayOrder			=	trim($_POST['DisplayOrder']);
		$txtMetaTitle			=	trim($_POST['txtMetaTitle']);
		$txtMetaKeywords		=	trim($_POST['txtMetaKeywords']);
		$txtMetaDescription		=	trim($_POST['txtMetaDescription']);
		$status					=	trim($_POST['status']);
		$desc					=	trim($_POST['desc']);	
		
		
		$smarty->assign("txtCityName", $txtCityName);
		$smarty->assign("txtCityUrl", $txtCityUrl);
		$smarty->assign("txtCityUrlOld", $txtCityUrlOld);
		$smarty->assign("DisplayOrder", $DisplayOrder);
		$smarty->assign("txtMetaTitle", $txtMetaTitle);
		$smarty->assign("txtMetaKeywords", $txtMetaKeywords);
		$smarty->assign("txtMetaDescription", $txtMetaDescription);
		$smarty->assign("status", $status);
		$smarty->assign("desc", $desc);
		 
		if( $txtCityName == '')  {
			 $ErrorMsg["txtCityName"] = "Please enter City name.";
		   }
	       
	    if(!preg_match('/^[a-zA-z0-9 ]+$/', $txtCityName)){
	       		$ErrorMsg["txtCityName"] = "Special characters are not allowed";
	       }
		if( $DisplayOrder == '')   {
			 $ErrorMsg["DisplayOrder"] = "Please enter display order.";
		   }
		if( $txtMetaTitle == '')   {
			 $ErrorMsg["txtMetaTitle"] = "Please enter meta title.";
		   }
		if( $txtMetaKeywords == '')  {
			 $ErrorMsg["txtMetaKeywords"] = "Please enter meta keywords.";
		   }
		if( $txtMetaDescription == '')  {
			 $ErrorMsg["txtMetaDescription"] = "Please enter meta description.";
		   }
		if( $desc == '')   {
			 $ErrorMsg["desc"] = "Please enter city description.";
		   }  
			
		   if($cityid == ''){
				$qryCity = "SELECT * FROM ".CITY." WHERE LABEL = '".$txtCityName."'";
				$res     = mysql_query($qryCity);
				if(mysql_num_rows($res)>0)
				{
					$ErrorMsg["txtCityName"] = "This CITY Already exists";
				}
		   }
		/*******end city url already exists*******/ 
	$url = urlCreaationDynamic('property-in-',$txtCityName);
	$smarty->assign("ErrorMsg", $ErrorMsg);
	if(is_array($ErrorMsg)) {
		
	} 
	else if ($cityid == '') {	
		InsertCity($txtCityName, $url, $DisplayOrder,$txtMetaTitle,$txtMetaKeywords,$txtMetaDescription,$status,$desc);
		header("Location:CityList.php?page=1&sort=all");
		
	}else if($cityid!= ''){
	
		$updateQry = "UPDATE ".CITY." SET 
					  LABEL					=	'".$txtCityName."',
					  META_TITLE			=	'".$txtMetaTitle."',
					  META_KEYWORDS		    =	'".$txtMetaKeywords."',
					  META_DESCRIPTION		=	'".$txtMetaDescription."',
					  ACTIVE				=	'".$status."',
					  URL					=	'".$url."',
					  DISPLAY_ORDER			=	'".$DisplayOrder."',
					  DESCRIPTION			=	'".$desc."' WHERE CITY_ID='".$cityid."'";
		mysql_query($updateQry);
		if($url != $txtCityUrlOld)
			updateProjectUrl($cityid,'city','');
		insertUpdateInRedirectTbl($url,$txtCityUrlOld);
		header("Location:CityList.php?page=1&sort=all");
	}	
	
}

elseif($cityid!=''){

	$cityDetailsArray		=   ViewCityDetails($cityid);
	$txtCityName			=	trim($cityDetailsArray['LABEL']);
	$txtCityUrlOld			=	trim($cityDetailsArray['URL']);
	$DisplayOrder			=	trim($cityDetailsArray['DISPLAY_ORDER']);
	$txtMetaTitle			=	trim($cityDetailsArray['META_TITLE']);
	$txtMetaKeywords		=	trim($cityDetailsArray['META_KEYWORDS']);
	$txtMetaDescription		=	trim($cityDetailsArray['META_DESCRIPTION']);
	$status					=	trim($cityDetailsArray['ACTIVE']);
	$desc					=	trim($cityDetailsArray['DESCRIPTION']);
	
	$smarty->assign("txtCityName", $txtCityName);
	$smarty->assign("txtCityUrlOld", $txtCityUrlOld);
	$smarty->assign("DisplayOrder", $DisplayOrder);
	$smarty->assign("txtMetaTitle", $txtMetaTitle);
	$smarty->assign("txtMetaKeywords", $txtMetaKeywords);
	$smarty->assign("txtMetaDescription", $txtMetaDescription);
	$smarty->assign("status", $status);
	$smarty->assign("desc", $desc);

	
}

 
?>
