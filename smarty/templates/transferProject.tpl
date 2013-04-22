<script type="text/javascript" src="js/jquery.js"></script>
<script>
function downloadExcel(phase,stage)
{
	$('#current_dwnld_phase').val(phase);
	$('#current_dwnld_stage').val(stage);
	document.frmdownload.action = "ajax/downloadProject.php";
	
	document.frmdownload.submit();
}

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


var idfordiv = 0;

function statuschange(projectId)
{
	idfordiv = projectId;
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	{
		alert ("Browser does not support HTTP Request")
		return
	}
	var url="RefreshBanStat.php?projectId="+projectId;
	//alert(url);
	xmlHttp.onreadystatechange=stateChanged
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}
	
function stateChanged()
{

	if (xmlHttp.readyState==4)
	{
	//alert("here");
		document.getElementById('statusRefresh'+idfordiv).innerHTML=xmlHttp.responseText;
	
	}
}

function update_locality(ctid)
{
	xmlHttpLoc=GetXmlHttpObject()
	
	var url="Refreshlocality.php?ctid="+ctid;	
	xmlHttpLoc.onreadystatechange=stateChanged
	xmlHttpLoc.open("GET",url,true)
	xmlHttpLoc.send(null)
}
	
function stateChanged()
{

	if (xmlHttpLoc.readyState==4)
	{	
		document.getElementById("LocalityList").innerHTML=xmlHttpLoc.responseText;
		$('#locality').attr('style','width:220px;border:1px solid #c2c2c2;padding:3px;height:28px;');
	}
}

function update_builder(ctid)
{
	xmlHttpBuilder=GetXmlHttpObject()
	
	var url="Refreshbuilder.php?ctid="+ctid;
	
	xmlHttpBuilder.onreadystatechange=stateBuilder
	xmlHttpBuilder.open("GET",url,true)
	xmlHttpBuilder.send(null)
}
function stateBuilder()
{
	if (xmlHttpBuilder.readyState==4)
	{	
		document.getElementById("BuilderList").innerHTML=xmlHttpBuilder.responseText;
	    $('#builder').attr('style','width:220px;border:1px solid #c2c2c2;padding:3px;height:28px;');
	}
}
	
function chkConfirm() 
{
	return confirm("Are you sure! you want to delete this record.");
}

function gotoAddress(link){
	window.location = link;
}

function updatelink(url)
{
	window.location = url;
}


function validation()
	{

		var ct			 = $("#city").val();
		var loc			 = $("#locality").val();
		var bldr		 = $("#builder").val();
		var phase		 = $("#phase").val();
		var stage		 = $("#stage").val();
		var Availability = $("#Avail").val();
		var Residential  = $("#Residential").val();
		var pid     	 = $("#projectId").val();
		var Active     	 = $("#Active").val();
		var Status     	 = $("#Status").val();
		var project_name = $("#project_name").val();
		
		if(ct == '' && loc == '' && bldr == '' && project_name == '' && phase=='' && stage=='' && pid == '' && Availability == null && Residential == '' && Active == null  && Status == null  && project_name == '')
		{
			$("#errmsg").show();
			return false;
		}
		else
		{
			$("#errmsg").hide();
			return true;
		}
		return false;
	}
	
	
	
function getSelectedProps(){
		var selectedProps = [];
		$('.stateSelection').each(function(index) {
			if($(this).attr('checked')){
				selectedProps.push($(this).val());
			}
		});
		return selectedProps;
	}
	function checkPhaseValues(){
		var phases = [];
		var flag = true;
		$('.stateSelection').each(function(index) {
			if( $(this).parent().find('.phaseCheck').val()!="dataCollection" && $(this).parent().find('.phaseCheck').val()!="complete"  && $(this).attr('checked')){
				$(this).parent().find('.phaseError').html("In middle of an audit");
				flag = false;
			}
		});
		return flag;
	}
	
	
function changePhase(value){
	var props = getSelectedProps();
	var checkPhaseFlag = checkPhaseValues();
	if(!checkPhaseFlag)
	{
		return;
	}
	if(props.length>0)
	{
		if(value!='0')
		{			
			if (confirm("Do you want to change label of selected projects?"))
			{
				$('#currentPhase').val(value);
				$('#selections').val(props);
				$("#returnURLPID").val(document.URL);
				$('#changePhaseForm').submit();
			}
		}
	}
}
function changePhaseSelected(value){
	var props = getSelectedProps();
	var checkPhaseFlag = checkPhaseValues();
	if(!checkPhaseFlag){
		console.log("in middle of an audit");
		return;
	}
	if(props.length>0){
		if(value!='0'){
			if (confirm("Do you want to change phase of selected projects?")) {
				$('#changePhase').val(value);
				$('#selections').val(props);
				$("#returnURLPID").val(document.URL);
				$('#changePhaseForm').submit();
			}
		}
	}
}

function makeLabel()
{
	if($('#project_tag').val()!=''){
		if (confirm("Do you want to make a new label?")) {
					$('#currentPhase').val('0');
					$('#label').val($('#project_tag').val());
					$("#returnURLPID").val(document.URL);
					$('#changePhaseForm').submit();
				}}
	else{
		$('#errmsgLabel').show();
		return false;
	}
}

function showHidePhase(phaseName,stageName)
{
		var phaseNameList = '';
	    $('.showHideCls:checked').each(function(){
	    	phaseNameList = phaseNameList+"#"+$(this).val();
	    });
	
	if(phaseNameList.search("audit2") != -1)
	{
		$("#noPhaseDisplay").show();
		$("#showHidePhs").hide();
		$("#removePhaseCode").html("1");
			
	}
	else
	{
		$("#noPhaseDisplay").hide();
		$("#showHidePhs").show();
		$("#removePhaseCode").html("0");
	}
}

function removeExtraCode()
{
	var id = $("#removePhaseCode").val();
	if(id == 0)
	{
		$("#noPhaseDisplay").html('');
	}
	else
	{
		$("#showHidePhs").html('');
	}
}
</script>
<form name='frmdownload' method='post' action='ajax/downloadProject.php'>
<input type='hidden' name='dwnld_city' id='dwnld_city' value="{$_POST['city']}">
<input type='hidden' name='dwnld_locality' id='dwnld_locality' value="{$_POST['locality']}">
<input type='hidden' name='dwnld_mode' id='dwnld_mode' value="{$_POST['mode']}">
<input type='hidden' name='dwnld_builder' id='dwnld_builder' value="{$_POST['builder']}">
<input type='hidden' name='dwnld_phase' id='dwnld_phase' value="{$_POST['phase']}">
<input type='hidden' name='dwnld_stage' id='dwnld_stage' value="{$_POST['stage']}">
<input type='hidden' name='dwnld_Residential' id='dwnld_Residential' value="{$_POST['Residential']}">
<input type='hidden' name='dwnld_Availability' id='dwnld_Availability' value="{implode(",",$_POST['Availability'])}">
<input type='hidden' name='dwnld_Active' id='dwnld_Active' value="{implode(",",$_POST['Active'])}">
<input type='hidden' name='dwnld_Status' id='dwnld_Status' value="{implode("','",$_POST['Status'])}">
<input type='hidden' name='dwnld_project_name' id='dwnld_project_name' value="{$_POST['project_name']}">
<input type='hidden' name='dwnld_projectId' id='dwnld_projectId' value="{$_POST['projectId']}">
<input type='hidden' name='dwnld_search' id='dwnld_search' value="{$_POST['search']}">
<input type='hidden' name='dwnld_transfer' id='dwnld_transfer' value="{$_POST['transfer']}">

<input type='hidden' name='current_dwnld_phase' id='current_dwnld_phase' value="">
<input type='hidden' name='current_dwnld_stage' id='current_dwnld_stage' value="">
</form>
<span id = "removePhaseCode" style = "display:none"></span>
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
	   		{include file="{$PROJECT_ADD_TEMPLATE_PATH}left.tpl"}
	  </TD>
          <TD vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</TD>
          <TD vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <TABLE cellspacing=1 cellPadding=0 width="100%" bgColor="#c2c2c2" border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>Projects List</TD>
					  <TD width="33%" align ="right">&nbsp;</TD>                   
                    </TR>
		  			</TBODY>
		  		  </TABLE>
				</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450 width='100%'><BR>
				<form name='search' method="post" onsubmit = "return validation();">
					  <center><table width="530" border="0" align="left" cellpadding="0" cellspacing="1" bgColor="#fcfcfc" style = "border:1px solid #c2c2c2;margin: 20px;">
					  	<tr bgcolor='#DCDCDC'>
							<td height="35" align="center" colspan= "2" style='border-bottom:1px solid #c2c2c2;color:#333;'>
								<b>Search Projects</b>
							</td>
						</tr>
						<tr>
							<td height="25" align="center" colspan= "2">
								<span id = "errmsg" style = "display:none;"><font color = "red">Please select atleast one field</font></span>
							</td>
						</tr>
						<tr bgcolor='#fcfcfc'>
							<td width="30%" align="right" style = "padding-left:20px;" height='35'><b>City:</b></td>
							<td width="70%" align="left" style = "padding-left:20px;" height='35'>
								<select name = 'city' id = "city" onchange = "update_locality(this.value);update_builder(this.value);" style='width:220px;border:1px solid #c2c2c2;padding:3px;height:28px;'>
									<option value = "">Select City</option>
									{foreach from = $citylist key= key item = val}							
										<option value = "{$key}" {if $city == $key} selected {/if}>{$val}</option>
									{/foreach}
								</select>
							</td>
						  </tr>
						  <tr>
							<td align="right" style = "padding-left:20px;" height='35'><b>Locality:</b></td>
							<td align="left" style = "padding-left:20px;" height='35'>
							<span id = "LocalityList"><select name = 'locality' id = "locality"  style='width:220px;border:1px solid #c2c2c2;padding:3px;height:28px;'>
									<option value = "">Select Locality</option>
									{foreach from = $localityArr key = key item = val}
										<option value = "{$key}" {if $locality == $key} selected {/if}>{$val}</option>
									{/foreach}
								</select></span>
							</td>
						  </tr>			
						  		   
						   <tr  bgcolor='#fcfcfc'>
							<td align="right" style = "padding-left:20px;" height='35'><b>Builder:</b></td>
							<td align="left" style = "padding-left:20px;" height='35'>
								<span id = "BuilderList">
									<select name = 'builder' id = "builder"  style='width:220px;border:1px solid #c2c2c2;padding:3px;height:28px;'>
										<option value = "">Select Builder</option>
										{foreach from = $builderList key= key item = val}
								
											<option value = "{$key}" {if $builder == $key} selected {/if}>{$val}</option>
										{/foreach}
									</select>
								</span>
							</td>
						  </tr>
						
						 <tr>
							<td align="right" style = "padding-left:20px;" height='35'><b>Stage</b></td>
							<td align="left" style = "padding-left:20px;" height='35'>
								<span>
									<select name = 'phase' id = "phase"  style='width:220px;border:1px solid #c2c2c2;padding:3px;height:28px;'>
										<option value = "">Select Stage</option>
										<option value = "dataCollection"{if {$phase}=='dataCollection'}selected{/if}>Data Collection</option>
										<option value = "newProject" {if {$phase}=='newProject'}selected{/if}>New Project Audit</option>
										<option value = "dcCallCenter" {if {$phase}=='dcCallCenter'}selected{/if}>Data Collection CallCenter</option>
										<option value = "audit1" {if {$phase}=='audit1'}selected{/if}>Audit 1</option>
										<option value = "audit2" {if {$phase}=='audit2'}selected{/if}>Audit 2</option>
										<option value = "complete" {if {$phase}=='complete'}selected{/if}>Completed</option>
									</select>
								</span>
							</td>
						  </tr>
							
							 <tr bgcolor='#fcfcfc'>
							<td align="right" style = "padding-left:20px;" height='35'><b>Phase:</b></td>
							<td align="left" style = "padding-left:20px;" height='35'>
								<span>
									<select name = 'stage' id = "stage"  style='width:220px;border:1px solid #c2c2c2;padding:3px;height:28px;'>
										<option value = "">Select Phase</option>
										<option value = "noStage"{if {$stage}=='NoPhase'}selected{/if}>No Phase</option>
										<option value = "newProject"{if {$stage}=='newProject'}selected{/if}>New Project Entry</option>										
										{foreach from=$UpdationArr key=k item=v}
										 <option value = "updationCycle|{$UpdationArr[$k].UPDATION_CYCLE_ID}" 
										 	{if "{$stage}|{$tag}" == "updationCycle|{$UpdationArr[$k].UPDATION_CYCLE_ID}"} selected {/if}
										 > updationCycle - {$UpdationArr[$k].LABEL}
										 </option>
										{/foreach}										
									</select>
								</span>
							</td>
						  </tr>
						  <tr>
							<td width="50" align="right" style = "padding-left:20px;" height='35' nowrap><b>Residential:</b></td>
							<td width="50" align="left" style = "padding-left:20px;" height='35'>
							<select name="Residential" id="Residential"  style='width:220px;border:1px solid #c2c2c2;padding:3px;height:28px;'>
										<option value="">Select</option>
										<option value="0" {if {$Residential}=='0'}selected{/if}>Yes</option>
										<option value="1" {if {$Residential}=='1'}selected{/if}>No</option>
										
									 </select>
							</td>
						  </tr>
						  <tr bgcolor='#fcfcfc'>
							<td width="50" align="right" style = "padding-left:20px;" height='80' nowrap><b>Availability:</b></td>
							<td width="50" align="left" style = "padding-left:20px;">
							<select name="Availability[]" id="Avail" multiple style='width:260px;border:1px solid #c2c2c2;padding:3px;'>
										<option value="">Select Availability</option>
										<option value="0" {if in_array(0,$Availability)}selected{/if}>Inventory Not Available</option>
										<option value="1" {if in_array(1,$Availability)}selected{/if}>Inventory Available</option>
										<option value="2" {if in_array(2,$Availability)}selected{/if}>Data Not Available</option>
										
									 </select>
							</td>
						  </tr>					
						  
						<tr bgcolor='#fcfcfc'>
							<td align="right" style = "padding-left:20px;" height='80'><b>Active:</b></td>
							<td align="left" style = "padding-left:20px;">
								<select name="Active[]" id="Active" class="field" multiple style='width:260px;border:1px solid #c2c2c2;padding:3px;'>
									  <option value ="" >Select</option>
									  <option value="0" {if in_array('0',$Active)} selected="selected"{/if} >Inactive on both Website and IS DB</option>
									 <option value="1" {if in_array('1',$Active)}  selected="selected" {/if} >Active on both Website and IS DB</option>
									 <option value="2" {if in_array('2',$Active)}  selected="selected" {/if} >Deleted</option>
									 <option value="3" {if in_array('3',$Active)}  selected="selected" {/if} >Active on IS but inactive on website</option>
									 </select>
							</td>
						  </tr>
						  <tr>
							<td align="right" style = "padding-left:20px;" height='80'><b>Project Status:</b></td>
							<td align="left" style = "padding-left:20px;">
								<select name="Status[]" id="Status" class="fieldState" multiple style='width:260px;border:1px solid #c2c2c2;padding:3px;'>
									<option value="">Select</option>
									{foreach from = $enum_value key = key item = value}
										<option value="{$value}" {if in_array($value,$Status)} selected {/if}>{$value} </option>
									{/foreach}


								 </select>
							</td>
						  </tr>
						  <tr bgcolor='#fcfcfc'>
							<td align="right" style = "padding-left:20px;" height='35'><b>Project Name:</b></td>
							<td align="left" style = "padding-left:20px;" height='35'>
								<input type = "text" name = "project_name" id = "project_name" value = "{$project_name}" style='width:220px;border:1px solid #c2c2c2;padding:3px;height:28px;'>
							</td>
						  </tr>
						  <tr>
							<td align="right" style = "padding-left:20px;" height='35'><b>Project Id:</b></td>
							<td align="left" style = "padding-left:20px;" height='35'>
								<textarea name = "projectId" id = "projectId" style='width:260px;border:1px solid #c2c2c2;padding:3px;height:130px;'>{$projectId}</textarea>
								<br><span style='font-size:10px;'>Please enter comma(,) saparated values for multiple project id. e.g 12323,23232,322322</span>
								
							</td>
						  </tr>
						  <tr>
							<td height="25" align="right" colspan= "2"></td>
						  </tr>
						   <tr>
							<td height="25" align="center" colspan= "2"  style = "padding-right:40px;">
								<input type = "submit" value = "Search" name = "search" style="border:1px solid #c2c2c2;height:30px;width:70px;background:#999999;color:#fff;font-weight:bold;cursor:hand;pointer:hand;">
							</td>
						  </tr>							
						   <tr>
							<td height="25" align="right" colspan= "2"></td>
						  </tr>
					  </table> 					
				  	  </center>
				  	  <center>
				  	  <table width="502" border="0" align="left" cellpadding="0" cellspacing="1" bgColor="#c2c2c2" style = "margin: 20px;">
				  	  <tr bgcolor='#ffffff'><td height=28 width='40'>&nbsp;</td><td align='center'><b>SNo</b></td><td align='center'><b>Count</b></td><td align='center'><b>Project Phase</b></td><td align='center'><b>Project Stage</b></td><td align='center'><b>Download</b></td></tr>
				  	  {$ctrl = 1}
				  	  {$flagcheck=0}
				  	  {$totcnt = 0}
				  	  {if count($projectDataArr)>0}
				  	  {foreach from=$projectDataArr key=key item=arrVal}				  	  
				  	  	<tr bgcolor='#ffffff'>
				  	  		<td align='center' width='40' height=30>
				  	  			&nbsp;
				  	  				{if $arrVal['PROJECT_STAGE'] == 'noStage' || $arrVal['PROJECT_STAGE'] == '' || $arrVal['PROJECT_PHASE'] == 'audit2'} 
				  	  					{$phaseName = $arrVal['PROJECT_PHASE']}
				  	  					{$stageName = $arrVal['PROJECT_STAGE']}
				  	  					<input class = "showHideCls" type='checkbox' onclick =  "showHidePhase('{$phaseName}','$stageName');" name='selectdata[]' value="{$arrVal['PROJECT_STAGE']}|{$arrVal['PROJECT_PHASE']}" 
				  	  					{if in_array("{$arrVal['PROJECT_STAGE']}|{$arrVal['PROJECT_PHASE']}",$selectdata)} checked {/if}
				  	  					> 
				  	  					{$flagcheck=1}
				  	  			   {else}
				  	  			   	-
				  	  			   {/if}
				  	  		</td>
				  	  		<td align='center'>{$ctrl}</td>
				  	  		<td align='center'>{$arrVal['CNT']}</td>
				  	  		<td style='padding-left:5px;'>
				  	  			{if $arrVal['PROJECT_STAGE']=='noStage'} 
				  	  				noPhase 
				  	  			{else}
				  	  			 	{$arrVal['PROJECT_STAGE']} 
				  	  			{/if}
				  	  		</td>
				  	  		<td style='padding-left:5px;'>
				  	  			{$arrVal['PROJECT_PHASE']}
				  	  		</td>
				  	  		<td align='center' style='padding-left:5px;'>
				  	  			<a href='javascript:void(0);' onClick='javascript:downloadExcel("{$arrVal['PROJECT_STAGE']}","{$arrVal['PROJECT_PHASE']}");'><img src='images/excel.png' border='0'></a>
				  	  		</td>
				  	  	 </tr>
				  	  	{$ctrl = $ctrl + 1}
				  	  	{$totcnt = $totcnt + $arrVal['CNT']}
				  	  {/foreach}
				  	  <tr bgcolor='#ffffff'><td align='center' height='35' colspan='2'><b>Total Projects</b></td><td align='center'>{$totcnt}</td><td colspan='3'></td></tr>
				  	  {else}
				  	  	<tr bgcolor='#ffffff'><td colspan='6' align='center' valign='middle' height='80'>No record found for the selected search criteria</td></tr>
				  	  {/if}
				  	  </table>
				  	  <br>
				  	  {if $flagcheck == 1}
				  	  <table width="502" border="0" align="left" cellpadding="0" cellspacing="1" bgColor="#fcfcfc" style = "border:1px solid #c2c2c2;margin: 20px;float:right">
				  	  	<tr bgcolor='#DCDCDC'>
							<td height="35" align="center" colspan= "2" style='border-bottom:1px solid #c2c2c2;color:#333;'>
								<b>Move Projects</b>
							</td>
						</tr>
						<tr>
							<td height="25" align="right" colspan= "2">
							{if $tot_affected_rows>0} {$tot_affected_rows} records has been moved{/if}
							</td>
						  </tr>
						
							<tr>
							<td width="75" align="right" style = "padding-left:20px;"><b>Select Phase:</b></td>
							<td width="25" align="left" style = "padding-left:20px;">
							<span id = "showHidePhs">
								<select name="updatePhase" id="updatePhase" class="updatePhase" style = "margin:5px;width:220px;border:1px solid #c2c2c2;padding:3px;height:28px;">									
											<option value="noStage|0" {if $updatePhasePost == "noStage|0"} selected {/if}>No Phase</option>
											<option value="newProject|0" {if $updatePhasePost == "newProject|0"} selected {/if}>New Project</option>
											{foreach from=$UpdationArr key=k item=v}
											 <option value = "updationCycle|{$UpdationArr[$k].UPDATION_CYCLE_ID}"  {if $updatePhasePost == "updationCycle|{$UpdationArr[$k].UPDATION_CYCLE_ID}"} selected {/if}> updationCycle - {$UpdationArr[$k].LABEL}
											 </option>
											{/foreach}	
								</select>
							</span>
							<span id = "noPhaseDisplay" style = "display:none;">
								<select name='updatePhase' id='updatePhase' class='updatePhase' style = 'margin:5px;width:220px;border:1px solid #c2c2c2;padding:3px;height:28px;'>
									<option value='noStage|0'>No Phase</option>
								</select>
							</span>
							</td>
							</tr>
							<!-- <tr>
							<td width="75" align="right" style = "padding-left:20px;"><b>Select Stage:</b></td>
							<td width="25" align="left" style = "padding-left:20px;">
							<select name="updateStage" id="updateStage" class="updateStage" style = "margin:5px;width:220px;border:1px solid #c2c2c2;padding:3px;height:28px;">
								<option value="">Select Stage</option>
								{foreach from=$arrProjStage key=k item=v}
								 	<option value = "{$v}" {if $updateStagePost == $v} selected {/if}>{$v}</option>
								{/foreach}
							 </select>
							</td>
							</tr> -->
							<tr>
							<td height="20" align="right" colspan= "2"></td>
						  </tr>
				  	  		<tr>
							<td height="25" align="center" colspan= "2"  style = "padding-right:40px;">
								<input onclick = "removeExtraCode();" type = "submit" value = "Transfer" name = "transfer" style="border:1px solid #c2c2c2;height:30px;width:70px;background:#999999;color:#fff;font-weight:bold;cursor:hand;pointer:hand;">
							</td>
						  </tr>							
						   <tr>
							<td height="25" align="right" colspan= "2"></td>
						  </tr>						  
				  	  </table>				  	  
				  	  {/if}
				  	  {if $projectIdUpdated != ''}
				  	  <div style='width:483px;border:2px solid #d2d2d2;height:178px;overflow:auto;text-align:justify;padding:8px;'>{$projectIdUpdated}</div>
					  {/if}
				  	  </center>
				</td>
						  </tr>
					  </table> 					