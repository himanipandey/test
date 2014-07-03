<?php

include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("modelsConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
require_once("common/function.php");
require_once("function/locality_functions.php");
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
    if($getbuilderArr['builder_status'] == 1 && $getbuilderArr['BUILDER_NAME'])
		echo "Inactive";
    else
		echo trim($getbuilderArr['BUILDER_NAME']);
}

if($_POST['part'] == 'replace-builder') {
	
	$flag = 1;
	$echovar =  explode(",", $_POST['builderinfo']);
	$oldBuilder = $echovar[0];    
	$newBuilder = $echovar[1];
	ResiProject::transaction(function(){
		try{	
			global $oldBuilder,$newBuilder, $flag; 				
			
			#updating project urls with new builder
			$projects = ResiProject::find("all",array("conditions"=>array("builder_id = ?",$oldBuilder)));  
			$new_builder_name = ResiBuilder::getbuildername($newBuilder);
			if($projects){
			  foreach($projects as $key => $project){
				$locCity = Locality::getLocalityCity($project->locality_id);	   
				$txtProjectURL = createProjectURL($locCity[0]->cityname, $locCity[0]->locname,  $new_builder_name[0]->builder_name, $project->project_name, $project->project_id);
				$updateQuery = "UPDATE ".RESI_PROJECT." set PROJECT_URL='".$txtProjectURL."' where PROJECT_ID='".$project->project_id."' and version = 'Cms'";
				$resUrl = mysql_query($updateQuery) or die(mysql_error());				
				#updating d_inventory_prices, d_inventory_prices & project_plan_images
			     mysql_query("update d_inventory_prices set builder_id ='".$newBuilder."', builder_name = '".$new_builder_name."' where project_id = '".$project->project_id."'") or die(mysql_error());		    
			     mysql_query("update d_inventory_prices_tmp set builder_id ='".$newBuilder."', builder_name = '".$new_builder_name."' where project_id = '".$project->project_id."'") or die(mysql_error());
			     mysql_query("update project_plan_images set builder_id ='".$newBuilder."' where project_id = '".$project->project_id."'") or die(mysql_error());
			     
			  }
			}	
			
			$builderAlias['builder_id'] = $oldBuilder;
			$builderAlias['alias_with'] = $newBuilder;
			$builderAlias['updated_date'] = date('Y-m-d H:i:s');
			$builderAlias['updated_by'] = $_SESSION['adminId'];
			$builderAlias['table_name'] = 'builder_alias';
			BuilderAlias::insetUpdateBuilderAlias($builderAlias);
			$exeQry = ResiBuilder::updatestatusofbuilder($oldBuilder);
			$oldUrl = ResiBuilder::getbuilderurl($oldBuilder);
			$newUrl = ResiBuilder::getbuilderurl($newBuilder);
			$action = insertUpdateInRedirectTbl($newUrl[0]->url, $oldUrl[0]->url);
			$resource = ResiProject::replace_builder_id($oldBuilder, $newBuilder);									
			
		}catch(Exception $e){
			$flag = 0;
		}
		
	});
	
	if($flag){
	  $_SESSION['migration_success_message'] = "Old Builder id:<b>".$oldBuilder ."</b> Is Now Replaced By New Builder id: <b>" . $newBuilder . "</b> Successfully";	
	  echo 1;
	}
	else{
	  echo 2;
	}
    
}

?>

