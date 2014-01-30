<?php
ob_start();
session_start();

include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("includes/function.php"); 
	
/*$projectID=$_GET['project_id'];

			$qry			=	"SELECT ABOUT_US,ID FROM ".CRAWLER_PROPERTY_DETAILS." WHERE PROPERTY_ID = '".$projectID."'";
			$res			=	mysql_query($qry,$db);
			$data			=	mysql_fetch_array($res);

			 $aboutus=$data['ABOUT_US'];

			$smarty->assign("aboutus", $aboutus);*/
			
			$smarty->display(OFFLINE_PROJECT_TEMPLATE_PATH."tinytext.tpl");

?>