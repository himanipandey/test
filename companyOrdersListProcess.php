<?php

$compId = mysql_real_escape_string($_GET['compId']);

$compOrderArr = CompanyOrder::getAllOrders($compId);

#$orderArr = CompanyOrder::getAllOrders(42);
//print "<pre>".print_r($orderArr,1)."</pre>";


$smarty->assign("compOrderArr", $compOrderArr);
//print "<pre>".print_r($compOrderArr,1)."</pre>";
?>
