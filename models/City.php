<?php

// Model integration for city list
class City extends ActiveRecord\Model
{

    static $table_name = 'city';
    static function CityArr($BranchLoc = '') {
        $cityNames = array();
        if(!empty($BranchLoc))
        {
            //print'<pre>';
//            print_r($BranchLoc);
//            die;
            foreach($BranchLoc as $key => $val)
            {
                $cityNames[] = $val;
            }  
        }
        $conditions = array('label IN (?)', $cityNames);
        $getCity = City::find('all',array('select'=>'city_id, label','order'=>'label asc' , 'conditions' => $conditions));
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