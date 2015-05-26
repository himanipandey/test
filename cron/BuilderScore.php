<?php
/*
 * This script is to populate the builder_score in the resi_builder table which
 * is used to compute the project livability as one of the factor. Builder score
 * factor is computed using various factors like construction status sqft area sold, unsold,
 * builder age and rate of unit sold by builder. 
 */
$docroot = dirname(__FILE__) . "/../";
$currentDir = dirname(__FILE__);
require_once $docroot.'dbConfig.php';
require_once ($currentDir . '/../includes/send_mail_amazon.php');
require_once ($currentDir . '/../log4php/Logger.php');
require_once ($currentDir . '/../cron/cronFunctions.php');


define('B2B_SUCCESS_EMAIL_RECIPIENT', 'azitabh.ajit@proptiger.com');
/*
|  1 | UnderConstruction | Under Construction |
|  2 | Cancelled         | Cancelled          |
|  3 | Completed         | Completed          |
|  5 | OnHold            | On Hold            |
|  6 | NotLaunched       | Not Launched       |
|  7 | Launch            | Launch             |
|  8 | PreLaunch         | Pre Launch 	      |
*/

Logger::configure(dirname(__FILE__) . '/../log4php.xml');
$logger = Logger::getLogger("main");

$__status_map = array('Completed' => 'SQRF_AREA_COMPLETED', 'Under Construction And Pre Launch' => 'SQRF_AREA_UNDER_CONSTRUCTION_And_Pre_Launch', 
		      'ESTABLISED_TIME' => 'ESTABLISED_TIME',
		      'BUILDER_OVERALL_DELAY' => 'BUILDER_OVERALL_DELAY', 'RATE_OF_SALE' => 'RATE_OF_SALE');
		      
$__param_multiplication_factor = array( 'SQRF_AREA_COMPLETED' => 0.175,
					'SQRF_AREA_UNDER_CONSTRUCTION_And_Pre_Launch' => 0.175,
					'BUILDER_OVERALL_DELAY' => 0.15,'RATE_OF_SALE' => 0.20,
					'ESTABLISED_TIME' => 0.15);
$__min_livability_score = 0.5;
//$__builder_api_respose = file_get_contents('https://localhost:8080/data/v1/entity/builder?selector={"fields":["id","establishedDate"],"paging":{"start":0,"rows":10000}}');
$__builder_api_respose = file_get_contents('https://qa.proptiger-ws.com/data/v1/entity/builder?selector={"fields":["id","establishedDated"],"paging":{"start":0,"rows":10000}}');
if(strlen($__builder_api_respose) > 0){
	$logger->info("builder api response successful");
}
else{
		$logger->error("builder api unable to fetch data");
		sendRawEmailFromAmazon(B2B_SUCCESS_EMAIL_RECIPIENT, '', '', 'builder api in Builder_score failed to fetch data', 'builder api in BuilderScore.php in cms codeBase failed to fetch data', '', '',   array(B2B_SUCCESS_EMAIL_RECIPIENT));
		exit(1);
}


$__builder_api_respose_json = json_decode($__builder_api_respose, true);
$__establised_date_array = $__builder_api_respose_json['data'];
$__builder_counts = count($__establised_date_array);
$__establised_date_param = array();
$__max_builder_age = 0; 
$__totla_ages = 0;
$__builder_count_with_established_data = 1;
for($__idx = 0; $__idx < $__builder_counts; $__idx++){
    $__builder_id = $__establised_date_array[$__idx]['id'];
    
    if (isset($__establised_date_array[$__idx]['establishedDate'])) {
	$__builder_age = (time() - $__establised_date_array[$__idx]['establishedDate']/1000)/(30*24*3600);
	$__establised_date_param[$__builder_id] = $__builder_age;
	$__totla_ages += $__builder_age;
	$__builder_count_with_established_data++;
    }
}
$__avg_builder_age = $__totla_ages/$__builder_count_with_established_data;
//$__current_trend_api_res = file_get_contents('https://localhost:8080/data/v1/trend/current?fields=sumRateOfSale,sumInventory&group=builderId&rows=30000');
$__current_trend_api_res = file_get_contents('http://qa.proptiger-ws.com/data/v1/trend/current?fields=sumRateOfSale,sumInventory&group=builderId&rows=30000');
if(strlen($__current_trend_api_res) > 0){
	$logger->info("current trend api response successful");
}
else{
		$logger->error("trend api unable to fetch data");
		sendRawEmailFromAmazon(B2B_SUCCESS_EMAIL_RECIPIENT, '', '', 'trend api in Builder_score failed to fetch data', 'trend api in BuilderScore.php in cms codeBase failed to fetch data', '', '',   array(B2B_SUCCESS_EMAIL_RECIPIENT));
		exit(1);
}

$__current_trend_api_res_json = json_decode($__current_trend_api_res, true);
$__rate_of_sale_array = $__current_trend_api_res_json['data'];
$__rate_of_sale_params = array();
$__builder_counts = count($__rate_of_sale_array);
foreach($__rate_of_sale_array as $__builder_id => $__rate_of_sale){
      $__rate_of_sale_params[$__builder_id]['SUM_RATE_OF_SALE'] = $__rate_of_sale[0]['extraAttributes']['sumRateOfSale'];
      $__rate_of_sale_params[$__builder_id]['SUM_INVENTORY'] = $__rate_of_sale[0]['extraAttributes']['sumInventory'];
}
//$__trend_api_response = file_get_contents('https://localhost:8080/data/v1/trend?fields=builderName,wavgCompletionDelayInMonthOnLaunchedUnit,wavgCompletionDelayInMonthOnInventory,wavgSizeOnUnitsSold,sumInventory,sumUnitsSold,sumLaunchedUnit,wavgSizeOnLaunchedUnit&group=builderId,constructionStatus&rows=30000');
$__trend_api_response = file_get_contents('http://qa.proptiger-ws.com/data/v1/trend?fields=builderName,wavgCompletionDelayInMonthOnLaunchedUnit,wavgCompletionDelayInMonthOnInventory,wavgSizeOnUnitsSold,sumInventory,sumUnitsSold,sumLaunchedUnit,wavgSizeOnLaunchedUnit&group=builderId,constructionStatus&rows=30000');
if(strlen($__trend_api_response)){
	$logger->info("trend api response successful");
}
else{
		$logger->error("trend api response unable to fetch data");
		sendRawEmailFromAmazon(B2B_SUCCESS_EMAIL_RECIPIENT, '', '', 'trend api response in Builder_score failed to fetch data', 'trend api response in BuilderScore.php in cms codeBase failed to fetch data', '', '',   array(B2B_SUCCESS_EMAIL_RECIPIENT));
		exit(1);
}


$__trend_api_response_json = json_decode($__trend_api_response, true);
$__trend_data_array = $__trend_api_response_json['data'];
$__params_array = array();
$__max_overall_area = 0;
$__max_val = array();
foreach($__trend_data_array as $__builder_id => $__status_array){
	//print_r($__status_array);
    $__total_size_sum = 0;
    $__params = array();
    $__sqrf_sold_area = 0;
    $__total_delay_month = 0;
    $__total_delay_unit_per_builder = 0;
    $_Under_Construction_And_Pre_Launch_Flag = TRUE;
    foreach($__status_array as $__status => $__value) {
    	$__sold_count = $__value[0]['extraAttributes']['sumUnitsSold'];
		$__sold_avg_area = $__value[0]['extraAttributes']['wavgSizeOnUnitsSold'];
		$__launch_count = $__value[0]['extraAttributes']['sumLaunchedUnit'];
		$__launch_avg_area = $__value[0]['extraAttributes']['wavgSizeOnLaunchedUnit'];
		
		$__total_delay_month += $__value[0]['extraAttributes']['wavgCompletionDelayInMonthOnInventory'];
		
		$__non_sold_area = $__launch_count * $__launch_avg_area - $__sold_count * $__sold_avg_area;
		$__non_sold_count = $__launch_count - $__sold_count;
		
		if ($__non_sold_area < 0 || $__non_sold_count <= 0) {
		    $__non_sold_area = 0;
	    	$__non_sold_count = 0;
		}
		
		$__total_delay_unit_per_builder += $__value[0]['extraAttributes']['sumInventory'];
		if($__status === 'Under Construction' or $__status === 'Pre Launch'){
			//echo "Under Construction or Pre Launch";
			if ($_Under_Construction_And_Pre_Launch_Flag){
				$__params[$__status_map['Under Construction And Pre Launch']] = 0;
				$_Under_Construction_And_Pre_Launch_Flag = FALSE;		
			}
			$__params[$__status_map['Under Construction And Pre Launch']] += log($__sold_count * $__sold_avg_area + $__non_sold_area + 1);
			if (!isset ($__max_val[$__status_map['Under Construction And Pre Launch']])) {
		    	$__max_val[$__status_map['Under Construction And Pre Launch']] = $__params[$__status_map['Under Construction And Pre Launch']];
			}
			else {
		    	if ($__max_val[$__status_map['Under Construction And Pre Launch']] < $__params[$__status_map['Under Construction And Pre Launch']]) {
					$__max_val[$__status_map['Under Construction And Pre Launch']] = $__params[$__status_map['Under Construction And Pre Launch']];
		    	}
			}
		}
		if ($__status === 'Completed'){
			//echo "Completed";
			$__params[$__status_map[$__status]] = log($__sold_count * $__sold_avg_area + $__non_sold_area + 1);
			if (!isset ($__max_val[$__status_map[$__status]])) {
		    	$__max_val[$__status_map[$__status]] = $__params[$__status_map[$__status]];
			}
			else {
		    	if ($__max_val[$__status_map[$__status]] < $__params[$__status_map[$__status]]) {
					$__max_val[$__status_map[$__status]] = $__params[$__status_map[$__status]];
		    	}
			}
		}
		
		$__total_size_sum += $__sold_count * $__sold_avg_area + $__non_sold_area;
		
    }
    
    $__params['BUILDER_OVERALL_DELAY'] = 0.1;
    if ($__total_delay_month * $__total_delay_unit_per_builder > 0 ) {
	$__params['BUILDER_OVERALL_DELAY'] = 1.0/(1.0 + log($__total_delay_month * $__total_delay_unit_per_builder));
    }
    
    if (isset($__rate_of_sale_params[$__builder_id])) {
	if (empty($__rate_of_sale_params[$__builder_id]['SUM_RATE_OF_SALE'])|| empty($__rate_of_sale_params[$__builder_id]['SUM_INVENTORY'])) {
	    $__params['RATE_OF_SALE'] = 0;
	}
	else {
	  $__params['RATE_OF_SALE'] =(8 + log(($__rate_of_sale_params[$__builder_id]['SUM_RATE_OF_SALE']+0.0001)/($__rate_of_sale_params[$__builder_id]['SUM_INVENTORY']+1)));
	}
    }
    else {
	$__params['RATE_OF_SALE'] = 0;
    }
    
    if (!isset($__params['BUILDER_NAME'])) {
	    $__params['BUILDER_NAME'] = $__value[0]['builderName'];
    }
    if (isset($__establised_date_param[$__builder_id])){
	$__params[$__status_map['ESTABLISED_TIME']] = ($__establised_date_param[$__builder_id]/100);
    }
    else {
	 $__params[$__status_map['ESTABLISED_TIME']] = $__avg_builder_age/100;
    }
   
    if (!isset ($__max_val['BUILDER_OVERALL_DELAY'])) {
	 $__max_val['BUILDER_OVERALL_DELAY'] = $__params['BUILDER_OVERALL_DELAY'];
    }
    else {
	 if ($__max_val['BUILDER_OVERALL_DELAY'] < $__params['BUILDER_OVERALL_DELAY']) {
	    $__max_val['BUILDER_OVERALL_DELAY'] = $__params['BUILDER_OVERALL_DELAY'];
	 }
    }
     
    if (!isset ($__max_val['RATE_OF_SALE'])) {
	 $__max_val['RATE_OF_SALE'] = $__params['RATE_OF_SALE'];
    }
    else {
	 if ($__max_val['RATE_OF_SALE'] < $__params['RATE_OF_SALE']) {
	    $__max_val['RATE_OF_SALE'] = $__params['RATE_OF_SALE'];
	 }
    }
    
    if (!isset ($__max_val[$__status_map['ESTABLISED_TIME']])) {
	 $__max_val[$__status_map['ESTABLISED_TIME']] = $__params[$__status_map['ESTABLISED_TIME']];
    }
    else {
	 if ($__max_val[$__status_map['ESTABLISED_TIME']] < $__params[$__status_map['ESTABLISED_TIME']]) {
	    $__max_val[$__status_map['ESTABLISED_TIME']] = $__params[$__status_map['ESTABLISED_TIME']];
	 }
    }
    
    $__params_array[$__builder_id] = $__params;
}
ksort($__params_array);
//get is builder listed or not

$__id_is_builder_listed_map = array();
$r = 1000;
$s = 0;
while(true){
	$url = 'https://www.proptiger.com/data/v1/entity/builder?selector={"fields":["id","isBuilderListed"],"paging":{"start":'.$s.',"rows":'.$r.'}}';
	$__api_respose = file_get_contents($url);
	if(strlen($__api_respose)){
		$logger->info("isBuilderListed api response successful");
	}
	else{
		$logger->error("builder api response unable to fetch data");
		sendRawEmailFromAmazon(B2B_SUCCESS_EMAIL_RECIPIENT, '', '', 'is builder listed api in Builder_score failed to fetch data', 'is builder listed api in BuilderScore.php in cms codeBase failed to fetch data .', '', '',   array(B2B_SUCCESS_EMAIL_RECIPIENT));
		exit(1);
	}

	$__api_respose_json = json_decode($__api_respose, true);
	$__listed_array = $__api_respose_json['data'];
	// create builder and is builder listed array
	if(sizeof($__listed_array) == $r){
		$s += $r;
	}
	else{
		break;
	}
	foreach($__listed_array as $__id => $__value){
		if (!$__value['isBuilderListed']){
			$__id_is_builder_listed_map[$__value['id']] = 0;	
		}
		else{
			$__id_is_builder_listed_map[$__value['id']] = 1;	
		}
	}
}
$__id_score_map = array();
foreach($__params_array as $__builder_id => $__final_params) {
	$__builder_score = 0;
	foreach($__status_map as $__param => $__val) {
		$__pos = strpos($__val, 'SQRF_');
		if ($__pos !== FALSE && (isset($__final_params[$__val]) || isset($__final_params[$__val]))) {
			$__builder_score += ($__final_params[$__val]/$__max_val[$__val]) * $__param_multiplication_factor[$__val];
		}
		else if (isset($__final_params[$__val])) {
			$__builder_score += ($__final_params[$__val]/$__max_val[$__val]) * $__param_multiplication_factor[$__val];
		}
	}
	if (array_key_exists($__builder_id,$__id_is_builder_listed_map)){
		if($__id_is_builder_listed_map[$__builder_id]){
			$__builder_score +=	0.05;
		}
	}
	$__id_score_map[$__builder_id] = 10* ($__builder_score*(1-$__min_livability_score) + $__min_livability_score);
}
//print_r($__id_score_map)
foreach ($__id_score_map as $__builder_id => $__builder_score) {
	$__update_query = "update cms.resi_builder set builder_score =". ($__builder_score) . " where builder_id =".$__builder_id;
	if(mysql_query($__update_query)){
		$logger->info("successfully update builder_score in resi_builder");
	}
	else{
		$logger->error('error in updating builder_score in resi_builder table'.mysql_error());
		sendRawEmailFromAmazon(B2B_SUCCESS_EMAIL_RECIPIENT, '', '', 'error in updating builder_score in resi_builder table', 'error in updating builder_score in resi_builder table'.mysql_error(), '', '',   array(B2B_SUCCESS_EMAIL_RECIPIENT));
		exit(1);
	}
}
//print("finished bye");
?>