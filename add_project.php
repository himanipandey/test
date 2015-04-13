<?php
error_reporting(1);
ini_set('display_errors','1');
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
        include_once("log4php/Logger.php");
        include_once("imageService/image_upload.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
        include("modelsConfig.php"); 
	AdminAuthentication();
        include("function/projectPhase.php");
	include('add_projectProcess.php');
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");

	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."add_project.tpl");
        include("builder_suggest_auto.php");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");

	
	
?>

