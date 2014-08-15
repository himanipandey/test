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

    static function getCompanyNameByQuery($query) {
        $companyDetail = Company::find('all',array('conditions'=>array("name like '%{$query}%'")));
        //$companyDetail = Company::find('all',array('conditions'=>array("name = 'Broker'")));
        $list = array();
        $i = 0;
        foreach ($companyDetail as $v) {
            //array_push($list, $v->name);
            $i++;
            $tmp = array();
            $tmp['name'] = $v->name;
            array_push($list, $tmp);
        }
        return $list;
    }
   
    static function getCompanyOtherDetails($companyDetail){
        $returnArr = array();
        foreach ($companyDetail as $v) {
            $sql = "SELECT id, address_line_1, city_id, pincode FROM addresses WHERE (table_name='company' and table_id={$v->id} and type='HQ')";
            $result = self::Connection()->query($sql);
            $address_hq_row = $result->fetch(PDO::FETCH_ASSOC);

            $sql = "SELECT LABEL FROM city WHERE  CITY_ID='{$address_hq_row['city_id']}'";
            $result = self::Connection()->query($sql);
            $city = $result->fetch(PDO::FETCH_NUM);

   /*********************office locations*************************************************/     
            
            $sql_test = "SELECT id, address_line_1 as address, city_id as c_id, locality_id as loc_id FROM addresses WHERE (table_name='company' and table_id={$v->id} and type='Other')";
            $result = self::Connection()->query($sql_test);
            $office_loc_row = array();
            while($data_test = $result->fetch(PDO::FETCH_ASSOC)){
                $label = Company::getLocalityLabel($data_test['loc_id']);
                $arr = array();
                foreach ($data_test as $k2 => $v2) {
                    $arr[$k2] = $v2;
                }
                $arr['c_name'] = $label['c_label'];
                $arr['loc_name'] = $label['loc_label'];
                array_push($office_loc_row, $arr);
            }


            $sql = "SELECT ip FROM company_ips WHERE  company_id='{$v->id}'";
            $result = self::Connection()->query($sql);
            $ips = array();
            while($data = $result->fetch(PDO::FETCH_NUM)){
                array_push($ips, $data[0]);
            }
/********************************coverage care*******************************************/
            $coverage = array();
            $sql = "SELECT id, city_id as c_id, locality_id as loc_id, builder_id as b_id, project_id as p_id FROM company_coverage WHERE company_id='{$v->id}'";
            $result = self::Connection()->query($sql);
            while($data = $result->fetch(PDO::FETCH_ASSOC)){
                $label = Company::getLocalityLabel($data['loc_id']);
                $b = Company::getBuilderLabel($data['b_id']);
                $p = Company::getProjectLabel($data['p_id']);
                $arr = array();
                foreach ($data as $k2 => $v2) {
                    $arr[$k2] = $v2;
                }
                $arr['c_name'] = $label['c_label'];
                $arr['loc_name'] = $label['loc_label'];
                $arr['b_name'] = $b['b_name'];
                $arr['p_name'] = $p['p_name'];
                array_push($coverage, $arr);
            }



   /********************* contact person *************************************************/     
            $sql = "SELECT id, name as person, contact_email as email FROM broker_contacts WHERE (broker_id={$v->id} and type='NAgent')";
            $result = self::Connection()->query($sql);
            $cont_person_row = array();
            while($data = $result->fetch(PDO::FETCH_ASSOC)){
                array_push($cont_person_row, $data);
            }

            foreach ($cont_person_row as $k1 => $v1) {
                $sql = "SELECT id, contact_no FROM contact_numbers WHERE (table_name='broker_contacts' and table_id='{$v1['id']}' and type='fax')";
                $result = self::Connection()->query($sql);
                $contfax= $result->fetch(PDO::FETCH_ASSOC);
                $cont_person_row[$k1]['fax'] = $contfax['contact_no'];
                $cont_person_row[$k1]['fax_id'] = $contfax['id'];

                $sql = "SELECT id, contact_no FROM contact_numbers WHERE (table_name='broker_contacts' and table_id='{$v1['id']}' and type='phone1')";
                $result = self::Connection()->query($sql);
                $phone1= $result->fetch(PDO::FETCH_ASSOC);
                $cont_person_row[$k1]['phone1'] = $phone1['contact_no'];
                $cont_person_row[$k1]['phone1_id'] = $phone1['id'];

                $sql = "SELECT id, contact_no FROM contact_numbers WHERE (table_name='broker_contacts' and table_id='{$v1['id']}' and type='phone2')";
                $result = self::Connection()->query($sql);
                $phone2= $result->fetch(PDO::FETCH_ASSOC);
                $cont_person_row[$k1]['phone2'] = $phone2['contact_no'];
                $cont_person_row[$k1]['phone2_id'] = $phone2['id'];

                $sql = "SELECT id, contact_no FROM contact_numbers WHERE (table_name='broker_contacts' and table_id='{$v1['id']}' and type='mobile')";
                $result = self::Connection()->query($sql);
                $mobile= $result->fetch(PDO::FETCH_ASSOC);
                $cont_person_row[$k1]['mobile'] = $mobile['contact_no'];
                $cont_person_row[$k1]['mobile_id'] = $mobile['id'];
            }

            

            $sql = "SELECT id, contact_no FROM contact_numbers WHERE (table_name='company' and table_id='{$v->id}' and type='phone1')";
            $result = self::Connection()->query($sql);
            $compphone = $result->fetch(PDO::FETCH_NUM);

            $sql = "SELECT id, contact_no FROM contact_numbers WHERE (table_name='company' and table_id='{$v->id}' and type='fax')";
            $result = self::Connection()->query($sql);
            $compfax = $result->fetch(PDO::FETCH_NUM);

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
            $cc_fax = $result->fetch(PDO::FETCH_ASSOC);
            $cust_care['fax'] = $cc_fax['contact_no'];
            $cust_care['fax_id'] = $cc_fax['id'];

/********************************broker details*******************************************/
            //$broker_details = array();
            $sql = "SELECT id, legal_type, rating, service_tax_no, office_size, employee_no, pt_manager_id FROM broker_details WHERE broker_id='{$v->id}' ";
            $result = self::Connection()->query($sql);
            $broker_details = $result->fetch(PDO::FETCH_ASSOC);

            $sql = "SELECT id, broker_property_type_id FROM broker_property_type_mappings WHERE broker_id='{$v->id}' ";
            $result = self::Connection()->query($sql);
            $broker_prop_type = array();
            while($data = $result->fetch(PDO::FETCH_ASSOC)){
                array_push($broker_prop_type, $data);
            }
            

            $sql = "SELECT id, transaction_type_id FROM transaction_type_mappings WHERE (table_name='company' and table_id='{$v->id}') ";
            $result = self::Connection()->query($sql);
            $transac_type = array();
            while($data = $result->fetch(PDO::FETCH_ASSOC)){
                array_push($transac_type, $data);
            }
            //$cust_care['phone'] = $ph['contact_no'];transac_type
            //$cust_care['phone_id'] = $ph['id'];


/********************************broker details*******************************************/

            $arr = array();
            $arr['id'] = $v->id;
            $arr['name'] = $v->name;
            $arr['type'] = $v->type;
            $arr['broker_info_type'] = $v->company_info_type;
            $arr['des'] = $v->description;
            $arr['status'] = $v->status;
            $arr['pan'] = $v->pan;
            $arr['email'] = $v->primary_email;
            $arr['active_since'] = $v->active_since;
            $arr['web'] = $v->website;

            $arr['address'] = $address_hq_row['address_line_1'];
            $arr['city'] = $address_hq_row['city_id'];
            $arr['pin'] = $address_hq_row['pincode'];
            $arr['ips'] = $ips;
            $arr['ipsstr'] = implode("-", $ips);
            $arr['compphone'] = $compphone[1];
            $arr['compfax'] = $compfax[1];

            //$arr['person'] = $agent_row[1];

            //$arr['fax'] = $fax[1];
            //$arr['phone'] = $phone[1];
            $extra = array();
            $extra['off_loc'] = $office_loc_row;
            $extra['coverage'] = $coverage;
            $extra['cont_person'] = $cont_person_row; //'{"data":'.json_encode($cont_person_row).'}'; //$cont_person_row;
            $extra['cust_care'] = $cust_care;
            $extra['broker_details'] = $broker_details;
            $extra['broker_prop_type'] = $broker_prop_type;
            $extra['transac_type'] = $transac_type;

            $arr['extra'] = $extra;
            $arr['extra_json'] = htmlentities('{"data":'.json_encode($extra).'}');
            $arr['city_name'] = $city[0];
            /*$arr['off_loc'] = '"off_loc":'.json_encode($office_loc_row).'}';
            $arr['coverage'] = '"coverage":'.json_encode($coverage).'}';//$coverage;
            $arr['cont_person'] = '"coverage":'.json_encode($coverage).'}';//$cont_person_row;
            $arr['cust_care'] = '"coverage":'.json_encode($coverage).'}';//$cust_care;
            $arr['broker_details'] = '"coverage":'.json_encode($coverage).'}';//$broker_details;
            $arr['broker_prop_type'] = '"coverage":'.json_encode($coverage).'}';//$broker_prop_type;
            $arr['transac_type'] = '"coverage":'.json_encode($coverage).'}';//$transac_type;
            */
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
        $sql = "SELECT l.LABEL as loc_label, c.LABEL as c_label FROM locality as l INNER JOIN city c on l.city_id = c.city_id WHERE l.LOCALITY_ID='{$l_id}'";
        $result = self::Connection()->query($sql);
        $city = $result->fetch(PDO::FETCH_ASSOC);
        return $city;
    }


    static function getBuilderLabel($b_id){
        $sql = "SELECT builder_name as b_name FROM resi_builder WHERE  BUILDER_ID='{$b_id}'";
        $result = self::Connection()->query($sql);
        $city = $result->fetch(PDO::FETCH_ASSOC);
        return $city;
    }

    static function getCompanyByType($type) {
        $companyDetail = Company::find('all',array('conditions'=>array("type = '{$type}' and status = 'Active'")));
        $list = array();
        foreach ($companyDetail as $v) {
            $list[$v->id] = $v->name;
        }
        return $list;

    }

    static function getProjectLabel($b_id){
        $sql = "SELECT project_name as p_name FROM resi_project WHERE  PROJECT_ID='{$b_id}'";
        $result = self::Connection()->query($sql);
        $city = $result->fetch(PDO::FETCH_ASSOC);
        return $city;
    }


}
