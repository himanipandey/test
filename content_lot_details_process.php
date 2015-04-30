<?php

$lot_id = $_REQUEST['l'];
$smarty->assign('lot_id', $lot_id);

$lot_details = fetch_lot_details($lot_id);
$smarty->assign('lot_details', $lot_details);

//print "<pre>".print_r($lot_details,1)."</pre>";


?>