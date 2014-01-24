<?php
$currentDir = dirname(__FILE__);

define('MIN_B2B_DATE', '2013-03-01');
define('MAX_B2B_DATE', '2020-01-01');
define('B2B_DOCUMENT_TYPE', 'B2B');

date_default_timezone_set ('UTC');

require_once($currentDir . '/../Apache/Solr/Service.php');
require_once ($currentDir . '/../log4php/Logger.php');
require_once ($currentDir . '/../modelsConfig.php');
require_once ($currentDir . '/../solrConfig.php');
require_once ($currentDir . '/../common/solrFunctions.php');
require_once ($currentDir . '/../vendor/autoload.php');

$ecClient = new Elasticsearch\Client();

ini_set('display_errors', '1');
error_reporting(E_ALL);

ini_set('memory_limit', '-1');
set_time_limit(0);
define("INVALID_DATE", "0000-00-00");

Logger::configure( dirname(__FILE__) . '/../log4php.xml');
$logger = Logger::getLogger("main");

$logger->info("\n\n\n\nStarting Indexing B2B Documents");
//$solr = new Apache_Solr_Service(SOLR_SERVER_HOSTNAME, SOLR_SERVER_PORT, '/solr/');
//$solr->deleteByQuery("DOCUMENT_TYPE:" . B2B_DOCUMENT_TYPE);
//$solr->commit();
$logger->info("Deleted All documents of document type ".B2B_DOCUMENT_TYPE);

$aProjectPhaseCount = ResiProjectPhase::getWebsitePhaseCountForProjects();
$aAllProjects = ResiProject::find_by_sql('select PROJECT_ID from resi_project where version = "Website"');
$logger->info("Project And Phase Details Retrieved");

DInventoryPrice::delete_all();

$i = 0;
while($i< count($aAllProjects)){
    $aPid = array();
    for($j=1; $j<=500 && $i< count($aAllProjects); $j++){
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
    
    $aSolrDocument = getSolrDocuments($aAllInventory, $aAllPrice);
    //$solr->addDocuments($aSolrDocument);
    //$solr->optimize();
    $logger->info(count($aSolrDocument) . " documents pushed into solr");
    $logger->info("Indexing complete for $i projects");
}


function getSolrDocuments($aAllInventory, $aAllPrice){
    global $logger;
    global $ecClient;
    $aKey = array_unique(array_merge(array_keys($aAllInventory), array_keys($aAllPrice)));
    $result = array();
    
    $params = array();
    $params['index'] = 'index';
    $params['type']  = 'type';
    
    foreach ($aKey as $key) {
//        $solrDocument = new Apache_Solr_Document();
//        
//        $solrDocument->addField('id', B2B_DOCUMENT_TYPE. "/" . $key);
//        $solrDocument->addField('DOCUMENT_TYPE', B2B_DOCUMENT_TYPE);
//        
//        $arrayToPick = isset($aAllInventory[$key])? $aAllInventory : $aAllPrice;
//        
//        
//        //Code used for storing the documents into solr
//        
//        //hard coded values as of now
//        $solrDocument->addField('COUNTRY_ID', 1);
//        $solrDocument->addField('COUNTRY_NAME', 'India');
//        
//        $solrDocument->addField('PROJECT_ID', $arrayToPick[$key]->project_id);
//        $solrDocument->addField('PROJECT_NAME',$arrayToPick[$key]->project_name);
//        $solrDocument->addField('PHASE_ID', $arrayToPick[$key]->phase_id);
//        $solrDocument->addField('PHASE_NAME', $arrayToPick[$key]->phase_name);
//        $solrDocument->addField('PHASE_TYPE', $arrayToPick[$key]->phase_type);
//        $solrDocument->addField('LOCALITY_ID', $arrayToPick[$key]->locality_id);
//        $solrDocument->addField('LOCALITY_NAME', $arrayToPick[$key]->locality_name);
//        $solrDocument->addField('SUBURB_ID', $arrayToPick[$key]->suburb_id);
//        $solrDocument->addField('SUBURB_NAME', $arrayToPick[$key]->suburb_name);
//        $solrDocument->addField('CITY_ID', $arrayToPick[$key]->city_id);
//        $solrDocument->addField('CITY_NAME', $arrayToPick[$key]->city_name);
//        $solrDocument->addField('BUILDER_ID', $arrayToPick[$key]->builder_id);
//        $solrDocument->addField('BUILDER_NAME', $arrayToPick[$key]->builder_name);
//        $effectiveMonth = substr($arrayToPick[$key]->effective_month, 0, 10);
//        $solrDocument->addField('EFFECTIVE_MONTH', getDateInSolrFormat(strtotime($effectiveMonth)));
//        if($arrayToPick[$key]->completion_date != INVALID_DATE)$solrDocument->addField('PROMISED_COMPLETION_DATE', getDateInSolrFormat(strtotime($arrayToPick[$key]->completion_date)));
//        if($arrayToPick[$key]->launch_date != INVALID_DATE)$solrDocument->addField('LAUNCH_DATE', getDateInSolrFormat(strtotime($arrayToPick[$key]->launch_date)));
//        $bedrooms = $arrayToPick[$key]->bedrooms;
//        if(is_int($bedrooms))$solrDocument->addField('BEDROOMS', $bedrooms);
//        $solrDocument->addField('UNIT_TYPE', isset($arrayToPick[$key])? $arrayToPick[$key]->unit_type : $aAllPrice[$key]->unit_type);
//        
//        if(isset($aAllPrice[$key])){
//            $solrDocument->addField('AVERAGE_PRICE_PER_UNIT_AREA', $aAllPrice[$key]->average_price_per_unit_area);
//            $solrDocument->addField('AVERAGE_SIZE', $aAllPrice[$key]->average_size);
//            $aSize = explode (',', $aAllPrice[$key]->size);
//            foreach ($aSize as $size) {
//                $solrDocument->addField('ALL_SIZE', $size);
//            }
//            $solrDocument->addField('AVERAGE_TOTAL_PRICE', $aAllPrice[$key]->average_total_price);
//        }
//        if(isset($aAllInventory[$key])){
//            $solrDocument->addField('SUPPLY', $aAllInventory[$key]->supply);
//            $solrDocument->addField('LAUNCHED_UNIT', $aAllInventory[$key]->launched);
//            $solrDocument->addField('INVENTORY', $aAllInventory[$key]->inventory);
//        }
//        
//        array_push($result, $solrDocument);
        
        
        //Code used for storing the documents into elasticsearch
        
        $ecArray = array();
        $ecArray['id'] = B2B_DOCUMENT_TYPE. "/" . $key;
        //$params['id'] = str_replace("/", "_", $ecArray['id']);
        //echo $ecArray['id']."\n";
        
        $arrayToPick = isset($aAllInventory[$key])? $aAllInventory : $aAllPrice;
        
        //hard coded values as of now
        $ecArray['COUNTRY_ID'] = 1;
        $ecArray['COUNTRY_NAME'] = 'India';
        
        $ecArray['PROJECT_ID'] = $arrayToPick[$key]->project_id;
        $ecArray['PROJECT_NAME'] = $arrayToPick[$key]->project_name;
        $ecArray['PHASE_ID'] = $arrayToPick[$key]->phase_id;
        $ecArray['PHASE_NAME'] = $arrayToPick[$key]->phase_name;
        $ecArray['PHASE_TYPE'] =  $arrayToPick[$key]->phase_type;
        $ecArray['LOCALITY_ID'] = intval($arrayToPick[$key]->locality_id);
        $ecArray['LOCALITY_NAME'] = $arrayToPick[$key]->locality_name;
        $ecArray['SUBURB_ID'] = $arrayToPick[$key]->suburb_id;
        $ecArray['SUBURB_NAME'] = $arrayToPick[$key]->suburb_name;
        $ecArray['CITY_ID'] = $arrayToPick[$key]->city_id;
        $ecArray['CITY_NAME'] = $arrayToPick[$key]->city_name;
        $ecArray['BUILDER_ID'] = $arrayToPick[$key]->builder_id;
        $ecArray['BUILDER_NAME'] = $arrayToPick[$key]->builder_name;
        $effectiveMonth = substr($arrayToPick[$key]->effective_month, 0, 10);
        $ecArray['EFFECTIVE_MONTH'] = getDateInSolrFormat(strtotime($effectiveMonth));
        if($arrayToPick[$key]->completion_date != INVALID_DATE)$ecArray['PROMISED_COMPLETION_DATE']= getDateInSolrFormat(strtotime($arrayToPick[$key]->completion_date));
        if($arrayToPick[$key]->launch_date != INVALID_DATE)$ecArray['LAUNCH_DATE'] = getDateInSolrFormat(strtotime($arrayToPick[$key]->launch_date));
        $bedrooms = $arrayToPick[$key]->bedrooms;
        if(is_int($bedrooms))$ecArray['BEDROOMS'] = $bedrooms;
        $ecArray['UNIT_TYPE'] = isset($arrayToPick[$key])? $arrayToPick[$key]->unit_type : $aAllPrice[$key]->unit_type;
        
        if(isset($aAllPrice[$key])){
            $ecArray['AVERAGE_PRICE_PER_UNIT_AREA'] = $aAllPrice[$key]->average_price_per_unit_area;
            $ecArray['AVERAGE_SIZE'] = $aAllPrice[$key]->average_size;
            $aSize = explode (',', $aAllPrice[$key]->size);
            foreach ($aSize as $size) {
                $ecArray['ALL_SIZE'] = $size;
            }
            $ecArray['AVERAGE_TOTAL_PRICE'] = $aAllPrice[$key]->average_total_price;
        }
        if(isset($aAllInventory[$key])){
            $ecArray['SUPPLY'] = $aAllInventory[$key]->supply;
            $ecArray['LAUNCHED_UNIT'] = $aAllInventory[$key]->launched;
            $ecArray['INVENTORY'] = $aAllInventory[$key]->inventory;
        }
        $params['body'] = $ecArray;
        //$ecClient->index($params);
        
        
        
        //Code used for storing the documents into mysql
        
        $ecArray['unique_key'] = $ecArray['id'];
        unset($ecArray['id']);
        unset($ecArray['SUBURB_ID']);
        unset($ecArray['SUBURB_NAME']);
        $mysqlArray = array();
        foreach ($ecArray as $k=>$value) {
            $mysqlArray[strtolower($k)] = $value;
        }
        
        $x = DInventoryPrice::create($mysqlArray);
        $x->save();
    }
    $logger->info("Solr document set ready");
    return $result;
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
?>