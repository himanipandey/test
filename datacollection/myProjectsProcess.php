<?php

if(!($_SESSION['ROLE'] === 'executive' && in_array($_SESSION['DEPARTMENT'], array('DATAENTRY', 'CALLCENTER')) || ($_SESSION['ROLE'] === 'teamLeader' && $_SESSION['DEPARTMENT'] === 'SURVEY'))){
    header("Location: project_desktop.php");
}

require_once "$_SERVER[DOCUMENT_ROOT]/datacollection/functions.php";

if($_POST['submit'] === 'Save'){
    $status = $_POST['status'];
    $remark = ($_POST['status']==='incomplete')? $_POST['remark'] : NULL;
    saveStatusUpdateByExecutive($_POST['projectid'], $status, $remark);
}

$assignedProjects = getAssignedProjects($_SESSION['adminId']);
$smarty->assign("assignedProjects", $assignedProjects);
$smarty->assign("projectPageURL", '/show_project_details.php?projectId=');
?>