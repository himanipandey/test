<script>
	function delete_logo(){
		$('#current-logo').hide();
		$('#bankLogo').val('del-logo');
	 }
	
	function bank_validation()
	{
		bankname = document.getElementById("bankname").value;
		bank_detail = document.getElementById("bank_detail").value
		if(bankname.trim() == '')
		{
			alert("Please enter bank name!");
			document.getElementById("bankname").focus();
			return false;
		}
		if(bank_detail.trim() == '')
		{
			alert("Please enter bank detail!");
			document.getElementById("bank_detail").focus();
			return false;
		}
		return true;
	}

	function getPhotos(){
		var dataResult = getPhotosFromDB();
		var template = '<img src="'+dataResult['data'][0]['SERVICE_IMAGE_PATH']+'" width = 150 height = 100 />';
		//console.log(template);
		alert(template);
		$("a#view").html( template );
		$("a#view").fancybox();
	
	}

	function getPhotosFromDB() {
    //initVar();
    //var data = getData(),
      //  res = null;
	     data = "bank="+{$bankid}+"&service_image_id="+{$service_image_id};
	    $.ajax({
	        async: false,
	        type : 'GET',
	        url  : '/ajax/photo.php',
	        data : data+"&getPh=1",
	        success: function( json ) {
	            var __json = JSON.parse( json );
	            if ( __json['result'] == true ) {
	                res = __json;
	            }
	            else {
	                res = null;
	            }
	        }
	    });
	    return res;
	}


	
</script>
<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
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
                <TD class=h1 align="left" background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18><b>Add Bank</b></TD>
                      <TD align=right colSpan=3></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height="450"><BR>
                  <table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="77%" height="25" align="left">
                             <!--{$Sorting}--> 
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>

					
					{if count($errormsg)!= 0} <table height = "30px"><tr><td><font color ="red">{$errormsg[0]}</font></td></tr></table>{/if}
					  <table cellSpacing=1 cellPadding="4" width="50%" align="center" style = "border:1px solid;" >
					
						<form name = "frm" method = "post" onsubmit = "return bank_validation();" enctype = "multipart/form-data">
						
						<tr bgcolor = '#F7F7F7'>
							<td align = "right"><font color="red">*</font><b>Bank Name:</b></td>
							<td align = "left"><input type = "text" name = "bankname" id = "bankname" value = "{$bankname}"></td>
						</tr>
						{if $img != ''}
							<tr bgcolor = '#F7F7F7'>
								<td width="20%" align="right" valign = top><b>Current Logo : </b></td>
								<td width="20%" align="left" >
								<div id='current-logo'>
											<a id="view" href="" onclick="getPhotos(); return false;" title="Bank Logo">View Image</a>  
											

											
											&nbsp;&nbsp;<img src="/images/delete_icon.gif" style="cursor:pointer" onclick="delete_logo()" title="Delete Logo"/>
											<input type = "hidden" name = "bankLogo" id="bankLogo" value = "">
								</div>
									
								</td>
								
						 	</tr>
						{/if}
						<tr bgcolor = '#F7F7F7'>
							<td align = "right"><b>Bank Logo:</b></td>
							<td align = "left"><input type = "file" name = "logo"></td>
						</tr>
						<tr bgcolor = '#F7F7F7'>
							<td align = "right" valign ="top"><font color="red">*</font><b>Bank Detail:</b></td>
							<td align = "left">
								<textarea name = "bank_detail" id = "bank_detail" rows="15" cols="30">{$bank_detail}</textarea>
							</td>
						</tr>
							<tr  class = "headingrowcolor">
								<td align = "right" colspan = "3">
									{if $bankid}
										<input type = "submit" value = "Update" name = "update">
									{else}
										<input type = "submit" value = "Submit" name = "submit">
									{/if}										
								</td>
							</tr>
						</form>
					</table>
				

					
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

