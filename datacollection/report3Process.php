<?php

$accessDataCollection = '';
if( $executivePerformanceAuth == false)
   $accessDataCollection = "No Access";
$smarty->assign("accessDataCollection",$accessDataCollection);

require_once "$_SERVER[DOCUMENT_ROOT]/datacollection/functions.php";

if($_POST['submit']==='Get'){
    $_SESSION[$_SERVER['PHP_SELF']]['dateFrom'] = $_POST['dateFrom'];
    $_SESSION[$_SERVER['PHP_SELF']]['dateTo'] = $_POST['dateTo'];
}

if(!empty($_SESSION[$_SERVER['PHP_SELF']]['dateFrom'])){
    $dateFrom = $_SESSION[$_SERVER['PHP_SELF']]['dateFrom'];
    $dateTo = $_SESSION[$_SERVER['PHP_SELF']]['dateTo'];  
    $days = (strtotime($dateTo) - strtotime($dateFrom) + 86400) / (60 * 60 * 24);
    $all_users = getAllUsers();
    $all_assigned_projects = getAllAssignedProject($all_users, $dateFrom, $dateTo);
   //print "<pre>".print_r($all_assigned_projects,1)."</pre>";
    $all_done_projects = getAllDoneProject($all_users, $dateFrom, $dateTo);
   // print "<pre>".print_r($all_done_projects,1)."</pre>"; 
    $all_revert_count = getRevertCountForExecs($dateFrom, $dateTo);
    //print "<pre>".print_r($all_revert_count,1)."</pre>";
    $all_revert_count_hash = array();
    foreach($all_revert_count as $v){
		$all_revert_count_hash[$v['ADMIN_ID']] = $v['REVERT_COUNT'];
	}
    //print "<pre>".print_r($all_revert_count_hash,1)."</pre>";
     $displayData = prepareDisplayData($all_users, $all_assigned_projects, $all_done_projects, $all_revert_count_hash, $days);
}

$smarty->assign("dateFrom", $dateFrom);
$smarty->assign("dateTo", $dateTo);
$smarty->assign("displayData", $displayData);



function prepareDisplayData($all_users, $all_assigned_projects, $all_done_projects, $all_revert_count_hash, $days){	
    $result = array();
    $leads_work = array();
    $excs_work = array();
    $grand_work = array();
    foreach($all_users['all_exec_under_leads'] as $leadId=>$excs) {	
	  $done_projects = 0;
	  $ass_projects = 0;	
	  $revert_count = 0;  
	  foreach($excs as $k => $exc){		
        $excs_work[$leadId][$exc['admin_id']]['username'] = $all_assigned_projects['ass_proj_to_exec'][$leadId][$exc['admin_id']]['username'];
	    $excs_work[$leadId][$exc['admin_id']]['done'] = $all_done_projects['done_proj_to_exec'][$leadId][$exc['admin_id']]['done'];	
	    $excs_work[$leadId][$exc['admin_id']]['ass'] = $all_assigned_projects['ass_proj_to_exec'][$leadId][$exc['admin_id']]['ass'];
	    $excs_work[$leadId][$exc['admin_id']]['revert_count'] = $all_revert_count_hash[$exc['admin_id']];
	    $excs_work[$leadId][$exc['admin_id']]['reversal'] = round(($all_revert_count_hash[$exc['admin_id']]/$all_done_projects['done_proj_to_exec'][$leadId][$exc['admin_id']]['done'])*100,1);
	    $excs_work[$leadId][$exc['admin_id']]['proj_per_day'] = round(($excs_work[$leadId][$exc['admin_id']]['done']/$days),2);
	    if($days < 7)
	      $excs_work[$leadId][$exc['admin_id']]['proj_per_week'] = 'N/A';
	    else
	      $excs_work[$leadId][$exc['admin_id']]['proj_per_week'] = round(($excs_work[$leadId][$exc['admin_id']]['done']/($days/7)),2);
	    if($days < 30)
	      $excs_work[$leadId][$exc['admin_id']]['proj_per_month'] = 'N/A';
	    else
	      $excs_work[$leadId][$exc['admin_id']]['proj_per_month'] = round(($excs_work[$leadId][$exc['admin_id']]['done']/($days/30)),2);
	    if($days < 90)
	      $excs_work[$leadId][$exc['admin_id']]['proj_per_qtr'] = 'N/A';
	    else
	      $excs_work[$leadId][$exc['admin_id']]['proj_per_qtr'] = round(($excs_work[$leadId][$exc['admin_id']]['done']/($days/90)),2);
	    
	    $done_projects += $excs_work[$leadId][$exc['admin_id']]['done'];
	    $ass_projects  += $excs_work[$leadId][$exc['admin_id']]['ass'];
	    $revert_count += $excs_work[$leadId][$exc['admin_id']]['revert_count'];
      }
      $leads_work[$leadId]['username'] = $all_assigned_projects['ass_proj_to_leads'][$leadId]['username'];
	  $leads_work[$leadId]['done'] = $done_projects;	
	  $leads_work[$leadId]['ass'] = $ass_projects;
	  $leads_work[$leadId]['revert_count'] = $revert_count;
	  $leads_work[$leadId]['reversal'] = round(($revert_count/$done_projects)*100,1);
	  $leads_work[$leadId]['proj_per_day'] = round(($done_projects/$days),2);
	  if($days < 7)
	    $leads_work[$leadId]['proj_per_week'] = 'N/A';
	  else
	    $leads_work[$leadId]['proj_per_week'] = round(($leads_work[$leadId]['done']/($days/7)),2);
	  if($days < 30)
	    $leads_work[$leadId]['proj_per_month'] = 'N/A';
	  else
	    $leads_work[$leadId]['proj_per_month'] = round(($leads_work[$leadId]['done']/($days/30)),2);
	  if($days < 90)
	    $leads_work[$leadId]['proj_per_qtr'] = 'N/A';
	  else
	    $leads_work[$leadId]['proj_per_qtr'] = round(($leads_work[$leadId]['done']/($days/90)),2);
	}
	$result['leads_work'] = $leads_work;
	$result['excs_work'] = $excs_work;
	
	$grand_work['username'] = 'Grand Total';
	foreach($result['leads_work'] as $k => $v){
      $grand_work['done'] = $grand_work['done'] + $v['done'];
      $grand_work['ass'] = $grand_work['ass'] + $v['ass'];
      $grand_work['revert_count'] = $grand_work['revert_count'] + $v['revert_count'];       	
	}
	
	$grand_work['reversal'] = round(($grand_work['revert_count']/$grand_work['done'])*100,1);
	  $grand_work['proj_per_day'] = round(($grand_work['done']/$days),2);
	if($days < 7)
	  $grand_work['proj_per_week'] = 'N/A';
	else
	  $grand_work['proj_per_week'] = round(($grand_work['done']/($days/7)),2);
	if($days < 30)
	  $grand_work['proj_per_month'] = 'N/A';
	else
	  $grand_work['proj_per_month'] = round(($grand_work['done']/($days/30)),2);
	if($days < 90)
	  $grand_work['proj_per_qtr'] = 'N/A';
	else
	  $grand_work['proj_per_qtr'] = round(($grand_work['done']/($days/90)),2);
	  
	$result['grand_work'] = $grand_work;  
		
    return $result;
}

function getAllDoneProject($all_users, $dateFrom, $dateTo){
  $all_projects_done = array();
  $arrLeadProjectDone = array();
  $arrExecProjectDone = array();	
 
  foreach($all_users['all_exec_under_leads'] as $leadId=>$excs) {
	foreach($excs as $k => $exc){		
	  $qryExec = "select count(pa.movement_history_id) count from project_assignment pa
                  join proptiger_admin pa1 on pa.assigned_to = pa1.adminid     
                  where pa.status = 'done' and pa.assigned_to = ".$exc['admin_id']." and DATE(pa.CREATION_TIME) between '$dateFrom' and '$dateTo'";
      $resExec = mysql_query($qryExec) or die(mysql_query());
      $dataExec = mysql_fetch_assoc($resExec);
      $arrExecProjectDone[$leadId][$exc['admin_id']]['done'] = $dataExec['count'];
      $arrExecProjectDone[$leadId][$exc['admin_id']]['username'] = $exc['username'];
	} 
  }
  foreach($all_users['all_leads'] as $k=>$v) {
    $qryExec = "select count(pa.movement_history_id) count from project_assignment pa
    join proptiger_admin pa1 on pa.assigned_to = pa1.adminid     
    where pa.status = 'done' and pa.assigned_to = ".$v['adminid']." and DATE(pa.CREATION_TIME) between '$dateFrom' and '$dateTo'";
    $resExec = mysql_query($qryExec) or die(mysql_query());
    $dataExec = mysql_fetch_assoc($resExec);
    $arrLeadProjectDone[$v['adminid']]['done'] = $dataExec['count'];
    $arrLeadProjectDone[$v['adminid']]['username'] = $v['username'];
  }	
  $all_projects_done['done_proj_to_leads'] = $arrLeadProjectDone;
  $all_projects_done['done_proj_to_exec'] = $arrExecProjectDone;
  return $all_projects_done;
}
function getAllAssignedProject($all_users, $dateFrom, $dateTo){
  $all_projects_ass = array();
  $arrLeadProjectAss = array();
  $arrExecProjectAss = array();	
 
  foreach($all_users['all_exec_under_leads'] as $leadId=>$excs) {
	foreach($excs as $k => $exc){		
	  $qryExec = "select count(pa.movement_history_id) count from project_assignment pa
                  join proptiger_admin pa1 on pa.assigned_to = pa1.adminid     
                  where pa.assigned_to = ".$exc['admin_id']." and DATE(pa.CREATION_TIME) between '$dateFrom' and '$dateTo'";
      $resExec = mysql_query($qryExec) or die(mysql_query());
      $dataExec = mysql_fetch_assoc($resExec);
      $arrExecProjectAss[$leadId][$exc['admin_id']]['ass'] = $dataExec['count'];
      $arrExecProjectAss[$leadId][$exc['admin_id']]['username'] = $exc['username'];
	} 
  }
  foreach($all_users['all_leads'] as $k=>$v) {
    $qryExec = "select count(pa.movement_history_id) count from project_assignment pa
    join proptiger_admin pa1 on pa.assigned_to = pa1.adminid     
    where pa.assigned_to = ".$v['adminid']." and DATE(pa.CREATION_TIME) between '$dateFrom' and '$dateTo'";
    $resExec = mysql_query($qryExec) or die(mysql_query());
    $dataExec = mysql_fetch_assoc($resExec);
    $arrLeadProjectAss[$v['adminid']]['ass'] = $dataExec['count'];
    $arrLeadProjectAss[$v['adminid']]['username'] = $v['username'];
  }	
  $all_projects_ass['ass_proj_to_leads'] = $arrLeadProjectAss;
  $all_projects_ass['ass_proj_to_exec'] = $arrExecProjectAss;
  return $all_projects_ass;
}
function getAllUsers(){
  $qryLead = "select adminid,username,role from proptiger_admin where department = 'SURVEY'";
  $redLead = mysql_query($qryLead) or die(mysql_error());
  $arrLead = array();
  $arrExecutive = array();
  $arrExecLead = array();
  while($dataLead = mysql_fetch_assoc($redLead)) {
    if($dataLead['role'] == 'teamLeader'){
        $arrLead[] = $dataLead;
        $qryExeclead = "select distinct(a.admin_id),b.username from proptiger_admin_city a 
                        join proptiger_admin b on a.admin_id = b.adminid
                        where a.city_id in (SELECT city_id FROM cms.proptiger_admin_city 
                                where admin_id = ".$dataLead['adminid']."
                        ) and admin_id != ".$dataLead['adminid']." and department != 'ADMINISTRATOR'";
        $resExeclead = mysql_query($qryExeclead) or die(mysql_error());
        while($dataExec = mysql_fetch_assoc($resExeclead)) {
            $arrExecLead[$dataLead['adminid']][] = $dataExec;
        }
    }
    elseif($dataLead['role'] == 'executive')
        $arrExecutive[] = $dataLead;
  }
  $all_users['all_leads'] = $arrLead;
  $all_users['all_exec_under_leads'] = $arrExecLead;
  $all_users['all_exec'] = $arrExecutive;
  return $all_users;  
//echo "<pre>";
//print_r($arrExecLead);//die;	
}









function getProjectIdsFromProjectDetails($projectDetails){
    $arr = array();
    foreach ($projectDetails as $entry) {
        $arr[] = $entry['PROJECT_ID'];
    }
    return $arr;
}

function extractPIDs($pidString){
    $result = array();
    $pidArr = explode(',', $pidString);
    foreach ($pidArr as $value) {
        $result[] = trim($value);
    }
    return $result;
}

function indexAdminId($array){
    $result = array();
    foreach ($array as $value) {
        $result[$value['ADMIN_ID']] = $value;
    }
    return $result;
}

function groupByAdminId($execCallDetail){
    $result = array();
    foreach ($execCallDetail as $value) {
        $result[$value['ADMINID']][] = $value;
    }
    return $result;
}

function getTotalAttemptsFromExecCallDetail($execCallDetail){
    $total = 0;
    foreach ($execCallDetail as $detail) {
        $total += $detail['TOTAL_CALLS'];
    }
    return $total;
}

function getTotalCallsFromExecCallDetail($execCallDetail){
    $total = 0;
    foreach ($execCallDetail as $detail) {
        if(!empty($detail['DialStatus']))$total += $detail['TOTAL_CALLS'];
    }
    return $total;
}

function getNotContactableCount($execCallDetail){
    $total = 0;
    foreach ($execCallDetail as $detail) {
        if($detail['DialStatus']!='answered' && !empty($detail['DialStatus'])){
            $total += $detail['TOTAL_CALLS'];
        }
    } 
    return $total;
}

function getIncompleteCallCount($execCallDetail){
    $total = 0;
    foreach ($execCallDetail as $detail) {
        if($detail['CallStatus']==='fail'){
            $total += $detail['TOTAL_CALLS'];
        }
    }
    return $total;
}

function getTotalCallTime($execCallDetail){
    $total = 0;
    foreach ($execCallDetail as $detail) {
        if($detail['DialStatus']=='answered'){
            $total += $detail['TOTAL_TIME'];
        }
    }
    return $total;
}

function secsToHumanReadable($secs)
{
    $secs = intval($secs);
    $units = array(
        "w"   => 7*24*3600,
        "d"    =>   24*3600,
        "h"   =>      3600,
        "m" =>        60,
        "s" =>         1,
    );

    // specifically handle zero
    if ( $secs == 0 ) return "0 s";

    $s = "";

    foreach ( $units as $name => $divisor ) {
            if ( $quot = intval($secs / $divisor) ) {
                    $s .= "$quot $name, ";
                    $secs -= $quot * $divisor;
            }
    }

    return substr($s, 0, -2);
}
?>
