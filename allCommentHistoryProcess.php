<?php
    $projectId = $_REQUEST['projectId'];
    $ProjectDetail = ProjectDetail($projectId);
    $smarty->assign("ProjectDetail",$ProjectDetail);
    $errorMsg = array();
    if( isset($_REQUEST['submit']) ) {
        $commentType = $_REQUEST['commentType'];
        $commentCycle = $_REQUEST['commentCycle'];
        $commentCycleActual = $_REQUEST['commentCycleActual'];
        $smarty->assign("commentType",$commentType);
        $smarty->assign("commentCycle",$commentCycle);
        $smarty->assign("commentCycleActual",$commentCycleActual);
        if( $commentType == '' && $commentCycle == '' && $commentCycleActual == '') {
            $errorMsg['commentType'] = "<font color = 'red'>Please select atleast one value</font>";
        }
        if( count($errorMsg) == 0 ) {
            
            if( $commentType == 'all' )
                $commentType = '';
            if( $commentCycle == 'all' )
                $commentCycle = '';
            if( $commentCycleActual == 'all' )
                $commentCycleActual = 'not_null';
            
            $commentList = CommentsHistory::getAllCommentsByProjectId($projectId, $commentType, $commentCycle, $commentCycleActual);
            if( count($commentList) == 0 )
                $errorMsg['noRecord'] = "<font color = 'red'>No Record Found</font>";
            $smarty->assign("commentList", $commentList);
        }
        
    }
    $smarty->assign("errorMsg",$errorMsg);
    $allCycle = CommentsHistory::getAllCycleByProjectId($projectId);
    $smarty->assign("allCycle",$allCycle['updationCycleMonth']);
    $smarty->assign("allCycleActual",$allCycle['updationCycleActual']);
     $commentTypeMap = array("Project" => 'Project Remark',
                    "Calling" => 'Calling Remark', 
                    'Audit' => 'Audit Remark',
                    'Secondary' => 'Secondary Remark',
                    'FieldSurvey' => 'Field Survey Remark',
                    'SecondaryAudit' => 'Secondary Audit Remark',
                    'Audit2' => 'Audit2 Remark'
                     );
     $smarty->assign("commentTypeMap",$commentTypeMap);

?>
