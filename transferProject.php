<?php

	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	date_default_timezone_set('Asia/Kolkata');
	include("builder_function.php"); 
	AdminAuthentication();
	$dept = $_SESSION['DEPARTMENT'];
		
	$smarty->assign("_POST", $_POST);
	//echo "<pre>";
	//print_r($_POST);
	
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
	
	
	if(count($_REQUEST['Active'])>0)
		$ActiveValue  = implode(",", $_REQUEST['Active']);
	else
		$ActiveValue = '';
	
	if(count($_REQUEST['Status'])>0)
		$StatusValue  = implode("','", $_REQUEST['Status']);
	else
		$StatusValue = '';
	
	if($StatusValue!="") $StatusValue = "'".$StatusValue."'";
	
	$projectDataArr = array();
	$NumRows =  $city = $builder = $project_name = '';
	
	$citylist		=	CityArr();
	$builderList	=	BuilderArr();
	$enum_value		=	enum_value();
	$UpdationArr 	= 	updationCycleTable();
	
	if($_POST['projectId'] != '') $ProjectDetail	=	ProjectDetail($_POST['projectId']);
	
	$transfer 		= 	$_REQUEST['transfer'];
	$search 		= 	$_REQUEST['search'];
	$city		  	=	$_REQUEST['city'];
	$locality	 	=	$_REQUEST['locality'];
	$builder		=	$_REQUEST['builder'];
	$phase 			= 	$_REQUEST['phase'];
	$arrPhase 		= 	explode('|',$_REQUEST['stage']);
	$stage 			= 	$arrPhase[0];
	$tag 			= 	$arrPhase[1];
	$Status 		= 	$_REQUEST['Status'];
	$Active 		= 	$_REQUEST['Active'];
	$Availability	= 	$_REQUEST['Availability'];
	$selectdata		= 	$_POST['selectdata'];
	
	$smarty->assign("enum_value",$enum_value);
	$smarty->assign("citylist", $citylist);
	$smarty->assign("builderList", $builderList);	
	$smarty->assign("UpdationArr", $UpdationArr);	
	$smarty->assign("search", $search);
	$smarty->assign("Residential", $_REQUEST['Residential']);
	$smarty->assign("Status", $_REQUEST['Status']);
	$smarty->assign("city", $city);
	$smarty->assign("builder", $builder);
	$smarty->assign("project_name", $project_name);
	$smarty->assign("projectId", $_POST['projectId']);
	$smarty->assign("selectdata", $selectdata);
	$smarty->assign("updatePhasePost", $_REQUEST['updatePhase']);
	$smarty->assign("updateStagePost", $_REQUEST['updateStage']);
	$smarty->assign("Availability", $Availability);
	$smarty->assign("Active", $_REQUEST['Active']);
	
		
	
	if($search != '' OR $transfer != '' OR $_POST['projectId'] != '')
	{
		
		if($_POST['projectId'] != '')
			$project_name= $ProjectDetail[0]['PROJECT_NAME'];
		else
			$project_name= $_REQUEST['project_name'];
	
		$smarty->assign("locality", $locality);
		$smarty->assign("phase", $phase);
		$smarty->assign("tag", $tag);
		$smarty->assign("stage", $stage);
		
		$arrProjStage = array();
		$q = "SELECT DISTINCT PROJECT_PHASE as PROJECT_STAGE FROM resi_project WHERE PROJECT_PHASE IS NOT NULL AND PROJECT_PHASE<>''";
		$rs = mysql_query($q);
		while($os = mysql_fetch_assoc($rs)){
			array_push($arrProjStage,$os['PROJECT_STAGE']);
		}
		
		$smarty->assign("arrProjStage", $arrProjStage);

		if($city != '')
		{
			$localityArr = Array();
			$sql = "SELECT A.LOCALITY_ID, A.LABEL FROM ".LOCALITY." AS A WHERE A.CITY_ID = '" . $city."' ORDER BY A.LABEL ASC";
			$data = mysql_query($sql);
			if(mysql_num_rows($data)>0)
			{
				while ($dataArr = mysql_fetch_array($data))
				{
					$localityArr[$dataArr['LOCALITY_ID']] =  $dataArr['LABEL'];
				}
			}
			else
			{
				$localityArr[] =  '';
			}
			
			$smarty->assign("localityArr", $localityArr);
		}
		
		$QueryMember1 = "Select PROJECT_ID,PROJECT_PHASE,PROJECT_STAGE FROM ".RESI_PROJECT."  ";
		$QueryMember2 = "Select COUNT(PROJECT_ID) CNT,PROJECT_PHASE,PROJECT_STAGE FROM ".RESI_PROJECT."  ";
		
		$and = " WHERE ";
	
		if($_POST['projectId'] == '')
		{				
			if($_REQUEST['Availability'] != '')
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
			
			if($_REQUEST['project_name'] != '')
			{
				$QueryMember .= $and." PROJECT_NAME LIKE '%".$_REQUEST['project_name']."%'";
				$and  = ' AND ';
			}
			if($_REQUEST['city'] != '')
			{
				$QueryMember .=  $and." CITY_ID = '".$_REQUEST['city']."'";
				$and  = ' AND ';
			}
			if($_REQUEST['Residential'] != '')
			{
				$QueryMember .=  $and." RESIDENTIAL = '".$_REQUEST['Residential']."'";
				$and  = ' AND ';
			}
				
			if($ActiveValue != '')
			{
				$QueryMember .=  $and." ACTIVE IN(".$ActiveValue.")";
				$and  = ' AND ';
			}
	
			if($StatusValue != '')
			{
				$QueryMember .=  $and." PROJECT_STATUS IN(".$StatusValue.")";
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
				$QueryMember .= $and." PROJECT_PHASE = '".$_REQUEST['phase']."'";
				$and  = ' AND ';
			}
			if($stage != '')
			{
				$QueryMember .= $and." PROJECT_STAGE = '".$stage."'";
				$and  = ' AND ';
			}
			if($tag != '')
			{
				$QueryMember .= $and." UPDATION_CYCLE_ID = '".$tag."'";
				$and  = ' AND ';
			}	
		}
		else
		{
			$QueryMember .= $and. " PROJECT_ID IN (".$_REQUEST['projectId'].")";
	
		}
		$QueryMember2	= $QueryMember2. $QueryMember . " GROUP BY PROJECT_PHASE,PROJECT_STAGE ORDER BY PROJECT_STAGE";
	}
	
	
	if($transfer != '')
	{
		$arrPropId = array();
		$QueryMember1 = $QueryMember1 . $QueryMember;
		$QueryExecute 	= mysql_query($QueryMember1) or die(mysql_error());
		$NumRows 		= mysql_num_rows($QueryExecute);
		
		$arrStagePhase = array();
	
		if($NumRows > 0)
		{
			while($data = mysql_fetch_assoc($QueryExecute))
			{
				$arrStagePhase[$data['PROJECT_STAGE']][$data['PROJECT_PHASE']][]=$data['PROJECT_ID'];
				array_push($arrPropId,$data['PROJECT_ID']);
			}			
			$getProjectId = implode(',',$arrPropId);
		}
		
		
		//print($getProjectId);
		
		if($getProjectId != '' && $selectdata != '')
		{
			$finalProjectIds = '';
			
			foreach($selectdata as $k=>$value)
			{
				//echo $value;
				$arrExp = explode("|",$value);
				
				$arrUpdatePhase = explode("|",$_POST['updatePhase']);
				$SET = ' SET ';
				$SetQry = '';
				
				if($arrUpdatePhase[0] != '') {
					$SetQry .= $SET . " PROJECT_STAGE = '".$arrUpdatePhase[0]."' ";
					$SET = ',';
					
					if($arrUpdatePhase[0] == 'noStage' || $arrUpdatePhase[0] == 'noPhase' )
					{
						$SetQry .= $SET . " PROJECT_PHASE = 'noStage' ";
						$SET = ',';
					}
					else
					{
						$SetQry .= $SET . " PROJECT_PHASE = 'dataCollection' ";
						$SET = ',';
					}
						
				}
								
				if($arrUpdatePhase[1] != '' && $arrUpdatePhase[1] != '0') {
					$SetQry .= $SET . " UPDATION_CYCLE_ID = '".$arrUpdatePhase[1]."' ";
					$SET = ',';
				}
				
				if($SetQry != '')
				{
					$Qry = " UPDATE resi_project " . $SetQry . " WHERE PROJECT_STAGE='".$arrExp[0]."' AND PROJECT_PHASE='".$arrExp[1]."' AND PROJECT_ID IN (".$getProjectId.") ";
					//echo "<br>".$Qry;
					if($arrUpdatePhase[1] != '' && $arrUpdatePhase[1] != '0')
					{
						$QueryUpdateCycle = "INSERT INTO revision_phase (PROJECT_ID, UPDATION_CYCLE_ID ) SELECT PROJECT_ID,'".$arrUpdatePhase[1]."' FROM resi_project WHERE PROJECT_STAGE='".$arrExp[0]."' AND PROJECT_PHASE='".$arrExp[1]."' AND PROJECT_ID IN (".$getProjectId.") ";
						$QueryExecute = mysql_query($QueryUpdateCycle) or die(mysql_error());
					}
					
					$QueryExecute = mysql_query($Qry) or die(mysql_error());
					$tot_affected_rows = mysql_affected_rows($Qry);		
					
					$projId_History = '';
					$projId_History = implode(", ",$arrStagePhase[$arrExp[0]][$arrExp[1]]);
										
					$finalProjectIds .= implode(", ",$arrStagePhase[$arrExp[0]][$arrExp[1]]);
					if($projId_History != '')
					{
						$qHistory = "";
						$qHistory = " INSERT INTO project_stage_history (PROJECT_ID,PROJECT_STAGE,PROJECT_PHASE,DATE_TIME,ADMIN_ID) SELECT PROJECT_ID,'".$arrExp[0]."','".$arrExp[1]."',NOW(),'".$_SESSION['adminId']."' FROM resi_project WHERE PROJECT_ID IN (".$projId_History.") ";
						mysql_query($qHistory)  or die(mysql_error().__LINE__);
					}
					$smarty->assign("projectIdUpdated",str_replace(',',', ',$finalProjectIds));
					$smarty->assign("tot_affected_rows", $tot_affected_rows);
				}
			}
			//echo "<br><br><br>".$getProjectId;
		}
		
	}
	
	if($search != '' OR $transfer != '' OR $_POST['projectId'] != '')
	{
		$QueryExecute 	= mysql_query($QueryMember2) or die(mysql_error());
		$NumRows 		= mysql_num_rows($QueryExecute);
		
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