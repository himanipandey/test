<?php
include("../dbConfig.php");
include("../modelsConfig.php");
include("../appWideConfig.php");
include("../builder_function.php");
date_default_timezone_set('Asia/Kolkata');

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
$locality = $_POST['dwnld_locality'];
$builder = $_POST['dwnld_builder'];
$phase = $_POST['current_dwnld_phase'];
$arrPhaseTag = explode('|',$_POST['dwnld_stage']);
$stage = $_POST['current_dwnld_stage'];
$tag = $arrPhaseTag[1];
$Status = $_POST['dwnld_Status'];
$Active = $_POST['dwnld_Active'];
$selectdata = $_POST['dwnld_selectdata'];

if($search != '' OR $transfer != '' OR $_POST['dwnld_projectId'] != '')
{

    $QueryMember1 = "SELECT RP.PROJECT_ID,RB.BUILDER_NAME,RP.PROJECT_NAME,PP.name as PROJECT_PHASE,
                PS.name as PROJECT_STAGE,ct.LABEL AS CITY_NAME, psm.project_status as 
                    PROJECT_STATUS,
                L.LABEL LOCALITY, PSH.DATE_TIME, PA.FNAME, UC.LABEL UPDATION_LABEL
                 FROM
                    resi_project RP
                 LEFT JOIN
                    updation_cycle UC ON RP.UPDATION_CYCLE_ID=UC.UPDATION_CYCLE_ID
                 LEFT JOIN
                     locality L ON RP.LOCALITY_ID = L.LOCALITY_ID
                 INNER JOIN
                     suburb sub ON L.SUBURB_ID = sub.SUBURB_ID
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
                    project_status_master psm on RP.PROJECT_STATUS_ID = psm.id";

    $and = " WHERE ";

    if($_POST['dwnld_projectId'] == '')
    {
       /* if($_REQUEST['dwnld_Availability'] != '')
        {
            $arrAvalibality = explode(",",$_REQUEST['dwnld_Availability']); 
            $QueryMember .= $and ." (1 = 0 ";
            if(in_array(0,$arrAvalibality))
            {
                    $QueryMember .=  " OR RP.AVAILABLE_NO_FLATS = 0";
            }
            if(in_array(1,$arrAvalibality))
            {
                    $QueryMember .=  " OR RP.AVAILABLE_NO_FLATS > 0";
            }
            if(in_array(2,$arrAvalibality))
            {
                    $QueryMember .=  " OR RP.AVAILABLE_NO_FLATS IS NULL ";
            }
            $QueryMember .= ")";
            $and  = ' AND ';
        }*/

        if($_POST['dwnld_project_name'] != '')
        {
            $QueryMember .= $and." RP.PROJECT_NAME LIKE '%".$_POST['dwnld_project_name']."%'";
            $and  = ' AND ';
        }
        if($_POST['dwnld_Residential'] != '')
        {
            $QueryMember .=  $and." RP.RESIDENTIAL = '".$_POST['dwnld_Residential']."'";
            $and  = ' AND ';
        }

        if($ActiveValue != '')
        {
            $QueryMember .=  $and." RP.STATUS IN(".$ActiveValue.")";
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
            $QueryMember .= $and." sub.CITY_ID = '".$_POST['dwnld_city']."'";
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
        if($tag != '')
        {
            $QueryMember .= $and." RP.UPDATION_CYCLE_ID = '".$tag."'";
            $and  = ' AND ';
        }
    }
    else
    {
        $QueryMember .= $and. " RP.PROJECT_ID IN (".$_POST['dwnld_projectId'].")";
    }
}
$arrPropId = array();
$QueryMember1 = $QueryMember1 . $QueryMember;

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
<td>BOOKING STATUS</td>
<td>STAGE</td>
<td>PHASE</td>
<td>STAGE MOVEMENT DATE</td>
<td>STAGE MOVEMENT DONE BY</td>
<td>UPDATION LABEL</td></tr>
";
$cnt = 1;
while($ob1 = mysql_fetch_assoc($QueryExecute))
{
	$stage = $ob1['PROJECT_PHASE'];
	$phase = $ob1['PROJECT_STAGE'];
	$builder = $ob1['BUILDER_NAME'];

	$projid = $ob1['PROJECT_ID'];
	$projname = $ob1['PROJECT_NAME'];
	
	$cityname = $ob1['CITY_NAME'];
        $date_time = $ob1['DATE_TIME'];
        $stage_move_by = $ob1['FNAME'];
        $localityname = $ob1['LOCALITY'];
	
	$proj_status = $ob1['PROJECT_STATUS'];
	$booking_status = $ob1['BOOKING_STATUS'];
	$updation_label = $ob1['UPDATION_LABEL'];
	
	$contents .= "
	<tr bgcolor='#f2f2f2'>
	<td>".$cnt."</td>
	<td>".$projid."</td>
	<td>".$builder."</td>
	<td>".$projname."</td>
        <td>".$cityname."</td>
        <td>".$localityname."</td>    
	<td>".$proj_status."</td>
	<td>".$booking_status."</td>
        <td>".$phase."</td>
	<td>".$stage."</td>
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