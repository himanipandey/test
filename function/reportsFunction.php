<?php

    function getDailyPerformanceReport($fromdate, $todate, $user, $team) {
    
        $quryand = $and = '';
        $and = ' WHERE ';

        if($fromdate!='')
        {
            $fromdate = $fromdate." 00:00:00";
            $quryand .= $and." A.DATE_TIME>='".$fromdate."'";	
            $and = ' AND ';
        }

        if($todate!='')
        {
            $todate = $todate." 23:59:59";
            $quryand .= $and." A.DATE_TIME<='".$todate."'";	
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
            $fromdate = $fromdate." 00:00:00";
            $todate = $todate." 23:59:59";
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
                   A.PROJECT_PHASE_ID, A.PROJECT_STAGE_ID, ADMIN_ID ORDER BY B.DEPARTMENT, B.FNAME, A.ADMIN_ID";
       $allData = ProjectStageHistory::find_by_sql($qry);

       //**********************************
       $whereQuery = str_replace("DATE_TIME", "login_date",$quryand);
       $queryTimeLog = "SELECT SUM(A.time_spent) as total_time_spent, A.admin_id FROM admin_time_log A INNER JOIN proptiger_admin B ON A.ADMIN_ID=B.ADMINID".$whereQuery." GROUP BY A.ADMIN_ID";
       $mysqlRes = mysql_query($queryTimeLog);
       $timeSpents = array();
       while ($row = mysql_fetch_assoc($mysqlRes)){
           $timeSpents[$row["admin_id"]] = gmdate("H:i:s", $row["total_time_spent"]);
       }
       //**********************************
       
       $finalArr = array();
       $arrAllData = array();
       foreach($allData as $data) {
           $finalArr[$data->department][$data->admin_id][$data->project_stage][$data->project_phase] += $data->cnt;	
           $arrAllData[$data->admin_id] = $data;
       }

        $mergeArr['finalArr']=$finalArr;
        $mergeArr['arrAllData']=$arrAllData;
        $mergeArr['timeSpent']=$timeSpents;
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
