<?php
//error_reporting(1);
//ini_set('display_errors','1');
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
	
	$tblName = 'resi_project';
	createTableStructure($tblName);
	AdminAuthentication();
	
?>