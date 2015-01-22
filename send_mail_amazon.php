<?php
    require_once('amazon-sdk/sdk.class.php');
    function sendMailFromAmazon($to,  $subject, $message, $from , $cc=null, $bcc=null, $ajaxCall=true) {
    //Provide the Key and Secret keys from amazon here.
    #old keys
    #$AWS_KEY = "AKIAIPT74FHV5KIH6CBA";
    #$AWS_SECRET_KEY = "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";
    #new keys
    $AWS_KEY = "AKIAIERS5YQ2JMRPGGQA";
    $AWS_SECRET_KEY = "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";

    //certificate_authority true means will read CA of amazon sdk and false means will read CA of OS
    $CA = true;

    $amazonSes = new AmazonSES(array( "key" => $AWS_KEY, "secret" => $AWS_SECRET_KEY, "certificate_authority" => $CA ));

    if($from==''){$from = "no-reply@proptiger.com";}

	$sendArray =array();
	if(!empty($to)) { $sendArray["ToAddresses"] = array($to);}
	if(!empty($cc)) { $sendArray["CcAddresses"] = array($cc);}
	if(!empty($bcc)) { $sendArray["BccAddresses"] = array($bcc);}
	
	$response = $amazonSes->send_email($from,
        $sendArray,
        array("Subject" =>array("Data"=>$subject),
                "Body"=>array(
                				"Text"=>array("Data"=>$message),
                				"Html"=>array("Data"=>$message)
             				)
        		));

	if (!$response->isOK()) {
            if($ajaxCall)
                echo 'Not Send';
            else
                return false;
	}else {
                
                if($ajaxCall)
                   echo 'Send';
               else
                   return true;
	} 
}

?>
