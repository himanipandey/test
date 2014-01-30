<?php
	$projectId		=	$_REQUEST['projectId'];
	$fetch_projectDetail	=	ProjectDetail($projectId);
	$smarty->assign("ProjectDetail", $fetch_projectDetail);

	$smarty->assign("fetch_projectDetail",$fetch_projectDetail);

	//echo "<pre>";
	//print_r($_REQUEST);
	//echo "</pre>";//die;
	/*************************************/
	$sourcepath=array();
	$destinationpath=array();
	$flag=0;
	$projectFolderCreated=0;

	if (isset($_POST['btnSave']))
	{
		$towername				=	$_REQUEST['TowerId'];
		$no_of_floors			=	$_REQUEST['FloorId'];
		$remarks				=	$_REQUEST['texta'];
        $stilt					=	$_REQUEST['stilt'];
		$no_of_flats_per_floor	=	$_REQUEST['AvilFlatId'];
		$towerface				=	$_REQUEST['face'];
        $completion_date		=	$_REQUEST['eff_date_to'];
		$remark					=	$_REQUEST['remark'];
		  
		insert_towerDetail($projectId,$towername,$no_of_floors,$stilt,$no_of_flats_per_floor,$towerface,$completion_date,$remark);
		if($_POST['btnSave'] == 'Submit')
				header("Location:ProjectList.php"); // need to modify 
			else
				header("Location:add_tower_construction.php?projectId=".$projectId); //in cases of add more 
    }
	else if($_POST['btnExit'] == "Exit")
	{
		  header("Location:ProjectList.php");
	}
	
	/**************************************/
	

?>