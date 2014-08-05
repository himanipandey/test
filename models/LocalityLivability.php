<?php

// Model integration for resi_project
require_once "support/objects.php";

class LocalityLivability extends ActiveRecord\Model {

    static $table_name = 'locality_livability';
    static $livability_expression = '0.175*school+0.175*hospital+0.050*restaurant+0.25*metro_station+0.25*bus_stand+0.25*suburban_railway_station+0.025*city_railway_station+0.025*airport+0.1*park+0.1*market+0.1*completion_percentage';
    static $column_name_for_landmark_type = array(
        1 => 'school', // school
        2 => 'hospital', // hospital
        5 => 'restaurant',
        7 => 'metro_station',
        8 => 'bus_stand',
        9 => 'suburban_railway_station',
        13 => 'airport',
        16 => 'park',
        17 => 'market',
        1000 => 'city_railway_station'
    );
    static $distance_expression_for_landmark_type = array(
        1 => 'sum(exp(-(greatest(500, distance)*(0.1)/1000))/priority)',
        2 => 'sum(exp(-(greatest(500, distance)*(0.1)/1000))/priority)',
        5 => 'sum(exp(-(greatest(200, distance)*(0.1)/1000))/priority)',
        7 => 'sum(exp(-(greatest(200, distance)*(0.1)/1000))/priority)',
        8 => 'sum(exp(-(greatest(200, distance)*(0.1)/1000))/priority)',
        9 => 'sum(exp(-(greatest(500, distance)*(0.1)/1000))/priority)',
        13 => 'sum(exp(-(greatest(2000, distance)*(0.05)/1000))/priority)',
        16 => 'sum(exp(-(greatest(100, distance)/1000))/priority)',
        17 => 'sum(exp(-(greatest(200, distance)*(0.5)/1000))/priority)',
        1000 => 'sum(exp(-(greatest(500, distance)*(0.03)/1000))/priority)'
    );
    static $min_max_livability = 0.95;

    static function repopulateLocalityIds() {
        $sql = "insert into locality_livability (locality_id) select LOCALITY_ID from locality where STATUS = 'Active'";
        self::delete_all();
        self::connection()->query($sql);
    }

    static function populateDistanceIndex($landmarkTypeId) {
        $expression = self::$distance_expression_for_landmark_type[$landmarkTypeId];
        $columnName = self::$column_name_for_landmark_type[$landmarkTypeId];

        $localityUpdateSql = "update " . self::table_name() . " ll inner join (select object_id, $expression ranking from " . LandmarkDistance::table_name() . " where place_type_id = $landmarkTypeId and object_type = 'Locality' group by object_id) t on ll.locality_id = t.object_id set ll.$columnName = t.ranking";
        self::connection()->query($localityUpdateSql);

        self::normalizeColumnOnCity($columnName);
    }

    static function populateCompletionPercentage() {
        $sql = "update locality_livability ll inner join (select LOCALITY_ID, sum(complete) completion_percentage from (select rp.PROJECT_ID, rp.LOCALITY_ID, sum(if(rp.PROJECT_STATUS_ID in (4,3), supply, 0)) complete, sum(if(rp.PROJECT_STATUS_ID in (4,3), 0, supply)) not_complete, sum(supply) from resi_project rp inner join resi_project_phase rpp on rp.PROJECT_ID = rpp.PROJECT_ID and rpp.version = 'Website' and rp.version = 'Website' inner join listings l on rpp.PHASE_ID = l.phase_id and l.status = 'Active' inner join project_supplies ps on l.id = ps.listing_id and ps.version = 'Website' inner join (select rpp.PHASE_ID, rpp.PHASE_TYPE from resi_project_phase rpp inner join resi_project_phase rpp1 on rpp.PROJECT_ID = rpp1.PROJECT_ID and rpp1.version = 'Website' and rpp.version = 'Website' group by rpp.PHASE_ID having count(distinct rpp1.PHASE_TYPE) = 1 or rpp.PHASE_TYPE = 'Actual') t1 on rpp.PHASE_ID = t1.PHASE_ID group by rp.PROJECT_ID) t group by LOCALITY_ID) t on ll.locality_id = t.locality_id set ll.completion_percentage = t.completion_percentage where t.completion_percentage is not null";
        self::connection()->query($sql);
        self::normalizeColumnOnCity('completion_percentage');
    }

    static function populateOverAllLivability() {
        $sql = "update locality_livability set livability = " . self::$livability_expression;
        self::connection()->query($sql);

        $cityNormalizeSql = "update locality_livability ll inner join locality l on ll.locality_id = l.LOCALITY_ID inner join suburb s on s.SUBURB_ID = l.SUBURB_ID inner join (select s.CITY_ID, if(max(livability) > " . self::$min_max_livability . ", 1, " . self::$min_max_livability . "/max(livability)) factor from locality_livability ll inner join locality l on ll.locality_id = l.LOCALITY_ID inner join suburb s on s.SUBURB_ID = l.SUBURB_ID group by s.CITY_ID) t on s.CITY_ID = t.CITY_ID set ll.livability = ll.livability*t.factor";
        self::connection()->query($cityNormalizeSql);
    }

    static function getMaxValueForCoulmn($columnName) {
        $max = self::find('first', array('order' => "$columnName desc", 'limit' => 1, 'select' => "$columnName max"));
        if (!empty($max)) {
            return $max->max;
        }
        return null;
    }

    static function normalizeColumnOnCity($columnName) {
        $sql = "update locality_livability ll inner join locality l on ll.locality_id = l.LOCALITY_ID inner join suburb s on s.SUBURB_ID = l.SUBURB_ID inner join (select s.CITY_ID, max($columnName) max from locality_livability ll inner join locality l on ll.locality_id = l.LOCALITY_ID inner join suburb s on s.SUBURB_ID = l.SUBURB_ID group by s.CITY_ID) t on s.CITY_ID = t.CITY_ID set ll.$columnName = ll.$columnName/t.max";
        self::connection()->query($sql);
    }
    
    static function populateLivabilityInLocalities(){
    	$sql = "update locality l inner join locality_livability ll on l.locality_id = ll.locality_id set l.livability_score = ROUND(ll.livability*10,1)";
    	self::connection()->query($sql);
    }
}
