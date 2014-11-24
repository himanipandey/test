<?php
	error_reporting(1);
ini_set('display_errors','1');
include("smartyConfig.php");
include("appWideConfig.php");

include("dbConfig.php");
//die("here");
include("modelsConfig.php");

include("includes/configs/configs.php");


AdminAuthentication();
//die("here");
    include('findOTPProcess.php');
    //die("here");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."findOTP.tpl");
	
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
	
?>