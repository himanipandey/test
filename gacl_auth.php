<?php

include_once dirname(__FILE__) . '/phpgacl-3.3.7/gacl.class.php';
$gacl = new gacl(array());



function isPermitted($username, $resource, $action) {

    global $gacl;
    $isAllowed = $gacl->acl_check($resource, $action, 'Users', $username);
    include("dbConfig.php");
    return $isAllowed;
}

$resource = $_REQUEST['resource'];
$username = $_REQUEST['username'];
$action = $_REQUEST['action'];

header('Content-Type: application/json');
echo json_encode(array("status" =>isPermitted($username, $resource, $action)));