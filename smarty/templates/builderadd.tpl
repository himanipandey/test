<script type="text/javascript" src="js/jquery.js"></script>
<!--<script type="text/javascript" src="js/photo.js"></script>-->
<script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
    tinyMCE.init({
        //mode : "textareas",
        mode : "specific_textareas",
        editor_selector : "myTextEditor",
        theme : "advanced"
    });

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


  function getPhotos(){
			
		var template = '<img src="{$imgSrc}" width = 150 height = 100 />';

		$("a#view").html( template );
		$("a#view").fancybox();
	
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
      
     $("#buidler-detail-button").bind('click',function(){
	    var bldrid = $('#newbuilder').val().trim();
        var oldBuilder = "{$builderid}";

        if(bldrid == '' || bldrid == undefined) {
            $("#err").html("<font color=red>Please enter new builder id</font>");
            return false;
        } 
        else {
			if(bldrid == oldBuilder){
		      $("#err").html("<font color=red>New Builder ID can not be same.</font>");
              return false;
		    }
            else if (isNaN(bldrid)) {
              $("#err").html("<font color=red>Please enter numeric builder id</font>");
              return false;
            }
            else if(bldrid < 100000 || bldrid > 500000) {
              $("#err").html("<font color=red>Please enter correct builder id</font>");
              return false;
            }else{
                $("#err").html("");
             }
        } 
        
        $.ajax  ({
            type: "POST",
            url: "getBuilderImage.php",
            data: 'part=builderInfo&newBuilder='+ bldrid,
            dataType : "html",
             success: function(responsedata)  {
                 responsedata = responsedata.trim();
                 if(responsedata == "" || responsedata == undefined){
                      $("#buttons-detail-cont #err").html("<font color=red>Builder is not exist in database!</font>");
                      return false;
                 }else if(responsedata == "Inactive"){
                      $("#buttons-detail-cont #err").html("<font color=red>Builder is Inactive!</font>");
                      return false;
                 }else {
					$("#buttons-replace-cont").show(); 
					$("#buttons-detail-cont").hide();
					$('#buttons-err-cont').hide();
                    $("#buttons-replace-cont #err").html("<font color=green>New Builder: <b>"+ responsedata +"</b></font>");
                    $("#buttons-replace-cont #buidler-replace-button").attr('rel',bldrid );
                    $('#newbuilder').attr("disabled",true);
                }
            }
       });
       
       $('#buttons-replace-cont #builder-cancel-button,#buttons-err-cont #builder-cancel-button').live('click',function(){
		    $("#buttons-replace-cont #err").html("");
            $("#buttons-replace-cont #buidler-replace-button").attr('rel','');
	      	$("#buttons-replace-cont").hide(); 
	      	$('#buttons-err-cont').hide();
	      	 $("#buttons-err-cont #errs").html("");
			$("#buttons-detail-cont").show();	
			$("#buttons-detail-cont #err").html("");   
			$('#newbuilder').val("");
			$('#newbuilder').attr("disabled",false);
	   });
       count = 0;
        $("#buidler-replace-button").bind('click', function(){
     	  $(this).attr("disabled",true);
		  $("#buttons-replace-cont #loader").show();		 
          var builderinfo = [];
          builderinfo[0] = $(this).attr('data');
          builderinfo[1] = $(this).attr('rel');
          
          if((builderinfo[0]!="" && builderinfo[0]!= undefined) && (builderinfo[1] != "" && builderinfo[1]!= undefined)){			  
             $.ajax({
                 type: "POST",                
                 data: 'part=replace-builder&builderinfo='+ builderinfo,
                 url: "getBuilderImage.php",
                success: function(flag){	
					$('#buttons-err-cont').remove();
					$("#buttons-replace-cont #loader").hide();				
                 if(flag == 1) {						 				
                   window.location = "/BuilderList.php";                               
                 }else{								
					$(this).attr("disabled","false");
					$("#buttons-replace-cont").hide(); 
					$("#buttons-detail-cont").hide();
					$('#buttons-err-cont').show();	    
					$('#buttons-err-cont #errs').html("<font color=red>Builder migration failed!</font>");
				  }
                }
             });
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
			      				<tr>
					<td  align = "center" colspan = "2">
						
					  
					   <font color = "red" style="font-size:17px;">{$ErrorMsg2}</font><br>
					 
					</td>
				</tr>
                                <tr>
                                     <td >&nbsp;</td>
                                    <td>
                                        <fieldset>
                                            <legend><b>Want To Replace This Builder With Another Builder</b></legend>
                                            <div style="margin:30px;">Enter New Builder Id: <input type="text" name="newbuilder" id="newbuilder" value="" style="width:200px;"> &nbsp;&nbsp; 
                                            <span id="buttons-detail-cont">
                                              <input type="button" id="buidler-detail-button" value="Get Builder Details"/>
                                              <span id="err"></span>
                                            </span>
                                            <span id="buttons-err-cont" style="display:none">												
                                              <span id="errs"></span>
                                              <input type="button" value="Try Again!" id="builder-cancel-button" />
                                            </span> 
                                            <br/>
                                            <span id="buttons-replace-cont" style="display:none">												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                              <img src="/images/ajax-loader.gif" style="position:absolute;display:none" id="loader" />
                                              <input type="button" id="buidler-replace-button" value="Replace Builder" data = "{$builderid}" rel=""/>
                                              with  
                                              <span id="err"></span>
                                              OR
                                              <input type="button" value="Cancel" id="builder-cancel-button" />
                                            </span>   
                                           
                                      </fieldset>
                                      
                                    </td>
                                </tr>
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
				  <td width="30%" align="left" ><textarea name="txtBuilderDescription" rows="10" class ="myTextEditor" cols="45">{$txtBuilderDescription}</textarea>
				   {if $txtBuilderDescription != ''}
                                      <input type="hidden" name="txtOldBuilderDescription" value="yes" />
                                   {else} 
                                       <input type="hidden" name="txtOldBuilderDescription" value="" />
                                   {/if}
				  {if ($dept=='ADMINISTRATOR') || ($dept=='CONTENT')}
                   <br/><br/>
                   <input type="checkbox" name="content_flag" {if $contentFlag}checked{/if}/> Reviewed?
				  {/if}
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
				<input type = 'hidden' name = 'imgSrc' value = '{$imgSrc}'>
					
				
				<tr>
					<td width="20%" align="right" valign = top>Current Image : </td>
					<td width="20%" align="left" >
					
					
					<div id='content'>
								<a id="view" href="" onclick="getPhotos(); return false;" title="Builder Logo">View Image</a>  
					</div>
				  
				</tr>
				
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
				  <td width="20%" align="right" > Meta Title : </td>
				  <td width="30%" align="left" ><input type=text name=txtMetaTitle id=txtMetaTitle value="{$txtMetaTitle}" style="width:360px;"></td>
				   <td width="50%" align="left"<td width="50%" align="left"></td>
				</tr>
				<tr>
				  <td width="20%" align="right" valign="top">Meta Keywords :</td>
				  <td width="30%" align="left" >
				  <textarea name="txtMetaKeywords" rows="10" cols="45">{$txtMetaKeywords}</textarea>
                  </td>
                  <td width="50%" align="left"></td>
				</tr>
				<tr>
				  <td width="20%" align="right" valign="top">Meta Description :</td>
				  <td width="30%" align="left" >
				  <textarea name="txtMetaDescription" rows="10" cols="45">{$txtMetaDescription}</textarea>
				  </td>
                  <td width="50%" align="left"></td>
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
				  <td width="20%" align="right" > <font color = "red">*</font>Website : </td>
				  <td width="30%" align="left" ><input type=text name="website" id="website" value="{$website}" style="width:360px;"></td>
				  {if $ErrorMsg["website"] != ''} <td nowrap width="50%" align="left" ><font color = "red">{$ErrorMsg["website"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
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
                                                   <option value = "{$item['PROJECT_ID']}"                                     {if in_array($item['PROJECT_ID'],$arrContactProjectMapping[$arrContact[$cnt]['ID']])} selected {/if}>
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
