<script language="javascript">
function chkConfirm() 
	{
		return confirm("Are you sure! you want to delete this record.");
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
          <TD vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</TD>
          <TD vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>Bank List</TD>
                      <TD align=right colSpan=3><a href="bank_add.php" style=" font-size:15px; color:#1B70CA; text-decoration:none; "><b>Add Bank</b></a></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
                  <table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="77%" height="25" align="left">
                             {$Sorting} 
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                    <TABLE cellSpacing=1 cellPadding=4 width="97%" align=center border=0>
                    <form name="form1" method="post" action="">
                      <TBODY>
                      <TR class = "headingrowcolor">
                        <TD class=whiteTxt width=10%>Bank Name</TD>
                        <TD class=whiteTxt width=15%>Bank Logo</TD>                        			 
                        <TD class=whiteTxt width=65% >Detail</TD>
						 <TD class=whiteTxt width=10% >Action</TD>
                      </TR>
                      {$count = 0}					
                       {section name=data loop=$projecttower}
                       	{$count = $count+1}
                       		{if $count%2 == 0}
                       			{$color = "bgcolor = '#F7F7F7'"}
                       		{else}
                       			{$color = "bgcolor = '#FCFCFC'"}	
                       		{/if}	
                       		                       		
                      <TR {$color}>
                        <TD align="left" class="td-border" valign = "top">
                        		                        	
                        	
                        	{$projecttower[data].BANK_NAME}
                        	
                         </TD>
                          <TD align="left" class="td-border" valign = "top">
                        		                        	
                        	
                        	<img src = {$imgDisplayPath}/bank_list/{$projecttower[data].BANK_LOGO} width ="100px" height = "100px;">
                        	
                         </TD>
                        <TD align="left" class="td-border"  valign = "top">{nl2br($projecttower[data].BANK_DETAIL)}</TD>

						   <TD align="left" class="td-border"  valign = "top">
						<a href="bank_add.php?bank_id={$projecttower[data].BANK_ID}" title="{$projecttower[data].BANK_NAME}">Edit</a>|
                         
                          <a href="?bank_id={$projecttower[data].BANK_ID}&mode=delete&page={$page}&sort={$sort}" title="Delete Bank" onClick="return chkConfirm();">Delete</a></TD>
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
                    {if $NumRows>0}
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