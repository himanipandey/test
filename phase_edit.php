<?php

include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
include("modelsConfig.php");
AdminAuthentication();
include('phaseProcess_edit.php');
$smarty->display(PROJECT_ADD_TEMPLATE_PATH . "header.tpl");
$smarty->display(PROJECT_ADD_TEMPLATE_PATH . "phase_edit.tpl");
$smarty->display(PROJECT_ADD_TEMPLATE_PATH . "footer.tpl");
?>
