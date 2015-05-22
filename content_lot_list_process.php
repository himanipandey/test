<?php

$CityDataArr = City::CityArr();
$smarty->assign("CityDataArr", $CityDataArr);

//fetching content users Vendors & Editors
$assignToUsers = fetch_assignTo_users();
$smarty->assign('assignToUsers', $assignToUsers);

//fetching content users Editors
$assignToEditors = fetch_assignTo_editors();
$smarty->assign('assignToEditors', $assignToEditors);

//current user
$smarty->assign('currentUser', $_SESSION['adminId']);

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


if(isset($_POST['searchLot'])){
    
    $errorMsg = array();
    
    $date_filter = $_POST['date_filter'];
    $frmdate = $_POST['from_date_filter'];
    $todate = $_POST['to_date_filter'];
    
    $dateArr = getDatesBetweeenTwoDates($frmdate,$todate);
    
    if(count($dateArr) == 0){
        $errorMsg['dateDiff'] = "<font color = 'red'>From date can not be greater then to date!</font>";
    }else{
        //fetch lots between dates
        $contentLots = fetch_lots($frmdate, $todate, $date_filter); 
        
    }
    
    $smarty->assign('date_filter', $date_filter);
    $smarty->assign('frmdate', $frmdate);
    $smarty->assign('todate', $todate);
    $smarty->assign('errorMsg', $errorMsg);
    
}else{
    //fetch all lots
    $contentLots = fetch_lots();    
}

$smarty->assign('contentLots', $contentLots);

?>