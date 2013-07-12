<?php 
$CityDataArr	=	CityArr();
$ProjectTypeArr	=	ProjectTypeArr();
$BankListArr	=	BankList();
$enum_value		=	enum_value();
$AmenitiesArr	=	AmenitiesList();
$BankListArr	=	BankList();

$projectId  = $_GET['projectId'];
$arrCaling = fetchProjectCallingLinks($projectId);
$smarty->assign("arrCaling", $arrCaling);

/*********code for audit tables*********/
$stageName = '';
$phasename = '';
$changedValueArr = array();
if(isset($_REQUEST['stageName']) && isset($_REQUEST['phasename']) && isset($_REQUEST['projectId']))
{
	$arrTableName = array("resi_project"=>"Project","resi_project_options"=>"project Configuration","resi_proj_supply"=>"Project Supply");
	$smarty->assign("changedValueArr", $changedValueArr);
	$stageName	=	$_REQUEST['stageName'];
	$phasename	=	$_REQUEST['phasename'];
	$projectId	=	$_REQUEST['projectId'];
	fetchColumnChanges($projectId, $stageName, $phasename, $arrProjectPriceAuditOld, $arrProjectAudit, $arrProjectSupply);
	
}
$smarty->assign("stageName", $stageName);
$smarty->assign("phasename", $phasename);
//echo "<pre>";
//print_r($arrProjectAudit);
//echo "</pre>";
// var_dump($arrProjectSupply);
$smarty->assign("arrProjectSupply", $arrProjectSupply);
$smarty->assign("arrProjectPriceAuditOld", $arrProjectPriceAuditOld);
$smarty->assign("changedValueArr", $arrProjectAudit);

/*********end code for audit tables*********/

if(!isset($_REQUEST['btnExit']))
	$_REQUEST['btnExit'] ='';
if($_REQUEST['btnExit'] == "Exit")
{
   header("Location:ProjectList.php?projectId=".$projectId);
}

$lastUpdatedDetail = lastUpdatedAuditDetail($projectId);
$smarty->assign("lastUpdatedDetail", $lastUpdatedDetail);

/******start display other pricing******/
$otherPricing = fetch_other_price($projectId);
$smarty->assign("otherPricing", $otherPricing);
/******end display other pricing******/

$ProjectOptionDetail	=	ProjectOptionDetail($projectId);
$PreviousMonthsData	=	getPrevMonthProjectData($projectId);
$PreviousMonthsAvailability = getFlatAvailability($projectId);

//echo key($PreviousMonthsData['current']);
$smarty->assign("ProjectOptionDetail",$ProjectOptionDetail);
$smarty->assign("PreviousMonthsData",$PreviousMonthsData);
$smarty->assign("PreviousMonthsAvailability",$PreviousMonthsAvailability);

$ProjectAmenities	=	ProjectAmenities($projectId, $arrNotninty, $arrDetail, $arrninty);
$arrSpecification	=	specification($projectId);

$smarty->assign("arrNotninty", $arrNotninty);
$smarty->assign("arrDetail", $arrDetail);
$smarty->assign("arrninty", $arrninty);
$smarty->assign("BankListArr",$BankListArr);


$smarty->assign("arrNotninty", $arrNotninty);

$path = "";
$smarty->assign("path", $path);

$ImageDataListingArr = allProjectImages($projectId);
$smarty->assign("ImageDataListingArr", $ImageDataListingArr);

$ImageDataListingArrFloor = allProjectFloorImages($projectId);
$smarty->assign("ImageDataListingArrFloor", $ImageDataListingArrFloor);

$towerDetail			=	fetch_towerDetails($projectId);

$towerDtlPhWise = array();
$towerNoPh = array();
foreach($towerDetail as $val)
{
	if($val['PHASE_NAME'] == '')
	{
		$towerNoPh['NoPhase'][] = $val;
	}
	else
	{
		$towerDtlPhWise[$val['PHASE_NAME']][] = $val;
	}	
}

$smarty->assign("towerDetail", array_merge($towerNoPh,$towerDtlPhWise));

$arrAudit   = AuditTblDataByTblName('resi_project_tower_details',$projectId);
$smarty->assign("arrAudit", $arrAudit); 

	$fetch_projectOptions=fetch_projectOptions($projectId);
	$smarty->assign("fetch_projectOptions",$fetch_projectOptions);
	
	if(!isset($_REQUEST['phaseId']))
		$_REQUEST['phaseId'] = '';
	if($_REQUEST['phaseId'] != -1)
		$phaseId				=	$_REQUEST['phaseId'];
		
/*******supply code start here**********/
	$supplyAllArray = array();
	$qry = "SELECT p.PHASE_NAME,p.LAUNCH_DATE,p.COMPLETION_DATE, a.*
				FROM resi_proj_supply a
				JOIN (SELECT PROJECT_ID, PHASE_ID, PROJECT_TYPE, NO_OF_BEDROOMS, MAX(PROJ_SUPPLY_ID) AS LATEST_PROJ_SUPPLY_ID
				         FROM resi_proj_supply
				         WHERE PROJECT_ID = $projectId
				         GROUP BY PROJECT_ID, PHASE_ID, PROJECT_TYPE, NO_OF_BEDROOMS) b
				ON (a.PROJ_SUPPLY_ID = b.LATEST_PROJ_SUPPLY_ID)
				LEFT JOIN resi_project_phase p
				       on (p.PHASE_ID = a.PHASE_ID)";
	
	$res = mysql_query($qry) or die(mysql_error());
	$arrPhaseCount = array();
	$arrPhaseTypeCount = array();
	if(mysql_num_rows($res) > 0)
	{
		while($data = mysql_fetch_assoc($res))
		{
			if($data['PHASE_NAME'] == '')
				$data['PHASE_NAME'] = 'noPhase';
			$supplyAllArray[$data['PHASE_NAME']][$data['PROJECT_TYPE']][] = $data;
			$arrPhaseCount[$data['PHASE_NAME']][] = $data['PROJECT_TYPE'];
			$arrPhaseTypeCount[$data['PHASE_NAME']][$data['PROJECT_TYPE']][] = '';
		}
	}
	$smarty->assign("arrPhaseCount",$arrPhaseCount);
	$smarty->assign("arrPhaseTypeCount",$arrPhaseTypeCount);
	$smarty->assign("supplyAllArray",$supplyAllArray);


// Project Phases
$projectId				=	$_REQUEST['projectId'];
$phaseDetail			=	fetch_phaseDetails($projectId);
$bedroomDetails = ProjectBedroomDetail($projectId);
$smarty->assign("BedroomDetails", $bedroomDetails); 
$phases = Array();
foreach($phaseDetail as $k=>$val) {
    $p = Array();
    $p['id'] = $val['PHASE_ID'];
    $p['name'] = $val['PHASE_NAME'];
    array_push($phases, $p);
}
$smarty->assign("phases", $phases);
$smarty->assign("phaseId", $phaseId);
if($phaseId) {
    $current_phase =	phaseDetailsForId($phaseId);
    $phase_towers  =    fetch_towers_in_phase($projectId, $phaseId);

    $arrTower = array();
    foreach($phase_towers as $key=>$val)
    {
    	array_push($arrTower,$val['TOWER_ID']); 
    }
    $smarty->assign("arrTower", $arrTower);
    $phase_quantity    =   get_phase_quantity($phaseId);
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
	if(!isset($_REQUEST['towerId']))
		$_REQUEST['towerId'] = '';
	if($_REQUEST['towerId'] != '')
	{
		$anchor = "anchor";
		$smarty->assign("anchor", $anchor);
		$smarty->assign("towerId", $_GET['towerId']);
		
		$towerDetailForId		=	towerDetailsForId($_GET['towerId']);
		//echo "<pre>";
		//print_r($towerDetailForId);
		//echo "</pre>";
		$smarty->assign("towerId", $_GET['towerId']);
		$smarty->assign("no_of_floors", $towerDetailForId[0]['NO_OF_FLOORS']);
		$smarty->assign("stilt", $towerDetailForId[0]['STILT']);
		$smarty->assign("no_of_flats_per_floor", $towerDetailForId[0]['NO_OF_FLATS']);
		$smarty->assign("towerface", $towerDetailForId[0]['TOWER_FACING_DIRECTION']);
		$smarty->assign("completion_date", $towerDetailForId[0]['ACTUAL_COMPLETION_DATE']);
		$smarty->assign("remark", $towerDetailForId[0]['REMARKS']);
		$smarty->assign("edit", $towerDetailForId[0]['NO_OF_FLOORS']);

		$arrAudit   = AuditTblDataByTblName('resi_project_tower_details',$projectId);
		$smarty->assign("arrAudit", $arrAudit); 
	}

$smarty->assign("arrSpecification", $arrSpecification);


$smarty->assign("projectId", $projectId);
$smarty->assign("ProjectTypeArr", $ProjectTypeArr);
$smarty->assign("enum_value", $enum_value);
$smarty->assign("AmenitiesArr", $AmenitiesArr);

$projectDetails = array();
$qry = "SELECT * FROM ".RESI_PROJECT." WHERE PROJECT_ID = '".$projectId."'";
$res = mysql_query($qry) or die(mysql_error());	
	if(!mysql_num_rows($res)>0){
			$smarty->assign("error", "error");
	}
	
	while($data	=	mysql_fetch_array($res))
 	{
 		$projectStage = $data['PROJECT_STAGE']; 
 		array_push($projectDetails, $data);	
 	}

 	if($projectDetails[0]['PROJECT_STAGE'] == 'newProject')
 	{
 		$phse = 'newP';
 	}
 	else if($projectDetails[0]['PROJECT_STAGE'] == 'noStage')
 	{
 		$phse = 'noS';
 	}
 	else if($projectDetails[0]['PROJECT_STAGE'] == 'updationCycle')
 	{
	 	$phse = 'updation';
 	}
        else if($projectDetails[0]['PROJECT_STAGE'] == 'secondaryPrice')
 	{
	 	$phse = 'secondary';
 	}
 	
 	$UpdationArr = updationCycleTable();
 	$projectLabel = '';
 	if($phse =='updation')
 	{
	 	foreach($UpdationArr as $k=>$v)
	 	{
		 	if($projectDetails[0]['UPDATION_CYCLE_ID'] == $UpdationArr[$k]['UPDATION_CYCLE_ID'])
		 		$projectLabel = $UpdationArr[$k]['LABEL'];
	 	}
	 	if($projectDetails[0]['UPDATION_CYCLE_ID']==null)
	 	{	
	 		$projectLabel = 'No Label';
	 	}
 	}
 	else
 	{ 
 		$projectLabel = 'No Label';
 	}
 	$smarty->assign("projectLabel", $projectLabel);
 	
 $suburbSelect = SuburbArr($projectDetails[0]['CITY_ID']);
 $localitySelect = localityList($projectDetails[0]['CITY_ID'], $projectDetails[0]['SUBURB_ID']);

 $builderDetail	= fetch_builderDetail($projectDetails[0]['BUILDER_ID']);
 $smarty->assign("builderDetail", $builderDetail);
 
 /*****code for promised completion date********/
 $expCompletionDate = costructionDetail($projectId); 
 $completionDate = '';
 if(count($expCompletionDate['EXPECTED_COMPLETION_DATE'])>0)
 {
	 date_default_timezone_set('Asia/Calcutta');
	 $dateProject   = new DateTime($projectDetails[0]['PROMISED_COMPLETION_DATE']);
	 $dateConstruct = new DateTime($expCompletionDate['EXPECTED_COMPLETION_DATE']);
	 if ($dateProject < $dateConstruct)
	 	$completionDate = $expCompletionDate['EXPECTED_COMPLETION_DATE'];
	 else
	 	$completionDate = $projectDetails[0]['PROMISED_COMPLETION_DATE'];
 }
 else
 	$completionDate = $projectDetails[0]['PROMISED_COMPLETION_DATE'];
 
 $smarty->assign("completionDate", $completionDate);
 /*****code for promised completion date********/

	$smarty->assign("localitySelect", $localitySelect);
	$smarty->assign("projectDetails", $projectDetails);
	$smarty->assign("CityDataArr", $CityDataArr);
	$smarty->assign("suburbSelect", $suburbSelect);

	if(!isset($_GET['towerId']))
		$_GET['towerId'] = '';
	if($_GET['towerId'] != '')
	{
		$tower_detail = towerDetail($_GET['towerId']);
		$arrAudit   = AuditTblDataByTblName('resi_proj_tower_construction_status',$projectId);
		$smarty->assign("tower_detail", $tower_detail); 
		$smarty->assign("arrAudit", $arrAudit);	
	}

	if(!isset($_GET['bedId']))
		$_GET['bedId'] = '';
	if($_GET['bedId'] != '')
	{
		$bed = explode("-",$_GET['bedId']);

		$supply_bed = bedSupplyDetail($projectId,$bed[1]);
		if(count($supply_bed) == 0)
		{
			$supply_bed[0]['NO_OF_BEDROOMS'] = $_GET['bedId'];			
		}
		else
		{
			$supply_bed[0]['NO_OF_BEDROOMS'] = $bed[0]."-".$supply_bed[0]['NO_OF_BEDROOMS'];
		}
		$arrAudit   = AuditTblDataByTblName('resi_proj_supply',$projectId);
		$smarty->assign("supply_bed", $supply_bed); 
		$smarty->assign("arrAudit", $arrAudit); 
		$smarty->assign("bedAnchor", "true");		
	}



$newPhase = array(
    "dataCollection" => "newProject",
    "newProject" => "dcCallCenter",
    "dcCallCenter" => "audit1",
    "audit1" => "audit2",
	"audit2" => "complete",
);


$updatePhase = array(
    "dataCollection" => "audit1",
    "audit1" => "audit2",
    "audit2" => "complete",
);

if(!isset($_POST['forwardFlag']))
	$_POST['forwardFlag'] = '';
if($_POST['forwardFlag'] == 'yes'){
$returnURLPID = $_POST['returnURLPID'];
$currentPhase = $_POST['currentPhase'];
$reviews = $_POST['reviews'];
foreach ($newPhase as $k => $v) {
    if($currentPhase == $k){
    	updateProjectPhase($projectId, $newPhase[$k], $reviews,$projectStage);
    }
}
header("Location:$returnURLPID");
}

if($_POST['forwardFlag'] == 'update'){
$returnURLPID = $_POST['returnURLPID'];
$currentPhase = $_POST['currentPhase'];
$reviews = $_POST['reviews'];
foreach ($updatePhase as $k => $v) {
    if($currentPhase == $k){
    	updateProjectPhase($projectId, $updatePhase[$k], $reviews,$projectStage);
    }
}
header("Location:$returnURLPID");
}

if($_POST['forwardFlag'] == 'no'){
$returnURLPID = $_POST['returnURLPID'];
$currentPhase = $_POST['currentPhase'];
$reviews = $_POST['reviews'];
/*foreach ($newPhase as $k => $v) {
    if($currentPhase == $newPhase[$k]){
    	updateProjectPhase($projectId, $k);
    }
}*/

if($_REQUEST['returnStage'] == 'newProject' AND $_REQUEST['currentPhase'] == 'audit1')
	$phaseName = 'dcCallCenter';
else
	$phaseName = 'dataCollection';

updateProjectPhase($projectId, $phaseName, $reviews, $projectStage,TRUE);


	header("Location:$returnURLPID");
}

include('builder_contact_info_process.php');
?>
