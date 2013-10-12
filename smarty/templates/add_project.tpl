<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>

<script language="javascript">
	function chkConfirm()
	{
		return confirm("Are you sure! you want to delete this record.");
	}

	   var id = 0;
	   $(document).ready(function()   {
	   $(".cityId").change(function()   {
			id=$(this).val();
			var dataString = 'part=refreshLoc&id='+ id;

	   $.ajax   ({
		   type: "POST",
		   url: "RefreshSuburb.php",
		   data: dataString,
		   cache: false,
		   success: function(html)   {
				$(".localityId").html(html);
			}
	   });

	  });

	});

	   $(document).ready(function()   {
			 $(".localityId").change(function()  {

				var locality_id = $(this).val();
				var cid = $(".cityId").val();				
				var dataString = 'part=refreshLoc&locality_id='+ locality_id +"&id = "+cid;

	   $.ajax  ({
			type: "POST",
			url: "RefreshSuburb.php",
			data: dataString,
			cache: false,
			success: function(html)  {
				$(".suburbId").html(html);
			}
	   });
	  });
          
          $(".builderId").change(function(){
            var builderid = $(this).val();
            $.ajax  ({
                type: "POST",
                url: "getBuilderImage.php",
                data: 'part=builderImage&builderid='+ builderid,
                 dataType : "html",
                 success: function(responsedata)  {
                    //alert(responsedata);
                    var splitArr = responsedata.split("@@");
                      $("#builderbox").html('<img alt='+splitArr[0]+' src='+splitArr[1]+' align="left" style="width: 100px; height: 40px; margin-right:10px; border:solid 1px #CCC;">');
                }
	   });
          });
	});

	function change_type(type_val)
	{
		if(type_val == 'app_form')
		{
			document.getElementById("app_form").style.display = '';
			document.getElementById("app_form_pdf").style.display = 'none';
		}
		else
		{
			document.getElementById("app_form").style.display = 'none';
			document.getElementById("app_form_pdf").style.display = '';
		}
	}

	function change_type_price(type_val)
	{
		if(type_val == 'price_list')
		{
			document.getElementById("price_list").style.display = '';
			document.getElementById("price_list_pdf").style.display = 'none';
		}
		else
		{
			document.getElementById("price_list").style.display = 'none';
			document.getElementById("price_list_pdf").style.display = '';
		}
	}

	function change_type_payment(type_val)
	{
		if(type_val == 'payment')
		{
			document.getElementById("payment").style.display = '';
			document.getElementById("payment_pdf").style.display = 'none';
		}
		else
		{
			document.getElementById("payment").style.display = 'none';
			document.getElementById("payment_pdf").style.display = '';
		}
	}

	function show_hide(id)
	{
        if(id == '2' || id == '3')
		{
			jQuery("#no_of_villa").show();
			
			
			jQuery("#no_of_plot").val('');
            jQuery("#no_of_plot").hide();

			jQuery("#no_of_towers").val('');
			jQuery("#no_of_flats").val('');
           
		}
        else if(id == '4' || id == '5' || id =='6')
        {
            jQuery("#no_of_plot").show();
            if(id == '5'){
                jQuery("#no_of_villa").show();

				jQuery("#no_of_towers").val('');
				jQuery("#no_of_flats").val('');
				jQuery("#no_of_plot").val('');

            }
            if(id == '6' || id == '4'){
				jQuery("#no_of_villa").val('');
                jQuery("#no_of_villa").hide();

				jQuery("#no_of_towers").val('');
				jQuery("#no_of_flats").val('');
				jQuery("#no_of_plot").val('');
                
            }
        }
		else
		{
			jQuery("#no_of_villa").val('');
			jQuery("#no_of_villa").hide();
			jQuery("#no_of_plot").val('');
            jQuery("#no_of_plot").hide();


			jQuery("#no_of_towers").val('');
			jQuery("#no_of_flats").val('');
           
		}

		if(id == '1' || id == '3' || id == '6')
		{
			jQuery("#no_of_towers").show();
            //jQuery("#no_of_towera").val('');
            jQuery("#no_of_flats").show();
           // jQuery("#no_of_flats").val('');

		    jQuery("#no_of_villa").val('');
			jQuery("#no_of_flats").val('');
			jQuery("#no_of_plot").val('');

		}
		else
		{
			jQuery("#no_of_towers").val('');
			jQuery("#no_of_towers").hide();
			jQuery("#no_of_flats").val('');
            jQuery("#no_of_flats").hide();

			jQuery("#no_of_villa").val('');
			jQuery("#no_of_plot").val('');
            
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

</script>
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
						  <TD class="h1" width="67%"><img height="18" hspace="5" src="images/arrow.gif" width="18">{if $projectId != ''} Edit Project  ({$BUILDER_NAME} {$txtProjectName}){else}Add New Project{/if} </TD>
						  <TD width="33%" align ="right"></TD>

						</TR>
					</TBODY>
				  </TABLE>
				</TD>
	      </TR>
		  <tr></tr>
			<TD vAlign="top" align="middle" class="backgorund-rt" height="450"><BR>

				<table cellSpacing="1" cellPadding="4" width="67%" align="center" border="0">
					 <form method="post" enctype="multipart/form-data" action = ''>
							<div>
							   <tr>
                                                                <td width="30%" align="right"><font color ="red">*</font><b>Project Name :</b> </td>
								  <td width="30%" align="left"><input type="text" name="txtProjectName" id="txtProjectName" value="{$txtProjectName}" style="width:357px;" /></td>

								  <td width="50%" align="left">
									  <font color="red">{if $ErrorMsg["txtProjectName"] != ''} {$ErrorMsg["txtProjectName"]} {/if}<span id = "err_project_name" style = "display:none;">Please enter project name!</span></font>
								  </td>
                                                                 <span id="builderbox" style="clear:both;float:right"></span>

							   </tr>
							   <tr>
								
                                                               <td width="20%" align="right"><font color ="red">*</font><b> Builder Name :</b> </td>
								  <td width="30%" align="left">
                                                                      
									 <select name="builderId" class="builderId">
										<option value="">Select Builder</option>
										{foreach from=$BuilderDataArr key=k item=v}
										<option {if $builderId == $k} value ="{$k}" selected="selected" {else} value ='{$k}'{/if} >{$v}</option>
										{/foreach}
									 </select>
									 <div id="imgPathRefresh"></div>
								  </td>
								  <td width="50%" align="left">
									  <font color="red"><span id = "err_builder_id" style = "display:none;">Please select builder name!</span></font>
								  </td>
                                                                  
							   </tr>
							   <tr>
								  <td width="20%" align="right"><font color ="red">*</font><b>City :</b> </td>
								  <td width="30%" align="left">
									 <select name="cityId" class="cityId" style="width:230px;">
										<option value="">Select City</option>
										{foreach from=$CityDataArr key=k item=v}
										<option  value ='{$k}' {if $cityId == $k} selected="selected" {/if}>{$v}</option>
										{/foreach}
									 </select>
								  </td>
								  <td width="50%" align="left">
									  <font color="red"><span id = "err_city_id" style = "display:none;">Please select city!</span></font>
								  </td>
                                                                   
							   </tr>
							   
							   <tr>
								  <td width="20%" align="right"><font color ="red">*</font><b>Locality :</b> </td>
								  <td width="30%" align="left">
									 <select name="localityId" class="localityId" style="width:230px;">
										<option value="">Select Locality</option>
										{foreach from=$localitySelect key=k item=v}
											<option {if $localityId == $k} value = "{$k}" selected="selected" {else}  value = "{$k}" {/if}>{$v}</option>
										{/foreach}
									 </select>
								  </td>
								 <td width="50%" align="left">
									  <font color="red"><span id = "err_locality_id" style = "display:none;">Please select locality!</span></font>
								 </td>
							   </tr>
                                                           
                                                           <tr>
								  <td width="20%" align="right"><font color ="red">*</font><b>Suburbs :</b> </td>
								  <td width="30%" align="left">
                                                                        <select name="suburbId" class="suburbId" style="width:230px;">
                                                                               <option value="">Select Suburb</option>
                                                                               {foreach from=$suburbSelect key=k item=v}
                                                                                       <option {if $suburbId == $k} value = "{$k}" selected="selected" {else}  value = "{$k}" {/if}>{$v}</option>
                                                                               {/foreach}

                                                                        </select>
								  </td>
								  <td width="50%" align="left">
									  <font color="red"><span id = "err_suburb_id" style = "display:none;">Please select Suburb!</span></font>
								  </td>
							   </tr>
                                                           
							   <tr>
								  <td width="20%" align="right" valign="top"><b><b><font color ="red">*</font><b>Project Description :</b> </td>
								  <td width="30%" align="left">
									 <textarea name="txtProjectDescription" rows="10" cols="45" id = "txtProjectDescription">{$txtProjectDescription}</textarea>
								  </td>
								  <td width="50%" align="left">
									  <font color="red"><span id = "err_project_desc" style = "display:none;">Please enter project description!</span></font>
								 </td>
							   </tr>
                                                           {if $userDepartment == 'DATAENTRY' || $userDepartment == 'NEWPROJECTAUDIT' || $userDepartment == 'ADMINISTRATOR'}
                                                            {if array_key_exists('projectRemark',$projectComments)}   
                                                            <tr>
                                                                <td width="20%" align="right" valign="top"><b>Project Old Remark :</b> </td>
                                                                <td width="30%" align="left" colspan="2">{$projectComments['projectRemark']->comment_text}</td>
                                                            </tr>
                                                            {else if array_key_exists('projectRemark',$projectOldComments)}
                                                              <tr>
                                                                <td width="20%" align="right" valign="top"><b>Project Old Remark :</b> </td>
                                                                <td width="30%" align="left" colspan="2">{$projectOldComments['projectRemark']->comment_text}</td>
                                                             </tr>
                                                            {/if}
                                                           <tr>
								  <td width="20%" align="right" valign="top"><b>Project Remark :</b> </td>
								  <td width="30%" align="left">
									 <textarea name="txtProjectRemark" rows="10" cols="45" id = "txtProjectRemark">{$txtProjectRemark}</textarea>
								  </td>
								  <td width="50%" align="left">
									  &nbsp;
								 </td>
							   </tr>
							   {/if}
                                                           {if $userDepartment == 'CALLCENTER' || $userDepartment == 'ADMINISTRATOR'}
                                                               {if array_key_exists('callingRemark',$projectComments)}   
                                                                <tr>
                                                                    <td width="20%" align="right" valign="top"><b>Calling Team Old Remark :</b> </td>
                                                                    <td width="30%" align="left" colspan="2">{$projectComments['callingRemark']->comment_text}</td>
                                                                </tr>
                                                                {elseif array_key_exists('callingRemark',$projectOldComments)}   
                                                                <tr>
                                                                    <td width="20%" align="right" valign="top"><b>Calling Team Old Remark :</b> </td>
                                                                    <td width="30%" align="left" colspan="2">{$projectOldComments['callingRemark']->comment_text}</td>
                                                                </tr>
                                                               {/if}
							    <tr>
								  <td width="20%" align="right" valign="top"><b>Calling Team Remark :</b> </td>
								  <td width="30%" align="left">
									 <textarea name="txtCallingRemark" rows="10" cols="45" id = "txtCallingRemark">{$txtCallingRemark}</textarea>
								  </td>
								  <td width="50%" align="left">
									  &nbsp;
								 </td>
							   </tr>
							   {/if}
                                                           {if $userDepartment == 'AUDIT-1' || $userDepartment == 'ADMINISTRATOR'}
                                                               {if array_key_exists('auditRemark',$projectComments)}    
                                                                <tr>
                                                                    <td width="20%" align="right" valign="top"><b>Audit Team Old Remark :</b> </td>
                                                                    <td width="30%" align="left" colspan="2">{$projectComments['auditRemark']->comment_text}</td>
                                                                </tr>
                                                                {elseif array_key_exists('auditRemark',$projectOldComments)}    
                                                                <tr>
                                                                    <td width="20%" align="right" valign="top"><b>Audit Team Old Remark :</b> </td>
                                                                    <td width="30%" align="left" colspan="2">{$projectOldComments['auditRemark']->comment_text}</td>
                                                                </tr>
                                                               {/if}
							    <tr>
								  <td width="20%" align="right" valign="top"><b>Audit Team Remark :</b> </td>
								  <td width="30%" align="left">
									 <textarea name="txtAuditRemark" rows="10" cols="45" id = "txtAuditRemark">{$txtAuditRemark}</textarea>
								  </td>
								  <td width="50%" align="left">
									  &nbsp;
								 </td>
							   </tr>
                                                           {/if}
                                                           {if $userDepartment == 'RESALE-CALLCENTER' || $userDepartment == 'ADMINISTRATOR'}
                                                               {if array_key_exists('secondaryRemark',$projectComments)}   
                                                                <tr>
                                                                    <td nowrap width="20%" align="right" valign="top"><b>Secondary Calling Team Old Remark :</b> </td>
                                                                    <td width="30%" align="left" colspan="2">{$projectComments['secondaryRemark']->comment_text}</td>
                                                                </tr>
                                                                {elseif array_key_exists('secondaryRemark',$projectOldComments)}   
                                                                <tr>
                                                                    <td nowrap width="20%" align="right" valign="top"><b>Secondary Calling Team Old Remark :</b> </td>
                                                                    <td width="30%" align="left" colspan="2">{$projectOldComments['secondaryRemark']->comment_text}</td>
                                                                </tr>
                                                               {/if}
                                                           <tr>
								  <td width="20%" align="right" valign="top"><b>Secondary Calling Team Remark :</b> </td>
								  <td width="30%" align="left">
									 <textarea name="secondaryRemark" rows="10" cols="45" id = "secondaryRemark">{$secondaryRemark}</textarea>
								  </td>
								  <td width="50%" align="left">
									  &nbsp;
								 </td>
							   </tr>
                                                           {/if}
                                                           {if $userDepartment == 'SURVEY' || $userDepartment == 'ADMINISTRATOR'}
                                                               {if array_key_exists('fieldSurveyRemark',$projectComments)}   
                                                                <tr>
                                                                    <td width="20%" align="right" valign="top"><b>Field Survey Team Old Remark :</b> </td>
                                                                    <td width="30%" align="left" colspan="2">{$projectComments['fieldSurveyRemark']->comment_text}</td>
                                                                </tr>
                                                                {elseif array_key_exists('fieldSurveyRemark',$projectOldComments)}   
                                                                <tr>
                                                                    <td width="20%" align="right" valign="top"><b>Field Survey Team Old Remark :</b> </td>
                                                                    <td width="30%" align="left" colspan="2">{$projectOldComments['fieldSurveyRemark']->comment_text}</td>
                                                                </tr>
                                                               {/if}
                                                           <tr>
								  <td width="20%" align="right" valign="top"><b>Field Survey Team Remark :</b> </td>
								  <td width="30%" align="left">
									 <textarea name="fieldSurveyRemark" rows="10" cols="45" id = "fieldSurveyRemark">{$fieldSurveyRemark}</textarea>
								  </td>
								  <td width="50%" align="left">
									  &nbsp;
								 </td>
							   </tr>
                                                           {/if}

							   <tr>
								  <td width="20%" align="right"><font color ="red">*</font><b>Project Address :</b> </td>
								  <td width="30%" align="left"><input type="text" name="txtProjectAddress" id="txtProjectAddress" value="{$txtAddress}" style="width:360px;" /></td>
								  <td width="50%" align="left">
									  <font color="red"><span id = "err_project_address" style = "display:none;">Please enter project address!</span></font>
								  </td>
							   </tr>
							   <tr>
								  <td width="20%" align="right"><font color ="red">*</font><b>Options Description :</b> </td>
								  <td width="30%" align="left"><input type="text" name="txtProjectDesc" id="txtProjectDesc" value="{$txtProjectDesc}" style="width:360px;" /><br><span style = "font-size:10px">Like:1bhk,2bhk etc.</span></td>

								  <td width="50%" align="left">
									  <font color="red"><span id = "err_project_bhk" style = "display:none;">Please enter project types!</span></font>
								  </td>
							   </tr>

							   <tr>
								  <td width="20%" align="right"><font color ="red">*</font><b>Source of Information :</b> </td>
								  <td width="30%" align="left"><input type="text" name="txtProjectSource" id="txtProjectSource" value="{$txtSourceofInfo}" style="width:360px;" /></td>
								  <td width="50%" align="left">
									  <font color="red"><span id = "err_project_source" style = "display:none;">Please enter project source of information!</span></font>
								  </td>
							   </tr>
								
							    <tr>
								  <td width="20%" align="right"><font color ="red">*</font><b>Project type :</b> </td>
								  <td width="30%" align="left">
									<select name = "project_type" class = "project_type" onchange = "show_hide(this.value);">
										<option value =''>Project Type</option>
										{foreach from=$ProjectTypeArr key=k item=v}
										<option value = "{$k}" {if $k == $project_type} selected {/if} >{ucwords($v|lower)|replace:'_':' '}</option>
										{/foreach}
									</select>
								  </td>
								  <td width="50%" align="left">
									  <font color="red"><span id = "err_project_type" style ="display:none;">Please select project type!</span></font>	
									  {if $project_type != '' && $project_type != 0}<font color="red"><span id = "err_project_typeChk">{$ErrorMsgType['showTypeError']}</span></font>{/if}	  
								  </td>
							   </tr>

							   <tr id = "no_of_villa" {if ($project_type == '2' || $project_type == '3' || $project_type == '5')}  {else} style = "display:none;" {/if}>
								  <td width="20%" align="right"><b>Number of Villa :</b> </td>
								  <td width="30%" align="left">
								  	<input type = "text" name = "no_of_villa" id = "no_of_villa" value = "{$no_of_villa}" onkeypress = "return isNumberKey(event);">
									
								  </td>
								  <td width="50%" align="left">
									  <font color="red"><span id = "err_project_type" style = "display:none;">Please select project type!</span></font>
								  </td>
							   </tr>

                               <tr id = "no_of_plot" {if ($project_type == '4' || $project_type =='5' || $project_type == '6')} {else} style = "display:none;" {/if}>
								  <td width="20%" align="right"><b>Number of Plots :</b> </td>
								  <td width="30%" align="left">
									
									<input type = "text" name = "no_of_plot" id = "no_of_plot" value = "{$no_of_plot}" onkeypress = "return isNumberKey(event);">
								  </td>
								  <td width="50%" align="left">
									  <font color="red"><span id = "err_project_type" style = "display:none;">Please select project type!</span></font>
								  </td>
							   </tr>

							    <tr id = "no_of_towers" {if ($project_type == '1' || $project_type =='3' || $project_type == '6')}  {else} style = "display:none;" {/if}>
								  <td width="20%" align="right" valign="top"><b>No Of Towers :</b> </td>
								  <td width="30%" align="left">

								  	<input type = "text" name="no_of_towers" id="no_of_towers" class="field" value = "{$no_of_towers}" onkeypress = "return isNumberKey(event);">
								  </td>
								  <td width="50%" align="left">
										 <font color="red"><span id = "err_no_of_towers" style = "display:none;">Please select no of towers!</span></font>
								  </td>
							   </tr>
							   <tr id = "no_of_flats" {if ($project_type == '1' || $project_type =='3' || $project_type == '6')}  {else} style = "display:none;" {/if}>
								  <td width="20%" align="right" valign="top"><b>No Of Flats :</b> </td>
								  <td width="30%" align="left">

								  	<input type = "text" name="no_of_flats" id="no_of_flats" class="field" value = "{$no_of_flats}" onkeypress = "return isNumberKey(event);">

								  </td>
								  <td width="50%" align="left"></td>
							   </tr>
							   
							   <tr>
								  <td width="20%" align="right" valign ="top"><b> Launched Units:</b> </td><td width="30%" align="left">

									 <input type = "text" name = "launchedUnits" id = "launchedUnits" value = "{$launchedUnits}" style ="width:360px;">

								  </td>
								  <td width="50%" align="left"><font color="red"></font></td>
							   </tr>
							   
							    <tr>
								  <td width="20%" align="right" valign ="top"><b> Reason For UnLaunched Units:</b> </td><td width="30%" align="left">

									 <textarea name = "reasonUnlaunchedUnits" id = "reasonUnlaunchedUnits" rows="10" cols="45">{$reasonUnlaunchedUnits}</textarea>

								  </td>
								  <td width="50%" align="left"><font color="red"></font></td>
							   </tr>
								
							   <tr>
								  <td width="20%" align="right" valign="top"><b><b><font color ="red">*</font><b>Project Location Desc :</b> </td>
								  <td width="30%" align="left">
									 <textarea name="txtProjectLocation" rows="10" cols="45" id = "txtProjectLocation">{$txtProjectLocation}</textarea>
								  </td>
								  <td width="50%" align="left">
									  <font color="red"><span id = "err_project_loc_desc" style = "display:none;">Please enter project location description!</span></font>
								  </td>
							   </tr>

							   <tr>
								  <td width="20%" align="right"><font color ="red">*</font><b>Project Latitude :</b> </td>
								  <td width="30%" align="left"><input type="text" name="txtProjectLattitude" id="txtProjectLattitude" value="{$txtProjectLattitude}" style="width:360px;" /></td>
								  <td width="50%" align="left">
									  <font color="red"><span id = "err_project_latt" style = "display:none;">Please enter project lattitude!</span></font>
								  </td>
							   </tr>
							   <tr>
								  <td width="20%" align="right"><font color ="red">*</font><b>Project Longitude :</b> </td>
								  <td width="30%" align="left"><input type="text" name="txtProjectLongitude" id="txtProjectLongitude" value="{$txtProjectLongitude}" style="width:360px;" /></td>
								  <td width="50%" align="left">
									  <font color="red"><span id = "err_project_long" style = "display:none;">Please enter project longitude</span></font>
								  </td>
							   </tr>
							   <tr>
								  <td width="20%" align="right"><b>Project Meta Title :</b> </td>
								  <td width="30%" align="left"><input type="text" name="txtProjectMetaTitle" id="txtProjectMetaTitle" value="{$txtProjectMetaTitle}" style="width:360px;" /></td>
								  <td width="50%" align="left">
									 
								  </td>
							   </tr>
							   <tr>
								  <td width="20%" align="right" valign="top"><b>Meta Keywords :</b> </td>
								  <td width="30%" align="left">
									 <textarea name="txtMetaKeywords" rows="10" cols="45" id = "txtMetaKeywords">{$txtMetaKeywords}</textarea>
								  </td>
								  <td width="50%" align="left">
									  
								  </td>
							   </tr>
							   <tr>
								  <td width="20%" align="right" valign="top"><b>Meta Description :</b> </td>
								  <td width="30%" align="left">
									 <textarea name="txtMetaDescription" rows="10" cols="45" id = "txtMetaDescription">{$txtMetaDescription}</textarea>
								  </td>
								  <td width="50%" align="left">
									 
								  </td>
							   </tr>
							   <tr>
								  <td width="20%" align="right"><b>Active :</b> </td>
								  <td width="30%" align="left">
								  {if $specialAccess == 0 AND $projectId != ''}
								 	 {if $Active == 0}Inactive on both Website and IS DB{/if}
								 	 {if $Active == 1}Active on both Website and IS DB{/if}
								 	 {if $Active == 2}Deleted{/if}
								 	 {if $Active == 3}Active on IS but inactive on website{/if}
								 	 <input type = "hidden"  name="Active" value = "{$Active}">
								  {else}
                                                                      {if $Active == ''}{$Active =1}{/if}
								  	<select name="Active" id="Active" class="field">
									  <option value ="" >Select</option>
									  <option {if $Active == 0}  value="0" selected="selected" {else} value="0"{/if}>Inactive on both Website and IS DB</option>
									 <option {if $Active == 1} value="1" selected="selected" {else} value="1" {/if}>Active on both Website and IS DB</option>
									 <option {if $Active == 2}  value="2" selected="selected" {else} value="2"{/if}>Deleted</option>
									 <option {if $Active == 3} value="3" selected="selected" {else} value="3" {/if}>Active on IS but inactive on website</option>
									 </select>
								  {/if}
									 
								  </td>
								  <td width="50%" align="left">
									  <font color="red"><span id = "err_project_active" style = "display:none;"></span></font>
								  </td>
							   </tr>
							   <tr>
								  <td width="20%" align="right"><font color ="red">*</font><b>Project Status :</b> </td>
								  <td width="30%" align="left" valign = "top">
									 <select name="Status" id="Status" class="fieldState">
										<option value="">Select</option>
										{foreach from = $enum_value key = key item = value}
											<option value="{$value}" {if $value == $Status} selected {/if}>{$value} </option>
										{/foreach}


									 </select>
								  </td>
								  <td width="50%" align="left">
									  <font color="red"><span id = "err_project_status" style = "display:none;">Please select project status!</span></font>
								  </td>
							   </tr>

							    <tr>
								  <td width="20%" align="right"><b>Booking Status :</b> </td>
								  <td width="30%" align="left">
									 <select name="Booking_Status" id="Booking_Status" class="fieldState">
										<option value="">Select</option>
										<option value="Available" {if $Booking_Status == 'Available'} selected {/if}>Available</option>
										<option value="On Hold" {if $Booking_Status == 'On Hold'} selected {/if}>On Hold</option>
										<option value="Sold out" {if $Booking_Status == 'Sold out'} selected {/if}>Sold Out</option>
									 </select>
								  </td>
								  <td width="50%" align="left">
									  <font color="red"><span id = "err_project_status" style = "display:none;">Please select project status!</span></font>
								  </td>
							   <tr>
								  <td width="20%" align="right"><font color ="red">*</font><b>Project URL :</b> </td>
								  <td width="30%" align="left">
								  	{if $projectId != '' && $urlEditAccess == 0}
								  		<input type="text" disabled name="txtProjectURL" id="txtProjectURL" value="{$txtProjectURL}" style="width:360px;" readonly />
								  	{else}
								  		<input type="text" disabled name="txtProjectURL" id="txtProjectURL" value="{$txtProjectURL}" style="width:360px;" />
								  		<br><span style = "font-size:10px">Like:noida/sector-50/dlf-group</font>
								  	{/if}
								  	
								  	<input type = "hidden" name = "txtProjectURLOld" value = "{$txtProjectURLOld}">
								  	


								  	</td>
								  <td width="50%" align="left">
									  <font color="red">{if $ErrorMsg["txtProjectURL"] != ''} {$ErrorMsg["txtProjectURL"]} {/if}

									  	{if $ErrorMsg["txtProjectUrlDuplicate"] != ''} {$ErrorMsg["txtProjectUrlDuplicate"]} {/if}

									  	
									   <span id = "err_project_url" style = "display:none;">Please enter project url!</span></font>
								  </td>
							   </tr>
							   <tr>
								  <td width="20%" align="right"><b>Featured :</b> </td>
								  <td width="30%" align="left">
									 <select name="Featured" id="Featured" class="field">
									 <option {if $Featured == 0} value="0" selected="selected" {else} value="0" {/if}>0</option>
									 <option {if $Featured == 1} value="1" selected="selected" {else} value="1" {/if}>1</option>
									 </select>
								  </td>
								  <td width="50%" align="left">
									  <font color="red">{if $ErrorMsg["projectFeatured"] != ''} {$ErrorMsg["projectFeatured"]} {/if}
									   <span id = "err_project_featured" style = "display:none;"></span></font>
								  </td>
							   </tr>

							   <tr>
								  <td width="20%" align="right" valign="top"><b>Price Disclaimer :</b></td>
								  <td width="30%" align="left">
									 <textarea name="txtDisclaimer" rows="10" cols="45" id = "txtDisclaimer">{$txtDisclaimer}</textarea>
								  </td>
								  <td width="50%" align="left">
									  <font color="red">{if $ErrorMsg["projectDisclaimer"] != ''} {$ErrorMsg["projectDisclaimer"]} {/if}</font>
								  </td>
							   </tr>

							  
                               <tr>
                                   <td width="20%" align="right" valign="top"><b>Pre - Launch Date :</b> </td>
                                   <td width="30%" align="left">
                                       <input name="pre_launch_date" value="{$pre_launch_date}" type="text" class="formstyle2" id="pre_f_date_c_to" size="10" />  <img src="images/cal_1.jpg" id="pre_f_trigger_c_to" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                                   </td>
                                   <td width="50%" align="left"><font color="red">{if count($ErrorMsg["preLaunchDate"])>0}{$ErrorMsg["preLaunchDate"]}{/if}</font></td>
                               </tr>
							   <tr>
							   <td width="20%" align="right" valign="top"><b>Launch Date :</b> </td>
							   <td width="30%" align="left">
							   <input name="eff_date_to" value="{$eff_date_to}" type="text" class="formstyle2" id="f_date_c_to" value="" size="10" />  <img src="images/cal_1.jpg" id="f_trigger_c_to" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
							   </td>
							   <td width="50%" align="left"><font color="red">
                                                            {if count($ErrorMsg['launchDate'])>0}
                                                                {$ErrorMsg['launchDate']}
                                                            {else}
                                                                {if count($ErrorMsg["launchDateGreater"])>0}{$ErrorMsg["launchDateGreater"]}{/if}
                                                            {/if}
                                                                </font></td>
							   </tr>
							   <tr>
								  <td width="20%" align="right" valign ="top"><b> Promised Completion Date:</b> </td><td width="30%" align="left">
									<input name="eff_date_to_prom" value="{$eff_date_to_prom}" type="text" class="formstyle2" id="f_date_c_prom" value="" size="10" />  <img src="images/cal_1.jpg" id="f_trigger_c_prom" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
								  </td>
								  <td width="50%" align="left"><font color="red">{if count($ErrorMsg['CompletionDateGreater'])>0} {$ErrorMsg['CompletionDateGreater']}{/if}</font> </td>
							   </tr>
                                                           
                                                           <tr>
                                                                <td width="20%" align="right" valign="top"><b>Expected Supply Date :</b> </td>
                                                                <td width="30%" align="left">
                                                                    <input name="exp_launch_date" value="{$exp_launch_date}" type="text" class="formstyle2" id="exp_f_date_c_to" size="10" />  <img src="images/cal_1.jpg" id="exp_f_trigger_c_to" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                                                                </td>
                                                                <td width="50%" align="left"><font color="red">{$ErrorMsg['supplyDate']}</font></td>
                                                            </tr>
							   
							   

								 <tr>
								  <td width="20%" align="right" valign="top"><b><b>Bank List:</b> </td><td width="30%" align="left">
									 <select name="bank_list[]" id="bank_list" class="field" multiple>
										<option value="">Select Bank</option>
										{if !is_array($bank_arr)}
											{$bank_arr = array()}
										{/if}
										{foreach from = $BankListArr key = key item = value}
										<option value="{$key}" {if in_array($key,$bank_arr)}selected{/if}>{$value}</option>
										{/foreach}
									 </select>
								  </td>
								  <td width="50%" align="left"><font color="red"></font></td>
							   </tr>

							   <tr>
								  <td width="20%" align="right"><b>YouTube Video Key:</b> </td><td width="30%" align="left">
									 <input type = "text" name = "youtube_link" value = "{$youtube_link}">
								  </td>
								  <td width="50%" align="left"><font color="red"></font></td>
							   </tr>

							   <tr>
								  <td width="100%" align="left" valign ="top" colspan ="3">
									<input type = "radio" name = "application" value = "app_form" checked = "checked" onclick = "change_type(this.value);"><b>Application form in html</b>
									 <input type = "radio" name = "application" value = "app_form_pdf" onclick = "change_type(this.value);"><b>Application form in pdf</b>
								  </td>
							   </tr>

							   <tr>
								  <td width="32%" align="right" valign ="top"><b>Application Form (in html):</b> </td><td width="30%" align="left">
									 <span id = "app_form">
										<textarea name = "app_form" id = "app_form" rows="10" cols="45">{$app_form}</textarea>
									</span>
									<span id = "app_form_pdf" style = "display:none;">
										<input type = "file" name = "app_pdf">
									</span>
								  </td>
								  {if $ErrorMsg["app_form"] != ''}
								  <td width="50%" align="left"><font color="red">{$ErrorMsg["app_form"]}</font></td>
								  {else}
								  <td width="50%" align="left"></td>
								  {/if}
							   </tr>

							   <tr>
								  <td width="100%" align="left" valign ="top" colspan ="3">
									<input type = "radio" name = "price_list_chk" value = "price_list" checked = "checked" onclick = "change_type_price(this.value);"><b>Price List in html</b>
									 <input type = "radio" name = "price_list_chk" value = "price_list_pdf" onclick = "change_type_price(this.value);"><b>Price List in pdf</b>
								  </td>
							   </tr>

							   <tr>
								  <td width="20%" align="right" valign ="top"><b>Price List (in html):</b> </td><td width="30%" align="left">
									 <span id = "price_list">
										<textarea name = "price_list" id = "price_list_text" rows="10" cols="45">{$price_list}</textarea>
									</span>
									<span id = "price_list_pdf" style = "display:none;">
										<input type = "file" name = "price_list_pdf">
									</span>
								  </td>
								  {if $ErrorMsg["price_list"] != ''}
								  <td width="50%" align="left"><font color="red">{$ErrorMsg["price_list"]}</font></td>
								  {else}
								  <td width="50%" align="left"></td>
								  {/if}
							   </tr>


							    <tr>
								  <td width="100%" align="left" valign ="top" colspan ="3">
									<input type = "radio" name = "payment_chk" value = "payment" checked = "checked" onclick = "change_type_payment(this.value);"><b>payment Plan in html</b>
									 <input type = "radio" name = "payment_chk" value = "payment_pdf" onclick = "change_type_payment(this.value);"><b>Payment Plan in pdf</b>
								  </td>
							   </tr>

							   <tr>
								  <td width="20%" align="right" valign ="top"><b>Payment Plan (in html):</b> </td><td width="30%" align="left">
									 <span id = "price_list">
										<textarea name = "payment" id = "payment" rows="10" cols="45">{$payment}</textarea>
									</span>
									<span id = "payment_pdf" style = "display:none;">
										<input type = "file" name = "payment_pdf">
									</span>
								  </td>
								  {if $ErrorMsg["payment"] != ''}
								  <td width="50%" align="left"><font color="red">{$ErrorMsg["payment"]}</font></td>
								  {else}
								  <td width="50%" align="left"></td>
								  {/if}
							   </tr>

							    <tr>
								  <td width="20%" align="right"><b>Approvals:</b> </td><td width="30%" align="left">
									 <input type = "text" name = "approvals" value = "{$approvals}" style ="width:360px;">
								  </td>
								  <td width="50%" align="left"><font color="red"></font></td>
							   </tr>

							    <tr>
								  <td width="20%" align="right"><b>Project Size:</b> </td><td width="30%" align="left">
									 <input type = "text" name = "project_size" id = "project_size" value = "{$project_size}" style ="width:360px;"  onkeypress='return isNumberKey(event)'><br>
									 <span style = "font-size:10px">in acres</span>
								  </td>
								  <td width="50%" align="left" nowrap>
								  		<font color="red">
								  		   <span id = "err_project_size" style = "display:none;">
								  		   	Project Size should be less than 500
								  		   </span>
										</font>
								</td>
							   </tr>

							   <tr>
								  <td width="20%" align="right"><b>Open Space:</b> </td><td width="30%" align="left">
									 <input type = "text" name = "open_space" value = "{$open_space}" style ="width:360px;"  onkeypress='return isNumberKey(event)'><br>
									 <span style = "font-size:10px">in Percentage(%)</span>
								  </td>
								  <td width="50%" align="left"></td>
							   </tr>

							    <tr>
								  <td width="20%" align="right"><b>No Of Lifts Per Tower:</b> </td><td width="30%" align="left">

								  	<input type = "text" name = "no_of_lift" value = "{$no_of_lift}" onkeypress = "return isNumberKey(event);">

								  </td>
								  <td width="50%" align="left"><font color="red"></font></td>
							   </tr>

							   <tr>
								  <td width="20%" align="right"><b>Power Backup:</b> </td><td width="30%" align="left">
									 <select name = "powerBackup">
										<option value = "No">No</option>
										<option value = "Only Common Areas" {if $powerBackup == 'Only Common Areas'} selected {/if}>Only Common Areas</option>
										<option value = "Common Area + Apartments" {if $powerBackup == 'Common Area + Apartments'} selected {/if}>Common Area + Apartments</option>
									 </select>
								  </td>
								  <td width="50%" align="left"><font color="red"></font></td>
							   </tr>

							   <tr>
								  <td width="20%" align="right"><b>Power Backup Capacity (KVA) :</b> </td><td width="30%" align="left">
									 <input type = "text" name = "power_backup_capacity" id = "power_backup_capacity" value = "{$power_backup_capacity}" style ="width:360px;"  onkeypress='return isNumberKey(event)'>

								  </td>
								  <td width="50%" align="left" nowrap><font color="red"><span id = "err_power_bkpKba" style = "display:none;">Power Backup should be in Numeric!</span>
								  	<span id = "err_power_bkpKba_10" style = "display:none;">Power Backup should be less then 10!</span></font>
								  </td>
							   </tr>

							   <tr>
								  <td width="20%" align="right"><b>Architect Name:</b> </td><td width="30%" align="left">
									 <input type = "text" name = "architect" id = "architect" value = "{$architect}" style ="width:360px;">
								  </td>
								  <td width="50%" align="left"><font color="red"></font></td>
							   </tr>

								<tr>
								   <td width="20%" align="right" valign="top"><b><b>Heighlight :</b> </td>
								   <td width="30%" align="left">
								   <select name="special_offer">
								   <option {if $special_offer == 'none'} value="none" selected = "selected" {else}  value ='none' {/if} >No Offer</option>
								   <option {if $special_offer == 'nl'} value="nl" selected = "selected"{else}  value ='nl'  {/if}>New Launch</option>
								   <option {if $special_offer == 'so'} value="so" selected = "selected"{else}  value ='so' {/if}>Sold Out</option>
								   </select>
								   </td>
							   <td width="50%" align="left"></td>
							   </tr>
							   
							    <tr>
								  <td width="20%" align="right" valign ="top"><b>Offer Heading:</b> </td><td width="30%" align="left">
									 
									 <input maxlength = "13" type = "text" name = "offer_heading" id = "offer_heading" value ="{$offer_heading}" style ="width:360px;">
								  </td>
								  <td width="50%" align="left"><font color="red"><span id = "offerHeading"></span></font></td>
							   </tr>

							    <tr>
								  <td width="20%" align="right" valign ="top"><b>Offer Description:</b> </td><td width="30%" align="left">
									<input maxlength = "40" type = "text" name = "offer_desc" id = "offer_desc" value ="{$offer_desc}" style ="width:360px;">
								  </td>
								  <td width="50%" align="left"><font color="red"><span id = "offerDesc"></span></font></td>
							   </tr>

							   <tr>
								  <td width="20%" align="right" valign ="top"><b> Residential:</b> </td><td width="30%" align="left">

									<select name="residential" id="residential" class="residential">
										<option value="">Select </option>
										<option value="0" {if $residential == 0} selected = selected {/if}>Residential </option>
										<option value="1" {if $residential == 1} selected = selected {/if}>Non Residential </option>
									</select>

								  </td>
								  <td width="50%" align="left"><font color="red"></font></td>
							   </tr>

							   <tr>
								  <td width="20%" align="right" valign ="top"><b> Township:</b> </td><td width="30%" align="left">

									 <input type = "text" name = "township" id = "township" value = "{$township}" style ="width:360px;">

								  </td>
								  <td width="50%" align="left"><font color="red"></font></td>
							   </tr>
							   
							   <tr>
								  <td width="20%" align="right" valign ="top"><b> Show price on website ?</b> </td><td width="30%" align="left">
									{if $shouldDisplayPrice == ''}{$shouldDisplayPrice =1}{/if}
                                                                      <select name="shouldDisplayPrice">
										<option value="1" {if $shouldDisplayPrice == 1} selected = selected {/if}>Yes</option>
										<option value="0" {if $shouldDisplayPrice == 0} selected = selected {/if}>No</option>
									</select>
								  
								  </td>
								  <td width="50%" align="left"><font color="red"></font></td>
							   </tr>
                                                           
                                                           <tr>
                                                                <td width="20%" align="right" valign ="top"><b> Skip Updation Cycle: </b> </td><td width="30%" align="left">
                                                                    <select name="identifyTownShip">
                                                                              <option value="0" {if $identifyTownShip == 0} selected = selected {/if}>No</option>
                                                                              <option value="1" {if $identifyTownShip == 1} selected = selected {/if}>Yes</option>
                                                                      </select>

                                                                </td>
                                                                <td width="50%" align="left"><font color="red"></font></td>
							   </tr>
                                                           
							   <tr>

								  <td>&nbsp;</td>
								  <td align="left" style="padding-left:152px;">
									 <input type="hidden" name="projectId" value="{$projectId}" />
									 <input type="hidden" name="oldbuilderId" value="{$builderId}" />
									 <input type="hidden" name="preview" value="{$preview}" />
									 <input type = "hidden" name = "project_type_hidden" id = "project_type_hidden" value = "{$projectTypeOld}">
									 {if $projectId == ''}
										<input type="submit" name="btnSave" id="btnSave" value="Next" onclick = "return project_scn1();" />
									 {else}
										<input type="submit" name="btnSave" id="btnSave" value="Save" onclick = "return project_scn1();" />
									 {/if}
									 &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit" />
								  </td>
							   </tr>
							</div>
					 </form>
				</table>

			</TD>
		</TR>

	</TABLE>

<script type="text/javascript">
    var cals_dict = {
        "f_trigger_c_to" : "f_date_c_to",
        "pre_f_trigger_c_to" : "pre_f_date_c_to",
        "exp_f_trigger_c_to" : "exp_f_date_c_to",
        "f_trigger_c_prom" : "f_date_c_prom"
    };

    $.each(cals_dict, function(k, v) {
        Calendar.setup({
            inputField     :    v,                                 // id of the input field
            //    ifFormat       :    "%Y/%m/%d %l:%M %P",         // format of the input field
            ifFormat       :    "%Y-%m-%d",                        // format of the input field
            button         :    k,                                 // trigger for the calendar (button ID)
            align          :    "Tl",                              // alignment (defaults to "Bl")
            singleClick    :    true,
            showsTime	  :	true
        });
    });
</script>