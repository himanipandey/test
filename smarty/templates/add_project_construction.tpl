<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>
<script>
    function change_phase() {
        var new_id = $('#phaseSelect').val();
        var newURL = updateURLParameter(window.location.href, 'phaseId', new_id);
        window.location.href = newURL;
    }
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

    function construction_status_validation(EffectiveDateList)
    {
        var flag = 'no';
        if ($("#phaseSelect").val() == -1) {
            $("#err_phaseSelect").show();
            flag = 'yes';
        }
        else {
            $("#err_phaseSelect").hide();
        }
        if ($("#remark").val() == '')
        {
            $("#err_edit_reson").show();
            flag = 'yes';
        }
        else
        {
            $("#err_edit_reson").hide();
        }

        if ($("#month_expected_completion").val() == '' || $("#year_expected_completion").val() == '')
        {
            $("#err_date").show();
            flag = 'yes';
        }
        else
        {
            $("#err_date").hide();
        }

        if ($("#year_effective_date").val() == '' && $("#month_effective_date").val() == '')
        {
            $("#err_date_effective").show();
            flag = 'yes';
        }
        else
        {
            $("#err_date_effective").hide();
        }


        if (flag == 'yes')
            return false;
        else {
            if ($("#month_effective_date").val().length == 1)
                var month = "0" + $("#month_effective_date").val();
            else
                var month = $("#month_effective_date").val();
            var valueToSearch = $("#year_effective_date").val() + "-" + month;
            var searchVal = EffectiveDateList.search(valueToSearch);
            if (searchVal != -1) {
                var updateOldData = confirm("Are you sure! you want to update completion date which already exists on same effective date.");
                if (updateOldData == false)
                    $("#updateOrInsertRow").val(0);
                else
                    $("#updateOrInsertRow").val(1);
            }
            return true;
        }
    }
    function showhistory(plsmns)
    {
        if (plsmns == 'plus')
        {
            document.getElementById("plusMinusImg").innerHTML = "<a href = 'javascript:void(0);' onclick = showhistory('minus');><img src = '../images/minus.jpg' width ='20px'></a>";
            document.getElementById("history_showHide").style.display = '';
        }
        else
        {
            document.getElementById("plusMinusImg").innerHTML = "<a href = 'javascript:void(0);' onclick = showhistory('plus');><img src = '../images/plus.jpg' width ='20px'></a>";
            document.getElementById("history_showHide").style.display = 'none';
        }
    }
</script>
<script>
    $(document).ready(function () {
        hist = "{$hist_update}";
        histerrorMsg = "{$histerrorMsg}";
        if (hist || histerrorMsg) {
            document.getElementById("plusMinusImg").innerHTML = "<a href = 'javascript:void(0);' onclick = showhistory('minus');><img src = '../images/minus.jpg' width ='20px'></a>";
            document.getElementById("history_showHide").style.display = '';
        }

    });
</script>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>{$SITETITLE}</title>
    <link href="{$FORUM_SERVER_PATH}css/css.css" rel="stylesheet" type="text/css">
    {if isset($photoCSS) && $photoCSS==1}
        <link href="{$FORUM_SERVER_PATH}css/photo.css" rel="stylesheet" type="text/css">
    {/if}
    <script language="javascript" src="{$FORUM_SERVER_PATH}js/jquery/jquery-1.4.4.min.js"></script>
    <link rel="stylesheet" type="text/css" media="all" href="{$FORUM_SERVER_PATH}jscal/skins/aqua/theme.css" title="Aqua" />
    <!-- <link href="{$FORUM_SERVER_PATH}css/calendar.css" rel="stylesheet" type="text/css">
    <link href="{$FORUM_SERVER_PATH}css/picker.css" rel="stylesheet" type="text/css"> -->
    <!-- <script language="javascript" src="{$FORUM_SERVER_PATH}js/calendar.js"></script>
    <script language="javascript" src="{$FORUM_SERVER_PATH}js/picker.js"></script>

    -->

</head>
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

                </TD>
                <TD vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</TD>
                <TD vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
                    <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
                            <TR>
                                <TD colspan="2" class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                                    <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0>
                                        <TBODY>
                                            <TR>
                                                <TD class="h1" width="67%"><img height="18" hspace="5" src="../images/arrow.gif" width="18">Promised Completion Date({ucwords($fetch_projectDetail[0]['PROJECT_NAME'])})</TD>
                                                <TD width="33%" align ="right"class="h1"></TD>   

                                            </TR>
                                        </TBODY>
                                    </TABLE>
                                </TD>

                            </TR>
                            <tr></tr>
                        <TD nowrap vAlign="top" align="left" class="backgorund-rt" height="450"><BR>

                            <table cellSpacing="1" cellPadding="4" width="67%" align="center" border="0">
                                <form method="post" id="formss" enctype="multipart/form-data">

                                    {if count($errorMsg)>0}
                                        <tr>
                                            <td colspan="2" nowrap><font color = "red">
                                                {foreach from = $errorMsg item = item key = key}
                                                    {$item}<br>
                                                {/foreach}
                                                </font></td>
                                        </tr>
                                    {/if}
                                    <tr>
                                        <td nowrap width="20%" align="right"><b>Phase :</b> </td>
                                        <td nowrap width="30%" align="left">
                                            <select id="phaseSelect" name="phaseSelect" onchange="change_phase();">
                                                <option value="-1">Select Phase</option>
                                                {foreach $phases as $p}
                                                    <option value="{$p.id}" {if $p.id == $phaseId}selected{/if}>{$p.name}</option>
                                                {/foreach}
                                            </select>
                                        </td>
                                        <td width="50%" align="left" nowrap>
                                            <font color="red">
                                            <span id = "err_phaseSelect" style = "display:none;">Please select phase!</span></font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="20%" align="right"><b>Construction Status :</b> </td>
                                        <td width="30%" align="left">                                                   
                                            <select name="construction_status" id="construction_status" class="fieldState">
                                                {foreach from = $projectStatus key = key item = value}
                                                    <option value="{$key}" {if $key == $construction_status} selected {/if}>{$value} </option>
                                                {/foreach}
                                            </select>
                                        </td>
                                        <td width="50%" align="left">
                                            <font color="red"><span id="err_construction_status" style = "display:none;">Select Construction Status</span></font>
                                        </td>
                                    </tr>
                                    {if $phaseId != ''}
                                        <tr>
                                            <td nowrap width="20%" align="right"><b>Launch Date :</b> </td>
                                            <td nowrap width="30%" align="left">
                                                {$launchDate}
                                                <input type="hidden" name = "launchDate" value="{$launchDate}">
                                            </td>
                                            <td width="50%" align="left" nowrap>
                                                &nbsp;
                                            </td>
                                        </tr>

                                        <tr>
                                            <td nowrap width="20%" align="right"><b>Pre Launch Date :</b> </td>
                                            <td nowrap width="30%" align="left">
                                                {$pre_launch_date}
                                                <input type="hidden" name = "pre_launch_date" value="{$pre_launch_date}">
                                            </td>
                                            <td width="50%" align="left" nowrap>
                                                &nbsp;
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="20%" align="right" valign="top" nowrap><b><font color ="red">*</font>Expected Completion Date :</b> </td>
                                            <td width="30%" align="left">
                                                Month:<select name = "month_expected_completion" id = "month_expected_completion">
                                                    <option value="">Select Month</option>
                                                    {foreach from = $months key = key item = item}
                                                        <option value="{$key}"
                                                                {if $month_expected_completion == $key} selected {/if}>{$item}</option>
                                                    {/foreach}
                                                </select>

                                                Year:<select name = "year_expected_completion" id = "year_expected_completion">
                                                    <option value="">Select Year</option>
                                                    {section name=foo start=$YearStart loop=$yearEnd step=1}
                                                        <option value="{$smarty.section.foo.index}" 
                                                                {if $year_expected_completion == $smarty.section.foo.index} selected {/if}>
                                                            {$smarty.section.foo.index}</option>
                                                        {/section}
                                                </select> 
                                            </td>

                                            <td width="50%" align="left" nowrap>
                                                <font color="red">
                                                <span id = "err_date" style = "display:none;">Please select month and year for expected delivery date!</span></font>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="20%" align="right" valign="top" nowrap><b><font color ="red">*</font>Date Effective From :</b> </td>
                                            <td width="30%" align="left" nowrap>
                                                Month:<select name = "month_effective_date" id = "month_effective_date">
                                                    <option value="">Select Month</option>
                                                    {foreach from = $months key = key item = item}
                                                        <option value="{$key}"
                                                                {if $month_effective_date == $key} selected {/if}>{$item}</option>
                                                    {/foreach}
                                                </select>

                                                Year:<select name = "year_effective_date" id = "year_effective_date">
                                                    <option value="">Select Year</option>
                                                    {section name=foo start=$YearStart loop=$yearEnd step=1}
                                                        <option value="{$smarty.section.foo.index}"
                                                                {if $year_effective_date == $smarty.section.foo.index} selected {/if}>{$smarty.section.foo.index}</option>
                                                    {/section}
                                                </select> 
                                            </td>

                                            <td width="50%" align="left" nowrap>
                                                <font color="red">
                                                <span id = "err_date_effective" style = "display:none;">Please select month and year for effective date!</span></font>
                                            </td>
                                        </tr>
                                        {if $compHistAuth==1}
                                            <tr>
                                                <td width="20%" align="right" valign="top"><b>Update Histroy :</b></td>
                                                <td width="80%" align="left">
                                                    <span id = "plusMinusImg">
                                                        <a href = "javascript:void(0);" onclick = "showhistory('plus');">
                                                            <img src = "images/plus.jpg" width ="20px">
                                                        </a>

                                                    </span>
                                                    {if $hist_update && count($histerrorMsg)==0}
                                                        <font color = "green">History updated successfuly.</font>
                                                    {/if}
                                                    {if count($histerrorMsg)>0}
                                                        <font color = "red">																 	{foreach from = $histerrorMsg item = item key = key}
                                                        {$item}<br>
                                                        {/foreach}
                                                            </font>

                                                            {/if}
                                                            </td>
                                                            <td width="50%" align="left">
                                                                &nbsp;						 
                                                            </td>
                                                        </tr>	
                                                        {/if}
                                                            <tr id = "history_showHide" style = "display:none;">
                                                                <td width="20%" align="right" valign="top">&nbsp;</td>
                                                                <td width="30%" align="left" colspan=2>
                                                                    <table style="width:700px;border:1px solid#aaa">
                                                                        <tr><td style="background:#555">&nbsp;</td>
                                                                            <td style="padding-left:100px;color:#fff;background:#555"><b>Effective Date</b></td>
                                                                            <td style="padding-left:100px;color:#fff;background:#555"><b>Completion Date</b></td>
                                                                        </tr>														 
                                                                        {foreach from = $costDetail key=keys item = items}
                                                                            {$sub_month = $items['SUBMITTED_DATE']|date_format:"%b"}
                                                                            {$sub_year = $items['SUBMITTED_DATE']|date_format:"%Y"}
                                                                            {$exp_month = $items['EXPECTED_COMPLETION_DATE']|date_format:"%b"}
                                                                            {$exp_year = $items['EXPECTED_COMPLETION_DATE']|date_format:"%Y"}
                                                                            <tr>
                                                                                <td>
                                                                                    {if in_array($items['EXPECTED_COMPLETION_ID'],$hist_update_arr)}<img src = "images/ok.png" width ="20px">{/if}
                                                                                    {if $error_id == $items['EXPECTED_COMPLETION_ID']}<img src = "images/exclamation.png" width ="20px">{/if}
                                                                                </td>
                                                                                <td>																	

                                                                                    Month:<select name = "hist_month_eff[]" id = "hist_month_eff_{$keys}">
                                                                                        <option value="">Select Month</option>
                                                                                        {foreach from = $months key = key item = item}
                                                                                            <option value="{$key}"
                                                                                                    {if $sub_month == $item} selected {/if}>{$item}</option>
                                                                                        {/foreach}
                                                                                    </select>

                                                                                    Year:<select name = "hist_year_eff[]" id = "hist_year_eff_{$keys}">
                                                                                        <option value="">Select Year</option>
                                                                                        {section name=foo start=$YearStart loop=$yearEnd step=1}
                                                                                            <option value="{$smarty.section.foo.index}"
                                                                                                    {if $sub_year == $smarty.section.foo.index} selected {/if}>{$smarty.section.foo.index}</option>
                                                                                        {/section}
                                                                                    </select>
                                                                                </td>
                                                                                <td>


                                                                                    Month:<select name = "hist_month_comp[]" id="hist_month_comp_{$keys}">
                                                                                        <option value="">Select Month</option>
                                                                                        {foreach from = $months key = key item = item}
                                                                                            <option value="{$key}"
                                                                                                    {if $exp_month == $item} selected {/if}>{$item}</option>
                                                                                        {/foreach}
                                                                                    </select>

                                                                                    Year:<select name = "hist_year_comp[]" id = "hist_year_comp_{$keys}">
                                                                                        <option value="">Select Year</option>
                                                                                        {section name=foo start=$YearStart loop=$yearEnd step=1}
                                                                                            <option value="{$smarty.section.foo.index}"
                                                                                                    {if $exp_year == $smarty.section.foo.index} selected {/if}>{$smarty.section.foo.index}</option>
                                                                                        {/section}
                                                                                    </select>
                                                                                </td>

                                                                            </tr>
                                                                        {/foreach}
                                                                        <tr>
                                                                            <td colspan=3 style="text-align:center;background:#ccc"> 
                                                                                <input type="submit" name="btnHistSave" id="btnHistSave" value="Save History"  />
                                                                            </td>																
                                                                        </tr>
                                                                    </table>
                                                                </td>                                                     
                                                            </tr>
                                                            <tr>
                                                                <td width="20%" align="right" valign="top"><b><font color ="red">*</font><b>Remarks :</b> </td>
                                                                <td width="30%" align="left">
                                                                    <textarea name="remark" rows="10" cols="30" id="remark">{$submitted_remark}</textarea>
                                                                </td>
                                                                <td width="50%" align="left">
                                                                    <font color="red"><span id = "err_edit_reson" style = "display:none;">Please enter reason for updating expected completion date</span></font>  								 
                                                                </td>
                                                            </tr>				 

                                                            <tr>
                                                                <td width="10%">&nbsp;</td>
                                                                <td width="90%" align='left' colspan='2'>
                                                                    <input type = "hidden" name = "updateOrInsertRow" id = "updateOrInsertRow">
                                                                    <input type = "hidden" name = "oldCompletionDate" id = "oldCompletionDate" value="{$oldCompletionDate}">
                                                                    <input type="submit" name="btnSave" id="btnSave" value="Submit"  onclick="return construction_status_validation('{$EffectiveDateList}');"/>
                                                                </td> 
                                                            </tr>
                                                            {/if}
                                                                </div>

                                                        </table>
                                                        </form>
                                                    </TD>

                                        </TR>
                                        <tr><td colspan="2">&nbsp;</td></tr>

                                    </TABLE>
