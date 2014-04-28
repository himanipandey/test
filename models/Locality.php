<?php

// Model integration for bank list
class Locality extends ActiveRecord\Model
{
    static $table_name = 'locality';
    static function localityList($suburbId) {
        $locality = Locality::find('all',array('conditions'=>array("suburb_id = $suburbId AND status = 'active'"),'order' => 'label asc'));
        $arrLocality = array();
        foreach ($locality  as $value) {
           $arrLocality[$valuelocality_id] = $value->label;
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
        $join .= 'INNER JOIN city c ON(a.city_id = c.city_id)';

        $getLocality = Locality::find('all',array('joins' => $join, 
               "conditions" => $conditions, "select" => "locality.locality_id,locality.label, c.label as cityname"));
        return $getLocality;
    }
    
    static function updateLocalityCoordinates(){
        $conn = self::connection();
        $sql = "update locality a, (select locality_id, avg(latitude) as latitude, avg(longitude) as longitude from resi_project where latitude is not null and latitude not in (0 , 1, 2, 3, 4, 5, 6, 7, 8, 9) and longitude is not null and longitude not in (0 , 1, 2, 3, 4, 5, 6, 7, 8, 9) and status in ('Active' , 'ActiveInCms') group by locality_id) b set a.latitude = b.latitude, a.longitude = b.longitude where a.LOCALITY_ID = b.LOCALITY_ID";
        $conn->query($sql);
    }
}
