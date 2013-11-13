<?php

    function getDailyPerformanceReport($fromdate, $todate, $user, $team) {
    
        $quryand = $and = '';
        $and = ' WHERE ';

        if($fromdate!='')
        {
            $quryand .= $and." DATE(A.DATE_TIME)>='".$fromdate."'";	
            $and = ' AND ';
        }

        if($todate!='')
        {
            $quryand .= $and." DATE(A.DATE_TIME)<='".$todate."'";	
            $and = ' AND ';
        }

        if($user!='')
        {
            $quryand .= $and." A.ADMIN_ID='".$user."'";	
            $and = ' AND ';
        }

        if($team!='')
        {
            $quryand .= $and." B.DEPARTMENT='".$team."'";	
            $and = ' AND ';
        }

        if($todate == '' && $fromdate == '')
        {
            $quryand .= $and." A.DATE_TIME BETWEEN '".$fromdate."' AND '".$todate."'";
            $and = ' AND ';	
        }

        #---------------------------------------
       $qry = '';
       $qry = "SELECT 
                    COUNT(DISTINCT(A.PROJECT_ID)) cnt, ph.name as PROJECT_PHASE, st.name as PROJECT_STAGE, A.ADMIN_ID,B.FNAME, A.DATE_TIME, B.DEPARTMENT
               FROM 
                    project_stage_history A LEFT JOIN proptiger_admin B ON A.ADMIN_ID=B.ADMINID 
                    inner join master_project_stages st on A.PROJECT_STAGE_ID = st.id
                    inner join master_project_phases ph on A.PROJECT_PHASE_ID = ph.id
               ".$quryand."
               GROUP BY 
                   A.PROJECT_PHASE_ID, A.PROJECT_STAGE_ID ORDER BY B.DEPARTMENT, B.FNAME, A.ADMIN_ID";
       $allData = ProjectStageHistory::find_by_sql($qry);

       $finalArr = array();
       $arrAllData = array();
       foreach($allData as $data) {
           $finalArr[$data->department][$data->admin_id][$data->project_stage][$data->project_phase] += $data->cnt;	
           $arrAllData[$data->admin_id] = $data;
       }

        $mergeArr['finalArr']=$finalArr;
        $mergeArr['arrAllData']=$arrAllData;
        return $mergeArr;
    }
    
    function getAdminDetail($team) {
       
       $adminDetailArr = array();
       $teamArr = array();
        $and  = '';
       if( $team != '' )
            $and  = " and department = '$team'";
       $adminArr = ProptigerAdmin::find('all', array('conditions' => array("status = 'Y' $and "),'order' => 'FNAME ASC'));
       foreach ($adminArr as $obj) {
           if($obj->fname != '')
           {
               $adminDetailArr[$obj->adminid] = $obj->fname;
           }
           if($obj->department != 'ADMINISTRATOR')
              $teamArr[] = $obj->department;
       }
       $arrAdminDetail['adminDetailArr'] = $adminDetailArr;
       $arrAdminDetail['teamArr'] = $teamArr;
       
       return $arrAdminDetail;
    }
?>
