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
        $select = 'private_equity_deals.id, pe_id, pe_id_2, pe_id_3, private_equity_deals.type, private_equity_deals.builder_id, value, article_link, transaction_date, extra_values, c.name, rb.BUILDER_NAME ';
        $companyDetail = PEDeals::find('all', array('joins'=> $join, 'select' => $select));
        $returnArr = array();
        foreach ($companyDetail as $v) {
            $arr = array();
            $arr['id'] = $v->id;
            $arr['pe_id'] = $v->pe_id;
            $arr['pe_id_2'] = $v->pe_id_2;
            if($v->pe_id_2){
			  $comp2 = Company::getCompanyById($v->pe_id_2);
              $arr['name_2'] = $comp2[0]->name;
            }
            $arr['pe_id_3'] = $v->pe_id_3;
            if($v->pe_id_3){
			   $comp3 = Company::getCompanyById($v->pe_id_3);
               $arr['name_3'] = $comp3[0]->name;
            }
            $arr['builder_id'] = $v->builder_id;
            $arr['name'] = $v->name;          
            $arr['type'] = $v->type;
            if($arr['type'] == 'Fund Raising')
              $arr['deal_type_id'] = 1;
            elseif($arr['type'] == 'Investment')
              $arr['deal_type_id'] = 2;
            elseif($arr['type'] == 'Exit')
              $arr['deal_type_id'] = 3;
            $arr['value'] = $v->value;
            $str = date('Y-m-d',strtotime(substr($v->transaction_date,0,11)));
            //$str = substr($str, 0,10);
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
            $deal_extras = json_decode($v->extra_values);
            if($deal_extras->projects){
			  $deal_projects = implode(",",$deal_extras->projects);	
			}
			if($deal_extras->investmentValue){
			  $invest_value = $deal_extras->investmentValue;
			}
			if($deal_extras->period){
			  $period = $deal_extras->period;
			}
			$arr['period'] = $period;
			$arr['investmentValue'] = $invest_value;
            $arr['deal_projects'] = $deal_projects;
            array_push($returnArr, $arr);
        }
        return $returnArr;
    }

    


     static function getPEDealsByType($type) {
        $companyDetail = PEDeals::find('all',array('conditions'=>array("type = '{$type}'")));
        return $companyDetail;
    }
   
    
    
    
}
