<link rel="stylesheet" type="text/css" href="tablesorter/css/theme.bootstrap.css">
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.widgets.min.js"></script> 
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="js/tablesorter_default_table.js"></script>


</TD>
</TR>
<TR>
    <TD class="white-bg paddingright10" vAlign=top align=middle bgColor=#ffffff>
        <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
            <TBODY>
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
                    {if $accessDataCollection == ''}
                        <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0>
                            <TBODY>
                                <TR>
                                    <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                                        <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                                                <TR>
                                                    <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Projects in {if $callingFieldFlag == 'survey'}Survey{else}CallCenter{/if}</TD>
                                                    <!--<TD align=right colSpan=3><a href="localityadd.php" style=" font-size:15px; color:#1B70CA; text-decoration:none; "><b>Add Locality</b></a></TD>-->
                                                </TR>
                                            </TBODY>
                                        </TABLE>
                                     </TD>
                                </TR>
                                {if !empty($message)}
                                    <tr>
                                        <TD>
                                            <div class="{$message['type']}" style="text-align: left;">
                                                {$message['content']}
                                            </div>
                                        </TD>
                                    </tr>
                                {/if}
                                <tr>
                                    <td colspan="0">
                                        <div style="height: 31px; float: left">
                                            <form method="post" onsubmit="return verifyDataGetForm();">
                                                <select name="cityId" id = "cityId" class="cityId" onchange="updateSuburbDropdown(this.value, 'suburbId', 'localityId');" STYLE="width: 150px">
                                                        <option value =''>Select City</option>
                                                        <option value ='-1' {if $selectedCity == -1} selected="selected" {/if}>All City</option>
                                                        {foreach from = $CityDataArr key=key item = item}
                                                        <option {if $selectedCity == {$key}} selected="selected" {/if} value ='{$key}'>{$item}</option>
                                                        {/foreach}
                                                        {if $callingFieldFlag != 'survey' || ($callingFieldFlag == 'survey' && $department == 'ADMINISTRATOR')}
                                                            <option value = "othercities" {if $city == "othercities"} selected  {else}{/if}>Other cities</option>
                                                        {/if}
                                                </select>
                                                <select name="suburbId" id = "suburbId" class="suburbId" onchange="updateLocalityDropdown(this.value, 'localityId');" STYLE="width: 150px">
                                                    <option value="">Select Suburb</option>
                                                    {foreach from = $SuburbDataArr key=key item = item}
                                                        <option {if $selectedSuburb == {$key}} selected="selected" {/if} value ='{$key}'>{$item}</option>
                                                    {/foreach}
                                                </select>
                                                <select name="localityId" id = "localityId" class="localityId" STYLE="width: 150px">
                                                    <option value="">Select Locality</option>
                                                    {foreach from = $LocalityDataArr key=key item = item}
                                                        <option {if $selectedLocality == {$key}} selected="selected" {/if} value ='{$key}'>{$item}</option>
                                                    {/foreach}
                                                </select>
                                                <input class="cityId" STYLE="width: 50px; vertical-align: top;" type="submit" name="submit" value="Get"></input>
                                            </form>
                                        </div>
                                        <div style="float: left; width: 20px; height: 31px;">
                                        </div>
                                        <div style="float: left; height: 31px;">
                                            <form method="post" onsubmit="return verifyAdminSelected();">
                                                <select name="executive" id="executive" STYLE="width: 150px">
                                                    
                                                        <option value =''>Select Exec</option>
                                                        {if $callingFieldFlag == 'survey'}
                                                        {foreach from = $arrSurveyTeamList key= key item = item}
                                                            <option {if $selectedExecutive == $item['ADMINID']} selected="selected" {/if} value ='{$item['ADMINID']}'>{$item['FNAME']} - {$item['WORKLOAD']}</option>
                                                        {/foreach}
                                                        {else}
                                                          {foreach from = $executiveList key = key item = item}
                                                            <option {if $selectedExecutive == $item['ADMINID']} selected="selected" {/if} value ='{$item['ADMINID']}'>{$item['USERNAME']} - {$item['WORKLOAD']}</option>
                                                          {/foreach}  
                                                        {/if}                                                           
                                                        
                                                </select>
                                                <input class="cityId" STYLE="width: 50px; vertical-align: top;" type="submit" name="submit" value="Get"></input>
                                            </form>
                                        </div>
                                        <div style="float: left; width: 20px; height: 31px;">
                                        </div>
                                        <div style="float: left; height: 31px;">
                                            <form method="post" onsubmit="return verifyProjectIds();">
                                                <input name="projectIds" id="projectIds" STYLE="width: 150px" placeholder="Comma Seperated PIDs" value="{$selectedProjectIds}">
                                                </input>
                                                <input class="cityId" STYLE="width: 50px; vertical-align: top;" type="submit" name="submit" value="Get"></input>
                                            </form>
                                        </div>
                                        <div style="float: left; height: 31px;margin-left: 18px">
                                            <a href="?download=true&flag={$callingFieldFlag}" >
                                                <button>
                                                    Download Excel
                                                </button>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <form method="post" action="project-assign.php?flag={$callingFieldFlag}" onsubmit="return verifyChecked()">
                                                <table class="tablesorter">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <input type=checkbox onclick="selectAllCheckBoxes('assign[]', this.checked);">
                                                            </th>
                                                            
                                                            <th style="font-size: 12px" nowrap>PID</td>
                                                            <th style="font-size: 12px" nowrap>Project Name</th>
                                                            <th style="font-size: 12px" nowrap>Builder Name</th>
                                                            <th style="font-size: 12px" nowrap>City</th>
                                                            <th style="font-size: 12px" nowrap>Locality</th>
                                                            <th style="font-size: 12px" nowrap>Project Status</th>
                                                            <th style="font-size: 12px" nowrap>Booking Status</th>
                                                            <th class="filter-select filter-exact" data-placeholder="Pick One" style="font-size: 12px" nowrap>Label</th>
                                                            <th style="font-size: 12px" nowrap>Project Phase</th>
                                                            <th style="font-size: 12px" nowrap>Project Stage</th>
                                                            <th style="font-size: 12px" nowrap>Last AuditDate</th>
                                                            <th style="font-size: 12px" nowrap>Last Worked At</th>
                                                            <th class="filter-select filter-exact" data-placeholder="Pick One" style="font-size: 12px" nowrap>Assignment Type</th>
                                                            <th style="font-size: 12px" nowrap>1st Assigned To</th>
                                                            <th style="font-size: 12px" nowrap>Assigned On</th>
                                                            <th style="font-size: 12px" nowrap>Status</th>
                                                            <th style="font-size: 12px" nowrap>Remark</th>
                                                            <th style="font-size: 12px" nowrap>2nd Assignment</th>
                                                            <th style="font-size: 12px" nowrap>Assigned On</th>
                                                            <th style="font-size: 12px" nowrap>Status</th>
                                                            <th style="font-size: 12px" nowrap>Remark</th>
                                                            <th style="font-size: 12px" nowrap>3rd Assignment</th>
                                                            <th style="font-size: 12px" nowrap>Assigned On</th>
                                                            <th style="font-size: 12px" nowrap>Status</th>
                                                            <th style="font-size: 12px" nowrap>Remark</th>
                                                            <th style="font-size: 12px" nowrap>Last Assignment</th>
                                                            <th style="font-size: 12px" nowrap>Assigned On</th>
                                                            <th style="font-size: 12px" nowrap>Status</th>
                                                            <th style="font-size: 12px" nowrap>Remark</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="21" class="pager form-horizontal" style="font-size:12px;">
                                                                {if $department == 'CALLCENTER' || $department == 'SURVEY'}
                                                                    <input type="submit" name="submit" value="fresh assignement"fresh assigne></input>&nbsp;&nbsp;
                                                                   {if $callingFieldFlag != 'survey'}
                                                                        <input type="submit" name="submit" value="field assignement"></input>&nbsp;&nbsp;
                                                                   {/if}
                                                                {/if}
                                                                <button class="btn first"><i class="icon-step-backward"></i></button>
                                                                <button class="btn prev"><i class="icon-arrow-left"></i></button>
                                                                <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                                                                <button class="btn next"><i class="icon-arrow-right"></i></button>
                                                                <button class="btn last"><i class="icon-step-forward"></i></button>
                                                                <select class="pagesize input-mini" title="Select page size">
                                                                    <option value="10">10</option>
                                                                    <option value="20">20</option>
                                                                    <option value="50">50</option>
                                                                    <option selected="selected" value="100">100</option>
                                                                </select>
                                                                <select class="pagenum input-mini" title="Select page number"></select>
                                                            </th>
                                                        </tr>
                                                    </tfoot>
                                                    <tbody>
                                                    {foreach from = $projectList key=key item = item}
                                                        {if $callingFieldFlag == 'survey' && $item['LAST_DEPARTMENT'] == 'SURVEY'}
                                                            <tr>
                                                                <td><input type="checkbox" name="assign[]" value="{$item['PROJECT_ID']}"></td>
                                                                <td><a href="{$projectPageURL}{$item['PROJECT_ID']}" target="_blank">{$item['PROJECT_ID']}</a></td>
                                                                <td>{$item['PROJECT_NAME']}</td>
                                                                <td>{$item['BUILDER_NAME']}</td>
                                                                <td>{$item['CITY']}</td>
                                                                <td nowrap>{$item['LOCALITY']}</td>
                                                                <td>{$item['PROJECT_STATUS']}</td>
                                                                <td>{$item['BOOKING_STATUS']}</td>
                                                                <td>{$item['LABEL']}</td>
                                                                <td>{$item['PROJECT_PHASE']}</td>
                                                                <td>{$item['PROJECT_STAGE']}</td>
                                                                <td>{$projectLastAuditDate[$item['PROJECT_ID']]}</td>
                                                                <td>{$item['LAST_WORKED_AT']}</td>
                                                                <td>
                                                                    {$assignType1 = $item['ASSIGNMENT_TYPE']|replace:'RevertedFieldField_Assigned-':'RevertedAssigned-'} 
                                                                    {$assignType = $assignType1|replace:'Field':'Unassigned'}
                                                                    {$newType = $assignType|replace:'_lead':''}
                                                                    {$otherType = $newType|replace:'Unassigned_':''}
                                                                    {$otherType2 = $otherType|replace:'RevertedUnassignedAssigned':'Assigned'}
                                                                    {$otherType = $otherType2|replace:'RevertedUnassignedUnassigned':'Unassigned'}
                                                                     
                                                                     {$otherType}
                                                                </td>
                                                                <td>{if $item['leadAssignedType'] == 0}{$item['ASSIGNED_TO'][0]}{else}&nbsp;{/if}</td>
                                                                <td>{if $item['leadAssignedType'] == 0}{$item['ASSIGNED_AT'][0]}{else}&nbsp;{/if}</td>
                                                                <td>{if $item['leadAssignedType'] == 0}{$item['STATUS'][0]}{else}&nbsp;{/if}</td>
                                                                <td>{if $item['leadAssignedType'] == 0}{$item['REMARK'][0]}{else}&nbsp;{/if}</td>
                                                                <td>{if $item['leadAssignedType'] == 0}{$item['ASSIGNED_TO'][1]}{else}&nbsp;{/if}</td>
                                                                <td>{if $item['leadAssignedType'] == 0}{$item['ASSIGNED_AT'][1]}{else}&nbsp;{/if}</td>
                                                                <td>{if $item['leadAssignedType'] == 0}{$item['STATUS'][1]}{else}&nbsp;{/if}</td>
                                                                <td>{if $item['leadAssignedType'] == 0}{$item['REMARK'][1]}{else}&nbsp;{/if}</td>
                                                                <td>{if $item['leadAssignedType'] == 0}{$item['ASSIGNED_TO'][2]}{else}&nbsp;{/if}</td>
                                                                <td>{if $item['leadAssignedType'] == 0}{$item['ASSIGNED_AT'][2]}{else}&nbsp;{/if}</td>
                                                                <td>{if $item['leadAssignedType'] == 0}{$item['STATUS'][2]}{else}&nbsp;{/if}</td>
                                                                <td>{if $item['leadAssignedType'] == 0}{$item['REMARK'][2]}{else}&nbsp;{/if}</td>
                                                                <td>{if $item['leadAssignedType'] == 0}{$item['ASSIGNED_TO'][count($item['ASSIGNED_TO']) -1]}{else}&nbsp;{/if}</td>
                                                                <td>{if $item['leadAssignedType'] == 0}{$item['ASSIGNED_AT'][count($item['ASSIGNED_AT']) -1]}{else}&nbsp;{/if}</td>
                                                                <td>{if $item['leadAssignedType'] == 0}{$item['STATUS'][count($item['STATUS']) -1]}{else}&nbsp;{/if}</td>
                                                                <td>{if $item['leadAssignedType'] == 0}{$item['REMARK'][count($item['STATUS']) -1]}{else}&nbsp;{/if}</td>
                                                            </tr>
                                                        {elseif $callingFieldFlag == 'callcenter' && !strstr($item['ASSIGNMENT_TYPE'],'Field')}
                                                           <tr>
                                                                <td><input type="checkbox" name="assign[]" value="{$item['PROJECT_ID']}"></td>
                                                                <td><a href="{$projectPageURL}{$item['PROJECT_ID']}" target="_blank">{$item['PROJECT_ID']}</a></td>
                                                                <td>{$item['PROJECT_NAME']}</td>
                                                                <td>{$item['BUILDER_NAME']}</td>
                                                                <td>{$item['CITY']}</td>
                                                                <td nowrap>{$item['LOCALITY']}</td>
                                                                <td>{$item['PROJECT_STATUS']}</td>
                                                                <td>{$item['BOOKING_STATUS']}</td>
                                                                <td>{$item['LABEL']}</td>
                                                                <td>{$item['PROJECT_PHASE']}</td>
                                                                <td>{$item['PROJECT_STAGE']}</td>
                                                                <td>{$projectLastAuditDate[$item['PROJECT_ID']]}</td>
                                                                <td>{$item['LAST_WORKED_AT']}</td>
                                                                <td>{$item['ASSIGNMENT_TYPE']}</td>
                                                                <td>{$item['ASSIGNED_TO'][0]}</td>
                                                                <td>{$item['ASSIGNED_AT'][0]}</td>
                                                                <td>{$item['STATUS'][0]}</td>
                                                                <td>{$item['REMARK'][0]}</td>
                                                                <td>{$item['ASSIGNED_TO'][1]}</td>
                                                                <td>{$item['ASSIGNED_AT'][1]}</td>
                                                                <td>{$item['STATUS'][1]}</td>
                                                                <td>{$item['REMARK'][1]}</td>
                                                                <td>{$item['ASSIGNED_TO'][2]}</td>
                                                                <td>{$item['ASSIGNED_AT'][2]}</td>
                                                                <td>{$item['STATUS'][2]}</td>
                                                                <td>{$item['REMARK'][2]}</td>
                                                                <td>{$item['ASSIGNED_TO'][count($item['ASSIGNED_TO']) -1]}</td>
                                                                <td>{$item['ASSIGNED_AT'][count($item['ASSIGNED_AT']) -1]}</td>
                                                                <td>{$item['STATUS'][count($item['STATUS']) -1]}</td>
                                                                <td>{$item['REMARK'][count($item['STATUS']) -1]}</td>
                                                            </tr>
                                                        {/if}    
                                                        
                                                        
                                                        {/foreach}
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                    </td>
                                </TR>
                            </TBODY>
                        </TABLE>
                       {else}
                            <font color = "red">No Access</font>
                       {/if}
                    </TD>
                </TR>
            </TBODY>
        </TABLE>
    </TD>
</TR>

<script>
function verifyChecked(){
    var all = document.getElementsByName('assign[]')
    var flag = false;
    for(i=0; i<all.length; i++){
        flag = flag || all[i].checked
    }
    if(!flag){
        alert("Please select projects to be assigned");
    }
    return flag;
}


function selectAllCheckBoxes(inputName, checked){
    var all = document.getElementsByName(inputName);
    for(var i=0; i<all.length; i++){
        if ($(all[i]).closest('tr').is(":visible")) all[i].checked = checked;
    }
}

function verifyDataGetForm(){
    var city = $(".cityId :selected").val();
    var sub = $(".suburbId :selected").val();
    if (city === ""){
        alert("Please Select City!");
        return false;
    }
    else{
        if(city==2 && sub === ""){
            alert("Please Select Suburb");
            return false;
        }
    }
    return true;
}

function verifyAdminSelected(){
    var id = $("#executive :selected").val();
    if (id === ""){
        alert("Please select Admin!");
        return false;
    }
    return true;
}

function verifyProjectIds(){
    var pids = $("#projectIds").val();
    if (pids.trim().match(/^[1-9]+([0-9, ]+[0-9]+)*$/)){
        return true;
    }
    alert("Please Enter ProjectIds!");
    return false;
}
function updateSuburbDropdown(cityId, suburbSelectboxId, localitySelectboxId)
{
    dataString = 'id='+cityId;
	$.ajax
	({
		type: "POST",
		url: "RefreshSuburb.php",
		data: dataString,
		cache: false,
		success: function(html)
		{
            var sub_loc_html = html.split("break");
            $("."+suburbSelectboxId).html('<option value="">All</option>'+sub_loc_html[0]);
            $("."+localitySelectboxId).html('<option value="">All</option>'+sub_loc_html[1]);
		}
	});


}
function updateLocalityDropdown(suburbId, localitySelectboxId)
{
        var cityId = $("#cityId").val();
        dataString = 'id='+cityId+"&suburb_id="+suburbId;
	$.ajax
	({
		type: "POST",
		url: "RefreshSuburb.php",
		data: dataString,
		cache: false,
		success: function(html)
		{
                    $("."+localitySelectboxId).html('<option value="">All</option>'+html);
		}
	});
}
</script>
