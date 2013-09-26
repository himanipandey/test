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
        $localityDetail = Locality::find('all',array('conditions'=>array("locality_id = $localityID")));
        return $localityDetail;
    }
}