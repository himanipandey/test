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
        return $result === array_filter($result);
    }
}
