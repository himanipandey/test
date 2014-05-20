<?php

error_reporting(1);
ini_set('display_errors','1');
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("modelsConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
include("function/functions_priority.php");
AdminAuthentication();

$builder_id = $_POST['builder_id'];
$projectData = array();
$arrSearchFields = array();
if( $builder_id != '' ){ 
    $arrSearchFields['builder_id'] = $builder_id;
	$getSearchResult = ResiProject::getAllSearchResult($arrSearchFields);

	foreach ($getSearchResult as $k => $v) {
		$tmpArr = array();
		$tmpArr['id'] = $v->project_id;
		$tmpArr['name'] = $v->project_name.$v->project_address;
		array_push($projectData, $tmpArr);
	}
	echo json_encode($projectData);
}
?>
