<?php

// Model integration for resi_project
require_once "support/objects.php";
class ResiProject extends Objects
{
    static $table_name = 'resi_project';
    static $default_scope = array("version" => "Cms");
    static $virtual_primary_key = 'project_id';
    static $pre_launch_status_id = 8;

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
   
   $ands = (count($arrSearch)>0)?" and ":"";
   
   	$offer_condition = '';
   	if(empty($_GET['projectId'])){
		if(!empty($_GET['offerId'])){
			$offer_id = mysql_real_escape_string($_GET['offerId']);
			$offer_condition = " and resi_project.project_id in (select distinct(project_id) from project_offers where id = '$offer_id')";		
		}else if(!empty($_GET['withOffer'])){
			if($_GET['withOffer'] == 'Yes')
				$offer_condition = " and resi_project.project_id in (select distinct(project_id) from project_offers)";
			else if($_GET['withOffer'] == 'No')
				$offer_condition = " and resi_project.project_id not in (select distinct(project_id) from project_offers) limit 1000";
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
						$conditions." $ands resi_project.version = 'Cms'".$offer_condition;
						
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
    
    public static function delete_website_version(){
        $conn = self::connection();
        $delete_project = "delete a.* from resi_project a left join resi_project b on a.PROJECT_ID = b.PROJECT_ID and b.version = 'Cms' where a.version = 'Website' and b.PROJECT_ID is null;";
        $delete_phase = "delete a.* from resi_project_phase a left join resi_project_phase b on a.PHASE_ID = b.PHASE_ID and b.version = 'Cms' where a.version = 'Website' and b.PHASE_ID is null;";
        $delete_inventory = "delete a.* from project_availabilities a inner join project_supplies b on a.project_supply_id = b.id left join project_supplies c on b.listing_id = c.listing_id and c.version = 'Cms' left join project_availabilities d on c.id = d.project_supply_id and a.effective_month = d.effective_month where b.version = 'Website' and d.id is null;";
        $delete_supply = "delete a.* from project_supplies a left join project_supplies b on a.listing_id = b.listing_id and b.version = 'Cms' where a.version = 'Website' and b.listing_id is null;";
        $delete_price = "delete a.* from listing_prices a left join listing_prices b on a.listing_id = b.listing_id and a.effective_date = b.effective_date and b.version = 'Cms' where a.version = 'Website' and b.id is null";
        $conn->query($delete_project);
        $conn->query($delete_phase);
        $conn->query($delete_inventory);
        $conn->query($delete_supply);
        // commenting to prevent deletion of marketplace listings... should be removed once appropriate fix is done
        #$conn->query($delete_price);
    }
    
    public static function partially_migrate_projects() {
        $conn = self::connection();
        $sql = "UPDATE resi_project a inner join resi_project b
            on a.PROJECT_ID = b.PROJECT_ID and a.version = 'Website' and b.version = 'Cms'
            SET a.LATITUDE = b.LATITUDE,
            a.PROJECT_ADDRESS = b.PROJECT_ADDRESS,
            a.LONGITUDE = b.LONGITUDE,
            a.SHOULD_DISPLAY_PRICE = b.SHOULD_DISPLAY_PRICE,
            a.D_AVAILABILITY= b.D_AVAILABILITY,
            a.LOCALITY_ID  = b.LOCALITY_ID,
            a.PROJECT_URL  = b.PROJECT_URL,
            a.PROJECT_NAME  = b.PROJECT_NAME,
            a.DISPLAY_ORDER = b.DISPLAY_ORDER,
            a.DISPLAY_ORDER_LOCALITY = b.DISPLAY_ORDER_LOCALITY,
            a.DISPLAY_ORDER_SUBURB = b.DISPLAY_ORDER_SUBURB,
            a.YOUTUBE_VIDEO = b.YOUTUBE_VIDEO,
            a.SAFETY_SCORE = b.SAFETY_SCORE,
            a.LIVABILITY_SCORE = b.LIVABILITY_SCORE";
        $conn->query($sql);
    }
    
    public static function get_recent_projects_without_website_version($time_in_seconds){
        $sql = "select a.* from resi_project a left join resi_project b on a.PROJECT_ID = b.PROJECT_ID and b.version = 'Website' where a.version = 'Cms' and a.created_at > DATE_SUB(NOW(), INTERVAL $time_in_seconds SECOND) and b.PROJECT_ID is null";
        return self::find_by_sql($sql);
    }
    
    public static function get_project_updation_date($project_id){
        $sql = "select max(b.updated_at), max(c.updated_at), max(d.updated_at), max(e.updated_at), max(f.updated_at), max(g.updated_at), max(h.updated_at), max(i.updated_at) from resi_project a left join resi_project_phase b on a.PROJECT_ID = b.PROJECT_ID and b.version = 'cms' left join resi_project_options c on a.PROJECT_ID = c.PROJECT_ID left join listings d on (c.OPTIONS_ID = d.option_id and d.listing_category='Primary') left join project_supplies e on d.id = e.listing_id and e.version = 'Cms' left join project_availabilities f on e.id = f.project_supply_id left join listing_prices g on d.id = g.listing_id and g.version = 'Cms' left join table_attributes h on a.PROJECT_ID = h.table_id and h.table_name = 'resi_project' and h.attribute_name != 'D_PROJECT_UPDATION_DATE' left join project_offers i on a.PROJECT_ID = i.project_id where a.PROJECT_ID = $project_id and a.version = 'Cms'";
        $result = self::find_by_sql($sql);
        $result = $result[0];
        return substr(max(array_values($result->to_array())), 0, 10);
    }
    
    public static function set_table_attribute($project_id, $attribute_name, $attribute_value, $updated_by){
        $project = self::virtual_find($project_id);
        $project->$attribute_name = $attribute_value;
        $project->set_attr_updated_by($updated_by);
        $project->save();
    }
    
   public static function replace_builder_id($oldBuilder_id, $newBuilder_id){
        $responce =  self::update_all(array('conditions' => array('builder_id' => $oldBuilder_id,'version' => 'Cms'), 'set' => "builder_id = $newBuilder_id"));
        return $responce;
    }
}
