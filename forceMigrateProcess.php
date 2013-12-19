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
            enqueProjectForMigration($pid, 'Forced');
        }
        
        fclose($handle);
        exec("cd /home/sysadmin/production/cron/migration; php migrateRefData.php; php main.php $tmpFile");	
        $msg = "Successfully migrated following ProjectIds:<br>$projectId";

    }
    $smarty->assign("msg", $msg);

?>
