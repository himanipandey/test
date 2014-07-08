<?php

/*
 *code for display all construction assignment history updation cycle id wise
 created by Vimlesh Rajput on 22nd may 2014*/

   $updation = UpdationCycle::find('all',array('conditions'=>array('cycle_type'=>'construction')));
   $smarty->assign("updationCycle",$updation);
   if(isset($_REQUEST['submit'])) {
       $projectId = $_REQUEST['projectId'];
       $updationCycleId = $_REQUEST['updationCycleId'];
       $smarty->assign("projectId",$projectId);
       $smarty->assign("updationCycleId",$updationCycleId);
       $errorMsg = '';
       if(trim($projectId) == '' && $updationCycleId == ''){
           $errorMsg = 'Please select atleast one field';    
       }
//echo "<pre>";print_r($_REQUEST);
       if($errorMsg == '') {
           $qry = 'select pas.*,uc.*,rp.project_name,pa.fname from process_assignment_system pas 
               join updation_cycle uc ON( pas.updation_cycle_id = uc.updation_cycle_id )
               join proptiger_admin pa on pas.assigned_to = pa.adminid
               join resi_project rp ON( pas.project_id = rp.project_id )';
            if($projectId != '' && $updationCycleId != '')
                $searchResult = $qry." where pas.project_id in( $projectId) and rp.version = 'Cms' and pas.updation_cycle_id = $updationCycleId";
            elseif($projectId != '' && $updationCycleId == '')
                 $searchResult = $qry." where pas.project_id  in( $projectId)  and rp.version = 'Cms'";
            elseif($projectId == '' && $updationCycleId != '')
                 $searchResult = $qry." where pas.updation_cycle_id = $updationCycleId and rp.version = 'Cms'";
            $smarty->assign("searchResult",$searchResult);
           // echo $searchResult;die;
           $res = ProcessAssignmentSystem::find_by_sql($searchResult);
         $smarty->assign("searchResult",$res);
       }
       $smarty->assign("errorMsg",$errorMsg);
   }
?>
