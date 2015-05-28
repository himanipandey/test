<?php
    $accessMigrate = '';
    if( $migrateAuth == false )
       $accessMigrate = "No Access";
    $smarty->assign("accessMigrate",$accessMigrate);

    $projectId = trim($_REQUEST['projectId']);
    $projectIdArr = array();
    $notVerifiedPids = array();
    foreach (explode(',', $projectId) as $pid) {
		//checking ig project is verified or not
        $is_verified = ProjectSupply::isSupplyLaunchVerified($pid);
        if($is_verified)
          $projectIdArr[] = filter_var($pid, FILTER_SANITIZE_NUMBER_INT);
        else
          $notVerifiedPids[]= $pid;
    }
    $projectId = implode(',', $projectIdArr);
    
    $smarty->assign("projectId", $projectId);
    $msg = '';
    if(isset($_REQUEST['submit']) && $projectId != '')
    {
        $tmpFile = '/tmp/project_ids.txt';
        $handle = fopen($tmpFile, "w");
        fwrite($handle, str_replace(',', "\n", $projectId));
        
        foreach ($projectIdArr as $pid) {
            ProjectMigration::enqueProjectForMigration($pid, 'Forced', $_SESSION['adminId']);
        }
        exec("/usr/bin/php ".strval(dirname(__FILE__))."/cron/migrateProjects.php  > /dev/null 2>/dev/null &");
        
        fclose($handle);
        //exec("ssh sysadmin@10.0.0.26 cd /home/sysadmin/production/cron/migration ; php migrateRefData.php ; php main.php $tmpFile");
        //exec("ssh sysadmin@10.0.0.153 cd /home/sysadmin/production/solr/ ; php solrIndex.php $projectId");
        //exec("ssh sysadmin@10.0.0.26 cd /home/sysadmin/production/solr/ ; php solrIndex.php $projectId");
        $msg = "Successfully migrated following ProjectIds:<br>$projectId";

    }
    
    if(count($notVerifiedPids))
      $smarty->assign("Errmsg", implode(",",$notVerifiedPids)." - These projects are not verified so Not Migrated!");
    $smarty->assign("msg", $msg);
?>
