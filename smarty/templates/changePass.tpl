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
          <TD class=border-all vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</TD>
          <TD class=border-rt vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src=",,/images/arrow.gif" width=18>Change Password </TD>
                      <TD align=right ></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
		<TABLE cellSpacing=1 cellPadding=4 width="93%" align=center border=0>
		  <tr>
		    <td>
		      {if is_array($ErrorMsg)}
			<fieldset class="error-box">
			  <legend><strong>The following error is occured</strong></legend>
			    <TABLE cellSpacing=0 cellPadding=0 width="93%" border=0>
			     {foreach from=$ErrorMsg item=value}
				<tr>
				
					<td align="left" style="font-size:11px; color:#FA5858;">
						
							{$value}

					</td>
					
				</tr>
			     {/foreach}
			      </TABLE>
			</fieldset>
			<br />
		     {/if}
<!--			<fieldset class="field-border">
			  <legend><b>Message</b></legend>-->
			  <TABLE cellSpacing=2 cellPadding=4 width="93%" align=center border=0>
			    <form method="post">
			      <TBODY>
				<tr>
					  <td width="27%" align="right" class=td-border>*Old  Password</td>
				      <td width="73%" align="left" class=td-border><input name="oldpassword" type="password" id="txtusername" style="width:250px;" /></td>
				</tr>
				<tr>
				  <td width="27%" align="right" class=td-border>*New Password</td>
				  <td width="73%" align="left" class=td-border><input name="newpassword" type="password" id="txtuserEmail"  style="width:250px;"  /></td>
				</tr>
				<tr>
					  <td width="27%" align="right" class=td-border>*Re-enter New Password</td>
				      <td width="73%" align="left" class=td-border><input name="reNewpassword" type="password" id="txtFname"  style="width:250px;" /></td>
				</tr>
				
				<tr>
				  <td class=td-border>&nbsp;</td>
				  <td align="left" style="padding-left:200px;" class=td-border><input type="submit" name="Save" id="btnSave" value="Save" />&nbsp;&nbsp;&nbsp;<input type="submit" name="Exit" id="btnSave" value="Exit"></td>
				</tr>
			      </TBODY>
			    </form>
			    </TABLE>
<!--			</fieldset>-->
	            </td>
		  </tr>
		</TABLE>
	      </TD>
            </TR>
          </TBODY></TABLE>
        </TD>
      </TR>
    </TBODY></TABLE>
  </TD>
</TR>
