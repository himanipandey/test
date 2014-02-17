<?php

class Aliases extends ActiveRecord\Model

{
    static $table_name = 'aliases';
    
    static function GetAliases() {
        $getAliases = Aliases::find('all',array('select'=>'id, name','order'=>'name asc'));
        //print_r($getNearPlaceTypes);
        $arrAliases = array();
        foreach($getAliases as $value) {

            //echo ($value->id); echo ($value->display_name);
            $arrAliases["$value->id"] = $value->name;
            //echo ($arrNearPlaceTypes["$value->id"]);
            //$arrNearPlaceTypes['i'] = 'you';
            //print_r($arrNearPlaceTypes);

        }
        
        return $arrAliases;

    }
    static function getAliasesById($Id) {
        $arrAliases = Aliases::find('all',array('conditions'=>array("id = $Id")));
       
        return $arrAliases;
    }
    
    
}