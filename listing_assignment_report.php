<?php

//error_reporting(1);
//ini_set('display_errors','1');
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("modelsConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
include("function/functions_assignments.php");
include("httpful.phar");
AdminAuthentication();

include('listing_assignment_report_process.php');
$smarty->display(PROJECT_ADD_TEMPLATE_PATH . "header.tpl");
$smarty->display(PROJECT_ADD_TEMPLATE_PATH . "listing_assignment_report.tpl");
$smarty->display(PROJECT_ADD_TEMPLATE_PATH . "footer.tpl");
?>
