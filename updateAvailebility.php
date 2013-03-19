<?php

	error_reporting(1);
	ini_set('display_errors','1');
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
	
	$qry = "SELECT PROJECT_ID FROM resi_project";
	$res = mysql_query($qry) or die(mysql_error());
	while($data = mysql_fetch_assoc($res))
	{
		 $returnAvailability = computeAvailability($data['PROJECT_ID']);
		if($returnAvailability)
		{
			$updateProject = updateAvailability($data['PROJECT_ID'],$returnAvailability);
			if($updateProject)
			{
				echo "Data has been updateed successfully for: ".$data['PROJECT_ID']."<br>";
			}
			else
			{
				echo "Problem in data updation for :".$data['PROJECT_ID']."<br>";
			}
		}
	}

	
	
?>
