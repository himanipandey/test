<?php
$CityDataArr = City::CityArr();
$ProjectTypeArr = ResiProjectType::ProjectTypeArr();
$BankListArr = BankList::arrBank();
$enum_value = ResiProject::projectStatusMaster();
$AmenitiesArr = AmenitiesList();

$projectId = $_REQUEST['projectId'];
if (!isset($_REQUEST['btnExit']))
    $_REQUEST['btnExit'] = '';
if ($_REQUEST['btnExit'] == "Exit") {
    header("Location:ProjectList.php?projectId=" . $projectId);
}

//$lastUpdatedDetail = lastUpdatedAuditDetail($projectId); //To Do
//$smarty->assign("lastUpdatedDetail", $lastUpdatedDetail);//To Do

$arrCalingPrimary = fetchProjectCallingLinks($projectId, 'primary');
$smarty->assign("arrCalingPrimary", $arrCalingPrimary);

/* * ****start display other pricing***** */
$otherPricing = fetch_other_price($projectId);
$smarty->assign("otherPricing", $otherPricing);
/* * ****end display other pricing***** */

//$ProjectPhases = ResiProjectPhase::get_phase_option_hash_by_project($projectId); //To Do
$PhaseOptionHash = $ProjectPhases[1];
$ProjectPhases = $ProjectPhases[0];
$ProjectOptionDetail	=	ProjectOptionDetail($projectId);
$PreviousMonthsData	=	getPrevMonthProjectData($projectId);
$PreviousMonthsAvailability = getFlatAvailability($projectId);
$smarty->assign("ProjectOptionDetail",$ProjectOptionDetail);
$smarty->assign("PreviousMonthsData",$PreviousMonthsData);
$smarty->assign("PreviousMonthsAvailability",$PreviousMonthsAvailability);
//$smarty->assign("ProjectPhases",$ProjectPhases); //To Do
$smarty->assign("PhaseOptionHash",$PhaseOptionHash);

$arrOnlyPreviousMonthData = array();
foreach($PreviousMonthsData as $k=>$v) { 
    if( $k != 'current' && $k != 'latest')
        $arrOnlyPreviousMonthData[] = $k;
}
$smarty->assign("arrOnlyPreviousMonthData",$arrOnlyPreviousMonthData);

$arrAvaiPreviousMonthData = array();
foreach($PreviousMonthsAvailability as $k=>$v) { 
    if( $k != 'current' && $k != 'latest')
        $arrAvaiPreviousMonthData[] = $k;
}
$smarty->assign("arrAvaiPreviousMonthData",$arrAvaiPreviousMonthData);


$ProjectAmenities = ProjectAmenities($projectId, $arrNotninty, $arrDetail, $arrninty);

$project = ResiProject::virtual_find($projectId,array('get_extra_scope'=>true));
$projectDetail = $project->to_custom_array();
$arrSpecification = array($projectDetail);

$smarty->assign("arrNotninty", $arrNotninty);
$smarty->assign("arrDetail", $arrDetail);
$smarty->assign("arrninty", $arrninty);
$smarty->assign("BankListArr", $BankListArr);


$smarty->assign("arrNotninty", $arrNotninty);

$path = "";
$smarty->assign("path", $path);

$ImageDataListingArr = allProjectImages($projectId);
$smarty->assign("ImageDataListingArr", $ImageDataListingArr);

$ImageDataListingArrFloor = allProjectFloorImages($projectId);
$smarty->assign("ImageDataListingArrFloor", $ImageDataListingArrFloor);

$towerDetail = fetch_towerDetails($projectId);

$towerDtlPhWise = array();
$towerNoPh = array();
foreach ($towerDetail as $val) {
    if ($val['PHASE_NAME'] == '') {
        $towerNoPh['NoPhase'][] = $val;
    } else {
        $towerDtlPhWise[$val['PHASE_NAME']][] = $val;
    }
}

$smarty->assign("towerDetail", array_merge($towerNoPh, $towerDtlPhWise));
//To Do
//$arrAudit = AuditTblDataByTblName('resi_project_tower_details', $projectId); //To Do
//$smarty->assign("arrAudit", $arrAudit); //To Do

$fetch_projectOptions = fetch_projectOptions($projectId);
$smarty->assign("fetch_projectOptions", $fetch_projectOptions);

if (!isset($_REQUEST['phaseId']))
    $_REQUEST['phaseId'] = '';
if ($_REQUEST['phaseId'] != -1)
    $phaseId = $_REQUEST['phaseId'];

/* * *****supply code start here********* */
/******* To Do
$supplyAllArray = array();
$res = ProjectSupply::projectSupplyForProjectPage($projectId);
die("ASDFGHJKL");
$arrPhaseCount = array();
$arrPhaseTypeCount = array();

foreach ($res as $data) {
    if ($data['PHASE_NAME'] == '')$data['PHASE_NAME'] = 'noPhase';
    $supplyAllArray[$data['PHASE_NAME']][$data['PROJECT_TYPE']][] = $data;
    $arrPhaseCount[$data['PHASE_NAME']][] = $data['PROJECT_TYPE'];
    $arrPhaseTypeCount[$data['PHASE_NAME']][$data['PROJECT_TYPE']][] = '';
}
*/
$isSupplyLaunchEdited = ProjectSupply::isSupplyLaunchEdited($projectId);

//$smarty->assign("arrPhaseCount", $arrPhaseCount);
//$smarty->assign("arrPhaseTypeCount", $arrPhaseTypeCount);
//$smarty->assign("supplyAllArray", $supplyAllArray);
$smarty->assign("isSupplyLaunchEdited", $isSupplyLaunchEdited);


// Project Phases
$phaseDetail = fetch_phaseDetails($projectId);
$bedroomDetails = ProjectBedroomDetail($projectId);
$smarty->assign("BedroomDetails", $bedroomDetails);
$phases = Array();
foreach ($phaseDetail as $k => $val) {
    $p = Array();
    $p['id'] = $val['PHASE_ID'];
    $p['name'] = $val['PHASE_NAME'];
    array_push($phases, $p);
}
$smarty->assign("phases", $phases);
$smarty->assign("phaseId", $phaseId);
if ($phaseId) {  //To Do
    $current_phase = phaseDetailsForId($phaseId);
    $phase_towers = fetch_towers_in_phase($projectId);

    $arrTower = array();
    foreach ($phase_towers as $key => $val) {
        array_push($arrTower, $val['TOWER_ID']);
    }
    $smarty->assign("arrTower", $arrTower);
    $phase_quantity = ProjectSupply::projectTypeGroupedQuantityForPhase($projectId, $phaseId);
    $smarty->assign("FlatsQuantity", explode_bedroom_quantity($phase_quantity['Apartment']));
    $smarty->assign("VillasQuantity", explode_bedroom_quantity($phase_quantity['Villa']));
    $smarty->assign("PlotQuantity", explode_bedroom_quantity($phase_quantity['Plot']));
    // Assign vars for smarty
    $smarty->assign("phase_name", $current_phase[0]['PHASE_NAME']);
    $smarty->assign("phase_launch_date", $current_phase[0]['LAUNCH_DATE']);
    $smarty->assign("phase_completion_date", $current_phase[0]['COMPLETION_DATE']);
    $smarty->assign("phase_towers", $phase_towers);
    $smarty->assign("phase_remark", $current_phase[0]['REMARKS']);
}
// End of Project Phases
$smarty->assign("anchor", "");
if (!isset($_REQUEST['towerId']))
    $_REQUEST['towerId'] = '';
if ($_REQUEST['towerId'] != '') {
    $anchor = "anchor";
    $smarty->assign("anchor", $anchor);
    $smarty->assign("towerId", $_GET['towerId']);

    $towerDetailForId = towerDetailsForId($_GET['towerId']);
    $smarty->assign("towerId", $_GET['towerId']);
    $smarty->assign("no_of_floors", $towerDetailForId[0]['NO_OF_FLOORS']);
    $smarty->assign("stilt", $towerDetailForId[0]['STILT']);
    $smarty->assign("no_of_flats_per_floor", $towerDetailForId[0]['NO_OF_FLATS']);
    $smarty->assign("towerface", $towerDetailForId[0]['TOWER_FACING_DIRECTION']);
    $smarty->assign("completion_date", $towerDetailForId[0]['ACTUAL_COMPLETION_DATE']);
    $smarty->assign("remark", $towerDetailForId[0]['REMARKS']);
    $smarty->assign("edit", $towerDetailForId[0]['NO_OF_FLOORS']);

    $arrAudit = AuditTblDataByTblName('resi_project_tower_details', $projectId);
    $smarty->assign("arrAudit", $arrAudit);
}

$smarty->assign("arrSpecification", $arrSpecification);


$smarty->assign("projectId", $projectId);
$smarty->assign("ProjectTypeArr", $ProjectTypeArr);
$smarty->assign("enum_value", $enum_value);
$smarty->assign("AmenitiesArr", $AmenitiesArr);

$projectDetails = array();
 $qry = "SELECT rp.*,ps.project_status,ps.display_name,t.township_name,mps.name as PROJECT_STAGE,
    mpp.name as PROJECT_PHASE
    FROM " . RESI_PROJECT . " rp
    left join project_status_master ps on rp.project_status_id = ps.id
    left join townships t on rp.township_id = t.id
    left join master_project_stages mps on rp.project_stage_id = mps.id
    left join master_project_phases mpp on rp.project_phase_id = mpp.id
    WHERE rp.PROJECT_ID = '" . $projectId . "' and rp.version = 'Cms'";
$res = mysql_query($qry) or die(mysql_error());
if (!mysql_num_rows($res) > 0) {
    $smarty->assign("error", "error");
}

while ($data = mysql_fetch_array($res)) {
    $projectStage = $data['PROJECT_STAGE'];
    array_push($projectDetails, $data);
}

$joinbank = "inner join bank_list bl on project_banks.bank_id = bl.bank_id";
$bankList = ProjectBanks::find('all',array('joins' => $joinbank,'conditions'=>
    array('project_id = ?', $projectDetails[0]['PROJECT_ID']),'select' => 
                    'project_banks.*,bl.bank_name'));
$smarty->assign("bankList", $bankList);



if ($projectDetails[0]['PROJECT_STAGE'] == 'NewProject') {
    $phse = 'newP';
} else if ($projectDetails[0]['PROJECT_STAGE_ID'] == '1') {
    $phse = 'noS';
} else if ($projectDetails[0]['PROJECT_STAGE_ID'] == '3') {
    $phse = 'updation';
} else if ($projectDetails[0]['PROJECT_STAGE_ID'] == '4') {
    $phse = 'updation';
}
$UpdationArr = updationCycleTable();
$projectLabel = '';
if ($phse == 'updation') {
    foreach ($UpdationArr as $k => $v) {
        if ($projectDetails[0]['UPDATION_CYCLE_ID'] == $UpdationArr[$k]['UPDATION_CYCLE_ID'])
            $projectLabel = $UpdationArr[$k]['LABEL'];
    }
    if ($projectDetails[0]['UPDATION_CYCLE_ID'] == null) {
        $projectLabel = 'No Label';
    }
} else {
    $projectLabel = 'No Label';
}
$smarty->assign("projectLabel", $projectLabel);

$suburbSelect = Suburb::SuburbArr($projectDetails[0]['CITY_ID']);
//$localitySelect = Locality::localityList($projectDetails[0]['SUBURB_ID']);//To Do

$locality = Locality::getLocalityById($projectDetails[0]['LOCALITY_ID']);
$smarty->assign('locality',$locality[0]->label);
$suburb = Suburb::getSuburbById($locality[0]->suburb_id);
$smarty->assign('suburb',$suburb[0]->label);
$city = City::getCityById($suburb[0]->city_id);
$smarty->assign('city',$city[0]->label);
$builderDetail = fetch_builderDetail($projectDetails[0]['BUILDER_ID']);
$smarty->assign("builderDetail", $builderDetail);
/* * ***code for promised completion date******* */
$expCompletionDate = costructionDetail($projectId);
$completionDate = '';
if (count($expCompletionDate['EXPECTED_COMPLETION_DATE']) > 0) {
    date_default_timezone_set('Asia/Calcutta');
    $dateProject = new DateTime($projectDetails[0]['PROMISED_COMPLETION_DATE']);
    $dateConstruct = new DateTime($expCompletionDate['EXPECTED_COMPLETION_DATE']);
    if ($dateProject < $dateConstruct)
        $completionDate = $expCompletionDate['EXPECTED_COMPLETION_DATE'];
    else
        $completionDate = $projectDetails[0]['PROMISED_COMPLETION_DATE'];
}
else
    $completionDate = $projectDetails[0]['PROMISED_COMPLETION_DATE'];

$smarty->assign("completionDate", $completionDate);
/* * ***code for promised completion date******* */

//$smarty->assign("localitySelect", $localitySelect); //To Do
$smarty->assign("projectDetails", $projectDetails);
$smarty->assign("CityDataArr", $CityDataArr);
$smarty->assign("suburbSelect", $suburbSelect);

/******code for project comment fetch from commeny history table*****/
$cycleId = $projectDetails[0]['PROJECT_STAGE'];
$projectComments = CommentsHistory::getCommentHistoryByProjectIdCycleId($projectId, $cycleId);
$smarty->assign("projectComments", $projectComments);

$projectOldComments = CommentsHistory::getOldCommentHistoryByProjectId($projectId);
$smarty->assign("projectOldComments", $projectOldComments);
/******end code for project comment fetch from commeny history table*****/

/**start code for fetch offer heading and desc from db**/
    $qryOfferFetch = "select * from project_offers where project_id = $projectId";
    $resOfferFetch = mysql_query($qryOfferFetch) or die(mysql_error());
    $dataOffer = mysql_fetch_assoc($resOfferFetch);
    $special_offer = $dataOffer['OFFER'];
    $offer_heading = $dataOffer['OFFER_HEADING'];
    $offer_desc = $dataOffer['OFFER_DESC'];
    $smarty->assign("special_offer", $special_offer);
    $smarty->assign("offer_heading", $offer_heading);
    $smarty->assign("offer_desc", $offer_desc);
    /**end code for fetch offer heading and desc from db**/
    
if (!isset($_GET['towerId']))
    $_GET['towerId'] = '';
if ($_GET['towerId'] != '') {
    $tower_detail = towerDetail($_GET['towerId']);
    $arrAudit = AuditTblDataByTblName('resi_proj_tower_construction_status', $projectId);
    $smarty->assign("tower_detail", $tower_detail);
    $smarty->assign("arrAudit", $arrAudit);
}

$newPhase = array(
    "DataCollection" => "NewProject",
    "NewProject" => "DcCallCenter",
    "DcCallCenter" => "Audit1",
    "Audit1" => "Audit2",
    "Audit2" => "Complete",
);


$updatePhase = array(
    "DataCollection" => "Audit1",
    "Audit1" => "Audit2",
    "Audit2" => "Complete",
);

if (!isset($_POST['forwardFlag']))
    $_POST['forwardFlag'] = '';
if ($_POST['forwardFlag'] == 'yes') {
    $returnURLPID = $_POST['returnURLPID'];
    $currentPhase = $_POST['currentPhase'];
    $reviews = $_POST['reviews'];
    foreach ($newPhase as $k => $v) {
        $qry = "select * from master_project_phases where name = '".$k."'";
        $res = mysql_query($qry) or die(mysql_error());
        $phaseId = mysql_fetch_assoc($res);
        
        $qryStg = "select * from master_project_stages where name = '".$projectStage."'";
        $resStg = mysql_query($qryStg) or die(mysql_error());
        $stageId = mysql_fetch_assoc($resStg);
        
        $qryCurrent = "select * from master_project_phases where name = '".$currentPhase."'";
        $resCurrent = mysql_query($qryCurrent) or die(mysql_error());
        $phaseIdCurrent = mysql_fetch_assoc($resCurrent);
        
        $qryNext = "select * from master_project_phases where name = '".$v."'";
        $resNext = mysql_query($qryNext) or die(mysql_error());
        $phaseIdNext = mysql_fetch_assoc($resNext);
        if ($phaseIdCurrent['id'] == $phaseId['id']) {
            updateProjectPhase($projectId, $phaseIdNext['id'], $stageId['id']);
            $arrCommentTypeValue['Audit'] = $reviews;
            CommentsHistory::insertUpdateComments($projectId, $arrCommentTypeValue, $projectStage);
        }
    }
    header("Location:$returnURLPID");
}
if ($_POST['forwardFlag'] == 'update') {
    $returnURLPID = $_POST['returnURLPID'];
    $currentPhase = $_POST['currentPhase'];
    $reviews = $_POST['reviews'];
    foreach ($updatePhase as $k => $v) {
        if ($currentPhase == $k) {
            
            $qry = "select * from master_project_phases where name = '".$v."'";
            $res = mysql_query($qry) or die(mysql_error());
            $phaseId = mysql_fetch_assoc($res);
            $qryStg = "select * from master_project_stages where name = '".$projectStage."'";
            $resStg = mysql_query($qryStg) or die(mysql_error());
            $stageId = mysql_fetch_assoc($resStg);
            updateProjectPhase($projectId, $phaseId['id'], $stageId['id']);
            $arrCommentTypeValue['Audit'] = $reviews;
            CommentsHistory::insertUpdateComments($projectId, $arrCommentTypeValue, $projectStage);
        }
    }
    header("Location:$returnURLPID");
}

if ($_POST['forwardFlag'] == 'no') {
    $returnURLPID = $_POST['returnURLPID'];
    $currentPhase = $_POST['currentPhase'];
    $reviews = $_POST['reviews'];
    /* foreach ($newPhase as $k => $v) {
      if($currentPhase == $newPhase[$k]){
      updateProjectPhase($projectId, $k);
      }
      } */

    // Reverted back from audit
    if ($_REQUEST['returnStage'] == 'NewProject' AND ($_REQUEST['currentPhase'] == 'Audit1' OR $_REQUEST['currentPhase'] == 'Audit2'))
        $phaseName = 'DcCallCenter';
    else
        $phaseName = 'DataCollection';

//  Get back older history
    $qry = "select * from master_project_phases where name = '".$phaseName."'";
    $res = mysql_query($qry) or die(mysql_error());
    $phaseId = mysql_fetch_assoc($res);
    
    $qryStg = "select * from master_project_stages where name = '".$projectStage."'";
    $resStg = mysql_query($qryStg) or die(mysql_error());
    $stageId = mysql_fetch_assoc($resStg);
    $history = ProjectStageHistory::find("all", array("conditions" => "project_id = {$projectId} and project_phase_id in
    (1,3,8) and project_stage_id = {$stageId['id']}", "limit" => 1, "order" => "date_time desc"));
//  If old history is found
    if(count($history) > 0){
        $history = $history[0];
        $lastAssignemnt = ProjectAssignment::find("all", array("conditions" => array("movement_history_id" => $history->history_id),
            "limit" => 1, "order" => "UPDATION_TIME desc"));
    }
//  if old history is not found
    else{
        $lastAssignemnt = array();
    }


    updateProjectPhase($projectId, $phaseId['id'], $stageId['id'], TRUE);

//  Assigning back to same user if assignment is found
    if(count($lastAssignemnt) > 0){
        $lastAssignemnt = $lastAssignemnt[0];
        $newAssignment = new ProjectAssignment();
        $project = ResiProject::find($projectId);
        $newAssignment->movement_history_id = $project->movement_history_id;
        $newAssignment->assigned_to = $lastAssignemnt->assigned_to;
        $newAssignment->assigned_by = $lastAssignemnt->assigned_by;
        $newAssignment->status = "notAttempted";
        $newAssignment->creation_time = Date('Y-m-d H:i:s');
        $newAssignment->updation_time = Date('Y-m-d H:i:s');
        $newAssignment->save();
    }

    header("Location:$returnURLPID");
}
include('builder_contact_info_process.php');

/* * code for secondary price dispaly*********** */
include("function/resale_functions.php");
$allBrokerByProject = getBrokerByProject($projectId);
$arrBrokerList = array();

foreach ($allBrokerByProject as $key => $val) {
    include("dbConfig_crm.php");
    $brikerList = getBrokerDetailById($key);
    $arrBrokerList[$key] = $brikerList;
}
include("dbConfig.php");
$arrBrokerPriceByProject = getBrokerPriceByProject($projectId);

$minMaxSum = array();
$maxEffectiveDt = $arrBrokerPriceByProject[0]['EFFECTIVE_DATE'];
$latestMonthAllBrokerPrice = array();
$oneMonthAgoPrice = array();
$twoMonthAgoPrice = array();

$arrCalingSecondary = fetchProjectCallingLinks($projectId, 'secondary');
$smarty->assign("arrCalingSecondary", $arrCalingSecondary);

/* * ****one and two month age date create***** */
$dateBreak = explode("-", $maxEffectiveDt);
$oneMonthAgo = mktime(0, 0, 0, $dateBreak[1] - 1, 1, $dateBreak[0]);
$oneMonthAgoDt = date('Y-m', $oneMonthAgo) . "-01 00:00:00";
$twoMonthAgo = mktime(0, 0, 0, $dateBreak[1] - 2, 1, $dateBreak[0]);
$twoMonthAgoDt = date('Y-m', $twoMonthAgo) . "-01 00:00:00";
/* * ****end one and two month age date create***** */
$brokerIdList = array();
foreach ($arrBrokerPriceByProject as $k => $v) {
    if ($maxEffectiveDt == $v['EFFECTIVE_DATE']) {
        $minMaxSum[$v['UNIT_TYPE']]['minPrice'][] = $v['MIN_PRICE'];
        $minMaxSum[$v['UNIT_TYPE']]['maxPrice'][] = $v['MAX_PRICE'];

        $latestMonthAllBrokerPrice[$v['UNIT_TYPE']][$v['BROKER_ID']]['minPrice'] = $v['MIN_PRICE'];
        $latestMonthAllBrokerPrice[$v['UNIT_TYPE']][$v['BROKER_ID']]['maxPrice'] = $v['MAX_PRICE'];
        if (!in_array($v['BROKER_ID'], $brokerIdList)) {
            $brokerIdList[] = $v['BROKER_ID'];
        }
    }

    if ($oneMonthAgoDt == $v['EFFECTIVE_DATE']) {
        $oneMonthAgoPrice[$v['UNIT_TYPE']]['minPrice'][] = $v['MIN_PRICE'];
        $oneMonthAgoPrice[$v['UNIT_TYPE']]['maxPrice'][] = $v['MAX_PRICE'];
    }

    if ($twoMonthAgoDt == $v['EFFECTIVE_DATE']) {
        $twoMonthAgoPrice[$v['UNIT_TYPE']]['minPrice'][] = $v['MIN_PRICE'];
        $twoMonthAgoPrice[$v['UNIT_TYPE']]['maxPrice'][] = $v['MAX_PRICE'];
    }
}

$smarty->assign("latestMonthAllBrokerPrice", $latestMonthAllBrokerPrice);
$smarty->assign("oneMonthAgoPrice", $oneMonthAgoPrice);
$smarty->assign("oneMonthAgoDt", $oneMonthAgoDt);
$smarty->assign("twoMonthAgoDt", $twoMonthAgoDt);

$smarty->assign("twoMonthAgoPrice", $twoMonthAgoPrice);
$smarty->assign("minMaxSum", $minMaxSum);
$smarty->assign("allBrokerByProject", $arrBrokerList);
$smarty->assign("brokerIdList", $brokerIdList);

$smarty->assign("maxEffectiveDt", $maxEffectiveDt);

$smarty->assign("arrCampaign", $arrCampaign);

//code for distinct unit for a project
$arrProjectType = fetch_projectOptions($projectId);
$arrPType = array();
foreach ($arrProjectType as $val) {
    $exp = explode("-", $val);
    if (!in_array(trim($exp[0]), $arrPType))
        array_push($arrPType, trim($exp[0]));
}
$smarty->assign("arrPType", $arrPType);
/* * code for secondary price dispaly*********** */
?>
