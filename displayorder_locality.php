<?php

include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
	AdminAuthentication();
	include('displayorder_loc_process.php');
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
	
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."displayorder_locality.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
	?>