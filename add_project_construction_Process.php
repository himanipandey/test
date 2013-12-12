<?php
	$projectId = $_REQUEST['projectId'];
	$fetch_projectDetail = ProjectDetail($projectId);
	$smarty->assign("fetch_projectDetail", $fetch_projectDetail); 
	
        //find start and end date for year and month drop down
        $YearStart = mktime(0,0,0,date('m'),date('d'),date('Y')-2);
        $YearStart = date('Y',$YearStart);
        $smarty->assign("YearStart", $YearStart); 
        $yearEnd = mktime(0,0,0,date('m'),date('d'),date('Y')+20);
        $yearEnd = date('Y',$yearEnd);
        $smarty->assign("yearEnd", $yearEnd); 
        //code for phase dropdown
        $phaseDetail = array();
        $phases = ResiProjectPhase::find("all", array("conditions" => array("project_id" => $projectId, "status" => 'Active'), "order" => "phase_name asc"));
        foreach($phases as $p){
            array_push($phaseDetail, $p->to_custom_array());
        }
        $phases = Array();
        $old_phase_name = '';
        if(isset($_REQUEST['phaseId'])) {
            $phaseId = $_REQUEST['phaseId'];
            $qryHistory = "select * from ".RESI_PROJ_EXPECTED_COMPLETION." 
             where project_id = $projectId and phase_id = $phaseId order by expected_completion_date desc";
            $resHistory = mysql_query($qryHistory);
            $arrHistory = array();
            $EffectiveDateList = '';
            while($data = mysql_fetch_assoc($resHistory)) {
                $arrHistory[] = $data;
                $exp = explode("-",$data['SUBMITTED_DATE']);
                $EffectiveDateList.= $exp[0]."-".$exp[1].'#';
            }
            $smarty->assign("costDetail", $arrHistory); 
            $smarty->assign("EffectiveDateList", $EffectiveDateList); 
            
             $current_phase = phaseDetailsForId($phaseId);
             $oldCompletionDate = $current_phase[0]['COMPLETION_DATE'];
            // Assign vars for smarty
            $smarty->assign("launchDate", $current_phase[0]['LAUNCH_DATE']);
            $smarty->assign("oldCompletionDate", $oldCompletionDate);
            $expCompletionDate = explode("-",$current_phase[0]['COMPLETION_DATE']);
            $smarty->assign("month_expected_completion", $expCompletionDate[1]);
            $smarty->assign("year_expected_completion", $expCompletionDate[0]);
            $sumittedDate = explode("-",$current_phase[0]['submitted_date']);
            $smarty->assign("month_effective_date", $sumittedDate[1]);
            $smarty->assign("year_effective_date", $sumittedDate[0]);
            
            $qrySelect = ResiProjectPhase::virtual_find($phaseId);
            $phaseName = $qrySelect->phase_name;
            $smarty->assign("phaseName", $phaseName);
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
        //end code for phase edit dropdown
	if(isset($_POST['btnSave']))
	{
		$remark	= $_REQUEST['remark'];
                $smarty->assign("remark", $remark);
		$month_expected_completion = $_REQUEST['month_expected_completion'];
                $year_expected_completion = $_REQUEST['year_expected_completion'];
                $smarty->assign("month_expected_completion", $month_expected_completion);
                $smarty->assign("year_expected_completion", $year_expected_completion);
                if(strlen($month_expected_completion)==1)
                    $month_expected_completion = "0".$month_expected_completion;
                $expectedCompletionDate  = $year_expected_completion."-".$month_expected_completion."-01";
                
                $month_effective_date = $_REQUEST['month_effective_date'];
                $year_effective_date = $_REQUEST['year_effective_date'];
                $smarty->assign("month_effective_date", $month_effective_date);
                $smarty->assign("year_effective_date", $year_effective_date);
                if($month_effective_date == '' && $year_effective_date == '')
                    $effectiveDt = date('Y')."-".date('m')."-01";
                else
                    $effectiveDt = $year_effective_date."-".$month_effective_date."-01";
                $errorMsg = array();
                /********validation taken from project add/edit page*************/
                $launchDate = $_REQUEST['launchDate'];
                $expLaunchDate = explode("-",$launchDate);
                if( $launchDate != '' && ($year_expected_completion < $expLaunchDate[0] 
                        || ( $year_expected_completion == $expLaunchDate[0] && $month_expected_completion <= $expLaunchDate[1])) ){
                    $errorMsg['CompletionDateGreater'] = 'Completion date to be always greater than launch date';
                }
                if( $launchDate != '' && $expectedCompletionDate !='' ) {
                    $retdt  = ((strtotime($expectedCompletionDate)-strtotime($launchDate))/(60*60*24));
                    if( $retdt <= 180 ) {
                        $errorMsg['CompletionDateGreater'] = 'Completion date to be always 6 month greater than launch date';
                    }
                }
                /******end validation taken from project add/edit page*************/
                
                else if( ($month_effective_date <= date('m') && $year_effective_date == date('Y')) || $year_effective_date <= date('Y') ) {
                    //code for update completion date history if month and year are same and already exists entry
                    $qryOldData = "select * from ".RESI_PROJ_EXPECTED_COMPLETION." 
                        where project_id = $projectId and phase_id = $phaseId";
                    $resOldData = mysql_query($qryOldData);
                    if($month_effective_date == date('m') && $year_effective_date == date('Y') && mysql_num_rows($resOldData)>0 && $_REQUEST['updateOrInsertRow'] == 1) {
                        $submittted_dateMin = $year_effective_date."-".$month_effective_date."-01";
                        $submittted_dateMax = $year_effective_date."-".$month_effective_date."-31";
                        $qry = "UPDATE ".RESI_PROJ_EXPECTED_COMPLETION."
                                SET	
                                    EXPECTED_COMPLETION_DATE = '".$expectedCompletionDate."',
                                    REMARK = '".$remark."',
                                    SUBMITTED_DATE = '".$effectiveDt."'
                                WHERE
                                    PROJECT_ID = '".$projectId."' 
                                AND
                                    phase_id = $phaseId
                                AND submitted_date >= '".$submittted_dateMin."' and submitted_date <= '".$submittted_dateMax."'";
                    }
                    else {
                        //code if user want to update completion date with new submitted date
                        $qryOldCompletionDateExists = "select * from ".RESI_PROJ_EXPECTED_COMPLETION."
                            where expected_completion_date = '".$expectedCompletionDate."'
                                  and project_id = $projectId and phase_id = $phaseId";
                        $resOldCompletionDate = mysql_query($qryOldCompletionDateExists);
                        if(mysql_num_rows($resOldCompletionDate)>0) {
                          $qry = "UPDATE ".RESI_PROJ_EXPECTED_COMPLETION."
                                    SET	
                                        EXPECTED_COMPLETION_DATE = '".$expectedCompletionDate."',
                                        REMARK = '".$remark."'
                                    WHERE
                                        PROJECT_ID = '".$projectId."' 
                                    AND
                                        phase_id = $phaseId
                                    AND
                                        SUBMITTED_DATE = '".$effectiveDt."'";
                        }
                        //code if user want to update completion date with new submitted date
                        else{
                            if($_REQUEST['oldCompletionDate'] == '0000-00-00') {
                                $startDt = $effectiveDt;
                                $endDt = explode("-",$effectiveDt);
                                $endDate = $endDt[0]."-".$endDt[1]."-31";
                             $qry = "UPDATE ".RESI_PROJ_EXPECTED_COMPLETION."
                                SET	
                                    EXPECTED_COMPLETION_DATE = '".$expectedCompletionDate."',
                                    REMARK = '".$remark."'
                                   WHERE
                                        PROJECT_ID = '".$projectId."' 
                                    AND
                                        phase_id = $phaseId
                                    AND
                                        SUBMITTED_DATE >= '".$startDt."' and SUBMITTED_DATE <= '".$endDate."'";
                            }
                            else {
                                   $qry = "INSERT INTO ".RESI_PROJ_EXPECTED_COMPLETION."
                                    SET	
                                        PROJECT_ID = '".$projectId."',
                                        phase_id = $phaseId,
                                        EXPECTED_COMPLETION_DATE = '".$expectedCompletionDate."',
                                        REMARK = '".$remark."',
                                        SUBMITTED_DATE = '$effectiveDt'";
                            }
                        }
                    }
                    $res = mysql_query($qry) OR die(mysql_error()." completion date update");
                    if($res) {
                        //phase update
                        $qryPhaseUpdate = "update resi_project_phase 
                            set completion_date = '".$expectedCompletionDate."',
                                submitted_date = '".$effectiveDt."'
                            where phase_id = $phaseId and project_id = $projectId";
                        $resPhaseUpdate = mysql_query($qryPhaseUpdate) or die(mysql_error()." phase update");
                        $costDetailLatest = costructionDetail($projectId);
                        $costDetailLatest['COMPLETION_DATE'];
                         $qry = "UPDATE resi_project 
                             set 
                                PROMISED_COMPLETION_DATE = '".$costDetailLatest['COMPLETION_DATE']."' 
                             where PROJECT_ID = $projectId";
                        $success = mysql_query($qry) OR die(mysql_error()." project update");
                    }
                    if($success)
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
                $smarty->assign('errorMsg',$errorMsg);
    }
	else if($_POST['btnExit'] == "Exit")
	{
		//  header("Location:ProjectList.php?projectId=".$projectId);
            ?>
                    <script type="text/javascript">
                     window.opener.location.reload(false);
                    window.close();
                    </script>
                    <?php
	}
	
	/**************************************/
	
$smarty->assign('eff_date',$effectiveDt);
?>