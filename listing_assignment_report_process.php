<?php

//date filter
$errorMsg = '';
$conditions = '';
$frmdate = $_POST['from_date_filter'];
$todate = $_POST['to_date_filter'];
if ($frmdate && $todate) {
    $dateArr = getDatesBetweeenTwoDates($frmdate, $todate);

    if (count($dateArr) == 0) {
        $errorMsg['dateDiff'] = "<font color = 'red'>From date can not be greater then to date!</font>";
    } else {
        $conditions = " AND DATE(ca.created_at) between '" . $frmdate . "' AND '" . $todate . "'";
    }
}

$report_data = array();
if ($_SESSION['ROLE'] == 'rm') {

    //photographers work
    $pgf_work_sql = mysql_query("select pa.adminid pgf_id, pa.fname pgf_name,
                        count(ca.assigned_to) assigned , 
                        SUM(IF(ca.status = 'readyToTouchUp' OR ca.status = 'touchUpDone', 1, 0)) complete,
                        crm.fname admin,crm.adminid
                        from proptiger_admin pa
                        left join `cms_assignments` ca on ca.assigned_to = pa.adminid
                        inner join proptiger_admin fm on pa.manager_id = fm.adminid
                        inner join proptiger_admin crm on fm.manager_id = crm.adminid
                        inner join proptiger_admin rm on crm.manager_id = rm.adminid 
                        and rm.adminid = " . $_SESSION['adminId'] . "
                        where pa.status = 'Y' and pa.department = 'RESALE' and pa.role = 'photoGrapher' " . $conditions . "
                        group by pa.adminid") or die(mysql_error());
} elseif ($_SESSION['ROLE'] == 'crm') {

    //photographers work
    $pgf_work_sql = mysql_query("select pa.adminid pgf_id, pa.fname pgf_name,
                        count(ca.assigned_to) assigned , 
                        SUM(IF(ca.status = 'readyToTouchUp' OR ca.status = 'touchUpDone', 1, 0)) complete,
                        fm.fname admin,fm.adminid
                        from proptiger_admin pa
                        left join `cms_assignments` ca on ca.assigned_to = pa.adminid
                        inner join proptiger_admin fm on pa.manager_id = fm.adminid
                        inner join proptiger_admin crm on fm.manager_id = crm.adminid                        
                        and crm.adminid = " . $_SESSION['adminId'] . "
                        where pa.status = 'Y' and pa.department = 'RESALE' and pa.role = 'photoGrapher' " . $conditions . "
                        group by pa.adminid") or die(mysql_error());
}

if (mysql_num_rows($pgf_work_sql)) {
    $report_data = prepare_display($pgf_work_sql);
}

$smarty->assign('current_user_role', $_SESSION['ROLE']);
$smarty->assign('current_user', $_SESSION['adminId']);
$smarty->assign('report_data', $report_data);
$smarty->assign('frmdate', $frmdate);
$smarty->assign('todate', $todate);
$smarty->assign('errorMsg', $errorMsg);

?>
