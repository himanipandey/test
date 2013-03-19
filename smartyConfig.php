<?php
// put full path to Smarty.class.php
require('Smarty-3.0.7/libs/Smarty.class.php');
$smarty = new Smarty();

$path = 'smarty';
if(!empty($myDocRoot))
	$path = "$myDocRoot/smarty";
$smarty->setTemplateDir("$path/templates");
$smarty->setCompileDir('/tmp');
$smarty->setCacheDir("$path/cache");
$smarty->setConfigDir("$path/configs");

?>
