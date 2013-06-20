<?php
require_once "$_SERVER[DOCUMENT_ROOT]/datacollection/functions.php";

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
        if(empty($assigned_to[0])){
            if($value['PREV_PROJECT_PHASE'] == 'audit1') $new['ASSIGNMENT_TYPE'] = 'Assigned - Reverted';
            else $new['ASSIGNMENT_TYPE'] = 'Unassigned';
        }
        else $new['ASSIGNMENT_TYPE'] = 'Assigned-'.  strval(count($assigned_to));
        $new['ASSIGNED_TO'] = $assigned_to;
        $new['ASSIGNED_AT'] = explode('|', $value['ASSIGNED_AT']);
        $new['STATUS'] = explode('|', $value['STATUS']);
        $new['REMARK'] = explode('|', $value['REMARK']);
        $result[] = $new;
    }
    return $result;
}
?>