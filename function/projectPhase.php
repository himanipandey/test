<?php

/*
 * functions for launch and pre launch date updation validation
 */
function checkAvailablityDate($projectId, $date){
    $phases = ResiProjectPhase::find("all", array("conditions" => array("project_id" => $projectId, "status" => 'Active', "version" => 'Cms'), "order" => "phase_name asc"));
    $rows = array();

    foreach ($phases as $p) {
        $sql = "select pa.* from project_availabilities pa 
        inner join project_supplies ps on (ps.id = pa.project_supply_id and ps.version='Cms') 
        inner join listings l on (l.id = ps.listing_id and l.status='Active')
        inner join resi_project_phase rpp on (rpp.PHASE_ID=l.phase_id and rpp.PHASE_ID='{$p->phase_id}' and rpp.version='Cms')
        where pa.effective_month <= '{$date}'";
        $res = mysql_query($sql);
         
        while($row = mysql_fetch_array($res))
        {
            $rows[] = $row;
        }
    }
    if(empty($rows)) return false;
    else return true;
}

function checkListingPricesDate($projectId, $date){
    $phases = ResiProjectPhase::find("all", array("conditions" => array("project_id" => $projectId, "status" => 'Active', "version" => 'Cms'), "order" => "phase_name asc"));
    $rows = array();
    foreach ($phases as $p) {
    $sql = "select lp.* from listing_prices lp 
        inner join listings l on (l.id = lp.listing_id and lp.status='Active' and lp.version='Cms' and l.status='Active')
        inner join resi_project_phase rpp on (rpp.PHASE_ID=l.phase_id and rpp.PHASE_ID='{$p->phase_id}' and rpp.version='Cms')
        where lp.effective_date <= '{$date}'";
        $res = mysql_query($sql);
         
        while($row = mysql_fetch_array($res))
        {
            $rows[] = $row;
        }
    }

    if(empty($rows)) return false;
    else return true;
}

function projectStageName($projectId){
    $ProjectDetail = ResiProject::virtual_find($projectId);
    $qryStg = "select * from master_project_stages where id = '".$ProjectDetail->project_stage_id."'";
    $resStg = mysql_query($qryStg) or die(mysql_error());
    $stageId = mysql_fetch_assoc($resStg);
    return $stageId['name'];
}

function projectStatusUpdate($projectId){
  $no_of_phases = 0;
  $condition = '';
	
  $phase_created = mysql_query("SELECT COUNT(*) as cnt FROM `resi_project_phase`  WHERE `resi_project_phase`.`version` = 'Cms' AND `resi_project_phase`.`PROJECT_ID` = '$projectId' AND `resi_project_phase`.`PHASE_TYPE` = 'Actual'  AND `resi_project_phase`.status = 'Active'") or die(mysql_error());
	
  if($phase_created)
	$no_of_phases = mysql_fetch_object($phase_created)->cnt;
			
  if($no_of_phases > 0){
	$status_sql = mysql_query("SELECT construction_status FROM `resi_project_phase`  WHERE `resi_project_phase`.`version` = 'Cms' AND `resi_project_phase`.`PROJECT_ID` = '$projectId' AND `resi_project_phase`.`PHASE_TYPE` = 'Actual'  AND `resi_project_phase`.status = 'Active'") or die(mysql_error());	
	$all_status = array();
	while($row = mysql_fetch_object($status_sql)){
	  	//updation will goes here
	  	$all_status[] = $row->construction_status;
	}
	$project_status = 0;
	if(in_array(1,$all_status)) //under construction	  
		$project_status = 1;
	else if(in_array(7,$all_status)) //Launch
		$project_status = 7;
	elseif(in_array(8,$all_status)) //pre-lauch
		$project_status = 8;
	elseif(in_array(3,$all_status) || in_array(4,$all_status)) //Ready for possession or Occupied
		$project_status = 3;	
	else
	    $project_status = 6;
	 mysql_query("update resi_project set project_status_id = '$project_status' where project_id = '$projectId' and version = 'Cms'") or die(mysql_error());   
	    
  }else{
     mysql_query("update resi_project set project_status_id = (select construction_status from resi_project_phase where phase_type = 'Logical' and project_id = '$projectId'and version = 'Cms') where project_id = '$projectId' and version = 'Cms'") or die(mysql_error());
  }
	
}
function fetch_project_status($projectId,$construction_status = ''){
  $no_of_phases = 0;
  $condition = '';
  $project_status = 0;
	
  $phase_created = mysql_query("SELECT COUNT(*) as cnt FROM `resi_project_phase`  WHERE `resi_project_phase`.`version` = 'Cms' AND `resi_project_phase`.`PROJECT_ID` = '$projectId' AND `resi_project_phase`.`PHASE_TYPE` = 'Actual'  AND `resi_project_phase`.status = 'Active'") or die(mysql_error());
	
  if($phase_created)
	$no_of_phases = mysql_fetch_object($phase_created)->cnt;
			
  if($no_of_phases > 0){
	$status_sql = mysql_query("SELECT construction_status FROM `resi_project_phase`  WHERE `resi_project_phase`.`version` = 'Cms' AND `resi_project_phase`.`PROJECT_ID` = '$projectId' AND `resi_project_phase`.`PHASE_TYPE` = 'Actual'  AND `resi_project_phase`.status = 'Active'") or die(mysql_error());	
	$all_status = array();
	while($row = mysql_fetch_object($status_sql)){
	  	//updation will goes here
	  	$all_status[] = $row->construction_status;
	}
	
	if($construction_status){
		$all_status[] = $construction_status;
	}
		
	if(in_array(1,$all_status)) //under construction	  
		$project_status = 1;
	else if(in_array(7,$all_status)) //Launch
		$project_status = 7;
	elseif(in_array(8,$all_status)) //pre-lauch
		$project_status = 8;
	elseif(in_array(3,$all_status) || in_array(4,$all_status)) //Ready for possession or Occupied
		$project_status = 3;	
	else
	    $project_status = 6;  
	    
  }else{
     $sql_project_status = mysql_fetch_object(mysql_query("select construction_status from resi_project_phase where phase_type = 'Logical' and project_id = '$projectId' and version = 'Cms'" ));
     if($construction_status)
       $project_status = $construction_status;
     else
       $project_status = $sql_project_status->construction_status;
  }
	
	return $project_status;
}
/* * *********************************** */
?>
