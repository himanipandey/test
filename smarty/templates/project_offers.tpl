<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>
<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<script type="text/javascript">
function archieved_offers(project_id)
{
	//code for builder contact info popup
    var url = "/archieved_offers.php?projectId="+project_id;
   //  jQuery.fancybox({
   //      'href' :  url
   //  });
     $.fancybox({
        'width'                :820,
        'height'               :400,
      
        'href'                 : url,
        'type'                : 'iframe'
    })

}
</script>
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
					<form method="post" enctype="multipart/form-data" id="project-offers-form">
			          <div>
                        {if $ErrorMsg!= ''}
                           <tr><td colspan = "2" align ="left" style="padding-left:120px;font-size:15px"><font color = "red">{$ErrorMsg}</font></td></tr>
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
								</select>								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input class="pt_click" type="button" title="Offers Archive" value="Offers Archive" onclick="return archieved_offers({$projectId});" />
							</td>
						</tr>
						<tr>
						  <td>&nbsp;</td>
						  <td>
						    <div id="field-group-1" style=" {if $currOffer == 'NoPreEmi'}display:block{else}display:none{/if}">
							  <table>
								<tr>
								  <td>
								    <b><font color = "red">*</font>No EMI Period : </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="no_emi_period"  value="months"  {if is_numeric($offer_period)}checked{/if}  onClick="$('#btnSave').attr('disabled',true)"/>
								    <input type="text" size="2"  maxlength="2" id="no_emi_Months" name="no_emi_Months" onkeyup="$('#btnSave').attr('disabled',true)" style="width:50px" onkeypress='return isNumberKey(event)' value="{if is_numeric($offer_period)}{$offer_period}{/if}"/>
								    Months 
								  </td>
								  <td>
								    &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="no_emi_period" value="pos" {if !is_numeric($offer_period) && isset($offer_period)}checked{/if} onClick="$('#btnSave').attr('disabled',true)"/> Till Possession
								  </td>
								</tr>
								<tr>
								<td>
								    <b>&nbsp;&nbsp;To Be Paid Now : </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="percent" name="no_emi_price"  {if $offer_price_type=='Percent'}checked{/if} onClick="$('#btnSave').attr('disabled',true)"/> 
								    <input type="text" size="3"  maxlength="3" id="no_emi_price_emiPer" name="no_emi_price_emiPer" onkeyup="$('#btnSave').attr('disabled',true)" style="width:50px" onkeypress='return isNumberKey(event)' value="{if $offer_price_type=='Percent'}{$offer_price}{/if}"/>
								    Percent 
								  </td>
								  <td>
								    &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="no_emi_price" value="deci"  {if $offer_price_type=='Absolute'}checked{/if} onClick="$('#btnSave').attr('disabled',true)"/>
								    <input type="text" id="no_emi_price_emiDeci" name="no_emi_price_emiDeci" style="width:50px" onKeyUp="$('#btnSave').attr('disabled',true)" value="{if $priceDeciUnit}{$offer_price}{/if}"/>
								     <select id="no_emi_price_emiUnit" name="no_emi_price_emiUnit" onChange = "$('#btnSave').attr('disabled',true)" >
									  <option value="Lakhs"  {if $priceDeciUnit=='Lakhs'}selected{/if} >Lakhs</option>
									  <option value="Crores" {if $priceDeciUnit=='Crores'}selected{/if} >Crores</option>
									 </select>
								  </td>
								</tr>
								<tr>
								  <td>
								    <b>Special BSP : </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" onclick="$('#btnSave').attr('disabled',true)" name="no_emi_special_bsp"  value="special_bsp"  {if is_numeric($bsp)}checked{/if} />
								    <input type="text"  id="no_emi_bsp" size="10"  maxlength="10" name="no_emi_bsp" onkeyup="$('#btnSave').attr('disabled',true)" style="width:50px" onkeypress='return isNumberKey(event)' value="{if is_numeric($bsp)}{$bsp}{/if}"/>
								  </td>
								  <td>&nbsp;</td>
								</tr>
							  </table>
						    </div>
						    <div id="field-group-2" style="{if $currOffer == 'PartEmi'}display:block{else}display:none{/if}">
							  <table>
								<tr>
								  <td>
								    <b><font color = "red">*</font>No. of Installment:</b>
								    <select id="plp_noi" name="plp_noi">
										<option value="">-Select-</option>
										{for $val=2 to 24}<option value="{$val}" {if $noi==$val}selected{/if}>{$val}</option>{/for}
									</select>
								  </td>
								</tr>
								  <td id="plp-fields">
									  {include file='plp_offer_fields.tpl'}
								  </td>
								<tr>
								</tr>
							  </table>
						    </div>
						    <div id="field-group-3" style="{if $currOffer == 'NoCharges'}display:block{else}display:none{/if}">
						      <table>
							    <tr>
								  <td>
									  <input type="checkbox" {if in_array("PLC",$discount_on)}checked{/if} name="nac_discount_on[]"  title="PLC" id="nac_plc" value="PLC" onclick="$('#btnSave').attr('disabled',true)"/>PLC <br/>
									  <input type="checkbox"  {if in_array("Parking",$discount_on)}checked{/if}  title="Parking" name="nac_discount_on[]" id="nac_parking" value="Parking" onclick="$('#btnSave').attr('disabled',true)"/>Parking <br/>
									  <input type="checkbox"  {if in_array("ClubMembership",$discount_on)}checked{/if} title="Club Membership" name="nac_discount_on[]" id="nac_clubMembership" value="ClubMembership" onclick="$('#btnSave').attr('disabled',true)"/>Club Membership <br/>
									  <input type="checkbox"  {if in_array("GymMembership",$discount_on)}checked{/if}  title="Gym Membership" name="nac_discount_on[]" id="nac_gymMembership" value="GymMembership"  onclick="$('#btnSave').attr('disabled',true)" />Gym Membership <br/>
									  <input type="checkbox"  {if in_array("Other",$discount_on)}checked{/if}  name="nac_discount_on[]" id="nac_other" value="Other" onclick="$('#btnSave').attr('disabled',true)" />Other <br/>
									  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name="nac_other_txt" id="nac_other_txt" style="width:300px;{if $other_text} display:block{else}display:none{/if}" onkeyup="$('#btnSave').attr('disabled',true)" value="{$other_text}" />
								  </td>
								</tr>
							  </table>
						    </div>
						    <div id="field-group-4" style="{if $currOffer == 'PriceDiscount'}display:block{else}display:none{/if}">
						      <table>
							    <tr>
							      <td>
									  <b><font color="red">*</font>PriceDiscount Amount: </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="pd_price" value="percent"  {if $offer_price_type=='Percent'}checked{/if} onClick="$('#btnSave').attr('disabled',true)"/>
									  <input type="text" size="3"  maxlength="3" name="pd_price_emiPer" id="pd_price_emiPer" onkeyup="$('#btnSave').attr('disabled',true)" style="width:50px" onkeypress='return isNumberKey(event)' value="{if $offer_price_type=='Percent'}{$offer_price}{/if}"/> 
								   Percent 
								  </td>
								  <td>
								    &nbsp;&nbsp;<input type="radio" name="pd_price" value="deci"  {if $offer_price_type=='Absolute'}checked{/if} onClick="$('#btnSave').attr('disabled',true)"/>
								    <input type="text" name="pd_price_emiDeci" id="pd_price_emiDeci" style="width:50px" onkeyup="$('#btnSave').attr('disabled',true)" value="{if $priceDeciUnit}{$offer_price}{/if}"/>
								     <select id="pd_price_emiUnit" name="pd_price_emiUnit" onchange="$('#btnSave').attr('disabled',true)">
									  <option value="" {if $priceDeciUnit=='none'}selected{/if} >-none-</option>	 
									  <option value="Lakhs" {if $priceDeciUnit=='Lakhs'}selected{/if} >Lakhs</option>
									  <option value="Crores" {if $priceDeciUnit=='Crores'}selected{/if} >Crores</option>						
								    </select>
							      </td>
							    </tr>
							    <tr>
							      <td>
									  <b>PriceDiscount On: </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									  <select id="pd_on" name="pd_on" onchange="$('#btnSave').attr('disabled',true)">
										<option value="">--Select--</option>  
									    <option value="BSP" {if ("BSP"==$discount_on[0])}selected{/if} >BSP</option>
									    <option value="PLC" {if ("PLC"==$discount_on[0])}selected{/if}>PLC</option>
									    <option value="BookingAmount" {if ("BookingAmount"==$discount_on[0])}selected{/if}>Booking Amount</option>
									    <option value="ClubMembership" {if ("ClubMembership"==$discount_on[0])}selected{/if}>Club Charges</option>
									    <option value="Parking" {if ("Parking"==$discount_on[0])}selected{/if}>Parking</option>
									    <option value="GymMembership" {if ("GymMembership"==$discount_on[0])}selected{/if}>Gym Membership</option>
									    <option value="Other" {if $other_text}selected{/if}>Other</option>
									  </select>
									   <br/>
									  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									  <input type="text" name="pd_other_txt" id="pd_other_txt"  style="width:300px;{if $other_text} display:block{else}display:none{/if}" value="{$other_text}" onkeyup="$('#btnSave').attr('disabled',true)"/>
							      </td>
							    </tr>							   
						      </table>
						    </div>						    
						  </td>
						</tr>
						 <tr>
						   <td width="20%" align="right" ><font color = "red">*</font>Offer Validity Date : </td>
						   <td>
							  <input onchange="$('#btnSave').attr('disabled',true)" value="{$offer_date}" name="offer_date" type="text" class="formstyle2" id="offer_date" readonly="1" size="10" />  <img src="../images/cal_1.jpg" id="offer_date_trigger" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
						   </td>
						   <td>&nbsp;</td>
					    </tr>
						<tr>
                          <td width="20%" align="right" ><font color = "red">*</font>Description : </td>
                            <td width="30%" align="left">
								<table>
									<tr>
										<td>
											<textarea name="offerDesc" id="offerDesc" rows="5" cols="50">{$offer_desc}</textarea>
										</td>
										<td>
											<input type="button" value="Generate Offer Description" onclick="populate_offer_desc();$('#btnSave').attr('disabled',false)"/>							
											
										</td>
									</tr>
								</table>
								
							</td>
                            <td>&nbsp;</td>
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
		}else if($("input[name='no_emi_period']:checked").val()=='months' && ($('#no_emi_Months').val()<=0 || $('#no_emi_Months').val()>60)){
			alert("EMI Period must be between 1 to 60 in months.");
			return false;
		}else if($("input[name='no_emi_price']:checked").val() == 'deci') {
			if((intRegex.test(noEmiDeci) || floatRegex.test(noEmiDeci))){
				 if(noEmiDeci <= 0 || noEmiDeci > 99.9){
					alert("To Be Paid Now's value must not be less than 1 and greater than 99.9");
					return false;
				}
			}else{
				alert("Please enter a valid To Be Paid Now's value.");
				return false;
			}
		}else if($("input[name='no_emi_price']:checked").val() == 'percent' && $("#no_emi_price_emiPer").val()<=0 || $("#no_emi_price_emiPer").val()>100){
			alert("To Be Paid Now's value must be between 1 to 100 in percent.");
			return false;
		}else if($("input[name='no_emi_special_bsp']:checked").val()=='special_bsp' && $('#no_emi_bsp').val()==''){
			alert("Please enter BSP.");
			return false;
		}
	  }else if(offer_type == 'PartEmi'){ //PLP validations
	     if($('#plp_noi').val()==''){
			alert('Please select Number of Installments.');
			return false;
		}else{
			inst_price_flag_per = 0;
			inst_price_flag_abs = 0;
			total_per = 0;
		  
				
		   for(i=1;i<=$('#plp_noi').val();i++){	
			   if($("input[name='plp_price_"+i+"']:checked").val() == 'percent')
					inst_price_flag_per = 1;
			   if($("input[name='plp_price_"+i+"']:checked").val() == 'deci')
					inst_price_flag_abs = 1;			   	
				partEmiDeci = $("input[name='plp_Deci_"+i+"']").val();								
				if($("input[name='plp_period_"+i+"']:checked").val() == undefined && i!=1 && i!=$('#plp_noi').val()){
					alert("Installment-"+ i +": Instalment Period is required.");
					return false;
				}else if($("input[name='plp_period_"+i+"']:checked").val() == 'months' && ($("input[name='plp_Months_"+i+"']").val() == '' || $("input[name='plp_Months_"+i+"']").val() <=0 || $("input[name='plp_Months_"+i+"']").val()>60)){
					alert("Installment-"+ i +": Instalment Period must be between 1 to 60.");
					return false;
				}else if($("input[name='plp_price_"+i+"']:checked").val() == undefined ){
					alert("Installment-"+ i +": Instalment price is required.");
					return false;
				}else if($("input[name='plp_price_"+i+"']:checked").val() == 'percent' && ($("input[name='plp_Per_"+i+"']").val() == '' || $("input[name='plp_Per_"+i+"']").val() <=0 || $("input[name='plp_Per_"+i+"']").val()>100)){
					alert("Installment-"+ i +": Instalment Price must be between 1 to 100.");
					return false;
				}else if($("input[name='plp_price_"+i+"']:checked").val() == 'deci'){
					if(intRegex.test(partEmiDeci) || floatRegex.test(partEmiDeci)){
					  if(partEmiDeci <= 0 || partEmiDeci > 99.9){
						alert("Installment-"+ i +": Instalment Price value must not be less than 1 and greater than 99.9");
						return false;
					  }
					}else{
						alert("Installment-"+ i +": Please enter a valid Instalment Price.");
						return false;
					}							
				}    				
				if($("input[name='plp_price_"+i+"']:checked").val() == 'percent' && $("input[name='plp_Per_"+i+"']").val() != ''){
					total_per = parseInt(total_per) + parseInt($("input[name='plp_Per_"+i+"']").val());
				}   
			}
			
			if(inst_price_flag_per && inst_price_flag_abs){
			  alert("All Installment prices must be of same type(Percent or Absolute)");
			  return false;
			}
						
			if(total_per > 0 && total_per != 100){
			  alert("Total Instalment Price must be 100%.");
			  return false;
			}			
		}
      }else if(offer_type == 'NoCharges'){ //NoCharges validations
		  if(!$('#nac_plc').attr('checked') && !$('#nac_parking').attr('checked') && !$('#nac_clubMembership').attr('checked') && !$('#nac_gymMembership').attr('checked') && !$('#nac_other').attr('checked') ){
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
		}else if($("input[name='pd_price']:checked").val() == 'percent' && ($("input[name='pd_price_emiPer']").val()<=0 || $("input[name='pd_price_emiPer']").val()>100 || $("input[name='pd_price_emiPer']").val()== '')){
			alert("Discount Amount must be between 1 to 100 in Percent.");
			return false;
		}else if($("input[name='pd_price']:checked").val() == 'deci' &&  pdEmiDeci.trim()==''){
			alert("Discount Amount value must be numeric & greater than 0");
				return false;					
		}else if($("input[name='pd_price']:checked").val() == 'deci' &&  isNaN(pdEmiDeci)){
			alert("Discount Amount value must be numeric & greater than 0");
				return false;					
		}else if($("input[name='pd_price']:checked").val() == 'deci' &&  pdEmiDeci <= 0){
			alert("Discount Amount value must be numeric & greater than 0");
				return false;					
		}else if($('#pd_on').val() == 'Other' && $('#pd_other_txt').val().trim() == ''){
			alert("Please enter text in Other textbox");
			return false;
		}       
	  }	
	  	
	  	
	  if($('#offer_date').val() == ''){
		alert("Offer Validity Date is required!");
		return false;		  
	  }else if($('#offer_date').val() != ''){
		date = $('#offer_date').val();
		dateArr = date.split("-");
		d1 = new Date(dateArr[1]+"/"+dateArr[0]+"/"+dateArr[2]);
		d2 = new Date();
		if(d1<d2){
		  alert("Offer Validity Date must be future date.");
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
	  $('#nac_other').click(function(){
		  if($(this).attr('checked'))
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
	 	 
	 	  
	  $('#pd_date').click(function(){
		$(this).val("");
	  });
	  
	  //delete offer
	  
	  $('.delete_offer').click(function(){
		offer_id = $(this).attr('id');
		var r=confirm("Are you sure");
		if(r==true)
			window.location = "project_offers.php?projectId={$projectId}&edit=delete&v=" + offer_id;
		
	  });
	  
	  //plp fields population
	  $('#plp_noi').change(function(){
		  $('#offerDesc').val("");  
		  for(i=1; i<=24; i++)
		    $('#inst-'+i+',#plpMnth-txt-wrapper-'+i).css('display','none');
		  noi = $(this).val();
		  for(i=1; i<=noi; i++)
		    $('#inst-'+i+',#plpMnth-wrapper-'+i).css('display','block');		  
		  //first & last Instalment period OFF
		  $('#plpMnth-wrapper-1').css('display','none');
		  $('#plpMnth-wrapper-'+noi).css('display','none');
		  $('#plpMnth-txt-wrapper-1').css('display','block').html("Now");
		  $('#plpMnth-txt-wrapper-'+noi).css('display','block').html("Possession");
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
		  else if($("input[name='no_emi_price']:checked").val() == 'deci'){
			if($('#no_emi_price_emiUnit').val())  
			  noEmiPrice = $('#no_emi_price_emiDeci').val() +" "+$('#no_emi_price_emiUnit').val();
			else
			  noEmiPrice = $('#no_emi_price_emiDeci').val();
		  }
				
		  if($("input[name='no_emi_period']:checked").val() && $("input[name='no_emi_price']:checked").val()){
			offer_desc = "Pay "+noEmiPrice+" now and avail No Pre-EMI till "+noEmiPeriod;
		  }else if($("input[name='no_emi_period']:checked").val()){
			offer_desc = "No Pre-EMI till " + noEmiPeriod;
		  }
		   if($("input[name='no_emi_special_bsp']:checked").val() == 'special_bsp'){
			bsp = $('#no_emi_bsp').val();
			bsp = bsp.split("").reverse();
			final_bsp = $('#no_emi_bsp').val();
			new_bsp = new Array();
			cnt = 1;knt=1;
			if(bsp.length > 3){
				$.each(bsp,function(k,v){
					//alert(v);
					new_bsp[knt] = v;
				  if(cnt%3 == 0 && cnt>=3){
					cnt++;
					knt++;
					new_bsp[knt] = ",";
				  }else{
					cnt++;
				  }
				  knt++;
				  
				});
				if(knt>2){
					new_bsp = new_bsp.reverse();					
					final_bsp = new_bsp.join("");					
					if(final_bsp.charAt(0)==',')
						final_bsp = final_bsp.substr(1, final_bsp.length);
				}
		   }
			
			offer_desc = offer_desc + " " +"at BSP of Rs."+final_bsp+ " per sq.ft.";
		   
		   }
		  
		    $('#offerDesc').val(offer_desc);  
		}else  if(offer_type == 'PartEmi'){   //----------------PLP Descirption Population
			for(i=1;i<=$('#plp_noi').val();i++){		
			  plpPeriod = '';partEmiPrice = '';
			  //fetching Emi Period Value
			  if($("input[name='plp_period_"+i+"']:checked").val() == 'months' && i!=$('#plp_noi').val()){
				 plpPeriod = "after "+ $('#plp_Months_'+i).val() + " months";
			  }else{
				if(i==1)
				  plpPeriod  = "now";
				else if(i==$('#plp_noi').val())
				  plpPeriod  = "on possession";
			  }			  				
			  //fetching Emi Price ValueofferType
			  if($("input[name='plp_price_"+i+"']:checked").val() == 'percent')
				plpPrice = $('#plp_Per_'+i).val() + "%";
			  else if($("input[name='plp_price_"+i+"']:checked").val() == 'deci'){
				if($('#plp_Unit_'+i).val())  
				  plpPrice = $('#plp_Deci_'+i).val() +" "+$('#plp_Unit_'+i).val();
				else
				  plpPrice = $('#plp_Deci_'+i).val();
			  }
					
			  if($("input[name='plp_period_"+i+"']:checked").val() && $("input[name='plp_price_"+i+"']:checked").val() && i!=$('#plp_noi').val()){
				offer_desc = offer_desc+", "+plpPrice+" "+plpPeriod;
			  }else{
				if(i==1 && $("input[name='plp_price_"+i+"']:checked").val())
				 offer_desc = "Pay "+plpPrice+" "+plpPeriod;
				else if(i==$('#plp_noi').val() && $("input[name='plp_price_"+i+"']:checked").val())
				 offer_desc = offer_desc+" and "+plpPrice+" "+plpPeriod;
			  }		
			}
		    $('#offerDesc').val(offer_desc);  
		}else if(offer_type == 'NoCharges'){   //----NoCharges Descirption Population
		  	str = new Array();cnt=0;popped='';
		  	if($('#nac_plc').attr('checked'))
		  	  str[cnt++] = $('#nac_plc').val();
		  	if($('#nac_parking').attr('checked'))
		  	  str[cnt++] = $('#nac_parking').val();
		  	if($('#nac_clubMembership').attr('checked'))
		  	  str[cnt++] = $('#nac_clubMembership').attr('title');
		  	if($('#nac_gymMembership').attr('checked'))
		  	  str[cnt++] = $('#nac_gymMembership').attr('title');
		  	if($('#nac_other').attr('checked'))
		  	  str[cnt++] = $('#nac_other_txt').val();
		  
			if(str.length > 1){
			 popped = str.pop();
			 popped = " and " + popped;
			}
		  	offer_desc = "Book Now & pay zero charges for " + str.join(", ") + popped;
			$('#offerDesc').val(offer_desc);  
		}else if(offer_type == 'PriceDiscount'){   //----PriceDiscount Descirption Population
			amount='';discount_on='';date='';	
			//fetching Amounts
			if($("input[name='pd_price']:checked").val() == 'percent')
			   amount = $('#pd_price_emiPer').val() + "%";
			else if($("input[name='pd_price']:checked").val() == 'deci'){
			  if($('#pd_price_emiUnit').val())
			    amount = $('#pd_price_emiDeci').val() +" "+$('#pd_price_emiUnit').val();
			  else
			    amount = $('#pd_price_emiDeci').val();
			}
			//fetching Discount on 
			if($('#pd_on').val()=='Other')
				discount_on = $('#pd_other_txt').val();
			else if($('#pd_on').val()!='')
				discount_on = $('#pd_on :selected').html();
			//Fetching Date On [plp_noi] => 2
			if($('#offer_date').val() != '0000-00-00' && $('#offer_date').val() !=''){
				var monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
				date = $('#offer_date').val();
				dateArr = date.split("-");
				curr_mnth = monthNames[parseInt(dateArr[1])-1];
				date = dateArr[0]+"-"+curr_mnth+"-"+dateArr[2];			
			}			
			if(amount && discount_on && date)
				offer_desc = "Book by "+date+" and save "+amount+" on "+discount_on;
			else if(amount && discount_on)
				offer_desc = "Book Now and save "+amount+" on "+discount_on;
			else if(amount && date)
				offer_desc = "Book by "+date+" and save " +amount;
				
			$('#offerDesc').val(offer_desc);  
		}		
	}
  function isNumberKey(evt)
  {
 	 var charCode = (evt.which) ? evt.which : event.keyCode;

	 if (charCode > 31 && (charCode < 48 || charCode > 57) || (charCode == 13))
		return false;

	 return true;
  }
</script>
<script type="text/javascript">             
                                                                                                                         
        var cals_dict = {
            "offer_date_trigger": "offer_date"
        };

        $.each(cals_dict, function(k, v) {
            if ($('#' + k).length > 0) {
                Calendar.setup({
                    inputField: v, // id of the input field
                    //    ifFormat       :    "%Y/%m/%d %l:%M %P",         // format of the input field
                    ifFormat: "%d-%m-%Y", // format of the input field
                    button: k, // trigger for the calendar (button ID)
                    align: "Tl", // alignment (defaults to "Bl")
                    singleClick: true,
                    showsTime: false
                });
            }
        });
   
 </script>
