<?php
	
error_reporting(1);
ini_set('display_errors','1');
include("dbConfig.php");
    
if($_POST['task'] === 'dataSendCMs')  {
    $Sql = "insert into boundary_data values (6, '".$_POST['city']."', '". $_POST['address'] ."','". $_POST['boundary'] ."')";
    mysql_query($Sql) or die();
}

?>