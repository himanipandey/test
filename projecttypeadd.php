<?php
set_time_limit(0);
ini_set("memory_limit","32M");
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("includes/function.php");
AdminAuthentication();
include('projecttypeaddprocess.php');
	$smarty->display(OFFLINE_PROJECT_TEMPLATE_PATH."header.tpl");
	$smarty->display(OFFLINE_PROJECT_TEMPLATE_PATH."projecttypeadd.tpl");
	$smarty->display(OFFLINE_PROJECT_TEMPLATE_PATH."footer.tpl");

?>