<?php
$currentDir = dirname(__FILE__);

define('MIN_B2B_DATE', '2013-03-01');
define('MAX_B2B_DATE', '2014-12-01');
define('B2B_DOCUMENT_TYPE', 'B2B');

require_once($currentDir . '/../Apache/Solr/Service.php');
require_once ($currentDir . '/../log4php/Logger.php');
require_once ($currentDir . '/../modelsConfig.php');
require_once ($currentDir . '/../solrConfig.php');
require_once ($currentDir . '/../common/solrFunctions.php');

ini_set('display_errors', '1');
error_reporting(E_ALL);

ini_set('memory_limit', '-1');
set_time_limit(0);
define("INVALID_DATE", "0000-00-00 00:00:00");

Logger::configure( dirname(__FILE__) . '/../log4php.xml');
$logger = Logger::getLogger("main");

$solr = new Apache_Solr_Service(SOLR_SERVER_HOSTNAME, SOLR_SERVER_PORT, '/solr/');
$solr->deleteByQuery("*:*");
$solr->commit();
$logger->info("Deleted All documents of document type ");

$aProjectPhaseCount = ResiProjectPhase::getWebsitePhaseCountForProjects();
$aAllProjects = ResiProject::find_by_sql('select PROJECT_ID from resi_project where version = "Website"');

$i = 0;
while($i< count($aAllProjects)){
    $aPid = array();
    for($j=1; $j<=500 && $i< count($aAllProjects); $j++){
        $aPid[] = $aAllProjects[$i]->project_id;
        $i=$i+1;
    }
    
    $aAllInventory = ProjectAvailability::getInventoryForIndexing($aPid);
    $aAllInventory = removeInvalidPhaseData($aAllInventory, $aProjectPhaseCount);
    $aAllPrice = ListingPrices::getPriceForIndexing($aPid);
    $aAllPrice = removeInvalidPhaseData($aAllPrice, $aProjectPhaseCount);
    
    
    fillIntermediateMonths($aAllInventory);
    fillIntermediateMonths($aAllPrice);
    indexPriceInventoryData($aAllInventory, $aAllPrice);
    
    $aSolrDocument = getSolrDocuments($aAllInventory, $aAllPrice);
    $solr->addDocuments($aSolrDocument);
    $solr->optimize();
    $solr->commit();

    echo count($aAllInventory). " == ". count($aAllPrice) ."\n";
}

function getSolrDocuments($aAllInventory, $aAllPrice){
    $aKey = array_unique(array_merge(array_keys($aAllInventory), array_keys($aAllPrice)));
    $result = array();
    foreach ($aKey as $key) {
        $solrDocument = new Apache_Solr_Document();
        
        $solrDocument->addField('id', B2B_DOCUMENT_TYPE.$key);
        $solrDocument->addField('DOCUMENT_TYPE', B2B_DOCUMENT_TYPE);
        $solrDocument->addField('PROJECT_ID', isset($aAllInventory[$key])? $aAllInventory[$key]->project_id : $aAllPrice[$key]->project_id);
        $solrDocument->addField('PROJECT_NAME', isset($aAllInventory[$key])? $aAllInventory[$key]->project_name : $aAllPrice[$key]->project_name);
        $solrDocument->addField('PHASE_ID', isset($aAllInventory[$key])? $aAllInventory[$key]->phase_id : $aAllPrice[$key]->phase_id);
        $solrDocument->addField('PHASE_NAME', isset($aAllInventory[$key])? $aAllInventory[$key]->phase_name : $aAllPrice[$key]->phase_name);
        $solrDocument->addField('PHASE_TYPE', isset($aAllInventory[$key])? $aAllInventory[$key]->phase_type : $aAllPrice[$key]->phase_type);
        $solrDocument->addField('LOCALITY_ID', isset($aAllInventory[$key])? $aAllInventory[$key]->locality_id : $aAllPrice[$key]->locality_id);
        $solrDocument->addField('LOCALITY_NAME', isset($aAllInventory[$key])? $aAllInventory[$key]->locality_name : $aAllPrice[$key]->locality_name);
        $solrDocument->addField('SUBURB_ID', isset($aAllInventory[$key])? $aAllInventory[$key]->suburb_id : $aAllPrice[$key]->suburb_id);
        $solrDocument->addField('SUBURB_NAME', isset($aAllInventory[$key])? $aAllInventory[$key]->suburb_name : $aAllPrice[$key]->suburb_name);
        $solrDocument->addField('CITY_ID', isset($aAllInventory[$key])? $aAllInventory[$key]->city_id : $aAllPrice[$key]->city_id);
        $solrDocument->addField('CITY_NAME', isset($aAllInventory[$key])? $aAllInventory[$key]->city_name : $aAllPrice[$key]->city_name);
        $solrDocument->addField('BUILDER_ID', isset($aAllInventory[$key])? $aAllInventory[$key]->builder_id : $aAllPrice[$key]->builder_id);
        $solrDocument->addField('BUILDER_NAME', isset($aAllInventory[$key])? $aAllInventory[$key]->builder_name : $aAllPrice[$key]->builder_name);
        
        $effectiveMonth = substr(isset($aAllInventory[$key])? $aAllInventory[$key]->effective_month : $aAllPrice[$key]->effective_month, 0, 10);
        $solrDocument->addField('EFFECTIVE_MONTH', getDateInSolrFormat(strtotime($effectiveMonth)));        $solrDocument->addField('COMPLETION_DATE', getDateInSolrFormat(strtotime(isset($aAllInventory[$key])? $aAllInventory[$key]->completion_date : $aAllPrice[$key]->completion_date)));        $solrDocument->addField('LAUNCH_DATE', getDateInSolrFormat(strtotime(isset($aAllInventory[$key])? $aAllInventory[$key]->launch_date : $aAllPrice[$key]->launch_date)));        
        $solrDocument->addField('PRE_LAUNCH_DATE', getDateInSolrFormat(strtotime(isset($aAllInventory[$key])? $aAllInventory[$key]->pre_launch_date : $aAllPrice[$key]->pre_launch_date)));
        $bedrooms = isset($aAllInventory[$key])? $aAllInventory[$key]->bedrooms : $aAllPrice[$key]->bedrooms;
        if(is_int($bedrooms))$solrDocument->addField('BEDROOMS', $bedrooms);
        $solrDocument->addField('UNIT_TYPE', isset($aAllInventory[$key])? $aAllInventory[$key]->unit_type : $aAllPrice[$key]->unit_type);
        
        if(isset($aAllPrice[$key]))$solrDocument->addField('AVERAGE_PRICE_PER_UNIT_AREA', $aAllPrice[$key]->average_price_per_unit_area);
        if(isset($aAllInventory[$key]))$solrDocument->addField('SUPPLY', $aAllInventory[$key]->supply);
        if(isset($aAllInventory[$key]))$solrDocument->addField('LAUNCHED_UNIT', $aAllInventory[$key]->launched);
        if(isset($aAllInventory[$key]))$solrDocument->addField('INVENTORY', $aAllInventory[$key]->inventory);
        if(isset($aAllPrice[$key]))$solrDocument->addField('AVERAGE_SIZE', $aAllPrice[$key]->average_size);
        if(isset($aAllPrice[$key])){
            $aSize = explode (',', $aAllPrice[$key]->size);
            foreach ($aSize as $size) {
                $solrDocument->addField('SIZE', $size);
            }
        }
        if(isset($aAllPrice[$key]))$solrDocument->addField('AVERAGE_TOTAL_PRICE', $aAllPrice[$key]->average_total_price);
        
        
        array_push($result, $solrDocument);
    }
    return $result;
}

function removeInvalidPhaseData($aData, $aProjectPhaseCount){
    $result = array();
    foreach ($aData as $value) {
        if($value->phase_type == 'Actual' || $aProjectPhaseCount[$value->project_id] == 1)$result[] = $value;
    }
    return $result;
}

function fillIntermediateMonths(&$aData){
    $aNewData = array();
    for($i=0; $i<count($aData); $i++){
        $currData = $aData[$i];
        array_push($aNewData, clone $currData);
        $fillTill = MAX_B2B_DATE;
        if(isset($aData[$i+1])){
            $currIndex = $currData->project_id . $currData->phase_id . $currData->unit_type . $currData->bedrooms;
            $nextIndex = $aData[$i+1]->project_id . $aData[$i+1]->phase_id . $aData[$i+1]->unit_type . $aData[$i+1]->bedrooms;
            if($currIndex === $nextIndex)$fillTill = getMonthShiftedDate ($aData[$i+1]->effective_month, -1);
        }
        if($aData[$i]->effective_month == $fillTill)continue;
        
        while(substr($currData->effective_month, 0, 10)<$fillTill){
            $nextMonth = getMonthShiftedDate($currData->effective_month, 1);
            $currData->unique_key = str_replace(substr($currData->effective_month, 0, 10), $nextMonth, $currData->unique_key);
            $currData->effective_month = $nextMonth;
            array_push($aNewData, clone $currData);
        }
    }
    $aData = $aNewData;
}

function indexPriceInventoryData(&$aAllInventory, &$aAllPrice){
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
}

function getMonthShiftedDate($date, $shift){
    $date = substr($date, 0, 10);
    return date("Y-m-d", strtotime("$date $shift month"));
}
?>