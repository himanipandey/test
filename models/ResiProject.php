<?php

// Model integration for bank list
class ResiProject extends ActiveRecord\Model
{
    static $table_name = 'resi_project';
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
       echo "<pre>";
       print_r($result);
       $arrStatus = array();
       foreach( $result as $value ) {
           $arrStatus[$value->id] = $value->project_status;
       }
       return $arrStatus;
   }
   static function projectAlreadyExist($txtProjectName, $builderId, $localityId, $cityId) {
        $conditionsProject = array("project_name = '$txtProjectName' and builder_id = $builderId 
           and locality_id = $localityId and city_id = $cityId");
        $projectChk = ResiProject::find('all',
           array('conditions'=>$conditionsProject, "select" => "project_name, project_small_image"));
        return $projectChk;
   }
   static function projectUrlExist($projectURL, $projectId) {
       $conditionsProjectUrl = array("project_id = $projectId and project_url = '$projectURL'");
        $projectUrlChk = ResiProject::find('all',
           array('conditions'=>$conditionsProjectUrl));
        return $projectUrlChk;
   }
   

}
