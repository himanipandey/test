<link rel="stylesheet" type="text/css" href="tablesorter/css/theme.bootstrap.css">
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.widgets.min.js"></script> 
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="js/tablesorter_default_table.js"></script>

<script>
function selectAllCheckBoxes(inputName, checked){
    var all = document.getElementsByName(inputName);
    for(var i=0; i<all.length; i++){
        if ($(all[i]).closest('tr').is(":visible")) all[i].checked = checked;
    }
}
</script>

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
                                <tr>
                                    <td>
                                        <div>
                                            <form method="post" action="project-assign.php" onsubmit="return verifyChecked()">
                                                <table class="tablesorter">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <input type=checkbox onclick="selectAllCheckBoxes('assign[]', this.checked);">
                                                            </th>
                                                            
                                                            <th style="font-size: 12px">PID</td>
                                                            <th style="font-size: 12px">Project Name</th>
                                                            <th style="font-size: 12px">Builder Name</th>
                                                            <th style="font-size: 12px">City</th>
                                                            <th style="font-size: 12px">Locality</th>
                                                            <th style="font-size: 12px">Project Phase</th>
                                                            <th style="font-size: 12px">Last Worked At</th>
                                                            <th style="font-size: 12px">Assignment Type</th>
                                                            <th style="font-size: 12px">1st Assigned To</th>
                                                            <th style="font-size: 12px">Assigned On</th>
                                                            <th style="font-size: 12px">Status</th>
                                                            <th style="font-size: 12px">Remark</th>
                                                            <th style="font-size: 12px">2nd Assignment</th>
                                                            <th style="font-size: 12px">Assigned On</th>
                                                            <th style="font-size: 12px">Status</th>
                                                            <th style="font-size: 12px">Remark</th>
                                                            <th style="font-size: 12px">3rd Assignment</th>
                                                            <th style="font-size: 12px">Assigned On</th>
                                                            <th style="font-size: 12px">Status</th>
                                                            <th style="font-size: 12px">Remark</th>

                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="21" class="pager form-horizontal" style="font-size:12px;">
                                                                <input type="submit" name="submit" value="fresh assignement"></input>&nbsp;&nbsp;
                                                                <input type="submit" name="submit" value="field assignement"></input>&nbsp;&nbsp;
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
                                                        {foreach from = $projectList key=key item = item}
                                                        <tr>
                                                            <td><input type="checkbox" name="assign[]" value="{$item['PROJECT_ID']}"></td>
                                                            <td><a href="{$projectPageURL}{$item['PROJECT_ID']}" target="_blank">{$item['PROJECT_ID']}</a></td>
                                                            <td>{$item['PROJECT_NAME']}</td>
                                                            <td>{$item['BUILDER_NAME']}</td>
                                                            <td>{$item['CITY']}</td>
                                                            <td>{$item['LOCALITY']}</td>
                                                            <td>{$item['PROJECT_PHASE']}</td>
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
                                                        </tr>
                                                        {/foreach}
                                                    </tbody>
                                                </table>
                                            </form>
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
</script>