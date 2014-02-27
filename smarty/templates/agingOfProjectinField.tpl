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
                                                    <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Report Format 1</TD>
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
                                        &nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <table style="width:100px;">
                                                <thead>
                                                    <tr>
                                                        <th style="font-size: 12px" nowrap>Owner</th>
                                                        <th style="font-size: 12px" nowrap>Done</th>
                                                        <th style="font-size: 12px" nowrap>Not Done</th>
                                                        <th style="font-size: 12px" nowrap>0-7 Days</th>
                                                        <th style="font-size: 12px" nowrap>8-15 Days</th>
                                                        <th style="font-size: 12px" nowrap>16-30 Days</th> 
                                                        <th style="font-size: 12px" nowrap>>30 Days</th>
                                                        <th style="font-size: 12px" nowrap>Grand Total</th> 
                                                        <th style="font-size: 12px" nowrap>Done Ratio%</th>
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
                                                    {$done = 0}
                                                            {$notDone = 0}
                                                            {$zeroSeven = 0}
                                                            {$eight15 = 0}
                                                            {$sixteen = 0}
                                                            {$plus = 0}
                                                            {$total = 0}
                                                    {foreach from = $arrLeadProjectDone key=key item = item}
                                                        
                                                        <tr bgcolor = '#c2c2c2'>
                                                            <td nowrap>{$item['fname']}</td>
                                                            <td nowrap>{$item['done']}</td>{$done = $done+$item['done']}
                                                            <td nowrap>{$item['notDone']}</td>{$notDone = $notDone+$item['notDone']}
                                                            <td nowrap>{$item['0-7']}</td>{$zeroSeven = $zeroSeven+$item['0-7']}
                                                            <td nowrap>{$item['8-15']}</td>{$eight15 = $eight15+$item['8-15']}
                                                            <td nowrap>{$item['16-30']}</td>{$sixteen = $eight15+$item['16-30']}
                                                            <td nowrap>{$item['plus30']}</td>{$plus = $plus+$item['plus30']}
                                                            <td nowrap>{$item['total']}</td>{$total = $total+$item['total']}
                                                            <td nowrap>{$item['doneRatio']}</td>
                                                        </tr>
                                                        {foreach $arrExecLead[$key]  key=keyExec item = itemExec}
                                                            {$done = $done+$arrExecProjectDone[$itemExec['admin_id']]['done']}
                                                            {$notDone = $notDone+$arrExecProjectDone[$itemExec['admin_id']]['notDone']}
                                                            {$zeroSeven = $zeroSeven+$arrExecProjectDone[$itemExec['admin_id']]['0-7']}
                                                            {$eight15 = $eight15+$arrExecProjectDone[$itemExec['admin_id']]['8-15']}
                                                            {$sixteen = $sixteen+$arrExecProjectDone[$itemExec['admin_id']]['16-30']}
                                                            {$plus30 = $plus30+$arrExecProjectDone[$itemExec['admin_id']]['plus30']}
                                                            {$total = $total+$arrExecProjectDone[$itemExec['admin_id']]['total']}
                                                            <tr>
                                                                <td nowrap>{$itemExec['fname']}</td>
                                                                <td nowrap>{$arrExecProjectDone[$itemExec['admin_id']]['done']}</td>
                                                                <td nowrap>{$arrExecProjectDone[$itemExec['admin_id']]['notDone']}</td>
                                                                <td nowrap>{$arrExecProjectDone[$itemExec['admin_id']]['0-7']}</td>
                                                                <td nowrap>{$arrExecProjectDone[$itemExec['admin_id']]['8-15']}</td>
                                                                <td nowrap>{$arrExecProjectDone[$itemExec['admin_id']]['16-30']}</td>
                                                                <td nowrap>{$arrExecProjectDone[$itemExec['admin_id']]['plus30']}</td>
                                                                <td nowrap>{$arrExecProjectDone[$itemExec['admin_id']]['total']}</td>
                                                                <td nowrap>{$arrExecProjectDone[$itemExec['admin_id']]['doneRatio']}</td>
                                                            </tr>
                                                        {/foreach}
                                                         <tr>
                                                            <td nowrap align = "center"><b>Grand Total:</b></td>
                                                            <td nowrap><b>{$done}</b></td>{$done = 0}
                                                               <td nowrap><b>{$notDone}</b></td>{$notDone = 0}
                                                               <td nowrap><b>{$zeroSeven}</b></td>{$zeroSeven = 0}
                                                               <td nowrap><b>{$eight15}</b></td>{$eight15 = 0}
                                                               <td nowrap><b>{$sixteen}</b></td>{$sixteen = 0}
                                                               <td nowrap><b>{$plus30}</b></td>{$plus30 = 0}
                                                               <td nowrap><b>{$total}</b></td>{$total = 0}
                                                               <td nowrap><b>{ceil($done/$total*100)}</b></td>
                                                           </tr>
                                                             
                                                    {/foreach}
                                                    {if !empty($arrLeadProjectDone)}
                                                        <tr>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                    {/if}
                                                    </tbody>
                                            </table>
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
