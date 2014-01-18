<?php
function getAssignedProjects($adminId=NULL){
    if(is_null($adminId))$adminId = $_SESSION['adminId'];
    $sql = "select rp.PROJECT_ID, rp.PROJECT_NAME, rb.BUILDER_NAME, c.LABEL CITY, pa.CREATION_TIME 
        ASSIGNMENT_DATE, pa.STATUS, pa.EXECUTIVE_REMARK REMARK from project_assignment pa 
        inner join resi_project rp 
        on (rp.MOVEMENT_HISTORY_ID = pa.MOVEMENT_HISTORY_ID and (rp.updation_cycle_id is null
            or rp.updation_cycle_id = pa.updation_cycle_id))
        inner join resi_builder rb on rp.builder_id = rb.builder_id
        inner join locality l on rp.locality_id = l.locality_id
        inner join suburb s on l.suburb_id = s.suburb_id
        inner join city c on s.CITY_ID = c.CITY_ID 
        where pa.ASSIGNED_TO = ".$adminId." and pa.STATUS = 'notAttempted' and rp.version = 'Cms';";
    return dbQuery($sql);
}

function saveStatusUpdateByExecutive($projectID, $status, $remark){
    dbExecute('begin');
    $sql = "select rp.PROJECT_ID, pa.ID from resi_project rp 
        inner join project_assignment pa 
            on (rp.MOVEMENT_HISTORY_ID = pa.MOVEMENT_HISTORY_ID and (rp.updation_cycle_id is null
            or rp.updation_cycle_id = pa.updation_cycle_id))
        where rp.PROJECT_ID = $projectID 
            and pa.ASSIGNED_TO = $_SESSION[adminId] 
            and rp.version = 'Cms'
            and pa.STATUS = 'notAttempted' for update;";
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
    $department = "'CALLCENTER', 'DATAENTRY','SURVEY'";
    if(empty($executives)){
        $sql = "select pa.ADMINID, pa.USERNAME, max(t.TOTAL) WORKLOAD 
            from 
            (select pa.ADMINID, 0 TOTAL from proptiger_admin pa 
              where pa.ROLE = 'executive' union select pa.ASSIGNED_TO, 
               count(rp.MOVEMENT_HISTORY_ID) TOTAL from project_assignment pa 
               inner join resi_project rp 
               on (rp.MOVEMENT_HISTORY_ID = pa.MOVEMENT_HISTORY_ID and (rp.updation_cycle_id is null
            or rp.updation_cycle_id = pa.updation_cycle_id))
               inner join master_project_phases mpp on rp.project_phase_id = mpp.id
               inner join master_project_stages mpstg on rp.project_stage_id = mpstg.id
               where 
               ((mpstg.name = '".NewProject_stage."' and mpp.name = '".DcCallCenter_phase."') 
               or (mpstg.name = '".UpdationCycle_stage."' and mpp.name = '".DataCollection_phase."')) and rp.version ='Cms' 
               and pa.STATUS = 'notAttempted' group by pa.ASSIGNED_TO) t 
               inner join proptiger_admin pa on t.ADMINID = pa.ADMINID 
               where pa.DEPARTMENT in ($department)  group by pa.ADMINID order by WORKLOAD;";
    }
    else{
        $sql = "select pa.ADMINID, pa.USERNAME, max(t.TOTAL) WORKLOAD 
            from (select pa.ADMINID, 0 TOTAL from proptiger_admin pa 
            where pa.ROLE = 'executive' union select pa.ASSIGNED_TO, count(rp.MOVEMENT_HISTORY_ID) TOTAL 
            from project_assignment pa 
            inner join resi_project rp 
            on (rp.MOVEMENT_HISTORY_ID = pa.MOVEMENT_HISTORY_ID and (rp.updation_cycle_id is null
            or rp.updation_cycle_id = pa.updation_cycle_id))
            inner join master_project_phases mpp on rp.project_phase_id = mpp.id
               inner join master_project_stages mpstg on rp.project_stage_id = mpstg.id
            where ((mpstg.name = '".NewProject_stage."' and mpp.name = '".DcCallCenter_phase."') or 
            (mpstg.name = '".UpdationCycle_stage."' and mpp.name = '".DataCollection_phase."')) and rp.version = 'Cms' 
            and pa.STATUS = 'notAttempted' group by pa.ASSIGNED_TO) t 
            inner join proptiger_admin pa on t.ADMINID = pa.ADMINID 
            where pa.DEPARTMENT in ($department) and pa.ADMINID in 
            (".  implode(',', $executives).") group by pa.ADMINID order by WORKLOAD;";
    }
    return $result = dbQuery($sql);
}

function getProjectListForManagers($cityId, $department = '', $suburbId = '', $localityId = ''){
    if($department == 'survey')
        $department = "and pa1.DEPARTMENT = 'SURVEY'";
    else {
        $department = "and pa1.DEPARTMENT in( 'CALLCENTER','SURVEY')";
    }
    $sql = "select rp.PROJECT_ID, rp.PROJECT_NAME, rb.BUILDER_NAME, ps.PROJECT_STATUS,mbst.name as BOOKING_STATUS,
         psh.DATE_TIME MOVEMENT_DATE, c.LABEL CITY, l.LABEL LOCALITY,
         max(pa.UPDATION_TIME) as LAST_WORKED_AT, pstg.name as PROJECT_STAGE, pphs.name as PROJECT_PHASE, 
         mpsp.name as PREV_PROJECT_STAGE, mppp.name PREV_PROJECT_PHASE,
         rp.MOVEMENT_HISTORY_ID, GROUP_CONCAT(pa1.USERNAME order by pa.ID asc separator '|') ASSIGNED_TO
         , GROUP_CONCAT(pa1.USERNAME order by pa.ID asc separator '|') ASSIGNED_TO, 
         GROUP_CONCAT(pa1.DEPARTMENT order by pa.ID asc separator '|') ASSIGNED_TO_DEPART,
         GROUP_CONCAT(pa1.ROLE order by pa.ID asc separator '|') ROLE,
         DEPARTMENT, GROUP_CONCAT(pa.CREATION_TIME order by pa.ID asc separator '|') ASSIGNED_AT, 
         GROUP_CONCAT(pa.STATUS order by pa.ID asc separator '|') STATUS, 
         GROUP_CONCAT(pa.EXECUTIVE_REMARK order by pa.ID asc separator '|') REMARK, 
         if(uc.LABEL is null, 'No Label', uc.LABEL) LABEL from resi_project rp 
         inner join locality l on rp.locality_id = l.LOCALITY_ID 
         inner join suburb sub on l.suburb_id = sub.suburb_id
         inner join city c on sub.city_id = c.city_id
         inner join resi_builder rb on rp.builder_id = rb.builder_id
         inner join project_status_master ps on rp.project_status_id = ps.id
         inner join project_stage_history psh on rp.MOVEMENT_HISTORY_ID = psh.HISTORY_ID 
         left join project_stage_history pshp on psh.PREV_HISTORY_ID = pshp.HISTORY_ID 
         inner join master_project_stages pstg on rp.project_stage_id = pstg.id
         inner join master_project_phases pphs on rp.project_phase_id = pphs.id
         left join master_project_stages mpsp on pshp.PROJECT_STAGE_ID = mpsp.id
         left join master_project_phases mppp on pshp.PROJECT_PHASE_ID = mppp.id
         inner join resi_project_phase rpphs on rp.project_id = rpphs.project_id 
            and rpphs.PHASE_TYPE = 'Logical' and rpphs.version = 'Cms'
         left join master_booking_statuses mbst on rpphs.booking_status_id = mbst.id
         left join project_assignment pa ON (rp.MOVEMENT_HISTORY_ID = pa.MOVEMENT_HISTORY_ID and (rp.updation_cycle_id is null
            or rp.updation_cycle_id = pa.updation_cycle_id))
         left join proptiger_admin pa1 on 
         (pa.ASSIGNED_TO = pa1.ADMINID $department) left join updation_cycle uc on rp.UPDATION_CYCLE_ID 
         = uc.UPDATION_CYCLE_ID 
         where ((pstg.name = '".NewProject_stage."' and pphs.name = '".DcCallCenter_phase."') or 
            (pstg.name = '".UpdationCycle_stage."' and pphs.name = '".DataCollection_phase."')) and 
         rp.MOVEMENT_HISTORY_ID is not NULL and rp.status in ('ActiveInCms','Active') 
         and rp.version = 'Cms'  ";
    
    global $arrOtherCities;
    
    if($cityId == 'othercities'){
		$group_city_ids = array();
		foreach($arrOtherCities as $key => $value){
			$group_city_ids[] = $key;
		}
		$group_city_ids = implode(",",$group_city_ids);
		$sql = $sql." and c.CITY_ID in ($group_city_ids)";
	}
    elseif((int)$cityId != -1){// city id = -1 denotes all cities
       $sql = $sql." and c.CITY_ID=$cityId";
    }
    elseif((int)$cityId == -1 && $department == 'survey'){
    // city id = -1 denotes all cities for survey
        $arrTeamLeadList = arrSurveyTeamLeadCities($_SESSION['adminId']);
        $arrCityIdList[] = array_keys($arrTeamLeadList);
        $expCityList = implode(',',$arrCityIdList[0]);
        $sql = $sql." and c.CITY_ID in($expCityList)";
    }
    if($suburbId!=''){
        $sql = $sql . " and sub.SUBURB_ID=$suburbId ";
    }
    if($localityId!=''){
        $sql = $sql . " and l.LOCALITY_ID=$localityId ";
    }
   $sql = $sql . " group by rp.MOVEMENT_HISTORY_ID order by rp.PROJECT_ID;";
    return  $res = dbQuery($sql); 
}

function getAssignedProjectsFromPIDs($pids, $callingFieldFlag){
    if($callingFieldFlag == 'survey')
        $department = "and pa1.DEPARTMENT = 'SURVEY'";
    else {
        $department = "and pa1.DEPARTMENT in( 'CALLCENTER','SURVEY')";
    }
    $res = array();
    if(!empty($pids)){
       $sql = "select rp.PROJECT_ID, rp.PROJECT_NAME, rb.BUILDER_NAME, ps.PROJECT_STATUS, mbst.name as BOOKING_STATUS, psh.DATE_TIME MOVEMENT_DATE, c.LABEL CITY, l.LABEL LOCALITY,
         max(pa.UPDATION_TIME) as LAST_WORKED_AT, pstg.name as PROJECT_STAGE, pphs.name as PROJECT_PHASE, 
         mpsp.name as PREV_PROJECT_STAGE, mppp.name PREV_PROJECT_PHASE,
         rp.MOVEMENT_HISTORY_ID, GROUP_CONCAT(pa1.USERNAME order by pa.ID asc separator '|') ASSIGNED_TO, 
         GROUP_CONCAT(pa1.DEPARTMENT order by pa.ID asc separator '|') 
         DEPARTMENT,
         GROUP_CONCAT(pa1.DEPARTMENT order by pa.ID asc separator '|') ASSIGNED_TO_DEPART,
         GROUP_CONCAT(pa1.ROLE order by pa.ID asc separator '|') ROLE,
         GROUP_CONCAT(pa.CREATION_TIME order by pa.ID asc separator '|') ASSIGNED_AT, 
         GROUP_CONCAT(pa.STATUS order by pa.ID asc separator '|') STATUS, 
         GROUP_CONCAT(pa.EXECUTIVE_REMARK order by pa.ID asc separator '|') REMARK, 
         if(uc.LABEL is null, 'No Label', uc.LABEL) LABEL from resi_project rp 
         inner join locality l on rp.locality_id = l.LOCALITY_ID 
         inner join suburb sub on l.suburb_id = sub.suburb_id
         inner join city c on sub.city_id = c.city_id
         inner join resi_builder rb on rp.builder_id = rb.builder_id
         inner join project_status_master ps on rp.project_status_id = ps.id
         inner join project_stage_history psh 
         on rp.MOVEMENT_HISTORY_ID = psh.HISTORY_ID left join project_stage_history pshp 
         on psh.PREV_HISTORY_ID = pshp.HISTORY_ID 
         inner join resi_project_phase rpphs on rp.project_id = rpphs.project_id and rpphs.PHASE_TYPE = 'Logical' and rpphs.version = 'Cms'
         left join master_booking_statuses mbst on rpphs.booking_status_id = mbst.id
         inner join master_project_stages pstg on rp.PROJECT_STAGE_ID = pstg.id
         inner join master_project_phases pphs on rp.PROJECT_PHASE_ID = pphs.id
         left join master_project_stages mpsp on pshp.PROJECT_STAGE_ID = mpsp.id
         left join master_project_phases mppp on pshp.PROJECT_PHASE_ID = mppp.id
         left join 
         project_assignment pa ON (rp.MOVEMENT_HISTORY_ID = pa.MOVEMENT_HISTORY_ID and (rp.updation_cycle_id is null
            or rp.updation_cycle_id = pa.updation_cycle_id))
         left join proptiger_admin pa1 on 
         (pa.ASSIGNED_TO = pa1.ADMINID $department)  left join updation_cycle uc on rp.UPDATION_CYCLE_ID 
         = uc.UPDATION_CYCLE_ID where ((pstg.name = '".NewProject_stage."' and pphs.name = '".DcCallCenter_phase."') or 
            (pstg.name = '".UpdationCycle_stage."' and pphs.name = '".DataCollection_phase."')) and 
         rp.MOVEMENT_HISTORY_ID is not NULL and rp.status in ('ActiveInCms','Active') and rp.version = 'Cms'
            and rp.PROJECT_ID in (" .  implode(',', $pids) . ")  $department
                group by rp.MOVEMENT_HISTORY_ID order by rp.PROJECT_ID;";
        $res = dbQuery($sql);
    }
    return $res;
}

function assignToCCExecutives($projectList, $executiveList){
    $errorList = array();
    $executiveCount = count($executiveList);
    $j = 0;
    while($projectId = current($projectList)){
        if(intval($executiveList[$j%$executiveCount]['WORKLOAD']) >= 80){
            unset($executiveList[$j/$executiveCount]);
            array_values($executiveList);
            $executiveCount = $executiveCount - 1;
        }
        if($executiveCount > 0){
            $assign = assignProject($projectId, $executiveList[($j%$executiveCount)]['ADMINID']);
            $j++;
            if(is_int($assign)){
                next($projectList);
                continue;
            }
            elseif ($assign === 'alreadyAssignedToSameId') {
                if($executiveCount == 1){
                    $errorList[$projectId] = 'No executive left within selected executives.';
                    next($projectList);
                }
                else{
                    continue;
                }
            }
            else{
                $errorList[$projectId] = 'Error Occured While Assignment';
                next($projectList);
            }
        }else{
            $errorList[$projectId] = 'No More Executive Left to Assign';
            next($projectList);
        }
    }
    return $errorList;
}

function assignProject($projectId, $adminId){
    $flag = false;
    dbExecute('begin');
    $sql = "select rp.PROJECT_ID, rp.MOVEMENT_HISTORY_ID, pa.ASSIGNED_TO, pa.STATUS, pa.ID,rp.UPDATION_CYCLE_ID 
         from resi_project rp left join 
         project_assignment pa ON rp.MOVEMENT_HISTORY_ID = pa.MOVEMENT_HISTORY_ID and (rp.updation_cycle_id is null
            or rp.updation_cycle_id = pa.updation_cycle_id)
         where rp.PROJECT_ID = $projectId and rp.version = 'Cms' order by pa.ID for update;";
    $assignmentHistory = dbQuery($sql);
    $movementId = $assignmentHistory[0]['MOVEMENT_HISTORY_ID'];
    $count = count($assignmentHistory);
    $lastAssignmentId = $assignmentHistory[$count-1]['ID'];
    $assignedToAll = getAllAssignedToFromAssignmentHistory($assignmentHistory);
    if(count($assignmentHistory[$count-1]['UPDATION_CYCLE_ID']) == 0)
        $updationCycleId = 0;
    else
        $updationCycleId = $assignmentHistory[$count-1]['UPDATION_CYCLE_ID'];
    if($assignmentHistory[$count-1]['STATUS'] === 'notAttempted'){
        $sql = "update project_assignment 
            set ASSIGNED_TO = $adminId, ASSIGNED_BY = ".$_SESSION['adminId'].",
                CREATION_TIME = NOW(),
                UPDATION_CYCLE_ID = '".$updationCycleId."' where ID = $lastAssignmentId;";
        $flag = dbExecute($sql);
    }
    else{
        if(in_array($adminId, $assignedToAll)){
            $error = 'alreadyAssignedToSameId';
        }
        else{
            $sql = "insert into project_assignment (MOVEMENT_HISTORY_ID, ASSIGNED_TO, 
                ASSIGNED_BY, STATUS, CREATION_TIME, UPDATION_TIME, UPDATION_CYCLE_ID) values($movementId, $adminId, 
            ".$_SESSION['adminId'].", 'notAttempted', NOW(), NOW(),'".$updationCycleId."');";
            $flag = dbExecute($sql);
        }
    }
    dbExecute('commit');
    if ($flag) return $flag;
    return $error;
}

function getAllAssignedToFromAssignmentHistory($assignmentHistory){
    $assignedTo = array();
    foreach ($assignmentHistory as $value) {
        $assignedTo[] = $value['ASSIGNED_TO'];
    }
    return $assignedTo;
}

function getMultipleProjectDetails($projectIds){
    if (empty($projectIds)) return array();
        $sql = "select rp.*,rb.BUILDER_NAME from " . RESI_PROJECT . " rp join
            resi_builder rb on rp.builder_id = rb.builder_id 
            where rp.PROJECT_ID in (".  implode(',', $projectIds).") and rp.version ='Cms'";
    return $result = dbQuery($sql);
}

function getSurveyTeamLeads(){
    $sql = "select pa.ADMINID, GROUP_CONCAT(pac.CITY_ID) from proptiger_admin pa inner join proptiger_admin_city pac on pa.ADMINId = pac.ADMIN_ID where pa.DEPARTMENT = 'SURVEY' and ROLE = 'teamLeader' group by pa.ADMINID;";
    return dbQuery($sql);
}

//returns all the cities for which survey team lead is there
//city id as index and admin id as value
function getSurveyTeamLeadsForLocalities($localityId){
    $sql = "select l.LOCALITY_ID, pa.ADMINID from proptiger_admin_city pac 
        inner join proptiger_admin pa on pa.ADMINId = pac.ADMIN_ID 
        inner join suburb s on pac.city_id = s.city_id
        inner join locality l on s.suburb_id = l.suburb_id
        where pa.DEPARTMENT = 'SURVEY' and ROLE = 'teamLeader' and l.locality_id = $localityId;";
    $queryRes = dbQuery($sql);
    $result = array();
    foreach ($queryRes as $value) {
        $result[$value['LOCALITY_ID']] = $value['ADMINID'];
    }
    return $result;
}

function assignProjectsToField($projectIds){
    $result = array();
    $projectDetails = getMultipleProjectDetails($projectIds);
    foreach ($projectDetails as $project) {
        $fieldTeamLeads  = getSurveyTeamLeadsForLocalities($project['LOCALITY_ID']);
        if(isset($fieldTeamLeads[$project['LOCALITY_ID']])){
            $res = assignProject($project['PROJECT_ID'], $fieldTeamLeads[$project['LOCALITY_ID']]);
            if(!is_int($res)){
                $result[$project['PROJECT_ID']] = $res;
            }
        }
        else{
            $result[$project['PROJECT_ID']] = 'No survey teamlead for this project';
        }
    }
    
    return $result;
}

function getExecCallCount($startTime = '0', $endTime = NULL){
    if(is_null($endTime)) $endTime = strftime('%Y-%m-%d %T', time());
    $sql = "select pa.ADMINID, pa.USERNAME, cd.CallStatus, cd.DialStatus, 
        SUM(TIME_TO_SEC(CallDuration)) TOTAL_TIME,
        count(*) TOTAL_CALLS 
        from 
        proptiger_admin pa 
        inner join CallDetails cd on pa.ADMINID = cd.AgentID 
        where 
        cd.CreationTime between '" . $startTime . "' and '" . $endTime . "' 
        group by pa.ADMINID, cd.CallStatus, cd.DialStatus order by ADMINID;";
    return dbQuery($sql);
}

function getCompletionCountByExecs($startTime = '0', $endTime = NULL){
    if(is_null($endTime)) $endTime = strftime('%Y-%m-%d %T', time());
    $sql = "select ADMIN_ID, count(*) COMPLETED 
        from 
        project_stage_history 
        where
        PROJECT_PHASE_ID = ".phaseId_4." and DATE_TIME between '" . $startTime . "' 
            and '" . $endTime . "' group by ADMIN_ID;";
    return dbQuery($sql);
}

function getRevertCountForExecs($startTime = '0', $endTime = NULL){
    if(is_null($endTime)) $endTime = strftime('%Y-%m-%d %T', time());
    $sql = "select t2.ADMIN_ID, count(*) REVERT_COUNT from project_stage_history t1
        inner join project_stage_history t2 
        on t1.PREV_HISTORY_ID = t2.HISTORY_ID 
        where
        t1.PROJECT_PHASE_ID = ".phaseId_8." 
        and t2.PROJECT_PHASE_ID = ".phaseId_4." 
        and t2.DATE_TIME between '" . $startTime . "' 
        and '" . $endTime . "' group by t2.ADMIN_ID";
    return dbQuery($sql);
}

function excel_file_download($data, $filename){
    require_once dirname(__FILE__).'/../cron/cronFunctions.php';
    putResultsInFile($data, $filename);
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($filename));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filename));
    ob_clean();
    flush();
    readfile($filename);
}

function arrSurveyTeamLeadCities($teamLeadId){
    $qry = "select c.city_id,c.label from proptiger_admin_city a join city c 
        on a.city_id = c.city_id where a.admin_id = $teamLeadId";
    $res = mysql_query($qry) or die(mysql_error());
    $arrSurveyTeamLeadCity = array();
    while ($data = mysql_fetch_assoc($res)){
        $arrSurveyTeamLeadCity[$data['city_id']] = $data['label'];
    }
    return $arrSurveyTeamLeadCity;
}

function surveyexecutiveList(){
    $arrAllSurveyLeadCityList = arrSurveyTeamLeadCities($_SESSION['adminId']);
        $sql = "select pa.ADMINID, pa.FNAME, max(t.TOTAL) WORKLOAD 
            from 
            (select pa.ADMINID, 0 TOTAL from proptiger_admin pa 
              where pa.ROLE = 'executive' union select pa.ASSIGNED_TO, 
               count(rp.MOVEMENT_HISTORY_ID) TOTAL from project_assignment pa 
               inner join resi_project rp
               on (pa.MOVEMENT_HISTORY_ID = rp.MOVEMENT_HISTORY_ID and pa.updation_cycle_id = rp.updation_cycle_id)
               inner join locality l on rp.locality_id = l.locality_id
               inner join suburb s on l.suburb_id = s.suburb_id
               
               inner join master_project_phases mpp on rp.project_phase_id = mpp.id
               inner join master_project_stages mpstg on rp.project_stage_id = mpstg.id
               where 
                s.city_id in(".implode(',',array_keys($arrAllSurveyLeadCityList)).") and
               ((mpstg.name = '".NewProject_stage."' and mpp.name = '".DcCallCenter_phase."') 
               or (mpstg.name = '".UpdationCycle_stage."' and mpp.name = '".DataCollection_phase."')) and rp.version ='Cms' 
               and pa.STATUS = 'notAttempted' group by pa.ASSIGNED_TO) t 
               inner join proptiger_admin pa on t.ADMINID = pa.ADMINID 
               where pa.DEPARTMENT in ('SURVEY') and pa.adminid not in(".$_SESSION['adminId'].") group by pa.ADMINID order by WORKLOAD;";
        $result = dbQuery($sql);
    return $result;
}

/********functions for field team related*******/
function getallprojectListForField(){
        $sql = "select rp.PROJECT_ID, rp.PROJECT_NAME, ps.PROJECT_STATUS,
        psh.DATE_TIME MOVEMENT_DATE,max(pa.UPDATION_TIME) as LAST_WORKED_AT, pstg.name as PROJECT_STAGE,
        pphs.name as PROJECT_PHASE, 
         mpsp.name as PREV_PROJECT_STAGE, mppp.name PREV_PROJECT_PHASE,pa1.fname,
         rp.MOVEMENT_HISTORY_ID, GROUP_CONCAT(pa1.USERNAME order by pa.ID asc separator '|') ASSIGNED_TO, 
         GROUP_CONCAT(pa1.DEPARTMENT order by pa.ID asc separator '|') 
         DEPARTMENT, GROUP_CONCAT(pa.CREATION_TIME order by pa.ID asc separator '|') ASSIGNED_AT,
         GROUP_CONCAT(pa.STATUS order by pa.ID asc separator '|') STATUS, 
         GROUP_CONCAT(pa.EXECUTIVE_REMARK order by pa.ID asc separator '|') REMARK, 
         if(uc.LABEL is null, 'No Label', uc.LABEL) LABEL from resi_project rp 
         inner join project_status_master ps on rp.project_status_id = ps.id
         inner join project_stage_history psh on rp.MOVEMENT_HISTORY_ID = psh.HISTORY_ID 
         left join project_stage_history pshp on psh.PREV_HISTORY_ID = pshp.HISTORY_ID 
         inner join master_project_stages pstg on rp.project_stage_id = pstg.id
         inner join master_project_phases pphs on rp.project_phase_id = pphs.id
         left join master_project_stages mpsp on pshp.PROJECT_STAGE_ID = mpsp.id
         left join master_project_phases mppp on pshp.PROJECT_PHASE_ID = mppp.id
         inner join resi_project_phase rpphs on rp.project_id = rpphs.project_id 
            and rpphs.PHASE_TYPE = 'Logical' and rpphs.version = 'Cms'
         left join master_booking_statuses mbst on rpphs.booking_status_id = mbst.id
         left join project_assignment pa 
         on (rp.MOVEMENT_HISTORY_ID=pa.MOVEMENT_HISTORY_ID 
         and rp.updation_cycle_id = pa.updation_cycle_id) left join proptiger_admin pa1 on 
         pa.ASSIGNED_TO = pa1.ADMINID left join updation_cycle uc on rp.UPDATION_CYCLE_ID 
         = uc.UPDATION_CYCLE_ID 
         where ((pstg.name = '".NewProject_stage."' and pphs.name = '".DcCallCenter_phase."') or 
            (pstg.name = '".UpdationCycle_stage."' and pphs.name = '".DataCollection_phase."')) and 
         rp.MOVEMENT_HISTORY_ID is not NULL and rp.status in ('ActiveInCms','Active') 
         and rp.version = 'Cms' and pa1.department = 'SURVEY' and role = 'teamleader'";
    $sql = $sql . " group by pa1.adminid order by rp.PROJECT_ID;";
    return  $res = dbQuery($sql); 
}
?>
