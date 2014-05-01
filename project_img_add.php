<?php
set_time_limit(0);
ini_set("memory_limit","32M");

include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("modelsConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
require_once("common/function.php");
include("imageService/image_upload.php");
include("SimpleImage.php");
include("watermark_image.class.php");
AdminAuthentication();

include('project_img_add_process.php');
$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");

$smarty->display(PROJECT_ADD_TEMPLATE_PATH."project_img_add.tpl");

$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
?>
