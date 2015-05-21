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

                        {if $listingAssignmentManage == true || listingAssignmentAccess == true}

                            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
                                    <TR>
                                        <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                                            <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                                                    <TR>
                                                        <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Listing Assignment List</TD>
                                                    </TR>
                                                </TBODY></TABLE>
                                        </TD>
                                    </TR>
                                    <TR>
                                        <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
                                            <div id="search-top" style="width:600px;float:left">
                                                {if $current_user_role != 'photoGrapher' && $current_user_role != 'reToucher'}
                                                <form method = "get">
                                                    <fieldset>
                                                        <legend>Filters</legend>                                                        
                                                        
                                                            <table width="80%" border="0" cellpadding="0" cellspacing="0" align="center">
                                                                {if $current_user_role == 'rm' || $current_user_role == 'crm'}
                                                                <tr>
                                                                    <td height="25" align="left" valign="top">
                                                                        <select id="citydd" name="citydd" >
                                                                            <option value=''>Select City</option>
                                                                            {foreach from=$cityArray key=k item=v}
                                                                                <option value="{$k}" {if $k==$citydd} selected  {/if}>{$v}</option>
                                                                            {/foreach}
                                                                        </select>
                                                                    </td>
                                                                    <td height="25" align="left" valign="top" style="padding-left: 10px;">
                                                                        <input type=text name="project_search" id="project_search" value="{$project_search}"  placeholder="Project"  style="width:210px;">
                                                                        <input type=hidden name="selProjId" id="selProjId" value="{$selProjId}">
                                                                    </td>
                                                                    <td height="25" align="left" valign="top" style="padding-left: 10px;">
                                                                        <input type=text name="listingId_serach" id="listingId_search" value="{$listingId_serach}" placeholder="Listing ID"  style="width:210px;">
                                                                    </td>
                                                                    
                                                                </tr> 
                                                                {/if}
                                                                {if $current_user_role == 'fieldManager'}
                                                                    
                                                                 {if count($errorMsg)>0}
                                                                    <tr><td colspan="3" align = "left">{$errorMsg['dateDiff']}</td></tr>
                                                                {/if}
                                                                <tr>
                                                                    <td colspan="3">
                                                                        <select style="width:150px" name="date_filter">   
                                                                            <option value="">-Select Date Type-</option>
                                                                            <option  value = "assigned-date" {if $date_filter == 'assigned-date'} selected  {else}{/if}>Assigned Date</option>
                                                                            <option  value = "visit-date" {if $date_filter == 'visit-date'} selected  {else}{/if}>Visit Date</option>
                                                                        </select>
                                                                         &nbsp;&nbsp&nbsp;&nbsp;
                                                                        <input placeholder="From Date" readonly="true" style="width:80px" name="from_date_filter" value="{$frmdate}" type="text" class="formstyle2" id="from_date_filter" size="10" />  <img src="images/cal_1.jpg" id="trigger_from_date_filter" style="cursor: pointer; border: 1px solid red;" title="From Date" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
                                                                         &nbsp;&nbsp&nbsp;&nbsp;
                                                                        <input placeholder="To Date" readonly="true" style="width:80px" name="to_date_filter" value="{$todate}" type="text" class="formstyle2" id="to_date_filter" size="10" />  <img src="images/cal_1.jpg" id="trigger_to_date_filter" style="cursor: pointer; border: 1px solid red;" title="From Date" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
                                                                    </td>
                                                                </tr>                                                                   
                                                                {/if}
                                                                <tr>
                                                                    <td colspan=3 style="padding-left: 10px;text-align:right">
                                                                        <input class="page-button" style="margin-bottom:20px" type = "submit" name = "submit" value = "Filter" />                                                                
                                                                        <input class="page-button" style="margin-bottom:20px" type = "button" name = "download" value = "Download" onclick="downloadClick()"/>
                                                                    </td>
                                                                    
                                                                </tr>
                                                            </table>                                                        
                                                    </fieldset>
                                                </form>
                                                {/if}                
                                            </div> 

                                            <div id='listing-assignment-list' align="left" style="padding-left:10px">

                                                <table class="row-border stripe hover" style="color:#fff" cellSpacing=1 cellPadding=4   border=0 id="listing_table" class="tablesorter">

                                                    <thead>
                                                        <TR class = "headingrowcolor">
                                                            <TH align="center" class="no-sort">&nbsp;</TH>
                                                            <th align="center">Serial</th>
                                                            <TH align="center">Listing Id</TH>
                                                            <th align="center">City</th>
                                                            <th align="center">Locality</th>
                                                            
                                                            {if $current_user_role == 'fieldManager'}
                                                                <TH align="center">Assignment Status</TH>
                                                                <TH align="center">Assigned To</TH> 
                                                                <TH align="center">Assigned Date</TH>                                                                
                                                            {/if}
                                                            {if $current_user_role == 'crm' || $current_user_role == 'rm'}
                                                                <TH align="center">Broker Name</TH>
                                                                <TH align="center">Project</TH>
                                                                <TH align="center">Listing</TH> 
                                                                <TH align="center">Touch Up</TH>
                                                                <TH align="center">Scheduling</TH>
                                                            {/if}                                                                                                                        
                                                            
                                                            <TH align="center">Key Person Name</TH>
                                                            <TH align="center">Key Person Contact</TH>
                                                            <TH align="center">Date & Time of Visit</TH>
                                                            {if $current_user_role == 'photoGrapher'}
                                                                <TH align="center">Remark</TH>
                                                                <TH align="center">&nbsp;</TH>
                                                            {/if}
                                                            {if $current_user_role == 'reToucher'}
                                                                <TH align="center">Photo Path</TH>
                                                                <TH align="center">&nbsp;</TH>
                                                            {/if}
                                                            
                                                        </TR>
                                                    </thead>
                                                    <tbody></tbody>
                                                    
                                                    <tfoot>                                                        
                                                        {if $current_user_role == 'crm'}
                                                            <tr>
                                                                <td colspan="13" style="text-align:left">
                                                                    <input type="button" class="page-button" value="Add/Edit Scheduling" onclick="add_edit_scheduling()">
                                                                </td>
                                                            </tr>  
                                                        {/if} 
                                                        {if $current_user_role == 'fieldManager'}
                                                            <tr>
                                                                <td colspan="11" style="text-align:left">
                                                                    <input type="button" class="page-button" value="Assign" onclick="assign_listings()">
                                                                </td>
                                                            </tr>  
                                                        {/if}
                                                    </tfoot>
                                                    
                                                    
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
<script type='text/javascript'>
    function view_listing(listing_id){
        var city = "";
        var projectId = "";
        var listingId = listing_id;
        var admin_cities = '';
        $.ajax({
                type: "POST",
                url: "ajax/ajax_assignment_listing.php?page=0&size=10&col&city=" + city + "&admin_cities=" + admin_cities + "&project=" + projectId + "&listingId=" + listingId + "&current_user_role=" + "{$current_user_role}&error_msg=" + "{$errorMsg['dateDiff']}&frmdate="+"{$frmdate}&todate="+"{$todate}&date_type="+"{$date_filter}&current_user="+"{$current_user}&download=0&readOnly=1",               
                beforeSend: function () {
                    $("body").addClass("loading");
                },
                success: function (msg) {
                    if (msg) {
                        $("body").removeClass("loading");
                        $.fancybox({
                            'content': msg.trim(),
                            'onCleanup': function () {
                                //
                            }

                        });
                    }
                }
            });
        
    }
    function downloadClick(){
        var city = "{$citydd}";
        var projectId = "{$selProjId}";
        var listingId = "{$listingId_serach}";
        var admin_cities = '{$admin_city_ids}';
        window.location.href="ajax/ajax_assignment_listing.php?page=0&size=10&col&city=" + city + "&admin_cities=" + admin_cities + "&project=" + projectId + "&listingId=" + listingId + "&current_user_role=" + "{$current_user_role}&error_msg=" + "{$errorMsg['dateDiff']}&frmdate="+"{$frmdate}&todate="+"{$todate}&date_type="+"{$date_filter}&current_user="+"{$current_user}&download=1";

        return false;
    }
    $(document).ready(function () {
        var city = "{$citydd}";
        var projectId = "{$selProjId}";
        var listingId = "{$listingId_serach}";
        var admin_cities = '{$admin_city_ids}';
        $('#listing_table').dataTable({
            /*"processing": true,
             "serverSide": true,*/
            columnDefs: [{
                    targets: "no-sort",
                    orderable: false
                }],
            "ajax": "ajax/ajax_assignment_listing.php?page=0&size=10&col&city=" + city + "&admin_cities=" + admin_cities + "&project=" + projectId + "&listingId=" + listingId + "&current_user_role=" + "{$current_user_role}&error_msg=" + "{$errorMsg['dateDiff']}&frmdate="+"{$frmdate}&todate="+"{$todate}&date_type="+"{$date_filter}&current_user="+"{$current_user}&download=0"
        });


        $('.schedule_check').live('click', function () {
            if($(this).is(':checked')){
                var selected_row = $(this).attr('id');
                $('.schedule_check').each(function(){
                    if($(this).attr('id') != selected_row){
                       $(this).attr('disabled', true); 
                    }
                });
            }else{
                $('.schedule_check').each(function(){
                    $(this).attr('disabled', false);
                });
            }
        });
        
        //calender set up
        if("{$current_user_role}"  == 'fieldManager'){
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
        }

    });

    $.widget("custom.catcomplete", $.ui.autocomplete, {
        _renderItem: function (ul, item) {
            //alert(item.label);
            var res = item.id.split("-");
            var tableName = res[1];
            return $("<li>")
                    .append($("<a>").text(item.label + "........." + tableName))
                    .appendTo(ul);
        },
    });

    //project-search
    // to get  listings on the table based on project search
    $("#project_search").catcomplete({
        source: function (request, response) {

            $.ajax({
                url: "{$url12}" + "?query=" + $("#project_search").val().trim() + "&typeAheadType=(project)&city=" + $("#citydd :selected").text().trim() + "&rows=10",
                //url: "{$url12}"+"?query="+$("#proj").val().trim()+$("#cityddEdit :selected").text().trim(),
                dataType: "json",
                data: {
                    featureClass: "P",
                    style: "full",
                    name_startsWith: request.term
                },
                success: function (data) {
                    response($.map(data.data, function (item) {
                        return {
                            label: item.displayText,
                            value: item.label,
                            id: item.id,
                        }

                    }));
                }
            });
        },
        select: function (event, ui) {
            window.selectedItem = ui.item;
            var res = ui.item.id.split("-");
            var projectId = res[2];
            pid = projectId;
            //console.log(projectId);

            $("#selProjId").val(projectId);



        },
        open: function () {
            $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
        },
        close: function () {
            $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
        },
    });    
    
    function assign_listings(){
        var selected_rows = {};
        $('.assign_check').each(function(){
            if($(this).is(':checked')){
                selected_rows[$(this).val()] = $(this).attr('name');
            }
            
        });
        if(!$.isEmptyObject(selected_rows)){
            console.log(selected_rows);
            $.ajax({
                type: "POST",
                url: 'ajax/assign_listings_form.php',
                data: { selected_rows: selected_rows, current_user: "{$current_user}" },
                beforeSend: function () {
                    $("body").addClass("loading");
                },
                success: function (msg) {
                    if (msg) {
                        $("body").removeClass("loading");
                        $.fancybox({
                            'content': msg,
                            'onCleanup': function () {
                                //
                            }

                        });
                    }
                }
            });
        }else{
            alert('Please select Listing to Assign!');
        }   
        
    }
    
    function touchup_listing(listing_id){
        $.ajax({
                type: "POST",
                url: 'ajax/touchup_done_listing.php',
                data: { listing_id: listing_id, current_user: "{$current_user}" },
                beforeSend: function () {
                    $("body").addClass("loading");
                },
                success: function (msg) {
                    $("body").removeClass("loading");
                    if (msg.trim() == 1) {
                        alert('Touchup done for Listing!');
                        window.location = 'listing_assignment_list.php';
                    }else{
                        alert('Touchup failed!');
                    }
                }
            });
    }
    
    function verify_scheduling(listing_id){
        $.ajax({
                type: "POST",
                url: 'ajax/verify_schedule_listings_form.php',
                data: { listing_id: listing_id, current_user: "{$current_user}" },
                beforeSend: function () {
                    $("body").addClass("loading");
                },
                success: function (msg) {
                    $("body").removeClass("loading");
                    if (msg) {
                        $.fancybox({
                            'content': msg,
                            'onCleanup': function () {
                                //
                            }

                        });
                    }
                }
            });
    }
    
    function add_edit_scheduling(){
        var selected_rows = '';
        $('.schedule_check').each(function(){
            if($(this).is(':checked')){
                selected_rows = $(this).val();
            }
            
        });
        //open a popup to schedule
        if (selected_rows) {
            $.ajax({
                type: "POST",
                url: 'ajax/schedule_listings_form.php',
                data: { selected_rows: selected_rows, current_user: "{$current_user}" },
                beforeSend: function () {
                    $("body").addClass("loading");
                },
                success: function (msg) {                    
                    if (msg) {
                        $("body").removeClass("loading");
                        $.fancybox({
                            'content': msg,
                            'onCleanup': function () {
                                //
                            }

                        });
                    }
                }
            });
        } else {
            alert('Please select Listings!');
        }
    }

</script>