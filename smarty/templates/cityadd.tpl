<script type="text/javascript" src="js/jquery.js"></script>
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
          <TD class=border-all vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</TD>
          <TD class=border-rt vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>{if $cityid == ''} Add New {else} Edit {/if} City</TD>
                      <TD align=right ></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
		
		
		     
<!--			<fieldset class="field-border">
			  <legend><b>Message</b></legend>-->
			  <TABLE cellSpacing=2 cellPadding=4 width="93%" align=center border=0>
			    <form method="post" enctype="multipart/form-data" id="frmcity" name="frmcity">
			      <div>
				<tr>
				  <td width="20%" align="right" >*City Name : </td>
				  <td width="30%" align="left"><input type=text name=txtCityName id=txtCityName value="{$txtCityName}" style="width:250px;"></td> {if $ErrorMsg["txtCityName"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtCityName"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
				</tr>
				{if $txtCityUrlOld != ''}
				<tr>
				  <td width="20%" align="right" >*City URL : </td>
				  <td width="30%" align="left" >{$txtCityUrlOld}</td>				   
					<input type = "hidden" name = "txtCityUrlOld" value = "{$txtCityUrlOld}">
				  	<td width="50%" align="left" ></td>
				</tr>	
				{/if}
				<tr>
				  <td width="20%" align="right">*Display Order : </td>
				  <td width="30%" align="left" >				  				  				 						
				  
				  <select name="DisplayOrder"  id="DisplayOrder" class="field" style="width:150px;">						 						 	
					 <option value="">Select </option>   							
					{section name=foo start=1 loop=51 step=1}
						<option {if $DisplayOrder == {$smarty.section.foo.index}} value="{$DisplayOrder}" selected = 'selected' {else} value="{$smarty.section.foo.index}"{/if} >{$smarty.section.foo.index}</option>  							
					 {/section}  							 	
 					</select>
				 	 </td>{if $ErrorMsg["DisplayOrder"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["DisplayOrder"]}</font></td>{else} <td width="50%" align="left" id="errmsgdispord"></td>{/if}
				</tr>				<tr>
				  <td width="20%" align="right" >* Meta Title : </td>
				  <td width="30%" align="left" ><input type=text name=txtMetaTitle id=txtMetaTitle value="{$txtMetaTitle}" style="width:250px;"></td>				   {if $ErrorMsg["txtMetaTitle"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtMetaTitle"]}</font></td>{else} <td width="50%" align="left" id="errmsgmetatitle"></td>{/if}
				</tr>				<tr>
				  <td width="20%" align="right" valign="top">*Meta Keywords :</td>
				  <td width="30%" align="left" >
				  <textarea name="txtMetaKeywords" rows="10" cols="35" id="txtMetaKeywords" style="width:250px;">{$txtMetaKeywords}</textarea>
                  </td>{if $ErrorMsg["txtMetaKeywords"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtMetaKeywords"]}</font></td>{else} <td width="50%" align="left" id="errmsgmetakey"></td>{/if}
				</tr>				<tr>
				  <td width="20%" align="right" valign="top">*Meta Description :</td>
				  <td width="30%" align="left" >
				  <textarea name="txtMetaDescription" rows="10" cols="35" id="txtMetaDescription" style="width:250px;">{$txtMetaDescription}</textarea>
                  </td>{if $ErrorMsg["txtMetaDescription"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtMetaDescription"]}</font></td>{else} <td width="50%" align="left" id="errmsgmetades"></td>{/if}
				</tr>									
								
				<tr>
				  <td width="20%" align="right" valign = top >*Description  : </td>
				  <td width="30%" align="left" ><textarea name = 'desc' id = 'desc' cols = "35" rows = "10" style="width:250px;">{$desc}</textarea></td>	{if $ErrorMsg["desc"] != ''} <td valign = 'top' width="50%" align="left" ><font color = "red">{$ErrorMsg["desc"]}</font></td>{else} <td width="50%" align="left" id="errmsgdes"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right">*Status  : </td>
				  <td width="30%" align="left" >
				    <select name = "status" id="status" style="width:150px;"> 
					  <option {if $status == '0'} value = "0" selected = 'selected' {else} value = "0" {/if}>Inactive</option>					<option {if $status == '1'} value = "1" selected = 'selected' {else} value = "1" {/if}>Active</option>		
					 </select>
				 </td>				   
				 <td width="50%" align="left"></td>
				</tr>
				<tr>
				  <td >&nbsp;</td>
				  <td align="left" style="padding-left:50px;" >
				  <input type="hidden" name="catid" value="<?php echo $catid ?>" />
				  <input type="submit" name="btnSave" id="btnSave" value="Save" style="cursor:pointer">
				  &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit" style="cursor:pointer">
				  </td>
				</tr>
			      </div>
			    </form>
			    </TABLE>
<!--			</fieldset>-->
	            </td>
		  </tr>
		</TABLE>
	      </TD>
            </TR>
          </TBODY></TABLE>
        </TD>
      </TR>
    </TBODY></TABLE>
  </TD>
</TR>
<script type="text/javascript">

jQuery(document).ready(function(){

	jQuery("#btnSave").click(function(){
	
		var cityname = jQuery("#txtCityName").val();
		var CityUrl = jQuery("#txtCityUrl").val();
		var DisplayOrder = jQuery("#DisplayOrder").val();
		var txtMetaTitle = jQuery("#txtMetaTitle").val();
		var MetaKeywords = jQuery("#txtMetaKeywords").val();
		var MetaDescription = jQuery("#txtMetaDescription").val();
		var status = jQuery("#status").val();
		var desc = jQuery("#desc").val();
		
		if(cityname==''){
		
			jQuery('#errmsgname').html('<font color="red">Please enter City name</font>');
			jQuery("#txtCityName").focus();
			return false;
		}else{
			jQuery('#errmsgname').html('');
		}
		
		 if(CityUrl==''){
		
			jQuery('#errmsgurl').html('<font color="red">Please enter City url</font>');
			jQuery("#txtCityUrl").focus();
			return false;
		}else{
			jQuery('#errmsgurl').html('');
		}
		
		if(DisplayOrder==''){
		
			jQuery('#errmsgdispord').html('<font color="red">Please select display order</font>');
			jQuery("#DisplayOrder").focus();
			return false;
		}else{
			jQuery('#errmsgdispord').html('');
		}
		
		if(txtMetaTitle==''){
		
			jQuery('#errmsgmetatitle').html('<font color="red">Please enter meta title</font>');
			jQuery("#txtMetaTitle").focus();
			return false;
		}else{
			jQuery('#errmsgmetatitle').html('');
		}
		
		if(MetaKeywords==''){
		
			jQuery('#errmsgmetakey').html('<font color="red">Please enter meta keywords</font>');
			jQuery("#txtMetaKeywords").focus();
			return false;
		}else{
			jQuery('#errmsgmetakey').html('');
		}
		
		if(MetaDescription==''){
		
			jQuery('#errmsgmetades').html('<font color="red">Please enter meta description</font>');
			jQuery("#txtMetaDescription").focus();
			return false;
		}else{
			jQuery('#errmsgmetades').html('');
		}
			
		if(desc==''){
		
			jQuery('#errmsgdes').html('<font color="red">Please enter the description</font>');
			jQuery("#desc").focus();
			return false;
		}else{
			jQuery('#errmsgdes').html('');
		}

	});

});

</script>