<?php
error_reporting(1);
ini_set('display_errors','1');
include("smartyConfig.php");
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
    
?>