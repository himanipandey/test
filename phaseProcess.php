
<?php

$projectId = $_REQUEST['projectId'];
$project = ResiProject::find($projectId);
$options = $project->options;
$smarty->assign("options", $options);
$projectDetail = ProjectDetail($projectId);
$smarty->assign("ProjectDetail", $projectDetail);
if (isset($_GET['error'])) {
    $smarty->assign("error_msg", "This phase already exists!");
}
$phaseDetail = fetch_phaseDetails($projectId);
$towerDetail = fetch_towerDetails_for_phase($projectId);
$smarty->assign("TowerDetails", $towerDetail);

// Project Options and Bedroom Details
$optionsDetails = ProjectOptionDetail($projectId);
$smarty->assign("OptionsDetails", $optionsDetails);
$bedroomDetails = ProjectBedroomDetail($projectId);
$smarty->assign("BedroomDetails", $bedroomDetails);

/* * ********************************** */
if (isset($_POST['btnSave']) || isset($_POST['btnAddMore'])) {
    // Vars
    $phasename = $_REQUEST['PhaseName'];
    $launch_date = $_REQUEST['launch_date'];
    $completion_date = $_REQUEST['completion_date'];
    $towers = $_REQUEST['towers'];  // Array
    $remark = $_REQUEST['remark'];
    if (isset($_REQUEST["phaseLaunched"])) {
        $phaseLaunched = $_REQUEST["phaseLaunched"];
    } else {
        $phaseLaunched = 0;
    }

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

    $PhaseExists = searchPhase($phaseDetail, $phasename);
    if ($PhaseExists != -1) {
        header("Location:phase.php?projectId=" . $projectId . "&error=true");
    } else {
        ############## Transaction ##############
        ResiProjectPhase::transaction(function(){
            global $projectId, $phasename, $launch_date, $completion_date, $remark, $phaseLaunched, $towers;

//          Creating a new phase
            $phase = new ResiProjectPhase();
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
        });
        #########################################
        if ($_POST['plotvilla'] != '') {
            $supply = $_POST['supply'];
            set_phase_quantity($phaseId, $_POST['plotvilla'], '0', $supply, $projectId);
        }

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
