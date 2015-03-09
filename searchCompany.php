<?php

error_reporting(1);
ini_set('display_errors', '1');
set_time_limit(0);
ini_set("memory_limit", "256M");
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("modelsConfig.php");
include("includes/configs/configs.php");
AdminAuthentication();

$reqCompanyType = $_GET["companyType"];
$reqCompanyTerm = $_GET["companyTerm"];

$comp = Company::getCompanyNamesByTypeTerm($reqCompanyType, $reqCompanyName);
echo json_encode(array("data"=>$comp));
?>