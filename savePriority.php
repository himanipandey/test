<?php
error_reporting(1);
ini_set('display_errors','1');
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
include("function/functions_priority.php");
AdminAuthentication();
if($_POST['autoadjust'])
{
    autoAdjustPrio(SUBURB, $_POST['cityId'], $_POST['prio']);
    autoAdjustPrio(LOCALITY, $_POST['cityId'], $_POST['prio']);
}

if(!empty($_POST['sub'])){
    updateSuburb($_POST['sub'],$_POST['prio']);
}else if(!empty ($_POST['loc'])){
    updateLocality($_POST['loc'],$_POST['prio']);
}else{

}
?>