<?php
error_reporting(1);
ini_set('display_errors','1');
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
include("function/functions_priority.php");
AdminAuthentication();
if($_POST['type'] == 'SUBURB'  && !empty($_POST['id'])){
    updateSuburb($_POST['id'],MAX_PRIORITY);
}else if($_POST['type']=='LOCALITY' && !empty($_POST['id'])){
    updateLocality($_POST['id'],MAX_PRIORITY);
}else{
    
}
?>