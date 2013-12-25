<?php
/*
Objects V0.1
Authored by: Azitabh Ajit
Date: 19/12/2013
 */

$docroot = dirname(__FILE__) . "/../";
require_once $docroot.'modelsConfig.php';


ResiProject::delete_website_version();
ResiProject::partially_migrate_projects();
?>
