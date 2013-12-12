<?php
    include("smartyConfig.php");
    include("appWideConfig.php");
    include("dbConfig.php");
    include("includes/configs/configs.php");
    AdminAuthentication();
    $error_type = array(
        'rate' => 'Price/ Rate',
        'status' => 'Project Status',
        'propdetails' => 'Property details',
        'other' => 'Other',
    );
    include('reportErrorProcess.php');
    $smarty->assign("error_type", $error_type);
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."reportError.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
?>

