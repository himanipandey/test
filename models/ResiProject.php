<?php

// Model integration for bank list
class ResiProject extends ActiveRecord\Model
{
    static $table_name = 'resi_project';
    static $has_many = array(
        array('resi_amenities', 'class_name' => "ResiProjectAmenities", "foreign_key" => "PROJECT_ID"),
        array('audits', 'class_name' => "Audit", "foreign_key" => "PROJECT_ID"),
        array('call_projects', 'class_name' => "CallProject", "foreign_key" => "ProjectId"),
        array('project_infra_mappings', 'class_name' => "ProjectInfraMapping", "foreign_key" => "PROJECT_ID"),

   );
}