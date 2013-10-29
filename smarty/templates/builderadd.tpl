<script type="text/javascript" src="js/jquery.js"></script>
<script>
	function showhide_row(numrow)
	{
		for(i=1;i<=10;i++)
		{
		
		 jQuery(".detail"+i).hide();
		}	
		for(i=1;i<=numrow;i++)
		{
		  jQuery(".detail"+i).show();
		}
	}

	function builder_validation()
	{
		if($("#pincode").value() != '')
		{
			alert();
		}
	}

  function isNumberKey(evt)
  {
 	 var charCode = (evt.which) ? evt.which : event.keyCode;
 	 if(charCode == 99 || charCode == 118)
   	 	return true;
	 if (charCode > 31 && (charCode < 46 || charCode > 57) || (charCode == 13))
		return false;

	 return true;
  }
  $(document).ready(function(){
      $("#txtBuilderName").change(function(){
        var builderid = $(this).val();
        $.ajax  ({
            type: "POST",
            url: "getBuilderImage.php",
            data: 'part=builderImage&builderid='+ builderid,
             dataType : "html",
             success: function(responsedata)  {
                //alert(responsedata);
                var splitArr = responsedata.split("@@");
                  $("#builderBox").html('<img alt='+splitArr[0]+' src='+splitArr[1]+' align="left" style="width: 100px; height: 40px; margin-right:10px; border:solid 1px #CCC;">');
            }
       });
      });
  });

</script>

<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>

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
          <TD class=border-all vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</TD>
          <TD class=border-rt vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=../images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>{if $builderid == ''} Add New {else} Edit {/if} Builder</TD>
                      <TD align=right ></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
		
		{if $accessBuilder == ''}
		     
<!--			<fieldset class="field-border">
			  <legend><b>Message</b></legend>-->
			  <TABLE cellSpacing=2 cellPadding=4 width="93%" align=center border=0>
			    <form method="post" enctype="multipart/form-data">
			      <div>
                                  <tr style="">
                                    <td width="20%" align="right" ><font color = "red"></font>Check if builder exist already : </td>
                                    <td width="30%" align="left" colspan="2">
                                        <select name=txtBuilderName id= "txtBuilderName" style="width:357px;">
                                           <option value="See Builders">See Builders</option>
                                            {foreach $BuilderDataArr as $k => $v}
                                                <option value="{$v["BUILDER_ID"]}">{$v["BUILDER_NAME"]}</option>
                                            {/foreach}
                                        </select>
                                        <div style="height:40px; float:right; margin:-15px 5px 0px 0px;" id="builderBox"></div>
                                    </td>
                                  </tr>
				<tr>
                                    <td width="20%" align="right" ><font color = "red">*</font>Builder Display Name : </td>
                                    <input type=hidden name="oldbuilder" id="oldbuilder" value="{$oldval}" style="width:357px;">
                                    <td width="30%" align="left"><input type=text name=txtBuilderName id=txtBuilderName value="{$txtBuilderName}" style="width:357px;"></td>
                                    {if $ErrorMsg["txtBuilderName"] != ''}
                                    <td width="50%" align="left" nowrap><font color = "red">{$ErrorMsg["txtBuilderName"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
                                <tr>
                                    <td width="20%" align="right" ><font color = "red">*</font>Legal Entity Name : </td>
                                    <td width="30%" align="left"><input type=text name="legalEntity" id="legalEntity" value="{$legalEntity}" style="width:357px;"></td>
                                    {if $ErrorMsg["legalEntity"] != ''}
                                    <td width="50%" align="left" nowrap><font color = "red">{$ErrorMsg["legalEntity"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right" valign="top"><font color = "red">*</font>Builder Description :</td>
				  <td width="30%" align="left" ><textarea name="txtBuilderDescription" rows="10" cols="45">{$txtBuilderDescription}</textarea>
</td>
					{if $ErrorMsg["txtBuilderDescription"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtBuilderDescription"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>

				<tr>
					 <td width="20%" align="right" ><font color = "red">*</font>Builder URL : </td>

					<td width="30%" align="left" >
						{if $builderid != ''  && $urlEditAccess == 0}
							<input type=text disabled name=txtBuilderUrl id=txtProjectUrl value="{$txtBuilderUrl}" style="width:360px;" readonly>
						{else}
							<input type=text disabled name=txtBuilderUrl id=txtProjectUrl value="{$txtBuilderUrl}" style="width:360px;">
						{/if}
						<input type = "hidden" name = "txtBuilderUrlOld" value = "{$txtBuilderUrlOld}">
						

					</td>
					 <td width="50%" align="left" nowrap>
					 	<font color = "red">
						 	{if $ErrorMsg["txtBuilderUrl"] != ''}

						 		{$ErrorMsg["txtBuilderUrl"]}
						 	{/if}

							 	{if $ErrorMsg["BuilderUrlExists"] != ''}

							 		{$ErrorMsg["BuilderUrlExists"]}
							 	{/if}


					 	</font>

					 </td>

				</tr>
				<input type = 'hidden' name = 'imgedit' value = '{$imgedit}'>
					{if $img != ''}
				
				<tr>
					<td width="20%" align="right" valign = top>Current Image : </td>
					<td width="20%" align="left" >
					
					
					<div id='content'>
								<a id="view" href="{$imgDisplayPath}{$img}" title="Builder Logo">View Image</a>  
								<script type="text/javascript">
								$(document).ready(function() {
								$("a#view").fancybox();
								});
								</script>
					</div>
				  
				</tr>
				{/if}
				<tr {if $builderid == ''} style="display:none" {/if}>
				  <td width="20%" align="right" ><font color = "red">*</font>Builder Image : </td>
				  <td width="30%" align="left">
                      <input type=file name='txtBuilderImg'  style="width:400px;">
                      <input type="hidden" name="serviceImageId" value="{$service_image_id}">
                  </td>
				    <td width="50%" align="left" nowrap>
				    	{if $ErrorMsg["ImgError"] != ''}
				    	
				    		<font color = "red">{$ErrorMsg["ImgError"]}</font>
				    	{/if}
				    </td>
				</tr>
				<tr>
				  <td width="20%" align="right"><font color = "red">*</font>Display Order : </td>
				  <td width="30%" align="left" >
				  
				  
				 
						 <select name="DisplayOrder"  id="DisplayOrder" class="field">
						 
						 	<option value="">Select </option> 
  							{section name=foo start=1 loop=101 step=1}
  							<option {if $DisplayOrder == {$smarty.section.foo.index}} value="{$DisplayOrder}" selected = 'selected' {else} value="{$smarty.section.foo.index}"{/if} >{$smarty.section.foo.index}</option>
  							{/section}
  							 	
 						 </select>
				 
				  </td>
				  {if $ErrorMsg["DisplayOrder"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["DisplayOrder"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right" ><font color = "red">*</font> Meta Title : </td>
				  <td width="30%" align="left" ><input type=text name=txtMetaTitle id=txtMetaTitle value="{$txtMetaTitle}" style="width:360px;"></td>
				   {if $ErrorMsg["txtMetaTitle"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtMetaTitle"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right" valign="top"><font color = "red">*</font>Meta Keywords :</td>
				  <td width="30%" align="left" >
				  <textarea name="txtMetaKeywords" rows="10" cols="45">{$txtMetaKeywords}</textarea>
                  </td>
                  {if $ErrorMsg["txtMetaKeywords"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtMetaKeywords"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right" valign="top"><font color = "red">*</font>Meta Description :</td>
				  <td width="30%" align="left" >
				  <textarea name="txtMetaDescription" rows="10" cols="45">{$txtMetaDescription}</textarea>
                  </td>
                  {if $ErrorMsg["txtMetaDescription"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtMetaDescription"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>

				</tr>
				<tr>
				  <td width="20%" align="right" valign="top" >Address :</td>
				  <td width="30%" align="left" >
				  <textarea name="address" rows="10" cols="45">{$address}</textarea>
                  </td>
                 <td width="50%" align="left" </td>
				</tr>
				<tr>
				  <td width="20%" align="right" ><font color = "red">*</font>City : </td>
				  <td width="30%" align="left">
                                    <select name = "city" class="city">
                                        <option value =''>Select City</option>
                                        {foreach from = $CityDataArr key = key item = item}
                                           <option {if $city == {$key}} selected {/if} value = {$key}>{$item}</option>
                                         {/foreach}	
                                    </select>				  
				  </td>
				  {if $ErrorMsg["txtCity"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtCity"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>

				<tr>
				  <td width="20%" align="right" > Pincode : </td>
				  <td width="30%" align="left" ><input type=text name="pincode" id="pincode" value="{$pincode}" style="width:360px;" onkeypress='return isNumberKey(event)'></td>
				   <td width="50%" align="left" ></td>
				</tr>

				<tr>
				  <td width="20%" align="right" > CEO MD Name : </td>
				  <td width="30%" align="left" ><input type=text name="ceo" id="ceo" value="{$ceo}" style="width:360px;"></td>
				   <td width="50%" align="left" ></td>
				</tr>

				<tr>
				  <td width="20%" align="right" > Total Number of employee : </td>
				  <td width="30%" align="left" ><input type=text name="employee" id="employee" value="{$employee}" style="width:360px;" onkeypress='return isNumberKey(event)'></td>
				   <td width="50%" align="left" ></td>
				</tr>
				<tr>
				  <td width="20%" align="right" > Total Number of Delivered Project : </td>
				  <td width="30%" align="left" ><input type=text name="delivered_project" id="delivered_project" value="{$delivered_project}" style="width:360px;" onkeypress='return isNumberKey(event)'></td>
				   <td width="50%" align="left" ></td>
				</tr>

				<tr>
				  <td width="20%" align="right" > Total Area Delivered : </td>
				  <td width="30%" align="left" ><input type=text name="area_delivered" id="area_delivered" value="{$area_delivered}" style="width:360px;" onkeypress='return isNumberKey(event)'></td>
				   <td width="50%" align="left" ></td>
				</tr>

				<tr>
				  <td width="20%" align="right" > Total Number Of On Going Project: </td>
				  <td width="30%" align="left" ><input type=text name="ongoing_project" id="ongoing_project" value="{$ongoing_project}" style="width:360px;" onkeypress='return isNumberKey(event)'></td>
				   <td width="50%" align="left" ></td>
				</tr>

				<tr>
				  <td width="20%" align="right" > Website : </td>
				  <td width="30%" align="left" ><input type=text name="website" id="website" value="{$website}" style="width:360px;"></td>
				   <td width="50%" align="left" ></td>
				</tr>

				<tr>
				  <td width="20%" align="right" > Revenue : </td>
				  <td width="30%" align="left" ><input type=text name="revenue" id="revenue" value="{$revenue}" style="width:360px;" onkeypress='return isNumberKey(event)'></td>
				   <td width="50%" align="left" ></td>
				</tr>

				<tr>
				  <td width="20%" align="right" > Debt : </td>
				  <td width="30%" align="left" ><input type=text name="debt" id="debt" value="{$debt}" style="width:360px;" onkeypress='return isNumberKey(event)'></td>
				   <td width="50%" align="left" ></td>
				</tr>

				<tr>
					<td width="20%" align="right" >Established Year : </td>
					<td width="30%" align="left" >
						<input name="established" value="{$established}" type="text" class="formstyle2" id="f_date_c_to" readonly="1" size="10" />  <img src="../images/cal_1.jpg" id="f_trigger_c_to" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
					</td>
				   <td width="50%" align="left" ></td>
				</tr>

				<tr>
					<td width="5%" align="right"></td>
					<td width="80%" align="left">
						<table width ="50%" align = "left" style = "border:1px solid;">
							<tr>
								<td align="center" colspan ="2"><b>Contact Information</b></td>
							</tr>
							<tr>
								<td align="center">&nbsp;</td>
							</tr>
							<tr>
								<td align="center"  width="10%" nowrap><b>Number of contacts:</b></td>
								<td align="left" width="90%">
									<select name ="no_contact" id ="no_contact" onchange = "showhide_row(this.value);">
										{for $var= 1 to 10}
										<option value = "{$var}" {if $var == $Contact} selected{/if}>{$var}</option>
										{/for}
									</select>
								</td>
							</tr>
							<tr>
								<td align="center">&nbsp;</td>
							</tr>
								
								{for $start = 1 to 10}
									{$cnt = ($start-1)}

									
									<tr class = "detail{$start}" 
									
									{if $start != 1} 
										
										{if (array_key_exists($cnt,$arrContact) AND $builderid != '')}
										
										{else} 
											style="display:none;"
										{/if}

									{else}
										
									{/if}
									>
										<td width="10%" align="right" valign ="top" nowrap>
											Contact Name {$start}:</td>
										<td width="90%" align="left" valign ="top"><input type = "text" name = "contact_name[]" value = "{$arrContact[$cnt]['NAME']}">
										</td>
									</tr>
									<tr class = "detail{$start}" 
									
									{if $start != 1} 
										
										{if (array_key_exists($cnt,$arrContact) AND $builderid != '')}
										
										{else} 
											style="display:none;"
										{/if}

									{else}
										
									{/if}
									>
										<td width="10%" align="right" valign ="top">
											Phone {$start}:</td>
										<td width="90%" align="left" valign ="top">	<input type = "text" name = "contact_ph[]"  value = "{$arrContact[$cnt]['PHONE']}" onkeypress='return isNumberKey(event)'>
										</td>
									</tr>
									<tr class = "detail{$start}" 
									
									{if $start != 1} 
										
										{if (array_key_exists($cnt,$arrContact) AND $builderid != '')}
										
										{else} 
											style="display:none;"
										{/if}

									{else}
										
									{/if}
									>
										<td width="10%" align="right" valign ="top">
											Email {$start}:</td>
										<td width="90%" align="left" valign ="top">	<input type = "text" name = "contact_email[]" value = "{$arrContact[$cnt]['EMAIL']}">
										</td>
									</tr>

									<tr class = "detail{$start}" 
									
									{if $start != 1} 
										
										{if (array_key_exists($cnt,$arrContact) AND $builderid != '')}
										
										{else} 
											style="display:none;"
										{/if}

									{else}
										
									{/if}
									>
										<td width="10%" align="right" valign ="top">
											Projects {$start}:</td>
										<td width="90%" align="left" valign ="top">	
											<select name = "projects_{$start}[]" multiple>
												<option value = "">Select Project</option>
												{foreach from = $ProjectList key = key item = item}
                                                                                                    <option value = "{$item['PROJECT_ID']}" 
                                                                                                    {if strstr($arrContactProjectMapping[$arrContact[$cnt]['ID']],$item['PROJECT_ID'])} selected {/if}>
                                                                                                    {$item['PROJECT_NAME']}</option>
												{/foreach}
												</option>
											</select>
										</td>
									</tr>

									<tr class = "detail{$start}" 
									
									{if $start != 1} 
										
										{if (array_key_exists($cnt,$arrContact) AND $builderid != '')}
										
										{else} 
											style="display:none;"
										{/if}

									{else}
										
									{/if}
									>
										<td colspan = "2" style="background:none; border:dotted 1px #999999; border-width:1px 0 0 0; height:1px; width:100%; margin:0px 0px 0px 0px; padding-top:1px;padding-bottom:1px;">&nbsp;</td>
									</tr>
								{/for}

						</table>
					</td>
					
					</td>
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
                <script type="text/javascript">
                     Calendar.setup({

                         inputField     :    "f_date_c_to",     // id of the input field
                     //    ifFormat       :    "%Y/%m/%d %l:%M %P",      // format of the input field
                     ifFormat       :    "%Y-%m-%d",      // format of the input field
                         button         :    "f_trigger_c_to",  // trigger for the calendar (button ID)
                         align          :    "Tl",           // alignment (defaults to "Bl")
                         singleClick    :    true,
                     showsTime		:	true

                     });
                  </script>                                             
                {else}
                    <font color="red">No Access</font>
                {/if}
	      </TD>
            </TR>
          </TBODY></TABLE>
        </TD>
      </TR>
    </TBODY></TABLE>
  </TD>
</TR>
