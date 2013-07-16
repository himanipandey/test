<?php
require_once "$_SERVER[DOCUMENT_ROOT]/datacollection/functions.php";

if(!(($_SESSION['ROLE'] === 'teamLeader') && ($_SESSION['DEPARTMENT'] === 'CALLCENTER'))){
    header("Location: project_desktop.php");
}


$smarty->assign("CityDataArr", $CityDataArr);
$smarty->assign("executiveList", $executiveList);
$smarty->assign("projectList", $projectList);
$smarty->assign("projectPageURL", '/show_project_details.php?projectId=');
$smarty->assign("selectedCity", $_SESSION['project-status']['city']);
$smarty->assign("selectedSuburb", $_SESSION['project-status']['suburb']);
$smarty->assign("selectedExecutive", $_SESSION['project-status']['executive']);
$smarty->assign("selectedProjectIds", $_SESSION['project-status']['projectIds']);
$smarty->assign("SuburbDataArr", $suburbDataArr);
$smarty->assign("message", $msg);



function prepareDisplayData($data){ 
    $result = array();
    foreach ($data as $value) {
        $new['PROJECT_ID'] = $value['PROJECT_ID'];
        $new['PROJECT_NAME'] = $value['PROJECT_NAME'];
        $new['BUILDER_NAME'] = $value['BUILDER_NAME'];
        $new['LOCALITY'] = $value['LOCALITY'];
        $new['PROJECT_PHASE'] = $value['PROJECT_STAGE'];
        $new['PROJECT_STAGE'] = $value['PROJECT_PHASE'];
        $new['LAST_WORKED_AT'] = $value['LAST_WORKED_AT'];
        $assigned_to = explode('|', $value['ASSIGNED_TO']);
        $assigned_to_dep = explode('|', $value['DEPARTMENT']);
        $assignment_type = '';
        if($value['PREV_PROJECT_PHASE'] == 'audit1') $assignment_type .= 'Reverted-';
        if($assigned_to_dep[count($assigned_to_dep)-1] === 'SURVEY')$assignment_type .= 'Field';
        elseif(empty($assigned_to[0])) $assignment_type .= 'Unassigned';
        else{
            $assignment_type .= 'Assigned-'.  strval(count($assigned_to));
        }
        $new['ASSIGNMENT_TYPE'] = $assignment_type;
        $new['ASSIGNED_TO'] = $assigned_to;
        $new['ASSIGNED_AT'] = explode('|', $value['ASSIGNED_AT']);
        $new['STATUS'] = explode('|', $value['STATUS']);
        $new['REMARK'] = explode('|', $value['REMARK']);
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
?>