<?php
	error_reporting(1);
	ini_set('display_errors','1');
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
	AdminAuthentication();

	include('builder_contact_info_process.php');
        $smarty->assign('arrCampaign', $arrCampaign);
        $smarty->display(PROJECT_ADD_TEMPLATE_PATH."builder_contact_info.tpl");
?>

