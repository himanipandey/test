
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="js/jquery/jquery-ui.css">
<script type="text/javascript" src="/js/jquery/jquery-1.4.4.min.js"></script> 
<script type="text/javascript" src="/js/jquery/jquery-ui-1.8.9.custom.min.js"></script> 
<script type="text/javascript" src="js/jquery/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery-ui.js"></script>
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.min.js"></script>


<script language="javascript">

  

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
    {if $companyAuth == 1}
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
                <TR>
                  <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                    <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                      <TR>
                        <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Broker Agent Management</TD>
                      </TR>
                    </TBODY></TABLE>
                  </TD>
                </TR>
                <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
                  

                  
                  <div id='create_agent' align="left">
                  <TABLE cellSpacing=2 cellPadding=4 width="93%" align="left" border=0 >
                  <form method="post" enctype="multipart/form-data" id="formlmk" name="formlmk">
                    <div>
                    
                 

                    <tr>
                      <td width="20%" align="right" ><font color = "red">*</font>User Email : </td>
                      <td width="30%" align="left" ><input type=text name="email" id="email" value="{$email}" style="width:250px;"></td>
                      <td width="40%" align="left" id="errmsgemail"></td>
                      
                    </tr>

                    

                    <tr>
                      <td >&nbsp;</td>
                      <td align="left" style="padding-left:50px;" >
                      <input type="submit" name="agentSave" id="agentSave" value="Find OTP" style="cursor:pointer"> &nbsp;&nbsp;                 
                      </td>
                    </tr>
                    {if $otp != ''}
                  <tr id='otp' >
                      <td width="20%" align="right" ><font color = "red">*</font>One Time Password : </td>
                      <td width="30%" align="left" >{$otp}</td><td width="40%" align="left" id="errmsgname"></td>
                      <td width="40%"></td>
                    </tr>
                  {/if}

                    </div>
                  </form>
                  
                  </TABLE> 
                  </div> 




                    
                 </TD>
            </TR>
          </TBODY></TABLE>
        </TD>
        {/if}
      </TR>
    </TBODY></TABLE>
  </TD>
</TR>
     

