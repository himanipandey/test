<link rel="stylesheet" type="text/css" href="csss.css"> 
<link rel="stylesheet" type="text/css" href="tablesorter/css/theme.bootstrap.css">
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css"> 

<link rel="stylesheet" type="text/css" href="fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<link rel="stylesheet" type="text/css" href="js/jquery/jquery-ui.css">
<script type="text/javascript" src="js/jquery/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery-ui.js"></script>

<script type="text/javascript" src="js/jquery/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>
<style>
    .calender{
        z-index:9999;
    }
</style>
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

                        {if $listingAssignmentManage == true}

                            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
                                    <TR>
                                        <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                                            <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                                                    <TR>
                                                        <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Daily Performance Report</TD>
                                                    </TR>
                                                </TBODY></TABLE>
                                        </TD>
                                    </TR>
                                    <TR>
                                        <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
                                            <form method = "post">
                                                <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
                                                    {if count($errorMsg)>0}
                                                        <tr><td colspan="3" align = "left">{$errorMsg['dateDiff']}</td></tr>
                                                    {/if}
                                                    <tr>
                                                        <td>
                                                            <b>Assignment Date: </b>
                                                            &nbsp;&nbsp&nbsp;&nbsp;
                                                            <input placeholder="From Date" style="width:80px" name="from_date_filter" value="{$frmdate}" type="text" class="formstyle2" id="from_date_filter" size="10" />  <img src="images/cal_1.jpg" id="trigger_from_date_filter" style="cursor: pointer; border: 1px solid red;" title="From Date" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
                                                            &nbsp;&nbsp&nbsp;&nbsp;
                                                            <input placeholder="To Date"  style="width:80px" name="to_date_filter" value="{$todate}" type="text" class="formstyle2" id="to_date_filter" size="10" />  <img src="images/cal_1.jpg" id="trigger_to_date_filter" style="cursor: pointer; border: 1px solid red;" title="From Date" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
                                                            &nbsp;&nbsp&nbsp;&nbsp;
                                                            &nbsp;&nbsp&nbsp;&nbsp;
                                                            <input class="page-button" style="margin-bottom:20px" type = "submit" name = "submit" value = "Filter" />                                                                
                                                            <input class="page-button" style="margin-bottom:20px" type = "button" name = "download" value = "Download" onclick="downloadClick()"/>
                                                        </td>

                                                    </tr>
                                                </table> 
                                            </form>

                                            <table width="100%" class="row-border stripe hover" style="color:#fff" cellSpacing=1 cellPadding=4   border=0 id="listing_table" class="tablesorter">

                                                <thead>
                                                    <TR class = "headingrowcolor">
                                                        <TH width="5%" align="center">Serial</TH>
                                                        <TH width="25%" align="center">Team</TH>
                                                        <th width="20%" align="center">Assigned</th>
                                                        <TH width="20%" align="center">Pending</TH>
                                                        <th width="22%" align="center">Complete ( Photo Clicked / Touchup Done )</th>                                                            
                                                </thead>
                                                <tbody>
                                                    {if count($report_data)}
                                                        {$count = 0}
                                                        {foreach from=$report_data key=key item=data}                                                                
                                                            {foreach from=$data['data'] key=k item=sub_data}
                                                                {$count = $count+1}
                                                                {if $count%2 == 0}
                                                                    {$color = "bgcolor = '#FCFCFC'"} 
                                                                {else}
                                                                    {$color = "bgcolor = '#F7F7F7'"}
                                                                {/if}	
                                                                <tr {$color} >
                                                                    <td>{$count}</td>
                                                                    <td>{$sub_data['pgf_name']}</td>
                                                                    <td>{$sub_data['assigned']}</td>
                                                                    <td>{$sub_data['pending']}</td>
                                                                    <td>{$sub_data['complete']}</td>
                                                                </tr>
                                                            {/foreach}

                                                            <tr {if $data['data']}style='background-color:#ddd'{else}style='background-color:#bbb'{/if}>
                                                                <td>&nbsp;</td>
                                                                <td><b>{$data['admin']}</b></td>
                                                                <td>{$data['total_assigned']}</td>
                                                                <td>{$data['total_pending']}</td>
                                                                <td>{$data['total_complete']}</td>
                                                            </tr>
                                                        {/foreach}
                                                    {else}
                                                        <tr>
                                                            <td colspan='5'>No Data!</td>
                                                        </tr>
                                                    {/if}

                                                </tbody>
                                            </table>

                                        </TD>
                                    </TR>
                                </TBODY></TABLE>
                            {/if}
                    </TD>

                </TR>
            </TBODY></TABLE>
    </TD>
</TR>
<script type='text/javascript'>
    function downloadClick(){
        window.location.href="ajax/download_listing_assignment_report.php?"+"current_user_role=" + "{$current_user_role}&error_msg=" + "{$errorMsg['dateDiff']}&frmdate="+"{$frmdate}&todate="+"{$todate}&current_user="+"{$current_user}&download=1";
    }
    $(document).ready(function () {
        //calender set up
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
</script>