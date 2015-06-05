<?php

error_reporting(1);
ini_set('display_errors','1');
include('httpful.phar');
require_once("appWideConfig.php");
include("dbConfig.php");
include("./modelsConfig.php");

if($_POST['task'] === 'get_tower')  {
   
    $Sql = "SELECT TOWER_ID,PROJECT_ID,TOWER_NAME,NO_OF_FLOORS FROM resi_project_tower_details WHERE PROJECT_ID =".$_POST['project_id']." ";
    $Tower = array();
    $ExecSql = mysql_query($Sql) or die();
    $cnt = 0;
    if (mysql_num_rows($ExecSql) > 0) {
        while($Res = mysql_fetch_assoc($ExecSql)) {
            $tmp = array();
            $tmp['tower_id'] = $Res['TOWER_ID'];
            $tmp['tower_name'] = $Res['TOWER_NAME'];
            $tmp['total_floor'] = $Res['NO_OF_FLOORS'];
            if($Res['TOWER_ID']!='')
                array_push($Tower, $tmp);
            $cnt++;
        }    
    }
    echo json_encode($Tower);
}

else if($_POST['task'] === 'get_seller')  {
    
    $Sql = "SELECT user_id, name FROM company_users  WHERE company_id=".$_POST['broker_id']." and status = 'Active' and user_id is not null";
    //$Sql = "SELECT user_id, name FROM company_users  WHERE company_id=".$_POST['broker_id']."";
    $Sel = array();
    $ExecSql = mysql_query($Sql) or die(mysql_error() . ' Error in fetching data from company_users');
    $cnt = 0;
    if (mysql_num_rows($ExecSql) > 0) {
        while($Res = mysql_fetch_assoc($ExecSql)) {
            
            $tmp = array();
            $tmp['user_id'] = $Res['user_id'];
            $tmp['name'] = $Res['name'];
            if($Res['user_id']!='')
                array_push($Sel, $tmp);
            $cnt++;
        }    
    }
    echo json_encode($Sel);

}
else if($_POST['task'] === 'get_broker')  {
    $company_id='';
    $Sql = "SELECT c.id FROM company c inner join company_users cu on c.id=cu.company_id WHERE cu.user_id=".$_POST['seller_id']." and c.status = 'Active' and cu.status='Active' ";
    //echo $Sql;
    $Sel = array();
    $ExecSql = mysql_query($Sql) or die(mysql_error() . ' Error in fetching data from company_users');
    $cnt = 0;
    if (mysql_num_rows($ExecSql) > 0) {
        $Res = mysql_fetch_assoc($ExecSql);
        $broker_id = $Res['id'];
           
    }
    echo $broker_id;

}
else if($_POST['task'] == 'delete_listing'){
    $login_cookie = authListing();
    if($login_cookie !=""){
        $listingId = $_POST['listingId'];
        $api_url = LISTING_API_URL."/".$listingId;
        $response = \Httpful\Request::delete($api_url)           
        ->sendsJson()
        ->body()
        ->addHeader("COOKIE", $login_cookie)
        ->send();
        
        if($response->body->statusCode=="2XX"){
            $returnArr['code'] = "2";
            $returnArr['msg'] = "Deleted successfully";
            echo json_encode($returnArr);
        }
        else{
            $returnArr['code'] = "0";
            $returnArr['msg'] = $response->body->error->msg;
            echo json_encode($returnArr);
        }
        
    }else{
        $returnArr['code'] = "0";
        $returnArr['msg'] = "Authentication error";
        echo json_encode($returnArr);
    }
    
}
else if($_REQUEST["desc_update_script"] != true){
    //$listing_id = $_POST['listing_id'];
    $listing_id='';
    

    $dataArr = array();
    if($_POST['task']=='update' && $_POST['listing_id']!=''){
        $listing_id=$_POST['listing_id'];
        //$dataArr['listingId'] = $_POST['listing_id'];
    }
    $dataArr['sellerId'] = $_POST['seller_id'];//"1216008";//
    if(!empty($_POST['facing']))
        $dataArr['facingId'] = $_POST['facing'];
    if(isset($_POST['property_id']) && !empty($_POST['property_id']))
        $dataArr['propertyId'] =$_POST['property_id'];
    //$dataArr['propertyId'] = $_POST['property_id'];
    $otherInfo = array(
        'size'=> $_POST['size'],
        'projectId'=> $_POST['project_id'],
        'bedrooms'=> $_POST['bedrooms'],
        'bathrooms'=> $_POST['bathrooms'], 

        'unitType'=>  $_POST['unit_type'],
        ); 

    $penthouse_studio = $_POST['penthouse_studio'];
    if(isset($penthouse_studio) && !empty($penthouse_studio)){
        if($penthouse_studio=="1")
            $otherInfo['penthouse'] = "true";
        if($penthouse_studio=="2")
            $otherInfo['studio'] = "true";
    }


    
    if($_POST['study_room'] != null) {
        $otherInfo['studyRoom'] = $_POST['study_room'];
    }
    if($_POST['servant_room'] != null) {
        $otherInfo['servantRoom'] = $_POST['servant_room'];
    }

        
    $dataArr['otherInfo'] = $otherInfo;

    if(isset($_POST['floor']) && $_POST['floor'] !=""){
        $dataArr['floor'] = $_POST['floor'];
    }
    
    $jsonDump = array();
    $owner_name = $_POST['owner_name'];
    $owner_email = $_POST['owner_email'];
    $owner_number = $_POST['owner_number'];
    $alt_owner_number = $_POST['alt_owner_number'];

    $tower = $_POST['tower'];
    $phaseId = $_POST['phase_id'];

    $study_room = $_POST['study_room'];
    $servant_room = $_POST['servant_room'];

    $total_floor = $_POST['total_floor'];

/***  listing v2 values  ****************************************************/   
    if(isset($tower) && !empty($tower))
        $dataArr['towerId'] =$tower;
    if(isset($phaseId) && !empty($phaseId))
        $dataArr['phaseId'] =$phaseId;

/*** json dump values  ****************************************************/    
   if(isset($total_floor) && !empty($total_floor))
        $jsonDump['total_floor'] =$total_floor;

    
    if(isset($owner_name) && !empty($owner_name))
        $jsonDump['owner_name'] = $owner_name;
   
    if(isset($owner_email) && !empty($owner_email))
        $jsonDump['owner_email'] = $owner_email;
    if(isset($owner_number) && !empty($owner_number))
        $jsonDump['owner_number'] = $owner_number;
    if(isset($alt_owner_number) && !empty($alt_owner_number))
        $jsonDump['alt_owner_number'] = $alt_owner_number;
        


    $dataArr['jsonDump'] = json_encode($jsonDump);

    if(isset($_POST['description']) && !empty($_POST['description'])){
        $dataArr['description'] = $_POST['description'];
    }else{
        $dataArr["description"] = createDescription();
    }


    if(isset($_POST['review']) && !empty($_POST['review']))
        $dataArr['remark'] =$_POST['review'];

    if(isset($_POST['flat_number']) && !empty($_POST['flat_number']))
        $dataArr['flatNumber'] =$_POST['flat_number'];


    if(isset($_POST['loan_bank']) && !empty($_POST['loan_bank']))
        $dataArr['homeLoanBankId'] =$_POST['loan_bank'];

    if(isset($_POST['parking']) && !empty($_POST['parking']))
        $dataArr['noOfCarParks'] =$_POST['parking'];


    if(isset($_POST['trancefer_rate']) && !empty($_POST['trancefer_rate']))
        $dataArr['transferCharges'] =$_POST['trancefer_rate'];

    if(isset($_POST['plc_val']) && !empty($_POST['plc_val']))
        $dataArr['plc'] =$_POST['plc_val'];

    if($_POST['negotiable'] != null)  {
        $dataArr['negotiable'] = $_POST['negotiable'];    
    }
    if($_POST['bookingStatusId'] != "")  {
        $dataArr['bookingStatusId'] = $_POST['bookingStatusId'];    
    }
    if($_POST['furnished'] != "")  {
        $dataArr['furnished'] = $_POST['furnished'];    
    }
    if($_POST['homeLoanBank'] != "")  {
        $dataArr['homeLoanBank'] = $_POST['homeLoanBank'];    
    }
    

    $masterAmenityIds = array(
        1,2,3,4
        );

    if($_POST['price_per_unit_area'] != NaN)
        $pricePerUnitArea = $_POST['price_per_unit_area'];
    else
        $pricePerUnitArea =0;
    if($_POST['price'] !=NaN){
        $price = $_POST['price'];
        $price = round($price, -2);
    }
    else
        $price =0;
    if($_POST['other_charges'] !=NaN){
        $other_charges = $_POST['other_charges'];
    }
    else
        $other_charges =0;
    
    
    if($pricePerUnitArea == '' || $pricePerUnitArea == null) {
        $pricePerUnitArea = null;
    }
    if($price == '' || $price == null) {
        $price = null;
    }
    if($other_charges == '' || $other_charges == null) {
        $other_charges = null;
    }
    $currentListingPrice = array(
        'pricePerUnitArea'=> $pricePerUnitArea,
        'price'=> $price,
        'otherCharges'=> $other_charges,
        'comment'=>''
        );
    if((isset($pricePerUnitArea) && $pricePerUnitArea!='') || (isset($price) && $price!=''))
        $dataArr['currentListingPrice'] = $currentListingPrice;

    
    if($_POST["vendor"] !=""){
        $dataArr["vendorId"] = $_POST["vendor"];
    }
    $dataArr["brokerConsent"] = $_POST["broker"];

    $dataJson = json_encode($dataArr);
    
        $uri = LISTING_API_URL;
        $uriLogin = ADMIN_USER_LOGIN_API_URL;
        
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
        
        if($ck_new!='')
        {    
            $returnArr = array();
            if($listing_id!=''){
                $uri = $uri."/".$listing_id;

                $response = \Httpful\Request::put($uri)           
                ->sendsJson()                               
                ->body($dataJson)
                ->addHeader("COOKIE", $ck_new) 
                ->send(); 


                if($response->body->statusCode=="2XX"){
                   
                    $returnArr['code'] = "2";
                    $returnArr['msg'] = "update";
                    if($response->body->error){
                        $returnArr['error_msg'] = $response->body->error->msg;
                    }
                    echo json_encode($returnArr);
                }
                else{
                     
                    $returnArr['code'] = "0";
                    $returnArr['msg'] = $response->body->error->msg;
                    echo json_encode($returnArr);
                }
            }
            else{
                $response = \Httpful\Request::post($uri)           
                ->sendsJson()                               
                ->body($dataJson)
                ->addHeader("COOKIE", $ck_new) 
                ->send(); 
                
                if($response->body->statusCode=="2XX"){
                    $id = $response->body->data->id;

                    $returnArr['code'] = "1";
                    $returnArr['msg'] = $id;
                    if($response->body->error){
                        $returnArr['error_msg'] = $response->body->error->msg;
                    }
                    echo json_encode($returnArr);
                }
                else{
                    
                    $returnArr['code'] = "0";
                    $returnArr['msg'] = $response->body->error->msg;
                    echo json_encode($returnArr);
                }
            }
            
        }
        else{
            echo "Authentication Error.";
        }

    }

function authListing(){
    $uriLogin = ADMIN_USER_LOGIN_API_URL;
    $response_login = \Httpful\Request::post($uriLogin)->sendsJson()->body('')->send();
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
    return $ck_new;
}
function createDescription(){
    global $property_id,$floor,$total_floor,$facing,$homeLoanBank,$bankName,$carpark,$furnished,$price_per_unit_area,$price,$other_charges;
    
    $property_id = $_POST["property_id"];
    $facing = $_POST["facing"];
    $floor = $_POST["floor"];
    $homeLoanBank = $_POST["homeLoanBank"];
    $bankName = $_POST["loan_bank"];
    $carpark = $_POST["parking"];
    $furnished = $_POST["furnished"];
    $price = $_POST["price"];
    $price_per_unit_area = $_POST["price_per_unit_area"];
    $other_charges = $_POST["other_charges"];
    
    
    $description = "";
    $getProjUrl = PROJECT_DETAIL_V4."".$_POST["project_id"];
    $response = \Httpful\Request::get($getProjUrl)->sendsJson()->body('')->send();
    
    if($response->body->statusCode == "2XX" && $response->body->data){
        if(getBhkNew($response->body->data, $property_id, true) == "plot"){
                return "";
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
    
    return ($description);
}


//For v4
function getBhkNew($project, $propId, $onlyUnitType=false){
    $propArr = $project->properties;
    foreach ($propArr as $prop){
        if($prop->propertyId == $propId){
            $unitName = explode("+", $prop->unitName);
            $city = ucfirst(strtolower($project->locality->suburb->city->label));
            $builderName = ucwords(strtolower($project->builder->name));
            if($onlyUnitType){
                return strtolower($prop->unitType);
            }
            return array($unitName[0], $prop->bedrooms, $prop->bathrooms, $prop->balcony, $city, $prop->size, $prop->displayCarpetArea, strtolower($prop->unitType), $builderName);
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

function hasVal($data){
    $data = trim($data);
    if(!isset($data) || is_null($data) || $data==NULL || $data=="" || $data==false || $data=="false"){
        return false;
    }
    return true;
}
function chkAmenities($needle, $haystack){
    foreach ($haystack as $amenity){
        if($amenity->amenityMaster->abbreviation == $needle){
            return strtolower($amenity->amenityDisplayName);
        }
    }
   return false;
}

function listingPrice($size){
    global $price_per_unit_area,$price,$other_charges;
    if($price_per_unit_area){
        $price = ($price_per_unit_area*$size) + $other_charges;
    }
    $tprice = $price/100000;
    $priceUnit = "lacs";
    if($tprice>=100){
        $tprice = $tprice/100;
        $priceUnit = "crs";
    }
    $tprice = number_format($tprice, 2);
    return $tprice." ".$priceUnit;
}


//*********************************

function descFormat1($proj){
    global $property_id,$floor,$total_floor,$facing,$homeLoanBank,$bankName,$carpark,$furnished;
    
    $furnishedArr = array("Furnished"=>"A fully furnished", "Semi-Furnished"=>"A semi furnished", "Unfurnished"=>"An unfurnished");
    $description = "";
    
    list($bhk, $bedrooms, $bathrooms, $balcony, $city, $size, $carpetArea, $unitType, $builderName) = getBhkNew($proj, $property_id);
    
    $facingDir = "";
    if($facing) {
        $facingDir = MasterDirections::find('first',array('conditions'=>array('id=?',$facing)));
        $facingDir = $facingDir->direction;
    }
    $description = "A";
    if($furnished != ""){
        $description = $furnishedArr[$furnished];
    }
    $bathroomStr = ($bathrooms>1)? "{$bathrooms} bathrooms " : (($bathrooms==1)? "1 bathroom ":"");
    $balconyStr = ($balcony>1)? "and {$balcony} balconies " : (($balcony==1)? "and 1 balcony ":"");
    $projeName = ucwords(strtolower($proj->name));
    $description .= " ".$bhk." {$unitType} with {$bathroomStr}{$balconyStr}in {$builderName} ".$projeName.", ".ucfirst(strtolower($city)).".";
    
    $temp .= "";
    if($facingDir != "" || $floor != ""){
        $facingDir = camel2dashed($facingDir);
        $temp .= (($facingDir !="")? " It is {$facingDir} facing" :"");
        if($floor !=""){
            $floorNoStr = "ground";
            if($floor != 0){
                $floorNoStr = addOrdinalNumberSuffix($floor);
            }
            $temp .= ($temp !="")? " and is" :" It is";
            $temp .= (($floor !="")? " located on the {$floorNoStr} floor" : "");
        }
        $temp .= ($floor !="" && $total_floor !="")? " (out of {$total_floor} total floors)" : "";
        $temp .= ".";
    }
    $description .= $temp;
    
    $price = listingPrice($size);
    $description .= " The price of this property is INR {$price} (all inclusive, registration charges extra).";
    if(hasVal($homeLoanBank)){
        $description .= " The property already has a home loan";
        if(hasVal($bankName)){
            $bankArray = BankList::find("first",array("conditions"=>array("bank_id=?",$bankName)));
            $bank = strtolower($bankArray->bank_name);
            $description .= " approved by {$bank}";
        }
        $description .= ".";
    }
    $car = "1";
    if(hasVal($carpark) && $carpark > 1){
        $car = $carpark;
    }
    $description .= " It has {$car} car parking.";
    if(isset($proj->specifications->Flooring)){
        $obj = $proj->specifications->Flooring;

        if((trim($obj->{'Living/Dining'}) == trim($obj->{'Master Bedroom'})) && (trim($obj->{'Living/Dining'})  == trim($obj->{'Other Bedroom'}))){
            $description .= " It has ".addFloring($obj->{'Living/Dining'})." in living/dining room, master bedroom and other bedrooms.";
        }else if(trim($obj->{'Living/Dining'}) == trim($obj->{'Master Bedroom'})){
            $description .= " It has ".addFloring($obj->{'Living/Dining'})." in living/dining room, master bedroom and ".addFloring($obj->{'Other Bedroom'})." in other bedrooms.";
        }else if(trim($obj->{'Living/Dining'}) == trim($obj->{'Other Bedroom'})){
            $description .= " It has ".addFloring($obj->{'Living/Dining'})." in living/dining room, other bedrooms and ".addFloring($obj->{'Master Bedroom'})." in master bedroom.";
        }else if(trim($obj->{'Master Bedroom'}) == trim($obj->{'Other Bedroom'})){
            $description .= " It has ".addFloring($obj->{'Master Bedroom'})." in master bedroom, other bedrooms and ".addFloring($obj->{'Living/Dining'})." in living/dining room.";
        }else{
            $description .= " It has ".addFloring($obj->{'Living/Dining'})." in living/dining room, ".addFloring($obj->{'Master Bedroom'}). " in master bedroom and ".addFloring($obj->{'Other Bedroom'})." other bedrooms.";
        }


        if(hasVal($obj->Toilets) & (trim($obj->Toilets) == trim($obj->Balcony))){
            $description .= " Toilets and balcony have ".addFloring($obj->Toilets).".";
        }else {
            $description .= " Toilets have ".addFloring($obj->Toilets)." and balconies have ".addFloring($obj->Balcony).".";
        }
        if(hasVal($obj->Kitchen)){
            $description .= " Kitchen has ".addFloring($obj->Kitchen).".";
        }
    }
    
    return ($description);
}

function descFormat3($proj){
    global $property_id,$floor,$total_floor,$facing,$homeLoanBank,$bankName,$furnished;
    
    $projeName = ucwords(strtolower($proj->name));
    list($bhk, $bedrooms, $bathrooms, $balcony, $city, $size, $carpetArea, $unitType, $builderName) = getBhkNew($proj, $property_id);
    
    $furnishedArr = array("Furnished"=>"furnished", "Semi-Furnished"=>"semi furnished", "Unfurnished"=>"unfurnished");
    
    $description= "This {FURNISHED-DATA} {UNIT-NAME} {UNIT-TYPE} in {BUILDER-NAME} {PROJECT-NAME} {LOCATED-DATA}. {FACING-DATA} {ROOM-FLOORING} {BALCONY-FLOORING}{PWR-SEC}{SWIM-GYM} This {UNIT-TYPE} is priced at INR {PRICE-DATA} (all inclusive and registration charges are extra). {LOAN-DATA}";
    
    $search = array("{FURNISHED-DATA}","{UNIT-NAME}","{UNIT-TYPE}","{BUILDER-NAME}","{PROJECT-NAME}","{LOCATED-DATA}","{FACING-DATA}","{ROOM-FLOORING}","{BALCONY-FLOORING}","{PWR-SEC}","{SWIM-GYM}","{UNIT-TYPE}","{PRICE-DATA}","{LOAN-DATA}");
    $replace = array($furnishedArr[$furnished],$bhk,$unitType,$builderName,$projeName);
    
    $temp = "";
    if($floor  != ""){
        $floorNoStr = addOrdinalNumberSuffix($floor);
        $temp = " is located on the {$floorNoStr} floor of the building";
        if(hasVal($total_floor)){
            $temp .= " (building has a total of ".$total_floor." floors)";
        }
    }
    $replace[] = $temp;
    
    $temp = "";
    if(hasVal($facing)) {
        $facingDir = MasterDirections::find('first',array('conditions'=>array('id=?',$facing)));
        $facingDir = camel2dashed($facingDir->direction);
        $temp = " Its main door is {$facingDir} facing";
        if(hasVal($proj->specifications->Doors->Main)){
            $temp .= " and has ".  strtolower($proj->specifications->Doors->Main);
        }
        $temp = $temp.".";
    }
    $replace[] = $temp;
    
    $temp = "";
    if(isset($proj->specifications->Flooring)){
        $obj = $proj->specifications->Flooring;
        if (hasVal($obj->{'Master Bedroom'}) && ($obj->{'Master Bedroom'} == $obj->{'Other Bedroom'})){
            $temp = "Its master bedroom and other bedrooms have ".addFloring($obj->{'Master Bedroom'}).".";
        }else{
            if(hasVal($obj->{'Master Bedroom'})){
                $temp = "Its master bedroom has ".addFloring($obj->{'Master Bedroom'});
            }
            if(hasVal($obj->{'Other Bedroom'})){
                $temp .= ($temp !="")? " while" :"Its";
                $temp = " other bedrooms have ".addFloring($obj->{'Other Bedroom'});
            }
            $temp .= ($temp !="")? "." : "";
        }
    }
    $replace[] = $temp;
    
    $temp = "";
    if(hasVal($obj->Balcony)){
        $temp = "Balcony has ".addFloring($obj->Balcony).".";
    }
    $replace[] = $temp;
    
    $temp = "";
    $pwrBkp = chkAmenities("Pow", $proj->projectAmenities);
    $security = chkAmenities("Sec", $proj->projectAmenities);
    if(hasVal($pwrBkp) && hasVal($security)){
        $temp = " It has {$pwrBkp} as well as {$security}.";
    }else if($pwrBkp || $security){
       $temp = " It has {$pwrBkp}{$security}.";
    }
    $replace[] = $temp;
    
    $temp = "";
    $swim = chkAmenities("Swi", $proj->projectAmenities);
    $gym = chkAmenities("Gym", $proj->projectAmenities);
    if(hasVal($swim) && ($gym)){
        $temp = " The project also has a {$swim} as well as a {$gym}.";
    }else if(hasVal($swim) || hasVal($gym)){
       $temp = " The project also has a {$swim}{$gym}.";
    }
    $replace[] = $temp;
    
    $replace[] = $unitType;
    $replace[] = listingPrice($size);

    $temp = "";
    if(hasVal($homeLoanBank)){
        $temp = " An additional benefit is that it already has a pre-approved loan";
        if(hasVal($bankName)){
            $bankArray = BankList::find("first",array("conditions"=>array("bank_id=?",$bankName)));
            $bank = strtolower($bankArray->bank_name);
            $temp .= " by {$bank}";
        }
        $temp.= ".";
    }
    $replace[] = $temp;
    
    
    $description = str_replace($search, $replace, $description);
    $description =replaceSpaces($description);
    
    return $description;
}

function descFormat4($proj){
    global $property_id,$floor,$total_floor,$facing,$homeLoanBank,$bankName,$carpark,$furnished;
    $furnishedArr = array("Furnished"=>"fully furnished", "Semi-Furnished"=>"semi furnished", "Unfurnished"=>"unfurnished");
    list($bhk, $bedrooms, $bathrooms, $balcony, $city, $size, $carpetArea, $unitType, $builderName) = getBhkNew($proj, $property_id);
    $projeName = ucwords(strtolower($proj->name));
    
    $description= "This is {A-DATA} {FURNISHED-DATA} highly spacious {BEDROOM-DATA} {UNIT-TYPE} with {BATHROOM-DATA}{BALCONIES-DATA}. {FLOOR-PARKING} {FACING-DATA} {INTERIOR-EXTERIOR} {WINDOWS-INT-DOORS} {ELECTRIC-FITTING} It is priced at INR {PRICE-DATA} (all inclusive, only registration charges are extra).";
    
    $search = array("{A-DATA}","{FURNISHED-DATA}","{BEDROOM-DATA}","{UNIT-TYPE}","{BATHROOM-DATA}","{BALCONIES-DATA}","{FLOOR-PARKING}","{FACING-DATA}","{INTERIOR-EXTERIOR}","{WINDOWS-INT-DOORS}","{ELECTRIC-FITTING}","{PRICE-DATA}");
    $temp = "a";
    if($furnishedArr[$furnished] == "unfurnished"){
        $temp = "an";
    }
    
    $replace = array($temp,$furnishedArr[$furnished]);
    $replace[] = ($bedrooms>1)? $bedrooms." bedrooms" : "1 bedroom";
    $replace[] = $unitType;
    $replace[] = ($bathrooms>1)? $bathrooms." bathrooms" : "1 bathroom";
    $replace[] = ($balcony>1)? " and {$balcony} balconies" : " and 1 balcony";
    
    
    $temp = "";
    if($floor  != ""){
        $floorNoStr = addOrdinalNumberSuffix($floor);
        $temp .= "It is on the {$floorNoStr} floor";
        if(hasVal($total_floor)){
            $temp .= " (out of ".$total_floor." floors)";
        }
    }
    $temp .= ($temp ? " and":"It")." has ".(($carpark>1)? $carpark : 1)." car parking";
    $replace[] = $temp.($temp? ".":"");
    
    $temp = "";
    if(hasVal($facing)) {
        $facingDir = MasterDirections::find('first',array('conditions'=>array('id=?',$facing)));
        $facingDir = camel2dashed($facingDir->direction);
        $temp = " Its main door is facing {$facingDir} direction";
        if(hasVal($proj->specifications->Doors->Main)){
            $temp .= " and has ".  strtolower($proj->specifications->Doors->Main);
        }
        $temp = $temp.".";
    }
    $replace[] = $temp;
    $obj = $proj->specifications;
    
    $temp = "";
    if($obj){
        if((hasVal($obj->Walls->Interior)) && ($obj->Walls->Interior == $obj->Walls->Exterior)){
            $temp = "Its interior and exterior walls are painted with ".$obj->Walls->Interior;
        }else{
            if(hasVal($obj->Walls->Interior)){
                $temp = "Its interior walls are painted with ".$obj->Walls->Interior;
            }
            if(hasVal($obj->Walls->Exterior)){
                $temp .= (($temp !="")? " while its" : "Its")." exterior wall is painted with ".$obj->Walls->Exterior;
            }
        }
    }
    $replace[] = $temp.(($temp != "")? ".":"");
    
    $temp = "";
    if($obj){
        if(hasVal($obj->Windows)){
            $temp = "Its windows are {$obj->Windows}";
        }
        if(hasVal($obj->Doors->Internal)){
            $temp .= ($temp? " and the" : "Its")." internal doors have {$obj->Doors->Internal}";
        }
    }
    $replace[] = $temp.($temp ? ".":"");
    
    $temp = "";
    if($obj){
        if(hasVal($obj->Fittings->Electrical)){
            $temp = "The electric fitting is done with {$obj->Fittings->Electrical}.";
        }
    }
    $replace[] = $temp;
    
    $replace[] = listingPrice($size);
    
    
    $description = str_replace($search, $replace, $description);
    $description =replaceSpaces($description);
    
    return $description;
}

function descFormat5($proj){
    global $property_id,$floor,$total_floor,$facing,$homeLoanBank,$bankName,$carpark,$furnished;
    
    $furnishedArr = array("Furnished"=>"fully furnished", "Semi-Furnished"=>"semi furnished", "Unfurnished"=>"unfurnished");
    list($bhk, $bedrooms, $bathrooms, $balcony, $city, $size, $carpetArea, $unitType, $builderName) = getBhkNew($proj, $property_id);
    $projeName = ucwords(strtolower($proj->name));
    
    $description= "{A-DATA} {FURNISHED-DATA} {UNIT-NAME} {UNIT-TYPE} in {ADDRESS-DATA} with {BATHROOM-DATA}{BALCONIES-DATA}. It has {CAR-PARKING} covered car parking. {FACING-DATA} Its super area is {SUPER-AREA} sq ft{CARPET-AREA}. {LOCATED-DATA} {FLOORING-SPCF} {WALLS-SPCF} {KITCHEN-BATHROOM} It is available at INR {PRICE-DATA} (all inclusive and registration charges are extra).";
    
    $search = array("{A-DATA}","{FURNISHED-DATA}","{UNIT-NAME}","{UNIT-TYPE}","{ADDRESS-DATA}","{BATHROOM-DATA}","{BALCONIES-DATA}","{CAR-PARKING}","{FACING-DATA}","{SUPER-AREA}","{CARPET-AREA}","{LOCATED-DATA}","{FLOORING-SPCF}","{KITCHEN-BATHROOM}","{PRICE-DATA}","{WALLS-SPCF}");
    $temp = "A";
    if($furnishedArr[$furnished] == "unfurnished"){
        $temp = "An";
    }
    
    $replace = array($temp, $furnishedArr[$furnished],$bhk,$unitType);
    
    $replace[] = ucwords(strtolower($proj->locality->suburb->label)).", ".$city;
    
    $replace[] = ($bathrooms >1)? "{$bathrooms} bathrooms" : (($bathrooms==1)? "1 bathroom":"");
    $replace[] = (($bathrooms >=1 && $balcony >=1)? " and ":"").(($balcony >1)? "{$balcony} balconies" : (($balcony==1)? "1 balcony":""));
    $replace[] = ($carpark >1)? $carpark : 1;
    
    $temp = "";
    if(hasVal($facing)) {
        $facingDir = MasterDirections::find('first',array('conditions'=>array('id=?',$facing)));
        $facingDir = camel2dashed($facingDir->direction);
        $temp = " Its is facing {$facingDir}.";
    }
    $replace[] = $temp;
    
    $replace[] = $size;
    
    $temp = "";
    if($carpetArea > 0){
        $temp = " whereas carpet area is {$carpetArea} sq ft";
    }
    $replace[] = $temp;
    
    $temp = "";
    if($floor  != ""){
        $floorNoStr = addOrdinalNumberSuffix($floor);
        $temp = "It is on the {$floorNoStr} floor of the building";
        if(hasVal($total_floor)){
            $temp .= "(total number of floors are ".$total_floor.")";
        }
    }
    $replace[] = $temp;
    
    $obj = $proj->specifications->Flooring;
    $temp = "";
    if($obj){
        $temp = " It has ".  addFloring($obj->Kitchen)." in kitchen";
        if(hasVal($obj->{'Master Bedroom'}) && ($obj->{'Master Bedroom'} == $obj->{'Living/Dining'})){
            $temp .= " while bedroom and living room have ".addFloring($obj->{'Master Bedroom'});
        }else{
            $temp .= " while bedroom has ".addFloring($obj->{'Master Bedroom'})." and living room has ".addFloring($obj->{'Living/Dining'})."";
        }
    }
    $replace[] = $temp.($temp? "." : "");
    
    $temp = "";
    $obj = $proj->specifications->Walls;
    if($obj){
        $temp = "Kitchen has ".$obj->Kitchen.".";
    }
    $replace[] = $temp;
    
    $replace[] = listingPrice($size);
    
    $description = str_replace($search, $replace, $description);
    $description =replaceSpaces($description);
    
    return $description;
}


function descFormat2($proj){
    global $property_id,$floor,$total_floor,$facing,$homeLoanBank,$bankName,$carpark,$furnished;
    
    $furnishedArr = array("Furnished"=>"fully furnished", "Semi-Furnished"=>"semi furnished", "Unfurnished"=>"unfurnished");
    list($bhk, $bedrooms, $bathrooms, $balcony, $city, $size, $carpetArea, $unitType, $builderName) = getBhkNew($proj, $property_id);
    $projeName = ucwords(strtolower($proj->name));
    
    $description= "{FACING-DATA} {FURNISHED-DATA} {UNIT-NAME} {UNIT-TYPE}{LOCATED-DATA}. It has {CAR-PARKING} car parking. {FITTING-ELE} {FITTING-KIT-TOILETS} {WALLS-INT-EXT} {WALLS-KITCH-TLT} It is priced at INR {PRICE-DATA} (all inclusive and registration charges are extra).";
    
    $search = array("{FACING-DATA}","{FURNISHED-DATA}","{UNIT-NAME}","{UNIT-TYPE}","{LOCATED-DATA}","{CAR-PARKING}","{FITTING-ELE}","{FITTING-KIT-TOILETS}","{WALLS-INT-EXT}","{WALLS-KITCH-TLT}","{PRICE-DATA}");
    $replace = array($furnishedArr[$furnished],$bhk,$unitType);
    
    $temp = "A ";
    if(hasVal($facing)) {
        $facingDir = MasterDirections::find('first',array('conditions'=>array('id=?',$facing)));
        $facingDir = camel2dashed($facingDir->direction);
        if($facingDir == "east"){
            $temp = "An ";
        }
        $temp .= "{$facingDir} facing";
    }
    $replace = array($temp,$furnishedArr[$furnished],$bhk,$unitType);
    
    $temp = "";
    if($floor  != ""){
        $floorNoStr = addOrdinalNumberSuffix($floor);
        $temp = " located on the {$floorNoStr} floor";
        if(hasVal($total_floor)){
            $temp .= " (out of ".$total_floor." floors)";
        }
        $temp .= " of the building";
    }
    $replace[] = $temp;
    $replace[] = ($carpark >1)? $carpark : 1;
    
    $obj = $proj->specifications->Fittings;
    $temp = "";
    if(hasVal($obj->Electrical)){
        $temp = " It has been fitted with ".($obj->Electrical).".";
    }
    $replace[] = $temp;
    
    $temp = "";
    if(hasVal($obj->Kitchen)){        
        $temp = " The kitchen has ".($obj->Kitchen);
    }
    if(hasVal($obj->Toilets)){
        $temp .= (hasVal($temp)? " while its": "Its");
        $temp .= " toilets have ".($obj->Toilets);
    }
    $temp .= (hasVal($temp)? ".": "");
    $replace[] = $temp;
    
    $obj = $proj->specifications->Walls;
    $temp = "";
    if(hasVal($obj->Interior)){        
        $temp = "  Inside, it has been beautifully painted with ".($obj->Interior);
    }
    if(hasVal($obj->Exterior)){
        $temp .= (hasVal($temp)? " while on the outside": " Outside,");
        $temp .= "  it is painted with ".($obj->Exterior);
    }
    $temp .= (hasVal($temp)? ".": "");
    $replace[] = $temp;
    
    $temp = "";
    if(hasVal($obj->Kitchen)){        
        $temp = "  Its kitchen has ".($obj->Kitchen);
    }
    if(hasVal($obj->Toilets)){
        $temp .= (hasVal($temp)? " while the": " Its,");
        $temp .= "  toilets have ".($obj->Toilets);
    }
    $temp .= (hasVal($temp)? ".": "");
    $replace[] = $temp;
    
    $replace[] = listingPrice($size);
    
    $description = str_replace($search, $replace, $description);
    $description =replaceSpaces($description);
    
    
    return $description;
}

?>
