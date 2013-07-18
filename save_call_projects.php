<?php

include_once("dbConfig.php");

$callId = $_REQUEST['callId'];
$status = $_REQUEST['status'];
$remark = $_REQUEST['remark'];
$brokerId = '';
if(isset($_REQUEST['brokerId']))
    $brokerId = $_REQUEST['brokerId'];

$sql = "UPDATE CallDetails SET CallStatus='" . $status . "',Remark='".$remark."' WHERE CallId=" . $callId . ";";
mysql_query($sql);
$projectList = explode(",", $_REQUEST['projectList']);
$sql = "INSERT INTO CallProject (CallId, ProjectId, BROKER_ID) VALUES ";
$arr = array();

foreach($projectList as $value) {
  array_push($arr, "(" . $callId . ", " . $value . ", " .$brokerId. ")"); 
}

$sql = $sql . implode(", ", $arr);

$return = mysql_query($sql);
if($return) {
    echo "success";
}
else {
    echo "fail";
}


?>