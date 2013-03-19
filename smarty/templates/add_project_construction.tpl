<script type="text/javascript" src="javascript/jquery.js"></script>
<script type="text/javascript" src="javascript/common.js"></script>
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>

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
						  <TD class="h1" width="67%"><img height="18" hspace="5" src="../images/arrow.gif" width="18">Project Construction Status({ucwords($fetch_projectDetail[0]['PROJECT_NAME'])})</TD>
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
								   <td width="20%" align="right" valign="top" nowrap><b><font color ="red">*</font>Expected Completion Date :</b> </td>
								   <td width="30%" align="left">
								   <input name="eff_date_to" value="{$costDetail['EXPECTED_COMPLETION_DATE']}" type="text" class="formstyle2" id="f_date_c_to" readonly="1" size="10" />  <img src="../images/cal_1.jpg" id="f_trigger_c_to" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
								   </td>
								    
								    <td width="50%" align="left">
								    <font color="red">
								    <span id = "err_date" style = "display:none;">Please choose expected delivery date!</span></font>
								    </td>
							   </tr>
							   <tr>
								<td width="20%" align="left">
								<b>Select Date Effective From :</b> </td>
								 <td width="30%" align="left"><input name="eff_date" value="{$costDetail['SUBMITTED_DATE']}" type="text" class="formstyle2" id="eff_date" readonly="1" value="" size="10" />  <img src="../images/cal_1.jpg" id="f_trigger" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
								</td>
							  </tr>
							   <tr>
								  <td width="20%" align="right" valign="top"><b><font color ="red">*</font><b>Remarks :</b> </td>
								  <td width="30%" align="left">
									 <textarea name="remark" rows="10" cols="30" id="remark">{$costDetail['REMARK']}</textarea>
								  </td>
								  <td width="50%" align="left">
									  <font color="red"><span id = "err_edit_reson" style = "display:none;">Please enter reson for updating information about tower</span></font>  								 
								   </td>
							   </tr>				 
							   
							   <tr>
								  <td width="10%">&nbsp;</td>
								  <td width="90%" align='left' colspan='2'>
								  <input type="submit" name="btnSave" id="btnSave" value="Submit"  onclick="return construction_status();"/>&nbsp;

									&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit" />
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
   
       inputField     :    "f_date_c_to",     // id of the input field
   //    ifFormat       :    "%Y/%m/%d %l:%M %P",      // format of the input field
   ifFormat       :    "%Y-%m-%d",      // format of the input field
       button         :    "f_trigger_c_to",  // trigger for the calendar (button ID)
       align          :    "Tl",           // alignment (defaults to "Bl")
       singleClick    :    true,
   showsTime		:	true
   
   });

   Calendar.setup({
   
       inputField     :    "eff_date",     // id of the input field
	   ifFormat       :    "%Y-%m-%d",      // format of the input field
       button         :    "f_trigger",  // trigger for the calendar (button ID)
       align          :    "Tl",           // alignment (defaults to "Bl")
       singleClick    :    true,
	  showsTime		:	true
   
   });
</script>