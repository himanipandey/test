<?php

/*
 * @Author : Jitendra pathak
 */
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("modelsConfig.php");
include("includes/configs/configs.php");

AdminAuthentication();
include('metaTemplatesProcess.php');
$smarty->display(PROJECT_ADD_TEMPLATE_PATH . "header.tpl");
if ($_REQUEST["operation"] == "edit") {
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH . "addMetaTemplates.tpl");
}else{
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH . "metaTemplates.tpl");
}
$smarty->display(PROJECT_ADD_TEMPLATE_PATH . "footer.tpl");
?>
