<?php

class ProjectSupply extends ActiveRecord\Model {
    
    static $before_save = array('launchedValidation');
    
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
        $query = "select rpp.PHASE_NAME, rpp.LAUNCH_DATE, rpp.COMPLETION_DATE, ps.project_id, ps.phase_id, ps.no_of_bedroom, ps.supply, ps.launched, pa.availability, pa.comment, pa.effective_month, ps.project_type from " . self::table_name() . " ps inner join " . ProjectAvailability::table_name() . " pa on ps.id=pa.project_supply_id inner join (select max(pa.id) id from " . self::table_name() . " ps inner join " . ProjectAvailability::table_name() . " pa on ps.id=pa.project_supply_id where ps.project_id = $projectId group by ps.id) t on pa.id=t.id left join " . ResiProjectPhase::table_name() . " rpp on ps.phase_id = rpp.PHASE_ID";
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
            $entry['LAUNCHED'] = $value->launched;
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
}