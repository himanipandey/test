<?php
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("modelsConfig.php");
include("includes/configs/configs.php");

//newproject = 2, updation cycle = 3 , secondary price cycle =4 :stages

// audit1 = 4

$projectID = $_POST['projectID'];
$projectStageId = $_POST['stageID'];
$currPhaseID = $_POST['phaseID'];
$projectPhaseId = 1;

if($projectStageId == 4) //if secondary price cycle
	$projectStageId  = 3; // updation cycle
	
if($projectStageId == 2) // if new project
	$projectPhaseId = 3;  //callcenter
	
	
if($projectStageId == 3) //updation cycle
	$projectPhaseId = 1; //data collection

//fetch stage name
$sql_stage_name = mysql_fetch_object(mysql_query("SELECT name from master_project_phases where id='".$projectPhaseId."'"));
	
//values on Callcenter
$sql_resi_callcenter_result = mysql_query("SELECT trp._t_transaction_id,trp.PRE_LAUNCH_DATE,trp.LAUNCH_DATE,trp.PROMISED_COMPLETION_DATE,trp.EXPECTED_SUPPLY_DATE,trp.PROJECT_STATUS_ID,trp.created_at,trp.updated_at,
ps.display_name,
mbs.display_name as booking_status
FROM _t_resi_project trp 
LEFT JOIN project_status_master ps on trp.project_status_id = ps.id
LEFT JOIN resi_project_phase  rpp on trp.project_id = rpp.project_id  and rpp.version = 'Cms'
LEFT JOIN master_booking_statuses mbs ON rpp.booking_status_id = mbs.id
WHERE trp.PROJECT_ID='$projectID'  AND (trp.PROJECT_STAGE_ID = '$projectStageId' && trp.PROJECT_PHASE_ID = '$projectPhaseId') AND rpp.PHASE_TYPE = 'Logical'  AND trp.version = 'Cms' ORDER BY trp._t_transaction_id DESC LIMIT 2") or die(mysql_error());
//print mysql_num_rows($sql_resi_callcenter_result); die;
if($sql_resi_callcenter_result){

	while($sql_resi_callcenter = mysql_fetch_object($sql_resi_callcenter_result)){
		
		$call_PrelaunchDate[] = ($sql_resi_callcenter->PRE_LAUNCH_DATE)?$sql_resi_callcenter->PRE_LAUNCH_DATE:"-";
		$call_LaunchDate[] = ($sql_resi_callcenter->LAUNCH_DATE)?$sql_resi_callcenter->LAUNCH_DATE:"-";
		$call_CompletionDate[] = ($sql_resi_callcenter->PROMISED_COMPLETION_DATE)?$sql_resi_callcenter->PROMISED_COMPLETION_DATE:"-";
		$call_SupplyDate[] = ($sql_resi_callcenter->EXPECTED_SUPPLY_DATE)?$sql_resi_callcenter->EXPECTED_SUPPLY_DATE:"-";
		$call_ProjectStatus[] = ($sql_resi_callcenter->display_name)?$sql_resi_callcenter->display_name:"-";
		
		//print $sql_resi_callcenter->updated_at." - ".$projectStageId." - ".$projectPhaseId;

		//booking status Audit---
		$sql_call_bk = mysql_query("SELECT trpp._t_transaction_id,mbs.display_name FROM _t_resi_project_phase trpp 
		LEFT JOIN master_booking_statuses mbs ON trpp.booking_status_id = mbs.id
		WHERE trpp.updated_at <= '$sql_resi_callcenter->updated_at' AND project_id='$projectID'  AND PHASE_TYPE = 'Logical'  AND version = 'Cms' ORDER BY trpp._t_transaction_id DESC LIMIT 1") or die(mysql_error());
		
		if($sql_call_bk){
			$sql_call_bk = mysql_fetch_object($sql_call_bk);
			$call_BookingStatus[] = ($sql_call_bk->display_name)?$sql_call_bk->display_name:"-";
		}
	}

}

$html = "";

$html = "<table width='600px'><tbody>
		<tr  height='30px;' class='headingrowcolor'>
			<th nowrap='nowrap' align='center' class='whiteTxt' width=30%>&nbsp;</th>
			<th nowrap='nowrap' align='center' class='whiteTxt' width=35%>$sql_stage_name->name Stage Values-1</th>
			<th nowrap='nowrap' align='center' class='whiteTxt' width=35%>$sql_stage_name->name Values-2</th>			
		</tr>
		<tr>
			<td a width=30%><b>PRE LAUNCH DATE</b></td>
			<td align='center' width=35%>$call_PrelaunchDate[0]</td>
			<td align='center' width=35%>$call_PrelaunchDate[1]</td>
		</tr>
		<tr>
			<td  width=30%><b>LAUNCH DATE</b></td>
			<td align='center' width=35%>$call_LaunchDate[0]</td>
			<td align='center' width=35%>$call_LaunchDate[1]</td>
		</tr>
		<tr>
			<td  width=30%><b>COMPLETION DATE</b></td>
			<td align='center' width=35%>$call_CompletionDate[0]</td>
			<td align='center' width=35%>$call_CompletionDate[1]</td>
		</tr>
		<tr>
			<td width=30%><b>EXPECTED SUPPLY DATE</b></td>
			<td align='center' width=35%>$call_SupplyDate[0]</td>
			<td align='center' width=35%>$call_SupplyDate[1]</td>
		</tr>
		<tr>
			<td  width=30%><b>PROJECT STATUS</b></td>
			<td align='center' width=35%>$call_ProjectStatus[0]</td>
			<td align='center' width=35%>$call_ProjectStatus[1]</td>
		</tr>
		<tr>
			<td  width=30%><b>BOOKING STATUS</b></td>
			<td align='center' width=35%>$call_BookingStatus[0]</td>
			<td align='center' width=35%>$call_BookingStatus[1]</td>
		</tr>
		</tbody></table>";

	print $html;
	
?>


y
