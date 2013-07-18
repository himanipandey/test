<?php
mysql_close();
$db = mysql_connect("localhost", "root", "root");
$dblink = mysql_select_db("ptigercrm", $db);
?>
