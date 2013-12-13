<?php
$projectId = $_REQUEST['projectId'];
$project = ResiProject::virtual_find($projectId);
$options = $project->get_all_options();
$smarty->assign("options", $options);
$projectDetail = array($project->to_custom_array());
$smarty->assign("ProjectDetail", $projectDetail);
if (isset($_GET['error'])) {
    $smarty->assign("error_msg", "This phase already exists!");
}
$phaseDetail = array();
$phases = ResiProjectPhase::find("all", array("conditions" => array("project_id" => $projectId,'STATUS' => 'Active'),
    "order" => "phase_name asc"));
foreach($phases as $p){
    array_push($phaseDetail, $p->to_custom_array());
}
$towers = $project->get_all_towers();
$towerDetail = array();
foreach($towers as $t) array_push($towerDetail, $t->to_custom_array());
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
    $bookingStatus = $_REQUEST['bookingStatus'];

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
    $phase = null;
    if ($PhaseExists != -1) {
        //header("Location:phase.php?projectId=" . $projectId . "&error=true");
    } else {
            $smarty->assign("launch_date",$launch_date);
            $smarty->assign("completion_date",$completion_date);
            $error_msg = '';
        if( $launch_date != '' && $completion_date !='' ) {
            $retdt  = ((strtotime($completion_date)-strtotime($launch_date))/(60*60*24));
            if( $retdt <= 180 ) {
                $error_msg = 'Completion date to be always 6 month greater than launch date';
            }
        }
        if( $launch_date != '') {
            $retdt  = ((strtotime($launch_date) - strtotime(date('Y-m-d'))) / (60*60*24));
            if( $retdt > 0 ) {
                    $error_msg = "Launch date should be less or equal to current date";
                }
          }
          if($error_msg == ''){
            ############## Transaction ##############
            ResiProjectPhase::transaction(function(){
                global $projectId, $phasename, $launch_date, $completion_date, $remark, $towers, $bookingStatus, $phase;
    //          Creating a new phase
                $phase = new ResiProjectPhase();
                $phase->project_id = $projectId;
                $phase->phase_name = $phasename;
                $phase->launch_date = $launch_date;
                $phase->completion_date = $completion_date;
                $phase->remarks = $remark;
                $phase->status = 'Active';
                $phase->booking_status_id = $bookingStatus;
                $phase->updated_by = $_SESSION["adminId"];
                 $phase->submitted_date = date('Y-m-d');
                $phase->virtual_save();

                 /***********end code related to completion date add/edit**************/
                if ($_POST['project_type_id'] == '1' || $_POST['project_type_id'] == '3' || $_POST['project_type_id'] == '6') {
                    $phase->add_towers($towers);
                }
            });

            /***********code related to completion date add/edit**************/
            $qryFetchPhaseId = "select phase_id from resi_project_phase 
                where project_id = $projectId and phase_name = '".$phasename."'";
            $resFetchPhaseId = mysql_query($qryFetchPhaseId) or die(mysql_error());
            $dataFetchPhaseId = mysql_fetch_assoc($resFetchPhaseId);

            $qryCompletionDate = "insert into resi_proj_expected_completion 
                set
                  project_id = $projectId,
                  expected_completion_date = '".$completion_date."',
                  submitted_date = now(),
                  phase_id = ".$dataFetchPhaseId['phase_id'];
             $successCompletionDate =  mysql_query($qryCompletionDate) or die(mysql_error());
             if($successCompletionDate) {
                $costDetailLatest = costructionDetail($projectId);
                $qry = "UPDATE resi_project 
                    set 
                       PROMISED_COMPLETION_DATE = '".$costDetailLatest['COMPLETION_DATE']."' 
                   where PROJECT_ID = $projectId";
                $success = mysql_query($qry) OR DIE(mysql_error());
             }
    /***********end code related to completion date add/edit**************/
                if(isset($_POST['options'])){
                    $arr = $_POST['options'];
                    $arr = array_diff($arr, array(-1));
                    $phase->reset_options($arr);
                }

            if (isset($_POST['btnSave']))
                header("Location:ProjectList.php?projectId=" . $projectId);
            else if (isset($_POST['btnAddMore']))
                header("Location:phase.php?projectId=" . $projectId);
        }
        else {
              $smarty->assign("error_msg",$error_msg);
              $smarty->assign("launch_date",$launch_date);
              $smarty->assign("completion_date",$completion_date);
        }
    }
}
else if ($_POST['btnExit'] == "Exit") {
    header("Location:ProjectList.php?projectId=" . $projectId);
}
/* * *********************************** */
?>
