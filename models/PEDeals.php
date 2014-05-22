<?php

// Model integration for Company list
class PEDeals extends ActiveRecord\Model
{
    static $table_name = 'private_equity_deals';
    //$result = array();
    static function getPEDealsById($compId) {
        $companyDetail = PEDeals::find('all',array('conditions'=>array("id = $compId")));
        return $companyDetail;
    }

    static function getAllPEDeals() {
        $join = ' INNER JOIN  company c ON c.id = private_equity_deals.pe_id ';
        $join .= ' left JOIN resi_builder rb ON (rb.BUILDER_ID = private_equity_deals.builder_id) ';
        $select = 'private_equity_deals.id, pe_id, private_equity_deals.type, private_equity_deals.builder_id, value, article_link, transaction_date, extra_values, c.name, rb.BUILDER_NAME ';
        $companyDetail = PEDeals::find('all', array('joins'=> $join, 'select' => $select));
        $returnArr = array();
        foreach ($companyDetail as $v) {
            $arr = array();
            $arr['id'] = $v->id;
            $arr['name'] = $v->name;
            $arr['type'] = $v->type;
            $arr['value'] = $v->value;
            $str = $v->transaction_date;
            $str = substr($str, 0,10);
            $arr['transaction_date'] = $str;
            $arr['article_link'] = $v->article_link;
            $arr['builder_name'] = $v->builder_name;
            $str = $v->extra_values;
            $str = str_replace('{', '', $str);
            $str = str_replace('}', '', $str);
            $str = str_replace('[', '', $str);
            $str = str_replace(']', '', $str);
            $str = str_replace('"', '', $str);
            $str = str_replace(',', ', ', $str);
            $arr['extra_values'] = $str;
            array_push($returnArr, $arr);
        }
        return $returnArr;
    }

    


     static function getPEDealsByType($type) {
        $companyDetail = PEDeals::find('all',array('conditions'=>array("type = '{$type}'")));
        return $companyDetail;
    }
   
    
    
    
}
