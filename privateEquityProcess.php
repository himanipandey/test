<?php


$peList = Company::getCompanyByType("Private Equity"); 
$smarty->assign("peList", $peList);
$builderList = ResiBuilder::ProjectSearchBuilderEntityArr();
$smarty->assign("builderList", $builderList);



/*
$arrSearchFields = array();
if( $_REQUEST['builder'] != '' ) 
    $arrSearchFields['builder_id'] = $_REQUEST['builder'];
$getSearchResult = ResiProject::getAllSearchResult($arrSearchFields);
*/

?>