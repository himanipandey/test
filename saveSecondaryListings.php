<?php

error_reporting(1);
ini_set('display_errors','1');
include('httpful.phar');

  
//$listing_id = $_POST['listing_id'];
$listing_id = 295044;

$dataArr = array();
$dataArr['sellerId'] = "1216008";//$_POST['broker_id'];
//$dataArr['propertyId'] = $_POST['property_id'];
$otherInfo = array(
    'size'=> $_POST['size'],
    'projectId'=> $_POST['project_id'],
    'bedrooms'=> $_POST['bedrooms'],
    'unitType'=> "Apartment", //$_POST['unit_type'],
    'penthouse'=>"true",
    'studio' => "true",
    'facing' => "North" //$_POST['facing']
    ); 
$dataArr['otherInfo'] = $otherInfo;

$dataArr['floor'] = $_POST['floor'];
$jsonDump = array(
    'comment' => "comment",
    'tower' => $_POST['tower'],
    'study_room' => $_POST['study_room'],
    'servant_room' => $_POST['servant_room'],
    );
//$dataArr['jsonDump'] = $jsonDump;
$dataArr['jsonDump'] = json_encode($jsonDump);

$dataArr['flatNumber'] = $_POST['flat_number'];
$dataArr['homeLoanBankId'] = $_POST['loan_bank'];
$dataArr['noOfCarParks'] = $_POST['parking'];
$dataArr['negotiable'] = "true";
$dataArr['transferCharges'] = $_POST['trancefer_rate']; 
$dataArr['plc'] = $_POST['plc_val'];

$masterAmenityIds = array(
    1,2,3,4
    );
//$dataArr['masterAmenityIds'] = $masterAmenityIds;
$currentListingPrice = array(
    'pricePerUnitArea'=> $_POST['price_per_unit_area'],
    'price'=> $_POST['price'],
    'otherCharges'=> '',
    'comment'=>''
    );
$dataArr['currentListingPrice'] = $currentListingPrice;


/*"{"floor":"2","jsonDump":{"comment":"QA Marketplace Test Company"},"sellerId":null,"flatNumber":"3","homeLoanBankId":"select bank","noOfCarParks":"4","negotiable":"true","transferCharges":"","plc":"","otherInfo":{"size":"","projectId":"503095","bedrooms":null,"unitType":"Sq. Ft.","facing":"East"},"currentListingPrice":{"pricePerUnitArea":"100"}}"*/

//'{"floor":{$x},"jsonDump":"{\"comment\":\"anubhav\"}","sellerId":"1216008","flatNumber":"D-12","homeLoanBankId":"1","noOfCarParks":"3","negotiable":"true","transferCharges":1000,"plc":200,"otherInfo":{"size":"100","projectId":"656368","bedrooms":"3","unitType":"Plot","penthouse":"true","studio":"true","facing":"North"},"masterAmenityIds":[1,2,3,4],"currentListingPrice":{"pricePerUnitArea":2000,}}'



$dataJson = json_encode($dataArr);
//print("<pre>");
//print_r($dataArr); die;
 //var_dump($dataJson);   


    $uri = "https://qa.proptiger-ws.com/data/v1/entity/user/listing";
    $uriLogin = "https://qa.proptiger-ws.com/app/v1/login?username=admin-1223006@proptiger.com&password=1234&rememberme=true";

    /*try{ 
        $response_login = \Httpful\Request::post($uri1)->sendIt();
        


        $response = \Httpful\Request::put($uri)->authenticateWith('admin-22550@proptiger.com', '1234')->sendsJson()->body($dataJson)->sendIt();    
        echo "This response has " . count($response); 
        echo $response,'\n';
        //echo $response1;
        //admin-22550@proptiger.com 
        //1234 


    } catch(Exception $e)  {
        print_R($e);
    }*/

    $response_login = \Httpful\Request::post($uriLogin)                  // Build a PUT request...
    ->sendsJson()                               // tell it we're sending (Content-Type) JSON...
    ->body('')             // attach a body/payload...
    ->send(); 

    $header = $response_login->headers;
    $header = $header->toArray();
    $ck = $header['set-cookie'];
    
    $ck_new = "";
    for($i = 0; $i < strlen($ck); $i++)  {
        if($ck[$i] == ';')  {
            break;
        }
        $ck_new = $ck_new.$ck[$i];
    }
    //echo $ck_new;
    if($ck_new!='')
    {    
        if($listing_id!=''){
            $uri = $uri."/".$listing_id;
            $response = \Httpful\Request::put($uri)           
            ->sendsJson()                               
            ->body($dataJson)
            ->addHeader("COOKIE", $ck_new) 
            ->send(); 
            if($response->body->statusCode=="2XX"){
                echo "Listing successfully updated";
            }
            else{
                 echo $response->body->error->msg;
            }
        }
        else{
            $response = \Httpful\Request::post($uri)           
            ->sendsJson()                               
            ->body($dataJson)
            ->addHeader("COOKIE", $ck_new) 
            ->send(); 
            if($response->body->statusCode=="2XX"){
                echo "Listing successfully created";
            }
            else{
                 echo $response->body->error->msg;
            }
        }


        //var_dump($response);

        
    }
    else{
        echo "Authentication Error.";
    }



    /*$ch = curl_init();

                curl_setopt($ch, CURLOPT_URL,$uri);             
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-length: ".strlen($dataJson))); 
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                //curl_setopt($ch, CURLOPT_VERBOSE, 1);
                //curl_setopt($ch, CURLOPT_HEADER, 1);
                curl_setopt($ch, CURLOPT_COOKIE, "JSESSIONID=".$cookies['JSESSIONID']);
                $server_output = curl_exec($ch);
                curl_close ($ch);
                $output_array = json_decode($server_output,true);
                print_r($output_array);*/

?>
