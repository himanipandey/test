<?php

// Model integration for bank list
class CommentsHistory extends ActiveRecord\Model
{
    static $table_name = 'comments_history';
    static function insertUpdateComments( $projectId, $arrCommentTypeValue, $updationCycleId, $projectUpdationCycleId = null ) {
        $updationCycleId = $updationCycleId."_".date("M-y");     
        foreach( $arrCommentTypeValue as $key=>$value ) {

            $conditions = array("project_id = ? AND comment_type = ? AND updation_cycle = ? 
                           AND status=? AND UPDATION_CYCLE_ID = ?",
                $projectId, $key, $updationCycleId,"New",$projectUpdationCycleId);

            $getComments = CommentsHistory::find('all', array("conditions" => $conditions));   

            if( count($getComments) == 0 ) {
                $comment = new CommentsHistory();

                $comment->updation_cycle = $updationCycleId;
                $comment->project_id = $projectId;
                $comment->comment_type = $key;
                $comment->updation_cycle_id = $projectUpdationCycleId;
            }
            else {
                $comment = CommentsHistory::find($getComments[0]->comment_id);               
            }

            $comment->comment_text = $value;
            $comment->user_id = $_SESSION['adminId'];
            $comment->save();    
        }
    }
    
    function getCommentHistoryByProjectIdCycleId($projectId, $cycleId) {
        
        $cycleId = date("M-y");
        $conditions = array("project_id = ? AND updation_cycle like ? ", $projectId, "%".$cycleId."%");
        $join = 'LEFT JOIN proptiger_admin a ON(comments_history.user_id = a.adminid)';

        $getComments = CommentsHistory::find('all',array('joins' => $join, 
                "conditions" => $conditions, "select" => "a.*, comments_history.*")); 
        $arrProjectComment = array();
        $commentTypeMap = array("Project" => 'projectRemark',
                   "Calling" => 'callingRemark', 
                   'Audit' => 'auditRemark',
                   'Audit2' => 'audit2Remark',
                   'SecondaryAudit' => 'secondaryAuditRemark',
                   'Secondary' => 'secondaryRemark',
                   'FieldSurvey' => 'fieldSurveyRemark'
                    );
        
        foreach($getComments as $value) {
            $arrProjectComment[$commentTypeMap[$value->comment_type]] = $value;
        }
        return $arrProjectComment;
    }
    
    function getOldCommentHistoryByProjectId($projectId) {
        
       $oldMonthCycle = date('M-y', strtotime("last month"));
       $qry = "SELECT ch.*,pa.fname FROM 
            comments_history as ch 
            left join 
            proptiger_admin pa
            on 
             ch.user_id = pa.adminid
            where 
                project_id = $projectId 
               and 
                updation_cycle like 
                    '%$oldMonthCycle'";

        $getOldComments = CommentsHistory::find_by_sql($qry); 
        $arrProjectOldComment = array();
        $commentTypeMap = array("Project" => 'projectRemark',
                   "Calling" => 'callingRemark', 
                   'Audit' => 'auditRemark',
                   'Audit2' => 'audit2Remark',
                   'SecondaryAudit' => 'secondaryAuditRemark',
                   'Secondary' => 'secondaryRemark',
                   'FieldSurvey' => 'fieldSurveyRemark'
                    );
        
        foreach($getOldComments as $value) {
            $arrProjectOldComment[$commentTypeMap[$value->comment_type]] = $value;
        }
        if(count($arrProjectOldComment) == 0) {
            
            $oldMonthCycle = date('M-y', strtotime("-2 month"));
            $qry = "SELECT ch.*,pa.fname FROM 
            comments_history as ch 
            left join 
            proptiger_admin pa
            on 
             ch.user_id = pa.adminid
            where 
                project_id = $projectId 
               and 
                updation_cycle like 
                    '%$oldMonthCycle'";

            $getOldComments = CommentsHistory::find_by_sql($qry); 
            $arrProjectOldComment = array();
            $commentTypeMap = array("Project" => 'projectRemark',
                       "Calling" => 'callingRemark', 
                       'Audit' => 'auditRemark',
                       'SecondaryAudit' => 'secondaryAuditRemark',
                       'Secondary' => 'secondaryRemark',
                       'FieldSurvey' => 'fieldSurveyRemark'
                        );

            foreach($getOldComments as $value) {
                $arrProjectOldComment[$commentTypeMap[$value->comment_type]] = $value;
            }
            return $arrProjectOldComment;
        }
        return $arrProjectOldComment;
    }
    
    function getAllCommentsByProjectId($projectId, $commentType, $updationCycleId, $actualUpdationCycle) {
              //echo $projectId."==". $commentType."==". $updationCycleId;die;
        if($actualUpdationCycle != '') {
            $conditions = array("project_id = $projectId  AND updation_cycle_id = ".$actualUpdationCycle);
        }
        else{
            if( $updationCycleId != '' && $commentType != '') {                
                 $conditions = array("project_id = ? AND comment_type = ? AND updation_cycle = ? ", $projectId, $commentType, $updationCycleId);
            }
            else if( $updationCycleId != '' && $commentType == '' )
                $conditions = array("project_id = ? AND updation_cycle = ? ", $projectId, $updationCycleId);
            else if( $updationCycleId == '' && $commentType != '' )
                 $conditions = array("project_id = ? AND comment_type = ? ", $projectId, $commentType);
            else
                 $conditions = array("project_id = ? ", $projectId);
        }
        $join = 'LEFT JOIN proptiger_admin a ON(comments_history.user_id = a.adminid)';

        $getAllComments = CommentsHistory::find('all',array('joins' => $join, 
                "conditions" => $conditions, "select" => "a.fname, comments_history.*")); 

        $arrProjectComment = array();
        foreach($getAllComments as $value) {
            $arrProjectComment[] = $value;
        }
        return $arrProjectComment;
    }
    
    function getAllCycleByProjectId($projectId) {
        $join = 'LEFT JOIN updation_cycle uc ON(comments_history.updation_cycle_id = uc.updation_cycle_id)';
        $getAllCycle = CommentsHistory::find('all',array('joins' => $join,
            'conditions'=>array('project_id = ?',$projectId), "select" => "uc.cycle_type,uc.label, comments_history.*"));
        $arrAllCycleByProjectId = array();
        foreach($getAllCycle as $value) {
            if( !array_key_exists($value->updation_cycle,$arrAllCycleByProjectId['updationCycleMonth']) ) {
               $str = str_replace("_", " ", $value->updation_cycle);
               $arrAllCycleByProjectId['updationCycleMonth'][$value->updation_cycle] =  $str;
            }
            if( !array_key_exists($value->updation_cycle_id,$arrAllCycleByProjectId['updationCycleActual']) ) {
               //if(!in_array($exp,$arrAllCycleByProjectId['updationCycleActual']))
                if($value->updation_cycle_id != '')
                 $arrAllCycleByProjectId['updationCycleActual'][$value->updation_cycle_id] =  ucfirst($value->cycle_type)." ".$value->label;
            }
            
        }
        return $arrAllCycleByProjectId;
    }
    
}
