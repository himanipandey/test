<?php

/**
 * @author AKhan
 * @copyright 2013
 */

include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("s3upload/s3_config.php");
include("SimpleImage.php");
date_default_timezone_set('Asia/Kolkata');
AdminAuthentication();	
include("modelsConfig.php");

include('sellercompanyManageProcess.php');

$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
$smarty->display(PROJECT_ADD_TEMPLATE_PATH."manageSeller.tpl");
$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");




?>