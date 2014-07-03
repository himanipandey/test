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
$smarty->assign('localityId',$_REQUEST['localityId']);
//echo "<pre>";
//print_r($_REQUEST);//die;
if(!empty($_REQUEST['placeType']))
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
    //if($_REQUEST['locality'] == '')
       //$_REQUEST['localityId'] = '';
    if(!empty($_REQUEST['localityId']) && !empty($cityId))
    {
        $localityId = $_REQUEST['localityId'];
        $smarty->assign('localityId',$localityId);
       // echo $nearPlaceTypesId;//die;
        if(!empty($nearPlaceTypesId))
        {
            //die("here0");
            $NearPlacesArr = getNearPlacesArr($_REQUEST['status'], $cityId, $localityId,'locality',$orderby, $nearPlaceTypesId);
            
        }
        else
        {
            //die("here");
        $NearPlacesArr = getNearPlacesArr($_REQUEST['status'], $cityId, $localityId,'locality',$orderby);
        //print_r($NearPlacesArr);
        }
    }
    else if(!empty($cityId))
    {
        //die("here2");
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
    if(!empty($_REQUEST['localityId']) && !empty($cityId))
    {
        //die("here3");
        $localityId = $_REQUEST['localityId'];
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
