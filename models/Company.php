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

    static function getAllCompany() {
        $companyDetail = Company::find('all');
        return $companyDetail;
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
   
    
    
    
}
