<?php

ini_set('display_errors', '1');
set_time_limit(0);
error_reporting(E_ALL);
$docroot = dirname(__FILE__) . "/../";
require_once $docroot . 'dbConfig.php';
require_once 'cronFunctions.php';
require_once $docroot . 'includes/send_mail_amazon.php';

$date = date("Y-m-d");

$query = "SELECT ls.id, ph.PROJECT_ID projectId, p.PROJECT_NAME, ls.option_id config, ls.tower_id tower, lp.price_per_unit_area unitprice, lp.price absprice, count(*) recordfoud FROM listings ls 
LEFT JOIN resi_project_phase ph ON ls.phase_id = ph.PHASE_ID AND ph.version='Cms' AND ph.STATUS='Active'
LEFT JOIN user.RESI_PROJECT p ON ph.PROJECT_ID = p.PROJECT_ID
LEFT JOIN listing_prices lp ON ls.current_price_id = lp.id
where DATE(ls.created_at) = DATE(subdate(current_date, 1)) GROUP BY projectId,config,tower,unitprice,absprice HAVING count(*)>1";

$resultResource = mysql_query($query) or die(mysql_error());
if (mysql_num_rows($resultResource) > 0) {
    $result_array = array();
    while ($row = mysql_fetch_assoc($resultResource)) {
        $result_array[] = $row;
    }
//    $to = 'suneel.kumar@proptiger.com';
    $to = 'jitendra.pathak@proptiger.com';
//    $recipients = array('suneel.kumar@proptiger.com', 'prakash.kanyal@proptiger.com');
    $recipients = array('jitendra.pathak@proptiger.com');
    $from = 'no-reply@proptiger.com';
    $emailSubject = "Duplicate listings inserted yesterday";
    $emailContent = "Hi, Please find the attached list of duplicate listings inserted yesterday";
    $filePath = $docroot.'/includes/temp/jitendra_rand.csv';
    putResultsInFile($result_array, $filePath);
    sendRawEmailFromAmazon($to, $from, null, $emailSubject, $emailContent, 'duplicate-listings-' . date("d-m-Y") . '.csv', $filePath, $recipients);
    unlink($filePath);
}