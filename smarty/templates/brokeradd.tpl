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
          <TD class=border-all vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</TD>
          <TD class=border-rt vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=../images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>{if $brokerId == ''} Add New {else} Edit {/if} Broker</TD>
                      <TD align=right ></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
		
		
		     
<!--			<fieldset class="field-border">
			  <legend><b>Message</b></legend>-->
			  <TABLE cellSpacing=2 cellPadding=4 width="93%" align=center border=0>
			    <form method="post" enctype="multipart/form-data">
			      <div>
                                  {if $ErrorMsg["dataInsertionError"] != ''}
                                  <tr><td colspan = "2" align ="center"><font color = "red">{$ErrorMsg["dataInsertionError"]}</font></td></tr>
                                  {/if}
                                  {if $ErrorMsg["success"] != ''}
                                  <tr><td colspan = "2" align ="center"><font color = "red">{$ErrorMsg["success"]}</font></td></tr>
                                  {/if}
                                  {if $ErrorMsg["wrongPId"] != ''}
                                  <tr><td colspan = "2" align ="center"><font color = "red">{$ErrorMsg["wrongPId"]}</font></td></tr>
                                  {/if}
				<tr>
                                    <td width="20%" align="right" ><font color = "red">*</font>Broker Name : </td>
                                    <td width="30%" align="left"><input type=text name="brokerName" id="brokerName" value="{$brokerName}" style="width:357px;"></td>
                                    {if $ErrorMsg["brokerName"] != ''}

                                    <td width="50%" align="left" nowrap><font color = "red">{$ErrorMsg["brokerName"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right" valign="top">Contact Person Name :</td>
				  <td width="30%" align="left" >
                                      <input type=text name="contactPerson" value ="{$contactPerson}" style="width:357px;">
                                  </td>
                                    <td width="50%" align="left" ></td>
				</tr>

				<tr>
                                   <td width="20%" align="right" >Address : </td>
                                   <td width="30%" align="left" >
                                           <input type=text name="address" id="Address" value="{$address}" style="width:360px;">	
                                   </td>
                                   <td width="50%" align="left" nowrap></td>
				</tr>
				
				<tr>
				  <td width="20%" align="right" ><font color = "red">*</font>Mobile : </td>
                                  <td width="30%" align="left" ><input type=text name="mobile" id="mobile" value="{$mobile}" maxlength="15" style="width:360px;"></td>
				   {if $ErrorMsg["mobile"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["mobile"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right" valign="top">Email :</td>
				  <td width="30%" align="left" >
				   <input type=text name="email" id ="email" rows="10" cols="45" value ="{$email}" style="width:357px;">
                                  </td>
                                   <td width="50%" align="left" ></td>
				</tr>
                                <tr>
				  <td width="20%" align="right" valign="top"><font color = "red">*</font>Head Quater :</td>
				  <td width="30%" align="left" >
				   <select name = "hq" id = "hq" style="width:357px;">
                                       <option value="">Select City</option>
                                       {foreach from= $cityArr key = k item = val}
                                           <option value="{$k}" {if $k == $hq} selected {/if}>{$val}</option>
                                       {/foreach}
                                   </select>
                                  </td>
                                   {if $ErrorMsg["hq"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["hq"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
                                 <tr>
				  <td width="20%" align="right" valign="top">Status :</td>
				  <td width="30%" align="left" >
				   <select name = "status" id = "status" style="width:357px;">
                                       <option value="1" {if $status == '1'}selected{/if}>Active</option>
                                       <option value="0" {if $status == '0'}selected{/if}>Inactive</option>
                                   </select>
                                  </td>
                                   <td width="50%" align="left" ></td>
				</tr>
                                
                                <tr>
				  <td width="20%" align="right" valign="top">How many project ids would you like to add? :</td>
				  <td width="30%" align="left" >
				   <select name="addMore" onchange="addMoreProject(this.value);">
                                        {section name=loop start=1 loop=100 step=1}
                                            <option {if $selectedVal == $smarty.section.loop.index} selected{/if} value="{$smarty.section.loop.index}">{$smarty.section.loop.index}</option>
                                        {/section}
                                    </select>
                                  </td>
                                   <td width="50%" align="left" ></td>
				</tr>
                                
                                <tr>
				  <td width="20%" align="right" valign="top">Project Ids :</td>
				  <td width="30%" align="left" >
				   {section name=loop start=1 loop=100 step=1}
                                        <div id="addId_{$smarty.section.loop.index}" style="display:none;">
                                            <input maxlength="10" onkeypress="return isNumberKey(event);" type="text" name ="multiple_project[]" value="">
                                        </div>
                                    {/section}
                                  </td>
                                   <td width="50%" align="left" ></td>
				</tr>
                                
				<tr>
				  <td >&nbsp;</td>
				  <td align="left" style="padding-left:152px;" >
				  <input type="hidden" name="brokerId" value="{$brokerId}" />
				  <input type="submit" name="btnSave" id="btnSave" value="Save">
				  &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit">
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
      </TR>
    </TBODY></TABLE>
  </TD>
</TR>
<script type="text/javascript">
    function addMoreProject(ct) {

        for(i=1;i<=ct;i++)
        {
         document.getElementById('addId_'+i).style.display='none';
        }	
        for(i=1;i<=ct;i++)
        {
         document.getElementById('addId_'+i).style.display='';
        }		
    }
    function isNumberKey(evt){
           var charCode = (evt.which) ? evt.which : event.keyCode;
              if(charCode == 99 || charCode == 118)
              return true;
           if (charCode > 31 && (charCode < 46 || charCode > 57))
              return false;
           return true;
    }
 </script>