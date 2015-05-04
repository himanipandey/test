
<link rel="stylesheet" type="text/css" href="fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<link rel="stylesheet" type="text/css" href="js/jquery/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="tablesorter/css/pager-ajax.css">
<script type="text/javascript" src="js/jquery/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery-ui.js"></script>
<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="tablesorter/css/theme.bootstrap.css">
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">


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
                                                        <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Lot Details for #{$lot_id}</TD>
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
                                                            <td><b>Status: </b>{$lot_details['lot_status']}</td>
                                                            <td>
                                                                {if $lot_details['lot_status'] == 'completedByVendor'}
                                                                    <input type='button' value='Approve'>
                                                                {else}
                                                                    &nbsp;
                                                                {/if}
                                                            </td>
                                                        </tr>
                                                        <tr style='height: 50px;'>
                                                            <td><b>#Lot Articles: </b>{$lot_details['lot_articles']}</td>
                                                            <td>&nbsp;</td>
                                                            <td>
                                                                {if $lot_details['lot_status'] == 'completedByVendor'}
                                                                    <input type='button' value='Revert'>
                                                                {else}
                                                                    &nbsp;
                                                                {/if}
                                                            </td>
                                                        </tr>
                                                        <tr style='height: 50px;'>
                                                            <td><b>#Words: </b>{$lot_details['lot_words_count']}</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                        </tr>                                                    
                                                    </table> 
                                                    {*--------Rendering Lot Details----------*}
                                                    <TABLE cellSpacing=1 cellPadding=4 width="97%" align=center border=0>
                                                        <thead>
                                                            <TR class = "headingrowcolor" height="25">
                                                                <th class=whiteTxt width=5% align="center">S.No.</th>
                                                                <th class=whiteTxt width=15% align="center">{$lot_details['lot_type']|ucwords} ID</th>
                                                                <th class=whiteTxt width=15% align="center">{$lot_details['lot_type']|ucwords} Name</th>
                                                                <th class=whiteTxt width=25% align="center">Current Description</th>
                                                                <th class=whiteTxt width=25% align="center">Updated Description</th>
                                                                <th class=whiteTxt width=12% align="center">Actions</th>
                                                                

                                                            </tr>
                                                        </thead>
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