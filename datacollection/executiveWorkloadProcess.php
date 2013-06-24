<?php
require_once "$_SERVER[DOCUMENT_ROOT]/datacollection/functions.php";
if(!(($_SESSION['ROLE'] === 'teamLeader') && ($_SESSION['DEPARTMENT'] === 'CALLCENTER'))){
    header("Location: project_desktop.php");
}

$executiveWorkLoad = getCallCenterExecutiveWorkLoad();
$smarty->assign("executiveWorkLoad", $executiveWorkLoad);
?>