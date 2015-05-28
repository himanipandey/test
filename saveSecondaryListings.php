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
else {
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
    $furnished = array("Furnished"=>"A fully furnished", "Semi-Furnished"=>"A semi furnished", "Unfurnished"=>"An unfurnished");
    $description = "";
    $getProjUrl = project_detail."".$_POST["project_id"];
    $response = \Httpful\Request::get($getProjUrl)->sendsJson()->body('')->send();
    if($response->body->statusCode == "2XX" && $response->body->data){
        $response = $response->body->data;
        list($bhk, $bathrooms, $balcony, $city, $size, $unitType, $builderName) = getBhk($response->properties, $_POST["property_id"]);
        if(!(strpos($unitType, "plot") === FALSE)){
            return "";
        }
        $facing = "";
        if($_POST["facing"]) {
            $facing = MasterDirections::find('first',array('conditions'=>array('id=?',$_POST["facing"])));
            $facing = $facing->direction;
        }        
        $description = "A";
        if($_POST["furnished"] != ""){
            $description = $furnished[$_POST["furnished"]];
        }
        $bathroomStr = ($bathrooms>1)? "{$bathrooms} bathrooms " : (($bathrooms==1)? "1 bathroom ":"");
        $balconyStr = ($balcony>1)? "and {$balcony} balconies " : (($balcony==1)? "and 1 balcony ":"");
        $description .= " ".$bhk." flat with {$bathroomStr}{$balconyStr}in {$builderName} ".strtolower($response->projectDetails->projectName).", ".strtolower($city).".";
        $floor = $_POST["floor"]; $floors = $_POST["total_floor"];
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
        $price = $_POST["price"];
        if($_POST["price_per_unit_area"]){
            $price = ($_POST["price_per_unit_area"]*$size) + $_POST["other_charges"];
        }
        $price = $price/100000;
        $priceUnit = "lacs";
        if($price>=100){
            $price = $price/100;
            $priceUnit = "crs";
        }
        $price = number_format($price, 2);
        $description .= " The price of this property is {$price} {$priceUnit} all inclusive(registration charges extra).";
        if($_POST["homeLoanBank"]){
            $description .= " The property already has a home loan";
            if($_POST["loan_bank"] !=""){
                $bankArray = BankList::find("first",array("conditions"=>array("bank_id=?",$_POST["loan_bank"])));
                $bank = strtolower($bankArray->bank_name);
                $description .= " approved by {$bank}";
            }
            $description .= ".";
        }
        $car = "1";
        if($_POST["parking"] !="" && $_POST["parking"]>1){
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
?>
