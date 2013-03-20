<?php
	$projectId = trim($_REQUEST['projectId']);
	$smarty->assign("projectId", $projectId);
	
	if(isset($_REQUEST['submit']))
	{
		function forceMigrateProjects($commaSeparatedProjectIds)
		{
			$tmpFile = '/tmp/project_ids.txt';
			$handle = fopen($tmpFile, "w");
			fwrite($handle, str_replace(',', "\n", $commaSeparatedProjectIds));
			fclose($handle);
			exec("cd /home/sysadmin/production/cron/migration; php main.php $tmpFile");
		}		
	}
	
	

?>