<?php

    include("smartyConfig.php");
    include("appWideConfig.php");
    include("dbConfig.php");
    include("modelsConfig.php");
    include("includes/configs/configs.php");
    $accessBulkProject = '';
    if( $bulkProjUpdateAuth == false )
       $accessBulkProject = "No Access";
    $smarty->assign("accessBulkProject",$accessBulkProject);
    date_default_timezone_set('Asia/Kolkata');
    include("builder_function.php"); 
    AdminAuthentication();
    $dept = $_SESSION['DEPARTMENT'];

    $smarty->assign("_POST", $_POST);

    if(!isset($_POST['projectId']))
            $_POST['projectId'] = '';

    if(!isset($_POST['mode']))
            $_POST['mode'] = '';

    if(!isset($_POST['search']))
            $_POST['search'] = '';

    if(!isset($_REQUEST['search']))
            $_REQUEST['search'] = '';

    if(!isset($_POST['city']))
            $_POST['city'] = '';

    if(!isset($_REQUEST['locality']))
            $_REQUEST['locality'] = '';	

    if(!isset($_REQUEST['Active']))
            $_REQUEST['Active'] = '';

    if(!isset($_REQUEST['Status']))
            $_REQUEST['Status'] = '';

    $errorMsg = '';
    
    if(count($_REQUEST['Active'])>0)
        $ActiveValue  = implode("','", $_REQUEST['Active']);
    else
        $ActiveValue = '';
    
    if(count($_REQUEST['Status'])>0)
        $StatusValue  = implode("','", $_REQUEST['Status']);
    else
        $StatusValue = '';

    if($StatusValue!="") $StatusValue = "'".$StatusValue."'";

    $projectDataArr = array();
    $NumRows =  $city = $builder = $project_name = '';

    $citylist = City::CityArr();
    $builderList = ResiBuilder::BuilderEntityArr();
    $projectStatus = ResiProject::projectStatusMaster();
    $UpdationArr = UpdationCycle::updationCycleTable();
    $arrCycle = array();
    foreach($UpdationArr as $val) {
        if($val->cycle_type == 'construction')
            $arrCycle[$val->updation_cycle_id] = $val;
    }
    $updateRemark= $_REQUEST['updateRemark'];
    $transfer = $_REQUEST['transfer'];
    $search = $_REQUEST['search'];
    $city = $_REQUEST['city'];
    $locality = $_REQUEST['locality'];
    $builder = $_REQUEST['builder'];
    $updationCycle = $_REQUEST['updationCycle'];
    $Status = $_REQUEST['Status'];
    $Active = $_REQUEST['Active'];
    $selectdata = $_POST['selectdata'];

    $smarty->assign("projectStatus",$projectStatus);
    $smarty->assign("citylist", $citylist);
    $smarty->assign("builderList", $builderList);	
    $smarty->assign("UpdationCycleCurrent", $arrCycle[max(array_keys($arrCycle))]);
    $smarty->assign("UpdationArr", $arrCycle);
    $smarty->assign("search", $search);
    $smarty->assign("Status", $_REQUEST['Status']);
    $smarty->assign("city", $city);
    $smarty->assign("builder", $builder);
    $smarty->assign("project_name", $project_name);
    $smarty->assign("projectId", $_POST['projectId']);
    $smarty->assign("selectdata", $selectdata);
    $smarty->assign("Active", $_REQUEST['Active']);
    $smarty->assign("assignStatus", $_REQUEST['assignStatus']);
    $smarty->assign("assignRemark", $_REQUEST['assignRemark']);
    $smarty->assign("assignCycle", $_REQUEST['assignCycle']);
    
    $QueryMember2 = "Select COUNT(distinct(p.PROJECT_ID)) CNT
                FROM ".RESI_PROJECT." p 
                left join locality on p.locality_id = locality.locality_id
                left join city on locality.city_id = city.city_id
                left join process_assignment_system pas on p.project_id = pas.project_id
                left join updation_cycle uc on pas.updation_cycle_id = uc.updation_cycle_id";
    if($search != '' OR $transfer != '' OR $_POST['projectId'] != '')
    {
	$project_name= $_REQUEST['project_name'];
        $smarty->assign("locality", $locality);
        $smarty->assign("updationCycle", $updationCycle);
        
        if($city != '')
        { 
            $getLocality = Array();
            if($city == 'othercities'){
                foreach($arrOtherCities as $key => $value){
                    $cityLocality = Locality::getLocalityByCity($key);
                    if(!empty($cityLocality))
                        $getLocality = array_merge($getLocality,$cityLocality);
                }
            }else
            $getLocality = Locality::getLocalityByCity($city);
            $smarty->assign("getLocality", $getLocality);
        }       
   
        $and = " WHERE ";
        
        //echo "<pre>";print_r($_REQUEST);
        if($_POST['projectId'] == '')
        {				
             if($_REQUEST['city'] != '')
             {
                $city = '';
                if($_REQUEST['city'] == 'othercities'){
                       $OtherCitiesKeys = array_keys($arrOtherCities);
                       $city = implode(",",$OtherCitiesKeys);
                }else{
                        $city = $_REQUEST['city'];
               }
             $QueryMember .= $and." city.city_id in ($city)";
             $and  = ' AND ';
             }
             if($_REQUEST['project_name'] != '')
             {
                 $QueryMember .= $and." PROJECT_NAME LIKE '%".$_REQUEST['project_name']."%'";
                 $and  = ' AND ';
             }
             if($StatusValue != '')
             {
                 $QueryMember .=  $and." PROJECT_STATUS_ID IN(".$StatusValue.")";
                 $and  = ' AND ';
             }

             if($_REQUEST['locality'] != '')
             {
                 $QueryMember .= $and." locality.LOCALITY_ID = '".$_REQUEST['locality']."'";
                 $and  = ' AND ';
             }
             
             if($_REQUEST['assignStatus'] != '')
             {
                 $QueryMember .= $and." pas.STATUS = '".$_REQUEST['assignStatus']."'";
                 $and  = ' AND ';
             }
             if($_REQUEST['assignRemark'] != '')
             {
                 $QueryMember .= $and." pas.EXECUTIVE_REMARK = '".$_REQUEST['assignRemark']."'";
                 if(trim($pasAnd) == ''){
                 }
                 $and  = ' AND ';
             }
             if($_REQUEST['assignCycle'] != '')
             {
                 $QueryMember .= $and." uc.updation_cycle_id = '".$_REQUEST['assignCycle']."'";
                 $and  = ' AND ';
             }
             if($_REQUEST['builder'] != '')
             {
                 $QueryMember .= $and." BUILDER_ID = '".$_REQUEST['builder']."'";
                 $and  = ' AND ';
             }
                 $QueryMember .= $and ." p.version = 'Cms' and p.status in('Active','ActiveInCms')";
        }
        else
        {
                $QueryMember .= $and. " p.PROJECT_ID IN (".$_REQUEST['projectId'].") AND p.version = 'Cms'
                    and p.status in('Active','ActiveInCms')";

        }
        
       $QueryMember2	=  $QueryMember2.$pasAnd. $QueryMember."  ORDER BY p.PROJECT_ID";
    }
    //echo "<pre>";print_r($_REQUEST);die;
   $constCycle = explode("|",$_REQUEST['updateConst']);
    if($constCycle[0] == 'ConstructionCycle') { //code for entry in process_assignment_system table start
       $QueryMember1 = "Select p.PROJECT_ID
                FROM ".RESI_PROJECT." p 
                left join locality on p.locality_id = locality.locality_id
                left join city on locality.city_id = city.city_id
                left join process_assignment_system pas on p.project_id = pas.project_id
                left join updation_cycle uc on pas.updation_cycle_id = uc.updation_cycle_id";
        $QueryMember1 = $QueryMember1.$QueryMember;
        $QueryExecute = mysql_query($QueryMember1) or die(mysql_error());
        if(mysql_num_rows($QueryExecute)>0)
        {
            while($data = mysql_fetch_assoc($QueryExecute))
            {
               $return =  ProcessAssignmentSystem::insertProcessAssignmentSystem( $data['PROJECT_ID'], $constCycle[1], 'construction' );
                
            }
            header("Location:transferConst.php");
        }
        $smarty->assign("NumRows",$NumRows);
    } //code for entry in process_assignment_system table end
    if($search != '' OR $transfer != '' OR $_POST['projectId'] != '')
    {
        //echo $QueryMember2;
        $QueryExecute = mysql_query($QueryMember2) or die(mysql_error());
        $NumRows = mysql_num_rows($QueryExecute);

        if($NumRows)
        {
            while($data = mysql_fetch_assoc($QueryExecute))
            {
                array_push($projectDataArr,$data);
            }
        }
        $smarty->assign("NumRows",$NumRows);
        $smarty->assign("projectDataArr", $projectDataArr);
    }
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."transferConst.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");	
?>
