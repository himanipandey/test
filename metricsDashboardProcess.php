<?php
    $accessIsMetrics = '';
    if( $isMetricsAuth == false )
       $accessIsMetrics = "No Access";
    $smarty->assign("accessIsMetrics",$accessIsMetrics);
    if( isset($_REQUEST['submit']) ) {
        $month = $_REQUEST['month']; 
    }
    else
        $month = 3;    
     $allMetricsData = IsMetrics::getData($month);
     $smarty->assign('month',$month);
     $smarty->assign('allMetricsData',$allMetricsData);

        $reCreateArr = array();
        foreach($allMetricsData as $value) {
            $reCreateArr['new_project'][] = $value->new_project;
            $reCreateArr['percent_project_with_master_plan'][] = 
                    $value->percent_project_with_master_plan;
            $reCreateArr['percent_project_with_location_plan'][] = 
                    $value->percent_project_with_location_plan;
            $reCreateArr['percent_project_with_floor_plan'][] = 
                    $value->percent_project_with_floor_plan;
            $reCreateArr['avg_floor_plan_per_project'][] = 
                    $value->avg_floor_plan_per_project;
            $reCreateArr['percent_project_with_construction_image'][] = 
                    $value->percent_project_with_construction_image;
            $reCreateArr['percent_project_missing_geo'][] = 
                    $value->percent_project_missing_geo;
            $reCreateArr['percent_project_launches_missing_size'][] = 
                    $value->percent_project_launches_missing_size;
            $reCreateArr['percent_project_stale_price'][] = 
                    $value->percent_project_stale_price;
        }
        $smarty->assign('reCreateArr',$reCreateArr);
        //echo "<pre>";
       // print_r($reCreateArr);
?>
