<?php

// Model integration for project_migration
require_once "support/objects.php";
class ProjectMigration extends ActiveRecord\Model
{
    static $table_name = 'project_migration';
    
    public static function enqueProjectForMigration($projectId, $migrationType, $adminId){
        $attributes = array(
            'project_id'=>$projectId,
            'migration_type'=>$migrationType,
            'version_from'=>'Cms',
            'version_to'=>'Website',
            'status'=>'Waiting',
            'created_by'=>$adminId,
            'created_at'=>'NOW()'
        );
        return self::create($attributes);
    }
    
    public static function isProjectWaitingForMigration($projectId){
        return self::count(array('conditions'=>array('project_id'=>$projectId, 'status'=>'Waiting')));
    }
    
    public static function getAllProjectsWaitingMigration(){
        return ResiProject::all(array('joins'=>"inner join project_migration pm on resi_project.PROJECT_ID = pm.PROJECT_ID and resi_project.version = 'Cms' and pm.status in ('Waiting', 'Error')", 'select'=>"resi_project.PROJECT_ID, resi_project.PROJECT_NAME, pm.status"));
    }
}
