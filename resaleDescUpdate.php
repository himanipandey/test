<?php
require("saveSecondaryListings.php");

$listings = getListingData();

foreach ($listings as $row){
    if(isset($row->description) && $row->description !=""){
        //continue;
    }
    
    $property_id = $row->property->propertyId;
    $facing = $row->facing->id;
    $floor = $row->floor;
    $homeLoanBank = $row->homeLoanBank;
    $bankName = $row->homeLoanBankId;
    $carpark = $row->noOfCarParks;
    $furnished = $row->furnished;
    $price = $row->currentListingPrice->price;
    
    $description = "";
    $getProjUrl = PROJECT_DETAIL_V4."".$row->property->projectId;
    $response = \Httpful\Request::get($getProjUrl)->sendsJson()->body('')->send();
    
    if($response->body->statusCode == "2XX" && $response->body->data){
        if(getBhkNew($response->body->data, $property_id, true) == "plot"){
                continue;
        }
        $format = mt_rand(1, 10);
        switch ($format){
            case 1 :
            case 10 :
                $description = descFormat1($response->body->data);
                break;
            case 2:
            case 9:
                $description = descFormat2($response->body->data);
                break;
            case 3:
            case 8:
                $description = descFormat3($response->body->data);
                break;
            case 4:
            case 7:
                $description = descFormat4($response->body->data);
                break;
            case 5:
            case 6:
                $description = descFormat5($response->body->data);
                break;
        }
    }
    
    $desciption = createDescription($row);
    $query = "UPDATE listings SET description='".$desciption."' WHERE id=".$row->id;
    mysql_query($query);
}




function getListingData(){
    $uriListing = RESALE_LISTING_API_V2_URL.'?selector={"paging":{"start":0,"rows":10},"fields":["id","property","projectId","propertyId","facing","direction","furnished","jsonObject","floor","total_floor","currentListingPrice","price","homeLoanBank","homeLoanBankId","noOfCarParks","description"]}';
    $responseLists = \Httpful\Request::get($uriListing)->send();
    if ($responseLists->body->statusCode == "2XX") {
        $data = $responseLists->body->data;
        return $data;
    }
}