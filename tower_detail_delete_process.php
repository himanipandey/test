<?php
	$RoomCategoryArr	=	RoomCategory::categoryList();
	$projectId			=	$_REQUEST['projectId'];
	$projectDetail1		=	ResiProject::virtual_find($projectId);
    $projectDetail1     =   $projectDetail1->to_array();
    foreach($projectDetail1 as $key=>$value){
        $projectDetail1[strtoupper($key)] = $value;
        unset($projectDetail1[$key]);
    }
    $projectDetail1     =   array($projectDetail1);
	$towerDetail_object	=	ResiProjectTowerDetails::find("all", array("conditions" => "project_id = {$projectId}"));//fetch_towerDetails($projectId); //fetch all tower details
    $towerDetail        =   array();
    $towerIdArr = array();
    foreach($towerDetail_object as $s){
        $s = $s->to_array();
        foreach($s as $key=>$value){
            $s[strtoupper($key)] = $value;
            unset($s[$key]);
        }
        $towerIdArr[] = $s["TOWER_ID"];
            array_push($towerDetail, $s);
    }

/////////////////////////////////////////LFN////////////////////////////////////////////////////////////////////
	$smarty->assign("last_updated_date", $towerDetail[0]['UPDATED_AT']);
///////////////////////////////////////////////////////////////////////////////////////////////////////////////


	if($_GET['totRow'] != '')
	{
		//selected row display
		$totRow			=	$_GET['totRow'];
	}
	
	else if(count($towerDetail)>0)
	{
		$totRow			=	count($towerDetail);
	}
	else
	{
		//default display rows
		$totRow			=	5;

	}
        
	$smarty->assign("TotRow", $totRow);
	$smarty->assign("towerDetail", $towerDetail);
	$smarty->assign("towersInResale", getTowerMappedInResale($towerIdArr));
	$smarty->assign("projectDetail", $projectDetail1);
	$smarty->assign("projectId", $projectId);
	
	$fetch_towerName = fetch_towerName($projectId);

    $finalFlg = 0;

	if ($_POST['btnSave'] == "Save")
	{
		foreach($_REQUEST['tower_name'] as $key=>$val)
		{
            ResiProjectTowerDetails::transaction(function(){
                global $key;
                global $val;
                global $projectId;
                $tower_name 		= 	$val;
                $no_of_floor 		= 	$_REQUEST['no_of_floor'][$key];
                $tower_id 			= 	$_REQUEST['tower_id'][$key];
                $no_of_flats 		= 	$_REQUEST['no_of_flats'][$key];
                $remark 			= 	$_REQUEST['remark'][$key];
                $face 				= 	$_REQUEST['face'][$key];
                $stilt 				= 	$_REQUEST['stilt'][$key];
                $eff_date 			= 	$_REQUEST['eff_date'][$key];

                $update_array = array(
                    "tower_name" => $tower_name,
                    "project_id" => $projectId,
                    "tower_id" => $tower_id,
                    "no_of_flats" => $no_of_flats,
                    "remarks" => $remark,
                    "no_of_floors" => $no_of_floor,
                    "tower_facing_direction" =>$face,
                    "stilt" => $stilt,
                    "actual_completion_date" => $eff_date,
                    "updated_by" => $_SESSION["adminId"]
                );

                if($tower_name != '') {
                    $tower_obj = ResiProjectTowerDetails::create_or_update($update_array);
                    $project = ResiProject::virtual_find($projectId);
                    $towerIds = array();

                    foreach ($project->get_all_towers() as $tower) {
                        $towerIds[] = $tower->tower_id;
                    }
                    
                    $phases = ResiProjectPhase::find('all', array('conditions' => array('project_id' => $projectId, 'phase_type' => 'Logical')));
                    $phases[0]->add_towers($towerIds);
                }

                
            });
            
            //checking conflicts in deletion
            $tower_id 	= 	$_REQUEST['tower_id'][$key];
            $deleteKey = "delete_".($key+1);
            if($_REQUEST[$deleteKey] == on)
            {
				$const_image = mysql_query("select * from project_plan_images where `tower_id`='".$tower_id."'");
				if(mysql_num_rows($const_image))
					$ErrorMsg1 = "This tower (".$_REQUEST['tower_name'][$key].") has been tagged to Construction Image. Un-tag it before deletion!";
						
				$tower_phase = mysql_query("SELECT lst.id from ".PHASE_TOWER_MAPPINGS." lst left join ".RESI_PROJECT_PHASE." rpp on lst.phase_id = rpp.phase_id where lst.tower_id = ".$tower_id." and rpp.phase_type = 'Actual' and rpp.version = 'Cms' and rpp.status = 'Active'");
				if(mysql_num_rows($tower_phase))
					$ErrorMsg1 = "This tower (".$_REQUEST['tower_name'][$key].") has been tagged to Actual Phase. Un-tag it before deletion!";
			}
                          
		}
		
		if($ErrorMsg1 == ''){
				
          $allData = $_REQUEST;
		  foreach($allData['tower_name'] as $key=>$val)
		  {	
			ResiProjectTowerDetails::transaction(function(){
					global $key;
					global $val;
					global $projectId;
					global $allData;
				 
					$tower_id 	= 	$allData['tower_id'][$key];
				  
					$deleteKey = "delete_".($key+1);
					if($allData[$deleteKey] == on)
					{
						$map_id = mysql_fetch_object(mysql_query("SELECT lst.id from ".PHASE_TOWER_MAPPINGS." lst left join ".RESI_PROJECT_PHASE." rpp on lst.phase_id = rpp.phase_id where lst.tower_id = ".$tower_id." and rpp.phase_type = 'Logical' and rpp.version = 'Cms' and rpp.status = 'Active'"));
						
						mysql_query("delete from ".PHASE_TOWER_MAPPINGS." where id ='".$map_id->id."'");
						
						mysql_query("DELETE FROM `resi_project_tower_details` WHERE `tower_id`='".$tower_id."'");
					}
					
				 });
			}

				header("Location:ProjectList.php?projectId=".$projectId);
		}
	}
    else if($_POST['btnExit'] == "Exit")
    {
          if($preview == 'true')
			header("Location:show_project_details.php?projectId=".$projectId);
		else
			header("Location:ProjectList.php?projectId=".$projectId);
    }

    $smarty->assign("ErrorMsg1", $ErrorMsg1);
    $smarty->assign("projecteror", $projecteror);

    function getTowerMappedInResale($towerIdArr){
        $result = array();
        $towerIds = implode(",", $towerIdArr);
        $sqlStr = "SELECT id,tower_id FROM listings WHERE tower_id in({$towerIds}) AND status='Active'";
        $sqlRes = mysql_query($sqlStr) or die("Some error occured(E-002)");
        if(mysql_num_rows($sqlRes)>0){
            while ($row = mysql_fetch_assoc($sqlRes)){
                $result[] = $row["tower_id"];
            }
        }
        return $result;
    }
?>
