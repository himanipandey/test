<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>
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
                                  Insert Secondary Price for {$projectDetails[0].BUILDER_NAME} {$projectDetails[0].PROJECT_NAME}
                              </TD>
                              <TD width="33%" align ="right"></TD>
                            </TR>
                        </TBODY>
                  </TABLE>
                </TD>
	      </TR>
	      
	      <TD vAlign="top" align="middle" class="backgorund-rt" height="450"><BR>
			 
            <table cellSpacing="1" cellPadding="4" width="67%" align="center" style="border:1px solid;">
              <tr bgcolor = '#F7F7F7'>
                  <td nowrap align ="center" valign ="top" colspan="3" style = "padding-left:20px;">
                      <b>Broker: </b><select name = "brokerId" onchange = refreshBroker(this.value,{$projectId});>
                        <option value="">Select Broker</option>
                        {foreach from= $allBrokerByProject key=k item = val}
                            <option value="{$k}" {if $k == $brokerId} selected{/if}>
                                {$val[0]['BROKER_NAME']}
                            </option>
                        {/foreach}
                    </select>
                </td>
              </tr>
              {if count($arrPType) == 0}
                <tr bgcolor = '#F7F7F7'>
                  <td align ="left" valign ="top" colspan="2"  style = "padding-left:310px;">
                      <font color="red">
                          First add property before price updation!
                      </font>
                  </td>
                </tr>
                <form method = "post">
                <tr class="headingrowcolor" height="30px;">
                    <td class="whiteTxt" colspan = "2" align ="center">
                        <input type = "hidden" name = "projectId" id = "projectId" value = "{$projectId}">
                        <input type="submit" name="btnExit" id="btnExit" value="Exit">
                    </td>
                </tr>
                </form>
             {/if}
              {if $brokerId != ''}
                  <form name ="frm" method = "post">
                  <tr bgcolor = '#FCFCFC'>
					   <td align ="right" valign ="top"  >
				             <b>Phase :</b>&nbsp;<select id="phaseSelect" name="phaseSelect" onchange="change_phase();">
                                                <option value="-1">Select Phase</option>
                                                {foreach $phases as $p}
                                                    <option value="{$p.id}" {if $currPhaseId == $p.id}selected{/if}>{$p.name}</option>
                                                {/foreach}
                                            </select>
                      </td>
                
                      <td align ="left" valign ="top">
                        <b>Effective Date:</b>&nbsp;<input name="effectiveDate" value="{$effectiveDate}" type="text" class="formstyle2" id="f_date_c_from" size="5" /> 
                        <img src="images/cal_1.jpg" id="f_trigger_c_from" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
                      </td>
                  </tr>
       {if $currPhaseId != ''}
                  {if count($arrBrokerPriceByProject)>0}
                   <tr bgcolor = '#FCFCFC'>
                      <td align ="left" valign ="top" colspan="2"  style = "padding-left:200px;">
                        <b>Effective Date:</b>&nbsp; {$arrBrokerPriceByProject[0]['EFFECTIVE_DATE']} 
                        <b>Last Modified Date:</b>&nbsp; {$arrBrokerPriceByProject[0]['LAST_MODIFIED_DATE']} 
                      </td>
                  </tr>  
                  {/if}
                  {if $errorPrice != ''}
                      <tr bgcolor = '#F7F7F7'>
                        <td align ="left" valign ="top" colspan="2"  style = "padding-left:310px;">
                          {$errorPrice}
                        </td>
                      </tr>
                  {/if}
                  <tr bgcolor = '#FCFCFC'>
                  <td align ="left" valign ="top" colspan="2"  style = "padding-left:20px;">
                        <table align="center" style = "border:1px solid;">
                            <tr class ="headingrowcolor" height="30px">
                                 <th style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>Project Type</b></th>
                                 <th nowrap style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>Min Price (per sqft)</b></th>
                                 <th nowrap style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>Max Price (per sqft)</b></th>
                                 <th nowrap style ="padding-left: 10px;" class ="whiteTxt" align = "left"><b>Mean (per sqft)</b></th>
                            </tr>
                            
                            {$cnt = 0}
                            {foreach from= $arrPType key=k item = val}
                                {$cnt = $cnt+1}
                                {if $cnt%2 == 0}
                                    {$bgcolor = '#F7F7F7'}
                                {else}
                                    {$bgcolor = '#FCFCFC'}
                                {/if}
                                 <form method = "post" action = "">
                                <tr bgcolor = "{$bgcolor}" height="30px">
                                   <td style = "padding-left:10px;" valign ="top" align = "left">
                                       {$val}
                                       <input type = "hidden" name = "unitType[]" value ="{$val}">
                                   </td>
                                   <td valign ="top" style ="padding-left: 10px;" align = "left">
                                       <input onkeypress="return isNumberKey(event);" type = "text" id = "minPrice_{$cnt}" name = "minPrice[]" value="{if $arrBrokerPriceByProject[$k]['MIN_PRICE'] != ''}{trim($arrBrokerPriceByProject[$k]['MIN_PRICE'])}{else}{$arrMinPrice[$k]}{/if}">
                                   </td>
                                   <td  valign ="top" style ="padding-left: 10px;" align = "left">
                                       <input onkeypress="return isNumberKey(event);" onkeyup = "meanCalculate(this.value,{$cnt});" 
                                            maxlength = '10' type = "text" id = "maxPrice_{$cnt}" name = "maxPrice[]" value="{if $arrBrokerPriceByProject[$k]['MAX_PRICE'] != ''}{trim($arrBrokerPriceByProject[$k]['MAX_PRICE'])}{else}{$arrMaxPrice[$k]}{/if}">
                                   </td>
                                   <td style ="padding-left: 10px;" align = "left">
                                    <div id = "mean_{$cnt}">
                                        {if $arrBrokerPriceByProject[$k]['MAX_PRICE'] != ''}
                                            {($arrBrokerPriceByProject[$k]['MAX_PRICE']+$arrBrokerPriceByProject[$k]['MIN_PRICE'])/2}
                                        {else}
                                            {if $arrMeanPrice[$k] !=''}     
                                                 {$arrMeanPrice[$k]}
                                             {else}
                                                 --
                                             {/if}
                                        {/if}
                                   </div>
                                   </td>    
                               </tr>
                            {/foreach}
                   {/if}
                            <tr class="headingrowcolor" height="30px;">
                                <td class="whiteTxt" colspan = "4" align ="center">
                                    <input type = "hidden" name = "projectId" id = "projectId" value = "{$projectId}">
                                    <input type = "hidden" name = "brokerId" id = "brokerId" value = "{$brokerId}">
                                    <input type="submit" name="submit"  value="Submit" onclick = "return validation();">
                                    <input type="submit" name="btnExit" id="btnExit" value="Exit">
                                </td>
                        </tr>
                       
                        </table>
                   </td>
                </tr>
                 </form>
                      <script>
                        var cals_dict = {
                            "f_trigger_c_from" : "f_date_c_from",
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
                    </script>
                {/if}
                <tr><td colspan ="8">&nbsp;</td><tr>
            </table>
</div>
<script>
	 
    function isNumberKey(evt){
       var charCode = (evt.which) ? evt.which : event.keyCode;
          if(charCode == 99 || charCode == 118)
          return true;
       if (charCode > 31 && (charCode < 46 || charCode > 57))
          return false;
       return true;
    }
    function  meanCalculate(maxPrice,rowCnt){
        var minPrice = $("#minPrice_"+rowCnt).val();
        var mean     = (parseInt(maxPrice)+parseInt(minPrice))/2;   
    $("#mean_"+rowCnt).html(mean);
    }
    function validation(){
        if(($("#f_date_c_from").val() == '')  || ($("#f_date_c_from").val() == '0000-00-00')){
            alert("Effective date cant blank!");
            return false;
        }
        else
            return true;
    }
    function refreshBroker(brokerId,projectId){
         window.location.assign("insertSecondaryPrice.php?projectId="+projectId+"&brokerId="+brokerId);
    }
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
</script>
      
