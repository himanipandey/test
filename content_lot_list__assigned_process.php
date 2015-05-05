<?php

$CityDataArr = City::CityArr();
$smarty->assign("CityDataArr", $CityDataArr);

//fetching content users Vendors & Editors
$assignToUsers = fetch_assignTo_users();
$smarty->assign('assignToUsers', $assignToUsers);

//fetching content users Editors
$assignToEditors = fetch_assignTo_editors();
$smarty->assign('assignToEditors', $assignToEditors);

//fetch all lots
$contentLots = fetch_lots();
$smarty->assign('contentLots', $contentLots);




//date filters
$date_filters = array(
    'created' => 'Date Created',
    'assigned' => 'Date Assigned',
    'completed' => 'Date Completed',
    'approved' => 'Date Approved',
    'reverted' => 'Date Reverted',
    'canceled' => 'Date Canceled'    
);
$smarty->assign('date_filters', $date_filters);


if(isset($_POST['createLot'])){
    print "<pre>".print_r($_POST,1)."</pre>";
}

?>