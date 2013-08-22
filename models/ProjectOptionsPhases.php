<?php

// Model integration for project option phases
class ProjectOptionsPhases extends ActiveRecord\Model
{
    static $table_name = 'project_options_phases';
    static $belongs_to = array(
      array("phase", "class_name" => "ResiProjectPhase", "foreign_key" => "phase_id"),
      array("option", "class_name" => "ResiProjectOptions", "foreign_key" => "option"),
    );
    
    function optionsForPhase($phaseId){
        $result = array();
        $ids = self::all(array('phase_id'=>$phaseId));
        foreach ($ids as $v) {
            $result[] = $v->option_id;
        }
        return $result;
    }
}