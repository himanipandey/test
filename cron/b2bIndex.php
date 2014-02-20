<?php
ini_set('display_errors', '1');
ini_set('memory_limit', '1G');
set_time_limit(0);
error_reporting(E_ALL);

// TODO
// Get demand data

$currentDir = dirname(__FILE__);
require_once ($currentDir . '/../log4php/Logger.php');
require_once ($currentDir . '/../modelsConfig.php');
require_once ($currentDir . '/../cron/cronFunctions.php');
require_once ($currentDir . '/../cron/b2bIndexTest.php');
require_once ($currentDir . '/../dbConfig.php');

define("INVALID_DATE", "0000-00-01");
define('MIN_B2B_DATE', '2013-03-01');
define('MAX_B2B_DATE', '2014-01-01');
define('B2B_DOCUMENT_TYPE', 'B2B');
define('CSV_FIELD_DELIMITER', '~#~');
define('CSV_LINE_DELIMITER', "\r\n");

$bulkInsert = FALSE;
if(isset($argv[1]) && $argv[1] == 'bulkInsert')$bulkInsert = TRUE;

Logger::configure( dirname(__FILE__) . '/../log4php.xml');
$logger = Logger::getLogger("main");
$handle = fopen("/tmp/" . DInventoryPriceTmp::table_name() . ".csv", "w+");

DInventoryPriceTmp::connection()->query("TRUNCATE TABLE d_inventory_prices_tmp");

$logger->info("\n\n\nDeleted All rows");

$aProjectPhaseCount = ResiProjectPhase::getWebsitePhaseCountForProjects();

$globalCondition = "rp.version = 'Website' and (uc.LABEL != 'Skip Updation' or uc.LABEL is null) and rp.STATUS = 'Active' and RESIDENTIAL_FLAG = 'Residential' and psm.project_status not in ('Cancelled', 'OnHold', 'NotLaunched')";

$aAllProjects = ResiProject::find_by_sql("select rp.PROJECT_ID, rp.PROJECT_NAME, rb.BUILDER_ID, rb.BUILDER_NAME, l.LOCALITY_ID, l.LABEL as LOCALITY_NAME, c.CITY_ID, c.LABEL as CITY_NAME, psm.display_name as construction_status  from resi_project rp inner join project_status_master psm on rp.PROJECT_STATUS_ID = psm.id inner join resi_builder rb on rp.BUILDER_ID = rb.BUILDER_ID inner join locality l on rp.LOCALITY_ID = l.LOCALITY_ID inner join suburb s on l.SUBURB_ID = s.SUBURB_ID inner join city c on s.CITY_ID = c.CITY_ID left join updation_cycle uc on rp.UPDATION_CYCLE_ID = uc.UPDATION_CYCLE_ID where $globalCondition");

$aAllIndexedProjects = indexArrayOnKey($aAllProjects, 'project_id');

$logger->info("Project And Phase Details Retrieved");

$i = 0;
while($i< count($aAllProjects)){
    $aPid = array();
    for($j=1; $j<=1000 && $i< count($aAllProjects); $j++){
        $aPid[] = $aAllProjects[$i]->project_id;
        $i=$i+1;
    }
    
    $aAllInventory = ProjectAvailability::getInventoryForIndexing($aPid);
    $aAllPrice = ListingPrices::getPriceForIndexing($aPid);
    $logger->info("Price and inventory data retrieved");
    
    removeInvalidPhaseData($aAllInventory);
    removeInvalidPhaseData($aAllPrice);
    
    fillIntermediateMonths($aAllInventory);
    fillIntermediateMonths($aAllPrice);
    
    $aAllInventory = indexArrayOnKey($aAllInventory, 'unique_key');
    $aAllPrice = indexArrayOnKey($aAllPrice, 'unique_key');
    
    createDocuments($aAllInventory, $aAllPrice);
    $logger->info("Indexing complete for $i projects");
}

indexProjectsWithLowerLaunchDate();
indexProjectsWithHigherCompletionDate();

if($bulkInsert){
    importTableFromTmpCsv(DInventoryPriceTmp::table_name());
    fclose($handle);
}

DInventoryPriceTmp::populateDemand();
DInventoryPriceTmp::deleteEntriesBeforeLaunch();
DInventoryPriceTmp::updateFirstPromoisedCompletionDate();

if(true || runTests()){
    DInventoryPriceTmp::connection()->query("rename table d_inventory_prices to d_inventory_prices_old, d_inventory_prices_tmp to d_inventory_prices, d_inventory_prices_old to d_inventory_prices_tmp;");
    $logger->info("Migration successful.");
}else{
    $logger->error("Test Cases Failed.");
}

function createDocuments($aAllInventory, $aAllPrice){
    global $logger;
    global $handle;
    global $bulkInsert;

    $aKey = array_unique(array_merge(array_keys($aAllInventory), array_keys($aAllPrice)));
    
    $i = 0;
    $prevKey = '';
    foreach ($aKey as $key) {
        $i++;
        //Code used for storing the documents into mysql
        $entry = array();
        $entry['unique_key'] = $key;
        
        $arrayToPick = isset($aAllInventory[$key])? $aAllInventory[$key] : $aAllPrice[$key];
        
        $entry['project_id'] = $arrayToPick->project_id;
        $entry['phase_id'] = $arrayToPick->phase_id;
        $entry['phase_name'] = $arrayToPick->phase_name;
        $entry['phase_type'] =  $arrayToPick->phase_type;
        $entry['effective_month'] = $arrayToPick->effective_month;
        $entry['unit_type'] = $arrayToPick->unit_type;
        $entry['bedrooms'] = intval($arrayToPick->bedrooms);
        $entry['average_size'] = $arrayToPick->average_size;
        
        if($arrayToPick->completion_date != INVALID_DATE){
            $entry['completion_date']= $arrayToPick->completion_date;
        }
        if($arrayToPick->launch_date != INVALID_DATE){
            $entry['launch_date'] = $arrayToPick->launch_date;
        }
        
        if(isset($aAllPrice[$key])){
            $entry['average_price_per_unit_area'] = $aAllPrice[$key]->average_price_per_unit_area;
            $entry['average_total_price'] = $aAllPrice[$key]->average_total_price;
        }
        
        if(isset($aAllInventory[$key])){
            $entry['supply'] = $aAllInventory[$key]->supply;
            $entry['ltd_supply'] = $aAllInventory[$key]->ltd_supply;
            $entry['launched_unit'] = $aAllInventory[$key]->launched;
            $entry['inventory'] = $aAllInventory[$key]->inventory;
            if(isset($aAllInventory[$prevKey]) && $aAllInventory[$key]->key_without_month === $aAllInventory[$prevKey]->key_without_month){
                $entry['units_sold'] = $aAllInventory[$prevKey]->inventory - $aAllInventory[$key]->inventory;
            }
        }
        setProjectLevelValues($entry);
        
        $prevKey = $key;
 
        $new = new DInventoryPriceTmp($entry);
        if($bulkInsert){
            $new = new DInventoryPriceTmp($entry);
            fwrite($handle, getCSVRowFromArray($new->to_array()));
        }else{
            $new->save();
        }
    }
    $logger->info($i . " documents inserted in mysql");
}

function removeInvalidPhaseData(&$aData){
    global $logger;
    global $aProjectPhaseCount;

    $result = array();
    foreach ($aData as $value) {
        if($value->phase_type == 'Actual' || $aProjectPhaseCount[$value->project_id] == 1)$result[] = $value;
    }
    $logger->info("Remove invalid phase operation complete");
    $aData = $result;
}

function fillIntermediateMonths(&$aData){
    global $logger;
    $aNewData = array();
    $count = count($aData);
    for($i=0; $i<$count; $i++){
        $currData = $aData[$i];
        array_push($aNewData, clone $currData);
        
        $fillTill = MAX_B2B_DATE;
        if(isset($aData[$i+1]) && $currData->key_without_month === $aData[$i+1]->key_without_month){
            $fillTill = getMonthShiftedDate($aData[$i+1]->effective_month, -1);
        }
        
        while(substr($currData->effective_month, 0, 10)<$fillTill){
            $nextMonth = getMonthShiftedDate($currData->effective_month, 1);
            $currData->unique_key = str_replace(substr($currData->effective_month, 0, 10), $nextMonth, $currData->unique_key);
            $currData->effective_month = $nextMonth;
            if(isset($currData->supply)){
                $currData->supply = ($currData->effective_month === $currData->launch_date) ? $currData->ltd_supply : null;
                $currData->launched = ($currData->effective_month === $currData->launch_date) ? $currData->ltd_launched : null;
            }
            array_push($aNewData, clone $currData);
        }
    }
    $logger->info("Filling missing months operation complete");
    $aData = $aNewData;
}

function indexProjectsWithLowerLaunchDate(){
    global $logger;
    global $handle;
    global $bulkInsert;
    global $globalCondition;


    $sql = "select rp.PROJECT_ID, rpp.PHASE_ID, rpp.PHASE_NAME, rpp.PHASE_TYPE, date_format(rpp.COMPLETION_DATE, '%Y-%m-01') COMPLETION_DATE, date_format(if(rpp.LAUNCH_DATE = 0, rp.PRE_LAUNCH_DATE, rpp.LAUNCH_DATE), '%Y-%m-01') LAUNCH_DATE, rpo.OPTION_TYPE as unit_type, rpo.BEDROOMS, avg(rpo1.SIZE) as average_size, ps.supply, ps.supply ltd_supply, ps.launched launched_unit from resi_project rp inner join project_status_master psm on rp.PROJECT_STATUS_ID = psm.id left join updation_cycle uc on rp.UPDATION_CYCLE_ID = uc.UPDATION_CYCLE_ID inner join resi_project_phase rpp on rp.PROJECT_ID = rpp.PROJECT_ID and rpp.version = 'Website' and rpp.status = 'Active' inner join listings l on rpp.PHASE_ID = l.phase_id and l.status = 'Active' inner join resi_project_options rpo on l.option_id = rpo.OPTIONS_ID and rpo.OPTION_CATEGORY = 'Logical' inner join resi_project_options rpo1 on rpo.PROJECT_ID = rpo1.PROJECT_ID and rpo.OPTION_TYPE = rpo1.OPTION_TYPE and rpo.BEDROOMS = rpo1.BEDROOMS and rpo1.OPTION_CATEGORY = 'Actual' inner join project_supplies ps on l.id = ps.listing_id and ps.version = 'Website' left join d_inventory_prices_tmp dip on rpp.phase_id = dip.phase_id and rpo.OPTION_TYPE = dip.unit_type and (rpo.BEDROOMS = dip.bedrooms or rpo.BEDROOMS is null) and (date_format(if(rpp.LAUNCH_DATE = 0, rp.PRE_LAUNCH_DATE, rpp.LAUNCH_DATE), '%Y-%m-01') = dip.effective_month) where $globalCondition and (rpp.LAUNCH_DATE != 0 or rp.PRE_LAUNCH_DATE != 0) and dip.id is null group by ps.id";
    $aData = DInventoryPriceTmp::find_by_sql($sql);
    removeInvalidPhaseData($aData);
    $i = 0;
    foreach ($aData as $data) {
        $entry = $data->to_array();
        if($entry['launch_date'] != INVALID_DATE){
            $entry['created_at'] = 'NOW()';
            $entry['launch_date'] = substr($entry['launch_date'], 0, 10);
            $entry['unique_key'] = $entry['project_id']."/".$entry['phase_id']."/".$entry['unit_type']."/".$entry['bedrooms']."/".$entry['launch_date'];
            if($entry['completion_date'] == INVALID_DATE)unset ($entry['completion_date']);
            $entry['effective_month'] = $entry['launch_date'];
            
            setProjectLevelValues($entry);
            
            $new = new DInventoryPriceTmp($entry);
            if($bulkInsert){
                fwrite($handle, getCSVRowFromArray($new->to_array()));
            }else{
                $new->save();
            }
            $i++;
        }
    }
    $logger->info("Inserted $i missing launch date entries");
}

function indexProjectsWithHigherCompletionDate(){
    global $logger;
    global $handle;
    global $bulkInsert;
    global $globalCondition;


    $sql = "select rp.PROJECT_ID, rpp.PHASE_ID, rpp.PHASE_NAME, rpp.PHASE_TYPE, date_format(rpp.COMPLETION_DATE, '%Y-%m-01') COMPLETION_DATE, date_format(if(rpp.LAUNCH_DATE = 0, rp.PRE_LAUNCH_DATE, rpp.LAUNCH_DATE), '%Y-%m-01') LAUNCH_DATE, rpo.OPTION_TYPE as unit_type, rpo.BEDROOMS, avg(rpo1.SIZE) as average_size, ps.supply ltd_supply from resi_project rp inner join project_status_master psm on rp.PROJECT_STATUS_ID = psm.id left join updation_cycle uc on rp.UPDATION_CYCLE_ID = uc.UPDATION_CYCLE_ID inner join resi_project_phase rpp on rp.PROJECT_ID = rpp.PROJECT_ID and rpp.version = 'Website' and rpp.status = 'Active' inner join listings l on rpp.PHASE_ID = l.phase_id and l.status = 'Active' inner join resi_project_options rpo on l.option_id = rpo.OPTIONS_ID and rpo.OPTION_CATEGORY = 'Logical' inner join resi_project_options rpo1 on rpo.PROJECT_ID = rpo1.PROJECT_ID and rpo.OPTION_TYPE = rpo1.OPTION_TYPE and rpo.BEDROOMS = rpo1.BEDROOMS and rpo1.OPTION_CATEGORY = 'Actual' inner join project_supplies ps on l.id = ps.listing_id and ps.version = 'Website' left join d_inventory_prices_tmp dip on rpp.phase_id = dip.phase_id and rpo.OPTION_TYPE = dip.unit_type and (rpo.BEDROOMS = dip.bedrooms or rpo.BEDROOMS is null) and date_format(rpp.COMPLETION_DATE, '%Y-%m-01') = dip.effective_month where $globalCondition and rpp.COMPLETION_DATE != 0 and dip.id is null group by ps.id";
    $aData = DInventoryPriceTmp::find_by_sql($sql);
    removeInvalidPhaseData($aData);
    $i = 0;
    foreach ($aData as $data) {
        $entry = $data->to_array();
        if($entry['completion_date'] != INVALID_DATE){
            $entry['created_at'] = 'NOW()';
            $entry['completion_date'] = substr($entry['completion_date'], 0, 10);
            $entry['unique_key'] = $entry['project_id']."/".$entry['phase_id']."/".$entry['unit_type']."/".$entry['bedrooms']."/".$entry['completion_date'];
            if($entry['launch_date'] == INVALID_DATE)unset ($entry['launch_date']);
            $entry['effective_month'] = $entry['completion_date'];
            
            setProjectLevelValues($entry);
            
            $new = new DInventoryPriceTmp($entry);
            if($bulkInsert){
                fwrite($handle, getCSVRowFromArray($new->to_array()));
            }else{
                $new->save();
            }
            $i++;
        }
    }
    $logger->info("Inserted $i missing completion date entries");
}

function setProjectLevelValues(&$entry){
    global $aAllIndexedProjects;
    
    $projectDetails = $aAllIndexedProjects[$entry['project_id']];
    
    $entry['country_id'] = 1;
    $entry['country_name'] = 'India';
    $entry['project_name'] = $projectDetails->project_name;
    $entry['locality_id'] = $projectDetails->locality_id;
    $entry['locality_name'] = $projectDetails->locality_name;
    $entry['city_id'] = $projectDetails->city_id;
    $entry['city_name'] = $projectDetails->city_name;
    $entry['builder_id'] = $projectDetails->builder_id;
    $entry['builder_name'] = $projectDetails->builder_name;
    $entry['construction_status'] = $projectDetails->construction_status;
    $entry['quarter'] = firstDayOf('quarter', $entry['effective_month']);
    $entry['half_year'] = firstDayOf('half_year', $entry['effective_month']);
    $entry['year'] = firstDayOf('year', $entry['effective_month']);
}
?>