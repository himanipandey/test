<?php
$currentDir = dirname(__FILE__);

define('MIN_B2B_DATE', '2013-03-01');
define('MAX_B2B_DATE', '2013-12-01');

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
$logger->info("Deleted All documents");

$aProjectPhaseCount = ResiProjectPhase::getWebsitePhaseCountForProjects();
$aAllProjects = ResiProject::find_by_sql('select PROJECT_ID from resi_project where version = "Website"');

$i = 0;
while($i< count($aAllProjects)-1){
    $aPid = array();
    for($j=1; $j<=500 && $i< count($aAllProjects); $j++){
        $aPid[] = $aAllProjects[$i]->project_id;
        $i=$i+1;
    }
    
    $aAllInventory = ProjectAvailability::getInventoryForIndexing($aPid);
    $aAllInventory = removeInvalidPhaseData($aAllInventory, $aProjectPhaseCount);
    $aAllPrice = ListingPrices::getPriceForIndexing($aPid);
    $aAllPrice = removeInvalidPhaseData($aAllPrice, $aProjectPhaseCount);
    
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
        $solrDocument->addField('id', 'B2B/'.$key);
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
        $aNewAllPrice[$key] = $value->unique_key;
    }
    $aAllPrice = $aNewAllPrice;
}
?>