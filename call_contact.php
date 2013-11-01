<?php
error_reporting(1);
ini_set('display_errors','1');
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("modelsConfig.php");
include("builder_function.php");
AdminAuthentication();

function getAgentContact($agentid) {
  $sql = "SELECT USERNAME FROM proptiger_admin WHERE ADMINID = $agentid";
  $res = mysql_query($sql) or die(mysql_error());
  $row = mysql_fetch_array($res);
  $uname = str_replace('.', '0', $row[0]);
  return $uname;
}


$aID = $_SESSION['adminId'];

/* $sql = "SELECT CLOUDAGENT_ID FROM proptiger_admin WHERE ADMINID=" . $aID . ";"; */

/* $result = mysql_query($sql); */
/* $row = mysql_fetch_array($result); */
/* $agentId = $row['CLOUDAGENT_ID']; */
$projectType = $_REQUEST['projectType'];
$contactNo = $_REQUEST['contactNo'];
$campaign = $_REQUEST['campaign'];


$callDetail = new CallDetails(array('AgentId'=>$aID, 'PROJECT_TYPE'=>$projectType, 'ContactNumber'=>$contactNo, 'CampaignName'=>$campaign));
$callDetail->save();
$callId= $callDetail->callid;

$api_url = "http://cloudagent.in/CAServices/PhoneManualDial.php";
$params = array();

$params["apiKey"] = "KK6553cb21f45e304ffb6c8c92a279fde5";
$params["did"] = "$did";
$params["phoneName"] = getAgentContact($aID);
$params["custNumber"] = $contactNo;
$params["uui"] = $callId;
$params["userName"] = "proptiger";
$api_url = "http://cloudagent.in/CAServices/PhoneManualDial.php";

$url = $api_url . "?" . http_build_query($params);
/* $url = "http://Kookoo.in/propTiger/manualDial.php?api_key=KK6553cb21f45e304ffb6c8c92a279fde5&customerNumber=" . $contactNo . "&uui=" . $callId . "&campaignName=" . $campaign . "&agentID=" . $agentId . "&username=proptiger"; */

$response = file_get_contents($url);
$xml = json_decode($response);
$status = $xml->status;
$message = $xml->message;

$sql = "update CallDetails set ApiResponse = '" . $message . "' where CallId = " . $callId;
mysql_query($sql);

if ($callId) 
  echo "call_" . $callId . "_" . $agentId;
else
  echo "Fail - $message";
?>