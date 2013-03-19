<?php 

include_once("dbConfig.php");

$test = $_REQUEST['data'];
$data = json_decode($test, true);


$audio = "'" . $data['AudioFile'] . "'";
$st = "'" . substr($data['StartTime'], 0, 19) . "'";
$et = "'" . substr($data['EndTime'], 0, 19) . "'";
$json = $test;
$callId = $data['UUI'];

$sql = "UPDATE CallDetails SET "
. "AudioLink=" . $audio . ", " 
. "StartTime=" . $st . ", "
. "EndTime=" . $et . ", "
. "CallbackJson='" . $test
. "' WHERE CallId=". $callId .";";

mysql_query($sql);

?>
