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

$listing=$supObj->getListingbyID($_GET['ID']);
$Builder=$projObj->getProjectBuilder($listing[0]['PROJECT_ID']);
$cityLocality=$projObj->getProjectCityLocality($listing[0]['PROJECT_ID']);
$projectOption=$projObj->getOptionDetails($listing[0]['PROPERTY_OPTION_ID']);
$towerinfo=$projObj->getTowerDetails($listing[0]['TOWER_ID']);
$param=array('proid'=>$listing[0]['PROJECT_ID']);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, SERVER_URL."/typeahead.php");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$projectDetails = curl_exec($ch);
curl_close($ch);
$projectDetails = json_decode( $projectDetails, TRUE );
$yr=date('Y');
$yrcons=substr($projectDetails[0]['COMPLETION_DATE'],-4,4);
$yrcons=intval($yr)-intval($yrcons);
if($yrcons==$yr) {
	$yrcons="NA";
	$projectDetails[0]['COMPLETION_DATE']="Not Available";
}
if($listing[0]['ADDED_BY']!='')
  $ADDED_BYname=$supObj->getUsers($listing[0]['ADDED_BY']);

$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");

?>
<link href="/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="/js/jquery/jquery-1.8.3.min.js"></script>
<script language="javascript" src="/bootstrap/js/bootstrap.js"></script>

<style type="text/css">
.table {
	width: 30%;
	/*border: 1px solid black;*/
	float: left;
	margin: 0.5%;
	table-layout: fixed;
}
.table td {
	line-height: 14px;
	font-size: 13px;
	text-align: center;
	vertical-align: center;
	border: 1px solid #dddddd;
	width: 30%;
	word-wrap:break-word;
}

.table th {
	font-weight: regular;
	font-size: 13px;
	text-align: center;
	vertical-align: middle;
	border: 1px solid #dddddd;
	width: 30%;
}
.columnsview {
	text-align: center;
	margin: 0 auto;
}
</style>

<body>
	<div class="columnsview">
		<table id="col1" class="table table-striped">
			<tr>
				<th>Resale Listing ID</th>
				<td><?php echo $listing[0]['ID']?></td>
			</tr>
			<tr>
				<th>Creation Date</th>
				<td><?php echo $listing[0]['CREATION_DATE']?></td>
			</tr>
			<tr>
				<th>Added By</th>
				<td><?php echo $ADDED_BYname[0]['USERNAME'] ?></td>
			</tr>
			<tr>
				<th>Contact Person Type</th>
				<td><?php if (intval($listing[0]['CONTACT_TYPE'])==0) echo "Owner"; else echo "Broker" ?></td>
			</tr>
			<?php if($listing[0]['ADDED_BY']==$_SESSION['CRMadminId']) { ?>
			<tr>
				<th>Broker Name</th>
				<td><?php echo $listing[0]['CONTACT_NAME']?></td>
			</tr>
			<tr>
				<th>Contact Name</th>
				<td><?php echo $listing[0]['CONTACT_NAME']?></td>
			</tr>			
			<tr>
				<th>Contact Email ID</th>
				<td><?php echo $listing[0]['CONTACT_EMAIL']?></td>
			</tr>
			<tr>
				<th>Contact Mobile No.</th>
				<td><?php echo $listing[0]['CONTACT_MOBILE']?></td>
			</tr>
			<?php } else {}?>
			<tr>
				<th>Builder Name</th>
				<td><?php echo $Builder?></td>
			</tr>
			<tr>
				<th>Project Name</th>
				<td><?php echo $listing[0]['PROJECT_NAME']?></td>
			</tr>
			<tr>
				<th>City</th>
				<td><?php echo $cityLocality['CITY']?></td><!-- from project id -->
			</tr>
			<tr>
				<th>Locality</th>
				<td><?php echo $cityLocality['LOCALITY']?></td><!-- from project id -->
			</tr>
			<tr>
				<th>Available Properties</th>
				<td><?php echo $projectOption[0]['UNIT_NAME']."(".$projectOption[0]['SIZE']." ".$projectOption[0]['MEASURE'].")";?></td><!-- from property id -->
			</tr>
		</table>
		<table id="col2" class="table table-striped">
			<tr>
				<th>Tower No.</th>
				<td><?php echo $towerinfo[0]['TOWER_NAME']?></td><!-- from tower id -->
			</tr>
			<tr>
				<th>Total Floors</th>
				<td><?php echo $towerinfo[0]['NO_OF_FLOORS']?></td><!-- from tower id -->
			</tr>
			<tr>
				<th>Flat Number</th>
				<td><?php echo $listing[0]['FLAT_NO']?></td>
			</tr>
			<tr>
				<th>Property Floor</th>
				<td><?php echo $listing[0]['FLOOR_NO']?></td>
			</tr>
			<tr>
				<th>Possession Date</th>
				<td><?php echo $projectDetails[0]['COMPLETION_DATE']?></td>
			</tr>
			<tr>
				<th>Years since Construction</th>
				<td><?php echo ($yrcons); ?></td>
			</tr>
			<tr>
				<th>Address</th>
				<td><?php echo $listing[0]['ADDRESS']?></td>
			</tr>
			<tr>
				<th>Description</th>
				<td><?php echo $listing[0]['DESCRIPTION']?></td>
			</tr>
			<tr>
				<th>Login Rate</th>
				<td><?php echo $listing[0]['LOGIN_RATE']?></td>
			</tr>
			<tr>
				<th>Demand Rate</th>
				<td><?php echo $listing[0]['DEMAND_RATE']?></td>
			</tr>
			<tr>
				<th>Other Charges</th>
				<td><?php echo $listing[0]['OTHER_CHARGE']?></td>
			</tr>
			<tr>
				<th>Car Parking and Club Charges</th>
				<td><?php echo $listing[0]['PARKING_CHARGE']?></td>
			</tr>
			<tr>
				<th>Indicative Price</th>
				<td><?php echo $listing[0]['INDICATIVE_PRICE']?></td>
			</tr>
		</table>
		<table id="col3" class="table table-striped">
			<tr>
				<th>Amount Paid</th>
				<td><?php echo $listing[0]['AMOUNT_PAID']?> <?php echo $listing[0]['AMOUNT_PAID_TYPE']?></td>
			</tr>
			<tr>
				<th>Is this Property on Home Loan?</th>
				<td><?php if($listing[0]['ON_HOME_LOAN']==1) echo "Yes"; else echo "No"?></td>
			</tr>
			<tr>
				<th>Negotiable</th>
				<td><?php if($listing[0]['IS_NEGOTIABLE']) echo "Yes"; else echo "No" ?></td>
			</tr>
			<tr>
				<th>Remarks</th>
				<td><?php echo $listing[0]['REMARK']?></td>
			</tr>
			<tr>
				<th>Furnished Type</th>
				<td><?php echo $listing[0]['FURNISHED_TYPE']?></td>
			</tr>
			<tr>
				<th>2 Wheeler Parking</th>
				<td><?php echo $listing[0]['PARKING_2']?></td>
			</tr>
			<tr>
				<th>4 Wheeler Parking</th>
				<td><?php echo $listing[0]['PARKING_4']?></td>
			</tr>
			<tr>
				<th>Ownership Type</th>
				<td><?php echo $listing[0]['OWNERSHIP']?></td>
			</tr>
			<tr>
				<th>Internal Images</th>
				<td></td>
			</tr>
			<tr>
				<th>External Images</th>
				<td></td>
			</tr>
			<tr>
				<th>Status</th>
				<td><?php if($listing[0]['STATUS']==1) echo "Open"; else echo "Closed" ;?></td>
			</tr>
			<tr>
				<th>Status Change Reason</th>
				<td><?php echo $listing[0]['REASON'] ?></td>
			</tr>
			<tr>
				<th>Status Changed Date</th>
				<td><?php echo $listing[0]['CHANGE_STATUS_DATE'] ?></td>
			</tr>
		</table>		
	</div>
	<div class="columnsview">
		<button class='btn' onClick="window.location = 'supplyentry.php?edit=<?php echo $listing[0]['ID'] ?>&action=update'">Edit this Listing</button>
		<button class='btn' onClick="window.location = 'resale_display.php'">Back to Search</button>
	</div>
</body>
<?php
include('footer.php');
?>