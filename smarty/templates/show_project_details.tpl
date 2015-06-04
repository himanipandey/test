<link rel="stylesheet" type="text/css" href="csss.css"> 
<script type="text/javascript" src="js/jquery.js"></script>

<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

<script type="text/javascript">
	
    $(document).ready(function() {
		var pid = '{$phaseId}';
        var noPhasePhaseId = '{$noPhasePhaseId}';
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
			["event8","new/availability/"],
			["event9","add_tower_construction_status.php"],
			["event10","add_project_construction.php"],
			["event11", "edit_floor_plan.php", true],
			["event12", "/new/price", true],
			["event13", "project_other_price.php", true],
            ["event14", "secondary_price.php", true],
            ["event15", "insertSecondaryPrice.php", true],
            ["event16", "updateSecondaryPrice.php", true],
            ["event17", "/new/supply-validation/", true],
            ["event18", "allCommentHistory.php"],
            ["event19", "/new/bulk_price_inventory/", true],
            ["event20", "project_offers.php", true]
		]; 
		for(var i=0; i< eventArray.length; i++){
			$('.clickbutton').live(eventArray[i][0], function(event){
				
				for(var i=0; i<eventArray.length; i++){
					if(eventArray[i].indexOf(event.type)!=(-1)){
						if(eventArray[i][2]){
							var str = "&edit=edit";
						}
						else{
							var str="";
						}
                                                if(eventArray[i][0]=='event19'){
                                                    var url = eventArray[i][1]+ projectId + "/edit";    
                                                }else if(eventArray[i][0]=='event8'){
                                                    var url = eventArray[i][1]+ noPhasePhaseId + "/edit";
                                                }else if(eventArray[i][0]=='event17'){
                                                    var url = eventArray[i][1]+ projectId;
                                                }else{
                                                    var url = eventArray[i][1]+ "?projectId="+projectId+str+"&preview=true";    
                                                }
                                                if( eventArray[i][1] == 'allCommentHistory.php' ) {
                                                    window.open(url,'All Comment List','height=600,width=800,left=300,top=100,resizable=yes,scrollbars=yes, status=yes');
                                                }
                                                else 
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

	function changePhase(pId, phase, dir, projectStatus, arrAllCompletionDateChk,launchDate, 
            preLaunchDate,phaseId,stg,availabilityOrderChk,bedRoomOrder,availOrder,projectMoveValidation,configSizeFlag)
	{
		var flatChk      = $("#flatChk").val();
		var flatAvailChk = $("#flatAvailChk").val();
		var val = $('input:radio[name=validationChk]:checked').val();
		var flgChk = 0;	
                
                var skipValidationFlag = 0; //if = 1 then all validation will be skip
                /*******check if validation will be skip********/
                if("{$projectDetails[0].SKIP_B2B}" == 1 || "{$projectDetails[0].STATUS}" == 'Inactive'){
                    skipValidationFlag = 1;
                }                
                
                /*******code for check user have access to move project or not******/
                if(dir != 'backward' && projectMoveValidation <=0 && projectMoveValidation != -999 
                    && ((phase == 'DataCollection' && (stg == 'UpdationCycle' || stg == 'SecondaryPriceCycle')) || phase == 'DcCallCenter')){
                    alert("This project is not assigned to you!");
                    return false;
                    
                }
                /*******end code for check user have access to move project or not******/
                console.log("Phase: "+phase, "Stage: "+stg, "SkipFlag:"+skipValidationFlag);
		if(skipValidationFlag == 0 && dir != 'backward' && val == 'Y' && ((phase == 'DataCollection' && stg == 'UpdationCycle') || (phase == 'DcCallCenter' && stg == 'NewProject')))
		{
			if(phaseId != '')
			{
				alert("Please select No Phase!");
				return false;
			}							
			else if((projectStatus == 'Occupied' || projectStatus == 'ReadyForPossession') && arrAllCompletionDateChk == '1')
			{
				alert("Promised Completion Date is Mendetory!");
				return false;
			}
			else if(projectStatus == 'UnderConstruction' || projectStatus == 'Launch')
			{
				if(launchDate == '0000-00-00' || arrAllCompletionDateChk == 1)
				{
					alert("Launch Date, Promised Completion Date are Mendetory!");
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
			else if(projectStatus == 'PreLaunch' && preLaunchDate == '0000-00-00')
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
		      //alert(configSizeFlag + phase);
				if(skipValidationFlag == 0 && dir != 'backward' && ((phase == 'DataCollection' && stg == 'UpdationCycle') || (phase == 'DcCallCenter' && stg == 'NewProject')) && configSizeFlag == 1 && (projectStatus == 'UnderConstruction' || projectStatus == ' Launch' || projectStatus == 'PreLaunch')) {
                    alert("Config sizes are required!");
                    return false;
                }
				isVerifedSupplyMovFlag = "{$isSupplyLaunchVerified}";				
				if(skipValidationFlag == 0 && dir != 'backward' && phase == 'Audit1' && !isVerifedSupplyMovFlag) {
                    alert("Supply is not verified!");
                    return false;
                }
                
                if(skipValidationFlag == 0 && dir != 'backward' && phase == 'Audit1' && availabilityOrderChk == 'false') {
                    alert("Supply order should be in descending order!\nCurrent order is "+availOrder+" for bedroom "+bedRoomOrder);
                    return false;
                }
		
		if(flgChk == 1)
		{
			isNewRemark = "{$projectComments['audit2Remark']->status}";
			remarkTxt = "{$projectComments['audit2Remark']->comment_text}";
			remarkId = "{$projectComments['audit2Remark']->comment_id}";
			if(dir=='forward'){
				if(skipValidationFlag == 0 && isNewRemark == 'New' && phase == "Audit1"){
					if (confirm("New Remark : '" + remarkTxt + "'\n\n Have you read the Above Remark ? (if yes then press OK and proceed)"))
					{
						$('#newRemarkId').val(remarkId);
						$('#forwardFlag').val('yes');
						$('#currentPhase').val(phase);
						$("#returnURLPID").val("show_project_details.php?projectId=" + pId);
						$('#changePhaseForm').submit();
					}
					
				}else{
					if (confirm("Do you want to proceed ?"))
					{
						$('#forwardFlag').val('yes');
						$('#currentPhase').val(phase);
						$("#returnURLPID").val("show_project_details.php?projectId=" + pId);
						$('#changePhaseForm').submit();
					}
				}
				
			}
			else if(dir=='backward')
			{
				if (confirm("Do you want to revert ?"))
				{
					$('#forwardFlag').val('no');
					$('#currentPhase').val(phase);
					$('#returnStage').val(stg);
					$("#returnURLPID").val("show_project_details.php?projectId=" + pId);
					$('#changePhaseForm').submit();
				}	
			}
			else if(dir=='updation')
			{
				if(skipValidationFlag == 0 && isNewRemark == 'New' && phase == "Audit1"){
					if (confirm("New Remark : '" + remarkTxt + "'\n\n Have you read the Above Remark ? (if yes then press OK and proceed)"))
					{   
						$('#newRemarkId').val(remarkId);
						$('#forwardFlag').val('update');
						$('#currentPhase').val(phase);
						$("#returnURLPID").val("show_project_details.php?projectId=" + pId);
						$('#changePhaseForm').submit();
					}	
				}else{
					if (confirm("Do you want to proceed ?"))
					{
						$('#forwardFlag').val('update');
						$('#currentPhase').val(phase);
						$("#returnURLPID").val("show_project_details.php?projectId=" + pId);
						$('#changePhaseForm').submit();
					}	
				}
				
			}
		}
	
	}

function getDateNow(){
	return (new Date().getTime());
}
function broker_call_edit(callId, brokerId)
{
	//code for builder contact info popup
    var url = "/broker_call_edit.php?callId="+callId+"&brokerId="+brokerId;
   //  jQuery.fancybox({
   //      'href' :  url
   //  });
     $.fancybox({
        'width'                :720,
        'height'               :200,
      
        'href'                 : url,
        'type'                : 'iframe'
    })

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
              beforeSend: function(){                
                $("body").addClass("loading");
              },
              success:function(dt){
                       $("body").removeClass("loading");
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
              beforeSend: function(){                
                $("body").addClass("loading");
              },
              success:function(dt){
                  $("body").removeClass("loading");
                  window.location.href = "show_project_details.php?projectId="+pid+"&flag="+dt;
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
       if(campaign == 'Select'){
		alert("Please select Campaign!");
		return;
	  }
      if(phNo.toString().trim().charAt(0)!=='0')phNo = '0'+phNo;
	 if( !isNaN(phNo) && $("#"+phId).val().indexOf('+') == -1 && $("#"+phId).val().indexOf('-') == -1) {
		$.ajax(
		{
	      type:"get",
	      url:"call_contact.php",
	      data:"contactNo="+phNo+"&campaign="+campaign+"&projectType=primary",
	      success: function(dt) { // return call Id
		  resp = dt.split('_');
		  if (resp[0].trim() === "call") {
		      $('#callId_'+id).val(resp[1].trim());
		      alert('Calling... '+phNo);
		  }
		  else 
		      alert("Error in calling");
		  
	      }
		});
      }
      else
        alert("Please enter valid mobile number");
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
      $.ajax({
         type: "POST",
         //dataType:"json",
         url: 'ajax/show_builder_contact_info.php',
         data: { 'currentUser':"{$currentUser}",BUILDER_ID:"{$builderDetail['BUILDER_ID']}", BUILDER_NAME:"{$builderDetail['BUILDER_NAME']}", URL:"{$builderDetail['URL']}", WEBSITE:"{$builderDetail['WEBSITE']}" },
         success:function(msg){
           if(msg){
             $.fancybox({
                    'content': msg,
                    'onCleanup': function () {
                        //	$("#row_"+rowId).remove();
                    }

                });
            }
         }
     });
  	
  }
/*********builder contact info related js end here*************/

/**********Old value dispaly function****/
 function oldValueShow(stageName, phasename, projectId)
 {
	window.location.href = "show_project_details.php?stageName="+stageName+"&phasename="+phasename+"&projectId="+projectId;
 }
 $(document).ready(function(){
	 
	 $('#diffButton').click(function(){
		 projectID = $('#projectId').val();
		 projectStage = "{$projectDetails[0]['PROJECT_STAGE_ID']}";
		 projectPhase = "{$projectDetails[0]['PROJECT_PHASE_ID']}";
		 $.ajax({
	      type:"post",
	      url:"project_stage_difference.php",
	      data:"projectID="+projectID+"&stageID="+projectStage+"&phaseID="+projectPhase,
	      success : function (dt) {
			$('#diffContent td').html(dt);
	      }
	  });
		
	});
});
function fetchPlanImages(objectType, objectId, contentArea){
    $.ajax({
        type: "post",
        url: "fetch_plan_images.php",
        data: "objectId=" + objectId + "&objectType=" + objectType,
        beforeSend: function(){
            console.log('in ajax beforeSend');
            $("body").addClass("loading");
          },
        success: function (dt) {                    
            $('#'+contentArea).html(dt);
            $("body").removeClass("loading");    
            if(dt.trim() != '<td>Data not found!</td>' && objectType == 'property'){
                $('#edit-floor-images').show();
            }else if(dt.trim() != '<td>Data not found!</td>' && objectType == 'project'){
                $('#edit-plan-images').show();
            }

        }
    });
}

function download_project_brochure(pid){
    $.ajax({
        type: "post",
        url: "ajax/fetch_project_brochure.php",
        data: "objectId=" + pid,
        beforeSend: function(){
            console.log('in ajax beforeSend');
            $("body").addClass("loading");
          },
        success: function (dt) {                    
            $("body").removeClass("loading"); 
            
            if(dt.trim() == 'Empty')
                alert('Project Brochure not available!');
            else
                window.location = dt;
        }
    });
}

function show_calling_links(pid, type){
    $.ajax({
        type: "post",
        url: "ajax/fetch_project_calling_links.php",
        data: "projectId=" + pid + "&projectType=" + type,
        beforeSend: function(){
            console.log('in ajax beforeSend');
            $("body").addClass("loading");
          },
        success: function (dt) {                    
            $("body").removeClass("loading"); 
            
            if(dt.trim() == 'Empty'){
                alert('Project '+ type +' calling links not available!');
            }else{
                
                if(type == 'primary'){
                    $('#primary-links').html(dt);
                }else{
                    $('#update-secodary-price').show();
                    $('#secondary-links').html(dt);
                }
            }
        }
    });
}

function show_project_prices(pid){
    
    $.ajax({
        type: "post",
        url: "ajax/fetch_project_prices.php",
        data: "projectId=" + pid + "&locId=" + "{$projectDetails[0]['LOCALITY_ID']}",
        beforeSend: function(){           
            $("body").addClass("loading");
          },
        success: function (dt) {                    
            $("body").removeClass("loading"); 
            
            if(dt.trim() == 'Empty'){
                alert('Project prices are not available!');
            }else{
                $('#show-project-prices').html(dt);
            }
        }
    });
}
function show_project_supplies(pid, project_phase, isSupplyLaunchVerified){
    $.ajax({
        type: "post",
        url: "ajax/fetch_project_supplies.php",
        data: "projectId=" + pid + "&project_phase="+project_phase + "&isSupplyLaunchVerified="+isSupplyLaunchVerified,
        beforeSend: function(){           
            $("body").addClass("loading");
          },
        success: function (dt) {                    
            $("body").removeClass("loading"); 
            
            if(dt.trim() == 'Empty'){
                alert('Project supplies are not available!');
            }else{
                $('#show-project-supplies').html(dt);
            }
        }
    });
}
</script>

<div class="modal">Please Wait..............</div>
<form  action="show_project_details.php?projectId={$projectId}" method="POST" id="changePhaseForm">
  <input type="hidden" id="forwardFlag" name="forwardFlag" value=""/>
  <input type="hidden" id="currentPhase" name="currentPhase" value=""/>
  <input type="hidden" id="reviews" name="reviews" value=""/>
  <input type="hidden" id="revertFlag" name="revertFlag" value=""/>
  <input type="hidden" id="returnURLPID" name="returnURLPID" value=""/>
  <input type="hidden" id="returnStage" name="returnStage" value=""/>
  <input type="hidden" id="newRemarkId" name="newRemarkId" value=""/>
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
		{if $projectDetails[0].PROJECT_PHASE=="DataCollection"}
		<span> Data Collection</span>
		{/if}

		{if $projectDetails[0].PROJECT_PHASE=="DcCallCenter"}
		<span> Data Collection Call Center</span>
		{/if}
		
		{if $projectDetails[0].PROJECT_PHASE=="NewProject"}
		<span> New Project Audit</span>
		{/if}
		{if $projectDetails[0].PROJECT_PHASE=="Audit1"}
		<span> Audit 1</span>
		{/if}
		{if $projectDetails[0].PROJECT_PHASE=="Audit2"}
		<span> Audit 2</span>
		{/if}
		{if $projectDetails[0].PROJECT_PHASE=="Complete"}
		<span> Audit Completed</span>
		{/if}
	</div>
{if $projectLabel != ''}
	Label: {$projectLabel}
	<br>
{/if}
<span>	Current Assigned Department : </span>
<span> {$currentCycle}</span>      
{if $projectDetails[0].PROJECT_STAGE != 'NoStage'}

	{$projectStatus = $projectDetails[0]['project_status']}
	{* $phaseId = $projectDetails[0]['PROJECT_PHASE_ID'] *}
	{$launchDate = $projectDetails[0]['LAUNCH_DATE']}
	{$prelaunchDate = $projectDetails[0]['PRE_LAUNCH_DATE']}
	{$stageProject = $projectDetails[0].PROJECT_STAGE}
	
	{if count($accessModule)>0}
             <br> 
            <span>
                Move Validation?<input type = "radio" name = "validationChk" value = "Y" checked>Yes&nbsp;
                <input type = "radio" name = "validationChk" value = "N">No<br>
            </span>
	{else}
	   <span style = "display:none;"><input type = "radio" name = "validationChk" value = "Y" checked></span>
	{/if}
	{if $projectDetails[0].PROJECT_STAGE=='NewProject'}
            {if in_array($projectDetails[0].PROJECT_PHASE,$arrProjEditPermission)}
                 <button id="phaseChange" onclick="changePhase({$projectId},'{$projectDetails[0].PROJECT_PHASE}','forward','{$projectStatus}','{$arrAllCompletionDateChk}',
                '{$launchDate}','{$prelaunchDate}','{$phaseId}','{$stageProject}','{$availabilityOrderChk}','{$bedRoomOrder}','{$availOrder}','{$projectMoveValidation}','{$configSizeFlag}');">Move To Next Stage	</button>
            {/if}
	{else}
            {if in_array($projectDetails[0].PROJECT_PHASE,$arrProjEditPermission)}
                <button id="phaseChange" onclick="changePhase({$projectId},'{$projectDetails[0].PROJECT_PHASE}',
                'updation','{$projectStatus}','{$arrAllCompletionDateChk}','{$launchDate}','{$prelaunchDate}','{$phaseId}','{$stageProject}',
            '{$availabilityOrderChk}','{$bedRoomOrder}','{$availOrder}','{$projectMoveValidation}','{$configSizeFlag}');">Move To Next Stage	</button>
            {/if}
	{/if}

	{if $projectDetails[0].PROJECT_PHASE!="DataCollection" && $projectDetails[0].PROJECT_PHASE!="Complete" && in_array($projectDetails[0].PROJECT_PHASE,$arrProjEditPermission)}
	<button id="phaseChange" onclick="changePhase({$projectId},'{$projectDetails[0].PROJECT_PHASE}','backward','{$projectStatus}',
'{$arrAllCompletionDateChk}','{$launchDate}','{$prelaunchDate}','{$phaseId}','{$stageProject}',
'{$availabilityOrderChk}','{$bedRoomOrder}','{$availOrder}','{$projectMoveValidation}','{$configSizeFlag}');">Revert	</button>

	{/if}
{/if}<br>
<!--{if $projectDetails[0].PROJECT_PHASE!="Complete"}
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
{/if} 	-->	
    {if $errorValidation != ''}{$errorValidation}{/if}
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
		<table cellSpacing="1" cellPadding="4" width="100%" align="center" border="0">
	      <div>
	      <tr>
	      <td style = "padding-left:30px;">
                <b>Project {if in_array($projectDetails[0].PROJECT_PHASE,$arrProjEditPermission)}<button class="clickbutton" onclick="$(this).trigger('event1');">Edit</button>{/if}

                    &nbsp;&nbsp;&nbsp;&nbsp;
                    {if 
                    (trim($projectDetails[0].PROJECT_STAGE) == 'NewProject' AND trim($projectDetails[0].PROJECT_PHASE) == 'NewProject')
                    OR
                    (trim($projectDetails[0].PROJECT_STAGE) == 'NewProject' AND trim($projectDetails[0].PROJECT_PHASE) == 'Audit1')
                    OR
                    (trim($projectDetails[0].PROJECT_STAGE) == 'UpdationCycle' AND trim($projectDetails[0].PROJECT_PHASE) == 'Audit1')}
                    {/if}
            <!-- Project Phases -->
            &nbsp;&nbsp;&nbsp;&nbsp;
            {if in_array($projectDetails[0].PROJECT_PHASE,$arrProjEditPermission)}
            &nbsp;&nbsp;&nbsp;&nbsp;<b align="left">Project Phases:<b><button class="clickbutton" onclick="$(this).trigger('event6');">Edit</button>
            {/if}
           
            <!-- End of Project Phases -->	
            <!-- Project Diff -->
            &nbsp;&nbsp;&nbsp;&nbsp;
            
            {if in_array($projectDetails[0].PROJECT_PHASE,$arrProjEditPermission) && $projectDetails[0]['PROJECT_PHASE_ID'] > 3 && ($projectDetails[0]['PROJECT_STAGE_ID'] == 2 || $projectDetails[0]['PROJECT_STAGE_ID'] == 3)} 
				&nbsp;&nbsp;&nbsp;&nbsp;<b align="left">Project Stage Differenece:</b><button id="diffButton">Diff</button>
		    {/if}
            <!-- End of Project Diff -->	   
            <!-- offer edit -->
             {if in_array($projectDetails[0].PROJECT_PHASE,$arrProjEditPermission)}
            &nbsp;&nbsp;&nbsp;&nbsp;<b align="left">Project Offers:<b><button class="clickbutton" onclick="$(this).trigger('event20');">Edit</button>
            {/if}
            <!-- offer edit end -->
			</td></tr>				   
			<tr>
				<td width = "100%" align = "center" colspan = "16" style="padding-left: 30px;">
					<table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">
						
						<tr id="diffContent">
							  <td colspan = "2" valign ="top"  nowrap="nowrap" width="1%" align="left"></td>						  
						</tr>
						<tr bgcolor = "#c2c2c2">
							  <td colspan = "2" valign ="top"  nowrap="nowrap" width="1%" align="left"><b>Last Updated Detail</b></br>
						  	  </br>
								{foreach from = $lastUpdatedDetail['resi_project'] key=key item = item}
									
									<b>Department: </b> {$item['dept']}</br>
									<b>Name: </b> {$item['name']}</br>
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
                                                        {$builderDetail['ENTITY']}
                                                  </td>
						</tr>

						<tr height="25px;">
							  <td width="1%" align="left" colspan ="2" valign ="top"><b>Builder Contact Information</b>&nbsp;&nbsp;
							  	<span id = "plusMinusImg">
							  	<a href = "javascript:void(0);" onclick = "showhideBuilder('plus');">
							  		
							  			<img src = "images/plus.jpg" width ="20px">
							  		
							  	</a>
							  	</span>{if $callerMessage != ''}
                                                                &nbsp;&nbsp;&nbsp;&nbsp;<font color = "green"><b>{$callerMessage}</b></font>
                                                              {/if}
							  </td>
						</tr>

						{*Builder contact info show hide*}
						
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
                                                       {$city}
                                                  </td>
						</tr>

						<tr height="25px;">
							  <td  nowrap="nowrap" width="1%" align="left"><b>Suburb:</b></td>
							  <td>
							  {$suburb}
							</td>
						</tr>

						<tr height="25px;">
                                                    <td  nowrap="nowrap" width="1%" align="left"><b>Locality:</b></td>

                                                    <td>
                                                          {$locality}
                                                  </td>
						</tr>

						<tr height="25px;">
                                                    <td  nowrap="nowrap" width="1%" align="left" valign ="top"><b>Project Description:</b></td>
                                                    <td>
                                                      {$projectDetails[0].PROJECT_DESCRIPTION}
                                                  </td>
						</tr>
						<tr height="25px;">
                                                    <td  nowrap="nowrap" width="1%" align="left" valign ="top"><b>Description Reviewed:</b></td>
                                                    <td>
                                                    	{if $projectDetails[0].desc_content_flag == 1}
                                                          Yes
                                                      	{else}
                                                          No
                                                      	{/if}
                                                      
                                                  </td>
						</tr>
                                                <tr height="25px;">
                                                    <td  nowrap="nowrap" width="1%" align="left" valign ="top"><b>Project Comments:</b></td>
                                                    <td>
                                                      {$projectDetails[0].COMMENTS}
                                                  </td>
						</tr>
						<tr height="25px;">
							  <td  nowrap="nowrap" width="1%" align="left"><b>Project Address:</b></td>

							  <td>
							  	{$projectDetails[0].PROJECT_ADDRESS}
							</td>
						</tr>

						<tr height="25px;">
							  <td  nowrap="nowrap" width="1%" align="left"><b>Source of Information:</b></td>

							  <td>
							  	{$projectDetails[0].SOURCE_OF_INFORMATION}
							</td>
						</tr>
                                                <tr height="25px;">
                                                    <td  nowrap="nowrap" width="1%" align="left" valign ="top"><b>Price Disclaimer:</b></td>
                                                    <td>
                                                      {if $projectDetails[0].PRICE_DISCLAIMER != ''}
                                                          {$projectDetails[0].PRICE_DISCLAIMER}
                                                      {else}
                                                          --
                                                      {/if}
                                                  </td>
						</tr>
                                                <tr height="25px;">
                                                    <td  nowrap="nowrap" width="1%" align="left" valign ="top"><b>Open Space:</b></td>
                                                    <td>
                                                      {if $projectDetails[0].OPEN_SPACE != ''}
                                                          {$projectDetails[0].OPEN_SPACE}
                                                      {else}
                                                          --
                                                      {/if}
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
											
						<!--<tr height="25px;">
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
						</tr>-->
                                               
						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>Number Of Towers:</b>
							</td>
							<td>
								{$projectDetails[0].NO_OF_TOWERS}
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
								{$projectDetails[0].STATUS}
							</td>
						</tr>
						{if $projectDetails[0].STATUS == 'Inactive' && $project_alias_detail != 0}
						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>Inactive Reason:</b>
							</td>
							<td>
								{if $project_alias_detail->duplicate_project_id}
									Duplicate PID : {$project_alias_detail->duplicate_project_id}
								{else}
									{$project_alias_detail->reason_text}
								{/if}
							</td>
						</tr>
						{/if}
                                                
						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>Booking Status:</b>
							</td>
							<td>
                                                            {if $project_booking_status_id > 0}
																	{if $project_booking_status_id == 1}Available{/if}
																	{if $project_booking_status_id == 2}Sold out{/if}
																	{if $project_booking_status_id == 3}On Hold{/if}
																{else}
																	--
																{/if}
							</td>
						</tr>
                                                
						<tr height="25px;">
                                                    <td nowrap="nowrap" width="6%" align="left">
                                                            <b>Project Status:</b>
                                                    </td>
                                                    <td>
                                                     {$projectDetails[0].display_name}
                                                    </td>
						</tr>

						<tr height="25px;">
							<td nowrap="nowrap" width="6%" align="left">
								<b>Project URL:</b>
							</td>
							<td>
								<a href = "https://www.proptiger.com/{$projectDetails[0].PROJECT_URL}">{$projectDetails[0].PROJECT_URL}</a>
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
								<b>Expected Supply Date:</b>
							</td>
							<td>
								 {$projectDetails[0].EXPECTED_SUPPLY_DATE}
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
                                                        {$completionDate}</td>
						</tr>
						<tr height="25px;">
                                                    <td nowrap="nowrap" width="6%" align="left">
                                                        <b>Completion Effective Date:</b>
                                                    </td>
                                                    <td>
                                                       {if $completionDate!='0000-00-00'}{$completionEffDate|date_format:"%b %Y"}{/if}
                                                    </td>
						</tr>
						
						<tr height="25px;">
                                                    <td nowrap="nowrap" width="6%" align="left">
                                                            <b>Bank List:</b>
                                                    </td>
                                                    <td>

                                                        {if count($bankList)>0}
                                                            {foreach from = $bankList key = key item = value}
                                                                    {$value->bank_name}
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
                                                        {if $project_video_links!=""}
                                                         <a href = "{$projectDetails[0].YOUTUBE_VIDEO}"><a>
                                                                {$project_video_links} Youtube Link Available
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
                                                        <b>Architect Name:</b>
                                                    </td>
                                                    <td>
                                                        {$projectDetails[0].ARCHITECT_NAME}
                                                    </td>
						</tr>
                                               
						<tr height="25px;">
                                                    <td nowrap="nowrap" width="6%" align="left">
                                                            <b>Residential:</b>
                                                    </td>
                                                    <td>
                                                      {$projectDetails[0].RESIDENTIAL_FLAG}
                                                    </td>
						</tr>

						<tr height="25px;">
                                                    <td nowrap="nowrap" width="6%" align="left">
                                                            <b>Township:</b>
                                                    </td>
                                                    <td>
                                                        {if $projectDetails[0].township_name != ''}
                                                                {$projectDetails[0].township_name}
                                                        {else}
                                                                --
                                                        {/if}
                                                    </td>
						</tr>
						<tr height="25px;">
                                                    <td nowrap="nowrap" width="6%" align="left">
                                                            <b>Housing Authority:</b>
                                                    </td>
                                                    <td>
                                                        {if $authority != ''}
                                                                {$authority}
                                                        {else}
                                                                --
                                                        {/if}
                                                    </td>
						</tr>
                                               
                                                <tr height="25px;">
                                                    <td nowrap="nowrap" width="6%" align="left">
                                                            <b>Special Offer Description:</b>
                                                    </td>
                                                    <td>
                                                        {if $offer_desc}
                                                            {$count=1}
															{foreach from=$offer_desc item=data}
																 {$count++}.[{$data->offer}] - {$data->offer_desc}<br/>
															{/foreach}
                                                            
                                                        {else}
                                                                --
                                                        {/if}
                                                    </td>
						</tr>
                                                
                                                 <tr height="25px;">
                                                    <td nowrap="nowrap" width="6%" align="left">
                                                            <b>Application Form:</b>
                                                    </td>
                                                    <td>
                                                        {if $projectDetails[0].APPLICATION_FORM != ''}
															<a href="{$projectDetails[0].APPLICATION_FORM}" target="_blank"/>
																<img src="/images/pdficon_small.gif" />
                                                            </a>    
                                                        {else}
                                                                --
                                                        {/if}
                                                    </td>
						</tr>
                                                <!--<tr height="25px;">
                                                    <td nowrap="nowrap" width="6%" align="left">
                                                            <b> Skip Updation Cycle: </b>
                                                    </td>
                                                    <td>
                                                        {if $projectDetails[0].UPDATION_CYCLE_ID == {$skipUpdationCycle_Id}}
                                                               Yes
                                                        {else}
                                                               No
                                                        {/if}
                                                    </td>
						</tr>-->
                                                
                                                <tr height="25px;">
                                                    <td nowrap="nowrap" width="6%" align="left">
                                                            <b> Skip B2B: </b>
                                                    </td>
                                                    <td>
                                                        {if $projectDetails[0].SKIP_B2B == 0}
                                                               No
                                                        {else}
                                                               Yes
                                                        {/if}
                                                    </td>
						</tr>
                                                
                                                <tr height="25px;">
                                                    <td nowrap="nowrap" width="6%" align="left">
                                                        <b> Price Display On Website: </b>
                                                    </td>
                                                    <td>
                                                        {if $projectDetails[0].SHOULD_DISPLAY_PRICE == 1}
                                                               Yes
                                                        {else}
                                                               No
                                                        {/if}
                                                    </td>
						</tr>
                                                <!-- @Jitendra pathak -->
                                                <tr height="25px;">
                                                    <td nowrap="nowrap" width="6%" align="left">
                                                            <b> Construction Contractor: </b>
                                                    </td>
                                                    <td>
                                                        {if $projectDetails[0].cons_comp != ''}
                                                               {$projectDetails[0].cons_comp}
                                                        {else}
                                                              --
                                                        {/if}
                                                    </td>
						</tr>
                                                <tr height="25px;">
                                                    <td nowrap="nowrap" width="6%" align="left">
                                                            <b> Maintenace Contractor: </b>
                                                    </td>
                                                    <td>
                                                        {if $projectDetails[0].maint_comp != ''}
                                                               {$projectDetails[0].maint_comp}
                                                        {else}
                                                              --
                                                        {/if}
                                                    </td>
						</tr>
                                                <tr height="25px;">
                                                    <td nowrap="nowrap" width="6%" align="left">
                                                            <b> Landscape Architect: </b>
                                                    </td>
                                                    <td>
                                                        {if $projectDetails[0].lands_arch_comp != ''}
                                                               {$projectDetails[0].lands_arch_comp}
                                                        {else}
                                                              --
                                                        {/if}
                                                    </td>
						</tr>
                                                
                                                
                                                
                                                <tr height="25px;">
                                                    <td nowrap="nowrap" width="6%" align="left">
                                                            <b> Power backup: </b>
                                                    </td>
                                                    <td>
                                                        {if $projectDetails[0].power_backup != ''}
                                                               {$projectDetails[0].power_backup}
                                                        {else}
                                                              --
                                                        {/if}
                                                    </td>
						</tr>
                                                <tr height="25px;">
                                                    <td nowrap="nowrap" width="6%" align="left">
                                                            <b> Power Backup Capacity: </b>
                                                    </td>
                                                    <td>
                                                        {if $projectDetails[0].POWER_BACKUP_CAPACITY != ''}
                                                               {$projectDetails[0].POWER_BACKUP_CAPACITY}
                                                        {else}
                                                              --
                                                        {/if}
                                                    </td>
						</tr>
						<tr height="25px;">
                                                    <td nowrap="nowrap" width="6%" align="left">
                                                            <b> Redevelopment Project: </b>
                                                    </td>
                                                    <td>
                                                        {$redevelopment_flag}
                                                    </td>
						</tr>
                                                <tr height="25px;">
                                                    <td nowrap="nowrap" width="6%" align="left">
                                                            <b> IS Smoothed ?: </b>
                                                    </td>
                                                    <td>
                                                        {if $projectDetails[0].IS_SMOOTHED == 0}
                                                               No
                                                        {else}
                                                               Yes
                                                        {/if}
                                                    </td>
						</tr>
                                                
					</table>
				</td>
			</tr>
                        <tr>
                            <td width = "100%" align = "center" colspan = "16" style="padding-left: 30px;">
                                <table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">
                                    <tr>
                                        <td>
                                            <button onclick="download_project_brochure('{$projectId}');">Download Project Brochure</button>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td width = "100%" align = "left" colspan = "16" style="padding-left: 30px;">
                                <button class="clickbutton" onclick="$(this).trigger('event18');">Show All Comments History</button>
                            </td>
                        </tr>
			{if count($projectOldComments)>0}
                        <tr>
                            <td width = "100%" align = "left" colspan = "16" style="padding-left: 30px;">
                                <b>Project Old Remarks:</b>
                                <table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">
                                   {if array_key_exists('projectRemark',$projectOldComments)}
                                    <tr height="25px;">
                                            <td  nowrap="nowrap" width="1%" align="left"><b>Project Entry Remark:</b></td>

                                            <td>
                                                {$projectOldComments['projectRemark']->comment_text}
                                                  &nbsp;<b>By </b>{$projectOldComments['projectRemark']->fname} on {($projectOldComments['projectRemark']->date_time)|date_format:'%b-%y'}
                                          </td>
                                      </tr>
                                     {/if}
                                     {if array_key_exists('callingRemark',$projectOldComments)}
                                      <tr height="25px;">
                                            <td  nowrap="nowrap" width="1%" align="left"><b>Calling Team Remark:</b></td>

                                            <td>
                                                {$projectOldComments['callingRemark']->comment_text}
                                                 &nbsp;<b>By </b>{$projectOldComments['callingRemark']->fname} on {($projectOldComments['callingRemark']->date_time)|date_format:'%b-%y'} 
                                          </td>
                                      </tr>
                                      {/if}
                                      {if array_key_exists('auditRemark',$projectOldComments)}    
                                      <tr height="25px;">
                                            <td  nowrap="nowrap" width="1%" align="left"><b>Audit Team Remark:</b></td>

                                            <td>#######################
                                                {$projectOldComments['auditRemark']->comment_text}
                                                &nbsp;<b>By </b>{$projectOldComments['auditRemark']->fname} on {($projectOldComments['auditRemark']->date_time)|date_format:'%b-%y'}
                                          </td>
                                      </tr>
                                      {/if}

                                      {if array_key_exists('audit2Remark',$projectOldComments)}    
                                      <tr height="25px;">
                                            <td  nowrap="nowrap" width="1%" align="left"><b>Audit Team Remark:</b></td>

                                            <td>
												<b>[{$projectComments['audit2Remark']->status}]</b>&nbsp;
                                                {$projectOldComments['audit2Remark']->comment_text}
                                                &nbsp;<b>By </b>{$projectOldComments['audit2Remark']->fname} on {($projectOldComments['audit2Remark']->date_time)|date_format:'%b-%y'}
                                          </td>
                                      </tr>
                                      {/if}

                                      {if array_key_exists('secondaryAuditRemark',$projectOldComments)}    
                                      <tr height="25px;">
                                            <td  nowrap="nowrap" width="1%" align="left"><b>Secondary Audit Team Remark:</b></td>

                                            <td>
                                                {$projectOldComments['secondaryAuditRemark']->comment_text}
                                                &nbsp;<b>By </b>{$projectOldComments['secondaryAuditRemark']->fname} on {($projectOldComments['secondaryAuditRemark']->date_time)|date_format:'%b-%y'}
                                          </td>
                                      </tr>
                                      {/if}
                                      {if array_key_exists('fieldSurveyRemark',$projectOldComments)}

                                      <tr height="25px;">
                                          <td  nowrap="nowrap" width="1%" align="left"><b>Field Survey Team Remark:</b></td>

                                          <td>
                                            {$projectOldComments['fieldSurveyRemark']->comment_text}
                                            &nbsp;<b>By </b>{$projectOldComments['fieldSurveyRemark']->fname} on {($projectOldComments['fieldSurveyRemark']->date_time)|date_format:'%b-%y'}
                                        </td>
                                      </tr>
                                      {/if}
                                      {if array_key_exists('secondaryRemark',$projectOldComments)}
                                       <tr height="25px;">
                                          <td  nowrap="nowrap" width="1%" align="left"><b>Secondary Calling Team Remark:</b></td>

                                          <td>
                                            {$projectOldComments['secondaryRemark']->comment_text}
                                            &nbsp;<b>By </b>{$projectOldComments['secondaryRemark']->fname} on {($projectOldComments['secondaryRemark']->date_time)|date_format:'%b-%y'} 
                                        </td>
                                      </tr>
                                     {/if}
                                </table>
                            
                            </td>
                        </tr>
                        {/if}
                        <tr>
                            <td width = "100%" align = "left" colspan = "16" style="padding-left: 30px;">
                                <b>Project Remarks:</b>
                                <table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">
                                    <tr height="25px;">
                                        <td  nowrap="nowrap" width="1%" align="left"><b>Project Entry Remark:</b></td>

                                        <td>
                                            {if array_key_exists('projectRemark',$projectComments)}   
                                                {$projectComments['projectRemark']->comment_text}
                                                  &nbsp;<b>By </b>{$projectComments['projectRemark']->fname} on {($projectComments['projectRemark']->date_time)|date_format:'%b-%y'}
                                            {else}
                                                --
                                            {/if}
                                      </td>
                                      </tr>

                                      <tr height="25px;">
                                        <td  nowrap="nowrap" width="1%" align="left"><b>Calling Team Remark:</b></td>

                                        <td>
                                            {if array_key_exists('callingRemark',$projectComments)}
                                                 {$projectComments['callingRemark']->comment_text}
                                                  &nbsp;<b>By </b>{$projectComments['callingRemark']->fname} on {($projectComments['callingRemark']->date_time)|date_format:'%b-%y'}
                                            {else}
                                                   --
                                            {/if}
                                      </td>
                                      </tr>

                                      <tr height="25px;">
                                            <td  nowrap="nowrap" width="1%" align="left"><b>Audit Team Remark:</b></td>

                                            <td>
                                                {if array_key_exists('auditRemark',$projectComments)}
                                                      {$projectComments['auditRemark']->comment_text}
                                                      &nbsp;<b>By </b>{$projectComments['auditRemark']->fname} on {($projectComments['auditRemark']->date_time)|date_format:'%b-%y'}
                                                {else}
                                                       --
                                                {/if}
                                          </td>
                                      </tr>

								

                                      <tr height="25px;">
                                            <td  nowrap="nowrap" width="1%" align="left"><b>Secondary Audit Team Remark:</b></td>

                                            <td>
                                                {if array_key_exists('secondaryAuditRemark',$projectComments)}
                                                      {$projectComments['secondaryAuditRemark']->comment_text}
                                                      &nbsp;<b>By </b>{$projectComments['secondaryAuditRemark']->fname} on {($projectComments['secondaryAuditRemark']->date_time)|date_format:'%b-%y'}
                                                {else}
                                                       --
                                                {/if}
                                          </td>
                                      </tr>
                                      <tr height="25px;">
                                            <td  nowrap="nowrap" width="1%" align="left"><b>Audit2 Team Remark:</b></td>					
                                            <td>
                                                {if array_key_exists('audit2Remark',$projectComments)}
                                                      <b>[{$projectComments['audit2Remark']->status}]</b>&nbsp;
                                                      {$projectComments['audit2Remark']->comment_text}
                                                      &nbsp;<b>By </b>{$projectComments['audit2Remark']->fname} on {($projectComments['audit2Remark']->date_time)|date_format:'%b-%y'}
                                                {else}
                                                       --
                                                {/if}
                                          </td>
                                      </tr>
                                      <tr height="25px;">
                                          <td  nowrap="nowrap" width="1%" align="left"><b>Field Survey Team Remark:</b></td>

                                          <td>
                                            {if array_key_exists('fieldSurveyRemark',$projectComments)}
                                                    {$projectComments['fieldSurveyRemark']->comment_text}
                                                    &nbsp;<b>By </b>{$projectComments['fieldSurveyRemark']->fname} on {($projectComments['fieldSurveyRemark']->date_time)|date_format:'%b-%y'} 
                                            {else}
                                                   --
                                            {/if}
                                        </td>
                                      </tr>

                                      <tr height="25px;">
                                          <td  nowrap="nowrap" width="1%" align="left"><b>Secondary Calling Team Remark:</b></td>

                                          <td>
                                            {if array_key_exists('secondaryRemark',$projectComments)}
                                                      {$projectComments['secondaryRemark']->comment_text}
                                                      &nbsp;<b>By </b>{$projectComments['secondaryRemark']->fname} on {($projectComments['secondaryRemark']->date_time)|date_format:'%b-%y'} 
                                            {else}
                                                   --
                                            {/if}
                                        </td>
                                      </tr>
                                
                                </table>
                            
                            </td>
                        </tr>
			{*code start for calling records primry*}
                        <tr>
                            <td width = "100%" align = "center" colspan = "16" style="padding-left: 30px;">
                                <table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">
                                    <tr>
                                        <td>
                                            <button onclick="show_calling_links('{$projectId}', 'primary');">Show Primary Calling Links</button>
                                            <div id="primary-links"></div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>                         			
			{*end code start for calling records primary*}

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
									<b>Department: </b> {$lastUpdatedDetail['resi_project_amenities']['dept']}</br>
									<b>Name: </b> {$lastUpdatedDetail['resi_project_amenities']['name']}</br>
									<b>last Updated Date: </b> {$lastUpdatedDetail['resi_project_amenities']['ACTION_DATE']}</br></br>
									
								  </td>
							  
							</tr>
						{/if}
                                                {array_search('Club House Area',$AmenitiesArr)}
						{foreach from=$AmenitiesArr key=k item=v} 
						{if $k != 99}
						{if array_key_exists($k,$arrNotninty)}
						<tr height="25px;">
                                                    
							<td nowrap="nowrap" align="left"><b>{$v} :</b></td>
								 <td align ="left" nowrap>
								 
								  {if !in_array($arrNotninty[$k],$AmenitiesArr)}
									 {if count($arrNotninty[$k]) >0} {$arrNotninty[$k]} {else} -- {/if}  
								  {/if}
                                                                  {if $v=='Club House'}
                                                                      <label style="margin-left:20px"><b>Club House Area </b> : </label>
                                                                      {if $clubHouseArea}
									 {if $clubHouseArea >0} {$clubHouseArea} {else} -- {/if}  
                                                                       {/if}
                                                                  {/if}
								  </td>	
							 {/if}
						</tr>
						{/if}
					  {/foreach}
					 
					  {section name=nm start=1 loop=20 step=1}
											
						<tr{if ($smarty.section.nm.index != 1) && (!array_key_exists($smarty.section.nm.index,$arrninty))} style = "display:none;"{/if}>
							<td nowrap="nowrap" width="6%" align="left"><b>Other Amenities:</b></td>
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
									<b>Department: </b> {$lastUpdatedDetail['resi_proj_specification']['dept']}</br>
									<b>Name: </b> {$lastUpdatedDetail['resi_proj_specification']['name']}</br>
									<b>last Updated Date: </b> {$lastUpdatedDetail['resi_proj_specification']['ACTION_DATE']}</br></br>
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
                                                                    {if $arrSpecification[0]['OTHER_SPECIFICATIONS'] != ''}
                                                                        {$arrSpecification[0]['OTHER_SPECIFICATIONS']}
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
					
						
						  	<tr>
							  <td width="20%" align="left">
                                                                <a href="javascript:void(0)" onclick="fetchPlanImages('project','{$projectId}', 'projectPlanImages')"><b>Project Plans</b></a>
                                                                {if in_array($projectDetails[0].PROJECT_PHASE,$arrProjEditPermission)}
                                                                <button style="display:none" id="edit-plan-images" class="clickbutton" onclick="$(this).trigger('event4');">Edit</button>
                                                                {/if}
                                                            </td>
							</tr>
						
						{if count($lastUpdatedDetail['project_plan_images'])>0}
						  <tr bgcolor = "#c2c2c2">
							  <td nowrap="nowrap"  align="left" colspan = "4"><b>Last Updated Detail</b><br></br>
								<b>Department: </b> {$lastUpdatedDetail['project_plan_images']['dept']}</br>
									<b>Name: </b> {$lastUpdatedDetail['project_plan_images']['name']}</br>
									<b>last Updated Date: </b> {$lastUpdatedDetail['project_plan_images']['ACTION_DATE']}</br></br>
							  </td>
							  
						  </tr>
						{/if}
                                                <tr bgcolor='#ffffff' id="projectPlanImages"></tr>
					
					</table>
				</td>
		   </tr>

		  <tr>
				<td width = "100%" align = "center" colspan = "16" style="padding-left: 30px;">
				
					<table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">
					
						
							<tr>
                                                            <td align="left"  nowrap colspan ="4">
                                                                <a href="javascript:void(0)" onclick="fetchPlanImages('property','{$projectId}', 'floorPlanImages')"><b>Floor Plans</b></a>
                                                                {if in_array($projectDetails[0].PROJECT_PHASE,$arrProjEditPermission)}
                                                                <button  style="display:none" id="edit-floor-images"  class="clickbutton" onclick="$(this).trigger('event11');">Edit</button>
                                                                {/if}
                                                            </td>
                                                        </tr>
						
						{if count($lastUpdatedDetail['resi_floor_plans'])>0}
						  <tr bgcolor = "#c2c2c2">
							  <td nowrap="nowrap"  align="left" colspan = "4"><b>Last Updated Detail</b><br></br>
								<b>Department: </b> {$lastUpdatedDetail['resi_floor_plans']['dept']}</br>
									<b>Name: </b> {$lastUpdatedDetail['resi_floor_plans']['name']}</br>
									<b>last Updated Date: </b> {$lastUpdatedDetail['resi_floor_plans']['ACTION_DATE']}</br></br>
								
							  </td>
							  
						  </tr>
						{/if}
                                                <tr bgcolor='#ffffff' id="floorPlanImages"></tr>						 
					</table>
				
				</td>
		   </tr>
		   		   
		   <tr>
				<td width = "100%" align = "center" colspan = "16" style="padding-left: 30px;">
				{*{if is_array($ImageDataListingArrFloor)}*}
					<table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">
					
						{if in_array($projectDetails[0].PROJECT_PHASE,$arrProjEditPermission)}
						<tr>
						  	<td align="left"  nowrap colspan = "9">
						  	<b>Project Price:</b> <button class="clickbutton" onclick="$(this).trigger('event12');">Edit</button>&nbsp;&nbsp;
							<b>Project Configuration:</b> <button class="clickbutton" onclick="$(this).trigger('event7');">Edit</button>
							<br><br>
						  	</td>
						</tr>
						{/if}
                                                <tr>
                                                    <td colspan="16">
                                                       <button onclick="show_project_prices('{$projectId}');">Show Project Prices</button> 
                                                       <div id="show-project-prices">
                                                           
                                                       </div>
                                                    </td>
                                                </tr> 			
						
                {*{/if}*}
                                        </table>
                                </td>
                  </tr>
                   {*code start for calling records secondary*}
                   <tr>
                        <td width = "100%" align = "center" colspan = "16" style="padding-left: 30px;">
                            <table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">
                                <tr>
                                    <td>
                                        <button onclick="show_calling_links('{$projectId}', 'secondary');">Show Secondary Price Broker Calling Detail</button>
                                        {if $projectDetails[0].PROJECT_STAGE == 'secondaryPriceCycle'}
                                            <div id="update-secodary-price" style="display:none">
                                                <br/>
                                                <b>Secondary Price Broker Calling Detail&nbsp&nbsp:</b><button class="clickbutton" onclick="$(this).trigger('event14');">Update Project Secondary Price</button>&nbsp;&nbsp;
                                                <br/>
                                            </div>
                                            
                                        {/if}
                                        <div id="secondary-links"></div>                                    
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>                     
                    
                {*end code start for calling records secondary*}

                <!--code start for all brokers secondary price display-->
                <tr>
                    <td align ="left" valign ="top" colspan="2"  style = "padding-left:30px;"><b>Secondary price Configuration Effective Date: </b>{$maxEffectiveDt}</td>
                    <td align ="left">&nbsp;</td>
                </tr>
                
                {if $projectDetails[0].PROJECT_STAGE == 'SecondaryPriceCycle'}
                    <tr>
                       <td align ="left" valign ="top" colspan="2"  style = "padding-left:30px;">
                           <button class="clickbutton" onclick="$(this).trigger('event15');">Update Secondary Price</button>&nbsp;&nbsp;
                           <button class="clickbutton" onclick="$(this).trigger('event16');">Edit Secondary Price</button>
                       </td>
                       <td align ="left">&nbsp;</td>
                   </tr>
                 {/if}

                <tr>
                 <td align ="left" valign ="top" colspan="2"  style = "padding-left:30px;">
                        <table align="left" style = "border:1px solid;">
                            <tr class ="headingrowcolor">
                                <td colspan="5">&nbsp;</td>
                                <td colspan="{count($brokerIdList)}" align ="center" class ="whiteTxt"><b>Brokers</b></td>
                                <td colspan="3">&nbsp;</td>
                            </tr>
                            <tr class ="headingrowcolor" height="30px">
								<th class ="whiteTxt" align = "left"><b>Phase Name</b></th>
                                <th class ="whiteTxt" align = "left"><b>S.NO.</b></th>
                                 <th style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>Unit Type</b></th>
                                 <th nowrap style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>Min Price</b></th>
                                 <th nowrap style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>Max Price</b></th>
                                 <th nowrap style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>Mean</b></th>
                                 {foreach from = $brokerIdList key=brokerkey item = brokerId}
                                    <th nowrap style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>{$allBrokerByProject[$brokerId][0]['BROKER_NAME']}</b></th>
                                 {/foreach}
                                    <th nowrap style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>Price as on {$oneMonthAgoDt}</b></th>
                                 <th nowrap style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>Price as on {$twoMonthAgoDt}</b></th>
                            </tr>
            			{foreach from=$phase_prices key=phase_name item = phase_values}		
                            {$cnt = 0}
                            {foreach from= $arrPType key=k item = val}
                              
                                {if $cnt%2 == 0}
                                    {$bgcolor = '#F7F7F7'}
                                {else}
                                    {$bgcolor = '#FCFCFC'}
                                {/if}
                           {if isset($phase_values['latestMonthAllBrokerPrice'][$val])}
								  {$cnt = $cnt+1}
                                <tr bgcolor = "{$bgcolor}" height="30px">
								
									 <td valign ="top" align = "center">{if $cnt == 1}{$phase_name}{/if}</td>
                                   <td valign ="top" align = "center">{$cnt}</td>
                                   <td valign ="top" style ="padding-left: 10px;" align = "left">
                                       {$val}
                                   </td>
                                   <td  valign ="top" style ="padding-left: 10px;" align = "left">
                                       {min($phase_values['minMaxSum'][$val]['minPrice'])|string_format:"%d"}
                                   </td>
                                   <td  valign ="top" style ="padding-left: 10px;" align = "left">
                                        {max($phase_values['minMaxSum'][$val]['maxPrice'])|string_format:"%d"}
                                   </td>
                                   <td  valign ="top" style ="padding-left: 10px;" align = "left">
                                       {$arrCnt = count($phase_values['minMaxSum'][$val]['minPrice'])+count($phase_values['minMaxSum'][$val]['maxPrice'])}
                                       {$arrSum = array_sum($phase_values['minMaxSum'][$val]['minPrice'])+array_sum($phase_values['minMaxSum'][$val]['maxPrice'])}
                                       {($arrSum/$arrCnt)|string_format:"%d"}
                                   </td>
                                    {foreach from = $brokerIdList key=brokerkey item = brokerId}
										<td  valign ="top" style ="padding-left: 10px;" align = "left">
											{$phase_values['latestMonthAllBrokerPrice'][$val][$brokerId]['minPrice']|string_format:"%d"} - {$phase_values['latestMonthAllBrokerPrice'][$val][$brokerId]['maxPrice']|string_format:"%d"}
										</td>
                                    {/foreach}
                                   <td  valign ="top" style ="padding-left: 10px;" align = "left">
                                       {$arrCnt = count($phase_values['oneMonthAgoPrice'][$val]['minPrice'])+count($phase_values['oneMonthAgoPrice'][$val]['maxPrice'])}
                                       {$arrSumOneMonthAgo = array_sum($phase_values['oneMonthAgoPrice'][$val]['minPrice'])+array_sum($phase_values['oneMonthAgoPrice'][$val]['maxPrice'])}
                                       {($arrSumOneMonthAgo/$arrCnt)|string_format:"%d"}
                                   </td>
                                   <td  valign ="top" style ="padding-left: 10px;" align = "left">
                                       {$arrCnt = count($phase_values['twoMonthAgoPrice'][$val]['minPrice'])+count($phase_values['twoMonthAgoPrice'][$val]['maxPrice'])}
                                       {$arrSumTwoMonthAgo = array_sum($phase_values['twoMonthAgoPrice'][$val]['minPrice'])+array_sum($phase_values['twoMonthAgoPrice'][$val]['maxPrice'])}
                                       {($arrSumTwoMonthAgo/$arrCnt)|string_format:"%d"}
                                   </td>
                               </tr>
                               {/if}
                            {/foreach}
                         {/foreach}
                        </table>
                   </td>
                 </tr>
                <!--end code for all brokers secondary price display-->
			
		   <tr>
				<td width = "100%" align = "center" colspan = "16" style="padding-left: 30px;">
				{*{if is_array($ImageDataListingArrFloor)}*}
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
									
									<b>Department: </b> {$item['dept']}</br>
									<b>Name: </b> {$item['name']}</br>
									<b>last Updated Date: </b> {$item['ACTION_DATE']}</br></br>
								{/foreach}	
								
							  </td>
							  
						  </tr>
						{/if}
						
						{if count($towerDetail)>0}
							{$flatChk = 0}
							{$flatAvailChk = 0}
								<tr class="headingrowcolor" height="30px;">
									<td class="whiteTxt" align = "left" nowrap><b>SNO.</b></td>
									<td class="whiteTxt" align = "center" nowrap><b>Phase Name</b></td>
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
                                                                                    <TD>{$keyInner+1}</TD>
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
											{if $innerItem['STILT'] == 'True'} Yes {/if}
											{if $innerItem['STILT'] == 'False'} No {/if}
										</td>
										<td align = "center">{$innerItem['ACTUAL_COMPLETION_DATE']}</td>
											
											
									</tr>		
									{/foreach}	
									
									<tr height ="30px" bgcolor="#F6D8CE">
										<td colspan ="4" align ="right"><b>Sub Total {$key} </b></b></td>
										<td  align = "center" nowrap><b>{$phaseWiseTotalFlats}</b></td>
									<td align = "center" nowrap><b></b></td>
									<td  align = "center" nowrap><b></b></td>
									<td  align = "center" nowrap><b></b></td>
									<td  align = "center" nowrap><b></b></td>
									</tr>		 
								</tr>
								{/foreach}
								
									<tr height ="30px" bgcolor="#F7F8E0">
										<td colspan ="4" align ="right"><b>Grand Total </b></b></td>
										<td  align = "center" nowrap><b>{$grandTotalFlats}</b></td>
									<td align = "center" nowrap><b></b></td>
									<td  align = "center" nowrap><b></b></td>
									<td  align = "center" nowrap><b></b></td>
									<td  align = "center" nowrap><b></b></td>
									</tr>
						{/if}
						  
					</table>
				{*{/if}*}
				</td>
		   </tr>
		   
		   <tr>
				<td width = "100%" align = "center" colspan = "16" style="padding-left: 30px;">
				{*{if is_array($ImageDataListingArrFloor)}*}
					<table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">
						 {if in_array($projectDetails[0].PROJECT_PHASE,$arrProjEditPermission)}
							<tr>
							  	<td align="left"  nowrap><b>Project Other Price: <button class="clickbutton" onclick="$(this).trigger('event13');">Edit</button></td>
							</tr>
						{/if}
						{if array_key_exists('resi_project_other_pricing',$lastUpdatedDetail)}
						  <tr bgcolor = "#c2c2c2">
							  <td nowrap="nowrap"  align="left" colspan = "3"><b>Last Updated Detail</b><br></br>
								<b>Department: </b> {$lastUpdatedDetail['resi_project_other_pricing']['dept']}</br>
									<b>Name: </b> {$lastUpdatedDetail['resi_project_other_pricing']['name']}</br>
									<b>last Updated Date: </b> {$lastUpdatedDetail['resi_project_other_pricing']['ACTION_DATE']}</br></br>
								
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
									{if $otherPricing[0]['OTHER_PRICING'] != ''}{$otherPricing[0]['OTHER_PRICING']}{else} -- {/if}
								</td>
								<td>&nbsp;</td>
								
							</tr>
						</table>
				{*{/if}*}
				</td>
		   </tr>
		   <tr>
				<td width = "100%" align = "center" colspan = "16" style="padding-left: 30px;">
                                    <table width="100%" align="center" style="border:1px solid #c2c2c2;">
                                        <tr>
                                            <td>
                                                <button onclick="show_project_supplies('{$projectId}', '{$projectDetails[0].PROJECT_PHASE}', '{$isSupplyLaunchVerified}');">Show Project Supplies</button> 
                                                <div id="show-project-supplies"></div>                                                
                                            </td>
                                        </tr>                                        
                                    </table> 
                                </td>
                                    
                   </tr>                             
		   <tr><td colspan ="16">&nbsp;</td><tr>
		   <tr class="headingrowcolor" height="30px;">
			<td class="whiteTxt" colspan = "16" align ="center">
				<form method = "post" action = "">
					<input type = "hidden" name = "projectId" id = "projectId" value = "{$projectId}">
					<input type="submit" name="btnExit" id="btnExit" value="Exit">
			    </form>
			</td>
		</tr>
</div>