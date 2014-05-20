<?php


$peList = Company::getCompanyByType("Private Equity"); 
$smarty->assign("peList", $peList);
$builderList = ResiBuilder::ProjectSearchBuilderEntityArr();
$smarty->assign("builderList", $builderList);



//101392
$projectData = array();
$arrSearchFields = array();

    $arrSearchFields['builder_id'] = 101392;
$getSearchResult = (array)ResiProject::getAllSearchResult($arrSearchFields);
foreach ($getSearchResult as $k => $v) {
	$tmpArr = array();
	$tmpArr['id'] = $v->project_id;
	$tmpArr['name'] = $v->project_name.$v->project_address;
	array_push($projectData, $tmpArr);
}
echo json_encode($projectData);

?>