<?php

include_once dirname(__FILE__) . '/function/login.php';

$error = $gacl->db->_errorMsg;
$json = array();
if($error){
    $json["status"] = "error";
    $json["message"] = $error;
    $json["value"] = false;
}
else{
    $resource = $_REQUEST['resource'];
    $username = $_REQUEST['username'];
    $action = $_REQUEST['action'];
    $is_permitted = isUserPermitted($resource, $action, $username);
    $json["status"] = "success";
    $json["message"] = "successfully authenticated";
    $json["value"] = $is_permitted;
}


header('Content-Type: application/json');
echo json_encode($json);