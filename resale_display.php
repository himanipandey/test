<?php
error_reporting(1);
ini_set('display_errors','1');
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

$smarty->display(PROJECT_ADD_TEMPLATE_PATH."header.tpl");

?>
<link href="/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="/js/jquery/jquery-1.8.3.min.js"></script>
<script language="javascript" src="/bootstrap/js/bootstrap.js"></script>

<style type="text/css">

.table td {
	padding: 2px;
	line-height: 14px;
	font-size: 13px;
	text-align: center;
	vertical-align: baseline;
	border-bottom: 1px solid #dddddd;
}

.table th {
	font-size: 13px;
	text-align: center;
	vertical-align: baseline;
	border: 1px solid #dddddd;
}
</style>

<body>
  <div id="display_search" class="form-container">
    <div id="search_prefs" class="main-form">
      <br>
	<a href="supplyentry.php" style="float: right; margin-right: 60px;font-size: 20px;">Add Entry</a>
	<br>
	  <table class="table" cellspacing=0 cellpadding=0 style="width: 100%;" border=0>		
	    <!-- Search Parameters here -->
	    <tr>
	      <th style="width: 25%;">City</th>
	      <td>
		<?php
		  $cities=$projObj->getCityList();
		  echo '<select name="CityDrop" id="CityDrop">';
		  echo '<option value = "-1"> Choose...</option>';
		  foreach ($cities as $row => $data) {
		    echo '<option value="'.$data['CITY_ID'].'"';
		    echo '>'.$data['LABEL'].'</option>';
		  }
		  echo "</select>";
		?>
	      </td>
	      <th>Locality</th>
	      <td id= "Locality">
		<?php
						$localities=$projObj->getLocalityList();
						echo '<select name="LocalityDrop" id="LocalityDrop">';
						echo '<option value = "-1"> Choose City First...</option>';
						echo "</select>";
						?>
					</td>
				</tr>
				<tr>
					<th style="width: 25%;">Builder</th>
					<td>
						<?php
						$builders=$projObj->getBuilderList();
						echo '<select name="BuilderDrop" id="BuilderDrop">';
						echo '<option value = "-1"> Choose...</option>';
						foreach ($builders as $row => $data) {
							echo '<option value="'.$data['BUILDER_NAME'].'"';
							// if($data['CITY_ID'] == $result['CityDrop'])
								// echo ' selected="selected"';
							echo '>'.$data['BUILDER_NAME'].'</option>';
						}
						echo "</select>";
						?>
					</td>

					<th>Status</th>
					<td>
						<select name="StatusDrop" id="StatusDrop">
							<option value=-1>Choose...</option>
							<option value=1>Open</option>
							<option value=0>Closed</option>
						</select>
					</td>
				</tr>
				<tr>
					<th style="width: 25%;">Contact Person Type</th>
					<td>
						<select id="CTypeDrop">
							<option value=-1>Choose...</option>
							<option value=0>Owner</option>
							<option value=1>Broker</option>
						</select>
					</td>

					<th>Broker Name</th>
					<td>
						<?php
						$brokers=$supObj->GetBrokerList();
						echo '<select name="BrokerDrop" id="BrokerDrop">';
						echo '<option value = "-1"> Choose...</option>';
						foreach ($brokers as $row => $data) {
							echo '<option value="'.$data['BROKER_ID'].'"';
							// if($data['LOCALITY_ID'] == $result['LocalityDrop'])
								// echo ' selected="selected"';
							echo '>'.$data['BROKER_NAME'].'</option>';
						}
						echo "</select>";
						?>
					</td>
				</tr>
				<tr>
					<th style="width: 25%;">No. of Bedrooms</th>
					<td>
						<select id="BedroomCount">							
							<option value=-1 selected>Choose...</option>
							<option value=1>1</option>
							<option value=2>2</option>
							<option value=3>3</option>
							<option value=4>4</option>
							<option value=5>5</option>
							<option value=6>6</option>
						</select>
					</td>

					<th>Budget</th>
					<td style="width: 25%;">
						<select style="width: 40%;" id="costmin">
                                <option value="-1">Min</option>
                                <option value="500000">5 Lacs</option>
                                <option value="2500000">25 Lacs</option>
                                <option value="4000000">40 Lacs</option>
                                <option value="7000000">70 Lacs</option>
                                <option value="15000000">1.5 Cr</option>
						</select>
						<select style="width: 40%;" id="costmax">
                                <option value="-1">Max</option>
                                <option value="2500000">25 Lacs</option>
                                <option value="4000000">40 Lacs</option>
                                <option value="7000000">70 Lacs</option>
                                <option value="15000000">1.5 Cr</option>
                                <option value="50000000">5 Cr</option>
						</select>
					</td>
				</tr>
				<tr>
					<th style="width: 25%;">Area</th>
					<td>
						<select style="width: 40%;" id="areamin">
							<option value="0"> Min </option>
                            <option value="100"> 100 sqft </option>
                            <?php for ( $__min = 1000; $__min <= 108000; $__min += 1000 ) { ?>
                                <option value="<?php echo $__min; ?>"> <?php echo $__min; ?> sqft </option>
                            <?php } ?>
                            <option value="108900"> 108900 sqft </option>
						</select>						
						<select style="width: 40%; vertical-align: middle;" id="areamax">
							<option value="-1"> Max </option>
                            <option value="108900"> 108900 sqft </option>
                            <?php for ( $__max = 108000; $__max >= 1000; $__max -= 1000 ) { ?>
                                <option value="<?php echo $__max; ?>"> <?php echo $__max; ?> sqft </option>
                            <?php } ?>
                            <option value="100"> 100 sqft </option>
						</select>
					</td>
					<th>Floor</th>
					<td>
						<select id="floordrop">							
							<option value=-1 selected>Choose...</option>
							<option value=5><5</option>
							<option value=10><10</option>
							<option value=15><15</option>
							<option value=20><20</option>
							<option value=21>>20</option>
						</select>
					</td>
				</tr>
				<tr>
					<th style="width: 25%;">Project Name</th>
					<td class="t-input-value">
						<input type="text" id="ProjectName" class="t-input c-input" data-provide="typeahead" />
					</td>

					<th>Resale Listing ID</th>
					<td>
						<input type="text" id="ResaleID"/>
					</td>
				</tr>
			</table>
			<div style="margin-left: 40%; margin-bottom: 1%">
				<button id="Submit" class="btn">Search and Display</button>
				<button id="Reset" class="btn" onClick="window.location='resale_display.php'">Reset</button>
			</div>
		</div>

		<div>
			<table id="result_table" class="table table-striped">
				<!-- Display Table Here. -->
				<tr>
					<th>S.No.</th>
					<th>Resale Listing ID</th>
					<th>Added By</th>
					<th>Created Date</th>
					<th>Contact Person</th>
					<!-- <th>Broker Name</th>
					<th>Contact Name</th>
					<th>Contact Email ID</th>
					<th>Mobile Number</th> -->
					<th>Builder Name</th>
					<th>Project Name</th>
					<th>Locality of Project</th>
					<th>Available Property for Resale</th>
					<th>Indicative Price</th>
					<th>Demand Rate</th>
					<th>Actions</th>
				</tr>
			</table>
		</div>
	</div>
		<div id="dump"></div>
</body>


<script type="text/javascript">
    $(document).ready( function() {
	$('#Submit').click(searcher);
    });

$(document).ready(function(){
    $('#ProjectName').keypress(function(e){
	if(e.keyCode==13)
      	    searcher();
    });
});

$(document).ready(function(){
    $('#ResaleID').keypress(function(e){
	if(e.keyCode==13)
      	    searcher();
    });
});

function searcher(){
    
    if(parseInt($('#costmin').val(),10)>parseInt($('#costmax').val(),10) && $('#costmax').val()!='-1') {
	alert("MINIMUM Budget can't be more than MAXIMUM Budget, please verify your filters.");
	return;
    }
    if(parseInt($('#areamin').val(),10)>parseInt($('#areamax').val(),10) && $('#areamax').val()!='-1') {
	alert("MINIMUM Area can't be more than MAXIMUM Area, please verify your filters.");
	return;
    }
    $.ajax({
	type: 'POST',
	url: 'resale_datahelper.php',
	data: {
	    action: 'search',
	    CITY_ID: document.getElementById("CityDrop").value,
	    LOCALITY_ID: document.getElementById("LocalityDrop").value,
	    BROKER_NAME: document.getElementById("BrokerDrop").value,
	    BUILDER_NAME: document.getElementById("BuilderDrop").value,
	    Status: document.getElementById("StatusDrop").value,
	    CONTACT_TYPE: document.getElementById("CTypeDrop").value,
	    ID: document.getElementById("ResaleID").value,
	    PROJECT_NAME: document.getElementById("ProjectName").value,
	    BEDROOM_COUNT: document.getElementById("BedroomCount").value,
	    MIN_AREA: document.getElementById("areamin").value,
	    MIN_COST: document.getElementById("costmin").value,
	    MAX_AREA: document.getElementById("areamax").value,
	    MAX_COST: document.getElementById("costmax").value,
	    FLOOR: document.getElementById("floordrop").value
	},
	cache: true,
	success: function(result) {
	    // $('#dump').append(result);
	    $('.searchResults').remove();
	    //$('#dump').append(result);
	    var userid="<?php echo $_SESSION['adminId'] ?>";
	    var obj=JSON.parse(result);					
	    var x=1;
	    if(obj['result']!=null && obj['result'].length!=0) {
		for (i in obj['result']) {
		    var str="";
		    str+="<tr class='searchResults'>";
		    str+="<td align='center'>"+x+"</td>";
		    str+="<td align='center'>"+obj['result'][i]['ID']+"</td>";
		    str+="<td align='center'>"+obj['result'][i]['ADDED_BY_NAME']+"</td>";
		    str+="<td align='center'>"+obj['result'][i]['CREATION_DATE']+"</td>";
		    if(obj['result'][i]['CONTACT_TYPE']=='0') //
			str+="<td align='center'>Owner</td>"; //Replace with contact info
		    else									  //adder or broker/client name
			str+="<td align='center'>Broker</td>";//
		    // str+="<td align='center'>"+obj['result'][i]['BROKER_NAME']+"</td>";
		    // str+="<td align='center'>"+obj['result'][i]['CONTACT_NAME']+"</td>";
		    // str+="<td align='center'>"+obj['result'][i]['CONTACT_EMAIL']+"</td>";
		    // str+="<td align='center'>"+obj['result'][i]['CONTACT_MOBILE']+"</td>";
		    str+="<td align='center'>"+obj['result'][i]['BUILDER_NAME']+"</td>";
		    str+="<td align='center'>"+obj['result'][i]['PROJECT_NAME']+"</td>";
		    str+="<td align='center'>"+obj['result'][i]['LOCALITY']+"</td>";
		    str+="<td align='center'>"+obj['result'][i]['AVAILABLE_PROPERTY']+"</td>";
		    if(obj['result'][i]['INDICATIVE_PRICE']==null) str+="<td align='center'>Not Available</td>";
		    else str+="<td align='center'>"+obj['result'][i]['INDICATIVE_PRICE']+"</td>";
		    str+="<td align='center'>"+obj['result'][i]['DEMAND_RATE']+"</td>";
		    if(obj['result'][i]['ADDED_BY'] == userid && obj['result'][i]['ADDED_BY']!=null)
			str+="<td align='center'><select class='actions' name='"+obj['result'][i]['ID']+"'><option value='def'>Choose an Action..</option><option value='view'>View Details</option><option value='edit'>Edit Details</option><option value='changestatus'>Change Status</option></select></td>"
		    else	str+="<td></td>";
		    //		str+="<td align='center'><select class='actions' name='"+obj['result'][i]['ID']+"'><option value='def'>Choose an Action..</option><option value='view'>View Details</option></select></td>"
		    str+="</tr>";
		    $("#result_table").append(str);
		    x+=1;
		};
	    }
	    else if(obj['result']==null || obj['result'].length==0) {
		$('.searchResults').remove();
		$("#result_table").append("<tr class='searchResults'><td style='font-size: 14px;' colspan=4>Sorry, no results found!</td></tr>");
	    }
	}
    });
}

$(document).ready(function () {
    $(document).on('change','.actions', function() {
	if(this.value=='changestatus')
	    window.location = 'resale_changestatus.php?ID='+this.name;
	else if(this.value=='view')
	    window.location = 'resale_viewedit.php?ID='+this.name+'&action='+this.value;
	else if(this.value=='edit')
	    window.location = 'supplyentry.php?edit='+this.name;
    });

    $(document).on('change','#CityDrop', function() {
	if($('#CityDrop').value=='-1') {
	    alert("DFSsd");
	    $('#LocalityDrop').html("");
	    return;
	}
	$.ajax({
	    type: 'POST',
	    url: 'resale_datahelper.php',
	    data: {
		action: 'locality',
		CITY_ID: document.getElementById("CityDrop").value
	    },
	    cache: false,
	    success: function(result) {
		$('#LocalityDrop').html(result);
	    }
	});
    });


});

$(document).ready(function() {	
    $('#ProjectName').typeahead({
	source: function (query, process) {
	    return $.ajax({
		url: 'resale_datahelper.php',
		type: 'POST',
		data: {
		    action: 'autocomplete',
		    term: document.getElementById('ProjectName').value
		},
		cache: false,
		success: function(data) {
		    var obj=JSON.parse(data);
		    var list=[""];
		    
		    if(obj['result']!=null)	{
			for (var i=0;i<obj['result'].length;i++) {		
			    list.push(obj['result'][i]['PROJECT_NAME']);					
			}						
		    }
		    return process(list);
		}
	    });
	},
	matcher: function (item) {
	    if (item.toLowerCase().indexOf(this.query.trim().toLowerCase()) != -1) {
		return true;
	    }
	},
	sorter: function (items) {
	    return items.sort();
	},
	highlighter: function (item) {
	    var regex = new RegExp( '(' + this.query + ')', 'gi' );
	    return item.replace( regex, "<strong>$1</strong>" );
	},
	updater: function (item) {
	    return item;
	}
    });
});
</script>

<?php 
$smarty->display(PROJECT_ADD_TEMPLATE_PATH."footer.tpl");
?>

