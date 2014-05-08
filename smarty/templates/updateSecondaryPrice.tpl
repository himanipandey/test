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
                                  Update Secondary Price for {$projectDetails[0].BUILDER_NAME} {$projectDetails[0].PROJECT_NAME}
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
                  <td align ="left" valign ="top" colspan="2"  style = "padding-left:80px;">
                    <table align="left" style = "border:1px solid;">
                       <form method="post">
                        <tr bgcolor = '#F7F7F7'>
                          <td nowrap align ="left" valign ="top" colspan="2" style = "padding-left:80px;">
                              <b>Broker: </b>&nbsp;&nbsp;
                              <select name = "brokerId" id = "brokerSearch">
                                <option value="">Select Broker</option>
                                {foreach from= $allBrokerByProject key=k item = val}
                                    <option value="{$k}" {if $k == $brokerId} selected{/if}>
                                        {$val[0]['BROKER_NAME']}
                                    </option>
                                {/foreach}
                             </select>
                        </td>
                      </tr>
                      <tr bgcolor = '#F7F7F7'>
                          <td align ="left" valign ="top" colspan="2" style = "padding-left:80px;" >
                        <b>Phase :</b>&nbsp;&nbsp;&nbsp;<select id="phaseSelect" name="phaseSelect">
                                                <option value="-1">Select Phase</option>
                                                {foreach $phases as $p}
                                                    <option value="{$p.id}" {if $arrBrokerPriceByProject[0]['PHASE_ID'] == $p.id || $phaseSelect == $p.id}selected{/if}>{$p.name}</option>
                                                {/foreach}
                                            </select>
                      </td>
                      </tr>
                        <tr bgcolor = '#FCFCFC'>
                            <td align ="left" valign ="top" colspan="2"  style = "padding-left:80px;">
                              <b>Year:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <select name ="year" id = "year">
                                  <option value="">Select Year</option>
                                  {section name=year start={$startYear} loop={$endYear} step=1}
                                    
                                    <option value="{$smarty.section.year.index}" {if $smarty.section.year.index == $year} selected{/if}>{$smarty.section.year.index}</option>
                                  {/section}
                              </select>
                            </td>
                        </tr>
                       
                        <tr bgcolor = '#F7F7F7'>
                            <td align ="left" valign ="top" colspan="2"  style = "padding-left:80px;">
                              <b>Month:</b>&nbsp;
                              
                              <select name ="month" id = "month">
                                  <option value="">{$month}Select Month</option>
                                  <option value="01" {if $month == "01"} selected{/if}>Jan</option>
                                  <option value="02" {if $month == "02"} selected{/if}>Feb</option>
                                  <option value="03" {if $month == "03"} selected{/if}>March</option>
                                  <option value="04" {if $month == "04"} selected{/if}>April</option>
                                  <option value="05" {if $month == "05"} selected{/if}>May</option>
                                  <option value="06" {if $month == "06"} selected{/if}>June</option>
                                  <option value="07" {if $month == "07"} selected{/if}>July</option>
                                  <option value="08" {if $month == "08"} selected{/if}>Aug</option>
                                  <option value="09" {if $month == "09"} selected{/if}>Sept</option>
                                  <option value="10" {if $month == "10"} selected{/if}>Oct</option>
                                  <option value="11" {if $month == "11"} selected{/if}>Nov</option>
                                  <option value="12" {if $month == "12"} selected{/if}>Dec</option>
                              </select>
                            </td>
                        </tr>
                        <tr class="headingrowcolor" height="30px;">
                            <td class="whiteTxt" align ="right" valign ="top" colspan="2"  style = "padding-left:80px;">
                                <input type = "submit" name = "search" value="Search" onclick = "return blankChk();">
                                &nbsp;
                                <input type = "hidden" name = "projectId" id = "projectId" value = "{$projectId}">
                                <input type="submit" name="btnExit" id="btnExit" value="Exit">
                            </td>
                        </tr>
                      </form>
                      </table>
                  </td>
                </tr>
                  {if $brokerId != ''}
                      {if count($arrBrokerPriceByProject)>0}
                        <form name ="frm" method = "post">
                            <tr bgcolor = '#F7F7F7'>
                               <td align ="left" valign ="top" colspan="2" style = "padding-left:80px;">
                                 <b>Effective Date:</b>&nbsp; {$arrBrokerPriceByProject[0]['EFFECTIVE_DATE']} 
                                 <b>Last Modified Date:</b>&nbsp; {$arrBrokerPriceByProject[0]['LAST_MODIFIED_DATE']} 
                               </td>
                           </tr>      
                           {if $errorPrice != ''}
                               <tr bgcolor = '#FCFCFC'>
                               <td align ="left" valign ="top" colspan="2" style = "padding-left:80px;">
                                 {$errorPrice}
                               </td>
                           </tr>
                           {/if}
                           <tr bgcolor = '#F7F7F7'>
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
                                                <input onkeypress="return isNumberKey(event);" type = "text" id = "minPrice_{$cnt}" name = "minPrice[]" value="{if $arrBrokerPriceByProject[$val]['MIN_PRICE'] != ''}{trim($arrBrokerPriceByProject[$val]['MIN_PRICE'])}{else}{$arrMinPrice[$val]}{/if}">
                                            </td>
                                            <td  valign ="top" style ="padding-left: 10px;" align = "left">
                                                <input onkeypress="return isNumberKey(event);" onkeyup = "meanCalculate(this.value,{$cnt});" 
                                                     maxlength = '10' type = "text" id = "maxPrice_{$cnt}" name = "maxPrice[]" value="{if $arrBrokerPriceByProject[$val]['MAX_PRICE'] != ''}{trim($arrBrokerPriceByProject[$val]['MAX_PRICE'])}{else}{$arrMaxPrice[$val]}{/if}">
                                            </td>
                                            <td style ="padding-left: 10px;" align = "left">
                                                <div id = "mean_{$cnt}">
                                               {if $arrBrokerPriceByProject[$val]['MAX_PRICE'] != ''}
                                                   {($arrBrokerPriceByProject[$val]['MAX_PRICE']+$arrBrokerPriceByProject[$val]['MIN_PRICE'])/2}
                                               {else}
                                                   {if $arrMeanPrice[$val] !=''}     
                                                        {$arrMeanPrice[$val]}
                                                    {else}
                                                        --
                                                    {/if}
                                               {/if}
                                            </div>
                                            </td>    
                                        </tr>
                                     {/foreach}
                                     <tr class="headingrowcolor" height="30px;">
                                         <td class="whiteTxt" colspan = "4" align ="center">
                                             <input type = "hidden" name = "projectId" id = "projectId" value = "{$projectId}">
                                             <input type = "hidden" name = "effectiveDt" id = "effectiveDt" value = "{$effectiveDt}">
                                             <input type = "hidden" name = "brokerId" id = "brokerId" value = "{$brokerId}">
                                             <input type = "hidden" name = "month" value = "{$month}">
                                             <input type = "hidden" name = "year" value = "{$year}">
                                              <input type = "hidden" name = "phaseSelect" value = "{$phaseSelect}">
                                             <input type="submit" name="submit"  value="Submit" onclick = "return validation();">
                                             <input type="submit" name="btnExit" id="btnExit" value="Exit">
                                         </td>
                                     </tr>

                                 </table>
                            </td>
                         </tr>
                    </form>
                    {else}
                            <tr>
                                <td style = "padding-left:70px"align = "left" colspan="2">
                                    <font color = "red">
                                      Sorry No Records Found!
                                    </font>
                                </td>
                            </tr>
                    {/if}
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
    function blankChk(){
        if($("#brokerSearch").val() == ''){
            alert("Please select broker!");
            return false;
        }
        if($("#phaseSelect").val() == '' || $("#phaseSelect").val() == '-1'){
            alert("Please select Phase");
            return false;
        }
        if($("#year").val() == ''){
            alert("Please select year!");
            return false;
        }
        if($("#month").val() == ''){
            alert("Please select month!");
            return false;
        }
    }
</script>
      
