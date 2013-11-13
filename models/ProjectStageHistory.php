<?php

// Model integration for bank list
class ProjectStageHistory extends ActiveRecord\Model
{
    static $table_name = 'project_stage_history';
    static $audit_stages = array(phaseId_4, phaseId_5);

    // Function gives last audit movement date by call center team,
    // excluding movement done by team leader
    static function get_last_audit_date($projectIds){
        $project_last_audit_date = array();

        if(count($projectIds) > 0){
            $join = "left join ".ProptigerAdmin::$table_name." pa on pa.adminid = ".self::$table_name.".admin_id";
            $projects = self::all(array('joins' => $join,'conditions' => array('project_id in (?) and project_phase_id in
            (?) and pa.department = ? and pa.role != ?', $projectIds, self::$audit_stages, ProptigerAdmin::$ADMIN_TYPES["call_center"], ProptigerAdmin::$TEAM_LEADER),
                "select" => "project_id, max(DATE_TIME) AUDIT_DATE", "group" =>"project_id"));
            foreach($projects as $project){
                $project_id = $project->project_id;
                $project_last_audit_date[$project_id] = $project->audit_date;
            }

            foreach($projectIds as $project_id){
                if (!array_key_exists($project_id, $project_last_audit_date))
                    $project_last_audit_date[$project_id] = NULL;
            }
        }
        return $project_last_audit_date;
    }
}