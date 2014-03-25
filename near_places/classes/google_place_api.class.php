<?php
require_once __DIR__.'/../../log4php/Logger.php';
//AIzaSyB1PVKAvCD6-i8yG8IT01ocud8RMkAJmIA
//my key: AIzaSyAjGqIATvbau2Rg4Ujr1OKImkk88Lt4_bw
define("KEY", "AIzaSyB1PVKAvCD6-i8yG8IT01ocud8RMkAJmIA");
define("URL", "https://maps.googleapis.com/maps/api/place/");
define("NEARBYSEARCH_MAX_RESULTS", 60);
define("RADARSEARCH_MAX_RESULTS", 200);
define("TEXTSEARCH_MAX_RESULTS", 20);
define("DETAILS_MAX_RESULTS", 1);
define("SENSOR_STATUS", "false");
define("TIME_DELAY", 1000); // 1 millisecond.

class GooglePlaceApi
{
	var $params;
	var $url;
	var $searchType;
	var $responseType;
	var $httpRequest;
	var $logger;

	public function __construct()
	{
		$this->httpRequest = new HttpRequest();
		$this->logger =  Logger::getLogger("main"); 
	}

	public function setParams($params)
	{
		$params['key'] = KEY;
		$params['sensor'] = SENSOR_STATUS;
		$this->params = $params;	
	}
	
	public function setUrlParams($searchType, $responseType)
	{
		$this->searchType = $searchType;
		$this->responseType = $responseType;
		$this->url = URL.$searchType."/{$responseType}";
	}

	public function sendRequest($numberOfResults, $locality_id=0, $place_type_id=0)
	{
		$max_allowed_results = $this->getMaxAllowedResults();
		if($numberOfResults > $max_allowed_results)
		{
			$numberOfResults = $max_allowed_results;
			logResult("MAX RESULT ALLOWED IS ".$max_allowed_results.". GIVEN $numberOfResults");
		}

		$totalRows = 0;
		$results = array();

		do
		{
			$response_data = $this->send();
			
			$this->checkResponseStatusAndLog($response_data, $locality_id, $place_type_id);

			$current_results = $this->getResults($response_data);
			$currentRows = count($current_results);

			$results = array_merge($results, $current_results);

			$totalRows += $currentRows;
			$pagetoken = (string)$response_data['pagetoken'];
			$this->setParams( array("pagetoken" => $pagetoken) );
			
			usleep(TIME_DELAY);
		}while($totalRows<$numberOfResults && $currentRows>0 && !empty($pagetoken) );

		return $totalRows> $numberOfResults? array_slice($results, 0, $numberOfResults): $results;				
	}
	
	public function send()
	{
	  $this->httpRequest->setMethod(HTTP_METH_GET);
  	$this->httpRequest->setQueryData($this->params);
  	$this->httpRequest->setUrl($this->url);
		
		try{
			$this->httpRequest->send();
		}
		catch(Exception $ex)
		{
			$this->handleException($ex);
		}

		$responseBody = $this->httpRequest->getResponseBody();

		return json_decode($responseBody, true);
	}
	
	public function getResults($response_body)
	{
		if( isset($response_body['results']) )
			return $response_body['results'];
		else
			return array($response_body['result']);
	}
	
	public function getMaxAllowedResults()
	{
		switch($this->searchType)
		{
			case "nearbysearch":
				return NEARBYSEARCH_MAX_RESULTS;
			case "textsearch":
				return TEXTSEARCH_MAX_RESULTS;
			case "radarsearch":
				return RADARSEARCH_MAX_RESULTS;
			case "details":
				return DETAILS_MAX_RESULTS;
		}			
	}
	
	public function checkResponseStatusAndLog($response_data, $locality_id, $place_type_id)
	{
		$status = $response_data['status'];
		$msg = "LOCALITY ID : $locality_id, PLACE TYPE ID: $place_type_id , STATUS : $status";
		$msg .= " SEARCH TYPE {$this->searchType}";
		
		switch($status)
		{
			case "OVER_QUERY_LIMIT":
			case "REQUEST_DENIED":
				$this->logger->info($msg);
				$this->logger->info("SCRIPT EXITING");
				exit();
				break;

			case "ZERO_RESULTS":
			case "INVALID_REQUEST":
			case "OK":
				$this->logger->info($msg);
				$this->logger->info($this->httpRequest->getRequestMessage());
				break;
		}

		return true;
	}
	
	public function handleException($ex)
	{
			$response_status = $this->httpRequest->getResponseStatus();
			$this->logger->error($ex."\n Response Message: ".$response_status);
	}
}	

?>
