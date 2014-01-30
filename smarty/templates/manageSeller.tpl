<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>
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
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>Agents List</TD>
                      <TD align=right colSpan=3>
                          {if $accessBroker == ''}
                          <a href="sellercompanyadd.php" style=" font-size:15px; color:#1B70CA; text-decoration:none; "><b>Add Agents</b></a>
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
                                <form name="frm_build" id="frm_build" method="post" action ="SellerCompanyList.php?page=1&sort=all">
                                    <div style="border:1px solid #c2c2c2;padding-top:10px;padding-bottom:10px;width:38%" align="center">
                                        <table>
                                            <tr>
                                                <td>
                                                    <label class="fwb">Company Name : </label>
                                                </td>
                                                <td>
                                                    <input name="broker" id="broker" value="{$broker}" class="text" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="fwb">Agent Name : </label>
                                                </td>
                                                <td>
                                                    <input name="agent" id="agent" value="{$agent}" class="text" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="fwb">Agent Rating : </label>
                                                </td>
                                                <td>
                                                    <select name="agent_rating" id="agent_rating" class="text">
                                                        <option value="">-- Select Rating --</option>
                                                        <option value="0.5" {if $agent_rating != '' && $agent_rating == "0.5"} selected=""  {/if}>0.5</option>
                                                        <option value="1" {if $agent_rating != '' && ($agent_rating == "1.0" || $agent_rating == "1")} selected=""  {/if}>1.0</option>
                                                        <option value="1.5" {if $agent_rating != '' && $agent_rating == "1.5"} selected=""  {/if}>1.5</option>
                                                        <option value="2" {if $agent_rating != '' && $agent_rating == "2.0"} selected=""  {/if}>2.0</option>
                                                        <option value="2.5" {if $agent_rating != '' && $agent_rating == "2.5"} selected=""  {/if}>2.5</option>
                                                        <option value="3" {if $agent_rating != '' && $agent_rating == "3.0"} selected=""  {/if}>3.0</option>
                                                        <option value="3.5" {if $agent_rating != '' && $agent_rating == "3.5"} selected=""  {/if}>3.5</option>
                                                        <option value="4" {if $agent_rating != '' && $agent_rating == "4.0"} selected=""  {/if}>4.0</option>
                                                        <option value="4.5" {if $agent_rating != '' && $agent_rating == "4.5"} selected=""  {/if}>4.5</option>
                                                        <option value="5" {if $agent_rating != '' && $agent_rating == "5.0"} selected=""  {/if}>5.0</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="fwb">Agent Qualification : </label>
                                                </td>
                                                <td>
                                                    <select name="agent_quali" id="agent_quali" class="text">
                                                        <option value="">--Select Qualification--</option>
                                                        {foreach from= $qualification key = k item = val}
                                                           <option value="{$val['id']}" {if $val['id'] == $agent_quali} selected="true" {/if}>{$val['qualification']}</option>
                                                        {/foreach}
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="fwb">Active Since : </label>
                                                </td>
                                                <td>
                                                    <input name="active_since" id="active_since" readonly="" style="width:80px;" value="{$active_since}" class="text" />
                                                    <img src="../images/cal_1.jpg" id="f_trigger_c_to" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>
                                                    <input type="submit" name="search" id="search" value="Search" class="button" />
                                                    <input type="button" name="reset" id="reset" value="Reset" class="button" />
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
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
                        <TD class=whiteTxt width=15% align="left">Company Name</TD>
                        <TD class=whiteTxt width=25% align="left">Agent Name</TD>
                        <TD class=whiteTxt width=25% align="left">Agent Type</TD>                        
                        <TD class=whiteTxt width=25% align="left">Agent Rating</TD>
                        <TD class=whiteTxt width=25% align="left">Agent Qualification</TD>
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
                        <TD align=left class=td-border>{if strlen($value['seller_name']) > 30} {$value['seller_name']|substr:0:30|cat:"..."} {else} {$value['seller_name']} {/if}</TD>
                        <TD align=left class=td-border>{$value['seller_type']}</TD>
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

<script type="text/javascript">
    jQuery(document).ready(function(){
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; 
        var yyyy = today.getFullYear();
        if(dd<10){
            dd='0'+dd
        } 
        if(mm<10)
        {
            mm='0'+mm
        } 
        today = yyyy + '-' + mm + '-' + dd ;
        
        jQuery('#search').click(function(){
            
            
            var active = jQuery('#active_since').val();
            active = active.split("/");
            var active = new Date(active[2] + '-' + active[1] + '-' + active[0]);
            var dd = active.getDate();
            var mm = active.getMonth()+1; 
            var yyyy = active.getFullYear();
            if(dd<10)
            {
                dd='0'+dd;
            } 
            if(mm<10)
            {
                mm='0'+mm;
            } 
            active = yyyy + '-' + mm + '-' + dd ;
            //alert(active + ' ' + today);
            if(active > today)
            {
                alert("Please enter Past Date");
                return false;
            }
            
        });
        jQuery('#reset').click(function(){
           jQuery('.text').val(''); 
        });
    });
    Calendar.setup({
            inputField     :    "active_since",     // id of the input field
            ifFormat       :    "%d/%m/%Y",      // format of the input field
            button         :    "f_trigger_c_to",  // trigger for the calendar (button ID)
            align          :    "Tl",           // alignment (defaults to "Bl")
            dateStatusFunc : dateRange,
            singleClick    :    true,
            showsTime		:	false

         });
    function dateRange(date) {
        var now = new Date();
        return (date.getTime() > now.getTime() )
    }
</script>
