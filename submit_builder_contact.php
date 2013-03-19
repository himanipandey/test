<?php

	//die("here");
	include("dbConfig.php");
	//include("includes/configs/configs.php");
	include("builder_function.php");
	
	$builderId		=	$_REQUEST['builderId'];

	$exp 			= explode("--",$_REQUEST['name']);
	$exp_Del 		= explode("--",$_REQUEST['deleteval']);
	$exp_id 		= explode("--",$_REQUEST['id']);
	$exp_phone 		= explode("--",$_REQUEST['phone']);
	$exp_email 		= explode("--",$_REQUEST['email']);
	$exp_projects 	= explode("--",$_REQUEST['projects']);
	$msg 			= 0;
	
	foreach($exp as $key=>$val)
	{
		if($val != '' AND $exp_id[$key] != 'undefined')
		{
			if($exp_Del[$key] == 1)
			{
				 $qryDel = "DELETE FROM builder_contact_info
								    WHERE
								       ID = '".$exp_id[$key]."'";
						$resDel = mysql_query($qryDel) or die(mysql_error());
						if($resDel)
						{
							audit_insert($exp_id[$key],'delete','builder_contact_info');
							$msg = 1;
						}
						else
							$msg = 0;
			}
			else
			{
				$qrySel = "SELECT * FROM builder_contact_info 
							WHERE 
								ID = '".$exp_id[$key]."'";
				$res    = mysql_query($qrySel);
				if(mysql_num_rows($res)>0)
				{
					$projects = substr($exp_projects[$key], 1);
					$resUp = "UPDATE builder_contact_info
								SET 
								  NAME    = '".$val."',
								  PHONE   = '".$exp_phone[$key]."',
								  EMAIL   = '".$exp_email[$key]."',
								  PROJECTS= '".str_replace(',','#', $projects)."'
								WHERE
								  ID      = '".$exp_id[$key]."'";
					$resUp = mysql_query($resUp);
					if($resUp)
					{
						audit_insert($exp_id[$key],'update','builder_contact_info','');
						$msg = 1;
					}
					else 
						$msg = 0;
				}
				else
				{
					$resIns = "INSERT INTO builder_contact_info
								SET 
								  NAME       = '".$val."',
								  PHONE      = '".$exp_phone[$key]."',
								  EMAIL      = '".$exp_email[$key]."', 
								  PROJECTS   = '".str_replace(',','#', $projects)."',
								  BUILDER_ID = '".$builderId."'";
					$resIns = mysql_query($resIns) or die(mysql_erro());
					if($resins)
						$msg = 1;
					else
						$msg = 0;

				}
			}
		}
	}
	echo $msg;
	

?>