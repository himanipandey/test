<?php

// Model integration for resi_project
require_once "support/objects.php";

class LocalityLivability extends ActiveRecord\Model {

    static $table_name = 'locality_livability';
    static $column_name_for_landmark_type = array(
        1 => 'school',
        2 => 'hospital',
        5 => 'restaurant',
        7 => 'metro_station',
        8 => 'bus_stand',
        9 => 'suburban_railway_station',
        13 => 'airport',
        1000 => 'city_railway_station',
        2000 => 'park',
        3000 => 'market'
    );
    static $distance_expression_for_landmark_type = array(
        1 => 'exp(-(greatest(500, distance)*(0.1)/1000))/priority',
        2 => 'exp(-(greatest(500, distance)*(0.1)/1000))/priority',
        5 => 'exp(-(greatest(500, distance)*(0.1)/1000))/priority',
        7 => 'exp(-(greatest(500, distance)*(0.1)/1000))/priority',
        8 => 'exp(-(greatest(500, distance)*(0.1)/1000))/priority',
        9 => 'exp(-(greatest(500, distance)*(0.1)/1000))/priority',
        13 => 'exp(-(greatest(500, distance)*(0.05)/1000))/priority',
        1000 => 'exp(-(greatest(500, distance)*(0.03)/1000))/priority',
        2000 => 'exp(-(greatest(500, distance)*(0.1)/1000))/priority',
        3000 => 'exp(-(greatest(500, distance)*(0.05)/1000))/priority'
    );

    static function repopulateLocalityIds() {
        $sql = "insert into locality_livability (locality_id) select LOCALITY_ID from locality";
        self::delete_all();
        self::connection()->query($sql);
    }

    static function populateDistanceIndex($landmarkTypeId) {
        $expression = self::$distance_expression_for_landmark_type[$landmarkTypeId];
        $columnName = self::$column_name_for_landmark_type[$landmarkTypeId];

        $localityMaxSql = "select sum($expression) ranking from " . LandmarkDistance ::table_name() . " where place_type_id = $landmarkTypeId and object_type = 'Locality' group by object_id order by ranking desc limit 1";

        $localityMaxVal = self::find_by_sql($localityMaxSql);
        if (count($localityMaxVal) > 0) {
            $localityMaxVal = $localityMaxVal[0]->ranking;
            $localityUpdateSql = "update " . self::table_name() . " ll inner join (select object_id, sum($expression)/$localityMaxVal ranking from " . LandmarkDistance::table_name() . " where place_type_id = $landmarkTypeId and object_type = 'Locality' group by object_id) t on ll.locality_id = t.object_id set ll.$columnName = t.ranking";
            self::connection()->query($localityUpdateSql);
        }
    }

}
