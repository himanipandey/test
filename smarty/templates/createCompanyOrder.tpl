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
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>
                       {if $page=='view'}
                         Company Order Detail : {$orderId}
                       {else}
                         Create Company Order
                       {/if}                       
                      </TD>
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
				{$error_flag}	  
				<tr>
				  <td width="20%" align="right" ><font color="red">*</font>Company: </td>
				  <td width="30%" align="left">
					  <input type=text name="txtCompId" id="txtCompId" value="{$txtCompId}" placeholder="Client ID" readonly=true style="width:250px;">
					  <br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>OR</b><br/>
					  <input type=text name="txtCompName" id="txtCompName" value="{$txtCompName}" placeholder="Company Name" readonly=true style="width:250px;">
				  </td>
				  <td width="50%" align="left" >
					  {if $page=='view'}
					    <a href="createCompanyOrder.php?o={$orderId}&page=edit"><strong>Edit Order</strong></a>
					  {/if}
				  </td>
				</tr>
                                
                                <tr>
				  <td width="20%" align="right" >Order Name: </td>
				  <td width="30%" align="left">
					  <input type=text name="orderName" id="orderName" value="{$orderName}" style="width:250px;">
				  </td>
				  <td width="50%" align="left" >
					  &nbsp;
				  </td>
				</tr>
				
				<tr>
				  <td width="20%" align="right" ><font color="red">*</font>Sales Person : </td>
				  <td width="30%" align="left">					  
					  <select name="txtSalesPerson" id="txtSalesPerson" style="width:300px;" {if $page=='view'}disabled=true{/if}>
						<option value=''>-select-</option>
					    {foreach from=$sales_pers key=ks item=sp}
					      <option value="{$ks}" {if $ks == $txtSalesPerson}selected{/if}>{$sp}</option>
					    {/foreach}
					  </select>
				  </td>
				  {if $ErrorMsg["txtSalesPerson"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtSalesPerson"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
				</tr>
				
				<tr>
				  <td width="20%" align="right" ><font color="red">*</font>Order Date : </td>
				  <td width="30%" align="left"> <input value="{$txtOrderDate}" name="txtOrderDate" type="text" class="formstyle2" id="txtOrderDate" readonly="1" size="10" />  
				  {if $page!='view'}
                                      <input type = "hidden" name = "txtOrderDateOld" id = "txtOrderDateOld" value="{$txtOrderDate}">
				  <img src="../images/cal_1.jpg" id="txtOrderDate_trigger" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
				  {/if}
				  </td> {if $ErrorMsg["txtOrderDate"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtOrderDate"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
				</tr>
				
				<tr>
				  <td width="20%" align="right" ><font color="red">*</font>Order Type : </td>
				  <td width="30%" align="left"> 
				    <input {if $page=='view'}disabled=true{/if} type="radio" name="orderType" value="trial" {if $orderType=='trial' || $orderType==''}checked="true"{/if} />Trial
				    &nbsp;&nbsp;&nbsp;&nbsp;
				    <input {if $page=='view'}disabled=true{/if} type="radio" name="orderType" value="paid" {if $orderType=='paid'}checked="true"{/if} />Paid
				  </td> {if $ErrorMsg["txtOrderDate"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtOrderDate"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
				</tr>
				
				<!--<tr class="trial_order">
				  <td width="20%" align="right" ><font color="red">*</font>Order Duration : </td>
				  <td width="30%" align="left">
				    <select id="txtOrderDur" name="txtOrderDur" onChange="calculateTrialOrderExpiryDate(this.value)" {if $page=='view'}disabled=true{/if}>
					  <option value="">-Select-</option>	
				      {foreach from=$orderDur item=od key=kd}
				        <option value="{$kd}" {if $kd==$txtOrderDur}selected{/if}>{$od}</option>
				      {/foreach}
				    </select>
				  </td>
				  {if $ErrorMsg["txtOrderDur"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtOrderDur"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
				</tr>
				
				<tr class="trial_order">
				  <td width="20%" align="right" ><font color="red">*</font>Exipry Date : </td>
				  <td width="30%" align="left"><input {if $page=='view'}disabled=true{/if} type=text name="txtExpiryTrialOrderDate" id="txtExpiryTrialOrderDate" readOnly=true value="{$txtExpiryTrialOrderDate}" style="width:140px;"></td> {if $ErrorMsg["txtExpiryTrialOrderDate"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtExpiryTrialOrderDate"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
				</tr>-->
				
				{if $page=='Edit'}
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
				{/if}	
					
				<tr class="paid_order">
				  <td width="20%" align="right" ><font color="red">*</font>Order Amount : </td>
				  <td width="30%" align="left"><input onkeypress='return isNumberKey2(event)' {if $page=='view'}disabled=true{/if} type=text name="txtOrderAmt" id="txtOrderAmt" value="{$txtOrderAmt}" style="width:250px;"></td> {if $ErrorMsg["txtOrderAmt"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtOrderAmt"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
				</tr>
				
				<tr class="">
				  <td width="20%" align="right" ><font color="red">*</font>Expiry Date : </td>
				  <td width="30%" align="left"> <input value="{$txtExpiryOrderDate}" name="txtExpiryOrderDate" type="text" class="formstyle2" id="txtExpiryOrderDate" readonly="1" size="10" />  
				  {if $page!='view'}
				  <img src="../images/cal_1.jpg" id="txtExpiryOrderDate_trigger" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
				  {/if}
				  </td> {if $ErrorMsg["txtExpiryOrderDate"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtExpiryOrderDate"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
				</tr>
                                
                                <tr>
				  <td width="20%" align="right" >Subscription Status</td>
				  <td width="30%" align="left">
                                      <select id="subs_status"  name="status">
                                          <option value="Active" {if $subscription_status=='Active'}selected{/if}>Active</option>
                                          <option value="Inactive" {if $subscription_status=='Inactive'}selected{/if}>Inactive</option>
                                      </select>
                                  </td>
				  <td width="50%" align="left" ></td>
				</tr>
				
				<tr class="paid_order">
				  <td width="20%" align="right" ><b>Payment Details</b> : </td>
				  <td width="30%" align="left">
					 <span>
					  <a href = "javascript:void(0);">
						{if $page!='view'}
					    <img src = "images/plus.jpg" width ="20px" onClick="addPmt()">
					    {/if}
					  </a>
					 </span>					
				  </td>
				  <td width="50%" align="left" >&nbsp;</td>
				</tr>
				
				<tr class="paid_order">
				  <td width="20%" align="right" >&nbsp;</td>
				  <td width="30%" align="left" colspan=2>
					   {for $k=1 to 50}	
					     <fieldset class="paid_order paid_order_pmt_{$k}">
						 <legend>Payment Details-{$k}:</legend>	
					     <table class="paid_order paid_order_pmt_{$k}">
						   <tr class="paid_order paid_order_pmt_{$k}">
							  <td width="20%" align="right" ><font color="red">*</font>Payment Method : </td>
							  <td width="30%" align="left">
								<select id="txtPaymentMethod{$k}" name="txtPaymentMethod[]" {if $page=='view'}disabled=true{/if}>
								  <option value="">-select-</option>	
								  {foreach from=$paymentMhd item=pmd key=kpmd}
									<option value="{$kpmd}" {if $kpmd==$txtPaymentDetails[$k-1]['payment_method']}selected{/if}>{$pmd}</option>
								  {/foreach}
								</select>
							  </td>
							  {if $ErrorMsg["txtPaymentMethod"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtPaymentMethod"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
							</tr>	
							
							<tr class="paid_order paid_order_pmt_{$k}">
							  <td width="20%" align="right" >Payment Instrument No : </td>
							  <td width="30%" align="left"><input {if $page=='view'}disabled=true{/if} type=text name="txtPaymentInstNo[]" id="txtPaymentInstNo{$k}" value="{$txtPaymentDetails[$k-1]['payment_instrument_no']}" style="width:140px;"></td>
							   <td width="50%" align="left" >
							    {if $ErrorMsg["txtPaymentInstNo"] != ''}<font color = "red">{$ErrorMsg["txtPaymentInstNo"]}</font>{/if}
							    {if $page!='view' && $k!=1}
							    <span style="float:right">
								  <a href = "javascript:void(0);">
									<img src = "images/minus.jpg" width ="20px" id = "paid_order_pmt_{$k}" onClick="removePmt(this.id,{$k})">
								  </a>
								 </span>
								{/if}
							   </td>
							  
							</tr>		
							
							<tr class="paid_order paid_order_pmt_{$k}">
							  <td width="20%" align="right" ><font color="red">*</font>Payment Amount : </td>
							  <td width="30%" align="left"><input onkeypress='return isNumberKey2(event)' {if $page=='view'}disabled=true{/if} type=text name="txtPaymentAmt[]" id="txtPaymentAmt{$k}"  value="{$txtPaymentDetails[$k-1]['payment_amount']}" style="width:140px;"></td> {if $ErrorMsg["txtPaymentAmt"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtPaymentAmt"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
							</tr>
							
							<tr class="paid_order paid_order_pmt_{$k}">
							  <td width="20%" align="right" ><font color="red">*</font>Payment Date : </td>
							  <td width="30%" align="left"> <input value="{$txtPaymentDetails[$k-1]['payment_date']}" name="txtPaymentDate[]" type="text" class="formstyle2" id="txtPaymentDate{$k}" readonly="1" size="10" /> 
							  {if $page!='view'}
							   <img src="../images/cal_1.jpg" id="txtPaymentDate_trigger{$k}" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background = 'red';" onMouseOut="this.style.background = ''" />
							  {/if} 
							   </td> {if $ErrorMsg["txtPaymentDate"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtPaymentDate"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
							</tr>	
							{if $txtPaymentDetails[$k-1]['payment_id']}
							  <input type="hidden" name="txtPaymentId[]" value="{$txtPaymentDetails[$k-1]['payment_id']}"/>
							{/if}
						</table>
					 </fieldset>
					{/for}	
					 
				  </td>				  
				</tr>				
				
				<tr id="gAccess_locs">
				  <td width="20%" align="right" ><font color="red">*</font>Geographic Access : </td>
				  <td width="50%" align="left" style="border:1px solid#ccc"> 
				    <table>
					  <tr>
						  <td>
							  <select id="locs_cities" name="locs_cities" onchange="update_locality(this.value);" {if $page=='view'}disabled=true{/if}>
								<option value="">-Select City-</option>                      
								{foreach from=$cityArray key=k item=v}
								  <option value="{$k}">{$v}</option>
								{/foreach}
							  </select>
                         </td>
						 <td>
						    <span id="locs_cities_locs"> 
							  <select><option value="">-Select Locality-</option></select>                                          
							</span>
							
						 </td>
						 <td>
						   {if $page!='view'}
							  <img src = "images/plus.png" width ="20px" id = "add_loc" style="position:relative;top:5px" onclick="add_locality();" />
							{/if}
						 </td>
					  </tr>
					</table>                 
                    <div id="sel_locs"></div>
				  </td>
				  <td width="50%" align="left" ></td>
				</tr>				
			
				<tr>
				  <td width="20%" align="right" ><font color="red">*</font>Section Access : </td>
				  <td width="30%" align="left"> 
				    <input {if $page=='view'}disabled=true{/if} type="checkbox" name="dash_access" value="Dashboard" {if in_array("Dashboard", $section_access)}checked{/if} />Dashboard&nbsp;&nbsp;&nbsp;&nbsp;
				    <span class="paid_order" ><input {if $page=='view'}disabled=true{/if} type="checkbox" name="builder_access" value="Builder" {if in_array("Builder", $section_access)}checked{/if} />Builder&nbsp;&nbsp;&nbsp;&nbsp;</span>
				    <input {if $page=='view'}disabled=true{/if} type="checkbox" name="catch_access" value="Catchment" {if in_array("Catchment", $section_access)}checked{/if} />Catchment
				  </td>
				  <td width="50%" align="left" ></td>
				</tr>
				
				<tr>
				  <td width="20%" align="right" ><font color="red">*</font>Data Access : </td>
				  <td width="30%" align="left"> 
				    <input {if $page=='view'}disabled=true{/if} type="checkbox" name="supply_access" value="Supply" {if in_array("Supply", $data_access)}checked{/if} />Supply&nbsp;&nbsp;&nbsp;&nbsp;
				    <input {if $page=='view'}disabled=true{/if} type="checkbox" name="demand_access" value="Demand" {if in_array("Demand", $data_access)}checked{/if} />Demand&nbsp;&nbsp;&nbsp;&nbsp;				    				   
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
					 {if $page!='view'}
					 <span>
					  <a href = "javascript:void(0);">
					    <img src = "images/plus.jpg" width ="20px" onclick="addUser()">
					  </a>
					 </span>
					 {/if}					
				  </td>
				  <td width="50%" align="left" >&nbsp;</td>
				</tr>
				
				<tr >
				  <td width="20%" align="right" >&nbsp;</td>
				  <td width="30%" align="left" colspan=2>
					   {for $k=1 to 50}
					   <fieldset class="subs_user_{$k}">
						 <legend>User-{$k}:</legend>
					     <table class="subs_user_{$k}">

					     	<tr class="subs_user_{$k}">
							  <td width="20%" align="right" ><font color="red">*</font>Full Name : </td>
							  <td width="30%" align="left"><input {if $page=='view'}disabled=true{/if} {if $page=='edit'}readonly="readonly"{/if} type=text name="txtSubsUserName[]" id="txtSubsUserName{$k}" value="{$txtSubsUserName[$k-1]}" style="width:240px;"></td> 
							  <td width="50%" align="left" >
							  {if $ErrorMsg["txtSubsUserName"] != ''} <font color = "red">{$ErrorMsg["txtSubsUserName"]}</font>{/if}
							  {if $page!='view'}
  							   <span style="float:right">
								  <a href = "javascript:void(0);">
									<img src = "images/minus.jpg" width ="20px" id = "subs_user_{$k}" onClick="removeUser(this.id,{$k})">
								  </a>
								 </span>
							  {/if}
							  </td>
						   	</tr>

						   <tr class="subs_user_{$k}">
							  <td width="20%" align="right" ><font color="red">*</font>Email : </td>
							  <td width="30%" align="left"><input {if $page=='view'}disabled=true{/if} {if $page=='edit'}readonly="readonly"{/if} type=text name="txtSubsUserEmail[]" id="txtSubsUserEmail{$k}" value="{$txtSubsUserEmail[$k-1]}" style="width:240px;"></td> 
							  <td width="50%" align="left" >
							  {if $ErrorMsg["txtSubsUserEmail"] != ''} <font color = "red">{$ErrorMsg["txtSubsUserEmail"]}</font>{/if}
							 
							  </td>
						   </tr>
						   <tr class="subs_user_{$k}">
							  <td width="20%" align="right" ><font color="red">*</font>Contact No : </td>
							  <td width="30%" align="left"><input {if $page=='view'}disabled=true{/if} {if $page=='edit'}readonly="readonly"{/if} onkeypress='return isNumberKey(event)' type=text name="txtSubsUserCont[]"  id="txtSubsUserCont{$k}" value="{$txtSubsUserCont[$k-1]}" style="width:140px;"></td> {if $ErrorMsg["txtSubsUserCont"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtSubsUserCont"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
						   </tr>
						   <tr class="subs_user_{$k}">
							  <td width="20%" align="right" >User Group : </td>
							  <td width="30%" align="left">
							    <select name="txtSubsUserGroup[]"  {if $page=='view'}disabled=true{/if} {if $page=='edit'}readonly="readonly"{/if}>
									<option value="defualt">Default User Group</option>
							    </select>
							  </td>
							  {if $ErrorMsg["txtSubsUserEmail"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtSubsUserEmail"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
						   </tr>
						   <tr class="subs_user_{$k}">
							  <td width="20%" align="right" >Disable OTP : </td>
							  <td width="30%" align="left">
							  	<input type="checkbox" name="txtSubsUserOtp[]" {if $page=='view'}disabled{/if} {if $txtSubsUserOtp[$k-1]=='TRUE'} checked='checked' {/if}  value="TRUE"  />
							  </td>
							  {if $ErrorMsg["txtSubsUserOtp"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtSubsUserOtp"]}</font></td>{else} <td width="50%" align="left" id="errmsgname"></td>{/if}
						   </tr>
						 </table>
						</fieldset>
					   {/for}
				  </td>
				  <td width="20%" align="right" >&nbsp;</td>
				</tr>
				
				<tr>
				  <td >&nbsp;</td>
				  <td align="left" style="padding-left:50px;" >				  
				  <input type="hidden" name="all_locs" id="all_locs" value="" />
				  {if $page=='edit'}
				    <input type="hidden" name="pmtNo" id="pmtNo" value="{$pmtNo}" />
				    <input type="hidden" name="userNo" id="userNo" value="{$userNo}" />
				    <input type="hidden" name="orderId" id="orderId" value="{$orderId}" />
				    <input type="hidden" name="subsId" id="subsId" value="{$subsId}" />
				    <input type="submit" name="btnEditSave" id="btnEditSave" value="Update" style="cursor:pointer" onclick="return validate_order();">
				  {/if}
				  {if $page!='view' && $page!='edit'}
				    <input type="hidden" name="pmtNo" id="pmtNo" value="1" />
				    <input type="hidden" name="userNo" id="userNo" value="1" />
				    <input type="submit" name="btnSave" id="btnSave" value="Save" style="cursor:pointer" onclick="return validate_order();">
				  {/if}
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
var pmt_detail = [1];
var total_pmt_detail = 1;
var total_subs_user = 1;

jQuery(document).ready(function(){
	order_type = '';
	if('{$page}' == 'view' || '{$page}' == 'edit'){	
	  order_type = "{$orderType}";
	  total_pmt_detail = "{$pmtNo}";
	  total_subs_user = "{$userNo}";
	  subs_user = [];
	  for(i=1;i<=total_subs_user;i++)
	    subs_user.push(i);
	  pmt_detail=[];
	  for(i=1;i<=total_pmt_detail;i++)
	    pmt_detail.push(i);
	}
	
	if(order_type == 'paid'){	
	  $('.paid_order').show();
	  $('.ext_trial_order').hide();
	  $('.trial_order').hide();
    }else if(order_type == 'trial'){	
	  $('.paid_order').hide();
	  $('.ext_trial_order').hide();
	  $('.trial_order').show();		
	}else{
	  $('.paid_order').hide(); //by defualt paid order will be hide
	  $('.ext_trial_order').hide(); 
	  $('.trial_order').show();
	}
	
	for(n=(parseInt(total_pmt_detail) + 1);n<=50;n++)
		  $(".paid_order_pmt_"+n).hide();
	
	for(n=(parseInt(total_subs_user) + 1);n<=50;n++)
		  $(".subs_user_"+n).hide(); 
	
	if('{$page}' == 'view' || '{$page}' == 'edit'){	  
	 	//creating saved localities boxes
		gAccess_objects = '{$gAccess_ids}';
		gAccess_objects = JSON.parse(gAccess_objects);
                if(gAccess_objects){
                    $.each(gAccess_objects,function(k,v){
                      selected_locs.push(k);		 
                    });
                }
		//updating added locality html	
	    var url="Refreshlocality.php";
	     $.ajax({
	       'url':url,
	       'data':"locArr="+selected_locs+"&&compOrder=1",
	       'type':'post',
	       success:function(data){
		     $("#sel_locs").html(data);  
		       selected_locs = [];
		       $.each($("#sel_locs div"),function(){
		      selected_locs.push($(this).attr('name'));
		    });
		    $('input[name="all_locs"]').val(selected_locs.join(",")); 		  
	       }	 
	     }); 
    }	  
	
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
	  	$("#txtOrderDur option[value='']").attr('selected', 'selected');
		$("#txtExpiryTrialOrderDate").val("");
	});	
		
	$("input[name='gAccess']").click(function(){
	  if($(this).val() == 'gAccess_cities'){
		$('#gAccess_cities').show();
		$('#gAccess_locs').hide();	
		$('input[name="all_locs"]').val("");	
	  }else{
		$('#gAccess_cities').hide();
		$('#gAccess_locs').show();
	  }	    	
	});	    	

	
	$('#txtOrderDate_trigger').click(function(){
	  $("#txtOrderDur option[value='']").attr('selected', 'selected');
	  $("#txtExpiryTrialOrderDate").val("");	
	});	
	
	/*$("input[name=txtSubsUserEmail[]]").click(function(){
	 alert($(this).attr('id'));	
	});*/
	
	
});

  function validate_order(){
	var errFlagUser = '',errFlagPmt = '';
	compId = $('#txtCompId').val().trim();
	compName = $('#txtCompName').val().trim();
	salesPer = $('#txtSalesPerson').val().trim();
	orderDate = $('#txtOrderDate').val();
        orderDateOld = $('#txtOrderDateOld').val();
	orderType = $("input[name='orderType']:checked").val();
	orderDur = $("#txtOrderDur").val();	
	//access
	gAccessType = $("input:radio[name='gAccess']:checked").val();
	cities = $("#cities option:selected").size();
	alllocs = $("#all_locs").val().trim();	
	
	if(compId == '' && compName == ''){
	  alert("Company Name or Company ID is required.");
	  return false;	
	}else if(salesPer == ''){
	  alert("Sales Person is required.");
	  return false;		
	}else if(orderDate == ''){
	  alert("Order Date is required.");	
	  $("#txtOrderDur option[value='']").attr('selected', 'selected');
	  $("#txtExpiryTrialOrderDate").val("");
	  return false;
	}
	if(orderType == 'trial'){
	  if(orderDur == ''){
		alert("Order duration is required!");
		return false;  
	  }		  
	}
        ordDt = new Date(orderDate).toDateString();
        ordDtOld = new Date(orderDateOld).toDateString();
	if(orderDate != '' && ordDt != ordDtOld){
		date = orderDate;		
		d1 = new Date(date).toDateString();		
		d2 = new Date().toDateString();
		d1 = new Date(d1);
		d2 = new Date(d2);
		if(d1<d2){
		  alert("Order Date must be Current or Future date.");
		  $("#txtOrderDur option[value='']").attr('selected', 'selected');
		  $("#txtExpiryTrialOrderDate").val("");
		  return false;
		}
	  }	  
	 //payment details validations
	 if(orderType == 'paid'){	
	   if($('#txtOrderAmt').val().trim() == '' || $('#txtOrderAmt').val().trim() == 0){
		 alert("Order Amount is required.");
		 return false;   
	   }	
	   
	   
	   if(pmt_detail.length == 0){
		 alert("Payment Details required.");  
		 return false;
	   }	 
	   $.each(pmt_detail,function(k,v){
		 if($('#txtPaymentMethod'+v).val() == ''){		
			errFlagPmt = "Payment Detail-"+v+": Payment Method is reqiured";	
			return false;	
		  }else if($('#txtPaymentAmt'+v).val().trim() == ''){
			errFlagPmt = "Payment Detail-"+v+": Payment amount is required.";  
			return false;			
		  }else if($('#txtPaymentDate'+v).val() == ''){
			errFlagPmt = "Payment Detail-"+v+": Payment Date is required.";  
			return false;			
		  }  	    
	  });		  
	}
        
        if($('#txtExpiryOrderDate').val() == ''){
		 alert("Expiry Date is required.");
		 return false;   
        } 
        if($('#txtExpiryOrderDate').val() != ''){
             date = $('#txtExpiryOrderDate').val();
             d1 = new Date(date).toDateString();
             d2 = new Date(orderDate).toDateString();
             d1 = new Date(d1);
             d2 = new Date(d2);
             if(d1<d2){
               alert("Order Expiry Date must greater than the Order Date.");		 
               return false;
             }
	  }
	
	if(errFlagPmt != ''){
	  alert(errFlagPmt);
	  return false;	
	}	
	 
	if(gAccessType == 'gAccess_cities' && cities == ''){
	  alert("Please select cities in Geographical Access.");	
	  return false;
	}else if(gAccessType == 'gAccess_locs' && alllocs == ''){
	  alert("Please select localities in Geographical Access.");	
	  return false;
	}else if(orderType == 'trial' && !$("input[name='dash_access']").is(":checked") && !$("input[name='catch_access']").is(":checked")){
	  alert("Please choose any access in Section Access.");	
	  return false;
	}else if(orderType == 'paid' && !$("input[name='dash_access']").is(":checked") && !$("input[name='catch_access']").is(":checked") && !$("input[name='builder_access']").is(":checked") ){
	  alert("Please choose any access in Section Access.");	
	  return false;
	}else if(!$("input[name='supply_access']").is(":checked") && !$("input[name='demand_access']").is(":checked")){
	  alert("Please choose any access in Data Access.");	
	  return false;
	}
	//sub users validations
	if(subs_user.length == 0){
		 alert("Subscribed Users Details required.");  
		 return false;
	}
	$.each(subs_user,function(k,v){	  	
	  if($('#txtSubsUserEmail'+v).val().trim() == '' || !validateEmail($('#txtSubsUserEmail'+v).val().trim())){		
		errFlagUser = "User-"+v+": Email ID must be valid.";	
		return false;	
	  }
	  if($('#txtSubsUserCont'+v).val().trim() == ''){
	  	errFlagUser = "User-"+v+": contact no. must be valid.";  
	  	return false;			
	  }		    
	});		
	
	if(errFlagUser != ''){
	  alert(errFlagUser);
	  return false;	
	}	
			
	return true;  
  }
  
  function calculateTrialOrderExpiryDate(dur){
	dur = dur.split("week"); 
	days = parseInt(dur[0]) * 7;
	orderDate = $('#txtOrderDate').val();
	if(orderDate){
	  d1 = new Date(orderDate);
	  d1.setDate(d1.getDate()+days);
	  $('#txtExpiryTrialOrderDate').val((d1.getFullYear())+"-"+('0'+parseInt(d1.getMonth()+1)).substr(-2,2)+"-"+('0'+d1.getDate()).substr(-2,2));
	}	  
  }

  function addPmt(){
	total_pmt_detail++;  
	pmt_detail.push(total_pmt_detail);
	$('.paid_order_pmt_'+total_pmt_detail).show();	
	$('#pmtNo').val(pmt_detail.length);
  }
  
  function removePmt(id,rid){	
	 $('.'+id).remove();	
	 pmt_detail.splice(pmt_detail.indexOf(id),1);
	 $('#pmtNo').val(pmt_detail.length);
  }

  function addUser(){
	total_subs_user++;  
	subs_user.push(total_subs_user);
	$('#userNo').val(subs_user.length);
	$('.subs_user_'+total_subs_user).show();	
	$('#txtSubsUserName'+total_subs_user).attr('readonly', false);
	$('#txtSubsUserEmail'+total_subs_user).attr('readonly', false);
	$('#txtSubsUserCont'+total_subs_user).attr('readonly', false);
	$('#txtSubsUserGroup'+total_subs_user).attr('readonly', false);
  }
  
  function removeUser(id,rid){	
	 $('.'+id).remove();	
	 subs_user.splice(subs_user.indexOf(rid),1);
	 $('#userNo').val(subs_user.length);
  }
	
  function add_locality(){		 
	 if($('#locality').val() != undefined && $('#locality').val() != '' && selected_locs.indexOf($('#locality').val()) == -1){		   
	   $.each($('#locality>option:selected'),function(){		   
		  selected_locs.push($(this).val());	 		 		  
	   });	   
	   //updating added locality html	
	   var url="Refreshlocality.php";
	   $.ajax({
	     'url':url,
	     'data':"locArr="+selected_locs+"&&compOrder=1",
	     'type':'post',
	     success:function(data){
		   $("#sel_locs").html(data);  
		    selected_locs = [];
		    $.each($("#sel_locs div"),function(){
		     selected_locs.push($(this).attr('name'));
		    });
		    $('input[name="all_locs"]').val(selected_locs.join(",")); 		  
	     }	 
	   });   
	 }
  }
  //////////.removeData() - to empty an array
  function remove_locality(id){	 
	 $("#locID-"+id).remove();
	 selected_locs.splice(selected_locs.indexOf(id),1);
	 $('input[name="all_locs"]').val(selected_locs.join(","));
	 //updating added locality html	
	   var url="Refreshlocality.php";
	   $.ajax({
	     'url':url,
	     'data':"locArr="+selected_locs+"&&compOrder=1&companyOrder=1",
	     'type':'post',
	     success:function(data){
		   $("#sel_locs").html(data);  
	     }	 
	   });
  }

  function update_locality(ctid)
  {
	 $("#locs_cities_locs").val('');
     var url="Refreshlocality.php?ctid="+ctid+"&suburb=include&companyOrder=1";
     $.ajax({
	   'url':url,
	   success:function(data){
		 $("#locs_cities_locs").html(data);  
	   }	 
	 });
     
  }
  
  function validateEmail(email) 
  {
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
  }
  
  function isNumberKey(evt)
  {
 	 var charCode = (evt.which) ? evt.which : event.keyCode;
	
	 if (charCode > 31 && (charCode < 48 || charCode > 57) || (charCode == 13))
		return false;

	 return true;
  }
  
  function isNumberKey2(evt)
  {
 	 var charCode = (evt.which) ? evt.which : event.keyCode;
	
	 if(charCode == 46)
	   return true;
	 
	 if (charCode > 31 && (charCode < 48 || charCode > 57) || (charCode == 13))
		return false;

	 return true;
  }
  
  function localitySelect(loclitySelectVal){}

</script>
<script type="text/javascript">             
                                                                                                                         
        var cals_dict = {
            "txtOrderDate_trigger": "txtOrderDate",
            "txtExpiryOrderDate_trigger": "txtExpiryOrderDate"
        };

        for(i=1;i<=50;i++){
            cals_dict["txtPaymentDate_trigger"+i] = "txtPaymentDate"+i;
     
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
