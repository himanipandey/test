<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>
<script>
	function refreshTower(towerId,arr)
	{
		var projectId = document.getElementById("projectId").value;	
		//if(arr.search(towerId)!=-1)
		//{
			window.location = "add_tower_construction_status.php?projectId="+projectId+"&towerId="+towerId;
		//}
		
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
						  <TD class="h1" width="67%"><img height="18" hspace="5" src="../images/arrow.gif" width="18">Tower Construction Status For Project {ucwords($fetch_projectDetail[0]['PROJECT_NAME'])} </TD>
						  <TD width="33%" align ="right"></TD>   
					   
						</TR>
					</TBODY>
				  </TABLE>
				</TD>
	      </TR>
		  <tr></tr>
			<TD vAlign="top" align="middle" class="backgorund-rt" height="450"><BR>
			 
				<table cellSpacing="1" cellPadding="4" width="67%" align="center" border="0">
					 <form method="post" id="formss" enctype="multipart/form-data">
							  
							  {if count($arrAudit)>0}
								   <tr>
									  <td width="20%" align="right"><font color ="red">*</font><b>Last Updated Date :</b> </td>
									  <td width="30%" align="left">
										 <input type = "text" name = "updated_date" readonly value = "{$arrAudit[0]['ACTION_DATE']}"
										 <div id="imgPathRefresh"></div>
									  </td>
									  <td width="50%" align="left">
										  <font color="red"><span id = "err_bed_name" style = "display:none;">Please select Type of Bedrooms !</span></font>
									  </td>
								   </tr>
							   {/if}
								 
							  <tr>
									<td width="20%" align="right" valign="top"><b><b><font color ="red">*</font><b>Select Towers</b> </td>
								        <td width="30%" align="left">
									 <select name="tower_name_select" class="tower_name_select" onchange ="refreshTower(this.value,'{$arr_RoomNot}');">
										<option value="">Select Towers</option>
										{foreach from=$fetch_towerDetails key=k item=v}
										 <option value = "{$v['TOWER_ID']}" {if $v['TOWER_ID'] == $tower_detail[0]['TOWER_ID']} selected {/if}>{$v['TOWER_NAME']}</option>
										{/foreach}
									 </select>
									</td>
									<td width="15%" align="left">
								        <font color="red"><span id = "err_tower_status" style = "display:none;">Please select tower to find its current status</span></font>
								        </td>
							  </tr>
							
							  <tr>
								  <td width="30%" align="right"><font color ="red">*</font><b>Enter No. of Floors Completed </b> </td>
								  <td width="30%" align="left">
								  <input type="text" name = "completed_floors" id="completed_floors" class="completed_floors" value = "{$tower_detail[0]['NO_OF_FLOORS_COMPLETED']}"/>
								  </td>

								  <td width="50%" align="left">
									  <font color="red">
									  <span id = "err_floor_name" style = "display:none;">Please enter floors!</span>
									   <span id = "err_floor_number_" style = "display:none;">Please enter integer value!</span>
									  </font>
								  </td>
							  
							   
							   </tr>
							   <tr>
								  <td width="20%" align="right" valign="top"><b><b><font color ="red">*</font><b>Remarks :</b> </td>
								  <td width="30%" align="left">
									 <textarea name="remark" rows="10" cols="30" id="remark">{$tower_detail[0]['GENERAL_REMARK']}</textarea>
								  </td>
								  <td width="50%" align="left">
									  <font color="red"><span id = "err_edit_reson" style = "display:none;">Please enter reson for updating information about tower</span></font>  								 
								   </td>
							   </tr>
							   

							   <tr>
								   <td width="20%" align="right" valign="top"><b>Expected Delivery Date :</b> </td>
								   <td width="30%" align="left">
								   <input name="eff_date_to" value="{$tower_detail[0]['EXPECTED_DELIVERY_DATE']}" type="text" class="formstyle2" id="f_date_c_to" readonly="1" size="10" />  <img src="../images/cal_1.jpg" id="f_trigger_c_to" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
								   </td>
								    
								    <td width="50%" align="left">
								    <font color="red">
								    <span id = "err_date" style = "display:none;">Please choose expected delivery date!</span></font>
								    </td>
							   
							   </tr>
							   
							   
							  <tr>
								<td width="30%" align="left">
								<b>Select Date Effective From :</b> </td>
								 <td width="30%" align="left"><input name="eff_date" value="{$tower_detail[0]['SUBMITTED_DATE']}" type="text" class="formstyle2" id="eff_date" readonly="1" size="10" />  <img src="../images/cal_1.jpg" id="f_trigger" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
								</td>
							  </tr>
							   
							   <tr>
								  <td width="10%">&nbsp;</td>
								  <td width="90%" align='left' colspan='2'>
								  <input type = "hidden" name = "projectId" id = "projectId" value = "{$fetch_projectDetail[0]['PROJECT_ID']}">
								  <input type="submit" name="btnSave" id="btnAddMore" value="Add More" onclick="return tower_status();"/>
								 &nbsp;&nbsp; <input type="submit" name="btnSave" id="btnSave" value="Submit"  onclick="return tower_status();"/>
								  &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit" />
								  </td> 
							   </tr>
							</div>
					 
				</table>
				</form>
			</TD>
		</TR>
 
	</TABLE>

<script type="text/javascript">
   Calendar.setup({
   
       inputField     :    "eff_date",     // id of the input field
	   ifFormat       :    "%Y-%m-%d",      // format of the input field
       button         :    "f_trigger",  // trigger for the calendar (button ID)
       align          :    "Tl",           // alignment (defaults to "Bl")
       singleClick    :    true,
	  showsTime		:	true
   
   });
</script>

<script type="text/javascript">
$("#TowerId").focus(function() {
	$("#TowerId").val('');
});
</script>



<script type="text/javascript">
$("#FloorId").focus(function() {
	$("#FloorId").val('');
});
</script>

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