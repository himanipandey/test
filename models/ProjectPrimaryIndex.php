<?php

// Model integration for Company list
class ProjectPrimaryIndex extends ActiveRecord\Model
{
    
    static $table_name = 'project_primary_index';
    
    static private function truncate(){
      self::connection()->query("truncate table " . self::$table_name);
    }
    
    static private function insertProjectIds(){
        self::connection()->query("insert into " . self::$table_name . " (id) select PROJECT_ID from resi_project where version = 'Website'");
    }

    static private function populateLaunchDateFactor(){
        
    }
    
    static private function populateInventoryFactor(){
        
    }
    
    static private function populateFinalIndex(){
        $sql = "update project_primary_index set primary_index = (launch_date_factor+inventory_factor)*10/2, resale_index = (completion_date_factor+1-inventory_factor)*10/2";
        self::connection()->query($sql);
        $sql = "update project_primary_index set primary_index = launch_date_factor*10, resale_index = completion_date_factor*10 where inventory_factor is null";
        self::connection()->query($sql);
        $sql = "update project_primary_index set primary_index = inventory_factor*10 where launch_date_factor is null";
        self::connection()->query($sql);
        $sql = "update project_primary_index set resale_index = (1-inventory_factor)*10 where completion_date_factor is null";
        self::connection()->query($sql);
       
        //do not remove below commented code, will be used when resale listing factor is used to calculate resale index
        /*$sql = "update project_primary_index set resale_index =  CASE 
            WHEN  completion_date_factor is null and resale_listing_count_factor is null THEN (1-inventory_factor)*10 
            WHEN  inventory_factor is null and resale_listing_count_factor is null THEN completion_date_factor*10 
            WHEN  inventory_factor is null and completion_date_factor is null THEN resale_listing_count_factor*10 
            WHEN  inventory_factor is null THEN (0.8*completion_date_factor + 0.2*resale_listing_count_factor)*10   
            WHEN  completion_date_factor is null THEN (0.8*(1-inventory_factor)+0.2*resale_listing_count_factor)*10 
            WHEN  resale_listing_count_factor is null THEN (0.5*(1-inventory_factor)+0.5*completion_date_factor)*10 
            ELSE (0.4*(1-inventory_factor)+0.4*completion_date_factor+0.2*resale_listing_count_factor)*10
            END";
        self::connection()->query($sql);*/
    }
  
   
    static public function populatePrimaryAndResaleIndex(){
        self::truncate();
        self::insertProjectIds();
        self::populateLaunchDateFactor();
        self::populateInventoryFactor();
        self::populateFinalIndex();
        $resaleListingData = array();
        $resaleListingData = self::getResaleListingsCount();
        
        $projects = self::getProjectData();
        $cities = self::getCityData();
                
        foreach ($projects as $projectId => $value) {
            $cityId = $value[0]->cityId; 
            $cityDetails = $cities->$cityId; 
            $projectLaunchDate = $value[0]->launchDate;
            $cityMaxLaunchDate = $cityDetails[0]->extraAttributes->maxLaunchDate;
            if(!is_null($projectLaunchDate) && !is_null($cityMaxLaunchDate)){
                self::update_all(array('set'=>"launch_date_factor = " . ($cityMaxLaunchDate - $projectLaunchDate), 'conditions'=>array('id'=>$projectId)));
            }
            $launchedUnits = $value[0]->extraAttributes->sumLtdLaunchedUnit;
            $inventory = $value[0]->extraAttributes->sumInventory;
            if(is_null($inventory)){
                $unsoldPercentage = 1;
            }
            elseif ($inventory == 0) {
                $unsoldPercentage = 0;
            }
            else{
                $unsoldPercentage = $inventory/$launchedUnits;
            }
            self::update_all(array('set'=>"inventory_factor = " . $unsoldPercentage, 'conditions'=>array('id'=>$projectId)));


            $projectCompletionDate = $value[0]->completionDate;
            $cityMaxCompletionDate = $cityDetails[0]->extraAttributes->maxCompletionDate;
            if(!is_null($projectCompletionDate) && !is_null($cityMaxCompletionDate)){
                self::update_all(array('set'=>"completion_date_factor = " . ($cityMaxCompletionDate - $projectCompletionDate), 'conditions'=>array('id'=>$projectId)));
            }

                        
            if(isset($resaleListingData[$projectId]) ){
                $data = $resaleListingData[$projectId]; 
                if(isset($data->resaleListingCount) ){
                    self::update_all(array('set'=>"resale_listing_count_factor = " . $data->resaleListingCount , 'conditions'=>array('id'=>$projectId)));
                }
            }

        }
        self::normalizeFactors();
        self::populateFinalIndex();
    }
    
    static private function normalizeFactors(){
        $cityMaxDateFactors = self::getCityMaxDateFactors();
        foreach ($cityMaxDateFactors as $cityFactor) {
            $cityId = $cityFactor->city_id;
            $maxLaunchFactor = $cityFactor->launch_date_factor;
            $maxCompletionFactor = $cityFactor->completion_date_factor;
            $maxResaleListingCountFactor = $cityFactor->resale_listing_count_factor;
            $updateStr = "";
            if(!is_null($maxLaunchFactor)){
                if($maxLaunchFactor == 0){
                    $updateStr .= 'ppi.launch_date_factor = 1,';
                }
                else{
                    $updateStr .= "ppi.launch_date_factor = 1 - ppi.launch_date_factor/ $maxLaunchFactor,";
                }
            }
            if(!is_null($maxCompletionFactor)){
                if($maxCompletionFactor == 0){
                    $updateStr .= 'ppi.completion_date_factor = 1,';
                }
                else{
                    $updateStr .= "ppi.completion_date_factor = 1 - ppi.completion_date_factor/ $maxCompletionFactor,";
                }
            }
            if(!is_null($maxResaleListingCountFactor)){

                if($maxResaleListingCountFactor == 0){
                    $updateStr .= 'ppi.resale_listing_count_factor = 1';
                }
                else{
                    $updateStr .= "ppi.resale_listing_count_factor = CASE WHEN ppi.resale_listing_count_factor > 0.5*$maxResaleListingCountFactor  THEN 1
                        ELSE ppi.resale_listing_count_factor/(0.5*$maxResaleListingCountFactor) END";
                }
            }

            $updateStr = rtrim($updateStr, ",");
            if(isset($updateStr) && !empty($updateStr)){
                $updateSql = "update project_primary_index ppi inner join resi_project rp on rp.project_id = ppi.id and rp.version = 'Website' inner join locality l on l.locality_id = rp.locality_id inner join suburb s on s.suburb_id = l.suburb_id and s.city_id = $cityId set $updateStr";
                self::connection()->query($updateSql);
            }
        }
    }
    
    

    static private function getCityMaxDateFactors() {
        $sql = "select s.city_id, max(ppi.launch_date_factor) launch_date_factor, max(ppi.completion_date_factor) completion_date_factor, max(ppi.resale_listing_count_factor) resale_listing_count_factor from project_primary_index ppi inner join resi_project rp on rp.project_id = ppi.id and rp.version = 'Website' inner join locality l on l.locality_id = rp.locality_id inner join suburb s on s.suburb_id = l.suburb_id group by s.city_id";
        return self::find_by_sql($sql);
    }

   

    static private function getProjectData(){
        $count = self::count();
        $url = PROPTIGER_URL . "/data/v1/trend/current?fields=cityId,sumLtdLaunchedUnit,sumInventory,launchDate,completionDate&group=projectId&rows=$count";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, CALL_TIMEOUT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response)->data;
    }
    
    static private function getCityData(){
        $count = self::count();
        $url = PROPTIGER_URL . "/data/v1/trend/current?fields=maxLaunchDate,maxCompletionDate&group=cityId&rows=$count";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, CALL_TIMEOUT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response)->data;
    }

    static private function getResaleListingsCount(){
        $count = self::count();
        $start = 0;
        $rows = 1000;
        $data = array();
        for($j = 0; $j < $count ; $j++){
           $url = 'https://www.proptiger.com/app/v2/project-listing?selector='. urlencode('{"fields": ["projectId", "resaleListingCount"], "paging":{"start": '.$start.', "rows":'.$rows.' }}');
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, CALL_TIMEOUT);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                            'Content-Type: application/json',
                                            'Connection: Keep-Alive'
                                            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $response = curl_exec($ch);
            curl_close($ch);
            $items = json_decode($response)->data->items;
            foreach ($items as $item ) {
                $data[$item->projectId] = $item;
            }
            $len = sizeof($items);
            if($len == 0){
                break;
            }
            $start = $j*$rows;
        }
        return $data;

    }
}
