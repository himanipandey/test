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
if(!empty($_POST['mode']))
{
    $projectId = $_POST['id'];
    $cityId = $_POST['cityId'];
    $localityid = $_POST['localityid'];
    $suburbid = $_POST['suburbid'];
    $type = $_POST['type'];
    if($type == 'DISPLAY_ORDER_SUBURB'  && !empty($projectId)){
        updateProj($projectId, PROJECT_MAX_PRIORITY, 'suburb', $suburbid);
    }else if($type=='DISPLAY_ORDER_LOCALITY' && !empty($projectId)){
        updateProj($projectId, PROJECT_MAX_PRIORITY, 'locality', $localityid);
    }else if($type=='DISPLAY_ORDER' && !empty($projectId)){
        updateProj($projectId, PROJECT_MAX_PRIORITY, 'city', $cityId);
    }
}
else
{
    if($_POST['type'] == 'SUBURB'  && !empty($_POST['id'])){
        updateSuburb($_POST['id'],MAX_PRIORITY);
    }else if($_POST['type']=='LOCALITY' && !empty($_POST['id'])){
        updateLocality($_POST['id'],MAX_PRIORITY);
    }
}
?>