<?php
require_once "$_SERVER[DOCUMENT_ROOT]/datacollection/functions.php";

if(!(($_SESSION['ROLE'] === 'teamLeader') && (in_array($_SESSION['DEPARTMENT'], array('DATAENTRY', 'CALLCENTER'))))){
    header("Location: project_desktop.php");
}

$projectsfromDB = getProjectListForManagers($_SESSION['adminId']);
$projectList = prepareDisplayData($projectsfromDB);

$smarty->assign("projectList", $projectList);
$smarty->assign("projectPageURL", '/show_project_details.php?projectId=');

function prepareDisplayData($data){ 
    $result = array();
    foreach ($data as $value) {
        $new['PROJECT_ID'] = $value['PROJECT_ID'];
        $new['PROJECT_NAME'] = $value['PROJECT_NAME'];
        $new['BUILDER_NAME'] = $value['BUILDER_NAME'];
        $new['CITY'] = $value['CITY'];
        $new['LOCALITY'] = $value['LOCALITY'];
        $new['PROJECT_PHASE'] = $value['PROJECT_STAGE'];
        $new['LAST_WORKED_AT'] = $value['LAST_WORKED_AT'];
        $assigned_to = explode('|', $value['ASSIGNED_TO']);
        $assigned_to_dep = explode('|', $value['DEPARTMENT']);
        if(empty($assigned_to[0])){
            if($value['PREV_PROJECT_PHASE'] == 'revert') $new['ASSIGNMENT_TYPE'] = 'Assigned - Reverted';
            else $new['ASSIGNMENT_TYPE'] = 'Unassigned';
        }
        elseif($assigned_to_dep[count($assigned_to_dep)-1] === 'SURVEY'){
            $new['ASSIGNMENT_TYPE'] = 'Field';
        }else{
            $new['ASSIGNMENT_TYPE'] = 'Assigned-'.  strval(count($assigned_to));
        }
        $new['ASSIGNED_TO'] = $assigned_to;
        $new['ASSIGNED_AT'] = explode('|', $value['ASSIGNED_AT']);
        $new['STATUS'] = explode('|', $value['STATUS']);
        $new['REMARK'] = explode('|', $value['REMARK']);
        $result[] = $new;
    }
    return $result;
}
?>