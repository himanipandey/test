<?php

include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("modelsConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
require_once("common/function.php");
require_once("imageService/image_service_upload.php");

if($_POST['part'] == 'builderImage') {
    $builderId = $_REQUEST['builderid'];
    $getbuilderArr = fetch_builderDetail($builderId);

    $url = readFromImageService("builder", $builderId);

	    //echo $url;
	    $content = file_get_contents($url);
	    $imgPath = json_decode($content);
	    
	  
	    foreach($imgPath->data as $k1=>$v1){
				$builderImage = $v1->absolutePath;
		}
    //$builderImage = IMG_SERVER.'images'.$getbuilderArr['BUILDER_IMAGE'];
    //$builderArr = array(urlencode($getbuilderArr['BUILDER_NAME']),  urlencode($builderImage));
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
			$oldUrl = ResiBuilder::getbuilderurl($oldBuilder);
			$newUrl = ResiBuilder::getbuilderurl($newBuilder);
			$action = insertUpdateInRedirectTbl($newUrl[0]->url, $oldUrl[0]->url);
            echo 1;
        }
    }
}
?>

