<?php

	include("dbConfig.php");
	include("appWideConfig.php");
	$case = '';
	if(isset($_POST))
	{
		$case = $_POST['case'];
	}
	switch($case)
	{
		case "showunit":
		if(isset($_POST))
					{	
						$phaseid = $_POST['phaseid'];
						$bed = $_POST['bed'];
						$unittype = $_POST['unittype'];
					}

					if($phaseid == '' || $bed == '' || $unittype == '')
					{
						echo '0';
					}

					$q = "SELECT QUANTITY FROM resi_phase_quantity WHERE PHASE_ID='".addslashes($phaseid)."' AND UNIT_TYPE='".addslashes($unittype)."' AND BEDROOMS='".addslashes($bed)."' ";
					$r = mysql_query($q);	
					if(mysql_num_rows($r)>0)
					{
						$o = mysql_fetch_assoc($r);
						echo $x = $o['QUANTITY'];						
					}
					else
					{
						echo '0';
					}
	}
	exit;
?>