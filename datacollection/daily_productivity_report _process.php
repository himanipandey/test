<?php

$accessDataCollection = '';
if( $processAssignmentLead != 1 && $processAssignmentForConstImg != 1)
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
    $displayData = prepareDisplayData($dateFrom, $dateTo);
}

$smarty->assign("dateFrom", $dateFrom);
$smarty->assign("dateTo", $dateTo);
$smarty->assign("displayData", $displayData);


function prepareDisplayData($dateFrom, $dateTo){ 
    $result = array();
    $data_sql = mysql_query("select pa.username,count(IF(pas.status='complete',pas.status,null)) 						complete,count(IF(pas.status='incomplete',pas.status,null)) incomplete,count(*) total_count from  process_assignment_system pas
	inner join proptiger_admin pa
	on pas.ASSIGNED_TO = pa.adminid
	where pas.ASSIGNED_TO != 0 
	and DATE(pas.ASSIGN_TIME) >= '$dateFrom' and DATE(pas.ASSIGN_TIME) <= '$dateTo'
	group by pas.ASSIGNED_TO") or die(mysql_error());
	
	if($data_sql){
      while($row = mysql_fetch_object($data_sql)){
		$new = array();
		$new['USERNAME'] = $row->username;
		$new['COMPLETE'] = $row->complete;
		$new['INCOMPLETE'] = $row->incomplete;		
		#$new['TOTAL'] = $row->total_count;
		$new['TOTAL'] = ($row->complete + $row->incomplete);
		$result[] = $new;	
	  }
	}
		
    return $result;
}
?>
