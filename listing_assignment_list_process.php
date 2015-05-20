<?php
$errorMsg = '';

//select all cities in case of photographer
if(in_array($_SESSION['ROLE'], array('photoGrapher', 'reToucher' ))){
    $cityArray = City::CityArr();
    $smarty->assign("cityArray", $cityArray);
    
    $admin_city_ids = City::CityIdArr();
    $smarty->assign("admin_city_ids", json_encode($admin_city_ids));
}else{
    $cityArray = ProptigerAdminCity::get_admin_city($_SESSION['adminId']);
    $smarty->assign("cityArray", $cityArray);
    
    $admin_city_ids = ProptigerAdminCity::get_admin_city_ids($_SESSION['adminId']);
    $smarty->assign("admin_city_ids", json_encode($admin_city_ids));
}

//filters
$selected_city = '';
if (isset($_GET['citydd'])) {
    $selected_city = $_GET['citydd'];
}
$smarty->assign('citydd', $selected_city);

$project_search = '';
$selProjId = '';
if (isset($_GET['project_search']) && isset($_GET['selProjId'])) {
    if (!empty($_GET['project_search'])) {
        $project_search = $_GET['project_search'];
        $selProjId = $_GET['selProjId'];
    }
}
$smarty->assign('project_search', $project_search);
$smarty->assign('selProjId', $selProjId);


$listingId_serach = '';
if (isset($_GET['listingId_serach'])) {
    $listingId_serach = $_GET['listingId_serach'];
}
$smarty->assign('listingId_serach', $listingId_serach);

//date filter
$date_filter = $_GET['date_filter'];
$frmdate = $_GET['from_date_filter'];
$todate = $_GET['to_date_filter'];
if ($date_filter && $frmdate && $todate) {
    $dateArr = getDatesBetweeenTwoDates($frmdate, $todate);

    if (count($dateArr) == 0) {
        $errorMsg['dateDiff'] = "<font color = 'red'>From date can not be greater then to date!</font>";
    } 
   
    $smarty->assign('date_filter', $date_filter);
    $smarty->assign('frmdate', $frmdate);
    $smarty->assign('todate', $todate);
    $smarty->assign('errorMsg', $errorMsg);
}

///////////////////////////////////////////////
$smarty->assign('url12', TYPEAHEAD_API_URL);
$smarty->assign('current_user_role', $_SESSION['ROLE']);
$smarty->assign('current_user', $_SESSION['adminId']);
?>
