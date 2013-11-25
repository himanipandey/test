<?php

/**
 * @author AKhan
 * @copyright 2013
 */



// Model integration for bank list
class BrokerCompany extends ActiveRecord\Model
{
    static $table_name = 'brokers';
    
    static function chkName($bname = '') {
        
        if(empty($bname))
            return false;
        
        $sql = "SELECT * FROM brokers WHERE broker_name = '".$bname."'";
        
        $chkExist = BrokerCompany::find_by_sql($sql);
        
        return $chkExist;        
    }
    
    static function getById($bid = '') {
        
        if(empty($bid))
            return false;
        
        $brkrDet = BrokerCompany::find('all' , array('conditions' => 'id='.$_GET['brokerCompanyId']));
        //print'<pre>';
//        print_r($brkrDet);
//        die;
        $brDet = array();
        if(!empty($brkrDet))
        {
            foreach($brkrDet as $key => $val)
            {
                $brDet['id'] = !empty($val->id)?$val->id:'';
                $brDet['name'] = !empty($val->broker_name)?$val->broker_name:'';
                $brDet['status'] = !empty($val->status)?$val->status:'';
                $brDet['description'] = !empty($val->description)?$val->description:'';
                $brDet['pan'] = !empty($val->pan)?$val->pan:'';
                
                if(!empty($val->active_since))
                {
                    foreach($val->active_since as $k => $v)
                    {
                        $brDet['active_since'] = !empty($v)?date('d/m/Y' , strtotime($v)):'';
                        break;
                    }
                }
                
                //print'<pre>';
//                print_r($val->active_since);
                //die;
                $brDet['primary_address_id'] = !empty($val->primary_address_id)?$val->primary_address_id:'';
                $brDet['fax_number_id'] = !empty($val->fax_number_id)?$val->fax_number_id:'';
                $brDet['primary_email'] = !empty($val->primary_email)?$val->primary_email:'';
                $brDet['primary_broker_contact_id'] = !empty($val->primary_broker_contact_id)?$val->primary_broker_contact_id:'';
                $brDet['primary_contact_number_id'] = !empty($val->primary_contact_number_id)?$val->primary_contact_number_id:'';
            }
        }
        //print'<pre>';
//        print_r($brDet);
//        die;
        return $brDet;        
    }
}

?>