<?php
//calling function for all the cities



$cityArray = City::CityArr();
$smarty->assign("cityArray", $cityArray);
$smarty->assign('dirname',$dirName);

$orderby = 'ASC';
if(isset($_POST['asc_x'])) $orderby = 'ASC';
else if(isset($_POST['desc_x'])) $orderby = 'DESC';

$cityId = $_REQUEST['citydd']; 

	

$smarty->assign('cityId',$cityId);

$suburbArr = array();
$suburbArr = Suburb::SuburbArr($cityId);
$smarty->assign('suburbArr',$suburbArr);

	

$locArr = array();
$locArr = Locality::getLocalityByCity($cityId);
$smarty->assign('localityArr',$locArr);

$NearPlaceTypesArr = NearPlaceTypes::NearPlaceTypesArr();

$smarty->assign("nearPlaceTypesArray", $NearPlaceTypesArr);


if(!empty($_REQUEST['near_place_type']))
{
    $nearPlaceTypesId = $_REQUEST['near_place_type']; 
    $smarty->assign('nearPlaceTypesId',$nearPlaceTypesId);
    //$suburbId = $_REQUEST['suburb'];
    //$smarty->assign('suburbId',$suburbId);
   // $projectArr = getProjectArr($suburbId,'suburb',$orderby);
}


$NearPlacesArr = array();

	

if(!empty($_REQUEST['suburb']))
{
    $suburbId = $_REQUEST['suburb'];
    $smarty->assign('suburbId',$suburbId);
    if(!empty($nearPlaceTypesId))
    {
        $NearPlacesArr = getNearPlacesArr($suburbId,'suburb',$orderby, $nearPlaceTypesId);
    }
    else
    {
    $NearPlacesArr = getNearPlacesArr($suburbId,'suburb',$orderby);
    }
}
else if(!empty($_REQUEST['locality']) && !empty($cityId))
{
    $localityId = $_REQUEST['locality'];
    $smarty->assign('localityId',$localityId);
    if(!empty($nearPlaceTypesId))
    {
        $NearPlacesArr = getNearPlacesArr( $cityId, $localityId,'locality',$orderby, $nearPlaceTypesId);
        //print_r("here");
    }
    else
    {
        //print_r("here");
    $NearPlacesArr = getNearPlacesArr($cityId, $localityId,'locality',$orderby);
    //print_r($NearPlacesArr);
    }
}
else if(!empty($cityId))
{
    if(!empty($nearPlaceTypesId))
    {
        $NearPlacesArr = getNearPlacesArrfromcity($cityId, $orderby, $nearPlaceTypesId);
    }
    else
    {
        //print_r("here");
       $NearPlacesArr = getNearPlacesArrfromcity($cityId, $orderby);
       //print_r($NearPlacesArr);
    }
   
    //print_r($NearPlacesArr);

}
$smarty->assign('nearPlacesArr',$NearPlacesArr);
?>
