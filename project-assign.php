<?php
    error_reporting(1);
    ini_set('display_errors','1');
    include("smartyConfig.php");
    include("appWideConfig.php");
    include("dbConfig.php");
    include("includes/configs/configs.php");
    include("builder_function.php");
    require_once "$_SERVER[DOCUMENT_ROOT]/includes/db_query.php";
    AdminAuthentication();
    include('projectAssignProcess.php');
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."project-assign.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
    
?>
