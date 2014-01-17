<?php

$accessDataCollection = '';
if( $dataCollectionFlowAuth == false )
   $accessDataCollection = "No Access";
$smarty->assign("accessDataCollection",$accessDataCollection);

require_once "$_SERVER[DOCUMENT_ROOT]/datacollection/functions.php";

$callingFieldFlag = '';
$getFlag = $_REQUEST['flag'];
if($getFlag === 'callcenter')
    $callingFieldFlag = 'callcenter';
else
    $callingFieldFlag = 'survey';

$executiveWorkLoad = array();
$arrSurveyTeamList = array();

if($callingFieldFlag === 'callcenter'){
    $executiveWorkLoad = getCallCenterExecutiveWorkLoad();
}
else{
    $arrSurveyTeamList = surveyexecutiveList();
}

$smarty->assign("callingFieldFlag",$callingFieldFlag);
$smarty->assign("executiveWorkLoad", $executiveWorkLoad);

$smarty->assign("arrSurveyTeamList", $arrSurveyTeamList);
?>
