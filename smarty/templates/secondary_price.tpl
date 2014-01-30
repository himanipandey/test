<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

<script type="text/javascript">

function builder_contact(builderId,buildernm)
{
	//code for builder contact info popup
        var url = "builder_contact_info.php?builderId="+builderId+"&builderName="+buildernm;
        $.fancybox({
            'href' :  url
           });

}

  function clickToCall(obj) {
      var id = $(obj).attr('id').split('_')[1];
      var phId = 'phone_' + id;
      var phNo = $('#'+phId).html(); 
      var compgnId = 'campaignName_'+id;
      var campaign = $("#"+compgnId).val();
      if(campaign == 'Select'){
		alert("Please select Campaign!");
		return;
	  }
	  if(phNo.toString().trim().charAt(0)!=='0')phNo = '0'+phNo;
      $.ajax(
	  {
	      type:"get",
	      url:"call_contact.php",
	      data:"contactNo="+phNo+"&campaign="+campaign+"&projectType=secondary",
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
      var brokerId = $('#brokerId_'+id).val();
      if (status === "success")
	  projectList = projectList.join(",");
      else 
	  projectList = "";
      
      if (callId) {
	  $.ajax({
	      type:"get",
	      url:"save_call_projects.php",
	      data:"projectList="+projectList+"&callId="+callId+"&status="+status+"&remark="+projectRemark+"&brokerId="+brokerId,
	      success : function (dt) {
		  alert("Saved Status as " + status + " with project Ids " + projectList);
	      }
	  });
      }
      else 
	  alert("Please call before setting disposition");
  }
/*********builder contact info related js end here*************/

</script>
{$error}
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
                              <TD class="h1" width="67%"><img height="18" hspace="5" src="images/arrow.gif" width="18">
                                  Secondary Price Update Screen
                              </TD>
                              <TD width="33%" align ="right"></TD>
                            </TR>
                        </TBODY>
                  </TABLE>
                </TD>
	      </TR> 
	      <TD vAlign="top" align="middle" class="backgorund-rt" height="450"><BR>	 
            <table cellSpacing="1" cellPadding="4" width="67%" align="center" style="border:1px solid;">
	      <tr>
                  <td colspan="2" style = "padding-left:30px;" align = "left">	
                      <div style="margin-top:10px;margin-bottom:10px">
                          <div>
                                <a style = " display: block;width: 100px; height: 15px;background: #c2c2c2; padding: 5px; text-align: center; border: 1px;border-radius: 5px;text-decoration: none;color: black;
                font-weight: bold;" onclick="window.open('assign_broker.php?projectId={$projectDetails->project_id}&projectName={$projectDetails->project_name}&cityId=','Assign Broker','height=600,width=750,left=300,top=100,resizable=yes,scrollbars=yes, status=yes');return false;" href="#"><b>&nbsp;&nbsp;Assign Broker&nbsp;&nbsp;</b></a>
                                        </div>

                                        <div style="float: left;  margin-left: 294px;  margin-top: -25px;">
                                <a style = "display: block;width: 100px; height: 15px;background: #c2c2c2; padding: 5px; text-align: center; border: 1px;border-radius: 5px;text-decoration: none;color: black;
                font-weight: bold;" href ="show_project_details.php?projectId={$projectDetails->project_id}"><b>&nbsp;&nbsp;Projct Detail&nbsp;&nbsp;</b></a>
                           </div>
                      </div>
                </td>
              </tr>
              
              <tr>
                  <td colspan="2" align = "left" style = "padding-left:20px;">
                      <table align = "left">
                          <tr style="height:25px;">
                              <td align ="left"><b>Project Name:</b></td>
                              <td align ="left">{ucwords($projectDetails->project_name)}</td>
                          </tr>
                          <tr style="height:25px;">
                              <td align ="left"><b>Builder Name:</b></td>
                              <td align ="left">{$builderName}</td>
                          </tr>
                          <tr>
                              <td align ="left" style="height:25px;"><b>City:</b></td>
                                
                              <td align ="left">{$cityName}</td>
                          </tr>
                          <tr>
                              <td align ="left" style="height:25px;"><b>Locality:</b></td>
                              <td align ="left">{$localityName}</td>
                          </tr>               
                      </table>
                  </td>
              </tr>
              <tr>
                <td align ="left" valign ="top" colspan="2"  style = "padding-left:20px;"><b>Brokers:</b></td>
                <td align ="left">&nbsp;</td>
              </tr>
              <tr>
                  <td align ="left" valign ="top" colspan="2"  style = "padding-left:20px;">
                        <table align="center" style = "border:1px solid;">
                            <tr class ="headingrowcolor" height="30px">
                                <th class ="whiteTxt" align = "left"><b>S.NO.</b></th>
                                 <th style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>Broker Name</b></th>
                                 <th nowrap style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>Contact Person Name</b></th>
                                 <th nowrap style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>Click To Call</b></th>
                                 <th nowrap style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>Campaign Name</b></th>
                                 <th nowrap style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>Select Projects for Call</b></th>
                                 <th nowrap style ="padding-left: 10px;" class ="whiteTxt" align = "center"><b>Remark</b></th>
                                 <th nowrap style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>Success / Fail</b></th>
                                 <th nowrap style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>Contact E-mail</b></th>
                                 <th nowrap style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>Contact Mobile Number</b></th>
                                 <th nowrap style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>Broker Address</b></th>
                            </tr>
                            <form name ="frm" method = "post">
                            {$cnt = 0}
                            {$totalRow = count($allBrokerByProject)}
                            {foreach from= $allBrokerByProject key=k item = val}
                                {$cnt = $cnt+1}
                                {if $cnt%2 == 0}
                                    {$bgcolor = '#F7F7F7'}
                                {else}
                                    {$bgcolor = '#FCFCFC'}
                                {/if}
                                <tr bgcolor = "{$bgcolor}" height="30px">
                                   <td valign ="top" align = "center">{$cnt}</td>
                                   <td valign ="top" style ="padding-left: 10px;" align = "left">{$val[0]['BROKER_NAME']}</td>
                                   <td  valign ="top" style ="padding-left: 10px;" align = "left">{$val[0]['CONTACT_NAME']}</td>
                                   <td  valign ="top" style ="padding-left: 10px;" align = "left">
                                       <a onclick="clickToCall(this);" style="width:120px" class="c2c" id="c2c_{$cnt}" href="javascript:void(0);"> Click To Call </a>
                                   </td>
                                   <input type ="hidden" name = "brokerId[]" id = "brokerId_{$cnt}" value ="{$k}">
                                   <td  valign ="top" style ="padding-left: 10px;" align = "left">
                                       <select name="campaignName" id="campaignName_{$cnt}">
                                        {foreach from = $arrCampaign item=item}
                                        <option value={$item}> {$item} </option>
                                        {/foreach}
                                       </select>
                                   </td>
                                   <td  valign ="top" style ="padding-left: 10px;" align = "left">
                                       <select multiple="" id="projects_call_{$cnt}" name="projects_call_[]">
                                            <option value="">Select Project</option>
                                            {foreach from = $arrProjectByBroker[$k] key=key item = value}
                                                <option value="{$value['PROJECT_ID']}">{$value['PROJECT_NAME']}</option>
                                            {/foreach}
                                       </select>
                                   </td>
                                   <td  valign ="top" style ="padding-left: 10px;" align = "left">
                                       <textarea id="remark_call_{$cnt}" name="remark_call_[]"></textarea>
                                   </td>
                                   
                                   <td  valign ="top" style ="padding-left: 10px;" align = "left">
                                       <input type="hidden" name="callId[]" id="callId_{$cnt}" value="">
                                        <a href="javascript:void(0);" id = "success_{$cnt}" onclick="setStatus(this);"> Success </a> ||
                                        <a href="javascript:void(0);" id = "fail_{$cnt}" onclick="setStatus(this);"> Fail </a>
                                   </td>
                                   <td  valign ="top" style ="padding-left: 10px;" align = "left">{$val[0]['BROKER_EMAIL']}</td>
                                   <td  valign ="top" style ="padding-left: 10px;" align = "left"><span id = "phone_{$cnt}">{$val[0]['BROKER_MOBILE']}</span></td>
                                   <td  valign ="top" style ="padding-left: 10px;" align = "left">{$val[0]['BROKER_ADDRESS']}</td>
                               </tr>
                            {/foreach}
                        </table>
                   </td>
                </tr>
              
                <!--code for broker calling detail-->
                {if $projectDetails->project_stage_id == 8}
                    {if count($arrCalingSecondary)>0}
                        <tr>
                            <td align ="left" valign ="top" colspan="2"  style = "padding-left:20px;"><b>Broker Calling Detail:</b></td>
                            <td align ="left">&nbsp;</td>
                        </tr>
                        <tr>
                            <td align ="left" valign ="top" colspan="2"  style = "padding-left:20px;">
                                <table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">

                                    <tr class="headingrowcolor" height="30px;">
                                        <td  nowrap="nowrap" width="10%" align="center" class=whiteTxt >SNo.</td>
                                        <td  nowrap="nowrap" width="10%" align="center" class=whiteTxt >Broker Name</td>
                                        <td  nowrap="nowrap" width="10%" align="left" class=whiteTxt >Caller Name</td>
                                        <td  nowrap="nowrap" width="10%" align="left" class=whiteTxt >Start Time</td>
                                        <td  nowrap="nowrap" width="10%" align="left" class=whiteTxt >End Time</td>
                                        <td  nowrap="nowrap" width="10%" align="center" class=whiteTxt >Audio Link</td>
                                        <td nowrap="nowrap" width="90%" align="left" class=whiteTxt>Remark</td>
                                        <td nowrap="nowrap" width="90%" align="left" class=whiteTxt>Add More Project</td>
                                    </tr>
                                    {foreach from = $arrCalingSecondary key = key item = item}
                                        {if ($key+1)%2 == 0}
                                              {$color = "bgcolor='#F7F8E0'"}
                                        {else}
                                                {$color = "bgcolor='#f2f2f2'"}
                                        {/if}
                                    <tr {$color} height="25px;">
                                        <td nowrap="nowrap" width="10%" align="center">
                                                {$key+1}
                                        </td>
                                        <td nowrap="nowrap" width="10%" align="center">
                                                {$brokerDetail = getBrokerDetailById($item['BROKER_ID'])}
                                                {$brokerDetail[0]['BROKER_NAME']}
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
                                        <td width ="90%">
                                           <a href="addMoreProjectCall.php?callId={$item['CallId']}&brokerId={$item['BROKER_ID']}&projectId={$projectId}">
                                             AddMore</a>
                                        </td>
                                    </tr>
                                    {/foreach}
                                </table>
                            </td>
                            <td align ="left">&nbsp;</td>
                        </tr>
                   {/if}
                 {/if}
                <!--end code for broker calling detail-->
              <!--code start for all brokers secondary price display-->
                <tr>
                    <td align ="left" valign ="top" colspan="2"  style = "padding-left:20px;"><b>Configuration Effective Date:</b>&nbsp; {$maxEffectiveDt}</td>
                    <td align ="left">&nbsp;</td>
                </tr>
               
                <tr>
                    <td align ="left" valign ="top" colspan="2"  style = "padding-left:20px;">
                        <div style="margin-top:10px;margin-bottom:10px">
                            <div><a style = "display: block;width: 200px; height: 15px;background: #c2c2c2; padding: 5px; text-align: center; border: 1px;border-radius: 5px;text-decoration: none;color: black;
  font-weight: bold;" href = "insertSecondaryPrice.php?projectId={$projectDetails->project_id}"><b>Update Secondary Price</b></a></div>
                            <div style="float: left;  margin-left: 294px;  margin-top: -25px;"><a style = "display: block;width: 200px; height: 15px;background: #c2c2c2; padding: 5px; text-align: center; border: 1px;border-radius: 5px;text-decoration: none;color: black;
  font-weight: bold;" href = "updateSecondaryPrice.php?projectId={$projectDetails->project_id}"><b>Edit Secondary Price</b></a></div>
                        </div>
                    </td>
                    <td align ="left">&nbsp;</td>
               </tr>
               
                <tr>
                  <td align ="left" valign ="top" colspan="2"  style = "padding-left:20px;">
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
                            <form name ="frm" method = "post">
						{foreach from=$phase_prices key=phase_name item = phase_values}		
                            {$cnt = 0}
                            {foreach from= $arrPType key=k item = val}
                                {$cnt = $cnt+1}
                                {if $cnt%2 == 0}
                                    {$bgcolor = '#F7F7F7'}
                                {else}
                                    {$bgcolor = '#FCFCFC'}
                                {/if}
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
                            {/foreach}
                         {/foreach}
                        </table>
                   </td>
                 </tr>
                 
                <!--end code for all brokers secondary price display-->
                <tr><td colspan ="8">&nbsp;</td><tr>
                <tr class="headingrowcolor" height="30px;">
                     <td class="whiteTxt" colspan = "8" align ="center">
                             <form method = "post" action = "">
                                     <input type = "hidden" name = "projectId" id = "projectId" value = "{$projectId}">
                                     <input type="submit" name="btnExit" id="btnExit" value="Exit">
                         </form>
                     </td>
             </tr>
            </table>
</div>
