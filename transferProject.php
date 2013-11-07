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

    if(!isset($_REQUEST['Residential']))
            $_REQUEST['Residential'] = '';	

    if(!isset($_REQUEST['Active']))
            $_REQUEST['Active'] = '';

    if(!isset($_REQUEST['Status']))
            $_REQUEST['Status'] = '';

    if(!isset($_REQUEST['exp_supply_date_from']))
    $_REQUEST['exp_supply_date_from'] = '';
    $exp_supply_date_from = $_REQUEST['exp_supply_date_from'];

    if(!isset($_REQUEST['exp_supply_date_to']))
    $_REQUEST['city'] = '';
    $exp_supply_date_to = $_REQUEST['exp_supply_date_to'];
    
    $errorMsg = '';
    if(isset($_REQUEST['exp_supply_date_from']) && isset($_REQUEST['exp_supply_date_to'])){
            if(date($exp_supply_date_from) > date($exp_supply_date_to)){
                $errorMsg = "To date should be greater than the From Date.";
                $smarty->assign("errorMsg", $errorMsg);
            }
	}


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
    $getProjectStages = ProjectStage::getProjectStages();
    $smarty->assign("getProjectStages", $getProjectStages);
    $getProjectPhases = ProjectPhase::getProjectPhases();
    $smarty->assign("getProjectPhases", $getProjectPhases);
    
    $transfer = $_REQUEST['transfer'];
    $search = $_REQUEST['search'];
    $city = $_REQUEST['city'];
    $locality = $_REQUEST['locality'];
    $builder = $_REQUEST['builder'];
    $phase = $_REQUEST['phase'];
    $arrPhase = explode('|',$_REQUEST['stage']);
    $stage = $arrPhase[0];
    $tag = $arrPhase[1];
    $Status = $_REQUEST['Status'];
    $Active = $_REQUEST['Active'];
    $Availability = $_REQUEST['Availability'];
    $selectdata = $_POST['selectdata'];

    $smarty->assign("projectStatus",$projectStatus);
    $smarty->assign("citylist", $citylist);
    $smarty->assign("builderList", $builderList);	
    $smarty->assign("UpdationArr", $UpdationArr);	
    $smarty->assign("search", $search);
    $smarty->assign("Residential", $_REQUEST['Residential']);
    $smarty->assign("Status", $_REQUEST['Status']);
    $smarty->assign("city", $city);
    $smarty->assign("builder", $builder);
    $smarty->assign("exp_supply_date_from", $exp_supply_date_from);
    $smarty->assign("exp_supply_date_to", $exp_supply_date_to);
    $smarty->assign("project_name", $project_name);
    $smarty->assign("projectId", $_POST['projectId']);
    $smarty->assign("selectdata", $selectdata);
    $smarty->assign("updatePhasePost", $_REQUEST['updatePhase']);
    $smarty->assign("updateStagePost", $_REQUEST['updateStage']);
    $smarty->assign("Availability", $Availability);
    $smarty->assign("Active", $_REQUEST['Active']);	

    if($search != '' OR $transfer != '' OR $_POST['projectId'] != '')
    {
		$project_name= $_REQUEST['project_name'];

        $smarty->assign("locality", $locality);
        $smarty->assign("phase", $phase);
        $smarty->assign("tag", $tag);
        $smarty->assign("stage", $stage);

        if($city != '')
        {
            $localityArr = Locality::getLocalityByCity($city);
            $smarty->assign("localityArr", $localityArr);
        }

        $QueryMember1 = "Select p.PROJECT_ID,p.PROJECT_PHASE_ID,p.PROJECT_STAGE_ID,ph.name as PROJECT_PHASE, 
                st.name as PROJECT_STAGE 
                FROM ".RESI_PROJECT." p 
                left join  master_project_phases ph on p.project_phase_id = ph.id 
                left join  master_project_stages st on p.project_stage_id = st.id 
                left join locality on p.locality_id = locality.locality_id
                left join suburb on locality.suburb_id = suburb.suburb_id
                left join city on suburb.city_id = city.city_id";

        $QueryMember2 = "Select COUNT(p.PROJECT_ID) CNT,p.PROJECT_PHASE_ID,p.PROJECT_STAGE_ID,
                ph.name as PROJECT_PHASE, st.name as PROJECT_STAGE 
                FROM ".RESI_PROJECT." p 
                left join  master_project_phases ph on p.project_phase_id = ph.id 
                left join  master_project_stages st on p.project_stage_id = st.id 
                left join locality on p.locality_id = locality.locality_id
                left join suburb on locality.suburb_id = suburb.suburb_id
                left join city on suburb.city_id = city.city_id";

        $and = " WHERE ";

        if($_POST['projectId'] == '')
        {				
            /* if($_REQUEST['Availability'] != '')
             {
                     $QueryMember .= $and ." (1 = 0 ";
                     if(in_array(0,$_REQUEST['Availability']))
                     {
                             $QueryMember .=  " OR AVAILABLE_NO_FLATS = 0";
                     }
                     if(in_array(1,$_REQUEST['Availability']))
                     {
                             $QueryMember .=  " OR AVAILABLE_NO_FLATS > 0";
                     }
                     if(in_array(2,$_REQUEST['Availability']))
                     {
                             $QueryMember .=  " OR AVAILABLE_NO_FLATS IS NULL ";
                     }
                     $QueryMember .= ")";
                     $and  = ' AND ';
             }
              */

             if($_REQUEST['city'] != '')
             {
                 $QueryMember .= $and." city.city_id ='".$_REQUEST['city']."'";
                 $and  = ' AND ';
             }
             if($_REQUEST['project_name'] != '')
             {
                 $QueryMember .= $and." PROJECT_NAME LIKE '%".$_REQUEST['project_name']."%'";
                 $and  = ' AND ';
             }
             if($_REQUEST['Residential'] != '')
             {
                 $QueryMember .=  $and." RESIDENTIAL_FLAG = '".$_REQUEST['Residential']."'";
                 $and  = ' AND ';
             }

             if($ActiveValue != '')
             {
                 $QueryMember .=  $and." p.STATUS IN('".$ActiveValue."')";
                 $and  = ' AND ';
             }

             if($StatusValue != '')
             {
                 $QueryMember .=  $and." PROJECT_STATUS_ID IN(".$StatusValue.")";
                 $and  = ' AND ';
             }

             if($_REQUEST['locality'] != '')
             {
                 $QueryMember .= $and." LOCALITY_ID = '".$_REQUEST['locality']."'";
                 $and  = ' AND ';
             }
             if($_REQUEST['builder'] != '')
             {
                 $QueryMember .= $and." BUILDER_ID = '".$_REQUEST['builder']."'";
                 $and  = ' AND ';
             }
             if($_REQUEST['phase'] != '')
             {
                 $QueryMember .= $and." PROJECT_PHASE_ID = '".$_REQUEST['phase']."'";
                 $and  = ' AND ';
             }
             if($stage != '')
             {
                 $QueryMember .= $and." PROJECT_STAGE_ID = '".$stage."'";
                 $and  = ' AND ';
             }
             if($tag != '')
             {
                 $QueryMember .= $and." UPDATION_CYCLE_ID = '".$tag."'";
                 $and  = ' AND ';
             }

             if($exp_supply_date_to != '' && $exp_supply_date_from != '')
             {
                 $QueryMember .= $and." EXPECTED_SUPPLY_DATE BETWEEN '".$exp_supply_date_from."' AND '".$exp_supply_date_to."'";
                 $and  = ' AND ';
             }
             if($exp_supply_date_to != '' && $exp_supply_date_from == '')
             {
                 $QueryMember .= $and." EXPECTED_SUPPLY_DATE <= '".$exp_supply_date_to."'";
                 $and  = ' AND ';
             }
             if($exp_supply_date_to == '' && $exp_supply_date_from != '')
             {
                 $QueryMember .= $and." EXPECTED_SUPPLY_DATE >= '".$exp_supply_date_from."'";
                 $and  = ' AND ';
             }
             $QueryMember .= $and ." version = 'Cms' and updation_cycle_id != ".skipUpdationCycle_Id;
        }
        else
        {
                $QueryMember .= $and. " PROJECT_ID IN (".$_REQUEST['projectId'].") AND version = 'Cms' and updation_cycle_id != ".skipUpdationCycle_Id;

        }
        $QueryMember2	= $QueryMember2. $QueryMember." GROUP BY PROJECT_PHASE_ID,PROJECT_STAGE_ID ORDER BY PROJECT_STAGE_ID";
    }

    if($transfer != '')
    {
        $arrPropId = array();
        $QueryMember1 = $QueryMember1 . $QueryMember;
        $QueryExecute = mysql_query($QueryMember1) or die(mysql_error());
        $NumRows = mysql_num_rows($QueryExecute);

        $arrStagePhase = array();

        if($NumRows > 0)
        {
            while($data = mysql_fetch_assoc($QueryExecute))
            {
                $arrStagePhase[$data['PROJECT_STAGE_ID']][$data['PROJECT_PHASE_ID']][]=$data['PROJECT_ID'];
                array_push($arrPropId,$data['PROJECT_ID']);
            }			
            $getProjectId = implode(',',$arrPropId);
        }
        if($getProjectId != '' && $selectdata != '')
        {
            foreach($selectdata as $k=>$value)
            {
                $arrExp = explode("|",$value);
                $arrUpdatePhase = explode("|",$_POST['updatePhase']);
                $SET = ' SET ';
                $SetQry = '';
                if($arrUpdatePhase[0] != '') {
					$getProjectStage = ProjectStage::getStageByName($arrUpdatePhase[0]);
                    $SetQry .= $SET . " PROJECT_STAGE_ID = '".$getProjectStage[0]->id."' ";
                    $SET = ',';

                    if($arrUpdatePhase[0] == 'NoStage' || $arrUpdatePhase[0] == 'NoPhase' )
                    {
                        $SetQry .= $SET . " PROJECT_PHASE_ID = ".phaseId_7." ";
                        $SET = ',';
                        $arrProjectPhase = phaseId_7;
                    }
                    else
                    {
                        $SetQry .= $SET . " PROJECT_PHASE_ID = ".phaseId_1." ";
                        $SET = ',';
                        $arrProjectPhase = phaseId_7;
                    }

                }
                if($arrUpdatePhase[1] != '' && $arrUpdatePhase[1] != '0') {
                    $SetQry .= $SET . " UPDATION_CYCLE_ID = '".$arrUpdatePhase[1]."' ";
                    $SET = ',';
                }

                if($SetQry != '')
                {
                    mysql_query('begin');
                    $phaseId = ProjectPhase::getPhaseByName($arrExp[1]);
                    $stageId = ProjectStage::getStageByName($arrExp[0]);
                    $Qry = " UPDATE resi_project " . $SetQry . " 
                            WHERE 
                            PROJECT_STAGE_ID='".$stageId[0]->id."' 
                            AND PROJECT_PHASE_ID='".$phaseId[0]->id."' 
                            AND PROJECT_ID IN (".$getProjectId.") AND version = 'Cms'";
                    $QueryExecute = mysql_query($Qry) or die(mysql_error());
                    $tot_affected_rows = mysql_affected_rows($Qry);		

                    $projId_History = '';
                    $projId_History = implode(", ",$arrStagePhase[$arrExp[0]][$arrExp[1]]);

                    $finalProjectIds .= implode(", ",$arrStagePhase[$arrExp[0]][$arrExp[1]]);
                    if($projId_History != '')
                    {
                        $qHistory = "";
                        $qHistory = " INSERT INTO project_stage_history (PROJECT_ID,PROJECT_STAGE_ID,PROJECT_PHASE_ID,DATE_TIME,ADMIN_ID) SELECT PROJECT_ID,'".$arrUpdatePhase[0]."','".$arrProjectPhase."',NOW(),'".$_SESSION['adminId']."' FROM resi_project WHERE PROJECT_ID IN (".$projId_History.") AND version = 'cms' ";
                        mysql_query($qHistory)  or die(mysql_error().__LINE__);
                        $qRecordHistoryId = "update resi_project rp inner join 
                              (select PROJECT_ID, max(HISTORY_ID) HISTORY_ID from project_stage_history where PROJECT_ID in ($projId_History)
                                group by PROJECT_ID) t
                                on rp.PROJECT_ID = t.PROJECT_Id 
                                set rp.MOVEMENT_HISTORY_ID = t.HISTORY_ID where rp.version = 'Cms';";
                        mysql_query($qRecordHistoryId)  or die(mysql_error());
                    }
                    mysql_query('commit');
                    $smarty->assign("projectIdUpdated",str_replace(',',', ',$finalProjectIds));
                    $smarty->assign("tot_affected_rows", $tot_affected_rows);
                }
            }
        }

    }

    if($search != '' OR $transfer != '' OR $_POST['projectId'] != '')
    {
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
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."transferProject.tpl");
    $smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");	
?>
