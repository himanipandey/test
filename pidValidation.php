<?php
include("dbConfig.php");

$cid   = mysql_escape_string($_POST['cid']);
$msg = 0;
$is_exist_sql = mysql_query("SELECT STATUS FROM `resi_project` WHERE PROJECT_ID = '$pid' AND version = 'Cms'") or die(mysql_error());

if($is_exist_sql){
	$count =  mysql_fetch_object($is_exist_sql)->STATUS;
}
	
echo $count;

?>
