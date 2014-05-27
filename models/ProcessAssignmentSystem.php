<?php

// Model project construction assignment
class ProcessAssignmentSystem extends ActiveRecord\Model
{
    static $table_name = 'process_assignment_system';
    static function insertProcessAssignmentSystem( $projectId, $updationCycleId, $assignType ) {			
            $conditions = array("project_id = $projectId");
            $getAssignedProject = ProcessAssignmentSystem::find('all',array('order' => 'id desc',"conditions" => $conditions)); 
          //echo trim($projectId)."<==>".trim($getAssignedProject[0]->project_id)."<==>".trim($updationCycleId)."<==>".trim($getAssignedProject[0]->updation_cycle_id);die;
            if(trim($projectId) == trim($getAssignedProject[0]->project_id) && trim($updationCycleId) == trim($getAssignedProject[0]->updation_cycle_id)){
                //die("here");
            }else{
                if( count($getAssignedProject) == 0 ) {
                    $assignProject = new ProcessAssignmentSystem();                
                    $assignProject->project_id = $projectId;
                }
                else {
                    $assignProject = new ProcessAssignmentSystem();
                    $assignProject->project_id = $projectId;
                    $assignProject->assigned_to = '';
                    $assignProject->status = '';
                    $assignProject->executive_remark = '';
                    $assignProject->source = '';
                }
                date_default_timezone_set("Asia/Kolkata");
                $assignProject->assignment_type = $assignType;
                $assignProject->updation_cycle_id = $updationCycleId;
                $assignProject->assigned_by = $_SESSION['adminId'];
                $assignProject->creation_time = date('Y-m-d H:i:s');
                $assignProject->save();
            }
    }
   
}

?>
