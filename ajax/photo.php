<?php
/**
 * User: swapnil
 * Date: 7/19/13
 * Time: 5:04 PM
 */
require_once "$_SERVER[DOCUMENT_ROOT]/smartyConfig.php";
require_once "$_SERVER[DOCUMENT_ROOT]/appWideConfig.php";
require_once "$_SERVER[DOCUMENT_ROOT]/dbConfig.php";
require_once "$_SERVER[DOCUMENT_ROOT]/modelsConfig.php";
require_once "$_SERVER[DOCUMENT_ROOT]/s3upload/s3_config.php";
require_once "$_SERVER[DOCUMENT_ROOT]/includes/db_query.php";
require_once "$_SERVER[DOCUMENT_ROOT]/includes/configs/configs.php";
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
    if($ab == 'test') echo "print";else echo "test";
    foreach( $upPh as $__cnt => $__newData ) {
        $image_name = $__newData["IMAGE_NAME"];
        $service_image_id = $__newData["SERVICE_IMAGE_ID"];
        unset($__newData["IMAGE_NAME"]);
        unset($__newData["SERVICE_IMAGE_ID"]);
        $res &= updateThisPhotoProperty( $__newData );

        if($__newData["IMAGE_ID"]){
            $dest = 'locality/'.$image_name;
            $source = $newImagePath.$dest;
            $locality_image = LocalityImage::find($__newData["IMAGE_ID"]);
            $s3upload = new ImageUpload($source, array("s3" => $s3,
                "image_path" => $dest, "object" => "locality","object_id" => $locality_image->locality_id,
                "image_type" => strtolower($__newData["IMAGE_CATEGORY"]), "service_image_id" => $service_image_id));
            $response = $s3upload->update();
            $image_id = $response["service"]->data();
            $image_id = $image_id->id;
            $locality_image->service_image_id = $image_id;
            $locality_image->save();
        }
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
