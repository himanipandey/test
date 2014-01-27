<?php
include("dbConfig.php");
$cid   = mysql_escape_string($_POST['cid']);
$update = mysql_query("UPDATE comments_history SET status = 'Read' WHERE comment_id = '$cid'");
if($update)
	print "Remark Read!";
else
	print "Failed";
?>
