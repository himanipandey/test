<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

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
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>Broker List</TD>
                      <TD align=right colSpan=3>
                          {if $accessBroker == ''}
                          <a href="brokercompanyadd.php" style=" font-size:15px; color:#1B70CA; text-decoration:none; "><b>Add Broker Company</b></a>
                          {/if}
                          </TD>
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
                            <td width="77%" height="25" align="center" style="padding-top:30px;padding-bottom:10px;">
                                <!--<form name="frm_build" id="frm_build" method="post" action ="BrokerCompanyList.php?page=1&sort=all">
                                    <label class="fwb">Enter Broker Company Name : </label>
                                    <input name="broker" id="broker" value="{$broker}" class="button" /> &nbsp;&nbsp;&nbsp;
                                    <input type="submit" name="search" id="search" value="Search" class="button" />
                                </form>-->
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
                        <TD class=whiteTxt width=15% align="left">Broker Company Name</TD>
                        <TD class=whiteTxt width=15% align="left">Comapny Logo</TD>
                        <TD class=whiteTxt width=25% align="left">PAN</TD>
                        <TD class=whiteTxt width=25% align="left">Description</TD>
                        <TD class=whiteTxt width=15% align = 'left'>Active Since</TD>
                        <TD class=whiteTxt width=15% align = 'left'>Status</TD>
                        <TD class=whiteTxt width=12% align="center">Action</TD>
                      </TR>
                      <TR><TD colspan=14 class=td-border></TD></TR>
                        {$count = 0}
                        {foreach from = $brokerDataArr key = k item = value}
                              {$count = $count+1}
                              {if $count%2 == 0}
                                      {$color = "bgcolor = '#FCFCFC'"} 
                              {else}
                                      {$color = "bgcolor = '#F7F7F7'"}
                              {/if}	
                      <TR {$color}>
                          
			             
                        <TD align=center class=td-border>{$count}</TD>
                        <TD align=left class=td-border>{$value['name']}  </TD>
                        <TD align=left class=td-border>{if $value['imageurl'] != ''} <img class="showcontent" id="img_{$count}" src="{$value['imageurl']}" style="width:120px;height:90px;" /> </a> {else}<img src="no_image.gif" width="" height="" /> {/if}</TD>
                        <TD align=left class=td-border>{$value['pan']}</TD>
                        <TD align=left class=td-border>{$value['description']}</TD>
                        <TD align=left class=td-border>{$value['active_since']}</TD>
                        <TD align=left class=td-border>{$value['status']}</TD>
                        <TD align=left class="td-border">
			                 <a href="brokercompanyadd.php?brokerCompanyId={$value['id']}&mode=edit&page={$page}&sort={$sort}" title="{$value['name']}">EDIT </a>
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
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('.showcontent').click(function(){
////            var id = jQuery(this).attr('id');
////            var subid = id.split("_");
////            alert(subid[0] + ' ' + subid[1]);
////            jQuery('#img_' + subid[1]).css("display" , "block");
                var href = jQuery(this).attr('href');
                $.fancybox({
                    type: 'iframe',
                    href: "'" + href + "'"
                });                
        });
        
        
    });
</script>

