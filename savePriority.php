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
$priority   = $_POST['prio'];
$cityId     = $_POST['cityId'];
$sub        = $_POST['sub'];
$loc        = $_POST['loc']; 
if(!empty($_POST['projectId']))
{
    $projectId    = $_POST['projectId'];
    if($priority>15){
        $priority = MAX_PRIORITY;
    }
    if(!empty($sub)){
        if($_POST['autoadjust']){
            autoAdjustProjPrio($sub, $priority, 'suburb');
        }
        updateProj($projectId, $priority, 'suburb', $sub);
    }else if(!empty ($loc)){
        if($_POST['autoadjust']){
            autoAdjustProjPrio($loc, $priority, 'locality');
        }
        updateProj($projectId, $priority, 'locality', $loc);
    }else{
        if($_POST['autoadjust']){
            autoAdjustProjPrio($cityId, $priority, 'city');
        }
        updateProj($projectId, $priority, 'city', $cityId);
    }
}
else
{
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
    }
}
?>