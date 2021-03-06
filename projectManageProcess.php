<?php
    $citylist = City::CityArr();
    $builderList = ResiBuilder::ProjectSearchBuilderEntityArr();
    if(!isset($_GET['projectId']))
        $_GET['projectId'] = '';
    $projectStatus = ResiProject::projectStatusMaster();
    $smarty->assign("projectStatus",$projectStatus);
    $ProjectDetail = ResiProject::virtual_find($projectId);
    $smarty->assign("citylist", $citylist);
    $smarty->assign("builderList", $builderList);
    ini_set('max_execution_time',10000000);
    $UpdationArr = UpdationCycle::updationCycleTable();
    $smarty->assign("UpdationArr", $UpdationArr);
    $getProjectStages = ProjectStage::getProjectStages();
    $smarty->assign("getProjectStages", $getProjectStages);
    $getProjectPhases = ProjectPhase::getProjectPhases();
    $smarty->assign("getProjectPhases", $getProjectPhases);
    $township = Townships::getAllTownships();
    $arrTownshipDetail = array();
    foreach($township as $value) {
        $arrTownshipDetail[$value->id] = $value->township_name;
    }
    //sort($arrTownshipDetail);
    $smarty->assign("arrTownshipDetail", $arrTownshipDetail);
    
    if(!isset($_REQUEST['Active']))
    {
    	$Active = array();
    	$smarty->assign("Active", $Active);
    }
    if(!isset($_REQUEST['Status']))
    {
    	 $Status = array();
    	 $smarty->assign("Status", $Status);
    }
   
    if(!isset($_GET['mode']))
    	$_GET['mode'] = '';

	if ($_GET['mode'] == 'delete')
	{
            DeleteProject($_GET['projectId']);
	}

	if(!isset($_GET['search']))
		$_GET['search'] = '';

	if(!isset($_REQUEST['search']))
		$_REQUEST['search'] = '';
	$search = $_REQUEST['search'];
	$smarty->assign("search", $search);
	$projectDataArr = array();
	$NumRows = '';
	$city    = '';
	$builder = '';
	$project_name = '';

	if($search != '' OR $_GET['projectId'] != '')
	{
		if(!isset($_REQUEST['exp_supply_date_from']))
			$_REQUEST['exp_supply_date_from'] = '';
		$exp_supply_date_from = $_REQUEST['exp_supply_date_from'];
                
        if(!isset($_REQUEST['exp_supply_date_to']))
			$_REQUEST['city'] = '';
		$exp_supply_date_to = $_REQUEST['exp_supply_date_to'];
                
                if(!isset($_REQUEST['city']))
		$_REQUEST['city'] = '';
		$city =	$_REQUEST['city'];

		if(!isset($_REQUEST['locality']))
			$_REQUEST['locality'] = '';

		$locality = $_REQUEST['locality'];
		if(!isset($_REQUEST['builder']))
			$_REQUEST['builder'] = '';
		$builder = $_REQUEST['builder'];
		if(!isset($_REQUEST['phase']))
			$_REQUEST['phase'] = '';
		$phase = $_REQUEST['phase'];
                if(!isset($_REQUEST['stage']))
			$_REQUEST['stage'] = '';
		$stage = $_REQUEST['stage'];
		if(!isset($_REQUEST['updationCycle']))
			$_REQUEST['updationCycle'] = '';
		$updationCycle = $_REQUEST['updationCycle'];
                if(!isset($_REQUEST['Status']))
			$_REQUEST['Status'] = '';
		if(!isset($_REQUEST['Active']))
			$_REQUEST['Active'] = '';
		$Status = $_REQUEST['Status'];
		$Active	= $_REQUEST['Active'];
		
		if(!empty($_GET['offerId'])){
			$offerId = $_GET['offerId'];
			$smarty->assign("offerId",$offerId);
		}else{
			$withOffer = $_GET['withOffer'];
			$smarty->assign("withAssign",$withOffer);
		}

		if($_GET['projectId'] != '')
			$project_name= $ProjectDetail[0]['PROJECT_NAME'];
		else
			$project_name= $_REQUEST['project_name'];
		$smarty->assign("locality", $locality);
                
                if(!isset($_REQUEST['townshipId']))
                    $_REQUEST['townshipId'] = '';
		
		$smarty->assign("townshipId", $_REQUEST['townshipId']);
                $smarty->assign("phase", $phase);
		$smarty->assign("stage", $stage);
                $smarty->assign("updationCycle", $updationCycle);
                $smarty->assign("exp_supply_date_from", $exp_supply_date_from);
                $smarty->assign("exp_supply_date_to", $exp_supply_date_to);
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

		if(!isset($_REQUEST['Residential']))
                    $_REQUEST['Residential'] = '';

		$smarty->assign("Residential", $_REQUEST['Residential']);

		if(count($_REQUEST['Availability'])>0)
                    $Availability  = implode(",", $_REQUEST['Availability']);
		else
                    $Availability = '';

		$smarty->assign("Availability", $_REQUEST['Availability']);

		if(!is_array($_REQUEST['Active']))
                    $_REQUEST['Active'] = array();

		$smarty->assign("Active", $_REQUEST['Active']);
		
		if(count($_REQUEST['Active'])>0)
                    $ActiveValue  = implode(",", $_REQUEST['Active']);
		else
                    $ActiveValue = '';

		if(!is_array($_REQUEST['Status']))
                    $_REQUEST['Status'] = array();

		$smarty->assign("Status", $_REQUEST['Status']);
		
		if(count($_REQUEST['Status'])>0)
                    $StatusValue  = implode(",", $_REQUEST['Status']);
		else
                    $StatusValue = '';
 	
		if($StatusValue!="") $StatusValue = $StatusValue;

                $arrSearchFields = array();
		if($_GET['projectId'] == '')
		{
                    if($_REQUEST['locality'] != '')
                        $arrSearchFields['locality_id'] = $_REQUEST['locality'];
					elseif(isset($city) && !empty($city)){ //if only city selected	
					
					  if($city == 'othercities'){
							$OtherCitiesKeys = array_keys($arrOtherCities);
							$cities = implode(",",$OtherCitiesKeys);
							$arrSearchFields['city_id'] = $cities;
					  }else
						$arrSearchFields['city_id'] = $city;
					}
					if($_REQUEST['project_name'] != '')
                        $arrSearchFields['project_name'] = trim($_REQUEST['project_name']);
                    if($_REQUEST['Residential'] != '')
                        $arrSearchFields['residential_flag'] = $_REQUEST['Residential'];

                    if($Availability != '')
                    {
                        $QueryMember = 'D_AVAILABILITY = -1';
                        if(in_array(1,$_REQUEST['Availability']))
                        {
                                $QueryMember .=  " OR D_AVAILABILITY = 0";
                        }
                        if(in_array(2,$_REQUEST['Availability']))
                        {
                                $QueryMember .=  " OR D_AVAILABILITY > 0";
                        }
                        if(in_array(3,$_REQUEST['Availability']))
                        {
                                $QueryMember .=  " OR D_AVAILABILITY IS NULL ";
                        }
                       $arrSearchFields['D_AVAILABILITY'] = $QueryMember;
                    }
                    if($ActiveValue != '')
                        $arrSearchFields['status'] = $_REQUEST['Active'];
                    if($StatusValue != '')
                        $arrSearchFields['project_status_id'] = $_REQUEST['Status'];
                    if( $_REQUEST['builder'] != '' ) 
                        $arrSearchFields['builder_id'] = $_REQUEST['builder'];
                    if($_REQUEST['phase'] != '')
                        $arrSearchFields['project_phase_id'] = $_REQUEST['phase'];
                    if($_REQUEST['townshipId'] != '')
                        $arrSearchFields['township_id'] = $_REQUEST['townshipId'];
                    if($stage != '')
                        $arrSearchFields['project_stage_id'] = $stage;
                    if($updationCycle != '')
                        $arrSearchFields['updation_cycle_id'] = $updationCycle;
                    if($exp_supply_date_to != '' && $exp_supply_date_from != '') {
                        $arrSearchFields['expected_supply_date_between_from_to'] = $exp_supply_date_from."_".$exp_supply_date_to;
                    }
                    if($exp_supply_date_to != '' && $exp_supply_date_from == '')
                        $arrSearchFields['expected_supply_date_to'] = $exp_supply_date_to;
                    if($exp_supply_date_to == '' && $exp_supply_date_from != '')
                        $arrSearchFields['expected_supply_date_from'] = $exp_supply_date_from;
		}
		else
                    $arrSearchFields['project_id'] = $_REQUEST['projectId'];
               
		if($exp_supply_date_to != '' && $exp_supply_date_from != ''){
			if(date($exp_supply_date_to) < date($exp_supply_date_from))
				$errorMsg = '<font color = red>Expected Supply To Date must be greater than Expected Supply From Date.</font>';		
		}
	    	   if($errorMsg == '')
			   {   
				   if( count($arrSearchFields) > 0 || !empty($_GET['offerId']) || !empty($_GET['withOffer'])) { 
						$getSearchResult = ResiProject::getAllSearchResult($arrSearchFields);
						$NumRows = count($getSearchResult);
						if(count($getSearchResult) == 0)
						{
							$errorMsg = '<font color = red>No result found!</font>';
						}
					}else {
						 $errorMsg = '<font color = red>Please select atleast one field</font>';
					}
				}
                $smarty->assign("errorMsg", $errorMsg);
                
	}
	$smarty->assign("city", $city);
	$smarty->assign("builder", $builder);
	$smarty->assign("project_name", $project_name);
	$smarty->assign("projectId", $_GET['projectId']);
	$smarty->assign("NumRows",$NumRows);
	$smarty->assign("getSearchResult", $getSearchResult);

?>
