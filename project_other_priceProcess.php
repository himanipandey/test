<?php
	
	$projectId			=	$_REQUEST['projectId'];
	$projectDetail		=	ProjectDetail($projectId);
	
	$smarty->assign("ProjectDetailArr", $projectDetail);
	
	$OtherPrice	= fetch_other_price($projectId);

	if(count($OtherPrice)>0)
		$edit = 1;
	else
		$edit = 0;
	
	$smarty->assign("edit", $edit);
	$smarty->assign("OtherPrice", $OtherPrice);
	if ($_POST['btnSave'] == "Next" || $_POST['btnSave'] == "Submit")
	{
		if($_REQUEST['edit'] == 0)
			$return = InsertOtherPrice($_REQUEST);
		else
			$return = UpdateOtherPrice($_REQUEST);
		if($return)
		{
			if($_POST['btnSave'] == 'Submit')
				header("Location:ProjectList.php?projectId=".$projectId);
			else
			header("Location:add_supply_inventory.php?projectId=".$projectId);
	}
	}
	else if($_REQUEST['btnExit'] == "Exit")
    {
          header("Location:ProjectList.php?projectId=".$projectId);
    }
	else if($_REQUEST['Skip'] == "Skip")
	{
		  header("Location:add_supply_inventory.php?projectId=".$projectId);
	}

?>