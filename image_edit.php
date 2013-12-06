<?php

	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
    include("s3upload/s3_config.php");
	include("SimpleImage.php");
	include("watermark_image.class.php");

	AdminAuthentication();
	include('image_editProcess.php');
	//$smarty->display(SERVER_PATH."/smarty/templates/admin/crawler/header.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");

	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."image_edit.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");

	
	
?>

