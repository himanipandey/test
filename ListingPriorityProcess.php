<?php
$listingDelAuth = isUserPermitted('listing', 'delete');
$smarty->assign('listingDelAuth',$listingDelAuth);
if(in_array($_SESSION["ROLE"], array("cityHeadpropertyAdvisor","teamLeadpropertyAdvisors"))){
    $cityArray = getUserCities($_SESSION["adminId"]);
}  else {
    $cityArray = City::CityArr();
}

$smarty->assign("cityArray", $cityArray);
$smarty->assign('dirname',$dirName);

$bankArray = BankList::arrBank();
$smarty->assign("bankArray",$bankArray);
$smarty->assign('dirname',$dirname);

$project_id = $_REQUEST['project'];


$brokerArray = array();
$broker = Company::find('all', array('conditions'=>array("type = 'Broker' and status = 'Active'" )));

foreach ($broker as $v) {
    $tmp['id'] = $v->id;
    $tmp['name'] = $v->name;
    array_push($brokerArray, $tmp);
}

$smarty->assign("brokerArray",$brokerArray);
$smarty->assign('dirname',$dirname);


$orderby = 'ASC';
if(isset($_POST['asc_x'])) $orderby = 'ASC';
else if(isset($_POST['desc_x'])) $orderby = 'DESC';

$cityId = $_REQUEST['citydd']; 
if($cityId=='')
    $cityId=2;
	
$smarty->assign('cityId',$cityId);
$smarty->assign('url12', TYPEAHEAD_API_URL);
$smarty->assign('url13', project_detail);
$smarty->assign('url_phase_id',phase_detail);
$smarty->assign('url14', LISTING_API_URL);
$smarty->assign('url15', ADMIN_USER_LOGIN_API_URL);

$suburbArr = array();
$suburbArr = Suburb::SuburbArr($cityId);
$smarty->assign('suburbArr',$suburbArr);
	

$locArr = array();
$locArr = Locality::getLocalityByCity($cityId);
$smarty->assign('localityArr',$locArr);


$dirctionsArr = array();
$res = MasterDirections::find('all');
foreach ($res as $v) {
    $tmp = array();
    $tmp['id'] = $v->id;
    $tmp['direction'] = $v->direction;
    array_push($dirctionsArr, $tmp);
}


$smarty->assign('dirctionsArr',$dirctionsArr);
$resaleListings = array();

$uriLogin = ADMIN_USER_LOGIN_API_URL; //master

$uriListing = LISTING_API_URL."?listingCategory=Resale&cityId={$cityId}&start=0&fields=seller,seller.fullName,id,listing,listing.facing,listing.jsonDump,listing.description,listing.remark,listing.homeLoanBankId,listing.flatNumber,listing.noOfCarParks,listing.negotiable,listing.transferCharges,listing.plc,property,property.propertyId,property.project,property.projectId,property.project.builder,property.project.locality,property.project.locality.suburb,property.project.locality.suburb.city,listingAmenities.amenity,listingAmenities.amenity.amenityMaster,label,masterAmenityIds,name,unitType,unitName,size,currentListingPrice,localityId,floor,pricePerUnitArea,price,otherCharges,jsonDump,latitude,longitude,amenityDisplayName,isDeleted,bedrooms,bathrooms,amenityId";

$jsonListing = htmlentities(json_encode($resaleListings));

$smarty->assign('resaleListings',$resaleListings);
$smarty->assign('jsonListing',$jsonListing);

$bookingStatus = BookingStatuses::find("all");
$bStatusList =array();
foreach($bookingStatus as $bookingStatus){
    $bStatusList[$bookingStatus->id] = $bookingStatus->display_name;
}
$smarty->assign("bStatusList", $bStatusList);

$typeArr = Company::getCompanyByType("VendorClassified"); 
$smarty->assign("comptype", $typeArr);


$furnData = mysql_fetch_assoc(mysql_query("SHOW COLUMNS FROM listings WHERE Field = 'furnished'"));
preg_match_all("/'([\w -]*)'/", $furnData["Type"], $values);
$smarty->assign("furnished_options", $values[1]);

function getUserCities($admin_id){
    $cities = array();
    $query = "SELECT ct.CITY_ID, ct.LABEL FROM proptiger_admin_city act LEFT JOIN city ct ON act.CITY_ID=ct.CITY_ID WHERE act.ADMIN_ID={$admin_id}";
    $result = mysql_query($query) or die(mysql_query()."(E-001)");
    if(mysql_num_rows($result)>0){
        while ($row = mysql_fetch_assoc($result)){
            $cities[$row["CITY_ID"]] = $row["LABEL"];
        }
    }
    return $cities;
}

?>
