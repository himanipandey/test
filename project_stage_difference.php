<?php
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("modelsConfig.php");
include("includes/configs/configs.php");

//newproject = 2, updation cycle = 3 , secondary price cycle =4

// audit1 = 4

$projectID = $_POST['projectID'];
$projectStageId = $_POST['stageID'];
$projectPhaseId = 1;

if($projectStageId == 4)
	$projectStageId  = 3;
	
if($projectStageId == 2)
	$projectPhaseId = 3;
	
	
if($projectStageId == 3)
	$projectPhaseId = 1;


$audit_PrelaunchDate = '-';
$audit_LaunchDate = '-';
$audit_CompletionDate = '-';
$audit_SupplyDate = '-';
$audit_ProjectStatus = '-';
$audit_BookingStatus = '-';

$call_PrelaunchDate = '-';
$call_LaunchDate = '-';
$call_CompletionDate = '-';
$call_SupplyDate = '-';
$call_ProjectStatus = '-';
$call_BookingStatus = '-';
	
//values on audit1	
$sql_resi_audit = mysql_query("SELECT trp._t_transaction_id,trp.PRE_LAUNCH_DATE,trp.LAUNCH_DATE,trp.PROMISED_COMPLETION_DATE,trp.EXPECTED_SUPPLY_DATE,trp.PROJECT_STATUS_ID,trp.created_at,trp.updated_at,
ps.display_name
FROM _t_resi_project trp 
LEFT JOIN project_status_master ps ON trp.project_status_id = ps.id
WHERE trp.PROJECT_ID='$projectID' AND (trp.PROJECT_STAGE_ID = '$projectStageId' && trp.PROJECT_PHASE_ID = 4) ORDER BY trp._t_transaction_id DESC LIMIT 1") or die(mysql_error());


if(mysql_num_rows($sql_resi_audit)){

	$sql_resi_audit = mysql_fetch_object($sql_resi_audit);
	
	$audit_PrelaunchDate = $sql_resi_audit->PRE_LAUNCH_DATE;
	$audit_LaunchDate = $sql_resi_audit->LAUNCH_DATE;
	$audit_CompletionDate = $sql_resi_audit->PROMISED_COMPLETION_DATE;
	$audit_SupplyDate = $sql_resi_audit->EXPECTED_SUPPLY_DATE;
	$audit_ProjectStatus = $sql_resi_audit->display_name;
	

	//booking status Audit---
	$sql_audit_bk = mysql_query("SELECT trpp._t_transaction_id,mbs.display_name FROM _t_resi_project_phase trpp 
	LEFT JOIN master_booking_statuses mbs ON trpp.booking_status_id = mbs.id
	WHERE trpp.updated_at <= '$sql_resi_audit->updated_at' AND project_id='$projectID'  AND PHASE_TYPE = 'Logical' ORDER BY trpp._t_transaction_id DESC LIMIT 1") or die(mysql_error());
	
	if($sql_audit_bk){
		$sql_audit_bk = mysql_fetch_object($sql_audit_bk);
		$audit_BookingStatus = $sql_audit_bk->display_name;
	}
	
}


//print "<pre>".print_r($sql_audit_sizes,1)."</pre>";

//values on Callcenter
$sql_resi_callcenter =mysql_query("SELECT trp._t_transaction_id,trp.PRE_LAUNCH_DATE,trp.LAUNCH_DATE,trp.PROMISED_COMPLETION_DATE,trp.EXPECTED_SUPPLY_DATE,trp.PROJECT_STATUS_ID,trp.created_at,trp.updated_at,
ps.display_name,
mbs.display_name as booking_status
FROM _t_resi_project trp 
LEFT JOIN project_status_master ps on trp.project_status_id = ps.id
LEFT JOIN resi_project_phase  rpp on trp.project_id = rpp.project_id  
LEFT JOIN master_booking_statuses mbs ON rpp.booking_status_id = mbs.id
WHERE trp.PROJECT_ID='$projectID'  AND (trp.PROJECT_STAGE_ID = '$projectStageId' && trp.PROJECT_PHASE_ID = '$projectPhaseId') AND rpp.PHASE_TYPE = 'Logical' ORDER BY trp._t_transaction_id DESC LIMIT 1") or die(mysql_error());

if($sql_resi_callcenter){

	$sql_resi_callcenter = mysql_fetch_object($sql_resi_callcenter);
	
	$call_PrelaunchDate = $sql_resi_callcenter->PRE_LAUNCH_DATE;
	$call_LaunchDate = $sql_resi_callcenter->LAUNCH_DATE;
	$call_CompletionDate = $sql_resi_callcenter->PROMISED_COMPLETION_DATE;
	$call_SupplyDate = $sql_resi_callcenter->EXPECTED_SUPPLY_DATE;
	$call_ProjectStatus = $sql_resi_callcenter->display_name;
	

	//booking status Audit---
	$sql_call_bk = mysql_query("SELECT trpp._t_transaction_id,mbs.display_name FROM _t_resi_project_phase trpp 
	LEFT JOIN master_booking_statuses mbs ON trpp.booking_status_id = mbs.id
	WHERE trpp.updated_at <= '$sql_resi_callcenter->updated_at' AND project_id='$projectID'  AND PHASE_TYPE = 'Logical' ORDER BY trpp._t_transaction_id DESC LIMIT 1") or die(mysql_error());
	
	if($sql_call_bk){
		$sql_call_bk = mysql_fetch_object($sql_call_bk);
		$call_BookingStatus = $sql_call_bk->display_name;
	}

}

$html = "";

$html = "<table width='600px'><tbody>
		<tr  height='30px;' class='headingrowcolor'>
			<th nowrap='nowrap' align='center' class='whiteTxt' width=30%>&nbsp;</th>
			<th nowrap='nowrap' align='center' class='whiteTxt' width=35%>Callcenter Stage</th>
			<th nowrap='nowrap' align='center' class='whiteTxt' width=35%>Audit-1 Stage</th>			
		</tr>
		<tr>
			<td a width=30%><b>PRE LAUNCH DATE</b></td>
			<td align='center' width=35%>$call_PrelaunchDate</td>
			<td align='center' width=35%>$audit_PrelaunchDate</td>
		</tr>
		<tr>
			<td  width=30%><b>LAUNCH DATE</b></td>
			<td align='center' width=35%>$call_LaunchDate</td>
			<td align='center' width=35%>$audit_LaunchDate</td>
		</tr>
		<tr>
			<td  width=30%><b>COMPLETION DATE</b></td>
			<td align='center' width=35%>$call_CompletionDate</td>
			<td align='center' width=35%>$audit_CompletionDate</td>
		</tr>
		<tr>
			<td width=30%><b>EXPECTED SUPPLY DATE</b></td>
			<td align='center' width=35%>$call_SupplyDate</td>
			<td align='center' width=35%>$audit_SupplyDate</td>
		</tr>
		<tr>
			<td  width=30%><b>PROJECT STATUS</b></td>
			<td align='center' width=35%>$call_ProjectStatus</td>
			<td align='center' width=35%>$audit_ProjectStatus</td>
		</tr>
		<tr>
			<td  width=30%><b>BOOKING STATUS</b></td>
			<td align='center' width=35%>$call_BookingStatus</td>
			<td align='center' width=35%>$audit_BookingStatus</td>
		</tr>
		</tbody></table>";



/*stdClass Object
(
    [_t_transaction_id] => 174836
    [PRE_LAUNCH_DATE] => 2013-12-18
    [LAUNCH_DATE] => 2013-12-19
    [PROMISED_COMPLETION_DATE] => 2014-01-01
    [EXPECTED_SUPPLY_DATE] => 2014-01-31
    [PROJECT_STATUS_ID] => 1
    [created_at] => 2013-12-20 11:59:43
    [updated_at] => 2014-01-15 17:47:09
    [display_name] => Under Construction
    [booking_status_id] => 
)*/

	print $html;
	
?>


