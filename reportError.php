<?php
    include("smartyConfig.php");
    include("appWideConfig.php");
    include("dbConfig.php");
    include("includes/configs/configs.php");
    AdminAuthentication();
    $error_type = array(
        'rate' => 'Price/ Rate',
        'status' => 'Project Status',
        'propdetails' => 'Property Details',
        'other' => 'Other',
    );
    
    $server_name = array(
        'cms.proptiger' => 'dev.proptiger',
        'cms.proptiger-ws.com' => 'nightly-build.proptiger-ws.com',
        'cms.proptiger.com' => 'proptiger.com'
    );
    
    $img_server_name = $server_name[$_SERVER['SERVER_NAME']];
    if($img_server_name==''){
        $img_server_name =  'proptiger.com';
    }        
    
    include('reportErrorProcess.php');
    $smarty->assign("error_type", $error_type);
    $smarty->assign("img_server_name", $img_server_name);
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."reportError.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
?>

