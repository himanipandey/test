<?php 

    include_once("dbConfig.php");
    include_once ("send_mail_amazon.php");
    $test = $_REQUEST['data'];
    $data = json_decode($test, true);

    $audio   = "'" . $data['AudioFile'] . "'";
    $st      = "'" . substr($data['StartTime'], 0, 19) . "'";
    $et      = "'" . substr($data['EndTime'], 0, 19) . "'";
    $agentId = $data['AgentID'];
    $callId  = $data['UUI'];

    $sql = "UPDATE CallDetails SET "
    . "AudioLink=" . $audio . ", " 
    . "StartTime=" . $st . ", "
    . "EndTime=" . $et . ", "
    . "CallbackJson='" . $test
    . "' WHERE CallId=". $callId .";";
    mysql_query($sql);
    
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
