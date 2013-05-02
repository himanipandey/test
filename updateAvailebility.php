<?php
    set_time_limit(0);
	error_reporting(1);
	ini_set('display_errors','1');
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
	
	$qry = "SELECT PROJECT_ID FROM resi_project";
	$res = mysql_query($qry) or die(mysql_error());
	$arr = array();
	while($data = mysql_fetch_assoc($res))
	{
		$arr[] = $data['PROJECT_ID'];
	}
	foreach($arr as $val)
	{
	 	$returnAvailability = computeAvailability($val);
		$updateProject = updateAvailability($val,$returnAvailability);
		if($updateProject)
		{
			echo "Data has been updateed successfully for: ".$val."<br>";
		}
		else
		{
			echo "Problem in data updation for :".$val."<br>";
		}
	}

	
	
?>
