<?php

// Model integration for bank list
class ProjectStageHistory extends ActiveRecord\Model
{
    static $table_name = 'project_stage_history';
    static $audit_stages = array(phaseId_5);

    // Function gives last audit movement date by call center team,
    // excluding movement done by team leader
    static function get_last_audit_date($projectIds){
        $project_last_audit_date = array();
        if(count($projectIds) > 0){
            $qry = "select project_id,max(date_time) as audit_date from project_stage_history
            where project_id in(".implode(",",$projectIds).") and project_phase_id = 4
            group by project_id";         
            $res = mysql_query($qry) or die(mysql_error());
            while($data = mysql_fetch_assoc($res)){
                $project_last_audit_date[$data['project_id']] = $data['audit_date'];
            }
            foreach($projectIds as $project_id){
                if (!array_key_exists($project_id, $project_last_audit_date))
                    $project_last_audit_date[$project_id] = NULL;
            }
        }
        return $project_last_audit_date;
    }
}
