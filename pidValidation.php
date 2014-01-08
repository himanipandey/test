<?php
include("dbConfig.php");

$pid   = mysql_escape_string($_POST['pid']);
$count = 0;
$is_exist_sql = mysql_query("SELECT COUNT(*) as cnt FROM `resi_project` WHERE PROJECT_ID = '$pid' AND STATUS != 'Inactive'") or die(mysql_error());

if($is_exist_sql)
	$count =  mysql_fetch_object($is_exist_sql)->cnt;
	
echo $count;

?>
