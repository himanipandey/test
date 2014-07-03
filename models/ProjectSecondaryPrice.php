<?php

// Model integration for bank list
class ProjectSecondaryPrice extends ActiveRecord\Model
{
    static $table_name = 'project_secondary_price';
    static $datetime_format = 'Y-m-d H:i:s';
    
    static $validates_uniqueness_of = array (
        array (
            array ("project_id","phase_id", "broker_id", "unit_type", "effective_date")
        )
    );
    
    static function insertUpdate($attributes,$delete_ids = Array()){
	  try{	
        $price = ProjectSecondaryPrice::first(array(
            'project_id'=>$attributes['project_id'],
            'phase_id'=>$attributes['phase_id'],
            'broker_id'=>$attributes['broker_id'],
            'unit_type'=>$attributes['unit_type'],
            'effective_date'=>$attributes['effective_date']
        ));
      
        if(empty($price)){
            $res = ProjectSecondaryPrice::create ($attributes);
        }else{					
			if(in_array($price->id,$delete_ids)){
			  $res = $price->delete_all(array("conditions"=>array("id=?",$price->id)));
			  $res = 'Deleted';
			}else
              $res = $price->update_attributes($attributes);
        }
      }catch(Exception $e){
		 
	  }
	  ##print $res;
        return $res;
    }
    
    static function getSecondryPriceUpdatedTypes($projectId){
        $res = self::find_by_sql("select GROUP_CONCAT(distinct UNIT_TYPE) as all_types from " . self::table_name() . " where PROJECT_ID = $projectId");
        $res = $res[0]->all_types;
        return explode(',', $res);
    }
}
