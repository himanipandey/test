<?php

class CampaignDids extends ActiveRecord\Model{
    static $table_name = "campaign_dids";
    
    static $validates_uniqueness_of = array (
        array (
            array ("campaign_did")
        )
    ); 
    static function allCampaign(){
	  $all_campaign = CampaignDids::all();
	  $arrCampaign = array();
	  foreach($all_campaign as $key=>$val){
		$arrCampaign[] = $val->campaign_name; 
	  }		
	  return $arrCampaign;
	}  
	static function getCampaignName($campDid){
	  $campaignName = CampaignDids::find('all',array('conditions'=>array('campaign_did'=>$campDid),'select'=>'campaign_name'));		
	  return $campaignName[0]->campaign_name;
	}
	static function getCampaignDid($campName){
	  $campdid = CampaignDids::find('all',array('conditions'=>array('campaign_name'=>$campName),'select'=>'campaign_did'));		
	  return $campdid[0]->campaign_did;
	}
}
