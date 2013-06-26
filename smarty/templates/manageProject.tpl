<style>
select {
border:1px solid #c2c2c2;padding:4px;width:280px;
}
div,td,label,span {
font-family:arial,tahoma,verdana;
}
</style>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>
<script language="javascript">

var selectAllFlag = false;
function selectAll(event){
	if(selectAllFlag){
		selectAllFlag = false;
	}
	else{
		selectAllFlag = true;
	}
	$('.stateSelection').each(function(index) {
  		if(selectAllFlag){
	  		if(!$(this).attr('checked')){
	  			$(this).click();
	  		}
	  		$(event).html('Clear All');
  		}
  		else{
  			if($(this).attr('checked')){
  				$(this).click();
  				$(event).html('Select All');
  			}
  		}
	});
}

function chkConfirm()
	{
		return confirm("Are you sure! you want to delete this record.");
	}

function gotoAddress(link){
	window.location = link;
}
/*************Ajax code************/
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
		//alert(xmlHttpLoc.responseText+"here");
			document.getElementById("LocalityList").innerHTML=xmlHttpLoc.responseText;

		}
	}

	function update_builder(ctid)
	{
		xmlHttpBuilder=GetXmlHttpObject()

		var url="Refreshbuilder.php?ctid="+ctid;
		//alert(url);
		xmlHttpBuilder.onreadystatechange=stateBuilder
		xmlHttpBuilder.open("GET",url,true)
		xmlHttpBuilder.send(null)
	}
	function stateBuilder()
	{

		if (xmlHttpBuilder.readyState==4)
		{
		 //alert("here"+xmlHttpBuilder.responseText);
			document.getElementById("BuilderList").innerHTML=xmlHttpBuilder.responseText;

		}
	}
	/*******************End Ajax Code*************/

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
		var tag		     = $("#tag").val();
		var Availability = $("#Avail").val();
		var Residential  = $("#Residential").val();
		var pid     	 = $("#projectId").val();
		var Active     	 = $("#Active").val();
		var Status     	 = $("#Status").val();
		var project_name = $("#project_name").val();
		var f_date_c_from = $("#f_date_c_from").val();
                var f_date_c_from = $("#f_date_c_to").val();
		if(ct == '' && loc == '' && bldr == '' && project_name == '' && phase=='' && stage=='' && tag=='' && pid == '' && Availability == null && Residential == '' && Active == null  && Status == null  && project_name == ''
                            && f_date_c_from =='' && f_date_c_to =='')
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

function labelSelect(label){

}

//var selectedProps = [];
/*$(document).ready(function(){
	$(".stateSelection").bind('change',function(event){
	if(!event.currentTarget.checked){
		selectedProps.push(event.currentTarget.value);
	}
	else{
	selectedProps.pop(event.currentTarget.value);
}
	} );
});*/
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
	if(!checkPhaseFlag){
		return;
	}
	if(props.length>0){
			if(value!='0'){
			if (confirm("Do you want to change label of selected projects?")) {
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

function makeLabel(){
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


</script>


<form  action="ProjectList.php?page=1&sort=all" method="GET" id="changePhaseForm">
  <input type="hidden" id="currentPhase" name="currentPhase" value=""/>
  <input type="hidden" id="changePhase" name="changePhase" value=""/>
		<input type="hidden" id="selections" name="selections" value=""/>
  <input type="hidden" id="returnURLPID" name="returnURLPID" value=""/>
 <input type="hidden" id="label" name="label" value=""/>
<input type="hidden" id="currentTime" name="currentTime" value="{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}"/>
</form>

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
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>Projects List</TD>
					  <TD width="33%" align ="right"><a href = "add_project.php"><b>ADD PROJECT</b></a></TD>

                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD colspan='2' vAlign=top align=center class="backgorund-rt" height=450><BR>
				
					  <center><table width="630" border="0" align="center" cellpadding="0" cellspacing="1" bgColor="#fcfcfc" style = "border:1px solid #c2c2c2;margin: 20px;">
					  	<form method = "get" action = "" onsubmit = "return validation();">
					  	
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
							<tr>
								<td align="right" style = "padding-left:20px;" width='35%'><b>City:</b></td>
								<td align="left" style = "padding-left:20px;" width='65%'>
									<select name = 'city' id = "city" onchange = "update_locality(this.value);update_builder(this.value);">
										<option value = "">Select City</option>
										{foreach from = $citylist key= key item = val}
	
											<option value = "{$key}" {if $city == $key} selected  {else}{/if}>{$val}</option>
										{/foreach}
									</select>
								</td>
							  </tr>
							  <tr><td>&nbsp;</td></tr>
							  <tr>
								<td align="right" style = "padding-left:20px;"><b>Locality:</b></td>
								<td align="left" style = "padding-left:20px;">
								<span id = "LocalityList">
									<select name = 'locality' id = "locality">
										<option value = "">Select Locality</option>
										{foreach from = $localityArr key = key item = val}
											<option value = "{$key}" {if $locality == $key} selected  {else}{/if}>{$val}</option>
										{/foreach}
									</select>
								</span>
								</td>
							  </tr>
							   <tr><td>&nbsp;</td></tr>
							   <tr>
								<td align="right" style = "padding-left:20px;"><b>Builder:</b></td>
								<td align="left" style = "padding-left:20px;">
									<span id = "BuilderList">
										<select name = 'builder' id = "builder">
											<option value = "">Select Builder</option>
											{foreach from = $builderList key= key item = val}
	
												<option value = "{$key}" {if $builder == $key} selected  {else}{/if}>{$val}</option>
											{/foreach}
										</select>
									</span>
								</td>
							  </tr>
							 <tr><td>&nbsp;</td></tr>
	
							 <tr>
								<td align="right" style = "padding-left:20px;"><b>Stage</b></td>
								<td align="left" style = "padding-left:20px;">
									<span id = "BuilderList">
										<select name = 'phase' id = "phase" >
											<option value = "">Select Stage</option>
											<option value = "dataCollection"{if $phase == 'dataCollection'}selected {else}{/if}>Data Collection</option>
											<option value = "newProject" {if $phase == 'newProject'}selected {else}{/if}>New Project Audit</option>
											<option value = "audit1" {if $phase == 'audit1'}selected {else}{/if}>Audit 1</option>
											<option value = "audit2" {if $phase == 'audit2'}selected {else}{/if}>Audit 2</option>
											<option value = "complete" {if $phase == 'complete'}selected {else}{/if}>Completed</option>
										</select>
									</span>
								</td>
							  </tr>
							 <tr><td>&nbsp;</td></tr>
	
								 <tr>
								<td align="right" style = "padding-left:20px;"><b>Phase:</b></td>
								<td align="left" style = "padding-left:20px;">
									<span id = "BuilderList">
										<select name = 'stage' id = "stage" >
											<option value = "">Select Phase:</option>
											<option value = "newProject"{if $stage == 'newProject'}selected {else}{/if}>New Project Entry</option>
											<option value = "updationCycle"{if $stage == 'updationCycle'}selected {else}{/if}>Updation Cycle</option>
											<option value = "noStage"{if $stage == 'NoPhase'}selected {else}{/if}>No Phase</option>
										</select>
									</span>
								</td>
							  </tr>
							 <tr><td>&nbsp;</td></tr>
	
	
							<tr>
								<td align="right" style = "padding-left:20px;"><b>Label:</b></td>
								<td align="left" style = "padding-left:20px;">
								<select name="tag" id="tag"  >
											<option value="">Select Label</option>
											{foreach from=$UpdationArr key=k item=v}
											 <option value = "{$UpdationArr[$k].UPDATION_CYCLE_ID}" {if $tag == $UpdationArr[$k].UPDATION_CYCLE_ID}selected {else} {/if}   >{$UpdationArr[$k].LABEL}</option>
											{/foreach}
										 </select>
								</td>
	
							  </tr>
							<tr><td>&nbsp;</td></tr>
							
							  <tr>
								<td width="50" align="right" style = "padding-left:20px;" nowrap><b>Availability:</b></td>
								<td width="50" align="left" style = "padding-left:20px;">
								<select name="Availability[]" id="Avail" multiple>
											<option value="">Select Availability</option>
											{if !is_array($Availability)}
												{$Availability = array()}
											{/if}
											<option value="0" {if in_array(0,$Availability)}selected {else} {/if}>Inventory Not Available</option>
											<option value="1" {if in_array(1,$Availability)}selected {else} {/if}>Inventory Available</option>
											<option value="2" {if in_array(2,$Availability)}selected {else} {/if}>Data Not Available</option>
											
										 </select>
								</td>
							  </tr>
	
							<tr><td>&nbsp;</td></tr>
	
							  <tr>
								<td width="50" align="right" style = "padding-left:20px;" nowrap><b>Residential:</b></td>
								<td width="50" align="left" style = "padding-left:20px;">
								<select name="Residential" id="Residential" >
											<option value="">Select</option>
											<option value="0" {if $Residential == '0'}selected {else}{/if}>Yes</option>
											<option value="1" {if $Residential == '1'}selected {else}{/if}>No</option>
	
										 </select>
								</td>
							  </tr>
							  <tr><td>&nbsp;</td></tr>		
	
								<tr>
								<td align="right" style = "padding-left:20px;"><b>Active:</b></td>
								<td align="left" style = "padding-left:20px;">
									<select name="Active[]" id="Active" class="field" multiple>
										  <option value ="" >Select</option>
										  <option value="0" {if in_array('0',$Active)} selected="selected" {else} {/if} >Inactive on both Website and IS DB</option>
										 <option value="1" {if in_array('1',$Active)}  selected="selected" {else} {/if} >Active on both Website and IS DB</option>
										 <option value="2" {if in_array('2',$Active)}  selected="selected" {else} {/if} >Deleted</option>
										 <option value="3" {if in_array('3',$Active)}  selected="selected" {else} {/if} >Active on IS but inactive on website</option>
										 </select>
								</td>
							  </tr>
							  <tr><td>&nbsp;</td></tr>
							  <tr>
								<td align="right" style = "padding-left:20px;"><b>Project Status:</b></td>
								<td align="left" style = "padding-left:20px;">
									<select name="Status[]" id="Status" class="fieldState" multiple>
										<option value="">Select</option>
										{foreach from = $enum_value key = key item = value}
											<option value="{$value}" {if in_array($value,$Status)} selected {else} {/if}>{$value} </option>
										{/foreach}
									 </select>
								</td>
							  </tr>      
                                                          
							  <tr><td>&nbsp;</td></tr>		 
							<tr> 
								<td align="right" style = "padding-left:20px;"><b>Expected Supply Date:</b></td>
								<td align="left" style = "padding-left:20px;">
									From:<input name="exp_supply_date_from" value="{$exp_supply_date_from}" type="text" class="formstyle2" id="f_date_c_from" size="5" />  <img src="images/cal_1.jpg" id="f_trigger_c_from" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                                                                       &nbsp; To:<input name="exp_supply_date_to" value="{$exp_supply_date_to}" type="text" class="formstyle2" id="f_date_c_to" size="5" />  <img src="images/cal_1.jpg" id="f_trigger_c_to" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                                                                        
                                                                        
								</td>
							  </tr>
                                                          
                                                          <tr><td>&nbsp;</td></tr>		 
							<tr> 
								<td align="right" style = "padding-left:20px;"><b>Project Name:</b></td>
								<td align="left" style = "padding-left:20px;">
									<input type = "text" name = "project_name" id = "project_name" value = "{$project_name}">
								</td>
							  </tr>
							  <tr><td>&nbsp;</td></tr>
	
							  <tr>
								<td align="right" style = "padding-left:20px;"><b>Project Id:</b></td>
								<td align="left" style = "padding-left:20px;">
									<input type = "text" name = "projectId" id = "projectId" value = "{$projectId}">
								</td>
							  </tr>
							  <tr>
								<td height="25" align="right" colspan= "2"></td>
							  </tr>
							   <tr>
								<td height="25" align="center" colspan= "2"  style = "padding-right:40px;">
									<input type = "submit" value = "search" name = "search" style="border:1px solid #c2c2c2;height:30px;width:70px;background:#999999;color:#fff;font-weight:bold;cursor:hand;pointer:hand;">
								</td>
							  </tr>							
							   <tr>
								<td height="25" align="right" colspan= "2"></td>
							  </tr>	
						  </form>					  
					  </table> 
				  

				  <table><tr><td width="77%" height="25" align="left">
							&nbsp;
							</td></tr></table>
                    <TABLE cellSpacing=1 cellPadding=4 width="97%" align=center border="0">

                      <TBODY>
                      <TR class = "headingrowcolor">
                        <TD class="whiteTxt" width=5%>SNO.</TD>
						<!-- <TD class="whiteTxt" width=5%>&nbsp;</TD> -->
						<TD class="whiteTxt" width=5% nowrap>Project Id</TD>
						<TD class="whiteTxt" width=5% nowrap>Project Name</TD>
						<TD class="whiteTxt" width=20%>Phase, Stage & Label</TD>
                        <TD class="whiteTxt" width=20% nowrap>Builder Name</TD>
                        <TD class="whiteTxt" width=10%>Address</TD>
                        <TD class="whiteTxt" width=15%>Location</TD>

                        <TD class="whiteTxt" width=13% align ="center">Action</TD>
                      </TR>                    

                      {$count = 0}

					  {section name=data loop=$projectDataArr}
						{$count = $count+1}
						{if $count%2 == 0}

							{$color = "bgcolor = '#F7F7F7'"}
						{else}

							{$color = "bgcolor = '#FCFCFC'"}

		               {/if}
		              
						{if $projectDataArr[data].PROJECT_STAGE == 'newProject'}

						{$BG = 'green'}
							{$phse = 'newP'}
						{else if $projectDataArr[data].PROJECT_STAGE=='noStage'}
							{$BG = 'white'}
							{$phse = 'noS'}
						{else if $projectDataArr[data].PROJECT_STAGE=='updationCycle'}
							{$BG = 'yellow'}
							{$phse = 'updation'}
						{/if}


                      <TR  style="background:{$color}">
                        <TD align=center class=td-border>{$count}  </TD>
						<!-- <TD align=left class="td-border">
							<div>
								<input type="checkbox" id="checkit" class="stateSelection" value="{$projectDataArr[data].PROJECT_ID}"><br>
								<input type="hidden" class = "phaseCheck" value = "{$projectDataArr[data].PROJECT_PHASE}"></input>
								<div class="phaseError" style="color:red;"> </div>
							</div>
						</TD>-->
					   <td align=left class=td-border>{$projectDataArr[data].PROJECT_ID}</td>
						{if $projectDataArr[data].PROJECT_STAGE!=""}
						<TD align=left class=td-border style="background:{$BG};"><a style="color:black" href="show_project_details.php?projectId={$projectDataArr[data].PROJECT_ID}" title='{$projectDataArr[data].PROJECT_STAGE}' alt='{$projectDataArr[data].PROJECT_STAGE}'>{$projectDataArr[data].PROJECT_NAME}  </a> </TD>
																							{else}
						<TD align=left class=td-border style="background:{$BG};">{$projectDataArr[data].PROJECT_NAME}</TD>
																									{/if}

																				{if $phse =='updation'}

																					{foreach from=$UpdationArr key=k item=v}
											 											{if ($projectDataArr[data].UPDATION_CYCLE_ID)==($UpdationArr[$k].UPDATION_CYCLE_ID)}
																								<TD align=left class=td-border nowrap>{$projectDataArr[data].PROJECT_STAGE} - {$projectDataArr[data].PROJECT_PHASE} - {$UpdationArr[$k].LABEL}</TD>
																						{/if}
																					{/foreach}
																					{if $projectDataArr[data].UPDATION_CYCLE_ID==null}
																									<TD align=left class=td-border nowrap>{$projectDataArr[data].PROJECT_STAGE} - {$projectDataArr[data].PROJECT_PHASE} - No Label</TD>
																					{/if}
																				{else}
																					<TD align=left class=td-border nowrap>{$projectDataArr[data].PROJECT_STAGE} - {$projectDataArr[data].PROJECT_PHASE} - No Label</TD>
																				{/if}
                        <TD align=left class=td-border nowrap>{$projectDataArr[data].BUILDER_NAME}</TD>
                        <TD align=left class=td-border>
                        	{$projectDataArr[data].PROJECT_ADDRESS}
                        </TD>
                        <TD align=left class=td-border>
                        	{$projectDataArr[data].PROJECT_ADDRESS}
                        </TD>

                        <TD  class="td-border" align=left nowrap = 'nowrap'>

						
							<select name = "option_value" onchange = "updatelink(this.value);" style='width:180px;'>
								<option value = "">Select Option</option>
								{if $projectDataArr[data].PROJECT_STAGE!="noStage"}
									<option value = "show_project_details.php?projectId={$projectDataArr[data].PROJECT_ID}">View Project</option>
								{/if}
								{if in_array($projectDataArr[data].PROJECT_PHASE,$arrProjEditPermission)}
								<option value = "add_project.php?projectId={$projectDataArr[data].PROJECT_ID}">Edit Project</option>
								<option value = "add_specification.php?projectId={$projectDataArr[data].PROJECT_ID}&edit=edit">Edit/Add Specification and Amenities</option>
								<option value = "image_edit.php?projectId={$projectDataArr[data].PROJECT_ID}&edit=edit">Edit Plans</option>
								<option value = "project_img_add.php?projectId={$projectDataArr[data].PROJECT_ID}&edit=edit">Add Plans</option>
								<option value = "add_apartmentConfiguration.php?projectId={$projectDataArr[data].PROJECT_ID}&edit=edit">Add/Edit Configuration</option>
								<option value = "edit_floor_plan.php?projectId={$projectDataArr[data].PROJECT_ID}&edit=edit">Edit Floor Plans</option>
								<option value = "add_apartmentFloorPlan.php?projectId={$projectDataArr[data].PROJECT_ID}&edit=edit">Add Floor Plans</option>
								<option value = "project_other_price.php?projectId={$projectDataArr[data].PROJECT_ID}&edit=edit">Edit Other Price</option>
								<option value = "tower_detail_delete.php?projectId={$projectDataArr[data].PROJECT_ID}&edit=edit">Add/Edit/Delete Tower Detail</option>
                                <option value = "phase.php?projectId={$projectDataArr[data].PROJECT_ID}">Add Phase</option>
                                <option value = "phase_edit.php?projectId={$projectDataArr[data].PROJECT_ID}">Edit Phase</option>
								{/if}
							</select>

							<select name = "option_value" onchange = "updatelink(this.value);" style='width:180px;'>
								<option value = "">Select Option</option>
								{if in_array($projectDataArr[data].PROJECT_PHASE,$arrProjEditPermission)}
								<option value = "update_price.php?projectId={$projectDataArr[data].PROJECT_ID}">Update Price</option>
								<option value = "add_supply_inventory.php?projectId={$projectDataArr[data].PROJECT_ID}">Update Availability(Supply)</option>
								<option value = "add_tower_construction_status.php?projectId={$projectDataArr[data].PROJECT_ID}">Update Tower Construction</option>
								<option value = "add_project_construction.php?projectId={$projectDataArr[data].PROJECT_ID}">Update Project Construction</option>
								{/if}
							</select>


                          <!--<a href="?projectid={$projectDataArr[data].PROJECT_ID}&mode=delete&page={$page}&sort={$sort}" title="Delete Member" onClick="return chkConfirm();">Delete</a></TD>-->
                      </TR>
                       {/section}

                        {if $NumRows<=0}
	                        <TR><TD colspan="9" class="td-border" align="left">Sorry, no records found.</TD></TR>
                        {/if}

                      <TR><TD colspan="9" class="td-border" align="right">&nbsp;</TD>
                      </TR>

                      </TBODY>

                    </TABLE>

	      </TD>
            </TR>
          </TBODY></TABLE>
        </td></tr>
    </TBODY></TABLE>

<script type="text/javascript">
    var cals_dict = {
      
        "f_trigger_c_from" : "f_date_c_from",
        "f_trigger_c_to" : "f_date_c_to",
       
    };

    $.each(cals_dict, function(k, v) {
        Calendar.setup({
            inputField     :    v,                                 // id of the input field
            //    ifFormat       :    "%Y/%m/%d %l:%M %P",         // format of the input field
            ifFormat       :    "%Y-%m-%d",                        // format of the input field
            button         :    k,                                 // trigger for the calendar (button ID)
            align          :    "Tl",                              // alignment (defaults to "Bl")
            singleClick    :    true,
            showsTime	  :	true
        });
    });
</script>