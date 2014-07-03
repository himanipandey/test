<?php

// Model integration for bank list
class Suburb extends ActiveRecord\Model
{
    static $table_name = 'suburb';
    static function SuburbArr($cityId) { 
        $suburb = Suburb::find('all',array('conditions'=>array("city_id" => $cityId),'order' => 'label asc'));
        $arrSuburb = array();
        foreach ($suburb  as $value) {
           $arrSuburb[$value->suburb_id] = $value->label;
        }
        return $arrSuburb;
    }
    static function getSuburbById($suburbId) {
        $suburbDetail = Suburb::find('all',array('conditions'=>array("suburb_id = $suburbId")));
        return $suburbDetail;
    }

    static function getSuburbByLocality($localityId) {
        $query = "select s.* from locality_suburb_mappings lsm 
                inner join suburb s on lsm.suburb_id=s.suburb_id where lsm.locality_id=".$localityId;
        $res = mysql_query($query) or die(mysql_error);

        $arrSuburb = array();
        while ($row = mysql_fetch_assoc($res)) {
            array_push($arrSuburb, $row);
        }
        return $arrSuburb;
    }
}
    static function getSuburbCity($suburbId) {
        $suburbDetail = Suburb::find('all',array('joins'=>'INNER JOIN city ON suburb.city_id = city.city_id','select'=>'suburb.label as suburb_name, city.label as cityname','conditions'=>array("suburb.suburb_id = $suburbId")));
        return $suburbDetail;
    }
}

