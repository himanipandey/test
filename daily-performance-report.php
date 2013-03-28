<?php
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	date_default_timezone_set('Asia/Kolkata');
	include("builder_function.php"); 
	AdminAuthentication();
	$dept = $_SESSION['DEPARTMENT'];
	
	$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");
?>
<script>
function generate_excel()
{
	/*
	var user		= $('#user').val();
	var frmdate		= $('#frmdate').val();
	var todate		= $('#todate').val();	
	?user="+user+"&frmdate="+frmdate+"&todate="+todate
	*/
	
	document.frm.action = "ajax/download-daily-report.php";
	document.frm.submit();
	
	document.frm.action = 'daily-performance-report.php'
}
</script>
 </TR>
  <TR>
    <TD class="white-bg paddingright10" vAlign=top align=middle bgColor=#ffffff>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><TBODY>
        <TR>
          <TD width=224 height=25>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=866>&nbsp;</TD>
		</TR>
        <TR>
          <TD class=paddingltrt10 vAlign=top align=middle bgColor=#ffffff>
	   		<?php $smarty->display(PROJECT_ADD_TEMPLATE_PATH."left.tpl");?>
		  </TD>
          <TD vAlign=top align=middle width="100%" bgColor=#FFFFFF height=400>
<?php	
$arr = array();
$fromdateymd = $fromdate = $_POST['frmdate']!='' ? $_POST['frmdate'] : date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
$todateymd  = $todate   = $_POST['todate']!='' ? $_POST['todate'] : date("Y-m-d",mktime(0, 0, 0, date("m"), date("d"), date("Y")));

$dateArr = getDatesBetweeenTwoDates($fromdate,$todate);

foreach($dateArr as $key=>$dates)
{
	$df = date('d',strtotime($dates));
	$mf = date('m',strtotime($dates));
	$Yf = date('Y',strtotime($dates));	
	
	$fromdate = date('Y-m-d H:i:s',mktime(0,0, 0, $mf, $df-1, $Yf));
	$todate = date('Y-m-d H:i:s',mktime(0,0, 0, $mf, $df, $Yf));
	$seldate = date('Y-m-d',mktime(0,0, 0, $mf, $df, $Yf));
	
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
	$q = '';
	$q = "SELECT 
					COUNT(1) cnt,PROJECT_PHASE,PROJECT_STAGE,ADMIN_ID,'".$seldate."' as DT
				FROM 
					project_stage_history A LEFT JOIN proptiger_admin B ON A.ADMIN_ID=B.ADMINID 
				".$quryand."
				GROUP BY 
					PROJECT_PHASE,PROJECT_STAGE,ADMIN_ID ORDER BY DATE_TIME DESC,ADMIN_ID";
	$res = mysql_query($q) or die(mysql_error().'Issue with Qry or Db');

	while($ob1 = mysql_fetch_assoc($res))
	{
		$ex	= $ob1['ADMIN_ID'];
		$dt = $ob1['DT'];
		$phase = $ob1['PROJECT_PHASE'];
		$stage = $ob1['PROJECT_STAGE'];
		
		$arr[$dt][$ex][$stage][$phase] += $ob1['cnt'];	
	}
}
//echo "<pre>";print_r($arr);
#---------------------------------------
#ksort($arr);
$data = array();
$option1 = $option2 = "<option value='' selected>Select Date</option>";

for($i=0;$i<=60;$i++)
{
	$dtval = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")-$i, date("Y")));
	$dtshow = date("d-m-Y",mktime(0, 0, 0, date("m"), date("d")-$i, date("Y")));
	
	$option1 .= "<option value='".$dtval."' ";
	if($fromdateymd == $dtval) $option1 .= " selected ";
	$option1 .= ">".$dtshow."</option>";
	
	$option2 .= "<option value='".$dtval."' ";
	if($todateymd == $dtval) $option2 .= " selected ";	
	$option2 .= ">".$dtshow."</option>";
}
$optionadmin = "<option value='' selected>Select User</option>";
$q = "SELECT FNAME,ADMINID FROM proptiger_admin WHERE STATUS='Y'";
$r = mysql_query($q);

while($obj = mysql_fetch_assoc($r))
{
	if($obj['FNAME'] != '')
	{
		$optionadmin .= "<option value='".$obj['ADMINID']."' ";
		if($_POST['user']==$obj['ADMINID']) $optionadmin .= " selected ";
		$optionadmin .= ">".$obj['FNAME']."</option>";
		$ex = $obj['ADMINID'];
		$arrAdmin[$ex] = $obj['FNAME'];
	}
}
$arrAdmin = array_unique($arrAdmin);
$html = "
<form name='frm' method='post' action='daily-performance-report.php'><div style='width:800px;'>Select User:&nbsp;<select name='user' id='user' style='width:120px;'>".$optionadmin."</select>&nbsp;&nbsp;From Date: <select name='frmdate' id='frmdate' style='width:120px;'>".$option1."</select>&nbsp;&nbsp;To Date:&nbsp;<select name='todate' id='todate' style='width:120px;' >".$option2."</select>&nbsp;&nbsp;<a href='javascript:void(0);' onClick='document.frm.submit();' style='padding:3px;text-decoration:none;border:1px solid #c2c2c2;background:#f2f2f2;'>Search</a>&nbsp;<a href='javascript:void(0);' onClick='generate_excel();return false;' style='padding:3px;text-decoration:none;border:1px solid #c2c2c2;background:#f2f2f2;' name='excel' id='excel'>Generate Excel</a></div></form>
<br><br>
<table cellpadding=0 cellspacing=1 border=0 bgcolor=#c2c2c2 width=1000 style='font-family:tahoma,arial;font-size:11px;color:#333333;'>
	<tr bgcolor='#f2f2f2'><td  height=25 colspan='14' align=center><b>Daily Performance Report</b></td></tr>
	<tr  bgcolor='#f2f2f2'>
		<td align='center' width='3%' height=25><b>SNo</b></td>		
		<td align='center' width='10%'><b>Executive</b></td>
		<td align='center' width='45%' colspan='6'><b>New Project</td>
		<td align='center' width='30%' colspan='5'><b>Updation Cycle</b></td>
		<td align='center' width='15%'><b>Date</b></td>		
	</tr>
	<tr  bgcolor='#f2f2f2'>
		<td align='center'  height=25>&nbsp;</td>		
		<td align='center'  height=25>&nbsp;</td>		
		<td align='center'><b>Data Collection</td>
		<td align='center' nowrap><b>Data Collection CallCenter</td>
		<td align='center'><b>New Project Audit</b></td>
		<td align='center' ><b>Audit-1</b></td>
		<td align='center' ><b>Audit-2</b></td>
		<td align='center' ><b>Completed</b></td>		
		<td align='center' ><b>Revert</b></td>		
		<td align='center'><b>Data Collection</td>		
		<td align='center' ><b>Audit-1</b></td>
		<td align='center' ><b>Audit-2</b></td>
		<td align='center' ><b>Completed</b></td>
		<td align='center' ><b>Revert</b></td>
		<td align='center' ><b>&nbsp;</b></td>
	</tr>
	";
$cnt = 1;
foreach($arr as $date=>$userdetails)
{
	foreach($userdetails as $user=>$projectArr)
	{
		if ($cnt%2 != 0) 
			$html .= "<tr  bgcolor='#ffffff'>";
		else
			$html .= "<tr  bgcolor='#f2f2f2'>";

		$html .="			
			<td align='center' height=25><b>".$cnt."</b></td>		
			<td align='center' nowrap><b>".($arrAdmin[$user]!='' ? $arrAdmin[$user] : 'No User')."</b></td>			
			<td align='center' ><b>".$projectArr['newProject']['dataCollection']."</td>
				<td align='center' ><b>".$projectArr['newProject']['dcCallCenter']."</td>
			<td align='center' ><b>".$projectArr['newProject']['newProject']."</td>
			<td align='center' ><b>".$projectArr['newProject']['audit1']."</td>
			<td align='center' ><b>".$projectArr['newProject']['audit2']."</td>
			<td align='center' ><b>".$projectArr['newProject']['complete']."</td>
			<td align='center' ><b>".$projectArr['newProject']['revert']."</td>
			<td align='center' ><b>".$projectArr['updationCycle']['dataCollection']."</td>			
			<td align='center' ><b>".$projectArr['updationCycle']['audit1']."</td>
			<td align='center' ><b>".$projectArr['updationCycle']['audit2']."</td>
			<td align='center' ><b>".$projectArr['updationCycle']['complete']."</td>
			<td align='center' ><b>".$projectArr['updationCycle']['revert']."</td>
			<td align='center'><b>".$date."</b></td>		
		";
		
		$html .= '</tr>';
		$cnt++;
	}
}
$html .= "</table>";
echo  $html;
?>
 </td>
		  </tr>
		</TABLE>
	      </TD>
            </TR>
          </TBODY></TABLE>
        </TD>
      </TR>
    </TBODY></TABLE>
  </TD>
</TR>
<?php 
$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
?>
