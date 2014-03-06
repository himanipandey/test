<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>
</TD>
  </TR>
  <TR>
    <TD class="white-bg paddingright10" vAlign=top align=middle bgColor=#ffffff>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><TBODY>
        <TR>
          <TD width=224 height=25>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=866>&nbsp;</TD>
		</TR>
        <TR>
          <TD class=paddingltrt10 vAlign=top align=middle bgColor=#ffffff>
        {include file="{$PROJECT_ADD_TEMPLATE_PATH}left.tpl"}
		  </TD>
          <TD class=border-rt vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>Add/Edit/Delete Project Offers ({$projectDetail[0]['BUILDER_NAME']} {$projectDetail[0]['PROJECT_NAME']})</TD>
                      <TD align=right ></TD>
                    </TR>
				  </TBODY></TABLE>
			    </TD>
             </TR>
             <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height="450"><BR>
                  <TABLE cellSpacing=2 cellPadding=4 width="93%" align=center border=0>
					{if count($offerDetails)<10 || $offerId != ''}
					<form method="post" enctype="multipart/form-data">
			          <div>
                        {if $ErrorMsg["offerType"] != ''}
                           <tr><td colspan = "2" align ="center"><font color = "red">{$ErrorMsg["offerType"]}</font></td></tr>
                        {/if}
                        <tr>
                          <td width="20%" align="right" ><font color = "red">*</font>Offer Type : </td>
                            <td width="80%" align="left">
								<select name="offerType" id="offerType" style="width:357px;">
								  {if !$currOffer}<option value="">--Select Offer--</option>{/if}
								  {foreach from=$arrOfferTypes key=key item=val}
								    {if $currOffer==$key}
										<option value="{$key}" {if $key==$currOffer}selected{/if}>{$val}</option>
									{/if}
									{if !$currOffer}
										<option value="{$key}">{$val}</option>
									{/if}
								  {/foreach}
								</select>
							</td>
						</tr>
						<tr>
						  <td>&nbsp;</td>
						  <td>
						    <div id="field-group-1" style=" {if $currOffer == 'NoPreEmi'}display:block{else}display:none{/if}">
							  <table>
								<tr>
								  <td>
								    <b><font color = "red">*</font>No EMI Period : </b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="no_emi_period"  value="months" onclick="populate_offer_desc()" {if is_numeric($offer_period)}checked{/if} />
								    <select id="no_emi_Months" name="no_emi_Months" onchange="populate_offer_desc()">
									  {for $val=1 to 10}<option value="{$val*6}" {if $offer_period==($val*6)}selected{/if}>{$val*6}</option>{/for}
								    </select> Months 
								  </td>
								  <td>
								    &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="no_emi_period" value="pos" {if !is_numeric($offer_period) && isset($offer_period)}checked{/if} onclick="populate_offer_desc()"/> Till Possession
								  </td>
								</tr>
								<tr>
								<td>
								    <b>&nbsp;&nbsp;No EMI Price : </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="percent" name="no_emi_price" onclick="populate_offer_desc()" {if $offer_price_type=='Percent'}checked{/if} /> 
								    <select id="no_emi_price_emiPer" name="no_emi_price_emiPer" onchange="populate_offer_desc()">
									  <option value="">&nbsp;</option>
									  {for $val=1 to 20}<option value="{$val*5}" {if $offer_price==($val*5)}selected{/if}>{$val*5}</option>{/for}
								    </select>Percent 
								  </td>
								  <td>
								    &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="no_emi_price" value="deci"  {if $offer_price_type=='Absolute'}checked{/if} onclick="populate_offer_desc()"/>
								    <input type="text" id="no_emi_price_emiDeci" name="no_emi_price_emiDeci" style="width:50px" onKeyUp="populate_offer_desc()" value="{if $priceDeciUnit}{$offer_price}{/if}"/>
								     <select id="no_emi_price_emiUnit" name="no_emi_price_emiUnit"  onclick="populate_offer_desc()">
									  <option value="Lakhs"  {if $priceDeciUnit=='Lakhs'}selected{/if} >Lakhs</option>
									  <option value="Crores" {if $priceDeciUnit=='Crores'}selected{/if} >Crores</option>
									  <option value="Thousands" {if $priceDeciUnit=='Thousands'}selected{/if} >Thousands</option>
								    </select>
								  </td>
								</tr>
							  </table>
						    </div>
						    <div id="field-group-2" style="{if $currOffer == 'PartEmi'}display:block{else}display:none{/if}">
							  <table>
								<tr>
								  <td>
								    <b><font color = "red">*</font>EMI Period : </b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="part_emi_period" value="months" onclick="populate_offer_desc()" {if is_numeric($offer_period)}checked{/if}/>
								    <select id="part_emiMonths" name="part_emiMonths" onchange="populate_offer_desc()">
									  {for $val=1 to 10}<option value="{$val*6}" {if $offer_period==($val*6)}selected{/if}>{$val*6}</option>{/for}
								    </select> Months 
								  </td>
								  <td>
								    &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="part_emi_period" value="pos" onclick="populate_offer_desc()" {if !is_numeric($offer_period) && isset($offer_period)}checked{/if}/> Possession
								  </td>
								</tr>
								<tr>
								<td>
								    <b><font color = "red">*</font>EMI Price : </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="part_emi_price" value="percent" onclick="populate_offer_desc()" {if $offer_price_type=='Percent'}checked{/if}/> 
								    <select  name="part_emi_price_emiPer" id="part_emi_price_emiPer" onchange="populate_offer_desc()">
										<option value=""></option>Othe
									  {for $val=1 to 20}<option value="{$val*5}" {if $offer_price==($val*5)}selected{/if}>{$val*5}</option>{/for}
								    </select>Percent 
								  </td>
								  <td>
								    &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="part_emi_price" value="deci" onclick="populate_offer_desc()" {if $offer_price_type=='Absolute'}checked{/if} />
								    <input type="text" name="part_emi_price_emiDeci" id="part_emi_price_emiDeci" style="width:50px" onkeyup="populate_offer_desc()" value="{if $priceDeciUnit}{$offer_price}{/if}"/>
								     <select id="part_emi_price_emiUnit" name="part_emi_price_emiUnit" onchange="populate_offer_desc()">
									  <option value="Lakhs" {if $priceDeciUnit=='Lakhs'}selected{/if} >Lakhs</option>
									  <option value="Crores" {if $priceDeciUnit=='Crores'}selected{/if} >Crores</option>
									  <option value="Thousands" {if $priceDeciUnit=='Thousands'}selected{/if} >Thousands</option>
								    </select>
								  </td>
								</tr>
							  </table>
						    </div>
						    <div id="field-group-3" style="{if $currOffer == 'NoCharges'}display:block{else}display:none{/if}">
						      <table>
							    <tr>
								  <td>
									  <input type="radio" {if ("PLC"==$discount_on)}checked{/if} name="nac_discount_on"  id="nac_plc" value="PLC" onclick="populate_offer_desc()"/>PLC <br/>
									  <input type="radio"  {if ("Parking"==$discount_on)}checked{/if}  name="nac_discount_on" id="nac_parking" value="Parking" onclick="populate_offer_desc()"/>Parking <br/>
									  <input type="radio"  {if ("ClubMembership"==$discount_on)}checked{/if}  name="nac_discount_on" id="nac_clubMembership" value="ClubMembership" onclick="populate_offer_desc()"/>Club Membership <br/>
									  <input type="radio"  {if ("GymMembership"==$discount_on)}checked{/if}  name="nac_discount_on" id="nac_gymMembership" value="GymMembership" onclick="populate_offer_desc()" />Gym Membership <br/>
									  <input type="radio"  {if $other_text}checked{/if}  name="nac_discount_on" id="nac_other" value="Other" onclick="populate_offer_desc()"/>Other <br/>
									  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name="nac_other_txt" id="nac_other_txt" style="width:300px;{if $other_text} display:block{else}display:none{/if}" onkeyup="populate_offer_desc()" value="{$other_text}" />
								  </td>
								</tr>
							  </table>
						    </div>
						    <div id="field-group-4" style="{if $currOffer == 'PriceDiscount'}display:block{else}display:none{/if}">
						      <table>
							    <tr>
							      <td>
									  <b><font color="red">*</font>PriceDiscount Amount: </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="pd_price" value="percent" onclick="populate_offer_desc()" {if $offer_price_type=='Percent'}checked{/if} /> 
								    <select name="pd_price_emiPer" id="pd_price_emiPer" onchange="populate_offer_desc()">
										<option value="">&nbsp;</option>
									  {for $val=1 to 20}<option value="{$val*5}"  {if $offer_price==($val*5)}selected{/if}>{$val*5}</option>{/for}
								    </select>Percent 
								  </td>
								  <td>
								    &nbsp;&nbsp;<input type="radio" name="pd_price" value="deci" onclick="populate_offer_desc()" {if $offer_price_type=='Absolute'}checked{/if} />
								    <input type="text" name="pd_price_emiDeci" id="pd_price_emiDeci" style="width:50px" onkeyup="populate_offer_desc()" value="{if $priceDeciUnit}{$offer_price}{/if}"/>
								     <select id="pd_price_emiUnit" name="pd_price_emiUnit" onchange="populate_offer_desc()">
									  <option value="Lakhs" {if $priceDeciUnit=='Lakhs'}selected{/if} >Lakhs</option>
									  <option value="Crores" {if $priceDeciUnit=='Crores'}selected{/if} >Crores</option>
									  <option value="Thousands" {if $priceDeciUnit=='Thousands'}selected{/if} >Thousands</option>
								    </select>
							      </td>
							    </tr>
							    <tr>
							      <td>
									  <b>PriceDiscount On: </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									  <select id="pd_on" name="pd_on" onchange="populate_offer_desc()">
										<option value="">--Select--</option>  
									    <option value="Rate" {if ("Rate"==$discount_on)}selected{/if} >Rate</option>
									    <option value="PLC" {if ("PLC"==$discount_on)}selected{/if}>PLC</option>
									    <option value="BookingAmount" {if ("BookingAmount"==$discount_on)}selected{/if}>Booking Amount</option>
									    <option value="ClubMembership" {if ("ClubMembership"==$discount_on)}selected{/if}>Club Charges</option>
									    <option value="Parking" {if ("Parking"==$discount_on)}selected{/if}>Parking</option>
									    <option value="GymMembership" {if ("GymMembership"==$discount_on)}selected{/if}>Gym Membership</option>
									    <option value="Other" {if $other_text}selected{/if}>Other</option>
									  </select>
									   <br/>
									  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									  <input type="text" name="pd_other_txt" id="pd_other_txt"  style="width:300px;{if $other_text} display:block{else}display:none{/if}" value="{$other_text}" onkeyup="populate_offer_desc()"/>
							      </td>
							    </tr>
							    <tr>
							      <td>
									  <b>PriceDiscount Date:  </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									  <input onchange="populate_offer_desc()" value="{$discount_date}" name="pd_date" value="{$pd_date}" type="text" class="formstyle2" id="pd_date" readonly="1" size="10" />  <img src="../images/cal_1.jpg" id="pd_date_trigger" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
							      </td>
							    </tr>
						      </table>
						    </div>						    
						  </td>
						</tr>
						<tr>
                          <td width="20%" align="right" ><font color = "red">*</font>Description : </td>
                            <td width="30%" align="left">
								<textarea name="offerDesc" id="offerDesc" rows="5" cols="50">{$offer_desc}</textarea>
							</td>
                            {if $ErrorMsg["offerDesc"] != ''}
                               <td width="50%" align="left" nowrap><font color = "red">{$ErrorMsg["offerDesc"]}</font></td>{else} <td width="50%" align="left"></td>
                            {/if}
						</tr>
						<tr>
						  <td></td>
						  <td>
						    <input type="submit" name="btnSave" id="btnSave" value="Submit" onclick="return validate_offers();" />
						    &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit">
						  </td>
						  <td></td>
						</tr>
							
                      </div>
                    </form>
                    {else}
						<b>You can create only 10 offers per Project.</b>
                    {/if}
                  </TABLE>
                  <br/>
                  <br/>
                  <br/>
                  <TABLE cellSpacing=1 cellPadding=4 width="97%" align=center border=0>
					<TBODY>
						 <TR class = "headingrowcolor">
								<TD class=whiteTxt width=1% align="center">SL</TD>
								 <TD class=whiteTxt width=5% align="center">Offer Type</TD>                          
								<TD class=whiteTxt width=23% align="left">Offer Desc</TD>
								<TD class=whiteTxt width=5% align="center">ACTION</TD>
						  </TR>
						  {if $offerDetails}
							  {$count = 0}
								{foreach from=$offerDetails item=data}
								{$count = $count+1}
								{if $count%2 == 0}

									  {$color = "bgcolor = '#F7F7F7'"}
								{else}                       			
									  {$color = "bgcolor = '#FCFCFC'"}
								{/if}
								 <TR {$color}>
									<TD>{$count}</TD>
									<TD>{$arrOfferTypes[$data->offer]}</TD>
									<TD>{$data->offer_desc}</TD>
									<TD><a id="edit_offer" href="project_offers.php?projectId={$projectId}&edit=edit&v={$data->id}" title="Edit" >Edit</a> &nbsp;&nbsp;|&nbsp;&nbsp;<a class="delete_offer" id="{$data->id}" href="#" title="Delete" >Delete</a></TD>
								</TR>
							{/foreach}
						 {else}
						   <tr>
							<td colspan=4>No Record Found.</td>
						   </tr>
						 {/if}
					</TBODY>
				</TABLE>
                  
                  
                  
			   </TD>
            </TR>
          </TBODY></TABLE>
     </TD>
   </TR>
</TBODY></TABLE>
<script type="text/javascript">  
	function validate_offers(){
		
	  var intRegex = /^\d+$/;
	  var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
	  
	  $flag = 1;
	  
	  offer_type = $('#offerType').val();
	  offerDesc  = $('#offerDesc').val();
	
	  if(offer_type == ''){
		alert("Offer Type is required!");
		return false;
	  }else if(offer_type == 'NoPreEmi'){   //NoPreEmi validation
	    noEmiDeci = $("input[name='no_emi_price_emiDeci']").val();
	    
		if($("input[name='no_emi_period']:checked").val() == undefined ){
			alert("EMI Period is required.");
			return false;
		}else if($("input[name='no_emi_price']:checked").val() == 'deci') {
			if((intRegex.test(noEmiDeci) || floatRegex.test(noEmiDeci))){
				 if(noEmiDeci <= 0 || noEmiDeci > 99.9){
					alert("Emi Price value must not be less than 1 and greater than 99.9");
					return false;
				}
			}else{
				alert("Please enter a valid EMI Price.");
				return false;
			}
		}else if($("input[name='no_emi_price']:checked").val() == 'percent' && $("select[name='no_emi_price_emiPer']").val() == ''){
			alert("Please select EMI in Percent.");
			return false;
		}
	  }else if(offer_type == 'PartEmi'){ //PartEmi validations
	    partEmiDeci = $("input[name='part_emi_price_emiDeci']").val();
		if($("input[name='part_emi_period']:checked").val() == undefined ){
			alert("EMI Period is required.");
			return false;
		}else if($("input[name='part_emi_price']:checked").val() == undefined ){
			alert("EMI Price is required.");
			return false;
		}else if($("input[name='part_emi_price']:checked").val() == 'percent' && $("select[name='part_emi_price_emiPer']").val() == ''){
			alert("Please select EMI in Percent.");
			return false;
		}else if($("input[name='part_emi_price']:checked").val() == 'deci'){
			if(intRegex.test(partEmiDeci) || floatRegex.test(partEmiDeci)){
			  if(partEmiDeci <= 0 || partEmiDeci > 99.9){
				alert("Emi Price value must not be less than 1 and greater than 99.9");
				return false;
			  }
			}else{
				alert("Please enter a valid EMI Price.");
				return false;
			}							
		}        
      }else if(offer_type == 'NoCharges'){ //NoCharges validations
		  if(!$("input[name='nac_discount_on']:checked").val()){
			alert("Please check at least one checkbox.");
			return false;
		  }else if($('#nac_other').attr('checked') && $('#nac_other_txt').val().trim() == ''){
		    alert("Please enter text for Other.");
		    return false;
		 }
		  
	  }else if(offer_type == 'PriceDiscount'){ // PriceDiscount Validation
		  pdEmiDeci = $("input[name='pd_price_emiDeci']").val();
		 if($("input[name='pd_price']:checked").val() == undefined ){
			alert("Discount Amount is required.");
			return false;
		}else if($("input[name='pd_price']:checked").val() == 'percent' && $("select[name='pd_price_emiPer']").val() == ''){
			alert("Please select Discount Amount. in Percent.");
			return false;
		}else if(($("input[name='pd_price']:checked").val() == 'deci' && pdEmiDeci <= 0) || ($("input[name='pd_price']:checked").val() == 'deci' && (!intRegex.test(pdEmiDeci) || !floatRegex.test(pdEmiDeci)))){
			alert("Discount Amount value must be numeric & greater than 0");
				return false;					
		}else if($('#pd_on').val() == '' && $('#pd_date').val() == ''){
			alert("Please select Discount On or Discount Date.");
			return false;
		}else if($('#pd_on').val() == 'Other' && $('#pd_other_txt').val().trim() == ''){
			alert("Please enter text in Other textbox");
			return false;
		}       
	  }	  
	  if(offerDesc.trim() == ''){
		alert("Offer Description is required!");
		return false;
	  }
	  
		return true;
	  
	}
	$(document).ready(function(){
	  $('#offerType').change(function(){
		$('#offerDesc').val("");  
		offer_type = $(this).val() ;
		if(offer_type == 'NoPreEmi'){
			$('#field-group-1').show();
			$('#field-group-2').hide();
			$('#field-group-3').hide();
			$('#field-group-4').hide();
		}else if(offer_type == 'PartEmi'){
			$('#field-group-1').hide();
			$('#field-group-2').show();
			$('#field-group-3').hide();
			$('#field-group-4').hide();
		}else if(offer_type == 'NoCharges'){
			
			$('#field-group-1').hide();
			$('#field-group-2').hide();
			$('#field-group-3').show();
			$('#field-group-4').hide();
		}else if(offer_type == 'PriceDiscount'){
			$('#field-group-1').hide();
			$('#field-group-2').hide();
			$('#field-group-3').hide();
			$('#field-group-4').show();
		}else{
			$('#field-group-1').hide();
			$('#field-group-2').hide();
			$('#field-group-3').hide();
			$('#field-group-4').hide();
		}			
	  });
		  
	  ////// extra textboxes hanelding
	  $('input[name="nac_discount_on"]').click(function(){
		  if($(this).val()=='Other')
			$('#nac_other_txt').show();
		  else
		   $('#nac_other_txt').hide();
	  })
	  	  
	  $('#pd_on').change(function(){
		if($(this).val() == 'Other')
		  $('#pd_other_txt').show();
		else
		  $('#pd_other_txt').hide();
	  })
	 	 
	  $('#offerDesc').focusin(function(){
		populate_offer_desc();
	  });
	  
	  $('#pd_date').click(function(){
		$(this).val("");populate_offer_desc();  
	  });
	  
	  //delete offer
	  
	  $('.delete_offer').click(function(){
		offer_id = $(this).attr('id');
		var r=confirm("Are you sure");
		if(r==true)
			window.location = "project_offers.php?projectId={$projectId}&edit=delete&v=" + offer_id;
		
	  });
	    
	  
	});
	 /////////////////Auto Populate Description box handeling//////////////
	function populate_offer_desc(){
		 offer_type = $('#offerType').val();
		offer_desc = '';
	    if(offer_type == 'NoPreEmi'){   //----------------NoPreEmi Descirption Population
		  noEmiPeriod = '';noEmiPrice = '';
		  //fetching Emi Period Value
		  if($("input[name='no_emi_period']:checked").val() == 'months')
			noEmiPeriod = $('#no_emi_Months').val() + " months";
		  else if($("input[name='no_emi_period']:checked").val() == 'pos')
			noEmiPeriod = "Possession";
		 //fetching Emi Price Value
		  if($("input[name='no_emi_price']:checked").val() == 'percent')
			noEmiPrice = $('#no_emi_price_emiPer').val() + "%";
		  else if($("input[name='no_emi_price']:checked").val() == 'deci')
			noEmiPrice = $('#no_emi_price_emiDeci').val() +" "+$('#no_emi_price_emiUnit').val();
				
		  if($("input[name='no_emi_period']:checked").val() && $("input[name='no_emi_price']:checked").val()){
			offer_desc = "Pay "+noEmiPrice+" now and avail No Pre-EMI till "+noEmiPeriod;
		  }else if($("input[name='no_emi_period']:checked").val()){
			offer_desc = "No Pre-EMI till " + noEmiPeriod;
		  }
		    $('#offerDesc').val(offer_desc);  
		}else  if(offer_type == 'PartEmi'){   //----------------PartEmi Descirption Population
		  partEmiPeriod = '';partEmiPrice = '';
		  //fetching Emi Period Value
		  if($("input[name='part_emi_period']:checked").val() == 'months')
			partEmiPeriod = "after "+ $('#part_emiMonths').val() + " months";
		  else if($("input[name='part_emi_period']:checked").val() == 'pos')
			partEmiPeriod = "on Possession";
		 //fetching Emi Price ValueofferType
		  if($("input[name='part_emi_price']:checked").val() == 'percent')
			partEmiPrice = $('#part_emi_price_emiPer').val() + "%";
		  else if($("input[name='part_emi_price']:checked").val() == 'deci')
			partEmiPrice = $('#part_emi_price_emiDeci').val() +" "+$('#part_emi_price_emiUnit').val();
				
		  if($("input[name='part_emi_period']:checked").val() && $("input[name='part_emi_price']:checked").val()){
			offer_desc = "Pay "+partEmiPrice+" now and rest "+partEmiPeriod;
		  }
		    $('#offerDesc').val(offer_desc);  
		}else if(offer_type == 'NoCharges'){   //----NoCharges Descirption Population
		  	str = new Array();cnt=0;popped='';
		  	if($('#nac_plc').attr('checked'))
		  	  str[cnt++] = $('#nac_plc').val();
		  	if($('#nac_parking').attr('checked'))
		  	  str[cnt++] = $('#nac_parking').val();
		  	if($('#nac_clubMembership').attr('checked'))
		  	  str[cnt++] = $('#nac_clubMembership').val();
		  	if($('#nac_gymMembership').attr('checked'))
		  	  str[cnt++] = $('#nac_gymMembership').val();
		  	if($('#nac_other').attr('checked'))
		  	  str[cnt++] = $('#nac_other_txt').val();
		  
			if(str.length > 1){
			 popped = str.pop();
			 popped = " and " + popped;
			}
		  	offer_desc = "Book Now & pay zero charges for " + str.join(",") + popped;
			$('#offerDesc').val(offer_desc);  
		}else if(offer_type == 'PriceDiscount'){   //----PriceDiscount Descirption Population
			amount='';discount_on='';date='';	
			//fetching Amounts
			if($("input[name='pd_price']:checked").val() == 'percent')
			   amount = $('#pd_price_emiPer').val() + "%";
			else if($("input[name='pd_price']:checked").val() == 'deci')
			   amount = $('#pd_price_emiDeci').val() +" "+$('#pd_price_emiUnit').val();
			//fetching Discount on 
			if($('#pd_on').val()=='Other')
				discount_on = $('#pd_other_txt').val();
			else if($('#pd_on').val()!='')
				discount_on = $('#pd_on :selected').html();
			//Fetching Date On
			if($('#pd_date').val() != '0000-00-00')
				date = $('#pd_date').val();
			
			if(amount && discount_on && date)
				offer_desc = "Book by "+date+" and save "+amount+" on "+discount_on;
			else if(amount && discount_on)
				offer_desc = "Book Now and save "+amount+" on "+discount_on;
			else if(amount && date)
				offer_desc = "Book by "+date+" and save " +amount;
				
			$('#offerDesc').val(offer_desc);  
		}		
	}
</script>
<script type="text/javascript">             
                                                                                                                         
        var cals_dict = {
            "pd_date_trigger": "pd_date"
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
   
 </script>
