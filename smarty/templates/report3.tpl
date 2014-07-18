<link rel="stylesheet" type="text/css" href="tablesorter/css/theme.bootstrap.css">
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.widgets.min.js"></script> 
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="js/tablesorter_default_table.js"></script>
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>


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
                                                    <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Report-3</TD>
                                                    <!--<TD align=right colSpan=3><a href="localityadd.php" style=" font-size:15px; color:#1B70CA; text-decoration:none; "><b>Add Locality</b></a></TD>-->
                                                </TR>
                                            </TBODY>
                                        </TABLE>
                                     </TD>
                                </TR>
                                <tr>
                                    <td colspan="0">
                                        <div style="height: 31px; float: left">
                                            <form method="post" onsubmit="return verifyDataGetForm();">
                                                From: <input name="dateFrom" value="{$dateFrom}" type="text" class="formstyle2" id="f_date_c_from" size="5" /><img src="images/cal_1.jpg" id="f_trigger_c_from" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                                                &nbsp;
                                                To: <input name="dateTo" value="{$dateTo}" type="text" class="formstyle2" id="f_date_c_to" size="5" />  <img src="images/cal_1.jpg" id="f_trigger_c_to" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                                                <input class="cityId" STYLE="width: 50px; vertical-align: top;" type="submit" name="submit" value="Get"></input>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <form method="post" action="project-assign.php" onsubmit="return verifyChecked()">
                                                <table class="tablesorter">
                                                    <thead>
                                                        <tr>
                                                            <th style="font-size: 12px">Owner</td>
                                                            <th style="font-size: 12px">Total Assigned projects</th>
                                                            <th style="font-size: 12px">Total Done projects</th>
                                                            <th style="font-size: 12px">Reverted</th>                                            
                                                            <th style="font-size: 12px">Reversal %</th>
                                                            <th style="font-size: 12px">Done ProjectsPer Day</th>
                                                            <th style="font-size: 12px">Done Projects Per Week</th>
                                                            <th style="font-size: 12px">Done Projects Per Month</th>                           
                                                            <th style="font-size: 12px">Done Projects Per Quarter</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="21" class="pager form-horizontal" style="font-size:12px;">
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
                                                        {foreach from = $displayData['excs_work'] key=leadId item = excs}
                                                          <tr style="background:#ccc">
                                                            <td>{$displayData['leads_work'][$leadId]['username']}</td>
                                                            <td>{$displayData['leads_work'][$leadId]['ass']}</td>
                                                            <td>{$displayData['leads_work'][$leadId]['done']}</td>
                                                            <td>{$displayData['leads_work'][$leadId]['revert_count']}</td>
                                                            <td>{$displayData['leads_work'][$leadId]['reversal']}</td>
                                                            <td>{$displayData['leads_work'][$leadId]['proj_per_day']}</td>
                                                            <td>{$displayData['leads_work'][$leadId]['proj_per_week']}</td>
                                                            <td>{$displayData['leads_work'][$leadId]['proj_per_month']}</td>
                                                            <td>{$displayData['leads_work'][$leadId]['proj_per_qtr']}</td>                                                           
                                                          </tr>                                                 
                                                          {foreach from = $excs key=excId item = excDt}
															<tr>
                                                              <td>{$excDt['username']}</td>
                                                              <td>{$excDt['ass']}</td>
                                                              <td>{$excDt['done']}</td>
                                                              <td>{$excDt['revert_count']}</td>
                                                              <td>{$excDt['reversal']}</td>
                                                              <td>{$excDt['proj_per_day']}</td>
                                                              <td>{$excDt['proj_per_week']}</td>
                                                              <td>{$excDt['proj_per_month']}</td>
                                                              <td>{$excDt['proj_per_qtr']}</td>                                                           
                                                            </tr>
														  {/foreach}                                                        
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
