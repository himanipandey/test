<?php
	
	$projectId		=	$_REQUEST['projectId'];
	$effectiveDt	=  ($_REQUEST['eff_date']!='') ? $_REQUEST['eff_date'] : date('Y-m-d');
	$fetch_projectDetail	=	ProjectDetail($projectId);
	$smarty->assign("fetch_projectDetail", $fetch_projectDetail); 

	$costDetail	=	costructionDetail($projectId);
	$smarty->assign("costDetail", $costDetail); 

	$arrAudit   = AuditTblDataByTblName('resi_proj_supply',$projectId);
	$smarty->assign("arrAudit", $arrAudit); 

	if(isset($_POST['btnSave']))
	{
		$remark					=	$_REQUEST['remark'];
		$expected_delivery_date	=	$_REQUEST['eff_date_to'];
		
		if($expected_delivery_date != $costDetail[0]['EXPECTED_COMPLETION_DATE'])
		{
			$qry	=	"INSERT INTO ".RESI_PROJ_EXPECTED_COMPLETION."
			
						SET	
							PROJECT_ID				=	'".$projectId."',
							EXPECTED_COMPLETION_DATE=	'".$expected_delivery_date."',
							REMARK					=	'".$remark."',
							SUBMITTED_DATE			=	'".$effectiveDt."'";
			$res	=	mysql_query($qry) OR DIE(MYSQL_ERROR());
			$lastId	=	mysql_insert_id();
                        $qry = "UPDATE resi_project set PROMISED_COMPLETION_DATE = '$expected_delivery_date' where PROJECT_ID = $projectId";
                        mysql_query($qry) OR DIE(MYSQL_ERROR());
			audit_insert($lastId,'insert','resi_proj_expected_completion',$projectId);

			header("Location:ProjectList.php?projectId=".$projectId);
		}
    }
	else if($_POST['btnExit'] == "Exit")
	{
		  header("Location:ProjectList.php?projectId=".$projectId);
	}
	
	/**************************************/
	
$smarty->assign('eff_date',$effectiveDt);
?>