<?php
//calling function for all the cities



$cityArray = City::CityArr();
$smarty->assign("cityArray", $cityArray);
$smarty->assign('dirname',$dirName);

$orderby = 'ASC';
if(isset($_POST['asc_x'])) $orderby = 'ASC';
else if(isset($_POST['desc_x'])) $orderby = 'DESC';

$cityId = $_REQUEST['citydd']; 
//$cityId  = 1;
	

$smarty->assign('cityId',$cityId);

$suburbArr = array();


	
//die("here");
$locArr = array();

//$locArr = Locality::getLocalityByCity($cityId);
//die("here");
//print_r($locArr);
$smarty->assign('localityArr',$locArr);
//die("here");
$NearPlaceTypesArr = NearPlaceTypes::getNearPlaceTypesEnabled();
//print_r($NearPlaceTypesArr);
$smarty->assign("nearPlaceTypesArray", $NearPlaceTypesArr);

//die("here");
if(!empty($_REQUEST['near_place_type']))
{
    $nearPlaceTypesId = $_REQUEST['near_place_type']; 
    $smarty->assign('nearPlaceTypesId',$nearPlaceTypesId);
    //$suburbId = $_REQUEST['suburb'];
    //$smarty->assign('suburbId',$suburbId);
   // $projectArr = getProjectArr($suburbId,'suburb',$orderby);
}


$AliasesArr = array();
$AliasesArr = getAllAliases();
//print_r($AliasesArr);
//die("here");
$smarty->assign('aliasesArr',$AliasesArr);
 

?>
