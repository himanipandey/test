<?php
include("dbConfig.php");
include("appWideConfig.php");
include("includes/function.php");
include("builder_function.php");
date_default_timezone_set('Asia/Kolkata');

$dept = $_SESSION['DEPARTMENT'];
//echo "<pre>";
//print_r($_POST);

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

$transfer 		= 	$_POST['dwnld_transfer'];
$search 		= 	$_POST['dwnld_search'];
$city		  	=	$_POST['dwnld_city'];
$locality	 	=	$_POST['dwnld_locality'];
$builder		=	$_POST['dwnld_builder'];
$phase 			= 	$_POST['current_dwnld_phase'];
$arrPhaseTag 		= 	explode('|',$_POST['dwnld_stage']);
$stage 			= 	$_POST['current_dwnld_stage'];
$tag 			= 	$arrPhaseTag[1];
$Status 		= 	$_POST['dwnld_Status'];
$Active 		= 	$_POST['dwnld_Active'];
$selectdata		= 	$_POST['dwnld_selectdata'];


if($search != '' OR $transfer != '' OR $_POST['dwnld_projectId'] != '')
{

	$QueryMember1 = "Select PROJECT_ID,BUILDER_NAME,PROJECT_NAME,PROJECT_PHASE,PROJECT_STAGE FROM resi_project  ";

	$and = " WHERE ";

	if($_POST['dwnld_projectId'] == '')
	{
		if($_REQUEST['dwnld_Availability'] != '')
		{
			$arrAvalibality = explode(",",$_REQUEST['dwnld_Availability']); 
			$QueryMember .= $and ." (1 = 0 ";
			if(in_array(0,$arrAvalibality))
			{
				$QueryMember .=  " OR AVAILABLE_NO_FLATS = 0";
			}
			if(in_array(1,$arrAvalibality))
			{
				$QueryMember .=  " OR AVAILABLE_NO_FLATS > 0";
			}
			if(in_array(2,$arrAvalibality))
			{
				$QueryMember .=  " OR AVAILABLE_NO_FLATS IS NULL ";
			}
			$QueryMember .= ")";
			$and  = ' AND ';
		}
		
		if($_POST['dwnld_project_name'] != '')
		{
			$QueryMember .= $and." PROJECT_NAME LIKE '%".$_POST['dwnld_project_name']."%'";
			$and  = ' AND ';
		}
		if($_POST['dwnld_city'] != '')
		{
			$QueryMember .=  $and." CITY_ID = '".$_POST['dwnld_city']."'";
			$and  = ' AND ';
		}
		if($_POST['dwnld_Residential'] != '')
		{
			$QueryMember .=  $and." RESIDENTIAL = '".$_POST['dwnld_Residential']."'";
			$and  = ' AND ';
		}

		if($ActiveValue != '')
		{
			$QueryMember .=  $and." ACTIVE IN(".$ActiveValue.")";
			$and  = ' AND ';
		}

		if($StatusValue != '')
		{
			$QueryMember .=  $and." PROJECT_STATUS IN(".$StatusValue.")";
			$and  = ' AND ';
		}

		if($_POST['dwnld_locality'] != '')
		{
			$QueryMember .= $and." LOCALITY_ID = '".$_POST['dwnld_locality']."'";
			$and  = ' AND ';
		}
		if($_POST['dwnld_builder'] != '')
		{
			$QueryMember .= $and." BUILDER_ID = '".$_POST['dwnld_builder']."'";
			$and  = ' AND ';
		}
		if($_POST['current_dwnld_phase'] != '')
		{
			$QueryMember .= $and." PROJECT_STAGE = '".$_POST['current_dwnld_phase']."'";
			$and  = ' AND ';
		}
		if($stage != '')
		{
			$QueryMember .= $and." PROJECT_PHASE = '".$stage."'";
			$and  = ' AND ';
		}
		if($tag != '')
		{
			$QueryMember .= $and." UPDATION_CYCLE_ID = '".$tag."'";
			$and  = ' AND ';
		}
	}
	else
	{
		$QueryMember .= $and. " PROJECT_ID IN (".$_POST['dwnld_projectId'].")";

	}
}

$arrPropId = array();
$QueryMember1 = $QueryMember1 . $QueryMember;

$QueryExecute 	= mysql_query($QueryMember1) or die(mysql_error());
$NumRows 		= mysql_num_rows($QueryExecute);


$contents = "";

$contents .= "<table cellspacing=1 bgcolor='#c3c3c3' cellpadding=0 width='100%' style='font-size:11px;font-family:tahoma,arial,verdana;vertical-align:middle;text-align:center;'>
<tr bgcolor='#f2f2f2'>
<td>SNO</td>
<td>PROJECT ID</td>
<td>BUILDER NAME</td>
<td>PROJECT NAME</td>
<td>PHASE</td>
<td>STAGE</td>
</tr>
";
$cnt = 1;
while($ob1 = mysql_fetch_assoc($QueryExecute))
{
	$stage = $ob1['PROJECT_PHASE'];
	$phase = $ob1['PROJECT_STAGE'];
	$builder = $ob1['BUILDER_NAME'];

	$projid = $ob1['PROJECT_ID'];
	$projname = $ob1['PROJECT_NAME'];

	$contents .= "
	<tr bgcolor='#f2f2f2'>
	<td>".$cnt."</td>
	<td>".$projid."</td>
	<td>".$builder."</td>
	<td>".$projname."</td>
	<td>".$phase."</td>
	<td>".$stage."</td>
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