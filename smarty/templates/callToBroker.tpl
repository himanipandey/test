<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

<script type="text/javascript">

  function clickToCall(obj) {
      var id = $(obj).attr('id').split('_')[1];
      var phNo = $("#mobile").val(); 
      var compgnId = 'campaignName_'+id;
      var campaign = $("#"+compgnId).val();
      if( !isNaN(phNo) ) {
        $.ajax(
	  {
	      type:"get",
	      url:"call_contact.php",
	      data:"contactNo="+phNo+"&campaign="+campaign+"&projectType=secondary",
	      success: function(dt) { // return call Id
		  resp = dt.split('_');
		  if (resp[0].trim() === "call") {
		      $('#callId_'+id).val(resp[1].trim());
		      alert('Calling... '+phNo);
		  }
		  else 
		      alert("Error in calling");
		  
	      }
            }
        );
      }
      else
        alert("Please enter valid mobile number");
      
  };

  function setStatus(obj) {
      var status = $(obj).attr('id').split('_')[0];
      var id = $(obj).attr('id').split('_')[1];
      var projectRemark = $('#remark_call_'+id).val();
      var callId = $('#callId_'+id).val();
       var mobile = $('#mobile').val();
      
      if ( callId ) {

	 window.location = "callToBroker.php?&callId="+callId+"&status="+status+"&remark="+projectRemark+"&mobile="+mobile;
      }
      else 
	alert("Please call before setting disposition");
  }
</script>
{$error}
  <TR>
    <TD class="white-bg paddingright10" vAlign=top align=middle bgColor=#ffffff>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
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
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0>
                        <TBODY>
                            <TR>
                              <TD class="h1" width="67%"><img height="18" hspace="5" src="images/arrow.gif" width="18">
                                  Direct Call To Broker
                              </TD>
                              <TD width="33%" align ="right"></TD>
                            </TR>
                        </TBODY>
                  </TABLE>
                </TD>
	      </TR> 
	      <TD vAlign="top" align="middle" class="backgorund-rt" height="450"><BR>	 
            <table cellSpacing="1" cellPadding="4"  align="center" style="border:1px solid;">
              
              <tr>
                  <td  align = "left">
                      <table align = "left">
                          <form name ="frm" method = "post">
                            {if $error != ''}
                            <tr style="height:25px;background-color:#f2f2f2;">
                                <td align ="left" colspan="2"><font color = "red">{$error}</font></td>
                            </tr>
                            {/if}
                            <tr style="height:25px;background-color:#f2f2f2;">
                                <td align ="left"><b>Mobile:</b></td>
                                <td align ="left"><input type="text" name = "mobile" value="{$mobile}" id = "mobile" maxlength="11"></td>
                            </tr>
                            <tr style="height:25px;background-color:#c2c2c2;">
                                <td align ="left"><b>Campaign Name:</b></td>
                                <td align ="left">
                                  <select name="campaignName" id="campaignName_{$cnt}">
                                    {foreach from = $arrCampaign item=item}
                                    <option value={$item}> {$item} </option>
                                    {/foreach}
                                  </select>
                                </td>
                            </tr>
                            <tr style="height:25px;background-color:#f2f2f2;">
                                <td align ="left"><b>Remark:</b></td>
                                <td align ="left">
                                  <textarea id="remark_call_{$cnt}" name="remark_call_[]"></textarea>
                                </td>
                            </tr> 
                            <tr style="height:25px;background-color:#c2c2c2;">
                                <td align ="left"><b>Click To Call: </b></td>
                                <td align ="left">
                                  <a onclick="clickToCall(this);" style="width:120px" class="c2c" id="c2c_{$cnt}" href="javascript:void(0);"> Click To Call </a>
                                </td>
                            </tr>
                            <tr style="height:25px;background-color:#f2f2f2;">
                                <td align ="left"><b>Success / Fail: </b></td>
                                <td align ="left">
                                  <input type="hidden" name="callId[]" id="callId_{$cnt}" value="">
                                  <a href="javascript:void(0);" id = "success_{$cnt}" onclick="setStatus(this);"> Success </a> ||
                                  <a href="javascript:void(0);" id = "fail_{$cnt}" onclick="setStatus(this);"> Fail </a>
                                </td>
                            </tr>
                          </form>
                      </table>
                  </td>
              </tr>
            </table>

          </TD>
        </TR>
       </TBODY></TABLE>
     </TD>
    </TR>
   </TBODY></TABLE>
  </TD>
</TR>