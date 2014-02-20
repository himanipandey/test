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
                                                    <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Citywise Done/Not Done Report</TD>
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
                                                        <th style="font-size: 12px" nowrap>Status</th>
                                                        {foreach $arrCity key=key item = item}
                                                        <th style="font-size: 12px" nowrap>{$item['city_name']}</th>
                                                        {/foreach}
                                                        <th style="font-size: 12px" nowrap>Total</th>
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
                                                    {foreach from = $citywiseDone key=key item = item}
                                                        {$arrGrandTotal = array()}
                                                        {if $newArrLead[$key] != ''}
                                                            {$total['total'] = array()}
                                                        <tr>
                                                            <td nowrap rowspan = 2>{$newArrLead[$key]}</td>
                                                            <td nowrap>Done</td>
                                                            {foreach $arrCity key=kDone item = itemCity}
                                                                <td nowrap>
                                                                    {if isset($item[$itemCity['city_id']][0])}
                                                                        {$arrGrandTotal[$key][$itemCity['city_id']][] = $item[$itemCity['city_id']][0]}
                                                                        {$item[$itemCity['city_id']][0]}
                                                                        {$total['total'][] = $item[$itemCity['city_id']][0]}
                                                                    {else} 0 {$total['total'][] = 0}
                                                                      {$arrGrandTotal[$key][$itemCity['city_id']][] = 0}
                                                                    {/if}
                                                                </td>
                                                            {/foreach}
                                                            <td nowrap>{array_sum($total['total'])}
                                                               {$arrGrandTotal[$key]['grandTotal'][] = array_sum($total['total'])}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            
                                                            <td nowrap>Not Done</td>
                                                            {foreach $arrCity key=kNotDone item = itemCity} 
                                                                <td nowrap>
                                                                {if isset($citywiseNotDone[$key][$itemCity['city_id']])}
                                                                    {array_sum($citywiseNotDone[$key][$itemCity['city_id']])}
                                                                    {$arrGrandTotal[$key][$itemCity['city_id']][] = array_sum($citywiseNotDone[$key][$itemCity['city_id']])}
                                                                {else}
                                                                    {$arrGrandTotal[$key][$itemCity['city_id']][] = 0}
                                                                    0
                                                                {/if}
                                                                </td>
                                                            {/foreach}
                                                            <td nowrap>
                                                                {$citywiseNotDone[$key]['total']}
                                                                {$arrGrandTotal[$key]['grandTotal'][] = $citywiseNotDone[$key]['total']}
                                                            </td>
                                                        </tr>
                                                        <tr bgcolor = '#c2c2c2'>
                                                            <td nowrap colspan = 2 align = 'right'><b>Grand Total</b></td>
                                                            {foreach $arrGrandTotal[$key] key = LastKey item = lastItem}
                                                                <td nowrap><b>{array_sum($lastItem)}</b></td>
                                                           {/foreach}                          
                                                        </tr>
                                                      {/if}
                                                    {/foreach}
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
