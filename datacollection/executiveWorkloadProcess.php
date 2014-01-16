<?php

$accessDataCollection = '';
if( $dataCollectionFlowAuth == false )
   $accessDataCollection = "No Access";
$smarty->assign("accessDataCollection",$accessDataCollection);

require_once "$_SERVER[DOCUMENT_ROOT]/datacollection/functions.php";

$callingFieldFlag = '';
if($_SESSION['DEPARTMENT'] === 'CALLCENTER')
    $callingFieldFlag = 'callcenter';
else
    $callingFieldFlag = 'survey';
$smarty->assign("callingFieldFlag",$callingFieldFlag);

$executiveWorkLoad = getCallCenterExecutiveWorkLoad($callingFieldFlag);
$smarty->assign("executiveWorkLoad", $executiveWorkLoad);

$arrSurveyTeamList = array();
if($callingFieldFlag == 'survey'){//filter executive list for survey
    $arrSurveyTeamList = surveyexecutiveList();
}
$smarty->assign("arrSurveyTeamList", $arrSurveyTeamList);
?>
