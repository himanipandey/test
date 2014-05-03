<?php

include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
require_once("common/function.php");
require_once("imageService/image_service_upload.php");

if($_POST['part']=='builderImage') {
    
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
?>

