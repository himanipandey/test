<style type="text/css">
.button {
    border: 1px solid #C2C2C2;
    background: #F2F2F2;
}

.fwb {
	font-weight: bold;
}
</style>
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
                <TD class=h1 align=left background=../images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>Broker List</TD>
                      <TD align=right colSpan=3>
                          {if $accessBroker == ''}
                          <a href="brokeradd.php" style=" font-size:15px; color:#1B70CA; text-decoration:none; "><b>Add Broker</b></a>
                          {/if}
                          </TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
                {if $accessBroker == ''}
                  <table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="77%" height="25" align="center" style="padding-top:30px;padding-bottom:10px;">
                                <form name="frm_build" id="frm_build" method="post" action ="brokerList.php?page=1&sort=all">
                                    <label class="fwb">Enter Broker Name : </label><input name="broker" id="broker" value="{$broker}" class="button"> &nbsp;&nbsp;&nbsp;
                                    <input type="submit" name="search" id="search" value="Search" class="button">
                                </form>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                    <TABLE cellSpacing=1 cellPadding=4 width="97%" align=center border=0>
                    <form name="form1" method="post" action="">
                      <TBODY>
                      <TR class = "headingrowcolor" height="25">
                        <TD class=whiteTxt width=5% align="center">S NO</TD>
			<TD class=whiteTxt width=15% align="left">Broker Name</TD>
                        <TD class=whiteTxt width=15% align="left">Contact Person Name</TD>
                        <TD class=whiteTxt width=25% align="left">Address</TD>
                        <TD class=whiteTxt width=25% align="left">Mobile</TD>
                         <TD class=whiteTxt width=15% align = 'left'>Email</TD>
                         <TD class=whiteTxt width=15% align = 'left'>City</TD>
                         <TD class=whiteTxt width=15% align = 'left'>Status</TD>
                        <TD class=whiteTxt width=12% align="center">Action</TD>
                      </TR>
                      <TR><TD colspan=12 class=td-border></TD></TR>
                      {$count = 0}
                        {section name=data loop=$brokerDataArr}
                              {$count = $count+1}
                              {if $count%2 == 0}
                                      {$color = "bgcolor = '#FCFCFC'"} 
                              {else}
                                      {$color = "bgcolor = '#F7F7F7'"}
                              {/if}	
                      <TR {$color}>
                          
			<TD align=center class=td-border>{$count}</TD>
                        <TD align=left class=td-border>{$brokerDataArr[data].BROKER_NAME}  </TD>
                        <TD align=left class=td-border>{$brokerDataArr[data].CONTACT_NAME}</TD>
                        <TD align=left class=td-border>{$brokerDataArr[data].BROKER_ADDRESS}</TD>
                        <TD align=left class=td-border>{$brokerDataArr[data].BROKER_MOBILE}</TD>
                        <TD align=left class=td-border>{$brokerDataArr[data].BROKER_EMAIL}</TD>
                        {$city = ViewCityDetails($brokerDataArr[data].HQ)}
                        <TD align=left class=td-border>{if $city['LABEL'] == ''}NA{else}{$city['LABEL']}{/if}</TD>
                        <TD align=left class=td-border>
                            {if $brokerDataArr[data].STATUS == '0'}
                                Inactive
                            {else}
                                Active
                            {/if}
                        </TD>
                        <TD  class="td-border" align=left nowrap>
			  <a href="brokeradd.php?brokerId={$brokerDataArr[data].BROKER_ID}&mode=edit&page={$page}&sort={$sort}" title="{$brokerDataArr[data].BROKER_NAME}">EDIT </a>
                          </TD>
                      </TR>
                       {/section}
                        {if $NumRows<=0}
	                        <TR><TD colspan="9" class="td-border" align="left">Sorry, no records found.</TD></TR>
                        {/if}
                         
                      <TR><TD colspan="9" class="td-border" align="right">&nbsp;</TD>
                      </TR>
                     
                      </TBODY>
                    </FORM>
                    </TABLE>
			{if $NumRows>30}
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
               {else}
                   <font color = "red">No Access</fonnt>
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
