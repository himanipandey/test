<?php

    $accessCity = '';
    if( $cityAuth == false )
       $accessCity = "No Access";
    $smarty->assign("accessCity",$accessCity);

$cityid = $_REQUEST['cityid'];
$smarty->assign("cityid", $cityid);

if(isset($_POST['btnExit'])){
	header("Location:CityList.php?page=1&sort=all");
}

if (isset($_POST['btnSave'])) {

		$txtCityName			=	trim($_POST['txtCityName']);
		$txtCityUrl				=	'';
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
		   
        /*******city url already exists**********/
        $cityURL = "";
        if ($cityid != '') {
            $cityURL = "and CITY_ID!=".$cityid;       
        }		   
        $qryCityUrl = "SELECT * FROM ".CITY." WHERE LABEL = '".$txtCityName."'".$cityURL;
        $resUrl     = mysql_query($qryCityUrl);
	    if(mysql_num_rows($resUrl)>0){
		    $ErrorMsg["txtCityName"] = "This city Already exists";
		}
    
		/*******end city url already exists*******/ 
	
    $txtCityUrl = preg_replace( '/\s+/', '-', $txtCityName.'-real-estate');
	$smarty->assign("ErrorMsg", $ErrorMsg);
	if(is_array($ErrorMsg)) {
		
	} 
	else if ($cityid == '') {	
		$city_id = InsertCity($txtCityName, $txtCityUrl, $DisplayOrder,$status,$desc);
		if($city_id){
                    $seoData['meta_title'] = $txtMetaTitle;
                    $seoData['meta_keywords'] = $txtMetaKeywords;
                    $seoData['meta_description'] = $txtMetaDescription;
                    $seoData['table_id'] = $city_id;
                    $seoData['table_name'] = 'city';
                    $seoData['updated_by'] = $_SESSION['adminId'];
                    SeoData::insetUpdateSeoData($seoData);
        }
		header("Location:CityList.php?page=1&sort=all");
		
	}else if($cityid!= ''){
	
		$updateQry = "UPDATE ".CITY." SET 
					  LABEL					=	'".$txtCityName."',
					  STATUS				=	'".$status."',
					  URL					=	'".$txtCityUrl."',
					  DISPLAY_ORDER			=	'".$DisplayOrder."',
					  DESCRIPTION			=	'".$desc."' WHERE CITY_ID='".$cityid."'";
		$rt = mysql_query($updateQry);
		if($rt){
                    $seoData['meta_title'] = $txtMetaTitle;
                    $seoData['meta_keywords'] = $txtMetaKeywords;
                    $seoData['meta_description'] = $txtMetaDescription;
                    $seoData['table_id'] = $cityid;
                    $seoData['table_name'] = 'city';
                    $seoData['updated_by'] = $_SESSION['adminId'];
                    SeoData::insetUpdateSeoData($seoData);
        }
		header("Location:CityList.php?page=1&sort=all");
	}	
	
}

elseif($cityid!=''){

	$cityDetailsArray		=   ViewCityDetails($cityid);
	$txtCityName			=	trim($cityDetailsArray['LABEL']);
	$txtCityUrl				=	trim($cityDetailsArray['URL']);
	$txtCityUrlOld			=	trim($cityDetailsArray['URL']);
	$DisplayOrder			=	trim($cityDetailsArray['DISPLAY_ORDER']);
	$status					=	trim($cityDetailsArray['STATUS']);
	$desc					=	trim($cityDetailsArray['DESCRIPTION']);
	
	//getting meta data
	$getSeoData = SeoData::getSeoData($cityid, 'city');
	$txtMetaTitle			=	trim($getSeoData[0]->meta_title);
	$txtMetaKeywords		=	trim($getSeoData[0]->meta_keywords);
	$txtMetaDescription		=	trim($getSeoData[0]->meta_description);
	
	//getting city aliases
	//$getAliasesArray = getAllAliases('city', $cityid);
	//$getSuburbAliasesArr = getSpecificAliases('city', $cityid, 'suburb');
	$getLandmarkAliasesArr = getLandmarkAliases('city', $cityid);
	//$getGenericAliasesArr = getSpecificAliases('city', $cityid, 'aliases');
	//print_r($getGenericAliasesArr);
	//$genericJson = json_encode($getGenericAliasesArr);
	$landmarkJson = json_encode($getLandmarkAliasesArr);
	//$suburbJson = json_encode($getSuburbAliasesArr);
	//echo $landmarkJson;


	$smarty->assign("txtCityName", $txtCityName);
	$smarty->assign("txtCityUrl", $txtCityUrl);
	$smarty->assign("txtCityUrlOld", $txtCityUrlOld);
	$smarty->assign("DisplayOrder", $DisplayOrder);
	$smarty->assign("txtMetaTitle", $txtMetaTitle);
	$smarty->assign("txtMetaKeywords", $txtMetaKeywords);
	$smarty->assign("txtMetaDescription", $txtMetaDescription);
	$smarty->assign("status", $status);
	$smarty->assign("desc", $desc);
	//$smarty->assign("allAliases", $getAliasesArray);
	//$smarty->assign("suburbAliases", $getSuburbAliasesArr);
	$smarty->assign("landmarkAliases", $getLandmarkAliasesArr);
	//$smarty->assign("genericAliases", $getGenericAliasesArr);

	//$smarty->assign("genericJson", $genericJson);
	//$smarty->assign("suburbJson", $suburbJson);
	$smarty->assign("landmarkJson", $landmarkJson);


	
}


 
?>
