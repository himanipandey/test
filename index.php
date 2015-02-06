<?php
echo "dgsahdsj";
require_once('header.php');
if(!isset($_SESSION['AdminLogin']))
	$_SESSION['AdminLogin'] = '';
if ($_SESSION['AdminLogin']) {
	header("location: ProjectList.php");
}

require_once('LoginProcess.php');

$smarty->assign("SITETITLE", SITE_TITLE);
$smarty->assign("adminId", $_SESSION['adminId']);
$smarty->display(PROJECT_ADD_TEMPLATE_PATH.'header.tpl');
$smarty->display(PROJECT_ADD_TEMPLATE_PATH.'Login.tpl');
$smarty->display(PROJECT_ADD_TEMPLATE_PATH.'footer.tpl');
?>
