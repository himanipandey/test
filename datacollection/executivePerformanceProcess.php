<?php
require_once "$_SERVER[DOCUMENT_ROOT]/datacollection/functions.php";

if(!(($_SESSION['ROLE'] === 'teamLeader') && ($_SESSION['DEPARTMENT'] === 'CALLCENTER'))){
    header("Location: project_desktop.php");
}

if($_POST['submit']==='Get'){
    $_SESSION[$_SERVER['PHP_SELF']]['dateFrom'] = $_POST['dateFrom'];
    $_SESSION[$_SERVER['PHP_SELF']]['dateTo'] = $_POST['dateTo'];
}

if(!empty($_SESSION[$_SERVER['PHP_SELF']]['dateFrom'])){
    $dateFrom = $_SESSION[$_SERVER['PHP_SELF']]['dateFrom'];
    $dateTo = $_SESSION[$_SERVER['PHP_SELF']]['dateTo'];
    $execCallCount = getExecCallCount($dateFrom, $dateTo);
    $completionCount = getCompletionCountByExecs($dateFrom, $dateTo);
    $revertCount = getRevertCountForExecs($dateFrom, $dateTo);
    $displayData = prepareDisplayData($execCallCount, $completionCount, $revertCount);
}

$smarty->assign("dateFrom", $dateFrom);
$smarty->assign("dateTo", $dateTo);
$smarty->assign("displayData", $displayData);



function prepareDisplayData($execCallCount, $completionCount, $revertCount){ 
    $result = array();
    $completionCount = indexAdminId($completionCount);
    $revertCount = indexAdminId($revertCount);
    
    $execCallCount = groupByAdminId($execCallCount);
    foreach ($execCallCount as $adminId=>$adminDetail) {
        $new = array();
        $new['USERNAME'] = $adminDetail[0]['USERNAME'];
        $new['TOTAL-CALLS'] = intval(getTotalCallsFromExecCallDetail($adminDetail));
        $new['DONE'] = intval($completionCount[$adminId]['COMPLETED']);
        $new['REVERTED'] = intval($revertCount[$adminId]['REVERT_COUNT']);
        $new['CALL-DONE-RATIO'] = round($new['TOTAL_CALLS']/$new['DONE'], 2);
        $new['NOT-CONTACTABLE'] = intval(getNotContactableCount($adminDetail));
        $new['NOT-CONTACTABLE-%'] = round(($new['NOT-CONTACTABLE']*100)/$new['TOTAL-CALLS'],2);
        $new['INCOMPLETE'] = intval(getIncompleteCallCount($adminDetail));
        $new['TOTAL-CONNECTED-CALLS'] = intval($new['TOTAL-CALLS']-$new['NOT-CONTACTABLE']);
        $new['ACCURACY'] = round((($new['TOTAL-CONNECTED-CALLS']-$new['INCOMPLETE'])*100)/$new['TOTAL-CONNECTED-CALLS'], 2);
        $totalCallTime = intval(getTotalCallTime($adminDetail));
        $new['TOTAL-CALL-TIME'] = round($totalCallTime/60, 2);
        $new['AVERAGE-CALL-TIME'] = round($totalCallTime/(60*$new['TOTAL-CONNECTED-CALLS']), 2);
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

function getTotalCallsFromExecCallDetail($execCallDetail){
    $total = 0;
    foreach ($execCallDetail as $detail) {
        $total += $detail['TOTAL_CALLS'];
    }
    return $total;
}

function getNotContactableCount($execCallDetail){
    $total = 0;
    foreach ($execCallDetail as $detail) {
        if(is_null($detail['CallStatus'])){
            $total = $detail['TOTAL_CALLS'];
            break;
        }
    }
    return $total;
}

function getIncompleteCallCount($execCallDetail){
    $total = 0;
    foreach ($execCallDetail as $detail) {
        if($detail['CallStatus']==='fail'){
            $total = $detail['TOTAL_CALLS'];
            break;
        }
    }
    return $total;
}

function getTotalCallTime($execCallDetail){
    $total = 0;
    foreach ($execCallDetail as $detail) {
        if(in_array($detail['CallStatus'], array('fail', 'success'))){
            $total = $detail['TOTAL_TIME'];
        }
    }
    return $total;
}
?>