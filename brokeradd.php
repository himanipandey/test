<?php
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
        include("function/resale_functions.php");
	AdminAuthentication();
	include('brokerAddProcess.php');
        include("modelsConfig.php");
         $cityArr = City::CityArr();
        $smarty->assign("cityArr", $cityArr);
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."brokeradd.tpl");
	include("broker_suggest_auto.php");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
?>
