<?php
mysql_close();
$db = mysql_connect("staging.proptiger-ws.com", "root", "staging");
$dblink = mysql_select_db("ptigercrm", $db);



?>
