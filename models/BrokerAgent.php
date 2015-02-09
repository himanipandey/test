<?php

// Model integration for Company list
class BrokerAgent extends ActiveRecord\Model
{
    static $table_name = 'company_users';
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
        
        
        $returnArr = array();
        foreach ($agentsDetail as $v) {
            $sql = "SELECT address_line_1, city_id, pincode FROM addresses WHERE (table_name='company_users' and table_id={$v->id})";
            $result = self::Connection()->query($sql);
            $address_row = $result->fetch(PDO::FETCH_NUM);

            $sql = "SELECT LABEL FROM city WHERE  CITY_ID='{$address_row[1]}'";
            $result = self::Connection()->query($sql);
            $city = $result->fetch(PDO::FETCH_NUM);
                   
            $sql = "SELECT name FROM company WHERE  id='{$v->company_id}'";
            $result = self::Connection()->query($sql);
            $broker_name = $result->fetch(PDO::FETCH_NUM);

            $sql = "SELECT qualification FROM academic_qualifications WHERE id='{$v->academic_qualification_id}'";
            $result = self::Connection()->query($sql);
            $qualification = $result->fetch(PDO::FETCH_NUM);

            $sql = "SELECT contry_code, contact_no FROM contact_numbers WHERE (table_name='company_users' and table_id='{$v->id}' and type='phone1')";
            $result = self::Connection()->query($sql);
            $phone= $result->fetch(PDO::FETCH_NUM);

            $sql = "SELECT contry_code, contact_no FROM contact_numbers WHERE (table_name='company_users' and table_id='{$v->id}' and type='mobile')";
            $result = self::Connection()->query($sql);
            $mobile = $result->fetch(PDO::FETCH_NUM);
     
            $arrtemp = array();
            $arr = array();
            $arr['id'] = $v->id;
            $arr['brokerId'] = $v->company_id;
            $arr['brokerName'] = $broker_name[0];
            
            $arrtemp[$v->company_id] = $arr['brokerName'];

            $arr['name'] = $v->name;
            $arr['role'] = $v->seller_type;
            //$arr['des'] = $v->description;
            //$arr['type'] = $v->type;
            $arr['status'] = $v->status;
            //$arr['pan'] = $v->pan;
            $arr['email'] = $v->email;
            $arr['user_id'] = $v->user_id;

            $arr['address'] = $address_row[0];
            $arr['city'] = $address_row[1];
            $arr['pin'] = $address_row[2];
            //$arr['ips'] = $ips;
            //$arr['ipsstr'] = implode("-", $ips);
            $arr['mobile'] = $mobile[1];
            //$arr['person'] = $agent_row[1];

            //$arr['fax'] = $fax[1];
            $arr['phone'] = $phone[1];
            $arr['city_name'] = $city[0];
            $arr['active_since'] = $v->active_since;
            $arr['qualification'] = $qualification[0];
            $arr['qualification_id'] = $v->academic_qualification_id;
            //$arr['city'] = $v->city;


            array_push($returnArr, $arr);    //Changed here !!
            //array_push($returnArr, $arr['brokerName']);


        }
        
        return $returnArr;
    }

     /*static function getBrokerAgentsByCompany($companyId) {
        $agentsDetail = BrokerAgent::find('all',array('conditions'=>array("company_id = $companyId ")));
        return $agentsDetail;
    }*/
    
    
    
}
