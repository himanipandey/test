<?php 


$email= $_REQUEST['email'];
$email = filter_var($email, FILTER_SANITIZE_EMAIL);

$smarty->assign("email", $email);

$query = "select otp from user.user_otps o join user.users u on o.user_id=u.id and u.email='{$email}' order by o.id desc limit 1";
//echo $query;
$result = mysql_query($query);
$d = mysql_fetch_assoc($result);
$otp = $d['otp'];
if(!isset($otp) && isset($email) && !empty($email)){
	$otp = "one time password not found for above email-id";
}
$smarty->assign("otp", $otp);
//$smarty->assign("otp", "asdf");

?>