<?php
	$projectId		=	$_REQUEST['projectId'];
	$effectiveDt	=  ($_REQUEST['eff_date']!='') ? $_REQUEST['eff_date'] : date('Y-m-d');
	$fetch_projectDetail	=	ProjectDetail($projectId);
	$smarty->assign("fetch_projectDetail", $fetch_projectDetail); 

	$fetch_towerDetails=fetch_towerDetails($projectId);
	$smarty->assign("fetch_towerDetails",$fetch_towerDetails);

	$arr_RoomNot = "";
	foreach($fetch_towerDetails as $key=>$val)
	{
		$qry = "SELECT TOWER_CONST_STATUS_ID FROM ".RESI_PROJ_TOWER_CONSTRUCTION_STATUS." WHERE TOWER_ID = '".$val['TOWER_ID']."'";
		$res = mysql_query($qry) or die(mysql_error());	
		if(mysql_num_rows($res)>0)
			$arr_RoomNot.= $val['TOWER_ID'].",";
	}
	$smarty->assign("arr_RoomNot",$arr_RoomNot);
	
	if($_GET['towerId'] != '')
	{
		$tower_detail = towerDetail($_GET['towerId']);
		//echo "<pre>";
		//print_r($tower_detail);
		//echo "</pre>";
		if(count($tower_detail) == 0)
			$tower_detail[0]['TOWER_ID'] = $_GET['towerId'];
		$arrAudit   = AuditTblDataByTblName('resi_proj_tower_construction_status',$projectId);
		$smarty->assign("tower_detail", $tower_detail); 
		$smarty->assign("arrAudit", $arrAudit);
		
	}

	if (isset($_POST['btnSave']))
	{
		$towerId				=	$_REQUEST['tower_name_select'];
		$no_of_floors_completed	=	$_REQUEST['completed_floors'];
		$remark					=	$_REQUEST['remark'];
		$expected_delivery_date	=	$_REQUEST['eff_date_to'];

		$blank_chk = 0;
		if(
				($no_of_floors_completed != $tower_detail[0]['NO_OF_FLOORS_COMPLETED'])
			OR
				($expected_delivery_date != $tower_detail[0]['EXPECTED_DELIVERY_DATE'])
			OR
				($expected_delivery_date != $tower_detail[0]['SUBMITTED_DATE'])
				
		 )
		{
			$blank_chk = 1;
		}
		if($blank_chk == 1)
		{
			insert_towerconstructionStatus($towerId,$no_of_floors_completed,$remark,$expected_delivery_date,$effectiveDt,$projectId);
			
			if($_POST['btnSave'] == 'Submit')
				header("Location:ProjectList.php?projectId=".$projectId);
			else
				header("Location:add_tower_construction_status.php?projectId=".$projectId);
		}
			
    }
	else if($_POST['btnExit'] == "Exit")
	{
		  header("Location:ProjectList.php?projectId=".$projectId);
	}
	
	/**************************************/
	$smarty->assign('eff_date',$effectiveDt);

?>