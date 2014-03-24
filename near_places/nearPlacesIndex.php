<?php
ini_set("display_errors", 0);
error_reporting(E_ERROR|E_PARSE);

/*
 * HOW TO RUN SCRIPT.
 * 1: php nearPlacesIndex.php => will run for all cities. Get the 
 *    place types and localities left to be updated and run for all of them
 * 2: php nearPlacesIndex.php city_name => will run for particular city name.
 * 3: php nearPlacesIndex.php city_name deleteThenInsert=> will 
 *    first delete all data on city name and then run for particular city name.
 * 4: php nearPlaceIndex.php deleteThenInsert => will truncate 
 *     entire data and then run on all cities
 * 5: php nearPlacesIndex.php city1,city2,city3 [deleteThenInsert] For Running 
 *    multiple cities at once, seperate the cities name with comma. 
 *    deleteThenInsert is optional.
 */

require_once '../dbConfig.php';

require_once 'classes/google_place_api.class.php';
require_once 'nearPlaces.conf.php';

Logger::configure( dirname(__FILE__) . '/../log4php.xml');
$logger = Logger::getLogger("main");
$logger->info(" NEAR PLACES SCRIPT STARTED ");

$logger->info(" RUNNING SCRIPT WAYS ");



retrieveGooglePlaceList($argv);

function retrieveGooglePlaceList($argv)
{
    global $logger;
	$cityData = getCityId($argv[1]);

    $numberOfCities = count($cityData);
    $numberOfCities = $numberOfCities<=0? 1: $numberOfCities;
    for($i=0; $i<$numberOfCities; $i++)
    {
        $city_id = $cityData[$i]['CITY_ID'];
	    if($argv[1] == "deleteThenInsert" or $argv[2] == "deleteThenInsert")
		    deleteNearPlacesData($city_id);

        $logger->info(" STARTED retrieving data for CITY {$cityData[$i]['CITY_ID']} ");
        
    	getNearPlacesList($city_id);

        $logger->info(" ENDED retrieving data for CITY {$cityData[$i]['CITY_ID']} ");
    }
} 


function getNearPlacesList($city_id)
{
	global $logger;

	$location_place_info = getRemainingLocAndTypeList($city_id);
	$place_info = getPlaceTypes();
	$locality_info = getAllLocalityDetails($city_id);
	$configuration = getConfiguration();
	$default_params = getDefaultParams($configuration);	

	foreach($location_place_info as $locality_id => $loc_info)
	{
		if( !isset($locality_info[$locality_id]) )
		{
			$logger->info(" INVALID LOCATION ID {$locality_id} in locality info");
			continue;
		}

		$city_id = $locality_info[$locality_id]['CITY_ID'];
		foreach($loc_info as $place_id)
		{
			$logger->info("LOCATION ID : {$locality_id}, PLACE ID : {$place_id}, CITY ID : {$city_id}");

			$params = $default_params;
			setParamsOnLocality($params, $configuration, $locality_id);
			$nearbyplaces = getNearPlacesOnLoc(	$locality_info[$locality_id], 
																					$place_info[$place_id], $params,
																					$locality_id, $place_id);
			if(count($nearbyplaces) >0)
			{
				getPlaceDetailsOnLocality($nearbyplaces, $params, $locality_id, $place_id);
				saveLocalityData($locality_id, $place_id, $city_id, $nearbyplaces);
			}
		}	
	}		
}

function saveLocalityData($locality_id, $place_id, $city_id, $nearbyplaces)
{
	foreach($nearbyplaces as $info)
	{
		setNearPlaceData($locality_id, $place_id, $city_id, $info);	
	}	
}



function getUnusedData($info)
{
	$others = array();
	
	$others['price_level'] = $info['price_level'];
	$others['rating'] = $info['rating'];

	return $others;	
}

function getNearPlacesOnLoc($location_info, $place_name, $params, $locality_id, $place_id)
{
	$params['location'] = "{$location_info['LATITUDE']},{$location_info['LONGITUDE']}";
	$params['types'] = $place_name;

	$google_place_api = new GooglePlaceApi();
	$google_place_api->setParams($params);
	$google_place_api->setUrlParams("nearbysearch", "json");
	$nearbyplaces = $google_place_api->sendRequest(	$params['numberofresults'], 
																									$locality_id, $place_id);

	return $nearbyplaces;	
}

function getPlaceDetails($params, $locality_id, $place_id)
{
	$google_place_api = new GooglePlaceApi();
	$google_place_api->setParams($params);
	$google_place_api->setUrlParams("details", "json");
	$place_details = $google_place_api->sendRequest(1, $locality_id, $place_id);

	return $place_details;
}

function getPlaceDetailsOnLocality(&$nearbyplaces, $params, $locality_id, $place_id)
{
	if( !$params['details_request'] )
		return;

	$count = 0;
	foreach($nearbyplaces as $key => $place_info)
	{
		$params = setParamsOnPlaceDetails($place_info);
		$place_details = getPlaceDetails($params, $locality_id, $place_id);
		if($place_details !== FALSE)
		{
			$place_details[0]['is_details'] = 1;
			$nearbyplaces[$key] = $place_details[0];
		}
	}
}

function getConfiguration()
{
	global $google_api_conf;

	return json_decode($google_api_conf, true); 
}

function getDefaultParams($configuration)
{
	return $configuration['default'];
}

function setParamsOnPlaceDetails($place_info)
{
	$params = array();
	$params['reference'] = $place_info['reference'];

	return $params;
}

function setParamsOnLocality(&$params, $configuration, $locality_id)
{
	$locality_conf = $configuration['locality_id'][$locality_id];
	foreach($locality_conf as $key => $value)
  {
    if( !empty($value) )
      $params[$key] = $value;
 }
}


function getPlaceTypes()
{
	global $logger;

	$qry = <<<QRY
		SELECT id, name FROM proptiger.NEAR_PLACE_TYPES
QRY;
	$rs = mysql_query($qry) or logMysqlError($qry, "C04");
	
	$data = array();
	while( ($row=mysql_fetch_row($rs)) !== FALSE)
		$data[$row[0]] = $row[1];
	
	checkAndlogMysqlRowError($qry, $data);

	return $data;
}

function getAllLocalityDetails($city_id)
{
	global $logger;
	
	if($city_id > 0)
		$city_id_str = " AND city_id = {$city_id} ";

	$query = <<<QRY
					SELECT LOCALITY_ID, SUBURB_ID, CITY_ID, LABEL, LATITUDE, LONGITUDE
						FROM proptiger.LOCALITY WHERE ACTIVE = 1 AND DELETED_FLAG = 1 AND LATITUDE > 3
							AND LONGITUDE > 3 {$city_id_str}
QRY;
	
	$rs = mysql_query($query) or logMysqlError($query, "C01");
	
	$locality = array();
	
	while( ($row= mysql_fetch_assoc($rs)) !== FALSE)
	{
		$locality_id = $row['LOCALITY_ID'];
		unset($row['LOCALITY_ID']);
		$locality[$locality_id] = $row;
	}

	checkAndlogMysqlRowError($qry, $locality);

	return $locality;
}


function setNearPlaceData($locality_id, $place_id, $city_id, $info)
{
	global $logger;

	$unused_data = getUnusedData($info);
	$unused_data = json_encode($unused_data);	
	$info['is_details'] = (int)$info['is_details'];
		
	$qry = <<<QRY
		INSERT INTO proptiger.LOCALITY_NEAR_PLACES (city_id, locality_id, place_type_id,
			 name, address, google_place_id, reference, latitude, longitude, phone_number,
			google_url, website, vicinity, is_details, rest_details) VALUES($city_id, 
			$locality_id, $place_id, "{$info['name']}", "{$info['formatted_address']}", 
			"{$info['id']}", "{$info['reference']}", {$info['geometry']['location']['lat']}, 
			{$info['geometry']['location']['lng']}, "{$info['international_phone_number']}", 
			"{$info['url']}", "{$info['website']}", "{$info['vicinity']}", 
			{$info['is_details']}, '{$unused_data}')
QRY;
	$rs = mysql_query($qry) or logMysqlError($qry, "C05");
	
	return $rs;
}

function getRemainingLocAndTypeList($city_id)
{
	global $logger;
	
	if($city_id > 0)
		$city_id_str = " WHERE city_id = {$city_id} ";
	$debug = "";

	$qry = <<<QRY
		SELECT P.city_id AS locality_id, GROUP_CONCAT(P.id) as type_id FROM 
			(SELECT city_id, id FROM cms.landmarks JOIN cms.city 
				$city_id_str $debug) AS P 
		GROUP BY P.locality_id;
QRY;
	
	$rs = mysql_query($qry) or ( logMysqlError($qry, "C03") and exit() );
	
	$data = array();
	while( ( $row=mysql_fetch_row($rs) ) !== FALSE)
		$data[$row[0]] = explode(",", $row[1]);

	checkAndlogMysqlRowError($qry, $data);
	return $data;
}

//@deprecated
function getInsertedNearPlacesDetails()
{
	global $logger;
	
	$query = <<<QRY
		SELECT LOCALITY_ID, GROUP_CONCAT(TYPE_ID) FROM test.NEAR_PLACES_LIST
			GROUP BY LOCALITY_ID ORDER BY LOCALITY_ID
QRY;
	$rs = mysql_query($query) or logMysqlError($query, "C02");
	
	$data = array();
	while( ($data[] = mysql_fetch_assoc($rs)) !== FALSE);
	array_pop($data);	
	
	return $data;	
}

function getCityId($city)
{
	if($city == "" || $city== "deleteThenInsert")
		return -1;
    $city = str_replace(",", "','", $city);
	$qry = <<<QRY
		SELECT LABEL, CITY_ID from proptiger.CITY WHERE label IN ('{$city}')
QRY;
	$rs = mysql_query($qry) or logMysqlError($qry, "C06");
    
    $cityData = array();
    while( ($row = mysql_fetch_assoc($rs))!== FALSE)
        $cityData[] = $row;

	checkAndlogMysqlRowError($qry, $cityData, " INVALID CITY ");

	return $cityData;
}

function deleteNearPlacesData($city_id)
{
	if( $city_id <=0  )
	{
		$qry = <<<QRY
			TRUNCATE proptiger.LOCALITY_NEAR_PLACES;
QRY;
	}
	else
	{
		$qry = <<<QRY
			DELETE FROM proptiger.LOCALITY_NEAR_PLACES WHERE CITY_ID = {$city_id}
QRY;
	}
	
	$rs = mysql_query($qry) or (logMysqlError($qry, "C07") and exit() );
}

function logMysqlError($query, $error_code)
{
	global $logger;
	$logger->error("ERROR IN QUERY {$error_code}: {$query} \n ERROR :".mysql_error());
}

function checkAndlogMysqlRowError($query, $row, $message="")
{
	global $logger;

	if($row === FALSE || count($row) <= 0)
	{
		$logger->error("ERROR RETRIEVING ROWS: {$query} \n {$message}");
		exit();
	}
}
?>
