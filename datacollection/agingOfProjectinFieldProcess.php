<?php

$accessDataCollection = '';
if( $dataCollectionFlowAuth == false )
   $accessDataCollection = "No Access";
$smarty->assign("accessDataCollection",$accessDataCollection);
$qryLead = "select adminid,fname,role from proptiger_admin where department = 'SURVEY'";
$redLead = mysql_query($qryLead) or die(mysql_error());
$arrLead = array();
$arrExecutive = array();
$arrExecLead = array();
while($dataLead = mysql_fetch_assoc($redLead)) {
    if($dataLead['role'] == 'teamLeader'){
        $arrLead[] = $dataLead;
        $qryExeclead = "select distinct(a.admin_id),b.fname from proptiger_admin_city a 
                        join proptiger_admin b on a.admin_id = b.adminid
                        where a.city_id in (SELECT city_id FROM cms.proptiger_admin_city 
                                where admin_id = ".$dataLead['adminid']."
                        ) and admin_id != ".$dataLead['adminid']." and department != 'ADMINISTRATOR'";
        $resExeclead = mysql_query($qryExeclead) or die(mysql_error());
        while($dataExec = mysql_fetch_assoc($resExeclead)) {
            $arrExecLead[$dataLead['adminid']][] = $dataExec;
        }
    }
    elseif($dataLead['role'] == 'executive')
        $arrExecutive[] = $dataLead;
}
//echo "<pre>";
//print_r($arrExecLead);
$smarty->assign("arrExecLead",$arrExecLead);

$arrLeadProjectDone = array();
$arrExecProjectDone = array();
//echo "<pre>";
//print_r($arrExecLead);//die;
foreach($arrExecutive as $k=>$v) {
    $qryExec = "select count(pa.movement_history_id) count from project_assignment pa
 join proptiger_admin pa1 on pa.assigned_to = pa1.adminid     
 where pa.status = 'done' and pa.assigned_to = ".$v['adminid'];
    $resExec = mysql_query($qryExec) or die(mysql_query());
    $dataExec = mysql_fetch_assoc($resExec);
    $arrExecProjectDone[$v['adminid']]['done'] = $dataExec['count'];
    $arrExecProjectDone[$v['adminid']]['fname'] = $v['fname'];

}
foreach($arrLead as $k=>$v) {
    $qryExec = "select count(pa.movement_history_id) count from project_assignment pa
    join proptiger_admin pa1 on pa.assigned_to = pa1.adminid     
    where pa.status = 'done' and pa.assigned_to = ".$v['adminid'];
    $resExec = mysql_query($qryExec) or die(mysql_query());
    $dataExec = mysql_fetch_assoc($resExec);
    $arrLeadProjectDone[$v['adminid']]['done'] = $dataExec['count'];
    $arrLeadProjectDone[$v['adminid']]['fname'] = $v['fname'];
}

  $qryNotDone = "select rp.project_id,pa1.fname,pa1.role,pa1.adminid,pa.updation_time from 
                resi_project rp join project_assignment pa on 
                     rp.movement_history_id = pa.movement_history_id
                join proptiger_admin pa1 on pa.assigned_to = pa1.adminid
                inner join master_project_phases mpp on rp.project_phase_id = mpp.id
               inner join master_project_stages mpstg on rp.project_stage_id = mpstg.id
               where 
               ((mpstg.name = '".NewProject_stage."' and mpp.name = '".DcCallCenter_phase."') 
               or (mpstg.name = '".UpdationCycle_stage."' and mpp.name = '".DataCollection_phase."'))
                and pa1.department = 'SURVEY'
                and rp.version = 'Cms' and rp.status in('Active' , 'ActiveInCms')
                 order by pa.updation_time asc";
$resNotDone = mysql_query($qryNotDone) or die(mysql_error());
$arrNotDone = array();
$arrAging = array();
while($dataNotDone = mysql_fetch_assoc($resNotDone)) {
        $arrNotDone[$dataNotDone['adminid']][] = $dataNotDone;
        
        $expDate = explode(" ",$dataNotDone['updation_time']);
    $noOfDays = (strtotime(date('Y-m-d')) - strtotime($expDate['0']))/(60*60*24);
    if($noOfDays < 8)
        $arrAging[$dataNotDone['adminid']]["0-7"][] = $noOfDays;
    elseif($noOfDays < 16)
        $arrAging[$dataNotDone['adminid']]["8-15"][] = $noOfDays;
    elseif($noOfDays < 31)
        $arrAging[$dataNotDone['adminid']]["16-30"][] = $noOfDays;
    else
        $arrAging[$dataNotDone['adminid']]["plus30"][] = $noOfDays;
}

foreach($arrExecutive as $k=>$v) {
    
    if(in_array($v['adminid'], array_keys($arrNotDone))) {
        $arrExecProjectDone[$v['adminid']]['notDone'] = count($arrNotDone[$v['adminid']]);
        $arrExecProjectDone[$v['adminid']]['fname'] = $v['fname'];
    }
    else {
        $arrExecProjectDone[$v['adminid']]['notDone'] = 0;
        $arrExecProjectDone[$v['adminid']]['fname'] = $v['fname'];
    }
    if(isset($arrAging[$v['adminid']])){
        if(isset($arrAging[$v['adminid']]["0-7"])) {
            $arrExecProjectDone[$v['adminid']]["0-7"] = count($arrAging[$v['adminid']]["0-7"]);
        }
        else
            $arrExecProjectDone[$v['adminid']]["0-7"] = 0;
       if(isset($arrAging[$v['adminid']]["8-15"])) {
            $arrExecProjectDone[$v['adminid']]["8-15"] = count($arrAging[$v['adminid']]["8-15"]);
        }
        else
           $arrExecProjectDone[$v['adminid']]["8-15"] = 0; 
        if(isset($arrAging[$v['adminid']]["16-30"])) {
            $arrExecProjectDone[$v['adminid']]["16-30"] = count($arrAging[$v['adminid']]["16-30"]);
        }
        else
            $arrExecProjectDone[$v['adminid']]["16-30"] = 0;
        if(isset($arrAging[$v['adminid']]["plus30"])) {
            $arrExecProjectDone[$v['adminid']]["plus30"] = count($arrAging[$v['adminid']]["plus30"]);
        }
        else
           $arrExecProjectDone[$v['adminid']]["plus30"] = 0; 
    }
    else{
        $arrExecProjectDone[$v['adminid']]["0-7"] = 0;
        $arrExecProjectDone[$v['adminid']]["8-15"] = 0;
        $arrExecProjectDone[$v['adminid']]["16-30"] = 0;
        $arrExecProjectDone[$v['adminid']]["plus30"] = 0;
    }
    $arrExecProjectDone[$v['adminid']]['total'] = $arrExecProjectDone[$v['adminid']]['notDone']+$arrExecProjectDone[$v['adminid']]['done'];
    $arrExecProjectDone[$v['adminid']]['doneRatio'] = ceil($arrExecProjectDone[$v['adminid']]['done']/$arrExecProjectDone[$v['adminid']]['total']*100);
    
}

foreach($arrLead as $k=>$v) {
    
    if(in_array($v['adminid'], array_keys($arrNotDone))) {
        $arrLeadProjectDone[$v['adminid']]['notDone'] = count($arrNotDone[$v['adminid']]);
        $arrLeadProjectDone[$v['adminid']]['fname'] = $v['fname'];
    }
    else {
        $arrLeadProjectDone[$v['adminid']]['notDone'] = 0;
        $arrLeadProjectDone[$v['adminid']]['fname'] = $v['fname'];
    }
    if(isset($arrAging[$v['adminid']])){
        if(isset($arrAging[$v['adminid']]["0-7"])) {
            $arrLeadProjectDone[$v['adminid']]["0-7"] = count($arrAging[$v['adminid']]["0-7"]);
        }
        else
            $arrLeadProjectDone[$v['adminid']]["0-7"] = 0;
       if(isset($arrAging[$v['adminid']]["8-15"])) {
            $arrLeadProjectDone[$v['adminid']]["8-15"] = count($arrAging[$v['adminid']]["8-15"]);
        }
        else
           $arrLeadProjectDone[$v['adminid']]["8-15"] = 0; 
        if(isset($arrAging[$v['adminid']]["16-30"])) {
            $arrLeadProjectDone[$v['adminid']]["16-30"] = count($arrAging[$v['adminid']]["16-30"]);
        }
        else
            $arrLeadProjectDone[$v['adminid']]["16-30"] = 0;
        if(isset($arrAging[$v['adminid']]["plus30"])) {
            $arrLeadProjectDone[$v['adminid']]["plus30"] = count($arrAging[$v['adminid']]["plus30"]);
        }
        else
           $arrLeadProjectDone[$v['adminid']]["plus30"] = 0; 
    }
    else{
        $arrLeadProjectDone[$v['adminid']]["0-7"] = 0;
        $arrLeadProjectDone[$v['adminid']]["8-15"] = 0;
        $arrLeadProjectDone[$v['adminid']]["16-30"] = 0;
        $arrLeadProjectDone[$v['adminid']]["plus30"] = 0;
    }
    $arrLeadProjectDone[$v['adminid']]['total'] = $arrLeadProjectDone[$v['adminid']]['notDone']+$arrLeadProjectDone[$v['adminid']]['done'];
    if($arrLeadProjectDone[$v['adminid']]['total'] != 0)
        $arrLeadProjectDone[$v['adminid']]['doneRatio'] = ceil($arrLeadProjectDone[$v['adminid']]['done']/$arrLeadProjectDone[$v['adminid']]['total']*100);
    else
        $arrLeadProjectDone[$v['adminid']]['doneRatio'] = 0;
}
//echo "<pre>";
//print_r($arrLeadProjectDone);
//echo "<pre>";
//print_r($arrExecProjectDone);
$smarty->assign('arrLeadProjectDone',$arrLeadProjectDone);
$smarty->assign('arrExecProjectDone',$arrExecProjectDone);
?>

