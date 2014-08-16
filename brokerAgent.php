<?php
	error_reporting(1);
ini_set('display_errors','1');
include("smartyConfig.php");
include("appWideConfig.php");

include("dbConfig.php");
//die("here");
include("modelsConfig.php");

include("includes/configs/configs.php");
//include("common/function.php");
include("imageService/image_upload.php");

include("function/functions_priority.php");
//die("here");
AdminAuthentication();
//die("here");
    include('brokerAgentProcess.php');
    //die("here");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."brokerAgent.tpl");
	
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
	
?>