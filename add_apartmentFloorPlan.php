<?php

	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
    include("modelsConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php"); 
	require_once("common/function.php");
	include("SimpleImage.php");
	include("watermark_image.class.php");
    include("imageService/image_upload.php");
	AdminAuthentication();

	include('add_apartmentFloorPlanProcess.php'); 
	//$smarty->display(SERVER_PATH."/smarty/templates/admin/crawler/header.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");

	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."add_apartment_floor_plan.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");

	
	
?>

