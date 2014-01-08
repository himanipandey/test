<link rel="stylesheet" type="text/css" href="fancy2.1/source/jquery.fancybox.css" media="screen" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">


<style type="text/css">
    .ui-autocomplete {
    max-height: 100px;
    overflow-y: auto;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
    z-index:10000;
    }
    /* IE 6 doesn't support max-height
    * we use height instead, but this forces the menu to always be this tall
    */
    * html .ui-autocomplete {
    height: 100px;
    }
    .ui-menu-item a {
        font-size: 10px;
    }
    
    .ui-state-focus a{
        font-size: 10px;
    }
    .divloc_class{
        border: 1px solid #D3D3D3;
        height: 100px;
        overflow: scroll;
        width: 230px;
    }
    
    .li-data{
        background-color: #000000;
        color: #FFFFFF;
        font-family: Verdana;
        font-size: 12px;
        cursor:pointer;
    }
    
    .li-data:hover{
        background-color: orange;
        color: #FFFFFF;
        font-family: Verdana;
        font-size: 12px;
        cursor:pointer;
        font-weight:bold;
    }
</style>
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>
<script type="text/javascript" src="fancy2.1/source/jquery.fancybox.pack.js"></script>

<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<script type="text/javascript">
    $(function() {
        var availableTags = {$brokerArr}; 
        $( "#seller_cmpny" ).autocomplete({
                source: availableTags,
                select: function( event, ui ) {
                    //event.preventDefault();
                    //alert(ui.item.value+ ' '+ ui.item.id );
                    jQuery('#seller_cmpny_hidden').val(ui.item.id);
                }
            });
        });
        
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
          <TD class=border-all vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</TD>
          <TD class=border-rt vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=../images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>{if $sellerCompanyId == ''} Add New {else} Edit {/if} Agents</TD>
                      <TD align=right ></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
		      
			  <TABLE cellSpacing=2 cellPadding=4 width="93%" align=center border=0>
			    <form method="post" id="frm1" enctype="multipart/form-data" action="sellercompanyadd.php">
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
                    <td width="30%" valign="top">Company Name :<font color = "red">*</font></td>
                    <td width="10%" valign="top">
                        <input type="text" name="seller_cmpny" id="seller_cmpny" value="{$seller_cmpny}" />
                        
                        
                    </td>
                    <td width="15%" align="right" valign="top" >Agent Name :<font color = "red">*</font></td>
                    <td width="10%" align="left" valign="top" >
                        <input type=text name="seller_name" id="seller_name" value="{$seller_name}" style="width:238px;" />	
                    </td>
                    <td width="10%" align="right">Status :</td>
                    <td width="10%" align="left" >
    				    <select name = "status" id = "status" style="width:90px;">
                           <option value="Active" {if $status == 'Active'}selected=""{/if}>Active</option>
                           <option value="Inactive" {if $status == 'Inactive'}selected=""{/if}>Inactive</option>
                        </select>
                    </td>
                      
				</tr>

                
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="8">
                        Contact Details
                        <hr />
                    </td>
                </tr>
                {if $sellerCompanyId == ""}
                <tr>
                    <td colspan="8">
                        
                        <input type="checkbox" name="copy" id="copy" />&nbsp;Copy Company Address
                        
                    </td>
                </tr>
                {/if}
				<tr>
				    <td width="20%" align="left" >Address Line 1 : <font color = "red">*</font></td>
                    <td width="30%" align="left" >
                        <input type=text name="addressline1" class="check" id="addressline1" value="{$addressline1}"  style="width:250px;" />
                        {if $ErrorMsg["addressline1"] != ''}
                            <font color = "red">{$ErrorMsg["addressline1"]}</font>
                        {/if}
                    </td>
                    <td width="20%" align="left" valign="top">City :<font color = "red">*</font></td>
                    <td width="30%" align="left" >
				        <select name="city_id" id="city_id" style="width:250px;">
                           <option value="">Select City</option>
                           {foreach from= $cityArr key = k item = val}
                               <option value="{$k}" {if $k == $cityhiddenArr} selected {/if}>{$val}</option>
                           {/foreach}
                       </select>
                      </td>
                      {if $ErrorMsg["city"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["hq"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
                
                <tr>
				    <td width="20%" align="left" >Address Line 2 : </td>
                    <td width="30%" align="left" >
                        <input type=text name="addressline2" class="check" id="addressline2" value="{$addressline2}" style="width:250px;" />
                    </td>
                    <td width="15%" align="left" valign="top" >Pincode : </td>
                    <td width="10%" align="left" valign="top" >
                        <input type=text name="pincode" class="check" id="pincode" value="{$pincode}" maxlength="12" style="width:85px;" />
                        {if $ErrorMsg["pincode"] != ''}
                            <font color = "red">{$ErrorMsg["pincode"]}</font>
                        {/if}	
                    </td>
				</tr>
                
                <tr>
    				<td width="15%" align="left" valign="top" >Office Phone 1 : </td>
                    <td width="10%" align="left" valign="top" >
                        <input type=text maxlength="2" readonly="true" value="+91" style="width:25px;" />
                        <input type=text name="phone1" class="check" id="phone1" value="{$phone1}" maxlength="12" style="width:85px;" />
                        {if $ErrorMsg["phone1"] != ''}
                            <font color = "red">{$ErrorMsg["phone1"]}</font>
                        {/if}		
                    </td>
                    <td width="15%" align="left" valign="top" >Office Phone 2 : </td>
                    <td width="10%" align="left" valign="top" >
                        <input type=text maxlength="2" readonly="true" value="+91" style="width:25px;" />
                        <input type=text name="phone2" class="check" id="phone2" value="{$phone2}" maxlength="12" style="width:85px;" />
                        {if $ErrorMsg["phone2"] != ''}
                            <font color = "red">{$ErrorMsg["phone2"]}</font>
                        {/if}		
                    </td>
                    
				</tr>
                
                <tr>
    				<td width="15%" align="left" valign="top" >Mobile:<font color = "red">*</font></td>
                    <td width="10%" align="left" valign="top" >
                        <input type=text name="mobile" id="mobile" value="{$mobile}" maxlength="10" style="width:85px;" />	
                        {if $ErrorMsg["mobile"] != ''}
                            <font color = "red">{$ErrorMsg["mobile"]}</font>
                        {/if}	
                    </td>
    				
    				<td width="15%" align="left" valign="top" >Office Email:</td>
                    <td width="10%" align="left" valign="top" >
                        <input type=text name="email" id="email" value="{$email}" style="width:250px;" />	
                        {if $ErrorMsg["email"] != ''}
                            <font color = "red">{$ErrorMsg["email"]}</font>
                        {/if}	
                    </td>
    				
				</tr>
                
                
                <tr>
                    <td colspan="8">
                        Other Details
                        <hr />
                    </td>
                </tr>
                <tr>
                    <td width="15%" align="right" valign="top" >Agent Logo:</td>
                    <td width="10%" align="left" valign="top" >
                        <input type="file" name="logo" id="logo" value="{$logo}" style="width:250px;" />
                        		
                    </td>
                    <td  width="10%" align="left" valign="top">
                        <div style="width:130px!important;height:130px">
                            {if $imgurl != ''} <a href="#div_img" class="showcontent" ><img src="{$imgurl}" style="width:120px;height:90px;cursor: pointer;" /> </a> <div style="display:none;"><div id="div_img"><img src="{$imgurl}" /></div></div> {else}<img src="no_image.gif" width="" height="" /> {/if}
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td colspan="8">
                        Rating Details
                        <hr />
                    </td>
                </tr>
                <tr>
                    <td width="15%" align="right" valign="top" >Agent Rating:</td>
                    <td width="10%" align="left" valign="top" >
                        <input type="radio" name="rating" class="rating" id="rating_auto" {if $rateoption == "auto"} checked=""  {/if} value="3.0" />	&nbsp;Auto&nbsp;
                        <input type="auto" name="auto" id="auto" value="3.0" style="width:25px;" readonly="" />
                    </td>
                </tr>
                <tr>
                    <td width="15%" align="right" valign="top" >&nbsp;</td>
                    <td width="10%" align="left" valign="top" >
                        <input type="radio" class="rating" name="rating" {if $rateoption == "forced"} checked=""  {/if} id="rating_forced" value="" />&nbsp;Forced
                        <select name="rate" id="rate">
                            <option value="0.5" {if $rating != '' && $rating == "0.5"} selected=""  {/if}>0.5</option>
                            <option value="1.0" {if $rating != '' && $rating == "1.0"} selected=""  {/if}>1.0</option>
                            <option value="1.5" {if $rating != '' && $rating == "1.5"} selected=""  {/if}>1.5</option>
                            <option value="2.0" {if $rating != '' && $rating == "2.0"} selected=""  {/if}>2.0</option>
                            <option value="2.5" {if $rating != '' && $rating == "2.5"} selected=""  {/if}>2.5</option>
                            <option value="3.0" {if $rating != '' && $rating == "3.0"} selected=""  {/if}>3.0</option>
                            <option value="3.5" {if $rating != '' && $rating == "3.5"} selected=""  {/if}>3.5</option>
                            <option value="4.0" {if $rating != '' && $rating == "4.0"} selected=""  {/if}>4.0</option>
                            <option value="4.5" {if $rating != '' && $rating == "4.5"} selected=""  {/if}>4.5</option>
                            <option value="5.0" {if $rating != '' && $rating == "5.0"} selected=""  {/if}>5.0</option>
                        </select>	
                    </td>
                </tr>
                
                <tr>
                    <td width="15%" align="right" valign="top" >Active Since</td>
                    <td width="10%" align="left" valign="top" >
                        <input type=text name="active_since" id="active_since" value="{$active_since}" style="width:85px;" readonly="1" size="10" />
                        <img src="../images/cal_1.jpg" id="f_trigger_c_to" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />	
                    </td>
                    <td width="15%" align="right" valign="top" >Select Qualification</td>
                    <td width="10%" align="left" valign="top" >
                        <select name="qualification" id="qualification">
                            <option value="">--Select Qualification--</option>
                            {foreach from= $qualification key = k item = val}
                               <option value="{$val['id']}" {if $val['id'] == $qualification_id} selected="true" {/if}>{$val['qualification']}</option>
                            {/foreach}
                        </select>	
                    </td>
                </tr>
				<tr>
				  <td colspan="3">&nbsp;</td>
				  <td align="left">
				  <input type="hidden" name="sellerCompanyId" id="sellerCompanyId" value="{$sellerCompanyId}" />
				  <input type="submit" name="btnSave" id="btnSave" value="Save" style="float:left;" />
				  &nbsp;&nbsp;<input type="button" name="btnExit" id="btnExit" value="Exit" style="float:right:" />
                  <input type="hidden" name="seller_cmpny_hidden" id="seller_cmpny_hidden" value="{$broker_id}" />
                  <input type="hidden" name="addressid" id="addressid" value="{$addressid}" />
                  <input type="hidden" name="brkr_cntct_id" id="brkr_cntct_id" value="{$brkr_cntct_id}" />
                  <input type="hidden" name="cityhiddenArr" id="cityhiddenArr" value="" />
                  <input type="hidden" name="brokerhiddenArr" id="brokerhiddenArr" value="" />
                  <input type="hidden" name="typehiddenArr" id="typehiddenArr" value="" />
                  <input type="hidden" name="statushiddenArr" id="statushiddenArr" value="" />
                  <input type="hidden" name="quahiddenArr" id="quahiddenArr" value="" />
                  <input type="hidden" name="imgid" value="{$imgid}" id="imgid" />
                  <input type="hidden" name="rateoption" value="{$rateoption}" id="rateoption" />
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
        
        jQuery('#copy').click(function(){
            
            if(!jQuery('#seller_cmpny_hidden').val())
            {
                alert("Please select Seller Company");
                return false;
            }
            
            if(jQuery('#copy').is(':checked'))
            {
                jQuery('.check').attr('readonly' , 'true');
                jQuery('#city_id').attr('disabled' , 'true');
                
                var dataString = 'broker='+jQuery('#seller_cmpny_hidden').val();
                //alert(dataString);
    //            return;
                jQuery.ajax({
                   'type' : 'POST',
                   'url' : 'sellerfetchAddress.php',
                   'data' : dataString,
                   'success' : function(data){
                        //alert(data);
    //                    return;
                        if(data != '') 
                        {
                            var json = JSON.parse(data);
                            if(json != '')
                            {
                                jQuery('#addressline1').val(json.addressline1);
                                jQuery('#addressline2').val(json.addressline2);
                                jQuery('#city_id').val(json.city_id);
                                jQuery('#cityhiddenArr').val(json.city_id);
                                jQuery('#pincode').val(json.pincode);
                                jQuery('#phone1').val(json.phone1);
                                jQuery('#phone2').val(json.phone2);
                                jQuery('#mobile').val(json.mobile);
                                jQuery('#email').val(json.email);
                            }
                        }
                        
                   },
                   'error': function(){
                        alert("Something went wrong");
                        return;
                   } 
                    
                });    
                
            }
            else
            {
                jQuery('.check').removeAttr('readonly');
                jQuery('#city_id').removeAttr('disabled');
                //alert("here");
                //return false;
            }
            
            
            
        });
        
        jQuery('.rating').click(function(){
            
           var id = jQuery(this).attr('id');
           id = id.split("_");
           
           if(id[1] != '')
           {
                jQuery('#rateoption').val(id[1]);
           } 
            
        });
        
        jQuery('#btnSave').click(function(){
            
            if(!jQuery('#seller_cmpny').val())
            {
                jQuery('#seller_cmpny').focus();
                alert("Please select Seller Company Name");
                return false;
            }
            else if(!jQuery('#seller_name').val())
            {
                jQuery('#seller_name').focus();
                alert("Please enter Seller Name");
                return false;
            }
            
            if(!jQuery('#copy').is(':checked'))
            {
                jQuery('#cityhiddenArr').val(jQuery('#city_id').val());
                /*---OFFICE Addres Details Validations STARTS---*/
                if(!jQuery('#addressline1').val())
                {
                    jQuery('#addressline1').focus();
                    alert("Please enter Address");
                    return false;
                }
                else if(!jQuery('#mobile').val())
                {
                    jQuery('#mobile').focus();
                    alert("Please enter Mobile Number");
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
                else if(jQuery('#fax').val() && isNaN(jQuery('#fax').val()))
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
                
                
                /*--- OFFICE Addres Details Validations ENDS---*/
            }
            
            if(!jQuery('#mobile').val())
            {
                jQuery('#mobile').focus();
                alert("Please enter Mobile");
                return false;
            }
            else if(jQuery('#mobile').val() && isNaN(jQuery('#mobile').val()))
            {
                jQuery('#mobile').focus(); 
                alert("Please enter only numbers");
                return false;
            }
            else if(jQuery('#mobile').val() && !isNaN(jQuery('#mobile').val()) && !(jQuery('#mobile').val().match(/^[0-9]+$/)))
            {
                jQuery('#mobile').focus();
                alert("Please enter valid numbers");
                return false;
            }
            else if(jQuery('#mobile').val() && (jQuery('#mobile').val().length < 10 || jQuery('#mobile').val().length > 10))
            {
                jQuery('#mobile').focus();
                alert("Phone Number should be equal to 10 digits");
                return false;
            } 
            else if(jQuery('#email').val() && !(jQuery('#email').val().match(/^[a-zA-Z0-9._]+\@[a-zA-Z0-9]+\.[a-zA-Z]+$/)))
            {
                jQuery('#email').focus();
                alert("Please enter valid Email Address");
                return false;
            }
            
            jQuery('#brokerhiddenArr').val(jQuery('#seller_cmpny').val());
            jQuery('#typehiddenArr').val(jQuery('#type').val());
            jQuery('#statushiddenArr').val(jQuery('#status').val());
            jQuery('#quahiddenArr').val(jQuery('#qualification').val());
            
            return true;
                
        });     
        
        $('.showcontent').fancybox({
            
        });   
    });
    function dateRange(date) {
    var now = new Date();
    return (date.getTime() > now.getTime() )
    }
    
    Calendar.setup({

                inputField     :    "active_since",     // id of the input field
                ifFormat       :    "%d/%m/%Y",      // format of the input field
                button         :    "f_trigger_c_to",  // trigger for the calendar (button ID)
                align          :    "Tl",           // alignment (defaults to "Bl")
                dateStatusFunc : dateRange,
                singleClick    :    true,
                showsTime		:	true

             });
 </script>
