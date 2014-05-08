<?php

// Model integration for bank list
class ProjectSecondaryPrice extends ActiveRecord\Model
{
    static $table_name = 'project_secondary_price';
    static $datetime_format = 'Y-m-d H:i:s';
    
    static $validates_uniqueness_of = array (
        array (
            array ("project_id", "broker_id", "unit_type", "effective_date")
        )
    );
    
    static function insertUpdate($attributes){
	  try{	
        $price = ProjectSecondaryPrice::first(array(
            'project_id'=>$attributes['project_id'],
            'broker_id'=>$attributes['broker_id'],
            'unit_type'=>$attributes['unit_type'],
            'effective_date'=>$attributes['effective_date']
        ));
        
        if(empty($price)){
            $res = ProjectSecondaryPrice::create ($attributes);
        }else{
            $res = $price->update_attributes($attributes);
        }
      }catch(Exception $e){
		 
	  }
        return $res;
    }
    
    static function getSecondryPriceUpdatedTypes($projectId){
        $res = self::find_by_sql("select GROUP_CONCAT(distinct UNIT_TYPE) as all_types from " . self::table_name() . " where PROJECT_ID = $projectId");
        $res = $res[0]->all_types;
        return explode(',', $res);
    }
}
