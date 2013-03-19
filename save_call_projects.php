<?php

include_once("dbConfig.php");

$callId = $_REQUEST['callId'];
$status = $_REQUEST['status'];
$remark = $_REQUEST['remark'];
$sql = "UPDATE CallDetails SET CallStatus='" . $status . "',Remark='".$remark."' WHERE CallId=" . $callId . ";";
mysql_query($sql);
$projectList = explode(",", $_REQUEST['projectList']);
$sql = "INSERT INTO CallProject (CallId, ProjectId) VALUES ";
$arr = array();

foreach($projectList as $value) {
  array_push($arr, "(" . $callId . ", " . $value . ")"); 
}

$sql = $sql . implode(", ", $arr);

mysql_query($sql);


?>