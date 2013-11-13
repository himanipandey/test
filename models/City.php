<?php

// Model integration for city list
class City extends ActiveRecord\Model
{
    static $table_name = 'city';
    static function CityArr() {
        $getCity = City::find('all',array('select'=>'city_id, label','order'=>'label asc'));
        $arrCity = array();
        foreach($getCity as $value) {
            $arrCity[$value->city_id] = $value->label;
        }
        return $arrCity;
    }
    static function getCityById($cityId) {
        $cityDetail = City::find('all',array('conditions'=>array("city_id = $cityId")));
        return $cityDetail;
    }
}