<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        var pid = '{$phaseId}';
        $('select#phaseSelect').val(pid);
        toggle_supply_and_option();
    });
    
    $(document).ready(function(){
        $('#isLaunchUnitPhase').change(function(){
            $('.launched').each(function(){
                if($('#isLaunchUnitPhase')[0].checked)$(this).removeAttr('readonly');
                else $(this).attr('readonly', 'true');
            });
        });
    });

    function updateURLParameter(url, param, paramVal) {
        var newAdditionalURL = "";
        var tempArray = url.split("?");
        var baseURL = tempArray[0];
        var additionalURL = tempArray[1];
        var temp = "";
        if (additionalURL) {
            tempArray = additionalURL.split("&");
            for (i = 0; i < tempArray.length; i++) {
                if (tempArray[i].split('=')[0] != param) {
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

        {*var phasename = $('#phaseName').val();
        if (!phasename) {
            $('#err_phase_name').show();
            name_flag = false;
        }
        else {
            $('#err_phase_name').hide();
            name_flag = true;
        }*}

        $('li.flat_bed').each(function() {
            var intRegex = /^\d+$/;
            var input = $(this).find('input');
            if (!$(input).is(":disabled")) {
                var v = input.val();
                var err = $(this).find('span.err_flat_bed');
                if (!intRegex.test(v)) {
                    $(err).show();
                    villa_bed = false;
                }
                else {
                    $(err).hide();
                }
            }
        });

        $('li.villa_bed').each(function() {
            var intRegex = /^\d+$/;
            var input = $(this).find('input');
            if (!$(input).is(":disabled")) {
                var v = input.val();
                var err = $(this).find('span.err_villa_bed');
                if (!intRegex.test(v)) {
                    $(err).show();
                    flat_bed = false;
                }
                else {
                    $(err).hide();
                }
            }
        });

        return name_flag && flat_bed && villa_bed;
    }

    function deletePhase()
    {
            return confirm("Are you sure! you want to delete phase.");
        }

        function toggle_supply_and_option() {
            $(".reset_option_and_supply").click(function() {
                if ($(this).is(".supply_button")) {
                    $(".options_select").show();
                    $(".options_select  select").removeAttr("disabled");
                    $(".supply_select  input").attr("disabled", true);
                    $(".supply_select").hide();
                }
                else {
                    $(".supply_select").show();
                    $(".supply_select  input").removeAttr("disabled");
                    $(".options_select  select").attr("disabled", true);
                    $(".options_select").hide();
                }
                return false;
            });

            $(".select_all_options").change(function() {
                if ($(this).is(":checked")) {
                    $("#options > option").attr("selected", true);
                    $("#options > option[value=\"-1\"]").attr("selected", false);
                }
                else {
                    $("#options > option").attr("selected", false);
                }
                return false;
            });

            $("#options").change(function() {
                var all_select = $("#options").find("option:selected").not("option[value='-1']").length == $("#options").
                        find("option").not("option[value='-1']").length;
                if (all_select) {
                    $(".select_all_options").attr("checked", true);
                }
                else {
                    $(".select_all_options").attr("checked", false);
                }
                return false;
            });
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
                                                    <input id="phaseName" name="phaseName" value="{$phasename}" {if $phaseObject.PHASE_TYPE == 'Logical'} readonly {/if} />
                                                </td>
                                                <td width="50%" align="left">
                                                    <font color="red"><span id="err_phase_name" style = "display:none;">Enter Phase Name</span></font>
                                                </td>
                                            </tr>
                                             <tr>
                                                <td width="20%" align="right"><b>Booking Status :</b> </td>
                                                <td width="30%" align="left">
                                                    <select id="bookingStatus" name="bookingStatus">
                                                        <option value="-1">Select Status</option>
                                                        {foreach $bookingStatuses as $b}
                                                            <option value="{$b->id}" {if $b->id == $bookingStatus}selected="selected" {/if}>{$b->display_name}</option>
                                                        {/foreach}
                                                    </select>
                                                </td>
                                                <td width="50%" align="left"></td>
                                            </tr>

                                         {if $phaseObject.PHASE_TYPE != 'Logical'}
                                            <tr>
                                                <td width="20%" align="right" valign="top"><b>Launch Date  :</b> </td>
                                                <td width="30%" align="left">
                                                    <input name="launch_date" value="{$launch_date}" type="text" class="formstyle2" id="launch_date" readonly="1" size="10" />  <img src="../images/cal_1.jpg" id="launch_date_trigger" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
                                                </td>
                                                <td width="50%" align="left">
                                                    <font color="red"><span id = "err_launch_date" style = "display:none;">Enter Launch Date</span></font>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="20%" align="right" valign="top"><b>Completion Date :</b> </td>
                                                <td width="30%" align="left">
                                                    <input name="completion_date" value="{$completion_date}" type="text" class="formstyle2" id="completion_date" readonly="1" size="10" />  <img src="../images/cal_1.jpg" id="completion_date_trigger" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
                                                </td>
                                                <td width="50%" align="left">
                                                    <font color="red"><span id = "err_completion_date" style = "display:none;">Enter Completion Date</span></font>
                                                </td>
                                            </tr>
                                        {/if}

                                        <tr>
                                            <td width="20%" align="right" valign="top"><b><b><b>Phase Launched :</b> </td>
                                                        <td width="30%" align="left">
                                                            <input name = "isLaunchUnitPhase" id="isLaunchUnitPhase" type = "checkbox" value = "1" {if $isLaunchUnitPhase == 1} checked {/if}>
                                                        </td>
                                                        <td width="50%" align="left"></td>
                                                        </tr>

                                                        <tr class="options_select" style="display: none">
                                                            <td width="20%" align="right" valign="top"><b><b><b>Select Options :</b> </td>
                                                                        <td width="30%" align="left">
                                                                            <select name="options[]" id="options" multiple="multiple" style="width: 236px; height: 210px;" disabled>
                                                                                <option value="-1" {if count($phase_options) <= 0}selected="selected"{/if}>Select Option</option>
                                                                                {foreach $options as $option}
                                                                                    <option {if in_array($option->options_id, $option_ids) && count($phase_options) > 0}selected="selected"{/if} value="{$option->options_id}">{$option->option_name} - {$option->size} sqft - {$option->option_type}</option>
                                                                                {/foreach}
                                                                            </select>
                                                                        </td>
                                                                        <td width="50%" align="left">
                                                                            <button class="reset_option_and_supply option_button">Change to supply</button>
                                                                            <br><br><strong>Select all options:</strong> <input type="checkbox" class="select_all_options">
                                                                        </td>
                                                                        </tr>

                                                                        {if $ProjectDetail[0]['PROJECT_TYPE_ID']==1 || $ProjectDetail[0]['PROJECT_TYPE_ID']==3 || $ProjectDetail[0]['PROJECT_TYPE_ID']==6}
                                                                            <tr class="supply_select">
                                                                                <td width="20%" align="right" valign="top"><b><b><b>Supply of Flats :</b> </td>
                                                                                            <td width="50%" align="left">
                                                                                                <ul id="flats_config">
                                                                                                    {foreach $bedrooms_hash['Apartment'] as $num}
                                                                                                        <li class="flat_bed">
                                                                                                            <font color="red"><span class = "err_flat_bed" style = "display:none;">Integer expected</span>
                                                                                                            <br/></font>
                                                                                                            <label for="flat_bed_{$num}">{$num} Bedroom(s)</label>
                                                                                                            <input id="flat_bed_{$num}" name="flat_bed_{$num}[supply]" style="width: 50px;" value="{$FlatsQuantity[$num]['supply']}" />
                                                                                                            <label>Launched</label>
                                                                                                            <input id="flat_bed_{$num}" {if !$isLaunchUnitPhase}readonly="true"{/if} name="flat_bed_{$num}[launched]" class="launched" style="width: 50px;" value="{$FlatsQuantity[$num]['launched']}" />
                                                                                                            <select multiple="multiple" style="width: 150px; height: 110px;" disabled>
                                                                                                                {foreach $OptionsDetails as $option}
                                                                                                                    {if $option.BEDROOMS == $num and $option.OPTION_TYPE == 'Apartment' and in_array($option.OPTIONS_ID, $option_ids)}
                                                                                                                        <option value="{$option.OPTION_NAME}">{$option.OPTION_NAME}</option>
                                                                                                                    {/if}
                                                                                                                {/foreach}
                                                                                                            </select>
                                                                                                        </li>
                                                                                                    {/foreach}
                                                                                                </ul>
                                                                                            </td>
                                                                                            {if $phaseObject['PHASE_TYPE'] != 'Logical'}
                                                                                                <td width="50%" align="left">
                                                                                                    <button class="reset_option_and_supply supply_button">Change to options</button>
                                                                                                </td>
                                                                                            {/if}
                                                                                            </tr>
                                                                                                <tr {if $phaseObject['PHASE_TYPE'] == 'Logical'} style="display: none;" {/if}>
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
                                                                                                            <tr class="supply_select">
                                                                                                                <td width="20%" align="right" valign="top"><b><b><b>Supply of Villas :</b> </td>
                                                                                                                            <td width="30%" align="left">
                                                                                                                                <ul id="villa_config">
                                                                                                                                    {foreach $bedrooms_hash['Villa'] as $num}
                                                                                                                                        <li class="villa_bed">
                                                                                                                                            <font color="red"><span class = "err_villa_bed" style = "display:none;">Integer expected</span>
                                                                                                                                            <br/></font>
                                                                                                                                            <label for="villa_bed_{$num}">{$num} Bedroom(s)</label>
                                                                                                                                            <input id="villa_bed_{$num}" name="villa_bed_{$num}[supply]" style="width: 50px;" value="{$VillasQuantity[$num]['supply']}" />
                                                                                                                                            <label>Launched</label>
                                                                                                                                            <input id="villa_bed_{$num}" {if !$isLaunchUnitPhase}readonly="true"{/if} name="villa_bed_{$num}[launched]" class="launched" style="width: 50px;" value="{$VillasQuantity[$num]['launched']}" />
                                                                                                                                            <select multiple="multiple" style="width: 150px; height: 110px;" disabled>
                                                                                                                                                {foreach $OptionsDetails as $option}
                                                                                                                                                    {if $option.BEDROOMS == $num and $option.OPTION_TYPE == 'Villa' and in_array($option.OPTIONS_ID, $option_ids)}
                                                                                                                                                        <option value="{$option.OPTION_NAME}">{$option.OPTION_NAME}</option>
                                                                                                                                                    {/if}
                                                                                                                                                {/foreach}
                                                                                                                                            </select>
                                                                                                                                        </li>
                                                                                                                                    {/foreach}
                                                                                                                                </ul>
                                                                                                                            </td>
                                                                                                                            
                                                                                                                            {if $phaseId != '0'}
                                                                                                                            <td width="50%" align="left">
                                                                                                                                <button class="reset_option_and_supply supply_button">Change to options</button>
                                                                                                                            {/if}
                                                                                                                            </td>
                                                                                                                            </tr>
                                                                                                                        {/if}
                                                                                                                        {if $ProjectDetail[0]['PROJECT_TYPE_ID']==4 || $ProjectDetail[0]['PROJECT_TYPE_ID']==5 || $ProjectDetail[0]['PROJECT_TYPE_ID']==6}
                                                                                                                            <tr>
                                                                                                                                <td width="20%" align="right" valign="top"><b>Supply of Plot  :</b> </td>
                                                                                                                                <td width="30%" align="left" nowrap>
                                                                                                                                    <input type='text' name='supply' id='supply' value='{$PlotQuantity[0]['supply']}'>
                                                                                                                                    <label>Launched</label>
                                                                                                                                    <input id="supply" {if !$isLaunchUnitPhase}readonly="true"{/if} name="launched" class="launched" style="width: 50px;" value="{$PlotQuantity[0]['launched']}" />
                                                                                                                                </td>
                                                                                                                                <td width="50%" align="left">
                                                                                                                                    <font color="red"><span id = "err_supply" style = "display:none;">Enter the supply for Plot</span></font>
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            <input type='hidden' name='plotvilla' id='plotvilla' value='Plot'>
                                                                                                                        {/if}  
                                                                                                                        {if $phaseId != '0'}
                                                                                                                        <tr>
                                                                                                                            <td width="20%" align="right" valign="top"><b><b><b>Remarks :</b> </td>
                                                                                                                                        <td width="30%" align="left">
                                                                                                                                            <textarea name="remark" rows="10" cols="30" id="remark">{$remark}</textarea>
                                                                                                                                        </td>
                                                                                                                                        <td width="50%" align="left"></td>
                                                                                                                                        </tr>
                                                                                                                                        {/if}

                                                                                                                                        <tr>
                                                                                                                                            <td>&nbsp;</td>

                                                                                                                                            <td align="left" style="padding-left:0px;">
                                                                                                                                                <input type="submit" name="btnSave" id="btnSave" value="Submit" onclick="return validate_phase();" />

                                                                                                                                                {if $specialAccess == 1 && $phaseObject.PHASE_TYPE != 'Logical'}
                                                                                                                                                    &nbsp;&nbsp;<input type="submit" name="delete" value="Delete" onclick = "return deletePhase();" />
                                                                                                                                                {/if}
                                                                                                                                                &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit" />
                                                                                                                                            </td>
                                                                                                                                        </tr>
                                                                                                                                    {else}
                                                                                                                                        <tr>
                                                                                                                                            <td>&nbsp;</td>
                                                                                                                                            <td align="left" style="padding-left:0px;">
                                                                                                                                                &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit" />
                                                                                                                                            </td>
                                                                                                                                        </tr>
                                                                                                                                    {/if}
                                                                                                                                    </form>
                                                                                                                                    </table>
                                                                                                                                    </td>
                                                                                                                                    </tr>

                                                                                                                                    </table>

<script type="text/javascript">                                                                                                                                    {if isset($phaseId) and !in_array($phaseId, array('-1', '0'))}
                                                                                                                              
        var cals_dict = {
            "launch_date_trigger": "launch_date",
            "completion_date_trigger": "completion_date"
        };

        $.each(cals_dict, function(k, v) {
            if ($('#' + k).length > 0) {
                Calendar.setup({
                    inputField: v, // id of the input field
                    //    ifFormat       :    "%Y/%m/%d %l:%M %P",         // format of the input field
                    ifFormat: "%Y-%m-%d", // format of the input field
                    button: k, // trigger for the calendar (button ID)
                    align: "Tl", // alignment (defaults to "Bl")
                    singleClick: true,
                    showsTime: true
                });
            }
        });
                                                                                                                                        {/if}                                                                                                                            </script>
