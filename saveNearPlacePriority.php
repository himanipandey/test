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

$boundary_array = "";

/*if($_GET['task'] === 'getLandMark')  {
    $qry = "select distinct(infrastructure_type) from landmark_infrastructure";
    $ExecSql = mysql_query($qry) or die();
    $getData = array();
    $gtdt = "";
    if (mysql_num_rows($ExecSql) > 0) {
        while($Res = mysql_fetch_assoc($ExecSql)) {
            $gtdt = $Res['infrastructure_type']; 
            array_push($getData, $gtdt);  
        }    
    }

    //echo $langlong;
    $smarty->assign("landmarkType", $getData);
    //echo $getData;
    echo json_encode($getData);
    //$landmarkType = getLandMark::getLandmarks();
    //$smarty->assign("landmarkType", $landmarkType);
    //echo $landmarkType;
}*/

if($_GET['task'] === 'GetMINDistance')  {

   $XDestination = '28.695640467825957';
   $YDestination = '77.06109157828398';
   $XStart = '28.577515600000000000';
   $YStart = '77.331576800000000000';
   $floatXStart = (float)$XStart;
   $floatYStart = (float)$YStart;
   $XB = $_GET['XB'];
   $YB = $_GET['YB'];
   $floatXB = (float)$XB;
   $floatYB = (float)$YB;

   $dist = distance($floatXStart, $floatYStart, $floatXB, $floatYB, "K");
   echo $dist.'|'.$XB.'|'.$YB;
}

if($_GET['task'] === 'GetAPIData')  {
   //$json = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?key=AIzaSyDyhEKLeFUFGs0r8VU4KvztSIahhUZ2DXc&language=en&units=metric&mode=driving&origins=28.580656,77.3188016&destinations=28.60363,77.31180');
   //$XDestination = $_GET['XBoundary'];
  // $YDestination = $_GET['YBoundary'];

   //$XDestination = '28.695640467825957';
   $XDestination = $_GET['LatNear'];
   $YDestination = $_GET['LngNear'];
   //$YDestination = '77.06109157828398';
   //26.5928613,74.257993
   $XStart = '26.5928613';
   $YStart = '74.257993';
   //$json = file_get_contents('https://maps.googleapis.com/maps/api/directions/json?language=en&units=metric&mode=driving&origins='.$XStart.','.$YStart.'&destinations='.$XDestination.','.$YDestination);
   
   $json = file_get_contents('https://maps.googleapis.com/maps/api/directions/json?origin=28.577515600000000000,77.331576800000000000&destination='.$XDestination.','.$YDestination);
   echo $json; 
   //echo json_encode($json);

}

function distance($lat1, $lon1, $lat2, $lon2, $unit) {
 
  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);
 
    if ($unit == "K") {
        return ($miles * 1.609344);
    } else if ($unit == "N") {
        return ($miles * 0.8684);
    } else {
        return $miles;
    }
}

/*echo distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";
echo distance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";
echo distance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>"; */


if($_GET['task'] === 'GetLength')  {
   $getData = $_GET['latlngArray'];
   echo $getData; 

}


if($_GET['task'] === 'GetPopUpdataSendCMs')  {
   $PopUpData = array();
   $Sql = "SELECT * FROM popDataCMS";
    $place = "";
    $city = "";
    $landtype = "";
    $ExecSql = mysql_query($Sql) or die();
    $cnt = 1;
    if (mysql_num_rows($ExecSql) > 0) {
        while($Res = mysql_fetch_assoc($ExecSql)) {
            $tmp = array();
            $tmp['place'] = $Res['place'];
            $tmp['city'] = $Res['city'];
            $tmp['landType'] = $Res['landType'];
            $tmp['distance'] = distance(28.6863155364990230, 77.0919723510742200, 28.6850051879882800, 77.4616241455078100, "K");
            array_push($PopUpData, $tmp);
        }    
    }
    //$result = $place . ' '.$city . ' ' . $landtype;
    //echo $result;
    echo json_encode($PopUpData);
}

if($_POST['task'] === 'PixeldataSendCMs')  {

   $landmark_pixel_id = 9;
   $Pixels = $_POST['Pixels'];

    $Sql = "INSERT INTO SavePixelCMS(landmark_pixel_id, Pixels) values ({$landmark_pixel_id},'{$Pixels}')";
    mysql_query($Sql) or die();

}

if($_POST['task'] === 'PopUpdataSendCMs')  {

   $landmark_data_id = 9;
   $placeName = $_POST['placeName'];
   $cityName = $_POST['cityName'];
   $landType = $_POST['landType'];

   $Sql = "SELECT * FROM boundary_data";
    $getLang = 0.0;
    $getLong = 0.0;
    $ExecSql = mysql_query($Sql) or die();
    $cnt = 0;
    if (mysql_num_rows($ExecSql) > 0) {
        while($Res = mysql_fetch_assoc($ExecSql)) {
            $getLang = $Res['latitude']; 
            $getLong = $Res['longitude'];   
        }    
    }

    $Sql = "INSERT INTO popDataCMS(landmark_data_id, latitude, longitude, place, city, landType) values ({$landmark_data_id},{$getLang},{$getLong},'{$placeName}', '{$cityName}','{$landType}')";
    mysql_query($Sql) or die();

}

if($_POST['task'] === 'DeleteLandmark_map_data')  {

    $Sql = "truncate table landmark_map_data";
    mysql_query($Sql) or die();
    echo "Complete";
}


if($_POST['task'] === 'EmptyLandmark_map_data')  {

    $Sql = "truncate table landmark_map_data";
    mysql_query($Sql) or die();

    $Sql = "INSERT INTO landmark_map_data(id) values (1)";
    mysql_query($Sql) or die(); 

    echo "Complete";
}

if($_POST['task'] === 'saveEncodedBoundary')  {
    $EncodeLatLong = $_POST['encodeString'];
    $JsonSVG = $_POST['JsonSVG'];
    $Sql = "truncate table landmark_map_data";
    mysql_query($Sql) or die();

    $Sql = "INSERT INTO landmark_map_data(id, svg_data, boundaryEncode) values (1,'{$JsonSVG}','{$EncodeLatLong}')";
    mysql_query($Sql) or die(); 

    echo "Complete";
}


if($_POST['task'] === 'MapdataSendCMs')  {
    $boundary_array = $_POST['boundary'];
    $center_of_boundary = $_POST['center_of_boundary'];
    $JsonSVG = $_POST['JsonSVG'];
    $boundary_type = $_POST['Type'];
    $EncodeLatLong = $_POST['EncodeLatLong'];
    echo $EncodeLatLong;

    //$Sql = "truncate table landmark_map_data";
    //mysql_query($Sql) or die();

    $Sql = "SELECT * FROM landmark_map_data";
    $cnt = 0;
    $ExecSql = mysql_query($Sql) or die();
    if (mysql_num_rows($ExecSql) > 0) {
      while($Res = mysql_fetch_assoc($ExecSql)) {
        $cnt++;
      }    
    }


    if($cnt == 0)  {
        $Sql = "INSERT INTO landmark_map_data(id, lat_long_data, svg_data, center_boundary, boundary_type) values (1,'{$boundary_array}','{$JsonSVG}','{$center_of_boundary}','{$boundary_type}')";
        mysql_query($Sql) or die();
    } else {
        $sql = "UPDATE landmark_map_data set lat_long_data='{$boundary_array}', svg_data='{$JsonSVG}', center_boundary='{$center_of_boundary}', boundary_type='{$boundary_type}' where id=1";
        $res_sql = mysql_query($sql);
    }    
}

function isCheck($val)  
{

    if($val == '0' || $val == '1' || $val == '2' || $val == '3' || $val == '4' || $val == '5' || $val == '6' || $val == '7' || $val == '8' || $val == '9' || $val == '.')  {
        return 1;
    } else {
        return 0;
    }
}

if($_GET['task'] === 'GetMapdataType')  {
    $id = $_GET['id'];
    $Sql = "SELECT boundary_type FROM landmarks where id = '{$id}'";
    $getData = array();
   
    $gtdt = "";
    $ExecSql = mysql_query($Sql) or die();
    if (mysql_num_rows($ExecSql) > 0) {
        while($Res = mysql_fetch_assoc($ExecSql)) {
            $gtdt = $Res['boundary_type']; 
        }    
    }
    echo $gtdt;
}

if($_GET['task'] === 'GetMapdataFromCMs')  {
    $id = $_GET['id'];
    $place = $_GET['place'];
    $Sql = "SELECT boundary FROM landmarks where id = '{$id}'";
    $getData = array();
   
    $gtdt = "";
    $ExecSql = mysql_query($Sql) or die();
    $cnt = 0;
    if (mysql_num_rows($ExecSql) > 0) {
        while($Res = mysql_fetch_assoc($ExecSql)) {
            $gtdt = $Res['boundary']; 
            $n = strlen($gtdt);
            $tempData = array();
            $langlong = '';
            
            array_push($getData, $gtdt);
            $cnt++;    
        }    
    }
    //echo $langlong;

    echo json_encode($getData);
}

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
      
    //$lat = '28.583235578433758';
    //$long = '28.583235578433758';  

    $phone   = $_POST['phone'];
    $web   = $_POST['web'];
    $prio   = $_POST['prio'];
    $status   = $_POST['status'];
    $mode =  $_POST['mode'];
    //$map = $_POST['map'];
    if($mode=='update' && $id!==null){

        $Sql = "SELECT * FROM landmark_map_data";
        $boundary = "";
        $svg = "";
        $flag_future = "";
        $center_boundary = "";
        $boundary_type = "";
        $boundaryEncode = "";
        $cnt = 0;
        $ExecSql = mysql_query($Sql) or die();
        if (mysql_num_rows($ExecSql) > 0) {
            while($Res = mysql_fetch_assoc($ExecSql)) {
                $boundary = $Res['lat_long_data'];
                $svg = $Res['svg_data'];
                $center_boundary = $Res['center_boundary'];
                $boundary_type = $Res['boundary_type'];
                $boundaryEncode = $Res['boundaryEncode'];
                $cnt++;
            }    
        }

        $center_boundary= trim ($center_boundary, ']');
        $center_boundary= trim ($center_boundary, '[');
        $json = json_decode($center_boundary);

        if($lat == "" || $long == "") {
            $lat = $json->{'0'};
            $long = $json->{'1'};
        }
        

        $flag = 0;
        
        if($cnt != 0)  {
          $sql = "UPDATE landmarks set city_id='{$city_id}', place_type_id='{$place_type_id}', name='{$name}', vicinity='{$address}', latitude='{$lat}', longitude='{$long}', phone_number='{$phone}', website='{$web}', priority='{$prio}', status='{$status}', boundary = '{$boundary}', center_boundary = '{$center_boundary}', svg_data = '{$svg}', boundary_type = '{$boundary_type}', boundaryEncode = '{$boundaryEncode}' where id='{$id}'";
          $res_sql = mysql_query($sql);
          if(mysql_affected_rows()>0)
              echo "1";
          else if (!mysql_error()) echo "2";
          else  echo "3";

        } else {
            $sql = "UPDATE landmarks set city_id='{$city_id}', place_type_id='{$place_type_id}', name='{$name}', vicinity='{$address}', latitude='{$lat}', longitude='{$long}', phone_number='{$phone}', website='{$web}', priority='{$prio}', status='{$status}' where id='{$id}'";
            $res_sql = mysql_query($sql);
            if(mysql_affected_rows()>0)
                echo "1";
            else if (!mysql_error()) echo "2";
            else  echo "3";
        }
        


        $query_max_id2 = "truncate table landmark_map_data";
        $res = mysql_query($query_max_id2);  
    }
    if ($mode=='create'){
        
        //$query = "INSERT INTO landmarks(city_id, place_type_id, name, vicinity, latitude, longitude, phone_number, website, priority, status, created_at) values ('{$city_id}', '{$place_type_id}','{$name}','{$address}','{$lat}','{$long}','{$phone}','{$web}','{$prio}','{$status}', NOW())";

        $Sql = "SELECT * FROM landmark_map_data";
        $boundary = "";
        $svg = "";
        $flag_future = "";
        $center_boundary = "";
        $boundary_type = "";
        $boundaryEncode = "";
        $ExecSql = mysql_query($Sql) or die();
        if (mysql_num_rows($ExecSql) > 0) {
            while($Res = mysql_fetch_assoc($ExecSql)) {
                $boundary = $Res['lat_long_data'];
                $svg = $Res['svg_data'];
                $center_boundary = $Res['center_boundary'];
                $boundary_type = $Res['boundary_type'];
                $boundaryEncode = $Res['boundaryEncode'];
            }    
        }

        //echo strlen($center_boundary);
        $center_boundary= trim ($center_boundary, ']');
        $center_boundary= trim ($center_boundary, '[');
        $json = json_decode($center_boundary);

        if($lat == "" || $long == "") {
            $lat = $json->{'0'};
            $long = $json->{'1'};
        }

        $flag = 0;




        //$query_max_id1 = "select max(id) from landmark_new";
        //$max_id1 = mysql_query($query_max_id1);

        $query = "INSERT INTO landmarks(city_id, place_type_id, name, vicinity, latitude, longitude, phone_number, website, priority, status, created_at, boundary, center_boundary, future_flag, svg_data, boundary_type, boundaryEncode) values ('{$city_id}', '{$place_type_id}','{$name}','{$address}', '{$lat}','{$long}','{$phone}','{$web}','{$prio}','{$status}', NOW(),'{$boundary}','{$center_boundary}', 0, '{$svg}', '{$boundary_type}', '{$boundaryEncode}')";
        $res = mysql_query($query);
        if(mysql_affected_rows()>0)
            echo "1";
        else
            echo "3".mysql_error();

        $query_max_id2 = "truncate table landmark_map_data";
        //echo " max id = ",$max_id2;
        //$queryforboundarydata = "INSERT INTO boundary_data(landmark_id, latitude, longitude, boundary) values ('{$max_id2}', '{$lat}','{$long}','{$boundary_array}')";
        $res = mysql_query($query_max_id2);      
    }
        
}
    
?>