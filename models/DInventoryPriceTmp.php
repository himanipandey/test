<?php
require_once dirname(__FILE__).'/../cron/cronFunctions.php';

// Model integration for resi_project
use ActiveRecord\Model;
class DInventoryPriceTmp extends Model
{
    static $table_name = 'd_inventory_prices_tmp';
    
    public static function updateFirstPromoisedCompletionDate(){
        self::connection()->query("update " . self::table_name() . " dipt inner join (select rpp.PHASE_ID, substring(substring_index(group_concat(rpec.EXPECTED_COMPLETION_DATE order by EXPECTED_COMPLETION_ID ASC), ',', 1), 1, 10) first_promised_completion_date from resi_project_phase rpp inner join resi_proj_expected_completion rpec on rpp.PHASE_ID = rpec.phase_id group by rpp.PHASE_ID) t on dipt.PHASE_ID = t.PHASE_ID set dipt.first_promised_completion_date = t.first_promised_completion_date");
        self::update_all(array('set'=>'first_promised_completion_date = completion_date', 'conditions'=>'first_promised_completion_date is null'));
        self::update_all(array('set'=>'first_promised_completion_date = completion_date', 'conditions'=>'first_promised_completion_date > completion_date'));
        self::update_all(array('set'=>"completion_delay = period_diff(date_format(completion_date, '%Y%m'), date_format(first_promised_completion_date, '%Y%m'))"));
    }
    
    public static function deleteEntriesBeforeLaunch(){
        self::delete_all(array('conditions'=>'effective_month < launch_date and launch_date is not null'));
    }
    
    public static function deleteInvalidPriceEntries(){
        self::update_all(array('set'=>'average_price_per_unit_area = null, average_total_price = null', 'conditions'=>'inventory = 0'));
    }
    
    public static function populateDemand(){
        self::populateProjectDemand();
        self::populateLocalityDemand();
        self::update_all(array('set'=>'demand = customer_demand + investor_demand'));
    }
    
    public static function populateProjectDemand(){
        $allLeadSql = "select concat_ws('/', l.LEAD_ID, date_format(min(lp.CREATED_DATE), '%Y-%m')) lead_key, lp.PROJECT_ID, l.PROJECT_TYPE UNIT_TYPE, if(l.PROJECT_TYPE = 'plot', 0, group_concat(distinct l.BEDROOMS)) all_bedrooms, date_format(min(lp.CREATED_DATE), '%Y-%m-01') EFFECTIVE_MONTH, l.CLIENT_TYPE from ptigercrm.LEADS l inner join ptigercrm.LEAD_PROJECTS lp on l.LEAD_ID = lp.LEAD_ID where l.PROJECT_TYPE is not null and l.PROJECT_TYPE <> '' and l.CLIENT_TYPE is not null and l.CLIENT_TYPE <> '' and lp.CREATED_DATE >= '" . B2B_DEMAND_START_DATE . " 00:00:00' and lp.PROJECT_ID between 500000 and 999999 and lp.ACTIVE = '1' group by l.LEAD_ID, lp.PROJECT_ID order by lead_key";
        $aAllLead = self::find_by_sql($allLeadSql);
        $aAllLead = groupOnKey($aAllLead, 'lead_key');
        
        $aAllBedRoomCount = self::getMonthWiseBedroomCountForAllProjects();
        $aAllPhaseCount = self::getMonthWisePhaseCountForAllProjects();
        
        foreach ($aAllLead as $aAllProjectLead){
            $leadProjectCount = count($aAllProjectLead);
            foreach ($aAllProjectLead as $projectLead){
                $key = implode("/", array($projectLead->project_id, ucfirst($projectLead->unit_type), substr($projectLead->effective_month, 0, 10)));
                
                $bedrooms = isset($aAllBedRoomCount[$key])? $aAllBedRoomCount[$key] : NULL;
                if(!empty($bedrooms)){
                    $bedrooms = $bedrooms->all_bedrooms;
                    $leadBedrooms = array_intersect(explode(",", $projectLead->all_bedrooms), explode(",", $bedrooms));
                    $bedroomCount = count($leadBedrooms);
                    foreach ($leadBedrooms as $bedroom) {
                        $phaseCount = $aAllPhaseCount[implode("/", array($projectLead->project_id, ucfirst($projectLead->unit_type), $bedroom, substr($projectLead->effective_month, 0, 10)))]->phase_count;
                        if($projectLead->client_type === 'buyer'){
                            $updateStr = "customer_demand = (customer_demand+(1/($leadProjectCount*$bedroomCount*$phaseCount)))";
                        }
                        elseif($projectLead->client_type === 'investor'){
                            $updateStr = "investor_demand = (investor_demand+(1/($leadProjectCount*$bedroomCount*$phaseCount)))";
                        }
                        self::update_all(array('set'=>$updateStr, 'conditions'=>array('project_id'=>$projectLead->project_id, 'unit_type'=>$projectLead->unit_type, 'bedrooms'=>  $bedroom, 'effective_month'=>$projectLead->effective_month)));
                    }
                }
            }
        }
    }
    
    public static function populateLocalityDemand(){
        $allLeadSql = "select concat_ws('/', l.LEAD_ID, date_format(min(lp.CREATED_DATE), '%Y-%m')) lead_key, lp.LOCALITY_ID, l.PROJECT_TYPE UNIT_TYPE, if(l.PROJECT_TYPE = 'plot', 0, group_concat(distinct l.BEDROOMS)) all_bedrooms, date_format(min(lp.CREATED_DATE), '%Y-%m-01') EFFECTIVE_MONTH, l.CLIENT_TYPE, lp.PROJECT_ID from ptigercrm.LEADS l inner join ptigercrm.LEAD_PROJECTS lp on l.LEAD_ID = lp.LEAD_ID where l.CLIENT_TYPE is not null and l.CLIENT_TYPE <> '' and lp.CREATED_DATE > '" . B2B_DEMAND_START_DATE . " 00:00:00' and lp.LOCALITY_ID between 50000 and 99999 and lp.ACTIVE = '1' group by l.LEAD_ID, lp.LOCALITY_ID having count(distinct PROJECT_ID) = 1 and (PROJECT_ID is null or PROJECT_ID = 0) order by lead_key";
        $aAllLead = self::find_by_sql($allLeadSql);
        $aAllLead = groupOnKey($aAllLead, 'lead_key');
        
        foreach ($aAllLead as $aAllLocalityLead){
            $leadLocalityCount = count($aAllLocalityLead);
            foreach ($aAllLocalityLead as $localityLead){
                $conditions = array('locality_id'=>$localityLead->locality_id, 'unit_type'=>$localityLead->unit_type, 'effective_month'=>$localityLead->effective_month, 'bedrooms'=>  explode(",", $localityLead->all_bedrooms));
                
                if($localityLead->client_type === 'buyer'){
                    $demandType = 'customer_demand';
                }
                elseif($localityLead->client_type === 'investor'){
                    $demandType = 'investor_demand';
                }
                
                $entries = self::getDemandWeightBasedOnCond($conditions, $demandType);
                foreach ($entries as $id => $weight) {
                    $updateStr = "$demandType=$demandType+($weight/$leadLocalityCount)";
                    self::update_all(array('set'=>$updateStr, 'conditions'=>array('id'=>$id)));
                }
            }
        }
    }
    
    public static function getDemandWeightBasedOnCond($aCondition, $demandType = 'demand'){
        $selectStr = "id, " . $demandType . " demand";
        
        $aData = self::find('all', array('select'=>$selectStr, 'conditions'=>$aCondition));
        $sum = getSumOfKeyValues($aData, 'demand');
        
        $result = array();
        if($sum == 0){
            foreach ($aData as $data) {
                $result[$data->id] = 1;
            }
        }else{
            foreach ($aData as $data) {
                $result[$data->id] = $data->demand/$sum;
            }
        }
        return $result;
    }

    public static function getMonthWiseBedroomCountForAllProjects(){
        $aData = self::find('all', array('select'=>"concat_ws('/', project_id, unit_type, effective_month) unique_key, project_id, unit_type, effective_month, group_concat(distinct bedrooms) all_bedrooms, count(distinct bedrooms) bedroom_count", 'group'=>'project_id, unit_type, effective_month'));
        return indexArrayOnKey($aData, 'unique_key');
    }
    
    public static function getMonthWisePhaseCountForAllProjects(){
        $aData = self::find('all', array('select'=>"concat_ws('/', project_id, unit_type, bedrooms, effective_month) unique_key, project_id, unit_type, effective_month, count(distinct phase_id) phase_count", 'group'=>'project_id, unit_type, bedrooms, effective_month'));
        return indexArrayOnKey($aData, 'unique_key');
    }
    
    public static function updateSecondaryPriceForAllProjects(){
        $sql = "update " . self::table_name() . " dip inner join (select PHASE_ID, UNIT_TYPE, DATE_FORMAT(EFFECTIVE_DATE, '%Y-%m-01') DATE, avg((MIN_PRICE+MAX_PRICE)/2) AVG_PRICE from project_secondary_price group by PROJECT_ID, PHASE_ID, UNIT_TYPE, DATE_FORMAT(EFFECTIVE_DATE, '%Y-%m-01')) t on dip.phase_id = t.phase_id and dip.unit_type = t.UNIT_TYPE and dip.effective_month = t.DATE set dip.average_secondary_price_per_unit_area = t.AVG_PRICE";
        self::connection()->query($sql);
    }
}