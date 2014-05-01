<?php

	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("modelsConfig.php");
	include("builder_function.php");
	require_once("common/function.php");
    include("imageService/image_upload.php");
	include("SimpleImage.php");
	include("watermark_image.class.php");

	AdminAuthentication();
	include('image_editProcess.php');
	//$smarty->display(SERVER_PATH."/smarty/templates/admin/crawler/header.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");

	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."image_edit.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");

	
	
?>

