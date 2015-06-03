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

                        {if ($contentDeliveryManage == true || $contentDeliveryAccess == true) && $is_allowed}

                            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
                                    <TR>
                                        <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                                            <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                                                    <TR>
                                                        <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Lot Details for #{$lot_id}
                                                            {if $lot_details['lot_status'] == 'revertedToVendor'}
                                                                (Reverted)
                                                            {/if}
                                                            <a href="content_lot_list_assigned.php"><img width="28" style="float:right" src="images/back.jpeg"></a>
                                                        </TD>
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
                                                            <td>
                                                                {if $currentRole == 'contentEditor'}
                                                                    <b>Status: </b> {$arrLotStatus[$lot_details['lot_status']]}
                                                                    {if $lot_details['lot_status'] == 'assigned' || $lot_details['lot_status'] == 'reverted'}
                                                                        &nbsp;to {$arrRoles[$lot_details['role']]}
                                                                    {/if}
                                                                {else}
                                                                    <b>Assignment Date: </b>{$lot_details['assignment_date']}
                                                                {/if}
                                                            </td>
                                                            <td>
                                                                {if $currentRole == 'contentEditor' && $lot_details['completed_by']}
                                                                    <input type='button' value='Approve' class="page-button" onclick="lot_action_approve()"/>
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                                    <input type='button' onclick='lot_action_revert()' value='Revert' class="page-button" style="background:#db0306"/>
                                                                {else}
                                                                    &nbsp;
                                                                {/if}
                                                            </td>
                                                        </tr>
                                                        <tr style='height: 50px;'>
                                                            <td>
                                                                {if $currentRole == 'contentVendor'}
                                                                    <b>#Articles(original/updated): </b>{$lot_details['lot_articles']} / {$lot_details['lot_completed_articles']}</td>
                                                                {else}
                                                                     <b>#Articles: </b>{$lot_details['lot_articles']}
                                                                {/if}
                                                            <td>
                                                                {if $lot_details['lot_status'] == 'revertedToVendor'}
                                                                    <b>Reverted Articles:</b> {$lot_details['reverted_articles']}&nbsp;&nbsp;<a href="javascript:void(0)" onclick="show_revert_comments('{$lot_details["lot_id"]}', '', '{$currentRole}')">Comments</a>
                                                                {/if}
                                                                
                                                            </td>
                                                            <td>
                                                                {if $lot_details['lot_articles'] == $lot_details['lot_completed_articles'] || $lot_details['lot_status'] == 'revertedToVendor'}
                                                                    {$enabled = ""}
                                                                {else}
                                                                    {$enabled = "disabled"}
                                                                {/if}
                                                                {if $currentRole == 'contentVendor'}
                                                                    <form method="post">
                                                                        <input type='submit' name='lotCompleted' id='lotCompleted' value='Submit' {$enabled} />
                                                                    </form>
                                                                {/if}
                                                            </td>
                                                        </tr>
                                                        <tr style='height: 50px;'>
                                                            <td>
                                                                {if $currentRole == 'contentVendor'}
                                                                    <b>#Words(original/updated): </b>{$lot_details['lot_words_count']} / {$lot_details['lot_updated_words_count']}</td>
                                                                {else}
                                                                    <b>#Words: </b>{$lot_details['lot_words_count']}
                                                                {/if}
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                        </tr>                                                    
                                                    </table> 
                                                    {*--------Rendering Lot Details----------*}
                                                    <TABLE cellSpacing=1 cellPadding=4 width="97%" align=center border=0>
                                                        <thead>
                                                            <TR class = "headingrowcolor" height="25">
                                                                <th style="font-size: 12px" nowrap>S.No.</th>
                                                                <th style="font-size: 12px" nowrap>{$lot_details['lot_type']|ucwords} Name</th>
                                                                <th style="font-size: 12px" nowrap>Locality/City</th>
                                                                <th style="font-size: 12px" nowrap>Current Description</th>
                                                                <th style="font-size: 12px" nowrap>Updated Description</th>
                                                                <th style="font-size: 12px" nowrap>
                                                                    {if $currentRole == 'contentEditor' && $lot_details['completed_by']}
                                                                        Revert
                                                                        <br/>
                                                                        <input type="checkbox" name="revertAll" value="revertAll" id="revertAll">
                                                                        <br/>
                                                                        {if $lot_details['total_revert_comment'] == 0}
                                                                            <a href='javascript:void(0)' id='add-all-comment' onclick='add_revert_comment_action()' style='display:none;color:#fff'>Add Comments</a>
                                                                        {/if}
                                                                    {else}
                                                                        Actions
                                                                    {/if}

                                                                </th>
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
                                                                    <td align=center class=td-border>{$row['entity_name']}</td>
                                                                    <td align=center class=td-border>
                                                                        {if $row['locality']}{$row['locality']}, {/if}                                                                       
                                                                        {$lot_details['lot_city']}
                                                                    </td>
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
                                                                    <td align=center class=td-border>
                                                                        {if $currentRole == 'contentEditor' && $lot_details['completed_by']}                                                                            
                                                                            <input type="checkbox" class="revert" name="revert-{$row['content_id']}" value="{$row['content_id']}" id="revert-{$row['content_id']}">
                                                                            <span id="revert-{$row['content_id']}-add-edit-comments" style="display:none">
                                                                                {if $row['revert_comments']}
                                                                                    <br/><a href="javascript:void(0)" alt='' onclick='add_revert_comment_action("{$row['content_id']}", "edit")'>Edit Comments</a>
                                                                                {else}                                                                                
                                                                                    <br/><a href="javascript:void(0)" alt='' onclick='add_revert_comment_action("{$row['content_id']}", "add")'>Add Comments</a>
                                                                                {/if}
                                                                            </span>
                                                                        {else}                                                                            
                                                                            {if ($row['content_status'] == 'revert' || $row['content_status'] == 'revertComplete') && $row['revert_comments']}
                                                                                <br/><a href='javascript:void(0)' onclick='show_revert_comments("{$lot_details['lot_id']}", "{$row['content_id']}", "{$currentRole}")'>Revert Comments</a>
                                                                            {/if}
                                                                        {/if}   
                                                                        
                                                                        {if $row['updated_content']}
                                                                            <a href='content_lot_update.php?l={$lot_id}&cid={$row["content_id"]}'>Edit</a>
                                                                        {else} 
                                                                            <a href='content_lot_update.php?l={$lot_id}&cid={$row["content_id"]}'>Add</a>
                                                                        {/if}

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
    function lot_action_approve() {
        $.ajax({
            url: "ajax/lot_actions.php",
            type: "POST",
            data: "lot_id=" + "{$lot_id}" + "&lotAction=" + "editorApproval" + "&currentUser=" + "{$currentUser}",
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
    function add_revert_comment_action(content_id, action) {
        var revertArr = [];
        if(!action)
            action = 'add';
        $('.revert').each(function () {
            if ($(this).is(':checked'))
                revertArr.push($(this).val());
        });
        if (!revertArr.length) {
            revertArr.push(content_id);
        }
        if (revertArr.length) {
            $.ajax({
                type: "POST",
                url: 'ajax/lot_action_revert_comment.php',
                data: { action:action, lot_id: "{$lot_id}", revertIds: revertArr.join(','), currentUser: "{$currentUser}", completedBy:"{$lot_details['completed_by']}" },
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
            alert('Please select Article(s)!');
        }
    }
    function lot_action_revert(){
        var revertArr = [];
        
        $('.revert').each(function () {
           revertArr.push($(this).val());
        });
        $.ajax({
            url: "ajax/lot_actions.php",
            type: "POST",
            data: "completedBy="+"{$lot_details['completed_by']}"+"&revertIds="+revertArr.join(',')+"&lot_id=" + "{$lot_id}" + "&lotAction=" + "revertVendor" + "&currentUser=" + "{$currentUser}",
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
    
    $(document).ready(function () {
        $('#revertAll').on('change', function () {
            if ($(this).is(':checked')) {
                $('.revert').each(function () {
                    $(this).prop('checked', true);
                });
                $('#add-all-comment').show();
            } else {
                $('.revert').each(function () {
                    $(this).prop('checked', false);
                    $('#'+$(this).attr('id')+'-add-edit-comments').hide();
                });
                $('#add-all-comment').hide();
            }
        });
        $('.revert').on('click', function(){
            if($(this).is(':checked')){
                $('#'+$(this).attr('id')+'-add-edit-comments').show();
            }else{
                $('#'+$(this).attr('id')+'-add-edit-comments').hide();
            }
        });
    });
</script>