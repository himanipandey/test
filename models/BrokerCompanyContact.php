<?php

/**
 * @author AKhan
 * @copyright 2013
 */



// Model integration for bank list
class BrokerCompanyContact extends ActiveRecord\Model
{
    static $table_name = 'broker_contacts';
    
    static function ContactArr($bid = '') {
        $options = '';
        $join = '';        
        
        if(empty($bid))
            return false;
        
        $sql = "SELECT adr.table_id,adr.address_line_1, adr.address_line_2,adr.pincode,adr.city_id,adr.locality_id FROM addresses AS adr INNER JOIN brokers AS b WHERE adr.id = b.primary_address_id AND adr.table_name = 'brokers' AND adr.table_id = '".$bid."'";
        //$getHeadContact = BrokerCompanyContact::find_by_sql($sql);        
//        
//        $sql = "SELECT adr.table_id,adr.address_line_1, adr.address_line_2,adr.pincode,adr.city_id,adr.locality_id FROM addresses AS adr,brokers AS b WHERE b.id = '".$bid."' AND adr.table_name = 'brokers' AND adr.table_id = '".$bid."'";
//
        $getContact = BrokerCompanyContact::find_by_sql($sql);
        //echo BrokerCompanyContact::connection()->last_query;
         //print'<pre>';
        //print_r($contactArr);
        //print_r($getContact);
        //print_r($contactHqArr);
        //die;
        $contactArr = ContactNumber::ContactNumArr($bid);
        $contactHqArr = ContactNumber::ContactHQArr($bid);
        
        
        $contacts = array(); 
        if(!empty($getContact))
        {
            foreach($getContact as $key => $val)
            {
                $contacts['id'] = $val->table_id;
                $contacts['addressline1'] = !empty($val->address_line_1)?$val->address_line_1:'';    
                $contacts['addressline2'] = !empty($val->address_line_2)?$val->address_line_2:'';
                $contacts['city_id'] = !empty($val->city_id)?$val->city_id:'';
                $contacts['locality_id'] = !empty($val->locality_id)?$val->locality_id:'';      
                $contacts['pincode'] = !empty($val->pincode)?$val->pincode:'';
            }
        }
        $contacts['contactids'] = !empty($contactArr['ids'])?$contactArr['ids']:'';
        
        if(!empty($contactHqArr))
        {
            foreach($contactHqArr as $key =>$val)
            {
                if($val['name'] == "Headquarter")
                {
                    $contacts['email'] = !empty($val['email'])?$val['email']:'';
                    $contacts['phone1'] = !empty($val['phone1'])?$val['phone1']:'';
                    $contacts['phone2'] = !empty($val['phone2'])?$val['phone2']:'';
                    $contacts['mobile'] = !empty($val['mobile'])?$val['mobile']:'';
                    $contacts['fax'] = !empty($val['fax'])?$val['fax']:'';
                }
                       
                
                if($val['name'] == "Customer Care")
                {
                    $contacts['cc_email'] = !empty($val['email'])?$val['email']:'';
                    $contacts['cc_phone'] = !empty($val['cc_phone'])?$val['cc_phone']:'';
                    $contacts['cc_mobile'] = !empty($val['cc_mobile'])?$val['cc_mobile']:'';
                    $contacts['cc_fax'] = !empty($val['cc_fax'])?$val['cc_fax']:'';
                }
                    
            }
        }
        
        if(!empty($contactArr['ids']))        
            unset($contactArr['ids']);
                   
        $contacts['contacts'] = !empty($contactArr)?$contactArr:'';
        //print'<pre>';
        //print_r($contacts);
        //print_r($contactHqArr);
        //die;

        
        
        return $contacts;
        

    }
    
    static function ContactIDTypeArr($bid = '') {
        
        if(empty($bid))
            return false;
        
        $contactHqArr = ContactNumber::ContactIDTypeArr($bid);
        
        return $contactHqArr;
    }
    
    static function ContactHQIDTypeArr($bid = '') {
        
        if(empty($bid))
            return false;
        
        $contactHqArr = ContactNumber::ContactHQIDTypeArr($bid);
        
        return $contactHqArr;
    }
    
    static function ContactBroArr($bid = '') {
        
        if(empty($bid))
            return false;
        
        $contactHqArr = ContactNumber::ContactBroArr($bid);
        
        return $contactHqArr;
    }
    
    static function ContactBrkIDFrmCnt($cid = '') {
        
        if(empty($cid))
            return false;
        
        
        $contactId = ContactNumber::find('all' , array('conditions' => "table_id=$cid AND table_name = 'broker_contacts'" ));
        //echo ContactNumber::connection()->last_query;
//        die;        
        if(!empty($contactId))
        {
            $i = 0;
            foreach($contactId as $k => $v)
            {
                $brkid[$i]['id'] = $v->id;
                $brkid[$i]['type'] = $v->type;
                $i++;
            }
        }
        
        //print'<pre>';
//        print_r($brkid);
//        die;
        return $brkid;
    }
}

?>