<?php
	
	$projectId = $_REQUEST['projectId'];
	$projectDetail		=	ProjectDetail($projectId);
	
	$smarty->assign("ProjectDetailArr", $projectDetail);
	
	$OtherPrice	= fetch_other_price($projectId);

	if(count($OtherPrice)>0)
		$edit = 1;
	else
		$edit = 0;
	
	$smarty->assign("edit", $edit);
	$smarty->assign("OtherPrice", $OtherPrice);
    $noPhasePhase = ResiProjectPhase::getNoPhaseForProject($projectId);
    $noPhasePhaseId = $noPhasePhase->phase_id;
	if ($_POST['btnSave'] == "Next" || $_POST['btnSave'] == "Submit")
	{
            $return = InsertUpdateOtherPrice($_REQUEST,$projectId);
            if($return == true)
            {
                if($_POST['btnSave'] == 'Submit')
                    header("Location:ProjectList.php?projectId=".$projectId);
                else
                    header("Location:new/availability/".$noPhasePhaseId."/edit");
            }
            else {
                die("error in other price insertion");
            }
	}
	else if($_REQUEST['btnExit'] == "Exit")
    {
          header("Location:ProjectList.php?projectId=".$projectId);
    }
	else if($_REQUEST['Skip'] == "Skip")
	{
		  header("Location:new/availability/".$noPhasePhaseId."/edit");
	}

?>
