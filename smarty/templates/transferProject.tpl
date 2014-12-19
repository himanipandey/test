<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>
<script>
function downloadExcel(phase,stage,cityArr)
{
	$('#current_dwnld_phase').val(phase);
	$('#current_dwnld_stage').val(stage);
        $('#dwnld_city').val(cityArr);
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
      document.getElementById('statusRefresh'+idfordiv).innerHTML=xmlHttp.responseText;
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

function showHidePhase(phaseName,stageName, id)
{
var checkedVals = $('.showHideCls:checkbox:checked').map(function() {
    return $(this).attr('rel');
}).get();
var stringVal = checkedVals.join(",");

var prevalue=stringVal.split(","), sum = 0;
for (var i=0;i<prevalue.length;i++){
    sum += parseInt(prevalue[i]); //<--- Use a parseInt to cast it or use parseFloat

 }
 if(parseInt(sum) >= 4000){
 alert("Maximum 4000 projects can be selected!");
 document.getElementById(id).checked = false;
  return false; 
} 
   //$("#maxProject").val(sum);
    var phaseNameList = '';
    $('.showHideCls:checked').each(function(){
        phaseNameList = phaseNameList+"#"+$(this).val();
    });
    if(phaseNameList.search("Audit2") != -1)
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
function localitySelect(loclitySelectVal) {
  $("#localitySelectText").val(loclitySelectVal);
}
$(function() {
  $("#localitySelectText").val();
  localitySelect({$locality});
});
</script>
<form name='frmdownload' method='post' action='ajax/downloadProject.php'>
<input type='hidden' name='dwnld_city' id='dwnld_city' value="{$_POST['city']}">
<input type='hidden' name='dwnld_mode' id='dwnld_mode' value="{$_POST['mode']}">
<input type='hidden' name='dwnld_builder' id='dwnld_builder' value="{$_POST['builder']}">
<input type='hidden' name='dwnld_phase' id='dwnld_phase' value="{$_POST['phase']}">
<input type='hidden' name='dwnld_stage' id='dwnld_stage' value="{$_POST['stage']}">
<input type='hidden' name='dwnld_Residential' id='dwnld_Residential' value="{$_POST['Residential']}">
<input type='hidden' name='dwnld_Availability' id='dwnld_Availability' value="{implode(",",$_POST['Availability'])}">
<input type='hidden' name='dwnld_Active' id='dwnld_Active' value="{implode(",",$_POST['Active'])}">
<input type='hidden' name='dwnld_Status' id='dwnld_Status' value="{implode("','",$_POST['Status'])}">
<input type='hidden' name='dwnld_exp_supply_date_from' id='dwnld_exp_supply_date_from' value="{$_POST['exp_supply_date_from']}">
<input type='hidden' name='dwnld_exp_supply_date_to' id='dwnld_exp_supply_date_to' value="{$_POST['exp_supply_date_to']}">
<input type='hidden' name='dwnld_project_name' id='dwnld_project_name' value="{$_POST['project_name']}">
<input type='hidden' name='dwnld_projectId' id='dwnld_projectId' value="{$_POST['projectId']}">
<input type='hidden' name='dwnld_search' id='dwnld_search' value="{$_POST['search']}">
<input type='hidden' name='dwnld_transfer' id='dwnld_transfer' value="{$_POST['transfer']}">
<input type='hidden' name='dwnld_updationCycle' id='dwnld_transfer' value="{$_POST['updationCycle']}">
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
                    {if $accessBulkProject == ''}
                            <form name='search' method="post">
                                    <center><table width="530" border="0" align="left" cellpadding="0" cellspacing="1" bgColor="#fcfcfc" style = "border:1px solid #c2c2c2;margin: 20px;">
                                          <tr bgcolor='#DCDCDC'>
                                            <td height="35" align="center" colspan= "2" style='border-bottom:1px solid #c2c2c2;color:#333;'>
                                               <b>Search Projects</b>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td height="25" align="center" colspan= "2">
                                                <span id = "errmsg" style = "display:none;"><font color = "red">Please select atleast one field</font></span>
                                                 {if $errorMsg}<br/><span id = "errmsg" ><font color = "red"> {$errorMsg} </font></span>{/if}
                                            </td>
                                          </tr>
                                          <tr>
                                      <td align="right" style = "padding-left:20px;" height='35' valign = "top"><b>City:</b></td>
                                      <td align="left" style = "padding-left:20px;" width='65%'>
                                          <select name = 'city[]' id = "city" multiple  style='width:220px;border:1px solid #c2c2c2;padding:3px;height:180px;'>
                                              <option value = "">Select City</option>
                                              {foreach from = $citylist key= key item = val}
                                                  <option value = "{$key}" {if in_array($key,$city)} selected  {else}{/if}>{$val}</option>
                                              {/foreach}
                                              <option value = "othercities" {if in_array('othercities',$city)} selected  {else}{/if}>Other cities</option>
                                          </select>
                                      </td>
                                  </tr>

                                  <tr  bgcolor='#fcfcfc'>
                                      <td align="right" style = "padding-left:20px;" height='35'><b>Builder:</b></td>
                                      <td align="left" style = "padding-left:20px;" height='35'>
                                          <select name = 'builder' id = "builder"  style='width:220px;border:1px solid #c2c2c2;padding:3px;height:28px;'>
                                           <option value = "">Select Builder</option>
                                           {foreach from = $builderList key= key item = val}
                                              <option value = "{$key}" {if $builder == $key} selected {/if}>{$val}</option>
                                           {/foreach}
                                         </select>
                                      </td>
                                 </tr>
                                  <tr>
                                    <td align="right" style = "padding-left:20px;" height='35'><b>Phase:</b></td>
                                    <td align="left" style = "padding-left:20px;" height='35'>
                                        <select name = 'stage' id = "stage" onchange = "refreshUpdationCycle(this.value);">
                                          <option value = "">Select Phase</option>
                                          {foreach from = $getProjectStages item = stages}
                                              <option value="{$stages->id}" {if $stage == $stages->id} selected{/if}>
                                                 {$stages->name}
                                              </option>
                                          {/foreach}
                                      </select>
                                    </td>
                                   </tr>
                                   <tr>
                                        <td width="50" align="right" style = "padding-left:20px;" height='35' nowrap><b>Updation Cycle:</b></td>
                                        <td width="50" align="left" style = "padding-left:20px;">
                                            <select name="updationCycle" id = "updationCycle">
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
                                  <tr bgcolor='#fcfcfc'>
                                      <td align="right" style = "padding-left:20px;" height='35'><b>Stage:</b></td>
                                      <td align="left" style = "padding-left:20px;" height='35'>
                                         <select name = 'phase' id = "phase"  style='width:220px;border:1px solid #c2c2c2;padding:3px;height:28px;'>
                                            <option value = "">Select Stage</option>
                                           {foreach from = $getProjectPhases item = phases}
                                               <option value="{$phases->id}" {if $phase == $phases->id} selected{/if}>
                                                  {if $phases->name == 'NewProject'} NewProject Audit {else}{$phases->name}{/if}
                                               </option>
                                           {/foreach}									
                                         </select>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td width="50" align="right" style = "padding-left:20px;" height='35' nowrap><b>Residential:</b></td>
                                      <td width="50" align="left" style = "padding-left:20px;" height='35'>
                                      <select name="Residential" id="Residential"  style='width:220px;border:1px solid #c2c2c2;padding:3px;height:28px;'>
                                             <option value="">Select</option>
                                             <option value="Residential" {if {$Residential}=='Residential'}selected{/if}>Residential</option>
                                             <option value="NonResidential" {if {$Residential}=='NonResidential'}selected{/if}>Non Residential</option>
                                      </select>
                                      </td>
                                  </tr>
                                     <tr bgcolor='#fcfcfc'>
                                           <td width="50" align="right" style = "padding-left:20px;" height='35' nowrap><b>Availability:</b></td>
                                           <td width="50" align="left" style = "padding-left:20px;">
                                           <select name="Availability[]" id="Avail" multiple style='width:260px;border:1px solid #c2c2c2;padding:3px;'>
                                                                   <option value="">Select Availability</option>
                                                                   <option value="1" {if in_array(1,$Availability)}selected{/if}>Inventory Not Available</option>
                                                                   <option value="2" {if in_array(2,$Availability)}selected{/if}>Inventory Available</option>
                                                                   <option value="3" {if in_array(3,$Availability)}selected{/if}>Data Not Available</option>

                                                            </select>
                                           </td>
                                     </tr>					
                                   
                                   <tr bgcolor='#fcfcfc'>
                                      <td align="right" style = "padding-left:20px;" height='35' valign ="top"><b>Active:</b></td>
                                      <td align="left" style = "padding-left:20px;">
                                        <select name="Active[]" id="Active" class="field" multiple>
                                            <option value ="" >Select</option>
                                            <option {if in_array('Inactive',$Active)} selected {/if} value="Inactive">Inactive on both Website and IS DB</option>
                                            <option {if in_array('Active',$Active)} selected {/if} value="Active">Active on both Website and IS DB</option>
                                            <option {if in_array('ActiveInCms',$Active)} selected{/if}  value="ActiveInCms">Active In Cms</option>
                                        </select>
                                      </td>
                                     </tr>
                                     <tr>
                                      <td align="right" style = "padding-left:20px;" height='35'><b>Project Status:</b></td>
                                      <td align="left" style = "padding-left:20px;">
                                          <select name="Status[]" id="Status" class="fieldState" multiple>
                                              <option value="">Select</option>
                                              {foreach from = $projectStatus key = key item = value}
                                                      <option value="{$key}" {if in_array($key,$Status)} selected {/if}>{$value} </option>
                                              {/foreach}
                                         </select>
                                      </td>
                                     </tr>
                                     <tr> 
                                       <td align="right" style = "padding-left:20px;" height='35'><b>Expected Supply Date:</b></td>
                                       <td align="left" style = "padding-left:20px;">
                                           From:<input name="exp_supply_date_from" value="{$exp_supply_date_from}" type="text" class="formstyle2" id="f_date_c_from" size="5" />  <img src="images/cal_1.jpg" id="f_trigger_c_from" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                                          &nbsp; To:<input name="exp_supply_date_to" value="{$exp_supply_date_to}" type="text" class="formstyle2" id="f_date_c_to" size="5" />  <img src="images/cal_1.jpg" id="f_trigger_c_to" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
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
                               <table width="502" align="left" cellpadding="0" cellspacing="1" bgColor="#c2c2c2" style = "margin: 20px;border:1px solid #c2c2c2;">
                               <tr bgcolor='#ffffff'><td height=28 width='40'>&nbsp;</td><td align='center'><b>SNo</b></td><td align='center'><b>Count</b></td><td align='center'><b>Project Phase</b></td><td align='center'><b>Project Stage</b></td><td align='center'><b>Download</b></td></tr>
                               {$ctrl = 1}
                               {$flagcheck=0}
                               {$totcnt = 0}
                               {$arrProjectVStage = array()}
                               {$arrProjectVPhase = array()}
                               {if count($projectDataArr)>0}
                               {foreach from=$projectDataArr key=key item=arrVal}				  	  
                                     <tr bgcolor='#ffffff'>
                                             <td align='center' width='40' height=30>
                                                &nbsp;
                                                {if count($forceMigrateModule)>0}
                                                    {$phaseName = $arrVal['PROJECT_PHASE']}
                                                    {$stageName = $arrVal['PROJECT_STAGE']}
                                                    <input rel = "{$arrVal['CNT']}" class = "showHideCls" id = "{$ctrl}" type='checkbox' onclick =  "showHidePhase('{$phaseName}','{$stageName}',{$ctrl});" name='selectdata[]' value="{$arrVal['PROJECT_STAGE']}|{$arrVal['PROJECT_PHASE']}" 
                                                    {if in_array("{$arrVal['PROJECT_STAGE']}|{$arrVal['PROJECT_PHASE']}",$selectdata)} checked {/if}
                                                    > 
                                                    {$flagcheck=1}
                                                {else}
                                                    {if $arrVal['PROJECT_STAGE'] == 'NoStage' || $arrVal['PROJECT_STAGE'] == '' || $arrVal['PROJECT_PHASE'] == 'Audit2'} 
                                                        {$phaseName = $arrVal['PROJECT_PHASE']}
                                                        {$stageName = $arrVal['PROJECT_STAGE']}
                                                        <input rel= "{$arrVal['CNT']}" class = "showHideCls" type='checkbox'  id = "{$ctrl}" onclick =  "showHidePhase('{$phaseName}','{$stageName}',{$ctrl});" name='selectdata[]' value="{$arrVal['PROJECT_STAGE']}|{$arrVal['PROJECT_PHASE']}" 
                                                        {if in_array("{$arrVal['PROJECT_STAGE']}|{$arrVal['PROJECT_PHASE']}",$selectdata)} checked {/if}
                                                        > 
                                                        {$flagcheck=1}
                                                    {else}
                                                        -
                                                    {/if}
                                               {/if}
                               </td>
                                             <td align='center'>{$ctrl}</td>
                                             <td align='center'>{$arrVal['CNT']}</td>
                                             <td style='padding-left:5px;'>
                                                     {if $arrVal['PROJECT_STAGE']=='NoStage'} 
                                                        noPhase 
                                                     {else}
                                                        {$arrVal['PROJECT_STAGE']} 
                                                     {/if}
                                             </td>
                                             <td style='padding-left:5px;'>
                                                {if $arrVal['PROJECT_PHASE'] == 'NewProject'} NewProject Audit {else}{$arrVal['PROJECT_PHASE']}{/if}
                                             </td>
                                             <td align='center' style='padding-left:5px;'>
                                                 {$arrProjectVStage[] = $arrVal['PROJECT_STAGE']}
                                                 {$arrProjectVPhase[] = $arrVal['PROJECT_PHASE']}
                                                 <a href='javascript:void(0);' onClick='javascript:downloadExcel("{$arrVal['PROJECT_STAGE']}","{$arrVal['PROJECT_PHASE']}");'><img src='images/excel.png' border='0'></a>
                                             </td>
                                      </tr>
                                     {$ctrl = $ctrl + 1}
                                     {$totcnt = $totcnt + $arrVal['CNT']}
                               {/foreach}
                                <tr bgcolor='#ffffff'><td align='center' height='35' colspan='2'><b>Total Projects</b></td><td align='center'>{$totcnt}</td><td colspan='2'></td>
                               <td align  = "center"><a href='javascript:void(0);' onClick='javascript:downloadExcel("{implode(",",$arrProjectVStage)}","{implode(",",$arrProjectVPhase)}","{implode(",",$city)}");'><img src='images/excel.png' border='0'></a></td>
                               </tr>
                               {else}
                                     <tr bgcolor='#ffffff'><td colspan='6' align='center' valign='middle' height='80'>No record found for the selected search criteria</td></tr>
                               {/if}
                               </table>
                               <br>
                               {if $flagcheck == 1}
                               <table width="502" border="0" align="left" cellpadding="0" cellspacing="1" bgColor="#fcfcfc" style = "border:1px solid #c2c2c2;margin: 20px;">
                                    <tr bgcolor='#DCDCDC'>
                                        <td height="35" align="center" colspan= "2" style='border-bottom:1px solid #c2c2c2;color:#333;'>
                                           <b>Update Remark</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="25" align="center" colspan= "2"  style = "padding:10px;">
                                          <textarea name="bulkRemark" rows=10 cols=60></textarea>
                                        </td>
                                    </tr>	
                                     <tr>
                                        <td height="25" align="center" colspan= "2"  style = "padding:10px;">
                                          <input onclick = "removeExtraCode();" type = "submit" value = "Update Remark" name = "updateRemark" 
                                            style="border:1px solid #c2c2c2;height:30px;width:125px;background:#999999;color:#fff;
                                            font-weight:bold;cursor:hand;pointer:hand;">
                                        </td>
                                    </tr>	
                                </table>
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
                                            <option value="NoStage|0" {if $updatePhasePost == "NoStage|0"} selected {/if}>No Phase</option>
                                            <option value="NewProject|0" {if $updatePhasePost == "NewProject|0"} selected {/if}>New Project</option>
                                            {foreach from=$UpdationArr key=k item=v}
                                              {if $v->cycle_type != 'construction'}
                                                {if $v->updation_cycle_id != $skipUpdationCycle_Id}
                                                    {if ucfirst($v->cycle_type) == 'NewProject'}
                                                        <option value = "{ucfirst($v->cycle_type)}|{$v->updation_cycle_id}"
                                                           {if $updatePhasePost == "{ucfirst($v->cycle_type)}|{$v->updation_cycle_id}"} selected {/if}> 
                                                           {ucfirst($v->cycle_type)} - {$v->label}
                                                        </option>
                                                    {else}
                                                         <option value = "{ucfirst($v->cycle_type)}Cycle|{$v->updation_cycle_id}"
                                                          {if $updatePhasePost == "{ucfirst($v->cycle_type)}Cycle|{$v->updation_cycle_id}"} selected {/if}> 
                                                           {ucfirst($v->cycle_type)}Cycle - {$v->label}
                                                         </option>
                                                   {/if}
                                                {/if}
                                              {/if}
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

                                    <tr>
                                        <td height="20" align="right" colspan= "2"></td>
                                    </tr>
                                    <tr>
                                        <td height="25" align="center" colspan= "2"  style = "padding-right:40px;">
                                          <input onclick = "removeExtraCode();" type = "submit" value = "Transfer" name = "transfer" 
                                            style="border:1px solid #c2c2c2;height:30px;width:70px;background:#999999;color:#fff;
                                            font-weight:bold;cursor:hand;pointer:hand;">
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
                              {else}
                                <font color="red">No Access</font>
                              {/if}          
                            </td>
                                              </tr>
                            </table> 
                                
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
