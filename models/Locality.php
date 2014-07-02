<?php

// Model integration for bank list
class Locality extends ActiveRecord\Model
{
    static $table_name = 'locality';
    static function localityList($suburbId) {
        $select = "select * from locality ";
        $join = " inner join locality_suburb_mappings lsm on lsm.locality_id=locality.LOCALITY_ID ";
        $where = " where lsm.suburb_id = '".$suburbId."' ";
        $query = $select.$join.$where;
        //$locality = Locality::find('all',array('conditions'=>array("suburb_id = $suburbId AND status = 'active'"),'order' => 'label asc'));
        $locality = Locality::find_by_sql($query);
        $arrLocality = array();
        foreach ($locality  as $value) {
           $arrLocality[$value->locality_id] = $value->label;
        }
        return $arrLocality;
    }

    static function localityListByCity($cityId) {
        $select = "select * from locality l ";
        $where = " where l.city_id = '".$cityId."' ";
        $query = $select.$where;
        //$locality = Locality::find('all',array('conditions'=>array("suburb_id = $suburbId AND status = 'active'"),'order' => 'label asc'));
        $locality = Locality::find_by_sql($query);
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
        $conditions = array("locality.city_id = ? and locality.status = ?", $ctid, 'Active');
        //$join = 'INNER JOIN suburb a ON(locality.suburb_id = a.suburb_id)';
        $join = 'INNER JOIN city c ON(c.city_id = locality.city_id)';

        $getLocality = Locality::find('all',array('joins' => $join, 
               "conditions" => $conditions, "select" => "locality.locality_id,locality.label, c.label as cityname"));
        return $getLocality;
    }
    
    static function getLocalityCity($locId) {
        $conditions = array("locality_id = ? and locality.status = ?", $locId, 'Active');
        //$join = 'INNER JOIN suburb a ON(locality.suburb_id = a.suburb_id)';
        $join = 'INNER JOIN city c ON(locality.city_id = c.city_id)';

        $getLocalityCity = Locality::find('all',array('joins' => $join, 
               "conditions" => $conditions, "select" => "locality.label locname, c.label as cityname"));
               
        return $getLocalityCity;
    }
    
    static function getAllLocalityByCity($ctid) {
        $conditions = array("a.city_id = ?", $ctid);
        //$join = 'INNER JOIN suburb a ON(locality.suburb_id = a.suburb_id)';
        $join = 'INNER JOIN city c ON(locality.city_id = c.city_id)';

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
