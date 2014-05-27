<?php

	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	date_default_timezone_set('Asia/Kolkata');
	include("builder_function.php"); 
	AdminAuthentication();	
	include("modelsConfig.php");
	include('campagindids_process.php');
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."campagindids.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
?>

