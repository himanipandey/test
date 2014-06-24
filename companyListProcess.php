<?php

$compid= $_REQUEST['compid'];

$typeArr = Company::getCompanyType(); 
$smarty->assign("comptype", $typeArr);

$cityArray = City::CityArr();
$smarty->assign("cityArray", $cityArray);

if($compid){
	$compArr = Company::getAllCompany($compid);
}
else{
	$compArr = Company::getAllCompany();
}

$smarty->assign("compArr", $compArr);
//print("<pre>");
//print_r($compArr);
//$co = 
/*
$builderList = ResiBuilder::ProjectSearchBuilderEntityArr();
$smarty->assign("builderList", $builderList);



$arrSearchFields = array();
if( $_REQUEST['builder'] != '' ) 
      $arrSearchFields['builder_id'] = $_REQUEST['builder'];
$getSearchResult = ResiProject::getAllSearchResult($arrSearchFields);

*/
?>