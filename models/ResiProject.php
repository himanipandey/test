<?php

// Model integration for resi_project
require_once "support/objects.php";
class ResiProject extends Objects
{
    static $table_name = 'resi_project';
    static $default_scope = array("version" => "cms");
    static $virtual_primary_key = 'project_id';

    static $has_many = array(
        array('resi_amenities', 'class_name' => "ResiProjectAmenities", "foreign_key" => "PROJECT_ID"),
        array('audits', 'class_name' => "Audit", "foreign_key" => "PROJECT_ID"),
        array('call_projects', 'class_name' => "CallProject", "foreign_key" => "ProjectId"),
        array('options', 'class_name' => "ResiProjectOptions", "foreign_key" => "PROJECT_ID"),
        array('phases', 'class_name' => "ResiProjectPhase", "foreign_key" => "PROJECT_ID"),
   ); 
   static function projectStatusMaster() {
       $qry = "select * from project_status_master";
       $result = ResiProject::find_by_sql($qry);
       $arrStatus = array();
       foreach( $result as $value ) {
           $arrStatus[$value->id] = $value->display_name;
       }
       return $arrStatus;
   }
   static function projectAlreadyExist($txtProjectName, $builderId, $localityId) {
        $conditionsProject = array("project_name = ? and builder_id = ? 
           and locality_id = ?",$txtProjectName, $builderId,$localityId);
        $projectChk = ResiProject::virtual_find('all',
           array('conditions'=>$conditionsProject, "select" => "project_name, project_small_image"));
        return $projectChk;
   }
   static function projectUrlExist($projectURL, $projectId) {
       $conditionsProjectUrl = array("project_id != '$projectId' and project_url = '$projectURL'");
       $projectUrlChk = ResiProject::find('all',
           array('conditions'=>$conditionsProjectUrl));
        return $projectUrlChk;
   } 
   static function getAllSearchResult($arrSearchFields) {
  
       $arrSearchFieldsValue = '';
       $date = '';
       $cnt = 0;
       
       foreach($arrSearchFields as $key => $value ) {
           $cnt++;
           $and = ' in ? and ';
           $comma = ' ,';
           if( count($arrSearchFields) == $cnt ) {
               $and = '';
               $comma = '';
           }
           if( $key == 'expected_supply_date_between_from_to' ) {
               $twoDates = explode('_',$value);
               $date = "date('expected_supply_date') between '$towDate[0]' and '$towDate[1]'";
           }
           else if( $key == 'expected_supply_date_between_from' ) {
               $date = "expected_supply_date >= '$value'";
           }
           else if( $key == 'expected_supply_date_between_to' ) {
               $date = "expected_supply_date <= '$value'";
           }
           else {
               $arrSearchFields = "$key $and";
               $arrSearchFieldsValue = "($value) $comma";
           }
       }
       echo $arrSearchFields."<br>";
       echo $arrSearchFieldsValue;
      // $conditionsProject = array("project_name = ? and builder_id = ? 
         //  and locality_id = ?",$txtProjectName, $builderId,$localityId);
       $projectSearch = ResiProject::find('all',
           array('conditions'=>array($arrSearchFields,$arrSearchFieldsValue)));
       echo "<pre>";
       print_r($projectSearch);die;
   }

}
