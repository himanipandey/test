
<script type="text/javascript" src="javascript/jquery.js"></script>

<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

<script type="text/javascript">
    $(document).ready(function() {
        var pid = '{$phaseId}';
        $('select#phaseName').val(pid);
        var projectId = $('#projectId').val();
        eventArray = [
			["event1", "add_project.php"],
			["event2", "add_specification.php", true],
			["event3", "add_specification.php", true],
			["event4", "image_edit.php", true],
			["event5","tower_detail_delete.php", true],
			["event6","phase_edit.php"],
			["event7","add_apartmentConfiguration.php", true],
			["event8","add_supply_inventory.php"],
			["event9","add_tower_construction_status.php"],
			["event10","add_project_construction.php"],
			["event11", "edit_floor_plan.php", true],
			["event12", "update_price.php", true],
			["event13", "project_other_price.php", true]
		]; 
		for(var i=0; i< eventArray.length; i++){
			$('.clickbutton').bind(eventArray[i][0], function(event){
				
				for(var i=0; i<eventArray.length; i++){
					if(eventArray[i].indexOf(event.type)!=(-1)){
						if(eventArray[i][2]){
							var str = "&edit=edit";
						}
						else{
							var str="";
						}
						var url = eventArray[i][1]+ "?projectId="+projectId+str+"&preview=true";    
						$(location).attr('href',url);
					}
				}
        	});
		}
    });

function builder_contact(builderId,buildernm)
{
	//code for builder contact info popup
        var url = "builder_contact_info.php?builderId="+builderId+"&builderName="+buildernm;
        $.fancybox({
            'href' :  url
           });

}

    function updateURLParameter(url, param, paramVal){
        var newAdditionalURL = "";
        var tempArray = url.split("?");
        var baseURL = tempArray[0];
        var additionalURL = tempArray[1];
        var temp = "";
        if (additionalURL) {
            tempArray = additionalURL.split("&");
            for (i=0; i<tempArray.length; i++){
                if(tempArray[i].split('=')[0] != param){
                    newAdditionalURL += temp + tempArray[i];
                    temp = "&";
                }
            }
        }

        var rows_txt = temp + "" + param + "=" + paramVal;
        return baseURL + "?" + newAdditionalURL + rows_txt;
    }

   
function towerSelect(towerId)
	{
		var projectId = document.getElementById("projectId").value;
		window.location="show_project_details.php?towerId="+towerId+"&projectId="+projectId;
	}
	
	function goToByScroll(id){
      // Remove "link" from the ID
    id = id.replace("link", "");
      // Scroll
    $('html,body').animate({
        scrollTop: $("#"+id).offset().top},
        'slow');
}

	function refreshSupply(bedId, arr)
	{
		var projectId = document.getElementById("projectId").value;	
		//if(arr.search(bedId)!=-1)
		//{
			window.location="show_project_details.php?projectId="+projectId+"&bedId="+bedId;	
		//}
		
	}

	function changePhase(pId, phase, dir, projectStatus, promisedComDate,launchDate, preLaunchDate,phaseId,stg)
	{
		var flatChk      = $("#flatChk").val();
		var flatAvailChk = $("#flatAvailChk").val();
		var val = $('input:radio[name=validationChk]:checked').val();
		var flgChk = 0;	
		if(dir != 'backward' && val == 'Y' && ((phase == 'dataCollection' && stg == 'updationCycle') || (phase == 'dcCallCenter' && stg == 'newProject')))
		{
			if(phaseId != '')
			{
				alert("Please select No Phase!");
				return false;
			}							
			else if((projectStatus == 'Occupied' || projectStatus == 'Ready for Possession') && promisedComDate == '0000-00-00 00:00:00')
			{
				alert("Promised Completion Date is Mendetory!");
				return false;
			}
			else if((projectStatus == 'Under Construction' || projectStatus == 'Launch'))
			{
				if(launchDate == '0000-00-00 00:00:00' || promisedComDate == '0000-00-00 00:00:00')
				{
					alert("Launch Date Promised Completion Date are Mendetory!");
					return false;
				}
				if(flatAvailChk == 1)
				{
					alert("Availability at BHK level is mandatory with current month");
					return false;
				}
				if(flatChk == 1)
				{
					alert("Total number of flats, villas and Plots as the case may be is mandatory");
					return false;
				}

				flgChk = 1;
			}
			else if(projectStatus == 'Pre Launch' && preLaunchDate == '0000-00-00 00:00:00')
			{
				alert("Pre Launch Date is Mendetory!");
				return false;
			}
			else
			{
				flgChk = 1;
			}
		}
		else
		{
			flgChk = 1;
		}
		
		if(flgChk == 1)
		{
			if(dir=='forward'){
				if (confirm("Do you want to proceed ?"))
				{
					$('#forwardFlag').val('yes');
					$('#currentPhase').val(phase);
					$('#reviews').val(document.getElementById("comments").value);
					$("#returnURLPID").val("show_project_details.php?projectId=" + pId);
					$('#changePhaseForm').submit();
				}
			}
			else if(dir=='backward')
			{
				if (confirm("Do you want to revert ?"))
				{
					$('#forwardFlag').val('no');
					$('#currentPhase').val(phase);
					$('#returnStage').val(stg);
					$('#reviews').val(document.getElementById("comments").value);
					$("#returnURLPID").val("show_project_details.php?projectId=" + pId);
					$('#changePhaseForm').submit();
				}	
			}
			else if(dir=='updation')
			{
				if (confirm("Do you want to proceed ?"))
				{
					$('#forwardFlag').val('update');
					$('#currentPhase').val(phase);
					$('#reviews').val(document.getElementById("comments").value);
					$("#returnURLPID").val("show_project_details.php?projectId=" + pId);
					$('#changePhaseForm').submit();
				}	
			}
		}
	
	}

function getDateNow(){
	return (new Date().getTime());
}


/*********builder contact info related js start here***********/

/*******function for deletion confirmation***********/
 function chkConfirm(TotRow) 
  {
    var chk = 0;
    var lp_select = TotRow+2;
    var rowChk = 0;
    var str1 = '';
    var phone1 = '';
    var email1 = '';
    var projects1 = '';
    var id = '';
    var deleteval = '';
    var builderId = $("#builderId").val();
    for(var i=1;i<=lp_select;i++)
    {      
        var name = "name_"+i;
        var phone = "phone_"+i;
        var email = "email_"+i;
        var idd = "id_"+i;
        var projects = "projects_"+i;
       
        if($("#"+name).val() != '')
        {
          str1 += "--"+($("#"+name).val());
          phone1+="--"+($("#"+phone).val());
          email1+="--"+($("#"+email).val());
          
          var mySelections = '';
          jQuery("#"+projects+' option').each(function(i) {
            if (this.selected == true) {
              mySelections += ","+this.value;
            }
              });

          projects1 +="--"+mySelections;


          id+="--"+($("#"+idd).val());
            rowChk = 1;
        }
        if($("#"+i).attr('checked'))
        {
           deleteval+="--1";
          chk = 1;
        }
        else
          deleteval+="--0";
    }
    if(rowChk == 0)
    {
      alert("All Contact name are blank!");
      return false;
    }
     var pid = $("#projectId").val();
    if(chk == 1){
      if(confirm("Are you sure! you want to delete contacts which are checked."))
      {
        $.ajax(
            {
              type:"post",
              url:"submit_builder_contact.php",
              data:"name="+str1+"&phone="+phone1+"&email="+email1+"&builderId="+builderId+"&deleteval="+deleteval+"&id="+id+"&projects="+projects1,
              success:function(dt){
                        window.location.href = "show_project_details.php?projectId="+pid;
                    // jQuery("#update_insert_delete").show();

              }

            }
          )
      }
    }
    else{
        $.ajax(
            {
              type:"post",
              url:"submit_builder_contact.php",
              data:"name="+str1+"&phone="+phone1+"&email="+email1+"&builderId="+builderId+"&deleteval="+deleteval+"&id="+id+"&projects="+projects1,
              success:function(dt){
                  window.location.href = "show_project_details.php?projectId="+pid;
              }

            }
          )
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

  function clickToCall(obj) {
      var id = $(obj).attr('id').split('_')[1];
      var phId = 'phone_' + id;
      var phNo = $('#'+phId).val(); 
      var campaign = $('#'+'campaignName_'+id).val();
      $.ajax(
	  {
	      type:"get",
	      url:"call_contact.php",
	      data:"contactNo="+phNo+"&campaign="+campaign,
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
  };

  function setStatus(obj) {
      var status = $(obj).attr('id').split('_')[0];
      var id = $(obj).attr('id').split('_')[1];
      var projectList = $('#projects_call_'+id).val();
      var projectRemark = $('#remark_call_'+id).val();
      var callId = $('#callId_'+id).val();
      if (status === "success")
	  projectList = projectList.join(",");
      else 
	  projectList = "";
      
      if (callId) {
	  $.ajax({
	      type:"get",
	      url:"save_call_projects.php",
	      data:"projectList="+projectList+"&callId="+callId+"&status="+status+"&remark="+projectRemark,
	      success : function (dt) {
		  alert("Saved Status as " + status + " with project Ids " + projectList);
	      }
	  });
      }
      else 
	  alert("Please call before setting disposition");
  }

  function showhideBuilder(plsmns)
  {
  	if(plsmns == 'plus')
  	{
	  	document.getElementById("plusMinusImg").innerHTML = "<a href = 'javascript:void(0);' onclick = showhideBuilder('minus');><img src = '../images/minus.jpg' width ='20px'></a>";
	  	document.getElementById("builder_showHide").style.display = '';
  	}
  	else
  	{
  		document.getElementById("plusMinusImg").innerHTML = "<a href = 'javascript:void(0);' onclick = showhideBuilder('plus');><img src = '../images/plus.jpg' width ='20px'></a>";
	  	document.getElementById("builder_showHide").style.display = 'none';
  	}
  }
/*********builder contact info related js end here*************/

/**********Old value dispaly function****/
 function oldValueShow(stageName, phasename, projectId)
 {
	window.location.href = "show_project_details.php?stageName="+stageName+"&phasename="+phasename+"&projectId="+projectId;
 }
</script>


<form  action="show_project_details.php?projectId={$projectId}" method="POST" id="changePhaseForm">
  <input type="hidden" id="forwardFlag" name="forwardFlag" value=""/>
  <input type="hidden" id="currentPhase" name="currentPhase" value=""/>
  <input type="hidden" id="reviews" name="reviews" value=""/>
  <input type="hidden" id="revertFlag" name="revertFlag" value=""/>
  <input type="hidden" id="returnURLPID" name="returnURLPID" value=""/>
  <input type="hidden" id="returnStage" name="returnStage" value=""/>
</form>


{$error}

<div>
	<div class="state"> 
		<span>	Current Phase : </span>
		<span> {ucfirst($projectDetails[0].PROJECT_STAGE)}</span>
		
	</div>
	
	<div>
	<div class="state"> 
		<span>	Current Stage : </span>
		{if $projectDetails[0].PROJECT_PHASE=="dataCollection"}
		<span> Data Collection</span>
		{/if}
		
		{if $projectDetails[0].PROJECT_PHASE=="dcCallCenter"}
		<span> Data Collection Call Center</span>
		{/if}
		
		{if $projectDetails[0].PROJECT_PHASE=="newProject"}
		<span> New Project Audit</span>
		{/if}
		{if $projectDetails[0].PROJECT_PHASE=="audit1"}
		<span> Audit 1</span>
		{/if}
		{if $projectDetails[0].PROJECT_PHASE=="audit2"}
		<span> Audit 2</span>
		{/if}
		{if $projectDetails[0].PROJECT_PHASE=="complete"}
		<span> Audit Completed</span>
		{/if}
	</div>
{if $projectLabel != ''}
	Label: {$projectLabel}
	<br>
{/if}
{if $projectDetails[0].PROJECT_STAGE != 'noStage'}

	{$projectStatus = $projectDetails[0]['PROJECT_STATUS']}
	{$promisedCompletionDate = $projectDetails[0]['PROMISED_COMPLETION_DATE']}
	{$launchDate = $projectDetails[0]['LAUNCH_DATE']}
	{$prelaunchDate = $projectDetails[0]['PRE_LAUNCH_DATE']}
	{$stageProject = $projectDetails[0].PROJECT_STAGE}
	
	<span>
		Move Validation?<input type = "radio" name = "validationChk" value = "Y" checked>Yes&nbsp;
											<input type = "radio" name = "validationChk" value = "N">No<br>
	</span>
	{if $projectDetails[0].PROJECT_STAGE=='newProject'}
		{if in_array($projectDetails[0].PROJECT_PHASE,$arrProjEditPermission)}
			<button id="phaseChange" onclick="changePhase({$projectId},'{$projectDetails[0].PROJECT_PHASE}','forward','{$projectStatus}','{$promisedCompletionDate}','{$launchDate}','{$prelaunchDate}','{$phaseId}','{$stageProject}');">Move To Next Stage	</button>
		{/if}
	{else}
		{if in_array($projectDetails[0].PROJECT_PHASE,$arrProjEditPermission)}
			<button id="phaseChange" onclick="changePhase({$projectId},'{$projectDetails[0].PROJECT_PHASE}','updation','{$projectStatus}','{$promisedCompletionDate}','{$launchDate}','{$prelaunchDate}','{$phaseId}','{$stageProject}');">Move To Next Stage	</button>
		{/if}
	{/if}

	{if $projectDetails[0].PROJECT_PHASE!="dataCollection" && $projectDetails[0].PROJECT_PHASE!="complete" && in_array($projectDetails[0].PROJECT_PHASE,$arrProjEditPermission)}
	<button id="phaseChange" onclick="changePhase({$projectId},'{$projectDetails[0].PROJECT_PHASE}','backward','{$projectStatus}','{$promisedCompletionDate}','{$launchDate}','{$prelaunchDate}','{$phaseId}','{$stageProject}');">Revert	</button>
	{/if}
{/if}
	<br>
	{if $projectDetails[0].PROJECT_PHASE!="complete"}
		<textarea name="comments" id="comments" placeholder="
			{if $projectDetails[0].AUDIT_COMMENTS}
			{else}
				Please put your comments here
			{/if}
		" cols="40" rows="5"
		>
			{if $projectDetails[0].AUDIT_COMMENTS}
				{$projectDetails[0].AUDIT_COMMENTS}
			{/if}
		</textarea>
	{/if}
										
<div> 

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
						  <TD class="h1" width="67%"><img height="18" hspace="5" src="images/arrow.gif" width="18">   {$projectDetails[0].PROJECT_NAME} </TD>
						  <TD width="33%" align ="right"></TD>
						</TR>
						
						
					</TBODY>
				  </TABLE>
				</TD>
	      </TR>
	      
	      <TD vAlign="top" align="middle" class="backgorund-rt" height="450"><BR>
			 
				<table cellSpacing="1" cellPadding="4" width="67%" align="center" border="0">
	      <div>
	      <tr>
	      <td style = "padding-left:30px;">
	      		<b>Project {if in_array($projectDetails[0].PROJECT_PHASE,$arrProjEditPermission)}<button class="clickbutton" onclick="$(this).trigger('event1');">Edit</button>{/if}
				
				&nbsp;&nbsp;&nbsp;&nbsp;
				
				{if 
				(trim($projectDetails[0].PROJECT_STAGE) == 'newProject' AND trim($projectDetails[0].PROJECT_PHASE) == 'newProject')
				OR
				(trim($projectDetails[0].PROJECT_STAGE) == 'newProject' AND trim($projectDetails[0].PROJECT_PHASE) == 'audit1')
				OR
				(trim($projectDetails[0].PROJECT_STAGE) == 'updationCycle' AND trim($projectDetails[0].PROJECT_PHASE) == 'audit1')}
					<input type = "button" name="oldValueDisplay" value = "Project Old Value Display" onclick = "oldValueShow('{$projectDetails[0].PROJECT_STAGE}','{$projectDetails[0].PROJECT_PHASE}',{$projectDetails[0].PROJECT_ID});">
				{/if}
			<!-- Project Phases -->
            &nbsp;&nbsp;&nbsp;&nbsp;
            {if in_array($projectDetails[0].PROJECT_PHASE,$arrProjEditPermission)}
            &nbsp;&nbsp;&nbsp;&nbsp;<b align="left">Project Phases:<b><button class="clickbutton" onclick="$(this).trigger('event6');">Edit</button>
            {/if}
           
            <!-- End of Project Phases -->	   
			</td></tr>				   
			<tr>
				<td width = "100%" align = "center" colspan = "16" style="padding-left: 30px;">
					<table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">
						
						<tr>
							<td align="left" colspan='4'>
								<div id = "projectOldVal">
									{if count($changedValueArr)>0}
										<table style = "border:1px solid #c2c2c2;" align = "left" width ="60%">
											{foreach from = $changedValueArr key=key item = item}
												<tr><td>&nbsp;</td></tr>
												<tr>
													<td align ="left" nowrap><b>Old Value for Project Detail</b></td>
												</tr>
												
												<tr class="headingrowcolor" height="30px;">
													 <td  nowrap="nowrap" width="10%" align="left" class=whiteTxt>Field Name</td>
													 <td nowrap="nowrap" width="25%" align="left" class=whiteTxt>New Value</td>
													 <td nowrap="nowrap" width="25%" align="left" class=whiteTxt>Old Value</td>	
												</tr>
												{$cnt =0}
												{foreach from = $item key=keyInner item = itemInner}
													{if ($cnt+1)%2 == 0}
														{$color = "bgcolor='#F7F8E0'"}
													{else}
														{$color = "bgcolor='#f2f2f2'"}
													{/if}
												<tr {$color} height="25px;">
													<td width="10%" align="left">{ucwords(strtolower(str_replace("_"," ",$keyInner)))}</td>
													<td width="10%" align="left">
															{if $itemInner['new'] != ''}
																{$itemInner['new']} 
															{else}
																--
															{/if}
													</td>
													<td width="10%" align="left">
															{if $itemInner['old'] != ''}
																{$itemInner['old']} 
															{else}
																--
															{/if}
													</td>
												</tr>
												{$cnt = $cnt+1}
												{/foreach}
											{/foreach}
										</table>
									{/if}
								</div>
							</td>
						</tr>
						<tr bgcolor = "#c2c2c2">
							  <td colspan = "2" valign ="top"  nowrap="nowrap" width="1%" align="left"><b>Last Updated Detail</b></br>
						  	  </br>
								{foreach from = $lastUpdatedDetail['resi_project'] key=key item = item}
									
									<b>Department: </b> {$key}</br>
									<b>Name: </b> {$item['FNAME']}</br>
									<b>last Updated Date: </b> {$item['ACTION_DATE']}</br></br>
								{/foreach}
								
							  </td>
						  
						</tr>
						<tr height="25px;">
							  <td  nowrap="nowrap" width="1%" align="left"><b>Project Name:</b></td>

							  <td>
							  		{$projectDetails[0].PROJECT_NAME}
							</td>
						</tr>

						<tr height="25px;">
							  <td  nowrap="nowrap" width="1%" align="left"><b>Builder Name:</b></td>

							  <td>
							  		{$projectDetails[0].BUILDER_NAME}
							</td>
						</tr>

						<tr height="25px;">
							  <td width="1%" align="left" colspan ="2" valign ="top"><b>Builder Contact Information</b>&nbsp;&nbsp;
							  	<span id = "plusMinusImg">
							  	<a href = "javascript:void(0);" onclick = "showhideBuilder('plus');">
							  		
							  			<img src = "images/plus.jpg" width ="20px">
							  		
							  	</a>
							  	</span>
							  </td>
						</tr>

						{*Builder contact info show hide*}
						<tr id = "builder_showHide" style = "display:none;">
							<td align = "center" colspan= "2">

								<table cellSpacing=0 cellPadding=0 width="100%" style = "border:1px solid #BDBDBD;" align= "center">
								   <tr><td>&nbsp;</td></tr>      
								  <TR>
								    <TD style = "padding-left:20px;" align = "left" nowrap colspan = "6"><b>Builder URL:</b> {$builderDetail['URL']}&nbsp;&nbsp;&nbsp;&nbsp;<b>Builder Website:</b> {$builderDetail['WEBSITE']}</TD>
								    
								  </TR>
								     <tr><td>&nbsp;</td></tr>    

								  <TR style = "display:none;" id = "update_insert_delete">
								    <TD style = "padding-left:20px;" align = "left" nowrap colspan = "6"><b><font COLOR="#008000">Data has been Inserted/Updated/Deleted Successfully!</font></b></TD>
								  </TR>

								  <TR style = "display:none;" id = "update_insert">
								    <TD style = "padding-left:20px;" align = "left" nowrap colspan = "6"><b><font COLOR="#008000">Data has been Inserted/Updated/Deleted Successfully!</font></b></TD>
								  </TR>

								  <TR style = "display:none;" id = "error1">
								    <TD style = "padding-left:20px;" align = "left" nowrap colspan = "6"><b><font COLOR="red">Problem in data Insertion/Updation!</font></b></TD>
								  </TR>
								      

								  <tr class="headingrowcolor" height="30px;">
								   
								    <td style = "padding-left:20px;" nowrap="nowrap" width="1%" align="center"class=whiteTxt>SNo.</td>
								    <td style = "padding-left:20px;" nowrap="nowrap" width="2%" align="left" class=whiteTxt>Contact Name</td>
								    <td style = "padding-left:20px;" nowrap="nowrap" width="3%" align="left" class=whiteTxt>Phone</td>
								    <td style = "padding-left:20px;" nowrap="nowrap" width="3%" align="left" class=whiteTxt>Click To Call</td>
								    <td style = "padding-left:20px;" nowrap="nowrap" width="3%" align="left" class=whiteTxt> Campaign Name </td>
								    <td style = "padding-left:20px;" nowrap="nowrap" width="3%" align="left" class=whiteTxt> Select Projects for Call </td>
								    <td style = "padding-left:20px;" nowrap="nowrap" width="3%" align="left" class=whiteTxt>Remark </td>
								    <td style = "padding-left:20px;" nowrap="nowrap" width="3%" align="left" class=whiteTxt> Success / Fail </td>
								    <td style = "padding-left:20px;" nowrap="nowrap" width="3%" align="left" class=whiteTxt>Email</td>
								    
								    <td style = "padding-left:20px;" nowrap="nowrap" width="3%" align="left" class=whiteTxt>Projects</td>
								     <td  style = "padding-right:20px;"nowrap="nowrap" width="1%" align="center" class=whiteTxt >Delete </td>  
								  </tr>
								    
								     <input type = "hidden" name = "builderid" id = "builderId" value = "{$builderId}">
								     {$cnt = 1}
								    {section name=rowLoop start=1 loop=(count($arrContact)+1) step=1}

								        {if ($smarty.section.rowLoop.index)%2 == 0}
								            {$color = "bgcolor = '#F7F7F7'"}
								        {else}
								            {$color = "bgcolor = '#FCFCFC'"}
								        {/if}

								        {$cnt = ($smarty.section.rowLoop.index)-1}

								        {$name         = $arrContact[$cnt]['NAME']}
								        {$phone        = $arrContact[$cnt]['PHONE']}
								        {$email        = $arrContact[$cnt]['EMAIL']}
								        {$projects     = $arrContact[$cnt]['PROJECTS']}    
								        {$id           = $arrContact[$cnt]['ID']}                      

								    <tr><td>&nbsp;</td></tr>         

								    <tr id="row_1" {$color}>

								       <td align="center" valign= "top">
								                 {$smarty.section.rowLoop.index}
								        </td>

								         <td align="center" valign = "top">
								                 
								              <input type = "text" name = "name[]" id = "name_{$smarty.section.rowLoop.index}" value = "{$name}" style = "width:150px">

								              <input type = "hidden" name = "name_old[]" value = "{$name}" style = "width:150px">

								              <input type = "hidden" name = "id[]" id = "id_{$smarty.section.rowLoop.index}" value = "{$id}" style = "width:150px">
								        </td>

								         <td align="center" valign = "top">
									   <input type = "text" name = "phone[]" id = "phone_{$smarty.section.rowLoop.index}" class="phone_box" value = "{$phone}" style = "width:120px"  onkeypress = "return isNumberKey(event);" maxlength = "13">

									     <input type = "hidden" name = "phone_old[]" value = "{$phone}" style = "width:150px">
								        </td>
									
									<td align="center" valign = "top">
									  <a href="javascript:void(0);" id = "c2c_{$smarty.section.rowLoop.index}" class="c2c" style = "width:120px"  onclick = "clickToCall(this);"> Click To Call </a>

								        </td>
									<td align="center" valign = "top">
									  <select name="campaignName{$start}[]" id="campaignName_{$smarty.section.rowLoop.index}">
									    {foreach from = $arrCampaign item=item}
									    <option value={$item}> {$item} </option>
									    {/foreach}

								        </td>
								        <td align="center" valign = "top">

							                  <select name = "projects_call_{$start}[]" id = "projects_call_{$smarty.section.rowLoop.index}" multiple>
							                        <option value = "">Select Project</option>
							                        {foreach from = $ProjectList key = key item = item}
							                          <option value = "{$item['PROJECT_ID']}" {if strstr($arrContact[$cnt]['PROJECTS'],$item['PROJECT_ID'])} selected {/if}>{$item['PROJECT_NAME']}</option>
							                        {/foreach}
							                        </option>
							                   </select>
								        </td>
								         <td align="center" valign = "top">
						
							                  <textarea name = "remark_call_{$start}[]" id = "remark_call_{$smarty.section.rowLoop.index}"></textarea>
								        </td>
								        <td align="center" valign = "top">
								             <input type="hidden" name="callId[]" id="callId_{$smarty.section.rowLoop.index}" value="">
								             <a href="javascript:void(0);" id = "success_{$smarty.section.rowLoop.index}" onclick="setStatus(this);"> Success </a> ||
								             <a href="javascript:void(0);" id = "fail_{$smarty.section.rowLoop.index}" onclick="setStatus(this);"> Fail </a>
								        </td>

								         <td align="center" valign = "top">
								                 <input type = "text" name = "email[]" id = "email_{$smarty.section.rowLoop.index}" value = "{$email}" style = "width:160px">
								                 <input type = "hidden" name = "emails_old[]" value = "{$email}" style = "width:150px">
								        </td>
								        <td align="center" valign = "top">
									  <input type = "hidden" name = "projects_old[]" value = "{$projects}" style = "width:150px">

									    <select name = "projects_{$start}[]" id = "projects_{$smarty.section.rowLoop.index}" multiple>
									      <option value = "">Select Project</option>
									      {foreach from = $ProjectList key = key item = item}
									      <option value = "{$item['PROJECT_ID']}" {if strstr($arrContact[$cnt]['PROJECTS'],$item['PROJECT_ID'])} selected {/if}>{$item['PROJECT_NAME']}</option>
									      {/foreach}
									    </option>
									  </select>
								        </td>
									<td align="center" valign = "top"><input type="checkbox" name="dlt_{$smarty.section.rowLoop.index}" id = "{$smarty.section.rowLoop.index}"></td>
								          
								        
								     </tr>
								     {$cnt = $cnt+2}
								    {/section}

								     <tr id="row_2">

								       <td align="center" valign= "top">
								                 {$cnt}
								        </td>

								         <td align="center" valign = "top">
								                 
								              <input type = "text" name = "name[]" id = "name_{$cnt}" value = "" style = "width:150px">
								              <input type = "hidden" name = "id[]" id = "id_{$cnt}" value = "blank1" style = "width:150px">
								        </td>

								         <td align="center" valign = "top">
								                <input type = "text" name = "phone[]" id = "phone_{$cnt}" value = "" style = "width:120px"  onkeypress = "return isNumberKey(event);" maxlength = "13">
								        </td>
									<td align="center" valign = "top">
									  <a href="javascript:void(0);" id = "c2c_{$smarty.section.rowLoop.index}" class="c2c" style = "width:120px"  onclick = "clickToCall(this);"> Click To Call </a>

								        </td>
									<td align="center" valign = "top">
									  <select name="campaignName{$start}[]" id="campaignName_{$smarty.section.rowLoop.index}">
									    {foreach from = $arrCampaign item=item}
									    <option value={$item}> {$item} </option>
									    {/foreach}

								        </td>
								        <td align="center" valign = "top">

								                  <select name = "projects_call_{$start}[]" id = "projects_call_{$cnt}" multiple>
								                        <option value = "">Select Project</option>
								                        {foreach from = $ProjectList key = key item = item}
								                          <option value = "{$item['PROJECT_ID']}">{$item['PROJECT_NAME']}</option>
								                        {/foreach}
								                        </option>
								                      </select>
								        </td>
								        <td align="center" valign = "top">
							                  
							                  <textarea name = "remark_call_{$start}[]" id = "remark_call_{$cnt}"></textarea>
								        </td>
								        <td align="center" valign = "top">
								             <input type="hidden" name="callId[]" id="callId_{$cnt}" value="">
								             <a href="javascript:void(0);" id = "success_{$cnt}" onclick="setStatus(this);"> Success </a> ||
								             <a href="javascript:void(0);" id = "fail_{$cnt}" onclick="setStatus(this);"> Fail </a>
								        </td>

								         <td align="center" valign = "top">
								                 <input type = "text" name = "email[]" id = "email_{$cnt}" value = "" style = "width:160px">
								        </td>
								        <td align="center" valign = "top">
								                  <select name = "projects_{$start}[]" id = "projects_{$cnt}" multiple>
								                        <option value = "">Select Project</option>
								                        {foreach from = $ProjectList key = key item = item}
								                          <option value = "{$item['PROJECT_ID']}">{$item['PROJECT_NAME']}</option>
								                        {/foreach}
								                        </option>
								                      </select>
								        </td>
								         <td align="center" valign = "top"><input type="checkbox" name="dlt_{$cnt}" id = "{$cnt}"></td>
								          
								        
								     </tr>

								     <tr id="row_3">

								       <td align="center" valign= "top">
								                 {$cnt+1}
								        </td>

								         <td align="center" valign = "top">
								                 
								              <input type = "text" name = "name[]" id = "name_{$cnt+1}" value = "" style = "width:150px">
								              <input type = "hidden" name = "id[]" id = "id_{$cnt+1}" value = "blank2" style = "width:150px">
								        </td>

								         <td align="center" valign = "top">
								                <input type = "text" name = "phone[]" id = "phone_{$cnt+1}" value = "" style = "width:120px"  onkeypress = "return isNumberKey(event);" maxlength = "13">
								        </td>
									<td align="center" valign = "top">
									  <a href="javascript:void(0);" id = "c2c_{$cnt+1}" class="c2c" style = "width:120px"  onclick = "clickToCall(this);"> Click To Call </a>

								        </td>
									<td align="center" valign = "top">
									  <select name="campaignName{$start}[]" id="campaignName_{$cnt+1}">
									    {foreach from = $arrCampaign item=item}
									    <option value={$item}> {$item} </option>
									    {/foreach}

								        </td>
								        <td align="center" valign = "top">

								                  <select name = "projects_call_{$start}[]" id = "projects_call_{$cnt+1}" multiple>
								                        <option value = "">Select Project</option>
								                        {foreach from = $ProjectList key = key item = item}
								                          <option value = "{$item['PROJECT_ID']}">{$item['PROJECT_NAME']}</option>
								                        {/foreach}
								                        </option>
								                      </select>
								        </td>
								        <td align="center" valign = "top">
							              
							                  <textarea name = "remark_call_{$start}[]" id = "remark_call_{$cnt+1}"></textarea>
							                 
								        </td>
								        <td align="center" valign = "top">
								             <input type="hidden" name="callId[]" id="callId_{$cnt+1}" value="">
								             <a href="javascript:void(0);" id = "success_{$cnt+1}" onclick="setStatus(this);"> Success </a> ||
								             <a href="javascript:void(0);" id = "fail_{$cnt+1}" onclick="setStatus(this);"> Fail </a>
								        </td>

								             
								         <td align="center" valign = "top">
								                 <input type = "text" name = "email[]" id = "email_{$cnt+1}" value = "" style = "width:160px">
								        </td>
								        <td align="center" valign = "top">

								                  <select name = "projects_{$start}[]" id = "projects_{$cnt+1}" multiple>
								                        <option value = "">Select Project</option>
								                        {foreach from = $ProjectList key = key item = item}
								                          <option value = "{$item['PROJECT_ID']}">{$item['PROJECT_NAME']}</option>
								                        {/foreach}
								                        </option>
								                      </select>
								        </td>
								         <td align="center" valign = "top"><input type="checkbox" name="dlt_{$cnt+1}" id = "{$cnt+1}"></td> 
								        
								     </tr>

								   <tr><td>&nbsp;</td></tr>         
								  <tr class = "headingrowcolor">
								      <td align="right" nowrap  colspan= "15">
								       <input type="hidden" name="projectId" value="{$projectId}" id ="projectId"/>
								      
								       <input type="button" name="btnSave" id="btnSave" value="Save" onclick = "return chkConfirm({count($arrContact)});" />
								      
								     </td>
								  </tr>
								</table>

							</td>
						</tr>
						{*end Builder contact info show hide*}

						<tr height="25px;">
							  <td  nowrap="nowrap" width="1%" align="left"><b>Project Size:</b></td>

							  <td>
							  		{$projectDetails[0].PROJECT_SIZE}
							</td>
						</tr>

						<tr height="25px;">
							  <td  nowrap="nowrap" width="1%" align="left"><b>City:</b></td>

							  <td>
							  	{foreach from=$CityDataArr key=k item=v}
								 {if $projectDetails[0].CITY_ID == $k}
								 	{$v}
								 {/if}
								{/foreach}
							</td>
						</tr>

						<tr height="25px;">
							  <td  nowrap="nowrap" width="1%" align="left"><b>Suburb:</b></td>

							  <td>
							  {foreach from=$suburbSelect key=k item=v}
								 {if $projectDetails[0].SUBURB_ID == $k}
								 	{$v}
								 {/if}
								{/foreach}
							</td>
						</tr>

						<tr height="25px;">
							  <td  nowrap="nowrap" width="1%" align="left"><b>Locality:</b></td>

							  <td>
							  	{foreach from=$localitySelect key=k item=v}
								 {if $projectDetails[0].LOCALITY_ID == $k}
								 	{$v}
								 {/if}
								{/foreach}
							</td>
						</tr>

						<tr height="25px;">
							  <td  nowrap="nowrap" width="1%" align="left"><b>Project Description:</b></td>

							  <td>
							  	{$projectDetails[0].PROJECT_DESCRIPTION}
							</td>
						</tr>

						<tr height="25px;">
							  <td  nowrap="nowrap" width="1%" align="left"><b>Project Entry Remark:</b></td>

							  <td>
							  	{if $projectDetails[0].PROJECT_REMARK == ''}
							  		--
							  	{else}
							  		{$projectDetails[0].PROJECT_REMARK}
							  	{/if}
							</td>
						</tr>
						
						<tr height="25px;">
							  <td  nowrap="nowrap" width="1%" align="left"><b>Calling Team Remark:</b></td>

							  <td>
							  	{if $projectDetails[0].CALLING_REMARK == ''}
							  		--
							  	{else}
							  		{$projectDetails[0].CALLING_REMARK}
							  	{/if}
							</td>
						</tr>
						
						<tr height="25px;">
							  <td  nowrap="nowrap" width="1%" align="left"><b>Audit Team Remark:</b></td>

							  <td>
							  	{if $projectDetails[0].AUDIT_REMARK == ''}
							  		--
							  	{else}
							  		{$projectDetails[0].AUDIT_REMARK}
							  	{/if}
							</td>
						</tr>

						<tr height="25px;">
							  <td  nowrap="nowrap" width="1%" align="left"><b>Project Address:</b></td>

							  <td>
							  	{$projectDetails[0].PROJECT_ADDRESS}
							</td>
						</tr>

						<tr height="25px;">
							  <td  nowrap="nowrap" width="1%" align="left"><b>Option Description:</b></td>

							  <td>
							  	{$projectDetails[0].OPTIONS_DESC}
							</td>
						</tr>

						<tr height="25px;">
							  <td  nowrap="nowrap" width="1%" align="left"><b>Source of Information:</b></td>

							  <td>
							  	{$projectDetails[0].SOURCE_OF_INFORMATION}
							</td>
						</tr>

						<tr height="25px;">
							  <td  nowrap="nowrap" width="1%" align="left"><b>Project type:</b></td>

							  <td>
							  	{foreach from=$ProjectTypeArr key=k item=v}
								  	{if $k == $projectDetails[0].PROJECT_TYPE_ID}  
								  		{ucwords($v|lower)|replace:'_':' '}
								  	{/if} 
								{/foreach}
							</td>
						</tr>

						{if $projectDetails[0].PROJECT_TYPE_ID != '1' && $projectDetails[0].PROJECT_TYPE_ID != '4'}
						<tr height="25px;">
							 <td  nowrap="nowrap" width="1%" align="left"><b>
							 	Number of 
									{foreach from=$ProjectTypeArr key=k item=v}
									  	{if $k == $projectDetails[0].PROJECT_TYPE_ID}  
									  		{if strstr($v,'PLOT APARTMENTS')}
									  			Apartment
									  		{else}
										  		{if strstr($v,'PLOT VILLA')}
										  			Villa
										  		{else}
											  		{if strstr($v,'VILLA APARTMENTS')}
											  			Villa
											  		{else}
											  			{ucwords($v|lower)|replace:'_':' '}
											  		{/if}
											  	{/if}
											 {/if}
									  	{/if} 
									{/foreach}
							 </b>
							 </td>
							<td>
								{if $phaseId != ''}
									{if count($VillasQuantity)>0}
										{array_sum($VillasQuantity)}	
									{else}
										--
									{/if}
								{else}
									{$projectDetails[0].NO_OF_VILLA}
								{/if}
							</td>
						</tr>
						{/if}

						{if $projectDetails[0].PROJECT_TYPE_ID == '4' || $projectDetails[0].PROJECT_TYPE_ID == '6' || $projectDetails[0].PROJECT_TYPE_ID == '5'}
						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>Number of Plots:</b>
							</td>
							<td>
								{if $phaseId != ''}
									{if count($PlotQuantity)>0}
										{array_sum($PlotQuantity)}	
									{else}
										--
									{/if}
								{else}
									{$projectDetails[0].NO_OF_PLOTS}
								{/if}
							</td>
						</tr>
						{/if}
						
						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>No Of Towers:</b>
							</td>
							<td>
								{if $phaseId != ''}
									{if count($phase_towers)>0}
										{count($phase_towers)}
									{else}
										--
									{/if}
								{else}
									{$projectDetails[0].NO_OF_TOWERS}
								{/if}
							</td>
						</tr>

						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>No Of Flats:</b>
							</td>
							<td>
								{if $phaseId != ''}
									{if count($FlatsQuantity)>0}
										{array_sum($FlatsQuantity)}
									{else}
										--
									{/if}
								{else}
									{$projectDetails[0].NO_OF_FLATS}
								{/if}
							</td>
						</tr>
						
						
						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>Launched Units:</b>
							</td>
							<td>
								{if $projectDetails[0].LAUNCHED_UNITS != ''}
									{$projectDetails[0].LAUNCHED_UNITS}
								{else}
									--
								{/if}
							</td>
						</tr>
						
						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left" valign ="top">
								<b>Reason For UnLaunched Units:</b>
							</td>
							<td>
								{if $projectDetails[0].REASON_UNLAUNCHED_UNITS != ''}
									{$projectDetails[0].REASON_UNLAUNCHED_UNITS}
								{else}
									--
								{/if}
							</td>
						</tr>

						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>Project Location Desc:</b>
							</td>
							<td>
								{$projectDetails[0].LOCATION_DESC}
							</td>
						</tr>

						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>Project Latitude:</b>
							</td>
							<td>
								{$projectDetails[0].LATITUDE}
							</td>
						</tr>

						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>Project Longitude:</b>
							</td>
							<td>
								{$projectDetails[0].LONGITUDE}
							</td>
						</tr>

						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>Active:</b>
							</td>
							<td>
								{$projectDetails[0].ACTIVE}
							</td>
						</tr>

						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>Booking Status:</b>
							</td>
							<td>
								{$projectDetails[0].BOOKING_STATUS}
							</td>
						</tr>

						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>Project Status:</b>
							</td>
							<td>
								{$projectDetails[0].PROJECT_STATUS}
							</td>
						</tr>

						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>Project URL:</b>
							</td>
							<td>
								<a href = "http://www.proptiger.com/{$projectDetails[0].PROJECT_URL}">{$projectDetails[0].PROJECT_URL}</a>
							</td>
						</tr>
						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>Pre - Launch Date:</b>
							</td>
							<td>
								 {$projectDetails[0].PRE_LAUNCH_DATE}
							</td>
						</tr>

						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>Launch Date:</b>
							</td>
							<td>
								{$projectDetails[0].LAUNCH_DATE}
							</td>
						</tr>

						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>Promised Completion Date:</b>
							</td>
							<td>
								<a href = "add_project_construction.php?projectId={$projectId}">{$completionDate}</a>
							</td>
						</tr>
						
						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>Bank List:</b>
							</td>
							<td>
								{assign var=bank_arr value=","|explode:$projectDetails[0].BANK_LIST} 
								{if count($bank_arr)>1}
									{foreach from = $BankListArr key = key item = value}
										{if in_array($key,$bank_arr)} {$value} {/if}
									{/foreach}
								{else}
									--
								{/if}
								
							</td>
						</tr>

						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>YouTube Video Key:</b>
							</td>
							<td>
								{if $projectDetails[0].YOUTUBE_VIDEO!=""}
								 <a href = "{$projectDetails[0].YOUTUBE_VIDEO}"><a>
								  	Youtube Link 
								  {else}
								  	No link available
								{/if}
							</td>
						</tr>

						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>Approvals:</b>
							</td>
							<td>
								{$projectDetails[0].APPROVALS}
							</td>
						</tr>
						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>No Of Lifts Per Tower:</b>
							</td>
							<td>
								{$projectDetails[0].NO_OF_LIFTS_PER_TOWER}
							</td>
						</tr>

						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>Power Backup Available:</b>
							</td>
							<td>
								{$projectDetails[0].POWER_BACKUP}
							</td>
						</tr>

						{if {$projectDetails[0].POWER_BACKUP}!="No"}		 
						<tr height="25px;">

							<td nowrap="nowrap" width="6%" align="left">
								Power Backup Available
							</td>
							<td>
								 {$projectDetails[0].POWER_BACKUP_CAPACITY}
							</td>
						</tr>	
						{/if}

						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>Architect Name:</b>
							</td>
							<td>
								{$projectDetails[0].ARCHITECT_NAME}
							</td>
						</tr>

						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>Special Offer Description:</b>
							</td>
							<td>
								{$projectDetails[0].OFFER_DESC}
							</td>
						</tr>

						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>Residential:</b>
							</td>
							<td>
							  {if $projectDetails[0].RESIDENTIAL == 0}
								  	Residential
							  {/if}
							  
							  {if $projectDetails[0].RESIDENTIAL == 1}
							  Non	Residential
							  {/if}
							</td>
						</tr>

						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>Township:</b>
							</td>
							<td>
								{if $projectDetails[0].TOWNSHIP != ''}
									{$projectDetails[0].TOWNSHIP}
								{else}
									--
								{/if}
							</td>
						</tr>
						
					</table>
				</td>
			</tr>
			
			{*code start for calling records*}
			{if count($arrCaling)>0}
			<tr>
				<td width = "100%" align = "center" colspan = "16" style="padding-left: 30px;">
					<table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">
						
						<tr class="headingrowcolor" height="30px;">
							 <td  nowrap="nowrap" width="10%" align="center" class=whiteTxt >SNo.</td>
							 <td  nowrap="nowrap" width="10%" align="left" class=whiteTxt >Caller Name</td>
							 <td  nowrap="nowrap" width="10%" align="left" class=whiteTxt >Start Time</td>
							 <td  nowrap="nowrap" width="10%" align="left" class=whiteTxt >End Time</td>
							 <td  nowrap="nowrap" width="10%" align="center" class=whiteTxt >Audio Link</td>
							 <td nowrap="nowrap" width="90%" align="left" class=whiteTxt>Remark</td>
						</tr>
						
						{foreach from = $arrCaling key = key item = item}
							{if ($key+1)%2 == 0}
									{$color = "bgcolor='#F7F8E0'"}
							{else}
								{$color = "bgcolor='#f2f2f2'"}
							{/if}
						<tr {$color} height="25px;">
							<td nowrap="nowrap" width="10%" align="center">
								{$key+1}
							</td>
							<td width ="15%">
								{$item['FNAME']}
							</td>
							<td width ="15%">
								{$item['StartTime']}
							</td>
							<td width ="15%">
								{$item['EndTime']}
							</td>
							<td width ="30%" nowrap>
								<a href = "{$item['AudioLink']}" target=_blank>{$item['AudioLink']}</a>
							</td>
							<td width ="90%">
								{$item['Remark']}
							</td>
						</tr>
						{/foreach}
					</table>
				</td>
			</tr>
			{/if}
			{*end code start for calling records*}
			
			<tr>
				<td width = "100%" align = "center" colspan = "16" style="padding-left: 30px;">
					<table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">
						{if in_array($projectDetails[0].PROJECT_PHASE,$arrProjEditPermission)}
						 <tr>
						  <td align="left" colspan='2'><b>Available Amenities<button class="clickbutton" onclick="$(this).trigger('event2');">Edit</button></td>
							</tr>
						{/if}
						{if array_key_exists("resi_project_amenities",$lastUpdatedDetail)}
							<tr bgcolor = "#c2c2c2">
								  <td nowrap="nowrap"  align="left" colspan = "2"><b>Last Updated Detail</b><br></br>
									{foreach from = $lastUpdatedDetail['resi_project_amenities'] key=key item = item}
										
										<b>Department: </b> {$key}</br>
										<b>Name: </b> {$item['FNAME']}</br>
										<b>last Updated Date: </b> {$item['ACTION_DATE']}</br></br>
									{/foreach}
									
								  </td>
							  
							</tr>
						{/if}
						{foreach from=$AmenitiesArr key=k item=v} 
						{if $k != 99}
						{if array_key_exists($k,$arrNotninty)}
						<tr height="25px;">
							<td nowrap="nowrap" align="left"><b>{$v} :</b></td>
								 <td align ="left" nowrap>
								 
								  {if !in_array($arrNotninty[$k],$AmenitiesArr)}
									 {if count($arrNotninty[$k]) >0} {$arrNotninty[$k]} {else} -- {/if}  
								  {/if}
								  </td>	
							 {/if}
						</tr>
						{/if}
					  {/foreach}
					 
					  {section name=nm start=1 loop=20 step=1}
											
						<tr{if ($smarty.section.nm.index != 1) && (!array_key_exists($smarty.section.nm.index,$arrninty))} style = "display:none;"{/if}>
							<td nowrap="nowrap" width="6%" align="left"><b>Other Amenitiy:</b></td>
							<td nowrap>
								{if array_key_exists($smarty.section.nm.index,$arrninty)}
									{$arrninty[$smarty.section.nm.index]}
								{else}
									--
								{/if}
							</td>  				  
						</tr>
						{/section}
					</table>
				</td>
			</tr>
		   
			<tr>
				<td width = "100%" align = "center" colspan = "16" style="padding-left: 30px;">
					<table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">
						{if in_array($projectDetails[0].PROJECT_PHASE,$arrProjEditPermission)}
					     <tr>
							<td width="20%" align="left"><b>Specifications :</b><button class="clickbutton" onclick="$(this).trigger('event3');">Edit</button> </td>
					    </tr>
					   {/if}
		   
						{if array_key_exists('resi_proj_specification',$lastUpdatedDetail)}
							<tr bgcolor = "#c2c2c2">
								  <td nowrap="nowrap"  align="left" colspan = "2"><b>Last Updated Detail</b><br></br>
									{foreach from = $lastUpdatedDetail['resi_project_amenities'] key=key item = item}
										
										<b>Department: </b> {$key}</br>
										<b>Name: </b> {$item['FNAME']}</br>
										<b>last Updated Date: </b> {$item['ACTION_DATE']}</br></br>
									{/foreach}
									
								  </td>
							  
							</tr>
						{/if}
					
					
					
						  <tr>
						    <td nowrap="nowrap" width="6%" align="left"><b>Flooring</b> </td>
						    <td></td>
						</tr>
							  <tr>
									<td nowrap="nowrap" width="6%" align="left">Master Bedroom :</td>
									<td>
										{if $arrSpecification[0]['FLOORING_MASTER_BEDROOM'] != ''}
											{$arrSpecification[0]['FLOORING_MASTER_BEDROOM']}
										{else}
											--
										{/if}
								  </td>		 
							   </tr>
							   <tr>
									<td nowrap="nowrap" width="6%" align="left">Other Bedroom :</td>
									<td>
										{if $arrSpecification[0]['FLOORING_OTHER_BEDROOM'] != ''}
											{$arrSpecification[0]['FLOORING_OTHER_BEDROOM']}
										{else}
											--
										{/if}
									</td>		  
							   </tr>
							    <tr>
									<td nowrap="nowrap" width="6%" align="left">Living/Dining :</td>
									<td>
										{if $arrSpecification[0]['FLOORING_LIVING_DINING']!=''}
											{$arrSpecification[0]['FLOORING_LIVING_DINING']}
										{else}
											--
										{/if}
										</td>		  
							   </tr>
							   <tr>
									<td nowrap="nowrap" width="6%" align="left">Kitchen :</td>
									<td>
										{if $arrSpecification[0]['FLOORING_KITCHEN'] != ''}
											{$arrSpecification[0]['FLOORING_KITCHEN']}
										{else}
										     --
										{/if}
									</td>		   
							   </tr>

							   <tr>
									<td nowrap="nowrap" width="6%" align="left">Toilets :</td>
									<td>
										{if $arrSpecification[0]['FLOORING_TOILETS'] !=''}
											{$arrSpecification[0]['FLOORING_TOILETS']}
										{else}
											--
										{/if}
									
									</td>		  
							   </tr>

							    <tr>
									<td nowrap="nowrap" width="6%" align="left">Balcony :</td>
									<td>
										{if $arrSpecification[0]['FLOORING_BALCONY'] != ''}
											{$arrSpecification[0]['FLOORING_BALCONY']}
										{else}
											--
										{/if}
									</td>		  
							    </tr>

							   
								  <td nowrap="nowrap" width="6%" align="left"><b>Walls</b></td>
							   		<td></td>

							   <tr>
									<td nowrap="nowrap" width="6%" align="left">Interior</td>
									<td>
										{if $arrSpecification[0]['WALLS_INTERIOR'] != ''}
											{$arrSpecification[0]['WALLS_INTERIOR']}
										{else}
											--
										{/if}
									</td>						
								</tr>
							   <tr>
									<td nowrap="nowrap" width="6%" align="left">Exterior</td>
									<td>
										{if $arrSpecification[0]['WALLS_EXTERIOR'] != ''}
											{$arrSpecification[0]['WALLS_EXTERIOR']}
										{else}
											--
										{/if}
										</td>		  
							   </tr>
							    
							  <tr>
									<td nowrap="nowrap" width="6%" align="left">Kitchen</td>
									<td>
										{if $arrSpecification[0]['WALLS_KITCHEN'] != ''}
											{$arrSpecification[0]['WALLS_KITCHEN']}
										{else}
											--
										{/if}
									</td>		  
							  </tr>

							   <tr>
									<td nowrap="nowrap" width="6%" align="left">Toilets</td>
									<td>
										{if $arrSpecification[0]['WALLS_TOILETS'] != ''}
											{$arrSpecification[0]['WALLS_TOILETS']}
										{else}
											--
										{/if}
									</td>		  
							  </tr>

							  <tr>
									<td nowrap="nowrap" width="6%" align="left"><b>Fittings and Fixtures</b> </td>
									<td></td>
							  </tr>

							  <tr>
								<td nowrap="nowrap" width="6%" align="left">Kitchen</td>
								<td>
									{if $arrSpecification[0]['FITTINGS_AND_FIXTURES_KITCHEN'] != ''}
										{$arrSpecification[0]['FITTINGS_AND_FIXTURES_KITCHEN']}
									{else}
										--
									{/if}
								</td>	 	
							  </tr>
							  <tr>
									<td nowrap="nowrap" width="6%" align="left">Toilets</td>
									<td>
										{if $arrSpecification[0]['FITTINGS_AND_FIXTURES_TOILETS'] != ''}
											{$arrSpecification[0]['FITTINGS_AND_FIXTURES_TOILETS']}
										{else}
											--
										{/if}
									</td>		  
							   </tr>
							   <tr>
									 <td nowrap="nowrap" width="6%" align="left"><b>Doors</b> </td>
									 <td></td>
							  </tr>
							  <tr>
									<td nowrap="nowrap" width="6%" align="left">Main</td>
									<td>
										{if $arrSpecification[0]['DOORS_MAIN'] != ''}
											{$arrSpecification[0]['DOORS_MAIN']}
										{else}
											--
										{/if}
									</td>						
								 
							   </tr>
							   <tr>
									<td nowrap="nowrap" width="6%" align="left">Internal</td>
									<td>
										{if $arrSpecification[0]['DOORS_INTERNAL'] != ''}
											{$arrSpecification[0]['DOORS_INTERNAL']}
										{else}
											--
										{/if}
									</td> 
							  </tr>
							   <tr>
									<td nowrap="nowrap" width="6%" align="left"><b>Windows : </b></td>
									<td>
										{if $arrSpecification[0]['WINDOWS'] != ''}
											{$arrSpecification[0]['WINDOWS']}
										{else}
											--
										{/if}
									</td>
							  </tr>
								
							  <tr>
								  <td nowrap="nowrap" width="6%" align="left"><b>Electrical Fitting : </b></td>
								  <td>
								  	{if $arrSpecification[0]['ELECTRICAL_FITTINGS'] != ''}
								  		{$arrSpecification[0]['ELECTRICAL_FITTINGS']}
								  	{else}
								  		--
								  	{/if}
								  	</td>
							  </tr>

							   <tr>
								  <td nowrap="nowrap" width="6%" align="left"><b>Others : </b></td>
								  <td>
								  	{if $arrSpecification[0]['OTHERS'] != ''}
								  		{$arrSpecification[0]['OTHERS']}
								  	{else}
								  		--
								  	{/if}
								  </td> 
							  </tr>
					  </table>
				</td>
			</tr>
		  
		   <tr>
				<td width = "100%" align = "center" colspan = "16" style="padding-left: 30px;">
					<table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">
					
						{if in_array($projectDetails[0].PROJECT_PHASE,$arrProjEditPermission)}
						  	<tr>
							  <td width="20%" align="left"><b>Project Plans {$path}</b><button class="clickbutton" onclick="$(this).trigger('event4');">Edit</button></td>
							</tr>
						{/if}
						{if count($lastUpdatedDetail['project_plan_images'])>0}
						  <tr bgcolor = "#c2c2c2">
							  <td nowrap="nowrap"  align="left" colspan = "4"><b>Last Updated Detail</b><br></br>
								{foreach from = $lastUpdatedDetail['project_plan_images'] key=key item = item}
									
									<b>Department: </b> {$key}</br>
									<b>Name: </b> {$item['FNAME']}</br>
									<b>last Updated Date: </b> {$item['ACTION_DATE']}</br></br>
								{/foreach}
								
							  </td>
							  
						  </tr>
						{/if}
					
						  <tr bgcolor='#ffffff'>
											
								{$cnt = 0}
								{section name=data loop=$ImageDataListingArr}
								
								{if $cnt != 0 && $cnt%4 == 0}</tr><tr bgcolor='#ffffff'>{/if}
								
								<td class = "tdcls_{$cnt}" >
								
									<div  style="border:1px solid #c2c2c2;padding:4px;margin:4px;">
										
											<a class="pt_reqflrplan" href="{$imgDisplayPath}{$ImageDataListingArr[data].PLAN_IMAGE}" target="_blank">
													<img src="{$imgDisplayPath}{$ImageDataListingArr[data].PLAN_IMAGE}" height="70px" width="70px" title="{$ImageDataListingArr[data].PLAN_IMAGE}" alt="{$ImageDataListingArr[data].PLAN_IMAGE}" />
												</a>
												<br>
											<b>Image Type</b> :{$ImageDataListingArr[data].PLAN_TYPE}
											<br><br>
										<b>Image Title </b>:{$ImageDataListingArr[data].TITLE}<br><br>
									</div>
								</td>
								{$cnt = $cnt+1} 		
								{/section}
							</tr>
					</table>
				</td>
		   </tr>
			
			
		  
		  <tr>
				<td width = "100%" align = "center" colspan = "16" style="padding-left: 30px;">
				{if is_array($ImageDataListingArrFloor)}
					<table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">
					
						{if in_array($projectDetails[0].PROJECT_PHASE,$arrProjEditPermission)}
							<tr>
						  		<td align="left"  nowrap colspan ="4"><b>Floor Plans</b><button class="clickbutton" onclick="$(this).trigger('event11');">Edit</button></td>
							</tr>
						{/if}
						{if count($lastUpdatedDetail['resi_floor_plans'])>0}
						  <tr bgcolor = "#c2c2c2">
							  <td nowrap="nowrap"  align="left" colspan = "4"><b>Last Updated Detail</b><br></br>
								{foreach from = $lastUpdatedDetail['resi_floor_plans'] key=key item = item}
									
									<b>Department: </b> {$key}</br>
									<b>Name: </b> {$item['FNAME']}</br>
									<b>last Updated Date: </b> {$item['ACTION_DATE']}</br></br>
								{/foreach}
								
							  </td>
							  
						  </tr>
						{/if}
						  <tr bgcolor='#ffffff'>
											
								{$cnt = 0}
								{section name=data loop=$ImageDataListingArrFloor}
								
								{if $cnt != 0 && $cnt%4 == 0}</tr><tr bgcolor='#ffffff'>{/if}
								
								<td class = "tdcls_{$cnt}" >
									<div  style="border:1px solid #c2c2c2;padding:4px;margin:4px;">
										
											<a class="pt_reqflrplan" href="{$imgDisplayPath}{$ImageDataListingArrFloor[data].IMAGE_URL}
														" target="_blank">
												<img src="{$imgDisplayPath}{$ImageDataListingArrFloor[data].IMAGE_URL}" height="70px" width="70px" title = "{$ImageDataListingArrFloor[data].IMAGE_URL}" alt ="{$ImageDataListingArrFloor[data].IMAGE_URL}" />
											</a>
											<br>
										<b>	Image Title : </b>{$ImageDataListingArrFloor[data].NAME}<br><br>
									</div>
								</td>
								{$cnt = $cnt+1} 		
								{/section}
							</tr>
					</table>
				{/if}
				</td>
		   </tr>
		   
		   
		   <tr>
				<td width = "100%" align = "center" colspan = "16" style="padding-left: 30px;">
				{if is_array($ImageDataListingArrFloor)}
					<table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">
					
						{if in_array($projectDetails[0].PROJECT_PHASE,$arrProjEditPermission)}
						<tr>
						  	<td align="left"  nowrap colspan = "9">
						  	<b>Project Price:</b> <button class="clickbutton" onclick="$(this).trigger('event12');">Edit</button>&nbsp;&nbsp;
							<b>Project Configuration:</b> <button class="clickbutton" onclick="$(this).trigger('event7');">Edit</button>
							&nbsp;&nbsp;<b>Project Price Effective From:</b> {$ProjectOptionDetail[0].CREATED_DATE}
							<br><br>
						  	
						  	
						  	</td>
						</tr>
						{/if}
						{if count($lastUpdatedDetail['resi_project_options'])>0}
						  <tr bgcolor = "#c2c2c2">
							  <td nowrap="nowrap"  align="left" colspan = "9"><b>Last Updated Detail</b><br></br>
								{foreach from = $lastUpdatedDetail['resi_project_options'] key=key item = item}
									
									<b>Department: </b> {$key}</br>
									<b>Name: </b> {$item['FNAME']}</br>
									<b>Last Updated Date: </b> {$item['ACTION_DATE']}</br></br>
								{/foreach}
								
							  </td>
							  
						  </tr>
						{/if}
						{if count($ProjectOptionDetail)>0}
							<tr class="headingrowcolor" height="30px;">
								 <td  nowrap="nowrap" width="1%" align="center" class=whiteTxt >SNo.</td>
								 <td nowrap="nowrap" width="7%" align="left" class=whiteTxt>Unit Name</td>
								 <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>Size</td>
								 <td nowrap="nowrap" width="6%" align="left" class=whiteTxt>Price Per Unit Area</td>
								 <td nowrap="nowrap" width="6%" align="left" class=whiteTxt>Price Per Unit DP</td>
								 <td nowrap="nowrap" width="6%" align="left" class=whiteTxt>Price Per Unit FP</td>
								 <td nowrap="nowrap" width="6%" align="left" class=whiteTxt>Number of Floors</td>
								 <td nowrap="nowrap" width="6%" align="left" class=whiteTxt>Villa Floors</td>
								 <td nowrap="nowrap" width="6%" align="left" class=whiteTxt>File Name</td>
								
							</tr>
							{$cntAudit = 0}
							{foreach from = $ProjectOptionDetail key=key item = item}
								{if ($key+1)%2 == 0}
									{$color = "bgcolor='#F7F8E0'"}
								{else}
									{$color = "bgcolor='#f2f2f2'"}
								{/if}
							<tr {$color}>
								<td align = "center">{$key+1}</td>
								<td>
						 			 <input type='hidden' value='{$projectId}' name='projectId' />
									{$ProjectOptionDetail[$key]['UNIT_NAME']}
							  </td>
							 
							  <td>
								
								{if $ProjectOptionDetail[$key]['TOTAL_PLOT_AREA'] != 0}
									{$ProjectOptionDetail[$key]['TOTAL_PLOT_AREA']}
								{else}
									{$ProjectOptionDetail[$key]['SIZE']}
								{/if}
								</td>
							  <td>
								{$ProjectOptionDetail[$key]['PRICE_PER_UNIT_AREA']}
							  </td>
							  <td>
								{$ProjectOptionDetail[$key]['PRICE_PER_UNIT_AREA_DP']}
							  
							  </td>
							  <td>
							{$ProjectOptionDetail[$key]['PRICE_PER_UNIT_AREA_FP']}
							  
							  </td>
							   <td>
									{$ProjectOptionDetail[$key]['NO_OF_FLOORS']}
							  </td>
							  <td>
									{$ProjectOptionDetail[$key]['VILLA_NO_FLOORS']}
							  </td>
							  <td nowrap>
							  {if $ProjectOptionDetail[$key]['FLOOR_IMAGES'] != ''}
							  		{$exp = explode(",",$ProjectOptionDetail[$key]['FLOOR_IMAGES'])}
							  		{foreach from = $exp key=key item=item}
								  		{$expinner = explode("/",$item)}
								  		{$cnt = count($expinner)-1}
								  		{$imgName = $expinner[$cnt]}
										<a style="text-decoration : none;" href= "javascript:void(0);">{$imgName}</a><br>
									{/foreach}
							  {else}
							  	--
							  {/if}
							  </td>
							</tr>
							{if $phasename != '' && $stageName != ''}
								<tr bgcolor ="#ACFA58">
										<td align = "center" nowrap>Old Value</td>
										<td>
											{if $arrProjectPriceAuditOld['UNIT_NAME'][$cntAudit] != $ProjectOptionDetail[$key]['UNIT_NAME']}
												{$arrProjectPriceAuditOld['UNIT_NAME'][$cntAudit]}
											{else}
												--
											{/if}
									  </td>
									  <td>										
										{if trim($arrProjectPriceAuditOld['SIZE'][$cntAudit]) != trim($ProjectOptionDetail[$key]['SIZE'])}
										
												{if $arrProjectPriceAuditOld['TOTAL_PLOT_AREA'][$cntAudit] != 0}
													{$arrProjectPriceAuditOld['TOTAL_PLOT_AREA'][$cntAudit]}
												{else}
													{$arrProjectPriceAuditOld['SIZE'][$cntAudit]}
												{/if}
										{else}
												--
										{/if}
										</td>
									  <td>										
										{if $arrProjectPriceAuditOld['PRICE_PER_UNIT_AREA'][$cntAudit] != $ProjectOptionDetail[$key]['PRICE_PER_UNIT_AREA']}
											{$arrProjectPriceAuditOld['PRICE_PER_UNIT_AREA'][$cntAudit]}
										{else}
											--
										{/if}
									  </td>
									  <td>
									  	{if $arrProjectPriceAuditOld['PRICE_PER_UNIT_AREA_DP'][$cntAudit] != $ProjectOptionDetail[$key]['PRICE_PER_UNIT_AREA_DP']}
											{$arrProjectPriceAuditOld['PRICE_PER_UNIT_AREA_DP'][$cntAudit]}
										{else}
											--
										{/if}
									  </td>
									  <td>
									  	{if $arrProjectPriceAuditOld['PRICE_PER_UNIT_AREA_FP'][$cntAudit] != $ProjectOptionDetail[$key]['PRICE_PER_UNIT_AREA_FP']}
											{$arrProjectPriceAuditOld['PRICE_PER_UNIT_AREA_FP'][$cntAudit]}
										{else}
											--
										{/if}
									  </td>
									   <td>
											{if $arrProjectPriceAuditOld['NO_OF_FLOORS'][$cntAudit] != $ProjectOptionDetail[$key]['NO_OF_FLOORS']}
												{$arrProjectPriceAuditOld['NO_OF_FLOORS'][$cntAudit]}
											{else}
												--
											{/if}
									  </td>
									  <td>
									  		{if $arrProjectPriceAuditOld['VILLA_NO_FLOORS'][$cntAudit] != $ProjectOptionDetail[$key]['VILLA_NO_FLOORS']}
												{$arrProjectPriceAuditOld['VILLA_NO_FLOORS'][$cntAudit]}
											{else}
												--
											{/if}
									  </td>
									  <td nowrap>
									 &nbsp;
									  </td>
								</tr>
							{/if}
							{$cntAudit = $cntAudit+1}
							{/foreach}
						{/if}
						  
					</table>
				{/if}
				</td>
		   </tr>
			
		   <tr>
				<td width = "100%" align = "center" colspan = "16" style="padding-left: 30px;">
				{if is_array($ImageDataListingArrFloor)}
					<table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">
						 {if in_array($projectDetails[0].PROJECT_PHASE,$arrProjEditPermission)}
							<tr>
							  	<td align="left"  nowrap><b>Tower Details</b><button class="clickbutton" onclick="$(this).trigger('event5');">Edit</button></td>
							</tr>
						{/if}
						{if array_key_exists('resi_project_tower_details',$lastUpdatedDetail)}
						  <tr bgcolor = "#c2c2c2">
							  <td nowrap="nowrap"  align="left" colspan = "8"><b>Last Updated Detail</b><br></br>
								{foreach from = $lastUpdatedDetail['resi_project_tower_details'] key=key item = item}
									
									<b>Department: </b> {$key}</br>
									<b>Name: </b> {$item['FNAME']}</br>
									<b>last Updated Date: </b> {$item['ACTION_DATE']}</br></br>
								{/foreach}
								
							  </td>
							  
						  </tr>
						{/if}
						
						{if count($towerDetail)>0}
							{$flatChk = 0}
							{$flatAvailChk = 0}
								<tr class="headingrowcolor" height="30px;">
									<td class="whiteTxt" align = "center" nowrap><b>SNO.</b></td>
									<td class="whiteTxt" align = "center" nowrap><b>Tower Name</b></td>
									<td class="whiteTxt" align = "center" nowrap><b>No of floors</b></td>
									<td class="whiteTxt" align = "center" nowrap><b>No. of Flats</b></td>
									<td class="whiteTxt" align = "center" nowrap><b>Remarks</b></td>
									<td class="whiteTxt" align = "center" nowrap><b>Tower Facing Direction</b></td>
									<td class="whiteTxt" align = "center" nowrap><b>Stilt On Ground Floor</b></td>
									<td class="whiteTxt" align = "center" nowrap><b>Actual Completion Date</b></td>
								</tr>
								{$olderValue = ''}
								{$color = ''}
								{$grandTotalFloor = 0}
								{$grandTotalFlats = 0}
								{foreach from = $towerDetail key=key item = item}
									{$phaseWiseTotalFlats = 0}
									
									{foreach from = $item key = keyInner item = innerItem}
										
										{if ($keyInner)%2 == 0}
											{$color = "bgcolor='#F7F8E0'"}
										{else}
											{$color = "bgcolor='#f2f2f2'"}
										{/if}
										
										<tr {$color}  height ="30px">
										{if $olderValue != $key}
											<td valign ="top" align = "center" rowspan ={count($towerDetail[$key])}>{$key}</td>
										{/if}
										{$olderValue = $key}
										<td align="center">{$innerItem['TOWER_NAME']}</td>
										<td align="center">{$innerItem['NO_OF_FLOORS']}</td>
										<td align="center">{$innerItem['NO_OF_FLATS']}</td>
										{$phaseWiseTotalFlats = $phaseWiseTotalFlats+$innerItem['NO_OF_FLATS']}
										
										{if $key != 'NoPhase'}
											{$grandTotalFlats = $grandTotalFlats+$innerItem['NO_OF_FLATS']}
										{/if}
										<td align="center">{$innerItem['REMARKS']}</td>
										<td align="center">{$innerItem['TOWER_FACING_DIRECTION']}</td>
										<td align ="center">
											{if $innerItem['STILT'] == '1'} Yes {/if}
											{if $innerItem['STILT'] == '0'} No {/if}
										</td>
										<td align = "center">{$innerItem['ACTUAL_COMPLETION_DATE']}</td>
											
											
									</tr>		
									{/foreach}	
									
									<tr height ="30px" bgcolor="#F6D8CE">
										<td colspan ="3" align ="right"><b>Sub Total {$key} </b></b></td>
										<td  align = "center" nowrap><b>{$phaseWiseTotalFlats}</b></td>
									<td align = "center" nowrap><b></b></td>
									<td  align = "center" nowrap><b></b></td>
									<td  align = "center" nowrap><b></b></td>
									<td  align = "center" nowrap><b></b></td>
									</tr>		 
								</tr>
								{/foreach}
								
									<tr height ="30px" bgcolor="#F7F8E0">
										<td colspan ="3" align ="right"><b>Grand Total </b></b></td>
										<td  align = "center" nowrap><b>{$grandTotalFlats}</b></td>
									<td align = "center" nowrap><b></b></td>
									<td  align = "center" nowrap><b></b></td>
									<td  align = "center" nowrap><b></b></td>
									<td  align = "center" nowrap><b></b></td>
									</tr>
						{/if}
						  
					</table>
				{/if}
				</td>
		   </tr>
		   
		   <tr>
				<td width = "100%" align = "center" colspan = "16" style="padding-left: 30px;">
				{if is_array($ImageDataListingArrFloor)}
					<table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">
						 {if in_array($projectDetails[0].PROJECT_PHASE,$arrProjEditPermission)}
							<tr>
							  	<td align="left"  nowrap><b>Project Other Price: <button class="clickbutton" onclick="$(this).trigger('event13');">Edit</button></td>
							</tr>
						{/if}
						{if array_key_exists('resi_project_other_pricing',$lastUpdatedDetail)}
						  <tr bgcolor = "#c2c2c2">
							  <td nowrap="nowrap"  align="left" colspan = "3"><b>Last Updated Detail</b><br></br>
								{foreach from = $lastUpdatedDetail['resi_project_other_pricing'] key=key item = item}
									
									<b>Department: </b> {$key}</br>
									<b>Name: </b> {$item['FNAME']}</br>
									<b>last Updated Date: </b> {$item['ACTION_DATE']}</br></br>
								{/foreach}
								
							  </td>
							  
						  </tr>
						{/if}
							<tr height = "30px" class="headingrowcolor">
								<td class="whiteTxt" align="right">Component</td>
								<td class="whiteTxt" align="center">Select Value</td>
								<td class="whiteTxt" align="left" width = "50%">Field Type</td>
							</tr>
							<tr id="trid1" bgcolor="#F7F8E0">
						
								<td align="right"><b>EDC/IDC</b></td>
								<td  align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['EDC_IDC'] != '' && $otherPricing[0]['EDC_IDC_TYPE'] != ''}
										{$otherPricing[0]['EDC_IDC']}
										&nbsp;
										{strtoupper($otherPricing[0]['EDC_IDC_TYPE'])}
									{else}
										--
									{/if}
									
								</td>
								<td  align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['EDC_IDC_MEND_OPT'] == 'mend'}
										  Mandatory 
									{else}
										Optional
									{/if}
								</td>
							</tr>
			
										
							<tr id="trid1" bgcolor="#F7F7F7">
									
								<td align="right"><b>Lease Rent</b></td>
								<td  align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['LEASE_RENT'] != '' && $otherPricing[0]['LEASE_RENT_TYPE'] != ''}
										{$otherPricing[0]['LEASE_RENT']}
										&nbsp;
										{strtoupper($otherPricing[0]['LEASE_RENT_TYPE'])}
									{else}
										--
									{/if}
								</td>
								<td  align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['LEASE_RENT_MEND_OPT'] == 'mend'}
										  Mandatory 
									{else}
										Optional
									{/if}
			
								</td>
							</tr>
			
							<tr id="trid1" bgcolor="#F7F8E0">
									
								<td align="right"><b>Open Car Parking</b></td>
								<td  align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['OPEN_CAR_PARKING'] != '' && $otherPricing[0]['OPEN_CAR_PARKING_TYPE'] != ''}
										{$otherPricing[0]['OPEN_CAR_PARKING']}
										&nbsp;
										{strtoupper($otherPricing[0]['OPEN_CAR_PARKING_TYPE'])}
									{else}
										--
									{/if}
								</td>
								<td  align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['OPEN_CAR_PARKING_MEND_OPT'] == 'mend'}
										  Mandatory 
									{else}
										Optional
									{/if}
								</td>
							</tr>
							
							<tr id="trid1" bgcolor="#F7F7F7">
									
								<td align="right"><b>Closed Car Parking</b></td>
								<td  align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['CLOSE_CAR_PARKING'] != '' && $otherPricing[0]['CLOSE_CAR_PARKING_TYPE'] != ''}
										{$otherPricing[0]['CLOSE_CAR_PARKING']}
										&nbsp;
										{strtoupper($otherPricing[0]['CLOSE_CAR_PARKING_TYPE'])}
									{else}
										--
									{/if}
									
								</td>
								<td  align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['CLOSE_CAR_PARKING_MEND_OPT'] == 'mend'}
										  Mandatory 
									{else}
										Optional
									{/if}
								</td>
							</tr>
			
							<tr id="trid1" bgcolor="#F7F8E0">
									
								<td align="right"><b>Semi Closed Car Parking</b></td>
								<td  align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['SEMI_CLOSE_CAR_PARKING'] != '' && $otherPricing[0]['SEMI_CLOSE_CAR_PARKING_TYPE'] != ''}
										{$otherPricing[0]['SEMI_CLOSE_CAR_PARKING']}
										&nbsp;
										{strtoupper($otherPricing[0]['SEMI_CLOSE_CAR_PARKING_TYPE'])}
									{else}
										--
									{/if}
									
								</td>
								<td  align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['SEMI_CLOSE_CAR_PARKING_MEND_OPT'] == 'mend'}
										  Mandatory 
									{else}
										Optional
									{/if}
			
								</td>
							</tr>
			
							<tr id="trid1" bgcolor="#F7F7F7">
									
								<td align="right"><b>Club House</b></td>
								<td  align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['CLUB_HOUSE'] != '' && $otherPricing[0]['CLUB_HOUSE_PSF_FIXED'] != ''}
									
										{$otherPricing[0]['CLUB_HOUSE']}
										&nbsp;
										{strtoupper($otherPricing[0]['CLUB_HOUSE_PSF_FIXED'])}
									{else}
											--
									{/if}
								</td>
								<td  align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['CLUB_HOUSE_MEND_OPT'] == 'mend'}
										  Mandatory 
									{else}
										Optional
									{/if}
								</td>
							</tr>
			
							<tr id="trid1" bgcolor="#F7F8E0">
									
								<td align="right"><b>IFMS</b></td>
								<td  align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['IFMS'] != '' && $otherPricing[0]['IFMS_PSF_FIXED'] != ''}
									
										{$otherPricing[0]['IFMS']}
										&nbsp;
										{strtoupper($otherPricing[0]['IFMS_PSF_FIXED'])}
								   {else}
								   		--
								   {/if}
								</td>
								<td  align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['IFMS_MEND_OPT'] == 'mend'}
										  Mandatory 
									{else}
										Optional
									{/if}
								</td>
							</tr>
			
							<tr id="trid1" bgcolor="#F7F7F7">
									
								<td align="right"><b>Power backup charges</b></td>
								<td align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['POWER_BACKUP'] != '' && $otherPricing[0]['POWER_BACKUP_PSF_FIXED'] != ''}
									
										{$otherPricing[0]['POWER_BACKUP']}
										&nbsp;
										{strtoupper($otherPricing[0]['POWER_BACKUP_PSF_FIXED'])}
									{else}
										--
									{/if}
								</td>
								<td  align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['POWER_BACKUP_MEND_OPT'] == 'mend'}
										  Mandatory 
									{else}
										Optional
									{/if}
								</td>
							</tr>
			
							<tr id="trid1" bgcolor="#F7F8E0">
									
								<td align="right"><b>Legal Fees</b></td>
								<td  align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['LEGAL_FEES'] != '' && $otherPricing[0]['LEGAL_FEES_PSF_FIXED'] != ''}
									
										{$otherPricing[0]['LEGAL_FEES']}
										&nbsp;
										{strtoupper($otherPricing[0]['LEGAL_FEES_PSF_FIXED'])}
									{else}
										--
									{/if}
								</td>
								<td  align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['LEGAL_FEES_MEND_OPT'] == 'mend'}
										  Mandatory 
									{else}
										Optional
									{/if}
								</td>
							</tr>
			
							<tr id="trid1" bgcolor="#F7F7F7">
									
								<td align="right"><b>Power and Water</b></td>
								<td  align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['POWER_WATER'] != '' && $otherPricing[0]['POWER_WATER_PSF_FIXED'] != ''}
									
										{$otherPricing[0]['POWER_WATER']}
										&nbsp;
										{strtoupper($otherPricing[0]['POWER_WATER_PSF_FIXED'])}
									{else}
										--
									{/if}
								</td>
								<td  align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['POWER_WATER_MEND_OPT'] == 'mend'}
										  Mandatory 
									{else}
										Optional
									{/if}
								</td>
							</tr>
			
							<tr id="trid1" bgcolor="#F7F8E0">
									
								<td align="right"><b>Maintenance Advance</b></td>
								<td  align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['MAINTENANCE_ADVANCE'] != '' && $otherPricing[0]['MAINTENANCE_ADVANCE_PSF_FIXED'] != ''}
									
										{$otherPricing[0]['MAINTENANCE_ADVANCE']}
										&nbsp;
										{strtoupper($otherPricing[0]['MAINTENANCE_ADVANCE_PSF_FIXED'])}
									{else}
										--
									{/if}
								</td>
								<td  align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['MAINTENANCE_ADVANCE_MEND_OPT'] == 'mend'}
										  Mandatory 
									{else}
										Optional
									{/if}
								</td>
							</tr>
			
							<tr id="trid1" bgcolor="#F7F7F7">
									
								<td align="right"><b>Maintenance Advance months</b></td>
								<td  align="left" style = "padding-left:30px;">
									{$otherPricing[0]['MAINTENANCE_ADVANCE_MONTHS']} Months
								</td>
								<td  align="left" style = "padding-left:30px;">--</td>
							</tr>
			
			
							<tr id="trid1" bgcolor="#F7F8E0">
									
								<td align="right" valign ="top"><b>PLC:</b></td>
								<td  align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['PLC'] != ''}{$otherPricing[0]['PLC']}{else} -- {/if}
								</td>
								<td  align="left" style = "padding-left:30px;"></td>
								
							</tr>
							<tr id="trid1" bgcolor="#F7F7F7">
									
								<td align="right" valign ="top"><b>Floor Rise:</b></td>
								<td  align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['FLOOR_RISE'] != ''}{$otherPricing[0]['FLOOR_RISE']}{else} -- {/if}
								</td>
								<td  align="left" style = "padding-left:30px;">&nbsp;</td>
								
							</tr>
							<tr id="trid1" bgcolor="#F7F8E0">
									
								<td align="right" valign ="top"><b>Other:</b></td>
								<td  align="left" style = "padding-left:30px;">
									{if $otherPricing[0]['OTHERS'] != ''}{$otherPricing[0]['OTHERS']}{else} -- {/if}
								</td>
								<td>&nbsp;</td>
								
							</tr>
						</table>
				{/if}
				</td>
		   </tr>
		   
		   <tr>
				<td width = "100%" align = "center" colspan = "16" style="padding-left: 30px;">
				{if is_array($supplyAllArray)}
					<table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">
						 {if in_array($projectDetails[0].PROJECT_PHASE,$arrProjEditPermission)}
							<tr>
							  	<td align="left"  nowrap><b>Supply</b><button class="clickbutton" onclick="$(this).trigger('event8');">Edit</button></td>
							</tr>
						{/if}
						{if count($lastUpdatedDetail['resi_proj_supply'])>0}
						  <tr bgcolor = "#c2c2c2">
							  <td nowrap="nowrap"  align="left" colspan = "8"><b>Last Updated Detail</b><br></br>
								{foreach from = $lastUpdatedDetail['resi_proj_supply'] key=key item = item}
									
									<b>Department: </b> {$key}</br>
									<b>Name: </b> {$item['FNAME']}</br>
									<b>last Updated Date: </b> {$item['ACTION_DATE']}</br></br>
								{/foreach}
								
							  </td>
							  
						  </tr>
						{/if}
						
						 <tr>
							<td width = "100%" align = "center" colspan = "16" style="padding-left: 30px;">
							
								<table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">
										<tr class="headingrowcolor" height="30px;">
											<td class="whiteTxt" align = "center" nowrap><b>SNO.</b></td>
											<td class="whiteTxt" align = "center" nowrap><b>Phase<br>Launch <br> Completion Date</b></td>
											<td class="whiteTxt" align = "center" nowrap><b>Project Type</b></td>
											<td class="whiteTxt" align = "center" nowrap><b>Unit Type</b></td>
											
											<td class="whiteTxt" align = "center" nowrap><b>No of Flats</b></td>
											<td class="whiteTxt" align = "center" nowrap><b>Is flats Information is Currect</b></td>
											<td class="whiteTxt" align = "center" nowrap><b>Available No of Flats</b></td>
											<td class="whiteTxt" align = "center" nowrap><b>Is Available Flat Information is Currect</b></td>
											<td class="whiteTxt" align = "center" nowrap><b>Edit Reason</b></td>
											<td class="whiteTxt" align = "center" nowrap><b>Source Of Information</b></td>
											<td class="whiteTxt" align = "center" nowrap><b>Effective Date</b></td>
										</tr>
										{$olderValuePhase = ''}
										{$cnt = 0}
										{$totalSumFlat = 0}
										{$totalSumflatAvail = 0}
										{foreach from = $supplyAllArray key=key item = item}
											{$totalNoOfFlatsPPhase = 0}
											{$availableoOfFlatsPPhase = 0}
											
											{$olderValueType = ''}
											{foreach from = $item key = keyInner item = innerItem}
												
												{$totalNoOfFlatsPtype = 0}
												{$availableoOfFlatsPtype = 0}
												
												{foreach from = $innerItem key = keylast item = lastItem}
													
													{$cnt = $cnt+1}
													{if ($cnt)%2 == 0}
															{$color = "bgcolor='#F7F8E0'"}
													{else}
														{$color = "bgcolor='#f2f2f2'"}
													{/if}
													
													<tr {$color}  height="30px;">
														<td valign ="top" align="center">{$cnt}</td>
														
														{if $olderValuePhase == '' || $olderValuePhase != $key}
															<td valign ="top" align = "center" nowrap rowspan = "{count($arrPhaseCount[$key])+1}">
																{ucfirst($key)}
																<br>
																{if $lastItem['LAUNCH_DATE'] != '' && $lastItem['COMPLETION_DATE'] != ''}
																	
																	{$lastItem['LAUNCH_DATE']} <br> {$lastItem['COMPLETION_DATE']}
																{else}
																	--
																{/if}
															</td>
														{/if}
													
														{$olderValuePhase = $key}
													
														{if $olderValueType != $keyInner || $olderValueType == ''}
														<td valign ="top" align = "center" rowspan = "{count($arrPhaseTypeCount[$key][$keyInner])}">
															{$keyInner}
														</td>
														{/if}
														{$olderValueType = $keyInner}
													
													<td valign ="top" align="center">
														{$lastItem['NO_OF_BEDROOMS']}BHK
													</td>
													<td valign ="top" align="center" >{$lastItem['NO_OF_FLATS']}
														{$totalNoOfFlatsPtype = $totalNoOfFlatsPtype+$lastItem['NO_OF_FLATS']}
														{$totalNoOfFlatsPPhase = $totalNoOfFlatsPPhase+$lastItem['NO_OF_FLATS']}
														{if $key != 'noPhase'}
															{$totalSumFlat = $totalSumFlat+$lastItem['NO_OF_FLATS']}
															{$totalSumflatAvail = $totalSumflatAvail+$lastItem['AVAILABLE_NO_FLATS']}																	
														{/if}
														{if $phasename != '' && $stageName != ''}
															{if $lastItem['NO_OF_FLATS'] != $arrProjectSupply[$key][$keyInner][$keylast]['NO_OF_FLATS']}
																<br>
																<span style="background-color: yellow;">{$arrProjectSupply[$key][$keyInner][$keylast]['NO_OF_FLATS']}</span>
															{/if}
														{/if}
													</td>
													<td valign ="top" align="center">
														 {if $lastItem['ACCURATE_NO_OF_FLATS_FLAG'] == 1} Accurate {else} Guessed {/if}
														 {if $phasename != '' && $stageName != ''}
															 {if $lastItem['ACCURATE_NO_OF_FLATS_FLAG'] != $arrProjectSupply[$key][$keyInner][$keylast]['ACCURATE_NO_OF_FLATS_FLAG'] AND $arrProjectSupply[$key][$keyInner][$keylast]['ACCURATE_NO_OF_FLATS_FLAG'] != ''}
																<br>
																<span style="background-color: yellow;">
																	 {if $arrProjectSupply[$key][$keyInner][$keylast]['ACCURATE_NO_OF_FLATS_FLAG'] == 1} Accurate {else} Guessed {/if}
																</span>
															{/if}
														 {/if}
													</td>
													<td valign ="top" align="center">{$lastItem['AVAILABLE_NO_FLATS']}
														{$availableoOfFlatsPtype = $availableoOfFlatsPtype+$lastItem['AVAILABLE_NO_FLATS']}
														{$availableoOfFlatsPPhase = $availableoOfFlatsPPhase+$lastItem['AVAILABLE_NO_FLATS']}
														
														{if $phasename != '' && $stageName != ''}
															{if $lastItem['AVAILABLE_NO_FLATS'] != $arrProjectSupply[$key][$keyInner][$keylast]['AVAILABLE_NO_FLATS']}
																<br>
																<span style="background-color: yellow;">{$arrProjectSupply[$key][$keyInner][$keylast]['AVAILABLE_NO_FLATS']}</span>
															{/if}	
														{/if}										
													</td>
													
													<td valign ="top" align="center">
														
														 {if $lastItem['ACCURATE_AVAILABLE_NO_OF_FLATS_FLAG'] == 1} Accurate {else} Guessed {/if}
													    {if $phasename != '' && $stageName != ''}
														    {if $lastItem['ACCURATE_AVAILABLE_NO_OF_FLATS_FLAG'] != $arrProjectSupply[$key][$keyInner][$keylast]['ACCURATE_AVAILABLE_NO_OF_FLATS_FLAG'] AND $arrProjectSupply[$key][$keyInner][$keylast]['ACCURATE_AVAILABLE_NO_OF_FLATS_FLAG'] != ''}
																<br>
																<span style="background-color: yellow;">
																	{if $arrProjectSupply[$key][$keyInner][$keylast]['ACCURATE_AVAILABLE_NO_OF_FLATS_FLAG'] == 1} Accurate {else} Guessed {/if}
																</span>
															{/if}
														{/if}
														
													</td>
													<td valign ="top" align="center">
														{$lastItem['EDIT_REASON']}
														
														{if $phasename != '' && $stageName != ''}
															{if $lastItem['EDIT_REASON'] != $arrProjectSupply[$key][$keyInner][$keylast]['EDIT_REASON']}
																<br>
																<span style="background-color: yellow;">{$arrProjectSupply[$key][$keyInner][$keylast]['EDIT_REASON']}</span>
															{/if}
														{/if}
													</td>
													<td valign ="top" align ="center">
														{$lastItem['SOURCE_OF_INFORMATION']}
														
														{if $phasename != '' && $stageName != ''}
															{if $lastItem['SOURCE_OF_INFORMATION'] != $arrProjectSupply[$key][$keyInner][$keylast]['SOURCE_OF_INFORMATION']}
																<br>
																<span style="background-color: yellow;">{$arrProjectSupply[$key][$keyInner][$keylast]['SOURCE_OF_INFORMATION']}</span>
															{/if}
														{/if}
													</td>
													
													<td valign ="top" align ="center" nowrap>
														{$lastItem['SUBMITTED_DATE']}
														
														{if $phasename != '' && $stageName != ''}
															{if $lastItem['SUBMITTED_DATE'] != $arrProjectSupply[$key][$keyInner][$keylast]['SUBMITTED_DATE']}
																<br>
																<span style="background-color: yellow;">{$arrProjectSupply[$key][$keyInner][$keylast]['SUBMITTED_DATE']}</span>
															{/if}
														{/if}
													</td>
													
												</tr>	
												{/foreach}
												{if count($arrPhaseTypeCount[$key][$keyInner])>1}
													<tr bgcolor ="#FBF2EF" height="30px;">
														<td align ="right" colspan ="4" nowrap><b>SubTotal {$lastItem['PROJECT_TYPE']}</b></td>
														<td align ="center"><b> {$totalNoOfFlatsPtype}</b></td>
														<td align ="right" nowrap>&nbsp;</td>
														<td  align ="center"><b> {$availableoOfFlatsPtype}</b></td>
														<td  align ="left" >&nbsp;</td>
														<td  align ="left" >&nbsp;</td>
														<td  align ="left" >&nbsp;</td>
														<td  align ="left" >&nbsp;</td>
													</tr>
												{/if}
											{/foreach}
												<tr bgcolor ="#F6D8CE" height="30px;">
													<td align ="right" colspan ="4" nowrap><b>SubTotal {ucfirst($key)}</b></td>
													<td align ="center"><b> {$totalNoOfFlatsPPhase}</b></td>
													<td align ="right" nowrap >&nbsp;</td>
													<td align ="center"><b> {$availableoOfFlatsPPhase}</b></td>
													{if ucfirst($key) == 'NoPhase'}
														<td  align ="left" colspan ="4"><b> 
														Sold Out&nbsp;&nbsp;:&nbsp;&nbsp;
															{100-($availableoOfFlatsPPhase*100/$totalNoOfFlatsPPhase)|string_format:"%.2f"}%
														</b></td>													
													{else}
														<td  align ="left">&nbsp;</td>
														<td  align ="left" >&nbsp;</td>
														<td  align ="left" >&nbsp;</td>
														<td  align ="left" >&nbsp;</td>
													
													{/if}

													
												</tr>			 
										{/foreach}
												{if count($supplyAllArray)>1}
												<tr bgcolor ="#F2F2F2" height="30px;">
													<td align ="right" colspan ="4" nowrap><b>Grand Total {$flafHideGrandTot}</b></td>
													<td align ="center"><b> {$totalSumFlat}</b></td>
													<td align ="right" nowrap >&nbsp;</td>
													<td align ="center"><b>{$totalSumflatAvail}</b></td>
													<td  align ="left" colspan ="4"><b> 
														Sold Out&nbsp;&nbsp;:&nbsp;&nbsp;
															{100-($totalSumflatAvail*100/$totalSumFlat)|string_format:"%.2f"}%
														</b></td>

												</tr>
												{/if}
								
							</table>
						
				</td>
		   </tr>
		   {/if}
		   <tr><td colspan ="8">&nbsp;</td><tr>
		   <tr class="headingrowcolor" height="30px;">
			<td class="whiteTxt" colspan = "8" align ="center">
				<form method = "post" action = "">
					<input type = "hidden" name = "projectId" id = "projectId" value = "{$projectId}">
					<input type="submit" name="btnExit" id="btnExit" value="Exit">
			    </form>
			</td>
		</tr>
</div>
