<?php
    include("smartyConfig.php");
    include("appWideConfig.php");
    include("dbConfig.php");
    include("includes/configs/configs.php");
    date_default_timezone_set('Asia/Kolkata');
    include("builder_function.php"); 
    include("function/reportsFunction.php"); 
    include("modelsConfig.php"); 
    AdminAuthentication();
    $dept = $_SESSION['DEPARTMENT'];
    
    $accessDailyPerform = '';
    if( $dailyPerformanceReportAuth == false )
       $accessDailyPerform = "No Access";
    $smarty->assign("accessDailyPerform",$accessDailyPerform);

    if (isset($_REQUEST['team']) ) {

        $team = $_REQUEST['team'];
        $adminDetail = getAdminDetail($team);
        $smarty->assign("adminDetailArr", $adminDetail['adminDetailArr']);
    }
    else
        $team = '';
    $smarty->assign("team", $team);

    if (isset($_REQUEST['user']) )
        $user = $_REQUEST['user'];
    else
        $user = '';
    $smarty->assign("user", $user);

    if (isset($_REQUEST['frmdate']) ) {
        if($_REQUEST['frmdate'] != '') {
            $frmdate = $_REQUEST['frmdate'];
        }
        else {
            $frmdate = date("Y-m-d");
        }
    }
    else {
        $frmdate = date("Y-m-d");
    }
    $smarty->assign("frmdate", $frmdate);

    if (isset($_REQUEST['todate']) ) {
       if($_REQUEST['todate'] != '') {
            $todate = $_REQUEST['todate'];
        }
        else {
            $todate = date("Y-m-d");
        }
    }
    else
        $todate = date("Y-m-d");
    $smarty->assign("todate", $todate);

    $dateArr = getDatesBetweeenTwoDates($frmdate,$todate);
    $errorMsg = array();
    if(count($dateArr) == 0)
        $errorMsg['dateDiff'] = "<font color = 'red'>From date can not be greater then to date!</font>";
    
    $mergeArr =  getDailyPerformanceReport($frmdate, $todate, $user, $team);

    $smarty->assign("errorMsg", $errorMsg);
    $smarty->assign("finalArr", $mergeArr['finalArr']);
    $smarty->assign("arrAllData", $mergeArr['arrAllData']);

    /*******code for fetch all active users**************/
    $adminDetail = getAdminDetail();
    $smarty->assign("teamArr", array_unique($adminDetail['teamArr']));
    $smarty->assign("adminDetailArr", $adminDetail['adminDetailArr']);

     /*******code for date drop down*************/
     $dateArr = array();
     for($i=0;$i<=10;$i++)
     {

        $dtval = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")-$i, date("Y")));
        $dtshow = date("d-m-Y",mktime(0, 0, 0, date("m"), date("d")-$i, date("Y")));
        $dateArr[$dtval] = $dtshow;
     }
     $smarty->assign("dateArr", $dateArr);

    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."daily_performance_report.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
?>
