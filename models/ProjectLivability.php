<?php

// Model integration for resi_project
require_once "support/objects.php";
require_once (dirname(__FILE__) . '/../cron/cronConfig.php');

class ProjectLivability extends ActiveRecord\Model {

    static $table_name = 'project_livability';
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
    static $custom_project_livability_expression = array(
        'builder' => 'CASE rb.DISPLAY_ORDER WHEN 1 THEN 1 WHEN 2 THEN 1 WHEN 3 THEN 0.8 WHEN 4 THEN 0.8 WHEN 5 THEN 0.6 WHEN 6 THEN 0.6 WHEN 7 THEN 0.5 WHEN 8 THEN 0.5 WHEN 9 THEN 0.4 WHEN 10 THEN 0.4 WHEN 11 THEN 0.3 WHEN 12 THEN 0.3 WHEN 13 THEN 0.3 ELSE 0.1 END',
        'overall' => '(0.175*pl.school+0.175*pl.hospital+0.050*pl.restaurant+0.035*pl.metro_station+0.035*pl.bus_stand+0.035*pl.suburban_railway_station+0.025*pl.city_railway_station+0.025*pl.airport+0.1*pl.park+0.1*pl.market)*0.2+0.075*pl.clubhouse+0.075*pl.power_backup+0.075*pl.children_play_area+0.05*pl.security+0.05*pl.other_amenity_count+if(pl.unit_per_floor=0,0.2*pl.unit_count,0.1*pl.unit_count+0.1*pl.unit_per_floor)+0.225*pl.builder+0.05*ll.completion_percentage'
    );
    static $min_max_livability = 0.95;

    static function repopulateProjectIds() {
        $sql = "insert into project_livability (project_id) select PROJECT_ID from resi_project where version = 'Website' and STATUS = 'Active'";
        self::delete_all();
        self::connection()->query($sql);
    }

    static function populateDistanceIndex($landmarkTypeId) {
        $expression = self::$distance_expression_for_landmark_type[$landmarkTypeId];
        $columnName = self::$column_name_for_landmark_type[$landmarkTypeId];


        $projectUpdateSql = "update " . self::table_name() . " pl inner join (select object_id, $expression ranking from " . LandmarkDistance::table_name() . " where place_type_id = $landmarkTypeId and object_type = 'Project' group by object_id) t on pl.project_id = t.object_id set pl.$columnName = t.ranking";
        self::connection()->query($projectUpdateSql);

        self::normalizeColumnOnCity($columnName);
    }

    static function populateClubhouse() {
        $sql = "update project_livability pl inner join (select rp.PROJECT_ID from resi_project rp inner join resi_project_amenities rpa on rp.PROJECT_ID = rpa.PROJECT_ID where rp.version = 'Website' and rpa.AMENITY_ID = 4) t on pl.PROJECT_ID = t.PROJECT_ID set pl.clubhouse = 1";
        self::connection()->query($sql);
    }

    static function populateSecurity() {
        $sql = "update project_livability pl inner join (select rp.PROJECT_ID from resi_project rp inner join resi_project_amenities rpa on rp.PROJECT_ID = rpa.PROJECT_ID where rp.version = 'Website' and rpa.AMENITY_ID = 11) t on pl.PROJECT_ID = t.PROJECT_ID set pl.security = 1";
        self::connection()->query($sql);
    }

    static function populatePowerBackup() {
        $sql = "update project_livability pl inner join (select rp.PROJECT_ID from resi_project rp inner join resi_project_amenities rpa on rp.PROJECT_ID = rpa.PROJECT_ID where rp.version = 'Website' and rpa.AMENITY_ID = 13) t on pl.PROJECT_ID = t.PROJECT_ID set pl.power_backup = 1";
        self::connection()->query($sql);
    }

    static function populateChildrenPlayArea() {
        $sql = "update project_livability pl inner join (select rp.PROJECT_ID from resi_project rp inner join resi_project_amenities rpa on rp.PROJECT_ID = rpa.PROJECT_ID where rp.version = 'Website' and rpa.AMENITY_ID = 3) t on pl.PROJECT_ID = t.PROJECT_ID set pl.children_play_area = 1";
        self::connection()->query($sql);
    }

    static function populateOtherAmenity() {
        $sql = "update project_livability pl inner join (select rp.PROJECT_ID, count(*) count from resi_project rp inner join resi_project_amenities rpa on rp.PROJECT_ID = rpa.PROJECT_ID where rp.version = 'Website' and rpa.AMENITY_ID not in (3, 4, 13, 11) group by rpa.PROJECT_ID) t on pl.PROJECT_ID = t.PROJECT_ID set pl.other_amenity_count = t.count";
        self::connection()->query($sql);

        $cityNormalizeSql = "update project_livability pl inner join resi_project rp on pl.project_id = rp.PROJECT_ID and rp.version = 'Website' inner join locality l on rp.LOCALITY_ID = l.LOCALITY_ID inner join suburb s on s.SUBURB_ID = l.SUBURB_ID inner join (select s.CITY_ID, max(other_amenity_count) max from project_livability pl inner join resi_project rp on pl.project_id = rp.PROJECT_ID and rp.version = 'Website' inner join locality l on rp.LOCALITY_ID = l.LOCALITY_ID inner join suburb s on s.SUBURB_ID = l.SUBURB_ID group by s.CITY_ID) t on s.CITY_ID = t.CITY_ID set pl.other_amenity_count = pl.other_amenity_count/t.max";
        self::connection()->query($cityNormalizeSql);
    }

    static function populateUnitCount() {
//        $maxSql = "select sum(supply) max from resi_project rp inner join resi_project_phase rpp on rp.PROJECT_ID = rpp.PROJECT_ID and rpp.version = 'Website' and rp.version = 'Website' inner join listings l on rpp.PHASE_ID = l.phase_id and l.status = 'Active' inner join project_supplies ps on l.id = ps.listing_id and ps.version = 'Website' inner join (select rpp.PHASE_ID, rpp.PHASE_TYPE from resi_project_phase rpp inner join resi_project_phase rpp1 on rpp.PROJECT_ID = rpp1.PROJECT_ID and rpp1.version = 'Website' and rpp.version = 'Website' group by rpp.PHASE_ID having count(distinct rpp1.PHASE_TYPE) = 1 or rpp.PHASE_TYPE = 'Actual') t1 on rpp.PHASE_ID = t1.PHASE_ID group by rp.PROJECT_ID order by sum(supply) desc limit 1";
//        $maxVal = self::find_by_sql($maxSql);
//        $maxVal = $maxVal[0]->max;

        $updateSql = "update project_livability pl inner join (select rp.PROJECT_ID, sum(supply) supply from resi_project rp inner join resi_project_phase rpp on rp.PROJECT_ID = rpp.PROJECT_ID and rpp.version = 'Website' and rp.version = 'Website' inner join listings l on rpp.PHASE_ID = l.phase_id and l.status = 'Active' inner join project_supplies ps on l.id = ps.listing_id and ps.version = 'Website' inner join (select rpp.PHASE_ID, rpp.PHASE_TYPE from resi_project_phase rpp inner join resi_project_phase rpp1 on rpp.PROJECT_ID = rpp1.PROJECT_ID and rpp1.version = 'Website' and rpp.version = 'Website' group by rpp.PHASE_ID having count(distinct rpp1.PHASE_TYPE) = 1 or rpp.PHASE_TYPE = 'Actual') t1 on rpp.PHASE_ID = t1.PHASE_ID group by rp.PROJECT_ID) t on pl.project_id = t.PROJECT_ID set pl.unit_count = t.supply;";
        self::connection()->query($updateSql);
        self::normalizeColumnOnCity('unit_count');
    }

    static function populateUnitPerFloor() {
        $aFloorCount = ResiProjectTowerDetails::getFloorCountForAllProjects();
        $aProjectSupply = ProjectSupply::getSupplyForAllProjects();
        $aProjectSupply = indexArrayOnKey($aProjectSupply, 'project_id');
        foreach ($aFloorCount as $floorCount) {
            $projectId = $floorCount->project_id;
            if (isset($aProjectSupply[$projectId])) {
                $unitsPerFloor = $aProjectSupply[$projectId]->supply / $floorCount->floor_count;
                self::update_all(array('conditions' => array('project_id' => $projectId), 'set' => "unit_per_floor = $unitsPerFloor"));
            }
        }

        self::normalizeColumnOnCity('unit_per_floor');

//        $maxval = self::getMaxValueForCoulmn('unit_per_floor');
//        self::update_all(array('set' => "unit_per_floor = unit_per_floor/$maxval"));
    }

    static function populateBuilder() {
        $sql = "update project_livability pl inner join (select rp.PROJECT_ID, " . self::$custom_project_livability_expression['builder'] . " BUILDER_RATING from resi_project rp inner join resi_builder rb on rp.BUILDER_ID = rb.BUILDER_ID where rp.version = 'Website') t on pl.PROJECT_ID = t.PROJECT_ID set pl.builder = BUILDER_RATING";
        self::connection()->query($sql);
        self::normalizeColumnOnCity('builder');
    }

    static function populateOverAllLivability() {
        $sql = "update project_livability pl inner join resi_project rp on pl.project_id = rp.PROJECT_ID and rp.version = 'Website' inner join locality_livability ll on rp.LOCALITY_ID = ll.locality_id set pl.livability = " . self::$custom_project_livability_expression['overall'];
        self::connection()->query($sql);
//        $maxVal = self::getMaxValueForCoulmn('livability');
//        if ($maxVal < self::$min_max_livability) {
//            $factor = self::$min_max_livability / $maxVal;
//            self::update_all(array('set' => "livability = livability*$factor"));
//        }

        $normalizeCitySql = "update project_livability pl inner join resi_project rp on pl.project_id = rp.PROJECT_ID and rp.version = 'Website' inner join locality l on rp.LOCALITY_ID = l.LOCALITY_ID inner join suburb s on s.SUBURB_ID = l.SUBURB_ID inner join (select s.CITY_ID, if(max(livability) > " . self::$min_max_livability . ", 1, " . self::$min_max_livability . "/max(livability)) factor from project_livability pl inner join resi_project rp on pl.project_id = rp.PROJECT_ID and rp.version = 'Website' inner join locality l on rp.LOCALITY_ID = l.LOCALITY_ID inner join suburb s on s.SUBURB_ID = l.SUBURB_ID group by s.CITY_ID) t on s.CITY_ID = t.CITY_ID set pl.livability = pl.livability*t.factor";
        self::connection()->query($normalizeCitySql);
    }

    static function getMaxValueForCoulmn($columnName) {
        $max = self::find('first', array('order' => "$columnName desc", 'limit' => 1, 'select' => "$columnName max"));
        if (!empty($max)) {
            return $max->max;
        }
        return null;
    }

    static function normalizeColumnOnCity($columnName) {
        $sql = "update project_livability pl inner join resi_project rp on pl.project_id = rp.PROJECT_ID and rp.version = 'Website' inner join locality l on rp.LOCALITY_ID = l.LOCALITY_ID inner join suburb s on s.SUBURB_ID = l.SUBURB_ID inner join (select s.CITY_ID, max($columnName) max from project_livability pl inner join resi_project rp on pl.project_id = rp.PROJECT_ID and rp.version = 'Website' inner join locality l on rp.LOCALITY_ID = l.LOCALITY_ID inner join suburb s on s.SUBURB_ID = l.SUBURB_ID group by s.CITY_ID) t on s.CITY_ID = t.CITY_ID set pl.$columnName = pl.$columnName/t.max";
        self::connection()->query($sql);
    }

}
