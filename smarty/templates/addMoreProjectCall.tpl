
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
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18> Add More Project Ids for call id {$callId}</TD>
                      <TD align=right ></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign="top" align="middle" class="backgorund-rt" height="450"><BR>

			  <TABLE cellSpacing=2 cellPadding=2 width="43%" align=center border=1 style = "border:1px solid;">
			    <form method="post">
				<tr>
                                    <td  align = "center" colspan = "2">
                                        {if count($ErrorMsg)>0}
                                           {foreach from=$ErrorMsg key=key item=data}
                                               
                                           <font color = "{if $key == 'success'}green{else}red{/if}">{$data}</font><br>
                                           {/foreach}
                                        {/if}
                                    </td>
				</tr>				
				 <tr>						
                                    <td width="20%" align="right" nowrap>
                                        <b>How many project ids would you like to add?.</b>
                                    </td> 
                                    <td width="50%" nowrap>		
                                        <select name="addMore" onchange="addMoreProject(this.value);">
                                            {section name=loop start=1 loop=100 step=1}
                                                <option {if $selectedVal == $smarty.section.loop.index} selected{/if} value="{$smarty.section.loop.index}">{$smarty.section.loop.index}</option>
                                            {/section}
                                        </select>	
                                    </td>									
                                    </td>				
				</tr>
				<tr>
				  <td width="20%" align="right" valign = "top" nowrap><b>Project Ids :</b> </td>
				  <td width="30%" align="left" nowrap>
				  {section name=loop start=1 loop=100 step=1}
                                        <div id="addId_{$smarty.section.loop.index}" style="display:none;">
                                            <input maxlength="10" onkeypress="return isNumberKey(event);" type="text" name ="multiple_project[]" value="">
                                        </div>
                                    {/section}
				</tr>
				<tr>
				  
				  <td colspan = "2" align="right" style="padding-left:152px;" >
                                    <input type="hidden" name="brokerId" value="{$brokerId}" />
                                    <input type="hidden" name="callId" value="{$callId}" />
                                    <input type="submit" name="submit" id="more" value="Save" style = "font-size:16px;">
                                    <input type="submit" name="exit" id="exit" value="Exit" style = "font-size:16px;">
				  </td>
				</tr>
			      </div>
			    </form>
			    </TABLE>
          </td>
		  </tr>
		</TABLE>
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
