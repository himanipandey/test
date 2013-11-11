<?php

// Model integration for bank list
class ResiProjectOptions extends ActiveRecord\Model
{
    static $table_name = 'resi_project_options';

    static $has_many = array(
        array('phases', 'class_name' => "ResiProjectPhase", "foreign_key" => "option_id",
            "association_foreign_key" => "phase_id", "join_table" => "project_options_phases"),
    );

    public function phases(){
        $join = "LEFT JOIN project_options_phases p on (resi_project_phase.PHASE_ID = p.phase_id)";
        return ResiProjectPhase::all(array("joins" => $join, "conditions" => array("option_id = ?", $this->options_id)));
    }

//   gives no of beds corresponding to given option ids
    function optionwise_bedroom_details($options) {
        if(empty($options)) array_push($options, 'NULL');
        $query = "SELECT option_type as UNIT_TYPE, GROUP_CONCAT(Distinct BEDROOMS) as BEDS
         FROM ".self::$table_name." WHERE OPTIONS_ID in  ('".join("','", $options)."')
         GROUP BY UNIT_TYPE ORDER BY UNIT_TYPE, BEDROOMS";
        $beds_per_apartment = ResiProjectOptions::find_by_sql($query);
        return $beds_per_apartment;
    }
}
