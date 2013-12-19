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
if(isset($_REQUEST['phaseId']))
   $phaseId = $_REQUEST['phaseId'];
else
    $phaseId = -1;
$preview = $_REQUEST['preview'];
$smarty->assign("preview", $preview);
$smarty->assign("projectId", $projectId);
$bookingStatuses = ResiProject::find_by_sql("select * from master_booking_statuses");
$smarty->assign("bookingStatuses", $bookingStatuses);

/* * *******code for delete phase********* */
if (isset($_REQUEST['delete'])) {
    $phase = ResiProjectPhase::virtual_find($phaseId);
    $phase->status = 'Inactive';
    $resDelete = $phase->virtual_save();
    if ($resDelete) {
        Listings::update_all(array('conditions' => array('phase_id' => $phaseId, 'listing_category' => 'Primary'), 'set' => array('status' => 'Inactive')));
        
        $costDetailLatest = costructionDetail($projectId);
        $qry = "UPDATE resi_project 
            set 
               PROMISED_COMPLETION_DATE = '".$costDetailLatest['COMPLETION_DATE']."' 
           where PROJECT_ID = $projectId";
        mysql_query($qry) OR DIE(mysql_error());
        
        
        if ($preview == 'true')
            header("Location:show_project_details.php?projectId=" . $projectId);
        else
            header("Location:ProjectList.php?projectId=" . $projectId);
    }
}
/********end code for delete phase***** */
/************/
$qrySelect = ResiProjectPhase::virtual_find($phaseId);
$phaseName = $qrySelect->phase_name;
$smarty->assign("phaseName", $phaseName);
/************/
$smarty->assign("phaseId", $phaseId);
$projectDetail = ResiProject::virtual_find($projectId);
$projectDetail = $projectDetail->to_custom_array();
$smarty->assign("ProjectDetail", array($projectDetail));

$phaseDetail = array();
$phases = ResiProjectPhase::find("all", array("conditions" => array("project_id" => $projectId, "status" => 'Active'), "order" => "phase_name asc"));
foreach($phases as $p){
    array_push($phaseDetail, $p->to_custom_array());
}

// Project Options and Bedroom Details
$optionsDetails = ProjectOptionDetail($projectId);
$smarty->assign("OptionsDetails", $optionsDetails);
$options = $project->get_all_options();
$smarty->assign("options", $options);
if (isset($phaseId) && $phaseId != -1) {
    $phase_options_temp = array();
    if($phaseId != '0'){
        $phase = ResiProjectPhase::virtual_find($phaseId);
        $smarty->assign("phase", $phase);
        $phase_options = $phase->get_all_options();
        if (count($phase_options) > 0){
            $phase_options_temp = $phase_options;
        }
    }
    $option_ids = array();
    foreach($phase_options_temp as $options) array_push($option_ids, $options->options_id);
    $bedrooms = ResiProjectOptions::optionwise_bedroom_details($option_ids, $phaseId);
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
    $smarty->assign("phaseObject", $current_phase[0]);
    $smarty->assign("bookingStatus", $current_phase[0]['BOOKING_STATUS_ID']);
    $smarty->assign("phasename", $current_phase[0]['PHASE_NAME']);
    $smarty->assign("launch_date", $current_phase[0]['LAUNCH_DATE']);
    $smarty->assign("completion_date", $current_phase[0]['COMPLETION_DATE']);
    $projectDetail = projectDetailById($projectId);
    $smarty->assign("pre_launch_date", $projectDetail[0]['PRE_LAUNCH_DATE']);
    $smarty->assign("remark", $current_phase[0]['REMARKS']);
    
    $towerDetail = fetch_towerDetails_for_phase($projectId, $phaseId);
    $smarty->assign("TowerDetails", $towerDetail);
    
    $phase_quantity = ProjectSupply::projectTypeGroupedQuantityForPhase($projectId, $phaseId);
    $phase_quantity_hash = array();
    foreach($phase_quantity as $quantity) $phase_quantity_hash[$quantity->unit_type] = $quantity->agg;
    $isLaunchUnitPhase = ProjectSupply::isLaunchUnitPhase($phaseId);
    $isInventoryCreated = ProjectSupply::isInventoryAdded($phaseId);
    $smarty->assign("isInventoryCreated", $isInventoryCreated);
    $smarty->assign("isLaunchUnitPhase", $isLaunchUnitPhase);
    $smarty->assign("FlatsQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['Apartment']));
    $smarty->assign("VillasQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['Villa']));
    $smarty->assign("PlotQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['Plot']));
    $smarty->assign("phase_quantity", $phase_quantity);
}
/* * ********************************** */
if (isset($_POST['btnSave'])) {
    $phasename = $_REQUEST['phaseName'];
    $launch_date = $_REQUEST['launch_date'];
    $completion_date = $_REQUEST['completion_date'];
   $pre_launch_date = $_REQUEST['pre_launch_date'];
    $towers = $_REQUEST['towers'];  // Array
    $remark = $_REQUEST['remark'];
    $isLaunchedUnitPhase = $_REQUEST['isLaunchUnitPhase'];

    // Assign vars for smarty
    $smarty->assign("phasename", $phasename);
    $smarty->assign("launch_date", $launch_date);
    $smarty->assign("completion_date", $completion_date);
    $smarty->assign("remark", $remark);
    $smarty->assign("pre_launch_date",$pre_launch_date);

    $PhaseExists = searchPhase($phaseDetail, $phasename);
    if ($PhaseExists != -1 && $phasename != $old_phase_name) {
        header("Location:phase_edit.php?projectId=" . $projectId . "&phaseId=" . $phaseId . "&error=1");
    } else {
        $error_msg = '';
        $smarty->assign("launch_date",$launch_date);
           // $smarty->assign("completion_date",$completion_date);
        if($launch_date == '0000-00-00')
            $launch_date = '';
        if($completion_date == '0000-00-00')
            $completion_date = '';
        if($pre_launch_date == '0000-00-00')
            $pre_launch_date = '';
        
        if( $launch_date != '' && $completion_date !='' ) {
            $retdt  = ((strtotime($completion_date)-strtotime($launch_date))/(60*60*24));
            if( $retdt <= 180 ) {
                $error_msg = 'Launch date should be atleast 6 month less than completion date';
            }
            
        }
        if( $pre_launch_date != '' && $launch_date !=''  && $phasename == 'No Phase' ) {
            $retdt  = ((strtotime($launch_date) - strtotime($pre_launch_date)) / (60*60*24));
            if( $retdt <= 0 ) {
                $error_msg = "Launch date to be always greater than Pre Launch date";
            }
        } 
        if( $launch_date != '') {
            $retdt  = ((strtotime($launch_date) - strtotime(date('Y-m-d'))) / (60*60*24));
            if( $retdt > 0 ) {
                    $error_msg = "Launch date should be less or equal to current date";
                }
          }
         
        if( $error_msg == '' ){
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
                global $projectId, $phaseId, $phasename, $launch_date, $remark, $towers;
                if($phaseId != '0'){
                    //          Updating existing phase
                    $phase = ResiProjectPhase::virtual_find($phaseId);
                    $phase->project_id = $projectId;
                    $phase->phase_name = $phasename;
                    $phase->launch_date = $launch_date;
                    $phase->remarks = $remark;
                    $phase->booking_status_id = (($_REQUEST['bookingStatus'] != -1) ? $_REQUEST['bookingStatus'] : null);
                    $phase->save();
                    if($phasename == 'No Phase') {
                        $qryUpdateProjectLaunchDate = "update resi_project 
                            set launch_date = '".$launch_date."'
                            where project_id = $projectId and version = 'Cms'";
                        mysql_query($qryUpdateProjectLaunchDate);
                    }
                    if ($_POST['project_type_id'] == '1' || $_POST['project_type_id'] == '3' || $_POST['project_type_id'] == '6') {
                        $phase->add_towers($towers);
                    }
                    if(isset($_POST['options'])){
                        $arr = $_POST['options'];
                        $arr = array_diff($arr, array(-1));

                        if(ProjectSupply::isInventoryAdded($projectId, $phaseId)){
                            $existingOptions = ProjectOptionsPhases::optionsForPhase($phaseId);
                            $removedOptions = array_diff($existingOptions, $arr);
                            if(empty($existingOptions) || !empty($removedOptions)){
                                header("Location:phase_edit.php?projectId=" . $projectId . "&phaseId=" . $phaseId . "&error=2");
                                exit;
                            }
                        }
                        $phase->reset_options($arr);
                    }
                }
            });
            #########################################
            // Phase Quantity
            if (sizeof($flats_config) > 0) {
                foreach ($flats_config as $key => $value) {
                    ProjectSupply::addEditSupply($projectId, $phaseId, 'apartment', $key, $value['supply'], $isLaunchedUnitPhase ? $value['launched'] : $value['supply']);
                }
            }
            if (sizeof($villas_config) > 0) {
                foreach ($villas_config as $key => $value) {
                    ProjectSupply::addEditSupply($projectId, $phaseId, 'villa', $key, $value['supply'], $isLaunchedUnitPhase ? $value['launched'] : $value['supply']);
                }
            }

           if ($_POST['plotvilla'] != '') {
                $supply = $_POST['supply'];
                if($supply == ''){
                  $qryPlotCase = "select ps.supply,ps.launched,l.status from resi_project_options rpo 
                    join listings l on(rpo.options_id = l.option_id and l.listing_category = 'Primary')
                    join project_supplies ps on (l.id = ps.listing_id and ps.version = 'Cms')
                    where rpo.option_type =  'plot' and l.phase_id = $phaseId order by l.id desc";
                    $resPlotCase = mysql_query($qryPlotCase);
                    echo mysql_num_rows($resPlotCase);
                    $dataPlotcase = mysql_fetch_assoc($resPlotCase);
                    if(($_POST['launched'] == '' || $_POST['launched'] == 0) && mysql_num_rows($resPlotCase)>0) {
                        $_POST['launched'] = $dataPlotcase['launched'];
                         $supply = $dataPlotcase['supply'];
                    }
                }
                ProjectSupply::addEditSupply($projectId, $phaseId, 'plot', 0, $supply, $_POST['launched']);
            }

            $towerDetail = fetch_towerDetails_for_phase($projectId, $phaseId);
            $smarty->assign("TowerDetails", $towerDetail);

            $phase_quantity = ProjectSupply::projectTypeGroupedQuantityForPhase($projectId, $phaseId);
            $phase_quantity_hash = array();
            foreach($phase_quantity as $quantity) $phase_quantity_hash[$quantity->unit_type] = $quantity->agg;
            $smarty->assign("FlatsQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['Apartment']));
            $smarty->assign("VillasQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['Villa']));
            $smarty->assign("PlotQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['Plot']));

            var_dump($phase_quantity_hash);

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
        else {
            $smarty->assign("error_msg",$error_msg);
            $smarty->assign("launch_date",$launch_date);
            $smarty->assign("completion_date",$completion_date);
        }
    }
} else if ($_POST['btnExit'] == "Exit") {
    if ($preview == 'true')
        header("Location:show_project_details.php?projectId=" . $projectId);
    else
        header("Location:ProjectList.php?projectId=" . $projectId);
}
/* * *********************************** */
?>
