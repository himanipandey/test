<style type="text/css">
.button {
    border: 1px solid #C2C2C2;
    background: #F2F2F2;
}

.fwb {
	font-weight: bold;
}
</style>
<script language="javascript">
function chkConfirm(fromUrl,page,url) 
{
   var returnStatus =  confirm("Are you sure! you want to delete this record.");
   if(returnStatus == true) {
       window.location.href = "redirectUrlManage.php?mode=delete&sort=all&page="+page+"&deleteUrl="+fromUrl+"&url="+url+"";
   }

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
                <TD class=h1 align=left background=../images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>Redirect URL Mapping List</TD>
                      <TD align=right colSpan=3>
                          {if $accessRedirectUrl == ''}
                            <a href="redirectUrl.php" style=" font-size:15px; color:#1B70CA; text-decoration:none; "><b>Add Redirect URL</b></a>
                          {/if}
                      </TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
                 {if $accessRedirectUrl == ''}
                  <table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="77%" height="25" align="center" style="padding-top:30px;padding-bottom:10px;">
                                <form name="frm_build" id="frm_build" method="post" action ="redirectUrlManage.php?page=1&sort=all">
                                        <label class="fwb">Enter From/To URL : </label><input name="url" id="url" value="{$url}" class="button"> &nbsp;&nbsp;&nbsp;
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
                        <TD class=whiteTxt width=5% align="left" nowrap>SNO</TD>
			<TD class=whiteTxt width=15% align="left">From URL</TD>
                        <TD class=whiteTxt width=15% align="left">To URL</TD>
                        <TD class=whiteTxt width=15% align="left">Submitted By</TD>
                        <TD class=whiteTxt width=25% align="left">Submitted Date</TD>
                        <TD class=whiteTxt width=25% align="left">Modified By</TD>
                         <TD class=whiteTxt width=15% align = 'left'>Modified Date</TD>
                        <TD class=whiteTxt width=12% align="left">Delete</TD>
                      </TR>
                      <TR><TD colspan=12 class=td-border></TD></TR>
                      {$count = 0}
                    {section name=data loop=$urlDataArr}
                          {$count = $count+1}
                          {if $count%2 == 0}
                                  {$color = "bgcolor = '#FCFCFC'"} 
                          {else}
                                  {$color = "bgcolor = '#F7F7F7'"}
                          {/if}	
                      <TR {$color}>
			 <TD nowrap align=left class=td-border>{$count}</TD>
                        <TD nowrap align=left class=td-border>{$urlDataArr[data].FROM_URL}  </TD>
                        <TD nowrap align=left class=td-border>{$urlDataArr[data].TO_URL}  </TD>
                        <TD nowrap align=left class=td-border>{$urlDataArr[data].SUBMITTED_BY}</TD>
                        <TD nowrap valign=left class=td-border>
                            {if $urlDataArr[data].SUBMITTED_DATE == '0000-00-00 00:00:00' || $urlDataArr[data].SUBMITTED_DATE == ''}
                                NA
                            {else}
                            {$urlDataArr[data].SUBMITTED_DATE|date_format}
                            {/if}
                         </TD>
                        <TD nowrap align=left class=td-border>{$urlDataArr[data].MODIFIED_BY}</TD>
                        <TD nowrap align=left class=td-border>
                            {if $urlDataArr[data].MODIFIIED_DATE == '0000-00-00 00:00:00' || $urlDataArr[data].MODIFIIED_DATE == ''}
                                NA
                            {else}
                                {$urlDataArr[data].MODIFIIED_DATE|date_format}
                            {/if}
                        </TD>
                        <TD  class="td-border" align=center nowrap>
                            <input type = "button" name = "deleteUrl" onclick="chkConfirm('{$urlDataArr[data].FROM_URL}','{$page}','{$url}');" value ="Delete">
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
                    <font color=red>{$accessRedirectUrl}</font>
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
