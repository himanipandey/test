<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
var id = 0;
$(document).ready(function()
{
$(".cityId").change(function()
{
id=$(this).val();
var dataString = 'id='+ id;

$.ajax
({
type: "POST",
url: "RefreshSuburb.php",
data: dataString,
cache: false,
success: function(html)
{
$(".suburbId").html(html);
}
});

});


});

$(document).ready(function()
{
$(".suburbId").change(function()
{
var suburb_id=$(this).val();
var dataString = 'suburb_id='+ suburb_id+"&id="+id;

$.ajax
({
type: "POST",
url: "RefreshSuburb.php",
data: dataString,
cache: false,
success: function(html)
{

$(".localityId").html(html);
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
	   		{include file="{$PROJECT_ADD_TEMPLATE_PATH}left.tpl"}
	  </TD>
          <TD class=border-all vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</TD>
          <TD class=border-rt vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>{if $localityid == ''} Add New {else} Edit {/if} Locality</TD>
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
			    <form method="post" enctype="multipart/form-data">
			      <div>
				<tr>
				  <td width="20%" align="right" >*Locality Name : </td>
				  <td width="30%" align="left"><input type=text name=txtLocalityName id=txtLocalityName value="{$txtLocalityName}" style="width:357px;"></td>
				 {if $ErrorMsg["txtLocalityName"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtLocalityName"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				
			<tr>
				  <td width="20%" align="right">*City : </td>
				  <td width="30%" align="left" >
					<select name = "cityId" class="cityId">
							<option value =''>Select City</option>
							 {section name=data loop=$CityDataArr}
							 	<option {if $cityId == {$CityDataArr[data].CITY_ID}} value ='{$cityId}' selected="selected" {else} value ='{$CityDataArr[data].CITY_ID}' {/if}>{$CityDataArr[data].LABEL}</option>
							 {/section}	
						</select>				  
				  </td>
				   {if $ErrorMsg["builderId"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["builderId"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				
				<tr>
				  <td width="20%" align="right">*Suburbs : </td>
				  <td width="30%" align="left"  >
				  			<select name="suburbId" class="suburbId" >
				  			<option value="">Select Suburb</option> 
				  			{if count($suburbSelect) gt 0} 
							
							{section name=data loop=$suburbSelect}
							
							<option {if $suburbId == {$suburbSelect[data].SUBURB_ID}} value = "{$suburbSelect[data].SUBURB_ID}" selected="selected" {else}  value = "{$suburbSelect[data].SUBURB_ID}" {/if}>{$suburbSelect[data].LABEL}</option>
  							{/section}	
  						   {/if}							 
 						</select> 		
						
				  </td>
				</tr>
				
			
				<tr>
				  <td width="20%" align="right" >* Meta Title : </td>
				  <td width="30%" align="left" ><input type=text name=txtLocalityMetaTitle id=txtLocalityMetaTitle value="{$txtLocalityMetaTitle}" style="width:360px;"></td>
				   {if $ErrorMsg["txtLocalityMetaTitle"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtLocalityMetaTitle"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right" valign="top">*Meta Keywords :</td>
				  <td width="30%" align="left" >
				  <textarea name="txtMetaKeywords" rows="10" cols="45">{$txtMetaKeywords}</textarea>
                  </td>
                  {if $ErrorMsg["txtMetaKeywords"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtMetaKeywords"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right" valign="top">*Meta Description :</td>
				  <td width="30%" align="left" >
				  <textarea name="txtMetaDescription" rows="10" cols="45">{$txtMetaDescription}</textarea>
                  </td>
                  {if $ErrorMsg["txtMetaDescription"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtMetaDescription"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				
				<tr>
				  <td width="20%" align="right" valign="top">*URL :</td>
				  
				  <td width="50%" align="left" ><input type=text name=txtLocalityUrl id=txtLocalityUrl value="{$txtLocalityUrl}" style="width:360px;"></td>
				   {if $ErrorMsg["txtLocalityUrl"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtLocalityUrl"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right">*Active : </td>
				  <td width="30%" align="left">
						 <select name="Active"  id="Active" class="field">
						 <option {if $Active == 0}  value="0" selected="selected" {else} value="0"{/if} >0</option> 
  							<option {if $Active == 1} value="1" selected="selected" {else} value="1" {/if} >1</option> 
  							
  							  	
 						 </select>
				 
				  </td>
					 <td width="50%" align="left"></td>
				</tr>
				
				<tr>
				  <td >&nbsp;</td>
				  <td align="left" style="padding-left:152px;" >
				  <input type="hidden" name="catid" value="<?php echo $catid ?>" />
				  <input type="submit" name="btnSave" id="btnSave" value="Save">
				  &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit">
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
<TR>
  <TD class=white-bg vAlign=top align=middle>&nbsp;</TD>
</TR>
<TR>
  <TD vAlign=top align=middle bgColor=#94a1b0 height=58>