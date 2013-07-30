<?php

    function getDailyPerformanceReport($fromdate, $todate, $user, $team) {
    
        $quryand = $and = '';
        $and = ' WHERE ';

        if($fromdate!='')
        {
            $quryand .= $and." DATE(DATE_TIME)>='".$fromdate."'";	
            $and = ' AND ';
        }

        if($todate!='')
        {
            $quryand .= $and." DATE(DATE_TIME)<='".$todate."'";	
            $and = ' AND ';
        }

        if($user!='')
        {
            $quryand .= $and." ADMIN_ID='".$user."'";	
            $and = ' AND ';
        }

        if($team!='')
        {
            $quryand .= $and." DEPARTMENT='".$team."'";	
            $and = ' AND ';
        }

        if($todate == '' && $fromdate == '')
        {
            $quryand .= $and." DATE_TIME BETWEEN '".$fromdate."' AND '".$todate."'";
            $and = ' AND ';	
        }

        #---------------------------------------
       $qry = '';
       $qry = "SELECT 
                    COUNT(DISTINCT(PROJECT_ID)) cnt, PROJECT_PHASE, PROJECT_STAGE, ADMIN_ID,FNAME, DATE_TIME, DEPARTMENT
               FROM 
                    project_stage_history A LEFT JOIN proptiger_admin B ON A.ADMIN_ID=B.ADMINID 
               ".$quryand."
               GROUP BY 
                   PROJECT_PHASE, PROJECT_STAGE, ADMIN_ID ORDER BY DEPARTMENT, FNAME, ADMIN_ID";
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
