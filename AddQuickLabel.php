<?php
	error_reporting(1);
	ini_set('display_errors','1');
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
	AdminAuthentication();
	
	if($_POST['label_txtbox']!='' && $_POST['submit']=='Save'){
		echo $QueryMember = "INSERT INTO updation_cycle (TIME_STAMP, LABEL, CYCLE_TYPE) values (now(), '".$_POST['label_txtbox']."', '".$_POST['cycleType']."')";
		$QueryExecute 	= mysql_query($QueryMember) or die(mysql_error());
		header("location:AddQuickLabel.php?m=1");
		exit;
	}
	
	$labelDataArr	=	array();
 	
 	$qry	=	"SELECT TIME_STAMP,LABEL,CYCLE_TYPE FROM updation_cycle ORDER BY TIME_STAMP ASC";
 	$res = mysql_query($qry,$db);
 	
 	while($data	=	mysql_fetch_array($res))
 	{
 		$labelDataArr[]	=	array($data['TIME_STAMP'],$data['LABEL'],$data['CYCLE_TYPE']);		
 	}
?>
	
<script type="text/javascript" src="js/jquery.js"></script>

<script>

function GetXmlHttpObject()
{
	var xmlHttp=null;
	try
	{
		// Firefox, Opera 8.0+, Safari
		xmlHttp=new XMLHttpRequest();
	}
	catch (e)
	{
		//Internet Explorer
	try
	{
		xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch (e)
	{
		xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
}
return xmlHttp;
}
</script>


<script>
function addlabel()
{
	label = $("#label_txtbox").val();
	if(label == '')
	{
		alert("Please enter the label");
		return false;
	}
	return true;
}
</script>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Label Management</title>
<link href="<?php echo FORUM_SERVER_PATH?>/css/css.css" rel="stylesheet" type="text/css">
</head>
<body>

<TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
<TR>
<TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
<TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
<TR>
<TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>Add Quick Label</TD>
<TD align=right colSpan=3></TD>
</TR>
</TBODY></TABLE>
</TD>
</TR>
<TR>
<TD vAlign=top align=middle class="backgorund-rt" height=250><BR>
<table width="70%" border="0" align="centercityval" cellpadding="0" cellspacing="0" style="border:1px solid #c2c2c2;">
<td align='center'>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<form name="frm" method="post" onSubmit="return addlabel();">
<?php if ($_GET["m"] == '1') {?>
<tr><td height="15px" colspan="3"></td></tr>
<tr>
<td align="center" colspan="3">
	<font color = 'red'>The records has been save successfully.</font></td>
</tr>
<?php } ?>
<tr><td height="15px" colspan="3"></td></tr>
<tr>
<td  height="25" align="right" style="padding-right:10px;">
<b>Add Label</b></td>
	<td height="25" align="left" >
		<div id="maincity_txtbox">
			<input type="text" name="label_txtbox" id="label_txtbox" maxLength="15" style='border:1px solid #333;padding:2px;height:28px;background:#c2c2c2;text-decoration:none;font-weight:bold;color:#fff;'>
		&nbsp;&nbsp;
                <select name ="cycleType" id ="cycleType">
                    <option value ="updation">Updation Cycle</option>
                    <option value ="secondaryPrice">Secondary Price</option>
                </select>
                &nbsp;&nbsp;
                <input type='submit' value='Save' name='submit' style='border:1px solid #333;padding:5px;background:#c2c2c2;text-decoration:none;font-weight:bold;color:#fff;'>
		</div>
	</td>
</tr>
<tr><td height="15px" colspan="3"></td></tr>
</form>
</table>
</td>
</tr>
</table>
<br>
<table cellspacing=1 bgcolor='#f2f2f2' border=0 width="90%">
<tr><td align='center' height='28' valign='middle' colspan='4'><b>Label Management</b></td></tr>
<tr bgcolor='#ffffff'>
    <td height='30' align="center"><b>SNo.</b></td><td align="center"><b>Label</b></td>
    <td align="center"><b>Cycle Type</b></td>
    <td align="center"><b>Date</b></td></tr>
<?php 
$i = 1;
foreach($labelDataArr as $k=>$valArr)
{
	echo "<tr bgcolor='#ffffff'><td height='30' align='center'>".$i."</td><td align='center'>".$valArr[1]."</td><td align='center'>".ucwords($valArr[2])."</td><td align='center'>".$valArr[0]."</td></tr>";
	$i++;
}

?>
</table>
</td>
</tr>
</table>

</body></html>

