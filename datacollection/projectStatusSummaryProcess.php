<?php

$accessDataCollection = '';
if( $dataCollectionFlowAuth == false )
   $accessDataCollection = "No Access";
$smarty->assign("accessDataCollection",$accessDataCollection);

require_once "$_SERVER[DOCUMENT_ROOT]/datacollection/functions.php";
if(!(($_SESSION['ROLE'] === 'teamLeader') && ($_SESSION['DEPARTMENT'] === 'CALLCENTER'))){
    header("Location: project_desktop.php");
}

if(isset($_POST['cityId']) && !empty($_POST['cityId'])){
    unset($_SESSION[$_SERVER['PHP_SELF']]);
    $_SESSION[$_SERVER['PHP_SELF']]['city'] = $_POST['cityId'];
    $_SESSION[$_SERVER['PHP_SELF']]['suburb'] = $_POST['suburbId'];
}

if(isset($_SESSION[$_SERVER['PHP_SELF']]['city']) && !empty($_SESSION[$_SERVER['PHP_SELF']]['city'])){
    $projectsfromDB = getProjectListForManagers($_SESSION[$_SERVER['PHP_SELF']]['city'], $_SESSION[$_SERVER['PHP_SELF']]['suburb']);
    $projectSummary = prepareDisplayData($projectsfromDB);
    $projectSummaryTotal = $projectSummary['total'];
    unset($projectSummary['total']);
}

$CityDataArr = CityArr();

$smarty->assign("CityDataArr", $CityDataArr);
$smarty->assign("projectSummary", $projectSummary);
$smarty->assign("projectSummaryTotal", $projectSummaryTotal);
$smarty->assign("selectedCity", $_SESSION[$_SERVER['PHP_SELF']]['city']);

function prepareDisplayData($data){ 
    $result = array();
    foreach ($data as $value) {
        $stage = $value['PROJECT_STAGE'];
        $aAssignedTo = explode('|', $value['ASSIGNED_TO']);
        $aAssignedToDep = explode('|', $value['DEPARTMENT']);
        $assignmentType = '';
        if($value['PREV_PROJECT_PHASE'] == 'audit1') $assignmentType .= 'Reverted-';
        if($aAssignedToDep[count($aAssignedToDep)-1] === 'SURVEY')$assignmentType .= 'Field';
        elseif(empty($aAssignedTo[0])) $assignmentType .= 'Unassigned';
        else{
            $assignmentType .= 'Assigned-'.  strval(count($aAssignedTo));
        }
        $aStatus = explode('|', $value['STATUS']);
        $completionStatus = end($aStatus);
        $result[$assignmentType][$stage]['total'] += 1;
        $result[$assignmentType][$stage][$completionStatus] += 1;
        $result['total'][$stage]['total'] += 1;
        $result['total'][$stage][$completionStatus] += 1;
    }
    return $result;
}
?>