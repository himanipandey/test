<?php

$lot_id = $_REQUEST['l'];
$smarty->assign('lot_id', $lot_id);

$lot_details = fetch_lot_details($lot_id);
$smarty->assign('lot_details', $lot_details);

//current user role
$smarty->assign('currentRole', $_SESSION['ROLE']);
$smarty->assign('currentUser', $_SESSION['adminId']);

?>