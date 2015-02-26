<?php
ini_set("display_errors",1);
error_reporting(1);
// Point to where you downloaded the phar

include('./httpful.phar');
 
// And you're ready to go!
//$url = "http://api.tuxx.co.uk/demo/server/time.php&app_id=" . $appID . "&app_key=" . $appKey;

$uri = "http://qa.proptiger-ws.com/data/v1/entity/user/listing";

$uri1 = "https://qa.proptiger-ws.com/app/v1/login?username=admin-10@proptiger.com&password=1234";
try{ 

    //$response = \Httpful\Request::post($uri1)->send();                     
    //$response1 = \Httpful\Request::get($uri)->send();
    //->body('{"floor":"2","jsonDump":"{\"comment\":\"anubhav\"}","sellerId":"1216008","flatNumber":"D-12","homeLoanBankId":"1","noOfCarParks":"3","negotiable":"true","transferCharges":1000,"plc":200,"otherInfo":{"size":"100","projectId":"656368","bedrooms":"3","unitType":"Plot","penthouse":"true","studio":"true","facing":"North"},"masterAmenityIds":[1,2,3,4],"currentListingPrice":{"pricePerUnitArea":2000,}}') // lets attach a body/payload...
    //echo $response;
    //echo $response1;

    $response = Request::put($uri)                  // Build a PUT request...
    ->sendsJson()                               // tell it we're sending (Content-Type) JSON...
    ->authenticateWith('admin-10@proptiger.com', '1234')  // authenticate with basic auth...
    ->body('{"json":"is awesome"}')             // attach a body/payload...
    ->send(); 


 print("<pre>");
    //$res = var_dump($response);
    //echo '-------------------------------------------------------------------------------','\n';
    //print_r($response.object); 
    //$data = json_decode($response);
    $header = $response->headers;
    $header = $header->toArray();
    $ck = $header['set-cookie'];
    print_r($ck);
    echo "\n";
    $ck_new = "";
    for($i = 0; $i < strlen($ck); $i++)  {
       
        if($ck[$i] == ';')  {
            
            break;
        }
        //print_r($ck_new);

        $ck_new = $ck_new.$ck[$i];
    }

    print_r($ck_new);

    //$cookie_name = "user";
    //$cookie_value = "John Doe";
    $ck_name = "JSESSIONID";
    setcookie($ck_name,$ck_new,time()+3600*24*100,"/","");

    if(!isset($_COOKIE[$ck_name])) {
    echo "Cookie named '" . $ck_name . "' is not set!";
} else {
    echo "Cookie '" . $ck_name . "' is set!<br>";
    echo "Value is: " . $_COOKIE[$ck_name];
}

$response1 = \Httpful\Request::post($uri1)->addHeader( $ck_name, $ck_new )->send(); 
echo $response1;
    //echo '\n\n', $res["raw_headers"]);
   

    //echo $res;

    //echo $response1 = \Httpful\Header::post($uri1)->sendIt();


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