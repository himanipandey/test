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
//
$restrictArr = array("locations","addloc","addmorecity","brokercmpnyid","citypkidArr1","remove_citylocids1");
if(isset($_POST['brokercmpnyid']) && !empty($_POST['brokercmpnyid']))
{
    $brokercmpnyid = $_POST['brokercmpnyid'];
    
    $citypkidArr    =   !empty($_POST['citypkidArr1'])?json_decode(base64_decode($_POST['citypkidArr1'])):array();
    $remove_citylocids = !empty($_POST['remove_citylocids1'])?json_decode(base64_decode($_POST['remove_citylocids1'])):array();
        
    $finaladdcitylocids = array_diff(!empty($citypkidArr)?$citypkidArr:array() , !empty($remove_citylocids)?$remove_citylocids:array());
    if(!empty($remove_citylocids))
        foreach($remove_citylocids as $key => $val)
        {
            $bcmpLocation = BrokerCompanyLocation::find_by_id($val);
                
            if(empty($bcmpLocation))
                continue;

            $bcmpLocation->delete();
        }
        
    $citylocids = $finaladdcitylocids;
      
    foreach($_POST as $key => $val)
    {
        if(!in_array($key , $restrictArr))
        {
            $city_id = CityLocationRel::CityLocArr(trim($key));
            $chkExist = BrokerCompanyLocation::find('all' , array('conditions' => "city_id = ".$city_id." AND locality_id = ".mysql_real_escape_string($key)));
            if(empty($chkExist))
            {
                $bcmpLocation = new BrokerCompanyLocation();
            
                $bcmpLocation->table_name = 'brokers';
                $bcmpLocation->table_id = $brokercmpnyid;
                $bcmpLocation->address_line_1 =  trim($val);
                $bcmpLocation->locality_id =  trim($key);
                $bcmpLocation->city_id = !empty($city_id)?$city_id:'';
                $bcmpLocation->updated_at =  date('Y-m-d H:i:s');
                $bcmpLocation->updated_by =  $_SESSION['adminId'];  
                
                $bcmpLocation->save();
        
                if($bcmpLocation->id != false) {
                    $citylocids[] = $bcmpLocation->id; 
                }  
            }
        }
    }
    //$data['citylocids'] = base64_encode(json_encode($citylocids));
    //echo json_encode($data);
    //print_r($data['citylocids']);
    //die;
}
else
{
    foreach($_POST as $key => $val)
    {
        
        if(!in_array($key , $restrictArr))
        {
            $city_id = CityLocationRel::CityLocArr(trim($key));
            
            $chkExist = BrokerCompanyLocation::find('all' , array('conditions' => "city_id = ".$city_id." AND locality_id = ".mysql_real_escape_string($key)));
            
            if(empty($chkExist))
            {
                $bcmpLocation = new BrokerCompanyLocation();
            
                $bcmpLocation->table_name = 'brokers';
                $bcmpLocation->address_line_1 = trim($val);
                $bcmpLocation->locality_id = trim($key);
                $bcmpLocation->city_id = !empty($city_id)?$city_id:'';
                $bcmpLocation->created_at = date('Y-m-d H:i:s');
                $bcmpLocation->updated_by = $_SESSION['adminId'];
                $bcmpLocation->save();
                
                if($bcmpLocation->id != false) {
                    $citylocids[] = $bcmpLocation->id; 
                }    
            }
            
        }
    }
}


if(!empty($citylocids))
{
    $citylocids1 = BrokerCompanyLocation::CityLocIDArr('' ,base64_encode(json_encode($citylocids)));
    $citylocids = array();
    $data = array();
    $i = 0;
    foreach($citylocids1 as $key => $val)
    {
        $data[$i]['pkid'] = $val->pkid;
        $data[$i]['city'] = $val->city;
        $data[$i]['location'] = $val->location;
        $data[$i]['address'] = $val->address_line_1;
        $citylocids[] = $val->pkid;
        $i++;
        
    }
    
    //print'<pre>';
//    print_R($data);
//    die;
    $data['citylocids'] = base64_encode(json_encode($citylocids));
    echo json_encode($data);
}
die;
?>