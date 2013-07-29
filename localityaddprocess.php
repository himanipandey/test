<?php

include_once("function/locality_functions.php");

$localityid = $_REQUEST['localityid'];
$smarty->assign("localityid", $localityid);

$cityId = $_REQUEST['c'];

if(isset($_POST['btnExit'])){
	header("Location:localityList.php?page=1&sort=all&citydd={$cityId}");
}

if (isset($_POST['btnSave'])) {

		$txtCityName			=	trim($_POST['txtCityName']);
		$txtCityUrl				=	trim($_POST['txtCityUrl']);
		$txtMetaTitle			=	trim($_POST['txtMetaTitle']);
		$txtMetaKeywords		=	trim($_POST['txtMetaKeywords']);
		$txtMetaDescription		=	trim($_POST['txtMetaDescription']);
		$status					=	trim($_POST['status']);
		$desc					=	trim($_POST['desc']);
		$old_loc_url			=	trim($_POST['old_loc_url']);
		$priority               =   trim($_POST['priority']);
		
		$smarty->assign("txtCityName", $txtCityName);
		$smarty->assign("old_loc_url", $old_loc_url);
		$smarty->assign("txtMetaTitle", $txtMetaTitle);
		$smarty->assign("txtMetaKeywords", $txtMetaKeywords);
		$smarty->assign("txtMetaDescription", $txtMetaDescription);
		$smarty->assign("status", $status);	
		$smarty->assign("desc", $desc);
		$smarty->assign("priority", $priority);
		 
		  if( $txtCityName == '')   {
			 $ErrorMsg["txtCityName"] = "Please enter locality name.";
		   }
		  
		   if(!preg_match('/^[a-zA-z0-9 ]+$/', $txtCityName)){
		   	$ErrorMsg["txtCityName"] = "Special characters are not allowed";
		   }

		   $txtCityUrl = createLocalityURL($txtCityName, $dataCity['LABEL']);

		   if( $txtMetaTitle == '')   {
		   	$ErrorMsg["txtMetaTitle"] = "Please enter meta title.";
		   }
		   
		if( $txtMetaKeywords == '')  {
			 $ErrorMsg["txtMetaKeywords"] = "Please enter meta keywords.";
		   }
		if( $txtMetaDescription == '')  {
			 $ErrorMsg["txtMetaDescription"] = "Please enter meta description.";
		   }

		   if( empty($priority) || $priority < 1 || $priority > 100) {
		       $ErrorMsg["priority"] = "Please enter valid priority(1-100)";
		   }
		/*******locality url already exists**********/
		   if($localityid == '')
		   {
				$qryLocality = "SELECT * FROM ".LOCALITY." WHERE LABEL = '".$txtCityName."'";
				
				$res     = mysql_query($qryLocality) or die(mysql_error());
				if(mysql_num_rows($res)>0)
				{
					$ErrorMsg["txtCityName"] = "This Locality Already exists";
				}
		   }
		/*******end locality url already exists*******/ 

		   if(!is_array($ErrorMsg))
		   {
		       $qryCity = "SELECT C.LABEL FROM locality L join city C on (C.city_id = L.city_id) where L.locality_id = $localityid";
		       $resCity = mysql_query($qryCity);
		       $dataCity = mysql_fetch_assoc($resCity);
		       mysql_free_result($resCity);
		       $txtCityUrl = createLocalityURL($txtCityName, $dataCity['LABEL']);
		        
				 $updateQry = "UPDATE ".LOCALITY." SET 
					 
					  LABEL					=	'".$txtCityName."',
					   META_TITLE			=	'".$txtMetaTitle."',		
					  META_KEYWORDS		    =	'".$txtMetaKeywords."',
					  META_DESCRIPTION		=	'".$txtMetaDescription."',
					  ACTIVE				=	'".$status."',
					  URL					=	'".$txtCityUrl."',
					  PRIORITY				=	$priority,
					  DESCRIPTION			=	'".$desc."' WHERE LOCALITY_ID='".$localityid."'";
					   
				$up = mysql_query($updateQry);
				if($up)
				{
					if($txtCityUrl != $old_loc_url)
						insertUpdateInRedirectTbl($txtCityUrl,$old_loc_url);
			   	 	header("Location:localityList.php?page=1&sort=all&citydd={$cityId}");
				}
		}
		else
		{
			$smarty->assign("ErrorMsg", $ErrorMsg);
		}
	}	
	

elseif($localityid!=''){

	$localityDetailsArray	=   ViewLocalityDetails($localityid);
	$txtCityName			=	trim($localityDetailsArray['LABEL']);
	$txtMetaTitle			=	trim($localityDetailsArray['META_TITLE']);
	$txtMetaKeywords		=	trim($localityDetailsArray['META_KEYWORDS']);
	$txtMetaDescription		=	trim($localityDetailsArray['META_DESCRIPTION']);
	$status					=	trim($localityDetailsArray['ACTIVE']);
	$desc					=	trim($localityDetailsArray['DESCRIPTION']);
	$priority				=	trim($localityDetailsArray['PRIORITY']);
	
	$smarty->assign("txtCityName", $txtCityName);
	$smarty->assign("txtMetaTitle", $txtMetaTitle);
	$smarty->assign("txtMetaKeywords", $txtMetaKeywords);
	$smarty->assign("txtMetaDescription", $txtMetaDescription);
	$smarty->assign("status", $status);	
	$smarty->assign("desc", $desc);
	$smarty->assign("priority", $priority ? $priority : 100);
}
 
?>
