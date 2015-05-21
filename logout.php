<?php
require_once 'header.php';
AdminAuthentication();
session_start();
session_destroy();
session_unset();
header("Location: index.php");
exit;
?>