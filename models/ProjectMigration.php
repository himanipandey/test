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
}
