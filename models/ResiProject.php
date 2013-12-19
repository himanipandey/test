<?php

// Model integration for resi_project
require_once "support/objects.php";
class ResiProject extends Objects
{
    static $table_name = 'resi_project';
    static $default_scope = array("version" => "Cms");
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
   static function projectAlreadyExist($txtProjectName, $builderId, $localityId, $projectId='') {
	if($projectId == '')
	 $conditionsProject = array("project_name = ? and builder_id = ? 
           and locality_id = ?",$txtProjectName, $builderId,$localityId);
	else
	$conditionsProject = array("project_name = ? and builder_id = ? 
           and locality_id = ? and project_id != ?" ,$txtProjectName, $builderId,$localityId,$projectId);
        
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
   static function getAllSearchResult($arrSearch) {
       $arrSearchFields = '';
       $arrSearchFieldsValue = array();
       $date = '';
       $cnt = 0;
       $conditions = '';
	if(count($arrSearch) > 1) 
		$city_and = ' and ';
	else
		$city_and = '';
    foreach($arrSearch as $key => $value ) {
      
      $cnt++;
      $and = ' and ';
	  $comma = ' ,';
	  $proj_nam_and = ' and ';
	  
      if( count($arrSearch) == $cnt ) {
          $comma = '';
          $and = ' ';
          $proj_nam_and = ' ';
      }
      
      if( count($arrSearch) < $cnt ){
          $and = '';
          $proj_nam_and = '';
      
      }
          
	  if( $key == 'city_id' ){
		$conditions .=  "city.city_id in ($value) ".$city_and;
   	  }
      else if( $key == 'expected_supply_date_between_from_to' ) {
          $twoDate = explode('_',$value);
          $conditions .= "expected_supply_date >= '".$twoDate[0]."' and ";
          $conditions .= "expected_supply_date <= '".$twoDate[1]."' ";
      }
      else if( $key == 'expected_supply_date_from' ) {
          $conditions .= "expected_supply_date >= '$value'";
	  }
      else if( $key == 'expected_supply_date_to' ) {
           $conditions .= "expected_supply_date <= '$value'";
      }
     elseif( $key == 'project_name' ){

		   $conditions .= "resi_project.$key  like '$value%' $proj_nam_and";          
	 }
	 elseif($key == 'D_AVAILABILITY'){
		
		 if(is_array($value))
		   $value = implode("','",$value);
		   
		 $conditions .= "($value) $and";
	
	 }
	 else {
		 if(is_array($value))
		   $value = implode("','",$value);
		 $conditions .= "resi_project.$key in ('$value') $and";

      }
    }
	
	 $query = "SELECT resi_project.*, rpp.PHASE_ID as no_phase_id, b.builder_name,phases.name as phase_name,stages.name as stage_name FROM `resi_project` 
                inner join resi_project_phase rpp
                    on resi_project.PROJECT_ID = rpp.PROJECT_ID and rpp.PHASE_TYPE = 'Logical' and rpp.version = 'Cms' 
				left join resi_builder b 
					on resi_project.builder_id = b.builder_id
                 left join master_project_phases phases 
                    on resi_project.project_phase_id = phases.id
                 left join master_project_stages stages
                    on resi_project.project_stage_id = stages.id
                 left join locality
                    on resi_project.locality_id = locality.locality_id
                 left join suburb 
                    on locality.suburb_id = suburb.suburb_id
                 left join city
                    on suburb.city_id = city.city_id WHERE ".
						$conditions." and resi_project.version = 'Cms'";
						
       $projectSearch = ResiProject::find_by_sql($query);  
                  	
       return $projectSearch;
   }

   public function get_all_options(){
       return ResiProjectOptions::find("all", array("conditions" => array("project_id" => $this->project_id, "option_category" => "Actual")));
   }
   
   public static function get_all_phases($project_id){
       return ResiProjectPhase::find("all", array("conditions" => array("project_id" => $project_id, "version" => "Cms")));
   }

       public function get_all_towers(){
           return ResiProjectTowerDetails::all(array("conditions" => array("project_id = ?", $this->project_id)));
       }
     
//     public function get_all_towers(){
//         $phase_ids = array();
//         $phases = ResiProjectPhase::find("all", array("conditions" => array("project_id" => $this->project_id)));
//         foreach($phases as $phase) array_push($phase_ids, $phase->phase_id);
//         return ResiProjectPhase::get_towers_for_phases($phase_ids);
//     }
}
