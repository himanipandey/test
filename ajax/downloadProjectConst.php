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

    $QueryMember1 = "SELECT RP.PROJECT_ID,RB.BUILDER_NAME,RP.PROJECT_NAME,ct.LABEL AS CITY_NAME, psm.project_status as 
                    PROJECT_STATUS,
                L.LABEL LOCALITY
                 FROM
                    resi_project RP
                 
                 LEFT JOIN
                     locality L ON RP.LOCALITY_ID = L.LOCALITY_ID
                 INNER JOIN
                     suburb sub ON L.SUBURB_ID = sub.SUBURB_ID
                 LEFT JOIN
                     city ct ON sub.CITY_ID = ct.CITY_ID    
                 INNER JOIN 
                     resi_builder RB on RP.BUILDER_ID = RB.BUILDER_ID
                 INNER JOIN
                    project_status_master psm on RP.PROJECT_STATUS_ID = psm.id";

    $and = " WHERE RP.version='Cms' and ";

    if($_POST['dwnld_projectId'] == '')
    {
       

        if($_POST['dwnld_project_name'] != '')
        {
            $QueryMember .= $and." RP.PROJECT_NAME LIKE '%".$_POST['dwnld_project_name']."%'";
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
	$contents .= "
	<tr bgcolor='#f2f2f2'>
	<td>".$cnt."</td>
	<td>".$projid."</td>
	<td>".$builder."</td>
	<td>".$projname."</td>
        <td>".$cityname."</td>
        <td>".$localityname."</td>    
	<td>".$proj_status."</td>

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
