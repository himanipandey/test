<script type="text/javascript" src="js/jquery.js"></script>
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
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>Campaign DIDs Management</TD>
                      
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
                  <TABLE cellSpacing=2 cellPadding=4 width="93%" align=center border=0>
					  <form method="post">
			            <div>
                                  {if $ErrorMsg["dataInsertionError"] != ''}
                                  <tr><td colspan = "2" align ="center"><font color = "red">{$ErrorMsg["dataInsertionError"]}</font></td></tr>
                                  {/if}
                                  {if $ErrorMsg["success"] != ''}
                                  <tr><td colspan = "2" align ="center"><font color = "green">{$ErrorMsg["success"]}</font></td></tr>
                                  {/if}
                                  {if $ErrorMsg["wrongPId"] != ''}
                                  <tr><td colspan = "2" align ="center"><font color = "red">{$ErrorMsg["wrongPId"]}</font></td></tr>
                                  {/if}
				            <tr>
                                    <td width="20%" align="right" ><font color = "red">*</font>Campaign Name : </td>
                                    <td width="30%" align="left"><input type=text name="campName" id="campName" value="{$campName}" style="width:357px;"></td>
                                    {if $ErrorMsg["campName"] != ''}

                                    <td width="50%" align="left" nowrap><font color = "red">{$ErrorMsg["campName"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				            </tr>
				             <tr>
                                    <td width="20%" align="right" ><font color = "red">*</font>Campaign DID : </td>
                                    <td width="30%" align="left"><input type=text name="campDid" id="campDid" value="{$campDid}" style="width:357px;"></td>
                                    {if $ErrorMsg["campDid"] != ''}

                                    <td width="50%" align="left" nowrap><font color = "red">{$ErrorMsg["campDid"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				            </tr>
				            <tr>
							  <td >&nbsp;</td>
							  <td align="left" style="padding-left:152px;" >
							  <input type="hidden" name="campId" value="{$campId}" />
											  <input type="hidden" name="campId" value="{$campId}" />
							  <input type="submit" name="btnSave" id="btnSave" value="Save" onclick="return validate_dids();">
							  &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit">
							  </td>
							</tr>				             
				       </div>
				    </form>
                  </TABLE>               
                
                {if $accessDIDs == ''}
                {else}
                    <font color = "red">No Access</font>
                {/if}
	      </TD>
            </TR>
          </TBODY></TABLE>
        </TD>
      </TR>
    </TBODY></TABLE>
<script type="text/javascript">
 function chkConfirm(){
	return confirm("Are you sure! you want to delete this record.");
 }
 function validate_dids(){
    campName = $('#campName').val();
    campDid = $('#campDid').val();
  if(campName.trim() == ''){
    alert("Campaign Name must not be blank.");
    return false;
  }else if(campDid.trim() == ''){
    alert("Campaign DID must not be blank.");
    return false;
  }
  return true;
   
 }
</script>
