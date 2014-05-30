<script type="text/javascript" src="js/jquery.js"></script>
<script>
function downloadExcel(phase,stage)
{
	$('#current_dwnld_phase').val(phase);
	$('#current_dwnld_stage').val(stage);
	document.frmdownload.action = "ajax/downloadProjectConst.php";
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
function localitySelect(loclitySelectVal) {
  $("#localitySelectText").val(loclitySelectVal);
}
$(function() {
  $("#localitySelectText").val();
  localitySelect({$locality});
});
</script>

<form name='frmdownload' method='post' action='ajax/downloadProjectConst.php'>
<input type='hidden' name='dwnld_city' id='dwnld_city' value="{$_POST['city']}">
<input type='hidden' name='dwnld_locality' id='dwnld_locality' value="{$_POST['locality']}">
<input type='hidden' name='dwnld_mode' id='dwnld_mode' value="{$_POST['mode']}">
<input type='hidden' name='dwnld_assignRemark' id='dwnld_assignRemark' value="{$_POST['assignRemark']}">
<input type='hidden' name='dwnld_assignStatus' id='dwnld_assignStatus' value="{$_POST['assignStatus']}">
<input type='hidden' name='dwnld_assignCycle' id='dwnld_assignCycle' value="{$_POST['assignCycle']}">
<input type='hidden' name='dwnld_builder' id='dwnld_builder' value="{$_POST['builder']}">
<input type='hidden' name='dwnld_Active' id='dwnld_Active' value="{implode(",",$_POST['Active'])}">
<input type='hidden' name='dwnld_Status' id='dwnld_Status' value="{implode("','",$_POST['Status'])}">
<input type='hidden' name='dwnld_project_name' id='dwnld_project_name' value="{$_POST['project_name']}">
<input type='hidden' name='dwnld_projectId' id='dwnld_projectId' value="{$_POST['projectId']}">
<input type='hidden' name='dwnld_search' id='dwnld_search' value="{$_POST['search']}">
<input type='hidden' name='dwnld_transfer' id='dwnld_transfer' value="{$_POST['transfer']}">
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
                                      <td align="right" style = "padding-left:20px;" height='35'><b>City:</b></td>
                                      <td align="left" style = "padding-left:20px;" width='65%'>
                                          <select name = 'city' id = "city" onchange = "update_locality(this.value);">
                                              <option value = "">Select City</option>
                                              {foreach from = $citylist key= key item = val}
                                                  <option value = "{$key}" {if $city == $key} selected  {else}{/if}>{$val}</option>
                                              {/foreach}
                                              <option value = "othercities" {if $city == "othercities"} selected  {else}{/if}>Other cities</option>
                                          </select>
                                      </td>
                                  </tr>

                                  <tr>
                                        <td align="right"  style = "padding-left:20px;" height='35'><b>Locality:</b></td>
                                        <td align="left" style = "padding-left:20px;">
                                        <span id = "LocalityList">
                                            <select name = 'locality' id = "locality" onchange="localitySelect(this.value);">
                                                <option value = "">Select Locality</option>
                                                {foreach from = $getLocality item = value}
                                                    <option value = "{$value->locality_id}" 
                                                    {if $locality == $value->locality_id} selected {/if} >{if $city == "othercities"}{$value->cityname} - {/if}{$value->label}</option>
                                                {/foreach}
                                              </select>
                                        </span>
                                        </td>
                                  <input type="hidden" name = "locality" id = "localitySelectText" value="{$locality}">
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
                                      <td align="right" style = "padding-left:20px;" height='35'><b>Assignment Status:</b></td>
                                      <td align="left" style = "padding-left:20px;">
                                          <select name="assignStatus" id="assignStatus" class="fieldState">
                                              <option value="">Select</option>
                                              <option value="complete" {if $assignStatus == 'complete'}selected{/if}>Complete</option>
                                              <option value="incomplete" {if $assignStatus == 'incomplete'}selected{/if}>Incomplete</option>
                                              <option value="notAttempted" {if $assignStatus == 'notAttempted'}selected{/if}>Not Attempted</option>
                                              
                                         </select>
                                      </td>
                                     </tr>
                                     
                                     <tr>
                                      <td align="right" style = "padding-left:20px;" height='35'><b>Assignment Remark:</b></td>
                                      <td align="left" style = "padding-left:20px;">
                                          <select name="assignRemark" id="assignRemark" class="fieldState">
                                              <option value="">Select</option>
                                              <option value="done" {if $assignRemark == 'done'}selected{/if}>Done</option>
                                              <option value="Latest Image not Available" {if $assignRemark == 'Latest Image not Available'}selected{/if}>Latest Image not Available</option>
                                              <option value="Image not found" {if $assignRemark == 'Image not found'}selected{/if}>Image not found</option>
                                              <option value="Website not Open" {if $assignRemark == 'Website not Open'}selected{/if}>Website not Open</option>
                                              <option value="Project Now Ready to Move" {if $assignRemark == 'Project Now Ready to Move'}selected{/if}>Project Now Ready to Move</option>
                                         </select>
                                      </td>
                                     </tr>
                                     
                                     <tr>
                                      <td align="right" style = "padding-left:20px;" height='35'><b>Assignment Cycle:</b></td>
                                      <td align="left" style = "padding-left:20px;">
                                          <select name="assignCycle" id="assignCycle" class="fieldState">
                                              <option value="">Select</option>
                                              {foreach from = $UpdationArr key = key item = item}
                                                 <option value="{$item->updation_cycle_id}" {if $assignCycle == $item->updation_cycle_id}selected{/if}>{$item->label}</option>
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
                               <table width="502" align="left" cellpadding="0" cellspacing="1" bgColor="#c2c2c2" style = "margin: 20px;border:1px solid #c2c2c2;">
                               <tr bgcolor='#ffffff'><td height=28 width='40'>&nbsp;</td><td align='center'><b>SNo</b></td><td align='center'><b>Projects Count</b></td><td align='center'><b>Download</b></td></tr>
                               {$ctrl = 1}
                               {$flagcheck=0}
                               {$totcnt = 0}
                               {if $projectDataArr[0]['CNT']>0}
                                   {$flagcheck=1}
                               {foreach from=$projectDataArr key=key item=arrVal}				  	  
                                     <tr bgcolor='#ffffff'>
                                             <td align='center' width='40' height=30>
                                                <input class = "showHideCls" type='checkbox' onclick =  "showHidePhase('{$phaseName}','$stageName');" name='selectdata[]' value="{$arrVal['PROJECT_STAGE']}|{$arrVal['PROJECT_PHASE']}" 
                                                        {if in_array("{$arrVal['PROJECT_STAGE']}|{$arrVal['PROJECT_PHASE']}",$selectdata)} checked {/if}
                                                        > 
                                            </td>
                                             <td align='center'>{$ctrl}</td>
                                             <td align='center'>{$arrVal['CNT']}</td>

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
                                    <td width="75" align="right" style = "padding-left:20px;"><b>Select Construction Cycle:</b></td>
                                    <td width="25" align="left" style = "padding-left:20px;">
                                    <span id = "showHidePhs">
                                        <select name="updateConst" id="updateConst" class="updateConst" style = "margin:5px;width:220px;border:1px solid #c2c2c2;padding:3px;height:28px;">									
                                            <option value="">Select Cycle</option>
                                                {if $UpdationCycleCurrent->updation_cycle_id != $skipUpdationCycle_Id}
                                                    <option value = "{ucfirst($UpdationCycleCurrent->cycle_type)}Cycle|{$UpdationCycleCurrent->updation_cycle_id}"
                                                    {if $updatePhasePost == "{ucfirst($UpdationCycleCurrent->cycle_type)}Cycle|{$UpdationCycleCurrent->updation_cycle_id}"} selected {/if}> 
                                                        {ucfirst($UpdationCycleCurrent->cycle_type)}Cycle - {$UpdationCycleCurrent->label}
                                                    </option>
                                                {/if}
                                            	
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
                                     
                            </td>
                                              </tr>
                            </table> 

