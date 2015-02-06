<?php
ini_set("display_errors",1);
error_reporting(1);
// Point to where you downloaded the phar

include('./httpful.phar');
 
// And you're ready to go!
//$url = "http://api.tuxx.co.uk/demo/server/time.php&app_id=" . $appID . "&app_key=" . $appKey;

$uri = "http://qa.proptiger-ws.com/data/v1/entity/user/listing";
// {"floor":"2","jsonDump":"{\"comment\":\"anubhav\"}","sellerId":"1216008","flatNumber":"D-12","homeLoanBankId":"1","noOfCarParks":"3","negotiable":"true","transferCharges":1000,"plc":200,"otherInfo":{"size":"100","projectId":"656368","bedrooms":"3","unitType":"Plot","penthouse":"true","studio":"true","facing":"North"},"masterAmenityIds":[1,2,3,4],"currentListingPrice":{"pricePerUnitArea":2000,}}
try{ 

    $response = \Httpful\Request::put($uri)        // Build a PUT request...
    ->sendsJson()                      // let's tell it we're sending (Content-Type) JSON...
    ->body('{"floor":"2","jsonDump":"{\"comment\":\"anubhav\"}","sellerId":"1216008","flatNumber":"D-12","homeLoanBankId":"1","noOfCarParks":"3","negotiable":"true","transferCharges":1000,"plc":200,"otherInfo":{"size":"100","projectId":"656368","bedrooms":"3","unitType":"Plot","penthouse":"true","studio":"true","facing":"North"},"masterAmenityIds":[1,2,3,4],"currentListingPrice":{"pricePerUnitArea":2000,}}') // lets attach a body/payload...
    ->sendIt();

	echo "This response had " . count($response);
    echo "hi";
    /*$response = \Httpful\Request::get($uri)
    ->parseWith(function($body) {
        return explode(",", $body);
    })
    ->sendIt();
	
    echo "This response had " . count($response) . " values separated via commas";*/
	//$response = \Httpful\Request::get($uri)->sendIt();
    //echo $uri,$response;

	// If the JSON response is {"scalar":1,"object":{"scalar":2}}
	//echo $response->body->scalar;           // prints 1
	//echo $response->body->object->scalar;   // prints 5    

 } catch(Exception $e)  {
 	print_R($e);
 }





/*$url = "http://proptiger.com/app/v1/project-detail?projectId=513233";
try {
$response = \Httpful\Request::get($url)->send();
}catch(Exception $e) {
	print_R($e);
}
echo $response;*/
?>