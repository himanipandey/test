<?php
	error_reporting(1);
	ini_set('display_errors','1');
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
	AdminAuthentication();
	
	include('add_apartmentConfigurationProcess.php');
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");

	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."add_apartment_configuration.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");	
?>

