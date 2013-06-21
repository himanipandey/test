<?php
require_once "$_SERVER[DOCUMENT_ROOT]/datacollection/functions.php";

$errorMsg = array();

//building data for the display when user is coming from project-status page
if(in_array($_POST['submit'], array('fresh assignement', 'field assignement'))){
    $projectIds = $_POST['assign'];
    if(empty($projectIds)){
        $errorMsg[] = 'No Project Selected for Assignment';
    }
    else {
        $projectDetails = getMultipleProjectDetails($projectIds);
        if($_POST['submit']==='fresh assignement'){
            $executiveWorkLoad = getCallCenterExecutiveWorkLoad();
        }
        else{
            $executiveWorkLoad = array(array('USERNAME'=>'field', 'WORKLOAD'=>'NA'));
        }
        $smarty->assign("executiveWorkLoad", $executiveWorkLoad);
        $smarty->assign("projectDetails", $projectDetails);
        $smarty->assign("assignmentType", $_POST['submit']);
    }
}
elseif($_POST['submit'] === 'Assign') {   //assigning projects
    if($_POST['assignmenttype'] === 'fresh assignement'){
        $projectIds = $_POST['projects'];
        $executives = $_POST['executives'];
        $executiveList = getCallCenterExecutiveWorkLoad($executives);
        $assignmentStatus = assignToCCExecutives($projectIds, $executiveList);
    }
    elseif($_POST['assignmenttype'] === 'field assignement'){
        $projectIds = $_POST['projects'];
        $result = assignProjectsToField($projectIds);
    }
    $errorMsg = array_keys($result);
}

$smarty->assign("errorMsg", $errorMsg);
?>