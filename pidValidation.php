<?php
include("dbConfig.php");

$pid   = mysql_escape_string($_POST['pid']);
$msg = 0;
$is_exist_sql = mysql_query("SELECT STATUS FROM `resi_project` WHERE PROJECT_ID = '$pid'") or die(mysql_error());

if($is_exist_sql){
	$count =  mysql_fetch_object($is_exist_sql)->STATUS;
}
	
echo $count;

?>
