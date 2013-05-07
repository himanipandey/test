<?php
    if(isset($_GET['error'])) {
        $smarty->assign("error_msg", "This phase already exists!");
    }

	$projectId				=	$_REQUEST['projectId'];
	$phaseId				=	$_REQUEST['phaseId'];
	$preview = $_REQUEST['preview'];
	$smarty->assign("preview", $preview);
	
	/*********code for delete phase**********/
	if(isset($_REQUEST['delete']))
	{
		 $qryDelete = "DELETE FROM ".RESI_PROJECT_PHASE." WHERE PHASE_ID = $phaseId";
		 $resDelete = mysql_query($qryDelete);
		 if($resDelete)
		 {
		 	audit_insert($phaseId,'delete','resi_project_phase',$projectId);
		 	$qrySupply = "SELECT  PROJ_SUPPLY_ID FROM ".RESI_PROJ_SUPPLY." WHERE PHASE_ID = $phaseId AND PROJECT_ID = $projectId";
		 	$resSupply = mysql_query($qrySupply);
		 	while($supply = mysql_fetch_assoc($resSupply))
		 	{
		 		$supplyId = $supply['PROJ_SUPPLY_ID'];
			 	$qrySupplyDel = "DELETE FROM ".RESI_PROJ_SUPPLY." WHERE PHASE_ID = $phaseId AND  PROJ_SUPPLY_ID = $supplyId.";
			 	$resSupplyDel = mysql_query($qrySupplyDel);
			 	if($resSupplyDel)
			 	{
			 		
			 		audit_insert($supplyId,'delete','resi_proj_supply',$projectId);
			 	}
		 	}
		 	if($preview == 'true')
		 		header("Location:show_project_details.php?projectId=".$projectId);
		 	else
		 		header("Location:ProjectList.php?projectId=".$projectId);
		 	
		 }
	}
	/*********end code for delete phase******/
    $smarty->assign("phaseId", $phaseId);
    $projectDetail			=	ProjectDetail($projectId);
	$smarty->assign("ProjectDetail", $projectDetail);

    $phaseDetail        =   fetch_phaseDetails($projectId);

    // Project Options and Bedroom Details
    $optionsDetails = ProjectOptionDetail($projectId);
    $smarty->assign("OptionsDetails", $optionsDetails);
    $bedroomDetails = ProjectBedroomDetail($projectId);
    $smarty->assign("BedroomDetails", $bedroomDetails);

    $phases = Array();
    $old_phase_name = '';

    foreach($phaseDetail as $k=>$val) {
        $p = Array();
        $p['id'] = $val['PHASE_ID'];
        $p['name'] = $val['PHASE_NAME'];
        if($val['PHASE_ID']==$phaseId) {
            $old_phase_name = $val['PHASE_NAME'];
        }
        array_push($phases, $p);
    }
    
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        $smarty->assign("phases", $phases);
        $current_phase =	phaseDetailsForId($phaseId);

        // Assign vars for smarty
        $smarty->assign("phasename", $current_phase[0]['PHASE_NAME']);
        $smarty->assign("launch_date", $current_phase[0]['LAUNCH_DATE']);
        $smarty->assign("completion_date", $current_phase[0]['COMPLETION_DATE']);
        $smarty->assign("remark", $current_phase[0]['REMARKS']);
        $smarty->assign("phaseLaunched", $current_phase[0]['LAUNCHED']);
        

        $towerDetail    =   fetch_towerDetails_for_phase($projectId);
        $smarty->assign("TowerDetails", $towerDetail);

        $phase_quantity    =   get_phase_quantity($phaseId);
        $smarty->assign("FlatsQuantity", explode_bedroom_quantity($phase_quantity['Apartment']));
        $smarty->assign("VillasQuantity", explode_bedroom_quantity($phase_quantity['Villa']));
        $smarty->assign("PlotQuantity", explode_bedroom_quantity($phase_quantity['Plot']));
    }
	/*************************************/
    if (isset($_POST['btnSave'])) {
        // Vars
        $phasename				=	$_REQUEST['phaseName'];
        $launch_date            =   $_REQUEST['launch_date'];
        $completion_date        =   $_REQUEST['completion_date'];
        $towers                 =   $_REQUEST['towers'];  // Array
        $remark                 =   $_REQUEST['remark'];
        if(isset($_REQUEST["phaseLaunched"]))
        {
        	$phaseLaunched = $_REQUEST["phaseLaunched"];
        }
        else
        {
        	$phaseLaunched = 0;
        }
        $phaseLaunched   =   $phaseLaunched;

        // Assign vars for smarty
        $smarty->assign("phasename", $phasename);
        $smarty->assign("launch_date", $launch_date);
        $smarty->assign("completion_date", $completion_date);
        $smarty->assign("remark", $remark);
        $smarty->assign("phaseLaunched", $phaseLaunched);

        $PhaseExists        =   searchPhase($phaseDetail,$phasename);
        if($PhaseExists!=-1 && $phasename!=$old_phase_name) {
            header("Location:phase_edit.php?projectId=".$projectId."&phaseId=".$phaseId."&error=true");
        }
        else {
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
                    mysql_query("SET AUTOCOMMIT=0");
                    mysql_query("START TRANSACTION");
                    $updated = update_phase($projectId,$phaseId,$phasename,$launch_date,$completion_date,$remark,$phaseLaunched);

                    if($_POST['project_type_id']=='1' || $_POST['project_type_id']=='3' || $_POST['project_type_id']=='6')
					{			
                    $return = update_towers_for_project_and_phase($projectId,$phaseId,$towers);
					}
                    
					if ($updated || $return) {
                        mysql_query("COMMIT");
                    } else {
                        echo 'Transaction failed..';
                        mysql_query("ROLLBACK");
                        die;
                    }
                    mysql_query("SET AUTOCOMMIT=1");
                    #########################################

                    // Phase Quantity
                    if(sizeof($flats_config)>0) {
                        foreach ($flats_config as $key => $value) {
                            set_phase_quantity($phaseId,'Apartment',$key,$value,$projectId);
                        }
                    }
                    if(sizeof($villas_config)>0) {
                        foreach ($villas_config as $key => $value) {
                            set_phase_quantity($phaseId,'Villa',$key,$value,$projectId);
                        }
                    }

					if($_POST['plotvilla'] != '')
					{
						$supply   =   $_POST['supply'];
						set_phase_quantity($phaseId,$_POST['plotvilla'],'0',$supply,$projectId);
					}

                    $towerDetail    =   fetch_towerDetails_for_phase($projectId);
                    $smarty->assign("TowerDetails", $towerDetail);

                    $phase_quantity    =   get_phase_quantity($phaseId);
                    $smarty->assign("FlatsQuantity", explode_bedroom_quantity($phase_quantity['Apartment']));
                    $smarty->assign("VillasQuantity", explode_bedroom_quantity($phase_quantity['Villa']));
                    $smarty->assign("PlotQuantity", explode_bedroom_quantity($phase_quantity['Plot']));

                    $phaseDetail = fetch_phaseDetails($projectId);
                    $phases = Array();
                    foreach($phaseDetail as $k=>$val) {
                        $p = Array();
                        $p['id'] = $val['PHASE_ID'];
                        $p['name'] = $val['PHASE_NAME'];
                        array_push($phases, $p);
                    }
                    $smarty->assign("phases", $phases);
                    header("Location:phase_edit.php?projectId=".$projectId);
        }
    }
    else if($_POST['btnExit'] == "Exit") {
       if($preview == 'true')
			header("Location:show_project_details.php?projectId=".$projectId);
		else
			header("Location:ProjectList.php?projectId=".$projectId);
    }
	/**************************************/
?>
