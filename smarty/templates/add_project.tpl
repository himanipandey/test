<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js?version=1"></script>
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
		   $(".suburbId").html('');
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

				var loc_id = $(this).val();
				var cid = $(".cityId").val();				
				var dataString = 'part=refreshLoc&loc_id='+ loc_id +"&id = "+cid;

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
	
  function isNumberKey(evt)
  {
 	 var charCode = (evt.which) ? evt.which : event.keyCode;
 	if(charCode == 99 || charCode == 118)
   	 return true;
	 if (charCode > 31 && (charCode < 46 || charCode > 57) || (charCode == 13))
		return false;

	 return true;
  }

 function delete_pdf(){
		$('#old_pdf').css("display","none");
		$('#app_pdf').css("display","block");
		$('#application').val("pdf-del");
 }
 
    $(document).ready(function(){
	
		   $('#reasonRow').hide();
		   $('#duplicate_pid').hide();
		   $('#other_reason_txt').hide();
		   $('#Active').change(function(){
				if($(this).val() == 'Inactive')
					$('#reasonRow').show();
				else
					$('#reasonRow').hide();
		   });
		   
		   $('input[name=reason]').click(function(){
				if($(this).val() == 'duplicate'){
					 $('#duplicate_pid').show();
					$('#other_reason_txt').hide();
				}else{
					$('#duplicate_pid').hide();
					$('#other_reason_txt').show();
				}
		   });
		
	})
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
								  <td width="30%" align="left">
                                                                      <input type="text" name="txtProjectName" id="txtProjectName" value="{$txtProjectName}" style="width:357px;" />
                                                                      <input type="hidden" name = "projectNameOld" value="{$projectNameOld}">
                                                                  </td>

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
									  <font color="red">{if $ErrorMsg["txtBuilder"] != ''} {$ErrorMsg["txtBuilder"]} {/if}<span id = "err_builder_id" style = "display:none;">Please select builder name!</span></font>
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
									  <font color="red">{if $ErrorMsg["txtCity"] != ''} {$ErrorMsg["txtCity"]} {/if}<span id = "err_city_id" style = "display:none;">Please select city!</span></font>
								  </td>
                                                                   
							   </tr>
                             
							   <tr>
                                                                <td width="20%" align="right"><font color ="red">*</font><b>Locality :</b> </td>
                                                                <td width="30%" align="left">
                                                                       <select name="localityId" class="localityId" style="width:230px;">
                                                                              <option value="">Select Locality</option>
                                                                              {foreach from=$getLocalityBySuburb key=k item=v}
                                                                                      <option {if $localityId == $k} value = "{$k}" selected="selected" {else}  value = "{$k}" {/if}>{$v}</option>
                                                                              {/foreach}
                                                                       </select>
                                                                </td>
                                                               <td width="50%" align="left">
                                                                        <font color="red">{if $ErrorMsg["txtLocality"] != ''} {$ErrorMsg["txtLocality"]} {/if}<span id = "err_locality_id" style = "display:none;">Please select locality!</span></font>
                                                               </td>
							   </tr>	
							   <tr>
								  <td width="20%" align="right"><font color ="red">*</font><b>Suburbs :</b> </td>
								  <td width="30%" align="left">
									                                <select name="suburbId" class="suburbId" style="width:230px;" readonly>
                                                                            <option value="">Select Suburb</option>
                                                                            {foreach from=$suburbSelect key=k item=v}
																				{if $suburbId == $k}
                                                                                    <option  selected  value = "{$k}">{$v}</option>
                                                                                 {/if}
                                                                            {/foreach}

                                                                        </select> 
								  </td>
								  <td width="50%" align="left">						  <font color="red">{if $ErrorMsg["txtSuburbs"] != ''} {$ErrorMsg["txtSuburbs"]} {/if}<span id = "err_suburb_id" style = "display:none;">Please select Suburb!</span></font>
								  </td>
							   </tr>						   
                                                           <tr>
                                                                <td width="20%" align="right" valign="top"><b><b><font color ="red">*</font><b>Project Description :</b> </td>
                                                                <td width="30%" align="left">
                                                                       <textarea name="txtProjectDesc" rows="10" cols="45" id = "txtProjectDesc">{$txtProjectDescription}</textarea>
                                                                </td>
                                                                <td width="50%" align="left">
                                                                        <font color="red">{if $ErrorMsg["txtComments"] != ''} {$ErrorMsg["txtComments"]} {/if}<span id = "err_project_bhk" style = "display:none;">Please enter Project Description!</span></font>
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
									  <font color="red">{if $ErrorMsg["txtAddress"] != ''} {$ErrorMsg["txtAddress"]} {/if}<span id = "err_project_address" style = "display:none;">Please enter project address!</span></font>
								  </td>
							   </tr>							   
							   <tr>
								  <td width="20%" align="right"><b>Project Comments :</b> </td>
								  <td nowrap width="30%" align="left"><input type="text" name="comments" id="comments" value="{$comments}" style="width:360px;" /><br><span style = "font-size:10px">Like:1bhk,2bhk etc.</span></td>

								  <td width="50%" align="left">
									  <font color="red">{if $ErrorMsg["Comment"] != ''} {$ErrorMsg["Comment"]} {/if}<span id = "err_project_bhk" style = "display:none;">Please enter Project Comment!</span></font>
								  </td>
							   </tr>

							   <tr>
								  <td width="20%" align="right"><font color ="red">*</font><b>Source of Information :</b> </td>
								  <td width="30%" align="left"><input type="text" name="txtProjectSource" id="txtProjectSource" value="{$txtSourceofInfo}" style="width:360px;" /></td>
								  <td width="50%" align="left">
									  <font color="red">{if $ErrorMsg["txtSource"] != ''} {$ErrorMsg["txtSource"]} {/if}<span id = "err_project_source" style = "display:none;">Please enter project source of information!</span></font>
								  </td>
							   </tr>
								
							    <tr>
								  <td width="20%" align="right"><font color ="red">*</font><b>Project type :</b> </td>
								  <td width="30%" align="left">
									<select name = "project_type">
										<option value =''>Project Type</option>
										{foreach from=$ProjectTypeArr key=k item=v}
										<option value = "{$k}" {if $k == $project_type} selected {/if} >{ucwords($v|lower)|replace:'_':' '}</option>
										{/foreach}
									</select>
								  </td>
								  <td width="50%" align="left">
									  <font color="red">{if $ErrorMsg["txtProject_type"] != ''} {$ErrorMsg["txtProject_type"]} {/if}<span id = "err_project_type" style ="display:none;">Please select project type!</span></font>	
									  {if $project_type != '' && $project_type != 0}<font color="red"><span id = "err_project_typeChk">{$ErrorMsgType['showTypeError']}</span></font>{/if}	  
								  </td>
							   </tr>

							   <tr>
								  <td width="20%" align="right"><font color ="red">*</font><b>Project Latitude :</b> </td>
								  <td width="30%" align="left"><input type="text" name="txtProjectLattitude" id="txtProjectLattitude" value="{$txtProjectLattitude}" style="width:360px;" /></td>
								  <td width="50%" align="left">
									  <font color="red">{if $ErrorMsg["txtLattitude"] != ''} {$ErrorMsg["txtLattitude"]} {/if}<span id = "err_project_latt" style = "display:none;">Please enter project lattitude!</span></font>
								  </td>
							   </tr>
							   <tr>
								  <td width="20%" align="right"><font color ="red">*</font><b>Project Longitude :</b> </td>
								  <td width="30%" align="left"><input type="text" name="txtProjectLongitude" id="txtProjectLongitude" value="{$txtProjectLongitude}" style="width:360px;" /></td>
								  <td width="50%" align="left">
									  <font color="red">{if $ErrorMsg["txtLongitude"] != ''} {$ErrorMsg["txtLongitude"]} {/if}<span id = "err_project_long" style = "display:none;">Please enter project longitude</span></font>
								  </td>
							   </tr>
							  
							   <tr>
								  <td width="20%" align="right"><b>Active :</b> </td>
								  <td width="30%" align="left">
								  {if $specialAccess == 0 AND $projectId != ''}
								 	 {if $Active == 'Inactive'}Inactive on both Website and IS DB{/if}
								 	 {if $Active == 'Active'}Active on both Website and IS DB{/if}
								 	 {if $Active == 'ActiveInCms'}Active In Cms{/if}
								 	 <input type = "hidden"  name="Active" value = "{$Active}">
								  {else}
                                                                      {if $Active == ''}{$Active ='Active'}{/if}
								  	<select name="Active" id="Active" class="field">
									  <option value ="" >Select</option>
									  <option {if $Active == 'Inactive'} selected {/if} value="Inactive">Inactive on both Website and IS DB</option>
									 <option {if $Active == 'Active'} selected {/if} value="Active">Active on both Website and IS DB</option>
									 <option {if $Active == 'ActiveInCms'} selected{/if}  value="ActiveInCms">Active In Cms</option>
									 </select>
								  {/if}
									 
								  </td>
								  <td width="50%" align="left">
									  <font color="red"><span id = "err_project_active" style = "display:none;"></span></font>
								  </td>
							   </tr>
							 {if $projectId != ''}
							   <tr id="reasonRow">
								  <td width="20%" align="right" valign="top"><b>Reason :</b> </td>
								  <td width="30%" align="left">
									<input type="radio" name="reason" value="duplicate"   />Duplicate
									<br/>
									<input type="radio" name="reason" value="other_reason"  />Other
									<br/>
									<br/>
									<div id="duplicate_pid">
										<font color="red">*</font>Duplicate PID : 
										<input type="text" name="duplicate_pid"  />
									</div>
									<div id="other_reason_txt">
										<font color="red">*</font>Other Reason : 
										<textarea name="other_reason_txt"  cols="45" rows="10" ></textarea>
									</div>
								  </td>
								  <td width="50%" align="left">
									
								  </td>
							   </tr> 
							  {/if}
							   <tr>
								  <td width="20%" align="right"><font color ="red">*</font><b>Project Status :</b> </td>
								  <td width="30%" align="left" valign = "top">
									 <select name="Status" id="Status" class="fieldState">
										<option value="">Select</option>
										{foreach from = $projectStatus key = key item = value}
											<option value="{$key}" {if $key == $Status} selected {/if}>{$value} </option>
										{/foreach}


									 </select>
								  </td>
								  <td width="50%" align="left">
									  <font color="red">{if $ErrorMsg["txtStatus"] != ''} {$ErrorMsg["txtStatus"]} {/if}<span id = "err_project_status" style = "display:none;">Please select project status!</span></font>
								  </td>
							   </tr>
							   <tr>
                                                <td width="20%" align="right"><b>Booking Status :</b> </td>
                                                <td width="30%" align="left">
                                                    <select id="bookingStatus" name="bookingStatus">
                                                        <option value="-1">Select Status</option>
                                                        {foreach $bookingStatuses as $b}
                                                            <option value="{$b->id}" {if $b->id == $bookingStatus}selected="selected" {/if}>{$b->display_name}</option>
                                                        {/foreach}
                                                    </select>
                                                </td>
                                                <td width="50%" align="left"></td>
                                            </tr>
							   <tr>

                                                           
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
                                                           {if $projectId == ''}
							   <tr>
								  <td width="20%" align="right" valign ="top"><b> Promised Completion Date:</b> </td><td width="30%" align="left">
									<input name="eff_date_to_prom" value="{$eff_date_to_prom}" type="text" class="formstyle2" id="f_date_c_prom" value="" size="10" />  <img src="images/cal_1.jpg" id="f_trigger_c_prom" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
								  </td>
								  <td width="50%" align="left"><font color="red">{if count($ErrorMsg['CompletionDateGreater'])>0} {$ErrorMsg['CompletionDateGreater']}{/if}</font> </td>
							   </tr>
                                                           <script type="text/javascript">
                                                                var cals_dict = {
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
                                                                <input type = "hidden" name = "completionDate" value="">
                                                           {else}
                                                               <input type = "hidden" name = "completionDate" value="{$completionDate}">
                                                           {/if}
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
								  <td width="32%" align="right" valign ="top"><b>Application form in pdf:</b> </td><td width="30%" align="left">
									  
									   
                                                           
                                                         <span id = "app_form_pdf" >
															{if $app_form}
																<input type = "hidden" name = "application" id="application" value = "pdf-old">
																<span id="old_pdf">
																{$app_form}
																&nbsp;&nbsp;<img src="/images/delete_icon.gif" style="cursor:pointer" onclick="delete_pdf()" title="Delete PDF"/>
																</span>
																
																<input type = "file" name = "app_pdf" id="app_pdf" style="display:none" />
															{else}
																 
																<input type = "hidden" name = "application" id="application" value = "pdf-new">
																<input type = "file" name = "app_pdf" />
															{/if}
                                                          </span>
                                                            
                                                                       
								  </td>
								  {if $ErrorMsg["app_form"] != ''}
								  <td width="50%" align="left"><font color="red">{$ErrorMsg["app_form"]}</font></td>
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
									{if $ErrorMsg["txtproject_size"] != ''} {$ErrorMsg["txtproject_size"]} {/if}
                                                                       <span id = "err_project_size" style = "display:none;">
                                                                            Project Size should be less than 500
                                                                       </span>
                                                                    </font>
								</td>
							   </tr>
                                                           <tr>
								  <td width="20%" align="right"><b>Open Space:</b> </td><td width="30%" align="left">
                                                                    <input type = "text" name = "open_space" id = "open_space" value = "{$open_space}" style ="width:360px;"  onkeypress='return isNumberKey(event)'><br>
                                                                    <span style = "font-size:10px">in percentage</span>
								  </td>
								  <td width="50%" align="left" nowrap>
                                                                    <font color="red">
									{if $ErrorMsg["txtopen_space"] != ''} {$ErrorMsg["txtopen_space"]} {/if}
                                                                      
                                                                    </font>
								</td>
							   </tr>

							   <tr>
                                                                <td width="20%" align="right"><b>Number Of Towers:</b> </td><td width="30%" align="left">
                                                                    <input type = "text" name = "numberOfTowers" value = "{$numberOfTowers}" style ="width:360px;"  onkeypress='return isNumberKey(event)'><br>
                                                                    
                                                                </td>
                                                                <td width="50%" align="left">
									  <font color="red">{if $ErrorMsg["numerOfTowers"] != ''} {$ErrorMsg["numerOfTowers"]} {/if}</font>
								  </td>
							   </tr>

							   <tr>
                                                                <td width="20%" align="right"><b>Power Backup:</b> </td><td width="30%" align="left">
                                                                  <select name = "powerBackup">
                                                                      <option value="">Select Power Backup</option>
                                                                      {foreach from = $getPowerBackupTypes item = value}
                                                                         <option value = "{$value->id}" {if $powerBackup == $value->id} selected {/if}>
                                                                            {$value->name}
                                                                         </option>
                                                                      {/foreach}
                                                                  </select>
                                                                </td>
                                                                <td width="50%" align="left"><font color="red"></font></td>
							   </tr>

							   <tr>
								  <td width="20%" align="right"><b>Power Backup Capacity (KVA) :</b> </td><td width="30%" align="left">
									 <input type = "text" name = "power_backup_capacity" id = "power_backup_capacity" value = "{$power_backup_capacity}" style ="width:360px;"  onkeypress='return isNumberKey(event)'>

								  </td>
								  <td width="50%" align="left" nowrap><font color="red">
{if $ErrorMsg["txtpower_backup_capacity"] != ''} {$ErrorMsg["txtpower_backup_capacity"]} {/if}
<span id = "err_power_bkpKba" style = "display:none;">Power Backup should be in Numeric!</span>
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
                                                                    <option value =''>No OFFER</option>
                                                                    <option value ='NoEmi' {if $special_offer == 'NoEmi'}selected{/if}>No EMI</option>
                                                                    <option value ='NewLaunch' {if $special_offer == 'NewLaunch'}selected{/if}>NEW LAUNCH</option>
                                                                    <option value ='SoldOut' {if $special_offer == 'SoldOut'}selected{/if}>SOLD OUT</option>
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
                                                                            <option value="residential" {if $residential == 'residential'} selected = selected {/if}>Residential </option>
                                                                            <option value="nonResidential" {if $residential == 'nonresidential'} selected = selected {/if}>Non Residential </option>
                                                                    </select>

                                                                </td>
                                                                <td width="50%" align="left"><font color="red"></font></td>
							   </tr>

							   <tr>
                                                                <td width="20%" align="right" valign ="top"><b>Township:</b> </td><td width="30%" align="left">
                                                                    <select name = "township">
                                                                        <option value="">Select Options</option>
                                                                        {foreach from = $allTownships item = item}
                                                                            <option value="{$item->id}" {if $item->id == $township}selected{/if}>
                                                                                {$item->township_name}
                                                                            </option>
                                                                        {/foreach}
                                                                    </select>
                                                                </td>
                                                                <td width="50%" align="left">&nbsp;</td>
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
                                                                    <select name="skipUpdationCycle">
                                                                        <option value="0" {if $skipUpdationCycle == 0} selected = selected {/if}>No</option>
                                                                        <option value="{$skipUpdationCycle_Id}" {if $skipUpdationCycle == {$skipUpdationCycle_Id}} selected {/if}>Yes</option>
                                                                    </select>
                                                                    <input type="hidden" name = "updationCycleIdOld" value="{$updationCycleIdOld}">
                                                                </td>
								<td width="50%" align="left"><font color="red"></font></td>
							   </tr>
							   <tr>
								  <td width="20%" align="right" valign ="top"><b> Redevelopment Project: </b> </td><td width="30%" align="left">
									<input type="checkbox" name="redevelopmentProject" {if $redevelopmentProject} checked {/if} />
                                  </td>
								  <td width="50%" align="left"><font color="red"></font></td>
							   </tr>
							   
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
        "exp_f_trigger_c_to" : "exp_f_date_c_to"
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
