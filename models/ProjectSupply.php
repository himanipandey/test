<?php

class ProjectSupply extends ActiveRecord\Model {
    
    static $before_save = array('launchedValidation');
//    static $after_save = array('save_total_flat_count');
    
    function deleteSupplyForPhase($projectId, $phaseId){
        self::table()->delete(array('project_id'=>$projectId, 'phase_id'=>$phaseId));
    }
    
    function addEditSupply($projectId, $phaseId, $projectType, $noOfBedroom, $supply, $launchedUnit){
        if($phaseId=='0') $phaseId = NULL;
        $attributes = array('project_id'=>$projectId, 'phase_id'=>$phaseId, 'no_of_bedroom'=>$noOfBedroom, 'project_type'=>$projectType, 'supply'=>$supply, 'launched'=>$launchedUnit, 'updated_by'=>$_SESSION['adminId']);
        $supply = self::find(array('project_id'=>$projectId, 'phase_id'=>$phaseId, 'no_of_bedroom'=>$noOfBedroom, 'project_type'=>$projectType));
        if($supply){
            $supply->update_attributes($attributes);
        }
        else{
            $supply = self::create($attributes);
        }
        $supply->save();
    }
    
    function projectTypeGroupedQuantityForPhase($projectId, $phaseId){
        $query = "select project_type UNIT_TYPE, GROUP_CONCAT(CONCAT(no_of_bedroom, ':', supply, ':', launched)) as AGG from " . self::table_name() . " where project_id = '$projectId' and phase_id ";
        if ($phaseId == '0')$query .= ' is NULL ';
        else $query .= " ='$phaseId' ";
        $query .= ' group by project_type;';
        return self::find_by_sql($query);
    }
    
    function projectSupplyForProjectPage($projectId){
        $result = array();
        $query = "select rpp.PHASE_NAME, rpp.LAUNCH_DATE, rpp.COMPLETION_DATE, ps.project_id, ps.phase_id, ps.no_of_bedroom, ps.supply, ps.edited_supply, ps.launched, ps.edited_launched, pa.availability, pa.comment, pa.effective_month, ps.project_type from " . self::table_name() . " ps inner join " . ProjectAvailability::table_name() . " pa on ps.id=pa.project_supply_id inner join (select ps.id, max(pa.effective_month) mon from " . self::table_name() . " ps inner join " . ProjectAvailability::table_name() . " pa on ps.id=pa.project_supply_id where ps.project_id = $projectId group by ps.id) t on ps.id=t.id and pa.effective_month=t.mon left join " . ResiProjectPhase::table_name() . " rpp on ps.phase_id = rpp.PHASE_ID union select rpp.PHASE_NAME, rpp.LAUNCH_DATE, rpp.COMPLETION_DATE, ps.project_id, ps.phase_id, ps.no_of_bedroom, ps.supply, ps.edited_supply, ps.launched, ps.edited_launched, pa.availability, pa.comment, pa.effective_month, ps.project_type from project_supplies ps left join project_availabilities pa on ps.id=pa.project_supply_id left join resi_project_phase rpp on ps.phase_id = rpp.PHASE_ID where pa.id is null and ps.project_id = $projectId";
        $data = self::find_by_sql($query);
        foreach ($data as $value) {
            $entry = array();
            $entry['PHASE_NAME'] = $value->phase_name;
            $entry['LAUNCH_DATE'] = $value->launch_date;
            $entry['COMPLETION_DATE'] = $value->completion_date;
            $entry['PROJECT_ID'] = $value->project_id;
            $entry['PHASE_ID'] = $value->phase_id;
            $entry['NO_OF_BEDROOMS'] = $value->no_of_bedroom;
            $entry['NO_OF_FLATS'] = $value->supply;
            $entry['EDITED_NO_OF_FLATS'] = $value->edited_supply;
            $entry['LAUNCHED'] = $value->launched;
            $entry['EDITED_LAUNCHED'] = $value->edited_launched;
            $entry['AVAILABLE_NO_FLATS'] = $value->availability; 
            $entry['EDIT_REASON'] = $value->comment;
            $entry['SUBMITTED_DATE'] = $value->effective_month;
            $entry['PROJECT_TYPE'] = $value->project_type;
            $result[] = $entry;
        }
        return $result;
    }
    
    function isLaunchUnitPhase($projectId, $phaseId){
        $sql = "select * from " . self::table_name() . " where project_id = '$projectId' and ";
        if($phaseId == '0') $sql .= " phase_id is null ";
        else $sql .= " phase_id = '$phaseId' ";
        $sql .= ' and supply > launched;';
        return count(self::find_by_sql($sql));
    }
    
    function isInventoryAdded($projectId, $phaseId){
        $sql = "select count(*) count from " . self::table_name() . " ps inner join " . ProjectAvailability::table_name() ." pa on ps.id = pa.project_supply_id where ps.project_id = '$projectId' and ";
        if($phaseId == '0') $sql .= " ps.phase_id is null ";
        else $sql .= " ps.phase_id = '$phaseId' ";
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


        $total_count = ProjectSupply::find_by_sql("select project_id, sum(supply) TOTAL_SUPPLY from project_supplies where CONCAT(project_id,'_',COALESCE(phase_id,''),'_',no_of_bedroom,'_',project_type)
                    in (".implode(",", $conditions).") group by project_id");
        $project->no_of_flats = $total_count[0]->total_supply;
        $project->save();
    }
    
    function isSupplyLaunchEdited($projectId){
        $sql = "select count(*) count from project_supplies where project_id = '$projectId' and edit_stage = 'callCenterEdit';";
        $result = self::find_by_sql($sql);
        return (intval($result[0]->count)>1);
    }
}