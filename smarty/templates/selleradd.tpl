
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
                    <td width="15%" align="right" valign="top" >Seller Name : </td>
                    <td width="10%" align="left" valign="top" >
                        <input type=text name="{$name}" id="pan" value="{$name}" style="width:85px;" />	
                    </td>
                    <td width="15%" align="right" valign="top" >Seller Type : </td>
                    <td width="10%" align="left" valign="top" >
                        <select name = "seller_type" id = "seller_type" style="width:90px;">
                               <option>--Select Type--</option>
                           </select>
                        <td width="10%" align="right">Status :</td>
                       <td width="10%" align="left" >
        				   <select name = "status" id = "status" style="width:90px;">
                               <option value="Active" {if $status == 'Active'}selected{/if}>Active</option>
                               <option value="Inactive" {if $status == 'Inactive'}selected{/if}>Inactive</option>
                           </select>
                      </td>xt name="seller_type" id="seller_type" value="{$seller_type}" style="width:85px;" />	
                    </td>
				</tr>

                
                
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3">
                        Contact Details
                        <hr />
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <input type="checkbox" name="checkbox" id="checkbox" />&nbsp;&nbsp;&nbsp; Copy Company Address
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
    				<td width="15%" align="right" valign="top" >Mobile : </td>
                    <td width="10%" align="left" valign="top" >
                        <input type=text maxlength="2" readonly="true" value="+91" style="width:25px;" />
                        <input type=text name="mobile" id="mobile" value="{$mobile}" maxlength="12" style="width:85px;" />
                        {if $ErrorMsg["mobile"] != ''}
                            <font color = "red">{$ErrorMsg["mobile"]}</font>
                        {/if}		
                    </td>
    				<td width="15%" align="right" valign="top" >Email:</td>
                    <td width="10%" align="left" valign="top" >
                        <input type=text name="email" id="email" value="{$email}" style="width:250px;" />	
                        {if $ErrorMsg["email"] != ''}
                            <font color = "red">{$ErrorMsg["email"]}</font>
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
                    <td width="15%" valign="top" >Seller :</td>
                    <td width="10%" align="left" valign="top" >
                        <input type="file" name="seller_img" id="seller_img" style="width:85px;" />
                        {if $ErrorMsg["seller_img"] != ''}
                            <font color = "red">{$ErrorMsg["seller_img"]}</font>
                        {/if}	
                    </td>
                    <td width="20%" align="right" valign="top" >
                        <input type="button" name="upload" id="upload" value="Upload" />
                    </td>
				</tr>
                
               
                <tr>
                    <td colspan="7">
                        Rating Details
                        <hr />
                    </td>
                </tr>
                
                <tr>
    				<td width="15%" valign="top" >Seller Rating : </td>
                    <td width="10%" align="left" valign="top" >
                        Auto: &nbsp;&nbsp;<input type="radio" name="rating" id="rating1" value="3.0" />
                    </td>
    				
				</tr>     
                <tr>
                    <td>&nbsp;</td>
                    <td width="15%" valign="top">
                        Forced: &nbsp;&nbsp;
                        <select name="forced" id="forced">
                            <option value="">--Select Rating--</option>
                        </select>
                        
                    </td>
                </tr>
                
                <tr>
    				<td width="15%" valign="top" >Active Since : </td>
                    <td width="10%" align="left" valign="top" >
                        <input type=text name="active_since" id="active_since" value="{$active_since}" style="width:85px;" readonly="1" size="10" />
                        <img src="../images/cal_1.jpg" id="f_trigger_c_to" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />	
                    </td>
    				<td width="10%" align="left" valign="top">
                        Seller Qualification
                    </td>
                    <td width="10%" align="left" valign="top">
                        <select name="qauli" id="qauli">
                            <option value="">--Select Qualification--</option>
                        </select>
                    </td>
				</tr>     
                                
                
                
                
				<tr>
				  <td colspan="3">&nbsp;</td>
				  <td align="left">
				  <input type="hidden" name="sellerId" id="sellerId" value="{$sellerId}" />
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


<script type="text/javascript">
    jQuery(document).ready(function(){
        
        
        jQuery('#btnExit').click(function(){
            window.location.href = 'SellerCompanyList.php';
        }); 
       
        jQuery('#btnSave').click(function(){
            
            if(!jQuery('#name').val())
            {
                jQuery('#name').focus();
                alert("Please enter Seller Company Name");
                return false;
            }
            else if(!jQuery('#seller_type').val())
            {
                jQuery('#seller_type').focus();
                alert("Please seller type");
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
            else if(jQuery('#phone2').val() && isNaN(jQuery('#phone2').val()))
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
            else if(!jQuery('#city_id').val())
            {
                jQuery('#city_id').focus();
                alert("Please select City");
                return false;
            }
            else if(jQuery('#pincode').val() && isNaN(jQuery('#pincode').val()))
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
            else if(jQuery('#mobile').val() && !isNaN(jQuery('#mobile').val()) && !(jQuery('#mobile').val().match(/^[0-9]+$/)))
            {
                jQuery('#mobile').focus();
                alert("Please enter only numbers");
                return false;
            }
            else if(jQuery('#mobile').val() && (jQuery('#mobile').val().length > 10 || jQuery('#mobile').val().length < 10))
            {
                jQuery('#mobile').focus();
                alert("Mobile Number should be equal to 10 digits");
                return false;
            }
            
            return false;
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
