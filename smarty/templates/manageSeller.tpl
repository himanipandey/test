
<style type="text/css">
.button {
    border: 1px solid #C2C2C2;
    background: #F2F2F2;
}

.fwb {
	font-weight: bold;
}
</style>


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
                <TD class=h1 align=left background=../images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>Seller List</TD>
                      <TD align=right colSpan=3>
                          {if $accessBroker == ''}
                          <a href="sellercompanyadd.php" style=" font-size:15px; color:#1B70CA; text-decoration:none; "><b>Add Seller Company</b></a>
                          {/if}
                          </TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
                
                  
                    <TABLE cellSpacing=1 cellPadding=4 width="97%" align=center border=0>
                    <form name="form1" method="post" action="">
                      <TBODY>
                        <TR class = "headingrowcolor" height="25">
                        <TD class=whiteTxt width=5% align="center">S NO</TD>
                        <TD class=whiteTxt width=15% align="left">Seller Company</TD>
                        <TD class=whiteTxt width=25% align="left">Seller Name</TD>
                        <TD class=whiteTxt width=25% align="left">Seller Type</TD>
                        <TD class=whiteTxt width=25% align="left">Seller Image</TD>
                        <TD class=whiteTxt width=25% align="left">Seller Rating</TD>
                        <TD class=whiteTxt width=25% align="left">Seller Qualification</TD>
                        <TD class=whiteTxt width=15% align = 'left'>Active Since</TD>
                        <TD class=whiteTxt width=15% align = 'left'>Status</TD>                      
                        <TD class=whiteTxt width=12% align="center">Action</TD>
                      </TR>
                      <TR><TD colspan=14 class=td-border></TD></TR>
                        {$count = 0}
                        {foreach from = $sellerDataArr key = k item = value}
                              {$count = $count+1}
                              {if $count%2 == 0}
                                      {$color = "bgcolor = '#FCFCFC'"} 
                              {else}
                                      {$color = "bgcolor = '#F7F7F7'"}
                              {/if}	
                      <TR {$color}>
                          
			             
                        <TD align=center class=td-border>{$count}</TD>
                        <TD align=left class=td-border>{$value['seller_cmpny']}  </TD>
                        <TD align=left class=td-border>{$value['seller_name']}</TD>
                        <TD align=left class=td-border>{$value['seller_type']}</TD>
                        <TD align=left class=td-border></TD>
                        <TD align=left class=td-border>{$value['rating']}</TD>
                        <TD align=left class=td-border>{$value['qualification']}</TD>
                        <TD align=left class=td-border>{$value['active_since']}</TD>
                        <TD align=left class=td-border>{$value['status']}</TD>
                        <TD align=left class="td-border">
			                 <a href="sellercompanyadd.php?sellerCompanyId={$value['id']}&mode=edit&page={$page}&sort={$sort}" title="{$value['seller_name']}">EDIT </a>
                          </TD>
                      </TR>
                       {/foreach}
                        {if $NumRows<=0}
	                        <TR><TD colspan="9" class="td-border" align="left">Sorry, no records found.</TD></TR>
                        {/if}
                         
                      
                     
                      </TBODY>
                    </FORM>
                    </TABLE>
                    
			     {if $NumRows>1}
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
               
               
	      </TD>
            </TR>
          </TBODY></TABLE>
        </TD>
      </TR>
    </TBODY></TABLE>
  </TD>
</TR>
<TR>
 
</TR>


