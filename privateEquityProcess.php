<?php

$peList = Company::getCompanyByType("PrivateEquity");
$smarty->assign("peList", $peList);
$builderList = ResiBuilder::ProjectSearchBuilderEntityArr();
$smarty->assign("builderList", $builderList);

//$peDeals = array();
$arrSearchFields = array();
$peDeals = PEDeals::getAllPEDeals();
$smarty->assign("pedeals", $peDeals);

//print("<pre>");
//print_r($peDeals);
//echo json_encode($projectData);

?>
