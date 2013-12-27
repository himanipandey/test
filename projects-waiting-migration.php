<?php
error_reporting(1);
ini_set('display_errors','1');

include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("modelsConfig.php");
include("includes/configs/configs.php");

AdminAuthentication();
$dept = $_SESSION['DEPARTMENT'];

$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");

$all = ProjectMigration::getAllProjectsWaitingMigration();

$displayData = array();
foreach ($all as $value) {
    $row = array();
    $row['PROJECT_ID'] = $value->project_id;
    $row['PROJECT_NAME'] = $value->project_name;
    $row['STATUS'] = $value->status;
    $displayData[] = $row;
}

$smarty->assign("displayData", $displayData);
$smarty->display(PROJECT_ADD_TEMPLATE_PATH."projects-waiting-migration.tpl");
?>