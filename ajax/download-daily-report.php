<?php
include("../dbConfig.php");
include("../appWideConfig.php");
include("../builder_function.php");

$arr = array();
$fromdateymd = $fromdate = $_POST['frmdate']!='' ? $_POST['frmdate'] : date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
$todateymd  = $todate   = $_POST['todate']!='' ? $_POST['todate'] : date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"), date("Y")));

$dateArr = getDatesBetweeenTwoDates($fromdate,$todate);

foreach($dateArr as $key=>$dates)
{
	$df = date('d',strtotime($dates));
	$mf = date('m',strtotime($dates));
	$Yf = date('Y',strtotime($dates));

	$fromdate = date('Y-m-d H:i:s',mktime(12,30, 0, $mf, $df-1, $Yf));
	$todate = date('Y-m-d H:i:s',mktime(12,30, 0, $mf, $df, $Yf));
	$seldate = date('Y-m-d',mktime(12,30, 0, $mf, $df, $Yf));

	$quryand = $and = '';
	$and = ' WHERE ';
	if($fromdate!='')
	{
		$quryand .= $and." DATE_TIME>='".$fromdate."'";
		$and = ' AND ';
	}

	if($todate!='')
	{
		$quryand .= $and." DATE_TIME<='".$todate."'";
		$and = ' AND ';
	}

	if($_POST['user']!='')
	{
		$quryand .= $and." ADMIN_ID='".$_POST['user']."'";
		$and = ' AND ';
	}

	if($todate == '' && $fromdate == '')
	{
		$quryand .= $and." DATE_TIME>='".$fromdate."' AND DATE_TIME<='".$todate."'";
		$and = ' AND ';
	}

	#---------------------------------------
	$q = "SELECT
				A.PROJECT_ID,C.PROJECT_NAME,A.PROJECT_PHASE,A.PROJECT_STAGE,B.FNAME,C.PROJECT_STATUS,C.BOOKING_STATUS,'".$seldate."' DT,D.LABEL AS CITY_NAME
			FROM
				project_stage_history A 
				LEFT JOIN proptiger_admin B ON A.ADMIN_ID=B.ADMINID
				LEFT JOIN resi_project C ON A.PROJECT_ID=C.PROJECT_ID
				LEFT JOIN city D ON D.CITY_ID=C.CITY_ID 
				".$quryand."
			ORDER BY DATE_TIME ";

	$res = mysql_query($q) or die(mysql_error().'Issue with Qry or Db');

	while($ob1 = mysql_fetch_assoc($res))
	{		
		$ex	= $ob1['ADMIN_ID'];
		$fname = $ob1['FNAME']; 
		$dt = $ob1['DT'];
		$phase = $ob1['PROJECT_PHASE'];
		$stage = $ob1['PROJECT_STAGE'];
		$projectId = $ob1['PROJECT_ID'];
		$projectName = $ob1['PROJECT_NAME'];
		
		$bookingStatus = $ob1['PROJECT_STATUS'];
		$projectStatus = $ob1['BOOKING_STATUS'];
		
		$cityName = $ob1['CITY_NAME'];
		
		
		
		$arr[] = array(
						'PROJECT_ID'=>$projectId,
						'PROJECT_NAME'=>$projectName,
						'BOOKING_STATUS'=>$bookingStatus,
						'PROJECT_STATUS'=>$projectStatus,
						'FNAME'=> $fname,
						'PROJECT_STAGE'=>$stage,
						'PROJECT_PHASE'=>$phase,
						'DT'=>$dt,
						'CITY_NAME'=>$cityName
					);
	}
}


$contents = "";

$contents .= "<table cellspacing=1 bgcolor='#c3c3c3' cellpadding=0 width='100%' style='font-size:11px;font-family:tahoma,arial,verdana;vertical-align:middle;text-align:center;'>
<tr bgcolor='#f2f2f2'>
<td>SNO</td>
<td>DATE</td>
<td>USER</td>
<td>PROJECT ID</td>
<td>PROJECT NAME</td>
<td>BOOKING STATUS</td>
<td>PROJECT STATUS</td>
<td>PHASE</td>
<td>STAGE</td>
<td>CITY</td>
</tr>
";
$cnt = 1;
foreach($arr as $key=>$ob1)
{
	$ex	= $ob1['FNAME'];
	$dt = $ob1['DT'];
	$phase = $ob1['PROJECT_PHASE'];
	$stage = $ob1['PROJECT_STAGE'];
	$projid = $ob1['PROJECT_ID'];
	$projname = $ob1['PROJECT_NAME'];
	
	$bookingStatus = $ob1['BOOKING_STATUS'];
	$projectStatus = $ob1['PROJECT_STATUS'];
	
	$cityName = $ob1['CITY_NAME'];
	
	$contents .= "
	<tr bgcolor='#f2f2f2'>
	<td>".$cnt."</td>
	<td>".$dt."</td>
	<td>".$ex."</td>
	<td>".$projid."</td>
	<td>".$projname."</td>
	<td>".$bookingStatus."</td>
	<td>".$projectStatus."</td>
	<td>".$phase."</td>
	<td>".$stage."</td>	
	<td>".$cityName."</td>
	</tr>
";
	$cnt++;
	
}

$contents .= "</table>";
	
$filename ="excelreport-".date('YmdHis').".xls";
header('Content-type: application/ms-excel');
header('Content-Disposition: attachment; filename='.$filename);
echo $contents;

?>