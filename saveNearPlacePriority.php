<?php

error_reporting(1);
ini_set('display_errors','1');

    echo  $_POST['cityid'];
    echo  $_POST['broker_name'];
    echo  $_POST['projectid'];
    echo  $_POST['projid'];
    echo  $_POST['bhk1'];
    echo  $_POST['facing'];
    echo  $_POST['size'];  
    echo  $_POST['bathroom'];
    echo  $_POST['toilet'];
    echo  $_POST['tower'];
    echo  $_POST['floor'];
    echo  $_POST['price_type'];
    echo  $_POST['price'];
    echo  $_POST['trancefer_rate'];
    echo  $_POST['price_in'];
    echo  $_POST['flat_number'];
    echo  $_POST['parking'];
    echo  $_POST['loan_bank'];
    echo  $_POST['plc_val'];
    echo  $_POST['study_room'];
    echo  $_POST['servant_room'];

    $bhk = $_POST['bhk1'];
    $flag = 0;
    $st1 = "";
    $st2 = "";
    for ($i = 0; $i < $bhk.size; $i++)  {
        if($flag == 0) {
            $st1 = $st1.$bhk[$i];
            if($bhk[$i] == ',')  {
                $flag = 1;
                break;
            }
            if($flag == 1){
              $st2 = $st2.$bhk[$i];
            } 
        }
    }

    $bthrm = "";
    $toil = "";
    if (strpos($st1,',') !== false) {
        $flag = 0;
        for($i = 0; $i < $st1.size; $i++)  {
            if($flag === 0)  {
                $bthrm = $bthrm.$st1[$i];
                if($st1[$i] === '+') {
                    $flag = 1;
                }
            }
            if(flag === 1) {
                $toil = $toil.$st1[$i];

            }
        }
    }

    $st = "0123456789";
    $bedrooms = "";
    for($i = 0; $i < $bthrm.size; $i++)  {
        if (strpos($st,$bthrm[$i]) !== false) {
            $bedrooms = $bedrooms.$bthrm[$i];
        } else {
            break;
        }
    }

    $toilets = "";
    for($i = 0; $i < $bthrm.size; $i++)  {
        if (strpos($st,$toil[$i]) !== false) {
            $toilets = $toilets.$toil[$i];
        } else {
            break;
        }
    }

    $sz = "";
    for($i = 0; $i < $st2.size; $i++)  {
        if (strpos($st,$st2[$i]) !== false) {
            $sz = $sz.$st2[$i];
        } else {
            break;
        }
    }
    
$dataArr = array();
$dataArr['floor'] = $_POST['floor'];
$jsonDump = array(
    'comment' => $_POST['broker_name']
    );
$dataArr['jsonDump'] = $jsonDump;
$dataArr['sellerId'] = $_POST['seller_id'];
$dataArr['flatNumber'] = $_POST['flat_number'];
$dataArr['homeLoanBankId'] = $_POST['loan_bank'];
$dataArr['noOfCarParks'] = $_POST['parking'];
$dataArr['negotiable'] = "true";
$dataArr['transferCharges'] = $_POST['trancefer_rate']; 
$dataArr['plc'] = $_POST['plc_val'];
$otherInfo = array(
    'size'=> $sz,
    'projectId'=> $_POST['projectid'],
    'bedrooms'=> $bedrooms,
    'unitType'=> "Sq. Ft.",
    'penthouse'=>"true",
    'studio' => "true",
    'facing' => $_POST['facing']
    ); 
$dataArr['otherInfo'] = $otherInfo;
$masterAmenityIds = array(
    1,2,3,4
    );
$dataArr['masterAmenityIds'] = $masterAmenityIds;
$currentListingPrice = array(
    'pricePerUnitArea'=> $_POST['price']
    );
$dataArr['currentListingPrice'] = $currentListingPrice;
$dataArr['discription'] = $_POST['discription'];


//'{"floor":{$x},"jsonDump":"{\"comment\":\"anubhav\"}","sellerId":"1216008","flatNumber":"D-12","homeLoanBankId":"1","noOfCarParks":"3","negotiable":"true","transferCharges":1000,"plc":200,"otherInfo":{"size":"100","projectId":"656368","bedrooms":"3","unitType":"Plot","penthouse":"true","studio":"true","facing":"North"},"masterAmenityIds":[1,2,3,4],"currentListingPrice":{"pricePerUnitArea":2000,}}'
$dataJson = json_encode($dataArr);

    include('./httpful.phar');

    $uri = "http://qa.proptiger-ws.com/data/v1/entity/user/listing";
    $uri1 = "https://qa.proptiger-ws.com/app/v1/login?username=admin-10@proptiger.com&password=1234";
    $i = 0;
    while($i < 10) {

    }    
    try{ 
        $response_login = \Httpful\Request::post($uri1)->sendIt();
        


        $response = \Httpful\Request::put($uri)->authenticateWith('admin-22550@proptiger.com', '1234')->sendsJson()->body($dataJson)->sendIt();    
        echo "This response has " . count($response); 
        echo $response,'\n';
        //echo $response1;
        //admin-22550@proptiger.com 
        //1234 


    } catch(Exception $e)  {
        print_R($e);
    }

/*include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
include("function/functions_priority.php");
AdminAuthentication();

    

if($_POST['task']=='editpriority'){
    $priority   = $_POST['prio'];
    $cityId     = $_POST['cityid'];

    if(!empty($_POST['nearPlaceId']))
    {
        $nearPlaceId    = $_POST['nearPlaceId'];
        $status = $_POST['status'];
        if($priority < 1 || trim($priority) == '' || $priority > 5){
    	     echo 4; return;
    	}
        if(!empty($sub)){
            $count = checkNearPlaceAvail($nearPlaceId, $priority, 'suburb', $sub);
            if($count > 0)
            {
                
                updateNearPlace($nearPlaceId, $priority, 'suburb', $sub);
            }else{
                echo "2";
            }
        }else if(!empty ($loc)){
            $count = checkNearPlaceAvail($nearPlaceId, $priority, 'locality', $loc);
            if($count > 0)
            {
                
                updateNearPlace($nearPlaceId, $priority, 'locality', $loc);
            }else{
                echo "2";
            }
        }else{

            updateNearPlace($nearPlaceId, $priority, $status, 'city', $cityId);
            
        }
    }
    else
    {
    			
        if($priority < 1 || trim($priority) == '' || $priority > 5){
    	     echo 4; return;
    	}
            
    }

}

else if($_POST['task']=='createLandmarkAlias'){
    $id = $_POST['id'];
    $city_id = $_POST['cid'];
    $place_type_id = $_POST['placeid'];

    $name   = $_POST['name'];
    $address   = $_POST['address'];
    $lat   = $_POST['lat'];
    $long   = $_POST['lon'];
    $phone   = $_POST['phone'];
    $web   = $_POST['web'];
    $prio   = $_POST['prio'];
    $status   = $_POST['status'];
    $mode =  $_POST['mode'];
    
    if($mode=='update' && $id!==null){
        
        $sql = "UPDATE landmarks set city_id='{$city_id}', place_type_id='{$place_type_id}', name='{$name}', vicinity='{$address}', latitude='{$lat}', longitude='{$long}', phone_number='{$phone}', website='{$web}', priority='{$prio}', status='{$status}' where id='{$id}'";
        $res_sql = mysql_query($sql);
        if(mysql_affected_rows()>0)
            echo "1";
        else if (!mysql_error()) echo "2";
        else  echo "3";

    }
    if ($mode=='create'){
        
        $query = "INSERT INTO landmarks(city_id, place_type_id, name, vicinity, latitude, longitude, phone_number, website, priority, status, created_at) values ('{$city_id}', '{$place_type_id}','{$name}','{$address}','{$lat}','{$long}','{$phone}','{$web}','{$prio}','{$status}', NOW())";
        $res = mysql_query($query);
        if(mysql_affected_rows()>0)
            echo "1";
        else
            echo "3".mysql_error();
    }
        
}

*/    

?>
