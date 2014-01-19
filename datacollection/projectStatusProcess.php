<?php

$accessDataCollection = '';
if( $surveyAuth == false && $callCenterAuth == false)
   $accessDataCollection = "No Access";
$smarty->assign("accessDataCollection",$accessDataCollection);
require_once "$_SERVER[DOCUMENT_ROOT]/datacollection/functions.php";

/*if(!(($_SESSION['ROLE'] === 'teamLeader') && ($_SESSION['DEPARTMENT'] === 'CALLCENTER' 
     || $_SESSION['DEPARTMENT'] === 'SURVEY'))){
    header("Location: project_desktop.php");
}*/
$callingFieldFlag = '';
$getFlag = $_REQUEST['flag'];
if($getFlag === 'callcenter')
    $callingFieldFlag = 'callcenter';
else
    $callingFieldFlag = 'survey';
$smarty->assign("callingFieldFlag",$callingFieldFlag);

$smarty->assign('department',$_SESSION['DEPARTMENT']);
if(isset($_POST['cityId']) && !empty($_POST['cityId'])){
    unset($_SESSION['project-status']);
    $_SESSION['project-status']['city'] = $_POST['cityId'];
    $_SESSION['project-status']['suburb'] = $_POST['suburbId'];
    if(isset($_POST['localityId']) && $_POST['localityId'] != '')
        $_SESSION['project-status']['locality'] = $_POST['localityId'];
}
elseif(isset($_REQUEST['executive']) && !empty($_REQUEST['executive'])){
    unset($_SESSION['project-status']);
    $_SESSION['project-status']['executive'] = $_REQUEST['executive'];
}
elseif(isset($_POST['projectIds']) && !empty($_POST['projectIds'])){
    unset($_SESSION['project-status']);
    $_SESSION['project-status']['projectIds'] = $_REQUEST['projectIds'];
}
if(isset($_SESSION['project-status']['city']) && !empty($_SESSION['project-status']['city'])){
    if(isset($_SESSION['project-status']['locality']) && !empty($_SESSION['project-status']['locality'])){
        $projectsfromDB = getProjectListForManagers($_SESSION['project-status']['city'], $getFlag, $_SESSION['project-status']['suburb'], $_SESSION['project-status']['locality']);
    }else {
    $projectsfromDB = getProjectListForManagers($_SESSION['project-status']['city'], $getFlag, 
            $_SESSION['project-status']['suburb']);
    }
    $projectList = prepareDisplayData($projectsfromDB);
    $suburbDataArr = Suburb::SuburbArr($_SESSION['project-status']['city']);
    if(isset($_POST['suburbId']) && $_POST['suburbId'] != '')
        $localityDataArr = Locality::localityList($_SESSION['project-status']['suburb']);
    
}elseif(isset($_SESSION['project-status']['executive']) && !empty($_SESSION['project-status']['executive'])){
    $projectsAssignedToExec = getAssignedProjects($_SESSION['project-status']['executive']);
    $projectIds = getProjectIdsFromProjectDetails($projectsAssignedToExec);
    $projectsfromDB = getAssignedProjectsFromPIDs($projectIds,$callingFieldFlag);
    $projectList = prepareDisplayData($projectsfromDB);
}elseif(isset($_SESSION['project-status']['projectIds']) && !empty($_SESSION['project-status']['projectIds'])){
    $projectIds = extractPIDs($_SESSION['project-status']['projectIds']);
    $projectsfromDB = getAssignedProjectsFromPIDs($projectIds,$callingFieldFlag);
    $projectList = prepareDisplayData($projectsfromDB);
}
$project_ids = array();
foreach($projectList as $p){
    array_push($project_ids, $p['PROJECT_ID']);
}
$projectLastAuditDate = ProjectStageHistory::get_last_audit_date($project_ids);
if(isset($_SESSION['project-status']['assignmentError'])){
    if(empty($_SESSION['project-status']['assignmentError'])){
        $msg['type'] = 'success';
        $msg['content'] = 'All Projects Assigned Successfully';
    }
    else {
        $msg['type'] = 'error';
        $msg['content'] = "ProjetId " . implode(', ', $_SESSION['project-status']['assignmentError']) ." couldn't be assigned.";
    }
    unset($_SESSION['project-status']['assignmentError']);
}
if($callingFieldFlag == 'survey')
    $CityDataArr = arrSurveyTeamLeadCities($_SESSION['adminId']);
else
    $CityDataArr = City::CityArr();
$executiveList = getCallCenterExecutiveWorkLoad();

if(isset($projectList) && $_REQUEST['download'] == 'true'){
    download_xls_file($projectList,$projectLastAuditDate);
}
$smarty->assign("CityDataArr", $CityDataArr);
$arrSurveyTeamList = array();
if($callingFieldFlag == 'survey'){//filter executive list for survey
    $arrSurveyTeamList = surveyexecutiveList();
}

$smarty->assign("arrSurveyTeamList", $arrSurveyTeamList);
$smarty->assign("executiveList", $executiveList);
$smarty->assign("projectList", $projectList);
$smarty->assign("projectPageURL", '/show_project_details.php?projectId=');
$smarty->assign("selectedCity", $_SESSION['project-status']['city']);
$smarty->assign("selectedSuburb", $_SESSION['project-status']['suburb']);
$smarty->assign("selectedLocality", $_SESSION['project-status']['locality']);
$smarty->assign("selectedExecutive", $_SESSION['project-status']['executive']);
$smarty->assign("selectedProjectIds", $_SESSION['project-status']['projectIds']);
$smarty->assign("SuburbDataArr", $suburbDataArr);
$smarty->assign("LocalityDataArr", $localityDataArr);
$smarty->assign("message", $msg);
$smarty->assign("projectLastAuditDate", $projectLastAuditDate);


function prepareDisplayData($data){ 
    $result = array();
     
     $pids = "";
     $depts = array();
     
	 $prevs_depts = array();
    foreach ($data as $value)    
		$pids .= $value['PROJECT_ID'].", ";

	if(!empty($pids)) {
		$pids = substr($pids,0,strlen($pids)-2);
		$sql = "select group_concat(pa1.DEPARTMENT order by pa.ID desc) as department,
                    rp.PROJECT_ID from resi_project rp inner join project_stage_history psh 
                    on rp.project_id = psh.project_id inner join project_assignment pa on 
                    (psh.HISTORY_ID = pa.MOVEMENT_HISTORY_ID and (rp.updation_cycle_id is null
                    or rp.updation_cycle_id = pa.updation_cycle_id)) inner join proptiger_admin pa1 on 
                    pa.ASSIGNED_TO = pa1.ADMINID where psh.history_id != rp.movement_history_id and 
                    rp.project_id in (".$pids.") and rp.version = 'Cms' group by rp.PROJECT_ID;";
		$sql_depts = dbQuery($sql);
	
		foreach($sql_depts as $value){
			$prv_asg_dept = explode(",",$value['department']);
			$prevs_depts[$value['PROJECT_ID']] = $value['department'];
			$depts[$value['PROJECT_ID']] = $prv_asg_dept[0];
		}
	}
    foreach ($data as $value) {
		
		 if($value['LAST_WORKED_AT'] == '')
            $value['LAST_WORKED_AT'] = 'NA';       
        $new = array('PROJECT_ID' => $value['PROJECT_ID'], 'PROJECT_NAME' => $value['PROJECT_NAME'], 'BUILDER_NAME'=>$value['BUILDER_NAME'], 
            'CITY' => $value['CITY'], 'LOCALITY'=>$value['LOCALITY'], 'PROJECT_PHASE'=>$value['PROJECT_STAGE'], 
            'PROJECT_STAGE'=>$value['PROJECT_PHASE'], 'MOVEMENT_DATE' => $value['MOVEMENT_DATE'],
            'LAST_WORKED_AT'=>$value['LAST_WORKED_AT'], 'PROJECT_STATUS'=>$value['PROJECT_STATUS'],'BOOKING_STATUS'=>$value['BOOKING_STATUS'], 
            'LABEL'=>$value['LABEL'], 'ASSIGNED_TO_DEPART'=>$value['ASSIGNED_TO_DEPART'],'ROLE'=>$value['ROLE']);
        $assigned_to = explode('|', $value['ASSIGNED_TO']);
        $assigned_to_dep = explode('|', $value['DEPARTMENT']);
        $assignment_type = '';
        if(($value['PREV_PROJECT_PHASE'] == 'Audit1' || $value['PREV_PROJECT_PHASE'] == 'Audit2') && strstr($prevs_depts[$value['PROJECT_ID']],$assigned_to_dep[count($assigned_to_dep)-1])){
            $assignment_type .= 'Reverted';
            if(isset($depts[$value['PROJECT_ID']]) && $depts[$value['PROJECT_ID']] === 'SURVEY')$assignment_type .= 'Field';
        }
        $RoleExp = explode('|',$value['ROLE']);
        $roleCount = count($RoleExp);
        $lastRole = $RoleExp[$roleCount-1];
       // $firstRole = '';
        if($assigned_to_dep[count($assigned_to_dep)-1] === 'SURVEY'){
            
            $qryOldHistoryId = "select pa1.role from resi_project rp
                join project_assignment pa on rp.movement_history_id = pa.movement_history_id
                join  proptiger_admin pa1 on pa.assigned_to = pa1.adminid
                where rp.project_id = ".$value['PROJECT_ID']." and rp.version = 'Cms' order by pa.updation_time asc limit 1";            
            $resOldHistoryId = mysql_query($qryOldHistoryId) or die(mysql_error());
            $dataOldHistoryId = mysql_fetch_assoc($resOldHistoryId);
           // $firstRole = $dataOldHistoryId['role'];
            if($assigned_to_dep[count($assigned_to_dep)-1] === 'SURVEY' && trim($lastRole) == 'teamLeader'){
                $assignment_type .= 'Field_lead';
            }
            else
                $assignment_type .= 'Field_Assigned-'.  strval(count($assigned_to));
         }
        elseif(empty($assigned_to[0])) $assignment_type .= 'Unassigned';
        else{
            $assignment_type .= 'Assigned-'.  strval(count($assigned_to));
        }
        
        $new['leadAssignedType'] = 0;
        if($assigned_to_dep[count($assigned_to_dep)-1] === 'SURVEY' && $lastRole == 'teamLeader'){
            $new['leadAssignedType'] = 1;
        }
        else 
            $new['STATUS'] = explode('|', $value['STATUS']);
        
        $new['ASSIGNMENT_TYPE'] = $assignment_type;
       // $new['FIRST_ROLE'] = $firstRole;
        $new['ASSIGNED_TO'] = $assigned_to;
        $new['ASSIGNED_AT'] = explode('|', $value['ASSIGNED_AT']);
        $new['REMARK'] = explode('|', $value['REMARK']);
        $new['LAST_DEPARTMENT'] = $assigned_to_dep[count($assigned_to_dep)-1];
        $result[] = $new;
    }
    return $result;
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

function download_xls_file($projectList, $projectLastAuditDate){
    $filename = "/tmp/data_collection_".time().".xls";
    foreach ($projectList as $pkey => $project){
        // For first three assignments
            $projectList[$pkey]["LAST_AUDIT_DATE"] = $projectLastAuditDate[$projectList[$pkey]["PROJECT_ID"]];
            foreach(array(1,2,3) as $a){
                $projectList[$pkey]["ASSIGNED_TO_{$a}"] = $projectList[$pkey]["ASSIGNED_TO"][$a-1];
                $projectList[$pkey]["ASSIGNED_AT_{$a}"] = $projectList[$pkey]["ASSIGNED_AT"][$a-1];
                $projectList[$pkey]["STATUS_{$a}"] = $projectList[$pkey]["STATUS"][$a-1];
                $projectList[$pkey]["REMARK_{$a}"] = $projectList[$pkey]["REMARK"][$a-1];
            }
            unset($projectList[$pkey]["ASSIGNED_TO"]);
            unset($projectList[$pkey]["ASSIGNED_AT"]);
            unset($projectList[$pkey]["STATUS"]);
            unset($projectList[$pkey]["REMARK"]);
            unset($projectList[$pkey]["ASSIGNED_TO_DEPART"]);
            unset($projectList[$pkey]["ROLE"]);
            unset($projectList[$pkey]["leadAssignedType"]);
    };
    excel_file_download($projectList, $filename);
}

?>
