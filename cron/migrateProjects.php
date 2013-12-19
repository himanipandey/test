<?php
/*
Objects V0.1
Authored by: Azitabh Ajit
Date: 19/12/2013
 */

$docroot = dirname(__FILE__) . "/../";

//require_once $docroot.'dbConfig.php';
require_once $docroot.'modelsConfig.php';
//require_once 'cronFunctions.php';
//require_once $docroot.'includes/send_mail_amazon.php';


$allItems = ProjectMigration::find('all', array('conditions'=>array('status'=>'Waiting'), 'select'=>'id'));

foreach ($allItems as $item) {
    $result = array();
    ProjectMigration::connection()->transaction();
        $itemForUpdate = ProjectMigration::find_by_sql("select * from project_migration where id = ".$item->id." for update");
        $itemForUpdate = $itemForUpdate[0];
        if($itemForUpdate->status === 'Waiting'){
            ProjectMigration::find_by_sql("select * from project_migration where project_id = ".$itemForUpdate->project_id." for update");
            $projectId = $itemForUpdate->project_id;
            $adminId = $itemForUpdate->created_by;
            
            $result[] = ResiProject::copy_cms_to_website($projectId, $adminId);
            $allPhases = ResiProject::get_all_phases($projectId);
            foreach ($allPhases as $phase) {
                $result[] = ResiProjectPhase::copy_cms_to_website($phase->phase_id, $adminId);
            }
            $result[] = ProjectAvailability::copyProjectInventoryToWebsite($projectId, $adminId);
            $result[] = ListingPrices::copyProjectPriceToWebsite($projectId, $adminId);
        }
        if($result === array_filter($result)){
            ProjectMigration::update_all(array('set' => array('status' => 'Processed'),'conditions' => array('id' => $itemForUpdate->id)));
        }
        else{
            ProjectMigration::connection()->rollback();
            $itemForUpdate->status = 'Error';
            $itemForUpdate->save();
        }
    ProjectMigration::connection()->commit();
}
?>
