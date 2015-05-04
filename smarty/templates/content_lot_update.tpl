
<link rel="stylesheet" type="text/css" href="fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<link rel="stylesheet" type="text/css" href="js/jquery/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="tablesorter/css/pager-ajax.css">
<script type="text/javascript" src="js/jquery/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery-ui.js"></script>
<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="tablesorter/css/theme.bootstrap.css">
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<script type="text/javascript" src="nicEdit/nicEdit.js"></script>
<script type="text/javascript">
	bkLib.onDomLoaded(function() {
            
        new nicEditor().panelInstance('currentCotent');
	new nicEditor().panelInstance('updatedCotent');	
        nicEditors.findEditor("currentCotent").disable(); 
});
</script>


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
                            <form method="POST">

                                <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
                                        <TR>
                                            <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                                                <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                                                        <TR>
                                                            <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Edit {$lot_content_details['lot_type']|ucWords} Description (Lot#{$lot_content_details['lot_id']})</TD>
                                                        </TR>
                                                    </TBODY></TABLE>
                                            </TD>
                                        </TR>
                                        <TR>
                                            <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>

                                                <div align="left" style="padding-left:10px">
                                                    {if $lot_content_details}
                                                        <TABLE cellSpacing=1 cellPadding=4 width="97%" align=center border=0>
                                                            <tr style='height: 50px;'>
                                                                <td width="10%">
                                                                    <b>{$lot_content_details['lot_type']|ucWords}: </b>                                                                
                                                                </td>
                                                                <td>
                                                                    {$lot_content_details['entity_name']}
                                                                </td>                                                            
                                                            </tr>
                                                            <tr style='height: 50px;'>
                                                                <td width="10%">
                                                                    <b>Locality: </b>                                                                
                                                                </td>
                                                                <td>
                                                                    {if $lot_content_details['locality']}{$lot_content_details['locality']}, {/if}
                                                                    {$lot_content_details['lot_city']}
                                                                </td>                                                            
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <b>Current Description:</b><br/>
                                                                    <textarea id="currentCotent" readonly="true" name="currentCotent" style="margin: 0px 0px 10px; width: 903px; height: 210px;">{$lot_content_details['content']}</textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <b>Updated Description:</b><br/>
                                                                    <textarea id="updatedCotent" name="updatedCotent" style="margin: 0px 0px 10px; width: 903px; height: 210px;">{$lot_content_details['updated_content']}</textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td height="25" align="center" colspan= "2"  style = "padding-right:40px;">
                                                                    <input type = "button" onclick="check_back_to_lot({$lot_id}, {$lot_content_details['id']})" value = "Back to Lot" id="backToLot" name = "backToLot" class="page-button">
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                                    <input type = "submit" value = "Save" id="updateLot" name = "updateLot" class="page-button">  
                                                                    <span style="float:right;padding-right:100px">
                                                                        {if $paginationIds['prevKey'] != null}
                                                                            <a href="content_lot_update.php?l={$lot_id}&cid={$paginationIds['prevKey']}" title="Previous"><img src="images/left_over.gif"></a>
                                                                            {/if}
                                                                        &nbsp;&nbsp;&nbsp;
                                                                        {if $paginationIds['nextKey'] != null}
                                                                            <a href="content_lot_update.php?l={$lot_id}&cid={$paginationIds['nextKey']}" title="Next"><img src="images/right_over.gif"></a>
                                                                            {/if}
                                                                    </span>

                                                                </td>
                                                            </tr>
                                                        </table>                                                     
                                                    {/if}
                                                </div> 
                                            </TD>
                                        </TR>
                                    </TBODY>
                                </TABLE>
                            </form>
                        {/if}
                    </TD>

                </TR>
            </TBODY></TABLE>
    </TD>
</TR>
<script type="text/javascript">
    function check_back_to_lot(lot_id, lot_content_id) {
        var lot_updated_data = $('#updatedCotent').val();
        var currentRole = "{$currentRole}";
        var redirectUrl = 'content_lot_details_assigned.php';
        if(currentRole == 'contentTeamLead'){
            redirectUrl = 'content_lot_details.php';
        }
        if (lot_id && lot_content_id) {
            $.ajax({
                url: 'ajax/check_updated_lot_content.php',
                data: 'lot_updated_data=' + lot_updated_data + "&lot_content_id=" + lot_content_id,
                type: "POST",
                success: function (dt) {
                    if (dt.trim() == '0') {
                        if (confirm('There are unsaved changes. Are you sure you want to move away.')) {
                            window.location = redirectUrl+'?l=' + lot_id;
                        }
                    } else {
                        window.location = redirectUrl+'?l=' + lot_id;
                    }
                }
            });
        }
    }
</script>