<?php

	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	date_default_timezone_set('Asia/Kolkata');
	include("builder_function.php"); 
	AdminAuthentication();	
	include("modelsConfig.php");
	include('projectManageProcess.php');

	//$smarty->display(SERVER_PATH."/smarty/templates/admin/crawler/header.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");

	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."manageProject.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");

	$audio = "http://recordings.kookoo.in/proptiger/proptiger_1271389878227247.mp3";

 $audio_file = file_get_contents($audio);
 print_r($audio_file);
    //file_put_contents('tmpfile.mp3', $audio_file, LOCK_EX);
    file_put_contents('audiofile.mp3', $audio_file, LOCK_EX);
	
?>

