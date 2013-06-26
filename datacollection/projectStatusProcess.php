<?php
require_once "$_SERVER[DOCUMENT_ROOT]/datacollection/functions.php";

if(!(($_SESSION['ROLE'] === 'teamLeader') && ($_SESSION['DEPARTMENT'] === 'CALLCENTER'))){
    header("Location: project_desktop.php");
}

if(isset($_POST['cityId']) && !empty($_POST['cityId'])){
    $_SESSION['project-status']['city'] = $_POST['cityId'];
    $_SESSION['project-status']['suburb'] = $_POST['suburbId'];
}

if(isset($_SESSION['project-status']['city']) && !empty($_SESSION['project-status']['city'])){
    $projectsfromDB = getProjectListForManagers($_SESSION['project-status']['city'], $_SESSION['project-status']['suburb']);
    $projectList = prepareDisplayData($projectsfromDB);
    $suburbDataArr = SuburbArr($_SESSION['project-status']['city']);
}

if(isset($_SESSION['project-status']['assignmentError'])){
    if(empty($_SESSION['project-status']['assignmentError'])){
        $msg['type'] = 'success';
        $msg['content'] = 'All Projects Assigned Successfully';
    }
    else {
        $msg['type'] = 'error';
        $msg['content'] = "ProjetId " . implode(', ', array_keys($_SESSION['project-status']['assignmentError'])) ." couldn't be assigned.";
    }
    unset($_SESSION['project-status']['assignmentError']);
}

$CityDataArr = CityArr();
$smarty->assign("CityDataArr", $CityDataArr);
$smarty->assign("projectList", $projectList);
$smarty->assign("projectPageURL", '/show_project_details.php?projectId=');
$smarty->assign("selectedCity", $_SESSION['project-status']['city']);
$smarty->assign("selectedSuburb", $_SESSION['project-status']['suburb']);
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
?>