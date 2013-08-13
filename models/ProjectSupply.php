<?php

class ProjectSupply extends ActiveRecord\Model {
    
    function deleteSupplyForPhase($projectId, $phaseId){
        self::table()->delete(array('project_id'=>$projectId, 'phase_id'=>$phaseId));
    }
    
    function addEditSupply($projectId, $phaseId, $projectType, $noOfBedroom, $supply, $launchedUnit){
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
        $query = "select project_type UNIT_TYPE, GROUP_CONCAT(CONCAT(no_of_bedroom, ':', supply, ':', launched)) as AGG from " . self::table_name() . " where project_id = '$projectId' and phase_id = '$phaseId' group by project_type;";
        return self::find_by_sql($query);
    }
}