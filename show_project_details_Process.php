<?php
$CityDataArr = City::CityArr();
$ProjectTypeArr = ResiProjectType::ProjectTypeArr();
$BankListArr = BankList::arrBank();
$enum_value = ResiProject::projectStatusMaster();
$AmenitiesArr = AmenitiesList();

$projectId = $_REQUEST['projectId'];

/****start code for supply order validation when move to audit2*****/
$availabilityOrderChk = availebilitydescendingOrder($projectId);
$bedRoomOrder = '';
$availOrder = '';
if($availabilityOrderChk['flg'] == true){
    $bedRoomOrder = $availabilityOrderChk['bedrooms'];
    $availOrder   = str_replace("|",",",$availabilityOrderChk['arrOrder']);
}
    
$smarty->assign("availabilityOrderChk", $availabilityOrderChk['flg']);
$smarty->assign("bedRoomOrder", $bedRoomOrder);
$smarty->assign("availOrder", $availOrder);
/****end code for supply order validation when move to audit2*****/ 
    
if (!isset($_REQUEST['btnExit']))
    $_REQUEST['btnExit'] = '';
if ($_REQUEST['btnExit'] == "Exit") {
    header("Location:ProjectList.php?projectId=" . $projectId);
}

if(ProjectMigration::isProjectWaitingForMigration($projectId))die ('This project is being migrated to website. You will be able to see project details only after some time.');

$lastUpdatedDetail = lastUpdatedAuditDetail($projectId); //To Do
$smarty->assign("lastUpdatedDetail", $lastUpdatedDetail);//To Do

$project_video_links = project_video_detail($projectId);
$smarty->assign("project_video_links", count($project_video_links));

$arrCalingPrimary = fetchProjectCallingLinks($projectId, 'primary');
$smarty->assign("arrCalingPrimary", $arrCalingPrimary);

$redevelopment_flag = fetchProjectRedevelolpmentFlag($projectId);
$smarty->assign("redevelopment_flag", $redevelopment_flag);

/* * ****start display other pricing******/
$otherPricing = fetch_other_price($projectId);
$smarty->assign("otherPricing", $otherPricing);
/* * ****end display other pricing***** */

//$ProjectPhases = ResiProjectPhase::get_phase_option_hash_by_project($projectId); //To Do
$optionsDetails = Listings::all(array('joins' => "join resi_project_phase p on (p.phase_id = listings.phase_id and p.version = 'Cms') 
    join resi_project_options o on (o.options_id = option_id)",'conditions' => 
    array("o.PROJECT_ID = $projectId and OPTION_CATEGORY = 'Actual' and p.status = 'Active' and listings.status = 'Active' and listings.listing_category='Primary'"), "select" => 
    "listings.*,p.phase_name,o.option_name,o.size,o.carpet_area,o.villa_plot_area,o.villa_no_floors"));
$uptionDetailWithPrice = array();
foreach($optionsDetails as $key => $value) {
    
    $listing_price = ListingPrices::find('all',array('conditions'=>
    array('listing_id = ?', $value->id),"limit" => 1, "order" => "effective_date desc",'select' => 
                    'effective_date'));
             
    
    $uptionDetailWithPrice[$value->phase_id][$value->option_id]['option_name'] = $value->option_name;
    $uptionDetailWithPrice[$value->phase_id][$value->option_id]['phase_name'] = $value->phase_name;
    $uptionDetailWithPrice[$value->phase_id][$value->option_id]['size'] = $value->size;
    $uptionDetailWithPrice[$value->phase_id][$value->option_id]['carpet_area'] = $value->carpet_area;
    $uptionDetailWithPrice[$value->phase_id][$value->option_id]['villa_no_floors'] = $value->villa_no_floors;
    $uptionDetailWithPrice[$value->phase_id][$value->option_id]['villa_plot_area'] = $value->villa_plot_area;
    $uptionDetailWithPrice[$value->phase_id][$value->option_id]['effective_date'] = date('Y-m-d',strtotime($listing_price[0]->effective_date));
    $uptionDetailWithPrice[$value->phase_id][$value->option_id]['booking_status_id'] = $value->booking_status_id;
}

$PhaseOptionHash = $ProjectPhases[1];
$ProjectPhases = $ProjectPhases[0];
$ProjectOptionDetail = ProjectOptionDetail($projectId);
$PreviousMonthsData = getPrevMonthProjectData($projectId);
$PreviousMonthsAvailability = getFlatAvailability($projectId);
$arrPriceListData = array();
$cnt = 0;
$arrPrevMonthDate = array();
$arrPhase = array();
foreach ($PreviousMonthsData as $k => $v) {
    if($cnt > 1) {
        foreach($v as $keyMiddle => $vMiddle) {
            foreach( $vMiddle as $kLast => $vLast ) {
                $vLast['phase_name'] = $uptionDetailWithPrice[$vLast['phase_id']][$vLast['options_id']]['phase_name'];
                if($cnt == 2) {
                    $uptionDetailWithPrice[$vLast['phase_id']][$vLast['options_id']]['latestPrice'] = $vLast['price'];
                }
                if($cnt == 3) {
                    $uptionDetailWithPrice[$vLast['phase_id']][$vLast['options_id']]['prevMonthPrice'] = $vLast['price'];
                }
                if($cnt == 4) {
                    $uptionDetailWithPrice[$vLast['phase_id']][$vLast['options_id']]['prevPrevMonthPrice'] = $vLast['price'];
                }
            }
        } 
    }
    if( $cnt >2 and  $cnt <=4)
        $arrPrevMonthDate[] = $k;
    $cnt++;
}
//echo "<pre>";
//print_r($uptionDetailWithPrice);
$smarty->assign("arrPrevMonthDate",$arrPrevMonthDate);
$smarty->assign("uptionDetailWithPrice",$uptionDetailWithPrice);

$smarty->assign("ProjectOptionDetail",$ProjectOptionDetail);
$smarty->assign("PreviousMonthsData",$PreviousMonthsData);
$smarty->assign("PreviousMonthsAvailability",$PreviousMonthsAvailability);
//$smarty->assign("ProjectPhases",$ProjectPhases); //To Do
$smarty->assign("PhaseOptionHash",$PhaseOptionHash);

//config sizes flag
$smarty->assign("configSizeFlag",configSizeCheckFlag($projectId));

//code for completion date validation for phase label
/*$qryAllPhase = "select * from resi_project_phase 
    where project_id = $projectId and status = 'Active' and version = 'Cms'";
$resAllPhase = mysql_query($qryAllPhase);
$allCompletionDateChk = 0;
while($data = mysql_fetch_assoc($resAllPhase)) {
    $data['completion_date'];
    if(trim($data['COMPLETION_DATE']) == '' || trim($data['COMPLETION_DATE']) == '0000-00-00') {
        $arrAllCompletionDateChk = 1;
    }
}
$smarty->assign("arrAllCompletionDateChk",$arrAllCompletionDateChk);*/
//end code for completion date validation for phase label
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
//echo "<pre>";
//print_r($PreviousMonthsAvailability);
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

$towerDetail = fetch_towerDetails($projectId);
//print'<pre>';
//print_r($towerDetail);


//$ImageDataListingArr = allProjectImages($projectId);
$objectType = "project";
$ImageDataListingArr = array(); //Image data from Image service

$objectId = $projectId;

$url = ImageServiceUpload::$image_upload_url."?objectType=$objectType&objectId=".$objectId;
$content = file_get_contents($url);
$imgPath = json_decode($content);

foreach($imgPath->data as $k=>$v){
    
        $data = array();
        $data['SERVICE_IMAGE_ID'] = $v->id;
        $data['objectType'] = $v->imageType->objectType->type;
        $data['objectId'] = $v->objectId; 
        
        
        $arr = preg_split('/(?=[A-Z])/',$v->imageType->type);
        $str = ucfirst (implode(" ",$arr));
        if($str=='Main')
            $data['PLAN_TYPE'] = "Project Image";
        else
            $data['PLAN_TYPE'] = $str;
         
        if ($data['PLAN_TYPE']=="Project Image" && $v->priority==0 )
            $data['display_order'] = 5;
        else
            $data['display_order'] = $v->priority;
        $data['TITLE'] = $v->title;
        $data['IMAGE_DESCRIPTION'] = $v->description;
        $data['SERVICE_IMAGE_ID'] = $v->id;
        $data['PLAN_IMAGE'] = $v->absolutePath;
       
        if(isset($v->takenAt)){
            $t = $v->takenAt/1000;
            $data['tagged_month'] =  date("Y-m-d", $t);
        }
       

        
        $str = trim(trim($v->jsonDump, '{'), '}');
        $towerarr = explode(":", $str);
        if(trim($towerarr[1],"\"") == "null")
            $data['tower_id']=null;
        else if(trim($towerarr[1],"\"") == "0"){
             $data['tower_id']="0";
            $data['TOWER_NAME']= "Other";
        }
        else
            $data['tower_id'] = (int)trim($towerarr[1],"\"");
        
        foreach ($towerDetail as $k1 => $v1) {
            if($v1['TOWER_ID']==$data['tower_id'])
                $data['TOWER_NAME']= $v1['TOWER_NAME'];

       }
       //echo $data['tower_id'];
       //if($data['tower_id'] == 0) $data['TOWER_NAME']="Other";
       //var_dump($data['tower_id']);
        $data['PROJECT_ID'] = $v->objectId;
        $data['STATUS'] = $v->active;
        $data['thumb_path'] = $v->absolutePath."?width=130&height=100";
       $data['alt_text'] = $v->altText;
        array_push($ImageDataListingArr, $data);
    
}
        
    


$smarty->assign("ImageDataListingArr", $ImageDataListingArr);

$ImageDataListingArrFloor = allProjectFloorImages($projectId);
//print'<pre>';
//print_r($ImageDataListingArr);
$ImageDataListingArrFloor = array();
$optionsArr = getAllProjectOptionsExceptPlot($projectId);
//print'<pre>';
//print_r($towerDetail);
        foreach ($optionsArr as $k1 => $v1) {
            $objectType = "property";
            
            
            $image_type = "floor_plan";
            $objectId = $v1['OPTION_ID'];
            
            $url = ImageServiceUpload::$image_upload_url."?objectType=$objectType&objectId=".$objectId;
            //echo $url;
            $content = file_get_contents($url);
            $imgPath = json_decode($content);
            $data = array();
            foreach($imgPath->data as $k=>$v){
                $data = array();
                $data['OPTION_ID'] = $v1['OPTION_ID'];
                $data['UNIT_TYPE'] = $v1['UNIT_TYPE'];
                $data['SIZE'] = $v1['SIZE'];
                $data['UNIT_NAME'] = $v1['UNIT_NAME'];
               

                $data['SERVICE_IMAGE_ID'] = $v->id;
                //$data['objectType'] = $v->imageType->objectType->type;
                //$data['objectId'] = $v->objectId; 
                $arr = preg_split('/(?=[A-Z])/',$v->imageType->type);
                $str = ucfirst (implode(" ",$arr));
                $data['PLAN_TYPE'] = "View ".$str;
                $data['DISPLAY_ORDER'] = $v->priority;
                $data['IMAGE_DESCRIPTION'] = $v->description;
                $data['IMAGE_URL'] = $v->absolutePath;
                $data['NAME'] = $v->title;
                
                $data['STATUS'] = $v->active;
                $data['thumb_path'] = $v->absolutePath."?width=130&height=100";
                $data['alt_text'] = $v->altText;
                array_push($ImageDataListingArrFloor, $data);
            }

        }


$smarty->assign("ImageDataListingArrFloor", $ImageDataListingArrFloor);


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
/******* To Do ***/
$supplyAll = array();
$res = ProjectSupply::projectSupplyForProjectPage($projectId);
$arrPhaseCount = array();
$arrPhaseTypeCount = array();

foreach ($res as $data) {
    if ($data['PHASE_NAME'] == '')$data['PHASE_NAME'] = 'noPhase';
    $supplyAll[$data['PHASE_NAME']][$data['PROJECT_TYPE']][] = $data;
    $arrPhaseCount[$data['PHASE_NAME']][] = $data['PROJECT_TYPE'];
    $arrPhaseTypeCount[$data['PHASE_NAME']][$data['PROJECT_TYPE']][] = '';
}
$supplyAllArray = array();
$isSupplyLaunchVerified = ProjectSupply::isSupplyLaunchVerified($projectId);
foreach($supplyAll as $k=>$v) {
    foreach($v as $kMiddle=>$vMiddle) {
        foreach($vMiddle as $kLast=>$vLast) {
            $supplyAllArray[$k][$kMiddle][$kLast]['PHASE_NAME'] = $vLast['PHASE_NAME'];
            $supplyAllArray[$k][$kMiddle][$kLast]['LAUNCH_DATE'] = $vLast['LAUNCH_DATE'];
            $supplyAllArray[$k][$kMiddle][$kLast]['REMARKS'] = $vLast['REMARKS'];
            $supplyAllArray[$k][$kMiddle][$kLast]['COMPLETION_DATE'] = $vLast['COMPLETION_DATE'];
            $supplyAllArray[$k][$kMiddle][$kLast]['submitted_date'] = $vLast['submitted_date'];
            $supplyAllArray[$k][$kMiddle][$kLast]['PROJECT_ID'] = $vLast['PROJECT_ID'];
            $supplyAllArray[$k][$kMiddle][$kLast]['PHASE_ID'] = $vLast['PHASE_ID'];
            $supplyAllArray[$k][$kMiddle][$kLast]['NO_OF_BEDROOMS'] = $vLast['NO_OF_BEDROOMS'];
            $supplyAllArray[$k][$kMiddle][$kLast]['EDITED_NO_OF_FLATS'] = $vLast['NO_OF_FLATS']; 
            $supplyAllArray[$k][$kMiddle][$kLast]['EDITED_LAUNCHED'] = $vLast['LAUNCHED'];
            
            $supplyAllArray[$k][$kMiddle][$kLast]['EDIT_REASON'] = $vLast['EDIT_REASON'];
            $supplyAllArray[$k][$kMiddle][$kLast]['SUBMITTED_DATE'] = $vLast['SUBMITTED_DATE'];
            $supplyAllArray[$k][$kMiddle][$kLast]['PROJECT_TYPE'] = $vLast['PROJECT_TYPE'];
            $supplyAllArray[$k][$kMiddle][$kLast]['LISTING_ID'] = $vLast['LISTING_ID'];
            $supplyAllArray[$k][$kMiddle][$kLast]['BOOKING_STATUS_ID'] = $vLast['BOOKING_STATUS_ID'];
            $supplyAllArray[$k][$kMiddle][$kLast]['construction_status'] = $vLast['construction_status'];
 
          
            $qryEditedLaunched = "select ps.supply,ps.launched,pa.availability from project_supplies ps
									inner join project_availabilities pa on ps.id = pa.project_supply_id
									where listing_id = '".$vLast['LISTING_ID']."' and version = 'Cms' order by effective_month desc limit 1";
                
            $resEditedLaunched = mysql_query($qryEditedLaunched) or die(mysql_error());
            $dataEditedLaunched = mysql_fetch_assoc($resEditedLaunched);
            $supplyAllArray[$k][$kMiddle][$kLast]['NO_OF_FLATS'] = $dataEditedLaunched['supply'];
            $supplyAllArray[$k][$kMiddle][$kLast]['LAUNCHED'] = $dataEditedLaunched['launched'];
            $supplyAllArray[$k][$kMiddle][$kLast]['AVAILABLE_NO_FLATS'] = $dataEditedLaunched['availability'];
        }
    }
} 

//echo "<pre>";
//print_r($supplyAllArray);die;
$smarty->assign("arrPhaseCount", $arrPhaseCount);
$smarty->assign("arrPhaseTypeCount", $arrPhaseTypeCount);
$smarty->assign("supplyAllArray", $supplyAllArray);
$smarty->assign("isSupplyLaunchVerified", $isSupplyLaunchVerified);

// Project Phases
$phaseDetail = fetch_phaseDetails($projectId);
$bedroomDetails = ProjectBedroomDetail($projectId);
$smarty->assign("BedroomDetails", $bedroomDetails);
$phases = Array();
$phaseIds  =array();
foreach ($phaseDetail as $k => $val) {
    $p = Array();
    $p['id'] = $val['PHASE_ID'];
    $p['name'] = $val['PHASE_NAME'];
    $phaseIds[] = $val['PHASE_ID'];
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
    mpp.name as PROJECT_PHASE,mpbt.display_name as power_backup, ta.attribute_value as desc_content_flag
    FROM " . RESI_PROJECT . " rp
    left join project_status_master ps on rp.project_status_id = ps.id
    left join townships t on rp.township_id = t.id
    left join master_project_stages mps on rp.project_stage_id = mps.id
    left join master_project_phases mpp on rp.project_phase_id = mpp.id
    left join master_power_backup_types mpbt on rp.power_backup_type_id = mpbt.id
    left join table_attributes ta on ta.table_id=rp.project_id and ta.table_name='resi_project' and ta.attribute_name='DESC_CONTENT_FLAG'
    WHERE rp.PROJECT_ID = '" . $projectId . "' and rp.version = 'Cms'";
    //die($qry);
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

if($projectDetails[0]['STATUS'] == 'Inactive'){
    $project_alias_detail = project_aliases_detail($projectId);
    $smarty->assign("project_alias_detail", $project_alias_detail);
}

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
#$completionDate = $projectDetails[0]['PROMISED_COMPLETION_DATE'];
#$smarty->assign("completionDate", $completionDate);
/* * ***code for promised completion date******* */
$projectd = $projectDetails[0]['PROJECT_ID'];
/* * ***code for completion effective date******* */
$comp_eff_date = costructionDetail($projectId);
$smarty->assign("completionEffDate", $comp_eff_date['submitted_date']);
$smarty->assign("completionDate", $comp_eff_date['COMPLETION_DATE']);
$arrAllCompletionDateChk = 0;
if(trim($comp_eff_date['COMPLETION_DATE']) == '' || trim($comp_eff_date['COMPLETION_DATE']) == '0000-00-00') {
        $arrAllCompletionDateChk = 1;
}
$smarty->assign("arrAllCompletionDateChk",$arrAllCompletionDateChk);
/* * ***code for completion effective date******* */

/********** booking status for project ***********/
 $project_booking_status = ResiProjectPhase::find("all", array("conditions" => array("project_id = {$projectId} and phase_type = 'Logical'"),'select' => 
                    'booking_status_id'));
$smarty->assign("project_booking_status_id", $project_booking_status[0]->booking_status_id);
    
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
    $dataOffer = ProjectOffers::find("all",array("conditions"=>array("project_id"=>$projectId,'status'=>'Active')));
    $smarty->assign("offer_desc", $dataOffer);
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

$arrNotLaunchOnHOldCancled = array('2','5','6');
if ($_POST['forwardFlag'] == 'yes') { 
    $returnURLPID = $_POST['returnURLPID'];
    $currentPhase = $_POST['currentPhase'];
    
    $errorValidation = '';
    $flgLogical = 0;
    
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
         /*****code for update project assignment status is 
        * done if project move in audit1 if assigned in survey ********/
        
       if($v == 'Audit1') {
           //code for if next stage is audit1
           //then check all phase should have logical entry    
           if($currentPhase == 'DcCallCenter'){
                foreach($phaseIds as $k=>$valPhaseId){
                   $qryPhaseActual = "select rpo.OPTION_CATEGORY from listings l
                                          join resi_project_options rpo on l.option_id = rpo.OPTIONS_ID
                                      where l.phase_id = ".$valPhaseId." and l.listing_category='Primary' and rpo.OPTION_CATEGORY = 'Logical'";
                   $resPhaseActual = mysql_query($qryPhaseActual) or die(mysql_error());
                   if(mysql_num_rows($resPhaseActual) == 0 && (!in_array($projectDetails[0]['PROJECT_STATUS_ID'],$arrNotLaunchOnHOldCancled) && $projectDetails[0]['RESIDENTIAL_FLAG'] == 'Residential')){
                    $flgLogical = 1;
                    //echo $flgLogical."ghdf";
                   }
                }
            
            }
            
        $qryCurrentAssign = "select pa.id from project_assignment pa 
            join proptiger_admin pa1 on pa.assigned_to = pa1.adminid
            join resi_project rp on  pa.movement_history_id = rp.movement_history_id
            where rp.project_id = $projectId and pa1.department = 'SURVEY' and rp.version = 'Cms' 
            order by pa.movement_history_id desc limit 1";
           $resAssigned = mysql_query($qryCurrentAssign) or die(mysql_error()." select query");
           if(mysql_num_rows($resAssigned) >0  && $flgLogical == 0) {
               $dataFetch = mysql_fetch_assoc($resAssigned);
               $qryUp = "update project_assignment set status = 'done' 
                   where id = ".$dataFetch['id'];
               $resUp = mysql_query($qryUp) or die(mysql_error()." update query");
           }
       }
        if ($phaseIdCurrent['id'] == $phaseId['id'] && $flgLogical == 0) {
            updateProjectPhase($projectId, $phaseIdNext['id'], $stageId['id']);
            //updating new remark
            if($currentPhase=='Audit1' && $_POST['newRemarkId'])
                update_remark_status($_POST['newRemarkId']);
        }
    }
    if($flgLogical == 1){
        $errorValidation = "<font color = 'red'>Please enter supply for all phases</font>";
        $smarty->assign("errorValidation",$errorValidation);
    }else
    header("Location:$returnURLPID");
}
if ($_POST['forwardFlag'] == 'update') {
    $returnURLPID = $_POST['returnURLPID'];
    $currentPhase = $_POST['currentPhase'];
    foreach ($updatePhase as $k => $v) {
        if ($currentPhase == $k) {
            
            $qry = "select * from master_project_phases where name = '".$v."'";
            $res = mysql_query($qry) or die(mysql_error());
            $phaseId = mysql_fetch_assoc($res);
            $qryStg = "select * from master_project_stages where name = '".$projectStage."'";
            $resStg = mysql_query($qryStg) or die(mysql_error());
            $stageId = mysql_fetch_assoc($resStg);
            /*****code for update project assignment status is 
            * done if project move in audit1 if assigned in survey ********/
           if($v == 'Audit1') {
            $qryCurrentAssign = "select pa.id from project_assignment pa 
                join proptiger_admin pa1 on pa.assigned_to = pa1.adminid
                join resi_project rp on  pa.movement_history_id = rp.movement_history_id
                where rp.project_id = $projectId and pa1.department = 'SURVEY' and rp.version = 'Cms' 
                order by pa.movement_history_id desc limit 1";
               $resAssigned = mysql_query($qryCurrentAssign) or die(mysql_error()." select query");
               if(mysql_num_rows($resAssigned) >0 ) {
                   $dataFetch = mysql_fetch_assoc($resAssigned);
                   $qryUp = "update project_assignment set status = 'done' 
                       where id = ".$dataFetch['id'];
                   $resUp = mysql_query($qryUp) or die(mysql_error()." update query");
               }
           }

           /********************/

            updateProjectPhase($projectId, $phaseId['id'], $stageId['id']);
            
            //updating new remark
            if($currentPhase=='Audit1' && isset($_POST['newRemarkId']))
                update_remark_status($_POST['newRemarkId']);
        }
    }
    header("Location:$returnURLPID");
}
if ($_POST['forwardFlag'] == 'no') {
    $returnURLPID = $_POST['returnURLPID'];
    $currentPhase = $_POST['currentPhase'];
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
    
    if(($_REQUEST['returnStage'] == 'NewProject' || $_REQUEST['returnStage'] == 'UpdationCycle')
            && ($_REQUEST['currentPhase'] == 'Audit1' OR $_REQUEST['currentPhase'] == 'Audit2')){
       /* $qry = "select pa.* from resi_project rp join project_assignment pa
                on rp.updation_cycle_id = pa.updation_cycle_id
                join project_stage_history psh on pa.movement_history_id = psh.history_id
                where rp.project_id = $projectId and psh.project_phase_id in
        (".phaseId_1.",".phaseId_3.",".phaseId_8.") and psh.project_stage_id = ".$stageId['id']." order by pa.UPDATION_TIME desc limit 1";*/
       $limitCondition = '';
        if($_REQUEST['currentPhase'] == 'Audit1')
           $limitCondition = "0,1";
       else {
           $limitCondition = "1,1"; 
       }
       $qrymovmentHistory = "select history_id from project_stage_history where history_id not in(
           select movement_history_id from resi_project 
           where project_id = $projectId and version = 'Cms' and status in ('ActiveInCms','Active')) and project_id = $projectId order by history_id desc limit $limitCondition";
       $resmovmentHistory = mysql_query($qrymovmentHistory) or die(mysql_error());
       $movmentHistoryData = mysql_fetch_assoc($resmovmentHistory);
       
      $qry = "select pa.* from resi_project rp join project_assignment pa
                on (rp.updation_cycle_id is null
                    or rp.updation_cycle_id = pa.updation_cycle_id or pa.updation_cycle_id is null)
                where rp.project_id = $projectId and rp.version = 'Cms' and rp.status in ('ActiveInCms','Active') 
                    and pa.movement_history_id = ".$movmentHistoryData['history_id']."
            order by pa.id desc limit 1";//die;
        $res = mysql_query($qry) or die(mysql_error());
        $OldHistory = mysql_fetch_assoc($res);
    //  Assigning back to same user if assignment is found
        if(mysql_num_rows($res) > 0){
            $lastAssignemnt = $OldHistory['ASSIGNED_TO'];
            $newAssignment = new ProjectAssignment();
            $project = ResiProject::virtual_find($projectId);
            $newAssignment->movement_history_id = $OldHistory['MOVEMENT_HISTORY_ID'];
            $newAssignment->assigned_to = $OldHistory['ASSIGNED_TO'];
            $newAssignment->assigned_by = $OldHistory['ASSIGNED_BY'];
            $newAssignment->status = "notAttempted";
            $newAssignment->creation_time = Date('Y-m-d H:i:s');
            $newAssignment->updation_time = Date('Y-m-d H:i:s');
            $newAssignment->save();
            updateProjectPhase($projectId, $phaseId['id'], $stageId['id'], TRUE);
            
            $qryUpdatestageHistory = "select history_id from project_stage_history 
                where project_id = $projectId order by history_id desc limit 1";
            $resUpdateHistory = mysql_query($qryUpdatestageHistory) or die(mysql_error());
            $dataUpdate = mysql_fetch_assoc($resUpdateHistory);
            $qryUpdateAssignment = "select id from project_assignment 
                 order by id desc limit 1";
            $resUpdateAssignment = mysql_query($qryUpdateAssignment) or die(mysql_error());
            $dataAssign = mysql_fetch_assoc($resUpdateAssignment);
            if($projectDetail['UPDATION_CYCLE_ID'] == null)
                $projectDetail['UPDATION_CYCLE_ID'] = 0;
            $qryUp = "update project_assignment set movement_history_id = ".$dataUpdate['history_id'].",
                updation_cycle_id = ".$projectDetail['UPDATION_CYCLE_ID']."
                where id = ".$dataAssign['id'];
            $resUp = mysql_query($qryUp) or   die(mysql_error());
            
        }else{
            updateProjectPhase($projectId, $phaseId['id'], $stageId['id'], TRUE);
        }
        header("Location:$returnURLPID");
    }
    else{
            updateProjectPhase($projectId, $phaseId['id'], $stageId['id'], TRUE);
        }
    header("Location:$returnURLPID");
}
/*****code for display updation cycle*********/
    $currentCycle = currentCycleOfProject($projectId,$projectDetails[0]['PROJECT_PHASE'],$projectDetails[0]['PROJECT_STAGE']);
    $smarty->assign('currentCycle',$currentCycle);
/************************************/
include('builder_contact_info_process.php');

/* * code for secondary price dispaly*********** */
include("function/resale_functions.php");
$allBrokerByProject = getBrokerByProject($projectId);
$arrBrokerList = array();

foreach ($allBrokerByProject as $key => $val) {
    $brikerList = getBrokerDetailById($key);
    $arrBrokerList[$key] = $brikerList;
}

$arrCalingSecondary = fetchProjectCallingLinks($projectId, 'secondary');
$smarty->assign("arrCalingSecondary", $arrCalingSecondary);

 $brokerIdList = array();
 $maxEffectiveDtAll = '';
     
 $phase_prices = getBrokerPriceByProject($projectId);
     
 $dateBreak = explode("-",$maxEffectiveDtAll );
 $oneMonthAgo = mktime(0, 0, 0, $dateBreak[1]-1, 1, $dateBreak[0]);
 $oneMonthAgoDt = date('Y-m',$oneMonthAgo)."-01 00:00:00";
 $twoMonthAgo = mktime(0, 0, 0, $dateBreak[1]-2, 1, $dateBreak[0]);
 $twoMonthAgoDt = date('Y-m',$twoMonthAgo)."-01 00:00:00";

$noPhasePhase = ResiProjectPhase::getNoPhaseForProject($projectId);
$noPhasePhaseId = $noPhasePhase->phase_id;

 $smarty->assign("oneMonthAgoDt",  $oneMonthAgoDt);
 $smarty->assign("twoMonthAgoDt", $twoMonthAgoDt);

$smarty->assign("twoMonthAgoPrice", $twoMonthAgoPrice);
$smarty->assign("minMaxSum", $minMaxSum);
$smarty->assign("allBrokerByProject", $arrBrokerList);

 $smarty->assign('phase_prices', $phase_prices);
$smarty->assign("brokerIdList", $brokerIdList);

$smarty->assign("maxEffectiveDt", $maxEffectiveDtAll);

$smarty->assign("arrCampaign", CampaignDids::allCampaign());
$smarty->assign("noPhasePhaseId", $noPhasePhaseId);

//code for distinct unit for a project
$arrProjectType = fetch_projectOptions($projectId);
$arrPType = array();
foreach ($arrProjectType as $val) {
    $exp = explode("-", $val);
    if (!in_array(trim($exp[0]), $arrPType))
        array_push($arrPType, trim($exp[0]));
}
$updatedTypes = ProjectSecondaryPrice::getSecondryPriceUpdatedTypes($projectId);
$arrPType = array_unique(array_merge($arrPType, $updatedTypes));
$smarty->assign("arrPType", $arrPType);
/* * code for secondary price dispaly*********** */
function availebilitydescendingOrder($projectId) {
            $qry = "SELECT 
            resi_project_options.BEDROOMS as bedrooms,
            GROUP_CONCAT(project_availabilities.availability order by project_availabilities.effective_month asc separator '|') availabilityArr 
        FROM
            project_supplies
                INNER JOIN
            listings ON listings.id = project_supplies.listing_id AND listings.status = 'Active' AND listings.listing_category='Primary'
                INNER JOIN
            resi_project_phase ON resi_project_phase.PHASE_ID = listings.phase_id AND resi_project_phase.version = 'Cms'
                INNER JOIN
            resi_project_options ON resi_project_options.OPTIONS_ID = listings.option_id
                left join
            project_availabilities ON project_supplies.id = project_availabilities.project_supply_id
        WHERE
            project_supplies.version = 'Cms' AND (project_supplies.version = 'Cms' and resi_project_phase.PROJECT_ID = '$projectId' and resi_project_phase.STATUS = 'Active')
           group by resi_project_phase.phase_id,resi_project_options.BEDROOMS,resi_project_options.OPTION_TYPE";
        $res = mysql_query($qry) or die(mysql_error());
        $arrOrder = array();
        $flag = 'true';
        while($data = mysql_fetch_assoc($res)) {
            $arrExp = explode('|',$data['availabilityArr']);
           
            $fstStr = implode("|",$arrExp);
            arsort($arrExp);
            $scndStr = implode("|",$arrExp);
           
            if(!($fstStr === $scndStr)){
                $flag = 'false';
                $arrOrder['arrOrder'] = $data['availabilityArr'];
                $arrOrder['bedrooms'] = $data['bedrooms'];
            }
        }
        $arrOrder['flg'] = $flag;
        return $arrOrder;  
}
$msg = '';
if(isset($_REQUEST['flag'])){
    if($_REQUEST['flag'] == 1)
        $msg = "callerNumber Inserted Successfully";
    else
        $msg = "callerNumber Not Inserted";
}
$smarty->assign("callerMessage", $msg);
$smarty->assign("localityAvgPrice", getLocalityAveragePrice($projectDetails[0]['LOCALITY_ID']));

/*******code for check user have access to move in audit1 stage or not***********/
if($_SESSION['DEPARTMENT'] == 'CALLCENTER') {
$qryChk = ResiProject::virtual_find($projectId);
$projectAssign = $qryAllProj = "select pa.* from project_assignment pa
                    where pa.movement_history_id = $qryChk->movement_history_id and pa.updation_cycle_id 
                        = '$qryChk->updation_cycle_id' and pa.assigned_to = '".$_SESSION['adminId']."'";
$projectAssignData = ProjectAssignment::find_by_sql($projectAssign);
$smarty->assign("projectMoveValidation", count($projectAssignData));
}
else {
    $smarty->assign("projectMoveValidation", -999);
}
/*******end code for check user have access to move in audit1 stage or not***********/
?>
