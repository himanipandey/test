<?php
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
	AdminAuthentication();
	    
	include('bank_list_proc.php');
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."bank_list.tpl");
	
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
	
?>

