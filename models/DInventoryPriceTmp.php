<?php

// Model integration for resi_project
use ActiveRecord\Model;
class DInventoryPriceTmp extends Model
{
    static $table_name = 'd_inventory_prices_tmp';
    
    public static function updateFirstPromoisedCompletionDate(){
        self::connection()->query("update d_inventory_prices_tmp dipt inner join (select rpp.PHASE_ID, substring(substring_index(group_concat(rpec.EXPECTED_COMPLETION_DATE order by EXPECTED_COMPLETION_ID ASC), ',', 1), 1, 10) first_promised_completion_date from resi_project_phase rpp inner join resi_proj_expected_completion rpec on rpp.PHASE_ID = rpec.phase_id group by rpp.PHASE_ID) t on dipt.PHASE_ID = t.PHASE_ID set dipt.first_promised_completion_date = t.first_promised_completion_date");
        self::update_all(array('set'=>'first_promised_completion_date = completion_date', 'conditions'=>'first_promised_completion_date is null'));
        self::update_all(array('set'=>'first_promised_completion_date = completion_date', 'conditions'=>'first_promised_completion_date > completion_date'));
        self::update_all(array('set'=>"completion_delay = period_diff(date_format(completion_date, '%Y%m'), date_format(first_promised_completion_date, '%Y%m'))"));
    }
    
    public static function deleteEntriesBeforeLaunch(){
        self::delete_all(array('conditions'=>'effective_month < launch_date and launch_date is not null'));
    }
    
    public function deleteInvalidPriceEntries(){
        self::update_all(array('set'=>'average_price_per_unit_area = null, average_total_price = null', 'conditions'=>'inventory = 0'));
    }
    
    public static function populateDemand(){
        DInventoryPriceTmp::update_all(array('set'=>'customer_demand = ceil(rand()*10), investor_demand = ceil(rand()*10), demand = customer_demand+investor_demand'));
}
}