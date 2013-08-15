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
$cityId     = $_POST['cityId'];
$priority   = $_POST['prio'];
$sub        = $_POST['sub'];
$loc        = $_POST['loc'];
if($_POST['autoadjust'])
{
    autoAdjustPrio(SUBURB, $cityId, $priority);
    autoAdjustPrio(LOCALITY, $cityId, $priority);
}
if($priority>=100){
    $priority = MAX_PRIORITY;
}
if(!empty($sub)){
    updateSuburb($sub, $priority);
}else if(!empty ($loc)){
    updateLocality($loc, $priority);
}else{

}
?>