<?php

include_once dirname(__FILE__) . '/phpgacl-3.3.7/gacl.class.php';

function isPermitted($username, $resource, $action) {

    global $gacl;
    $isAllowed = $gacl->acl_check($resource, $action, 'Users', $username);
    include("dbConfig.php");
    return $isAllowed;
}


$gacl = new gacl(array());
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
    $is_permitted = isPermitted($username, $resource, $action);
    $json["status"] = "success";
    $json["message"] = "successfully authenticated";
    $json["value"] = $is_permitted;
}


header('Content-Type: application/json');
echo json_encode($json);