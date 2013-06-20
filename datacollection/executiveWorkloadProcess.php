<?php
require_once "$_SERVER[DOCUMENT_ROOT]/datacollection/functions.php";

$executiveWorkLoad = getCallCenterExecutiveWorkLoad();
$smarty->assign("executiveWorkLoad", $executiveWorkLoad);
?>