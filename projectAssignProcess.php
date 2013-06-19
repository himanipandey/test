<?php
//if(empty($_POST['assign'])) $errorMsg[] = 'No Project Selected for Assignment';

$projectIds = array(1,2,3,4,5);//$_POST['assign'];
$projectDetails = getMultipleProjectDetails($projectIds);
$executiveWorkLoad = getExecutiveWorkLoad();

function getExecutiveWorkLoad(){
    $sql = "select pa.ADMINID, pa.USERNAME, max(t.TOTAL) WORKLOAD from (select pa.ADMINID, 0 TOTAL from proptiger_admin pa union select pa.ADMINID, count(rp.MOVEMENT_HISTORY_ID) TOTAL from proptiger_admin pa inner join project_assignment pa1 on pa.ADMINID = pa1.ASSIGNED_TO inner join resi_project rp on pa1.MOVEMENT_HISTORY_ID = rp.MOVEMENT_HISTORY_ID  group by pa.ADMINID) t inner join proptiger_admin pa on t.ADMINID = pa.ADMINID where pa.ROLE = 'executive' group by pa.ADMINID order by WORKLOAD;";
    $sql = "select pa.ADMINID, pa.USERNAME, max(t.TOTAL) WORKLOAD from (select pa.ADMINID, 0 TOTAL from proptiger_admin pa union select pa.ADMINID, count(rp.MOVEMENT_HISTORY_ID) TOTAL from proptiger_admin pa inner join project_assignment pa1 on pa.ADMINID = pa1.ASSIGNED_TO inner join resi_project rp on pa1.MOVEMENT_HISTORY_ID = rp.MOVEMENT_HISTORY_ID  group by pa.ADMINID) t inner join proptiger_admin pa on t.ADMINID = pa.ADMINID group by pa.ADMINID order by WORKLOAD;";
    return $result = dbQuery($sql);
}

function getMultipleProjectDetails($projectIds){
    if (empty($projectIds)) return array();
    $sql = "select * from " . RESI_PROJECT . " where PROJECT_ID in (".  implode(',', $projectIds).")";
    return $result = dbQuery($sql);
}

$smarty->assign("errorMsg", $errorMsg);
$smarty->assign("projectDetails", $projectDetails);
$smarty->assign("executiveWorkLoad", $executiveWorkLoad);
?>