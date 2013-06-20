<?php
function getAssignedProjects($adminId){
    $sql = "select rp.PROJECT_ID, rp.PROJECT_NAME, rp.BUILDER_NAME, c.LABEL CITY, pa.CREATION_TIME ASSIGNMENT_DATE, count(pa1.MOVEMENT_HISTORY_ID) ASSIGNMENT_COUNT, pa.STATUS, pa.EXECUTIVE_REMARK REMARK from project_assignment pa inner join resi_project rp on pa.MOVEMENT_HISTORY_ID = rp.MOVEMENT_HISTORY_ID inner join city c on c.CITY_ID = rp.CITY_ID inner join project_assignment pa1 on pa.MOVEMENT_HISTORY_ID = pa1.MOVEMENT_HISTORY_ID where pa.ASSIGNED_TO = $_SESSION[adminId] and pa.STATUS = 'notAttempted' group by rp.MOVEMENT_HISTORY_ID;";
    return dbQuery($sql);
}

function saveStatusUpdateByExecutive($projectID, $status, $remark){
    dbExecute('begin');
    $sql = "select rp.PROJECT_ID, pa.ID from resi_project rp inner join project_assignment pa on rp.MOVEMENT_HISTORY_ID = pa.MOVEMENT_HISTORY_ID where rp.PROJECT_ID = $projectID and pa.ASSIGNED_TO = $_SESSION[adminId] and pa.STATUS = 'notAttempted' for update;";
    $result = dbQuery($sql);
    if(count($result)==1){
        $ID = $result[0]['ID'];
        $sql = "update project_assignment set STATUS = '$status', EXECUTIVE_REMARK = '$remark' where ID = $ID;";
        $result = dbExecute($sql);
    }
    dbExecute('commit');
    return;
}


?>
