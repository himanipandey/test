<?php
error_reporting(1);
ini_set('display_errors', '1');
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("modelsConfig.php");
include("includes/configs/configs.php");
include("function/functions_assignments.php");
include_once("includes/send_mail_amazon.php");
AdminAuthentication();
include('create_content_lot_process.php');
$smarty->display(PROJECT_ADD_TEMPLATE_PATH . "header.tpl");
$smarty->display(PROJECT_ADD_TEMPLATE_PATH . "create_content_lot.tpl");
$smarty->display(PROJECT_ADD_TEMPLATE_PATH . "footer.tpl");
?>