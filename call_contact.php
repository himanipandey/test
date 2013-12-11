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

$aID = $_SESSION['adminId'];
$projectType = $_REQUEST['projectType'];
$contactNo = $_REQUEST['contactNo'];
$campaign = $_REQUEST['campaign'];


$callDetail = new CallDetails(array('AgentId'=>$aID, 'PROJECT_TYPE'=>$projectType, 'ContactNumber'=>$contactNo, 'CampaignName'=>$campaign));
$callDetail->save();
$callId= $callDetail->callid;

$params = array();
$params["apiKey"] = CLOUDAGENT_KEY;
$params["did"] = $arrCampaignDids[$campaign];
$params["phoneName"] = getAgentContact();
$params["custNumber"] = $contactNo;
$params["uui"] = $callId;
$params["userName"] = CLOUDAGENT_USER;


releaseAgent($params["phoneName"]);

$url = CLOUDAGENT_CALL_URL . http_build_query($params);
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

function getAgentContact() {
  $sql = "SELECT USERNAME FROM proptiger_admin WHERE ADMINID = '$_SESSION[adminId]'";
  $res = mysql_query($sql) or die(mysql_error());
  $row = mysql_fetch_array($res);
  $uname = str_replace('.', '', $row[0]);
  return $uname;
}

function releaseAgent($phName) {
  $params = array();
  $params["apiKey"] = CLOUDAGENT_KEY;
  $params["user"] = CLOUDAGENT_USER;
  $params["phoneName"] = $phName;
  $url = CLOUDAGENT_RELEASE_USER_URL . http_build_query($params);
  file_put_contents("/tmp/az","\n".$url,FILE_APPEND);
  file_get_contents($url);
}
?>