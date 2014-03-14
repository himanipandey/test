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
$aInvalidCoordinates = array(0,1,2,3,4,5,6,7,8,9);
$aAllLandmarkTypes = array(1,2,5,7,8,9,13);

$aMaxLandmarkDistance = array(
    1=>10000,   //school
    2=>5000     // hospital
    );

$aMaxLandmarkCount = array(
    1=>100,     // school
    2=>50       // hospital
    );

$connection = ActiveRecord\Connection::instance();

truncateTable('landmark_distances');
insertDistances();
deleteInsignificantEntries();


foreach($aMaxLandmarkDistance as $k=>$v){
  $sql = "delete from landmark_distances where place_type_id = $k and distance > $v";
  $connection->query($sql);
}

function insertDistances(){
    insertProjectDistance();
    insertLocalityDistance();
}

function insertProjectDistance(){
    global $connection, $aInvalidCoordinates, $aAllLandmarkTypes;

    $sql = "insert into landmark_distances (object_id, object_type, landmark_id, distance, city_id, place_type_id, priority) select rp.PROJECT_ID, 'Project', ld.id, " . getDBDistanceQueryString('rp.LONGITUDE', 'rp.LATITUDE', 'ld.longitude', 'ld.latitude') . " AS distance, ld.city_id, ld.place_type_id, ld.priority from resi_project rp inner join locality l on rp.LOCALITY_ID = l.LOCALITY_ID inner join suburb s on l.SUBURB_ID = s.SUBURB_ID inner join landmarks ld on s.CITY_ID = ld.city_id where rp.version = 'Website' and rp.LONGITUDE not in (" . implode(",", $aInvalidCoordinates) . ") and rp.LATITUDE not in (" . implode(",", $aInvalidCoordinates) . ") and ld.status = 'Active' and ld.place_type_id in (" . implode(",", $aAllLandmarkTypes) . ")";
    $connection->query($sql);
}

function insertLocalityDistance(){
    global $connection, $aInvalidCoordinates, $aAllLandmarkTypes;

    $sql = "insert into landmark_distances (object_id, object_type, landmark_id, distance, city_id, place_type_id, priority) select l.LOCALITY_ID, 'Locality', ld.id, " . getDBDistanceQueryString('l.LONGITUDE', 'l.LATITUDE', 'ld.longitude', 'ld.latitude') . " AS distance, ld.city_id, ld.place_type_id, ld.priority from locality l inner join suburb s on l.SUBURB_ID = s.SUBURB_ID inner join landmarks ld on s.CITY_ID = ld.city_id where l.LONGITUDE not in (" . implode(",", $aInvalidCoordinates) . ") and l.LATITUDE not in (" . implode(",", $aInvalidCoordinates) . ") and ld.status = 'Active' and ld.place_type_id in (" . implode(",", $aAllLandmarkTypes) . ")";

    $connection->query($sql);
}

function deleteInsignificantEntries(){
    deleteEntriesBasedOnDistance();
    deleteEntriesBasedOnCount();
}

function deleteEntriesBasedOnDistance(){
    global $connection, $aMaxLandmarkDistance;

    foreach($aMaxLandmarkDistance as $k=>$v){
        $sql = "delete from landmark_distances where place_type_id = $k and distance > $v";
        $connection->query($sql);
    }
}

function deleteEntriesBasedOnCount(){
    global $connection, $aMaxLandmarkCount;
    
    foreach($aMaxLandmarkCount as $landmarkType=> $count) {
        $sql = "select object_id, object_type from landmark_distances where place_type_id = $landmarkType group by object_id, object_type";
        $aRow = ResiProject::find_by_sql($sql);
        foreach ($aRow as $row) {
            $sql = "delete ld.* from landmark_distances ld inner join (select id, distance from landmark_distances where object_id = $row->object_id and object_type = '$row->object_type' and place_type_id = $landmarkType order by distance limit 100000 OFFSET $count) t on ld.id = t.id;";
            $connection->query($sql);
        }
    }
}