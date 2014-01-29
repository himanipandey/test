<?php
$currentDir = dirname(__FILE__);

define('MIN_B2B_DATE', '2013-03-01');
define('MAX_B2B_DATE', '2014-01-01');
define('B2B_DOCUMENT_TYPE', 'B2B');

//date_default_timezone_set ('UTC');

require_once ($currentDir . '/../log4php/Logger.php');
require_once ($currentDir . '/../modelsConfig.php');


//TODO
// Bulk Insert in table
// Builder Data in last 2 steps
// Get demand data

ini_set('display_errors', '1');
error_reporting(E_ALL);

ini_set('memory_limit', '-1');
set_time_limit(0);
define("INVALID_DATE", "0000-00-01");

Logger::configure( dirname(__FILE__) . '/../log4php.xml');
$logger = Logger::getLogger("main");

//DInventoryPriceTmp::connection()->query("set session sql_mode = 'STRICT_ALL_TABLES'");
DInventoryPriceTmp::connection()->query("TRUNCATE TABLE d_inventory_prices_tmp");

$logger->info("\n\n\nDeleted All rows");

$aProjectPhaseCount = ResiProjectPhase::getWebsitePhaseCountForProjects();
$aAllProjects = ResiProject::find_by_sql('select PROJECT_ID from resi_project where version = "Website"');
$logger->info("Project And Phase Details Retrieved");


$i = 0;
while(false && $i< count($aAllProjects)){
    $aPid = array();
    for($j=1; $j<=1000 && $i< count($aAllProjects); $j++){
        $aPid[] = $aAllProjects[$i]->project_id;
        $i=$i+1;
    }
    
    $logger->info("Retrieving price and inventory data");
    $aAllInventory = ProjectAvailability::getInventoryForIndexing($aPid);
    $aAllPrice = ListingPrices::getPriceForIndexing($aPid);
    $logger->info("Price and inventory data retrieved");
    
    $aAllInventory = removeInvalidPhaseData($aAllInventory, $aProjectPhaseCount);
    $aAllPrice = removeInvalidPhaseData($aAllPrice, $aProjectPhaseCount);
    
    fillIntermediateMonths($aAllInventory);
    fillIntermediateMonths($aAllPrice);
    indexPriceInventoryData($aAllInventory, $aAllPrice);
    
    getSolrDocuments($aAllInventory, $aAllPrice);
    
    $logger->info("Indexing complete for $i projects");
}

indexProjectsWithLowerLaunchDate();
indexProjectsWithHigherCompletionDate();

DInventoryPriceTmp::connection()->query("rename table d_inventory_prices to d_inventory_prices_old, d_inventory_prices_tmp to d_inventory_prices, d_inventory_prices_old to d_inventory_prices_tmp;");


function getSolrDocuments($aAllInventory, $aAllPrice){
    global $logger;
    $aKey = array_unique(array_merge(array_keys($aAllInventory), array_keys($aAllPrice)));
    
    $i = 0;
    $prevKey = '';
    foreach ($aKey as $key) {
        $i++;
        //Code used for storing the documents into mysql
        $mysqlArray = array();
        $mysqlArray['unique_key'] = $key;
        
        $arrayToPick = isset($aAllInventory[$key])? $aAllInventory : $aAllPrice;
        
        //hard coded values as of now
        $mysqlArray['country_id'] = 1;
        $mysqlArray['country_name'] = 'India';
        $mysqlArray['project_id'] = $arrayToPick[$key]->project_id;
        $mysqlArray['project_name'] = $arrayToPick[$key]->project_name;
        $mysqlArray['phase_id'] = $arrayToPick[$key]->phase_id;
        $mysqlArray['phase_name'] = $arrayToPick[$key]->phase_name;
        $mysqlArray['phase_type'] =  $arrayToPick[$key]->phase_type;
        $mysqlArray['locality_id'] = intval($arrayToPick[$key]->locality_id);
        $mysqlArray['locality_name'] = $arrayToPick[$key]->locality_name;
        $mysqlArray['city_id'] = $arrayToPick[$key]->city_id;
        $mysqlArray['city_name'] = $arrayToPick[$key]->city_name;
        $mysqlArray['builder_id'] = $arrayToPick[$key]->builder_id;
        $mysqlArray['builder_name'] = $arrayToPick[$key]->builder_name;
        $effectiveMonth = $arrayToPick[$key]->effective_month;
        $mysqlArray['effective_month'] = $effectiveMonth;
        $mysqlArray['quarter'] = firstDayOf('quarter', $mysqlArray['effective_month']); //$arrayToPick[$key]->quarter;
        $mysqlArray['half_year'] = firstDayOf('half_year', $mysqlArray['effective_month']); //$arrayToPick[$key]->half_year;
        $mysqlArray['year'] = firstDayOf('year', $mysqlArray['effective_month']); //$arrayToPick[$key]->year;
        if($arrayToPick[$key]->completion_date != INVALID_DATE)$mysqlArray['completion_date']= $arrayToPick[$key]->completion_date;
        if($arrayToPick[$key]->launch_date != INVALID_DATE)$mysqlArray['launch_date'] = $arrayToPick[$key]->launch_date;
        $bedrooms = $arrayToPick[$key]->bedrooms;
        if(is_int($bedrooms))$mysqlArray['bedrooms'] = $bedrooms;
        $mysqlArray['unit_type'] = isset($arrayToPick[$key])? $arrayToPick[$key]->unit_type : $aAllPrice[$key]->unit_type;
        $mysqlArray['bedrooms'] = isset($arrayToPick[$key])? $arrayToPick[$key]->bedrooms : $aAllPrice[$key]->bedrooms;
        
        if(isset($aAllPrice[$key])){
            $mysqlArray['average_price_per_unit_area'] = $aAllPrice[$key]->average_price_per_unit_area;
            $mysqlArray['average_size'] = $aAllPrice[$key]->average_size;
            $mysqlArray['all_size'] = json_encode(explode (',', $aAllPrice[$key]->size));
            $mysqlArray['average_total_price'] = $aAllPrice[$key]->average_total_price;
        }
        if(isset($aAllInventory[$key])){
            $mysqlArray['supply'] = $aAllInventory[$key]->supply;
            $mysqlArray['launched_unit'] = $aAllInventory[$key]->launched;
            $mysqlArray['inventory'] = $aAllInventory[$key]->inventory;
            if(isset($aAllInventory[$prevKey]) && $aAllInventory[$key]->key_without_month === $aAllInventory[$prevKey]->key_without_month)$mysqlArray['units_sold'] = $aAllInventory[$prevKey]->inventory - $aAllInventory[$key]->inventory;
        }
        
        $prevKey = $key;
        
        $x = DInventoryPriceTmp::create($mysqlArray);
        $x->save();
    }
    $logger->info($i . " documents inserted in mysql");
}

function removeInvalidPhaseData($aData, $aProjectPhaseCount){
    global $logger;
    $result = array();
    foreach ($aData as $value) {
        if($value->phase_type == 'Actual' || $aProjectPhaseCount[$value->project_id] == 1)$result[] = $value;
    }
    $logger->info("Remove invalid phase operation complete");
    return $result;
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

function indexPriceInventoryData(&$aAllInventory, &$aAllPrice){
    global $logger;
    $aNewAllInventory = array();
    foreach ($aAllInventory as $value) {
        $key = $value->unique_key;
        $aNewAllInventory[$key] = $value;
    }
    $aAllInventory = $aNewAllInventory;
    
    $aNewAllPrice = array();
    foreach ($aAllPrice as $value) {
        $key = $value->unique_key;
        $aNewAllPrice[$key] = $value;
    }
    $aAllPrice = $aNewAllPrice;
    $logger->info("Indexing price and inventory data complete");
}

function getMonthShiftedDate($date, $shift){
    $date = substr($date, 0, 10);
    return date("Y-m-d", strtotime("$date $shift month"));
}

function firstDayOf($period, $date = null)
{
    $period = strtolower($period);
    $validPeriods = array('year', 'quarter', 'month', 'week', 'half_year');

    if(is_string($date)){
        $date;
        $date = new DateTime($date);
    }
    
    if ( ! in_array($period, $validPeriods))
        throw new InvalidArgumentException('Period must be one of: ' . implode(', ', $validPeriods));
 
    $newDate = ($date === null) ? new DateTime() : clone $date;
 
    switch ($period) {
        case 'year':
            $newDate->modify('first day of january ' . $newDate->format('Y'));
            break;
        case 'half_year':
            $month = $newDate->format('n') ;
            
            if($month < 6){
                $newDate->modify('first day of january ' . $newDate->format('Y'));
            } else {
                $newDate->modify('first day of july ' . $newDate->format('Y'));
            }
            break;
        case 'quarter':
            $month = $newDate->format('n') ;
 
            if ($month < 4) {
                $newDate->modify('first day of january ' . $newDate->format('Y'));
            } elseif ($month > 3 && $month < 7) {
                $newDate->modify('first day of april ' . $newDate->format('Y'));
            } elseif ($month > 6 && $month < 10) {
                $newDate->modify('first day of july ' . $newDate->format('Y'));
            } elseif ($month > 9) {
                $newDate->modify('first day of october ' . $newDate->format('Y'));
            }
            break;
        case 'month':
            $newDate->modify('first day of this month');
            break;
        case 'week':
            $newDate->modify(($newDate->format('w') === '0') ? 'monday last week' : 'monday this week');
            break;
    }
    return $newDate;
}


function indexProjectsWithLowerLaunchDate(){
    global $logger;
    global $aAllProjects;
    global $aProjectPhaseCount;
    
    $sql = "select 1 as country_id, 'India' as country_name, rp.PROJECT_ID, rp.PROJECT_NAME, rpp.PHASE_ID, rpp.PHASE_NAME, rpp.PHASE_TYPE, lo.LOCALITY_ID, lo.LABEL as locality_name, c.CITY_ID, c.LABEL as city_name, date_format(rpp.COMPLETION_DATE, '%Y-%m-01') COMPLETION_DATE, date_format(if(rpp.LAUNCH_DATE = 0, rp.PRE_LAUNCH_DATE, rpp.LAUNCH_DATE), '%Y-%m-01') LAUNCH_DATE, rpo.OPTION_TYPE as unit_type, rpo.BEDROOMS, avg(rpo1.SIZE) as average_size, group_concat(rpo1.SIZE) as all_size, ps.supply, ps.launched launched_unit from resi_project rp inner join locality lo on rp.LOCALITY_ID = lo.LOCALITY_ID inner join suburb s on lo.SUBURB_ID = s.SUBURB_ID inner join city c on s.CITY_ID = c.CITY_ID inner join resi_project_phase rpp on rp.PROJECT_ID = rpp.PROJECT_ID and rpp.version = 'Website' inner join listings l on rpp.PHASE_ID = l.phase_id inner join resi_project_options rpo on l.option_id = rpo.OPTIONS_ID and rpo.OPTION_CATEGORY = 'Logical' inner join resi_project_options rpo1 on rpo.PROJECT_ID = rpo1.PROJECT_ID and rpo.OPTION_TYPE = rpo1.OPTION_TYPE and rpo.BEDROOMS = rpo1.BEDROOMS and rpo1.OPTION_CATEGORY = 'Actual' inner join project_supplies ps on l.id = ps.listing_id and ps.version = 'Website' left join d_inventory_prices_tmp dip on rpp.phase_id = dip.phase_id and rpo.OPTION_TYPE = dip.unit_type and (rpo.BEDROOMS = dip.bedrooms or rpo.BEDROOMS is null) and (date_format(if(rpp.LAUNCH_DATE = 0, rp.PRE_LAUNCH_DATE, rpp.LAUNCH_DATE), '%Y-%m-01') = dip.effective_month) where rp.version = 'Website' and (rpp.LAUNCH_DATE != 0 or rp.PRE_LAUNCH_DATE != 0) and dip.id is null group by ps.id";
    $aData = DInventoryPriceTmp::find_by_sql($sql);
    $aData = removeInvalidPhaseData($aData, $aProjectPhaseCount);
    $i = 0;
    foreach ($aData as $data) {
        $mysqlArray = $data->to_array();
        if($mysqlArray['launch_date'] != INVALID_DATE){
            $mysqlArray['created_at'] = 'NOW()';
            $mysqlArray['launch_date'] = substr($mysqlArray['launch_date'], 0, 10);
            $mysqlArray['unique_key'] = $mysqlArray['project_id']."/".$mysqlArray['phase_id']."/".$mysqlArray['unit_type']."/".$mysqlArray['bedrooms']."/".$mysqlArray['launch_date'];
            if($mysqlArray['completion_date'] == INVALID_DATE)unset ($mysqlArray['completion_date']);
            $mysqlArray['effective_month'] = $mysqlArray['launch_date'];
            $mysqlArray['year'] = firstDayOf('year', $mysqlArray['effective_month']);
            $mysqlArray['half_year'] = firstDayOf('half_year', $mysqlArray['effective_month']);
            $mysqlArray['quarter'] = firstDayOf('quarter', $mysqlArray['effective_month']);
            
            DInventoryPriceTmp::create($mysqlArray)->save();
            $i++;
        }
    }
    $logger->info("Inserted $i missing launch date entries");
}

function indexProjectsWithHigherCompletionDate(){
    global $logger;
    global $aAllProjects;
    global $aProjectPhaseCount;
    
    $sql = "select 1 as country_id, 'India' as country_name, rp.PROJECT_ID, rp.PROJECT_NAME, rpp.PHASE_ID, rpp.PHASE_NAME, rpp.PHASE_TYPE, lo.LOCALITY_ID, lo.LABEL as locality_name, c.CITY_ID, c.LABEL as city_name, date_format(rpp.COMPLETION_DATE, '%Y-%m-01') COMPLETION_DATE, date_format(if(rpp.LAUNCH_DATE = 0, rp.PRE_LAUNCH_DATE, rpp.LAUNCH_DATE), '%Y-%m-01') LAUNCH_DATE, rpo.OPTION_TYPE as unit_type, rpo.BEDROOMS, avg(rpo1.SIZE) as average_size, group_concat(rpo1.SIZE) as all_size, ps.supply, ps.launched launched_unit from resi_project rp inner join locality lo on rp.LOCALITY_ID = lo.LOCALITY_ID inner join suburb s on lo.SUBURB_ID = s.SUBURB_ID inner join city c on s.CITY_ID = c.CITY_ID inner join resi_project_phase rpp on rp.PROJECT_ID = rpp.PROJECT_ID and rpp.version = 'Website' inner join listings l on rpp.PHASE_ID = l.phase_id inner join resi_project_options rpo on l.option_id = rpo.OPTIONS_ID and rpo.OPTION_CATEGORY = 'Logical' inner join resi_project_options rpo1 on rpo.PROJECT_ID = rpo1.PROJECT_ID and rpo.OPTION_TYPE = rpo1.OPTION_TYPE and rpo.BEDROOMS = rpo1.BEDROOMS and rpo1.OPTION_CATEGORY = 'Actual' inner join project_supplies ps on l.id = ps.listing_id and ps.version = 'Website' left join d_inventory_prices_tmp dip on rpp.phase_id = dip.phase_id and rpo.OPTION_TYPE = dip.unit_type and (rpo.BEDROOMS = dip.bedrooms or rpo.BEDROOMS is null) and date_format(rpp.COMPLETION_DATE, '%Y-%m-01') = dip.effective_month where rp.version = 'Website' and rpp.COMPLETION_DATE != 0 and dip.id is null group by ps.id";
    $aData = DInventoryPriceTmp::find_by_sql($sql);
    $aData = removeInvalidPhaseData($aData, $aProjectPhaseCount);
    $i = 0;
    foreach ($aData as $data) {
        $mysqlArray = $data->to_array();
        if($mysqlArray['completion_date'] != INVALID_DATE){
            $mysqlArray['created_at'] = 'NOW()';
            $mysqlArray['completion_date'] = substr($mysqlArray['completion_date'], 0, 10);
            $mysqlArray['unique_key'] = $mysqlArray['project_id']."/".$mysqlArray['phase_id']."/".$mysqlArray['unit_type']."/".$mysqlArray['bedrooms']."/".$mysqlArray['completion_date'];
            if($mysqlArray['launch_date'] == INVALID_DATE)unset ($mysqlArray['launch_date']);
            $mysqlArray['effective_month'] = $mysqlArray['completion_date'];
            $mysqlArray['year'] = firstDayOf('year', $mysqlArray['effective_month']);
            $mysqlArray['half_year'] = firstDayOf('half_year', $mysqlArray['effective_month']);
            $mysqlArray['quarter'] = firstDayOf('quarter', $mysqlArray['effective_month']);
            
            DInventoryPriceTmp::create($mysqlArray)->save();
            $i++;
        }
    }
    $logger->info("Inserted $i missing completion date entries");
}
?>