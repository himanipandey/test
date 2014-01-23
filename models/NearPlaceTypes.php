<?php

class NearPlaceTypes extends ActiveRecord\Model

{
    static $table_name = 'NEAR_PLACE_TYPES';
    
    static function NearPlaceTypesArr() {
        $getNearPlaceTypes = NearPlaceTypes::find('all',array('select'=>'id, display_name','order'=>'display_name asc'));
        //print_r($getNearPlaceTypes);
        $arrNearPlaceTypes = array();
        foreach($getNearPlaceTypes as $value) {

            //echo ($value->id); echo ($value->display_name);
            $arrNearPlaceTypes["$value->id"] = $value->display_name;
            //echo ($arrNearPlaceTypes["$value->id"]);
            //$arrNearPlaceTypes['i'] = 'you';
            //print_r($arrNearPlaceTypes);

        }
        
        return $arrNearPlaceTypes;

    }
    static function getNearPlaceTypesById($Id) {
        $NearPlaceTypesDetail = NearPlaceTypes::find('all',array('conditions'=>array("id = $Id")));
        return $NearPlaceTypesDetail;
    }
    
}