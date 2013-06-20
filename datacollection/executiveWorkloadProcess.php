<?php
function getExecutiveWorkLoad(){
    $sql = "select pa.ADMINID, pa.USERNAME, max(t.TOTAL) WORKLOAD from (select pa.ADMINID, 0 TOTAL from proptiger_admin pa union select pa.ADMINID, count(rp.MOVEMENT_HISTORY_ID) TOTAL from proptiger_admin pa inner join project_assignment pa1 on pa.ADMINID = pa1.ASSIGNED_TO inner join resi_project rp on pa1.MOVEMENT_HISTORY_ID = rp.MOVEMENT_HISTORY_ID  group by pa.ADMINID) t inner join proptiger_admin pa on t.ADMINID = pa.ADMINID group by pa.ADMINID order by WORKLOAD;";
    return $result = dbQuery($sql);
}


$executiveWorkLoad = getExecutiveWorkLoad();

$smarty->assign("executiveWorkLoad", $executiveWorkLoad);


?>