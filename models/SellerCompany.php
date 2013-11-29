<?php

/**
 * @author AKhan
 * @copyright 2013
 */



// Model integration for bank list
class SellerCompany extends ActiveRecord\Model
{
    static $table_name = 'agents';
    
    static function getQualification()
    {
        $sql = @mysql_query("SELECT * FROM `academic_qualifications`");
        $data = array();
        while($row = @mysql_fetch_assoc($sql))
            $data[] = $row;
        
        return $data;
    }
    
    static function chkName($seller_name = '')
    {
        if(empty($seller_name))
            return false;
            
        $sql = @mysql_query("SELECT broker_id AS id ,name FROM `broker_contacts` WHERE name = '".mysql_real_escape_string($seller_name)."'");
        $data = array();
        if(@mysql_num_rows($sql) > 0)
        {
            while($row = @mysql_fetch_assoc($sql))
                $data[] = $row;
        }
            
        
        return $data;
    }
    
    static function getByid($sid = '')
    {
        if(empty($sid))
            return false;
        
        
        $sql = @mysql_query("SELECT agents.* , brokers.broker_name ,b_c.id AS brkr_cntct_id, 
                                    b_c.name , b_c.contact_email , 
                                    adr.id AS addressid,
                                    adr.address_line_1,
                                    adr.address_line_2,
                                    adr.city_id,adr.pincode,aq.qualification 
                                    FROM `agents` 
                                    LEFT JOIN brokers ON agents.broker_id = brokers.id 
                                    LEFT JOIN broker_contacts AS b_c ON agents.id = b_c.broker_id 
                                    LEFT JOIN addresses AS adr ON agents.id = adr.table_id 
                                    LEFT JOIN academic_qualifications AS aq ON agents.academic_qualification_id = aq.id
                                    WHERE b_c.type = 'Agent' AND adr.table_name = 'agents' AND agents.id = ".$sid);
                                    
        
        
        $data = array();
        $contacts = array();
        $tcontacts = array();
        if(@mysql_num_rows($sql) > 0)
        {
            while($row = @mysql_fetch_assoc($sql))
            {
                $sql2 = @mysql_query("SELECT * FROM contact_numbers AS cn WHERE cn.table_name = 'broker_contacts' AND cn.table_id = '".$row['brkr_cntct_id']."'");
                
                if(@mysql_num_rows($sql2) > 0)
                {
                    $i = 0;
                    while($row1 = @mysql_fetch_assoc($sql2))
                        $contacts[$row1['type']] = $row1['contact_no'];
                } 
                
                $data = $row;
            }
        }
        
        $data = array_merge($data , $contacts);
        //print'<pre>';
//        print_r($data);
//        print_r($contacts);
//        die;  
        
        return $data;
    }
}

?>