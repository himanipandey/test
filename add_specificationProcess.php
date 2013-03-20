<?php
	$AmenitiesArr	=	AmenitiesList();	
	$smarty->assign("AmenitiesArr",$AmenitiesArr);
	//echo "<pre>";
		//print_r($AmenitiesArr);
	//echo "</pre>";//die;
	/*************************************/
	$sourcepath=array();
	$destinationpath=array();
	$flag=0;
	$projectFolderCreated=0;
	$projectId  = $_REQUEST['projectId'];
	$edit		= $_REQUEST['edit'];
	if($edit != '')
	{
		$ProjectAmenities	=	ProjectAmenities($projectId,$arrNotninty,$arrDetail,$arrninty);
		$arrSpecification	=	specification($projectId);
		$smarty->assign("arrSpecification", $arrSpecification);
		$smarty->assign("arrNotninty", $arrNotninty);
		$smarty->assign("arrninty", $arrninty);
		$smarty->assign("edit_project", $projectId);
		//echo "<pre>";
		//print_r($arrNotninty);
		//echo "</pre>";

	}
	else
		$smarty->assign("edit_project", '');

	$smarty->assign("projectId", $projectId);

	$projectDetail	=	ProjectDetail($projectId);
	$smarty->assign("projectDetail", $projectDetail);
	$builderDetail = fetch_builderDetail($projectDetail[0]['BUILDER_ID']);
	$smarty->assign("builderDetail", $builderDetail);

	$preview = $_REQUEST['preview'];
	$smarty->assign("preview", $preview);

	if (isset($_POST['btnSave']))
	{
		if($_POST['btnSave'] == "Save")
		{
			deleteAmenities($projectId);
			deleteSpecification($projectId);
		}
		$qryIns_one = "INSERT INTO ".RESI_PROJECT_AMENITIES." (PROJECT_ID,AMENITY_DISPLAY_NAME,AMENITY_ID) VALUES ";	
		$qryIns		= '';
		$ErrMsg     = '';
		$ErrMsg1     = '';
		$ErrMsg2     = '';
		$newArr = array();
		foreach($_REQUEST as $key=>$val)
		{
			if(strstr($key,'#'))
			{	
				if($val != 0)
					{
						$amenity_name = '';
						$amenity_id   = '';
					
						$exp = explode("#",$key);
						$amenity_name = $exp[0];
						$amenity_id   = $exp[1];

						$key_display = "display_name_".$amenity_id;
						if(array_key_exists($key_display,$_REQUEST))
						{
							if($_REQUEST[$key_display][0] != '' AND !in_array($_REQUEST[$key_display][0],$newArr))
							{
								$qryIns .= "('".$projectId."','".str_replace("_"," ",addslashes($_REQUEST[$key_display][0]))."','".$amenity_id."'),";
							}
							else
							{
								$qryIns .= "('".$projectId."','".str_replace("_"," ",addslashes($amenity_name))."','".$amenity_id."'),";
							}
						}
					}
				}
			}

			foreach($_REQUEST['newAmenity'] as $key=>$val)
			{
				if($val != '')
				{
					$qryIns .= "('".$projectId."','".addslashes(str_replace("_"," ",$val))."','99'),";
				}
			}

			if($qryIns != '')
			{
				$qryIns = $qryIns_one.$qryIns;
				$qryins = substr($qryIns,0,-1);

				$res	= mysql_query($qryins) or die(mysql_error());
				$lastId = mysql_insert_id();
				audit_insert($lastId,'insert','resi_project_amenities',$projectId);
			}
			else
			{
				$ErrMsg1    = '1';
			}
	
			$master_bedroom_flooring	=	trim($_POST['master_bedroom_flooring']);
			$other_bedroom_flooring		=	trim($_POST['other_bedroom_flooring']);
			$living_room_flooring		=	trim($_POST['living_room_flooring']);
			$kitchen_flooring			=	trim($_POST['kitchen_flooring']);
			$toilets_flooring			=	trim($_POST['toilets_flooring']);
			$balcony_flooring			=	trim($_POST['balcony_flooring']);
			$interior_walls				=	trim($_POST['interior_walls']);
			$exterior_walls				=	trim($_POST['exterior_walls']);
			$kitchen_walls				=	trim($_POST['kitchen_walls']);
			$toilets_walls				=	trim($_POST['toilets_walls']);
			$kitchen_fixtures			=	trim($_POST['kitchen_fixtures']);		
			$toilets_fixtures			=	trim($_POST['toilets_fixtures']);
			$main_doors					=	trim($_POST['main_doors']);
			$internal_doors				=	trim($_POST['internal_doors']);
			$windows					=	trim($_POST['windows']);
			$electrical_fitting			=	trim($_POST['electrical_fitting']);
			$others						=	trim($_POST['others']);
		
		     if($master_bedroom_flooring == '' && $other_bedroom_flooring == ''&& $living_room_flooring == ''&& $kitchen_flooring == ''&& $toilets_flooring == ''&& $balcony_flooring == ''&& $interior_walls == ''&& $exterior_walls == ''&& $kitchen_walls == ''&& $toilets_walls == ''&& $kitchen_fixtures == ''&& $toilets_fixtures == ''&& $main_doors == ''&& $internal_doors == ''&& $windows == ''&& $electrical_fitting == ''&& $others == '')
			  {
					$ErrMsg2    = '2';
			  }
			  else
			  {
					InsertSpecification($projectId,$master_bedroom_flooring, $other_bedroom_flooring, $living_room_flooring,$kitchen_flooring,$toilets_flooring,$balcony_flooring,$interior_walls,$exterior_walls,$kitchen_walls,$toilets_walls,$kitchen_fixtures,$toilets_fixtures,$main_doors,$internal_doors,$windows,$electrical_fitting,$others);
					
			  }

			if($ErrMsg1 != '' && $ErrMsg2 != '')
			{
				$ErrMsg    = 'Please select atleast one value!';
				$smarty->assign("ErrMsg", $ErrMsg);
			}
			else
			{
				if($_POST['btnSave'] == "Save")
				{
					if($preview == 'true')
						header("Location:show_project_details.php?projectId=".$projectId);
					else
						header("Location:ProjectList.php?projectId=".$projectId);
				}
				else
				{
					header("Location:add_apartmentConfiguration.php?projectId=".$projectId);
				}
			}
	} 
	else if(isset($_POST['btnExit']))
	{
		  if($preview == 'true')
			header("Location:show_project_details.php?projectId=".$projectId);
		  else
			header("Location:ProjectList.php?projectId=".$projectId);
	}
	else if(isset($_POST['Skip']))
	{
		  header("Location:add_apartmentConfiguration.php?projectId=".$projectId);
	}


	/**************************************/
	

?>
