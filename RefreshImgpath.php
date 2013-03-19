<?php
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("../../dbConfig.php");
	include("../includes/configs/configs.php");
	include("../includes/function.php"); 
	AdminAuthentication();
	
	$builderId		=	$_REQUEST['buildid'];
	$projectname	=	$_REQUEST['projectname'];
	$qry				=	"SELECT BUILDER_IMAGE FROM ".RESI_BUILDER." WHERE BUILDER_ID = '".$builderId."'";
	$res				=	mysql_query($qry);
	if(mysql_num_rows($res)>0)
	{
			$data		=	mysql_fetch_array($res);
			$exp		=	explode("/",$data['BUILDER_IMAGE']);
			$proname	=	str_replace(" ","-",$projectname);
			echo $path		=	"<b>Folder Path: </b> images/".strtolower($exp[1])."/".$proname;
	}
?>