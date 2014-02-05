<?php

// Model integration for bank list
class NameAliases extends ActiveRecord\Model
{
    static $table_name = 'name_aliases';
    /*static function localityList($suburbId) {
        $locality = Locality::find('all',array('conditions'=>array("suburb_id = $suburbId AND status = 'active'"),'order' => 'label asc'));
        $arrLocality = array();
        foreach ($locality  as $value) {
           $arrLocality[$value->locality_id] = $value->label;
        }
        return $arrLocality;
    }*/
    static function getAliasesById($Id) {
        $aliasDetail = NameAliases::find('all',array('conditions'=>array("id = $Id")));
        return $aliasDetail;
    }

    static function getAliasesByName($name) {
        $aliasDetail = NameAliases::find('all',array('conditions'=>array("alias_name like '$name'")));
        return $aliasDetail;
    }
    static function getAliasesByTable($ctid) {
        /*$conditions = array("a.city_id = ? and a.status = ? and locality.status = ?", $ctid, 'Active', 'Active');
        $join = 'INNER JOIN suburb a ON(locality.suburb_id = a.suburb_id)';
        $join .= 'INNER JOIN city c ON(a.city_id = c.city_id)';

        $getLocality = Locality::find('all',array('joins' => $join, 
               "conditions" => $conditions, "select" => "locality.locality_id,locality.label, c.label as cityname"));
        return $getLocality;*/

        $localityDetail = NameAliases::find('all',array('conditions'=>array("CITY_ID = $ctid")));
        return $localityDetail;

    }
    
   
 }
