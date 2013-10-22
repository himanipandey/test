<?php
set_time_limit(0);
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
AdminAuthentication();

require_once("includes/class_supply.php");
require_once("includes/class_project.php");
require_once("common/start.php");

$projObj= new Project($db_project);
$supObj = new Supply($db_project);

?>

<form name="ChangeStatusForm" action="resale_datahelper.php" method="post" onsubmit="return Blank_TextField_Validator()" style="margin-left: 40%; margin-top:10%">
	<input type="hidden" name='ID' value='<?php echo $_GET['ID'] ?>'/>
	<input type="hidden" name='action' value='changestatus'/>
	<table>
		<tr>
			<td style="font-size: 14px; vertical-align: middle;">Resale Listing Status: </td>
			<td>
				<?php
				$st=$supObj->getStatusByID($_GET['ID']);
				?>
				<select name="Status">
					<option value=1 <?php if($st==1) echo 'selected'?>>Open</option>
					<option value=0 <?php if($st==0) echo 'selected'?>>Closed</option>
				</select>
			</td>
		</tr>
		<tr>
			<td style="font-size: 14px; vertical-align: middle;">Reason:</td>
			<td>
				<input type="text" id="reason" name="Reason"/>
			</td>
		</tr>
	</table>
	<input type="hidden" class='btn' name="CHANGE_STATUS_DATE" value="NOW()">
	<input type="submit" class='btn' name="submit" style="margin-left: 10%;">
	<input type="button" class='btn' name="Back to Search" onClick="window.location = 'resale_display.php'" value="Back to Search">
</form>

<script type="text/javascript">
function Blank_TextField_Validator()
{
	if ($.trim( $('#reason').val()).length == 0 ) {
		alert("Please fill in a valid Reason.");
		ChangeStatusForm.Reason.focus();
		return (false);
	}
	return (true);
}
</script>
