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
                    {if $accessImageAssignmentLead == ''}
                        <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0>
                            <TBODY>
                                <TR>
                                    <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                                        <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                                                <TR>
                                                    <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Project List for Construction Images Update</TD>
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
                                            <form method="post">
                                                Project Id<INPUT type="text" name = "projectId" value="{$projectId}">
                                                Updation Cycle
                                                <select name = "updationCycleId">
                                                    <option value="">Select Updation Cycle</OPTION>
                                                    {foreach from = $updationCycle key= key item = item}
                                                       <option value="{$item->updation_cycle_id}" 
                                                            {if $updationCycleId == $item->updation_cycle_id} selected {/if}>{$item->label}</OPTION>
                                                    {/foreach}
                                                </select>
                                                <input class="cityId" STYLE="width: 50px; vertical-align: top;" type="submit" name="submit" value="Get"></input>
                                            </form>
                                        </div>
                                        <div style="float: left; width: 20px; height: 31px;">
                                        </div>
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <form method="post" action="process-assign.php" onsubmit="return verifyChecked()">
                                                <table class="tablesorter">
                                                    <thead>
                                                        <tr>
                                                            
                                                            
                                                            <th style="font-size: 12px" nowrap>SNO.</td>
                                                            <th style="font-size: 12px" nowrap>Updation Cycle</th>
                                                            <th style="font-size: 12px" nowrap>Project Id</th>
                                                            <th style="font-size: 12px" nowrap>Project Name</th>
                                                            <th style="font-size: 12px" nowrap>Remark</th>
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
                                                     {foreach from = $searchResult key = key item = item}
                                                        <tr>
                                                            <td>{$key+1}</td>
                                                            <td>{$item->label}</td>
                                                            <td>{$item->project_id}</td>
                                                            <td>{$item->project_name}</td>
                                                            <td>{$item->executive_remark}</td>                                                                
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
                       {else}
                            <font color = "red">No Access</font>
                       {/if}
                    </TD>
                </TR>
            </TBODY>
        </TABLE>
    </TD>
</TR>
