<?php
    $projectId = $_REQUEST['projectId'];
    $ProjectDetail = ProjectDetail($projectId);
    $smarty->assign("ProjectDetail",$ProjectDetail);
    $errorMsg = array();
    if( isset($_REQUEST['submit']) ) {

        $commentType = $_REQUEST['commentType'];
        $commentCycle = $_REQUEST['commentCycle'];
        $smarty->assign("commentType",$commentType);
        $smarty->assign("commentCycle",$commentCycle);
        if( $commentType == '' && $commentCycle == '') {
            $errorMsg['commentType'] = "<font color = 'red'>Please select atleast one value</font>";
        }
        if( count($errorMsg) == 0 ) {
            
            if( $commentType == 'all' )
                $commentType = '';
            if( $commentCycle == 'all' )
                $commentCycle = '';
            $commentList = CommentsHistory::getAllCommentsByProjectId($projectId, $commentType, $commentCycle);
            if( count($commentList) == 0 )
                $errorMsg['noRecord'] = "<font color = 'red'>No Record Found</font>";
            $smarty->assign("commentList", $commentList);
        }
        
    }
    $smarty->assign("errorMsg",$errorMsg);
    $allCycle = CommentsHistory::getAllCycleByProjectId($projectId);
    $smarty->assign("allCycle",$allCycle);
    
     $commentTypeMap = array("Project" => 'Project Remark',
                    "Calling" => 'Calling Remark', 
                    'Audit' => 'Audit Remark',
                    'Secondary' => 'Secondary Remark',
                    'FieldSurvey' => 'Field Survey Remark'
                     );
     $smarty->assign("commentTypeMap",$commentTypeMap);

?>
