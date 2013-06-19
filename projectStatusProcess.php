<?php
function getProjectListForManagers($adminId){
    $sql = "select rp.PROJECT_ID, rp.PROJECT_NAME, rp.BUILDER_NAME, c.LABEL CITY, l.LABEL LOCALITY, max(pa.UPDATION_TIME) as LAST_WORKED_AT, psh.PROJECT_STAGE, psh.PROJECT_PHASE, pshp.PROJECT_STAGE PREV_PROJECT_STAGE, pshp.PROJECT_PHASE PREV_PROJECT_PHASE, rp.MOVEMENT_HISTORY_ID, GROUP_CONCAT(pa.ASSIGNED_TO order by pa.ID asc separator '|') ASSIGNED_TO, GROUP_CONCAT(pa.CREATION_TIME order by pa.ID asc separator '|') ASSIGNED_AT, GROUP_CONCAT(pa.STATUS order by pa.ID asc separator '|') STATUS, GROUP_CONCAT(pa.EXECUTIVE_REMARK order by pa.ID asc separator '|') REMARK from resi_project rp inner join city c on rp.CITY_ID = c.CITY_ID inner join locality l on rp.LOCALITY_ID = l.LOCALITY_ID inner join project_stage_history psh on rp.MOVEMENT_HISTORY_ID = psh.HISTORY_ID left join project_stage_history pshp on psh.PREV_HISTORY_ID = pshp.HISTORY_ID left join project_assignment pa on rp.MOVEMENT_HISTORY_ID=pa.MOVEMENT_HISTORY_ID where rp.PROJECT_STAGE='newProject' and rp.PROJECT_PHASE='dcCallCenter' and rp.MOVEMENT_HISTORY_ID is not NULL group by rp.MOVEMENT_HISTORY_ID order by rp.PROJECT_ID;";
    return $res = dbQuery($sql);
}

function prepareDisplayData($data){ 
    $result = array();
    foreach ($data as $value) {
        $new['PROJECT_ID'] = $value['PROJECT_ID'];
        $new['PROJECT_NAME'] = $value['PROJECT_NAME'];
        $new['BUILDER_NAME'] = $value['BUILDER_NAME'];
        $new['CITY'] = $value['CITY'];
        $new['LOCALITY'] = $value['LOCALITY'];
        $new['PROJECT_PHASE'] = $value['PROJECT_STAGE'];
        $new['LAST_WORKED_AT'] = $value['LAST_WORKED_AT'];
        $assigned_to = explode('|', $value['ASSIGNED_TO']);
        if(empty($assigned_to[0])){
            if($value['PREV_PROJECT_PHASE'] == 'audit1') $new['ASSIGNMENT_TYPE'] = 'Assigned - Reverted';
            else $new['ASSIGNMENT_TYPE'] = 'Unassigned';
        }
        else $new['ASSIGNMENT_TYPE'] = 'Assigned-'.  strval(count($assigned_to));
        $new['ASSIGNED_TO'] = $assigned_to;
        $new['ASSIGNED_AT'] = explode('|', $value['ASSIGNED_AT']);
        $new['STATUS'] = explode('|', $value['STATUS']);
        $new['REMARK'] = explode('|', $value['REMARK']);
        $result[] = $new;
    }
    return $result;
}

$projectList = getProjectListForManagers($_SESSION['adminId']);
$projectList = prepareDisplayData($projectList);

//print_r($projectList);

$smarty->assign("projectList", $projectList);


?>