<?php

//$db = mysql_connect("ip-182-50-129-43.ip.secureserver.net", "root", "PropTiger1");
//$link = mysql_select_db("vtigercrm521_New", $db);


$db = mysql_connect("192.168.1.136", "staging", "staging");
$link = mysql_select_db("staging_crm", $db); 
error_reporting(0);

?>
