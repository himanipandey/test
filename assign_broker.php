<?php
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
	AdminAuthentication();
	$dept = $_SESSION['DEPARTMENT'];
	$smarty->assign("arrProjEditPermission", $ARR_PROJ_EDIT_PERMISSION[$dept]);
        include("dbConfig_crm.php");
        include("function/resale_functions.php");
	include('assign_brokerProcess.php');
        $smarty->register_function('ViewCityDetails', 'ViewCityDetails');
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."assign_broker.tpl");


	
	
?>
