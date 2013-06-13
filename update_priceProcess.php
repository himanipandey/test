<?php

	$effectiveDt =  ($_REQUEST['eff_date_to']!='') ? $_REQUEST['eff_date_to'] : '';
	$projectId	 =	$_REQUEST['projectId'];
	if($projectId == '')
		header("Location:index.php");
	$projectDetail			=	ProjectDetail($projectId);
	$ProjectOptionDetail	=	ProjectOptionDetail($projectId);
	$PreviousMonthsData		=	getPrevMonthProjectData($projectId);
	$source_of_information	=	fetch_sourceofInformation();
	//echo "<pre>";
	//print_r($ProjectOptionDetail);
	//echo "</pre>";
	//die("fdsgs");
	$arrAudit	=	AuditTblDataByTblName('resi_project_options',$projectId);
	$smarty->assign("PreviousMonthsData",$PreviousMonthsData);
	$smarty->assign("projectId", $projectId);
	$smarty->assign("source_of_information", $source_of_information);
	$smarty->assign("ProjectOptionDetail",$ProjectOptionDetail);
	$smarty->assign("arrAudit",$arrAudit);

	$smarty->assign("ProjectDetail", $projectDetail);

	$preview = $_REQUEST['preview'];
	$smarty->assign("preview", $preview);

	if(isset($_POST['btnSave']))
	{
		/*************Add new project type if projectid is blank*********************************/
		if($projectid_type == '') 
		{
			$blank_chk = 0;
			$insertlist = '';
			foreach($_REQUEST['option_id'] AS $key=>$val)
			{
				
				
				if($_REQUEST['option_id'][$key] != '')
				{
					$option_id					=	$_REQUEST['option_id'][$key];
					$price_per_unit_area		=	$_REQUEST['price_per_unit_area'][$key];
					$price_per_unit_area_dp		=	$_REQUEST['price_per_unit_area_dp'][$key];
					$price_per_unit_area_fp		=	$_REQUEST['price_per_unit_area_fp'][$key];	
					$edit_reason				=	$_REQUEST['edit_reason'][$key];
					$flats						=	$_REQUEST['flats'][$key];
					$price_per_unit_high		=	$_REQUEST['price_per_unit_high'][$key];
					$price_per_unit_low			=	$_REQUEST['price_per_unit_low'][$key];
					$soi						=	$_REQUEST['soi'][$key];
					$eff_dt						=	$effectiveDt;
					$price_type					=	$_REQUEST['price_type'];

					if(
							($price_per_unit_area != $ProjectOptionDetail[$key]['PRICE_PER_UNIT_AREA'])
						 OR 
							($price_per_unit_area_dp != $ProjectOptionDetail[$key]['PRICE_PER_UNIT_AREA_DP'])
						OR 
							($price_per_unit_area_fp != $ProjectOptionDetail[$key]['PRICE_PER_UNIT_AREA_FP'])
						OR 
							($price_per_unit_high != $ProjectOptionDetail[$key]['PRICE_PER_UNIT_HIGH'])
						OR 
							($price_per_unit_low != $ProjectOptionDetail[$key]['PRICE_PER_UNIT_LOW'])
						OR 
							($eff_dt != $ProjectOptionDetail[$key]['CREATED_DATE'])
						OR 
							($price_type != $ProjectOptionDetail[$key]['PRICE_TYPE'])
						)
					{
						$blank_chk = 1;
					}
					if($blank_chk == 1)
					{
						if(!is_array($ErrorMsg))
						{
							$qrySel		=	"SELECT * FROM ".RESI_PROJECT_OPTIONS." 
												WHERE
													OPTIONS_ID				=	'".$option_id."'
												AND
													PROJECT_ID				=	'".$projectId."'";
							$resSel		=	mysql_query($qrySel);
							$dataSel	=	mysql_fetch_assoc($resSel);

							$insertlist.=	 "('$option_id', '$projectId', '".$dataSel['PRICE_PER_UNIT_AREA']."', '".$dataSel['PRICE_PER_UNIT_AREA_DP']."', '".$dataSel['PRICE_PER_UNIT_AREA_FP']."', '".$dataSel['PRICE_PER_UNIT_LOW']."', '".$dataSel['PRICE_PER_UNIT_HIGH']."','".$dataSel['EDIT_REASON']."', '".$dataSel['ACCURATE_FLAG']."','".$dataSel['SOURCE_OF_INFORMATION']."','".$dataSel['CREATED_DATE']."','".$dataSel['PRICE_TYPE']."'),";

							$qry	=	"UPDATE ".RESI_PROJECT_OPTIONS." 
										SET 
											
											PRICE_PER_UNIT_AREA		=	'".$price_per_unit_area."',
											PRICE_PER_UNIT_AREA_DP	=	'".$price_per_unit_area_dp."',
											PRICE_PER_UNIT_AREA_FP	=	'".$price_per_unit_area_fp."',
											PRICE_PER_UNIT_LOW		=	'".$price_per_unit_low."',
											PRICE_PER_UNIT_HIGH		=	'".$price_per_unit_high."',
											CREATED_DATE			=	'".$eff_dt."',
											EDIT_REASON				=	'".$edit_reason."',
											ACCURATE_FLAG			=	'".$flats."',
											SOURCE_OF_INFORMATION	=	'".$soi."',
											PRICE_TYPE				=	'".$price_type."'
										WHERE
											OPTIONS_ID				=	'".$option_id."'
										AND
											PROJECT_ID				=	'".$projectId."'";
							$res	=	mysql_query($qry) or die(mysql_error());
							if($res)
							{
								audit_insert($option_id,'update','resi_project_options',$projectId);
							}
							
						}
					}
					
				}
			}
			if($blank_chk == 1)
			{
				$qry	=	"INSERT INTO ".RESI_PROJECT_OPTIONS_ARC." (OPTIONS_ID,PROJECT_ID,PRICE_PER_UNIT_AREA,PRICE_PER_UNIT_AREA_DP,PRICE_PER_UNIT_AREA_FP,PRICE_PER_UNIT_LOW,PRICE_PER_UNIT_HIGH,EDIT_REASON,ACCURATE_FLAG,SOURCE_OF_INFORMATION,SUBMITTED_DATE,PRICE_TYPE) VALUES ";
				
				$insertlist	=	substr($insertlist,0,-1);
				$qry_new	=	$qry.$insertlist;
				$res		=	mysql_query($qry_new) or die(mysql_error()); 
				$lastId		=	mysql_insert_id();
				audit_insert($lastId,'insert','resi_project_options_arc',$projectId);
				
					 if($preview == 'true')
						header("Location:show_project_details.php?projectId=".$projectId);
					else
						header("Location:ProjectList.php?projectId=".$projectId);
			}

		}

	}

	if(isset($_REQUEST['btnExit']))
	{
		 if($preview == 'true')
			header("Location:show_project_details.php?projectId=".$projectId);
		else
			header("Location:ProjectList.php?projectId=".$projectId);
	}
	
$smarty->assign('eff_date_to',$effectiveDt);
?>
