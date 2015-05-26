<?php

include("../smartyConfig.php");
include("../appWideConfig.php");
include("../modelsConfig.php");
include("../dbConfig.php");
include("../httpful.phar");
include("../includes/configs/configs.php");
include("../builder_function.php");
include("../function/functions_assignments.php");

$current_user_role = filter_input(INPUT_GET, "current_user_role");
$date_filter['error_msg'] = filter_input(INPUT_GET, "error_msg");
$frmdate = filter_input(INPUT_GET, "frmdate");
$todate = filter_input(INPUT_GET, "todate");
$current_user = filter_input(INPUT_GET, "current_user");

if ($frmdate && $todate) {
    $dateArr = getDatesBetweeenTwoDates($frmdate, $todate);

    if (count($dateArr) == 0) {
        $errorMsg['dateDiff'] = "<font color = 'red'>From date can not be greater then to date!</font>";
    } else {
        $conditions = " AND DATE(ca.created_at) between '" . $frmdate . "' AND '" . $todate . "'";
    }
}

$report_data = array();
if ($current_user_role == 'rm') {

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
                        and rm.adminid = " . $current_user . "
                        where pa.status = 'Y' and pa.department = 'RESALE' and pa.role = 'photoGrapher' " . $conditions . "
                        group by pa.adminid") or die(mysql_error());
} elseif ($current_user_role == 'crm') {

    //photographers work
    $pgf_work_sql = mysql_query("select pa.adminid pgf_id, pa.fname pgf_name,
                        count(ca.assigned_to) assigned , 
                        SUM(IF(ca.status = 'readyToTouchUp' OR ca.status = 'touchUpDone', 1, 0)) complete,
                        fm.fname admin,fm.adminid
                        from proptiger_admin pa
                        left join `cms_assignments` ca on ca.assigned_to = pa.adminid
                        inner join proptiger_admin fm on pa.manager_id = fm.adminid
                        inner join proptiger_admin crm on fm.manager_id = crm.adminid                        
                        and crm.adminid = " . $current_user . "
                        where pa.status = 'Y' and pa.department = 'RESALE' and pa.role = 'photoGrapher' " . $conditions . "
                        group by pa.adminid") or die(mysql_error());
}

if (mysql_num_rows($pgf_work_sql)) {
    $report_data = prepare_display($pgf_work_sql);
}

download_report($report_data);
?>
<?php

function download_report($report_data) {
    $content .= '<table width="100%" class="row-border stripe hover" style="color:#fff" cellSpacing=1 cellPadding=4 >
            <thead>
                <TR class = "">
                    <TH width="5%" align="center">Serial</TH>
                    <TH width="25%" align="center">Team</TH>
                    <th width="20%" align="center">Assigned</th>
                    <TH width="20%" align="center">Pending</TH>
                    <th width="22%" align="center">Complete ( Photo Clicked / Touchup Done )</th>                                                            
            </thead>
            <tbody>';
    if (count($report_data)) {
        $count = 0;
        foreach ($report_data as $key => $data) {

            foreach ($data['data'] as $k => $sub_data) {
                $count = $count + 1;
                if ($count % 2 == 0)
                    $color = "bgcolor = ''";
                else
                    $color = "bgcolor = ''";
                $content .= '<tr >
                        <td>' . $count . '</td>
                        <td>' . $sub_data['pgf_name'] . '</td>
                        <td>' . $sub_data['assigned'] . '</td>
                        <td>' . $sub_data['pending'] . '</td>
                        <td>' . $sub_data['complete'] . '</td>
                    </tr>';
            }
            ;
            if ($data['data'])
                $color = "style=''";
            else
                $color = "style=''";


            $content .= '<tr>
                    <td>&nbsp;</td>
                    <td><b>' . $data['admin'] . '</b></td>
                    <td>' . $data['total_assigned'] . '</td>
                    <td>' . $data['total_pending'] . '</td>
                    <td>' . $data['total_complete'] . '</td>
                </tr>';
        }
    }else {
        $content .= '<tr>
                <td colspan="5">No Data!</td>
            </tr>';
    }
    $content .= "</tbody></table>";
    
    $filename = "assignment-listings-report-" . date('YmdHis') . ".xls";
    header('Content-type: application/ms-excel');
    header('Content-Disposition: attachment; filename=' . $filename);
    echo $content;
}
?>
