<script type="text/javascript" src="javascript/jquery.js"></script>

 <SCRIPT language=Javascript>

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
		  if (resp[0] === "call") {
		      $('#callId_'+id).val(resp[1]);
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
      var callId = $('#callId_'+id).val();
      if (status === "success")
	  projectList = projectList.join(",");
      else 
	  projectList = "";
      
      if (callId) {
	  $.ajax({
	      type:"get",
	      url:"save_call_projects.php",
	      data:"projectList="+projectList+"&callId="+callId+"&status="+status,
	      success : function (dt) {
		  alert("Saved Status as " + status + " with project Ids " + projectList);
	      }
	  });
      }
      else 
	  alert("Please call before setting disposition");
  }
</SCRIPT>


 <table cellSpacing=0 cellPadding=0 width="50%" style = "border:1px solid #BDBDBD;" align= "center">
   <tr><td>&nbsp;</td></tr>      
  <TR>
    <TD style = "padding-left:20px;" align = "left" nowrap colspan = "6"><b>Builder Contact Information({$builderName})</b></TD>
    
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
      

  <tr class = "headingrowcolor">
   
    <td style = "padding-left:20px;" nowrap="nowrap" width="1%" align="center"class=whiteTxt>SNo.</td>
    <td style = "padding-left:20px;" nowrap="nowrap" width="2%" align="left" class=whiteTxt>Contact Name</td>
    <td style = "padding-left:20px;" nowrap="nowrap" width="3%" align="left" class=whiteTxt>Phone</td>
    <td style = "padding-left:20px;" nowrap="nowrap" width="3%" align="left" class=whiteTxt>Click To Call</td>
    <td style = "padding-left:20px;" nowrap="nowrap" width="3%" align="left" class=whiteTxt> Campaign Name </td>
    <td style = "padding-left:20px;" nowrap="nowrap" width="3%" align="left" class=whiteTxt> Select Projects for Call </td>
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
                          <option value = "{$item['PROJECT_ID']}" {if strstr($arrContact[$cnt]['PROJECTS'],$item['PROJECT_ID'])} selected {/if}>{$item['PROJECT_NAME']}</option>
                        {/foreach}
                        </option>
                      </select>
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
                          <option value = "{$item['PROJECT_ID']}" {if strstr($arrContact[$cnt]['PROJECTS'],$item['PROJECT_ID'])} selected {/if}>{$item['PROJECT_NAME']}</option>
                        {/foreach}
                        </option>
                      </select>
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
      <td align="right" nowrap  colspan= "6">
       <input type="hidden" name="projectId" value="{$projectId}" id ="projectId"/>
      
       <input type="button" name="btnSave" id="btnSave" value="Save" onclick = "return chkConfirm({count($arrContact)});" />
      
     </td>
  </tr>
</table>

