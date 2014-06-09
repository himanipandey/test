<?php
$projectId = $_REQUEST['projectId'];
$project = ResiProject::virtual_find($projectId);
$options = $project->get_all_options();
$smarty->assign("options", $options);
$projectDetail = array($project->to_custom_array());
$smarty->assign("ProjectDetail", $projectDetail);
$smarty->assign("pre_launch_date", $projectDetail[0]['PRE_LAUNCH_DATE']);
$projectStatus = ResiProject::projectStatusMaster();
$smarty->assign("projectStatus",$projectStatus);
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
    $phasename = "Phase - ".$_REQUEST['PhaseName'];
    $construction_status = $_REQUEST['construction_status'];
    $launch_date = $_REQUEST['launch_date'];
    $completion_date = $_REQUEST['completion_date'];
    $pre_launch_date = $_REQUEST['pre_launch_date'];
    $towers = $_REQUEST['towers'];  // Array
    $remark = $_REQUEST['remark'];
    $sold_out_date = $_REQUEST['sold_out_date']; 
    $bookingStatus = 1; //available

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
            $smarty->assign("pre_launch_date",$pre_launch_date);
            $error_msg = '';
            
        if($launch_date == '0000-00-00')
            $launch_date = '';
        if($completion_date == '0000-00-00')
            $completion_date = '';
        if($pre_launch_date == '0000-00-00')
            $pre_launch_date = '';
        if($sold_out_date == '0000-00-00')
            $sold_out_date = '';
        if($construction_status == ""){
		  	 $error_msg = 'Construction Status is required!';
		}
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
        if($sold_out_date != ''){
			$retdt  = ((strtotime($sold_out_date) - strtotime($launch_date)) / (60*60*24));
            if( $retdt <= 0 || $launch_date=='') {
                $error_msg = "Sold out date to be always greater than Launch date";
            } 			 
			 
		 }
          
        if( $pre_launch_date != '' && $completion_date !='') {
                $retdt  = ((strtotime($completion_date) - strtotime($pre_launch_date)) / (60*60*24));
                if( $retdt <= 0 ) {
                    $error_msg = "Completion date to be always greater than Pre Launch date";
                }
         }
     /*    $fetch_projectDetail = projectDetailById($projectId);
         if( $fetch_projectDetail[0]['PROJECT_STATUS_ID'] == OCCUPIED_ID_3 || $fetch_projectDetail[0]['PROJECT_STATUS_ID'] == READY_FOR_POSSESSION_ID_4 ) {
            $yearExp = explode("-",$completion_date);
            if( $yearExp[0] == date("Y") ) {
                if( intval($yearExp[1]) > intval(date("m"))) {
                  $error_msg = "Completion date cannot be greater current month";
                }    
            } 
            else if (intval($yearExp[0]) > intval(date("Y")) ) {
                $error_msg = "Completion date cannot be greater current month";
            }
        }*/
        
        ////phase level check regarding status
		 $project_status = fetch_project_status($projectId,$construction_status);                    
        if($projectDetail[0]['LAUNCH_DATE'] == '0000-00-00')
		  $projectDetail[0]['LAUNCH_DATE'] = '';
		if($projectDetail[0]['PRE_LAUNCH_DATE'] == '0000-00-00')
		  $projectDetail[0]['PRE_LAUNCH_DATE'] = '';
		  
		$comp_eff_date = costructionDetail($projectId);
		$project_completion_date = '';
		if($completion_date > $comp_eff_date['COMPLETION_DATE'])
		  $project_completion_date = $completion_date;
		if($completion_date < $comp_eff_date['COMPLETION_DATE'])
		  $project_completion_date = $comp_eff_date['COMPLETION_DATE'];
		if($project_completion_date == '0000-00-00')
		  $project_completion_date = '';
				  
			  
	    if($construction_status == UNDER_CONSTRUCTION_ID_1) { 
           $yearExp = explode("-",$launch_date);
           $yearExp2 = explode("-",$completion_date);
           if($launch_date != ''){
			   if( $yearExp[0] == date("Y") ) {
				   if( intval($yearExp[1]) > intval(date("m"))) {
					 $error_msg = "Launch date should not be greater than current month in case of Construction Status is Under construction.";
				   }    
			   } 
			   else if (intval($yearExp[0]) > intval(date("Y")) ) {
				  $error_msg = "Launch date should not be greater than current month in case of  Construction Status is  Under construction.";
			   }
		   }
		  
           if($completion_date != ''){			   	
			   if( $yearExp2[0] == date("Y") ) {				   
				   if( intval($yearExp2[1]) < intval(date("m"))) {
					 $error_msg = "Completion date cannot be less than the current month in case of Construction Status is Under construction.";
				   }    
			   } 
			   else if (intval($yearExp2[0]) < intval(date("Y")) ) {
				  $error_msg = "Completion date cannot be less than the current month in case of Construction Status is Under construction.";
			   }
		   }		
        }elseif($construction_status == OCCUPIED_ID_3 || $construction_status == READY_FOR_POSSESSION_ID_4 ){
			$yearExp = explode("-",$completion_date);
            if( $yearExp[0] == date("Y") ) {
                if( intval($yearExp[1]) > intval(date("m"))) {
                  $error_msg = "Completion date cannot be greater current month in case of Construction Status is Completed.";
                }    
            } 
            else if (intval($yearExp[0]) > intval(date("Y")) ) {
                $error_msg = "Completion date cannot be greater current month in case of Construction Status is Completed.";
            }			
		}elseif( $construction_status == PRE_LAUNCHED_ID_8 && $launch_date != '') { 
           $error_msg = "Launch date should blank in case of Construction Status is Pre Launched.";
        }
        if($error_msg == ''){
			if( $project_status == PRE_LAUNCHED_ID_8 && $projectDetail[0]['LAUNCH_DATE'] != '') {
			  $error_msg = "Launch date should be blank/zero in case of Pre Launched Project.";	 
			}
			elseif( $project_status == PRE_LAUNCHED_ID_8 && $projectDetail[0]['PRE_LAUNCH_DATE'] == '') {
			   $error_msg = "Project Status can not be Pre Launched in case of Pre Launched Date is blank.";	 
			}elseif(($project_status == OCCUPIED_ID_3 || $project_status == READY_FOR_POSSESSION_ID_4) && $project_completion_date != ''){
				$yearExp = explode("-",$project_completion_date);
				if( $yearExp[0] == date("Y") ) {
					if( intval($yearExp[1]) > intval(date("m"))) {
					  $error_msg = "Completion date cannot be greater than the current month in case of Completed Project";
					}    
				} 
				else if (intval($yearExp[0]) > intval(date("Y")) ) {
					$error_msg = "Completion date cannot be greater than the current month in case of Completed Project";
				}			
			}elseif($project_status == UNDER_CONSTRUCTION_ID_1 && $project_completion_date != ''){
				$yearExp = explode("-",$project_completion_date);
				if( $yearExp[0] == date("Y") ) {
					if( intval($yearExp[1]) < intval(date("m"))) {
					  $error_msg = "Completion date cannot be less than the current month in case of Under construction Project";
					}    
				} 
				else if (intval($yearExp[0]) < intval(date("Y")) ) {
					$error_msg = "Completion date cannot be less than the current month in case of Under construction Project";
				}			
			}
		 }		
		 //////////////////////////////////////
        
          if($error_msg == ''){
            ############## Transaction ##############
            ResiProjectPhase::transaction(function(){
                global $projectId, $phasename, $launch_date, $completion_date, $remark, $towers, $bookingStatus, $phase, $sold_out_date, $construction_status;
    //          Creating a new phase
                $phase = new ResiProjectPhase();
                $phase->project_id = $projectId;
                $phase->phase_name = $phasename;
                $phase->launch_date = $launch_date;
                $phase->completion_date = $completion_date;
                $phase->remarks = $remark;
                $phase->status = 'Active';
                $phase->construction_status = $construction_status;
                $phase->booking_status_id = $bookingStatus;
                $phase->updated_by = $_SESSION["adminId"];
                $phase->sold_out_date = $sold_out_date;
                $phase->submitted_date = date('Y-m-d');
                 
                $phase->virtual_save();

                 /***********end code related to completion date add/edit**************/
                if ($_POST['project_type_id'] == '1' || $_POST['project_type_id'] == '3' || $_POST['project_type_id'] == '6') {
                    $phase->add_towers($towers);
                }
            });
			projectStatusUpdate($projectId); //update project status
			updateD_Availablitiy($projectId); // update D_availability  
			
            /***********code related to completion date add/edit**************/
          if($completion_date != '' && $completion_date != '0000-00-00'){  
            $qryFetchPhaseId = "select phase_id from resi_project_phase 
                where project_id = $projectId and phase_name = '".$phasename."' 
                    and version = 'Cms' ";
            $resFetchPhaseId = mysql_query($qryFetchPhaseId) or die(mysql_error());
            $dataFetchPhaseId = mysql_fetch_assoc($resFetchPhaseId);
			$effectiveDt = date('Y')."-".date('m')."-01";
            $qryCompletionDate = "insert into resi_proj_expected_completion 
                set
                  project_id = $projectId,
                  expected_completion_date = '".$completion_date."',
                  submitted_date = '".$effectiveDt."',
                  phase_id = ".$dataFetchPhaseId['phase_id'];
             $successCompletionDate =  mysql_query($qryCompletionDate) or die(mysql_error());
             if($successCompletionDate) {
                $costDetailLatest = costructionDetail($projectId);
                $qry = "UPDATE resi_project 
                    set 
                       PROMISED_COMPLETION_DATE = '".$costDetailLatest['COMPLETION_DATE']."' 
                   where PROJECT_ID = $projectId and version = 'Cms'";
                $success = mysql_query($qry) OR DIE(mysql_error());
             }
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
              $smarty->assign("pre_launch_date",$pre_launch_date);
        }
    }
}
else if ($_POST['btnExit'] == "Exit") {
    header("Location:ProjectList.php?projectId=" . $projectId);
}
/* * *********************************** */
?>
