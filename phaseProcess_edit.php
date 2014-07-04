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
//echo "<pre>";print_r($_REQUEST);die("inner");
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
$projectStatus = ResiProject::projectStatusMaster();
$smarty->assign("projectStatus",$projectStatus);

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

/* * *******code for delete phase********* */
if (isset($_REQUEST['delete'])) {
	$del_flag = 1;
	ResiProjectPhase::transaction(function(){
		global $del_flag, $phaseId, $projectId, $error_msg, $projectDetail;
		//print "<pre>".print_r($projectDetail,1)."</pre>";
		#validate phase deletion
		if($projectDetail['LAUNCH_DATE'] == '0000-00-00')
		  $projectDetail['LAUNCH_DATE'] = '';
		if($projectDetail['PRE_LAUNCH_DATE'] == '0000-00-00')
		  $projectDetail['PRE_LAUNCH_DATE'] = '';
		if($projectDetail['PROMISED_COMPLETION_DATE'] == '0000-00-00')
		  $projectDetail['PROMISED_COMPLETION_DATE'] = '';		
		$comp_eff_date = costructionDetail($projectId,$phaseId,false);	
				
		if($comp_eff_date['COMPLETION_DATE'] == '0000-00-00')
		  $comp_eff_date['COMPLETION_DATE'] = '';
		  
		$project_status = fetch_project_status($projectId,'',$phaseId,false); 
		if( $project_status == PRE_LAUNCHED_ID_8 && $projectDetail['LAUNCH_DATE'] != '') {
		  $error_msg = "Project Status would be Pre Launched after deletion but Launch date should be blank/zero in case of Pre Launched Project.";	 
		}elseif( $project_status == PRE_LAUNCHED_ID_8 && $projectDetail['PRE_LAUNCH_DATE'] == '') {
		  $error_msg = "Project Status would be Pre Launched after deletion but Pre Launched Date is blank!";	 
		}elseif(($project_status == OCCUPIED_ID_3 || $project_status == READY_FOR_POSSESSION_ID_4) && $comp_eff_date['COMPLETION_DATE'] != ''){
		  $yearExp = explode("-",$comp_eff_date['COMPLETION_DATE']);
		  if( $yearExp[0] == date("Y") ) {
			if( intval($yearExp[1]) > intval(date("m"))) {
			  $error_msg = "Project Status would be Completed after deletion but Completion date cannot be greater than the current month in case of Completed Project";
			}    
		  } 
		  else if (intval($yearExp[0]) > intval(date("Y")) ) {
			$error_msg = "Project Status would be Completed after deletion but Completion date cannot be greater than the current month in case of Completed Project";
		  }			
		}elseif($project_status == UNDER_CONSTRUCTION_ID_1 && $comp_eff_date['COMPLETION_DATE'] != ''){
		  $yearExp = explode("-",$comp_eff_date['COMPLETION_DATE']);
		  if( $yearExp[0] == date("Y") ) {
			if( intval($yearExp[1]) < intval(date("m"))) {
			  $error_msg = "Project Status would be Completed after deletion but  Completion date cannot be less than the current month in case of Under construction Project";
			}    
		  } 
		  else if (intval($yearExp[0]) < intval(date("Y")) ) {
			$error_msg = " Project Status would be Completed after deletion but Completion date cannot be less than the current month in case of Under construction Project";
		  }			
		}
		if($error_msg == ''){
		  try{
			$all_lst_ids = array();
			$all_price_ids = array();
			$all_supply_ids = array();
			$all_avail_ids = array();
			$all_sec_price_ids = array();
			$all_comp_ids = array();
			
			#listing
				$all_lst = Listings::find("all",array("conditions"=>array("phase_id = ?",$phaseId)));
				foreach($all_lst as $key=>$lst){
				  $all_lst_ids[] = $lst->id; 
				}
				$all_lst_ids = implode(",",$all_lst_ids);				
						
				#prices
				if($all_lst_ids){
				  $all_prices = ListingPrices::find("all", array("conditions"=>array("listing_id in ($all_lst_ids)")));
				  foreach($all_prices as $key=>$lstp){
				    $all_price_ids[] = $lstp->id; 
				  }
				  $all_price_ids = implode(",",$all_price_ids);				 
			    }
							
				#supplies
				$all_supplies = mysql_query("SELECT * FROM `project_supplies` WHERE listing_id in ($all_lst_ids)");
				if($all_supplies){
				  while($sup = mysql_fetch_object($all_supplies)){
					$all_supply_ids[] = $sup->id;
				  }
				  $all_supply_ids = implode(",",$all_supply_ids);				 
				}
							
				#inventories
				if($all_supply_ids){
				  $all_avails = ProjectAvailability::find("all",array("conditions"=>array("project_supply_id in ($all_supply_ids)")));
				  foreach($all_avails as $key=>$avails){
					$all_avail_ids[] = $avails->id;	
				  }
				  $all_avail_ids = implode(",",$all_avail_ids);				 
				}
							
				#secondry_price
				$all_sec_prices = ProjectSecondaryPrice::find("all",array("conditions"=>array("phase_id=?",$phaseId)));
				foreach($all_sec_prices as $key=>$secp){
				  $all_sec_price_ids[] = $secp->id;
				}
				$all_sec_price_ids = implode(",",$all_sec_price_ids);
										
				#completion_history
				$all_comp = ResiProjExpectedCompletion::find("all",array("conditions"=>array("phase_id=?",$phaseId)));
				foreach($all_comp as $key=>$comps){
					$all_comp_ids[] = $comps->expected_completion_id;
				}
				$all_comp_ids = implode(",",$all_comp_ids);
							
			#dependent data deletion
				if($all_avail_ids)
				  ProjectAvailability::delete_all(array('conditions'=>array("id in ($all_avail_ids)")));
				if($all_supply_ids)
				  ProjectSupply::delete_all(array('conditions'=>array("id in ($all_supply_ids)")));
				if($all_price_ids)
				  ListingPrices::delete_all(array('conditions'=>array("id in ($all_price_ids)")));
				
				if($all_sec_price_ids)
				  ProjectSecondaryPrice::delete_all(array('conditions'=>array("id in ($all_sec_price_ids)")));
				if($all_comp_ids)
				  ResiProjExpectedCompletion::delete_all(array('conditions'=>array("expected_completion_id in ($all_comp_ids)")));	
				  
				if($all_lst_ids)
				  Listings::delete_all(array('conditions'=>array("id in ($all_lst_ids)")));
				  
				mysql_query("DELETE FROM `phase_tower_mappings` WHERE phase_id='$phaseId'");
				mysql_query("DELETE FROM `d_inventory_prices` WHERE phase_id='$phaseId'");
				mysql_query("DELETE FROM `d_inventory_prices_tmp` WHERE phase_id='$phaseId'");	
				
				ResiProjectPhase::delete_all(array('conditions'=>array("phase_id = ?",$phaseId)));	
												
		}catch(Exeception $e){
		  $del_flag = 0;			  
		}
	  }else
	    $del_flag = 0;
	});	
	if($del_flag){
		#dependent values updation			
		$costDetailLatest = costructionDetail($projectId);
		$qry = "UPDATE resi_project 
					set 
					   PROMISED_COMPLETION_DATE = '".$costDetailLatest['COMPLETION_DATE']."' 
				   where PROJECT_ID = $projectId and version = 'Cms'";
		mysql_query($qry) OR DIE(mysql_error());
		
		projectStatusUpdate($projectId); //update project status	
		updateD_Availablitiy($projectId); // update D_availability 	
				
	  if ($preview == 'true')
		header("Location:show_project_details.php?projectId=" . $projectId);
	  else
		header("Location:ProjectList.php?projectId=" . $projectId);	
	}else{
	  if($error_msg == '')	
	    $error_msg = "Error in deletion of Phase depenedent data. Phase deletion failed!";			
	  $smarty->assign("error_msg",$error_msg);	
	}	
}
/********end code for delete phase***** */
/************/

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

if ($_SERVER['REQUEST_METHOD']) {
    $current_phase = phaseDetailsForId($phaseId);
    // Assign vars for smarty
    $smarty->assign("phaseObject", $current_phase[0]);
    $smarty->assign("bookingStatus", $current_phase[0]['BOOKING_STATUS_ID']);
    $smarty->assign("construction_status", $current_phase[0]['construction_status']);
    $smarty->assign("phasename", $current_phase[0]['PHASE_NAME']);
    $smarty->assign("phase_pre_launch_date", $current_phase[0]['PRE_LAUNCH_DATE']);
    $smarty->assign("launch_date", $current_phase[0]['LAUNCH_DATE']);
    $smarty->assign("completion_date", $current_phase[0]['COMPLETION_DATE']);
    $projectDetail = projectDetailById($projectId);
    $smarty->assign("pre_launch_date", $projectDetail[0]['PRE_LAUNCH_DATE']);
    $smarty->assign("remark", $current_phase[0]['REMARKS']);
    $smarty->assign("sold_out_date", $current_phase[0]['sold_out_date']);
    
    $towerDetail = fetch_towerDetails_for_phase($projectId, $phaseId);
    $smarty->assign("TowerDetails", $towerDetail);
    
    $phase_quantity = ProjectSupply::projectTypeGroupedQuantityForPhase($projectId, $phaseId);
    $phase_quantity_hash = array();
    foreach($phase_quantity as $quantity) $phase_quantity_hash[$quantity->unit_type] = $quantity->agg;
    //echo "<pre>";print_r($phase_quantity);
    $isLaunchUnitPhase = ProjectSupply::isLaunchUnitPhase($phaseId);
    $isInventoryCreated = ProjectSupply::isInventoryAdded($phaseId);
    $smarty->assign("isInventoryCreated", $isInventoryCreated);
    $smarty->assign("isLaunchUnitPhase", $isLaunchUnitPhase);
    $smarty->assign("FlatsQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['Apartment']));
    $smarty->assign("VillasQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['Villa']));
    $smarty->assign("PlotQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['Plot']));
    $smarty->assign("ShopQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['Shop']));
    $smarty->assign("OfficeQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['Office']));
    $smarty->assign("OtherQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['Other']));
    $smarty->assign("phase_quantity", $phase_quantity);
}
/* * ********************************** */
if (isset($_POST['btnSave'])) {
    $phasename = $_REQUEST['phaseName'];
    $launch_date = $_REQUEST['launch_date'];
    $completion_date = $_REQUEST['completion_date'];
    $construction_status = $_REQUEST['construction_status'];
    $pre_launch_date = $_REQUEST['pre_launch_date'];    
    $phase_pre_launch_date = $_REQUEST['phase_pre_launch_date'];
    $towers = $_REQUEST['towers'];  // Array
    $remark = $_REQUEST['remark'];
    $isLaunchedUnitPhase = $_REQUEST['isLaunchUnitPhase'];
    $sold_out_date = $_REQUEST['sold_out_date']; 
   
    // Assign vars for smarty
    $smarty->assign("phasename", $phasename);
    $smarty->assign("launch_date", $launch_date);
    $smarty->assign("completion_date", $completion_date);
    $smarty->assign("construction_status", $construction_status);
    $smarty->assign("remark", $remark);
    $smarty->assign("pre_launch_date",$pre_launch_date);
     $smarty->assign("sold_out_date",$sold_out_date);

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
        if($phase_pre_launch_date == '0000-00-00')
            $phase_pre_launch_date = '';
        if($sold_out_date == '0000-00-00')
            $sold_out_date = '';
        if($construction_status == ""){
		  	 $error_msg = 'Construction Status is required!';
		}
        if( $launch_date != '' && $completion_date !='' ) {
            $retdt  = ((strtotime($completion_date)-strtotime($launch_date))/(60*60*24));
            if( $retdt <= 180 ) {
                $error_msg = 'Launch date should be atleast 6 month less than completion date';
            }            
        }
        if( $pre_launch_date != '' && $launch_date !=''  && $phasename == 'No Phase' ) { // TO BE DONE to COMPARE PROJECT PRELAUNCH DATE at RUN TIME
            $retdt  = ((strtotime($launch_date) - strtotime($pre_launch_date)) / (60*60*24));
            if( $retdt <= 0 ) {
                $error_msg = "Launch date to be always greater than Pre Launch date for Project";
            }
            
        } 
        if( $phase_pre_launch_date != '' && $launch_date !='') {
            $retdt  = ((strtotime($launch_date) - strtotime($phase_pre_launch_date)) / (60*60*24));
            if( $retdt <= 0 ) {
                $error_msg = "Launch date to be always greater than Pre Launch date for Phase";
            }
            
        } 
        if( $launch_date != '' && $_REQUEST['phaseName'] == 'No Phase') {
            $retdt  = ((strtotime($launch_date) - strtotime(date('Y-m-d'))) / (60*60*24));
            if( $retdt > 0 ) {
                    $error_msg = "Launch date should be less or equal to current date";
                }
           /* if($pre_launch_date == '' && projectStageName($projectId)=="UpdationCycle" && (checkAvailablityDate($projectId, $launch_date) || checkListingPricesDate($projectId, $launch_date))) {
                $error_msg  .= " Inventory or Prices with effective date before {$launch_date} are present. So can not change the Launch Date.";
            }*/
          }
         if($sold_out_date != ''){
	    $retdt  = ((strtotime($sold_out_date) - strtotime($launch_date)) / (60*60*24));
            if( $retdt <= 0 || $launch_date=='') {
                $error_msg = "Sold out date to be always greater than Launch date";
            } 			 		 
        }
         
            // Flats Config
            $flats_config = array();
            foreach ($_REQUEST as $key => $value) {
                if (substr($key, 0, 9) == "flat_bed_") {
                    $beds = substr($key, 9);
                    $flats_config[$beds] = $value;
                    if($value['supply'] < $value['launched'])
			$error_msg = "Supply Unit must be greater than Launched Unit.";
                    if(!ProjectSupply::checkAvailability($projectId, $phaseId, 'apartment', $beds, $value['supply'], $isLaunchedUnitPhase ? $value['launched'] : $value['supply']))
                        $error_msg = "Launched Unit must be greater than Availability.";
                }
            }
            // Villas Config
            $villas_config = array();
            foreach ($_REQUEST as $key => $value) {
                if (substr($key, 0, 10) == "villa_bed_") {
                    $beds = substr($key, 10);
                    $villas_config[$beds] = $value;
                    if($value['supply'] < $value['launched'])
						$error_msg = "Supply Unit must be greater than Launched Unit.";
                    if(!ProjectSupply::checkAvailability($projectId, $phaseId, 'apartment', $beds, $value['supply'], $isLaunchedUnitPhase ? $value['launched'] : $value['supply']))
						$error_msg = "Launched Unit must be greater than Availability.";
                }
            }
            
         if ($_POST['plotvilla'] != '' && !isset($_POST['options'])) { 
			 if($_POST['supply'] < $_POST['launched'])
						$error_msg = "Supply Unit must be greater than Launched Unit.";
            if(!ProjectSupply::checkAvailability($projectId, $phaseId, 'plot', 0, $_POST['supply'], $isLaunchedUnitPhase ? $_POST['launched'] : $_POST['supply']))
                   $error_msg = "Launched Unit must be greater than Availability.";
		 }
		 
		 ////phase level check regarding status
		 $project_status = fetch_project_status($projectId,$construction_status,$phaseId);                    
        if($projectDetail[0]['LAUNCH_DATE'] == '0000-00-00')
		  $projectDetail[0]['LAUNCH_DATE'] = '';
		if($projectDetail[0]['PRE_LAUNCH_DATE'] == '0000-00-00')
		  $projectDetail[0]['PRE_LAUNCH_DATE'] = '';
		if($projectDetail[0]['PROMISED_COMPLETION_DATE'] == '0000-00-00')
		  $projectDetail[0]['PROMISED_COMPLETION_DATE'] = '';
	    if( $construction_status == UNDER_CONSTRUCTION_ID_1 ) { 
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
			}elseif(($project_status == OCCUPIED_ID_3 || $project_status == READY_FOR_POSSESSION_ID_4) && $projectDetail[0]['PROMISED_COMPLETION_DATE'] != ''){
				$yearExp = explode("-",$projectDetail[0]['PROMISED_COMPLETION_DATE']);
				if( $yearExp[0] == date("Y") ) {
					if( intval($yearExp[1]) > intval(date("m"))) {
					  $error_msg = "Completion date cannot be greater than the current month in case of Completed Project";
					}    
				} 
				else if (intval($yearExp[0]) > intval(date("Y")) ) {
					$error_msg = "Completion date cannot be greater than the current month in case of Completed Project";
				}			
			}elseif($project_status == UNDER_CONSTRUCTION_ID_1 && $projectDetail[0]['PROMISED_COMPLETION_DATE'] != ''){
				$yearExp = explode("-",$projectDetail[0]['PROMISED_COMPLETION_DATE']);
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
	}
        if ($_POST['Shop'] != '' && !isset($_POST['options'])) { 
			 if($_POST['supply_shop'] < $_POST['launched_shop'])
						$error_msg = "Supply Unit must be greater than Launched Unit.";
            if(!ProjectSupply::checkAvailability($projectId, $phaseId, 'Shop', 0, $_POST['supply_shop'], $isLaunchedUnitPhase ? $_POST['launched_shop'] : $_POST['supply_shop']))
                   $error_msg = "Launched Unit must be greater than Availability.";
	}
        if ($_POST['Office'] != '' && !isset($_POST['options'])) { 
           // echo "<pre>";print_r($_POST);//die;
			 if($_POST['supply_office'] < $_POST['launched_office'])
						$error_msg = "Supply Unit must be greater than Launched Unit.";
            if(!ProjectSupply::checkAvailability($projectId, $phaseId, 'Office', 0, $_POST['supply_office'], $isLaunchedUnitPhase ? $_POST['launched_office'] : $_POST['supply_office']))
                   $error_msg = "Launched Unit must be greater than Availability.";
	}
        if ($_POST['Other'] != '' && !isset($_POST['options'])) { 
			 if($_POST['supply'] < $_POST['launched'])
						$error_msg = "Supply Unit must be greater than Launched Unit.";
            if(!ProjectSupply::checkAvailability($projectId, $phaseId, 'Other', 0, $_POST['supply'], $isLaunchedUnitPhase ? $_POST['launched'] : $_POST['supply']))
                   $error_msg = "Launched Unit must be greater than Availability.";
	}
		//echo "<pre>";print_r($error_msg); 	
         if( $error_msg == '' ){
            // Update
            ############## Transaction ##############
            ResiProjectPhase::transaction(function(){
                global $projectId, $phaseId, $phasename, $launch_date, $remark, $towers, $sold_out_date, $construction_status,$phase_pre_launch_date;
                if($phaseId != '0'){
                    //          Updating existing phase
                    $phase = ResiProjectPhase::virtual_find($phaseId);
                    $phase->project_id = $projectId;
                    $phase->phase_name = $phasename;
                    $phase->pre_launch_date = $phase_pre_launch_date;
                    $phase->launch_date = $launch_date;
                    $phase->remarks = $remark;
                    $phase->sold_out_date = $sold_out_date;
                    $phase->construction_status = $construction_status;
                    $phase->save();
                    if($phasename == 'No Phase') {
                        $qryUpdateProjectLaunchDate = "update resi_project 
                            set launch_date = '".$launch_date."'
                            where project_id = $projectId and version = 'Cms'";
                        mysql_query($qryUpdateProjectLaunchDate);
                    }
                    if ($_POST['project_type_id'] == APARTMENTS || $_POST['project_type_id'] == VILLA_APARTMENTS || $_POST['project_type_id'] == PLOT_APARTMENTS
                           || $_POST['project_type_id'] == COMMERCIAL || $_POST['project_type_id'] == SHOP || $_POST['project_type_id'] == OFFICE
                           || $_POST['project_type_id'] == SHOP_OFFICE || $_POST['project_type_id'] == OTHER) {
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
             projectPreLaunchDateUpdate($projectId); //updating preLaunch date
             projectStatusUpdate($projectId); //update project status
             updateD_Availablitiy($projectId); // update D_availability  
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
//echo "<pre>";print_r($_REQUEST);die;
           if ($_POST['plotvilla'] != '') {
                $supply = $_POST['supply'];
                if($supply == ''){
                  $qryPlotCase = "select ps.supply,ps.launched,l.status from resi_project_options rpo 
                    join listings l on(rpo.options_id = l.option_id and l.listing_category = 'Primary')
                    join project_supplies ps on (l.id = ps.listing_id and ps.version = 'Cms')
                    where rpo.option_type =  'plot' and l.phase_id = $phaseId order by l.id desc";
                    $resPlotCase = mysql_query($qryPlotCase);
                    $dataPlotcase = mysql_fetch_assoc($resPlotCase);
                    if(($_POST['launched'] == '' || $_POST['launched'] == 0) && mysql_num_rows($resPlotCase)>0) {
                        $_POST['launched'] = $dataPlotcase['launched'];
                         $supply = $dataPlotcase['supply'];
                    }
                }
                if($supply != null && isset($_POST['supply']) && isset($_POST['launched']))
					ProjectSupply::addEditSupply($projectId, $phaseId, 'plot', 0, $supply, $isLaunchedUnitPhase ? $_POST['launched'] : $_POST['supply']);
            }

            if ($_POST['Shop'] != '') {
                $supply = $_POST['supply_shop'];
                if($supply == ''){
                  $qryShopCase = "select ps.supply,ps.launched,l.status from resi_project_options rpo 
                    join listings l on(rpo.options_id = l.option_id and l.listing_category = 'Primary')
                    join project_supplies ps on (l.id = ps.listing_id and ps.version = 'Cms')
                    where rpo.option_type =  'Shop' and l.phase_id = $phaseId order by l.id desc";
                    $resShopCase = mysql_query($qryShopCase);
                    $dataShopcase = mysql_fetch_assoc($resShopCase);
                    if(($_POST['launched_shop'] == '' || $_POST['launched_shop'] == 0) && mysql_num_rows($resShopCase)>0) {
                        $_POST['launched_shop'] = $dataShopcase['launched'];
                         $supply = $dataShopcase['supply'];
                    }
                }
                if($supply != null && isset($_POST['supply_shop']) && isset($_POST['launched_shop']))
		   ProjectSupply::addEditSupply($projectId, $phaseId, 'Shop', 0, $supply, $isLaunchedUnitPhase ? $_POST['launched_shop'] : $_POST['supply_shop']);
            }
            
            if ($_POST['Office'] != '') {
                $supply = $_POST['supply_office'];
                if($supply == ''){
                  $qryOfficeCase = "select ps.supply,ps.launched,l.status from resi_project_options rpo 
                    join listings l on(rpo.options_id = l.option_id and l.listing_category = 'Primary')
                    join project_supplies ps on (l.id = ps.listing_id and ps.version = 'Cms')
                    where rpo.option_type =  'Office' and l.phase_id = $phaseId order by l.id desc";
                    $resOfficeCase = mysql_query($qryOfficeCase);
                    $dataOfficecase = mysql_fetch_assoc($resOfficeCase);
                    if(($_POST['launched_office'] == '' || $_POST['launched_office'] == 0) && mysql_num_rows($resOfficeCase)>0) {
                        $_POST['launched_office'] = $dataPlotcase['launched'];
                         $supply = $dataPlotcase['supply'];
                    }
                }
                if($supply != null && isset($_POST['supply_office']) && isset($_POST['launched_office']))
					ProjectSupply::addEditSupply($projectId, $phaseId, 'Office', 0, $supply, $isLaunchedUnitPhase ? $_POST['launched_office'] : $_POST['supply_office']);
            }
            
            if ($_POST['Other'] != '') {
                $supply = $_POST['supply'];
                if($supply == ''){
                  $qryOtherCase = "select ps.supply,ps.launched,l.status from resi_project_options rpo 
                    join listings l on(rpo.options_id = l.option_id and l.listing_category = 'Primary')
                    join project_supplies ps on (l.id = ps.listing_id and ps.version = 'Cms')
                    where rpo.option_type =  'Other' and l.phase_id = $phaseId order by l.id desc";
                    $resOtherCase = mysql_query($qryOtherCase);
                    $dataOthercase = mysql_fetch_assoc($resOtherCase);
                    if(($_POST['launched'] == '' || $_POST['launched'] == 0) && mysql_num_rows($resOtherCase)>0) {
                        $_POST['launched'] = $dataPlotcase['launched'];
                         $supply = $dataPlotcase['supply'];
                    }
                }
                if($supply != null && isset($_POST['supply']) && isset($_POST['launched']))
					ProjectSupply::addEditSupply($projectId, $phaseId, 'Other', 0, $supply, $isLaunchedUnitPhase ? $_POST['launched'] : $_POST['supply']);
            }
            
            $towerDetail = fetch_towerDetails_for_phase($projectId, $phaseId);
            $smarty->assign("TowerDetails", $towerDetail);

            $phase_quantity = ProjectSupply::projectTypeGroupedQuantityForPhase($projectId, $phaseId);
            $phase_quantity_hash = array();
            foreach($phase_quantity as $quantity) $phase_quantity_hash[$quantity->unit_type] = $quantity->agg;
            $smarty->assign("FlatsQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['Apartment']));
            $smarty->assign("VillasQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['Villa']));
            $smarty->assign("PlotQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['Plot']));
            $smarty->assign("ShopQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['Shop']));
            $smarty->assign("OfficeQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['Office']));
            $smarty->assign("OtherQuantity", explodeBedroomSupplyLaunched($phase_quantity_hash['Other']));

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
} else if ($_POST['btnExit'] == "Exit") {
    if ($preview == 'true')
        header("Location:show_project_details.php?projectId=" . $projectId);
    else
        header("Location:ProjectList.php?projectId=" . $projectId);
}
?>
