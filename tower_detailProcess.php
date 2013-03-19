<?php
	$projectId				=	$_REQUEST['projectId'];
	$projectDetail			=	ProjectDetail($projectId);
	$smarty->assign("ProjectDetail", $projectDetail); 

	$towerDetail			=	fetch_towerDetails($projectId);
	//echo "<pre>";
	//print_r($_REQUEST);
	//echo "</pre>";

	$edit = $_GET['edit'];
	$smarty->assign("edit", $edit); 
	/*************************************/
	$sourcepath=array();
	$destinationpath=array();
	$flag=0;
	$projectFolderCreated=0;

	if (isset($_POST['btnSave']))
	{
		$towername				=	$_REQUEST['TowerId'];
		$no_of_floors			=	$_REQUEST['FloorId'];
        $stilt					=	$_REQUEST['stilt'];
		$no_of_flats_per_floor	=	$_REQUEST['AvilFlatId'];
		$towerface				=	$_REQUEST['face'];
        $completion_date		=	$_REQUEST['eff_date_to'];
		$remark					=	$_REQUEST['remark'];
		$edit					=	$_REQUEST['edit'];

		$smarty->assign("towername", $towername);
		$smarty->assign("no_of_floors", $no_of_floors);
		$smarty->assign("stilt", $stilt);
		$smarty->assign("no_of_flats_per_floor", $no_of_flats_per_floor);
		$smarty->assign("towerface", $towerface);
		$smarty->assign("completion_date", $completion_date);
		$smarty->assign("remark", $remark);
		$smarty->assign("edit", $edit);
		 $TowerExists			=	searchTower($towerDetail,$towername); 
		 if($TowerExists == 1)
		 {
			$errorMsg['Error'] = "This tower already exists!";
		 }
		 if(is_array($errorMsg))
		 {
			
		 }
		 else
		 {
			insert_towerDetail($projectId,$towername,$no_of_floors,$stilt,$no_of_flats_per_floor,$towerface,$completion_date,$remark);
			if($_POST['btnSave'] == 'Next')
				header("Location:add_tower_construction_status.php?projectId=".$projectId); // need to modify 
			else if($_POST['btnSave'] == 'Submit')
				header("Location:ProjectList.php?projectId=".$projectId); // add new tower 
			else
			 {
				if($edit == 'edit')
					header("Location:tower_detail.php?projectId=".$projectId."&edit=edit"); //in cases of add more 
				else
					header("Location:tower_detail.php?projectId=".$projectId); //in cases of add more 
			 }
		 }
		$smarty->assign("errorMsg", $errorMsg); 
    }
	else if($_POST['btnExit'] == "Exit")
	{
		  header("Location:ProjectList.php?projectId=".$projectId);
	}
	else if($_POST['Skip'] == "Skip")
	{
		  header("Location:add_tower_construction_status.php?page=1&sort=all"); // need to modify 
	}
	/**************************************/
	

?>


