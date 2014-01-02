<?php

$accessMyProjects = '';
if( $myProjectsAuth == false )
   $accessMyProjects = "No Access";
$smarty->assign("accessDataCollection",$accessDataCollection);

$callingFieldFlag = '';
if($_SESSION['DEPARTMENT'] === 'CALLCENTER')
    $callingFieldFlag = 'callcenter';
else
    $callingFieldFlag = 'survey';
$smarty->assign("callingFieldFlag",$callingFieldFlag);

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
