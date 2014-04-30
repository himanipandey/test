<?php
    $accessMigrate = '';
    if( $migrateAuth == false )
       $accessMigrate = "No Access";
    $smarty->assign("accessMigrate",$accessMigrate);

    $projectId = trim($_REQUEST['projectId']);
    $projectIdArr = array();
    foreach (explode(',', $projectId) as $pid) {
        $projectIdArr[] = filter_var($pid, FILTER_SANITIZE_NUMBER_INT);
    }
    $projectId = implode(',', $projectIdArr);
    
    $smarty->assign("projectId", $projectId);
    $msg = '';
    if(isset($_REQUEST['submit']))
    {
        $tmpFile = '/tmp/project_ids.txt';
        $handle = fopen($tmpFile, "w");
        fwrite($handle, str_replace(',', "\n", $projectId));
        
        foreach ($projectIdArr as $pid) {
            ProjectMigration::enqueProjectForMigration($pid, 'Forced', $_SESSION['adminId']);
        }
        exec("/usr/bin/php ".strval(dirname(__FILE__))."/cron/migrateProjects.php  > /dev/null 2>/dev/null &");
        
        fclose($handle);
        exec("cd ".SERVER_PATH_SOLR_RESTART."/cron/migration; php migrateRefData.php; php main.php $tmpFile");
        exec("php ".SERVER_PATH_SOLR_RESTART."/solr/solrIndex.php $projectId");
        $msg = "Successfully migrated following ProjectIds:<br>$projectId";

    }
    $smarty->assign("msg", $msg);
?>
