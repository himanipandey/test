<?php 
    $projectId  = $_GET['projectId'];
    if (isset($_REQUEST['btnExit'])) {
        header("Location:show_project_details.php?projectId=$projectId");
    }
    $allBrokerByProject   = getBrokerByProject($projectId);
    $arrBrokerList = array();
    $arrProjectByBroker = array();
    foreach($allBrokerByProject as $key=>$val){
        include("dbConfig_crm.php");
         $brikerList = getBrokerDetailById($key);
         $arrBrokerList[$key] = $brikerList;
         $arrProjectByBroker[$key] = getProjectByBroker($key);
    }
     include("dbConfig.php");
     $arrBrokerPriceByProject = getBrokerPriceByProject($projectId);
     $minMaxSum = array();
     $maxEffectiveDt = $arrBrokerPriceByProject[0]['EFFECTIVE_DATE'];
     $latestMonthAllBrokerPrice = array();
     $oneMonthAgoPrice = array();
     $twoMonthAgoPrice = array();
     
     $arrCalingSecondary = fetchProjectCallingLinks($projectId,'secondary');
     $smarty->assign("arrCalingSecondary", $arrCalingSecondary);
     
     /******one and two month age date create******/
     $dateBreak = explode("-",$maxEffectiveDt);
     $oneMonthAgo = mktime(0, 0, 0, $dateBreak[1]-1, 1, $dateBreak[0]);
     $oneMonthAgoDt = date('Y-m',$oneMonthAgo)."-01 00:00:00";
     $twoMonthAgo = mktime(0, 0, 0, $dateBreak[1]-2, 1, $dateBreak[0]);
     $twoMonthAgoDt = date('Y-m',$twoMonthAgo)."-01 00:00:00";
     /******end one and two month age date create******/
     $brokerIdList = array();
     foreach($arrBrokerPriceByProject as $k=>$v) {
         if ($maxEffectiveDt == $v['EFFECTIVE_DATE']) {
            $minMaxSum[$v['UNIT_TYPE']]['minPrice'][] = $v['MIN_PRICE'];
            $minMaxSum[$v['UNIT_TYPE']]['maxPrice'][] = $v['MAX_PRICE'];
            if(count($latestMonthAllBrokerPrice[$v['UNIT_TYPE']][$v['BROKER_ID']]['minPrice']) == 0) {
                $latestMonthAllBrokerPrice[$v['UNIT_TYPE']][$v['BROKER_ID']]['minPrice'] = $v['MIN_PRICE'];
                $latestMonthAllBrokerPrice[$v['UNIT_TYPE']][$v['BROKER_ID']]['maxPrice'] = $v['MAX_PRICE'];
            }
            if (!in_array($v['BROKER_ID'],$brokerIdList)) {
                $brokerIdList[] = $v['BROKER_ID'];
            }
         }
        
         if($oneMonthAgoDt == $v['EFFECTIVE_DATE']){
            $oneMonthAgoPrice[$v['UNIT_TYPE']]['minPrice'][] = $v['MIN_PRICE'];
            $oneMonthAgoPrice[$v['UNIT_TYPE']]['maxPrice'][] = $v['MAX_PRICE'];
         }
         
         if($twoMonthAgoDt == $v['EFFECTIVE_DATE']){
            $twoMonthAgoPrice[$v['UNIT_TYPE']]['minPrice'][] = $v['MIN_PRICE'];
            $twoMonthAgoPrice[$v['UNIT_TYPE']]['maxPrice'][] = $v['MAX_PRICE'];
         }
     }
     
     $projectDetails = projectDetailById($projectId);
     
     $smarty->assign("latestMonthAllBrokerPrice", $latestMonthAllBrokerPrice);
     $smarty->assign("oneMonthAgoPrice", $oneMonthAgoPrice);
     $smarty->assign("twoMonthAgoPrice", $twoMonthAgoPrice);
     $smarty->assign("oneMonthAgoDt",  $oneMonthAgoDt);
     $smarty->assign("twoMonthAgoDt", $twoMonthAgoDt);
     $smarty->assign("minMaxSum", $minMaxSum);
     $smarty->assign("allBrokerByProject", $arrBrokerList);
     $smarty->assign("brokerIdList", $brokerIdList);
     $smarty->assign("arrProjectByBroker", $arrProjectByBroker);
     $smarty->assign("maxEffectiveDt", $maxEffectiveDt);
     $smarty->assign("projectDetails", $projectDetails);
     $smarty->assign("arrCampaign", $arrCampaign);
     $smarty->assign("projectId", $projectId);
     
     //code for distinct unit for a project
    $arrProjectType = fetch_projectOptions($projectId);
    $arrPType = array(); 
    foreach($arrProjectType as $val){
        $exp = explode("-",$val);
        if(!in_array(trim($exp[0]),$arrPType))
            array_push($arrPType,trim($exp[0]));
    }
    $smarty->assign("arrPType", $arrPType);
?>
