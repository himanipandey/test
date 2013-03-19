<?php
	error_reporting(1);
	ini_set('display_errors','1');
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
	AdminAuthentication();
	include('addquickcityprocess.php');
	//$smarty->display(SERVER_PATH."/smarty/templates/admin/header.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."addquickcity.tpl");
	//$smarty->display(SERVER_PATH."/smarty/templates/admin/footer.tpl");
	
?>

