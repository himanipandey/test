<?php
/*
 * This script is to populate the builder_score in the resi_builder table which
 * is used to compute the project livability as one of the factor. Builder score
 * factor is computed using various factors like construction status sqft area sold, unsold,
 * builder age and rate of unit sold by builder. 
 */
$docroot = dirname(__FILE__) . "/../";
require_once $docroot.'dbConfig.php';

/*
|  1 | UnderConstruction | Under Construction |
|  2 | Cancelled         | Cancelled          |
|  3 | Completed         | Completed          |
|  5 | OnHold            | On Hold            |
|  6 | NotLaunched       | Not Launched       |
|  7 | Launch            | Launch             |
|  8 | PreLaunch         | Pre Launch 	      |
*/

$__status_map = array('Completed' => 'SQRF_AREA_COMPLETED', 'Under Construction' => 'SQRF_AREA_UNDER_CONSTRUCTION',
		'Pre Launch' => 'SQRF_AREA_PRE_LAUNCH', 'ESTABLISED_TIME' => 'ESTABLISED_TIME',
		'BUILDER_OVERALL_DELAY' => 'BUILDER_OVERALL_DELAY', 'RATE_OF_SALE' => 'RATE_OF_SALE');

$__param_multiplication_factor = array( 'SQRF_AREA_COMPLETED_SOLD' => 0.10,'SQRF_AREA_COMPLETED_UNSOLD' => 0.05,
		'SQRF_AREA_UNDER_CONSTRUCTION_SOLD' => 0.15,'SQRF_AREA_UNDER_CONSTRUCTION_UNSOLD' => 0.10,
		'SQRF_AREA_PRE_LAUNCH_SOLD' => 0.125,'SQRF_AREA_PRE_LAUNCH_UNSOLD' => 0.120,
		'BUILDER_OVERALL_DELAY' => 0.05,'RATE_OF_SALE' => 0.10,
		'ESTABLISED_TIME' => 0.25);

$__min_livability_score = 0.5;

$__builder_api_respose = file_get_contents('https://www.proptiger.com/data/v1/entity/builder?selector={"fields":["id","establishedDate"],"paging":{"start":0,"rows":10000}}');
$__builder_api_respose_json = json_decode($__builder_api_respose, true);
$__establised_date_array = $__builder_api_respose_json['data'];

$__builder_counts = count($__establised_date_array);
$__establised_date_param = array();

$__max_builder_age = 0;

for($__idx = 0; $__idx < $__builder_counts; $__idx++){
	$__builder_id = $__establised_date_array[$__idx]['id'];

	if (isset($__establised_date_array[$__idx]['establishedDate'])) {
		$__builder_age = (time() - $__establised_date_array[$__idx]['establishedDate']/1000)/(30*24*3600);
		$__establised_date_param[$__builder_id] = $__builder_age;
	}
}

$__current_trend_api_res = file_get_contents('https://www.proptiger.com/data/v1/trend/current?fields=sumRateOfSale,sumInventory&group=builderId&start=0&rows=30000');
$__current_trend_api_res_json = json_decode($__current_trend_api_res, true);
$__rate_of_sale_array = $__current_trend_api_res_json['data'];
$__rate_of_sale_params = array();
$__builder_counts = count($__rate_of_sale_array);

foreach($__rate_of_sale_array as $__builder_id => $__rate_of_sale){
	$__rate_of_sale_params[$__builder_id]['SUM_RATE_OF_SALE'] = $__rate_of_sale[0]['extraAttributes']['sumRateOfSale'];
	$__rate_of_sale_params[$__builder_id]['SUM_INVENTORY'] = $__rate_of_sale[0]['extraAttributes']['sumInventory'];
}

$__trend_api_response = file_get_contents('https://www.proptiger.com/data/v1/trend?fields=builderName,wavgCompletionDelayInMonthOnLaunchedUnit,wavgCompletionDelayInMonthOnInventory,wavgSizeOnUnitsSold,sumInventory,sumUnitsSold,sumLaunchedUnit,wavgSizeOnLaunchedUnit&group=builderId,constructionStatus&start=0&rows=30000');
$__trend_api_response_json = json_decode($__trend_api_response, true);

$__trend_data_array = $__trend_api_response_json['data'];
$__params_array = array();

$__max_val = array();
foreach($__trend_data_array as $__builder_id => $__status_array){
	$__params = array();
	$__total_delay_month = 0;
	$__total_delay_unit_per_builder = 0;
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

		$__params[$__status_map[$__status]."_SOLD"] = log($__sold_count * $__sold_avg_area + 1);
		$__params[$__status_map[$__status]."_UNSOLD"] = log($__non_sold_area+1); 

		if (!isset ($__max_val[$__status_map[$__status]."_SOLD"])) {
			$__max_val[$__status_map[$__status]."_SOLD"] = $__params[$__status_map[$__status]."_SOLD"];
		}
		else {
			if ($__max_val[$__status_map[$__status]."_SOLD"] < $__params[$__status_map[$__status]."_SOLD"]) {
				$__max_val[$__status_map[$__status]."_SOLD"] = $__params[$__status_map[$__status]."_SOLD"];
			}
		}

		if (!isset ($__max_val[$__status_map[$__status]."_UNSOLD"])) {
			$__max_val[$__status_map[$__status]."_UNSOLD"] = $__params[$__status_map[$__status]."_UNSOLD"];
		}
		else {
			if ($__max_val[$__status_map[$__status]."_UNSOLD"] < $__params[$__status_map[$__status]."_UNSOLD"]) {
				$__max_val[$__status_map[$__status]."_UNSOLD"] = $__params[$__status_map[$__status]."_UNSOLD"];
			}
		}
	}

	$__params['BUILDER_OVERALL_DELAY'] = 0.1;
	if ($__total_delay_month * $__total_delay_unit_per_builder > 0 ) {
		$__params['BUILDER_OVERALL_DELAY'] = 1/($__total_delay_month * $__total_delay_unit_per_builder);
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
	 $__params[$__status_map['ESTABLISED_TIME']] = 0;
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
$__id_score_map = array();
foreach($__params_array as $__builder_id => $__final_params) {
	$__builder_score = 0;
	foreach($__status_map as $__param => $__val) {
		$__pos = strpos($__val, 'SQRF_');
		if ($__pos !== FALSE && (isset($__final_params[$__val."_SOLD"]) || isset($__final_params[$__val."_UNSOLD"]))) {
			$__builder_score += ($__final_params[$__val."_SOLD"]/$__max_val[$__val."_SOLD"]) * $__param_multiplication_factor[$__val."_SOLD"];
			$__builder_score += ($__final_params[$__val."_UNSOLD"]/$__max_val[$__val."_UNSOLD"]) * $__param_multiplication_factor[$__val."_UNSOLD"];
		}
		else if (isset($__final_params[$__val])) {
			$__builder_score += ($__final_params[$__val]/$__max_val[$__val]) * $__param_multiplication_factor[$__val];
		}
	}

	$__id_score_map[$__builder_id] = 10* ($__builder_score*(1-$__min_livability_score) + $__min_livability_score);
}

foreach ($__id_score_map as $__builder_id => $__builder_score) {
	$__update_query = "update cms.resi_builder set builder_score =". ($__builder_score) . " where builder_id =".$__builder_id;
	mysql_query($__update_query) or die(mysql_error());
}
?>