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
                                                    <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>My Projects</TD>
                                                    <!--<TD align=right colSpan=3><a href="localityadd.php" style=" font-size:15px; color:#1B70CA; text-decoration:none; "><b>Add Locality</b></a></TD>-->
                                                </TR>
                                            </TBODY>
                                        </TABLE>
                                     </TD>
                                </TR>
                                <tr>
                                    <td>
                                        <div>
                                            <table class="tablesorter">
                                                <thead>
                                                    <tr>
                                                        <th>PID</td>
                                                        <th>Project Name</th>
                                                        <th>Builder Name</th>
                                                        <th>City</th>
                                                        <th>Assigned Date</th>
                                                        <th>Assignment Type</th>
                                                        <th>Assignment Status</th>
                                                        <th>Remark</th>
                                                        <th class="{ sorter: false }">&nbsp;</th>

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
                                                                <option selected="selected" value="10">10</option>
                                                                <option value="20">20</option>
                                                                <option value="30">30</option>
                                                                <option value="40">40</option>
                                                            </select>
                                                            <select class="pagenum input-mini" title="Select page number"></select>
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    {foreach from = $assignedProjects key=key item = item}
                                                    <tr>
                                                        <form id={$item['PROJECT_ID']} method="post" onsubmit="return verifyValues({$item['PROJECT_ID']});">
                                                            <input type="hidden" name="projectid" value={$item['PROJECT_ID']}></input>
                                                            <td>{$item['PROJECT_ID']}</td>
                                                            <td>{$item['PROJECT_NAME']}</td>
                                                            <td>{$item['BUILDER_NAME']}</td>
                                                            <td>{$item['CITY']}</td>
                                                            <td>{$item['ASSIGNMENT_DATE']}</td>
                                                            <td>
                                                                {if $item['ASSIGNMENT_COUNT'] gt 1}
                                                                    REVERTED
                                                                {else}
                                                                    ASSIGNED
                                                                {/if}
                                                            </td>
                                                            <td>
                                                                <select name="status">
                                                                    <option value='notAttempted'>Not Attempted</option>
                                                                    <option value='done'>Done</option>
                                                                    <option value='incomplete'>Incomplete</option>
                                                                    <option value='doneExceptInventory'>All But Inventory</option>
                                                                </select>
                                                            </td>
                                                            <td><input name="remark" type="textbox" value="{$item['REMARK']}"></input></td>
                                                            <td>
                                                                <input type="submit" name="submit" value="Save">
                                                            </td>
                                                        </form>
                                                    </tr>
                                                    {/foreach}
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
function verifyValues(formId){
    var id = formId.toString();
    var form = document.getElementById(id);
    
    if(form.status.value === 'notAttempted' && form.remark.value.trim() === ''){
        alert("Please change status or put remerk");
        return false;
    }
    return true;
}
</script>