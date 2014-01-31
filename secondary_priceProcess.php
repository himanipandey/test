<?php 
    $projectId  = $_GET['projectId'];
    if (isset($_REQUEST['btnExit'])) {
        header("Location:show_project_details.php?projectId=$projectId");
    }
    $allBrokerByProject   = getBrokerByProject($projectId);
    $arrBrokerList = array();
    $arrProjectByBroker = array();
    foreach($allBrokerByProject as $key=>$val){
         $brikerList = getBrokerDetailById($key);
         $arrBrokerList[$key] = $brikerList;
         $arrProjectByBroker[$key] = getProjectByBroker($key);
    }
    
    $arrCalingSecondary = fetchProjectCallingLinks($projectId,'secondary',1);
     $smarty->assign("arrCalingSecondary", $arrCalingSecondary);
     
     $brokerIdList = array();
     $maxEffectiveDtAll = '';
     
     $phase_prices = getBrokerPriceByProject($projectId);
     
     $dateBreak = explode("-",$maxEffectiveDtAll );
     $oneMonthAgo = mktime(0, 0, 0, $dateBreak[1]-1, 1, $dateBreak[0]);
     $oneMonthAgoDt = date('Y-m',$oneMonthAgo)."-01 00:00:00";
     $twoMonthAgo = mktime(0, 0, 0, $dateBreak[1]-2, 1, $dateBreak[0]);
     $twoMonthAgoDt = date('Y-m',$twoMonthAgo)."-01 00:00:00";
    
     
     $projectDetails = ResiProject::virtual_find($projectId);
     $builderName = ResiBuilder::getBuilderById($projectDetails->builder_id);
     $localityName = Locality::getLocalityById($projectDetails->locality_id);
     $smarty->assign("builderName", $builderName);
     $smarty->assign("localityName", $localityName);
     
     $smarty->assign("oneMonthAgoDt",  $oneMonthAgoDt);
     $smarty->assign("twoMonthAgoDt", $twoMonthAgoDt);
     
     $smarty->assign('phase_prices', $phase_prices);
    
     $smarty->assign("brokerIdList", $brokerIdList);
     

     $smarty->assign("allBrokerByProject", $arrBrokerList);
     $smarty->assign("arrProjectByBroker", $arrProjectByBroker);
     $smarty->assign("maxEffectiveDt", $maxEffectiveDtAll );
     $smarty->assign("projectDetails", $projectDetails);
     $smarty->assign("arrCampaign", $arrCampaign);
     $smarty->assign("projectId", $projectId);
     $builderName = ResiBuilder::getBuilderById($projectDetails->builder_id);
     $smarty->assign("builderName", $builderName[0]->builder_name);
     $locality = Locality::getLocalityById($projectDetails->locality_id);
     $suburb = Suburb::getSuburbById($locality[0]->suburb_id);
     $city = City::getCityById($suburb[0]->city_id);
     $smarty->assign("cityName", $city[0]->label);
     $smarty->assign("localityName", $locality[0]->label);
     
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
