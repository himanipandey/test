<?php

	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
        include("modelsConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
	AdminAuthentication();
	include('add_project_construction_Process.php');
	//$smarty->display(SERVER_PATH."/smarty/templates/admin/crawler/header.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."add_project_construction.tpl");

	
	
?>

