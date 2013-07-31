
<?php

$projectId = $_REQUEST['projectId'];
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
        mysql_query("SET AUTOCOMMIT=0");
        mysql_query("START TRANSACTION");

        $no_of_flats = $available_no_flats = 0;


        $phaseId = insert_phase($projectId, $phasename, $launch_date, $completion_date, $remark, $phaseLaunched);

        if ($_POST['project_type_id'] == '1' || $_POST['project_type_id'] == '3' || $_POST['project_type_id'] == '6') {
            $return = update_towers_for_project_and_phase($projectId, $phaseId, $towers);
        }

        if ($return || $phaseId) {
            mysql_query("COMMIT");
        } else {
            echo 'Transaction failed..';
            mysql_query("ROLLBACK");
            die;
        }
        mysql_query("SET AUTOCOMMIT=1");
        #########################################
        // Phase Quantity
        if (sizeof($flats_config) > 0) {
            foreach ($flats_config as $key => $value) {
                set_phase_quantity($phaseId, 'Apartment', $key, $value, $projectId);
            }
        }
        if (sizeof($villas_config) > 0) {
            foreach ($villas_config as $key => $value) {
                set_phase_quantity($phaseId, 'Villa', $key, $value, $projectId);
            }
        }

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
