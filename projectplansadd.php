<?php
set_time_limit(0);
ini_set("memory_limit","32M");

include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("includes/function.php");
include("SimpleImage.php");
include("watermark_image.class.php");
AdminAuthentication();

include('projectplansaddprocess.php');

	$smarty->display(OFFLINE_PROJECT_TEMPLATE_PATH."header.tpl");
	$smarty->display(OFFLINE_PROJECT_TEMPLATE_PATH."projectplansadd.tpl");
	$smarty->display(OFFLINE_PROJECT_TEMPLATE_PATH."footer.tpl");


?>