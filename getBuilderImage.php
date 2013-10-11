<?php

include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");

if($_POST['part']=='builderImage') {
    
    $builderId = $_REQUEST['builderid'];
    $getbuilderArr = fetch_builderDetail($builderId);
    $builderImage = IMG_SERVER.'images'.$getbuilderArr['BUILDER_IMAGE'];
    //$builderArr = array(urlencode($getbuilderArr['BUILDER_NAME']),  urlencode($builderImage));
    echo $getbuilderArr['BUILDER_NAME'].'@@'.$builderImage;
}
?>

