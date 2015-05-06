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

		$txtCityName = replaceSpaces(trim($_POST['txtCityName']));
		$txtCityUrl = '';
		$txtCityUrlOld = trim($_POST['txtCityUrlOld']);
		$DisplayOrder = trim($_POST['DisplayOrder']);
		$txtMetaTitle = trim($_POST['txtMetaTitle']);
		$txtMetaKeywords = trim($_POST['txtMetaKeywords']);
		$txtMetaDescription = trim($_POST['txtMetaDescription']);
		$status = trim($_POST['status']);
		$desc = trim($_POST['desc']);
		$oldDesc = trim($_POST['oldDesc']);
		$content_flag =	trim($_POST['content_flag']);	
                
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
	
    	//$txtCityUrl = preg_replace( '/\s+/', '-', $txtCityName.'-real-estate');
	$txtCityUrl = 'projects-in-'.preg_replace('/\s+/', '-', trim($txtCityName));	
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
                    
                            $cont_flag = new TableAttributes();
                            $cont_flag->table_name = 'city';
                            $cont_flag->table_id = $city_id;
                            $cont_flag->attribute_name = 'DESC_CONTENT_FLAG';
                            $cont_flag->attribute_value = ($_POST["content_flag"])? 1 : 0;
                            $cont_flag->updated_by = $_SESSION['adminId'];
                            $cont_flag->save();				
                 
        }
	header("Location:CityList.php?page=1&sort=all");
		
	}else if($cityid!= ''){
	
		$updateQry = "UPDATE ".CITY." SET 
                            LABEL					=	'".$txtCityName."',
                            STATUS				=	'".$status."',
                            URL					=	'".strtolower($txtCityUrl)."',
                            DISPLAY_ORDER			=	'".$DisplayOrder."',
                            updated_at = now(),
                            updated_by			= '" .$_SESSION['adminId']."',
                            DESCRIPTION	= '" . d_($desc) . "' WHERE CITY_ID='".$cityid."'";
		$rt = mysql_query($updateQry);
		if($rt){
                    if($txtCityUrlOld != $txtCityUrl) { //update locality project and suburb url
                        $localityList = Locality::getAllLocalityByCity($cityid);
                        $projList = array();
                        foreach($localityList as $localityList) {
                                $locId['locality_id'] = $localityList->locality_id;
                                if($locId['locality_id'] != '') {
                                    $projList = ResiProject::getAllSearchResult($locId); //all project of a locality
                                    foreach($projList as $value) {
                                        $projUrl = createProjectURL($localityList->cityname, $localityList->label, $value->builder_name, $value->project_name, $value->project_id);
                                        $qryProUrl = "update resi_project set 
                                                      updated_by			= '" .$_SESSION['adminId']."',
                                                      project_url = '".$projUrl."' where project_id = '".$value->project_id."'";
                                        $resProjUrl = mysql_query($qryProUrl) or die(mysql_error());
                                        
                                    }
                                }

                            $locUrl = createLocalityURL($localityList->label,$txtCityName,$localityList->locality_id,'locality');
                            $updateLoc = "UPDATE ".LOCALITY." SET
                                updated_by			= '" .$_SESSION['adminId']."',
                                URL	= '".$locUrl."',
                                updated_at = now() WHERE LOCALITY_ID='".$localityList->locality_id."'";
                            $rt = mysql_query($updateLoc) or die(mysql_error()." loc url update");
                            
                        }
                        $subArr = Suburb::SuburbArr($cityid);
                        foreach($subArr as $k=>$subList) {
                            $subUrl = createLocalityURL($subList,$txtCityName,$k,'suburb');
                            $updateSub = "UPDATE ".SUBURB." SET 
                                updated_by			= '" .$_SESSION['adminId']."',
                                URL	= '".$subUrl."',
                                updated_at = now() WHERE SUBURB_ID='".$k."'";
                            $rt = mysql_query($updateSub) or die(mysql_error()." sub url update");
                        }
                       
                    }
                    $seoData['meta_title'] = $txtMetaTitle;
                    $seoData['meta_keywords'] = $txtMetaKeywords;
                    $seoData['meta_description'] = $txtMetaDescription;
                    $seoData['table_id'] = $cityid;
                    $seoData['table_name'] = 'city';
                    $seoData['updated_by'] = $_SESSION['adminId'];
                    SeoData::insetUpdateSeoData($seoData);
                    
                    ## - descripion content flag handeling
						$cont_flag = TableAttributes::find('all',array('conditions' => array('table_id' => $cityid, 'attribute_name' => 'DESC_CONTENT_FLAG', 'table_name' => 'city' )));					   
					   if($cont_flag){
							$content_flag = ($_POST["content_flag"])? 1 : 0;
							if(is_numeric($content_flag)){
								$cont_flag = TableAttributes::find($cont_flag[0]->id);
								$cont_flag->updated_by = $_SESSION['adminId'];
								$cont_flag->attribute_value = $content_flag;
								$cont_flag->save();		
							}
						}else{
							$cont_flag = new TableAttributes();
							$cont_flag->table_name = 'city';
							$cont_flag->table_id = $cityid;
							$cont_flag->attribute_name = 'DESC_CONTENT_FLAG';
							$cont_flag->attribute_value = ($_POST["content_flag"])? 1 : 0;
							$cont_flag->updated_by = $_SESSION['adminId'];
							$cont_flag->save();				
						}
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
	
	$smarty->assign("txtCityName", $txtCityName);
	$smarty->assign("txtCityUrl", $txtCityUrl);
	$smarty->assign("txtCityUrlOld", $txtCityUrlOld);
	$smarty->assign("DisplayOrder", $DisplayOrder);
	$smarty->assign("txtMetaTitle", $txtMetaTitle);
	$smarty->assign("txtMetaKeywords", $txtMetaKeywords);
	$smarty->assign("txtMetaDescription", $txtMetaDescription);
	$smarty->assign("status", $status);
	$smarty->assign("desc", $desc);
	
	$contentFlag = TableAttributes::find('all',array('conditions' => array('table_id' => $cityid, 'attribute_name' => 'DESC_CONTENT_FLAG', 'table_name' => 'city')));   
            
	$smarty->assign("contentFlag", $contentFlag[0]->attribute_value);
	$smarty->assign("dept", $_SESSION['DEPARTMENT']);
	
}


 
?>
