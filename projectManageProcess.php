<?php
	$citylist	=	CityArr();
	$builderList	=	BuilderEntityArr();
	if(!isset($_GET['projectId']))
            $_GET['projectId'] = '';

	$enum_value = enum_value();
	$smarty->assign("enum_value",$enum_value);
	$ProjectDetail	= ProjectDetail($_GET['projectId']);
	$smarty->assign("citylist", $citylist);
	$smarty->assign("builderList", $builderList);
	ini_set('max_execution_time',10000000);
	$UpdationArr = updationCycleTable();
    $smarty->assign("UpdationArr", $UpdationArr);
    
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
		$city			=	$_REQUEST['city'];

		if(!isset($_REQUEST['locality']))
			$_REQUEST['locality'] = '';

		$locality	 	=	$_REQUEST['locality'];
		if(!isset($_REQUEST['builder']))
			$_REQUEST['builder'] = '';
		$builder		=	$_REQUEST['builder'];
		if(!isset($_REQUEST['phase']))
			$_REQUEST['phase'] = '';
		$phase = $_REQUEST['phase'];
		
                $arrPhase   = 	explode('|',$_REQUEST['stage']);
                $stage      = 	$arrPhase[0];
                $tag        =   $arrPhase[1];
		
		if(!isset($_REQUEST['Status']))
			$_REQUEST['Status'] = '';
		if(!isset($_REQUEST['Active']))
			$_REQUEST['Active'] = '';
		$Status = $_REQUEST['Status'];
		$Active	= $_REQUEST['Active'];

		if($_GET['projectId'] != '')
			$project_name= $ProjectDetail[0]['PROJECT_NAME'];
		else
			$project_name= $_REQUEST['project_name'];
		
		$smarty->assign("locality", $locality);
		
		$smarty->assign("phase", $phase);
		$smarty->assign("stage", $stage);
                $smarty->assign("tag", $tag);
                $smarty->assign("exp_supply_date_from", $exp_supply_date_from);
                $smarty->assign("exp_supply_date_to", $exp_supply_date_to);

  		if($city != '')
  		{
                    $localityArr = Array();
                    $sql = "SELECT LOCALITY_ID, LABEL FROM ".LOCALITY." AS A WHERE CITY_ID = '" . $city."' ORDER BY LABEL ASC";		
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

		if(!isset($_REQUEST['Residential']))
                    $_REQUEST['Residential'] = '';

		$smarty->assign("Residential", $_REQUEST['Residential']);

		if(!isset($_REQUEST['Availability']))
                    $_REQUEST['Availability'] = '';

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
                    $StatusValue  = implode("','", $_REQUEST['Status']);
		else
                    $StatusValue = '';
 	
		if($StatusValue!="") $StatusValue = "'".$StatusValue."'";

		$QueryMember = "SELECT * FROM ".RESI_PROJECT." WHERE ";

		if($_GET['projectId'] == '')
		{
                    $and = " ";

                    if($_REQUEST['project_name'] != '')
                    {
                        $QueryMember .= $and." PROJECT_NAME LIKE '%".$_REQUEST['project_name']."%'";
                        $and  = ' AND ';
                    }
                    if($_REQUEST['city'] != '')
                    {
                        $QueryMember .=  $and." CITY_ID = '".$_REQUEST['city']."'";
                        $and  = ' AND ';
                        //code for builder refresh if city selected
                        $ctName = ViewCityDetails($_REQUEST['city']);
                        $sqlBuilder = "SELECT A.ENTITY, A.BUILDER_ID FROM ".RESI_BUILDER." AS A WHERE A.CITY = '" .$ctName['LABEL']."'ORDER BY ENTITY ASC";	
                        $arrBuilder	=	array();
                        $resBuilder	=	mysql_query($sqlBuilder);
                        while($data = mysql_fetch_assoc($resBuilder))
                        {
                                $arrBuilder[$data['BUILDER_ID']] = $data['ENTITY'];
                        }
                        $smarty->assign("builderList", $arrBuilder);
                    }
                    if($_REQUEST['Residential'] != '')
                    {
                        $QueryMember .=  $and." RESIDENTIAL = '".$_REQUEST['Residential']."'";
                        $and  = ' AND ';
                    }

                    if($_REQUEST['Availability'] != '')
                    {
                        $QueryMember .= "$and (1 = 0 ";
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
                    if($exp_supply_date_to != '' && $exp_supply_date_from != '')
                    {
                        $QueryMember .= $and." EXPECTED_SUPPLY_DATE BETWEEN '".$exp_supply_date_to."' AND '".$exp_supply_date_from."'";
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
		}
		else
		{
                    $QueryMember .=" PROJECT_ID = '".$_REQUEST['projectId']."'";
		}
		$QueryMember	.= " ORDER BY PROJECT_NAME,BUILDER_NAME DESC";
		//echo $QueryMember;//die;
		$QueryExecute 	= mysql_query($QueryMember) or die(mysql_error());
		$NumRows 	= mysql_num_rows($QueryExecute);

		if($NumRows)
		{
                    while($data = mysql_fetch_assoc($QueryExecute))
                    {
                        array_push($projectDataArr,$data);
                    }
		}
                
	}
	$smarty->assign("city", $city);
	$smarty->assign("builder", $builder);
	$smarty->assign("project_name", $project_name);
	$smarty->assign("projectId", $_GET['projectId']);
	$smarty->assign("NumRows",$NumRows);
	$smarty->assign("projectDataArr", $projectDataArr);

?>
