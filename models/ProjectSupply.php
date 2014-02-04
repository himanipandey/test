<?php

require_once "support/objects.php";

class ProjectSupply extends Objects {

    static $before_save = array('launchedValidation');
    static $default_scope = array("version" => "Cms");
    static $virtual_primary_key = 'id';
    
//    static $after_save = array('save_total_flat_count');
    
    function deleteSupplyForPhase($projectId, $phaseId){
        self::table()->delete(array('project_id'=>$projectId, 'phase_id'=>$phaseId));
    }
    
    function addEditSupply($projectId, $phaseId, $projectType, $noOfBedroom, $supply, $launchedUnit) {
        if($phaseId=='0') $phaseId = NULL;
        $supply_new = self::find("all", array("joins" => "join listings l on (l.id = project_supplies.listing_id and l.phase_id = $phaseId) join resi_project_options o on (l.option_id = o.options_id and " . ($noOfBedroom == null ? "(o.bedrooms is null OR o.bedrooms = 0)" : "o.bedrooms = $noOfBedroom") . " and o.option_type='$projectType')"));
        if($supply_new) {
            $supply_new = $supply_new[0];
            $attributes['updated_by']=$_SESSION['adminId'];
            if($supply_new->supply != $supply || $supply_new->launched != $launchedUnit)
				$supply_new->is_verified = 'false';
				
            $attributes['supply']=$supply;
            $attributes['launched']=$launchedUnit;
            $supply_new->update_attributes($attributes);
        }
        else{
            $options = ResiProjectOptions::find("all", array("conditions" => array('project_id' => $projectId, 'option_category' => 'Logical', 'bedrooms' => $noOfBedroom, 'option_type' => $projectType)));
            $option = null;
            if (empty($options)) {
                $option = new ResiProjectOptions();
                $option->project_id = $projectId;
                $option->option_category = 'Logical';
                $option->option_type = $projectType;
                $option->bedrooms = $noOfBedroom;
                $option->updated_at = date('Y-m-d H:i:s');
                $option->updated_by = $_SESSION['adminId'];
                $option->created_at = date('Y-m-d H:i:s');
                $option->save();
                $options = array($option);
            }

            $listings = Listings::find('all', array('conditions' => array('phase_id' => $phaseId, 'option_id' => $options[0]->options_id, 'listing_category' => 'Primary')));
            if (empty($listings)) {
                $listing = new Listings();
                $listing->phase_id = $phaseId;
                $listing->option_id = $options[0]->options_id;
                $listing->listing_category = 'Primary';
                $listing->status = 'Active';
                $listing->updated_at = date('Y-m-d H:i:s');
                $listing->updated_by = $_SESSION['adminId'];
                $listing->created_at = date('Y-m-d H:i:s');
                $listing->save();
                $listings = array($listing);
            }

            $attributes = array('listing_id'=>$listings[0]->id, 'supply'=>$supply, 'launched'=>$launchedUnit, 'updated_by'=>$_SESSION['adminId'], 'updated_at'=>date('Y-m-d H:i:s'), 'created_at' => date('Y-m-d H:i:s'));
            $attributes['version'] = 'Cms';
            $supply_new = self::create($attributes);
            $supply_new->save();
            $attributes['version'] = 'Website';
            $supply_new = self::create($attributes);
            $supply_new->save();
        }
    }
    
    function projectTypeGroupedQuantityForPhase($projectId, $phaseId) {
        $query = "select option_type UNIT_TYPE, GROUP_CONCAT(CONCAT(IFNULL( bedrooms, 0 ), ':', supply,
            ':', launched)) as AGG from " . self::table_name() . " 
               s join listings l on (l.id = s.listing_id  
               and l.listing_category = 'Primary' and l.status = 'Active') join resi_project_options o on 
               (o.options_id = l.option_id) where version = 'Cms' and project_id = '$projectId' and phase_id";
        if ($phaseId == '0')$query .= ' is NULL ';
        else $query .= " ='$phaseId' ";
        $query .= ' group by UNIT_TYPE;';
        return self::find_by_sql($query);
    }
    
    function projectSupplyForProjectPage($projectId){
        $result = array();
        $query = "select rpp.PHASE_NAME, rpp.LAUNCH_DATE, rpp.COMPLETION_DATE, rpp.submitted_date, rpp.project_id,  rpp.BOOKING_STATUS_ID,
            ls.phase_id, rpo.bedrooms as no_of_bedroom, ps.supply, ps.launched, 
            pa.availability, pa.comment, pa.effective_month, rpo.option_type as project_type,ls.id as listing_id
            from 
             " . self::table_name() . " ps 
             inner join " . ProjectAvailability::table_name() . " pa on (ps.id=pa.project_supply_id and ps.version = 'Cms')
             inner join listings ls on ps.listing_id = ls.id
             inner join resi_project_options rpo on rpo.options_id = ls.option_id    
             inner join 
                (select ps.id, max(pa.effective_month) mon 
                    from " . self::table_name() . " ps inner join " . ProjectAvailability::table_name() . " pa 
                    on ps.id=pa.project_supply_id
                    inner join listings ls on (ps.listing_id = ls.id and ls.listing_category = 'Primary' and ls.status = 'Active')  
                    left join " . ResiProjectPhase::table_name() . " rpp on ls.phase_id = rpp.PHASE_ID 
                    where rpp.project_id = $projectId and rpp.version = 'Cms' and ps.version = 'Cms' and rpp.status = 'Active' group by ps.id
                 ) t 
                on ps.id=t.id and pa.effective_month=t.mon 
             left join " . ResiProjectPhase::table_name() . "  rpp on (ls.phase_id = rpp.PHASE_ID and rpp.version = 'Cms')
       union 
            select rpp.PHASE_NAME, rpp.LAUNCH_DATE, 
                rpp.COMPLETION_DATE, rpp.submitted_date, rpp.project_id,rpp.BOOKING_STATUS_ID, ls.phase_id, rpo.bedrooms as no_of_bedroom, ps.supply,
                ps.launched, pa.availability, pa.comment, pa.effective_month, 
                rpo.option_type as project_type,ls.id as listing_id 
            from 
                project_supplies ps left join project_availabilities pa on (ps.id=pa.project_supply_id and ps.version = 'Cms')
            inner join listings ls on (ps.listing_id = ls.id  and ls.listing_category = 'Primary' and ls.status = 'Active')          
            left join resi_project_phase rpp on (ls.phase_id = rpp.PHASE_ID)
            inner join resi_project_options rpo on rpo.options_id = ls.option_id
          where pa.id is null and ps.version = 'Cms' and rpp.project_id = $projectId and rpp.version = 'Cms' and rpp.status = 'Active'";
        $data = self::find_by_sql($query);
        foreach ($data as $value) {
            $entry = array();
            $entry['PHASE_NAME'] = $value->phase_name;
            $entry['LAUNCH_DATE'] = $value->launch_date;
            $entry['COMPLETION_DATE'] = $value->completion_date;
            $entry['submitted_date'] = $value->submitted_date;
            $entry['PROJECT_ID'] = $value->project_id;
            $entry['PHASE_ID'] = $value->phase_id;
            $entry['NO_OF_BEDROOMS'] = $value->no_of_bedroom;
            $entry['NO_OF_FLATS'] = $value->supply;
            $entry['LAUNCHED'] = $value->launched;
            $entry['AVAILABLE_NO_FLATS'] = $value->availability; 
            $entry['EDIT_REASON'] = $value->comment;
            $entry['SUBMITTED_DATE'] = $value->effective_month;
            $entry['PROJECT_TYPE'] = $value->project_type;
            $entry['BOOKING_STATUS_ID'] = $value->booking_status_id;
            $entry['LISTING_ID'] = $value->listing_id;
            $result[] = $entry;
        }
        return $result;
    }
    
    function isLaunchUnitPhase($phaseId){
        $sql = "select * from " . self::table_name() . " s join listings l on (l.id = s.listing_id) where version = 'Cms' and ";
        if($phaseId == '0') $sql .= " phase_id is null ";
        else $sql .= " phase_id = '$phaseId' ";
        $sql .= ' and supply > launched;';
        return count(self::find_by_sql($sql));
    }
    
    function isInventoryAdded($phaseId){
        $sql = "select count(*) count from " . self::table_name() . " ps inner join " . ProjectAvailability::table_name() ." pa on ps.id = pa.project_supply_id join listings l on (l.id = ps.listing_id) where ps.version = 'Cms' and ";
        if($phaseId == '0' || $phaseId == NULL) $sql .= " phase_id is null ";
        else $sql .= " phase_id = '$phaseId' ";
        $result = self::find_by_sql($sql);
        return $result[0]->count;
    }
    
    function launchedValidation(){
        return intval($this->launched)<=intval($this->supply);
    }

    
    function save_total_flat_count($project_id = NULL){
        if($project_id == NULL) $project_id = $this->project_id;
        $project = ResiProject::find($project_id);
        $phases = ResiProjectPhase::all(array('conditions' => 'project_id = '.$project->id));
        $project_options = ResiProjectOptions::all(array('conditions' => 'project_id = '.$project->id));
        $conditions = array();
        if(count($phases) == 0){
            foreach($project_options as $option){
                $set = "'".$project->project_id."__".$option->bedrooms."_".$option->unit_type."'";
                array_push($conditions, $set);
            }
        }
        else{
            foreach($phases as $phase){
                $options = $phase->options();
                if (count($options) == 0) $options = $project_options;
                foreach($options as $option){
                    $set = "'".$project->project_id."_".$phase->phase_id."_".$option->bedrooms."_".$option->unit_type."'";
                    array_push($conditions, $set);
                }
            }
        }


        $total_count = ProjectSupply::find_by_sql("select project_id, sum(supply) TOTAL_SUPPLY 
            from project_supplies where version = 'Cms' and CONCAT(project_id,'_',COALESCE(phase_id,''),'_',no_of_bedroom,'_',project_type)
                    in (".implode(",", $conditions).") group by project_id");
        $project->no_of_flats = $total_count[0]->total_supply;
        $project->save();
    }
    function isSupplyLaunchVerified($projectId){
		
		$sql = "select l.id from project_supplies ps inner join listings l
                on ps.listing_id = l.id
                inner join resi_project_options rpo on l.option_id = rpo.options_id
                where 
                rpo.project_id = '$projectId' and l.status = 'Active' group by l.id having count(distinct supply) >1 or count(distinct launched) > 1";
         $result = self::find_by_sql($sql);
         
          if(count($result)>0)
			return FALSE;
		  else
			return TRUE;
		
	}
    function isVerifiedFlagCheck($projectId){
		
		$sql = "select rpp.phase_id,ps.* from project_supplies ps
				inner join listings lst on lst.id = ps.listing_id
				inner join resi_project_phase rpp on rpp.phase_id = lst.phase_id
				where rpp.project_id = $projectId 
				and lst.status = 'Active' and rpp.status = 'Active'
				and rpp.phase_type = 'Actual' and ps.version = 'Cms'
				and is_verified = 'false'";
         $result = self::find_by_sql($sql);
         
          if(count($result)>0)
			return 1;
		  else
			return 0;
		
	}
	
	function checkAvailability($projectId, $phaseId, $projectType, $noOfBedroom, $supply, $launchedUnit) {
        if($phaseId=='0') $phaseId = NULL;
        $supply_new = self::find("all", array("joins" => "join listings l on (l.id = project_supplies.listing_id and l.phase_id = $phaseId) join resi_project_options o on (l.option_id = o.options_id and " . ($noOfBedroom == null ? "(o.bedrooms is null OR o.bedrooms = 0)" : "o.bedrooms = $noOfBedroom") . " and o.option_type='$projectType')"));
        if($supply_new) {
			$supplyId = $supply_new[0]->id;
                        $supplyId = $supply_new[0]->id;
                        $availability = ProjectAvailability::getAvailability($supplyId);
			if($availability > $launchedUnit && $availability>0 && $supply >0 && $launchedUnit>0){
                            return false;
			}else{
				return true;
                        }
		}
                else
                    return true;
		
	}
}
