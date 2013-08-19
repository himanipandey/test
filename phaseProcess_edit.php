<?php

if (isset($_GET['error'])) {
    $smarty->assign("error_msg", "This phase already exists!");
}

$projectId = $_REQUEST['projectId'];
$project = ResiProject::find($projectId);
$phaseId = $_REQUEST['phaseId'];
$preview = $_REQUEST['preview'];
$smarty->assign("preview", $preview);

/* * *******code for delete phase********* */
if (isset($_REQUEST['delete'])) {
    ProjectAvailability::deleteAvailabilityForPhase($projectId, $phaseId);
    ProjectSupply::deleteSupplyForPhase($projectId, $phaseId);
    $qryDelete = "DELETE FROM " . RESI_PROJECT_PHASE . " WHERE PHASE_ID = $phaseId";
    $resDelete = mysql_query($qryDelete);
    if ($resDelete) {
        audit_insert($phaseId, 'delete', 'resi_project_phase', $projectId);
        if ($preview == 'true')
            header("Location:show_project_details.php?projectId=" . $projectId);
        else
            header("Location:ProjectList.php?projectId=" . $projectId);
    }
}
/* * *******end code for delete phase***** */
$smarty->assign("phaseId", $phaseId);
$projectDetail = ProjectDetail($projectId);
$smarty->assign("ProjectDetail", $projectDetail);

$phaseDetail = fetch_phaseDetails($projectId);

// Project Options and Bedroom Details
$optionsDetails = ProjectOptionDetail($projectId);
$smarty->assign("OptionsDetails", $optionsDetails);
$options = $project->options;
//print_r($options);die;
$smarty->assign("options", $options);
if (isset($phaseId) && $phaseId != -1){
    $phase_options_temp = $options;
    if($phaseId != '0'){
        $phase = ResiProjectPhase::find($phaseId);//die;
        $smarty->assign("phase", $phase);
        $phase_options = $phase->options();
        if (count($phase_options) > 0){
            $phase_options_temp = $phase_options;
        }
    }
    $option_ids = array();
    foreach($phase_options_temp as $options) array_push($option_ids, $options->options_id);
    $bedrooms = ResiProjectOptions::optionwise_bedroom_details($option_ids);
    $bedrooms_hash = array();
    foreach($bedrooms as $bed) $bedrooms_hash[$bed->unit_type] = explode(",", $bed->beds);
    $smarty->assign("option_ids", $option_ids);
    $smarty->assign("phase_options", $phase_options);
    $smarty->assign("bedrooms_hash", $bedrooms_hash);
}

$phases = Array();
$old_phase_name = '';

foreach ($phaseDetail as $k => $val) {
    $p = Array();
    $p['id'] = $val['PHASE_ID'];
    $p['name'] = $val['PHASE_NAME'];
    if ($val['PHASE_ID'] == $phaseId) {
        $old_phase_name = $val['PHASE_NAME'];
    }
    array_push($phases, $p);
}
$smarty->assign("phases", $phases);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $current_phase = phaseDetailsForId($phaseId);

    // Assign vars for smarty
    $smarty->assign("phasename", $current_phase[0]['PHASE_NAME']);
    $smarty->assign("launch_date", $current_phase[0]['LAUNCH_DATE']);
    $smarty->assign("completion_date", $current_phase[0]['COMPLETION_DATE']);
    $smarty->assign("remark", $current_phase[0]['REMARKS']);
    $smarty->assign("phaseLaunched", $current_phase[0]['LAUNCHED']);


    $towerDetail = fetch_towerDetails_for_phase($projectId);
    $smarty->assign("TowerDetails", $towerDetail);

    $phase_quantity = ProjectSupply::projectTypeGroupedQuantityForPhase($projectId, $phaseId);
    $phase_quantity_hash = array();
    foreach($phase_quantity as $quantity) $phase_quantity_hash[$quantity->unit_type] = $quantity->agg;
    $isLaunchUnitPhase = ProjectSupply::isLaunchUnitPhase($projectId, $phaseId);
    $isInventoryCreated = ProjectSupply::isInventoryAdded($projectId, $phaseId);
    $smarty->assign("isInventoryCreated", $isInventoryCreated);
    $smarty->assign("isLaunchUnitPhase", $isLaunchUnitPhase);
    $smarty->assign("FlatsQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['apartment']));
    $smarty->assign("VillasQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['villa']));
    $smarty->assign("PlotQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['plot']));
}
/* * ********************************** */
if (isset($_POST['btnSave'])) {
    $phasename = $_REQUEST['phaseName'];
    $launch_date = $_REQUEST['launch_date'];
    $completion_date = $_REQUEST['completion_date'];
    $towers = $_REQUEST['towers'];  // Array
    $remark = $_REQUEST['remark'];
    if (isset($_REQUEST["phaseLaunched"])) {
        $phaseLaunched = $_REQUEST["phaseLaunched"];
    } else {
        $phaseLaunched = 0;
    }
    $phaseLaunched = $phaseLaunched;

    // Assign vars for smarty
    $smarty->assign("phasename", $phasename);
    $smarty->assign("launch_date", $launch_date);
    $smarty->assign("completion_date", $completion_date);
    $smarty->assign("remark", $remark);
    $smarty->assign("phaseLaunched", $phaseLaunched);

    $PhaseExists = searchPhase($phaseDetail, $phasename);
    if ($PhaseExists != -1 && $phasename != $old_phase_name) {
        header("Location:phase_edit.php?projectId=" . $projectId . "&phaseId=" . $phaseId . "&error=true");
    } else {
        // Flats Config
        $flats_config = array();
        foreach ($_REQUEST as $key => $value) {
            if (substr($key, 0, 9) == "flat_bed_") {
                $beds = substr($key, 9);
                $flats_config[$beds] = $value;
            }
        }

        // Villas Config
        $villas_config = array();
        foreach ($_REQUEST as $key => $value) {
            if (substr($key, 0, 10) == "villa_bed_") {
                $beds = substr($key, 10);
                $villas_config[$beds] = $value;
            }
        }

        // Update
        ############## Transaction ##############
        ResiProjectPhase::transaction(function(){
            global $projectId, $phaseId, $phasename, $launch_date, $completion_date, $remark, $phaseLaunched, $towers;
            if($phaseId != '0'){
                //          Updating existing phase
                $phase = ResiProjectPhase::find($phaseId);
                $phase->project_id = $projectId;
                $phase->phase_name = $phasename;
                $phase->launch_date = $launch_date;
                $phase->completion_date = $completion_date;
                $phase->remarks = $remark;
                $phase->launched = $phaseLaunched;
                $phase->save();

                if ($_POST['project_type_id'] == '1' || $_POST['project_type_id'] == '3' || $_POST['project_type_id'] == '6') {
                    ResiProjectTowerDetails::update_towers_for_project_and_phase($projectId, $phase->phase_id, $towers);
                }
                if(isset($_POST['options'])){
                    $arr = $_POST['options'];
                    $arr = array_diff($arr, array(-1));
                    $phase->reset_options($arr);
                }
            }
        });
        #########################################
        // Phase Quantity
        if (sizeof($flats_config) > 0) {
            foreach ($flats_config as $key => $value) {
                ProjectSupply::addEditSupply($projectId, $phaseId, 'apartment', $key, $value['supply'], $value['launched']);
            }
        }
        if (sizeof($villas_config) > 0) {
            foreach ($villas_config as $key => $value) {
                ProjectSupply::addEditSupply($projectId, $phaseId, 'villa', $key, $value['supply'], $value['launched']);
            }
        }

        if ($_POST['plotvilla'] != '') {
            $supply = $_POST['supply'];
            ProjectSupply::addEditSupply($projectId, $phaseId, 'plot', $key, $_POST['supply'], $_POST['launched']);
        }

        $towerDetail = fetch_towerDetails_for_phase($projectId);
        $smarty->assign("TowerDetails", $towerDetail);

        $phase_quantity = ProjectSupply::projectTypeGroupedQuantityForPhase($projectId, $phaseId);
        $phase_quantity_hash = array();
        foreach($phase_quantity as $quantity) $phase_quantity_hash[$quantity->unit_type] = $quantity->agg;
        $smarty->assign("FlatsQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['apartment']));
        $smarty->assign("VillasQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['villa']));
        $smarty->assign("PlotQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['plot']));

        $phaseDetail = fetch_phaseDetails($projectId);
        $phases = Array();
        foreach ($phaseDetail as $k => $val) {
            $p = Array();
            $p['id'] = $val['PHASE_ID'];
            $p['name'] = $val['PHASE_NAME'];
            array_push($phases, $p);
        }
        $smarty->assign("phases", $phases);
        $loc = "Location:phase_edit.php?projectId=$projectId";
        if($preview == 'true') $loc = $loc."&preview=true";
        header($loc);
    }
} else if ($_POST['btnExit'] == "Exit") {
    if ($preview == 'true')
        header("Location:show_project_details.php?projectId=" . $projectId);
    else
        header("Location:ProjectList.php?projectId=" . $projectId);
}
/* * *********************************** */
?>