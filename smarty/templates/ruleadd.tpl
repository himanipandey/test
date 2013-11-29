
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
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>{if $sellerCompanyId == ''} Add New {else} Edit {/if} Rules</TD>
                      <TD align=right ></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
		      
			  <TABLE cellSpacing=2 cellPadding=4 width="65%" align=center border=0>
			    <form method="post" id="frm1" enctype="multipart/form-data" action="ruleadd.php">
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
                    <td width="30%" align="right" valign="top">Company Name :<font color = "red">*</font></td>
                    <td width="10%" align="left" valign="top">
                        <select name="broker_cmpny" id="broker_cmpny">
                            <option value="">--Select Company--</option>
                            {if $brokerArr != ''}
                                    {foreach from= $brokerArr key = k item = val}
                                        <option value="{$val->id}" {if $val->id == $broker_id} selected="" {/if}>{$val->broker_name}</option>
                                    {/foreach}
                            {/if}
                        </select>
                        
                    </td>  
				</tr>
                <tr>
                    <td width="15%" align="right" valign="top" >Rule Name :<font color = "red">*</font></td>
                    <td width="10%" align="left" valign="top" >
                        <input type=text name="rule_name" id="rule_name" maxlength="10" value="{$rule_name}" style="width:238px;" />	
                    </td>
                </tr>
                
				<tr>
				    <td width="20%" align="right" valign="top">City :<font color = "red">*</font></td>
                    <td width="30%" align="left" >
				        <select name="city_id" id="city_id" {if $copy == "on"} disabled="" {/if} style="width:250px;">
                           <option value="">Select City</option>
                           {foreach from= $cityArr key = k item = val}
                               <option value="{$k}" {if $k == $cityhiddenArr} selected {/if}>{$val}</option>
                           {/foreach}
                       </select>
                      </td>
                      {if $ErrorMsg["city"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["hq"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
                
                <tr>
				    <td width="20%" align="right" >Locality : </td>
                    <td width="20%" align="right" >Project : </td>
                    <td width="20%" align="right" >Agent : </td>
				</tr>
                <tr>
                    <td width="30%" align="right" >
                        <select multiple="" name="locality" id="locality" style="20px;">
                            <option>---Select Locality---</option>
                            {if $locality != ''}
                                {foreach $locality key = k item = val}
                                    <option value="{$val->locality_id}">{$val->label}</option>
                                {/foreach} 
                            {/if}
                        </select>
                    </td>
                    <td width="30%" align="right" >
                        <select multiple="" name="project" id="project">
                            <option>---Select Project---</option>
                        </select>
                    </td>
                    <td width="30%" align="right" >
                        <select multiple="" name="agent" id="agent">
                            <option>---Select Agent---</option>
                            {if $seller_company != ''}
                                {foreach $seller_company key = k item = val}
                                    <option value="{$val->agent_id}">{$val->agent_name}</option>
                                {/foreach}
                            {/if}
                        </select>
                    </td>                    
                </tr>                
                <tr>
                    <td>&nbsp;</td>
                </tr>
				<tr>
				  <td align="center" colspan="2">
				  <input type="hidden" name="ruleId" id="ruleId" value="{$ruleId}" />
				  <input type="submit" name="btnSave" id="btnSave" value="Submit Rule" style="float:right;" />
				  &nbsp;&nbsp;<input type="button" name="btnExit" id="btnExit" value="Exit" style="float:right;" />
                  
                  
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
        
        jQuery('#btnSave').click(function(){
            
            if(!jQuery('#broker_cmpny').val())
            {
                jQuery('#broker_cmpny').focus();
                alert("Please select Company");
                return false;
            }
            else if(!jQuery('#rule_name').val())
            {
                jQuery('#rule_name').focus();
                alert("Please enter Rule");
                return false;
            }
            else if(!jQuery('#city_id').val())
            {
                jQuery('#city_id').focus();
                alert("Please select City");
                return false;
            }
            
            return false;
                
        });        
    });
    
    
 </script>
