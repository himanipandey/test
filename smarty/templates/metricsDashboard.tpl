 
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

          <TD class=border-rt vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>IS Metrics Dashboard</TD>
                      <TD align=right ></TD>
                    </TR>
      </TBODY></TABLE>
    </TD>
        </TR>
        <TR>
          <TD vAlign=top align=middle class="backgorund-rt" height="450"><BR>

            

            <div style="overflow:auto;">
             {if $accessIsMetrics == ''}
              <form method="post" enctype="multipart/form-data">
              <TABLE cellSpacing=2 cellPadding=4 width="70%" align=center  style="border:1px solid #c2c2c2;">
                <tr>
                    <td align = "center" nowrap colspan = {count($allMetricsData)+1}>
                       <b>IS Metrics: &nbsp;&nbsp;</b> <select name = "month">
                            <option value="3" {if $month == 3} selected {/if}>Last 3 month</option>
                            <option value="6" {if $month == 6} selected {/if}>Last 6 month</option>
                            <option value="9" {if $month == 9} selected {/if}>Last 9 month</option>
                        </select>
                        &nbsp;<input type = "submit" name = "submit" value = "Search">
                    </td>
                </tr>
              </form>
              </TABLE>
              <TABLE cellSpacing=2 cellPadding=4 width="70%">  <tr><td></td></tr> </TABLE>         
              <TABLE cellSpacing=2 cellPadding=4 width="70%" style="border:1px solid #c2c2c2;">         
                <tr height ="25px" class ="headingrowcolor">
                    <td width = "200px" align = "center" nowrap>&nbsp;</td>
                    {foreach from = $allMetricsData key = key item = item}
                        <td class ="whiteTxt" align = "center"><b>{$item->month|date_format:"%b %G"}</b>
                    {/foreach}
               </tr>
               {$cnt = 0}
               {foreach from = $reCreateArr key = key item = item}
                   {if $cnt%2 == 0}
                       {$bgcolor = "#F7F7F7"}
                   {else}
                       {$bgcolor = "#FCFCFC"}
                   {/if}
                <tr bgcolor = "{$bgcolor}">
                     {if $cnt == 0}
                        <td class = "td-border" align = "left" nowrap>New Projects Added</td>
                     {else if $cnt == 1}
                          <td class = "td-border" align = "left" nowrap>% Project With Master Plan</td>
                     {else if $cnt == 2}
                          <td class = "td-border" align = "left" nowrap>% Project Wth Location Plan</td>  
                     {else if $cnt == 3}
                          <td class = "td-border" align = "left" nowrap>% Project With Floor Plan</td>
                     {else if $cnt == 4}
                          <td class = "td-border" align = "left" nowrap>Avg Floor Plan Per Project</td>  
                     {else if $cnt == 5}
                          <td class = "td-border" align = "left" nowrap>% Project With Construction Image</td>
                     {else if $cnt == 6}
                          <td class = "td-border" align = "left" nowrap>% Project With GEO</td> 
                     {else if $cnt == 7}
                          <td class = "td-border" align = "left" nowrap>% Project With Sizes</td>
                     {else if $cnt == 8}
                          <td class = "td-border" align = "left" nowrap>% Project With Fresh Price</td>
                     {/if}
                     {$oldVal = -9999}
                     {for $loop = 0 to (count($allMetricsData)-1)}
                        <td class = "td-border" align = "center">
                            {$item[$loop]}                           
                            {if $oldVal != -9999 && $oldVal < $item[$loop]}
                                <img src="images/green_up_arrow.jpg" height="15px" width="15px">
                             {else if $oldVal != -9999 && $oldVal > $item[$loop]}
                                 <img src="images/down.png" height="15px" width="10px">
                            {/if}
                            {$oldVal = $item[$loop]}
                        </td>
                     {/for} 
                </tr>
                {$cnt = $cnt+1}
               {/foreach}
              </TABLE>
              {else}
                  <font color = "red">No Access</font>
              {/if}
        
       </TD>
            </TR>
          </TBODY></TABLE>
        </td></tr>
    </TBODY></TABLE>