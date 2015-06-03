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
                                    {if isset($assignmentType)}
                                        <TD>
                                            <div class="contentBox">
                                                <form method="post" onsubmit="return verifySelected();">
                                                    <table cellSpacing=10px style="margin: 0 auto">
                                                        <TR>
                                                            <th>PROJECTS</th>
                                                            <th>EXECUTIVES</th>
                                                        </TR>
                                                        <TR>
                                                            <td>
                                                                <select name="projects[]" class="squareDiv200" multiple>
                                                                    {foreach from = $projectDetails item = project}
                                                                        <option value={$project["PROJECT_ID"]} selected>{$project['PROJECT_NAME']} - {$project['BUILDER_NAME']}</option>
                                                                    {/foreach}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <select name="executive" class="squareDiv200" size="{$executiveWorkLoad|count}">  
                                                                    {foreach from = $executiveWorkLoad key = key item = itemExec}
                                                                        <option value ='{trim($itemExec['adminid'])}'>{trim($itemExec['username'])}</option>
                                                                    {/foreach}  
                                                            </td>
                                                        </TR>
                                                        <tr>
                                                            <td colspan="2" style="text-align: center">
                                                                <input type="hidden" name="assignmenttype" value="{$assignmentType}">
                                                                <input type="submit" name="submit" value="Assign">
                                                            </TD>
                                                        </TR>
                                                    </table>
                                                </form>
                                            </div>
                                        </TD>
                                    {else}
                                        {if !empty($errorMsg)}
                                            <TD colspan="0">
                                                <div class="error" style="text-align: center">
                                                    ProjetId {implode(', ', $errorMsg)} couldn't be assigned.
                                                </div>
                                            </TD>
                                        {else}
                                            <td style="text-align: center;">
                                                Projects Assigned Sussessfully.
                                            </td>
                                        {/if}
                                    {/if}
                                </TR>
                                <TR>
                                    <td style="text-align: center;">
                                        <a href = "project_const_img.php">BACK</a>
                                    </td>
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
function verifySelected(){
    var errMsg = '';
    
    if($("[name='projects[]'] option:selected").length === 0)errMsg += "Please select projects. \n";
    if($("[name='executive'] option:selected").length === 0)errMsg += "Please select executives.";
    
    if(errMsg === '')return true;
    alert(errMsg);
    return false;
}
</script>