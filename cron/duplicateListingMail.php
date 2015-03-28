<?php

ini_set('display_errors', '1');
set_time_limit(0);
error_reporting(E_ALL);
$docroot = dirname(__FILE__) . "/../";
require_once $docroot . 'dbConfig.php';
require_once 'cronFunctions.php';
require_once $docroot . 'includes/send_mail_amazon.php';

$date = date("Y-m-d");

$query = "SELECT ls.id Listing_ID, ls.created_at Created_Date, c.name Company, cu.name Seller, ph.PROJECT_ID Project_ID, po.option_name BHK ,tw.Tower_Name Tower, ls.floor Floor_NO, ls.flat_number Flat_NO, lp.price_per_unit_area UNIT_PRICE, lp.price ABS_PRICE, count(*) DUPLICATE_COUNT 
FROM listings ls 
LEFT JOIN resi_project_phase ph ON ls.phase_id = ph.PHASE_ID AND ph.version='Cms' AND ph.STATUS='Active' 
LEFT JOIN resi_project p ON ph.PROJECT_ID = p.PROJECT_ID 
LEFT JOIN listing_prices lp ON ls.current_price_id = lp.id 
LEFT JOIN resi_project_tower_details tw ON ls.tower_id = tw.tower_id 
LEFT JOIN resi_project_options po ON ls.option_id = po.options_id 
LEFT JOIN company_users cu ON ls.seller_id = cu.id 
LEFT JOIN company c ON cu.company_id = c.id 
where DATE(ls.created_at) = DATE(subdate(current_date, 1)) GROUP BY Project_ID,ls.option_id,ls.tower_id,UNIT_PRICE,ABS_PRICE HAVING count(*)>1";

$resultResource = mysql_query($query) or die(mysql_error());
if (mysql_num_rows($resultResource) > 0) {
    $result_array = array();
    while ($row = mysql_fetch_assoc($resultResource)) {
        $result_array[] = $row;
    }
//    $to = 'jitendra.pathak@proptiger.com';
//    $recipients = array('jitendra.pathak@proptiger.com');
    $to = 'suneel.kumar@proptiger.com';
    $recipients = array('suneel.kumar@proptiger.com', 'prakash.kanyal@proptiger.com');
    $from = 'no-reply@proptiger.com';
    $emailSubject = "Duplicate listings";
    $emailContent = "Hi, Please find the attached list of duplicate listings inserted yesterday";
    $filePath = $docroot . '/includes/temp/jitendra_rand.csv';
    putResultsInFile($result_array, $filePath);
    sendRawEmailFromAmazon($to, $from, null, $emailSubject, $emailContent, 'duplicate-listings-' . date("d-m-Y") . '.csv', $filePath, $recipients);
    unlink($filePath);
}