<?php

// Model integration for Company list
class Company extends ActiveRecord\Model
{
    static $table_name = 'company';
    //$result = array();
    static function getCompanyById($compId) {
        $companyDetail = Company::find('all',array('conditions'=>array("id = $compId")));
        return $companyDetail;
    }

    static function getAllCompany($arr=null) {
        $compid = $arr['id'];
        $type = $arr['type'];

        if($compid!='' && $type!=''){
            $companyDetail = Company::find('all', array('conditions'=>array("id = $compid", "type = '$type'")));
        }
        else if($compid>0){
            $companyDetail = Company::find('all', array('conditions'=>array("id = $compid")));
        }
        else if($type!=''){
            $companyDetail = Company::find('all', array('conditions'=>array("type = '$type'")));
        }
        else{
            $companyDetail = Company::find('all');
        }
        
        $returnArr = Company::getCompanyOtherDetails($companyDetail);
        //$returnArr = array();
        
        
        return $returnArr;
    }

    static function getCompanyType() {
        $sql = "SHOW COLUMNS FROM company LIKE 'type'";
        //$result = 
        $result = self::Connection()->query($sql);
        $row = $result->fetch(PDO::FETCH_NUM);

        if ($row) { // If the query's successful
            
            
            preg_match_all("/'([\w ]*)'/", $row[1], $values);
            
        } 
   
        return $values[1];
    }

     static function getCompanyByType($type) {
        $companyDetail = Company::find('all',array('conditions'=>array("type = '{$type}'")));
        $list = array();
        foreach ($companyDetail as $v) {
            $list[$v->id] = $v->name;
        }
        return $list;
    }
   
    static function getCompanyOtherDetails($companyDetail){
        $returnArr = array();
        foreach ($companyDetail as $v) {
            $sql = "SELECT id, address_line_1, city_id, pincode FROM addresses WHERE (table_name='company' and table_id={$v->id} and type='HQ')";
            $result = self::Connection()->query($sql);
            $address_hq_row = $result->fetch(PDO::FETCH_NUM);

            $sql = "SELECT LABEL FROM city WHERE  CITY_ID='{$address_hq_row[2]}'";
            $result = self::Connection()->query($sql);
            $city = $result->fetch(PDO::FETCH_NUM);

   /*********************office locations*************************************************/     
            
            $sql = "SELECT id, address_line_1 as address, city_id as c_id, locality_id as loc_id FROM addresses WHERE (table_name='company' and table_id={$v->id} and type='Other')";
            $result = self::Connection()->query($sql);
            $office_loc_row = array();
            while($data = $result->fetch(PDO::FETCH_ASSOC)){
                $data['c_name'] = Company::getLocalityLabel($data['l_id'])['c_label'];
                $data['loc_name'] = Company::getLocalityLabel($data['l_id'])['loc_label'];
                array_push($office_loc_row, $data);
            };


            $sql = "SELECT ip FROM company_ips WHERE  company_id='{$v->id}'";
            $result = self::Connection()->query($sql);
            $ips = array();
            while($data = $result->fetch(PDO::FETCH_NUM)){
                array_push($ips, $data[0]);
            };
/********************************coverage care*******************************************/
            $coverage = array();
            $sql = "SELECT id, city_id as c_id, locality_id as loc_id, builder_id as b_id, project_id as p_id FROM company_coverage WHERE company_id='{$v->id}'";
            $result = self::Connection()->query($sql);
            while($data = $result->fetch(PDO::FETCH_ASSOC)){
                $data['c_name'] = Company::getLocalityLabel($data['l_id'])['c_label'];
                $data['loc_name'] = Company::getLocalityLabel($data['l_id'])['loc_label'];
                $data['b_name'] = Company::getLocalityLabel($data['b_id'])['b_name'];
                $data['p_name'] = Company::getLocalityLabel($data['p_id'])['p_name'];
                array_push($coverage, $data);
            };



   /********************* contact person *************************************************/     
            $sql = "SELECT id, name, contact_email FROM broker_contacts WHERE (broker_id={$v->id} and type='NAgent')";
            $result = self::Connection()->query($sql);
            $cont_person_row = array();
            while($data = $result->fetch(PDO::FETCH_ASSOC)){
                array_push($cont_person_row, $data);
            };

            foreach ($cont_person_row as $k => $v) {
                $sql = "SELECT id, contact_no FROM contact_numbers WHERE (table_name='broker_contacts' and table_id='{$v['id']}' and type='fax')";
                $result = self::Connection()->query($sql);
                $fax= $result->fetch(PDO::FETCH_ASSOC);
                $cont_person_row[$k]['fax'] = $fax['contact_no'];
                $cont_person_row[$k]['fax_id'] = $fax['id'];

                $sql = "SELECT id, contact_no FROM contact_numbers WHERE (table_name='broker_contacts' and table_id='{$v['id']}' and type='phone1')";
                $result = self::Connection()->query($sql);
                $phone1= $result->fetch(PDO::FETCH_ASSOC);
                $cont_person_row[$k]['phone1'] = $phone1['contact_no'];
                $cont_person_row[$k]['phone1_id'] = $phone1['id'];

                $sql = "SELECT id, contact_no FROM contact_numbers WHERE (table_name='broker_contacts' and table_id='{$v['id']}' and type='phone2')";
                $result = self::Connection()->query($sql);
                $phone2= $result->fetch(PDO::FETCH_ASSOC);
                $cont_person_row[$k]['phone2'] = $phone2['contact_no'];
                $cont_person_row[$k]['phone2_id'] = $phone2['id'];

                $sql = "SELECT id, contact_no FROM contact_numbers WHERE (table_name='broker_contacts' and table_id='{$v['id']}' and type='mobile')";
                $result = self::Connection()->query($sql);
                $mobile= $result->fetch(PDO::FETCH_ASSOC);
                $cont_person_row[$k]['mobile'] = $mobile['contact_no'];
                $cont_person_row[$k]['mobile_id'] = $mobile['id'];
            }

            

            $sql = "SELECT id, contact_no FROM contact_numbers WHERE (table_name='company' and table_id='{$v->id}' and type='phone1')";
            $result = self::Connection()->query($sql);
            $compphone = $result->fetch(PDO::FETCH_NUM);

/********************************customer care*******************************************/
            $cust_care = array();
            $sql = "SELECT id, contact_no FROM contact_numbers WHERE (table_name='company' and table_id='{$v->id}' and type='cc_phone')";
            $result = self::Connection()->query($sql);
            $ph = $result->fetch(PDO::FETCH_ASSOC);
            $cust_care['phone'] = $ph['contact_no'];
            $cust_care['phone_id'] = $ph['id'];

            $sql = "SELECT id, contact_no FROM contact_numbers WHERE (table_name='company' and table_id='{$v->id}' and type='cc_mobile')";
            $result = self::Connection()->query($sql);
            $mobile = $result->fetch(PDO::FETCH_ASSOC);
            $cust_care['mobile'] = $mobile['contact_no'];
            $cust_care['mobile_id'] = $mobile['id'];

            $sql = "SELECT id, contact_no FROM contact_numbers WHERE (table_name='company' and table_id='{$v->id}' and type='cc_fax')";
            $result = self::Connection()->query($sql);
            $fax = $result->fetch(PDO::FETCH_ASSOC);
            $cust_care['fax'] = $fax['contact_no'];
            $cust_care['fax_id'] = $fax['id'];

/********************************broker details*******************************************/
            //$broker_details = array();
            $sql = "SELECT id, legal_type, rating, service_tax_no, office_size, employee_no, pt_manager_id FROM broker_details WHERE broker_id='{$v->id}' ";
            $result = self::Connection()->query($sql);
            $broker_details = $result->fetch(PDO::FETCH_ASSOC);
            
            //$cust_care['phone'] = $ph['contact_no'];
            //$cust_care['phone_id'] = $ph['id'];


/********************************broker details*******************************************/

            $arr = array();
            $arr['id'] = $v->id;
            $arr['name'] = $v->name;
            $arr['type'] = $v->type;
            $arr['des'] = $v->description;
            $arr['type'] = $v->type;
            $arr['status'] = $v->status;
            $arr['pan'] = $v->pan;
            $arr['email'] = $v->primary_email;

            $arr['address'] = $address_hq_row[0];
            $arr['city'] = $address_hq_row[1];
            $arr['pin'] = $address_hq_row[2];
            $arr['ips'] = $ips;
            $arr['ipsstr'] = implode("-", $ips);
            $arr['compphone'] = $compphone[1];
            //$arr['person'] = $agent_row[1];

            //$arr['fax'] = $fax[1];
            //$arr['phone'] = $phone[1];
            $arr['city_name'] = $city[0];
            $arr['off_loc'] = $office_loc_row;
            $arr['coverage'] = $coverage;
            $arr['cont_person'] = $cont_person_row;
            $arr['cust_care'] = $cust_care;
            $arr['broker_details'] = $broker_details;
            //$arr['city'] = $v->city;


            array_push($returnArr, $arr);

        }

        return $returnArr;
    }
    
    

    static function getCityLabel($c_id){
        $sql = "SELECT LABEL FROM city WHERE  CITY_ID='{$c_id}'";
        $result = self::Connection()->query($sql);
        $city = $result->fetch(PDO::FETCH_ASSOC);
        return $city;
    }

    static function getLocalityLabel($l_id){
        $sql = "SELECT l.LABEL as loc_label, c.LABEL as c_label FROM locality as l
                INNER JOIN city c on l.city_id = c.city_id WHERE l.LOCALITY_ID='{$l_id}'";
        $result = self::Connection()->query($sql);
        $city = $result->fetch(PDO::FETCH_ASSOC);
        return $city;
    }

    static function getBuilderLabel($b_id){
        $sql = "SELECT builder_name as b_name, FROM resi_builder WHERE  BUILDER_ID='{$b_id}'";
        $result = self::Connection()->query($sql);
        $city = $result->fetch(PDO::FETCH_ASSOC);
        return $city;
    }

    static function getProjectLabel($b_id){
        $sql = "SELECT project_name as p_name, FROM resi_project WHERE  PROJECT_ID='{$b_id}'";
        $result = self::Connection()->query($sql);
        $city = $result->fetch(PDO::FETCH_ASSOC);
        return $city;
    }
}
