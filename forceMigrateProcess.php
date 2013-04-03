<?php
	$projectId = trim($_REQUEST['projectId']);
	$smarty->assign("projectId", $projectId);
	$msg = '';
	if(isset($_REQUEST['submit']))
	{

			$tmpFile = '/tmp/project_ids.txt';
			$handle = fopen($tmpFile, "w");
			fwrite($handle, str_replace(',', "\n", $projectId));
			fclose($handle);
			exec("cd /home/sysadmin/production/cron/migration; php main.php $tmpFile");	
			
			$res = mysql_query('delete from proptiger.REDIRECT_URL_MAP;');
			$res = mysql_query('insert into proptiger.REDIRECT_URL_MAP select * from project.redirect_url_map;');
			if($res)
				$msg = "Successfully migrated following ProjectIds:<br>$projectId";
			else
				$msg = "Problem in migration for following ProjectIds:<br>$projectId";
			
	}
	$smarty->assign("msg", $msg);
	
	

?>