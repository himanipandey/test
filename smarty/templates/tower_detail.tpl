<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>

<script type="text/javascript">

 window.onload=init;

 function init()
 {
   document.getElementById('formss').onClick=validateForm;
 }

 function validateForm()
 { 
 
 var text= document.formss.text.value; 
 if(text==""){ 
 alert('text area cannot be empty');
 return false;
 } 
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
						  <TD class="h1" width="67%"><img height="18" hspace="5" src="../images/arrow.gif" width="18">Add Tower Detail({$ProjectDetail[0]['BUILDER_NAME']} {$ProjectDetail[0]['PROJECT_NAME']})</TD>
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
								{if is_array($errorMsg)}
									<tr>
								  <td colspan="3"><font color ="red">{$errorMsg['Error']}</font> </td>
								  
								{/if}
							   <tr>
								  <td width="20%" align="right"><font color ="red">*</font><b>Tower Name :</b> </td>
								  <td width="30%" align="left">
									 
									 <input type="text" name="TowerId" class="TowerId" id="TowerId" value = "{$towername}" />
									 <div id="imgPathRefresh"></div>
								  </td>
								  <td width="50%" align="left">
									  <font color="red"><span id = "err_tower_name" style = "display:none;">Please Enter Tower Name</span></font>
								  </td>
							   </tr>  
							   <tr>
								  <td width="20%" align="right"><font color ="red">*</font><b>No of floors :</b> </td>

								  <td width="30%" align="left">
								    	<input type="text" name="FloorId" class="FloorId" id="FloorId"  value = "{$no_of_floors}"/>
								  </td>

								  <td width="50%" align="left">
									  <font color="red">
									  <span id = "err_floor_name" style = "display:none;">Please enter floors!</span>
									   <span id = "err_floor_number" style = "display:none;">Please enter integer value!</span>
									  </font>
								  </td>
							   </tr>
							   <tr>
								  <td width="20%" align="right"><b>No. of Flats :</b> </td>
								  <td width="30%" align="left">
											<input type="text" name="AvilFlatId" class="AvilFlatTowerId" value ="{$no_of_flats_per_floor}"/>
							       </td>
								  
								  <td width="15%" align="left"><font color="red"><span id = "err_Avail_tower" style = "display:none;">Please enter integer value!</span></font></td>
								  								  
							   </tr>
							   
							   <tr>
								  <td width="20%" align="right" valign="top"><b><b><b>Remarks :</b> </td>
								  <td width="30%" align="left">
									 <textarea name="remark" rows="10" cols="30" id="textb">{$remark}</textarea>
								  </td>
								  <td width="50%" align="left"></td>
							   </tr>
							   <tr>
								  <td width="20%" align="right"><b>Please Chooose Tower Facing Direction :</b> </td>
								  
								  <td width="30%" align="left">
								  
								  <select name="face" class="face">
								  <option value="">Choose Direction</option>
								  <option value="N" {if $towerface == 'N'} selected {/if}>N</option>
								  <option value="E" {if $towerface == 'E'} selected {/if}>E</option>
								  <option value="W" {if $towerface == 'W'} selected {/if}>W</option>
								  <option value="S" {if $towerface == 'S'} selected {/if}>S</option>
								  <option value="NE" {if $towerface == 'NE'} selected {/if}>NE</option>
								  <option value="NW" {if $towerface == 'NW'} selected {/if}>NW</option>
								  <option value="SE" {if $towerface == 'SE'} selected {/if}>SE</option>
								  <option value="SW" {if $towerface == 'SW'} selected {/if}>SW</option>
								  </select>
								  </td>
								  
								  <td width="50%" align="left"></td>
							   </tr>
								
							   
							  <tr>
								  <td width="20%" align="right"><font color ="red">*</font><b>Stilt On Ground Floor:</b> </td>
								  
								  <td width="30%" align="left">
								  <select name="stilt" class="stilt">
								  <option value="">Choose Atleast One</option>
								  <option value="1" {if $stilt == '1'} selected {/if}>Yes</option>
								  <option value="0" {if $stilt == '0'} selected {/if}>No</option>
								  </select>
								  </td>
								  
								  <td width="50%" align="left">
									  <font color="red">
									   <span id = "err_stilt" style = "display:none;">Please choose Yes or No!</span></font>
								  </td>
							   </tr>
							  

							   <tr>
							   <td width="20%" align="right" valign="top"><b>Actual Completion Date  :</b> </td>
							   <td width="30%" align="left">
							   <input name="eff_date_to" value="{$completion_date}" type="text" class="formstyle2" id="f_date_c_to" readonly="1" size="10" />  <img src="../images/cal_1.jpg" id="f_trigger_c" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
							   </td>
							    
							    <td width="50%" align="left"></td>
							   
							   <tr>
								  <td>&nbsp;</td>
									
								  <td align="left" style="padding-left:0px;">
								  <input type="submit" name="btnSave" id="btnAddMore" value="Add More"  onclick = "return project_scn3();"/>
								  {if $edit == ''}
									<input type="submit" name="btnSave" id="btnSave" value="Next"   onclick = "return project_scn3();"/>
									&nbsp;&nbsp;<input type="submit" name="Skip" id="Skip" value="Skip" />
								  {else}
									<input type = "hidden" name = "edit" value = "{$edit}">
									<input type="submit" name="btnSave" id="btnSave" value="Submit"   onclick = "return project_scn3();"/>
								  {/if}
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
   
       inputField     :    "f_date_c_to",     // id of the input field
   //    ifFormat       :    "%Y/%m/%d %l:%M %P",      // format of the input field
   ifFormat       :    "%Y-%m-%d",      // format of the input field
       button         :    "f_trigger_c",  // trigger for the calendar (button ID)
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