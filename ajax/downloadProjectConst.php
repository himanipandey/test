<?php
include("../smartyConfig.php");
include("../dbConfig.php");
include("../modelsConfig.php");
include("../appWideConfig.php");
include("../builder_function.php");
require_once("../common/function.php");
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
$pasAnd = "";
$pasField = "";
//echo "<pre>";print_r($_REQUEST);
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
//echo "<pre>";print_r($_REQUEST);
if($search != '' OR $transfer != '' OR $_POST['dwnld_projectId'] != '')
{

    $QueryMember1 = "SELECT p.PROJECT_ID,pas.source,pas.creation_time,pa1.fname,rb.BUILDER_NAME,p.PROJECT_NAME,city.LABEL AS CITY_NAME, psm.project_status as 
                    PROJECT_STATUS,
                locality.LABEL LOCALITY, pas.EXECUTIVE_REMARK, pas.STATUS as ASSIGN_STATUS,uc.LABEL as ASSIGNMENT_CYCLE
                FROM resi_project p 
                left join locality on p.locality_id = locality.locality_id
                left join suburb on locality.suburb_id = suburb.suburb_id
                left join city on suburb.city_id = city.city_id
                left join project_status_master psm on p.project_status_id = psm.id
                left join resi_builder rb on p.builder_id = rb.builder_id
                left join process_assignment_system pas on p.project_id = pas.project_id
                left join proptiger_admin  pa1 on pas.assigned_to = pa1.adminid
                left join updation_cycle uc on pas.updation_cycle_id = uc.updation_cycle_id";

    $and = " WHERE p.version='Cms' and ";

    if($_POST['dwnld_projectId'] == '')
    {
       

        if($_POST['dwnld_project_name'] != '')
        {
            $QueryMember .= $and." p.PROJECT_NAME LIKE '%".$_POST['dwnld_project_name']."%'";
            $and  = ' AND ';
        }
       
        if($ActiveValue != '')
        {
            $ActiveValue = explode(",",$ActiveValue);
            $ActiveValue = implode("','",$ActiveValue);
            $QueryMember .=  $and." p.STATUS IN('".$ActiveValue."')";
            $and  = ' AND ';
        }

        if($StatusValue != '')
        {
            $QueryMember .=  $and." p.PROJECT_STATUS_ID IN(".$StatusValue.")";
            $and  = ' AND ';
        }

        if($_POST['dwnld_locality'] != '')
        {
            $QueryMember .= $and." p.LOCALITY_ID = '".$_POST['dwnld_locality']."'";
            $and  = ' AND ';
        }
        if($_POST['dwnld_assignRemark'] != '')
        {
            $QueryMember .= $and." pas.EXECUTIVE_REMARK = '".$_POST['dwnld_assignRemark']."'";
            $and  = ' AND ';
        }
        if($_POST['dwnld_assignStatus'] != '')
        {
            $QueryMember .= $and." pas.STATUS = '".$_POST['dwnld_assignStatus']."'";
            $and  = ' AND ';
        }
        if($_POST['dwnld_assignCycle'] != '')
        {
            $QueryMember .= $and." uc.updation_cycle_id = '".$_POST['dwnld_assignCycle']."'";
            $and  = ' AND ';
        }
        if($_POST['dwnld_city'] != '')
        {
            $QueryMember .= $and." suburb.CITY_ID in (".$city.")";
            $and  = ' AND ';
        }
        if($_POST['dwnld_builder'] != '')
        {
            $QueryMember .= $and." p.BUILDER_ID = '".$_POST['dwnld_builder']."'";
            $and  = ' AND ';
        }
    }
    else
    {
        $QueryMember .= $and. " p.PROJECT_ID IN (".$_POST['dwnld_projectId'].")";
    }
}
$arrPropId = array();
$QueryMember1 = $QueryMember1 . $QueryMember."  and p.status in ('Active' , 'ActiveInCms')";//die;

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
<td>ASSIGNMENT REMARK</td>
<td>ASSIGNMENT STATUS</td>
<td>ASSIGNMENT CYCLE</td>
<td>SOURCE</td>
<td>ASSIGN TO</td>
<td>ASSIGNED ON</td>
</tr>
";
$cnt = 1;
while($ob1 = mysql_fetch_assoc($QueryExecute))
{
	$builder = $ob1['BUILDER_NAME'];

	$projid = $ob1['PROJECT_ID'];
	$projname = $ob1['PROJECT_NAME'];
	$cityname = $ob1['CITY_NAME'];
        $stage_move_by = $ob1['FNAME'];
        $localityname = $ob1['LOCALITY'];
	
	$proj_status = $ob1['PROJECT_STATUS'];
		
	$updation_label = $ob1['UPDATION_LABEL'];
      //  if(isset($ob1['EXECUTIVE_REMARK']))
            $executive_remark = $ob1['EXECUTIVE_REMARK'];
        //if(isset($ob1['EXECUTIVE_REMARK']))
            $status = $ob1['ASSIGN_STATUS'];
            $statusCycle = $ob1['ASSIGNMENT_CYCLE'];
            
        //else
          //  $status = '';
        if($ob1['source'] == '')
            $ob1['source'] = 'NA';
        if($ob1['fname'] == '')
            $ob1['fname'] = 'NA';
        if($executive_remark == '')
            $executive_remark = 'NA';
        if($status == '')
            $status = 'NA';
	$contents .= "
	<tr bgcolor='#f2f2f2'>
	<td>".$cnt."</td>
	<td>".$projid."</td>
	<td>".$builder."</td>
	<td>".$projname."</td>
        <td>".$cityname."</td>
        <td>".$localityname."</td>    
	<td>".$proj_status."</td>
        <td>".$executive_remark."</td>    
	<td>".$status."</td>       
        <td>".$statusCycle."</td>
        <td>".$ob1['source']."</td>    
	<td>".$ob1['fname']."</td>
        <td>".$ob1['creation_time']."</td> 
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
