<?php
/*
 * This script is to populate the builder_score in the resi_builder table which
 * is used to compute the project livability as one of the factor. Builder score
 * factor is computed using various factors like construction status sqft area sold, unsold,
 * builder age and rate of unit sold by builder. 
 */

error_reporting(E_ALL);

$docroot = dirname(__FILE__) . "/../";
$currentDir = dirname(__FILE__);
require_once $docroot.'dbConfig.php';
require_once $docroot.'/cron/cronConfig.php';
require_once $docroot.'/cron/errorHandler.php';
require_once ($currentDir . '/../includes/send_mail_amazon.php');
require_once ($currentDir . '/../log4php/Logger.php');
require_once ($currentDir . '/../cron/cronFunctions.php');

Logger::configure(dirname(__FILE__) . '/../log4php.xml');
$logger = Logger::getLogger("main");

define("OVERHANG_INVERSE", "OVERHANG_INVERSE");
define("COMPLETION_DELAY", "COMPLETION_DELAY");
define("COMPLETED_AREA", "COMPLETED_AREA");
define("NON_COMPLETED_AREA", "NON_COMPLETED_AREA");
define("AGE_IN_DAY", "AGE_IN_DAY");
define("LISTED", "LISTED");

define("SCORE_SUFFIX", "_SCORE");

$weights = array(
    COMPLETED_AREA => 0.2,
    NON_COMPLETED_AREA => 0.2,
    COMPLETION_DELAY => 0.2,
    OVERHANG_INVERSE => 0.2,
    AGE_IN_DAY => 0.05,
    LISTED => 0.15
);
#print_r($weights);echo $weights[COMPLETED_AREA];die;
define("MIN_BUILDER_SCORE", 0.5);
define("MIN_MAX_LIVABILITY_SCORE", 0.95);
    
$builder_api_respose = file_get_contents(PROPTIGER_URL . '/data/v1/entity/builder?selector={"fields":["id","establishedDate","isBuilderListed"],"paging":{"start":0,"rows":20000}}');
$builder_api_respose_json = json_decode($builder_api_respose, true);
$builder_api_respose_data = groupOnArrayKey($builder_api_respose_json['data'], "id");

$trend_api_response_status_grouped_array = getStatusGroupedBuilderTrend();

$trend_api_response_array = getBuilderTrend();

$min_builder_count = ResiBuilder::count() * 0.7;

if(!(count($builder_api_respose_data) > $min_builder_count && count($trend_api_response_status_grouped_array) > $min_builder_count && count($trend_api_response_array) > $min_builder_count)){
  trigger_error ("Not enough builders found in api response", E_USER_ERROR );
}

$params_array = array();
foreach($trend_api_response_array as $builder_id => $trend_api_response_builder){
    $trend_extra_attributes = $trend_api_response_builder[0]["extraAttributes"];
    
    $params_array[OVERHANG_INVERSE][$builder_id] = 0;
    $total_inventory = $trend_extra_attributes['sumInventory'];
    $rate_of_sale = $trend_extra_attributes['sumRateOfSale'];
    if(isset($total_inventory) && isset($rate_of_sale) && $total_inventory > 20){
        $params_array[OVERHANG_INVERSE][$builder_id] = $rate_of_sale / $total_inventory;
    }
    
    $status_grouped_response = $trend_api_response_status_grouped_array[$builder_id];
    $params_array[COMPLETED_AREA][$builder_id] = 0;
    $params_array[NON_COMPLETED_AREA][$builder_id] = 0;
    
    $wavg_completed_size;
    $completed_launched_units;
    $wavg_non_completed_size;
    $non_completed_launched_units;
    
    foreach ($status_grouped_response as $value) {
        $status_extra_attributes = $value["extraAttributes"];
        if($value["constructionStatus"] === "Completed"){
            $wavg_completed_size = $status_extra_attributes["wavgSizeOnLtdLaunchedUnit"];
            $completed_launched_units = $status_extra_attributes["sumLtdLaunchedUnit"];
        }
        else{
            $wavg_non_completed_size = $status_extra_attributes["wavgSizeOnLtdLaunchedUnit"];
            $non_completed_launched_units = $status_extra_attributes["sumLtdLaunchedUnit"];
        }
    }
    if(isset($wavg_completed_size) && isset($completed_launched_units)){
        $params_array[COMPLETED_AREA][$builder_id] = $wavg_completed_size * $completed_launched_units;
    }
    
    if(isset($wavg_non_completed_size) && isset($non_completed_launched_units)){
        $params_array[NON_COMPLETED_AREA][$builder_id] = $wavg_non_completed_size * $non_completed_launched_units;
    }
    
    $wavg_delay = $trend_extra_attributes["wavgCompletionDelayInMonthOnLtdLaunchedUnit"];
    $launch_units = $trend_extra_attributes["sumLtdLaunchedUnit"];
    if(isset($wavg_delay) && isset($launch_units) && $non_completed_launched_units > 0){
        $params_array[COMPLETION_DELAY][$builder_id] = $wavg_delay * $launch_units / $non_completed_launched_units;
    }
    
    $params_array[AGE_IN_DAY][$builder_id] = 0;
    
    $established_date = $builder_api_respose_data[$builder_id][0]["establishedDate"];
    //echo "$established_date\n";
    if(isset($established_date)){
        $params_array[AGE_IN_DAY][$builder_id] = (time() - $established_date/1000)/86400;
    }
    
    $params_array[LISTED][$builder_id] = 0;
    $listed = $builder_api_respose_data[$builder_id][0]["isBuilderListed"];
    if(isset($listed) && $listed){
        $params_array[LISTED][$builder_id] = 1;
    }
}

foreach ($params_array as $key => $value) {
    $sorted = array_values($value);
    sort($sorted);
    $max = $sorted[round(count($sorted)*0.998)];
    if($max === 0){
        $max = 1;
    }
    
    foreach ($value as $builder_id => $param_value) {
        $score = 0;
        if($key === COMPLETION_DELAY){
            $score = 1 - $param_value / $max;
        }
        else{
            $score = $param_value / $max;
        }
        if($score < 0){
            $score = 0;
        }
        else if($score > 1){
            $score = 1;
        }
        $params_array[$key . SCORE_SUFFIX][$builder_id] = $score;
    }
}

foreach ($trend_api_response_array as $builder_id => $value) {
    $score = $params_array[AGE_IN_DAY . SCORE_SUFFIX][$builder_id] * $weights[AGE_IN_DAY] + $params_array[OVERHANG_INVERSE . SCORE_SUFFIX][$builder_id] * $weights[OVERHANG_INVERSE] + $params_array[COMPLETION_DELAY . SCORE_SUFFIX][$builder_id] * $weights[COMPLETION_DELAY] + $params_array[COMPLETED_AREA . SCORE_SUFFIX][$builder_id] * $weights[COMPLETED_AREA] + $params_array[NON_COMPLETED_AREA . SCORE_SUFFIX][$builder_id] * $weights[NON_COMPLETED_AREA] + $params_array[LISTED . SCORE_SUFFIX][$builder_id] * $weights[LISTED];
    ResiBuilder::updateBuiderScore($builder_id, $score);
    # $sql = "update cms.resi_builder set AGE_IN_DAY = " . $params_array[AGE_IN_DAY][$builder_id] . ", AGE_IN_DAY_SCORE = " . $params_array[AGE_IN_DAY_SCORE][$builder_id] . ", OVERHANG_INVERSE = " . $params_array[OVERHANG_INVERSE][$builder_id] . ", OVERHANG_INVERSE_FLOAT = " . $params_array[OVERHANG_INVERSE_SCORE][$builder_id] . ", COMPLETION_DELAY = " . $params_array[COMPLETION_DELAY][$builder_id] . ", COMPLETION_DELAY_SCORE = " . $params_array[COMPLETION_DELAY_SCORE][$builder_id] . ", COMPLETED_AREA = " . $params_array[COMPLETED_AREA][$builder_id] . ", COMPLETED_AREA_SCORE = " . $params_array[COMPLETED_AREA_SCORE][$builder_id] . ", NON_COMPLETED_AREA = " . $params_array[NON_COMPLETED_AREA][$builder_id] . ", NON_COMPLETED_AREA_SCORE = " . $params_array[NON_COMPLETED_AREA_SCORE][$builder_id] . ", LISTED_SCORE = " . $params_array[LISTED_SCORE][$builder_id] . " where builder_id = $builder_id";
    # mysql_query($sql);
}

function getBuilderTrend(){
    $url = PROPTIGER_URL . "/data/v1/trend-list/current?filters=unitType!=Plot&fields=builderName,wavgCompletionDelayInMonthOnLtdLaunchedUnit,sumLtdLaunchedUnit,sumRateOfSale,sumInventory&group=builderId&order=builderId";
    $response = getAllPaginatedResponse($url);
    return groupOnArrayKey($response, "builderId");
}

function getStatusGroupedBuilderTrend(){
    $url = PROPTIGER_URL . "/data/v1/trend-list/current?filters=unitType!=Plot&fields=builderName,sumLtdLaunchedUnit,wavgSizeOnLtdLaunchedUnit&group=builderId,constructionStatus&order=builderId,constructionStatus";
    $response = getAllPaginatedResponse($url);
    return groupOnArrayKey($response, "builderId");
}

function getAllPaginatedResponse($url){
    $page_size = 1000;
    $count_response = json_decode(file_get_contents($url . "&start=0&rows=1"), TRUE);
    $count = $count_response["totalCount"];
    $final_array = array();
    $start = 0;
    
    while($start <= $count){
        $paginated_response = json_decode(file_get_contents($url . "&start=$start&rows=$page_size"), TRUE);
        $data = $paginated_response["data"];
        
        $final_array = array_merge($final_array, $data);
        $start = $start + $page_size;
    }
    return $final_array;
}
?>
