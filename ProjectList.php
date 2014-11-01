<?php

	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	date_default_timezone_set('Asia/Kolkata');
	include("builder_function.php"); 
	AdminAuthentication();	
	include("modelsConfig.php");
	include('projectManageProcess.php');
ini_set("memory_limit","256M");
	//$smarty->display(SERVER_PATH."/smarty/templates/admin/crawler/header.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");

	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."manageProject.tpl");
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
$audio = "http://recordings.kookoo.in/proptiger/proptiger_2311407848436921.mp3";
$filenameArr = explode("/", $audio); 
$filename = array_pop($filenameArr);
$path = $newImagePath.$filename;

$audio_file = file_get_contents($audio);

file_put_contents($path, $audio_file, LOCK_EX);

 //upload to media service
    $media_extra_attributes = array('startTime'=>"st", 'endTime'=>"et", 'callDuration'=>"duration", 'dialStatus'=>"dialStatus");
   $jsonMediaExtraAttributes= json_encode($media_extra_attributes);
   $post = array('file'=>$path,'objectType'=>'project',
           'objectId' => "500055", 'documentType' => 'call', 'mediaExtraAttributes'=>$jsonMediaExtraAttributes);
   $url = AUDIO_SERVICE_URL;
   $method = "POST";

   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL,$url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_VERBOSE, 1);
   curl_setopt($ch, CURLOPT_HEADER, 1);
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST,$method);
   if($method == "POST" || $method == "PUT")
       curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
   $response= curl_exec($ch);
   $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
   $response_header = substr($response, 0, $header_size);
   $response_body = json_decode(substr($response, $header_size));
   $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
   curl_close ($ch);

   $response = array("header" => $response_header, "body" => $response_body, "status" => $status);

print_r($response);
    // save response from media service to cms db
   if(empty($serviceResponse["service"]->response_body->error->msg)){
       $audio_id = $serviceResponse["service"]->response_body->data->id;

       $audio_link = $serviceResponse["service"]->response_body->data->absolutePath;
       //echo  "hi:".$audio_link;                
   }
   else
   	//echo $serviceResponse["service"]->response_body->error->msg;



?>

