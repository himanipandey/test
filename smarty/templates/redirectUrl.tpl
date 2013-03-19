 
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
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>Redirect URL</TD>
                      <TD align=right ></TD>
                    </TR>
      </TBODY></TABLE>
    </TD>
        </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height="450"><BR>

      <form method="post" enctype="multipart/form-data">

        <div style="overflow:auto;">
          <TABLE cellSpacing=2 cellPadding=4 width="50%" align=center  style="border:1px solid #c2c2c2;">
          <div>
           {if $msg != ''}
	            <tr>
	                <td align = "center" nowrap colspan ="2">{$msg}</td>
	            </tr> 
           {/if}
            <tr>
                <td align = "center" nowrap><b>From URL:</b></td>
                <td align = "left">
                    <input type = "text" name = "fromUrl" value = "{$fromUrl}" style = "width:300px;"><br>
                    <span syyle = "font-size:10px">Like:p-logix-neo-world-noida-sector-150.php</span>
                </td>
            </tr> 
            <tr>
                <td align = "center" nowrap><b>To URL:</b></td>
                <td align = "left">
                    <input type = "text" name = "toUrl" value = "{$toUrl}" style = "width:300px;"><br>
                    <span syyle = "font-size:10px">Like:p-logix-neo-world-noida-sector-150.php</span>
                </td>
            </tr>
            
            <tr>
                <td align = "center" nowrap colspan ="2"><input type = "submit" name = "submit" value = "Submit">
                </td>
            </tr>                          
            </div>
         </TABLE>
        </div>

        </form>
<!--      </fieldset>-->

       </TD>
            </TR>
          </TBODY></TABLE>
        </td></tr>
    </TBODY></TABLE>