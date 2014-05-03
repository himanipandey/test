<?php

class CampaignDids extends ActiveRecord\Model{
    static $table_name = "campaign_dids";
    
    static $validates_uniqueness_of = array (
        array (
            array ("campaign_did","campaign_name")
        )
    );
   
    static function insertUpdate($attributes){
        $campaign = CampaignDids::first(array(
            'campaign_name'=>$attributes['campaign_name'],
            'campaign_did'=>$attributes['campaign_did'],
            'created_at'=>$attributes['created_at'],
            'updated_by'=>$attributes['updated_by']
        ));       
        if(empty($campaign)){
            $res = CampaignDids::create($attributes);
        }else{
            $res = $campaign->update_attributes($attributes);
        }
        return $res;
    }
}
