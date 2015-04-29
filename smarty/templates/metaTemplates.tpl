</td>
</tr>
<tr>
    <td class="white-bg paddingright10" vAlign=top align=middle bgColor=#ffffff></td>
<table cellSpacing=0 cellPadding=0 width="100%" border=0>
    <tbody>
        <tr>
            <td width=224 height=25>&nbsp;</td>
            <td width=10>&nbsp;</td>
            <td width=866>&nbsp;</td>
        </tr>
        <tr>
            <td class=paddingltrt10 vAlign=top align=middle bgColor=#ffffff>
                {include file="{$PROJECT_ADD_TEMPLATE_PATH}left.tpl"}
            </td>
            <td vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</td>
            <td vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
                <table cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0>
                    <tbody>
                        <tr>
                            <td class=h1 align=left background=../images/heading_bg.gif bgColor=#ffffff height=40>
                                <table cellSpacing=0 cellPadding=0 width="99%" border=0>
                                    <tbody>
                                        <tr>
                                            <td class="h1">
                                                <IMG height=18 hspace=5 src="../images/arrow.gif" width=18>SEO Meta Templates
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td vAlign=top align=middle class="backgorund-rt" height=450><br>
                                <form name="form1" method="post" action="">
                                    <table cellSpacing=1 cellPadding=4 width="97%" align=center border=0>
                                        <tbody>
                                            <tr class="headingrowcolor" height="25">
                                                <td class=whiteTxt width=10% align="center">S NO</td>
                                                <td class=whiteTxt width=15% align="center">Template Name</td>
                                                <td class=whiteTxt width=25% align="center">Title</td>
                                                <td class=whiteTxt width=30% align="center">Description</td>
                                                <td class=whiteTxt width=10% align="center">Edit</td>
                                            </tr>
                                            {foreach from=$result key="index" item="row"}
                                                {$color = "bgcolor = '#F7F7F7'"}
                                                {if $index%2 == 0}
                                                    {$color = "bgcolor = '#FCFCFC'"}
                                                {/if}	
                                                <tr {$color}>
                                                    <td class="td-border" align="center">{(($pageNum-1)*$rowsPerPage)+($index+1)}</td>
                                                    <td class="td-border" lign="center">{$row["template_name"]}</td>
                                                    <td class="td-border" align="center">{$row["title"]}</td>
                                                    <td class="td-border" align="center">{$row["description"]}</td>
                                                    <td class="td-border" align="center">
                                                        <a href="meta_templates.php?operation=edit&name={$row["template_name"]}" title=""><img src="../images/edit_icon.gif" /></a>
                                                    </td>
                                                </tr>
                                            {/foreach}
                                        </tbody>
                                    </table>
                                </form>
                                {if $numRows>30}
                                    <table width="97%" border="0" align="center" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td>
                                                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                                    <tr style="background-color: #aaa" height="35" >
                                                        <td width="77%" align="center">
                                                            {$pagginnation}
                                                        </td>
                                                        <td align="right">&nbsp;</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                {/if}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
</table>
</tr>