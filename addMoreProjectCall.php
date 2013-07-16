<?php
    include("smartyConfig.php");
    include("appWideConfig.php");
    include("dbConfig.php");
    include("includes/configs/configs.php");
    include("builder_function.php");
    AdminAuthentication();
    $dept = $_SESSION['DEPARTMENT'];
    $smarty->assign("arrProjEditPermission", $ARR_PROJ_EDIT_PERMISSION[$dept]);
    include("dbConfig_crm.php");
    include("function/resale_functions.php");
    include('addMoreProjectCallProcess.php');
    die("1");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
    die("2");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."addMoreProjectCall.tpl");
    die("3");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
?>
