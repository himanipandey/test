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

require_once (dirname(__FILE__) . '/../modelsConfig.php');

LandmarkDistance::truncate();
LandmarkDistance::insertProjectDistance();
LandmarkDistance::insertLocalityDistance();
LandmarkDistance::deleteInsignificantEntries();