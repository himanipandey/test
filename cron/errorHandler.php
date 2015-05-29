<?php

// error handler function
$currentDir = dirname(__FILE__);
require_once ($currentDir . '/../includes/send_mail_amazon.php');
if(file_exists($currentDir . "/../.production")){
  define("FAILURE_EMAIL_RECEIPIENT", "site_errors@proptiger.com");
}
else{
  define("FAILURE_EMAIL_RECEIPIENT", "add-your-email@proptiger.com");
}

function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    if(in_array($errno, array(E_WARNING, E_ERROR, E_USER_ERROR))){
      sendRawEmailFromAmazon(FAILURE_EMAIL_RECEIPIENT, '', '', 'Error in cron on ' . exec('hostname'), "Error[$errno] in cron on server: " . exec('hostname') . " on line  $errline in file $errfile. Error message: $errstr. Aborting....", '', '', array(FAILURE_EMAIL_RECEIPIENT));
      exit(1);
    }
    else{
      return false;
    }
}

// registering error handler function with php
$old_error_handler = set_error_handler("myErrorHandler");
?>
