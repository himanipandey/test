<?php
require_once "$_SERVER[DOCUMENT_ROOT]/datacollection/functions.php";

$errorMsg = array();

    $department = $_REQUEST['department'];
    $arrExecTeamList = ProptigerAdmin::getAllExecByDepartment($department);
    $smarty->assign("arrExecTeamList", $arrExecTeamList);
//echo "<pre>";
//print_r($_REQUEST);//die;
//building data for the display when user is coming from project-status page
if(in_array($_POST['submit'], array('fresh assignement'))){
    $projectIds = $_POST['assign'];
    if(empty($projectIds)){
        $errorMsg[] = 'No Project Selected for Assignment';
    }
    else {
        $projectDetails = getMultipleProjectDetails($projectIds);
        if($_POST['submit']==='fresh assignement'){
            $executiveWorkLoad = getDataEntryExecutive();
        }
        $smarty->assign("executiveWorkLoad", $executiveWorkLoad);
        $smarty->assign("projectDetails", $projectDetails);
        $smarty->assign("assignmentType", $_POST['submit']);
    }
}
elseif($_POST['submit'] === 'Assign') {   //assigning projects
    if($_POST['assignmenttype'] === 'fresh assignement'){
        $projectIds = $_POST['projects'];
        $executive = $_POST['executive'];
        $assignmentStatus = assignToDEntryExecutives($projectIds, $executive);
    }
    $errorMsg = array_keys($assignmentStatus);
    $_SESSION['project-status']['assignmentError'] = $errorMsg;
   // header("Location: process-assign.php");
}
elseif($_POST['submit'] === 'Delete') {   //assigning projects
    $projectIds = implode(",",$_POST['assign']);
    $qryDel = "delete from process_assignment_system where project_id in($projectIds)";
    $resDel = mysql_query($qryDel) or die(mysql_error());
    header("Location:project_const_img.php");
   // header("Location: process-assign.php");
}
?>