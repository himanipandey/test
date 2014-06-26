<?php
    error_reporting(1);
    ini_set('display_errors','1');
    include("smartyConfig.php");
    include("appWideConfig.php");
    include("dbConfig.php");
    include("modelsConfig.php");
    include("includes/configs/configs.php");
    include("builder_function.php");
    require_once "$_SERVER[DOCUMENT_ROOT]/includes/db_query.php";
    AdminAuthentication();
    require_once ('datacollection/projectConstImgProcess.php');
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."projectConstImg.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
?>