<?php

// Model integration for resi_project
require_once "support/objects.php";

class LocalityLivability extends ActiveRecord\Model {

    static $table_name = 'locality_livability';
    static $livability_expression = '0.175*ll.school+0.175*ll.hospital+0.050*ll.restaurant+0.035*ll.metro_station+0.035*ll.bus_stand+0.035*ll.suburban_railway_station+0.025*ll.city_railway_station+0.025*ll.airport+0.05*ll.park+0.05*ll.market+0.1*ll.completion_percentage';
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
        1 => 'sum(exp(-(greatest(500, distance)*(0.1)/1000))/priority)',
        2 => 'sum(exp(-(greatest(500, distance)*(0.1)/1000))/priority)',
        5 => 'sum(exp(-(greatest(500, distance)*(0.1)/1000))/priority)',
        7 => 'sum(exp(-(greatest(500, distance)*(0.1)/1000))/priority)',
        8 => 'sum(exp(-(greatest(500, distance)*(0.1)/1000))/priority)',
        9 => 'sum(exp(-(greatest(500, distance)*(0.1)/1000))/priority)',
        13 => 'sum(exp(-(greatest(500, distance)*(0.05)/1000))/priority)',
        1000 => 'sum(exp(-(greatest(500, distance)*(0.03)/1000))/priority)',
        2000 => 'count(*)',
        3000 => 'count(*)'
    );

    static function repopulateLocalityIds() {
        $sql = "insert into locality_livability (locality_id) select LOCALITY_ID from locality";
        self::delete_all();
        self::connection()->query($sql);
    }

    static function populateDistanceIndex($landmarkTypeId) {
        $expression = self::$distance_expression_for_landmark_type[$landmarkTypeId];
        $columnName = self::$column_name_for_landmark_type[$landmarkTypeId];

        $localityMaxSql = "select $expression ranking from " . LandmarkDistance ::table_name() . " where place_type_id = $landmarkTypeId and object_type = 'Locality' group by object_id order by ranking desc limit 1";

        $localityMaxVal = self::find_by_sql($localityMaxSql);
        if (count($localityMaxVal) > 0) {
            $localityMaxVal = $localityMaxVal[0]->ranking;
            $localityUpdateSql = "update " . self::table_name() . " ll inner join (select object_id, $expression/$localityMaxVal ranking from " . LandmarkDistance::table_name() . " where place_type_id = $landmarkTypeId and object_type = 'Locality' group by object_id) t on ll.locality_id = t.object_id set ll.$columnName = t.ranking";
            self::connection()->query($localityUpdateSql);
        }
    }

    static function populateCompletionPercentage() {
        $sql = "update locality_livability ll inner join (select LOCALITY_ID, (sum(complete)/(sum(complete)+sum(not_complete))) completion_percentage from (select rp.PROJECT_ID, rp.LOCALITY_ID, sum(if(rp.PROJECT_STATUS_ID in (4,5), supply, 0)) complete, sum(if(rp.PROJECT_STATUS_ID in (4,5), 0, supply)) not_complete, sum(supply) from resi_project rp inner join resi_project_phase rpp on rp.PROJECT_ID = rpp.PROJECT_ID and rpp.version = 'Cms' and rp.version = 'Cms' inner join listings l on rpp.PHASE_ID = l.phase_id and l.status = 'Active' inner join project_supplies ps on l.id = ps.listing_id and ps.version = 'Cms' inner join (select rpp.PHASE_ID, rpp.PHASE_TYPE from resi_project_phase rpp inner join resi_project_phase rpp1 on rpp.PROJECT_ID = rpp1.PROJECT_ID and rpp1.version = 'Cms' and rpp.version = 'Cms' group by rpp.PHASE_ID having count(distinct rpp1.PHASE_TYPE) = 1 or rpp.PHASE_TYPE = 'Actual') t1 on rpp.PHASE_ID = t1.PHASE_ID group by rp.PROJECT_ID) t group by LOCALITY_ID) t on ll.locality_id = t.locality_id set ll.completion_percentage = t.completion_percentage where t.completion_percentage is not null";
        self::connection()->query($sql);
    }

    static function populateOverAllLivability() {
        $sql = "update locality_livability ll1 inner join locality_livability ll on ll1.id = ll.id set ll1.livability = " . self::$livability_expression;
        self::connection()->query($sql);
    }

}
