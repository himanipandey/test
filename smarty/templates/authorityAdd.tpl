<script type="text/javascript" src="js/jquery.js"></script>
<script language="javascript">
function isNumberKey(evt)
  {
 	 var charCode = (evt.which) ? evt.which : event.keyCode;
 	  	
 	if(charCode == 8 || charCode == 13 || charCode == 46)
   	 return true;
	
	if (charCode >= 48 && charCode <= 57)
		return true;

	 return false;
  }
</script>
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
          {if $authorityAuth == 1}
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>{if $authorityId == ''} Add New {else} Edit {/if} Authority</TD>
                      <TD align=right ></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>

			  <TABLE cellSpacing=2 cellPadding=4 width="93%" align=center border=0>
			    <form method="post" enctype="multipart/form-data" id="frmcity" name="frmcity">
			      <div>
				<tr>
                                    <td width="20%" align="right" ><font color = "red">*</font>Authority Name : </td>
				  <td width="30%" align="left"><input type=text name="authorityName" id="authorityNameName" value="{$authorityName}" style="width:250px;"></td> {if $ErrorMsg["authorityName"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["authorityName"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
				</tr>
				<tr>
                                    <td width="20%" align="right" ><font color = "red">*</font>City : </td>
				  <td width="30%" align="left"><select name = "city" class="city">
                                        <option value =''>Select City</option>
                                        {foreach from = $CityDataArr key = key item = item}
                                           <option {if $city == {$key}} selected {/if} value = {$key}>{$item}</option>
                                         {/foreach}	
                                    </select></td> {if $ErrorMsg["city"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["city"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
				</tr>
				
				<tr>
				  <td >&nbsp;</td>
				  <td align="left" style="padding-left:50px;" >
				  <input type="hidden" name="authorityId" value="{$authorityId}" />
				  <input type="submit" name="btnSave" id="btnSave" value="Save" style="cursor:pointer">
				  &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit" style="cursor:pointer">
				  </td>
				</tr>
			      </div>
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
        {/if}
      </TR>
    </TBODY></TABLE>
  </TD>
</TR>
