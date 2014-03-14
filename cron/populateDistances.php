<?php

/* 
 * Author
 * Azitabh Ajit
 * 
 */

ini_set('display_errors', '1');
set_time_limit(0);
error_reporting(E_ALL);

// TODO
// Get demand data

$currentDir = dirname(__FILE__);
require_once ($currentDir . '/../log4php/Logger.php');
require_once ($currentDir . '/../modelsConfig.php');
require_once ($currentDir . '/../cron/cronFunctions.php');


// Config Values
$invalidCoordinates = array(0,1,2,3,4,5,6,7,8,9);
$allLandmarkTypes = array(1,2,5,7,8,9,13);

$maxLandmarkDistance = array(0=>10000, 1=>5000);



echo $sql = "insert into landmark_distances (object_id, object_type, landmark_id, distance, city_id, place_type_id, priority) select rp.PROJECT_ID, 'Project', ld.id, ((ACOS(SIN(rp.LATITUDE * PI() / 180) * SIN(ld.latitude * PI() / 180) + COS(rp.LATITUDE * PI() / 180) * COS(ld.latitude * PI() / 180) * COS((rp.LONGITUDE - ld.longitude) * PI() / 180)) * 180 / PI()) * 60 * 1.1515 * 1609.34) AS distance, ld.city_id, ld.place_type_id, ld.priority from resi_project rp inner join locality l on rp.LOCALITY_ID = l.LOCALITY_ID inner join suburb s on l.SUBURB_ID = s.SUBURB_ID inner join landmarks ld on s.CITY_ID = ld.city_id where rp.version = 'Website' and rp.LONGITUDE not in (" . implode(",", $invalidCoordinates) . ") and rp.LATITUDE not in (" . implode(",", $invalidCoordinates) . ") and ld.status = 'Active' and ld.place_type_id in (" . implode(",", $allLandmarkTypes) . ")";

ActiveRecord\Connection::instance()->query($sql);


foreach($maxLandmarkDistance as $k=>$v){
  $sql = "delete from landmark_distances where place_type_id = $k and distance > $v";
  ActiveRecord\Connection::instance()->query($sql);
}
