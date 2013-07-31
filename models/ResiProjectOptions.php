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
}