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
	   		{include file="{$OFFLINE_PROJECT_TEMPLATE_PATH}left.tpl"}
	  </TD>
          <TD vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</TD>
          <TD vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Project Type List</TD>
                    
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
                           <TD class=whiteTxt width=21%>Project Name</TD>
                        <TD class=whiteTxt width=48%>Project Location</TD>
                       
						<TD class=whiteTxt width=14%>Unit Name</TD>
                        <TD class=whiteTxt width=8%>Unit Type</TD>
                        <TD class=whiteTxt width=8% align = center>Size</TD>
                        <TD class=whiteTxt width=14%>Measure</TD>				 
                        <TD class=whiteTxt width=12% >Action</TD>
                      </TR>
                      <TR><TD colspan=12 class=td-border>&nbsp;</TD></TR>
                      {$count = 0}
                       {section name=data loop=$projecttypeDataArr}
                       	{$count = $count+1}
                       		{if $count%2 == 0}
                       			{$color = "bgcolor = '#F7F7F7'"}
                       		{else}
                       			{$color = "bgcolor = '#FCFCFC'"}	
                       		{/if}	
                       		                       		
                      <TR {$color}>
                        <TD align=left class=td-border>
                        		                        	
                        	
                        	{$projecttypeDataArr[data].PROPERTY_NAME}
                        	
                         </TD>
                          <TD align=left class=td-border>
                        		                        	
                        	
                        	{$projecttypeDataArr[data].FOLDER_NAME}
                        	
                         </TD>
                        <TD align=left class=td-border>{$projecttypeDataArr[data].UNIT_NAME|upper}</TD>
                        <TD align=left class=td-border>{$projecttypeDataArr[data].TYPE}  </TD>
                        <TD align=center class=td-border>{$projecttypeDataArr[data].SIZE}</TD>
                        <TD align=left class=td-border>{$projecttypeDataArr[data].MEASURE|lower}</TD>
                        
                        <!--<TD  class="td-border" align=left nowrap="">
						<a href="projecttypeadd.php?projecttypeid={$projecttypeDataArr[data].TYPE_ID}" title="{$projecttypeDataArr[data].UNIT_NAME}">Edit</a>|
                          <a href=#" title="{$projecttypeDataArr[data].UNIT_NAME}"><font color="black">View </font></a> |
                          <a href="?projecttypeid={$projecttypeDataArr[data].TYPE_ID}&mode=delete&page={$page}&sort={$sort}" title="Delete Member" onClick="return chkConfirm();">Delete</a></TD>-->

						   <TD  class="td-border" align=left nowrap="">
						<a href="projecttypeadd.php?projectid_type={$projecttypeDataArr[data].PROPERTY_ID}" title="{$projecttypeDataArr[data].UNIT_NAME|upper}">Edit</a>|
                       
                          <a href="?projecttypeid={$projecttypeDataArr[data].PID}&mode=delete&page={$page}&sort={$sort}" title="Delete Member" onClick="return chkConfirm();">Delete</a></TD>
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