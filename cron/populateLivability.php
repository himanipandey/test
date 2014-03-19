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
require_once ($currentDir . '/../modelsConfig.php');
require_once ($currentDir . '/../cron/cronFunctions.php');


ProjectLivability::repopulateProjectIds();
LocalityLivability::repopulateLocalityIds();

ProjectLivability::populateDistanceIndex(1);
ProjectLivability::populateDistanceIndex(2);
ProjectLivability::populateDistanceIndex(5);
ProjectLivability::populateDistanceIndex(7);
ProjectLivability::populateDistanceIndex(8);
ProjectLivability::populateDistanceIndex(9);
ProjectLivability::populateDistanceIndex(1000);
ProjectLivability::populateDistanceIndex(2000);
ProjectLivability::populateDistanceIndex(3000);

LocalityLivability::populateDistanceIndex(1);
LocalityLivability::populateDistanceIndex(2);
LocalityLivability::populateDistanceIndex(5);
LocalityLivability::populateDistanceIndex(7);
LocalityLivability::populateDistanceIndex(8);
LocalityLivability::populateDistanceIndex(9);
LocalityLivability::populateDistanceIndex(1000);
LocalityLivability::populateDistanceIndex(2000);
LocalityLivability::populateDistanceIndex(3000);
