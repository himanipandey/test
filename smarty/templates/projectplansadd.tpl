

<script type="text/javascript" src="../javascript/jquery.js"></script>
<script type="text/javascript" src="../fancybox/fancybox/jquery.fancybox-1.3.4.js"></script>
<link href="../fancybox/fancybox/jquery.fancybox-1.3.4.css" rel="stylesheet" type="text/css">
<script type="text/javascript">

$(document).ready(function()
{

	$(".projectDropDown").change(function()
	{
			
			var project_id=$(this).val();
			var dataString = 'project_id='+ project_id;

			$.ajax
			({
				type: "POST",
				url: "loadImages.php",
				data: dataString,
				cache: false,

				beforeSend: function() {
				$('#loader').show()
				},
				complete: function(){
				$('#loader').hide()
				},


				success: function(html)
				{

				$("#imagesDiv").html(html);
			}
		});

	});


});


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
          <TD class=border-all vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</TD>
          <TD class=border-rt vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../../images/arrow.gif" width=18>{if $projectplansid == ''} Add  {else} Edit {/if} Images <span id="loader" style="float:right; padding-right:100px; display:none;"><img src="../../images/ajax-loader1.gif" /></span></TD>
                      <TD align=right ></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
		
		
		     
<!--			<fieldset class="field-border">
			  <legend><b>Message</b></legend>-->
			  <TABLE cellSpacing=2 cellPadding=2 width="43%" align=center border=1 style = "border:1px solid;">
			    <form method="post" enctype="multipart/form-data">
			     
				<tr>
					<td  align = "center" colspan = "2">
						{if count($ErrorMsg)>0}
					   {foreach from=$ErrorMsg item=data}
					   <font color = "red">{$data}</font><br>
					   {/foreach}
					{/if}
					</td>
				</tr>

				<tr>
				  <td width="20%" align="right" ><b>Project Name :</b><font color = "red">*</font> </td>
				   <td width="30%" align="left" >
				  <select name = "projectDropDown" class="projectDropDown">
							<option value =''>Select Project</option>
							 {section name=data loop=$Project}
							 	<option {if $projectId == {$Project[data].ID}} value ='{$projectId}' selected="selected" {else} value ='{$Project[data].ID}-{$Project[data].PROPTIGER_PROJECT_ID}'{/if} >{$Project[data].PROPERTY_NAME}</option>
							 {/section}	
						</select>				  
				  </td>
				  <td width="50%" align="left" ></td>
				</tr>
				
				<tr>
				
	
			
				

				
			      </div>

			
			    </form>
			    </TABLE>
			    <TABLE cellSpacing=1 cellPadding=0 width="100%"  border=0><TBODY>
			    <tr><td><div id="imagesDiv"></div>
				  </td></tr>
			    
			   <TBODY> </TABLE>
<!--			</fieldset>-->
	   
          </td>
		  </tr>
		</TABLE>
	      </TD>
            </TR>
          </TBODY></TABLE>
        </TD>
      </TR>