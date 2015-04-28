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
                                            <td class=h1 width="67%">
                                                <IMG height=18 hspace=5 src="../images/arrow.gif" width=18>SEO Meta Templates
                                            </td>
                                            <td align=right colSpan=3>
                                                {if $accessBuilder == ''}
                                                    <a href="builderadd.php" style="font-size:15px; color:#1B70CA; text-decoration:none;"><b>Add Meta Template</b></a>
                                                {/if}
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
                                            <tr class = "headingrowcolor" height="25">
                                                <td class=whiteTxt width=5% align="center">S NO</td>
                                                <td class=whiteTxt width=15% align="center">Builder Display Name</td>
                                                <td class=whiteTxt width=15% align="center">Legal Entity Name</td>
                                                <td class=whiteTxt width=15% align="center">Builder URL</td>
                                                <td class=whiteTxt width=25% align="center">Meta Title</td>
                                                <td class=whiteTxt width=25% align="center">Meta Keywords</td>
                                                <td class=whiteTxt width=15% align = 'center'>Display Order</td>
                                                <td class=whiteTxt width=12% align="center">Action</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
                                {if $NumRows>30}
                                    <table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td>
                                                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td width="77%" height="25" align="center">
                                                            {$Pagginnation}

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