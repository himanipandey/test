<?php
//calling function for all the cities
$cityArray = CityArr();
$smarty->assign("cityArray", $cityArray);
$smarty->assign('dirname',$dirName);

$orderby = 'ASC';
if(isset($_POST['asc_x'])) $orderby = 'ASC';
else if(isset($_POST['desc_x'])) $orderby = 'DESC';

$cityId = $_REQUEST['citydd'];
$smarty->assign('cityId',$cityId);

$suburbArr = array();
$suburbArr = SuburbArr($cityId);
$smarty->assign('suburbArr',$suburbArr);

$locArr = array();
$locArr = localityArr($cityId);
$smarty->assign('localityArr',$locArr);

$projectArr = array();
if(!empty($_REQUEST['suburb']))
{
    $suburbId = $_REQUEST['suburb'];
    $smarty->assign('suburbId',$suburbId);
    $projectArr = getProjectArr($suburbId,'suburb',$orderby);
}
else if(!empty($_REQUEST['locality']))
{
    $localityId = $_REQUEST['locality'];
    $smarty->assign('localityId',$localityId);
    $projectArr = getProjectArr($localityId,'locality',$orderby);
}
else if(!empty($cityId))
{
    $projectArr = getProjectArr($cityId,'city',$orderby);
}
$smarty->assign('projectArr',$projectArr);
?>
