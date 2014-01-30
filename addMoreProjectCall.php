<?php
    include("smartyConfig.php");
    include("appWideConfig.php");
    include("dbConfig.php");
    include("includes/configs/configs.php");
    include("builder_function.php");
    AdminAuthentication();
    $dept = $_SESSION['DEPARTMENT'];
    $smarty->assign("arrProjEditPermission", $ARR_PROJ_EDIT_PERMISSION[$dept]);
    include('addMoreProjectCallProcess.php');
    
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."addMoreProjectCall.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
?>
