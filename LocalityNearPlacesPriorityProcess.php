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
//print_r($locArr);
$smarty->assign('localityArr',$locArr);

$NearPlaceTypesArr = NearPlaceTypes::getNearPlaceTypesEnabled();
//print_r($NearPlaceTypesArr);
$smarty->assign("nearPlaceTypesArray", $NearPlaceTypesArr);
$smarty->assign('status',$_REQUEST['status']);
$smarty->assign('placeType',$_REQUEST['placeType']);
//echo "<pre>";
//print_r($_REQUEST);die;
if(!empty($_REQUEST['near_place_type']))
{
    $nearPlaceTypesId = $_REQUEST['placeType']; 
    $smarty->assign('nearPlaceTypesId',$nearPlaceTypesId);
    //$suburbId = $_REQUEST['suburb'];
    //$smarty->assign('suburbId',$suburbId);
   // $projectArr = getProjectArr($suburbId,'suburb',$orderby);
}
//echo "<pre>";
//print_r($_REQUEST);
$NearPlacesArr = array();
if(isset($_REQUEST['submit'])) {
    if(!empty($_REQUEST['locality']) && !empty($cityId))
    {
        $localityId = $_REQUEST['locality'];
        $smarty->assign('localityId',$localityId);
        if(!empty($nearPlaceTypesId))
        {
            $NearPlacesArr = getNearPlacesArr($_REQUEST['status'], $cityId, $localityId,'locality',$orderby, $nearPlaceTypesId);
            
        }
        else
        {
            //print_r("here");
        $NearPlacesArr = getNearPlacesArr($_REQUEST['status'], $cityId, $localityId,'locality',$orderby);
        //print_r($NearPlacesArr);
        }
    }
    else if(!empty($cityId))
    {
        if(!empty($nearPlaceTypesId))
        {
            $NearPlacesArr = getNearPlacesArrfromcity($_REQUEST['status'], $cityId, $orderby, $nearPlaceTypesId);
        }
        else
        {
            //print_r("here");
           $NearPlacesArr = getNearPlacesArrfromcity($_REQUEST['status'], $cityId, $orderby);
           //print_r($NearPlacesArr);
        }

        //print_r($NearPlacesArr);

    }
}
else{
    if(!empty($_REQUEST['locality']) && !empty($cityId))
    {
        $localityId = $_REQUEST['locality'];
        $smarty->assign('localityId',$localityId);
       /* if(!empty($nearPlaceTypesId))
        {
            $NearPlacesArr = getNearPlacesArr( $cityId, $localityId,'locality',$orderby, $nearPlaceTypesId);
            //print_r("here");
        }
        else
        {
            //print_r("here");
        $NearPlacesArr = getNearPlacesArr($cityId, $localityId,'locality',$orderby);
        //print_r($NearPlacesArr);
        }*/
    }
}
/*else if(!empty($cityId))
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

}*/
$smarty->assign('nearPlacesArr',$NearPlacesArr);
?>
