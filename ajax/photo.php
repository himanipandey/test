<?php
/**
 * User: swapnil
 * Date: 7/19/13
 * Time: 5:04 PM
 */
require_once "$_SERVER[DOCUMENT_ROOT]/dbConfig.php";
require_once "$_SERVER[DOCUMENT_ROOT]/includes/db_query.php";
require_once "$_SERVER[DOCUMENT_ROOT]/common/function.php";

$json = array(
    'result' => FALSE,
    'data' => ''
);

$data = array();

if ( !empty( $_REQUEST['upPh'] ) ) {
    $upPh = $_REQUEST['upPh'];
    $upPh = json_decode( $upPh, TRUE );
    $res = TRUE;
    foreach( $upPh as $__cnt => $__newData ) {
        $res &= updateThisPhotoProperty( $__newData );
    }
    if ( $res ) {
        $data['result'] = TRUE;
    }
    else {
        $data['result'] = FALSE;
    }
}
else {
    if ( !empty( $_REQUEST['city'] ) ) {
        $data['city'] = $_REQUEST['city'];
    }
    if ( !empty( $_REQUEST['suburb'] ) ) {
        $data['suburb'] = $_REQUEST['suburb'];
    }
    if ( !empty( $_REQUEST['getPh'] ) ) {
        if ( !empty( $_REQUEST['locality'] ) ) {
            $data['locality'] = $_REQUEST['locality'];
        }
        $data = getPhoto( $data );
    }
    else {
        $data = getListing( $data );
    }
}

if ( is_array( $data ) && count( $data ) ) {
    $json['result'] = TRUE;
    $json['data'] = $data;
}

echo json_encode( $json );
exit;
