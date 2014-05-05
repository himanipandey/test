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
                                   {if $errorCampaign != ''}
									  <tr bgcolor = '#F7F7F7'>
										<td align ="left" valign ="top" colspan="3"  style = "padding-left:310px;">
										  {$errorCampaign}
										</td>
									  </tr>
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
							  {if $edit == 'edit'}
							    <input type="submit" name="btnSave" id="btnSave" value="Update" onclick="return validate_dids();">	
							  {else}		  
							    <input type="submit" name="btnSave" id="btnSave" value="Save" onclick="return validate_dids();">
							  {/if}							  
							  &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit">
							  </td>
							</tr>				             
				       </div>
				    </form>
                  </TABLE>
                  <br/>
                  <br/>
                  <br/>
                  <TABLE cellSpacing=1 cellPadding=4 width="97%" align=center border=0>
					<TBODY>
						 <TR class = "headingrowcolor">
								<TD class=whiteTxt width=1% align="center">SL</TD>
								 <TD class=whiteTxt width=20% align="center">Campaign</TD>                          
								<TD class=whiteTxt width=23% align="center">DID</TD>
								<TD class=whiteTxt width=10% align="center">ACTION</TD>
						  </TR>
						  {if $all_camps}
							  {$count = 0}
								{foreach from=$all_camps item=data}
								{$count = $count+1}
								{if $count%2 == 0}

									  {$color = "bgcolor = '#F7F7F7'"}
								{else}                       			
									  {$color = "bgcolor = '#FCFCFC'"}
								{/if}
								 <TR {$color} style="text-align:center">
									<TD>{$count}</TD>
									<TD>{$data->campaign_name}</TD>
									<TD>{$data->campaign_did}</TD>
									<TD><a id="edit_offer" href="campagindids.php?&edit=edit&v={$data->id}" title="Edit" >Edit</a> &nbsp;&nbsp;|&nbsp;&nbsp;<a class="delete_offer" id="{$data->id}" href="javascript:void(0)" title="Delete" >Delete</a></TD>
								</TR>
							{/foreach}
						 {else}
						   <tr>
							<td colspan=4>No Record Found.</td>
						   </tr>
						 {/if}
					</TBODY>
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
  $(document).ready(function(){
	$('.delete_offer').click(function(){
		camp_id = $(this).attr('id');
		var r=confirm("Are you sure to delete it?");
		if(r==true)
			window.location = "campagindids.php?edit=delete&v=" + camp_id;
		
	 }); 
  })
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
