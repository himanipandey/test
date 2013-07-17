<?php
    $effectiveDt  = '';
	$projectId			=	$_REQUEST['projectId'];
	$projectDetail		=	ProjectDetail($projectId);
	$smarty->assign("ProjectDetail", $projectDetail);
	$smarty->assign("projectId", $projectId);
	$source_of_information=fetch_sourceofInformation();
	$smarty->assign("source_of_information",$source_of_information);
	$dt = date("Y-m-d");
	$smarty->assign("newDate",$dt);
	
	$preview = $_REQUEST['preview'];
	$smarty->assign("preview", $preview);
	
	$fetch_projectOptions=fetch_projectOptions($projectId);
	$smarty->assign("fetch_projectOptions",$fetch_projectOptions);
	
	$phaseProject = fetch_phaseDetails($projectId);
	$smarty->assign("phaseProject",$phaseProject);

	/*******new code start here************/
	$supplyAllArray = array();
	$qry = "SELECT p.PHASE_NAME, a.*
				FROM resi_proj_supply a
				JOIN (SELECT PROJECT_ID, PHASE_ID, PROJECT_TYPE, NO_OF_BEDROOMS, MAX(PROJ_SUPPLY_ID) AS LATEST_PROJ_SUPPLY_ID
				         FROM resi_proj_supply
				         WHERE PROJECT_ID = $projectId
				         GROUP BY PROJECT_ID, PHASE_ID, PROJECT_TYPE, NO_OF_BEDROOMS) b
				ON (a.PROJ_SUPPLY_ID = b.LATEST_PROJ_SUPPLY_ID)
				LEFT JOIN resi_project_phase p
				       on (p.PHASE_ID = a.PHASE_ID)";
	
	$res = mysql_query($qry) or die(mysql_error());
	$arrPhaseCount = array();
	$arrPhaseTypeCount = array();
	$submittedDate = '';
	if(mysql_num_rows($res) > 0)
	{
		while($data = mysql_fetch_assoc($res))
		{
			if($data['PHASE_NAME'] == '')
				$data['PHASE_NAME'] = 'noPhase';
			$supplyAllArray[$data['PHASE_NAME']][$data['PROJECT_TYPE']][] = $data;
			$arrPhaseCount[$data['PHASE_NAME']][] = $data['PROJECT_TYPE'];
			$arrPhaseTypeCount[$data['PHASE_NAME']][$data['PROJECT_TYPE']][] = '';
			$submittedDate = $data['SUBMITTED_DATE'];
		}
	}
	$smarty->assign("submittedDate",$submittedDate);
	$smarty->assign("arrPhaseCount",$arrPhaseCount);
	$smarty->assign("arrPhaseTypeCount",$arrPhaseTypeCount);
	$smarty->assign("supplyAllArray",$supplyAllArray);

	$arrAudit   = getLastUpdatedTime($projectId);
	$smarty->assign("arrAudit", $arrAudit);
	/*******end new code*******************/
	
	if (isset($_POST['btnSave']))
    {		
    	$deleteRow = array();
    	$flg = 0;
    	foreach($_REQUEST['noOfFlats'] as $key=>$val)
    	{
    		$supplyId				=	$_REQUEST['supplyId'][$key];
			$no_of_flats			=	$_REQUEST['noOfFlats'][$key];
			$no_of_flats_old		=	$_REQUEST['old_noOfFlats'][$key];
			$isFlats				=   $_REQUEST['isFlats'][$key];
			$isFlats_old			=   $_REQUEST['old_isFlats'][$key];
			$AvilFlatId				=	$_REQUEST['AvilFlatId'][$key];
			$AvilFlatId_old			=	$_REQUEST['old_AvilFlatId'][$key];
			$avilflats      		=   $_REQUEST['avilflats'][$key];
			$avilflats_old      	=   $_REQUEST['old_avilflats'][$key];
	        $edit_reson				=	$_REQUEST['edit_reason'][$key];
	        $edit_reson_old			=	$_REQUEST['old_edit_reason'][$key];
			$source_of_information	=	$_REQUEST['soi'][$key];
			$source_of_information_old	=	$_REQUEST['old_soi'][$key];
			
			$effectiveDt =  ($_REQUEST['eff_date_to']!='') ? $_REQUEST['eff_date_to'] : date('Y-m-d');
			
			$configs		=	$_REQUEST['configs'][$key];
			$phaseId		=	$_REQUEST['phaseId'][$key];
			$projectType	=	$_REQUEST['projectType'][$key];
			$blank_chk = 0;
			
			if(
					($no_of_flats != $no_of_flats_old)
				OR
					($isFlats != $isFlats_old)
				OR
					($AvilFlatId != $AvilFlatId_old)
				OR
					($avilflats != $avilflats_old)
				OR
					($source_of_information	 != $source_of_information)
				OR
					($effectiveDt	 != $_REQUEST['old_date'])	
			 )
			{
				$returnChk = insert_supplyandinventoryDetail($projectId,$configs,$no_of_flats,$isFlats,$AvilFlatId,$avilflats,$edit_reson,$source_of_information,$effectiveDt,$projectType,$phaseId);	
				$flg = 1;
			}
			
			$delete = "delete_".($key+1);
			if($_REQUEST[$delete] == on)
			{
				if($projectType != '')
					$pType = " PROJECT_TYPE = '$projectType'";
				else 
					$pType = "(PROJECT_TYPE IS NULL || PROJECT_TYPE = '')";
				
				   $del = "DELETE FROM ".RESI_PROJ_SUPPLY." 
						WHERE 
								PROJECT_ID = $projectId 
							AND
							 	PHASE_ID   = $phaseId
							AND
								NO_OF_BEDROOMS = $configs
							AND
								$pType";
				$res = mysql_query($del) or die(mysql_error());
				$flg = 1;
			}	
    	}
    	if($_REQUEST['newBedId'] != '')
    	{
    		$split = explode("-",$_REQUEST['newBedId']);
    		$effectiveDt =  ($_REQUEST['eff_date_to']!='') ? $_REQUEST['eff_date_to'] : date('Y-m-d');
    		$returnChk = insert_supplyandinventoryDetail($projectId,$split[1],$_REQUEST['newNoOfFlats'],$_REQUEST['newIsFlats'],$_REQUEST['newAvailFlats'],$_REQUEST['newAvailIsFlats'],$_REQUEST['newEditReason'],$_REQUEST['newSoi'],$effectiveDt,$split[0],$_REQUEST['newPhase']);
    		$flg = 1;
    	}
		if($flg == 1)
		{
			$returnAvailability = computeAvailability($projectId);
			$updateProject = updateAvailability($projectId,$returnAvailability);
			if($_POST['btnSave'] == 'Add More')
			{
				header("Location:add_supply_inventory.php?projectId=".$projectId."&preview=".$preview);
			}
			else
			{
				if($preview == 'true')
					header("Location:show_project_details.php?projectId=".$projectId);
				else
					header("Location:ProjectList.php?projectId=".$projectId);
			}
		}
				
    }
	else if(isset($_POST['btnExit']))
	{
		if($preview == 'true')
			header("Location:show_project_details.php?projectId=".$projectId);
		else
			header("Location:ProjectList.php?projectId=".$projectId);
	}

	/**************************************/
$smarty->assign('eff_date_to',$effectiveDt);


?>