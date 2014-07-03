<?php

$accessMyProjects = '';
if( $myProjectsAuth == false )
   $accessMyProjects = "No Access";
$smarty->assign("accessDataCollection",$accessDataCollection);

require_once "$_SERVER[DOCUMENT_ROOT]/datacollection/functions.php";

if($_POST['submit'] === 'Save'){
    $status = $_POST['status'];
    $remark = $_POST['remark'];
    $source = $_POST['source'];
    $id = $_POST['id'];
    saveStatusUpdateByExecutiveConst($_POST['projectid'], $status, $remark, $source,$id);
}

$assignedProjectsConst = getAssignedProjectsConst($_SESSION['adminId']);
$smarty->assign("assignedProjectsConst", $assignedProjectsConst);
$smarty->assign("projectPageURL", '/show_project_details.php?projectId=');
?>
