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
		      'Cancelled' => 'SQRF_AREA_CANCELLED','Pre Launch' => 'SQRF_AREA_PRE_LAUNCH', 
		      'ESTABLISED_TIME' => 'ESTABLISED_TIME','BUILDER_OVERALL_DELAY' => 'BUILDER_OVERALL_DELAY');
		      
$__param_multiplication_factor = array( 'SQRF_AREA_COMPLETED_SOLD' => 0.15,'SQRF_AREA_COMPLETED_UNSOLD' => 0.05,
					'SQRF_AREA_UNDER_CONSTRUCTION_SOLD' => 0.10,'SQRF_AREA_UNDER_CONSTRUCTION_UNSOLD' => 0.10,
					'SQRF_AREA_CANCELLED_SOLD' => 0.05,'SQRF_AREA_CANCELLED_UNSOLD' => -0.05,
					'SQRF_AREA_PRE_LAUNCH_SOLD' => 0.125,'SQRF_AREA_PRE_LAUNCH_UNSOLD' => 0.120,
					'BUILDER_OVERALL_DELAY' => -0.10,'RATE_OF_SALE' => 0.10,
					'ESTABLISED_TIME' => 0.25);

$__min_livability_score = 0.4;

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

$__current_trend_api_res = file_get_contents('https://www.proptiger.com/data/v1/trend/current?fields=sumRateOfSale&group=builderId&rows=30000');
$__current_trend_api_res_json = json_decode($__current_trend_api_res, true);
$__rate_of_sale_array = $__current_trend_api_res_json['data'];
$__rate_of_sale_params = array();
$__builder_counts = count($__rate_of_sale_array);

foreach($__rate_of_sale_array as $__builder_id => $__rate_of_sale){
      $__rate_of_sale_params[$__builder_id] = $__rate_of_sale[0]['extraAttributes']['sumRateOfSale'];
}

$__trend_api_response = file_get_contents('https://www.proptiger.com/data/v1/trend?fields=builderName,wavgCompletionDelayInMonthOnLaunchedUnit,wavgSizeOnUnitsSold,sumUnitsSold,sumLaunchedUnit,wavgSizeOnLaunchedUnit&group=builderId,constructionStatus&rows=30000');
$__trend_api_response_json = json_decode($__trend_api_response, true);

$__trend_data_array = $__trend_api_response_json['data'];
$__params_array = array();

$__division_factor = 10;

foreach($__trend_data_array as $__builder_id => $__status_array){
    $__total_size_sum = 0;
    $__params = array();
    $__sqrf_sold_area = 0;
    $__total_delay_month = 0;
    $__total_delay_unit_per_builder = 0;
    foreach($__status_array as $__status => $__value) {
	$__sold_count = $__value[0]['extraAttributes']['sumUnitsSold'];
	$__sold_avg_area = $__value[0]['extraAttributes']['wavgSizeOnUnitsSold'];
	
	$__launch_count = $__value[0]['extraAttributes']['sumLaunchedUnit'];
	$__launch_avg_area = $__value[0]['extraAttributes']['wavgSizeOnLaunchedUnit'];
	
	$__total_delay_month += $__value[0]['extraAttributes']['wavgCompletionDelayInMonthOnLaunchedUnit'];
	
	$__non_sold_area = $__launch_count * $__launch_avg_area - $__sold_count * $__sold_avg_area;
	$__non_sold_count = $__launch_count - $__sold_count;
	
	if ($__non_sold_area < 0 || $__non_sold_count <= 0) {
	    $__non_sold_area = 0;
	    $__non_sold_count = 0;
	}
	$__total_delay_unit_per_builder += $__non_sold_count;
	$__params[$__status_map[$__status]."_SOLD"] = log($__sold_count * $__sold_avg_area + 1)/$__division_factor;
	$__params[$__status_map[$__status]."_UNSOLD"] = log($__non_sold_area+1)/$__division_factor;
	
	$__total_size_sum += $__sold_count * $__sold_avg_area + $__non_sold_area;
    }
    
    $__params['BUILDER_OVERALL_AREA_CONSTRUCTED'] = log($__total_size_sum+1)/$__division_factor;
    $__params['BUILDER_OVERALL_DELAY'] = log($__total_delay_month * $__total_delay_unit_per_builder*100+1)/$__division_factor;
    
    if (isset($__rate_of_sale_params[$__builder_id])) {
	$__params['RATE_OF_SALE'] =(8 + log(($__rate_of_sale_params[$__builder_id]+0.0001)/($__non_sold_count+1)))/$__division_factor;
    }
    else {
	$__params['RATE_OF_SALE'] = 1;
    }
    
    if (!isset($__params['BUILDER_NAME'])) {
	    $__params['BUILDER_NAME'] = $__value[0]['builderName'];
    }
    if (isset($__establised_date_param[$__builder_id])){
	$__params[$__status_map['ESTABLISED_TIME']] = ($__establised_date_param[$__builder_id]/100)/$__division_factor;
    }
    else {
	 $__params[$__status_map['ESTABLISED_TIME']] = 0;
    }
   
    $__params_array[$__builder_id] = $__params;
}

ksort($__params_array);
$__max_builder_score = 0;
$__id_score_map = array(); 
foreach($__params_array as $__builder_id => $__final_params) {
    $__builder_score = 0;
    foreach ( $__status_map as $__param => $__val ) {
		$__pos = strpos ( $__val, 'SQRF_' );
		if ($__pos !== FALSE && (isset ( $__final_params [$__val . "_SOLD"] ) || isset ( $__final_params [$__val . "_UNSOLD"] ))) {
			$__builder_score += $__final_params [$__val . "_SOLD"] * $__param_multiplication_factor [$__val . "_SOLD"];
			$__builder_score += $__final_params [$__val . "_UNSOLD"] * $__param_multiplication_factor [$__val . "_UNSOLD"];
		} else if (isset ( $__final_params [$__val] )) {
			$__builder_score += $__final_params [$__val] * $__param_multiplication_factor [$__val];
		}
	}
    $__builder_score += $__final_params['RATE_OF_SALE'] * $__param_multiplication_factor['RATE_OF_SALE'];
    $__builder_score += $__builder_score * (1-$__min_livability_score) + $__min_livability_score;
    if ($__max_builder_score < $__builder_score) {
    	$__max_builder_score = $__builder_score;
    }
    $__id_score_map[$__builder_id] = $__builder_score;
}

foreach ($__id_score_map as $__builder_id => $__builder_score) {
	$__update_query = "update cms.resi_builder set builder_score =". ($__builder_score/$__max_builder_score) . " where builder_id =".$__builder_id;
	mysql_query($__update_query) or die(mysql_error());
}
?>