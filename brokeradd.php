<?php
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
        include("dbConfig_crm.php");
        include("function/resale_functions.php");
	AdminAuthentication();
	include('brokerAddProcess.php');
        include("dbConfig.php");
         $cityArr = CityArr();
        $smarty->assign("cityArr", $cityArr);
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."brokeradd.tpl");

	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
?>