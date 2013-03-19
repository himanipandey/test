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
$smarty->assign("ProjectOptionDetail",$ProjectOptionDetail);

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
$smarty->assign("towerDetail", $towerDetail);

$arrAudit   = AuditTblDataByTblName('resi_project_tower_details',$projectId);
$smarty->assign("arrAudit", $arrAudit); 

	$fetch_projectOptions=fetch_projectOptions($projectId);
	$smarty->assign("fetch_projectOptions",$fetch_projectOptions);
	
	if(!isset($_REQUEST['phaseId']))
		$_REQUEST['phaseId'] = '';
	if($_REQUEST['phaseId'] != -1)
		$phaseId				=	$_REQUEST['phaseId'];
		
/*******supply code start here**********/
	if($phaseId != '')
		$qryPhase = " AND PHASE_ID = $phaseId";
	else
		$qryPhase = '';
	
	$supplyArray = array();
	$supplyAllArray = array();
	
	$qry = "SELECT
					a.*
			FROM
			".RESI_PROJ_SUPPLY." a
			JOIN
				(SELECT PROJECT_ID, PROJECT_TYPE, NO_OF_BEDROOMS, MAX(PROJ_SUPPLY_ID) AS LATEST_PROJ_SUPPLY_ID
			FROM 
				".RESI_PROJ_SUPPLY."
			WHERE 
				PROJECT_ID = $projectId $qryPhase
			GROUP BY 
				PROJECT_ID, PROJECT_TYPE, NO_OF_BEDROOMS) b
			 ON
				(a.PROJ_SUPPLY_ID = b.LATEST_PROJ_SUPPLY_ID)";
	
	$res = mysql_query($qry) or die(mysql_error());
	if(mysql_num_rows($res) > 0) 
	{
		while($data = mysql_fetch_assoc($res))
		{
			array_push($supplyArray,$data);
			$supplyAllArray[$data['PROJECT_TYPE']][$data['NO_OF_BEDROOMS']] = $data;
		}
	}
	
	//print_r($supplyAllArray);
	$smarty->assign("supplyAllArray",$supplyAllArray);	
	$total =0;
	$available=0;
	$flagnof = false;
	foreach($supplyArray as $key=>$val){
		$total+= $val['NO_OF_FLATS'];
		if($val['AVAILABLE_NO_FLATS'] != '-')
		{
			$available+=$val['AVAILABLE_NO_FLATS'];
		}
		if($val['AVAILABLE_NO_FLATS'] == '-')
		{
			$flagnof = true;
		}		
	}
	
	if($total != 0 && $available > 0)
		$percentAvailable = $available/$total*100;
	else if($flagnof == true && $available == 0)
		$percentAvailable = 100;
	else
		$percentAvailable = 0;
	$smarty->assign("percentAvailable",$percentAvailable);
	$smarty->assign("AvailableFlat",$available);
	$smarty->assign("total",$total);
/*******end supply code start here**********/
	$arr_RoomNot = "";
	
	foreach($fetch_projectOptions as $key=>$val)
	{
		$exp = explode("-",$val);
		$bedroom = trim($exp[1]);
		$qry = "SELECT PROJ_SUPPLY_ID FROM ".RESI_PROJ_SUPPLY." WHERE NO_OF_BEDROOMS = '".$bedroom."' AND PROJECT_ID = '".$projectId."'";
		$res = mysql_query($qry) or die(mysql_error());	
		if(mysql_num_rows($res)>0)
			$arr_RoomNot.= $bedroom.",";
	}
	$smarty->assign("arr_RoomNot",$arr_RoomNot);


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

 $suburbSelect = SuburbArr($projectDetails[0]['CITY_ID']);
 $localitySelect = localityList($projectDetails[0]['CITY_ID'], $projectDetails[0]['SUBURB_ID']);

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
updateProjectPhase($projectId, "dataCollection", $reviews,$projectStage,TRUE);


	header("Location:$returnURLPID");
}

include('builder_contact_info_process.php');

?>
