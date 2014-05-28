<?php

    include("smartyConfig.php");
    include("appWideConfig.php");
    include("dbConfig.php");
    include("includes/configs/configs.php");
    include("builder_function.php");
    include("modelsConfig.php");
    AdminAuthentication();
    $dept = $_SESSION['DEPARTMENT'];

    include("function/resale_functions.php");
    include('callToBrokerProcess.php');

    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."callToBroker.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");

	
	
?>
