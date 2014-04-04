<?php

// Model integration for listing list
class ListingPrices extends ActiveRecord\Model
{
    static $table_name = 'listing_prices';
    
    public static function copyProjectPriceToWebsite($projectId, $updatedBy){
        $result = array();
        $all_prices = self::find('all', array('joins'=>'inner join listings l on listing_prices.listing_id = l.id inner join resi_project_options rpo on l.option_id = rpo.OPTIONS_ID and rpo.PROJECT_ID = '.$projectId));
        
        $indexed_price_data = array();
        foreach ($all_prices as $price) {
            $date = substr($price->effective_date, 0,10);
            $indexed_price_data[$price->version][$price->listing_id.$date] = $price;
        }
        
        if(isset($indexed_price_data['Cms'])){
            foreach ($indexed_price_data['Cms'] as $key => $cms_price) {
                $cms_price = $cms_price->to_array();
                unset($cms_price['id']);
                $cms_price['version'] = 'Website';
                $cms_price['updated_by'] = $updatedBy;
                if(isset($indexed_price_data['Website'][$key])){
                    $website_price = $indexed_price_data['Website'][$key];
                    $website_price->update_attributes($cms_price);
                    $result[] = $website_price->save();
                }
                else{
                    $cms_price['created_at'] = 'NOW()';
                    $result[] = self::create($cms_price);
                }
            }
        }
        return $result === array_filter($result);
    }
    
    public static function getPriceForIndexing($aProjectId){
        $condition = "where rp.version = 'Website' and lp.effective_date between '" . MIN_B2B_DATE . "' and '" . MAX_B2B_DATE . "' and rp.PROJECT_ID in (".  implode(',', $aProjectId).")";
        $sql = "select concat_ws('/', rp.PROJECT_Id, rpp.PHASE_ID, rpo.OPTION_TYPE, rpo.BEDROOMS, lp.effective_date) as unique_key, concat_ws('/', rp.PROJECT_Id, rpp.PHASE_ID, rpo.OPTION_TYPE, rpo.BEDROOMS) as key_without_month, rp.PROJECT_ID, rpp.PHASE_ID, rpo.OPTION_TYPE as UNIT_TYPE, rpo.BEDROOMS, FLOOR(avg(rpo.SIZE)) as AVERAGE_SIZE, FLOOR(avg(rpo.SIZE)*avg(lp.price_per_unit_area)) as AVERAGE_TOTAL_PRICE, lp.effective_date as effective_month, rpp.PHASE_NAME, date_format(if(rpp.COMPLETION_DATE=0, NULL, rpp.COMPLETION_DATE), '%Y-%m-01') COMPLETION_DATE, date_format(" . ResiProjectPhase::$custom_launch_date_string . ", '%Y-%m-01') LAUNCH_DATE, rpp.PHASE_TYPE, FLOOR(avg(if(rpo.DISPLAY_CARPET_AREA = 0, lp.price_per_unit_area, lp.price_per_unit_area/1.4))) as average_price_per_unit_area from resi_project rp inner join resi_project_phase rpp on rp.PROJECT_ID = rpp.PROJECT_ID and rpp.version = 'Website' and rpp.status = 'Active' inner join listings li on rpp.PHASE_ID = li.phase_id and li.status = 'Active' inner join resi_project_options rpo on li.option_id = rpo.OPTIONS_ID and rpo.OPTION_CATEGORY = 'Actual' inner join listing_prices lp on li.id = lp.listing_id and lp.version = 'Website' and lp.status = 'Active' $condition group by rp.PROJECT_Id, rpp.PHASE_ID, rpo.OPTION_TYPE, rpo.bedrooms, lp.effective_date order by rp.PROJECT_Id, rpp.PHASE_ID, rpo.OPTION_TYPE, rpo.BEDROOMS, lp.effective_date";
        return self::find_by_sql($sql);
    }
}
