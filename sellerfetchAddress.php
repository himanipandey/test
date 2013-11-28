<?php

/**
 * @author AKhan
 * @copyright 2013
 */


include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("modelsConfig.php");
include("includes/configs/configs.php");
//print'<pre>';
//print_r($_POST);
//die;

if(!empty($_POST['broker']))
{
    $broker = mysql_real_escape_string($_POST['broker']);
    $primary_address_query = BrokerCompany::find('all' , array('select' => 'primary_address_id' , 'conditions' => " id=".$broker));
    $primary_address_id = ''; 
    
    if(!empty($primary_address_query))
    {
        foreach($primary_address_query as $key => $val)
        {
            if(!empty($val->primary_address_id))
            {
                $primary_address_id = $val->primary_address_id;
                break;
            }
        }
    }
    
    if(!empty($primary_address_id))
    {
        $addresssDetail = BrokerCompanyLocation::find('all' , array('conditions' => " id = ".$primary_address_id));
        $brokerDetail = BrokerCompanyContact::ContactBroArr($broker);    
    }
    
    
    
    
    $data = array();
    if(!empty($addresssDetail))
    {
        foreach($addresssDetail as $key => $val)
        {
            $data['addressline1'] = $val->address_line_1;
            $data['addressline2'] = $val->address_line_2;
            $data['city_id'] = $val->city_id;
            $data['pincode'] = $val->pincode;
        }
        $data = array_merge($data,$brokerDetail);
    }
    
    echo json_encode($data);
    exit();
    //print'<pre>';
//    print_r($data);
//    die;
}

?>