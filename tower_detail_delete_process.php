<?php
	$RoomCategoryArr	=	RoomCategoryList();
	$projectId			=	$_REQUEST['projectId'];
	$projectDetail1		=	ProjectDetail($projectId);

	$towerDetail		=	fetch_towerDetails($projectId); //fetch all tower details

	$arrAudit   = AuditTblDataByTblName('resi_project_tower_details',$projectId); //last updated date 
	$smarty->assign("arrAudit", $arrAudit); 

	if($_GET['totRow'] != '')
	{
		//selected row display
		$totRow			=	$_GET['totRow'];
	}
	
	else if(count($towerDetail)>0)
	{
		$totRow			=	count($towerDetail);
	}
	else
	{
		//default display rows
		$totRow			=	5;

	}			
	$smarty->assign("TotRow", $totRow);

	$smarty->assign("towerDetail", $towerDetail);
	$smarty->assign("projectDetail", $projectDetail1);
	$smarty->assign("projectId", $projectId);
	
	$fetch_towerName = fetch_towerName($projectId);
	if ($_POST['btnSave'] == "Save")
	{

		$flgDel = 0;
		$flgUp = 0;
		$flgInsert = 0;
		$towerIdList	=	'';
		foreach($_REQUEST['tower_name'] as $key=>$val)
		{

			$tower_nanme 		= 	$val;
			$no_of_floor 		= 	$_REQUEST['no_of_floor'][$key];
			$tower_id 			= 	$_REQUEST['tower_id'][$key];
			$no_of_flats 		= 	$_REQUEST['no_of_flats'][$key];
			$remark 			= 	$_REQUEST['remark'][$key];
			$face 				= 	$_REQUEST['face'][$key];
			$stilt 				= 	$_REQUEST['stilt'][$key];
			$eff_date 			= 	$_REQUEST['eff_date'][$key];

			$tower_name_old		= 	$_REQUEST['tower_name_old'][$key];
			$no_of_floor_old 	= 	$_REQUEST['no_of_floor_old'][$key];
			$no_of_flats_old 	= 	$_REQUEST['no_of_flats_old'][$key];
			$remark_old 		= 	$_REQUEST['remark_old'][$key];
			$face_old 			= 	$_REQUEST['face_old'][$key];
			$stilt_old 			= 	$_REQUEST['stilt_old'][$key];
			$eff_date_old 		= 	$_REQUEST['eff_date_old'][$key];

			if(($val != '') AND (!array_key_exists($tower_id,$fetch_towerName)))
			{
				//code for insert tower detail if not exists
				$insertlist.=	 "('','$projectId', '$tower_nanme', '$no_of_floor', '$remark', '$stilt', '$no_of_flats', '$face','$eff_date'),";
				$flgInsert  = 1;
			}
			else
			{
				//code for update tower detail
				if(
					trim($tower_nanme) 	    != $tower_name_old 
					|| trim($no_of_floor)   != $no_of_floor_old 
					|| trim($no_of_flats)   != $no_of_flats_old 
					|| trim($remark) 		!= $remark_old 
					|| trim($face)   		!= $face_old
					|| trim($stilt) 	    != $stilt_old
					|| trim($eff_date) 	    != $eff_date_old 
				 )
				{
					$qryUp	=	"UPDATE ".RESI_PROJECT_TOWER_DETAILS."
								
								SET	
									TOWER_NAME      		=	'".$tower_nanme."',
									NO_OF_FLOORS      		=	'".$no_of_floor."',
									REMARKS      			=	'".$remark."',
									STILT      				=	'".$stilt."',
									NO_OF_FLATS      		=	'".$no_of_flats."',
									TOWER_FACING_DIRECTION  =	'".$face."',
									ACTUAL_COMPLETION_DATE  =	'".$eff_date."'
								WHERE
									PROJECT_ID = '".$projectId."'
								AND
									TOWER_ID   = '".$tower_id."'";
					$resUp	=	mysql_query($qryUp) or die(mysql_error());
					if($resUp)
					{
						audit_insert($tower_id,'update','resi_project_tower_details',$projectId);
						$flgUp  = 1;
					}

				}		
				//end code for update tower detail

				//code for delete tower detail
				$deleteKey = "delete_".($key+1);
				if($_REQUEST[$deleteKey] == on)
				{

					//$towerId = array_search($towernanme, $fetch_towerName); // find array key;
					if(array_key_exists($tower_id, $fetch_towerName))
					{
						$towerIdList .= $tower_id.',';
						$flgDel = 1;
					}
				}
			}
		}
		$finalFlg = 0;
		if($flgInsert == 1)
		{

			$insertlist = substr($insertlist,0,-1);
			$insert     = insert_towerDetail($insertlist,$projectId); //Insertt tower detail call
			if($insert)
				$finalFlg = 1;
		}

		if($flgDel == 1)
		{
			$towerIdList= substr($towerIdList,0,-1);
			$deleteReturn = deleteTowerDetail($projectId,$towerIdList); //delete tower detail call
			if($deleteReturn)
				$finalFlg = 1;
		}

		if($finalFlg == 1 || $flgUp == 1)	
			header("Location:ProjectList.php?projectId=".$projectId);

	}
    else if($_POST['btnExit'] == "Exit")
    {
          if($preview == 'true')
			header("Location:show_project_details.php?projectId=".$projectId);
		else
			header("Location:ProjectList.php?projectId=".$projectId);
    }
    

    $smarty->assign("ErrorMsg1", $ErrorMsg1);
    $smarty->assign("projecteror", $projecteror);

?>