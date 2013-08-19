<?php

$accessDataCollection = '';
if( $dataCollectionFlowAuth == false )
   $accessDataCollection = "No Access";
$smarty->assign("accessDataCollection",$accessDataCollection);

require_once "$_SERVER[DOCUMENT_ROOT]/datacollection/functions.php";

$executiveWorkLoad = getCallCenterExecutiveWorkLoad();
$smarty->assign("executiveWorkLoad", $executiveWorkLoad);
?>
