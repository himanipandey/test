<?php
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("../../dbConfig.php");
	include("../includes/configs/configs.php");
	include("../includes/function.php"); 
	AdminAuthentication();
	include('ProjectTypeManageProcess.php');

	$smarty->display(OFFLINE_PROJECT_TEMPLATE_PATH."header.tpl");
	$smarty->display(OFFLINE_PROJECT_TEMPLATE_PATH."manageProjectType.tpl");
	$smarty->display(OFFLINE_PROJECT_TEMPLATE_PATH."footer.tpl");
	
	
?>

