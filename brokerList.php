<?php
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
        include("function/resale_functions.php");
        include("dbConfig_crm.php");
	AdminAuthentication();
       
	include('brokerManageProcess.php');
        include("dbConfig.php");
         $smarty->register_function('ViewCityDetails', 'ViewCityDetails');
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."manageBroker.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
	
?>

