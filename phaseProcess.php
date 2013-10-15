
<?php

$projectId = $_REQUEST['projectId'];
$project = ResiProject::virtual_find($projectId);
$options = $project->options;
$smarty->assign("options", $options);
$projectDetail = array($project->to_custom_array());
$smarty->assign("ProjectDetail", $projectDetail);
if (isset($_GET['error'])) {
    $smarty->assign("error_msg", "This phase already exists!");
}
$phaseDetail = array();
$phases = ResiProjectPhase::find("all", array("conditions" => array("project_id" => $projectId),
    "order" => "phase_name asc"));
foreach($phases as $p){
    array_push($phaseDetail, $p->to_custom_array());
}
$towers = $project->get_all_towers();
$towerDetail = array();
foreach($towers as $t) array_push($towerDetail, $t->to_custom_array());
$smarty->assign("TowerDetails", $towerDetail);

/* * ********************************** */
if (isset($_POST['btnSave']) || isset($_POST['btnAddMore'])) {
    // Vars
    $phasename = $_REQUEST['PhaseName'];
    $launch_date = $_REQUEST['launch_date'];
    $completion_date = $_REQUEST['completion_date'];
    $towers = $_REQUEST['towers'];  // Array
    $remark = $_REQUEST['remark'];
    $bookingStatus = $_REQUEST['bookingStatus'];
    $PhaseExists = searchPhase($phaseDetail, $phasename);
    if ($PhaseExists != -1) {
        header("Location:phase.php?projectId=" . $projectId . "&error=true");
    } else {
        ############## Transaction ##############
        ResiProjectPhase::transaction(function(){
            global $projectId, $phasename, $launch_date, $completion_date, $remark, $towers, $bookingStatus;

//          Creating a new phase
            $phase = new ResiProjectPhase();
            $phase->project_id = $projectId;
            $phase->phase_name = $phasename;
            $phase->launch_date = $launch_date;
            $phase->completion_date = $completion_date;
            $phase->remarks = $remark;
            $phase->booking_status_id = $bookingStatus;
            $phase->updated_by = $_SESSION["adminId"];
            $phase->virtual_save();

            if ($_POST['project_type_id'] == '1' || $_POST['project_type_id'] == '3' || $_POST['project_type_id'] == '6') {
                $phase->add_towers($towers);
            }
        });
        #########################################
        if (isset($_POST['btnSave']))
            header("Location:ProjectList.php?projectId=" . $projectId);
        else if (isset($_POST['btnAddMore']))
            header("Location:phase.php?projectId=" . $projectId);
    }
}
else if ($_POST['btnExit'] == "Exit") {
    header("Location:ProjectList.php?projectId=" . $projectId);
}
/* * *********************************** */
?>
