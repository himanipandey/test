<?php

// Model integration for project stage
class ProjectPhase extends ActiveRecord\Model
{
    static $table_name = 'master_project_phases';
    static function getProjectPhases() {
        $getPhases = ProjectPhase::find('all',array('conditions'=>array('name != ?','revert')));
        return $getPhases;
    }
    static function getPhaseByName($phaseName) {
        $phaseDetail = ProjectPhase::find('all',array('conditions'=>array('name = ?',$phaseName)));
        return $phaseDetail;
    }
}
