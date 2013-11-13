<?php

// Model integration for project stage
class ProjectStage extends ActiveRecord\Model
{
    static $table_name = 'master_project_stages';
    static function getProjectStages() {
        $getStages = ProjectStage::find('all');
        return $getStages;
    }
    static function getStageByName($stageName) {
        $stageDetail = ProjectStage::find('all',array('conditions'=>array('name = ?',$stageName)));
        return $stageDetail;
    }
}