
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>

<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />


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
                <TD class=h1 align=left background=../images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>{if $brokerCompanyId == ''} Add New {else} Edit {/if} Broker Company</TD>
                      <TD align=right ></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
		      
			  <TABLE cellSpacing=2 cellPadding=4 width="93%" align=center border=0>
			    <form method="post" id="frm1" enctype="multipart/form-data" action="brokercompanyadd.php">
			      <div>
                      {if $ErrorMsg["dataInsertionError"] != ''}
                      <tr><td colspan = "2" align ="center"><font color = "red">{$ErrorMsg["dataInsertionError"]}</font></td></tr>
                      {/if}
                      {if $ErrorMsg["success"] != ''}
                      <tr><td colspan = "2" align ="center"><font color = "green">{$ErrorMsg["success"]}</font></td></tr>
                      {/if}
                      {if $ErrorMsg["wrongPId"] != ''}
                      <tr><td colspan = "2" align ="center"><font color = "red">{$ErrorMsg["wrongPId"]}</font></td></tr>
                      {/if}
	           
				<tr>
                    <td width="30%" valign="top">Broker Company Name :<font color = "red">*</font></td>
                    <td width="10%" valign="top">
                        <input type=text name="name" id="name" value ="{$name}" style="width:250px;" />
                        {if $ErrorMsg["name"] != ''}
                            <font color = "red">{$ErrorMsg["name"]}</font>
                        {/if}
                    </td>
                    <td width="10%" align="right" valign="top" >Company Logo : </td>
                    <td width="10%" align="left" valign="top" >
                        <input type="file" name="logo" id="logo" value="" style="width:180px;" />
                    </td>
                    <td width="15%" align="left" valign="top">
                        <div style="width:130px!important;height:130px">
                        </div>
                    </td>
                    <td width="15%" align="right" valign="top" >PAN : </td>
                    <td width="10%" align="left" valign="top" >
                        <input type=text name="pan" id="pan" maxlength="10" value="{$pan}" style="width:85px;" />	
                    </td>
				</tr>

                
                <tr>
                       <td width="20%" >Description :<font color = "red">*</font></td>
                       <td width="20%" align="left" >
                               <textarea name="description" style="width:250px;" id="description">{$description}</textarea>	
                                {if $ErrorMsg["description"] != ''}
                                    <font color = "red">{$ErrorMsg["description"]}</font>
                                {/if}
                       </td>
                       <td width="10%" align="right">Status :</td>
                       <td width="10%" align="left" >
        				   <select name = "status" id = "status" style="width:90px;">
                               <option value="Active" {if $status == 'Active'}selected{/if}>Active</option>
                               <option value="Inactive" {if $status == 'Inactive'}selected{/if}>Inactive</option>
                           </select>
                      </td>
                      
				</tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="7">
                        Address Details (Headquarter)
                        <hr />
                    </td>
                </tr>
                
				<tr>
				    <td width="20%" align="right" >Address Line 1 : <font color = "red">*</font></td>
                    <td width="30%" align="left" >
                        <input type=text name="addressline1" id="addressline1" value="{$addressline1}" style="width:250px;" />
                        {if $ErrorMsg["addressline1"] != ''}
                            <font color = "red">{$ErrorMsg["addressline1"]}</font>
                        {/if}
                    </td>
                    <td width="20%" align="right" valign="top">City :<font color = "red">*</font></td>
                    <td width="30%" align="left" >
				        <select name="city_id" id = "city_id" style="width:250px;">
                           <option value="">Select City</option>
                           {foreach from= $cityArr key = k item = val}
                               <option value="{$k}" {if $k == $city_id} selected {/if}>{$val}</option>
                           {/foreach}
                       </select>
                      </td>
                      {if $ErrorMsg["city"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["hq"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
                
                <tr>
				    <td width="20%" align="right" >Address Line 2 : </td>
                    <td width="30%" align="left" >
                        <input type=text name="addressline2" id="addressline2" value="{$addressline2}" style="width:250px;" />
                    </td>
                    <td width="15%" align="right" valign="top" >Pincode : </td>
                    <td width="10%" align="left" valign="top" >
                        <input type=text name="pincode" id="pincode" value="{$pincode}" maxlength="12" style="width:85px;" />
                        {if $ErrorMsg["pincode"] != ''}
                            <font color = "red">{$ErrorMsg["pincode"]}</font>
                        {/if}	
                    </td>
				</tr>
                
                <tr>
    				<td width="15%" align="right" valign="top" >Office Phone 1 :<font color = "red">*</font> </td>
                    <td width="10%" align="left" valign="top" >
                        <input type=text maxlength="2" readonly="true" value="+91" style="width:25px;" />
                        <input type=text name="phone1" id="phone1" value="{$phone1}" maxlength="12" style="width:85px;" />
                        {if $ErrorMsg["phone1"] != ''}
                            <font color = "red">{$ErrorMsg["phone1"]}</font>
                        {/if}		
                    </td>
                    <td width="15%" align="right" valign="top" >Office Email:</td>
                    <td width="10%" align="left" valign="top" >
                        <input type=text name="email" id="email" value="{$email}" style="width:250px;" />	
                        {if $ErrorMsg["email"] != ''}
                            <font color = "red">{$ErrorMsg["email"]}</font>
                        {/if}	
                    </td>
    				
				</tr>
                
                <tr>
    				<td width="15%" align="right" valign="top" >Office Phone 2 : </td>
                    <td width="10%" align="left" valign="top" >
                        <input type=text maxlength="2" readonly="true" value="+91" style="width:25px;" />
                        <input type=text name="phone2" id="phone2" value="{$phone2}" maxlength="12" style="width:85px;" />
                        {if $ErrorMsg["phone2"] != ''}
                            <font color = "red">{$ErrorMsg["phone2"]}</font>
                        {/if}		
                    </td>
    				
				</tr>
                
                <tr>
    				<td width="15%" align="right" valign="top" >Office Fax : </td>
                    <td width="10%" align="left" valign="top" >
                        <input type=text name="fax" id="fax" value="{$fax}" maxlength="12" style="width:85px;" />
                        {if $ErrorMsg["fax"] != ''}
                            <font color = "red">{$ErrorMsg["fax"]}</font>
                        {/if}	
                    </td>
    				
				</tr>
                
                <tr>
                    <td colspan="7">
                        Contact Person Details
                        <hr />
                    </td>
                </tr>
                
				<tr>
                    <td colspan="7">
                        <table id="contactdet">
                            {if $contacts != ''}
                                {foreach from= $contacts key = k item = val}
                                    
                                    <tr class="{$val['id']}">
                                        <td colspan="4">
                                            <hr style="border: 0.1px dotted;"/>
                                        </td>
                                    </tr>
                                    <tr class="{$val['id']}">
                                        
                                        <td width="30%" valign="top"><input type="checkbox" name="chkbox_{$val['id']}" id="chkbox_{$val['id']}" class="chkbox" /> &nbsp;Name :<font color = "red">*</font></td>
                                        <td width="10%" valign="top">
                                            <input type="text" name="cp_name" id="cp_name-{$val['id']}" class="cp_name" value ="{$val['name']}" style="width:250px;" />
                                            
                                        </td>
                                        <td width="20%" align="right" >Contact Mobile : </td>
                                        <td width="30%" align="left" >
                                            <input type="text" name="cp_mobile" id="cp_mobile-{$val['id']}" class="cp_mobile" value="{$val['mobile']}" style="width:85px;" maxlength="10" />
                                            
                                        </td>
                    				</tr>
                                    
                                   
                                    <tr class="{$val['id']}">
                        				<td width="15%" valign="top" >Contact Phone 1 :<font color = "red">*</font> </td>
                                        <td width="10%" align="left" valign="top" >
                                            <input type="text"maxlength="2" readonly="true" value="+91" style="width:25px;" />
                                            <input type="text" name="cp_phone1" id="cp_phone1-{$val['id']}" class="cp_phone1" value="{$val['phone1']}" maxlength="12" style="width:85px;" />
                                            
                                        </td>
                                        <td width="15%" align="right" valign="top" >Contact Email:</td>
                                        <td width="10%" align="left" valign="top" >
                                            <input type="text" name="cp_email" id="cp_email-{$val['id']}" class="cp_email" value="{$val['email']}" style="width:250px;" />
                                            
                                        </td>
                        				
                    				</tr>
                                    
                                    <tr class="{$val['id']}">
                        				<td width="15%" valign="top" >Contact Phone 2 : </td>
                                        <td width="10%" align="left" valign="top" >
                                            <input type=text maxlength="2" readonly="true" value="+91" style="width:25px;" />
                                            <input type="text" name="cp_phone2" id="cp_phone2-{$val['id']}" class="cp_phone2" value="{$val['phone2']}" maxlength="12" style="width:85px;" />
                                            
                                        </td>
                        				
                    				</tr>
                                    
                                    <tr class="{$val['id']}">
                        				<td width="15%" valign="top" >Contact Fax : </td>
                                        <td width="10%" align="left" valign="top" >
                                            <input type="text" name="cp_fax" id="cp_fax-{$val['id']}" class="cp_fax" value="{$val['fax']}" maxlength="12" style="width:85px;" />
                                           	{if $ErrorMsg["cp_fax"] != ''}
                                                <font color = "red">{$ErrorMsg["cp_fax"]}</font>
                                            {/if}
                                        </td>
                    				</tr>
                                    
                                {/foreach}
                            {elseif $contacts == ''}
                                    <tr>
                                        <td width="30%" valign="top">Name :<font color = "red">*</font></td>
                                        <td width="10%" valign="top">
                                            <input type="text" name="cp_name" id="cp_name-01" class="cp_name" value ="{$cp_name}" style="width:250px;" />
                                            {if $ErrorMsg["cp_name"] != ''}
                                                <font color = "red">{$ErrorMsg["cp_name"]}</font>
                                            {/if}
                                        </td>
                                        <td width="20%" align="right" >Contact Mobile : </td>
                                        <td width="30%" align="left" >
                                            <input type="text" name="cp_mobile" id="cp_mobile-01" class="cp_mobile" value="{$cp_mobile}" style="width:85px;" maxlength="10" />
                                            {if $ErrorMsg["cp_mobile"] != ''}
                                                <font color = "red">{$ErrorMsg["cp_mobile"]}</font>
                                            {/if}
                                        </td>
                    				</tr>
                                    
                                   
                                    <tr>
                        				<td width="15%" valign="top" >Contact Phone 1 :<font color = "red">*</font> </td>
                                        <td width="10%" align="left" valign="top" >
                                            <input type="text"maxlength="2" readonly="true" value="+91" style="width:25px;" />
                                            <input type="text" name="cp_phone1" id="cp_phone1-01" class="cp_phone1" value="{$cp_phone1}" maxlength="12" style="width:85px;" />
                                            {if $ErrorMsg["cp_phone1"] != ''}
                                                <font color = "red">{$ErrorMsg["cp_phone1"]}</font>
                                            {/if}
                                        </td>
                                        <td width="15%" align="right" valign="top" >Contact Email:</td>
                                        <td width="10%" align="left" valign="top" >
                                            <input type="text" name="cp_email" id="cp_email-01" class="cp_email" value="{$cp_email}" style="width:250px;" />
                                            {if $ErrorMsg["cp_email"] != ''}
                                                <font color = "red">{$ErrorMsg["cp_email"]}</font>
                                            {/if}
                                        </td>
                        				
                    				</tr>
                                    
                                    <tr>
                        				<td width="15%" valign="top" >Contact Phone 2 : </td>
                                        <td width="10%" align="left" valign="top" >
                                            <input type=text maxlength="2" readonly="true" value="+91" style="width:25px;" />
                                            <input type="text" name="cp_phone2" id="cp_phone2-01" class="cp_phone2" value="{$cp_phone2}" maxlength="12" style="width:85px;" />
                                            {if $ErrorMsg["cp_phone2"] != ''}
                                                <font color = "red">{$ErrorMsg["cp_phone2"]}</font>
                                            {/if}
                                        </td>
                        				
                    				</tr>
                                    
                                    <tr>
                        				<td width="15%" valign="top" >Contact Fax : </td>
                                        <td width="10%" align="left" valign="top" >
                                            <input type="text" name="cp_fax" id="cp_fax-01" class="cp_fax" value="{$cp_fax}" maxlength="12" style="width:85px;" />
                                           	{if $ErrorMsg["cp_fax"] != ''}
                                                <font color = "red">{$ErrorMsg["cp_fax"]}</font>
                                            {/if}
                                        </td>
                    				</tr>
                                
                            {/if}
                                      
                        </table>
                        <table>
                            <tr>
                                <td>
                                    <input type="button" name="addcontact" id="addcontact" value="Add Contact" />
                                    <input type="button" name="delcontact" id="delcontact" value="Delete Contact" />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>      
                
				
                <tr>
                    <td colspan="7">
                        Customer Care Details
                        <hr />
                    </td>
                </tr>
                
				<tr>
                    <td width="15%" valign="top" >Cust Care Phone :</td>
                    <td width="10%" align="left" valign="top" >
                        <input type=text name="cc_phone" id="cc_phone" value="{$cc_phone}" maxlength="12" style="width:85px;" />
                        {if $ErrorMsg["cc_phone"] != ''}
                            <font color = "red">{$ErrorMsg["cc_phone"]}</font>
                        {/if}	
                    </td>
                    <td width="20%" align="right" valign="top" >Cust Care Mobile : </td>
                    <td width="30%" align="left" >
                    <input type=text maxlength="2" readonly="true" value="+91" style="width:25px;" />
                        <input type=text name="cc_mobile" id="cc_mobile" value="{$cc_mobile}" maxlength="10" style="width:85px;" />
                        {if $ErrorMsg["cc_mobile"] != ''}
                            <font color = "red">{$ErrorMsg["cc_mobile"]}</font>
                        {/if}
                    </td>
				</tr>
                
               
                <tr>
    				<td width="15%" valign="top" >Cust Care Fax : </td>
                    <td width="10%" align="left" valign="top" >
                        <input type=text name="cc_fax" id="cc_fax" value="{$cc_fax}" maxlength="10" style="width:85px;" />
                        {if $ErrorMsg["cc_fax"] != ''}
                            <font color = "red">{$ErrorMsg["cc_fax"]}</font>
                        {/if}	
                    </td>
                    <td width="15%" align="right" valign="top" >Cust Care Email:</td>
                    <td width="10%" align="left" valign="top" >
                        
                        <input type=text name="cc_email" id="cc_email" value="{$cc_email}" style="width:250px;" />
                        {if $ErrorMsg["cc_email"] != ''}
                            <font color = "red">{$ErrorMsg["cc_email"]}</font>
                        {/if}	
                    </td>
    				
				</tr>
                
                <tr>
                    <td colspan="7">
                        Other Details
                        <hr />
                    </td>
                </tr>
                
                <tr>
    				<td width="15%" valign="top" >Active Since : </td>
                    <td width="10%" align="left" valign="top" >
                        <input type=text name="active_since" id="active_since" value="{$active_since}" style="width:85px;" readonly="1" size="10" />
                        <img src="../images/cal_1.jpg" id="f_trigger_c_to" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />	
                    </td>
    				
				</tr>     
                                
                <tr>
                    <td colspan="2">
                        <u>Office Locations</u>
                    </td>
                    <td>&nbsp;</td>
                    <td>
                        <a id="showcontent" href="#applocations" style="text-decoration: none;">
                            <input type="button" name="add" id="add" value="Add" />
                        </a>
                        <input type="button" name="delete" id="delete" value="Delete" />
                    </td>
                    <td>
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="7">
                        <div style="overflow-y:scroll;width:58%;height:212px;">
                            <table width="100%" id="cityData">
                                <tr>
                                    <td>
                                        <input type="checkbox" name="selectall" id="selectall" />
                                    </td>
                                    <td>
                                        <strong>City</strong>
                                    </td>
                                    <td>
                                        <strong>Locality</strong>
                                    </td>
                                    <td>
                                        <strong>Address</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <hr />
                                    </td>
                                </tr>
                             
                                {if $cityLocIDArr != ''}
                                    {foreach from= $cityLocIDArr key = k item = val}
                                        <tr id="cityloc_{$val->pkid}" class="cityloc_">
                                            <td>
                                                <input type="checkbox" name="{$val->pkid}" id="{$val->pkid}" class="citychkbox" />
                                            </td>
                                            <td>
                                                {$val->city}
                                            </td>
                                            <td>
                                                {$val->location}
                                            </td>
                                            <td>
                                                {$val->address_line_1}  
                                            </td>
                                        </tr>
                                        <tr id="citylocD_{$val->pkid}" class="cityloc_">
                                            <td colspan="4">
                                                <hr style="border: 0.5px dotted" />
                                            </td>
                                        </tr>
                                    {/foreach}
                                {/if}
                            </table>
                        </div>
                    </td>
                </tr>                   
                
                
				<tr>
				  <td colspan="3">&nbsp;</td>
				  <td align="left">
				  <input type="hidden" name="brokerCompanyId" id="brokerCompanyId" value="{$brokerCompanyId}" />
				  <input type="submit" name="btnSave" id="btnSave" value="Save" style="float:left;" />
				  &nbsp;&nbsp;<input type="button" name="btnExit" id="btnExit" value="Exit" style="float:right:" />
                  
                  <input type="hidden" name="xcp_name" id="xcp_name" value="" />
                  <input type="hidden" name="xcp_mobile" id="xcp_mobile" value="" />
                  <input type="hidden" name="xcp_email" id="xcp_email" value="" />
                  <input type="hidden" name="xcp_phone1" id="xcp_phone1" value="" />
                  <input type="hidden" name="xcp_phone2" id="xcp_phone2" value="" />
                  <input type="hidden" name="xcp_fax" id="xcp_fax" value="" />
                  
                  <input type="hidden" name="xcp_ids" id="xcp_ids" value="{$contactsids}" />
                  <input type="hidden" name="rcontactids" id="rcontactids" value="" />
                  <input type="hidden" name="acontactids" id="acontactids" value="" />
                  <input type="hidden" name="remove_citylocids" id="remove_citylocids" />
                  <input type="hidden" name="citypkidArr" id="citypkidArr" value="{$citypkidArr}" />
                  
                  <input type="hidden" name="primary_address_id" id="primary_address_id" value="{$primary_address_id}" />
                  <input type="hidden" name="fax_number_id" id="fax_number_id" value="{$fax_number_id}" />
                  <input type="hidden" name="primary_broker_contact_id" id="primary_broker_contact_id" value="{$primary_broker_contact_id}" />
                  <input type="hidden" name="primary_contact_number_id" id="primary_contact_number_id" value="{$primary_contact_number_id}" />
                                    
                  <input type="hidden" name="sort" id="sort" value="{$sort}" />
                  <input type="hidden" name="page" id="page" value="{$page}" />
				  </td>
				</tr>
			      </div>
			    </form>
			    </TABLE>
<!--			</fieldset>-->
	            </td>
		  </tr>
		</TABLE>
                               
	      </TD>
            </TR>
          </TBODY></TABLE>
        </TD>
      </TR>
    </TBODY></TABLE>
  </TD>
</TR>
<div style="display:none;">
    <div id="applocations">
        <form method="post" name="frm2" id="frm2">
            <table id="addlocations" style="width:600px;height:auto;padding-bottom:60pxa;">
                <tr>
                    <td>
                        Add Localities :
                    </td>
                    <td>
                        <select name="locations" id="locations">
                            <option value="">Select Locations</option>
                            {if $cityLocArr != ''}
                                {foreach from= $cityLocArr key = k item = val}
                                   <option value="{$k}" {if $k == $city_id} selected {/if}>{$val}</option>
                                {/foreach}
                            {/if}
                        </select>
                        <input type="hidden" name="addmorecity" id="addmorecity" value="{$citylocids}" />
                    </td>
                </tr>
                
            </table>
            <table align="center">
                <tr>
                    <td>
                        <input type="hidden" name="brokercmpnyid" id="brokercmpnyid" value="{$brokerCompanyId}" />
                        <input type="hidden" name="remove_citylocids1" id="remove_citylocids1" />
                        <input type="hidden" name="citypkidArr1" id="citypkidArr1" value="{$citypkidArr}" />
                        <input type="button" name="cancel" id="cancel" value="Cancel" />
                        <input type="button" name="addloc" id="addloc" value="Add" />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function(){
        
        
        jQuery('#btnExit').click(function(){
            window.location.href = 'BrokerCompanyList.php';
        }); 
       
        jQuery('#pan').blur(function(){
            if(jQuery(this).val() == '')
                return false;
            
            if(jQuery(this).val() && (jQuery(this).val().length < 10 || jQuery(this).val().length > 10))
            {
                alert("Please enter 10 Character PAN");
                return false;
            }
            jQuery(this).val((jQuery(this).val()).toUpperCase());
            
            var dataString = 'pan='+jQuery(this).val();
            
            //alert(dataString);
//            return;
            jQuery.ajax({
                'type' : 'POST',
                'url' : 'brokercompanychkPan.php',
                'data' : dataString,
                'success' : function(data){
                    //alert(data);
//                    return;
                    var json = JSON.parse(data);
                    
                    if(json.response == 'error')
                    {
                        alert("Please enter another PAN");
                    }
                    return false;
                    
                },
                'error' : function(){
                    alert("Something went wrong");
                    return false;
                }
            
            
            });
        });
        
        jQuery('#cancel').click(function(){
            
            jQuery.fancybox.close();
        });
        
        jQuery('#addloc').click(function(){
            var dataString = jQuery('#frm2').serialize();
            var appendData = '';
            //alert(dataString);
            //return;
            jQuery.ajax({
                'type' : 'POST',
                'url' : 'brokerCompanyAddAjax.php',
                'data' : dataString,
                'success' : function(data){
                    //alert(data);
//                    return false;
                    var json = JSON.parse(data);
                    var appendData = '';
                    //alert(json.citylocids);
                    
                    if(jQuery('#brokerCompanyId').val())
                    {
                        jQuery('.cityloc_').remove();
                    }
                    
                    for(var key in json)
                    {
                        //alert(key);
                        
                        var temp = json[key];
                        
                        if(typeof temp.pkid != undefined && temp.pkid != '' && temp.pkid != undefined)
                            appendData += '<tr id="cityloc_'+temp.pkid+'" class="cityloc_"><td><input type="checkbox" name="'+temp.pkid+'" id="'+temp.pkid+'" class="citychkbox" /></td><td>'+temp.city+'</td><td>'+temp.location+'</td><td>'+temp.address+'</td></tr><tr id="citylocD_'+temp.pkid+'" class="cityloc_"><td colspan="4"><hr style="border: 0.5px dotted" /></td></tr>';
                        
                    }
                    jQuery('#cityData').append(appendData);
                    jQuery('#citypkidArr1').val(json.citylocids);
                    jQuery('#citypkidArr').val(json.citylocids);
                    jQuery.fancybox.close();
                    return false;
                },
                'error' : function(){
                    alert("Something went wrong");
                    return false;
                }
                
                
            });
            
        });
        
        jQuery('#delete').click(function(){
           
           if(jQuery('#selectall').is(':checked'))
           {
                jQuery(this).attr('disabled' , 'true');
                jQuery('#selectall').removeAttr('checked');
                var rmvall = new Array();
                
                jQuery('.citychkbox').each(function(){
                    var id = jQuery(this).attr('id');
                    jQuery('#cityloc_' + id).remove();
                    jQuery('#citylocD_' + id).remove();
                    rmvall.push(id);    
                });
                console.log(rmvall);
                jQuery('#remove_citylocids1').val(btoa(JSON.stringify(rmvall)));
                jQuery('#remove_citylocids').val(btoa(JSON.stringify(rmvall)));
           } 
           else
           {
                var removeCityIds = new Array();
                var rmv = '';
                
                if(jQuery('#remove_citylocids1').val())
                {
                    rmv = JSON.parse(atob(jQuery('#remove_citylocids1').val()));
                    //console.log(rmv);
                }
                jQuery('.citychkbox').each(function(){
                    var id = '';
                    if(jQuery(this).is(':checked'))
                    {
                        id = jQuery(this).attr('id');
                        jQuery('#cityloc_' + id).remove();
                        jQuery('#citylocD_' + id).remove();    
                    }
                    //alert(id);
                    if(rmv != '' && (id != '' && typeof id != undefined && id != undefined))
                        rmv.push(id);
                    else if(id != '' && typeof id != undefined && id != undefined)
                        removeCityIds.push(id);
                }); 
                
                if(rmv != '')
                    jQuery('#remove_citylocids1').val(btoa(JSON.stringify(rmv)));
                else
                    jQuery('#remove_citylocids1').val(btoa(JSON.stringify(removeCityIds)));
               // console.log('---RMV---');
//                console.log(rmv);
//                console.log('---Cityid---');
//                console.log(removeCityIds);

           }
            
        });
        
        jQuery('#selectall').click(function(){
            
            if(jQuery(this).is(':checked'))
            {
                jQuery('.citychkbox').each(function(){
                    
                    jQuery(this).attr('checked' , 'true');
                });
            }
            else
            {
                jQuery('.citychkbox').each(function(){
                    jQuery(this).removeAttr('checked');
                });
            }
        });
        
        jQuery('#addloc').click(function(){
            var flg = 0;
            jQuery('.cityloctxt').each(function(){
               if(jQuery(this).length > 0)
               {
                    if(jQuery(this).val() == '')
                    {
                        alert("Please enter the Address");
                        jQuery(this).focus();
                        flg = 1;
                        return false;
                    }
               } 
                
            });
            
            if(flg == 0)
                return true;
            else
                return false;
        });
        
        jQuery('#locations').change(function(){
            //alert(jQuery(this).val() + ' ' +jQuery(this).find(":selected").text());
            //alert(jQuery(this).val());
            if(!(jQuery('#' + jQuery(this).val()).length > 0))
            {
                var trdata = '<tr class="cityloc" id="cl_' + jQuery(this).val() + '"><td>' + jQuery(this).find(":selected").text() + ' :</td><td><input type="text" name="' + jQuery(this).val() + '" id="' + jQuery(this).val() + '" class="cityloctxt" /></td><td><input type="button" name="remove" id="remove-' + jQuery(this).val() + '" class="remove" value="Remove" /></td></tr>';
                jQuery('#addlocations').append(trdata);
            } 
            else
            {
                alert("You have already added Address for this City Location");
                return false;
            }
        });
        
        jQuery('.remove').live('click' , function(){
           var id = jQuery(this).attr('id');
           id = id.split("-");
           
           if(id[1] != '')
           {
                jQuery('#cl_' + id[1]).remove();
           }  
            
            
        });
        
        jQuery('#btnSave').click(function(){
            
            if(!jQuery('#name').val())
            {
                jQuery('#name').focus();
                alert("Please enter Broker Company Name");
                return false;
            }
            else if(jQuery('#pan').val() && (jQuery('#pan').val().length < 10 || jQuery('#pan').val().length > 10))
            {
                jQuery('#pan').focus();
                alert("Pan length must be equal to 10");
                return false;
            }
            else if(!jQuery('#description').val())
            {
                jQuery('#description').focus();
                alert("Please enter Description");
                return false;
            }
            
            
            /*--- OFFICE Addres Details Validations STARTS---*/
            if(!jQuery('#addressline1').val())
            {
                jQuery('#addressline1').focus();
                alert("Please enter Address");
                return false;
            }
            else if(!jQuery('#phone1').val())
            {
                jQuery('#phone1').focus();
                alert("Please enter Office Phone number");
                return false;
            }
            else if(jQuery('#phone1').val() && isNaN(jQuery('#phone1').val()))
            {
                jQuery('#phone1').focus();
                alert("Please enter only numbers");
                return false;
            }
            else if(jQuery('#phone1').val() && !isNaN(jQuery('#phone1').val()) && !(jQuery('#phone1').val().match(/^[0-9]+$/)))
            {
                jQuery('#phone1').focus();
                alert("Please enter only numbers");
                return false;
            }
            else if(jQuery('#phone1').val() && (jQuery('#phone1').val().length > 12))
            {
                jQuery('#phone1').focus();
                alert("Phone Number should be equal to 12 digits");
                return false;
            }
            
            
            if(jQuery('#phone2').val() && isNaN(jQuery('#phone2').val()))
            {
                jQuery('#phone2').focus();
                alert("Please enter only numbers");
                return false;
            }
            else if(jQuery('#phone2').val() && !isNaN(jQuery('#phone2').val()) && !(jQuery('#phone2').val().match(/^[0-9]+$/)))
            {
                jQuery('#phone2').focus();
                alert("Please enter only numbers");
                return false;
            }
            else if(jQuery('#phone2').val() && (jQuery('#phone2').val().length > 12))
            {
                jQuery('#phone2').focus();
                alert("Phone Number should be equal to 12 digits");
                return false;
            }
            
            
            if(jQuery('#fax').val() && isNaN(jQuery('#fax').val()))
            {
                jQuery('#fax').focus();
                alert("Please enter only numbers");
                return false;
            }
            else if(jQuery('#fax').val() && !isNaN(jQuery('#fax').val()) && !(jQuery('#fax').val().match(/^[0-9]+$/)))
            {
                jQuery('#fax').focus();
                alert("Please enter only numbers");
                return false;
            }
            else if(jQuery('#fax').val() && jQuery('#fax').val().length > 12)
            {
                jQuery('#fax').focus();
                alert("Fax Number should be less than or eaual to 12 digits");
                return false;
            }
            
            
            if(!jQuery('#city_id').val())
            {
                jQuery('#city_id').focus();
                alert("Please select City");
                return false;
            }
            
            
            if(jQuery('#pincode').val() && isNaN(jQuery('#pincode').val()))
            {
                jQuery('#pincode').focus();
                alert("Please enter only numbers");
                return false;
            }
            else if(jQuery('#pincode').val() && !isNaN(jQuery('#pincode').val()) && !(jQuery('#pincode').val().match(/^[0-9]+$/)))
            {
                jQuery('#pincode').focus();
                alert("Please enter only numbers");
                return false;
            }
            else if(jQuery('#pincode').val() && jQuery('#pincode').val().length > 12)
            {
                jQuery('#pincode').focus();
                alert("Pincode should be less than 12 digits");
                return false;
            }
            else if(jQuery('#email').val() && !(jQuery('#email').val().match(/^[a-zA-Z0-9._]+\@[a-zA-Z0-9]+\.[a-zA-Z]+$/)))
            {
                jQuery('#email').focus();
                alert("Please enter valid Email Address");
                return false;
            }
            
            /*--- OFFICE Addres Details Validations ENDS---*/
            
            /*--- Contact Person Details Validations START---*/
            var flag = 0;
            jQuery('.cp_name').each(function(){
                
                if(!jQuery(this).val())
                {
                    jQuery(this).focus();
                    alert("Please enter Contact Person Name");
                    flag = 1;
                    return false;
                }
                else
                {
                    flag = 0;
                }    
            });
            
            jQuery('.cp_phone1').each(function(){
                
                if(flag == 1)
                    return false;
                
                if(!jQuery(this).val())
                {
                    jQuery(this).focus();
                    alert("Please enter Phone Number");
                    flag = 1;
                    return false;
                }
                else if(jQuery(this).val() && isNaN(jQuery(this).val()))
                {
                    jQuery(this).focus();
                    alert("Please enter only numbers");
                    flag = 1;
                    return false;
                }
                else if(jQuery(this).val() && !isNaN(jQuery(this).val()) && !(jQuery(this).val().match(/^[0-9]+$/)))
                {
                    jQuery(this).focus();
                    alert("Please enter only numbers");
                    flag = 1;
                    return false;
                }
                else if(jQuery(this).val() && (jQuery(this).val().length > 12))
                {
                    jQuery(this).focus();
                    alert("Phone Number should be less or equal to 12 digits");
                    flag = 1;
                    return false;
                } 
                
                
            });
            
            jQuery('.cp_phone2').each(function(){
                
                if(flag == 1)
                    return false;
                
                if(jQuery(this).val() && isNaN(jQuery(this).val()))
                {
                    jQuery(this).focus();
                    alert("Please enter only numbers");
                    flag = 1;
                    return false;
                }
                else if(jQuery(this).val() && !isNaN(jQuery(this).val()) && !(jQuery(this).val().match(/^[0-9]+$/)))
                {
                    jQuery(this).focus();
                    alert("Please enter only numbers");
                    flag = 1;
                    return false;
                }
                else if(jQuery(this).val() && (jQuery(this).val().length > 12))
                {
                    jQuery(this).focus();
                    alert("Phone Number should be less or equal to 12 digits");
                    flag = 1;
                    return false;
                } 
                
            });
            
            jQuery('.cp_fax').each(function(){
                
                if(flag == 1)
                    return false;
                if(jQuery(this).val() && isNaN(jQuery(this).val()))
                {
                    jQuery(this).focus();
                    alert("Please enter only numbers");
                    flag = 1;
                    return false;
                }
                else if(jQuery(this).val() && !isNaN(jQuery(this).val()) && !(jQuery(this).val().match(/^[0-9]+$/)))
                {
                    jQuery(this).focus();
                    alert("Please enter only numbers");
                    flag = 1;
                    return false;
                }
                else if(jQuery(this).val() && (jQuery(this).val().length > 12))
                {
                    jQuery(this).focus();
                    alert("Fax Number should be les than or equal to 12 digits");
                    flag = 1;
                    return false;
                } 
                
                
            });
            
            jQuery('.cp_mobile').each(function(){
                
                if(flag == 1)
                    return false;
                if(jQuery(this).val() && isNaN(jQuery(this).val()))
                {
                    jQuery(this).focus();
                    alert("Please enter only numbers");
                    flag = 1;
                    return false;
                }
                else if(jQuery(this).val() && !isNaN(jQuery(this).val()) && !(jQuery(this).val().match(/^[0-9]+$/)))
                {
                    jQuery(this).focus();
                    alert("Please enter valid numbers");
                    flag = 1;
                    return false;
                }
                else if(jQuery(this).val() && (jQuery(this).val().length > 10 || jQuery(this).val().length < 10))
                {
                    jQuery(this).focus();
                    alert("Mobile Number should be equal to 10 digits");
                    flag = 1;
                    return false;
                } 
                
            });
            
            jQuery('.cp_email').each(function(){
                
                if(flag == 1)
                    return false;
                if(jQuery(this).val() && !(jQuery(this).val().match(/^[a-zA-Z0-9._]+\@[a-zA-Z0-9]+\.[a-zA-Z]+$/)))
                {
                    jQuery(this).focus();
                    alert("Please enter valid Email Address");
                    flag = 1;
                    return false;
                }    
                else
                {
                    flag = 0;
                }
                
            });
            
            
            /*--- Contact Person Details Validations ENDS---*/
            
            /*--- Customer Care Details Validations STARTS---*/
            
            if(jQuery('#cc_phone').val() && isNaN(jQuery('#cc_phone').val()))
            {
                jQuery('#cc_phone').focus();
                alert("Please enter only numbers");
                return false;
            }
            else if(jQuery('#cc_phone').val() && !isNaN(jQuery('#cc_phone').val()) && !(jQuery('#cc_phone').val().match(/^[0-9]+$/)))
            {
                jQuery('#cc_phone').focus();
                alert("Please enter only numbers");
                return false;
            }
            else if(jQuery('#cc_phone').val() && jQuery('#cc_phone').val().length > 12)
            {
                jQuery('#cc_phone').focus();
                alert("Phone Number should be equal to 12 digits");
                return false;
            }
            else if(jQuery('#cc_fax').val() && isNaN(jQuery('#cc_fax').val()))
            {
                jQuery('#cc_fax').focus();
                alert("Please enter only numbers");
                return false;
            }
            else if(jQuery('#cc_fax').val() && !isNaN(jQuery('#cc_fax').val()) && !(jQuery('#cc_fax').val().match(/^[0-9]+$/)))
            {
                jQuery('#cc_fax').focus();
                alert("Please enter only numbers");
                return false;
            }
            else if(jQuery('#cc_fax').val() && jQuery('#cc_fax').val().length > 12)
            {
                jQuery('#cc_fax').focus();
                alert("Fax Number should be less than or eaual to 12 digits");
                return false;
            }
            else if(jQuery('#cc_mobile').val() && isNaN(jQuery('#cc_mobile').val()))
            {
                jQuery('#cc_mobile').focus();
                alert("Please enter only numbers");
                return false;
            }
            else if(jQuery('#cc_mobile').val() && !isNaN(jQuery('#cc_mobile').val()) && !(jQuery('#cc_mobile').val().match(/^[0-9]+$/)))
            {
                jQuery('#cc_mobile').focus();
                alert("Please enter only numbers");
                return false;
            }
            else if(jQuery('#cc_mobile').val() && (jQuery('#cc_mobile').val().length > 10 || jQuery('#cc_mobile').val().length < 10))
            {
                jQuery('#cc_mobile').focus();
                alert("Mobile Number should be equal to 10 digits");
                return false;
            }
            else if(jQuery('#cc_email').val() && !(jQuery('#cc_email').val().match(/^[a-zA-Z0-9._]+\@[a-zA-Z0-9]+\.[a-zA-Z]+$/)))
            {
                jQuery('#cc_email').focus();
                alert("Please enter valid Email Address");
                return false;
            }
            
            /*--- Customer Care Details Validations ENDS---*/
            var cp_name = {};
            var cp_phone1 = {};
            var cp_phone2 = {};
            var cp_fax = {};
            var cp_mobile = {};
            var cp_email = {};
            var cp_ids = new Array();
            
            jQuery('.cp_name').each(function(){
                var cp_id = jQuery(this).attr('id');
                
                //alert(cp_id + ' ' + jQuery(this).val());
                cp_id = cp_id.split('-');
                if(cp_id[1] != '')
                {
                    cp_ids.push(cp_id[1]);
                    cp_name[cp_id[1]] = jQuery(this).val();
                }
                
                //alert(cp_id[1]);
                
            });
            
            jQuery('.cp_phone1').each(function(){
                var cp_id = jQuery(this).attr('id');
                cp_id = cp_id.split('-');
                
                if(cp_id[1] != '')
                {
                    cp_phone1[cp_id[1]] = jQuery(this).val();
                }
            });
            
            jQuery('.cp_phone2').each(function(){
                var cp_id = jQuery(this).attr('id');
                cp_id = cp_id.split('-');
                
                if(cp_id[1] != '')
                {
                    cp_phone2[cp_id[1]] = jQuery(this).val();
                }
            });
            
            jQuery('.cp_fax').each(function(){
                var cp_id = jQuery(this).attr('id');
                cp_id = cp_id.split('-');
                
                if(cp_id[1] != '')
                {
                    cp_fax[cp_id[1]] = jQuery(this).val();
                }
            });
            
            jQuery('.cp_mobile').each(function(){
                var cp_id = jQuery(this).attr('id');
                cp_id = cp_id.split('-');
                
                if(cp_id[1] != '')
                {
                    cp_mobile[cp_id[1]] = jQuery(this).val();
                }
            });
            
            jQuery('.cp_email').each(function(){
                var cp_id = jQuery(this).attr('id');
                cp_id = cp_id.split('-');
                
                if(cp_id[1] != '')
                {
                    cp_email[cp_id[1]] = jQuery(this).val();
                }
            });
            
            
            if(flag == 0)
            {
                
                //console.log(cp_ids);
                //console.log(JSON.stringify(cp_ids));
                
                //console.log(JSON.stringify(cp_name));
                 
                jQuery('#xcp_name').val(btoa(JSON.stringify(cp_name)));
                jQuery('#xcp_phone1').val(btoa(JSON.stringify(cp_phone1)));
                jQuery('#xcp_phone2').val(btoa(JSON.stringify(cp_phone2)));
                jQuery('#xcp_fax').val(btoa(JSON.stringify(cp_fax)));
                jQuery('#xcp_mobile').val(btoa(JSON.stringify(cp_mobile)));
                jQuery('#xcp_email').val(btoa(JSON.stringify(cp_email)));
                jQuery('#xcp_ids').val(btoa(JSON.stringify(cp_ids)));
                return true;   
            }   
            else
                return false;
        });
       
        jQuery("a#showcontent").fancybox({
            //'width'  : 600,           // set the width
//            'height' : 600,           // set the height
//            'type'   : 'iframe'
        });
        
        jQuery('#addcontact').click(function(){
            var timestamp = new Date().getUTCMilliseconds();
            
            var trdata = '<tr class="' + timestamp + '"><td colspan="4"><hr style="border: 0.1px dotted;"/></td></tr><tr class="' + timestamp + '"><td width="30%" valign="top"><input type="checkbox" name="chkbox_' + timestamp + '" id="chkbox_' + timestamp + '" class="chkbox" /> &nbsp;Name :<font color = "red">*</font></td><td width="10%" valign="top"><input type=text name="cp_name['+ timestamp +']" id="cp_name-' + timestamp + '" class="cp_name" value ="" style="width:250px;" /></td><td width="20%" align="right" >Contact Mobile : </td><td width="30%" align="left" ><input type=text name="cp_mobile['+ timestamp +']" id="cp_mobile-' + timestamp + '" class="cp_mobile" value="" style="width:85px;" maxlength="10" /></td></tr><tr class="' + timestamp + '"><td width="15%" valign="top" >Contact Phone 1 :<font color = "red">*</font> </td><td width="10%" align="left" valign="top" ><input type=text maxlength="2" readonly="true" value="+91" style="width:25px;" /><input type=text name="cp_phone1['+ timestamp +']" id="cp_phone1-' + timestamp + '" class="cp_phone1" value="" maxlength="12" style="width:85px;" /></td><td width="15%" align="right" valign="top" >Contact Email:</td><td width="10%" align="left" valign="top" ><input type=text name="cp_email['+ timestamp +']" id="cp_email-' + timestamp + '" class="cp_email" value="" style="width:250px;" /></td></tr><tr class="' + timestamp + '"><td width="15%" valign="top" >Contact Phone 2 : </td><td width="10%" align="left" valign="top" ><input type=text maxlength="2" readonly="true" value="+91" style="width:25px;" /><input type=text name="cp_phone2['+ timestamp +']" id="cp_phone2-' + timestamp + '" class="cp_phone2" value="" maxlength="12" style="width:85px;" /></td></tr><tr class="' + timestamp + '"><td width="15%" valign="top" >Contact Fax : </td><td width="10%" align="left" valign="top" ><input type=text name="cp_fax['+ timestamp +']" id="cp_fax-' + timestamp + '" class="cp_fax" value="" maxlength="12" style="width:85px;" /></td></tr>';
            var acontactids = new Array();
            
            if(jQuery('#acontactids').val())
            {
                var temp = JSON.parse(atob(jQuery('#acontactids').val()));
                temp.push(timestamp);
                jQuery('#acontactids').val(btoa(JSON.stringify(temp)));
            }
            else
            {
                acontactids.push(timestamp);
                jQuery('#acontactids').val(btoa(JSON.stringify(acontactids)));
            }
            
            jQuery('#contactdet').append(trdata); 
            
            
            
        });
        
        
        jQuery('#delcontact').click(function(){
            
            var removeContact = new Array();
            var rmv = '';
            
            if(jQuery('#rcontactids').val())
            {
                rmv = JSON.parse(atob(jQuery('#rcontactids').val()));
                //console.log(rmv);
            }
            jQuery('.chkbox').each(function(){
                var id = '';
                if(jQuery(this).is(':checked'))
                {
                    id = jQuery(this).attr('id');
                    id = id.split("_");
                    jQuery('.'+ id[1]).remove();    
                }
                
                if(jQuery('#acontactids').val() && (id[1] != '' && typeof id[1] != undefined && id[1] != undefined))
                {
                    var acontactids = JSON.parse(atob(jQuery('#acontactids').val()));
                    var temp = new Array(); 
                    for(var key in acontactids)
                    {
                        //console.log(acontactids[key] + '<-- -->' + id[1]);
                        if(acontactids[key] != id[1])
                        {
                            temp.push(acontactids[key]);                             
                        }
                        
                        //console.log('KEY :' + key);
//                        console.log('Val :' + acontactids[key]);
                    }    
                    //console.log(temp)
                    jQuery('#acontactids').val(btoa(JSON.stringify(temp)));
                }
                
                
                if(rmv != '' && (id[1] != '' && typeof id[1] != undefined && id[1] != undefined))
                    rmv.push(id[1]);
                else if(id[1] != '' && typeof id[1] != undefined && id[1] != undefined)
                    removeContact.push(id[1]);
            }); 
            
            //console.log('---RMV---');
//            console.log(rmv);
//            console.log('---Contact---');
//            console.log(removeContact);
//            
            if(rmv != '')
                jQuery('#rcontactids').val(btoa(JSON.stringify(rmv)));
            else
                jQuery('#rcontactids').val(btoa(JSON.stringify(removeContact)));
            
        });
        
    });
    
    Calendar.setup({

                inputField     :    "active_since",     // id of the input field
                ifFormat       :    "%d/%m/%Y",      // format of the input field
                button         :    "f_trigger_c_to",  // trigger for the calendar (button ID)
                align          :    "Tl",           // alignment (defaults to "Bl")
                singleClick    :    true,
                showsTime		:	true

             });
 </script>