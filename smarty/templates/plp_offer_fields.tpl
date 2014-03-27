			{for $k=1 to 24}
							<table style="border:1px solid#ccc;padding:5px;{if $k<=$noi}display:block{else}display:none{/if}" id="inst-{$k}">
								<tr><th colspan=2>Installment-{$k}</th></tr>
								<tr>
								  <td>
								    <b><font color = "red">*</font>EMI Period : </b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="plp_period_{$k}" value="months" onclick="populate_offer_desc()" {if is_numeric($plp_arr['offer_period'][$k])}checked{/if}/>
								    <select id="plp_Months_{$k}" name="plp_Months_{$k}" onchange="populate_offer_desc()">
									  {for $val=1 to 15}<option value="{$val*3}" {if $plp_arr['offer_period'][$k]==($val*3)}selected{/if}>{$val*3}</option>{/for}
								    </select> Months 
								  </td>
								  <td>
								    &nbsp;&nbsp;&nbsp;&nbsp;
								  </td>
								</tr>
								<tr>
								<td>
								    <b><font color = "red">*</font>EMI Price : </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="plp_price_{$k}" value="percent" onclick="populate_offer_desc()" {if $plp_arr['offer_price_type'][$k]=='Percent'}checked{/if}/> 
								    <select  name="plp_Per_{$k}" id="plp_Per_{$k}" onchange="populate_offer_desc()">
										<option value=""></option>Othe
									  {for $val=1 to 20}<option value="{$val*5}" {if $plp_arr['offer_price'][$k]==($val*5)}selected{/if}>{$val*5}</option>{/for}
								    </select>Percent 
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
