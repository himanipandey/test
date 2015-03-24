<?php
//calling function for all the cities
include("appWideConfig.php");
include("dbConfig.php");
include("listing_function.php");
//die("here");
include("function/functions_listing.php");
include("httpful.phar");

$page =  $_REQUEST['page'];
$size = $_REQUEST['size'];
//$size = 1;
$start = $page*$size;

$cityId = $_REQUEST['city']; 
if($cityId==''){
    $cityId=2;
}
$projectId = $_REQUEST['project']; 
$listingId = $_REQUEST['listingId']; 
$projStr="";
if(isset($projectId) && !empty($projectId) && ($projectId !="null") && ($projectId !="")){
    $projStr = "&projectId=".$projectId;
}

$listingIdStr="";
if(isset($listingId) && !empty($listingId) && ($listingId !="null") && ($listingId !="")){
    $listingIdStr = $listingId;
}


$tbsorterArr = array();

$uriLogin = ADMIN_USER_LOGIN_API_URL; //master
//$uriLogin = "https://qa.proptiger-ws.com/app/v1/login?username=admin-10@proptiger.com&password=1234&rememberme=true"; //normal user
//$projectId="653678";
//$uriListing = "https://qa.proptiger-ws.com/data/v1/entity/user/listing?cityId=2&fields=seller,id,property&start=0&rows=10";
$uriListing = LISTING_API_URL."?listingCategory=Resale&cityId={$cityId}{$projStr}&start={$start}&rows={$size}&fields=seller,seller.fullName,id,listing,listing.facingId,listing.jsonDump,listing.description,listing.remark,listing.homeLoanBankId,listing.flatNumber,listing.noOfCarParks,listing.negotiable,listing.transferCharges,listing.plc,listing.towerId,listing.phaseId,property,property.propertyId,property.project.name,property.projectId,property.project.builder,property.project.locality,property.project.locality.suburb,property.project.locality.suburb.city,listingAmenities.amenity,listingAmenities.amenity.amenityMaster,label,masterAmenityIds,name,unitType,unitName,size,currentListingPrice,localityId,floor,pricePerUnitArea,price,otherCharges,jsonDump,latitude,longitude,amenityDisplayName,isDeleted,bedrooms,bathrooms,amenityId";

//echo $uriListing; die;
//$uri = "https://qa.proptiger-ws.com/data/v1/entity/user/listing";
//$dataArr = array();
//$dataArr['sellerId'] = "1216008";
//$dataJson = json_encode($dataArr);
try{ 

    

    $responseLogin = \Httpful\Request::post($uriLogin)                  // Build a PUT request...
    ->sendsJson()                               // tell it we're sending (Content-Type) JSON...
    ->body('')             // attach a body/payload...
    ->send(); 


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


   
   
    if($ck_new!='')
    {    
        $responseLists = \Httpful\Request::get($uriListing)->addHeader("COOKIE", $ck_new )->send(); 
        //var_dump($responseLists->body);
        if($responseLists->body->statusCode=="2XX"){
            $data = $responseLists->body->data;

            $tbsorterArr['total_rows'] = $responseLists->body->totalCount;
            $tbsorterArr['headers'] = array("Serial", "Listing Id", "City", "Broker Name", "Project", "Listing", "Price", "Save");
            
            $tbsorterArr['rows'] = array();
            
            echo "<pre>";
            print_r(get_object_vars($data[0]));
            
            //var_dump($responseLists->body);
            foreach ($data as $k => $v){ 
                //$uriListingDetail =  "https://qa.proptiger-ws.com/data/v1/entity/user/listing/".$v->id;                
                //$responseListingDetail = \Httpful\Request::get($uriListingDetail)->addHeader("COOKIE", $ck_new )->send();
                //if($responseListingDetail->body->statusCode=="2XX"){
                $seller_id = $v->seller->id;
                $company_id='';
                if($seller_id){
                    $Sql = "SELECT c.name, c.id FROM company c inner join company_users cu on c.id=cu.company_id WHERE cu.user_id=".$seller_id." and c.status = 'Active' and cu.status='Active' ";
                    //echo $Sql;
                    $Sel = array();
                    $ExecSql = mysql_query($Sql) or die(mysql_error() . ' Error in fetching data from company_users');
                    $cnt = 0;
                    if (mysql_num_rows($ExecSql) > 0) {
                        $Res = mysql_fetch_assoc($ExecSql);
                        $broker_name = $Res['name'];
                        $broker_id = $Res['id'];
                           
                    }
                    $v->seller->brokerName = $broker_name; 
                    $v->seller->brokerId = $broker_id; 
                }
                
                $tmp['json'] = htmlentities(json_encode($v));
                $tmp['val'] = $v;
                array_push($resaleListings,$tmp);
                if ($v->currentListingPrice->pricePerUnitArea != 0)
                    $price = "Price Per Unit Area - ".$v->currentListingPrice->pricePerUnitArea;
                else
                    $price = "Price - ".$v->currentListingPrice->price;
                if ($v->currentListingPrice->otherCharges != 0)
                    $price .= "<br>Other Charges - ".$v->currentListingPrice->otherCharges;
                $v->property->project->description = '';
                $v->property->project->locality->description = ''; 
                $v->property->project->locality->suburb->description = '';   
                $v->property->project->locality->suburb->city->description = '';                            
                //echo "here";
                $rows = array(
                                            "Serial" => $start+$k+1,
                                            "City" => $v->property->project->locality->suburb->city->label,
                                            "BrokerName" => $v->seller->brokerName,
                                            "Project" => $v->property->project->name. ", ".$v->property->project->builder->name,
                                            "Listing" => $v->property->unitName."-".$v->property->size."-".$v->property->unitType,
                                            "Price" => $price,
                                            "Save" =>  json_encode($v),//htmlentities(json_encode($v)), //$v,
                                            "ListingId" => $v->id
                        );
                //var_dump($rows);

                array_push($tbsorterArr['rows'], $rows);
            

            }
        }

    }



 

 } 
 catch(Exception $e)  {
    print_R($e);
 }

 echo json_encode($tbsorterArr);

 ?>