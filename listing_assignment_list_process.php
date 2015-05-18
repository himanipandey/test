<?php

$cityArray = ProptigerAdminCity::get_admin_city($_SESSION['adminId']);
$smarty->assign("cityArray", $cityArray);

$admin_city_ids = ProptigerAdminCity::get_admin_city_ids($_SESSION['adminId']);
$smarty->assign("admin_city_ids", json_encode($admin_city_ids));

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

///////////////////////////////////////////////
$smarty->assign('url12', TYPEAHEAD_API_URL);
$smarty->assign('current_user_role', $_SESSION['ROLE']);
$smarty->assign('current_user', $_SESSION['adminId']);

?>
