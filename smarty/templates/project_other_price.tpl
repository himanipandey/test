<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript">

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
						  <TD nowrap class="h1" width="67%"><img height="18" hspace="5" src="../images/arrow.gif" width="18">
								{if $edit != 0} Edit {else}Add {/if} Project Other Pricing({$ProjectDetailArr[0]['BUILDER_NAME']} {$ProjectDetailArr[0]['PROJECT_NAME']})
						  </TD>
						  <TD width="33%" align ="right"></TD>
					   
						</TR>
					</TBODY>
				  </TABLE>
				</TD>
	      </TR>
		  <tr>
		</tr>
		<tr>
		<TD vAlign="top" align="middle" class="backgorund-rt" height="450"><BR>

			<TABLE cellSpacing=1 cellPadding=4 width="70%" align="center"  style = "border:1px solid;">

			 <form method = 'post' name = 'frm' action = '' >								
				<tr class="headingrowcolor">
					<td class="whiteTxt" align="center">Component</td>
					<td class="whiteTxt" align="center">Select Value <span style="font-size:10px">(only numeric value)</span></td>
					<td class="whiteTxt" align="center">Field Type</td>
				</tr>
				
				<tr id="trid1" bgcolor="#F7F7F7">
						
					<td align="right"><b>EDC/IDC:</b></td>
					<td align="center">
						
						{if $OtherPrice[0]['EDC_IDC_TYPE'] == 'psf'}
							<input name="edc_idc" id="edc_idc" value="psf" checked="checked" type="radio"> PSF
							<input name="edc_idc" id="edc_idc" value="Fixed" type="radio"> Fixed
						{else}
							<input name="edc_idc" id="edc_idc" value="psf" type="radio"> PSF
							<input name="edc_idc" id="edc_idc" value="Fixed"  checked="checked" type="radio"> Fixed
						{/if}

						<input onkeypress="return isNumberKey(event)" name="edc_idc_val1" id="edc_idc_1" type="text" value = "{$OtherPrice[0]['EDC_IDC']}">

					</td>
					<td align="center">
						{if $OtherPrice[0]['EDC_IDC_MEND_OPT'] == 'mend'}
							 <input name="edc_idc_type1" id="edc_idc_type1" value="mend" checked="checked" type="radio"> Mandatory
							 <input name="edc_idc_type1" id="edc_idc_type1" value="opt" type="radio"> Optional
						{else}
							 <input name="edc_idc_type1" id="edc_idc_type1" value="mend" type="radio"> Mandatory
							 <input name="edc_idc_type1" id="edc_idc_type1" value="opt"  checked="checked" type="radio"> Optional
						{/if}
					</td>
				</tr>

							
				<tr id="trid1" bgcolor="#F7F7F7">
						
					<td align="right"><b>Lease Rent:</b></td>
					<td align="center">
						{if $OtherPrice[0]['LEASE_RENT_TYPE'] == 'psf'}
							<input name="lease_rent" id="lease_rent" value="psf" checked="checked" type="radio"> PSF
							<input name="lease_rent" id="lease_rent" value="Fixed" class="lease_rent1" type="radio"> Fixed
						{else}
							<input name="lease_rent" id="lease_rent" value="psf"  type="radio"> PSF
							<input name="lease_rent" id="lease_rent" value="Fixed" checked="checked" type="radio"> Fixed
						{/if}
						<input onkeypress="return isNumberKey(event)" name="lease_rent_val1" id="lease_rent_val1" type="text" value = "{$OtherPrice[0]['LEASE_RENT']}">
					</td>
					<td align="center">
						{if $OtherPrice[0]['LEASE_RENT_MEND_OPT'] == 'mend'}
							 <input name="lease_rent_type1" id="lease_rent_type1" value="mend" checked="checked" type="radio"> Mandatory
							 <input name="lease_rent_type1" id="lease_rent_type1" value="opt" type="radio"> Optional
						{else}
							<input name="lease_rent_type1" id="lease_rent_type1" value="mend" type="radio"> Mandatory
							 <input name="lease_rent_type1" id="lease_rent_type1" value="opt"  checked="checked" type="radio"> Optional
						{/if}

					</td>
				</tr>

				<tr id="trid1" bgcolor="#F7F7F7">
						
					<td align="right"><b>Open Car Parking:</b></td>
					<td align="center">
						{if $OtherPrice[0]['OPEN_CAR_PARKING_TYPE'] == 'psf'}
							<input name="open_car_parking" id="open_car_parking" value="psf" checked="checked" type="radio"> PSF
							<input name="open_car_parking" id="open_car_parking" value="Fixed" class="open_car_parking1" type="radio"> Fixed
						{else}
							<input name="open_car_parking" id="open_car_parking" value="psf" type="radio"> PSF
							<input name="open_car_parking" id="open_car_parking" value="Fixed"  checked="checked" type="radio"> Fixed
						{/if}
						<input onkeypress="return isNumberKey(event)" name="open_car_parking1" id="open_car_parking1" type="text" value = "{$OtherPrice[0]['OPEN_CAR_PARKING']}">
					</td>
					<td align="center">
						{if $OtherPrice[0]['OPEN_CAR_PARKING_MEND_OPT'] == 'mend'}
							<input name="open_car_parking_type1" id="open_car_parking_type1" value="mend" checked="checked" type="radio"> Mandatory
							<input name="open_car_parking_type1" id="open_car_parking_type1" value="opt" type="radio"> Optional
						{else}
							<input name="open_car_parking_type1" id="open_car_parking_type1" value="mend" type="radio"> Mandatory
							<input name="open_car_parking_type1" id="open_car_parking_type1" value="opt" checked="checked" type="radio"> Optional
						{/if}
					</td>
				</tr>
				
				<tr id="trid1" bgcolor="#F7F7F7">
						
					<td align="right"><b>Closed Car Parking:</b></td>
					<td align="center">
						{if $OtherPrice[0]['CLOSE_CAR_PARKING_TYPE'] == 'psf'}
							<input name="close_car_parking" id="close_car_parking" value="psf" checked="checked" type="radio"> PSF
							<input name="close_car_parking" id="close_car_parking" value="Fixed" class="close_car_parking1" type="radio"> Fixed
						{else}
							<input name="close_car_parking" id="close_car_parking" value="psf" type="radio"> PSF
							<input name="close_car_parking" id="close_car_parking" value="Fixed" checked="checked" type="radio"> Fixed
						{/if}
						<input onkeypress="return isNumberKey(event)" name="close_car_parking1" id="close_car_parking1" type="text" value = "{$OtherPrice[0]['CLOSE_CAR_PARKING']}">
					</td>
					<td align="center">
						{if $OtherPrice[0]['CLOSE_CAR_PARKING_MEND_OPT'] == 'mend'}
							<input name="close_car_parking_type1" id="close_car_parking_type1" value="mend" checked="checked" type="radio"> Mandatory
							<input name="close_car_parking_type1" id="close_car_parking_type1" value="opt" type="radio"> Optional
						{else}
							<input name="close_car_parking_type1" id="close_car_parking_type1" value="mend" type="radio"> Mandatory
							<input name="close_car_parking_type1" id="close_car_parking_type1" value="opt" checked="checked" type="radio"> Optional
						{/if}
					</td>
				</tr>

				<tr id="trid1" bgcolor="#F7F7F7">
						
					<td align="right"><b>Semi Closed Car Parking:</b></td>
					<td align="center">
						{if $OtherPrice[0]['SEMI_CLOSE_CAR_PARKING_TYPE'] == 'psf'}
							<input name="semi_close_car_parking" id="semi_close_car_parking" value="psf" checked="checked" type="radio"> PSF
							<input name="semi_close_car_parking" id="semi_close_car_parking" value="Fixed" type="radio"> Fixed
						{else}
							<input name="semi_close_car_parking" id="semi_close_car_parking" value="psf" type="radio"> PSF
							<input name="semi_close_car_parking" id="semi_close_car_parking" value="Fixed" checked="checked" type="radio"> Fixed
						{/if}
						
						<input onkeypress="return isNumberKey(event)" name="semi_close_car_parking1" id="semi_close_car_parking1" type="text" value = "{$OtherPrice[0]['SEMI_CLOSE_CAR_PARKING']}">
					</td>
					<td align="center">
						{if $OtherPrice[0]['SEMI_CLOSE_CAR_PARKING_MEND_OPT'] == 'mend'}
							<input name="semi_close_car_parking_type1" id="semi_close_car_parking_type1" value="mend" checked="checked" type="radio"> Mandatory
							<input name="semi_close_car_parking_type1" id="semi_close_car_parking_type1" value="opt" type="radio"> Optional
						{else}
							<input name="semi_close_car_parking_type1" id="semi_close_car_parking_type1" value="mend" type="radio"> Mandatory
							<input name="semi_close_car_parking_type1" id="semi_close_car_parking_type1" value="opt" checked="checked" type="radio"> Optional
						{/if}

					</td>
				</tr>

				<tr id="trid1" bgcolor="#F7F7F7">
						
					<td align="right"><b>Club House:</b></td>
					<td align="center">
						{if $OtherPrice[0]['CLUB_HOUSE_PSF_Fixed'] == 'psf'}
							<input name="club_house" id="club_house" value="psf" checked="checked" type="radio"> PSF
							<input name="club_house" id="club_house" value="Fixed" class="club_house" type="radio"> Fixed
						{else}
							<input name="club_house" id="club_house" value="psf" type="radio"> PSF
							<input name="club_house" id="club_house" value="Fixed" checked="checked" type="radio"> Fixed
						{/if}
						<input onkeypress="return isNumberKey(event)" name="club_house1" id="club_house1" type="text" value = "{$OtherPrice[0]['CLUB_HOUSE']}">
					</td>
					<td align="center">
						{if $OtherPrice[0]['CLUB_HOUSE_MEND_OPT'] == 'mend'}
							<input name="club_house_type1" id="club_house_type1" value="mend" checked="checked" type="radio"> Mandatory
							<input name="club_house_type1" id="club_house_type1" value="opt" type="radio"> Optional	
						{else}
							<input name="club_house_type1" id="club_house_type1" value="mend" type="radio"> Mandatory
							<input name="club_house_type1" id="club_house_type1" value="opt" checked="checked" type="radio"> Optional	
						{/if}
					</td>
				</tr>

				<tr id="trid1" bgcolor="#F7F7F7">
						
					<td align="right"><b>IFMS:</b></td>
					<td align="center">
						{if $OtherPrice[0]['IFMS_PSF_Fixed'] == 'psf'}
							<input name="ifms" id="ifms" value="psf" checked="checked" type="radio"> PSF
							<input name="ifms" id="ifms" value="Fixed" class="ifms" type="radio"> Fixed
						{else}
							<input name="ifms" id="ifms" value="psf" type="radio"> PSF
							<input name="ifms" id="ifms" value="Fixed" checked="checked" class="ifms" type="radio"> Fixed
						{/if}
						
						<input onkeypress="return isNumberKey(event)" name="ifms1" id="ifms1" type="text" value = "{$OtherPrice[0]['IFMS']}">
					</td>
					<td align="center">
						{if $OtherPrice[0]['IFMS_MEND_OPT'] == 'mend'}
							<input name="ifms_type1" id="ifms_type1" value="mend" checked="checked" type="radio"> Mandatory
							<input name="ifms_type1" id="ifms_type1" value="opt" type="radio"> Optional
						{else}
							<input name="ifms_type1" id="ifms_type1" value="mend" type="radio"> Mandatory
							<input name="ifms_type1" id="ifms_type1" value="opt" checked="checked" type="radio"> Optional
						{/if}
					</td>
				</tr>

				<tr id="trid1" bgcolor="#F7F7F7">
						
					<td align="right"><b>Power backup charges:</b></td>
					<td align="center">
						{if $OtherPrice[0]['POWER_BACKUP_PSF_Fixed'] == 'psf'}
							<input name="power_backup" id="power_backup" value="psf" checked="checked" type="radio"> PSF
							<input name="power_backup" id="power_backup" value="Fixed" type="radio"> Fixed
						{else}
							<input name="power_backup" id="power_backup" value="psf" type="radio"> PSF
							<input name="power_backup" id="power_backup" value="Fixed" checked="checked" type="radio"> Fixed
						{/if}
						
						<input onkeypress="return isNumberKey(event)" name="power_backup1" id="power_backup1" type="text" value = "{$OtherPrice[0]['POWER_BACKUP']}">
					</td>
					<td align="center">
						{if $OtherPrice[0]['POWER_BACKUP_MEND_OPT'] == 'mend'}
							<input name="power_backup_type1" id="power_backup_type1" value="mend" checked="checked" type="radio"> Mandatory
							<input name="power_backup_type1" id="power_backup_type1" value="opt" type="radio"> Optional
						{else}
							<input name="power_backup_type1" id="power_backup_type1" value="mend" type="radio"> Mandatory
						<input name="power_backup_type1" id="power_backup_type1" value="opt" checked="checked" type="radio"> Optional
						{/if}
					</td>
				</tr>

				<tr id="trid1" bgcolor="#F7F7F7">
						
					<td align="right"><b>Legal Fees:</b></td>
					<td align="center">
						{if $OtherPrice[0]['LEGAL_FEES_PSF_Fixed'] == 'psf'}
							<input name="legal_fees" id="legal_fees" value="psf" checked="checked" type="radio"> PSF
							<input name="legal_fees" id="legal_fees" value="Fixed" type="radio"> Fixed 
						{else}
							<input name="legal_fees" id="legal_fees" value="psf" type="radio"> PSF
							<input name="legal_fees" id="legal_fees" value="Fixed" checked="checked" type="radio"> Fixed 
						{/if}
						
						<input onkeypress="return isNumberKey(event)" name="legal_fees1" id="legal_fees1" type="text" value = "{$OtherPrice[0]['LEGAL_FEES']}">
					</td>
					<td align="center">
						{if $OtherPrice[0]['LEGAL_FEES_MEND_OPT'] == 'mend'}
							<input name="legal_fees_type1" id="legal_fees_type1" value="mend" checked="checked" type="radio"> Mandatory
							<input name="legal_fees_type1" id="legal_fees_type1" value="opt" type="radio"> Optional
						{else}
							<input name="legal_fees_type1" id="legal_fees_type1" value="mend"  type="radio"> Mandatory
							<input name="legal_fees_type1" id="legal_fees_type1" value="opt" checked="checked" type="radio"> Optional
						{/if}
					</td>
				</tr>

				<tr id="trid1" bgcolor="#F7F7F7">
						
					<td align="right"><b>Power and Water:</b></td>
					<td align="center">
						{if $OtherPrice[0]['POWER_WATER_PSF_Fixed'] == 'psf'}
							<input name="power_and_water" id="power_and_water" value="psf" checked="checked" type="radio"> PSF
							<input name="power_and_water" id="power_and_water" value="Fixed" type="radio"> Fixed
						{else}
							<input name="power_and_water" id="power_and_water" value="psf" type="radio"> PSF
							<input name="power_and_water" id="power_and_water" value="Fixed" checked="checked" type="radio"> Fixed
						{/if}
						
						<input onkeypress="return isNumberKey(event)" name="power_and_water1" id="power_and_water1" type="text" value = "{$OtherPrice[0]['POWER_WATER']}">
					</td>
					<td align="center">
						{if $OtherPrice[0]['POWER_WATER_MEND_OPT'] == 'mend'}
							<input name="power_and_water_type1" id="power_and_water_type1" value="mend" checked="checked" type="radio"> Mandatory
							<input name="power_and_water_type1" id="power_and_water_type1" value="opt" type="radio"> Optional
						{else}
							<input name="power_and_water_type1" id="power_and_water_type1" value="mend" type="radio"> Mandatory
							<input name="power_and_water_type1" id="power_and_water_type1" value="opt" checked="checked" type="radio"> Optional
						{/if}
					</td>
				</tr>

				<tr id="trid1" bgcolor="#F7F7F7">
						
					<td align="right"><b>Maintenance Advance:</b></td>
					<td align="center">
						{if $OtherPrice[0]['MAINTENANCE_ADVANCE_PSF_Fixed'] == 'psf'}
							<input name="maintenance_advance" id="maintenance_advance" value="psf" checked="checked" type="radio"> PSF
							<input name="maintenance_advance" id="maintenance_advance" value="Fixed" type="radio"> Fixed
						{else}
							<input name="maintenance_advance" id="maintenance_advance" value="psf" type="radio"> PSF
							<input name="maintenance_advance" id="maintenance_advance" value="Fixed" checked="checked" type="radio"> Fixed
						{/if}

						<input onkeypress="return isNumberKey(event)" name="maintenance_advance1" id="maintenance_advance1" type="text" value = "{$OtherPrice[0]['MAINTENANCE_ADVANCE']}">
					</td>
					<td align="center">
						{if $OtherPrice[0]['MAINTENANCE_ADVANCE_MEND_OPT'] == 'mend'}
							<input name="maintenance_advance_type1" id="maintenance_advance_type1" value="mend" checked="checked" type="radio"> Mandatory
							<input name="maintenance_advance_type1" id="maintenance_advance_type1" value="opt" type="radio"> Optional
						{else}
							<input name="maintenance_advance_type1" id="maintenance_advance_type1" value="mend"  type="radio"> Mandatory
							<input name="maintenance_advance_type1" id="maintenance_advance_type1" value="opt" checked="checked" type="radio"> Optional
						{/if}
					</td>
				</tr>

				<tr id="trid1" bgcolor="#F7F7F7">
						
					<td align="right"><b>Maintenance Advance months:</b></td>
					<td align="center">
						
							<select name = "maintenance_advance_months" id = "maintenance_advance_months">
								{for $loop = 1 to 100}
									<option value = "{$loop}" {if $OtherPrice[0]['MAINTENANCE_ADVANCE_MONTHS'] == $loop} selected {/if}>
										{$loop}
									</option>
								{/for}
							</select>
						
					</td>
					<td align="center">&nbsp;</td>
				</tr>


				<tr id="trid1" bgcolor="#F7F7F7">
						
					<td align="right" valign ="top"><b>PLC:</b></td>
					<td align="left" colspan = "2">
						<textarea name = "plc" id = "plc" rows="6" cols = "55">
							{trim($OtherPrice[0]['PLC'])}
						</textarea>
					</td>
					
				</tr>
				<tr id="trid1" bgcolor="#F7F7F7">
						
					<td align="right" valign ="top"><b>Floor Rise:</b></td>
					<td align="left" colspan = "2">
						<textarea name = "floor_rise" id = "floor_rise" rows="6" cols = "55">
							{trim($OtherPrice[0]['FLOOR_RISE'])}
						</textarea>
					</td>
					
				</tr>
				<tr id="trid1" bgcolor="#F7F7F7">
						
					<td align="right" valign ="top"><b>Other:</b></td>
					<td align="left" colspan = "2">
						<textarea name = "other" id = "other" rows="6" cols = "55">
							{trim($OtherPrice[0]['OTHERS'])}
						</textarea>
					</td>
					
				</tr>

				<input type = "hidden" name = "projectId" id = "projectId" value = "{$ProjectDetailArr[0]['PROJECT_ID']}">
				<input type = "hidden" name = "edit" id = "edit" value = "{$edit}">
				<input type = "hidden" name = "row_id" id = "row_id" value = "{$ProjectDetailArr[0]['ID']}">
				<tr class = "headingrowcolor">
					<td align="right" colspan ="3" >
					
					 
					 <input type="submit" name="btnSave" id="btnSave" value="Next">
					 <input type="submit" name="Skip" id="Skip" value="Skip">
				  
					 <input type="submit" name="btnSave" id="btnSave" value="Submit">
				  
					 &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit" />
					 </td>
				</tr>
			   </form>
			</TABLE>

		</TD>
	</TR>
 
</TABLE>

