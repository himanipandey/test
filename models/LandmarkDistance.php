<?php

// Model integration for resi_project

require_once "support/objects.php";
require_once (dirname(__FILE__) . "/../common/function.php");
require_once (dirname(__FILE__) . '/../cron/cronConfig.php');

class LandmarkDistance extends ActiveRecord\Model {

    static $table_name = 'landmark_distances';
    static $all_landmark_type_ids = array(
        1, // school
        2, // hospital
        5, // restaurant
        7, // metro_station
        8, // bus_station
        9, // train_station
        2000, // parks
        3000    // markets
    );
    static $city_independent_landmark_types = array(
        13, // airport
        1000    //city_railway_station
    );
    static $max_landmark_distance = array(
        1 => 10000,
        2 => 5000
    );
    static $max_landmark_count = array(
        1 => 100,
        2 => 50,
        13 => 1,
        1000 => 2
    );

    static function insertProjectDistance() {
        global $latLongList;

        $sql = "insert into landmark_distances (object_id, object_type, landmark_id, distance, city_id, place_type_id, priority) select rp.PROJECT_ID, 'Project', ld.id, " . getDBDistanceQueryString('rp.LONGITUDE', 'rp.LATITUDE', 'ld.longitude', 'ld.latitude') . " AS distance, ld.city_id, ld.place_type_id, ld.priority from resi_project rp inner join locality l on rp.LOCALITY_ID = l.LOCALITY_ID inner join suburb s on l.SUBURB_ID = s.SUBURB_ID inner join landmarks ld on s.CITY_ID = ld.city_id where rp.version = 'Cms' and rp.LONGITUDE not in (" . $latLongList . ") and rp.LATITUDE not in (" . $latLongList . ") and ld.status = 'Active' and ld.place_type_id in (" . implode(",", self::$all_landmark_type_ids) . ")";
        self::connection()->query($sql);

        $sql = "insert into landmark_distances (object_id, object_type, landmark_id, distance, city_id, place_type_id, priority) select rp.PROJECT_ID, 'Project', ld.id, " . getDBDistanceQueryString('rp.LONGITUDE', 'rp.LATITUDE', 'ld.longitude', 'ld.latitude') . " AS distance, ld.city_id, ld.place_type_id, ld.priority from resi_project rp inner join landmarks ld where rp.version = 'Cms' and rp.LONGITUDE not in (" . $latLongList . ") and rp.LATITUDE not in (" . $latLongList . ") and ld.status = 'Active' and ld.place_type_id in (" . implode(",", self::$city_independent_landmark_types) . ")";
        self::connection()->query($sql);
    }

    static function insertLocalityDistance() {
        global $latLongList;

        $sql = "insert into landmark_distances (object_id, object_type, landmark_id, distance, city_id, place_type_id, priority) select l.LOCALITY_ID, 'Locality', ld.id, " . getDBDistanceQueryString('l.LONGITUDE', 'l.LATITUDE', 'ld.longitude', 'ld.latitude') . " AS distance, ld.city_id, ld.place_type_id, ld.priority from locality l inner join suburb s on l.SUBURB_ID = s.SUBURB_ID inner join landmarks ld on s.CITY_ID = ld.city_id where l.LONGITUDE not in (" . $latLongList . ") and l.LATITUDE not in (" . $latLongList . ") and ld.status = 'Active' and ld.place_type_id in (" . implode(",", self::$all_landmark_type_ids) . ")";
        self::connection()->query($sql);
    }

    static function deleteInsignificantEntries() {
        self::deleteEntriesBasedOnDistance();
        self::deleteEntriesBasedOnCount();
    }

    static function deleteEntriesBasedOnDistance() {
        foreach (self::$max_landmark_distance as $k => $v) {
            self::delete_all(array('conditions' => "place_type_id = $k and distance > $v"));
        }
    }

    static function deleteEntriesBasedOnCount() {

        foreach (self::$max_landmark_count as $landmarkType => $count) {
            $aRow = self::find('all', array('conditions' => array('place_type_id' => $landmarkType), 'group' => 'object_id, object_type', 'select' => 'object_id, object_type', 'having'=>"count(*)>$count"));
            foreach ($aRow as $row) {
                $sql = "delete ld.* from landmark_distances ld inner join (select id, distance from landmark_distances where object_id = $row->object_id and object_type = '$row->object_type' and place_type_id = $landmarkType order by distance limit 100000 OFFSET $count) t on ld.id = t.id;";
                self::connection()->query($sql);
            }
        }
    }

}
