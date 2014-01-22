<?php

/**
 * @author AKhan
 * @copyright 2013
 */

class CityLocationRel extends ActiveRecord\Model
{
    static $table_name = 'locality';
    static function CityLocArr($cid = '' , $search = '' , $brokercompanyId = '') {
    
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
        else if(!empty($search))
        {
            $options = array('joins' => $join , 'select' => 
            'CONCAT(city.label,"-",locality.label) AS city_loc , locality.locality_id ' , 'conditions' => array("city.label like '%$search%' OR locality.label like '%$search%'"));
            
            $getCity = CityLocationRel::find('all' , $options);
            foreach($getCity as $value) {
                $arrCity[$value->locality_id] = $value->city_loc;
            }
            return $arrCity;    
        }
        else if(!empty($brokercompanyId))
        {
            $options = array('joins' => $join , 'select' => 
            'CONCAT(city.label,"-",locality.label) AS city_loc , city.city_id , locality.locality_id ' , 'conditions' => array("city.label like '%$search%' OR locality.label like '%$search%'"));
            $getCity = CityLocationRel::find('all' , $options);
            
            foreach($getCity as $value) {
                $chkSql = @mysql_query("SELECT city_id, locality_id FROM addresses WHERE table_name = 'brokers' AND table_id = '".mysql_escape_string($brokercompanyId)."' AND city_id = '".$value->city_id."' AND locality_id = '".$value->locality_id."'");
                if(!@mysql_num_rows($chkSql) > 0)
                    $arrCity[$value->locality_id] = $value->city_loc;
            }
            //print'<pre>';
            //print_r($arrCity);
            //print_r($getCity);
            //die;
            return $arrCity;    
        }
        
        
        
       

    }
    
}



?>