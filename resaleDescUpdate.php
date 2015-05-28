<?php
include('httpful.phar');
require_once("appWideConfig.php");
include("dbConfig.php");
include("./modelsConfig.php");

$listings = getListingData();
foreach ($listings as $row){
    if(isset($row->description) && $row->description !=""){
        continue;
    }
    $desciption = createDescription($row);
    $query = "UPDATE listings SET description='".$desciption."' WHERE id=".$row->id;
    mysql_query($query);
}




function getListingData(){
    $uriListing = RESALE_LISTING_API_V2_URL.'?selector={"paging":{"start":0,"rows":200000},"fields":["id","property","projectId","propertyId","facing","direction","furnished","jsonObject","floor","total_floor","currentListingPrice","price","homeLoanBank","homeLoanBankId","noOfCarParks","description"]}';
    $responseLists = \Httpful\Request::get($uriListing)->send();
    if ($responseLists->body->statusCode == "2XX") {
        $data = $responseLists->body->data;
        return $data;
    }
}


function createDescription($listing){
    $furnished = array("Furnished"=>"A fully furnished", "Semi-Furnished"=>"A semi furnished", "Unfurnished"=>"An unfurnished");
    $description = "";
    $getProjUrl = project_detail."".$listing->property->projectId;
    $response = \Httpful\Request::get($getProjUrl)->sendsJson()->body('')->send();
    if($response->body->statusCode == "2XX" && $response->body->data){
        $response = $response->body->data;
        list($bhk, $bathrooms, $balcony, $city, $size, $unitType, $builderName) = getBhk($response->properties, $listing->propertyId);
        if(!(strpos($unitType, "plot") === FALSE)){
            return "";
        }
        $facing = "";
        if(isset($listing->facing) && isset($listing->facing->direction)) {
            $facing = $listing->facing->direction;
        }
        $description = "A";
        if(isset($listing->furnished) && $listing->furnished != ""){
            $description = $furnished[$listing->furnished];
        }
        $bathroomStr = ($bathrooms>1)? "{$bathrooms} bathrooms " : (($bathrooms==1)? "1 bathroom ":"");
        $balconyStr = ($balcony>1)? "and {$balcony} balconies " : (($balcony==1)? "and 1 balcony ":"");
        $description .= " ".$bhk." flat with {$bathroomStr}{$balconyStr}in {$builderName} ".strtolower($response->projectDetails->projectName).", ".strtolower($city).".";
        $floor = $listing->floor; 
        $floors = "";
        if(isset($listing->jsonObject) && isset($listing->jsonObject->total_floor)){
            $floors = $listing->jsonObject->total_floor;
        }
        $temp .= "";
        if($facing != "" || $floor != ""){
            $facing = camel2dashed($facing);
            $temp .= (($facing !="")? " It is {$facing} facing" :"");
            if($floor !=""){
                $floorNoStr = "ground";
                if($floor != 0){
                    $floorNoStr = addOrdinalNumberSuffix($floor);
                }
                $temp .= ($temp !="")? " and is" :" It is";
                $temp .= (($floor !="")? " located on {$floorNoStr} floor" : "");
            }
            $temp .= ($floor !="" && $floors !="")? "(out of {$floors} total floors)" : "";
            $temp .= ".";
        }
        $description .= $temp;
        $price = $listing->currentListingPrice->price;
        $price = $price/100000;
        $priceUnit = "lacs";
        if($price>=100){
            $price = $price/100;
            $priceUnit = "crs";
        }
        $price = number_format($price, 2);
        $description .= " The price of this property is {$price} {$priceUnit} all inclusive(registration charges extra).";
        if(isset($listing->homeLoanBank) && $listing->homeLoanBank==1){
            $description .= " The property already has a home loan";
            if(isset($listing->homeLoanBankId)){
                $bankArray = BankList::find("first",array("conditions"=>array("bank_id=?",$listing->homeLoanBankId)));
                $bank = strtolower($bankArray->bank_name);
                $description .= " approved by {$bank}";
            }
            $description .= ".";
        }
        $car = "1";
        if(isset($listing->noOfCarParks) && $listing->noOfCarParks >1){
            $car = $_POST["parking"];
        }
        $description .= " It has {$car} car parking and 1 two-wheeler parking.";
        if(isset($response->specification->flooring)){
            $obj = $response->specification->flooring;

            if((trim($obj->LivingDining) == trim($obj->MasterBedroom)) && (trim($obj->LivingDining)  == trim($obj->OtherBedroom))){
                $description .= " It has ".addFloring($obj->LivingDining)." in living/dining room, master bedroom and other bedrooms.";
            }else if(trim($obj->LivingDining) == trim($obj->MasterBedroom)){
                $description .= " It has ".addFloring($obj->LivingDining)." in living/dining room, master bedroom and ".addFloring($obj->OtherBedroom)." in other bedrooms.";
            }else if(trim($obj->LivingDining) == trim($obj->OtherBedroom)){
                $description .= " It has ".addFloring($obj->LivingDining)." in living/dining room, other bedrooms and ".addFloring($obj->MasterBedroom)." in master bedroom.";
            }else if(trim($obj->MasterBedroom) == trim($obj->OtherBedroom)){
                $description .= " It has ".addFloring($obj->MasterBedroom)." in master bedroom, other bedrooms and ".addFloring($obj->LivingDining)." in living/dining room.";
            }else{
                $description .= " It has ".addFloring($obj->LivingDining)." in living/dining room, ".addFloring($obj->MasterBedroom). " in master bedroom and ".addFloring($obj->OtherBedroom)." other bedrooms.";
            }


            if(trim($obj->Toilets) == trim($obj->Balcony)){
                $description .= " Toilets and balcony have ".addFloring($obj->Toilets).".";
            }else {
                $description .= " Toilets have ".addFloring($obj->Toilets)." and balconies have ".addFloring($obj->Balcony).".";
            }
            if($obj->kitchen){
                $description .= " Kitchen has ".addFloring($obj->kitchen).".";
            }
        }
    }
    return ($description);
}
function getBhk($propArr, $propId){
    foreach ($propArr as $prop){
        if($prop->propertyId == $propId){
            $unitName = explode("+", $prop->unitName);
            $city = $prop->project->locality->suburb->city->label;
            $builderName = strtolower($prop->project->builder->name);
            return array($unitName[0], $prop->bathrooms, $prop->balcony, $city, $prop->size, strtolower($prop->unitType), $builderName);
        }
    }
}
function addOrdinalNumberSuffix($num) {
    if (!in_array(($num % 100),array(11,12,13))){
        switch ($num % 10) {
            case 1:  return $num.'st';
            case 2:  return $num.'nd';
            case 3:  return $num.'rd';
        }
    }
    return $num.'th';
}
function camel2dashed($str) {
    return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $str));
}

function addFloring($string){
    $string = strtolower($string);
    $last_word_start = strrpos($string, ' ') + 1;
    $last_word = substr($string, $last_word_start);
    if($last_word != "flooring"){
        $string = $string." flooring";
    }
    return $string;
}