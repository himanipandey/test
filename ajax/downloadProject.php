<?php
include("../smartyConfig.php");
include("../dbConfig.php");
include("../modelsConfig.php");
include("../appWideConfig.php");
include("../builder_function.php");
require_once("../common/function.php");
include("../includes/configs/phaseStageConfig.php");
date_default_timezone_set('Asia/Kolkata');

$arrOtherCities = 
  array(
		"24"=>"Chandigarh",
		"23"=>"Lucknow",
		"13"=>"Indore",
		"33"=>"Bhopal",
		"35"=>"Nashik",
		"25"=>"Nagpur",
		"38"=>"Vadodara",
		"27"=>"Goa",
		"97"=>"Durgapur",
		"31"=>"Bhubaneswar",
		"30"=>"Kochi",
		"29"=>"Trivandrum",
		"45"=>"Trichy",
		"41"=>"Visakhapatnam",
		"90"=>"Sonepat",
		"98"=>"Panipat",
		"99"=>"Raigad",
		"26"=>"Coimbatore",
		"28"=>"Jaipur",
		"46"=>"Agra",
		"48"=>"Pondicherry",
		"61"=>"Vijayawada",
		"91"=>"Karnal"
  );

$dept = $_SESSION['DEPARTMENT'];

if(!isset($_POST['dwnld_projectId']))
	$_POST['dwnld_projectId'] = '';

if(!isset($_POST['dwnld_mode']))
	$_POST['dwnld_mode'] = '';

if(!isset($_POST['dwnld_search']))
	$_POST['dwnld_search'] = '';

if(!isset($_POST['dwnld_search']))
	$_POST['dwnld_search'] = '';

if(!isset($_POST['dwnld_city']))
	$_POST['dwnld_city'] = '';

if(!isset($_POST['dwnld_locality']))
	$_POST['dwnld_locality'] = '';

if(!isset($_POST['dwnld_Residential']))
	$_POST['dwnld_Residential'] = '';

if(!isset($_POST['dwnld_Active']))
	$_POST['dwnld_Active'] = '';

if(!isset($_POST['dwnld_Status']))
	$_POST['dwnld_Status'] = '';

if($_POST['dwnld_Active'] != '')
	$ActiveValue  = $_POST['dwnld_Active'];
else
	$ActiveValue = '';

if($_POST['dwnld_Status'] != '')
	$StatusValue  = $_POST['dwnld_Status'];
else
	$StatusValue = '';

if($StatusValue!="") $StatusValue = "'".$StatusValue."'";

$projectDataArr = array();
$NumRows =  $city = $builder = $project_name = '';

$transfer = $_POST['dwnld_transfer'];
$search = $_POST['dwnld_search'];
$city =	$_POST['dwnld_city'];
if($city == 'othercities'){
	$group_city_ids = array();
	foreach($arrOtherCities as $key => $value){
		$group_city_ids[] = $key;
	}
	$city= implode(",",$group_city_ids);
}

$locality = $_POST['dwnld_locality'];
$builder = $_POST['dwnld_builder'];
$phase = $_POST['current_dwnld_phase'];
$arrPhaseTag = explode('|',$_POST['dwnld_stage']);
$stage = $_POST['current_dwnld_stage'];
$updation_cycle = $_POST['dwnld_updationCycle'];
$Status = $_POST['dwnld_Status'];
$Active = $_POST['dwnld_Active'];
$selectdata = $_POST['dwnld_selectdata'];
if($search != '' OR $transfer != '' OR $_POST['dwnld_projectId'] != '')
{

    $QueryMember1 = "SELECT RP.updation_cycle_id,RP.PROJECT_ID,RB.BUILDER_NAME,RP.PROJECT_NAME,PP.name as PROJECT_PHASE,
                PS.name as PROJECT_STAGE,ct.LABEL AS CITY_NAME, psm.project_status as 
                    PROJECT_STATUS,rpp.phase_type,rpp.booking_status_id,
                L.LABEL LOCALITY, PSH.DATE_TIME, PA.FNAME, UC.LABEL UPDATION_LABEL
                 FROM
                    resi_project RP
                 LEFT JOIN
                    updation_cycle UC ON RP.UPDATION_CYCLE_ID=UC.UPDATION_CYCLE_ID
                 LEFT JOIN
                     locality L ON RP.LOCALITY_ID = L.LOCALITY_ID
                 LEFT JOIN
                     city ct ON sub.CITY_ID = ct.CITY_ID    
                 LEFT JOIN
                     project_stage_history PSH ON RP.MOVEMENT_HISTORY_ID = PSH.HISTORY_ID
                 LEFT JOIN 
                     proptiger_admin PA ON PSH.ADMIN_ID = PA.ADMINID
                 INNER JOIN 
                     resi_builder RB on RP.BUILDER_ID = RB.BUILDER_ID
                 INNER JOIN
                     master_project_stages PS ON RP.project_stage_id = PS.id
                 INNER JOIN
                     master_project_phases PP on RP.project_phase_id = PP.id
                 INNER JOIN
                    project_status_master psm on RP.PROJECT_STATUS_ID = psm.id
                  INNER JOIN
                    resi_project_phase rpp on RP.PROJECT_ID = rpp.PROJECT_ID ";

    $and = " WHERE RP.version='Cms' and ";

    if($_POST['dwnld_projectId'] == '')
    {
        if($_REQUEST['dwnld_Availability'] != '')
        {
            $arrAvalibality = explode(",",$_REQUEST['dwnld_Availability']); 
            $QueryMember .= $and ." (1 = 0 ";
            if(in_array(1,$arrAvalibality))
            {
                    $QueryMember .=  " OR RP.D_AVAILABILITY = 0";
            }
            if(in_array(2,$arrAvalibality))
            {
                    $QueryMember .=  " OR RP.D_AVAILABILITY > 0";
            }
            if(in_array(3,$arrAvalibality))
            {
                    $QueryMember .=  " OR RP.D_AVAILABILITY IS NULL ";
            }
            $QueryMember .= ")";
            $and  = ' AND ';
        }

        if($_POST['dwnld_project_name'] != '')
        {
            $QueryMember .= $and." RP.PROJECT_NAME LIKE '%".$_POST['dwnld_project_name']."%'";
            $and  = ' AND ';
        }
        if($_POST['dwnld_Residential'] != '')
        {
            $QueryMember .=  $and." RP.RESIDENTIAL_FLAG = '".$_POST['dwnld_Residential']."'";
            $and  = ' AND ';
        }

        if($ActiveValue != '')
        {
			$ActiveValue = explode(",",$ActiveValue);
			$ActiveValue = implode("','",$ActiveValue);
		    $QueryMember .=  $and." RP.STATUS IN('".$ActiveValue."')";
            $and  = ' AND ';
        }

        if($StatusValue != '')
        {
            $QueryMember .=  $and." RP.PROJECT_STATUS_ID IN(".$StatusValue.")";
            $and  = ' AND ';
        }

        if($_POST['dwnld_locality'] != '')
        {
            $QueryMember .= $and." RP.LOCALITY_ID = '".$_POST['dwnld_locality']."'";
            $and  = ' AND ';
        }
        if($_POST['dwnld_city'] != '')
        {
            $QueryMember .= $and." sub.CITY_ID in (".$city.")";
            $and  = ' AND ';
        }
        if($_POST['dwnld_builder'] != '')
        {
            $QueryMember .= $and." RP.BUILDER_ID = '".$_POST['dwnld_builder']."'";
            $and  = ' AND ';
        }
        if($_POST['current_dwnld_phase'] != '')
        {
            $getProjectStage = ProjectStage::getStageByName($_POST['current_dwnld_phase']);
            $QueryMember .= $and." RP.PROJECT_STAGE_ID = '".$getProjectStage[0]->id."'";
            $and  = ' AND ';
        }
        if($stage != '')
        {
            $getProjectPhase = ProjectPhase::getPhaseByName($stage);
            $QueryMember .= $and." RP.PROJECT_PHASE_ID = '".$getProjectPhase[0]->id."'";
            $and  = ' AND ';
        }
        if($updation_cycle != '')
        {
            $QueryMember .= $and." RP.UPDATION_CYCLE_ID = '".$updation_cycle."'";
            $and  = ' AND ';
        }
    }
    else
    {
        $QueryMember .= $and. " RP.PROJECT_ID IN (".$_POST['dwnld_projectId'].")";
    }
}
$arrPropId = array();
$QueryMember1 = $QueryMember1 . $QueryMember." Group By rpp.PROJECT_ID";

$QueryExecute = mysql_query($QueryMember1) or die(mysql_error());
$NumRows = mysql_num_rows($QueryExecute);

$contents = "";

$contents .= "<table cellspacing=1 bgcolor='#c3c3c3' cellpadding=0 width='100%' style='font-size:11px;font-family:tahoma,arial,verdana;vertical-align:middle;text-align:center;'>
<tr bgcolor='#f2f2f2'>
<td>SNO</td>
<td>PROJECT ID</td>
<td>BUILDER NAME</td>
<td>PROJECT NAME</td>
<td>CITY</td>
<td>LOCALITY</td>
<td>PROJECT STATUS</td>
<td>ASSIGNED DEPARTMENT</td>
<td>BOOKING STATUS</td>
<td>PHASE</td>
<td>STAGE</td>
<td>STAGE MOVEMENT DATE</td>
<td>STAGE MOVEMENT DONE BY</td>
<td>UPDATION LABEL</td></tr>
";
$cnt = 1;
while($ob1 = mysql_fetch_assoc($QueryExecute))
{
	$stage = $ob1['PROJECT_STAGE'];
	$phase = $ob1['PROJECT_PHASE'];
	$builder = $ob1['BUILDER_NAME'];

	$projid = $ob1['PROJECT_ID'];
	$projname = $ob1['PROJECT_NAME'];
	$currentCycle = currentCycleOfProject($projid,$ob1['PROJECT_PHASE'],$ob1['PROJECT_STAGE']);
	$cityname = $ob1['CITY_NAME'];
        $date_time = $ob1['DATE_TIME'];
        $stage_move_by = $ob1['FNAME'];
        $localityname = $ob1['LOCALITY'];
	
	$proj_status = $ob1['PROJECT_STATUS'];
	
	$booking_status = ($ob1['phase_type'] == 'Logical')?$ob1['booking_status_id']:fetch_project_booking_status($projid);
	
	if ($booking_status > 0){
			if ($booking_status == 1) $booking_status = "Available";
			if ($booking_status == 2) $booking_status = "Sold out";
			if ($booking_status == 3) $booking_status = "On Hold";
	}
	else
		$booking_status = "-";
		
	$updation_label = $ob1['UPDATION_LABEL'];
	if($phase == 'NewProject') $phse = 'NewProject Audit';
        else $phse = $phase;
	$contents .= "
	<tr bgcolor='#f2f2f2'>
	<td>".$cnt."</td>
	<td>".$projid."</td>
	<td>".$builder."</td>
	<td>".$projname."</td>
        <td>".$cityname."</td>
        <td>".$localityname."</td>    
	<td>".$proj_status."</td>
        <td>".$currentCycle."</td>  
	<td>".$booking_status."</td>
	<td>".$stage."</td>
        <td>".$phse."</td>
	      <td>".$date_time."</td>
        <td>".$stage_move_by."</td>            
	<td>".$updation_label."</td>

	</tr>
";
	$cnt++;

}

$contents .= "</table>";
//echo $contents; exit;
$filename ="excelreport-".date('YmdHis').".xls";
header('Content-type: application/ms-excel');
header('Content-Disposition: attachment; filename='.$filename);
echo $contents;

?>
