<?php 

    include_once("dbConfig.php");
    include("appWideConfig.php");
    include_once ("send_mail_amazon.php");
    $test = $_REQUEST['data'];
    error_log("JSON_CALLBACK ==== ".$test);
    $data = json_decode($test, true);

    $audio   = "'" . $data['AudioFile'] . "'";
    $st      = "'" . substr($data['StartTime'], 0, 19) . "'";
    $et      = "'" . substr($data['EndTime'], 0, 19) . "'";
    $agentId = $data['AgentID'];
    $callId  = $data['UUI'];
    $duration = "'$data[Duration]'";
    $dialStatus = "'$data[DialStatus]'";
 
    //$audio_file = file_get_contents($url);
    //$audio_file = file_get_contents($audio);
    //$audio = "http://recordings.kookoo.in/proptiger/proptiger_1271389878227247.mp3";

    $audio_file = file_get_contents($audio);
    //print_r($audio_file);
    $newdirpro = $newImagePath."recordings";
    if(!is_dir($newdirpro))
        {
            mkdir($newdirpro, 0777);
            $path   =   $newdirpro."/audiofile.mp3";//die("here");
        }
    else
        {
            $path   =   $newdirpro."/audiofile.mp3";//die("here");

        }


    file_put_contents($path, $audio_file, LOCK_EX);

    $media_extra_attributes = array('startTime'=>$st, 'endTime'=>$et, 'callDuration'=>$duration, 'dialStatus'=>$dialStatus);
    $jsonMediaExtraAttributes= json_encode($media_extra_attributes);
    $post = array('file'=>'@'.$path, 'objectType'=>'call',
            'objectId' => $callId, 'documentType' => 'recording', 'mediaExtraAttributes'=>$jsonMediaExtraAttributes);
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

    if(empty($serviceResponse["service"]->response_body->error->msg)){
        $audio_id = $serviceResponse["service"]->response_body->data->id;

        $audio_link = $serviceResponse["service"]->response_body->data->absolutePath;
        $sql = "UPDATE CallDetails SET "
                . "AudioLink=" . $audio_link . ", " 
                . "StartTime=" . $st . ", "
                . "EndTime=" . $et . ", "
                . "CallDuration=" . $duration . ", "
                . "DialStatus=" . $dialStatus . ", "
                . "CallbackJson='" . $test
                . "' WHERE CallId=". $callId .";";
        mysql_query($sql);
                                                
    }


    
    
    /**code for fetch email id of an agent***/
    $qryAgentEmail = "SELECT ADMINEMAIL FROM proptiger_admin WHERE CLOUDAGENT_ID = '".$agentId."'";
    $resAgentEmail = mysql_query($qryAgentEmail);
    $resData = mysql_fetch_assoc($resAgentEmail);
    $agentEmail = $resData['ADMINEMAIL'];
    $arrEmail  = array('ankur.dhawan@proptiger.com',$agentEmail); 
    /****code for send mail to calling team if audio value return -1*********/
   if( $audio == '-1' ) {
       foreach($arrEmail as $email) {
        $subject="CloudAgent issue for CallId vimlesh";
        $email_message = 'Recording link not sent in the response. Received -1 as audio link';
        $to = $email;
        $sender = "no-reply@proptiger.com";
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'To: '.$email."\r\n";
        $headers .= 'From: '.$sender."\r\n";
        sendMailFromAmazon($to, $subject, $email_message, $sender,null,null,false);
       }
   }

?>
