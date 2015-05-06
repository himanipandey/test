<?php

/* * *****Auth code***** */
if (isset($_SESSION['builderAuth'])) {
    $builderAuth = $_SESSION['builderAuth'];    
} else {
    $builderAuth = isUserPermitted('builder', 'manage');
    $_SESSION['builderAuth'] = $builderAuth;
}
$smarty->assign("builderAuth", $builderAuth);

$seoMetaAuth = isUserPermitted('seo-meta-template', 'templateAccess');
$smarty->assign("seoMetaAuth", $seoMetaAuth);

if (isset($_SESSION['authorityAuth'])) {
    $authorityAuth = $_SESSION['authorityAuth'];    
} else {
    $authorityAuth = isUserPermitted('authority', 'manage');
    $_SESSION['authorityAuth'] = $authorityAuth;
    
}
$smarty->assign("authorityAuth", $authorityAuth);

if (isset($_SESSION['listingAuth'])) {
    $listingAuth =  $_SESSION['listingAuth'];
} else {
    $listingAuth = isUserPermitted('listing', 'manage');    
    $_SESSION['listingAuth'] = $listingAuth;
}
$smarty->assign("listingAuth", $listingAuth);

if (isset($_SESSION['listingPhotoAuth'])) {
    $listingPhotoAuth =  $_SESSION['listingPhotoAuth'];
} else {
    $listingPhotoAuth = isUserPermitted('listing-photo', 'manage');    
    $_SESSION['listingPhotoAuth'] = $listingPhotoAuth;
}
$smarty->assign("listingPhotoAuth", $listingPhotoAuth);

if (isset($_SESSION['crawlingAuth'])) {
    $crawlingAuth = $_SESSION['crawlingAuth'];
} else {
    $crawlingAuth = isUserPermitted('crawling', 'manage');    
    $_SESSION['crawlingAuth'] = $crawlingAuth;
}
$smarty->assign("crawlingAuth", $crawlingAuth);

if (isset($_SESSION['bulkProjUpdateAuth'])) {
    $bulkProjUpdateAuth = $_SESSION['bulkProjUpdateAuth'];
} else {
    $bulkProjUpdateAuth = isUserPermitted('bulk-project-update', 'read');    
    $_SESSION['bulkProjUpdateAuth'] = $bulkProjUpdateAuth;
}
$smarty->assign("bulkProjUpdateAuth", $bulkProjUpdateAuth);

if (isset($_SESSION['companyAuth'])) {
    $companyAuth = $_SESSION['companyAuth'];
} else {
    $companyAuth = isUserPermitted('company', 'manage');    
    $_SESSION['companyAuth'] = $companyAuth;
}
$smarty->assign("companyAuth", $companyAuth);

if (isset($_SESSION['roomTypeAuth'])) {
    $roomTypeAuth = $_SESSION['roomTypeAuth'];
} else {
    $roomTypeAuth = isUserPermitted('room-type', 'manage');    
    $_SESSION['roomTypeAuth'] = $roomTypeAuth;
}
$smarty->assign("roomTypeAuth", $roomTypeAuth);

if (isset($_SESSION['isSmoothedAuth'])) {
    $isSmoothedAuth = $_SESSION['isSmoothedAuth'];
} else {
    $isSmoothedAuth = isUserPermitted('is-smoothed', 'manage');    
    $_SESSION['isSmoothedAuth'] = $isSmoothedAuth;
}
$smarty->assign("isSmoothedAuth", $isSmoothedAuth);

if (isset($_SESSION['companyOrderAdminAuth'])) {
   $companyOrderAdminAuth = $_SESSION['companyOrderAdminAuth'];
} else {
    $companyOrderAdminAuth = isUserPermitted('comp-orders-mgmt', 'admin');    
    $_SESSION['companyOrderAdminAuth'] = $companyOrderAdminAuth;
}
$smarty->assign("companyOrderAdminAuth", $companyOrderAdminAuth);

if (isset($_SESSION['companyOrderViewAuth'])) {
    $companyOrderViewAuth = $_SESSION['companyOrderViewAuth'];
} else {
    $companyOrderViewAuth = isUserPermitted('comp-orders-mgmt', 'view');    
    $_SESSION['companyOrderViewAuth'] = $companyOrderViewAuth;
}
$smarty->assign("companyOrderViewAuth", $companyOrderViewAuth);

if (isset($_SESSION['couponAuth'])) {
    $couponAuth = $_SESSION['couponAuth'];
} else {
    $couponAuth = isUserPermitted('coupon', 'manage');    
    $_SESSION['couponAuth'] = $couponAuth;
}
$smarty->assign("couponAuth", $couponAuth);

if (isset($_SESSION['cityAuth'])) {
    $cityAuth = $_SESSION['cityAuth'];
} else {
    $cityAuth = isUserPermitted('city', 'manage');    
    $_SESSION['cityAuth'] = $cityAuth;
}
$smarty->assign("cityAuth", $cityAuth);

if (isset($_SESSION['bankAuth'])) {
    $bankAuth = $_SESSION['bankAuth'];
} else {
    $bankAuth = isUserPermitted('bank', 'manage');    ;
    $_SESSION['bankAuth'] = $bankAuth;
}
$smarty->assign("bankAuth", $bankAuth);

if (isset($_SESSION['peDealsAuth'])) {
    $peDealsAuth = $_SESSION['peDealsAuth'];
} else {
    $peDealsAuth = isUserPermitted('private-equity-deals', 'manage');    
    $_SESSION['peDealsAuth'] = $peDealsAuth;
}
$smarty->assign("peDealsAuth", $peDealsAuth);

if (isset($_SESSION['dailyPerformanceReportAuth'])) {
    $dailyPerformanceReportAuth = $_SESSION['dailyPerformanceReportAuth'];
} else {
    $dailyPerformanceReportAuth = isUserPermitted('daily-performance-report', 'read');    
    $_SESSION['dailyPerformanceReportAuth'] = $dailyPerformanceReportAuth;
}
$smarty->assign("dailyPerformanceReportAuth", $dailyPerformanceReportAuth);

if (isset($_SESSION['dataCollectionFlowAuth'])) {
    $dataCollectionFlowAuth = $_SESSION['dataCollectionFlowAuth'];
} else {
    $dataCollectionFlowAuth = isUserPermitted('data-clloection-flow', 'read');    
    $_SESSION['dataCollectionFlowAuth'] = $dataCollectionFlowAuth;
}
$smarty->assign("dataCollectionFlowAuth", $dataCollectionFlowAuth);

if (isset($_SESSION['imageAuth'])) {
    $imageAuth = $_SESSION['imageAuth'];
} else {
    $imageAuth = isUserPermitted('image', 'add');    
    $_SESSION['imageAuth'] = $imageAuth;
}
$smarty->assign("imageAuth", $imageAuth);

if (isset($_SESSION['compHistAuth'])) {
    $compHistAuth = $_SESSION['compHistAuth'];
} else {
    $compHistAuth = isUserPermitted('comp-history-mgmt', 'manage');    
    $_SESSION['compHistAuth'] = $compHistAuth;
}
$smarty->assign("compHistAuth", $compHistAuth);

if (isset($_SESSION['labelAuth'])) {
    $labelAuth = $_SESSION['labelAuth'];
} else {
    $labelAuth = isUserPermitted('label', 'add');    
    $_SESSION['labelAuth'] = $labelAuth;
}
$smarty->assign("labelAuth", $labelAuth);

if (isset($_SESSION['localityAuth'])) {
    $localityAuth = $_SESSION['localityAuth'];
} else {
    $localityAuth = isUserPermitted('locality', 'manage');    
    $_SESSION['localityAuth'] = $localityAuth;
}
$smarty->assign("localityAuth", $localityAuth);

if (isset($_SESSION['migrateAuth'])) {
    $migrateAuth = $_SESSION['migrateAuth'];
} else {

    $migrateAuth = isUserPermitted('migrate', 'perform');    
    $_SESSION['migrateAuth'] = $migrateAuth;
}
$smarty->assign("migrateAuth", $migrateAuth);

if (isset($_SESSION['prokectAuth'])) {
    $prokectAuth = $_SESSION['prokectAuth'];
} else {
    $prokectAuth = isUserPermitted('project', 'manage');    
    $_SESSION['prokectAuth'] = $prokectAuth;
}
$smarty->assign("prokectAuth", $prokectAuth);

if (isset($_SESSION['suburbAuth'])) {
    $suburbAuth = $_SESSION['suburbAuth'];
} else {

    $suburbAuth = isUserPermitted('suburb', 'manage');    
    $_SESSION['suburbAuth'] = $suburbAuth;
}
$smarty->assign("suburbAuth", $suburbAuth);

if (isset($_SESSION['urlAuth'])) {
    $urlAuth = $_SESSION['urlAuth'];
} else {

    $urlAuth = isUserPermitted('url', 'redirect');    
    $_SESSION['urlAuth'] = $urlAuth;
}
$smarty->assign("urlAuth", $urlAuth);

if (isset($_SESSION['brokerAuth'])) {
    $brokerAuth = $_SESSION['brokerAuth'];
} else {

    $brokerAuth = isUserPermitted('broker', 'manage');    
    $_SESSION['brokerAuth'] = $brokerAuth;
}
$smarty->assign("brokerAuth", $brokerAuth);

if (isset($_SESSION['myProjectsAuth'])) {
    $myProjectsAuth = $_SESSION['myProjectsAuth'];
} else {

    $myProjectsAuth = isUserPermitted('my-projects', 'read');    
    $_SESSION['myProjectsAuth'] = $myProjectsAuth;
}
$smarty->assign("myProjectsAuth", $myProjectsAuth);

if (isset($_SESSION['urlEditAuth'])) {
    $urlEditAuth = $_SESSION['urlEditAuth'];
} else {

    $urlEditAuth = isUserPermitted('url-edit', 'url-edit');    
    $_SESSION['urlEditAuth'] = $urlEditAuth;
}
$smarty->assign("urlEditAuth", $urlEditAuth);

$urlEditAccess = 0;
if ($urlEditAuth == true)
    $urlEditAccess = 1;
$smarty->assign("urlEditAccess", $urlEditAccess);

if (isset($_SESSION['specialAccessAuth'])) {
    $specialAccessAuth = $_SESSION['specialAccessAuth'];
} else {

    $specialAccessAuth = isUserPermitted('projectSpecialAttrs', 'manage');    
    $_SESSION['specialAccessAuth'] = $specialAccessAuth;
}
$smarty->assign("specialAccessAuth", $specialAccessAuth);
$specialAccess = 0;
if ($specialAccessAuth == true)
    $specialAccess = 1;
$smarty->assign("specialAccess", $specialAccess);

if (isset($_SESSION['specialAccessAuth'])) {
    $localityCleanedAuth = $_SESSION['specialAccessAuth'];
} else {

    $localityCleanedAuth = isUserPermitted('locality-cleaned', 'access');
    $_SESSION['localityCleanedAuth'] = $localityCleanedAuth;
}
$localityCleanedAccess = 0;
if ($localityCleanedAuth == true)
    $localityCleanedAccess = 1;
$smarty->assign("localityCleanedAccess", $localityCleanedAccess);


if (isset($_SESSION['isMetricsAuth'])) {
    $isMetricsAuth = $_SESSION['isMetricsAuth'];
} else {

    $isMetricsAuth = isUserPermitted('is-metrics', 'read');
    $_SESSION['isMetricsAuth'] = $isMetricsAuth;
}
$isMetricsAccess = 0;
if ($isMetricsAuth == true)
    $isMetricsAccess = 1;
$smarty->assign("isMetricsAccess", $isMetricsAccess);

if (isset($_SESSION['isupplyEditPermissionAuth'])) {
    $isupplyEditPermissionAuth = $_SESSION['isupplyEditPermissionAuth'];
} else {

    $isupplyEditPermissionAuth = isUserPermitted('supplyEditPermission', 'access');
    $_SESSION['isupplyEditPermissionAuth'] = $isupplyEditPermissionAuth;
}
$supplyEditPermissionAccess = 0;
if ($isupplyEditPermissionAuth == true)
    $supplyEditPermissionAccess = 1;
$smarty->assign("supplyEditPermissionAccess", $supplyEditPermissionAccess);


if (isset($_SESSION['priorityMgmtPermissionAuth'])) {
    $priorityMgmtPermissionAuth = $_SESSION['priorityMgmtPermissionAuth'];
} else {

    $priorityMgmtPermissionAuth = isUserPermitted('priorityManagement', 'access');
    $_SESSION['priorityMgmtPermissionAuth'] = $priorityMgmtPermissionAuth;
}
$priorityMgmtPermissionAccess = 0;
if ($priorityMgmtPermissionAuth == 1)
    $priorityMgmtPermissionAccess = 1;
$smarty->assign("priorityMgmtPermissionAccess", $priorityMgmtPermissionAccess);



////////////////////////////report error//////////////////
if (isset($_SESSION['isReportErrorPermissionAuth'])) {
    $isReportErrorPermissionAuth = $_SESSION['isReportErrorPermissionAuth'];
} else {

    $isReportErrorPermissionAuth = isUserPermitted('report-error', 'access');
    $_SESSION['isReportErrorPermissionAuth'] = $isReportErrorPermissionAuth;
}
$reportErrorPermissionAccess = 0;
if ($isReportErrorPermissionAuth == true)
    $reportErrorPermissionAccess = 1;
$smarty->assign("reportErrorPermissionAccess", $reportErrorPermissionAccess);

/* * ***********datacollection callcenter and survey auth************** */
if (isset($_SESSION['callCenterAuth'])) {
    $callCenterAuth = $_SESSION['callCenterAuth'];
} else {

    $callCenterAuth = isUserPermitted('callcenter', 'access');    
    $_SESSION['callCenterAuth'] = $callCenterAuth;
}
$smarty->assign("callCenterAuth", $callCenterAuth);

if (isset($_SESSION['campaigndidsAuth'])) {
    $campaigndidsAuth = $_SESSION['campaigndidsAuth'];
} else {

    $campaigndidsAuth = isUserPermitted('campaigndids', 'manage');    
    $_SESSION['campaigndidsAuth'] = $campaigndidsAuth;
}
$smarty->assign("campaigndidsAuth", $campaigndidsAuth);

if (isset($_SESSION['myProjectsCallCenterAuth'])) {
    $myProjectsCallCenterAuth = $_SESSION['myProjectsCallCenterAuth'];
} else {

    $myProjectsCallCenterAuth = isUserPermitted('myprojects_callcenter', 'access');    
    $_SESSION['myProjectsCallCenterAuth'] = $myProjectsCallCenterAuth;
}
$smarty->assign("myProjectsCallCenterAuth", $myProjectsCallCenterAuth);

if (isset($_SESSION['surveyAuth'])) {
    $surveyAuth = $_SESSION['surveyAuth'];
} else {

    $surveyAuth = isUserPermitted('survey', 'access');    
    $_SESSION['surveyAuth'] = $surveyAuth;
}
$smarty->assign("surveyAuth", $surveyAuth);

if (isset($_SESSION['myProjectsSurveyAuth'])) {
    $myProjectsSurveyAuth = $_SESSION['myProjectsSurveyAuth'];
} else {

    $myProjectsSurveyAuth = isUserPermitted('myprojects_survey', 'access');    
    $_SESSION['myProjectsSurveyAuth'] = $myProjectsSurveyAuth;
}
$smarty->assign("myProjectsSurveyAuth", $myProjectsSurveyAuth);

if (isset($_SESSION['userManagement'])) {
    $userManagement = $_SESSION['userManagement'];
} else {

    $userManagement = isUserPermitted('user-management', 'access');    
    $_SESSION['userManagement'] = $userManagement;
}
$smarty->assign("userManagement", $userManagement);

if (isset($_SESSION['executivePerformanceAuth'])) {
    $executivePerformanceAuth = $_SESSION['executivePerformanceAuth'];
} else {
    $executivePerformanceAuth = isUserPermitted('executive-performance', 'access');    
    $_SESSION['executivePerformanceAuth'] = $executivePerformanceAuth;
}
$smarty->assign("executivePerformanceAuth", $executivePerformanceAuth);


/* * **project image construction update****** */
if (isset($_SESSION['processAssignmentForConstImg'])) {
    $processAssignmentForConstImg = $_SESSION['processAssignmentForConstImg'];
} else {
    $processAssignmentForConstImg = isUserPermitted('construction-image-assignment', 'access');
    $_SESSION['processAssignmentForConstImg'] = $processAssignmentForConstImg;
}
$processAssignmentImg = 0;
if ($processAssignmentForConstImg == 1)
    $processAssignmentImg = 1;
$smarty->assign("processAssignmentForConstImg", $processAssignmentImg);

if (isset($_SESSION['constructionLead'])) {
    $constructionLead = $_SESSION['constructionLead'];
} else {
    $constructionLead = isUserPermitted('construction-img-lead', 'access');
    $_SESSION['constructionLead'] = $constructionLead;
}
$processAssignmentLead = 0;
if ($constructionLead == 1)
    $processAssignmentLead = 1;
$smarty->assign("processAssignmentLead", $processAssignmentLead);

if (isset($_SESSION['constructionExec'])) {
    $constructionExec = $_SESSION['constructionExec'];
} else {
    $constructionExec = isUserPermitted('construction_image_assign_exec', 'access');
    $_SESSION['constructionExec'] = $constructionExec;
}
$processAssignmentExec = 0;
if ($constructionExec == 1)
    $processAssignmentExec = 1;
$smarty->assign("processAssignmentExec", $processAssignmentExec);


if (isset($_SESSION['skipUpdationYesNo'])) {
    $skipUpdationYesNo = $_SESSION['skipUpdationYesNo'];
} else {
    $skipUpdationYesNo = isUserPermitted('skip-updation-cycle', 'skipAccess');
    $_SESSION['skipUpdationYesNo'] = $skipUpdationYesNo;
}
$skipUpdtnCycle = 0;
if ($skipUpdationYesNo == 1)
    $skipUpdtnCycle = 1;
$smarty->assign("skipUpdtnCycle", $skipUpdtnCycle);


if (isset($_SESSION['micrositeExec'])) {
    $micrositeExec = $_SESSION['micrositeExec'];
} else {
    $micrositeExec = isUserPermitted('microsite', 'microsite-access');
    $_SESSION['micrositeExec'] = $micrositeExec;
}
$micrositeFlgExec = 0;
if ($micrositeExec == 1)
    $micrositeFlgExec = 1;
$smarty->assign("micrositeFlgExec", $micrositeFlgExec);

if (isset($_SESSION['mapVarifyAuth'])) {
    $mapVarifyAuth = $_SESSION['mapVarifyAuth'];
} else {
    $mapVarifyAuth = isUserPermitted('map-varification', 'manage');    
    $_SESSION['mapVarifyAuth'] = $mapVarifyAuth;
}
$smarty->assign("mapVarifyAuth", $mapVarifyAuth);

if (isset($_SESSION['contentDeliveryManage'])) {
    $contentDeliveryManage = $_SESSION['contentDeliveryManage'];
} else {
    $contentDeliveryManage = isUserPermitted('content-delivery-system', 'manage');    
    $_SESSION['contentDeliveryManage'] = $contentDeliveryManage;
}
$smarty->assign("contentDeliveryManage", $contentDeliveryManage);

if (isset($_SESSION['contentDeliveryAccess'])) {
    $contentDeliveryAccess = $_SESSION['contentDeliveryAccess'];
} else {
    $contentDeliveryAccess = isUserPermitted('content-delivery-system', 'access');    
    $_SESSION['contentDeliveryAccess'] = $contentDeliveryAccess;
}
$smarty->assign("contentDeliveryAccess", $contentDeliveryAccess);

    $mapVarifyAuth = isUserPermitted('map-varification', 'manage');
    $smarty->assign("mapVarifyAuth", $mapVarifyAuth );
    
    $projectManageAuth = isUserPermitted('project-management', 'access'); 
    $smarty->assign("projectManageAuth", $projectManageAuth);
    
    $townshipManageAuth = isUserPermitted('township-management', 'access');
    $smarty->assign("townshipManageAuth", $townshipManageAuth);    
    
?>
