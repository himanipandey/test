<?php

// Model integration for bank list
class Locality extends ActiveRecord\Model
{
    static $table_name = 'locality';
    static function localityList($suburbId) {
        $locality = Locality::find('all',array('conditions'=>array("suburb_id = $suburbId AND status = 'active'"),'order' => 'label asc'));
        $arrLocality = array();
        foreach ($locality  as $value) {
           $arrLocality[$value->locality_id] = $value->label;
        }
        return $arrLocality;
    }
    static function getLocalityById($localityId) {
        $localityDetail = Locality::find('all',array('conditions'=>array("locality_id = $localityId")));
        return $localityDetail;
    }
    static function getLocalityByCity($ctid) {
        $conditions = array("a.city_id = ? and a.status = ? and locality.status = ?", $ctid, 'Active', 'Active');
        $join = 'INNER JOIN suburb a ON(locality.suburb_id = a.suburb_id)';

        $getLocality = Locality::find('all',array('joins' => $join, 
               "conditions" => $conditions, "select" => "locality.locality_id,locality.label"));
        return $getLocality;
    }
}
