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
                        <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0>
                            <TBODY>
                                <TR>
                                    <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                                        <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                                                <TR>
                                                    <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Projects in Phase DCCallCenter</TD>
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
                                                <select name="cityId" id = "cityId" class="cityId" onchange="updateSuburbDropdown(this.value, 'suburbId');" STYLE="width: 150px">
                                                        <option value =''>Select City</option>
                                                        {foreach from = $CityDataArr key=key item = item}
                                                        <option {if $selectedCity == {$key}} selected="selected" {/if} value ='{$key}'>{$item}</option>
                                                        {/foreach}
                                                </select>
                                                <input class="cityId" STYLE="width: 50px; vertical-align: top;" type="submit" name="submit" value="Get"></input>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <table style="width:100px;">
                                                <thead>
                                                    <tr>
                                                        <th style="font-size: 12px" rowspan="2">Assignment Type</td>
                                                        <th style="font-size: 12px" colspan="4">Updation Cycle</td>
                                                        <th style="font-size: 12px" colspan="4">New Project</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="font-size: 12px">Total</td>
                                                        <th style="font-size: 12px">Incomplete</td>
                                                        <th style="font-size: 12px">DoneExceptInventory</td>
                                                        <th style="font-size: 12px">NotAttempted</td>
                                                        <th style="font-size: 12px">Total</td>
                                                        <th style="font-size: 12px">Incomplete</td>
                                                        <th style="font-size: 12px">DoneExceptInventory</td>
                                                        <th style="font-size: 12px">NotAttempted</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {foreach from = $projectSummary key=key item = item}
                                                        <tr>
                                                            <td>{$key}</td>
                                                            <td>{$item['updationCycle']['total']}</td>
                                                            <td>{$item['updationCycle']['incomplete']}</td>
                                                            <td>{$item['updationCycle']['doneExceptInventory']}</td>
                                                            <td>{$item['updationCycle']['notAttempted']}</td>
                                                            <td>{$item['newProject']['total']}</td>
                                                            <td>{$item['newProject']['incomplete']}</td>
                                                            <td>{$item['newProject']['doneExceptInventory']}</td>
                                                            <td>{$item['newProject']['notAttempted']}</td>
                                                        </tr>
                                                    {/foreach}
                                                    {if !empty($projectSummary)}
                                                        <tr>
                                                            <td>Total</td>
                                                            <td>{$projectSummaryTotal['updationCycle']['total']}</td>
                                                            <td>{$projectSummaryTotal['updationCycle']['incomplete']}</td>
                                                            <td>{$projectSummaryTotal['updationCycle']['doneExceptInventory']}</td>
                                                            <td>{$projectSummaryTotal['updationCycle']['notAttempted']}</td>
                                                            <td>{$projectSummaryTotal['newProject']['total']}</td>
                                                            <td>{$projectSummaryTotal['newProject']['incomplete']}</td>
                                                            <td>{$projectSummaryTotal['newProject']['doneExceptInventory']}</td>
                                                            <td>{$projectSummaryTotal['newProject']['notAttempted']}</td>
                                                        </tr>
                                                    {/if}
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </TR>
                            </TBODY>
                        </TABLE>
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
function updateSuburbDropdown(cityId, suburbSelectboxId)
{
        dataString = 'id='+cityId
	$.ajax
	({
		type: "POST",
		url: "RefreshSuburb.php",
		data: dataString,
		cache: false,
		success: function(html)
		{
			$("."+suburbSelectboxId).html('<option value="">All</option>'+html);
		}
	});
}
</script>