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
    
    public static function getPriceForIndexing($aPhaseId){
        $condition = "where rpp.version = 'Website' and rpp.status = 'Active' and li.status = 'Active' and lp.version = 'Website' and lp.status = 'Active' and lp.effective_date between '" . MIN_B2B_DATE . "' and '" . MAX_B2B_DATE . "' and rpp.phase_id in (".  implode(',', $aPhaseId).")";
        $sql = "select concat_ws('/', rpp.PHASE_ID, rpo.OPTION_TYPE, rpo.BEDROOMS, lp.effective_date) as unique_key, concat_ws('/', rpp.PHASE_ID, rpo.OPTION_TYPE, rpo.BEDROOMS) as key_without_month, rpp.project_id, rpp.phase_type, FLOOR(avg(rpo.SIZE)*avg(lp.price_per_unit_area)) as AVERAGE_TOTAL_PRICE, lp.effective_date as effective_month, rpp.PHASE_NAME, FLOOR(avg(if(rpo.DISPLAY_CARPET_AREA = 0, lp.price_per_unit_area, lp.price_per_unit_area/1.4))) as average_price_per_unit_area from resi_project_phase rpp inner join listings li on rpp.PHASE_ID = li.phase_id inner join resi_project_options rpo on li.option_id = rpo.OPTIONS_ID and rpo.OPTION_CATEGORY = 'Actual' inner join listing_prices lp on li.id = lp.listing_id $condition group by unique_key order by rpp.PHASE_ID, rpo.OPTION_TYPE, rpo.BEDROOMS, lp.effective_date";
        return self::find_by_sql($sql);
    }
}
