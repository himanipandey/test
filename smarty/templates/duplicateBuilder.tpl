<script type="text/javascript" src="js/jquery.js"></script>


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
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>Duplicate Builder</TD>

                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD colspan='2' vAlign=top align=center class="backgorund-rt" height=450><BR>
				
					  <center><table width="630" border="0" align="center" cellpadding="0" cellspacing="1" bgColor="#fcfcfc" style = "border:1px solid #c2c2c2;margin: 20px;">
					  	<form method = "get" action = "" onsubmit = "return validation();">
					  	
						  	<tr bgcolor='#DCDCDC'>
								<td height="35" align="center" colspan= "2" style='border-bottom:1px solid #c2c2c2;color:#333;'>
									<b>Select Builders</b>
								</td>
							</tr>
							   
								<tr>
								<td height="25" align="right" colspan= "2"></td>
							  </tr>	

							<tr>
								<td align="right" style = "padding-left:10px;" width='25%'><b>Original Builder:</b></td>
								<td align="left" style = "padding-left:10px;" width='75%'>
                	<select name="correct_builderId" id="correct_builder">
                  	<option value="-1">Select Builder</option>
                    {section name=bldr loop=$builderList}
                    	<option rel="{$builderList[bldr].BUILDER_IMAGE}" value={$builderList[bldr].BUILDER_ID}>{$builderList[bldr].BUILDER_NAME}-{$builderList[bldr].BUILDER_ID} ({$builderList[bldr].ENTITY})</option>
                		{/section}
                	</select>
								</td>
							  </tr>
							  <tr><td>&nbsp;</td></tr>
							  <tr>
								<td align="right" style = "padding-left:10px;"><b>Duplicate Builder:</b></td>
								<td align="left" style = "padding-left:10px;">
								<span id = "LocalityList">
					 				<select name="duplicate_builderId" id="duplicate_builder">
										<option value="-1">Select Builder</option>
                    {section name=bldr loop=$builderList}
                    	<option rel="{$builderList[bldr].BUILDER_IMAGE}" value={$builderList[bldr].BUILDER_ID}>{$builderList[bldr].BUILDER_NAME}-{$builderList[bldr].BUILDER_ID} ({$builderList[bldr].ENTITY})</option>
                    {/section} 
                  </select>
								</span>
								</td>
							  </tr>
							  <tr>
								<td height="25" align="right" colspan= "2"></td>
							  </tr>
<td id="bldr_updateStatus" align="right" style="color:RED;" colspan="2"></td>
							   <tr>
								<td height="25" align="center" colspan= "2"  style = "padding-right:40px;">
									<input type = "submit" value = "UPDATE" id="updateBuilder" name = "search" style="border:1px solid #c2c2c2;height:30px;width:70px;background:#999999;color:#fff;font-weight:bold;cursor:hand;pointer:hand;">
								</td>
							  </tr>							
							   <tr>
								<td height="25" align="right" colspan= "2"></td>
							  </tr>	
						  </form>					  
					  </table> 

		</TD>
            </TR>
          </TBODY></TABLE>
        </TD>
      </TR>
    </TBODY></TABLE>

<script>

    function validation(cBldr, dBldr){
        if(cBldr == -1){
            $("#bldr_updateStatus").html('Please select a original builder.');
            return false;
        } 
        if(dBldr == -1){
            $("#bldr_updateStatus").html('Please select a duplicate builder.');
            return false;
        }
        if(cBldr == dBldr){
            $("#bldr_updateStatus").html('Selected builders are same.');
            return false;
        }

        return true;
    };

    $('#updateBuilder').live('click', function(e){
        e.preventDefault();
          
        $("#bldr_updateStatus").html("");

        var cor_builderId = $("#correct_builder").val();
        var dup_builderId = $("#duplicate_builder").val();

        if(validation(cor_builderId, dup_builderId)){
	        $.ajax   ({
		        type: "POST",
		        url: "duplicateBuilderProcess.php",
		        data: "correct_builderId="+cor_builderId+"&duplicate_builderId="+dup_builderId,
		        success: function(resp)   {
                    $("#bldr_updateStatus").html(resp);
			    }
	        });
        }  
    });

    $(document).ready(function(){
          
        /*
        $('#duplicate_builder').change(function(){
            var img = $(this).attr('rel');
            $('#dup_bldr_img').html("<img src='http://www.proptiger.com/images"+img+"'>");
        });        
        */
    });

</script>