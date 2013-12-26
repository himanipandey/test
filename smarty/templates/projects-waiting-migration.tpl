</TD>
</TR>
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
                    <TD vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
                        {if (count($displayData)>0)}
                            <table border="1">
                                <tr>
                                    <th>SNo</th>
                                    {foreach from = $displayData[0] key=key item = item}
                                        <th>{$key}</th>
                                    {/foreach}
                                <tr>
                                {foreach from = $displayData key=key item = item}
                                    <tr>
                                        <td>{$key+1}</td>
                                        {foreach from = $item item = td}
                                            <td>{$td}</td>
                                        {/foreach}
                                    </tr>
                                {/foreach}
                            </table>
                        {else}
                            <p>No Project Waiting for migration.</p>
                        {/if}
                    </TD>
                </TR>
            </TBODY>
        </TABLE>
    </TD>
</TR>