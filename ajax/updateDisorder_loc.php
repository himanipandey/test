<?php
	include("../dbConfig.php");
	include("../appWideConfig.php");	
	include("../builder_function.php");
	$cityid				=	$_REQUEST['cityid'];
	$localityid			=	$_REQUEST['localityid'];
	$updatedorder		=	$_REQUEST['updatedorder'];
	$newlistforuporder	=	explode(",",$updatedorder);
	$listorder			=	$_REQUEST['listorder'];
	$exp				=	explode("rows now: ",$listorder);
	$newlist			=	$exp[1];
	$expfinallist		=	explode(" ",$newlist);

	if($expfinallist[0] != '')	{
		
		$count=1;
		foreach($expfinallist as $val)	{
			$exp3	=	explode("---",$val);				
			if($exp3[1]!='') {
				$updateQry = "UPDATE resi_project 
							 SET 
								DISPLAY_ORDER_LOCALITY = '".$count."' 
							 WHERE
								PROJECT_ID = '".$exp3[1]."' 
							AND 
								CITY_ID = '".$cityid."'
							AND 
								LOCALITY_ID = '".$localityid."'";
				$resDelete			=	mysql_query($updateQry);
				$count = $count+1;
			}
		}
		echo "<font color = 'green'><strong>The project locality display order has been updated successfully.</strong></font>";
	}
	else	{
		echo "<font color = 'red'>Opps! there is a problem please refresh the page.</font>";
	}

?>