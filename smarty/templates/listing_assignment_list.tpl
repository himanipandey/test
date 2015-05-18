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
                                                        <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Listing Assignment List</TD>
                                                    </TR>
                                                </TBODY></TABLE>
                                        </TD>
                                    </TR>
                                    <TR>
                                        <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
                                            <div id="search-top" style="width:500px;float:left">
                                                <form method = "get">
                                                    <fieldset>
                                                        <legend>Filters</legend>
                                                        <table width="80%" border="0" cellpadding="0" cellspacing="0" align="center">		                        
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


                                                                <td style="padding-left: 10px;">
                                                                    <input class="page-button" style="margin-bottom:20px" type = "submit" name = "submit" value = "submit" onclick="return submitButton();">                                                                
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </fieldset>

                                                </form>
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
                                                            <TH align="center">Broker Name</TH>
                                                            <TH align="center">Project</TH>
                                                            <TH align="center">Listing</TH> 
                                                            <TH align="center">Assignment Status</TH>                                                            
                                                            <TH align="center">Scheduling</TH>
                                                            <TH align="center">Key Person Name</TH>
                                                            <TH align="center">Key Person Contact</TH>
                                                            <TH align="center">Date & Time of Visit</TH>
                                                            
                                                        </TR>
                                                    </thead>
                                                    <tbody></tbody>
                                                    {if $current_user_role == 'crm'}
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="13" style="text-align:left">
                                                                <input type="button" class="page-button" value="Add/Edit Scheduling" onclick="add_edit_scheduling()">
                                                            </td>
                                                        </tr>   
                                                    </tfoot>
                                                    {/if}
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
            "ajax": "ajax/ajax_assignment_listing.php?page=0&size=10&col&city=" + city + "&admin_cities=" + admin_cities + "&project=" + projectId + "&listingId=" + listingId
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
                success: function (msg) {
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
        } else {
            alert('Please select Listings!');
        }
    }

</script>