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
          <TD class=border-all vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</TD>
          <TD class=border-rt vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>Create Company Order</TD>
                      <TD align=right ></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
		{if $companyOrderAdminAuth == true}

			  <TABLE cellSpacing=2 cellPadding=4 width="93%" align=center border=0>
			    <form method="post" enctype="multipart/form-data" id="frmcity" name="frmcity">
			      <div>
				<tr>
				  <td width="20%" align="right" ><font color="red">*</font>Company: </td>
				  <td width="30%" align="left">
					  <input type=text name="txtCompId" id="txtCompId" value="{$txtCompId}" placeholder="Client ID" style="width:250px;">
					  <br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>OR</b><br/>
					  <input type=text name="txtCompName" id="txtCompName" value="{$txtCompName}" placeholder="Company Name" style="width:250px;">
				  </td>{if $ErrorMsg["txtCompName"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtCompName"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
				</tr>
				
				<tr>
				  <td width="20%" align="right" ><font color="red">*</font>Sales Person : </td>
				  <td width="30%" align="left">					  
					  <select name="txtSalesPerson" id="txtSalesPerson" style="width:300px;">
						<option value=''>-select-</option>
					    {foreach from=$sales_pers key=ks item=sp}
					      <option value="{$ks}">{$sp}</option>
					    {/foreach}
					  </select>
				  </td>
				  {if $ErrorMsg["txtSalesPerson"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtSalesPerson"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
				</tr>
				
				<tr>
				  <td width="20%" align="right" ><font color="red">*</font>Order Date : </td>
				  <td width="30%" align="left"> <input value="{$order_date}" name="txtOrderDate" type="text" class="formstyle2" id="txtOrderDate" readonly="1" size="10" />  <img src="../images/cal_1.jpg" id="txtOrderDate_trigger" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" /></td> {if $ErrorMsg["txtOrderDate"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtOrderDate"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
				</tr>
				
				<tr>
				  <td width="20%" align="right" ><font color="red">*</font>Order Type : </td>
				  <td width="30%" align="left"> 
				    <input type="radio" name="orderType" value="trial" checked=true />Trial
				    &nbsp;&nbsp;&nbsp;&nbsp;
				    <input type="radio" name="orderType" value="paid"/>Paid
				  </td> {if $ErrorMsg["txtOrderDate"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtOrderDate"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
				</tr>
				
				<tr class="trial_order">
				  <td width="20%" align="right" ><font color="red">*</font>Order Duration : </td>
				  <td width="30%" align="left">
				    <select id="txtOrderDur">
				      {foreach from=$orderDur item=od key=kd}
				        <option value="{$kd}">{$od}</option>
				      {/foreach}
				    </select>
				  </td>
				  {if $ErrorMsg["txtOrderDur"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtOrderDur"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
				</tr>
				
				<tr class="trial_order">
				  <td width="20%" align="right" ><font color="red">*</font>Exipry Date : </td>
				  <td width="30%" align="left"><input type=text name="txtExpiryTrialOrderDate" id="txtExpiryTrialOrderDate" readOnly=true value="{$txtExpiryTrialOrderDate}" style="width:140px;"></td> {if $ErrorMsg["txtExpiryTrialOrderDate"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtExpiryTrialOrderDate"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
				</tr>
				
				<tr class="trial_order">
				  <td width="20%" align="right" >&nbsp;</td>
				  <td width="30%" align="left"><input type=button name="txtExtendTrial" id="txtExtendTrial" readOnly=true value="Extend Trial" style="width:140px;"></td>
				  <td width="50%" align="left" ></td>
				</tr>
				<tr class="ext_trial_order">
				  <td width="20%" align="right" >Extension Duration : </td>
				  <td width="30%" align="left">
				    <select id="txtExtOrderDur">
				      {foreach from=$orderDur item=od key=kd}
				        <option value="{$kd}">{$od}</option>
				      {/foreach}
				    </select>
				  </td>
				  {if $ErrorMsg["txtExtOrderDur"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtExtOrderDur"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
				</tr>
				<tr class="ext_trial_order">
				  <td width="20%" align="right" >Extension Exipry Date : </td>
				  <td width="30%" align="left"><input type=text name="txtExtExpiryTrialOrderDate" id="txtExtExpiryTrialOrderDate" readOnly=true value="{$txtExtExpiryTrialOrderDate}" style="width:140px;"></td> {if $ErrorMsg["txtExtExpiryTrialOrderDate"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtExtExpiryTrialOrderDate"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
				</tr>
							
				<tr class="paid_order">
				  <td width="20%" align="right" ><font color="red">*</font>Order Amount : </td>
				  <td width="30%" align="left"><input type=text name="txtOrderAmt" id="txtOrderAmt" value="{$txtOrderAmt}" style="width:250px;"></td> {if $ErrorMsg["txtOrderAmt"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtOrderAmt"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
				</tr>
				
				<tr class="paid_order">
				  <td width="20%" align="right" ><font color="red">*</font>Expiry Date : </td>
				  <td width="30%" align="left"> <input value="{$txtExpiryOrderDate}" name="txtExpiryOrderDate" type="text" class="formstyle2" id="txtExpiryOrderDate" readonly="1" size="10" />  <img src="../images/cal_1.jpg" id="txtExpiryOrderDate_trigger" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" /></td> {if $ErrorMsg["txtExpiryOrderDate"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtExpiryOrderDate"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
				</tr>
				
				<tr class="paid_order">
				  <td width="20%" align="right" ><b>Payment Details</b> : </td>
				  <td width="30%" align="left">
					 <span>
					  <a href = "javascript:void(0);">
					    <img src = "images/plus.jpg" width ="20px" onClick="addPmt()">
					  </a>
					 </span>					
				  </td>
				  <td width="50%" align="left" >&nbsp;</td>
				</tr>
				
				<tr class="paid_order">
				  <td width="20%" align="right" >&nbsp;</td>
				  <td width="30%" align="left" colspan=2>
					   {for $k=1 to 50}		
					     <table class="paid_order paid_order_pmt_{$k}" style ="border:1px solid#ccc;padding:5px">
						   <tr class="paid_order paid_order_pmt_{$k}">
							  <td width="20%" align="right" >Payment Method : </td>
							  <td width="30%" align="left">
								<select id="txtPaymentMethod">
								  {foreach from=$paymentMhd item=pmd key=kpmd}
									<option value="{$kpmd}">{$pmd}</option>
								  {/foreach}
								</select>
							  </td>
							  {if $ErrorMsg["txtPaymentMethod"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtPaymentMethod"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
							</tr>	
							
							<tr class="paid_order paid_order_pmt_{$k}">
							  <td width="20%" align="right" >Payment Instrument No : </td>
							  <td width="30%" align="left"><input type=text name="txtPaymentInstNo" id="txtPaymentInstNo" value="{$txtPaymentInstNo}" style="width:140px;"></td>
							   <td width="50%" align="left" >
							    {if $ErrorMsg["txtPaymentInstNo"] != ''}<font color = "red">{$ErrorMsg["txtPaymentInstNo"]}</font>{/if}						    
							    <span style="float:right">
								  <a href = "javascript:void(0);">
									<img src = "images/minus.jpg" width ="20px" id = "paid_order_pmt_{$k}" onClick="removePmt(this.id)">
								  </a>
								 </span>
							   </td>
							  
							</tr>		
							
							<tr class="paid_order paid_order_pmt_{$k}">
							  <td width="20%" align="right" ><font color="red">*</font>Payment Amount : </td>
							  <td width="30%" align="left"><input type=text name="txtPaymentAmt" id="txtPaymentAmt"  value="{$txtPaymentAmt}" style="width:140px;"></td> {if $ErrorMsg["txtPaymentAmt"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtPaymentAmt"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
							</tr>
							
							<tr class="paid_order paid_order_pmt_{$k}">
							  <td width="20%" align="right" ><font color="red">*</font>Payment Date : </td>
							  <td width="30%" align="left"> <input value="{$txtPaymentDate}" name="txtPaymentDate" type="text" class="formstyle2" id="txtPaymentDate" readonly="1" size="10" />  <img src="../images/cal_1.jpg" id="txtPaymentDate_trigger" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" /></td> {if $ErrorMsg["txtPaymentDate"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtPaymentDate"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
							</tr>	
						</table>			
					{/for}	
					 
				  </td>				  
				</tr>
				
				<tr>
				  <td width="20%" align="right" ><font color="red">*</font>Geographic Access : </td>
				  <td width="30%" align="left"> 
				    <input type="radio" name="gAccess" value="gAccess_cities" checked=true />Cities
				    &nbsp;&nbsp;&nbsp;&nbsp;
				    <input type="radio" name="gAccess" value="gAccess_locs"/>Localities
				  </td>
				  <td width="50%" align="left" ></td>
				</tr>
				
				<tr id="gAccess_cities">
				  <td width="20%" align="right" >&nbsp;</td>
				  <td width="30%" align="left"> 
				    <select style="height:150px;width:150px;" id="cities" name="cities[]" multiple>                      
                      {foreach from=$cityArray key=k item=v}
                        <option value="{$k}" {if $cityId==$k}  selected="selected" {/if}>{$v}</option>
                      {/foreach}
                    </select>
				  </td>
				  <td width="50%" align="left" ></td>
				</tr>
				
				<tr id="gAccess_locs" style="display:none">
				  <td width="20%" align="right" >&nbsp;</td>
				  <td width="50%" align="left" style="border:1px solid#ccc"> 
				    <select id="locs_cities" name="locs_cities" onchange="update_locality(this.value);">
					  <option value="">-Select City-</option>                      
                      {foreach from=$cityArray key=k item=v}
                        <option value="{$k}">{$v}</option>
                      {/foreach}
                    </select>
                    <span id="locs_cities_locs"> 
                      <select><option>-Select Locality-</option></select>                                          
                    </span>
                    <img src = "images/plus.png" width ="20px" id = "add_loc" style="position:relative;top:5px" onclick="add_locality();" />
                    <div id="sel_locs"></div>
				  </td>
				  <td width="50%" align="left" ></td>
				</tr>				
			
				<tr>
				  <td width="20%" align="right" >Section Access : </td>
				  <td width="30%" align="left"> 
				    <input type="checkbox" name="dash_access" value="Dashboard" />Dashboard&nbsp;&nbsp;&nbsp;&nbsp;
				    <input type="checkbox" name="builder_access" value="Builder" />Builder&nbsp;&nbsp;&nbsp;&nbsp;
				    <input type="checkbox" name="catch_access" value="Catchment" />Catchment
				  </td>
				  <td width="50%" align="left" ></td>
				</tr>
				
				<tr>
				  <td width="20%" align="right" >Data Access : </td>
				  <td width="30%" align="left"> 
				    <input type="checkbox" name="supply_access" value="Supply" />Supply&nbsp;&nbsp;&nbsp;&nbsp;
				    <input type="checkbox" name="demand_access" value="Demand" />Demand&nbsp;&nbsp;&nbsp;&nbsp;				    				   
				  </td>
				  <td width="50%" align="left" ></td>
				</tr>
				
				<tr>
				  <td width="20%" align="right" ><b>Licensing Details</b></td>
				  <td width="30%" align="left"></td>				  
				  <td width="50%" align="left" ></td>
				</tr>
				
				<tr>
				  <td width="20%" align="right" >#Licenses</td>
				  <td width="30%" align="left"><input type="textfield" name="noLicen" id="noLicen" value=1 /></td>				  
				  <td width="50%" align="left" ></td>
				</tr>
				
				<tr>
				  <td width="20%" align="right" ><b>Subscribed Users</b> : </td>
				  <td width="30%" align="left">
					 <span>
					  <a href = "javascript:void(0);">
					    <img src = "images/plus.jpg" width ="20px" onclick="addUser()">
					  </a>
					 </span>					
				  </td>
				  <td width="50%" align="left" >&nbsp;</td>
				</tr>
				
				<tr >
				  <td width="20%" align="right" >&nbsp;</td>
				  <td width="30%" align="left" colspan=2>
					   {for $k=1 to 50}
					     <table class="subs_user_{$k}" style ="border:1px solid#ccc;padding:5px">
						   <tr class="subs_user_{$k}">
							  <td width="20%" align="right" >Email : </td>
							  <td width="30%" align="left"><input type=text name="txtSubsUserEmail[]"  value="{$txtSubsUserEmail}" style="width:240px;"></td> 
							  <td width="50%" align="left" >
							  {if $ErrorMsg["txtSubsUserEmail"] != ''} <font color = "red">{$ErrorMsg["txtSubsUserEmail"]}</font>{/if}
  							   <span style="float:right">
								  <a href = "javascript:void(0);">
									<img src = "images/minus.jpg" width ="20px" id = "subs_user_{$k}" onClick="removeUser(this.id)">
								  </a>
								 </span>
							  </td>
						   </tr>
						   <tr class="subs_user_{$k}">
							  <td width="20%" align="right" >Contact No : </td>
							  <td width="30%" align="left"><input type=text name="txtSubsUserCont[]"  value="{$txtSubsUserCont}" style="width:140px;"></td> {if $ErrorMsg["txtSubsUserCont"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtSubsUserCont"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
						   </tr>
						   <tr class="subs_user_{$k}">
							  <td width="20%" align="right" >User Group : </td>
							  <td width="30%" align="left">
							    <select name="txtSubsUserGroup[]">
									<option value="defualt">Default User Group</option>
							    </select>
							  </td>
							  {if $ErrorMsg["txtSubsUserEmail"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtSubsUserEmail"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
						   </tr>
						 </table>
					   {/for}
				  </td>
				  <td width="20%" align="right" >&nbsp;</td>
				</tr>				
				
				<tr>
				  <td >&nbsp;</td>
				  <td align="left" style="padding-left:50px;" >
				  <input type="hidden" name="pmtNo" id="pmtNo" value="{$paymentNoDetails}" />
				  <input type="hidden" name="all_locs" value="" />
				  <input type="submit" name="btnSave" id="btnSave" value="Save" style="cursor:pointer">
				  &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit" style="cursor:pointer">
				  </td>
				</tr>
			      </div>
			    </form>
			    </TABLE>
	            </td>
		  </tr>
		</TABLE>
                {else}
                    <font color="red">No Access</font>
                {/if}                         
	      </TD>
            </TR>
          </TBODY></TABLE>
        </TD>
      </TR>
    </TBODY></TABLE>
  </TD>
</TR>
<script type="text/javascript">

var selected_locs = [];
var subs_user = [1];
var total_subs_user = 1;
var pmt_detail = [1];
var total_pmt_detail = 1;

jQuery(document).ready(function(){
		
	$('.paid_order').hide(); //by defualt paid order will be hide
	$('.ext_trial_order').hide(); //by defualt paid order will be hide
	
	for(n=(parseInt(total_subs_user) + 1);n<=50;n++)
		  $(".subs_user_"+n).hide(); 
	
	$("input[name='orderType']").click(function(){
	  if($(this).val() == 'paid'){
		$('.paid_order').show();
		$('.trial_order').hide();	
		$('.ext_trial_order').hide();	 		  
		for(n=(parseInt(total_pmt_detail) + 1);n<=50;n++)
		  $(".paid_order_pmt_"+n).hide(); 
	  }else{
		$('.paid_order').hide();
		$('.trial_order').show();
	  }	    	
	});
	$("input[name='txtExtendTrial']").click(function(){
	  	$('.ext_trial_order').show(); 
	});	
		
	$("input[name='gAccess']").click(function(){
	  if($(this).val() == 'gAccess_cities'){
		$('#gAccess_cities').show();
		$('#gAccess_locs').hide();		
	  }else{
		$('#gAccess_cities').hide();
		$('#gAccess_locs').show();
	  }	    	
	});	
});

  function addPmt(){
	total_pmt_detail++;  
	$('.paid_order_pmt_'+total_pmt_detail).show();	
  }
  
  function removePmt(id){	
	 $('.'+id).remove();	
  }

  function addUser(){
	total_subs_user++;  
	$('.subs_user_'+total_subs_user).show();	
  }
  
  function removeUser(id){	
	 $('.'+id).remove();	
  }
	
  function add_locality(){	 
	 if($('#locality').val() != '' && selected_locs.indexOf($('#locality').val()) == -1){
	   $('#sel_locs').append('<span id="locID-'+$('#locality').val()+'"><br/><img src="images/arrow-1.png" style="position:relative;top:2px">'+$('#locality>option:selected').text()+'&nbsp;&nbsp;<img src="images/stop.gif" style="position:relative;top:2px" id="'+$('#locality').val()+'" onclick="remove_locality(this.id)"></span>');	
	   selected_locs.push($('#locality>option:selected').val());
	   $('input[name="all_locs"]').val(selected_locs.join(",")); 	   
	 }
  }
  //////////.removeData() - to empty an array
  function remove_locality(id){	 
	 $("#locID-"+id).remove();
	 selected_locs.splice(selected_locs.indexOf(id),1);
	 $('input[name="all_locs"]').val(selected_locs.join(","));
  }

  function update_locality(ctid)
  {
	 $("#locs_cities_locs").val('');
     var url="Refreshlocality.php?ctid="+ctid;
     $.ajax({
	   'url':url,
	   success:function(data){
		 $("#locs_cities_locs").html(data);  
	   }	 
	 });
     
  }
  
  function localitySelect(loclitySelectVal){}

</script>
<script type="text/javascript">             
                                                                                                                         
        var cals_dict = {
            "txtOrderDate_trigger": "txtOrderDate",
            "txtExpiryOrderDate_trigger": "txtExpiryOrderDate",
            "txtPaymentDate_trigger": "txtPaymentDate"
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
