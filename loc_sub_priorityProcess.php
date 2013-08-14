<?php
$cityId = $_REQUEST['citydd'];
$smarty->assign('cityId',$cityId);
$arraySubLoc = array();
$suburbArr = array();
$localityArr = array();
$orderby = 'ASC';

if(isset($_POST['asc_x'])) $orderby = 'ASC';
else if(isset($_POST['desc_x'])) $orderby = 'DESC';

$arraySubLoc = getSubLocData($cityId, $orderby);

//echo "<pre>";print_r($arraySubLoc);echo "</pre>";
$smarty->assign("arraySubLoc", $arraySubLoc);
//echo "<pre>";print_r($arraySubLoc);echo "</pre>";

//calling function for all the cities
$cityArray = getAllCities();
$smarty->assign("cityArray", $cityArray);
$smarty->assign('dirname',$dirName);
?>
