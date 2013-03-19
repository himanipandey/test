<?php
    include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
	AdminAuthentication();

	$cityid				=	$_REQUEST['cityid'];
	$updatedorder		=	$_REQUEST['updatedorder'];
	$newlistforuporder	=	explode(",",$updatedorder);
	$listorder			=	$_REQUEST['listorder'];
	$exp				=	explode("rows now: ",$listorder);
	$newlist			=	$exp[1];
	$expfinallist		=	explode(" ",$newlist);
	
	if($expfinallist[0] != '')	{
			
		$count=1;
		foreach($expfinallist as $val)
		{
			$exp3	=	explode("---",$val);				
			if($exp3[1]!=''){
				$updateQry = "UPDATE ".RESI_PROJECT." 
							 SET 
								DISPLAY_ORDER = '".$count."' 
							 WHERE
								PROJECT_ID = '".$exp3[1]."' 
							AND 
								CITY_ID ='".$cityid."'";
					
				$resDelete			=	mysql_query($updateQry);
				$count = $count+1;
			}
		}

			
			echo "<font color = 'green'><strong>The project display order has been updated successfully.</strong></font>";
	}
	else
	{
		echo "<font color = 'red'>Oops! there is a problem please change the sequence of projects.</font>";
	}

?>