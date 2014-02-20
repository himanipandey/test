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
    }
}
$smarty->assign('newArrLead',$newArrLead);
/********end list of all leads***********/
//echo "<pre>";
//print_r($arrCity);
$citywiseDone = array();

    foreach($arrCity as $kCityId=>$vInner) {
        $qryExec = "select count(pa.movement_history_id) count from project_assignment pa
                    join proptiger_admin pa1 on pa.assigned_to = pa1.adminid
                    join project_stage_history psh on pa.movement_history_id = psh.history_id
                    where pa.status = 'done' and psh.project_id in(
                        select project_id from resi_project rp join locality l on (rp.locality_id = l.locality_id)
                        join suburb s on l.suburb_id = s.suburb_id
                        where s.city_id = $kCityId
                   )";
        $resExec = mysql_query($qryExec) or die(mysql_query());
        $dataExec = mysql_fetch_assoc($resExec);
            $citywiseDone[$vInner['adminid']][$kCityId][] = $dataExec['count'];
            
    }

//echo "<pre>";
//print_r($citywiseDone);
$smarty->assign('citywiseDone',$citywiseDone);

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


