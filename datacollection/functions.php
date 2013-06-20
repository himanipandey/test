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

function getCallCenterExecutiveWorkLoad($executives = array()){
    if(empty($executives)){
        $sql = "select pa.ADMINID, pa.USERNAME, max(t.TOTAL) WORKLOAD from (select pa.ADMINID, 0 TOTAL from proptiger_admin pa where pa.ROLE = 'executive' union select pa.ASSIGNED_TO, count(rp.MOVEMENT_HISTORY_ID) TOTAL from project_assignment pa inner join resi_project rp on pa.MOVEMENT_HISTORY_ID = rp.MOVEMENT_HISTORY_ID where ((PROJECT_STAGE = 'newProject' and PROJECT_PHASE = 'dcCallCenter') or (PROJECT_STAGE = 'updationCycle' and PROJECT_PHASE = 'dataCollection')) and pa.STATUS = 'notAttempted' group by pa.ASSIGNED_TO) t inner join proptiger_admin pa on t.ADMINID = pa.ADMINID where pa.DEPARTMENT in ('CALLCENTER', 'DATAENTRY') group by pa.ADMINID order by WORKLOAD;";
    }
    else{
        $sql = "select pa.ADMINID, pa.USERNAME, max(t.TOTAL) WORKLOAD from (select pa.ADMINID, 0 TOTAL from proptiger_admin pa where pa.ROLE = 'executive' union select pa.ASSIGNED_TO, count(rp.MOVEMENT_HISTORY_ID) TOTAL from project_assignment pa inner join resi_project rp on pa.MOVEMENT_HISTORY_ID = rp.MOVEMENT_HISTORY_ID where ((PROJECT_STAGE = 'newProject' and PROJECT_PHASE = 'dcCallCenter') or (PROJECT_STAGE = 'updationCycle' and PROJECT_PHASE = 'dataCollection')) and pa.STATUS = 'notAttempted' group by pa.ASSIGNED_TO) t inner join proptiger_admin pa on t.ADMINID = pa.ADMINID where pa.DEPARTMENT in ('CALLCENTER', 'DATAENTRY') and pa.ADMINID in (".  implode(',', $executives).") group by pa.ADMINID order by WORKLOAD;";
    }
    return $result = dbQuery($sql);
}

function getProjectListForManagers($adminId){
    $sql = "select rp.PROJECT_ID, rp.PROJECT_NAME, rp.BUILDER_NAME, c.LABEL CITY, l.LABEL LOCALITY, max(pa.UPDATION_TIME) as LAST_WORKED_AT, psh.PROJECT_STAGE, psh.PROJECT_PHASE, pshp.PROJECT_STAGE PREV_PROJECT_STAGE, pshp.PROJECT_PHASE PREV_PROJECT_PHASE, rp.MOVEMENT_HISTORY_ID, GROUP_CONCAT(pa.ASSIGNED_TO order by pa.ID asc separator '|') ASSIGNED_TO, GROUP_CONCAT(pa.CREATION_TIME order by pa.ID asc separator '|') ASSIGNED_AT, GROUP_CONCAT(pa.STATUS order by pa.ID asc separator '|') STATUS, GROUP_CONCAT(pa.EXECUTIVE_REMARK order by pa.ID asc separator '|') REMARK from resi_project rp inner join city c on rp.CITY_ID = c.CITY_ID inner join locality l on rp.LOCALITY_ID = l.LOCALITY_ID inner join project_stage_history psh on rp.MOVEMENT_HISTORY_ID = psh.HISTORY_ID left join project_stage_history pshp on psh.PREV_HISTORY_ID = pshp.HISTORY_ID left join project_assignment pa on rp.MOVEMENT_HISTORY_ID=pa.MOVEMENT_HISTORY_ID where ((rp.PROJECT_STAGE='newProject' and rp.PROJECT_PHASE='dcCallCenter') or (rp.PROJECT_STAGE='updationCycle' and rp.PROJECT_PHASE='dataCollection')) and rp.MOVEMENT_HISTORY_ID is not NULL group by rp.MOVEMENT_HISTORY_ID order by rp.PROJECT_ID;";
    return $res = dbQuery($sql);
}
?>
