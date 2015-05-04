<link rel="stylesheet" type="text/css" href="csss.css"> 
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
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>
<script type="text/javascript" src="js/content_delivery.js"></script>

<div class="modal">Please Wait..............</div>
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
                                                        <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Lot List</TD>
                                                    </TR>
                                                </TBODY></TABLE>
                                        </TD>
                                    </TR>
                                    <TR>
                                        <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>

                                            <div id='create_agent' align="left" style="padding-left:10px">
                                                <table width="100%">
                                                    {if count($errorMsg)>0}
                                                        <tr><td colspan="2" align = "left">{$errorMsg['dateDiff']}</td></tr>
                                                        {/if}
                                                    <tr>
                                                        <td>
                                                            <form method="POST" >
                                                                <select style="width:150px" name="date_filter">
                                                                    {foreach from = $date_filters key= key item = val}
                                                                        <option value = "{$key}" {if $date_filter == $key} selected  {else}{/if}>{$val}</option>
                                                                    {/foreach}
                                                                </select>
                                                                &nbsp;&nbsp&nbsp;&nbsp;
                                                                <input readonly="true" style="width:80px" name="from_date_filter" value="{$frmdate}" type="text" class="formstyle2" id="from_date_filter" size="10" />  <img src="images/cal_1.jpg" id="trigger_from_date_filter" style="cursor: pointer; border: 1px solid red;" title="From Date" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
                                                                &nbsp;&nbsp To &nbsp;&nbsp;
                                                                <input readonly="true" style="width:80px" name="to_date_filter" value="{$todate}" type="text" class="formstyle2" id="to_date_filter" size="10" />  <img src="images/cal_1.jpg" id="trigger_to_date_filter" style="cursor: pointer; border: 1px solid red;" title="From Date" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
                                                                &nbsp;&nbsp&nbsp;&nbsp;
                                                                <input type="submit" value="Filter" name="searchLot" class="page-button" onclick = "return validateFilter()" />
                                                            </form>
                                                        </td>                                                        
                                                    </tr>
                                                </table>

                                                <table class="tablesorter">
                                                    <thead>
                                                        <tr>
                                                            <th style="font-size: 12px" nowrap>Lot ID</td>
                                                            <th style="font-size: 12px" nowrap>Lot Type</th>
                                                            <th class="filter-false" style="font-size: 12px" nowrap>Articles / Words</th>
                                                            <th class="filter-false" style="font-size: 12px" nowrap>Work Done</th>
                                                                {if $currentUserRole == 'contentVendor'}
                                                                    <th class="filter-false" style="font-size: 12px" nowrap>Actions</th>
                                                                {/if}
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
                                                        {if count($assignedLots)}
                                                            {foreach from = $assignedLots key= key item = val}
                                                                {$color = ""}
                                                                {if $val['date_old'] >= 5 && $currentUserRole == 'contentVendor'}
                                                                    {$color = "background:#f75d59"}                                                            
                                                                {/if}
                                                                {if $val['articles'] == $val['lot_completed_articles']  && $currentUserRole == 'contentVendor'}
                                                                    {$color = "background:#bbb"}
                                                                {/if}

                                                                <tr style="{$color}">
                                                                    <td>
                                                                        <a href="content_lot_details_assigned.php?l={$val['lot_id']}">LT{$val['lot_id']}</a>
                                                                    </td>
                                                                    <td>
                                                                        {$val['lot_type']|ucWords}
                                                                        {if $val['lot_status'] == 'revertedToVendor'}
                                                                            <a href="javascript:void(0)" onclick="show_revert_comments('{$val["lot_id"]}', '', '{$currentUserRole}')">-Reverted</a>
                                                                        {/if}
                                                                    </td>
                                                                    <td>
                                                                        {$val['articles']} / {$val['words']}
                                                                    </td>
                                                                    <td>{$val['lot_completed_articles']} / {$val['lot_completed_words']}</td>
                                                                    {if $currentUserRole == 'contentVendor'}
                                                                        <td>

                                                                            {if $val['articles'] == $val['lot_completed_articles'] || $val['revert_comments']}

                                                                                <input type='submit' name='lotCompleted' id='lotCompleted' onclick="complete_lot_action('{$val["lot_id"]}')" value='Submit' {$enabled} />

                                                                            {/if}

                                                                        </td>
                                                                    {/if} 
                                                                </tr>
                                                            {/foreach} 
                                                        {/if}

                                                    </tbody>
                                                </table>                                                                                          
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

    $(document).ready(function () {

        $('#create-lot').on('click', function () {
            window.location = 'create_content_lot.php';
        });

        var cals_dict = {
            "trigger_from_date_filter": "from_date_filter",
            "trigger_to_date_filter": "to_date_filter"
        };
        $.each(cals_dict, function (k, v) {
            Calendar.setup({
                inputField: v, // id of the input field
                //    ifFormat       :    "%Y/%m/%d %l:%M %P",         // format of the input field
                ifFormat: "%Y-%m-%d", // format of the input field
                button: k, // trigger for the calendar (button ID)
                align: "Tl", // alignment (defaults to "Bl")
                singleClick: true,
                showsTime: true
            });
        });

    });
    function validateFilter() {

        if ($('#from_date_filter').val().trim() == '' || $('#to_date_filter').val().trim() == '') {
            alert("Please select the Date Range!");
            return  false;
        }

        return true;
    }
    function complete_lot_action(lot_id) {
        $.ajax({
            url: "ajax/lot_actions.php",
            type: "POST",
            data: "lot_id=" + lot_id + "&lotAction=" + "complete&currentUserRole=" + "{$currentUserRole}" + "&currentUser=" + "{$currentUser}",
            beforeSend: function () {
                $("body").addClass("loading");
            },
            success: function (dt) {
                $("body").removeClass("loading");
                alert(dt);
                if (dt.trim() != 'Action Failed!') {
                    window.location = 'content_lot_list_assigned.php';
                }
            }
        });
    }

</script>