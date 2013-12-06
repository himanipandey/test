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
    xmlHttp.onreadystatechange=stateChanged
    xmlHttp.open("GET",url,true)
    xmlHttp.send(null)
}
function stateChanged()
{
    if (xmlHttp.readyState==4)
    {
        document.getElementById('statusRefresh'+idfordiv).innerHTML=xmlHttp.responseText;
    }
}

function update_locality(ctid)
{
	  $("#localitySelectText").val('');
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
/*******************End Ajax Code*************/

function updatelink(url)
{
    window.location = url;
}

function labelSelect(label){

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

function selectedBuilderValue(builderId) {
    $(".builerUPdate").val(builderId);
}

function localitySelect(loclitySelectVal) {
  $("#localitySelectText").val(loclitySelectVal);
}
$(function() {
  $("#localitySelectText").val();
  localitySelect({$locality});
});
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
				
                            <center>
                        <table width="630" border="0" align="center" cellpadding="0" cellspacing="1" bgColor="#fcfcfc" style = "border:1px solid #c2c2c2;margin: 20px;">
                          <form method = "get" action = "" onsubmit = "return validation();">

                                  <tr bgcolor='#DCDCDC'>
                                          <td height="35" align="center" colspan= "2" style='border-bottom:1px solid #c2c2c2;color:#333;'>
                                                  <b>Search Projects</b>
                                          </td>
                                  </tr>
                                  <tr>
                                          <td height="25" align="center" colspan= "2">
                                                  <span id = "errmsg" style = "display:none;"><font color = "red">Please select atleast one field</font></span>
                                                  {if $errorMsg} {$errorMsg} {/if}
                                          </td>
                                  </tr>
                                  <tr>
                                          <td align="right" style = "padding-left:20px;" width='35%'><b>City:</b></td>
                                          <td align="left" style = "padding-left:20px;" width='65%'>
                                                  <select name = 'city' id = "city" onchange = "update_locality(this.value);">
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
                                              <select name = 'locality' id = "locality" onchange="localitySelect(this.value);">
                                                <option value = "">Select Locality</option>
                                                {foreach from = $getLocality item = value}
                                                    <option value = "{$value->locality_id}" 
                                                    {if $locality == $value->locality_id} selected {/if}>{$value->label}</option>
                                                {/foreach}
                                              </select>
                                          </span>
                                          </td>
                                           <input id="localitySelectText" type="hidden" name="locality" />
                                   </tr>
                                     <tr><td>&nbsp;</td></tr>
                                     <tr>
                                          <td align="right" style = "padding-left:20px;"><b>Builder:</b></td>
                                          <td align="left" style = "padding-left:20px;">
                                            <select name = 'builder' id = "builder" onchange = 'selectedBuilderValue(this.value);'>
                                                <option value = "">Select Builder</option>
                                                {foreach from = $builderList key= key item = val}

                                                        <option value = "{$key}" {if $builder == $key} selected  {else}{/if}>{$val}</option>
                                                {/foreach}
                                            </select>
                                          </td>
                                    </tr>
                                   <tr><td>&nbsp;</td></tr>

                                   <tr>
                                        <td align="right" style = "padding-left:20px;"><b>Phase</b></td>
                                        <td align="left" style = "padding-left:20px;">
                                            <span id = "BuilderList">
                                            <select name = 'stage' id = "stage" >
                                                <option value = "">Select Phase</option>
                                                {foreach from = $getProjectStages item = stages}
                                                    <option value="{$stages->id}" {if $stage == $stages->id} selected{/if}>
                                                       {$stages->name}
                                                    </option>
                                                {/foreach}
                                            </select>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>
                                     <tr>
                                          <td width="50" align="right" style = "padding-left:20px;" nowrap><b>Updation Cycle:</b></td>
                                          <td width="50" align="left" style = "padding-left:20px;">
                                          <select name="updationCycle">
                                              <option value="">Select Updation Cycle</option>
                                              {foreach from=$UpdationArr key=k item=v}
                                              <option value = "{$v->updation_cycle_id}" 
                                                  {if "{$updationCycle}" == "{$v->updation_cycle_id}"}
                                                     selected {/if} > {$v->cycle_type}Cycle - {$v->label}
                                              </option>
                                             {/foreach}
                                           </select>
                                          </td>
                                    </tr>
                                   <tr><td>&nbsp;</td></tr>

                                           <tr>
                                          <td align="right" style = "padding-left:20px;"><b>Stage:</b></td>
                                          <td align="left" style = "padding-left:20px;">
                                              <span id = "BuilderList">
                                              <select name = 'phase' id = "phase" >
                                                  <option value=''>Select Stage</option>
                                              {foreach from = $getProjectPhases item = phases}
                                                  <option value="{$phases->id}" {if $phase == $phases->id} selected{/if}>
                                                     {if $phases->name == 'NewProject'} NewProject Audit {else}{$phases->name}{/if}
                                                  </option>
                                              {/foreach}
                                              </select>
                                              </span>
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
                                              <option value="1" {if in_array(1,$Availability)}selected {else} {/if}>Inventory Not Available</option>
                                              <option value="2" {if in_array(2,$Availability)}selected {else} {/if}>Inventory Available</option>
                                              <option value="3" {if in_array(3,$Availability)}selected {else} {/if}>Data Not Available</option>
                                           </select>
                                          </td>
                                    </tr>
                                  <tr><td>&nbsp;</td></tr>

                                    <tr>
                                         <td width="50" align="right" style = "padding-left:20px;" nowrap><b>Residential:</b></td>
                                        <td width="50" align="left" style = "padding-left:20px;">
                                        <select name="Residential" id="Residential" >
                                          <option value="">Select</option>
                                          <option value="Residential" {if $Residential == 'Residential'}selected {else}{/if}>Residential</option>
                                          <option value="NonResidential" {if $Residential == 'NonResidential'}selected {else}{/if}>Non Residential</option>
                                       </select>
                                        </td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>
                                    <tr>
                                         <td width="50" align="right" style = "padding-left:20px;" nowrap><b>Township:</b></td>
                                        <td width="50" align="left" style = "padding-left:20px;">
                                        <select name="townshipId" id="townshipId" >
                                          <option value="">Select Township</option>
                                           {foreach from = $arrTownshipDetail key = key item = value}
                                               <option value="{$key}" {if $key == $townshipId} selected {/if}>{$value} </option>
                                           {/foreach}
                                       </select>
                                        </td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>

                                          <tr>
                                          <td align="right" style = "padding-left:20px;"><b>Active:</b></td>
                                          <td align="left" style = "padding-left:20px;">
                                              <select name="Active[]" id="Active" class="field" multiple>
                                                   <option value ="" >Select</option>
                                                   <option {if in_array('Inactive',$Active)} selected {/if} value="Inactive">Inactive on both Website and IS DB</option>
                                                   <option {if in_array('Active',$Active)} selected {/if} value="Active">Active on both Website and IS DB</option>
                                                   <option {if in_array('ActiveInCms',$Active)} selected{/if}  value="ActiveInCms">Active In Cms</option>
                                               </select>
                                          </td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>
                                    <tr>
                                          <td align="right" style = "padding-left:20px;"><b>Project Status:</b></td>
                                          <td align="left" style = "padding-left:20px;">
                                                  <select name="Status[]" id="Status" class="fieldState" multiple>
                                                          <option value="">Select</option>
                                                          {foreach from = $projectStatus key = key item = value}
                                                                  <option value="{$key}" {if in_array($key,$Status)} selected {/if}>{$value} </option>
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
                                    <input type="hidden" name = "builder" class = "builerUPdate">
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
                        <TD class="whiteTxt" width=15%>Active</TD>

                        <TD class="whiteTxt" width=13% align ="center">Action</TD>
                      </TR>                    

                      {$count = 0}
                      {foreach from = $getSearchResult key = k item = value}
                        {$count = $count+1}
                        {if $count%2 == 0}
                            {$color = "bgcolor = '#F7F7F7'"}
                        {else}
                            {$color = "bgcolor = '#FCFCFC'"}
                       {/if}

                        {if $value->stage_name == 'NewProject'}

                        {$BG = 'green'}
                            {$phse = 'newP'}
                        {else if $value->stage_name=='NoStage'}
                            {$BG = 'white'}
                            {$phse = 'noS'}
                        {else if $value->stage_name=='SecondaryPriceCycle'}
                            {$BG = '#A9A9F5'}
                            {$phse = 'updation'}

                        {else if $value->stage_name=='UpdationCycle'}
                            {$BG = 'yellow'}
                            {$phse = 'updation'}
                        {/if}


                  <TR  style="background:{$color}">
                   <TD align=center class=td-border>{$count}  </TD>
                   <td align=left class=td-border>{$value->project_id}</td>
                    {if $value->stage_name!=""}
                    <TD align=left class=td-border style="background:{$BG};">
                        <a style="color:black" href="show_project_details.php?projectId={$value->project_id}"
                           title='{$value->stage_name}' alt='{$value->stage_name}'>
                            {$value->project_name}  </a> </TD>
                                                                                                                                                            {else}
                    <TD align=left class=td-border style="background:{$BG};">{$value->project_name}</TD>
                                                                                                                                                                            {/if}
                    {if $phse =='updation'}
                        {foreach from=$UpdationArr key=k item=v}
                            {if ($value->updation_cycle_id)==($v->updation_cycle_id)}
                               <TD align=left class=td-border nowrap>{$value->stage_name} - 
                                   {$value->phase_name} - {$v->label}</TD>
                            {/if}
                        {/foreach}
                        {if $value->updation_cycle_id==null}
                            <TD align=left class=td-border nowrap>{$value->stage_name} - 
                                {$value->phase_name} - No Label</TD>
                        {/if}
                    {else}
                        <TD align=left class=td-border nowrap>{$value->stage_name} - 
                            {$value->phase_name} - No Label</TD>
                    {/if}
                    <TD align=left class=td-border nowrap>{$value->builder_name}</TD>
                    <TD align=left class=td-border nowrap>
                            {$value->project_address}
                    </TD>

                    <td align=left class=td-border nowrap valign = "top"> 
                        {if $value->status == 'Inactive'}Inactive on both Website and IS DB{/if}
                        {if $value->status == 'Active'}Active on both Website and IS DB{/if}
                        {if $value->status == 'ActiveInCms'}Active In Cms{/if}
                    </td>

                    <TD  class="td-border" align=left nowrap = 'nowrap'>
                            <select name = "option_value" onchange = "updatelink(this.value);" style='width:180px;'>
                             <option value = "">Select Option</option>
                            {if $value->stage_name!="NoStage"}
                                    <option value = "show_project_details.php?projectId={$value->project_id}">View Project</option>
                            {/if}
                            {if in_array($value->phase_name,$arrProjEditPermission)}
                                <option value = "add_project.php?projectId={$value->project_id}">Edit Project</option>
                                <option value = "add_specification.php?projectId={$value->project_id}&edit=edit">Edit/Add Specification and Amenities</option>
                                <option value = "image_edit.php?projectId={$value->project_id}&edit=edit">Edit Plans</option>
                                <option value = "project_img_add.php?projectId={$value->project_id}&edit=edit">Add Plans</option>
                                <option value = "add_apartmentConfiguration.php?projectId={$value->project_id}&edit=edit">Add/Edit Configuration</option>
                                <option value = "edit_floor_plan.php?projectId={$value->project_id}&edit=edit">Edit Floor Plans</option>
                                <option value = "add_apartmentFloorPlan.php?projectId={$value->project_id}&edit=edit">Add Floor Plans</option>
                                <option value = "project_other_price.php?projectId={$value->project_id}&edit=edit">Edit Other Price</option>
                                <option value = "tower_detail_delete.php?projectId={$value->project_id}&edit=edit">Add/Edit/Delete Tower Detail</option>
                                <option value = "phase.php?projectId={$value->project_id}">Add Phase</option>
                                <option value = "phase_edit.php?projectId={$value->project_id}">Edit Phase</option>
                             {/if}
                            </select>

                            <select name = "option_value" onchange = "updatelink(this.value);" style='width:180px;'>
                                    <option value = "">Select Option</option>
                                    {if in_array($value->phase_name,$arrProjEditPermission)}
                                        <option value = "/new/price?projectId={$value->project_id}">Update Price</option>
                                        <option value = "/new/availability/{$value->no_phase_id}/edit">Update Availability(Supply)</option>
                                        <option value = "project_img_add.php?projectId={$value->project_id}&edit=edit">Add Construction Image</option>
                                        {if $value->stage_name == 'SecondaryPriceCycle'}
                                         <option value = "secondary_price.php?projectId={$value->project_id}">Update Project Secondary Price</option>
                                        {/if}
                                    {else}
                                        <option value = "project_img_add.php?projectId={$value->project_id}&edit=edit&auth=auth">Add Construction Image</option>
                                    {/if}
                            </select>


                      <!--<a href="?projectid={$value->project_id}&mode=delete&page={$page}&sort={$sort}" title="Delete Member" onClick="return chkConfirm();">Delete</a></TD>-->
                  </TR>
                   {/foreach}

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
