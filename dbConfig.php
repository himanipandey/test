<?php

//  Site Database

define("SYSTEM_USER_ID", "53");

define("DB_PROJECT_HOST", "localhost");
define("DB_PROJECT_USER", "root");
define("DB_PROJECT_PASS", "root");
define("DB_PROJECT_NAME", "cmsdev");


$db = mysql_connect(DB_PROJECT_HOST, DB_PROJECT_USER, DB_PROJECT_PASS);
$dblink = mysql_select_db(DB_PROJECT_NAME, $db);

?>
