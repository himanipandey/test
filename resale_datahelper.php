<?php

include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
AdminAuthentication();

require_once("includes/class_supply.php");
require_once("includes/class_project.php");
require_once("common/start.php");

$projObj= new Project($db_project);
$supObj = new Supply($db_project);


if(isset($_POST['action']) && !empty($_POST['action'])) {
  $action = $_POST['action'];
  switch($action) {
  case 'autocomplete' : autocomplete($_POST);break;
  case 'search' : search($_POST);break;
  case 'locality' : locality($_POST);break;
  case 'update_listing' : $r=$supObj->updateRecord($_POST);	header("Location: ".CRM_BASE_PATH."resale_display.php"); die();break;
  case 'changestatus' : $r=$supObj->updateRecord($_POST);	header("Location: ".CRM_BASE_PATH."resale_display.php"); die();break;
  }
}

function autocomplete($projterm){
  global $projObj;

  $result=$projObj->getProjectsAuto($projterm['term']);
  $res['result']=$result;
  
  $result=json_encode($res);
  error_log($result);
  // header('Content-type: application/json');
  echo $result;
}

function search($params){
  $projectsbycitylocality=array();
  $projectsbybuilder=array();
  global $projObj;
  global $supObj;

  $projects[0]=array('PROJECT_ID'=>'-1');

  if($params['LOCALITY_ID']==-1){
    if($params['CITY_ID']!=-1)
      $projectsbycitylocality = $projObj->getProjectsByCityID($params['CITY_ID']);
  }
  else{
    $projectsbycitylocality=$projObj->getProjectsByLocalityID($params['LOCALITY_ID']);
  }

  if($params['BUILDER_NAME']!=-1 && $params['BUILDER_NAME']!='-1')
    $projectsbybuilder=$projObj->getProjectsByBuilderName($params['BUILDER_NAME']);
  
  if(!empty($projectsbybuilder) && !empty($projectsbycitylocality)){
    $projects=array();
    foreach ($projectsbycitylocality as $key => $value) {
      foreach ($projectsbybuilder as $key1 => $value1) {
	if($value1['PROJECT_ID']==$value['PROJECT_ID']) {
	  $projects[]=$value['PROJECT_ID'];
	  break;
	}					
      }
    }
  }
  elseif (!empty($projectsbybuilder) && empty($projectsbycitylocality)) {
    $projects=array();
    foreach ($projectsbybuilder as $key1 => $value1) {			
      $projects[]=$value1['PROJECT_ID'];
    }
  }
  elseif (empty($projectsbybuilder) && !empty($projectsbycitylocality)) {
    $projects=array();
    foreach ($projectsbycitylocality as $key1 => $value1) {			
      $projects[]=$value1['PROJECT_ID'];
    }
  }

  if(!empty($projects))
    $results=$supObj->getSearchResultListings($projects,$params);
  else
    $results=array();

  $resproj=array();
  if(!empty($results)){
    foreach ($results as $key => $value) {
      $resproj[$key]=$value['PROJECT_ID'];
    }
  }

  $resproj = array_unique($resproj, SORT_REGULAR);
  if(!empty($resproj)){
    $builders = $projObj->getBuildersByProjectID($resproj);
    $localities = $projObj->getLocalitiesByProjectID($resproj);
    if(!empty($builders)) {
      foreach ($results as $key => $value) {
	foreach ($builders as $key1 => $value1) {
	  if($value1['PROJECT_ID']==$value['PROJECT_ID']){
	    $results[$key]['BUILDER_NAME']=$value1['BUILDER_NAME'];
	  }
	}
      }
    }
    else {
      foreach ($results as $key => $value) {
	$value['BUILDER_NAME']='NA';
      }
    }
    if(!is_null($localities[0])) {
      foreach ($results as $key => $value) {
	foreach ($localities as $key1 => $value1) {
	  // print_r($value1);
	  // echo "<br>";echo "<br>";
	  // print_r($value);
	  // echo "<br>";echo "<br>";
	  if($value1['PROJECT_ID']==$value['PROJECT_ID']){
	    $results[$key]['LOCALITY']=$value1['LOCALITY'];
	  }
	}
      }
    }
    else {
      foreach ($results as $key => $value) {
	$value['LOCALITY']='NA';
      }
    }

  }

  //Adding property type and checking area/bedroom validity
  foreach ($results as $key => $value) {
    $re;
    if(!empty($value['PROPERTY_OPTION_ID'])){
      $re=$projObj->getOptionDetails($value['PROPERTY_OPTION_ID']);
      if(empty($re[0])){
	unset($results[$key]);
	continue;
      }
      
      $str=$re[0]['UNIT_NAME']." (".$re[0]['SIZE']." ".$re[0]['MEASURE'].")";

      if(intval($params['MIN_AREA'])!=-1 && intval($params['MIN_AREA'])!='-1'){
	if(!(intval($params['MIN_AREA'])<=intval($re[0]['SIZE']))) {
	  unset($results[$key]);
	  continue;
	}
      }
      if(intval($params['MAX_AREA'])!=-1 && intval($params['MAX_AREA'])!=-1){
	if(!(intval($params['MAX_AREA'])>=intval($re[0]['SIZE']))) {
	  unset($results[$key]);
	  continue;
	}
      }
      if($params['BEDROOM_COUNT']!=-1 && $params['BEDROOM_COUNT']!='-1') {
	if($params['BEDROOM_COUNT']!=$re[0]['BEDROOMS']) {
	  unset($results[$key]);
	  continue;
	}
      }

      $results[$key]['AVAILABLE_PROPERTY']=$str;
    }
    else {
      unset($results[$key]);
    }
  }

  //CHECKING COST and FLOOR VALIDITY
  foreach ($results as $key => $value) {
    $serachfloor = $params['FLOOR'];
    $unitfloor = $value['FLOOR_NO'];
    if(intval($params['MIN_COST'])!=-1){
      if(intval($params['MIN_COST'])>intval($results[$key]['INDICATIVE_PRICE'])) {
	unset($results[$key]);
	continue;
      }
    }
    if(intval($params['MAX_COST'])!=-1){
      if(intval($params['MAX_COST'])<intval($results[$key]['INDICATIVE_PRICE'])) {
	unset($results[$key]);
	continue;
      }
    }
    if(intval($params['FLOOR'])!=-1){
      if(intval($params['FLOOR'])!=21) {
	if(intval($params['FLOOR'])<intval($results[$key]['FLOOR_NO'])) {
	  unset($results[$key]);
	  continue;
	}
      }
      elseif (intval($params['FLOOR'])==21) {
	if(intval($results[$key]['FLOOR_NO'])<20) {
	  unset($results[$key]);
	  continue;
	}
      }
    }
  }

  foreach ($results as $key => $value) {
    if($value['ADDED_BY']!='') {
      $admin=$supObj->getUsers($value['ADDED_BY']);
      $results[$key]['ADDED_BY_NAME']=$admin[0]['USERNAME'];
    }
    else
      $results[$key]['ADDED_BY_NAME']="Not Available";
  }

  $res['result']=$results;
  $r=json_encode($res);
  echo $r;
}

function locality($params){
  global $projObj;
  if($params['CITY_ID']!='-1')
    $localities=$projObj->getLocalityList($params['CITY_ID']);

  $result='<option value=-1>Choose...</option>';
  foreach ($localities as $row => $data) {
    $result.= '<option value="'.$data['LOCALITY_ID'].'"'. '>'.$data['LABEL'].'</option>';
  }
  echo $result;
}
?>
