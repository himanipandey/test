<?php
//calling function for all the cities




$cityArray = City::CityArr();
$smarty->assign("cityArray", $cityArray);
$smarty->assign('dirname',$dirName);

$bankArray = BankList::arrBank();
$smarty->assign("bankArray",$bankArray);
$smarty->assign('dirname',$dirname);

//$arr = Array('type'=> 'Broker');
//$brokerArray= Company::getAllCompany($arr);
$brokerArray = Company::find('all', array('conditions'=>array("type = 'Broker' and status = 'Active'" )));
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
$uriLogin = "https://qa.proptiger-ws.com/app/v1/login?username=admin-1223006@proptiger.com&password=1234&rememberme=true"; //master
//$uriLogin = "https://qa.proptiger-ws.com/app/v1/login?username=admin-10@proptiger.com&password=1234&rememberme=true"; //normal user

//$uriListing = "https://qa.proptiger-ws.com/data/v1/entity/user/listing?cityId=2&fields=seller,id,property&start=0&rows=10";
$uriListing = "https://qa.proptiger-ws.com/data/v1/entity/user/listing?listingCategory=Resale&cityId={$cityId}&start=0&fields=seller,seller.fullName,id,listing,listing.facing,listing.jsonDump,listing.homeLoanBankId,listing.flatNumber,listing.noOfCarParks,listing.negotiable,listing.transferCharges,listing.plc,property,property.propertyId,property.project,property.projectId,property.project.builder,property.project.locality,property.project.locality.suburb,property.project.locality.suburb.city,listingAmenities.amenity,listingAmenities.amenity.amenityMaster,label,masterAmenityIds,name,unitType,unitName,size,currentListingPrice,localityId,floor,pricePerUnitArea,price,otherCharges,jsonDump,latitude,longitude,amenityDisplayName,isDeleted,bedrooms,bathrooms,amenityId";
//$uri = "https://qa.proptiger-ws.com/data/v1/entity/user/listing";
//$dataArr = array();
//$dataArr['sellerId'] = "1216008";
//$dataJson = json_encode($dataArr);
try{ 

    //$response = \Httpful\Request::post($uri1)->send();                     
    //$response1 = \Httpful\Request::get($uri)->send();
    //->body('{"floor":"2","jsonDump":"{\"comment\":\"anubhav\"}","sellerId":"1216008","flatNumber":"D-12","homeLoanBankId":"1","noOfCarParks":"3","negotiable":"true","transferCharges":1000,"plc":200,"otherInfo":{"size":"100","projectId":"656368","bedrooms":"3","unitType":"Plot","penthouse":"true","studio":"true","facing":"North"},"masterAmenityIds":[1,2,3,4],"currentListingPrice":{"pricePerUnitArea":2000,}}') // lets attach a body/payload...
    //echo $response;
    //echo $response1;

    /*$response = \Httpful\Request::post($uriLogin)                  // Build a PUT request...
    ->sendsJson()                               // tell it we're sending (Content-Type) JSON...
    ->authenticateWith('admin-10@proptiger.com', '1234')  // authenticate with basic auth...
    ->body('{"json":"is awesome"}')             // attach a body/payload...
    ->send(); */

    $responseLogin = \Httpful\Request::post($uriLogin)                  // Build a PUT request...
    ->sendsJson()                               // tell it we're sending (Content-Type) JSON...
    ->body('')             // attach a body/payload...
    ->send(); 


   // print("<pre>");
    //$res = var_dump($response);
    //echo '-------------------------------------------------------------------------------','\n';
    //print_r($response.object); 
    //$data = json_decode($response);
    $header = $responseLogin->headers;
    $header = $header->toArray();
    $ck = $header['set-cookie'];
    
    $ck_new = "";
    for($i = 0; $i < strlen($ck); $i++)  {
        if($ck[$i] == ';')  {
            break;
        }
        $ck_new = $ck_new.$ck[$i];
    }


    //print_r($ck_new);

    /*$ck_name = "JSESSIONID";
    setcookie($ck_name,$ck_new,time()+3600*24*100,"/","");

    if(!isset($_COOKIE[$ck_name])) {
    echo "Cookie named '" . $ck_name . "' is not set!";
    } 
    else {
        echo "Cookie '" . $ck_name . "' is set!<br>";
        echo "Value is: " . $_COOKIE[$ck_name];
    }*/

    
    //echo '\n\n', $res["raw_headers"]);
   
    if($ck_new!='')
    {    
        $responseLists = \Httpful\Request::get($uriListing)->addHeader("COOKIE", $ck_new )->send(); 
        //var_dump($responseLists->body);
        if($responseLists->body->statusCode=="2XX"){
            $data = $responseLists->body->data;
            //var_dump($responseLists->body);
            foreach ($data as $k => $v){ 
                //$uriListingDetail =  "https://qa.proptiger-ws.com/data/v1/entity/user/listing/".$v->id;                
                //$responseListingDetail = \Httpful\Request::get($uriListingDetail)->addHeader("COOKIE", $ck_new )->send();
                //if($responseListingDetail->body->statusCode=="2XX"){
                $tmp['json'] = htmlentities(json_encode($v));
                $tmp['val'] = $v;
                    array_push($resaleListings,$tmp);
                //}

            }
        }

    }



 

 } 
 catch(Exception $e)  {
    print_R($e);
 }
//print("<pre>");
//print_r($resaleListings);

$jsonListing = htmlentities(json_encode($resaleListings));

$smarty->assign('resaleListings',$resaleListings);
$smarty->assign('jsonListing',$jsonListing);





?>
