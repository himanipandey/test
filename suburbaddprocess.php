<?php

$suburbid = $_REQUEST['suburbid'];
$smarty->assign("suburbid", $suburbid);

$cityId = $_REQUEST['c'];

if(isset($_POST['btnExit'])){
	header("Location:suburbList.php?page=1&sort=all&citydd={$cityId}");
}

if (isset($_POST['btnSave'])) {

		$txtCityName			=	trim($_POST['txtCityName']);
		$txtCityUrl				=	trim($_POST['txtCityUrl']);
		$txtMetaTitle			=	trim($_POST['txtMetaTitle']);
		$txtMetaKeywords		=	trim($_POST['txtMetaKeywords']);
		$txtMetaDescription		=	trim($_POST['txtMetaDescription']);
		$status					=	trim($_POST['status']);
		$desc					=	trim($_POST['desc']);	
		$old_sub_url			=	trim($_POST['old_sub_url']);	
		 
		 if( $txtCityUrl == '')   {
			 $ErrorMsg["txtCityUrl"] = "Please enter locality URL.";
		 } 
		 else 
		 {		
			if(!preg_match('/^property-in-[a-z0-9\-]+\.php$/',$txtCityUrl)){
				$ErrorMsg["txtCityUrl"] = "Please enter a valid url that contains only small characters, numerics & hyphen";
			}
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
		
		if(!is_array($ErrorMsg))
		{
			$updateQry = "UPDATE ".SUBURB." SET 
						 
						  META_TITLE			=	'".$txtMetaTitle."',
						  META_KEYWORDS		    =	'".$txtMetaKeywords."',
						  META_DESCRIPTION		=	'".$txtMetaDescription."',
						  ACTIVE				=	'".$status."',
						  URL					=	'".$txtCityUrl."',
						  DESCRIPTION			=	'".$desc."' WHERE SUBURB_ID ='".$suburbid."'";
						   
			mysql_query($updateQry);
			header("Location:suburbList.php?page=1&sort=all&citydd={$cityId}");
		}
		else
		{
			$smarty->assign("ErrorMsg", $ErrorMsg);
		}
	}	
	

if($suburbid!=''){

	$localityDetailsArray	=   ViewSuburbDetails($suburbid);
	$txtCityName			=	trim($localityDetailsArray['LABEL']);
	$txtCityUrl				=	trim($localityDetailsArray['URL']);
	$old_sub_url			=	trim($localityDetailsArray['URL']);
	$txtMetaTitle			=	trim($localityDetailsArray['META_TITLE']);
	$txtMetaKeywords		=	trim($localityDetailsArray['META_KEYWORDS']);
	$txtMetaDescription		=	trim($localityDetailsArray['META_DESCRIPTION']);
	$status					=	trim($localityDetailsArray['ACTIVE']);
	$desc					=	trim($localityDetailsArray['DESCRIPTION']);
	
	$smarty->assign("txtCityName", $txtCityName);
	$smarty->assign("txtCityUrl", $txtCityUrl);
	$smarty->assign("txtMetaTitle", $txtMetaTitle);
	$smarty->assign("txtMetaKeywords", $txtMetaKeywords);
	$smarty->assign("txtMetaDescription", $txtMetaDescription);
	$smarty->assign("status", $status);	
	$smarty->assign("desc", $desc);
	$smarty->assign("old_sub_url", $old_sub_url);
}
 
?>
