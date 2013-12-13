<?php

/**
 * @author AKhan
 * @copyright 2013
 */



// Model integration for bank list
class BrokerCompanyLocation extends ActiveRecord\Model
{
    static $table_name = 'addresses';
    
    static function CityLocIDArr($bid = '', $cids = '') {
        
        if(empty($bid) && empty($cids))
            return false;
        
        $join = " LEFT JOIN city ON addresses.city_id = city.city_id
        LEFT JOIN locality AS locality
        ON addresses.locality_id = locality.locality_id";
        $addrArr = array();
        if(!empty($cids))
        {
            $cids = json_decode(base64_decode($cids));
            
            foreach($cids as $key => $val)
            {
                $options = array('joins' => $join , 'select' => 
        'addresses.address_line_1 , addresses.address_line_2 , addresses.pincode , locality.locality_id AS locality_id , city.label AS city , locality.label AS location ,addresses.id AS pkid' , 'conditions' => array(" addresses.table_name = 'brokers' AND addresses.id ='".$val."'"));    
                $getCity = BrokerCompanyLocation::find('all' , $options);   
                $addrArr[] = $getCity[0];
            }
            return $addrArr;
        }
        else
        {
            $brkrComp = BrokerCompany::find('all' , array('select' => 'primary_address_id' , 'conditions' => "brokers.id=".$bid) );
            //echo BrokerCompany::connection()->last_query."<br>";
            $primary_address_id = '';
            //print'<pre>';
//            print_r($brkrComp);
            if(!empty($brkrComp))
            {
                foreach($brkrComp as $key => $val)
                {
                    if($key == "primary_address_id")
                    {
                        $primary_address_id = $val->primary_address_id;
                        break;
                    }
                    
                }
            }
            
            if(!empty($primary_address_id))
                $primary_address_id = " AND addresses.id != $primary_address_id";
            $options = array('joins' => $join , 'select' => 
        'addresses.address_line_1 , addresses.address_line_2 , addresses.pincode , locality.locality_id AS locality_id , city.label AS city , locality.label AS location ,addresses.id AS pkid' , 'conditions' => array(" addresses.table_name = 'brokers' AND addresses.table_id = '".$bid."' $primary_address_id"));
        
        
            $getCity = BrokerCompanyLocation::find('all' , $options);
            
            return $getCity;    
        }
        
        
        //echo BrokerCompanyLocation::connection()->last_query."<br>";
//        
//        print'<pre>';
//        print_r($getCity);
//        die;
//        
        
        

    }
}

?>