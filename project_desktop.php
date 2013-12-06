<?php
include_once('header.php');

AdminAuthentication();

$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
$smarty->display(PROJECT_ADD_TEMPLATE_PATH."desktop.tpl");
$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
?>
