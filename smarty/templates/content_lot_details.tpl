
<link rel="stylesheet" type="text/css" href="fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<link rel="stylesheet" type="text/css" href="js/jquery/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="tablesorter/css/pager-ajax.css">
<script type="text/javascript" src="js/jquery/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery-ui.js"></script>
<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
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

                        {if $contentDeliveryManage == true || $contentDeliveryAccess == true}

                            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
                                    <TR>
                                        <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                                            <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                                                    <TR>
                                                        <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Lot Details for #{$lot_id} <a href="content_lot_list.php"><img width="28" style="float:right" src="images/back.jpeg"></a></TD>
                                                    </TR>
                                                </TBODY></TABLE>
                                        </TD>
                                    </TR>
                                    <TR>
                                        <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>

                                            <div align="left" style="padding-left:10px">
                                                {if $lot_details}
                                                    <table width="100%">
                                                        <tr style='height: 50px;'>
                                                            <td><b>Lot Type: </b>{$lot_details['lot_type']|ucwords}</td>
                                                            <td><b>Status: </b>
                                                                {$arrLotStatus[$lot_details['lot_status']]}
                                                                {if $lot_details['lot_status'] == 'assigned' || $lot_details['lot_status'] == 'reverted'}
                                                                &nbsp;to {$arrRoles[$lot_details['role']]}
                                                                {/if}
                                                            </td>
                                                            <td>
                                                                
                                                                    &nbsp;
                                                                
                                                            </td>
                                                        </tr>
                                                        <tr style='height: 50px;'>
                                                            <td><b>#Lot Articles: </b>{$lot_details['lot_articles']}</td>
                                                            <td>&nbsp;</td>
                                                            <td>
                                                                
                                                                    &nbsp;
                                                                
                                                            </td>
                                                        </tr>
                                                        <tr style='height: 50px;'>
                                                            <td><b>#Words: </b>{$lot_details['lot_words_count']}</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                        </tr>                                                    
                                                    </table> 
                                                    {*--------Rendering Lot Details----------*}
                                                    <TABLE class="tablesorter" cellSpacing=1 cellPadding=4 width="97%" align=center border=0>
                                                        <thead>
                                                            <tr>
                                                                <th style="font-size: 12px" nowrap>S.No.</th>
                                                                <th style="font-size: 12px" nowrap>{$lot_details['lot_type']|ucwords} ID</th>
                                                                <th style="font-size: 12px" nowrap>{$lot_details['lot_type']|ucwords} Name</th>
                                                                <th style="font-size: 12px" nowrap>Current Description</th>
                                                                <th style="font-size: 12px" nowrap>Updated Description</th>
                                                                <th class="filter-false" style="font-size: 12px" nowrap>Actions</th>                                                                

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
                                                                        <option value="50">50</option>
                                                                        <option  value="100">100</option>
                                                                    </select>
                                                                    <select class="pagenum input-mini" title="Select page number"></select>
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                        <tbody>
                                                            {foreach from=$lot_details['lot_contents'] key=key item=row}
                                                                {$count = $key+1}
                                                                {if $count%2 == 0}
                                                                    {$color = "bgcolor = '#FCFCFC'"} 
                                                                {else}
                                                                    {$color = "bgcolor = '#F7F7F7'"}
                                                                {/if}	
                                                                <tr {$color}>
                                                                    <td align=center class=td-border>{$key+1}</td>
                                                                    <td align=center class=td-border>{$row['entity_id']}</td>
                                                                    <td align=center class=td-border>{$row['entity_name']}</td>
                                                                    <td align=left class=td-border>
                                                                        {$row['content']}
                                                                        {if $row['content']}
                                                                            ...
                                                                        {/if}
                                                                    </td>
                                                                    <td align=left class=td-border>
                                                                        {$row['updated_content']}
                                                                        {if $row['updated_content']}
                                                                            ...
                                                                        {/if}
                                                                    </td>
                                                                    <td>
                                                                        <a href='content_lot_update.php?l={$lot_id}&cid={$row["content_id"]}'>Edit</a>
                                                                    </td>
                                                                </tr>
                                                            {/foreach}
                                                        </tbody>
                                                    </table>
                                                {/if}
                                            </div> 


                                        </TD>
                                    </TR>
                                </TBODY></TABLE>
                            {/if}
                    </TD>

                </TR>
            </TBODY></TABLE>
    </TD>
</TR>
<script type="text/javascript">

</script>