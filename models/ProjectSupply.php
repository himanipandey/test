<?php

class ProjectSupply extends ActiveRecord\Model {
    
    function deleteSupplyForPhase($projectId, $phaseId){
        self::table()->delete(array('project_id'=>$projectId, 'phase_id'=>$phaseId));
    }
    
    function bedSupplyDetail($projectId, $bedId, $project_type, $phaseId = '') {
    $sql = "SELECT *
					FROM " . RESI_PROJ_SUPPLY . "
				WHERE
					PROJECT_ID ='" . $projectId . "'
				AND
					NO_OF_BEDROOMS	=	'" . $bedId . "'
				AND
					PROJECT_TYPE    =  '" . $project_type . "'";
    if ($phaseId != '')
        $sql .= " AND PHASE_ID='" . $phaseId . "' ";
    $sql .=" ORDER BY PROJ_SUPPLY_ID DESC LIMIT 1";

    $data = mysql_query($sql) or die(mysql_error());
    $arr = array();
    while ($dataarr = mysql_fetch_assoc($data)) {
        $arr[] = $dataarr;
    }
    return $arr;
}

    function test(){
        $sql = "select * from project_supplies";
        return self::connection()->query($sql);
    }
    
    function setPhaseQuantity($phaseId, $projectType, $bedrooms, $quantity, $projectId) {
        $quantity = empty($quantity)? 0 : $quantity;
        
        $count = self::count(array('project_id' => $projectId, 'phase_id' => $phaseId, 'project_type' => $projectType, 'no_of_bedroom' => $bedrooms));
        echo $count;die;
        if ($count > 0) {
                if ($quantity == '')
                    $quantity = 0;
                $ins = "UPDATE resi_proj_supply SET NO_OF_FLATS='" . $quantity . "' WHERE PROJECT_ID='" . $projectId . "' AND PHASE_ID='" . $phaseId . "' AND NO_OF_BEDROOMS='" . $bedrooms . "' AND PROJECT_TYPE='" . $unit_type . "' ORDER BY PROJ_SUPPLY_ID DESC LIMIT 1";
                self::save();
                mysql_query($ins);

                $returnAvailability = computeAvailability($projectId);
                if ($returnAvailability) {
                    $updateProject = updateAvailability($projectId, $returnAvailability);
                }
                audit_insert($projectId, 'update', 'resi_project', $projectId);
        }else {
            $ins = "INSERT INTO project_supplies (project_id, phase_id, no_of_bedrooms, project_type, supply, launched, updated_by, created_at) VALUES ('" . $projectId . "','" . $phaseId . "','" . $bedrooms . "','" . $projectType . "','" . $quantity . "','" . $quantity . "','" . $_SESSION['USER_ID'] . "', NOW())";
            self::connection()->query($ins);
        }
    }

}