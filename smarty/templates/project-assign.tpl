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
                    <TD vAlign=top align=middle width="100%" bgColor=#ffffff height=400>
                        <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#ffffff>
                            <TBODY>
                                <TR>
                                    <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                                        <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0>
                                            <TBODY>
                                                <TR>
                                                    <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Assign Projects</TD>
                                                </TR>
                                            </TBODY>
                                        </TABLE>
                                    </TD>
                                </TR>
                                <TR>
                                    {if is_array($errorMsg)}
                                        <TD colspan="0">
                                            <div class="error" style="text-align: center">
                                                {foreach from = $errorMsg item = error}
                                                    {$error}<br/>
                                                {/foreach}
                                            </div>
                                        </TD>
                                    {else}
                                        <TD>
                                            <div class="contentBox">
                                                <form>
                                                    <table cellSpacing=10px style="margin: 0 auto">
                                                        <TR>
                                                            <th>PROJECTS</th>
                                                            <th>EXECUTIVES</th>
                                                        </TR>
                                                        <TR>
                                                            <td>
                                                                <div class="squareDiv200">
                                                                    {foreach from = $projectDetails item = project}
                                                                        {$project['PROJECT_NAME']} - {$project['BUILDER_NAME']}<br/>
                                                                    {/foreach}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <select class="squareDiv200" multiple>
                                                                    {foreach from = $executiveWorkLoad item = executive}
                                                                        <option value="{$executive["ADMINID"]}">{$executive['USERNAME']} - {$executive['TOTAL']}</option>
                                                                    {/foreach}
                                                            </td>
                                                        </TR>
                                                        <tr>
                                                            <td colspan="2" style="text-align: center">
                                                                <input type="submit" name="submit" value="Assign">
                                                            </TD>
                                                        </TR>
                                                    </table>
                                                </form>
                                            </div>
                                        </TD>
                                    {/if}
                                </TR>
                            </TBODY>
                        </TABLE>
                    </TD>
                </TR>
            </TBODY>
        </TABLE>
    </TD>
</TR>

<script>
$(document).ready(function() {
    $("#abc").ajaxForm({
        success: function(responseText){
            $.fancybox({
                'content' : responseText
            });
        }
    }); 
});

$("#abc").click(function() {
    $('<a href="path/to/whatever">Friendly description</a>').fancybox({
    	overlayShow: true
    }).click();
});
</script>