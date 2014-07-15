<?php

// Model integration for Company list
class BrokerAgent extends ActiveRecord\Model
{
    static $table_name = 'agents';
    //$result = array();
    static function getBrokerAgentsById($agentId) {
        $agentsDetail = BrokerAgent::find('all',array('conditions'=>array("id = $agentId")));
        return $agentsDetail;
    }

    static function getAllBrokerAgents($agentId=0) {
        if($agentId>0){
            $agentsDetail = BrokerAgent::find('all', array('conditions'=>array("id = $agentId")));
        }
        else{
            $agentsDetail = BrokerAgent::find('all');
        }
        
        /*
        $returnArr = array();
        foreach ($companyDetail as $v) {
            $sql = "SELECT address_line_1, city_id, pincode FROM addresses WHERE (table_name='company' and table_id={$v->id})";
            $result = self::Connection()->query($sql);
            $address_row = $result->fetch(PDO::FETCH_NUM);

            $sql = "SELECT LABEL FROM city WHERE  CITY_ID='{$address_row[1]}'";
            $result = self::Connection()->query($sql);
            $city = $result->fetch(PDO::FETCH_NUM);

            $sql = "SELECT ip FROM company_ips WHERE  company_id='{$v->id}'";
            $result = self::Connection()->query($sql);
            $ips = array();
            while($data = $result->fetch(PDO::FETCH_NUM)){
                array_push($ips, $data[0]);
            };
            

            $sql = "SELECT id, name, contact_email FROM broker_contacts WHERE (broker_id={$v->id} and type='NAgent')";
            $result = self::Connection()->query($sql);
            $agent_row = $result->fetch(PDO::FETCH_NUM);

            $sql = "SELECT contry_code, contact_no FROM contact_numbers WHERE (table_name='broker_contacts' and table_id='{$agent_row[0]}' and type='fax')";
            $result = self::Connection()->query($sql);
            $fax= $result->fetch(PDO::FETCH_NUM);

            $sql = "SELECT contry_code, contact_no FROM contact_numbers WHERE (table_name='company' and table_id='{$v->id}' and type='cc_phone')";
            $result = self::Connection()->query($sql);
            $compphone = $result->fetch(PDO::FETCH_NUM);
     
            $sql = "SELECT contry_code, contact_no FROM contact_numbers WHERE (table_name='broker_contacts' and table_id='{$agent_row[0]}' and type='phone1')";
            $result = self::Connection()->query($sql);
            $phone = $result->fetch(PDO::FETCH_NUM);

            $arr = array();
            $arr['id'] = $v->id;
            $arr['name'] = $v->name;
            $arr['type'] = $v->type;
            $arr['des'] = $v->description;
            $arr['type'] = $v->type;
            $arr['status'] = $v->status;
            $arr['pan'] = $v->pan;
            $arr['email'] = $v->primary_email;

            $arr['address'] = $address_row[0];
            $arr['city'] = $address_row[1];
            $arr['pin'] = $address_row[2];
            $arr['ips'] = $ips;
            $arr['ipsstr'] = implode("-", $ips);
            $arr['compphone'] = $compphone[1];
            $arr['person'] = $agent_row[1];

            $arr['fax'] = $fax[1];
            $arr['phone'] = $phone[1];
            $arr['city_name'] = $city[0];
            //$arr['city'] = $v->city;


            array_push($returnArr, $arr);


        }
        */
        return $agentsDetail;
    }

    
    
    
    
}
