<?php

/**
 * @author AKhan
 * @copyright 2013
 */


// Model integration for bank list
class ContactNumber extends ActiveRecord\Model
{
    static $table_name = 'contact_numbers';
    
    static function ContactNumArr($bid = '') {
        
        if(empty($bid))
            return false;
            
        $sql = "SELECT * FROM broker_contacts WHERE broker_id = '".$bid."' AND name != 'Headquarter' AND name != 'Customer Care' AND type = 'NAgent'";
        
        $brokerContacts = ContactNumber::find_by_sql($sql);
        $contactId = array();
        $contactIdArr = array();
        $contactNumbers = array();
        if(!empty($brokerContacts))
        {
            $i = 0;
            foreach($brokerContacts as $key => $val)
            {
                $contactNumbers[$i]['id'] = $val->id;
                $contactNumbers[$i]['name'] = $val->name;
                $contactNumbers[$i]['email'] = $val->contact_email;
                $contactId = ContactNumber::find('all' , array('conditions' => "table_id=$val->id AND table_name = 'broker_contacts'" ));
                if(!empty($contactId))
                {
                    foreach($contactId as $k => $v)
                    {
                        //$contactIdArr[$v->type] = $v->contact_no;
                        $contactNumbers[$i][$v->type] = $v->contact_no;
                    }
                }
                $contactIdArr[] = $val->id;
                $i++;
            }
        }
        $contactNumbers['ids'] = $contactIdArr;
        //print'<pre>';
//        print_r($contactNumbers);
//        die;
        return $contactNumbers;
            
    }
    
    static function ContactBroArr($bid = '') {
        
        if(empty($bid))
            return false;
            
        $sql = "SELECT * FROM broker_contacts WHERE broker_id = '".$bid."' AND name = 'Headquarter' AND type = 'NAgent'";
        
        $brokerContacts = ContactNumber::find_by_sql($sql);
        $contactId = array();
        $contactNumbers = array();
        if(!empty($brokerContacts))
        {
            foreach($brokerContacts as $key => $val)
            {
                $contactNumbers['id'] = $val->id;
                $contactNumbers['name'] = $val->name;
                $contactNumbers['email'] = $val->contact_email;
                $contactId = ContactNumber::find('all' , array('conditions' => "table_id=$val->id AND table_name = 'broker_contacts'" ));
                if(!empty($contactId))
                {
                    foreach($contactId as $k => $v)
                    {
                        $contactNumbers[$v->type] = $v->contact_no;
                    }
                }
            }
        }
        
        //print'<pre>';
//        print_r($contactNumbers);
//        die;
        return $contactNumbers;
            
    }
    
    static function ContactHQArr($bid = '') {
        
        if(empty($bid))
            return false;
            
        $sql = "SELECT * FROM broker_contacts WHERE broker_id = '".$bid."' AND (name = 'Headquarter' OR name = 'Customer Care') AND type = 'NAgent'";
        
        $brokerContacts = ContactNumber::find_by_sql($sql);
        $contactId = array();
        $contactNumbers = array();
        if(!empty($brokerContacts))
        {
            $i = 0;
            foreach($brokerContacts as $key => $val)
            {
                $contactNumbers[$i]['id'] = $val->id;
                $contactNumbers[$i]['name'] = $val->name;
                $contactNumbers[$i]['email'] = $val->contact_email;
                $contactId = ContactNumber::find('all' , array('conditions' => "table_id=$val->id AND table_name = 'broker_contacts'" ));
                if(!empty($contactId))
                {
                    foreach($contactId as $k => $v)
                    {
                        $contactNumbers[$i][$v->type] = $v->contact_no;
                    }
                }
                $i++;
            }
        }
        //print'<pre>';
//        print_r($contactNumbers);
//        die;
        return $contactNumbers;
            
    }
    
    static function ContactCCTypeArr($bid = '') {
        
        if(empty($bid))
            return false;
            
        $sql = "SELECT * FROM broker_contacts WHERE broker_id = '".$bid."' AND name = 'Customer Care' AND type = 'NAgent'";
        
        $brokerContacts = ContactNumber::find_by_sql($sql);
        $contactId = array();
        $contactNumbers = array();
        if(!empty($brokerContacts))
        {
            $i = 0;
            foreach($brokerContacts as $key => $val)
            {
                $contactNumbers[$i]['id'] = $val->id;
                $contactNumbers[$i]['name'] = $val->name;
                $contactNumbers[$i]['email'] = $val->contact_email;
                $contactId = ContactNumber::find('all' , array('conditions' => "table_id=$val->id AND table_name = 'broker_contacts'" ));
                if(!empty($contactId))
                {
                    foreach($contactId as $k => $v)
                    {
                        $contactNumbers[$i][$v->type] = $v->contact_no;
                    }
                }
                $i++;
            }
        }
        //print'<pre>';
//        print_r($contactNumbers);
//        die;
        return $contactNumbers;
            
    }
    
    static function ContactHQTypeArr($bid = '') {
        
        if(empty($bid))
            return false;
            
        $sql = "SELECT * FROM broker_contacts WHERE broker_id = '".$bid."' AND name = 'Headquarter' AND type = 'NAgent'";
        
        $brokerContacts = ContactNumber::find_by_sql($sql);
        $contactId = array();
        $contactNumbers = array();
        if(!empty($brokerContacts))
        {
            $i = 0;
            foreach($brokerContacts as $key => $val)
            {
                $contactNumbers[$i]['id'] = $val->id;
                $contactNumbers[$i]['name'] = $val->name;
                $contactNumbers[$i]['email'] = $val->contact_email;
                $contactId = ContactNumber::find('all' , array('conditions' => "table_id=$val->id AND table_name = 'broker_contacts'" ));
                if(!empty($contactId))
                {
                    foreach($contactId as $k => $v)
                    {
                        $contactNumbers[$i][$v->type] = $v->contact_no;
                    }
                }
                $i++;
            }
        }
        //print'<pre>';
//        print_r($contactNumbers);
//        die;
        return $contactNumbers;
            
    }
    
    static function ContactIDTypeArr($bid = '') {
        
        if(empty($bid))
            return false;
            
        $sql = "SELECT * FROM broker_contacts WHERE broker_id = '".$bid."' AND name != 'Headquarter' AND name != 'Customer Care' AND type = 'NAgent'";
        
        $brokerContacts = ContactNumber::find_by_sql($sql);
       
        $contactId = array();
        $contactNumbers = array();
        if(!empty($brokerContacts))
        {
            
            foreach($brokerContacts as $key => $val)
            {
                $contactId = ContactNumber::find('all' , array('conditions' => "table_id=$val->id AND table_name = 'broker_contacts'" ));
                if(!empty($contactId))
                {
                    $i = 0;
                    foreach($contactId as $k => $v)
                    {
                        $contactNumbers[$i]['id'] = $v->id;
                        $contactNumbers[$i]['type'] = $v->type;
                        $i++;
                    }
                }
                
            }
        }
        //print'<pre>';
//        print_r($contactNumbers);
//        die;
        return $contactNumbers;
            
    }
    
    static function ContactHQIDTypeArr($bid = '') {
        
        if(empty($bid))
            return false;
            
        $sql = "SELECT * FROM broker_contacts WHERE broker_id = '".$bid."' AND name = 'Headquarter' AND type = 'NAgent'";
        
        $brokerContacts = ContactNumber::find_by_sql($sql);
       
        $contactId = array();
        $contactNumbers = array();
        if(!empty($brokerContacts))
        {
            
            foreach($brokerContacts as $key => $val)
            {
                $contactId = ContactNumber::find('all' , array('conditions' => "table_id=$val->id AND table_name = 'broker_contacts'" ));
                if(!empty($contactId))
                {
                    $i = 0;
                    foreach($contactId as $k => $v)
                    {
                        $contactNumbers[$i]['id'] = $v->id;
                        $contactNumbers[$i]['type'] = $v->type;
                        $i++;
                    }
                }
                
            }
        }
        //print'<pre>';
//        print_r($contactNumbers);
//        die;
        return $contactNumbers;
            
    }
}

?>