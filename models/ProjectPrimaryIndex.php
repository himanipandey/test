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
        $sql = "update project_primary_index set primary_index = (launch_date_factor+inventory_factor)*10/2";
        self::connection()->query($sql);
        $sql = "update project_primary_index set primary_index = launch_date_factor*10 where inventory_factor is null";
        self::connection()->query($sql);
        $sql = "update project_primary_index set primary_index = inventory_factor*10 where launch_date_factor is null";
        self::connection()->query($sql);
    }


    static public function populatePrimaryIndex(){
        self::truncate();
        self::insertProjectIds();
        self::populateLaunchDateFactor();
        self::populateInventoryFactor();
        self::populateFinalIndex();
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
        }
        self::normalizeLaunchDateFactor();
        self::populateFinalIndex();
    }
    
    static private function normalizeLaunchDateFactor(){
        $cityMaxLaunchFactors = self::getCityMaxLaunchDateFactors();
        foreach ($cityMaxLaunchFactors as $cityFactor) {
            $cityId = $cityFactor->city_id;
            $maxLaunchFactor = $cityFactor->launch_date_factor;
            if(!is_null($maxLaunchFactor)){
                if($maxLaunchFactor == 0){
                    $updateStr = '1';
                }
                else{
                    $updateStr = "1 - ppi.launch_date_factor/ $maxLaunchFactor";
                }
                $updateSql = "update project_primary_index ppi inner join resi_project rp on rp.project_id = ppi.id and rp.version = 'Website' inner join locality l on l.locality_id = rp.locality_id inner join suburb s on s.suburb_id = l.suburb_id and s.city_id = $cityId set ppi.launch_date_factor = $updateStr";
                self::connection()->query($updateSql);
            }
        }
    }
    
    static private function getCityMaxLaunchDateFactors() {
        $sql = "select s.city_id, max(ppi.launch_date_factor) launch_date_factor from project_primary_index ppi inner join resi_project rp on rp.project_id = ppi.id and rp.version = 'Website' inner join locality l on l.locality_id = rp.locality_id inner join suburb s on s.suburb_id = l.suburb_id group by s.city_id";
        return self::find_by_sql($sql);
    }

    static private function getProjectData(){
        $count = self::count();
        $url = PROPTIGER_URL . "/data/v1/trend/current?fields=cityId,sumLtdLaunchedUnit,sumInventory,launchDate&group=projectId&rows=$count";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, CALL_TIMEOUT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response)->data;
    }
    
    static private function getCityData(){
        $count = self::count();
        $url = PROPTIGER_URL . "/data/v1/trend/current?fields=maxLaunchDate&group=cityId&rows=$count";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, CALL_TIMEOUT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response)->data;
    }
}
