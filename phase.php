<?php

include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
include("modelsConfig.php");
AdminAuthentication();
include('phaseProcess.php');
$smarty->display(PROJECT_ADD_TEMPLATE_PATH . "header.tpl");
$smarty->display(PROJECT_ADD_TEMPLATE_PATH . "phase.tpl");
$smarty->display(PROJECT_ADD_TEMPLATE_PATH . "footer.tpl");
?>
