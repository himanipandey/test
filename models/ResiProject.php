<?php

// Model integration for bank list
require_once "support/objects.php";
class ResiProject extends Objects
{
    static $table_name = 'resi_project';
    static $default_scope = array("version" => "cms");
    static $virtual_primary_key = 'PROJECT_ID';

    static $has_many = array(
        array('resi_amenities', 'class_name' => "ResiProjectAmenities", "foreign_key" => "PROJECT_ID"),
        array('audits', 'class_name' => "Audit", "foreign_key" => "PROJECT_ID"),
        array('call_projects', 'class_name' => "CallProject", "foreign_key" => "ProjectId"),
        array('options', 'class_name' => "ResiProjectOptions", "foreign_key" => "PROJECT_ID"),
        array('phases', 'class_name' => "ResiProjectPhase", "foreign_key" => "PROJECT_ID"),
   );


    public function get_all_towers(){
        $phase_ids = array();
        $phases = ResiProjectPhase::find("all", array("conditions" => array("project_id" => $this->project_id)));
        foreach($phases as $phase) array_push($phase_ids, $phase->phase_id);
        return ResiProjectPhase::get_towers_for_phases($phase_ids);
    }
}
