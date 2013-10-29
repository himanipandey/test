<script language="javascript">
function chkConfirm() 
	{
		return confirm("Are you sure! you want to delete this record.");
	}

function selectCity(value){
	document.getElementById('frmcity').submit();
	window.location.href="{$dirname}/suburbList.php?page=1&sort=all&citydd="+value;
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
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>Suburb List</TD>
                      <!--<TD align=right colSpan=3><a href="localityadd.php" style=" font-size:15px; color:#1B70CA; text-decoration:none; "><b>Add Locality</b></a></TD>-->
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
                {if $accessSuburb == ''}
                  <table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="77%" height="25" align="left">
                             {$Sorting} 
                            </td>
							<td width="35%" height="25" align="right" valign="top">
                             <form name="frmcity" id="frmcity" method="post">
                                <select id="citydd" name="citydd" onchange="selectCity(this.value)">
                                       <option>select</option>
                                       {foreach from=$cityArray key=k item=v}
                                        <option  value="{$v.CITY_ID}" {if $cityId=={$v.CITY_ID}}  selected="selected" {/if}>{$v.LABEL}</option>
                                  {/foreach}
                                </select>
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
                      <TR class = "headingrowcolor">
                        <TD class=whiteTxt width=13% align="center">SUBURB NAME</TD>
                         <TD class=whiteTxt width=15% align="center">URL</TD>                          
			 <TD class=whiteTxt width=10% align="center">STATUS</TD>
                        <TD class=whiteTxt width=12% align="center">ACTION</TD>
                      </TR>
                      <TR><TD colspan=12 class=td-border>&nbsp;</TD></TR>
                      {$count = 0}
					  {section name=data loop=$localityDataArr}
					  
					  {$count = $count+1}
					  {if $count%2 == 0}
                       			
						{$color = "bgcolor = '#F7F7F7'"}
					  {else}                       			
						{$color = "bgcolor = '#FCFCFC'"}
					 {/if}	
                      <TR {$color}>
                        <TD align=left class=td-border>{if $localityDataArr[data].LABEL!=''}{$localityDataArr[data].LABEL}{else}<span align="center">-</span>{/if}</TD>
						
						<TD align=left class=td-border>{if $localityDataArr[data].URL!=''}{$localityDataArr[data].URL}{else}-{/if}</TD>

						<TD align=left class=td-border>{if $localityDataArr[data].STATUS!=''}{$localityDataArr[data].STATUS}{else}-{/if}</TD>

						 <TD  class="td-border" align=left>
                                                    <a href="suburbadd.php?suburbid={$localityDataArr[data].SUBURB_ID}&c={$cityId}" title="Edit">Edit</a>
                                                </TD>
                      </TR>
                       {/section}
                        {if $NumRows<=0}
							{if $cityId!="select"}
								<TR><TD colspan="9" class="td-border" align="left">Sorry, no records found.</TD></TR>
							
							{else if $cityId=="" || $cityId=="select"}
								<TR><TD colspan="9" class="td-border" align="left">Please select atleast one option.</TD></TR>
							{/if}
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
                            <td width="77%" height="25" align="center">{$Pagginnation}
                              
                            </td>
                            <td align="right">&nbsp;</td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>                
		    {/if}
                 {else}
                     <font color = "red">No Access</font>
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
