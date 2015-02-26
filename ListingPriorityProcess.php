<?php
//calling function for all the cities




$cityArray = City::CityArr();
$smarty->assign("cityArray", $cityArray);
$smarty->assign('dirname',$dirName);

$bankArray = BankList::arrBank();
$smarty->assign("bankArray",$bankArray);
$smarty->assign('dirname',$dirname);

$brokerArray = array();
$broker = Company::find('all', array('conditions'=>array("type = 'Broker' and status = 'Active'" )));
//print_r($brokerArray);
foreach ($broker as $v) {
    $tmp['id'] = $v->id;
    $tmp['name'] = $v->name;
    array_push($brokerArray, $tmp);
}
//print_r($brokerArray);
$smarty->assign("brokerArray",$brokerArray);


$smarty->assign('dirname',$dirname);


$orderby = 'ASC';
if(isset($_POST['asc_x'])) $orderby = 'ASC';
else if(isset($_POST['desc_x'])) $orderby = 'DESC';

$cityId = $_REQUEST['citydd']; 
if($cityId=='')
    $cityId=2;
//echo '*********************************', $cityId, '############################################ \n\n';

	
$smarty->assign('cityId',$cityId);
$smarty->assign('url12', TYPEAHEAD_API_URL);
$smarty->assign('url13', project_detail);

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
if(!empty($_REQUEST['placeType']))
{
    $nearPlaceTypesId = $_REQUEST['placeType']; 
    $smarty->assign('nearPlaceTypesId',$nearPlaceTypesId);
    //$suburbId = $_REQUEST['suburb'];
    //$smarty->assign('suburbId',$suburbId);
   // $projectArr = getProjectArr($suburbId,'suburb',$orderby);
}
//echo "<pre>";


$resaleListings = array();


 
// And you're ready to go!
//$url = "http://api.tuxx.co.uk/demo/server/time.php&app_id=" . $appID . "&app_key=" . $appKey;

//print("<pre>");
//print_r($resaleListings);

$jsonListing = htmlentities(json_encode($resaleListings));

$smarty->assign('resaleListings',$resaleListings);
$smarty->assign('jsonListing',$jsonListing);





?>
