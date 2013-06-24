<?php
function getAssignedProjects($adminId){
    $sql = "select rp.PROJECT_ID, rp.PROJECT_NAME, rp.BUILDER_NAME, c.LABEL CITY, pa.CREATION_TIME ASSIGNMENT_DATE, count(pa1.MOVEMENT_HISTORY_ID) ASSIGNMENT_COUNT, pa.STATUS, pa.EXECUTIVE_REMARK REMARK from project_assignment pa inner join resi_project rp on pa.MOVEMENT_HISTORY_ID = rp.MOVEMENT_HISTORY_ID inner join city c on c.CITY_ID = rp.CITY_ID inner join project_assignment pa1 on pa.MOVEMENT_HISTORY_ID = pa1.MOVEMENT_HISTORY_ID where pa.ASSIGNED_TO = ".$_SESSION[adminId]." and pa.STATUS = 'notAttempted' group by rp.MOVEMENT_HISTORY_ID;";
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
    $sql = "select rp.PROJECT_ID, rp.PROJECT_NAME, rp.BUILDER_NAME, c.LABEL CITY, l.LABEL LOCALITY, max(pa.UPDATION_TIME) as LAST_WORKED_AT, psh.PROJECT_STAGE, psh.PROJECT_PHASE, pshp.PROJECT_STAGE PREV_PROJECT_STAGE, pshp.PROJECT_PHASE PREV_PROJECT_PHASE, rp.MOVEMENT_HISTORY_ID, GROUP_CONCAT(pa1.USERNAME order by pa.ID asc separator '|') ASSIGNED_TO, GROUP_CONCAT(pa1.DEPARTMENT order by pa.ID asc separator '|') DEPARTMENT, GROUP_CONCAT(pa.CREATION_TIME order by pa.ID asc separator '|') ASSIGNED_AT, GROUP_CONCAT(pa.STATUS order by pa.ID asc separator '|') STATUS, GROUP_CONCAT(pa.EXECUTIVE_REMARK order by pa.ID asc separator '|') REMARK from resi_project rp inner join city c on rp.CITY_ID = c.CITY_ID inner join locality l on rp.LOCALITY_ID = l.LOCALITY_ID inner join project_stage_history psh on rp.MOVEMENT_HISTORY_ID = psh.HISTORY_ID left join project_stage_history pshp on psh.PREV_HISTORY_ID = pshp.HISTORY_ID left join project_assignment pa on rp.MOVEMENT_HISTORY_ID=pa.MOVEMENT_HISTORY_ID left join proptiger_admin pa1 on pa.ASSIGNED_TO = pa1.ADMINID where ((rp.PROJECT_STAGE='newProject' and rp.PROJECT_PHASE='dcCallCenter') or (rp.PROJECT_STAGE='updationCycle' and rp.PROJECT_PHASE='dataCollection')) and rp.MOVEMENT_HISTORY_ID is not NULL group by rp.MOVEMENT_HISTORY_ID order by rp.PROJECT_ID limit 100;";
    return $res = dbQuery($sql);
}

function assignToCCExecutives($projectList, $executiveList){
    $errorList = array();
    $executiveCount = count($executiveList);
    $j = 0;
    while($projectId = current($projectList)){
        if($executiveList[$j%$executiveCount]['WORKLOAD'] >= 80){
            unset($executiveList[$j/$executiveCount]);
            array_values($executiveList);
            $executiveCount = $executiveCount - 1;
        }
        if($executiveCount > 0){
            $assign = assignProject($projectId, $executiveList[($j%$executiveCount)]['ADMINID']);
            if(is_int($assign)){
                next($projectList);
                $j++;
                continue;
            }
            elseif ($assign === 'alreadyAssignedToSameId') {
                if($executiveCount == 1){
                    $errorList[$projectId] = 'No executive left within selected executives.';
                    next($projectList);
                }
                else{
                    $assign = assignProject($projectId, $executiveList[($j%$executiveCount)]['ADMINID']);
                }
            }
            else{
                $errorList[$projectId] = 'Error Occured While Assignment';
                next($projectList);
            }
        }
    }
    return $errorList;
}

function assignProject($projectId, $adminId){
    $flag = false;
    dbExecute('begin');
    $sql = "select rp.PROJECT_ID, rp.MOVEMENT_HISTORY_ID, pa.ASSIGNED_TO, pa.STATUS, pa.ID from resi_project rp left join project_assignment pa on rp.MOVEMENT_HISTORY_Id = pa.MOVEMENT_HISTORY_Id where PROJECT_ID = $projectId order by pa.ID for update;";
    $assignmentHistory = dbQuery($sql);
    $movementId = $assignmentHistory[0]['MOVEMENT_HISTORY_ID'];
    $count = count($assignmentHistory);
    $lastAssignmentId = $assignmentHistory[$count-1]['ID'];
    if($assignmentHistory[$count-1]['STATUS'] === 'notAttempted'){
        $sql = "update project_assignment set ASSIGNED_TO = $adminId, ASSIGNED_BY = $_SESSION[adminId], CREATION_TIME = NOW()  where ID = $lastAssignmentId;";
        $flag = dbExecute($sql);
    }
    elseif($count < 3){
        if($assignmentHistory[$count-1]['ASSIGNED_TO'] == $adminId){
            $error = 'alreadyAssignedToSameId';
        }
        else{
            $sql = "insert into project_assignment (MOVEMENT_HISTORY_ID, ASSIGNED_TO, ASSIGNED_BY, STATUS, CREATION_TIME, UPDATION_TIME) values($movementId, $adminId, $_SESSION[adminId], 'notAttempted', NOW(), NOW());";
            $flag = dbExecute($sql);
        }
    }
    elseif($count>=3){
        $adminDetail = AdminDetail($adminId);
        if($adminDetail['DEPARTMENT'] === 'SURVEY'){
            $sql = "insert into project_assignment (MOVEMENT_HISTORY_ID, ASSIGNED_TO, ASSIGNED_BY, STATUS, CREATION_TIME, UPDATION_TIME) values($movementId, $adminId, $_SESSION[adminId], 'notAttempted', NOW(), NOW());";
            $flag = dbExecute($sql);
        }
        else{
            $error = 'assignmentCountReached';
        }
    }
    dbExecute('commit');
    if ($flag) return $flag;
    return $error;
}

function getMultipleProjectDetails($projectIds){
    if (empty($projectIds)) return array();
        $sql = "select * from " . RESI_PROJECT . " where PROJECT_ID in (".  implode(',', $projectIds).")";
    return $result = dbQuery($sql);
}

function getSurveyTeamLeads(){
    $sql = "select pa.ADMINID, GROUP_CONCAT(pac.CITY_ID) from proptiger_admin pa inner join proptiger_admin_city pac on pa.ADMINId = pac.ADMIN_ID where pa.DEPARTMENT = 'SURVEY' and ROLE = 'teamLeader' group by pa.ADMINID;";
    return dbQuery($sql);
}

//returns all the cities for which survey team lead is there
//city id as index and admin id as value
function getSurveyTeamLeadsForCities(){
    $sql = "select pac.CITY_ID, pa.ADMINID from proptiger_admin_city pac inner join proptiger_admin pa on pa.ADMINId = pac.ADMIN_ID where pa.DEPARTMENT = 'SURVEY' and ROLE = 'teamLeader';";
    $queryRes = dbQuery($sql);
    $result = array();
    foreach ($queryRes as $value) {
        $result[$value['CITY_ID']] = $value['ADMINID'];
    }
    return $result;
}

function assignProjectsToField($projectIds){
    $result = array();
    $projectDetails = getMultipleProjectDetails($projectIds);
    $fieldTeamLeads  = getSurveyTeamLeadsForCities();
    foreach ($projectDetails as $project) {
        error_log(json_encode($project));
        error_log(json_encode($fieldTeamLeads));
        if(isset($fieldTeamLeads[$project['CITY_ID']])){
            $res = assignProject($project['PROJECT_ID'], $fieldTeamLeads[$project['CITY_ID']]);
            if(!is_int($res)){
                $result[$project['PROJECT_ID']] = $res;
            }
        }
        else{
            $result[$project['PROJECT_ID']] = 'No survey teamlead for this project';
        }
    }
}
?>
