<?php
error_reporting(1);
ini_set('display_errors','1');
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
	
	$qry = "SELECT PROJECT_ID FROM resi_project";
	$res = mysql_query($qry);
	while($data = mysql_fetch_assoc($res))
	{
		$returnAvailability = computeAvailability($data['PROJECT_ID']);
		if($returnAvailability)
		{
			$updateProject = updateAvailability($data['PROJECT_ID'],$returnAvailability);
			audit_insert($data['PROJECT_ID'],'update','resi_project',$data['PROJECT_ID']);
		}
	}
	
?>
