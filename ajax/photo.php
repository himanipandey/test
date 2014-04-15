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
 $objectType = '';
 $objectId = '';
 $obj = '';
if(!empty($_REQUEST['locality'])) {
    $objectId = $_REQUEST['locality'];
    $objectType = 'locality';
    $obj = 'LOCALITY_ID';
}
else if(!empty($_REQUEST['suburb'])) {
    $objectId = $_REQUEST['suburb'];
    $objectType = 'suburb';
    $obj = 'SUBURB_ID';
}
else if(!empty($_REQUEST['city'])) {
    $objectId = $_REQUEST['city'];
    $objectType = 'city';
    $obj = 'CITY_ID';
}
else if(!empty($_REQUEST['bank'])) {
    $objectId = $_REQUEST['bank'];
    $objectType = 'bank';
    $obj = 'BANK_ID';
}
if ( !empty( $_REQUEST['upPh'] ) ) {
    $upPh = $_REQUEST['upPh'];
    $upPh = json_decode( $upPh, TRUE );
    $res = TRUE;
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
        $data = array();
        $service_image_id = $_REQUEST['service_image_id'];
        $url = ImageServiceUpload::$image_upload_url."?objectType=$objectType&objectId=".$objectId;
        $content = file_get_contents($url);
        $imgPath = json_decode($content);
        
       foreach($imgPath->data as $k=>$v){
            $data[$k]['IMAGE_ID'] = $v->id;
            $data[$k][$obj] = $v->objectId;
            $data[$k]['priority'] = $v->priority;
            $data[$k]['IMAGE_CATEGORY'] = $v->imageType->type;
            $data[$k]['IMAGE_DISPLAY_NAME'] = $v->title;
            $data[$k]['IMAGE_DESCRIPTION'] = $v->description;
            $data[$k]['SERVICE_IMAGE_ID'] = $v->id;
            $data[$k]['SERVICE_IMAGE_PATH'] = $v->absolutePath;
        }
        
    }
    else {
        $data = getListing( $data );
    }
}
$localityArr = array();
 $localityType = ImageServiceUpload::$image_types;
 $localityArr = $localityType[$objectType];
//echo "<pre>";print_r($localityArr);die;
if ( is_array( $data ) && count( $data ) ) {
    $json['result'] = TRUE;
    $json['data'] = $data;
    $json[$objectType] = $localityArr;
    $json['objectType'] = $objectType;
}
echo json_encode( $json );
exit;
