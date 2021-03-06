<?php

// Model integration for bank list
require_once dirname(__FILE__)."/support/objects.php";
class ResiProjectTowerDetails extends Objects
{
    static $table_name = 'resi_project_tower_details';
    function update_towers_for_project_and_phase($projectId, $phaseId, $tower_array){

        $tower_ids = join(',',$tower_array);
        if($tower_ids) {
            $qry_ins	=	"
				UPDATE ". self::$table_name."
				SET
					PHASE_ID	=	COALESCE(
										CASE WHEN TOWER_ID IN (".$tower_ids.") THEN ".$phaseId." ELSE NULL END,
										CASE WHEN PHASE_ID=".$phaseId." THEN NULL ELSE PHASE_ID END
									)
				WHERE
					PROJECT_ID	= '".$projectId."'";
        }
        else {
            $qry_ins	=	"
				UPDATE ". self::$table_name."
				SET
					PHASE_ID	=	COALESCE(
										CASE WHEN PHASE_ID=".$phaseId." THEN NULL ELSE PHASE_ID END
									)
				WHERE
					PROJECT_ID	= '".$projectId."'";
        }
        return ResiProjectTowerDetails::query($qry_ins);
    }
    
    static function getFloorCountForAllProjects(){
        return self::find('all', array('group'=>'PROJECT_ID', 'having'=>'FLOOR_COUNT > 0', 'select'=>'PROJECT_ID, sum(NO_OF_FLOORS) FLOOR_COUNT'));
    }
}