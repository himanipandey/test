<?php

	if(!isset($_REQUEST['builderId']))
		$_REQUEST['builderId'] ='';
	$builderId			=	$projectDetails[0]['BUILDER_ID'];
	$builderName		=	$projectDetails[0]['BUILDER_NAME'];
	$arrContact 		= 	BuilderContactInfo($builderId);
        $agentId = $_SESSION['adminId']; 
	$ProjectList = project_list($builderId);
	$smarty->assign("ProjectList", $ProjectList);

    $smarty->assign("arrContact", $arrContact);
    $smarty->assign("builderName", $builderName);
    $smarty->assign("builderId", $builderId);
    $smarty -> assign("agentId", $agentId);
    $smarty->assign("arrCampaign", $arrCampaign);
?>