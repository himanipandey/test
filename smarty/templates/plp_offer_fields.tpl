			{for $k=1 to 24}
							<table style="border:1px solid#ccc;padding:5px;{if $k<=$noi}display:block{else}display:none{/if}" id="inst-{$k}">
								<tr><th colspan=2>Instalment-{$k}</th></tr>
								<tr>
								  <td>
									<div style="float:left">
								      <b><font color = "red">*</font>Instalment Period : </b>&nbsp;&nbsp;&nbsp;&nbsp;
								    </div>
								    <div id="plpMnth-wrapper-{$k}" style="display:{if ($k==1) || ($k==$noi)}none{/if}">
								       	<input type="radio" name="plp_period_{$k}" value="months" onclick="populate_offer_desc()" {if is_numeric($plp_arr['offer_period'][$k])}checked{/if}/>
										 <input type="text" size="2"  maxlength="2" id="plp_Months_{$k}" name="plp_Months_{$k}" onkeyup="populate_offer_desc()" style="width:50px" onkeypress='return isNumberKey(event)' value="{if is_numeric($plp_arr['offer_period'][$k])}{$plp_arr['offer_period'][$k]}{/if}"/>
										 Months 
									</div>
									<div id="plpMnth-txt-wrapper-{$k}" style="display:{if ($k==1) || ($k==$noi)}block{else}none{/if}">{if $k==1}Now{else}Possession{/if}</div>
								  </td>
								  <td>
								    &nbsp;&nbsp;&nbsp;&nbsp;
								  </td>
								</tr>
								<tr>
								<td>
								    <b><font color = "red">*</font>Instalment Price : </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="plp_price_{$k}" value="percent" onclick="populate_offer_desc()" {if $plp_arr['offer_price_type'][$k]=='Percent'}checked{/if}/> 
								     <input type="text" size="3"  maxlength="3"  name="plp_Per_{$k}" id="plp_Per_{$k}" onkeyup="populate_offer_desc()" style="width:50px" onkeypress='return isNumberKey(event)' value="{if $offer_price_type=='Percent'}{$plp_arr['offer_price'][$k]}{/if}"/>
								     Percent 
								  </td>
								  <td>
								    &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="plp_price_{$k}" value="deci" onclick="populate_offer_desc()" {if $plp_arr['offer_price_type'][$k]=='Absolute'}checked{/if} />
								    <input type="text" name="plp_Deci_{$k}" id="plp_Deci_{$k}" style="width:50px" onkeyup="populate_offer_desc()" value="{if $plp_arr['priceDeciUnit'][$k]}{$plp_arr['offer_price'][$k]}{/if}"/>
								     <select id="plp_Unit_{$k}" name="plp_Unit_{$k}" onchange="populate_offer_desc()">
									  <option value="Lakhs" {if $plp_arr['priceDeciUnit'][$k]=='Lakhs'}selected{/if} >Lakhs</option>
									  <option value="Crores" {if $plp_arr['priceDeciUnit'][$k]=='Crores'}selected{/if} >Crores</option>
									  <option value="Thousands" {if $plp_arr['priceDeciUnit'][$k]=='Thousands'}selected{/if} >Thousands</option>
									  <option value="Hundreds" {if $plp_arr['priceDeciUnit'][$k]=='Hundreds'}selected{/if} >Hundreds</option>
								    </select>
								  </td>
								</tr>
							  </table>
			{/for}				
