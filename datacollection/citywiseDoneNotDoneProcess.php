<?php
/*
 * citywise done/notDone Report process
 * Created By Vimlesh Rajput on 4/02/1014
 * citywiseDoneNotDone.php
 */
$accessDataCollection = '';
if( $dataCollectionFlowAuth == false )
   $accessDataCollection = "No Access";
$smarty->assign("accessDataCollection",$accessDataCollection);
/*****array of all acitve city where field lead belong*******/
$qryCity = "select distinct(c.city_id) as city_id,a.label as city_name, pa.adminid
    from proptiger_admin_city c join city a on c.city_id = a.city_id
    join proptiger_admin pa on c.admin_id = pa.adminid
    where pa.role = 'teamLeader' and pa.department = 'SURVEY' and a.status = 'Active'";
$resCity = mysql_query($qryCity) or die(mysql_error());
$arrCity = array();
while($dataRes = mysql_fetch_assoc($resCity)) {
    $arrCity[$dataRes['city_id']] = $dataRes;
}
$smarty->assign("arrCity",$arrCity);
/*****end array of all acitve city where field lead belong*******/

/********list of all leads***********/
$qryLead = "select adminid,fname,role from proptiger_admin where department = 'SURVEY'";
$redLead = mysql_query($qryLead) or die(mysql_error());
$arrLead = array();
$newArrLead = array();
while($dataLead = mysql_fetch_assoc($redLead)) {
    if($dataLead['role'] == 'teamLeader'){
        $newArrLead[$dataLead['adminid']] = $dataLead['fname'];
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
}
$smarty->assign('newArrLead',$newArrLead);
/********end list of all leads***********/

/******query for fetch all project id and history id which are in audit1 stage*****/
$qry = "select 
    rp.movement_history_id, rp.project_id
from
    project_stage_history h
        right join
    resi_project rp ON h.history_id = rp.movement_history_id
    
where
    rp.version = 'Cms' and rp.status in ('Active' , 'ActiveInCms') and h.project_phase_id = ".phaseId_4;//die;
$res = mysql_query($qry) or die(mysql_error());
$historyId = array();

while($auditStagePId = mysql_fetch_assoc($res)) {
      $qryHistory = "select h.project_id,pa1.fname,pa1.role,pa1.adminid,h.date_time,s.city_id 
            from project_stage_history h
            join resi_project rp on h.project_id = rp.project_id
            join locality l on rp.locality_id = l.locality_id
            join suburb s on l.suburb_id = s.suburb_id
            join city c on s.city_id = c.city_id
            join project_assignment pa on h.history_id = pa.movement_history_id
            join proptiger_admin pa1 on pa.assigned_to = pa1.adminid
            where 
            h.history_id < ".$auditStagePId['movement_history_id']."
            and h.project_id = ".$auditStagePId['project_id']." 
            and pa1.department = 'SURVEY'
            and h.project_phase_id != ".phaseId_4."
            and rp.version = 'Cms' and rp.status in('Active' , 'ActiveInCms')
            order by h.history_id desc limit 1";
         $resInner = mysql_query($qryHistory) or die(mysql_error());
         $historyInner =  mysql_fetch_assoc($resInner);
         if($historyInner['project_id'] != '')
             $historyId[$historyInner['adminid']][$historyInner['city_id']][] = $historyInner;
}

$citywiseDone = array();
foreach($historyId as $k=>$v) {
    $cnt = 0;
    $adminKey = 0;
    foreach($arrCity as $kCityId=>$vInner) {
        if(in_array($kCityId,  array_keys($v))) {
            $citywiseDone[$vInner['adminid']][$kCityId][] = count($historyId[$k][$kCityId]);
                $cnt +=count($historyId[$k][$kCityId]);
                $adminKey = $k;
        }
        else
            $citywiseDone[$vInner['adminid']][$kCityId][] = 0;   
    }
    $citywiseDone[$adminKey]['total'] = $cnt;
    
    
}
//echo "<pre>";
//print_r($citywiseDone);
$smarty->assign('citywiseDone',$citywiseDone);
//die;
/*$arrLeadProjectDone = array();
foreach($arrLead as $k=>$v) {
        $qryLeadCity = "select distinct city_id from proptiger_admin_city where admin_id = ".$v['admin_id'];
        $resLeadCity = mysql_query($qryLeadCity) or die(mysql_error());
        while($city = mysql_fetch_assoc($resLeadCity)) {
            if(in_array($v['adminid'], array_keys($historyId))) {
               */ 
           //     $arrLeadProjectDone[$v['adminid']][$city['city_id']]['done'][] = count($historyId[$v['adminid']][[$city['city_id']]);
               // $arrLeadProjectDone[$v['adminid']]['fname'] = $v['fname'];

           /* }
            else {
                $arrLeadProjectDone[$v['adminid']]['done'] = 0;
                $arrLeadProjectDone[$v['adminid']]['fname'] = $v['fname'];
            }
        }
}*/
/*******query for project not done************/
 $qryNotDone = "select rp.project_id,pa1.fname,pa1.role,pa1.adminid,pa.updation_time,s.city_id from 
                resi_project rp join project_assignment pa on 
                     rp.movement_history_id = pa.movement_history_id
                    join locality l on rp.locality_id = l.locality_id
                join suburb s on l.suburb_id = s.suburb_id
                join city c on s.city_id = c.city_id     
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
while($dataNotDone = mysql_fetch_assoc($resNotDone)) {
        $arrNotDone[$dataNotDone['adminid']][$dataNotDone['city_id']][] = $dataNotDone;
}
$citywiseNotDone = array();
foreach($arrNotDone as $k=>$v) {
   $cnt = 0;
    $adminKey = 0;
    foreach($arrCity as $kCityId=>$vInner) {
        if(in_array($kCityId,  array_keys($v))) {
            $citywiseNotDone[$vInner['adminid']][$kCityId][] = count($arrNotDone[$k][$kCityId]);
                $cnt +=count($arrNotDone[$k][$kCityId]);
                $adminKey = $k;
        }
        else
            $citywiseNotDone[$vInner['adminid']][$kCityId][] = 0;   
    }
    $citywiseNotDone[$adminKey]['total'] = $cnt;
}

$smarty->assign('citywiseNotDone',$citywiseNotDone);
$smarty->assign('arrExecProjectDone',$arrExecProjectDone);
?>


