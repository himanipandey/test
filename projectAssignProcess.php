<?php
echo "<pre>";
print_r($_SESSION);

function getProjectListForManagers($adminId){
    $sql = "select rp.PROJECT_ID, rp.MOVEMENT_HISTORY_ID, pa.ASSIGNED_TO from resi_project rp inner join proptiger_admin_city pac on rp.CITY_ID=pac.CITY_ID left join project_assignment pa on rp.MOVEMENT_HISTORY_ID=pa.MOVEMENT_HISTORY_ID where pac.ADMIN_ID = $adminId and rp.PROJECT_STAGE='newProject' and rp.PROJECT_PHASE='dcCallCenter' and rp.MOVEMENT_HISTORY_ID is not NULL";
    //$result 	= mysql_query($sql) or die(mysql_error().' Error in function getProjectListForManagers()');
    return $res = dbQuery($sql);
}

$projectList = getProjectListForManagers($_SESSION['adminId']);

$smarty->assign("projectList", $projectList);

//print_r($projectList);
?>