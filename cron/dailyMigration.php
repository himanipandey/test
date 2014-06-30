<?php
/*
Objects V0.1
Authored by: Azitabh Ajit
Date: 19/12/2013
 */

$docroot = dirname(__FILE__) . "/../";
require_once $docroot.'modelsConfig.php';

ResiProject::delete_website_version();
ResiProject::partially_migrate_projects();
//Locality::updateLocalityCoordinates();

$missingProjects = ResiProject::get_recent_projects_without_website_version(86400*2);

foreach ($missingProjects as $project) {
    $projectMigration = new ProjectMigration();
    
    
    $projectMigration->project_id = $project->project_id;
    $projectMigration->migration_type = 'NewProject';
    $projectMigration->version_from = 'Cms';
    $projectMigration->version_to = 'Website';
    $projectMigration->status = 'Waiting';
    $projectMigration->created_at = 'NOW()';
    $projectMigration->created_by = SYSTEM_USER_ID;

    $projectMigration->save();
}
?>