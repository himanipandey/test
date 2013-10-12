<?php

if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case '1':
            $smarty->assign("error_msg", "This phase already exists!");
            break;
        case '2':
            $smarty->assign("error_msg", "Phase Config Mapping Cant be Changed. Inventory already added!");
            break;
    }
}

$projectId = $_REQUEST['projectId'];
$project = ResiProject::virtual_find($projectId);
$phaseId = $_REQUEST['phaseId'];
$preview = $_REQUEST['preview'];
$smarty->assign("preview", $preview);
$bookingStatuses = ResiProject::find_by_sql("select * from master_booking_statuses");
$smarty->assign("bookingStatuses", $bookingStatuses);
/* * *******code for delete phase********* */
if (isset($_REQUEST['delete'])) {
    $phase = ResiProjectPhase::virtual_find($phaseId);
    $resDelete = $phase->delete();
    if ($resDelete) {
        if ($preview == 'true')
            header("Location:show_project_details.php?projectId=" . $projectId);
        else
            header("Location:ProjectList.php?projectId=" . $projectId);
    }
}
/* * *******end code for delete phase***** */
$smarty->assign("phaseId", $phaseId);

$projectDetail = ResiProject::virtual_find($projectId);
$projectDetail = $projectDetail->to_custom_array();

$smarty->assign("ProjectDetail", $projectDetail);

$phaseDetail = array();
$phases = ResiProjectPhase::find("all", array("conditions" => array("project_id" => $projectId),
    "order" => "phase_name asc"));
foreach($phases as $p){
    array_push($phaseDetail, $p->to_custom_array());
}

// Project Options and Bedroom Details
if (isset($phaseId) && $phaseId != -1){
    if($phaseId != '0'){
        $phase = ResiProjectPhase::virtual_find($phaseId);//die;
        $smarty->assign("phase", $phase);
    }
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
    if(isset($phaseId)){
        $current_phase = ResiProjectPhase::virtual_find($phaseId);
        $current_phase = $current_phase->to_custom_array();
        // Assign vars for smarty
        $smarty->assign("phasename", $current_phase['PHASE_NAME']);
        $smarty->assign("launch_date", $current_phase['LAUNCH_DATE']);
        $smarty->assign("completion_date", $current_phase['COMPLETION_DATE']);
        $smarty->assign("remark", $current_phase['REMARKS']);
        $smarty->assign("phaseLaunched", $current_phase['LAUNCHED']);
        $smarty->assign("bookingStatus", $current_phase['BOOKING_STATUS_ID']);
    }


    $towers = $project->get_all_towers();
    $towerDetail = array();
    foreach($towers as $t) array_push($towerDetail, $t->to_custom_array());
    $smarty->assign("TowerDetails", $towerDetail);

//    $phase_quantity = ProjectSupply::projectTypeGroupedQuantityForPhase($projectId, $phaseId);
//    $phase_quantity_hash = array();
//    foreach($phase_quantity as $quantity) $phase_quantity_hash[$quantity->unit_type] = $quantity->edited_agg;
//    $isLaunchUnitPhase = ProjectSupply::isLaunchUnitPhase($projectId, $phaseId);
//    $isInventoryCreated = ProjectSupply::isInventoryAdded($projectId, $phaseId);
//    $smarty->assign("isInventoryCreated", $isInventoryCreated);
//    $smarty->assign("isLaunchUnitPhase", $isLaunchUnitPhase);
//    $smarty->assign("FlatsQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['apartment']));
//    $smarty->assign("VillasQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['villa']));
//    $smarty->assign("PlotQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['plot']));
}
/* * ********************************** */
if (isset($_POST['btnSave'])) {
    $phasename = $_REQUEST['phaseName'];
    $launch_date = $_REQUEST['launch_date'];
    $completion_date = $_REQUEST['completion_date'];
    $towers = $_REQUEST['towers'];  // Array
    $remark = $_REQUEST['remark'];
    $bookingStatus = $_REQUEST['bookingStatus'];
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
        header("Location:phase_edit.php?projectId=" . $projectId . "&phaseId=" . $phaseId . "&error=1");
    } else {
        // Update
        ############## Transaction ##############
        ResiProjectPhase::transaction(function(){
            global $projectId, $phaseId, $phasename, $launch_date, $completion_date, $remark, $bookingStatus, $towers;
            if($phaseId != '0'){
                //          Updating existing phase
                $phase = ResiProjectPhase::virtual_find($phaseId);
                $phase->project_id = $projectId;
                $phase->phase_name = $phasename;
                $phase->launch_date = $launch_date;
                $phase->completion_date = $completion_date;
                $phase->remarks = $remark;
                $phase->booking_status_id = $bookingStatus;
                $phase->save();

                if ($_POST['project_type_id'] == '1' || $_POST['project_type_id'] == '3' || $_POST['project_type_id'] == '6') {
                    $phase->add_towers($towers);
                }
            }
        });
        #########################################

        $towers = $project->get_all_towers();
        $towerDetail = array();
        foreach($towers as $t) array_push($towerDetail, $t->to_custom_array());
        $smarty->assign("TowerDetails", $towerDetail);

        $phaseDetail = array();
        $phases = ResiProjectPhase::find("all", array("conditions" => array("project_id" => $projectId),
            "order" => "phase_name asc"));
        foreach($phases as $p){
            array_push($phaseDetail, $p->to_custom_array());
        }
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
