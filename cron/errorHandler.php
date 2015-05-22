<?php

// error handler function
$currentDir = dirname(__FILE__);
require_once ($currentDir . '/../includes/send_mail_amazon.php');

function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    $email_recipient = getenv("FAILURE_EMAIL_RECEIPIENT"); 
    if(in_array($errno, array(E_WARNING, E_ERROR))){
      sendRawEmailFromAmazon($email_recipient, '', '', 'Just Checking. Error in cron on ' . exec('hostname'), "Error[$errno] in cron on server: " . exec('hostname') . " on line  $errline in file $errfile. Error message: $errstr. Aborting....", '', '', array($email_recipient, "azitabh.ajit@proptiger.com"));
      exit(1);
    }
    else{
      return false;
    }
}

// registering error handler function with php
$old_error_handler = set_error_handler("myErrorHandler");
?>
