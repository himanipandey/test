<?php



//print "<pre>".print_r($assignedLots,1)."</pre>";


//date filters
$date_filters = array(    
    'assignmentDate' => 'Assignment Date',    
       
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
        $assignedLots = fetch_assigned_lots($frmdate, $todate, $date_filter); 
        
    }
    
    $smarty->assign('date_filter', $date_filter);
    $smarty->assign('frmdate', $frmdate);
    $smarty->assign('todate', $todate);
    $smarty->assign('errorMsg', $errorMsg);
    
}else{
    //fetch all lots
    $assignedLots = fetch_assigned_lots();    
}

//fetch all assigned lots
$smarty->assign('assignedLots', $assignedLots);

?>