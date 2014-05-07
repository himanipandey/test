<?php

include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
include("modelsConfig.php");

if($_POST['part'] == 'builderImage') {
    $builderId = $_REQUEST['builderid'];
    $getbuilderArr = fetch_builderDetail($builderId);
    $builderImage = IMG_SERVER.'images'.$getbuilderArr['BUILDER_IMAGE'];
    echo $getbuilderArr['BUILDER_NAME'].'@@'.$builderImage;
}

if($_POST['part'] == 'builderInfo') {
    $builderId = $_REQUEST['newBuilder'];
    $getbuilderArr = fetch_builderDetail($builderId);
    echo trim($getbuilderArr['BUILDER_NAME']);
}

if($_POST['part'] == 'replace-builder') {
    $echovar =  explode(",", $_POST['builderinfo']);
    $oldBuilder = $echovar[0];    
    $newBuilder = $echovar[1];
    $resource = ResiProject::replace_builder_id($oldBuilder, $newBuilder);
    if($resource){
        $builderAlias['builder_id'] = $oldBuilder;
        $builderAlias['alias_with'] = $newBuilder;
        $builderAlias['updated_date'] = date('Y-m-d H:i:s');
        $builderAlias['updated_by'] = $_SESSION['adminId'];
        $builderAlias['table_name'] = 'builder_alias';
        BuilderAlias::insetUpdateBuilderAlias($builderAlias);
        $exeQry = ResiBuilder::updatestatusofbuilder($oldBuilder);
        if($exeQry) {
            updateBuilderIdForRevenueAssurance($oldBuilder, $newBuilder);
            echo 1;
        }
    }
}
?>

