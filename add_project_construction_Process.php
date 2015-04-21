<?php

$projectId = $_REQUEST['projectId'];
$fetch_projectDetail = ProjectDetail($projectId);
$smarty->assign("fetch_projectDetail", $fetch_projectDetail);

$projectStatus = ResiProject::projectStatusMaster();
$smarty->assign("projectStatus", $projectStatus);

//find start and end date for year and month drop down
$YearStart = 2000;
// $YearStart = date('Y',$YearStart);
$smarty->assign("YearStart", $YearStart);
$yearEnd = mktime(0, 0, 0, date('m'), date('d'), date('Y') + 20);
$yearEnd = date('Y', $yearEnd);
$smarty->assign("yearEnd", $yearEnd);
//code for phase dropdown
$phaseDetail = array();
$phases = ResiProjectPhase::find("all", array("conditions" => array("project_id" => $projectId, "status" => 'Active'), "order" => "phase_name asc"));
foreach ($phases as $p) {
    array_push($phaseDetail, $p->to_custom_array());
}

//print "<pre>".print_r($phaseDetail,1)."</pre>";

$phases = Array();
$old_phase_name = '';
if (isset($_REQUEST['phaseId'])) {
    $phaseId = $_REQUEST['phaseId'];
    $qryHistory = "select * from " . RESI_PROJ_EXPECTED_COMPLETION . " 
             where project_id = $projectId and phase_id = $phaseId and expected_completion_date !='0000-00-00 00:00:00' and submitted_date !='0000-00-00 00:00:00' order by submitted_date";
    $resHistory = mysql_query($qryHistory);
    $arrHistory = array();
    $EffectiveDateList = '';
    while ($data = mysql_fetch_assoc($resHistory)) {
        $arrHistory[] = $data;
        $exp = explode("-", $data['SUBMITTED_DATE']);
        $EffectiveDateList.= $exp[0] . "-" . $exp[1] . '#';
    }
    $arrHistoryAll = $arrHistory;
    $current_element = array_pop($arrHistory);
    $smarty->assign("costDetail", $arrHistory);
    $smarty->assign("EffectiveDateList", $EffectiveDateList);

    $current_phase = phaseDetailsForId($phaseId);
    $oldCompletionDate = $current_phase[0]['COMPLETION_DATE'];
    // Assign vars for smarty
    $smarty->assign("launchDate", $current_phase[0]['LAUNCH_DATE']);
    $fetch_projectDetail = ProjectDetail($projectId);
    
    //print "<pre>".print_r($fetch_projectDetail,1)."</pre>";
    
    $smarty->assign("pre_launch_date", $fetch_projectDetail[0]['PRE_LAUNCH_DATE']);
    $smarty->assign("oldCompletionDate", $oldCompletionDate);
    $expCompletionDate = explode("-", $current_phase[0]['COMPLETION_DATE']);
    $sumittedDate = explode("-", $current_phase[0]['submitted_date']);

    if ($expCompletionDate[1] && $expCompletionDate[1] != '00' && $expCompletionDate[2] && $expCompletionDate[2] != '00' && $sumittedDate[1] && $sumittedDate[1] != '00' && $sumittedDate[0] && $sumittedDate[0] != '00') {
        $smarty->assign("month_expected_completion", $expCompletionDate[1]);
        $smarty->assign("year_expected_completion", $expCompletionDate[0]);
        $smarty->assign("month_effective_date", $sumittedDate[1]);
        $smarty->assign("year_effective_date", $sumittedDate[0]);
    }


    $qrySelect = ResiProjectPhase::virtual_find($phaseId);
    $phaseName = $qrySelect->phase_name;
    $smarty->assign("phaseName", $phaseName);
    
    
    //fetching remark of related submitted date
    $submitted_date_string = $sumittedDate[0] . "-" . $sumittedDate[1];
    $submitted_remark = ResiProjExpectedCompletion::find("all", array("conditions" => array(" project_id = {$projectId} and SUBMITTED_DATE  like '{$submitted_date_string}%' and phase_id = {$phaseId}"), 'select' => 'REMARK', 'limit' => 1));
    $smarty->assign("submitted_remark", $submitted_remark[0]->remark);
}
foreach ($phaseDetail as $k => $val) {
    $p = Array();
    $p['id'] = $val['PHASE_ID'];
    $p['name'] = $val['PHASE_NAME'];
    if ($val['PHASE_ID'] == $phaseId) {
        $old_phase_name = $val['PHASE_NAME'];
    }
    array_push($phases, $p);
}
$smarty->assign("phaseId", $phaseId);
$smarty->assign("phases", $phases);

$smarty->assign("construction_status", $qrySelect->construction_status);

//print "<pre>".print_r($qrySelect->phase_name,1)."</pre>";
//end code for phase edit dropdown

$hiserrorMsg = array();

//saving history
include('histroy_updation_construction_Process.php');
if (isset($_GET['updated_ids']) && count($hiserrorMsg) == 0) {
    $smarty->assign("hist_update_arr", explode("-", $_GET['updated_ids']));
    $smarty->assign("hist_update", $_GET['hist']);
}

if (isset($_POST['btnSave']) && ($_REQUEST['updateOrInsertRow'] == 1 || $_REQUEST['updateOrInsertRow'] == '')) {

    //print "<pre>".print_r($qrySelect->phase_name,1)."</pre>"; die;

    $construnction_status = $_REQUEST['construction_status'];

    $remark = $_REQUEST['remark'];
    $smarty->assign("remark", $remark);
    $month_expected_completion = $_REQUEST['month_expected_completion'];
    $year_expected_completion = $_REQUEST['year_expected_completion'];
    $smarty->assign("month_expected_completion", $month_expected_completion);
    $smarty->assign("year_expected_completion", $year_expected_completion);
    if (strlen($month_expected_completion) == 1)
        $month_expected_completion = "0" . $month_expected_completion;
    $expectedCompletionDate = $year_expected_completion . "-" . $month_expected_completion . "-01";

    $month_effective_date = $_REQUEST['month_effective_date'];
    $year_effective_date = $_REQUEST['year_effective_date'];
    $smarty->assign("month_effective_date", $month_effective_date);
    $smarty->assign("year_effective_date", $year_effective_date);
    $month_effective_date = ($month_effective_date < 9) ? "0" . $month_effective_date : $month_effective_date;
    if ($month_effective_date == '' && $year_effective_date == '')
        $effectiveDt = date('Y') . "-" . date('m') . "-01";
    else
        $effectiveDt = $year_effective_date . "-" . $month_effective_date . "-01";

    /*     * ******validation taken from project add/edit page************ */
    $launchDate = $_REQUEST['launchDate'];
    $pre_launch_date = $_REQUEST['pre_launch_date'];
    $expLaunchDate = explode("-", $launchDate);

    if ($launchDate == '0000-00-00')
        $launchDate = '';
    if ($expectedCompletionDate == '0000-00-00')
        $expectedCompletionDate = '';
    if ($pre_launch_date == '0000-00-00')
        $pre_launch_date = '';
    if ($launchDate != '' && ($year_expected_completion < $expLaunchDate[0] || ( $year_expected_completion == $expLaunchDate[0] && $month_expected_completion <= $expLaunchDate[1]))) {
        $errorMsg['CompletionDateGreater'] = 'Completion date to be always greater than launch date';
    }
    if ($launchDate != '' && $expectedCompletionDate != '') {
        $retdt = ((strtotime($expectedCompletionDate) - strtotime($launchDate)) / (60 * 60 * 24));
        if ($retdt <= 180) {
            $errorMsg['CompletionDateGreater'] = 'Completion date to be always 6 month greater than launch date';
        }
    }

    #phase level validations #############   
    //$phase_pre_launch_date = $phaseDetail[0]['PRE_LAUNCH_DATE'];
    $phase_pre_launch_date = $qrySelect->pre_launch_date;
    if ($phase_pre_launch_date != '' && $expectedCompletionDate != '') {
        $retdt = ((strtotime($expectedCompletionDate) - strtotime($phase_pre_launch_date)) / (60 * 60 * 24));
        if ($retdt <= 0) {
            $error_msg = "Completion date to be always greater than Pre Launch date for Phase";
        }
    }
    if ($construnction_status == OCCUPIED_ID_3 || $construnction_status == READY_FOR_POSSESSION_ID_4) {
        $yearExp = explode("-", $expectedCompletionDate);
        if ($yearExp[0] == date("Y")) {
            if (intval($yearExp[1]) > intval(date("m"))) {
                $errorMsg['CompletionDateGreater'] = "Completion date cannot be greater current month  in case of Construction Status is completed.";
            }
        } else if (intval($yearExp[0]) > intval(date("Y"))) {
            $errorMsg['CompletionDateGreater'] = "Completion date cannot be greater current month  in case of Construction Status is completed.";
        }
    }
    if ($construnction_status == UNDER_CONSTRUCTION_ID_1 || $construnction_status == LAUNCHED_ID_7 || $construnction_status == PRE_LAUNCHED_ID_8) {
        $yearExp = explode("-", $expectedCompletionDate);
        if ($yearExp[0] == date("Y")) {
            if (intval($yearExp[1]) < intval(date("m"))) {
                $errorMsg['CompletionDateGreater'] = "Completion date cannot be less than the current month in case of Construction Status is Under Construction.";
            }
        } else if (intval($yearExp[0]) < intval(date("Y"))) {
            $errorMsg['CompletionDateGreater'] = "Completion date cannot be less than the current month  in case of Construction Status is Under Construction.";
        }
    }
    if ($construnction_status == PRE_LAUNCHED_ID_8) {
        $yearExp = explode("-", $expectedCompletionDate);
        if ($yearExp[0] == date("Y")) {
            if (intval($yearExp[1]) < intval(date("m"))) {
                $errorMsg['CompletionDateGreater'] = "Completion date cannot be less than the current month in case of Construction Status is PreLaunch.";
            }
        } else if (intval($yearExp[0]) < intval(date("Y"))) {
            $errorMsg['CompletionDateGreater'] = "Completion date cannot be less than the current month  in case of Construction Status is Under PreLaunch.";
        }
    }
    if ($construnction_status == LAUNCHED_ID_7) {
        $yearExp = explode("-", $expectedCompletionDate);
        if ($yearExp[0] == date("Y")) {
            if (intval($yearExp[1]) < intval(date("m"))) {
                $errorMsg['CompletionDateGreater'] = "Completion date cannot be less than the current month in case of Construction Status is Launch.";
            }
        } else if (intval($yearExp[0]) < intval(date("Y"))) {
            $errorMsg['CompletionDateGreater'] = "Completion date cannot be less than the current month  in case of Construction Status is Launch.";
        }
    }
    
    if (($construnction_status == UNDER_CONSTRUCTION_ID_1 || $construnction_status == LAUNCHED_ID_7) && !(($fetch_projectDetail[0]['PROJECT_STAGE_ID'] == 2 && $fetch_projectDetail[0]['PROJECT_PHASE_ID'] == 2) || ($fetch_projectDetail[0]['PROJECT_STAGE_ID'] == 2 && $fetch_projectDetail[0]['PROJECT_PHASE_ID'] == 1))) {
        $construction_status_text = ($construnction_status == UNDER_CONSTRUCTION_ID_1) ? "Under construction" : "Launched";
        $yearExp = explode("-", $launchDate);
        $yearExp2 = explode("-", $expectedCompletionDate);

        if ($launchDate != '') {
            if (strtotime($launchDate) > time()) {
                $errorMsg['CompletionDateGreater'] = "Launch date cannot be Future date in case of Construction Status is $construction_status_text.";
            }
        } else {
            $errorMsg['CompletionDateGreater'] = "Launch date is required in case of Construction Status is $construction_status_text.";
        }

        if ($expectedCompletionDate != '') {
            if ($yearExp2[0] == date("Y")) {
                if (intval($yearExp2[1]) < intval(date("m"))) {
                    $errorMsg['CompletionDateGreater'] = "Completion date cannot be past date in case of Construction Status is $construction_status_text.";
                }
            } else if (intval($yearExp2[0]) < intval(date("Y"))) {
                $errorMsg['CompletionDateGreater'] = "Completion date cannot be past date in case of Construction Status is $construction_status_text.";
            }
        } else {
            $errorMsg['CompletionDateGreater'] = "Completion date is required in case of Construction Status is $construction_status_text.";
        }
    } elseif (($construnction_status == OCCUPIED_ID_3 || $construnction_status == READY_FOR_POSSESSION_ID_4) && !(($fetch_projectDetail[0]['PROJECT_STAGE_ID'] == 2 && $fetch_projectDetail[0]['PROJECT_PHASE_ID'] == 2) || ($fetch_projectDetail[0]['PROJECT_STAGE_ID'] == 2 && $fetch_projectDetail[0]['PROJECT_PHASE_ID'] == 1))) {
        $yearExp = explode("-", $expectedCompletionDate);
        if ($expectedCompletionDate != '') {
            if ($yearExp2[0] == date("Y")) {
                if (intval($yearExp2[1]) > intval(date("m"))) {
                    $errorMsg['CompletionDateGreater'] = "Completion date cannot be future date in case of Construction Status is Completed.";
                }
            } else if (intval($yearExp2[0]) > intval(date("Y"))) {
                $errorMsg['CompletionDateGreater'] = "Completion date cannot be future date in case of Construction Status is Completed.";
            }
        } else {
            $errorMsg['CompletionDateGreater'] = "Completion date is required in case of Construction Status is Completed.";
        }
        if ($launchDate == '') {
            $errorMsg['CompletionDateGreater'] = "Launch date is required in case of Construction Status is Completed.";
        }
    } elseif ($construnction_status == PRE_LAUNCHED_ID_8 && $phase_pre_launch_date == '' && !(($fetch_projectDetail[0]['PROJECT_STAGE_ID'] == 2 && $fetch_projectDetail[0]['PROJECT_PHASE_ID'] == 2) || ($fetch_projectDetail[0]['PROJECT_STAGE_ID'] == 2 && $fetch_projectDetail[0]['PROJECT_PHASE_ID'] == 1))) {
        $errorMsg['CompletionDateGreater'] = "Phase Status can not be Pre Launched in case of Pre Launched Date is blank.";
    } elseif ($construnction_status == PRE_LAUNCHED_ID_8 && $launchDate != '') {
        $errorMsg['CompletionDateGreater'] = "Launch date should blank in case of Construction Status is Pre Launched.";
    }
    if ($construnction_status == PRE_LAUNCHED_ID_8 && $expectedCompletionDate == '' && !(($fetch_projectDetail[0]['PROJECT_STAGE_ID'] == 2 && $fetch_projectDetail[0]['PROJECT_PHASE_ID'] == 2) || ($fetch_projectDetail[0]['PROJECT_STAGE_ID'] == 2 && $fetch_projectDetail[0]['PROJECT_PHASE_ID'] == 1))) {
        $errorMsg['CompletionDateGreater'] = "Completion date can not be blank in case of Construction Status is Pre Launched.";
    } elseif ($construnction_status == PRE_LAUNCHED_ID_8 && $expectedCompletionDate != '' && !(($fetch_projectDetail[0]['PROJECT_STAGE_ID'] == 2 && $fetch_projectDetail[0]['PROJECT_PHASE_ID'] == 2) || ($fetch_projectDetail[0]['PROJECT_STAGE_ID'] == 2 && $fetch_projectDetail[0]['PROJECT_PHASE_ID'] == 1))) {
        $yearExp = explode("-", $expectedCompletionDate);
        if ($yearExp[0] == date("Y")) {
            if (intval($yearExp[1]) < intval(date("m"))) {
                $errorMsg['CompletionDateGreater'] = "Completion date cannot be Past date in case of Construction Status is Pre Launched.";
            }
        } else if (intval($yearExp[0]) < intval(date("Y"))) {
            $errorMsg['CompletionDateGreater'] = "Completion date cannot be Past date in case of Construction Status is Pre Launched.";
        }
    }
    
    //print "--->".$phase_pre_launch_date; die;
    
    if ($construnction_status == PRE_LAUNCHED_ID_8 && $phase_pre_launch_date != '' && !(($fetch_projectDetail[0]['PROJECT_STAGE_ID'] == 2 && $fetch_projectDetail[0]['PROJECT_PHASE_ID'] == 2) || ($fetch_projectDetail[0]['PROJECT_STAGE_ID'] == 2 && $fetch_projectDetail[0]['PROJECT_PHASE_ID'] == 1))) {
        if (time() < strtotime($phase_pre_launch_date)) {
            $errorMsg['CompletionDateGreater'] = "Pre-Launch date cannot be Future date in case of Construction Status is Pre Launched.";
        }
    }

    ######################################
    $phase_created = mysql_fetch_object(mysql_query("SELECT COUNT(*) as cnt FROM `resi_project_phase`  WHERE `resi_project_phase`.`version` = 'Cms' AND `resi_project_phase`.`PROJECT_ID` = '$projectId' AND `resi_project_phase`.`PHASE_TYPE` = 'Actual'  AND `resi_project_phase`.status = 'Active'")) or die(mysql_error());

    $comp_eff_date = costructionDetail($projectId);
    $project_status = fetch_project_status($projectId, $construnction_status, $_REQUEST['phaseSelect'], false);

    $project_completion_date = '';
    if ($phase_created->cnt) {
        if ($expectedCompletionDate >= $comp_eff_date['COMPLETION_DATE'])
            $project_completion_date = $expectedCompletionDate;
        if ($expectedCompletionDate < $comp_eff_date['COMPLETION_DATE'])
            $project_completion_date = $comp_eff_date['COMPLETION_DATE'];
        if ($_REQUEST['phaseSelect'] == $comp_eff_date['PHASE_ID'])
            $project_completion_date = $expectedCompletionDate;
        if ($project_completion_date == '0000-00-00')
            $project_completion_date = '';
    } else
        $project_completion_date = $expectedCompletionDate;


//    if ($project_status == OCCUPIED_ID_3 || $project_status == READY_FOR_POSSESSION_ID_4) {
//        $yearExp = explode("-", $project_completion_date);
//        if ($yearExp[0] == date("Y")) {
//            if (intval($yearExp[1]) > intval(date("m"))) {
//                $errorMsg['CompletionDateGreater'] = "Completion date cannot be greater current month in case project status is Completed.";
//            }
//        } else if (intval($yearExp[0]) > intval(date("Y"))) {
//            $errorMsg['CompletionDateGreater'] = "Completion date cannot be greater current month in case project status is Completed.";
//        }
//    }
//
//    if ($project_status == UNDER_CONSTRUCTION_ID_1) {
//        $yearExp = explode("-", $project_completion_date);
//        if ($yearExp[0] == date("Y")) {
//            if (intval($yearExp[1]) < intval(date("m"))) {
//                $errorMsg['CompletionDateGreater'] = "Completion date cannot be less than the current month in case project status is Under Construction.";
//            }
//        } else if (intval($yearExp[0]) < intval(date("Y"))) {
//            $errorMsg['CompletionDateGreater'] = "Completion date cannot be less than the current month  in case project status is Under Construction.";
//        }
//    }
    //project level construction status validations
    if (empty($error_msg)) {
        if (($project_status == PRE_LAUNCHED_ID_8 && $launchDate != '' && $qrySelect->phase_name != 'No Phase') || ($project_status == PRE_LAUNCHED_ID_8 && $launch_date != '' && $qrySelect->phase_name == 'No Phase')) {
            $errorMsg['CompletionDateGreater'] = "Launch date should be blank/zero in case of Pre Launched Project.";
        } elseif ($project_status == PRE_LAUNCHED_ID_8 && $pre_launch_date == '') {
            $errorMsg['CompletionDateGreater'] = "Project Status can not be Pre Launched in case of Pre Launched Date is blank.";
        } elseif (($project_status == OCCUPIED_ID_3 || $project_status == READY_FOR_POSSESSION_ID_4) && $project_completion_date != '') {
            $yearExp = explode("-", $project_completion_date);
            if ($yearExp[0] == date("Y")) {
                if (intval($yearExp[1]) > intval(date("m"))) {
                    $errorMsg['CompletionDateGreater'] = "Completion date cannot be greater than the current month in case of Completed Project";
                }
            } else if (intval($yearExp[0]) > intval(date("Y"))) {
                $errorMsg['CompletionDateGreater'] = "Completion date cannot be greater than the current month in case of Completed Project";
            }
        } elseif ($project_status == UNDER_CONSTRUCTION_ID_1 && $project_completion_date != '') {
            $yearExp = explode("-", $project_completion_date);
            if ($yearExp[0] == date("Y")) {
                if (intval($yearExp[1]) < intval(date("m"))) {
                    $errorMsg['CompletionDateGreater'] = "Completion date cannot be less than the current month in case of Under construction Project";
                }
            } else if (intval($yearExp[0]) < intval(date("Y"))) {
                $errorMsg['CompletionDateGreater'] = "Completion date cannot be less than the current month in case of Under construction Project";
            }
        }
    }



    if ($pre_launch_date != '' && $expectedCompletionDate != '') {
        $retdt = ((strtotime($expectedCompletionDate) - strtotime($pre_launch_date)) / (60 * 60 * 24));
        if ($retdt <= 0) {
            $errorMsg['CompletionDateGreater'] = "Completion date to be always greater than Pre Launch date";
        }
    }
    $submitted_date_string = $year_effective_date . "-" . $month_effective_date . "-01";
    if (strtotime($expectedCompletionDate) > strtotime($current_element['EXPECTED_COMPLETION_DATE']) && strtotime($submitted_date_string) < strtotime($current_element['SUBMITTED_DATE'])) {
        $errorMsg['CompletionDateGreater'] = "Completion date($expectedCompletionDate) to be always less the latest completion date(" . $current_element['EXPECTED_COMPLETION_DATE'] . ").";
    }
    if (count($errorMsg) > 0) {
        $smarty->assign('errorMsg', $errorMsg);
    }
    /*     * ****end validation taken from project add/edit page************ */ else if (($month_effective_date <= date('m') && $year_effective_date == date('Y')) || $year_effective_date < date('Y')) {

        $updation_flag = 0; //flag of updation in phase and resi_project tables

        $submitted_date_string = $year_effective_date . "-" . $month_effective_date;

        //code for update completion date history if month and year are same and already exists entry

        $exist_eff_date = ResiProjExpectedCompletion::find("all", array("conditions" => array(" project_id = {$projectId} and SUBMITTED_DATE  like '{$submitted_date_string}%' and phase_id = {$phaseId}"), 'select' => 'SUBMITTED_DATE', 'limit' => 1, 'order' => 'SUBMITTED_DATE desc'));

        if ($exist_eff_date) {
            $qry = "UPDATE " . RESI_PROJ_EXPECTED_COMPLETION . "
                                SET	
                                    EXPECTED_COMPLETION_DATE = '" . $expectedCompletionDate . "',
                                    REMARK = '" . $remark . "',
                                    SUBMITTED_DATE = '" . $effectiveDt . "'
                                WHERE
                                    PROJECT_ID = '" . $projectId . "' 
                                AND
                                    phase_id = '" . $phaseId . "'
                                AND
                                SUBMITTED_DATE  like '{$submitted_date_string}%'";
            $res = mysql_query($qry) OR die(mysql_error() . " completion date update");
        } else {
            $qry = "insert into " . RESI_PROJ_EXPECTED_COMPLETION . "
                                    SET	
                                        EXPECTED_COMPLETION_DATE = '" . $expectedCompletionDate . "',
                                        REMARK = '" . $remark . "',
                                        PROJECT_ID = '" . $projectId . "',
                                        phase_id = $phaseId,
                                        SUBMITTED_DATE = '" . $effectiveDt . "'";
            $res = mysql_query($qry) OR die(mysql_error() . " completion date update");
        }

        //maintaining Ascending Order                    
        if ($res) {
            $effectiveDt = $year_effective_date . "-" . $month_effective_date . "-01";
            $check_rows = mysql_query("select * from resi_proj_expected_completion 
										where project_id = '" . $projectId . "' and phase_id = '" . $phaseId . "' 
										 and DATE_FORMAT(SUBMITTED_DATE, '%Y-%m-%d') < '" . $effectiveDt . "' 
										 and DATE_FORMAT(EXPECTED_COMPLETION_DATE, '%Y-%m-%d') > '" . $expectedCompletionDate . "'");

            if (mysql_num_rows($check_rows)) {
                mysql_query("UPDATE " . RESI_PROJ_EXPECTED_COMPLETION . "
                                SET	
                                    EXPECTED_COMPLETION_DATE = '" . $expectedCompletionDate . "'
                                WHERE
                                    PROJECT_ID = '" . $projectId . "' 
                                AND
                                    phase_id = '" . $phaseId . "'
                                 AND DATE_FORMAT(SUBMITTED_DATE, '%Y-%m-%d') < '" . $effectiveDt . "'") or die(mysql_error());
            }
        }
        if ($res && ($month_effective_date >= date('m', strtotime($current_element['SUBMITTED_DATE'])) && $year_effective_date >= date('Y', strtotime($current_element['SUBMITTED_DATE'])))) { // updation only with latest month data
            //phase update
            $qryPhaseUpdate = "update resi_project_phase 
                            set 
                                updated_by = " . $_SESSION['adminId'] . ",
                                completion_date = '" . $expectedCompletionDate . "',
                                submitted_date = '" . $effectiveDt . "',
                                construction_status = '" . $construnction_status . "'
                            where phase_id = $phaseId and project_id = $projectId
                              and version = 'Cms'";
            $resPhaseUpdate = mysql_query($qryPhaseUpdate) or die(mysql_error() . " phase update");
            $costDetailLatest = costructionDetail($projectId);
            $costDetailLatest['COMPLETION_DATE'];
            $qry = "UPDATE resi_project 
                             set 
                                updated_by = " . $_SESSION['adminId'] . ",
                                PROMISED_COMPLETION_DATE = '" . $costDetailLatest['COMPLETION_DATE'] . "' 
                             where PROJECT_ID = $projectId and version = 'Cms'";
            $success = mysql_query($qry) OR die(mysql_error() . " project update");
            projectStatusUpdate($projectId); //update project status
            updateD_Availablitiy($projectId); // update D_availability
        }
        if ($success)
        //header("Location:ProjectList.php?projectId=".$projectId);
            
            ?>
            <script type="text/javascript">
                window.opener.location.reload(false);
                window.close();
            </script>
        <?php

    }
    else {
        $errorMsg['submitted_date'] = "Submitted date can not be greater then current month";
    }
    $smarty->assign('errorMsg', $errorMsg);
} else if ($_POST['btnExit'] == "Exit") {
    //  header("Location:ProjectList.php?projectId=".$projectId);
    ?>
    <script type="text/javascript">
        window.opener.location.reload(false);
        window.close();
    </script>
    <?php

}

/* * *********************************** */
$smarty->assign('eff_date', $effectiveDt);

$months = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul',
    8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
$smarty->assign('months', $months);
?>
