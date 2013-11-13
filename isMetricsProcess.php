<?php

    $cityList = "1,2,5,6,8,11,12,16,18,20,21";
    $joinLocSub = 'INNER JOIN suburb a ON locality.suburb_id = a.suburb_id
                   INNER JOIN city c on a.city_id = c.city_id';
    $locIds = Locality::find('all',array('joins' => $joinLocSub,
        "select" => "locality.locality_id"));
    $arrIds = array();
    foreach($locIds as $ids) {
        $arrIds[] = $ids->locality_id;
    }
    $LocIdList = implode(',', $arrIds);
    $active = "resi_project.status = 'Active' OR resi_project.status = 'ActiveInCms'";
    $activeProject = ResiProject::virtual_find('all',array('conditions'=>array("($active) AND residential_flag = 'Residential' AND locality_id in ($LocIdList)"),
        "select" => "count(*) as PROJECT_COUNT"));
    
    /******code for get all projects which was added in last n number of days***/
    $dt = mktime(0,0,0,date('m')-1,date('d'),date('Y'));
    $newDate = date('Y-m-d',$dt);
    $joinProject = 'LEFT JOIN _t_resi_project a ON(resi_project.project_id = a.project_id)';
    $conditions = array("a._t_operation = 'I' AND a._t_transaction_date >= '$newDate' 
        AND ($active) AND resi_project.residential_flag = 'Residential'");
    
    $newAddedProject = ResiProject::virtual_find('all',array('joins' => $joinProject, 'conditions'=>$conditions, "select" => "count(*) as NEW_PROJECT_COUNT"));
   
   /******code for get all project which have master plans***/
    $arrPlanType = "'Master Plan', 'Site Plan', 'Layout Plan'";
    $join = 'LEFT JOIN project_plan_images a ON( resi_project.project_id = a.project_id )';
    $conditionsMaster = array("($active) AND resi_project.residential_flag = 'Residential' AND resi_project.locality_id in ($LocIdList) 
        AND a.plan_type in ($arrPlanType) ");
    $masterPlan = ResiProject::virtual_find('all',array('joins' => $join, 'conditions'=>$conditionsMaster, "select" => "count(*) as MASTER_PLAN_COUNT"));
    
    $percentProjectWithMasterPlan = ($masterPlan[0]->master_plan_count/$activeProject[0]->project_count)*100;
    
    /******code for get all project which have location maps***/
    $conditionsLocation = array("($active) AND resi_project.residential_flag = 'Residential' AND resi_project.locality_id in ($LocIdList) AND a.plan_type = 'Location Plan'");
    $locationPlan = ResiProject::virtual_find('all',array('joins' => $join, 'conditions'=>$conditionsLocation, "select" => "count(*) as LOCATION_PLAN_COUNT"));
    
    $percentProjectWithLocationPlan = ($locationPlan[0]->location_plan_count/$activeProject[0]->project_count)*100;
    
    /*******count of project floor plan**************/
    $conditionsFloor = array("($active) AND resi_project.residential_flag = 'Residential' AND resi_project.locality_id in ($LocIdList)");
     $joinFloor = 'LEFT JOIN resi_project_options a ON( resi_floor_plans.option_id = a.options_id ) 
                   LEFT JOIN resi_project ON(a.project_id = resi_project.project_id)';
    $floorPlans = ResiFloorPlans::find('all',array('joins' => $joinFloor, 'conditions'=>$conditionsFloor,"select" => "count(*) as FLOOR_PLAN_COUNT"));
    
    $joinProjectWithFloor = 'INNER JOIN resi_project_options a ON( resi_project.project_id = a.project_id ) 
                   INNER JOIN resi_floor_plans b ON(a.options_id = b.floor_plan_id)';
    $conditionsProjectWithFloor = array("($active) AND resi_project.residential_flag = 'Residential' AND resi_project.locality_id in ($LocIdList)");
    
    $projectWithFoorPlans = ResiProject::virtual_find('all',array('joins' => $joinProjectWithFloor, 'conditions'=>$conditionsProjectWithFloor,
        'group' => 'resi_project.project_id',"select" => "count(*) as PROJECT_COUNT_WITH_FLOOR"));
    $percntProjectWithFloorPlans = (count($projectWithFoorPlans)/$activeProject[0]->project_count)*100;
    $avrgNumOfFloorPlanPerProj = $floorPlans[0]->floor_plan_count/$activeProject[0]->project_count;
   
    /********Percentage of under const project with const images**************/
    $conditionsConstImg = array("($active) AND resi_project.residential_flag = 'Residential' 
                            AND resi_project.locality_id in ($LocIdList) AND a.plan_type = 'Construction Status'");
    $projectCountOfConstImg = ResiProject::virtual_find('all',array('joins' => $join, 'conditions'=>$conditionsConstImg,
        "select" => "count(DISTINCT resi_project.project_id) as PROJECT_COUNT_OF_CONSTRUCTION_IMAGES"));
    
    $activeProjectWithUnderConst = ResiProject::virtual_find('all',array('conditions'=>array("(active = '1' OR active = '3') AND residential_flag = 'Residential' AND locality_id in ($LocIdList) AND  project_status = 'Under Construction'" ),
        "select" => "count(*) as PROJECT_COUNT"));
    $percntProjWithConstImg = ($projectCountOfConstImg[0]->project_count_of_construction_images/$activeProjectWithUnderConst[0]->project_count)*100;
   
   /*Prect of project with launch date > oct 2012 and project_status = ('launch' or uncer_cons)*/
    $joinOptions = "INNER JOIN resi_project_options as b on ( resi_project.project_id = b.project_id )";
   $projectWithLaunchGrtrOct2012 = ResiProject::virtual_find('all',array('joins' => $joinOptions, 
        'conditions'=>array("($active) 
            AND resi_project.residential_flag = 'Residential' AND resi_project.locality_id in ($LocIdList) 
                AND  resi_project.project_status in ('Launch', 'Under Construction') 
                AND resi_project.launch_date >= 2012-10-01 AND b.size = 0" ), "select" => "count(distinct b.project_id) as PROJECT_COUNT"));
    $activeProjectWithUnderConstNLaunch = ResiProject::virtual_find('all',array('conditions'=>array("(active = '1' OR active = '3') AND residential_flag = 'Residential' AND locality_id in ($LocIdList) AND  project_status in( 'Under Construction','Launch' )" ),
        "select" => "count(*) as PROJECT_COUNT"));
   $percntProjectWithSize = (100-($projectWithLaunchGrtrOct2012[0]->project_count/$activeProjectWithUnderConstNLaunch[0]->project_count)*100);
   
   /* % of project with lat long */
   $latLongList = '0,1,2,3,4,5,6,7,8,9';    
   $latLongWithProject = ResiProject::virtual_find('all', array('conditions' => array("(latitude in($latLongList) 
                    OR longitude in($latLongList)) and ($active) AND residential_flag = 'Residential' 
                            AND locality_id in ($LocIdList)"), "select" => "count(*) as PROJECT_COUNT"));
   $percntProjWithGeo = (100-($latLongWithProject[0]->project_count/$activeProject[0]->project_count)*100);
   /*Project_availability = yes and CITY = (Bangalore, Chennai, Noida,
    Gurgaon, Ghaziabad, Faridabad, Delhi, Mumbai, Pune, Kolkatta, Hyderabad,
    Ahmedabad) % of projects with Last Price updation older than 2 months**/
   
   $dt2monthBefore = mktime(0,0,0,date('m')-2,date('d'),date('Y'));
   $twoMonthBeforeDate = date('Y-m-d',$dt2monthBefore); 
   $query = "select count(*) as project_count from (SELECT a.project_id as PROJECT_COUNT FROM `resi_project` 
                LEFT JOIN resi_project_options a ON( resi_project.project_id = a.project_id ) 
                LEFT JOIN resi_project_options_prices b ON(a.options_id = b.option_id)
               WHERE ($active) 
                 AND resi_project.residential_flag = 'Residential' 
                 AND resi_project.locality_id in ($LocIdList) 
                 AND resi_project.available_no_flats != 0 group by a.project_id 
              having max(b.effective_date) < '$twoMonthBeforeDate') table_alias";
    $availableProjects = ResiProject::find_by_sql($query);
    
    $availableProjectpriceUpdated2monthOld = (100-($availableProjects[0]->project_count/$activeProject[0]->project_count)*100);
    
    
   /********code for insert in db*****/
   $isMetricsInsert = new IsMetrics();
   $isMetricsInsert->new_project = $newAddedProject[0]->new_project_count;
   $isMetricsInsert->percent_project_with_master_plan = $percentProjectWithMasterPlan;
   $isMetricsInsert->percent_project_with_location_plan = $percentProjectWithLocationPlan;
   $isMetricsInsert->percent_project_with_floor_plan = $percntProjectWithFloorPlans;
   $isMetricsInsert->avg_floor_plan_per_project = $avrgNumOfFloorPlanPerProj;
   $isMetricsInsert->percent_project_with_construction_image = $percntProjWithConstImg;
   $isMetricsInsert->percent_project_missing_geo = $percntProjWithGeo;
   $isMetricsInsert->percent_project_launches_missing_size = $percntProjectWithSize;
   $isMetricsInsert->month = date("Y-m")."-01";
   $isMetricsInsert->percent_project_stale_price = $availableProjectpriceUpdated2monthOld;
   $isMetricsInsert->save();
   
   /*******code for dump project ids created in old month*******/
   require_once 'log4php/Logger.php';
   Logger::configure( dirname(__FILE__) . '/log4php.xml');
   $logger = Logger::getLogger("main");
   $logger->info("Project ids created in old month. Only which have active flag = 1 or 3 
                  and residential_flag = 'Residential' and city =(CITY = (Bangalore, Chennai, Noida,
    Gurgaon, Ghaziabad, Faridabad, Delhi, Mumbai, Pune, Kolkatta, Hyderabad, Ahmedabad)");
   
    $newProjectAddedLog = ResiProject::virtual_find('all',array('conditions'=>array("($active) AND residential_flag = 'Residential' AND locality_id in ($LocIdList)"),
        "select" => "project_id"));
   $projectIdList = '';
    foreach($newProjectAddedLog as $value) {
         $projectIdList .= $value->project_id.",";
    }
    $logger->info($projectIdList);
   
?>
