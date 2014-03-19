<?php

// Model integration for resi_project
require_once "support/objects.php";

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

    static function repopulateProjectIds() {
        $sql = "insert into project_livability (project_id) select PROJECT_ID from resi_project group by PROJECT_ID;";
        self::delete_all();
        self::connection()->query($sql);
    }

    static function populateDistanceIndex($landmarkTypeId) {
        $expression = self::$distance_expression_for_landmark_type[$landmarkTypeId];
        $columnName = self::$column_name_for_landmark_type[$landmarkTypeId];

        $projectMaxSql = "select $expression ranking from " . LandmarkDistance::table_name() . " where place_type_id = $landmarkTypeId and object_type = 'Project' group by object_id order by ranking desc limit 1";

        $projectMaxVal = self::find_by_sql($projectMaxSql);
        if (count($projectMaxVal) > 0) {
            $projectMaxVal = $projectMaxVal[0]->ranking;
            $projectUpdateSql = "update " . self::table_name() . " pl inner join (select object_id, $expression/$projectMaxVal ranking from " . LandmarkDistance::table_name() . " where place_type_id = $landmarkTypeId and object_type = 'Project' group by object_id) t on pl.project_id = t.object_id set pl.$columnName = t.ranking";
            self::connection()->query($projectUpdateSql);
        }
    }

}
