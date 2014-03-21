<?php

    /*******Auth code******/
    $builderAuth = isUserPermitted('builder', 'manage');
    $smarty->assign("builderAuth", $builderAuth);
    
    $bulkProjUpdateAuth = isUserPermitted('bulk-project-update', 'read');
    $smarty->assign("bulkProjUpdateAuth", $bulkProjUpdateAuth);
    
    $cityAuth = isUserPermitted('city', 'manage');
    $smarty->assign("cityAuth", $cityAuth);
    
    $dailyPerformanceReportAuth = isUserPermitted('daily-performance-report', 'read');
    $smarty->assign("dailyPerformanceReportAuth", $dailyPerformanceReportAuth);
    
    $dataCollectionFlowAuth = isUserPermitted('data-clloection-flow', 'read');
    $smarty->assign("dataCollectionFlowAuth", $dataCollectionFlowAuth);
    
    $imageAuth = isUserPermitted('image', 'add');
    $smarty->assign("imageAuth", $imageAuth);
    
    $labelAuth = isUserPermitted('label', 'add');
    $smarty->assign("labelAuth", $labelAuth);
    
    $localityAuth = isUserPermitted('locality', 'manage');
    $smarty->assign("localityAuth", $localityAuth);
    
    $migrateAuth = isUserPermitted('migrate', 'perform');
    $smarty->assign("migrateAuth", $migrateAuth);
    
    $prokectAuth = isUserPermitted('project', 'manage');
    $smarty->assign("prokectAuth", $prokectAuth);
    
    $suburbAuth = isUserPermitted('suburb', 'manage');
    $smarty->assign("suburbAuth", $suburbAuth);
    
    $urlAuth = isUserPermitted('url', 'redirect');
    $smarty->assign("urlAuth", $urlAuth);
        
    $brokerAuth = isUserPermitted('broker', 'manage');
    $smarty->assign("brokerAuth", $brokerAuth);
    
    $myProjectsAuth = isUserPermitted('my-projects', 'read');
    $smarty->assign("myProjectsAuth", $myProjectsAuth);
    
    $urlEditAuth = isUserPermitted('url-edit', 'url-edit');
    $smarty->assign("urlEditAuth", $urlEditAuth);
     $urlEditAccess = 0;
    if($urlEditAuth == true)
        $urlEditAccess = 1;
    $smarty->assign("urlEditAccess",$urlEditAccess);
    
    $specialAccessAuth = isUserPermitted('projectSpecialAttrs', 'manage');
    $smarty->assign("specialAccessAuth", $specialAccessAuth);
    $specialAccess = 0;
    if($specialAccessAuth == true)
        $specialAccess = 1;
    $smarty->assign("specialAccess",$specialAccess);
    
    $localityCleanedAuth = isUserPermitted('locality-cleaned', 'access');

    $localityCleanedAccess = 0;
    if($localityCleanedAuth == true)
        $localityCleanedAccess = 1;
    $smarty->assign("localityCleanedAccess",$localityCleanedAccess);
    
    $isMetricsAuth = isUserPermitted('is-metrics', 'read');

    $isMetricsAccess = 0;
    if($isMetricsAuth == true)
        $isMetricsAccess = 1;
    $smarty->assign("isMetricsAccess",$isMetricsAccess);
    
    $isupplyEditPermissionAuth = isUserPermitted('supplyEditPermission', 'access');
    $supplyEditPermissionAccess = 0;
    if($isupplyEditPermissionAuth == true)
        $supplyEditPermissionAccess = 1;
    $smarty->assign("supplyEditPermissionAccess",$supplyEditPermissionAccess);
    
    $priorityMgmtPermissionAuth = isUserPermitted('priorityManagement', 'access');
    $priorityMgmtPermissionAccess = 0;
    if($priorityMgmtPermissionAuth == 1)
        $priorityMgmtPermissionAccess = 1;
    $smarty->assign("priorityMgmtPermissionAccess",$priorityMgmtPermissionAccess);
   
    ////////////////////////////report error//////////////////
    $isReportErrorPermissionAuth = isUserPermitted('report-error', 'access');
    $reportErrorPermissionAccess = 0;
    if($isReportErrorPermissionAuth == true)
        $reportErrorPermissionAccess = 1;
    $smarty->assign("reportErrorPermissionAccess",$reportErrorPermissionAccess);

    
    /*************datacollection callcenter and survey auth***************/
    $callCenterAuth = isUserPermitted('callcenter', 'access');
    $smarty->assign("callCenterAuth", $callCenterAuth);
    
    $myProjectsCallCenterAuth = isUserPermitted('myprojects_callcenter', 'access');
    $smarty->assign("myProjectsCallCenterAuth", $myProjectsCallCenterAuth);
    
    
    $surveyAuth = isUserPermitted('survey', 'access');
    $smarty->assign("surveyAuth", $surveyAuth);
    
    $myProjectsSurveyAuth = isUserPermitted('myprojects_survey', 'access');
    $smarty->assign("myProjectsSurveyAuth", $myProjectsSurveyAuth);
    
    $userManagement = isUserPermitted('user-management', 'access');
    $smarty->assign("userManagement", $userManagement);
    
    $executivePerformanceAuth = isUserPermitted('executive-performance', 'access');
    $smarty->assign("executivePerformanceAuth", $executivePerformanceAuth);
    

?>
