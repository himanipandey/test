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

$missingProjects = ResiProject::get_projects_without_website_version();


foreach ($missingProjects as $project) {
    ProjectMigration::connection()->transaction();
        $result = [];
        $result[] = ResiProject::copy_cms_to_website($project->project_id, $project->updated_by);
        $allPhases = ResiProject::get_all_phases($project->project_id);
        foreach ($allPhases as $phase) {
            $result[] = ResiProjectPhase::copy_cms_to_website($phase->phase_id, $phase->updated_by);
        }
        $result[] = ProjectAvailability::copyProjectInventoryToWebsite($project->project_id, $project->updated_by);
        $result[] = ListingPrices::copyProjectPriceToWebsite($project->project_id, $project->updated_by);
        if(!($result === array_filter($result))){
            ProjectMigration::connection()->rollback();
        }
    ProjectMigration::connection()->commit();
}
?>
