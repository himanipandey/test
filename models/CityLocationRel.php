<?php

/**
 * @author AKhan
 * @copyright 2013
 */

class CityLocationRel extends ActiveRecord\Model
{
    static $table_name = 'locality';
    static function CityLocArr($cid = '') {
    
        $join = " left join suburb AS sub
        on locality.suburb_id = sub.suburb_id
        left join city AS city
        on sub.city_id = city.city_id";
        $arrCity = array();
        $city_id = '';
        if(!empty($cid))
        {
            $options = array('joins' => $join , 'select' => 'city.city_id' , 'conditions' => "locality.locality_id = '".$cid."'");
            $getCity = CityLocationRel::find('all' , $options);
            foreach($getCity as $key => $val)
            {
                $city_id = $val->city_id;    
            }
            return $city_id;
        }
        else
        {
            $options = array('joins' => $join , 'select' => 
            'CONCAT(city.label,"-",locality.label) AS city_loc , locality.locality_id ');
            
            $getCity = CityLocationRel::find('all' , $options);
            foreach($getCity as $value) {
                $arrCity[$value->locality_id] = $value->city_loc;
            }
            return $arrCity;    
        }
        
        
        
        //print'<pre>';
//        print_r($arrCity);
//        die;

    }
    
    
    
}



?>