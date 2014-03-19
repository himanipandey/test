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
$aExpression = array(
    1 => 'exp(-(greatest(500, distance)*(0.1)/1000))/priority', // school
    2 => 'exp(-(greatest(500, distance)*(0.1)/1000))/priority', // hospital
    5 => 'exp(-(greatest(500, distance)*(0.1)/1000))/priority',
    100 => 'count(distance)'                                      // parks
);

$aColumnName = array(
    1 => 'school',
    2 => 'hospital',
    5 => 'restaurant'
);

populateTables();
populateDistanceIndex(1);
populateDistanceIndex(2);
populateDistanceIndex(5);

function populateDistanceIndex($landmarkTypeId) {
    global $aExpression, $aColumnName;

    $expression = $aExpression[$landmarkTypeId];

    $projectMaxSql = "select sum($expression) ranking from landmark_distances where place_type_id = $landmarkTypeId and object_type = 'Project' group by object_id order by ranking desc limit 1";

    $projectMaxVal = Resiproject::find_by_sql($projectMaxSql);
    if(count($projectMaxVal)>0){
        $projectMaxVal = $projectMaxVal[0]->ranking;
        $projectUpdateSql = "update project_livability pl inner join (select object_id, sum($expression)/$projectMaxVal ranking from landmark_distances where place_type_id = $landmarkTypeId and object_type = 'Project' group by object_id) t on pl.project_id = t.object_id set pl.$aColumnName[$landmarkTypeId] = t.ranking";
        ActiveRecord\Connection::instance()->query($projectUpdateSql);
    }

    $localityMaxSql = "select sum($expression) ranking from landmark_distances where place_type_id = $landmarkTypeId and object_type = 'Project' group by object_id order by ranking desc limit 1";
    $localityMaxVal = Resiproject::find_by_sql($localityMaxSql);
    if (count($localityMaxVal) > 0) {
        $localityMaxVal = $localityMaxVal[0]->ranking;
        $localityUpdateSql = "update locality_livability ll inner join (select object_id, sum($expression)/$localityMaxVal ranking from landmark_distances where place_type_id = $landmarkTypeId and object_type = 'Locality' group by object_id) t on ll.locality_id = t.object_id set ll.$aColumnName[$landmarkTypeId] = t.ranking";
        ActiveRecord\Connection::instance()->query($localityUpdateSql);
    }
}

function populateTables() {
    truncateTable('project_livability');
    truncateTable('locality_livability');

    $sql = "insert into project_livability (project_id) select PROJECT_ID from resi_project group by PROJECT_ID;";
    ActiveRecord\Connection::instance()->query($sql);
    $sql = "insert into locality_livability (locality_id) select LOCALITY_ID from locality";
    ActiveRecord\Connection::instance()->query($sql);
}

function populateSecurity(){
    $sql = "";
}