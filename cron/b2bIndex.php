<?php
ini_set('display_errors', '1');
ini_set('memory_limit', '-1');
set_time_limit(0);
error_reporting(E_ALL);

//TODO
// Bulk Insert in table
// Get demand data
// verify units sold data .. should be less than inventory

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

$bulkInsert = FALSE;
if(isset($argv[1]) && $argv[1] == 'bulkInsert')$bulkInsert = TRUE;

Logger::configure( dirname(__FILE__) . '/../log4php.xml');
$logger = Logger::getLogger("main");

DInventoryPriceTmp::connection()->query("TRUNCATE TABLE d_inventory_prices_tmp");

$logger->info("\n\n\nDeleted All rows");

$aProjectPhaseCount = ResiProjectPhase::getWebsitePhaseCountForProjects();
$aAllProjects = ResiProject::find_by_sql("select rp.PROJECT_ID, rb.BUILDER_ID, rb.BUILDER_NAME, l.LOCALITY_ID, l.LABEL as LOCALITY_NAME, c.CITY_ID, c.LABEL as CITY_NAME, psm.display_name as construction_status  from resi_project rp inner join project_status_master psm on rp.PROJECT_STATUS_ID = psm.id inner join resi_builder rb on rp.BUILDER_ID = rb.BUILDER_ID inner join locality l on rp.LOCALITY_ID = l.LOCALITY_ID inner join suburb s on l.SUBURB_ID = s.SUBURB_ID inner join city c on s.CITY_ID = c.CITY_ID left join updation_cycle uc on rp.UPDATION_CYCLE_ID = uc.UPDATION_CYCLE_ID where rp.version = 'Website' and (uc.LABEL != 'Skip Updation' or uc.LABEL is null) and rp.STATUS = 'Active' and RESIDENTIAL_FLAG = 'Residential' and psm.project_status not in ('Cancelled', 'OnHold', 'NotLaunched')");
$aAllIndexedProjects = indexProjects($aAllProjects);

$logger->info("Project And Phase Details Retrieved");

$i = 0;
while($i< count($aAllProjects)){
    $handle = fopen("/tmp/" . DInventoryPriceTmp::table_name() . ".csv", "w+");
    $aPid = array();
    for($j=1; $j<=1000 && $i< count($aAllProjects); $j++){
        $aPid[] = $aAllProjects[$i]->project_id;
        $i=$i+1;
    }
    
    $logger->info("Retrieving price and inventory data");
    $aAllInventory = ProjectAvailability::getInventoryForIndexing($aPid);
    $aAllPrice = ListingPrices::getPriceForIndexing($aPid);
    $logger->info("Price and inventory data retrieved");
    
    removeInvalidPhaseData($aAllInventory);
    removeInvalidPhaseData($aAllPrice);
    
    fillIntermediateMonths($aAllInventory);
    fillIntermediateMonths($aAllPrice);
    
    indexPriceInventoryData($aAllInventory);
    indexPriceInventoryData($aAllPrice);
    
    createDocuments($aAllInventory, $aAllPrice);
    
    if($bulkInsert){
        importTableFromTmpCsv(DInventoryPriceTmp::table_name());
        fclose($handle);
    }
    
    $logger->info("Indexing complete for $i projects");
}

$handle = fopen("/tmp/" . DInventoryPriceTmp::table_name() . ".csv", "w+");
indexProjectsWithLowerLaunchDate();
indexProjectsWithHigherCompletionDate();
if($bulkInsert){
    importTableFromTmpCsv(DInventoryPriceTmp::table_name());
    fclose($handle);
}

if(runTests()){
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
        
        $arrayToPick = isset($aAllInventory[$key])? $aAllInventory : $aAllPrice;
        
        $entry['project_id'] = $arrayToPick[$key]->project_id;
        $entry['project_name'] = $arrayToPick[$key]->project_name;
        $entry['phase_id'] = $arrayToPick[$key]->phase_id;
        $entry['phase_name'] = $arrayToPick[$key]->phase_name;
        $entry['phase_type'] =  $arrayToPick[$key]->phase_type;        
        
        $effectiveMonth = $arrayToPick[$key]->effective_month;
        $entry['effective_month'] = $effectiveMonth;
        $entry['quarter'] = firstDayOf('quarter', $entry['effective_month']); //$arrayToPick[$key]->quarter;
        $entry['half_year'] = firstDayOf('half_year', $entry['effective_month']); //$arrayToPick[$key]->half_year;
        $entry['year'] = firstDayOf('year', $entry['effective_month']); //$arrayToPick[$key]->year;
        if($arrayToPick[$key]->completion_date != INVALID_DATE)$entry['completion_date']= $arrayToPick[$key]->completion_date;
        if($arrayToPick[$key]->launch_date != INVALID_DATE)$entry['launch_date'] = $arrayToPick[$key]->launch_date;
        $bedrooms = $arrayToPick[$key]->bedrooms;
        if(is_int($bedrooms))$entry['bedrooms'] = $bedrooms;
        $entry['unit_type'] = isset($arrayToPick[$key])? $arrayToPick[$key]->unit_type : $aAllPrice[$key]->unit_type;
        $entry['bedrooms'] = isset($arrayToPick[$key])? $arrayToPick[$key]->bedrooms : $aAllPrice[$key]->bedrooms;
        
        if(isset($aAllPrice[$key])){
            $entry['average_price_per_unit_area'] = $aAllPrice[$key]->average_price_per_unit_area;
            $entry['average_size'] = $aAllPrice[$key]->average_size;
            $entry['all_size'] = json_encode(explode (',', $aAllPrice[$key]->size));
            $entry['average_total_price'] = $aAllPrice[$key]->average_total_price;
        }
        if(isset($aAllInventory[$key])){
            $entry['supply'] = $aAllInventory[$key]->supply;
            $entry['launched_unit'] = $aAllInventory[$key]->launched;
            $entry['inventory'] = $aAllInventory[$key]->inventory;
            if(isset($aAllInventory[$prevKey]) && $aAllInventory[$key]->key_without_month === $aAllInventory[$prevKey]->key_without_month)$entry['units_sold'] = $aAllInventory[$prevKey]->inventory - $aAllInventory[$key]->inventory;
        }
        
        setProjectLevelValues($entry);
        
        $prevKey = $key;
 
        if($bulkInsert){
            $x = new DInventoryPriceTmp($entry);
            fwrite($handle, str_replace(",,", ",NULL,", str_replace(",,", ",NULL,", $x->to_csv()))."\r\n");
        }else{
            $x->save();
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
        if(isset($aData[$i+1]) && $currData->key_without_month === $aData[$i+1]->key_without_month)$fillTill = getMonthShiftedDate($aData[$i+1]->effective_month, -1);
        
        while(substr($currData->effective_month, 0, 10)<$fillTill){
            $nextMonth = getMonthShiftedDate($currData->effective_month, 1);
            $currData->unique_key = str_replace(substr($currData->effective_month, 0, 10), $nextMonth, $currData->unique_key);
            $currData->effective_month = $nextMonth;
            array_push($aNewData, clone $currData);
        }
    }
    $logger->info("Filling missing months operation complete");
    $aData = $aNewData;
}

function indexPriceInventoryData(&$aData){
    global $logger;
    $result = array();
    foreach ($aData as $data) {
        $result[$data->unique_key] = $data;
    }
    $logger->info("Indexing on unique key complete");
    $aData = $result;
}

function getMonthShiftedDate($date, $shift){
    $date = substr($date, 0, 10);
    return date("Y-m-d", strtotime("$date $shift month"));
}


function indexProjectsWithLowerLaunchDate(){
    global $logger;
    global $handle;
    global $bulkInsert;
    
    $sql = "select 1 as country_id, 'India' as country_name, rp.PROJECT_ID, rp.PROJECT_NAME, rpp.PHASE_ID, rpp.PHASE_NAME, rpp.PHASE_TYPE, date_format(rpp.COMPLETION_DATE, '%Y-%m-01') COMPLETION_DATE, date_format(if(rpp.LAUNCH_DATE = 0, rp.PRE_LAUNCH_DATE, rpp.LAUNCH_DATE), '%Y-%m-01') LAUNCH_DATE, rpo.OPTION_TYPE as unit_type, rpo.BEDROOMS, avg(rpo1.SIZE) as average_size, group_concat(rpo1.SIZE) as all_size, ps.supply, ps.launched launched_unit from resi_project rp inner join project_status_master psm on rp.PROJECT_STATUS_ID = psm.id left join updation_cycle uc on rp.UPDATION_CYCLE_ID = uc.UPDATION_CYCLE_ID inner join resi_project_phase rpp on rp.PROJECT_ID = rpp.PROJECT_ID and rpp.version = 'Website' and rpp.status = 'Active' inner join listings l on rpp.PHASE_ID = l.phase_id and l.status = 'Active' inner join resi_project_options rpo on l.option_id = rpo.OPTIONS_ID and rpo.OPTION_CATEGORY = 'Logical' inner join resi_project_options rpo1 on rpo.PROJECT_ID = rpo1.PROJECT_ID and rpo.OPTION_TYPE = rpo1.OPTION_TYPE and rpo.BEDROOMS = rpo1.BEDROOMS and rpo1.OPTION_CATEGORY = 'Actual' inner join project_supplies ps on l.id = ps.listing_id and ps.version = 'Website' left join d_inventory_prices_tmp dip on rpp.phase_id = dip.phase_id and rpo.OPTION_TYPE = dip.unit_type and (rpo.BEDROOMS = dip.bedrooms or rpo.BEDROOMS is null) and (date_format(if(rpp.LAUNCH_DATE = 0, rp.PRE_LAUNCH_DATE, rpp.LAUNCH_DATE), '%Y-%m-01') = dip.effective_month) where rp.version = 'Website' and (uc.LABEL != 'Skip Updation' or uc.LABEL is null) and rp.STATUS = 'Active' and RESIDENTIAL_FLAG = 'Residential' and psm.project_status not in ('Cancelled', 'OnHold', 'NotLaunched') and (rpp.LAUNCH_DATE != 0 or rp.PRE_LAUNCH_DATE != 0) and dip.id is null group by ps.id";
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
            $entry['year'] = firstDayOf('year', $entry['effective_month']);
            $entry['half_year'] = firstDayOf('half_year', $entry['effective_month']);
            $entry['quarter'] = firstDayOf('quarter', $entry['effective_month']);
            
            setProjectLevelValues($entry);
            
            if($bulkInsert){
                $x = new DInventoryPriceTmp($entry);
                fwrite($handle, str_replace(",,", ",NULL,", str_replace(",,", ",NULL,", $x->to_csv()))."\r\n");
            }else{
                $x->save();
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
    
    $sql = "select 1 as country_id, 'India' as country_name, rp.PROJECT_ID, rp.PROJECT_NAME, rpp.PHASE_ID, rpp.PHASE_NAME, rpp.PHASE_TYPE, date_format(rpp.COMPLETION_DATE, '%Y-%m-01') COMPLETION_DATE, date_format(if(rpp.LAUNCH_DATE = 0, rp.PRE_LAUNCH_DATE, rpp.LAUNCH_DATE), '%Y-%m-01') LAUNCH_DATE, rpo.OPTION_TYPE as unit_type, rpo.BEDROOMS, avg(rpo1.SIZE) as average_size, group_concat(rpo1.SIZE) as all_size, ps.supply, ps.launched launched_unit from resi_project rp inner join project_status_master psm on rp.PROJECT_STATUS_ID = psm.id left join updation_cycle uc on rp.UPDATION_CYCLE_ID = uc.UPDATION_CYCLE_ID inner join resi_project_phase rpp on rp.PROJECT_ID = rpp.PROJECT_ID and rpp.version = 'Website' and rpp.status = 'Active' inner join listings l on rpp.PHASE_ID = l.phase_id and l.status = 'Active' inner join resi_project_options rpo on l.option_id = rpo.OPTIONS_ID and rpo.OPTION_CATEGORY = 'Logical' inner join resi_project_options rpo1 on rpo.PROJECT_ID = rpo1.PROJECT_ID and rpo.OPTION_TYPE = rpo1.OPTION_TYPE and rpo.BEDROOMS = rpo1.BEDROOMS and rpo1.OPTION_CATEGORY = 'Actual' inner join project_supplies ps on l.id = ps.listing_id and ps.version = 'Website' left join d_inventory_prices_tmp dip on rpp.phase_id = dip.phase_id and rpo.OPTION_TYPE = dip.unit_type and (rpo.BEDROOMS = dip.bedrooms or rpo.BEDROOMS is null) and date_format(rpp.COMPLETION_DATE, '%Y-%m-01') = dip.effective_month where rp.version = 'Website' and (uc.LABEL != 'Skip Updation' or uc.LABEL is null) and rp.STATUS = 'Active' and RESIDENTIAL_FLAG = 'Residential' and psm.project_status not in ('Cancelled', 'OnHold', 'NotLaunched') and rpp.COMPLETION_DATE != 0 and dip.id is null group by ps.id";
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
            $entry['year'] = firstDayOf('year', $entry['effective_month']);
            $entry['half_year'] = firstDayOf('half_year', $entry['effective_month']);
            $entry['quarter'] = firstDayOf('quarter', $entry['effective_month']);
            
            setProjectLevelValues($entry);
            
            if($bulkInsert){
                $x = new DInventoryPriceTmp($entry);
                fwrite($handle, str_replace(",,", ",NULL,", str_replace(",,", ",NULL,", $x->to_csv()))."\r\n");
            }else{
                $x->save();
            }
            $i++;
        }
    }
    $logger->info("Inserted $i missing completion date entries");
}

function indexProjects($aAllProjects){
    $aAll = array();
    foreach ($aAllProjects as $value) {
        $aAll[$value->project_id] = $value;
    }
    return $aAll;
}

function setProjectLevelValues(&$entry){
    global $aAllIndexedProjects;
    
    $projectId = $entry['project_id'];
    
    $entry['country_id'] = 1;
    $entry['country_name'] = 'India';
    $entry['locality_id'] = $aAllIndexedProjects[$projectId]->locality_id;
    $entry['locality_name'] = $aAllIndexedProjects[$projectId]->locality_name;
    $entry['city_id'] = $aAllIndexedProjects[$projectId]->city_id;
    $entry['city_name'] = $aAllIndexedProjects[$projectId]->city_name;
    $entry['builder_id'] = $aAllIndexedProjects[$projectId]->builder_id;
    $entry['builder_name'] = $aAllIndexedProjects[$projectId]->builder_name;
    $entry['construction_status'] = $aAllIndexedProjects[$projectId]->construction_status;
}

function importTableFromTmpCsv($tableName){
    exec('mysqlimport --local --fields-optionally-enclosed-by="\"" --fields-terminated-by=, --lines-terminated-by="\r\n" -u' . DB_PROJECT_USER . ' -p' . DB_PROJECT_PASS . ' ' . DB_PROJECT_NAME . ' /tmp/' . $tableName . '.csv');
}
?>