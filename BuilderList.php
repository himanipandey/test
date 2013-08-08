<?php
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php"); 
	AdminAuthentication();
        
        include('BuilderManageProcess.php');
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."manageBuilder.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
	
?>

