<script type="text/javascript" src="javascript/jquery.js"></script>
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>

<script>
	/*******function for deletion confirmation***********/
 function chkConfirm(TotRow) 
  {
    var chk = 0;
    var lp_select = TotRow;
    if($("#f_date_c_to").val() == '')
    {
    	alert("Please select effective date!");
    	$("#f_date_c_to").focus();
    	return false;
    }
    for(var i=1;i<=lp_select;i++)
    {      

        var noOfFlats   =  "noOfFlats_"+i;
        var no_of_floor =  "no_of_floor_"+i;
        var isFlats     =  "isFlats_"+i;
        var soi         =  "soi_"+i;
        if($("#"+noOfFlats).val() == '')
        {
            alert("Number of flats cant blank!");
            $("#"+noOfFlats).focus();
            return false;

        }
        else if($("#"+isFlats).val() == '')
        {

            alert("Please choose Is flats Information is Currect!");
            $("#"+isFlats).focus();
            return false;
        }
        else if($("#"+soi).val() == '')
        {

            alert("Please choose Source Of Information!");
            $("#"+soi).focus();
            return false;
        }
        else
        {
          if($("#"+i).attr('checked'))
          {
            chk = 1;
          }
        }
    }
    
    var newBedRoom = $("#newBedId").val();
   
    if(newBedRoom != '')
    {
    	var newNoOfFlats = $("#newNoOfFlats").val();
    	if(newNoOfFlats == '')
    	{
    		alert("Please enter No Of Flats in new supply!");
    		$("#newNoOfFlats").focus();
    		return false;
    	}
    	
    	var newIsFlats = $("#newIsFlats").val();
    	if(newAvailFlats == '')
    	{
    		alert("Please choose atleast one choice in new supply!");
    		$("#newIsFlats").focus();
    		return false;
    	}
    	
    	var newSoi = $("#newSoi").val();
    	if(newAvailFlats == '')
    	{
    		alert(" Please choose source of informtaion in new supply!");
    		$("#newSoi").focus();
    		return false;
    	}
    }
    if(chk == 1)
      return confirm("Are you sure! you want to delete records which are checked.");
    else
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
						  <TD class="h1" width="67%"><img height="18" hspace="5" src="images/arrow.gif" width="18">Supply({$ProjectDetail[0]['BUILDER_NAME']} {$ProjectDetail[0]['PROJECT_NAME']})</TD>
						  <TD width="33%" align ="right"></TD>

						</TR>
					</TBODY>
				  </TABLE>
				</TD>
	      </TR>
		  <tr></tr>
			<TD vAlign="top" align="middle" class="backgorund-rt" height="450"><BR>

				<table cellSpacing="1" cellPadding="4" width="67%" align="center" border="0">
					<form method="post" name='frm' id="formss" enctype="multipart/form-data" action = ''>
				    	
						<tr>
							<td>
								
								  <table align = "center" width = "90%">
									   <tr>
										  <td width="20%" align="left"><b>Last Updated Date :</b>
											 {if $arrAudit != ''} 
											 	{$arrAudit}
											 {else}
											 	--
											 {/if}	
											 &nbsp;&nbsp;
											<b> Effective Date:</b>
											 <input name="eff_date_to" value="" type="text"
												 class="f_date_c_to" id="f_date_c_to"
												   value="" size="10"  style="width:150px;"/> 
											 <img src="images/cal_1.jpg" id="f_trigger_c_to" 
											 	style="cursor: pointer; border: 1px solid red;" title="Date selector"
											 	 onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
											 	 
											 <input type = "hidden" name = "old_date" value = "{$submittedDate}">
											<script type="text/javascript">
											   Calendar.setup({
											
											    inputField     :    "f_date_c_to",     // id of the input field
												ifFormat       :    "%Y-%m-%d",      // format of the input field
											    button         :    "f_trigger_c_to",  // trigger for the calendar (button ID)
											    align          :    "Tl",           // alignment (defaults to "Bl")
											    singleClick    :    true,
											    showsTime		:	true
											  });
											</script>
										&nbsp;&nbsp;
										<b>Last Effective Date:</b>
											<input name="" value="{$submittedDate}" type="text"
												 class="formstyle2"  readonly="1" size="10"  style="width:150px;"/> 
										  </td>
										 
									   </tr>
								   </table>
							  
								</td>
						<tr>

					
						
						<tr>
							<td width = "100%" align = "center" colspan = "16" style="padding-left: 30px;">
							
								<table align = "center" width = "100%" style = "border:1px solid #c2c2c2;">
										<tr class="headingrowcolor" height="30px;">
											<td class="whiteTxt" align = "center" nowrap><b>SNO.</b></td>
											<td class="whiteTxt" align = "center" nowrap><b>Phase</b></td>
											<td class="whiteTxt" align = "center" nowrap><b>Project Type</b></td>
											<td class="whiteTxt" align = "center" nowrap><b>Unit Type</b></td>
											
											<td class="whiteTxt" align = "center" nowrap><b>No of Flats</b></td>
											<td class="whiteTxt" align = "center" nowrap><b>Is flats Information is Currect</b></td>
											<td class="whiteTxt" align = "center" nowrap><b>Available No of Flats</b></td>
											<td class="whiteTxt" align = "center" nowrap><b>Is Available Flat Information is Currect</b></td>
											<td class="whiteTxt" align = "center" nowrap><b>Edit Reason</b></td>
											<td class="whiteTxt" align = "center" nowrap><b>Source Of Information</b></td>
											<td class="whiteTxt" align = "center" nowrap><b>Delete</b></td>
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
													
													<tr {$color} >
														<td valign ="top" align="center">{$cnt}</td>
														
														{if $olderValuePhase == '' || $olderValuePhase != $key}
															<td valign ="top" align = "center" nowrap rowspan = "{count($arrPhaseCount[$key])+1}">
																{ucfirst($key)}
															</td>
														{/if}
														<input type = "hidden" name = "phaseId[]" value = "{$lastItem['PHASE_ID']}">
													
														{$olderValuePhase = $key}
													
														{if $olderValueType != $keyInner || $olderValueType == ''}
														<td valign ="top" align = "center" rowspan = "{count($arrPhaseTypeCount[$key][$keyInner])}">
															{$keyInner}
														</td>
														{/if}
														<input type = "hidden" name = "projectType[]" value = "{$lastItem['PROJECT_TYPE']}">
														{$olderValueType = $keyInner}
													
													<td valign ="top" align="center">
													{$lastItem['NO_OF_BEDROOMS']}BHK
													<input type = "hidden" name = "configs[]" value = "{$lastItem['NO_OF_BEDROOMS']}">
													</td>
													<td valign ="top" align="center" >
														<input style = "width:63px;" type = "text" name = "noOfFlats[]" 
															value = "{$lastItem['NO_OF_FLATS']}" id ="noOfFlats_{$cnt}">
														<input type = "hidden" name = "old_noOfFlats[]" value = "{$lastItem['NO_OF_FLATS']}">
														{$totalNoOfFlatsPtype = $totalNoOfFlatsPtype+$lastItem['NO_OF_FLATS']}
														{$totalNoOfFlatsPPhase = $totalNoOfFlatsPPhase+$lastItem['NO_OF_FLATS']}
														{if $key != 'noPhase'}
															{$totalSumFlat = $totalSumFlat+$lastItem['NO_OF_FLATS']}
															{$totalSumflatAvail = $totalSumflatAvail+$lastItem['AVAILABLE_NO_FLATS']}
														{/if}
													</td>
													<td valign ="top" align="center">
														 <select name="isFlats[]" id = "isFlats_{$cnt}">
														  <option value="">Choose atleast One</option>
														  <option value="1" {if $lastItem['ACCURATE_NO_OF_FLATS_FLAG'] == 1} selected {/if}>Accurate</option>
														  <option value="0" {if $lastItem['ACCURATE_NO_OF_FLATS_FLAG'] == 0} selected {/if}>Guessed</option>
														 </select>	
														 <input type = "hidden" name = "old_isFlats[]" value = "{$lastItem['ACCURATE_NO_OF_FLATS_FLAG']}">
													</td>
													<td valign ="top" align="center">
														<input style = "width:63px;" type="text" value="{$lastItem['AVAILABLE_NO_FLATS']}" name="AvilFlatId[]" 
															class="AvilFlatId" id="AvilFlatId_{$cnt}"/>
													    <input type = "hidden" name = "old_AvilFlatId[]" value = "{$lastItem['AVAILABLE_NO_FLATS']}">
														{$availableoOfFlatsPtype = $availableoOfFlatsPtype+$lastItem['AVAILABLE_NO_FLATS']}
														{$availableoOfFlatsPPhase = $availableoOfFlatsPPhase+$lastItem['AVAILABLE_NO_FLATS']}
																									
													</td>
													
													<td valign ="top" align="center">
														<select name="avilflats[]" id ="avilflats_{$cnt}" class="avilflats">
														  <option value="">Choose atleast One</option>
														  <option value="1" {if $lastItem['ACCURATE_AVAILABLE_NO_OF_FLATS_FLAG'] == 1} selected {/if}>Accurate</option>
														  <option value="0" {if $lastItem['ACCURATE_AVAILABLE_NO_OF_FLATS_FLAG'] == 0} selected {/if}>Guessed</option>
													    </select>
													    <input type = "hidden" name = "old_avilflats[]" value = "{$lastItem['ACCURATE_AVAILABLE_NO_OF_FLATS_FLAG']}">
													</td>
													<td valign ="top" align="center">
													 	<textarea name="edit_reason[]" rows="2" cols="20" id="texta_{$cnt}">{$supply_bed[0]['EDIT_REASON']}</textarea>
													</td>
													<td valign ="top" align ="center">
														<select name="soi[]" id = "soi_{$cnt}">
															<option value="">Choose Source</option>
															{foreach from=$source_of_information key=k item=v}
															<option value = "{$v['SOURCE_NAME']}" {if $lastItem['SOURCE_OF_INFORMATION'] == $v['SOURCE_NAME']}  selected {/if}>{$v['SOURCE_NAME']}</option>
															{/foreach}
														  </select>
														<input type = "hidden" name = "old_soi[]" value = "{$lastItem['SOURCE_OF_INFORMATION']}">
													</td>
													
													<td valign ="top" align ="center" nowrap>
														<input type="checkbox" name="delete_{$cnt}" id = "{$cnt}">
													</td>
												</tr>
												
													<input type = "hidden" name = "supplyId[]" value = "{$lastItem['PROJ_SUPPLY_ID']}">
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
													<td  align ="left" >&nbsp;</td>
													<td  align ="left" >&nbsp;</td>
													<td  align ="left" >&nbsp;</td>
													<td  align ="left" >&nbsp;</td>
													
												</tr>			 
										{/foreach}
												<tr bgcolor ="#F2F2F2" height="30px;">
													<td align ="right" colspan ="4" nowrap><b>Grand Total</b></td>
													<td align ="center"><b> {$totalSumFlat}</b></td>
													<td align ="right" nowrap >&nbsp;</td>
													<td align ="center"><b>{$totalSumflatAvail}</b></td>
													<td  align ="left" >&nbsp;</td>
													<td  align ="left" >&nbsp;</td>
													<td  align ="left" >&nbsp;</td>
													<td  align ="left" >&nbsp;</td>
												</tr>
								 <tr>
								 	<td align = "left" colspan = "12">
								 		<b>Insert New Supply</b>
								 	</td>
								 <tr>
								  <tr>
								 	<td align = "right" colspan = "2" valign = "top">
								 		<select name="newPhase" id = "newPhase">
											<option value="0">No Phase</option>
											{foreach from=$phaseProject key=k item=v}
												<option value = "{$v.PHASE_ID}">{$v.PHASE_NAME}</option>
											{/foreach}
										 </select>
								 		
								 	</td>
								 	<td align = "center" valign = "top" colspan = "2">
								 		<select name="newBedId" id="newBedId">
										<option value="">Select Bedrooms</option>
										{foreach from=$fetch_projectOptions key=k item=v}
										 <option value = "{$v}">{$v} BHK</option>
										{/foreach}
									 </select>
								 	</td>
								 	
								 	<td align = "center" valign = "top">
								 		<input type = "text" name = "newNoOfFlats" id = "newNoOfFlats" style = "width:63px;">
								 	</td>
								 	<td align = "center" valign = "top">
								 		<select name="newIsFlats" id = "newIsFlats">
										  <option value="">Choose atleast One</option>
										  <option value="1">Accurate</option>
										  <option value="0">Guessed</option>
										 </select>
								 	</td>
								 	<td align = "center" valign = "top">
								 		<input type = "text" name = "newAvailFlats" id = "newAvailFlats" style = "width:63px;">
								 	</td>
								 	<td align = "center" valign = "top">
								 		<select name="newAvailIsFlats" id = "newAvailIsFlats">
										  <option value="">Choose atleast One</option>
										  <option value="1">Accurate</option>
										  <option value="0">Guessed</option>
										 </select>
								 	</td>
								 	<td align = "center" valign = "top">
								 		<textarea name="newEditReason" rows="2" cols="20" id="newEditReason"></textarea>
								 	</td>
								 	<td align = "center" valign = "top">
								 		<select name="newSoi" id = "newSoi">
											<option value="">Choose Source</option>
											{foreach from=$source_of_information key=k item=v}
											<option value = "{$v['SOURCE_NAME']}">{$v['SOURCE_NAME']}</option>
											{/foreach}
									   </select>
								 	</td>
								 	<td align = "center" valign = "top">
								 		<input name="newEffDateTo" value="{$newDate}" type="text"
											 class="formstyle2" id="newEffDateTo"
											  readonly="1" value="" size="10"  style="width:150px;"/> 
										 <img src="../images/cal_1.jpg" id="f_trigger_c_new" 
										 	style="cursor: pointer; border: 1px solid red;" title="Date selector"
										 	 onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
										<script type="text/javascript">
										   Calendar.setup({
										
										    inputField     :    "newEffDateTo",     // id of the input field
											ifFormat       :    "%Y-%m-%d",      // format of the input field
										    button         :    "f_trigger_c_to_new",  // trigger for the calendar (button ID)
										    align          :    "Tl",           // alignment (defaults to "Bl")
										    singleClick    :    true,
										    showsTime		:	true
										  });
										</script>
								 	</td>
								 	
								 <tr>
								 <tr class = "headingrowcolor">
					                <td align="left" nowrap  colspan = "6">
					                 <input type="hidden" name="projectId" value="{$projectId}" id ="projectId"/>
					                <input type="submit" name="btnSave" id="btnSave" value="Add More"  onclick = "return chkConfirm({$cnt});"/>
					                 <input type="submit" name="btnSave" id="btnSave" value="Submit" onclick = "return chkConfirm({$cnt});" />
					                 &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit" />
					               </td>
					               
					               <td align="right" nowrap  colspan = "9">
					                 <input type="hidden" name="projectId" value="{$projectId}" id ="projectId"/>
					                 <input type="submit" name="btnSave" id="btnSave" value="Add More"  onclick = "return chkConfirm({$cnt});"/>
					                 <input type="submit" name="btnSave" id="btnSave" value="Submit" onclick = "return chkConfirm({$cnt});" />
					                 &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit" />
					               </td>
					               
					            </tr>
							</table>
						</td>
				   </tr>
				</form>
				 
			</table>

			
           
          

		</TD>
	</TR>

</TABLE>
