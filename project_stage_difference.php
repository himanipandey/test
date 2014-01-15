<?php
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("modelsConfig.php");
include("includes/configs/configs.php");

//1- Pre-launch Date, Launch Date, Completion Date, Expected Supply Date
//2- Project Status, 
//Booking Status -------- NoPhase Booking Status ID
//3- Sizes with Unit Name 
	
$projectID = $_POST['projectID'];
	
//values on audit1	
$sql_resi = "SELECT _t_transaction_id,PRE_LAUNCH_DATE,LAUNCH_DATE,PROMISED_COMPLETION_DATE,EXPECTED_SUPPLY_DATE,PROJECT_STATUS_ID,created_at,updated_at FROM _t_resi_project WHERE PROJECT_ID=652529 AND PROJECT_PHASE_ID = 4 ORDER BY _t_transaction_id DESC LIMIT 1";

//values on Callcenter
$sql_resi = "SELECT _t_transaction_id,PRE_LAUNCH_DATE,LAUNCH_DATE,PROMISED_COMPLETION_DATE,EXPECTED_SUPPLY_DATE,PROJECT_STATUS_ID,created_at,updated_at FROM _t_resi_project WHERE PROJECT_ID=652529 AND PROJECT_PHASE_ID = 3 ORDER BY _t_transaction_id DESC LIMIT 1";

//left join project_status_master ps on rp.project_status_id = ps.id
	
?>
