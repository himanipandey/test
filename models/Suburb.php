<?php

// Model integration for bank list
class Suburb extends ActiveRecord\Model
{
    static $table_name = 'suburb';
    static function SuburbArr($cityId) { 
        $suburb = Suburb::find('all',array('conditions'=>array("city_id" => $cityId),'order' => 'label asc'));
        $arrSuburb = array();
        foreach ($suburb  as $value) {
           $arrSuburb[$value->suburb_id] = $value->label;
        }
        return $arrSuburb;
    }
}