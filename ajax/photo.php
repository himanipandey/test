<?php
/**
 * User: swapnil
 * Date: 7/19/13
 * Time: 5:04 PM
 */
error_reporting(E_ALL);
ini_set('display_errors','1');
require_once "$_SERVER[DOCUMENT_ROOT]/dbConfig.php";
require_once "$_SERVER[DOCUMENT_ROOT]/includes/db_query.php";
require_once "$_SERVER[DOCUMENT_ROOT]/common/function.php";

$json = array(
    'result' => FALSE,
    'data' => ''
);

$data = array();
if ( !empty( $_REQUEST['city'] ) ) {
    $data['city'] = $_REQUEST['city'];
}
if ( !empty( $_REQUEST['suburb'] ) ) {
    $data['suburb'] = $_REQUEST['suburb'];
}
$data = getListing( $data );
if ( is_array( $data ) && count( $data ) ) {
    $json['result'] = TRUE;
    $json['data'] = $data;
}

echo json_encode( $json );
exit;