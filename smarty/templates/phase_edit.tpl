<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        var pid = '{$phaseId}';
        $('select#phaseSelect').val(pid);
    });

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

    function change_phase() {
        var new_id = $('#phaseSelect').val();
        var newURL = updateURLParameter(window.location.href, 'phaseId', new_id);
        window.location.href = newURL;
    }

    function validate_phase() {
        var name_flag = true;
        var flat_bed = true;
        var villa_bed = true;

        var phasename     = $('#phaseName').val();
        if(!phasename) {
            $('#err_phase_name').show();
            name_flag = false;
        }
        else {
            $('#err_phase_name').hide();
            name_flag = true;
        }

        $('li.flat_bed').each(function() {
          var intRegex = /^\d+$/;
          var v = $(this).find('input').val();
          var err = $(this).find('span.err_flat_bed');
          if(!intRegex.test(v)) {
            $(err).show();
            villa_bed = false;
          }
          else {
            $(err).hide();
          }
        });

        $('li.villa_bed').each(function() {
          var intRegex = /^\d+$/;
          var v = $(this).find('input').val();
          var err = $(this).find('span.err_villa_bed');
          if(!intRegex.test(v)) {
            $(err).show();
            flat_bed = false;
          }
          else {
            $(err).hide();
          }
        });

        return name_flag && flat_bed && villa_bed;
    }
    
    function deletePhase()
    {
    	return confirm("Are you sure! you want to delete phase.");
    }

</script>

    <tr>
        <td class="white-bg paddingright10" vAlign=top align=middle bgColor=#ffffff>
        <table cellSpacing=0 cellPadding=0 width="100%" border=0>
            <tr>
              <td width=224 height=25>&nbsp;</td>
              <td width=10>&nbsp;</td>
              <td width=866>&nbsp;</td>
            </tr>
        <tr>
            <td class=paddingltrt10 vAlign=top align=middle bgColor=#ffffff>
            {include file="{$PROJECT_ADD_TEMPLATE_PATH}left.tpl"}
            </td>
            <td vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</td>
            <td vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <table cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><tbody>
                <tr>
                    <td class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                        <table cellSpacing=0 cellPadding=0 width="99%" border=0>
                            <tbody>
                                <tr>
                                  <td class="h1" width="67%"><img height="18" hspace="5" src="../images/arrow.gif" width="18">Edit Phase ({$ProjectDetail[0]['BUILDER_NAME']} {$ProjectDetail[0]['PROJECT_NAME']})</td>
                                  <td width="33%" align ="right"></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
	            </tr>
		        <tr></tr>
			<td vAlign="top" align="middle" class="backgorund-rt" height="450"><BR>
			 
				<table cellSpacing="1" cellPadding="4" width="67%" align="center" border="0">
              <form method="post" id="phase_form">
			   <input type='hidden' name='project_type_id' value="{$ProjectDetail[0]['PROJECT_TYPE_ID']}">
              {if $error_msg}
                <tr>
                    <td colspan="3"><font color ="red">Error :: {$error_msg}</font></td>
                </tr>
              {/if}
							   <tr>
								  <td width="20%" align="right"><b>Phase :</b> </td>
								  <td width="30%" align="left">
                        <select id="phaseSelect" name="phaseSelect" onchange="change_phase();">
                            <option value="-1">Select Phase</option>
                             {foreach $phases as $p}
                                 <option value="{$p.id}">{$p.name}</option>
                             {/foreach}
                        </select>
								  </td>
								  <td width="50%" align="left"></td>
							   </tr>
							
							 
							 {if isset($phaseId) and $phaseId != -1}
								 <tr>
									  <td width="20%" align="right"><font color ="red">*</font><b>Phase Name :</b> </td>
									  <td width="30%" align="left">
											<input id="phaseName" name="phaseName" value="{$phasename}" />
									  </td>
									  <td width="50%" align="left">
										<font color="red"><span id="err_phase_name" style = "display:none;">Enter Phase Name</span></font>
									  </td>
								 </tr>

                                <tr>
                                    <td width="20%" align="right" valign="top"><b>Launch Date  :</b> </td>
                                    <td width="30%" align="left">
                                        <input name="launch_date" value="{$launch_date}" type="text" class="formstyle2" id="launch_date" readonly="1" size="10" />  <img src="../images/cal_1.jpg" id="launch_date_trigger" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                                    </td>
                                    <td width="50%" align="left">
                                        <font color="red"><span id = "err_launch_date" style = "display:none;">Enter Launch Date</span></font>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%" align="right" valign="top"><b>Completion Date :</b> </td>
                                    <td width="30%" align="left">
                                        <input name="completion_date" value="{$completion_date}" type="text" class="formstyle2" id="completion_date" readonly="1" size="10" />  <img src="../images/cal_1.jpg" id="completion_date_trigger" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                                    </td>
                                    <td width="50%" align="left">
                                        <font color="red"><span id = "err_completion_date" style = "display:none;">Enter Completion Date</span></font>
                                    </td>
                                </tr>

                                {if $ProjectDetail[0]['PROJECT_TYPE_ID']==1 || $ProjectDetail[0]['PROJECT_TYPE_ID']==3 || $ProjectDetail[0]['PROJECT_TYPE_ID']==6}
									<tr>
									  <td width="20%" align="right" valign="top"><b><b><b>Supply of Flats :</b> </td>
									  <td width="30%" align="left">
										 <ul id="flats_config">
										  {foreach $BedroomDetails['Apartment'] as $num}
											<li class="flat_bed">
											  <font color="red"><span class = "err_flat_bed" style = "display:none;">Integer expected</span>
												<br/></font>
											  <label for="flat_bed_{$num}">{$num} Bedroom(s)</label>
											  <input id="flat_bed_{$num}" name="flat_bed_{$num}" style="width: 50px;" value="{$FlatsQuantity[$num]}" />
											  <select multiple="multiple" style="width: 150px; height: 110px;" disabled>
												 {foreach $OptionsDetails as $option}
													 {if $option.BEDROOMS == $num and $option.UNIT_TYPE == 'Apartment'}
													 <option value="{$option.UNIT_NAME}">{$option.UNIT_NAME}</option>
													 {/if}
												 {/foreach}
											  </select>
											</li>
										  {/foreach}
										 </ul>
									  </td>
									  <td width="50%" align="left"></td>
								   </tr>

								   <tr>
									  <td width="20%" align="right" valign="top"><b><b><b>Select Towers :</b> </td>
									  <td width="30%" align="left">
										 <select name="towers[]" id="towers" multiple="multiple" style="width: 150px; height: 110px;">
											<option value="-1">Select Towers</option>
										   {foreach $TowerDetails as $tower}
											   <option value="{$tower.TOWER_ID}" {if $tower.PHASE_ID eq $phaseId}selected{/if}>{$tower.TOWER_NAME}</option>
										   {/foreach}
										 </select>
									  </td>
									  <td width="50%" align="left"></td>
								   </tr>
                               {/if}

                                {if $ProjectDetail[0]['PROJECT_TYPE_ID']==2 || $ProjectDetail[0]['PROJECT_TYPE_ID']==3 || $ProjectDetail[0]['PROJECT_TYPE_ID']==5}
									<tr>
									  <td width="20%" align="right" valign="top"><b><b><b>Supply of Villas :</b> </td>
									  <td width="30%" align="left">
										 <ul id="villa_config">
										  {foreach $BedroomDetails['Villa'] as $num}
											<li class="villa_bed">
											  <font color="red"><span class = "err_villa_bed" style = "display:none;">Integer expected</span>
												<br/></font>
											  <label for="villa_bed_{$num}">{$num} Bedroom(s)</label>
											  <input id="villa_bed_{$num}" name="villa_bed_{$num}" style="width: 50px;" value="{$VillasQuantity[$num]}" />
											  <select multiple="multiple" style="width: 150px; height: 110px;" disabled>
												 {foreach $OptionsDetails as $option}
													 {if $option.BEDROOMS == $num and $option.UNIT_TYPE == 'Villa'}
													 <option value="{$option.UNIT_NAME}">{$option.UNIT_NAME}</option>
													 {/if}
												 {/foreach}
											  </select>
											</li>
										  {/foreach}
										 </ul>
									  </td>
									  <td width="50%" align="left"></td>
								   </tr>
                               {/if}
							   {if $ProjectDetail[0]['PROJECT_TYPE_ID']==4 || $ProjectDetail[0]['PROJECT_TYPE_ID']==5 || $ProjectDetail[0]['PROJECT_TYPE_ID']==6}
									<tr>
										<td width="20%" align="right" valign="top"><b>Supply of Plot  :</b> </td>
										<td width="30%" align="left" nowrap>
											<input type='text' name='supply' id='supply' value='{$PlotQuantity[0]}'>
										</td>
										<td width="50%" align="left">
											<font color="red"><span id = "err_supply" style = "display:none;">Enter the supply for Plot</span></font>
										</td>
									</tr>
									<input type='hidden' name='plotvilla' id='plotvilla' value='Plot'>
							  {/if}  
							   <tr>
								  <td width="20%" align="right" valign="top"><b><b><b>Remarks :</b> </td>
								  <td width="30%" align="left">
									 <textarea name="remark" rows="10" cols="30" id="remark">{$remark}</textarea>
								  </td>
								  <td width="50%" align="left"></td>
							   </tr>
							   
							    <tr>
								  <td width="20%" align="right" valign="top"><b><b><b>Phase Launched :</b> </td>
								  <td width="30%" align="left">
									 <select name = "phaseLaunched">
									 	<option value ="1" {if $phaseLaunched == 1} selected {/if}>Launched</option>
									 	<option value ="0" {if $phaseLaunched == 0} selected {/if}>Not Launched</option>
									 </select>
								  </td>
								  <td width="50%" align="left"></td>
							   </tr>
							   
							   <tr>
								  <td>&nbsp;</td>
									
								  <td align="left" style="padding-left:0px;">
									<input type="submit" name="btnSave" id="btnSave" value="Submit" onclick="return validate_phase();" />
								  
								  {if count($accessModule)>0}
								  	&nbsp;&nbsp;<input type="submit" name="delete" value="Delete" onclick = "return deletePhase();" />
								  {/if}
								  &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit" />
								  </td>
							   </tr>
                               {/if}
                     </form>
				</table>
			</td>
		</tr>
 
</table>
{if isset($phaseId) and $phaseId != -1}
<script type="text/javascript">
    var cals_dict = {
        "launch_date_trigger" : "launch_date",
        "completion_date_trigger" : "completion_date"
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
{/if}
</script>
