<?php
    error_reporting(1);
    ini_set('display_errors','1');
    include("smartyConfig.php");
    include("appWideConfig.php");
    include("dbConfig.php");
    include("modelsConfig.php");
    include("includes/configs/configs.php");
    include("builder_function.php");
    AdminAuthentication();
    include('addquickcityprocess.php');
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."addquickcity.tpl");
	
?>

