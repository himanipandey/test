<?php
error_reporting(1);
ini_set('display_errors','1');
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
	AdminAuthentication();
	$dept = $_SESSION['DEPARTMENT'];
        
        
        
	$smarty->assign("arrProjEditPermission", $ARR_PROJ_EDIT_PERMISSION[$dept]);
        include("function/resale_functions.php");
	include('secondary_priceProcess.php');
        
        include("dbConfig_crm.php");
         $smarty->register_function('getBrokerDetailById', 'getBrokerDetailById');
        include("dbConfig.php");
        
         $smarty->register_function('ViewCityDetails', 'ViewCityDetails');
         $smarty->register_function('ViewLocalityDetails', 'ViewLocalityDetails');
	//$smarty->display(SERVER_PATH."/smarty/templates/admin/crawler/header.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."secondary_price.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");

	
	
?>
