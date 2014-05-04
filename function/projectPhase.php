<?php

/*
 * functions for launch and pre launch date updation validation
 */
function checkAvailablityDate($projectId, $date){
    $phases = ResiProjectPhase::find("all", array("conditions" => array("project_id" => $projectId, "status" => 'Active', "version" => 'Cms'), "order" => "phase_name asc"));
    $rows = [];

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
    $rows = [];
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

/* * *********************************** */
?>
