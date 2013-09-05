<?php

//  Site Database
define("DB_PROJECT_HOST", "localhost");
define("DB_PROJECT_USER", "root");
define("DB_PROJECT_PASS", "root");
define("DB_PROJECT_NAME", "project");


$db = mysql_connect(DB_PROJECT_HOST, DB_PROJECT_USER, DB_PROJECT_PASS);
$dblink = mysql_select_db(DB_PROJECT_NAME, $db);
?>
