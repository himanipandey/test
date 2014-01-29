<?php
	$projectId				=	$_REQUEST['projectId'];
	$projectDetail			=	ProjectDetail($projectId);
	$smarty->assign("ProjectDetail", $projectDetail); 
	$smarty->assign("projectId", $projectId); 
	$towerDetail			=	fetch_towerDetails($projectId);

	$preview = $_REQUEST['preview'];
	$smarty->assign("preview", $preview);

	if($_REQUEST['towerId'] != '')
	{

		$towerDetailForId		=	towerDetailsForId($_GET['towerId']);
		//echo "<pre>";
		//print_r($towerDetailForId);
		//echo "</pre>";
		$smarty->assign("towerId", $_GET['towerId']);
		$smarty->assign("no_of_floors", $towerDetailForId[0]['NO_OF_FLOORS']);
		$smarty->assign("stilt", $towerDetailForId[0]['STILT']);
		$smarty->assign("no_of_flats_per_floor", $towerDetailForId[0]['NO_OF_FLATS']);
		$smarty->assign("towerface", $towerDetailForId[0]['TOWER_FACING_DIRECTION']);
		$smarty->assign("completion_date", $towerDetailForId[0]['ACTUAL_COMPLETION_DATE']);
		$smarty->assign("remark", $towerDetailForId[0]['REMARKS']);
		$smarty->assign("edit", $towerDetailForId[0]['NO_OF_FLOORS']);

		$arrAudit   = AuditTblDataByTblName('resi_project_tower_details',$projectId);
		$smarty->assign("arrAudit", $arrAudit); 
	}
	$smarty->assign("towerDetail", $towerDetail);
	
	$edit = $_GET['edit'];
	$smarty->assign("edit", $edit); 
	/*************************************/

	if (isset($_POST['btnSave']))
	{
		$TowerId				=	$_REQUEST['TowerId'];
		$no_of_floors			=	$_REQUEST['FloorId'];
        $stilt					=	$_REQUEST['stilt'];
		$no_of_flats_per_floor	=	$_REQUEST['AvilFlatId'];
		$towerface				=	$_REQUEST['face'];
        $completion_date		=	$_REQUEST['eff_date_to'];
		$remark					=	$_REQUEST['remark'];
		$edit					=	$_REQUEST['edit'];

		$smarty->assign("towername", $phasename);
		$smarty->assign("no_of_floors", $no_of_floors);
		$smarty->assign("stilt", $stilt);
		$smarty->assign("no_of_flats_per_floor", $no_of_flats_per_floor);
		$smarty->assign("towerface", $towerface);
		$smarty->assign("completion_date", $completion_date);
		$smarty->assign("remark", $remark);
		$smarty->assign("edit", $edit);

		
		$blank_chk = 0;
		if(
				($no_of_floors != $towerDetailForId[0]['NO_OF_FLOORS'])
			OR
				($stilt != $towerDetailForId[0]['STILT'])
			OR
				($no_of_flats_per_floor != $towerDetailForId[0]['NO_OF_FLATS'])
			OR
				($towerface != $towerDetailForId[0]['TOWER_FACING_DIRECTION'])
			OR
				($completion_date != $towerDetailForId[0]['ACTUAL_COMPLETION_DATE'])
				
		 )
		{
			$blank_chk = 1;
		}

		if($blank_chk == 1)
		{
			update_towerDetail($projectId,$TowerId,$no_of_floors,$stilt,$no_of_flats_per_floor,$towerface,$completion_date,$remark);
			
				
			if($_POST['btnSave'] == 'Submit')
			{
				if($preview == 'true')
					header("Location:show_project_details.php?projectId=".$projectId);
				else
					header("Location:ProjectList.php?projectId=".$projectId);
			}
			else
			{
				header("Location:tower_detail_edit.php?projectId=".$projectId."&edit=edit");
			}


			$smarty->assign("errorMsg", $errorMsg); 
		}
    }
	else if($_POST['btnExit'] == "Exit")
	{
		 if($preview == 'true')
			header("Location:show_project_details.php?projectId=".$projectId);
		else
			header("Location:ProjectList.php?projectId=".$projectId);
	}
	
	/**************************************/
	

?>


