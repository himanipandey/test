<?php
/**
 * Created by JetBrains PhpStorm.
 * User: swapnil
 * Date: 12/6/13
 * Time: 12:45 PM
 * To change this template use File | Settings | File Templates.
 */

class Project {

  private $DBPR;

  function __construct( $conns ) {
    $this->DBPR = $conns;
  }

  function getOptionDetails($param) {
    $query = "SELECT UNIT_NAME,UNIT_TYPE,SIZE,MEASURE,BEDROOMS FROM resi_project_options WHERE OPTIONS_ID = $param";
    $result = $this->DBPR->Query( $query );
    return $result;
  }

  function getProjectsAuto($term) {
    $query= "SELECT PROJECT_NAME FROM resi_project WHERE PROJECT_NAME like '$term%'";
    $result = $this->DBPR->Query( $query );
    return $result;
  }    
  function getLocalityList($cityid=-1) {
    if ( $cityid==-1 ) {
      $query = "SELECT LOCALITY_ID,LABEL FROM `locality` ORDER BY `LABEL`";
    }
    else {
      $query = "SELECT LOCALITY_ID,LABEL FROM `locality` WHERE CITY_ID = $cityid ORDER BY `LABEL` ";
    }
    $result = $this->DBPR->Query( $query );
    return $result;
  }

  function getCityList() {
    $query = "SELECT CITY_ID,LABEL FROM city ORDER BY LABEL";
    $result = $this->DBPR->Query( $query );
    return $result;
  }
  function getBuilderList() {
    $query = "SELECT DISTINCT BUILDER_NAME FROM resi_project ORDER BY BUILDER_NAME";
    $result = $this->DBPR->Query( $query );
    return $result;
  }

  function getProjectsByBuilderName($param) {
    $query = "SELECT DISTINCT PROJECT_ID FROM resi_project WHERE BUILDER_NAME = '$param'";
    $result = $this->DBPR->Query( $query );
    return $result;	
  }

  function getProjectsByCityID($param) {
    $query = "SELECT DISTINCT PROJECT_ID FROM resi_project WHERE CITY_ID = $param";
    $result = $this->DBPR->Query( $query );
    return $result;	
  }

  function getProjectsByLocalityID($param) {
    $query = "SELECT DISTINCT PROJECT_ID FROM resi_project WHERE LOCALITY_ID = $param";
    $result = $this->DBPR->Query( $query );
    return $result;	
  }

  function getBuildersByProjectID($param) { //accepts assoc array of project ids
    $query = "SELECT DISTINCT PROJECT_ID,BUILDER_NAME FROM resi_project WHERE PROJECT_ID IN (";
    foreach ($param as $key => $value) {
      $query.="'".$value."',";
    }
    $query=rtrim($query,",");
    $query.=")";
    $result = $this->DBPR->Query( $query );
    return $result;	
  }
  function getLocalitiesByProjectID($param) {
    $query = "SELECT DISTINCT PROJECT_ID,LOCALITY_ID FROM resi_project WHERE PROJECT_ID IN (";
    foreach ($param as $key => $value) {
      $query.="'".$value."',";
    }
    $query=rtrim($query,",");
    $query.=")";
    $result = $this->DBPR->Query( $query );

    $query1 = "SELECT DISTINCT LABEL,LOCALITY_ID FROM locality where LOCALITY_ID in (";
    foreach ($result as $key => $value) {
      $query1.="'".$value['LOCALITY_ID']."',";
    }
    $query1=rtrim($query1,",");
    $query1.=")";
    $res = $this->DBPR->Query( $query1);	
    foreach ($result as $key => $value) {
      foreach ($res as $key1 => $value1) {
	if($value1['LOCALITY_ID']==$value['LOCALITY_ID']){
	  $result[$key]['LOCALITY']=$value1['LABEL'];
	}
      }
    }
    return $result;		
  }
  public function getTowerInfoByProjectId( $projectId ) {
    if ( is_numeric( $projectId ) && $projectId > 0 ) {
      $query = "SELECT * FROM `resi_project_tower_details` WHERE PROJECT_ID = $projectId";
      $result = $this->DBPR->Query( $query );
      return $result;
    }
  }

  public function getAvailableProjectInfo( $projectId ) {
    if ( is_numeric( $projectId ) && $projectId > 0 ) {
      $query = "SELECT `OPTIONS_ID`, `PROJECT_ID`, `UNIT_NAME`, `UNIT_TYPE`, `SIZE`, `MEASURE` FROM `resi_project_options` WHERE PROJECT_ID = $projectId";
      $result = $this->DBPR->Query( $query );
      return $result;
    }
  }

  public function addProjectDetail( $projectId, $otherDetail ) {
    if ( $projectId > 0 ) {
      $otherDetail['PROJECT_ID'] = $projectId;
      $col = array();
      $val = array();
      foreach( $otherDetail as $__columnName => $__value ) {
	$col[] = $__columnName;
	$val[] = "'$__value'";
      }
      $col = implode( ', ', $col );
      $val = implode( ', ', $val );
      $query = "INSERT INTO `resi_project_options` ( $col ) VALUES ( $val )";
      return $this->DBPR->Insert( $query );
    }
    return -1;
  }

  public function addTowerDetail( $projectId, $otherDetail ) {
    if ( $projectId > 0 ) {
      $col = array();
      $val = array();
      foreach( $otherDetail as $__columnName => $__value ) {
	$col[] = $__columnName;
	$val[] = "'$__value'";
      }
      $col = implode( ', ', $col );
      $val = implode( ', ', $val );
      $query = "INSERT INTO `resi_project_tower_details` ( $col ) VALUES ( $val )";
      return $this->DBPR->Insert( $query );
    }
    return -1;
  }


  function getProjectBuilder($param) { // accepts string projid
    $query = "SELECT DISTINCT PROJECT_ID,BUILDER_NAME FROM resi_project WHERE PROJECT_ID = ".$param;
    $result = $this->DBPR->Query( $query );
    return $result[0]['BUILDER_NAME'];
  }

  function getProjectCompletion($param) {
    $query = "SELECT DISTINCT PROJECT_ID,COMPLETION_DATE FROM resi_project WHERE PROJECT_ID = ".$param;
    $result = $this->DBPR->Query( $query );
    return $result[0]['COMPLETION_DATE'];	
  }

  function getProjectCityLocality($param) {
    $query = "SELECT DISTINCT LOCALITY_ID,CITY_ID FROM resi_project WHERE PROJECT_ID = ".$param;
    $r = $this->DBPR->Query( $query );
    $query1 = "SELECT LABEL,LOCALITY_ID FROM locality WHERE LOCALITY_ID = ".$r[0]['LOCALITY_ID'];
    $query2 = "SELECT LABEL,CITY_ID FROM city WHERE CITY_ID = ".$r[0]['CITY_ID'];
    $r1 = $this->DBPR->Query( $query1 );
    $r2 = $this->DBPR->Query( $query2 );

    $result['CITY']=$r2[0]['LABEL'];
    $result['LOCALITY']=$r1[0]['LABEL'];

    return $result;
  }

  function getTowerDetails($param) {
    $query = "SELECT TOWER_NAME,NO_OF_FLOORS,ACTUAL_COMPLETION_DATE FROM resi_project_tower_details WHERE TOWER_ID = ".$param;
    $result = $this->DBPR->Query( $query );

    return $result;
  }

}
