
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
                        <input type=text name="rule_name" id="rule_name" value="{$rule_name}" style="width:238px;" />	
                    </td>
                </tr>
                
				<tr>
				    <td width="20%" align="right" valign="top">City :<font color = "red">*</font></td>
                    <td width="30%" align="left" >
				        <select name="city_id" id="city_id" {if $copy == "on"} disabled="" {/if} style="width:250px;">
                           <option value="">Select City</option>
                           {foreach from= $cityArr key = k item = val}
                               <option value="{$k}" {if $k == $city_id} selected {/if}>{$val}</option>
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
                        <select multiple="" name="locality[]" id="locality" style="20px;">
                            <option>---Select Locality---</option>
                            {if $locality != ''}
                                {foreach from = $locality key = k item = val}
                                    <option value="{$val->locality_id}" {if in_array($val->locality_id , $locIdArr)} selected="" {/if}>
                                        {$val->label}
                                    </option>
                                {/foreach} 
                            {/if}
                        </select>
                    </td>
                    <td width="30%" align="right" >
                        <select multiple="" name="project[]" id="project">
                            <option>---Select Project---</option>
                            {if $project != ''}
                                {foreach from = $project key = k item = val}
                                    <option value="{$val->id}" {if in_array($val->id , $projectIdArr)} selected="" {/if}>{$val->label}</option>
                                {/foreach} 
                            {/if}
                        </select>
                    </td>
                    <td width="30%" align="right" >
                        <select multiple="" name="agent[]" id="agent">
                            <option>---Select Agent---</option>
                            {if $seller_company != ''}
                                {foreach from = $seller_company key = k item = val}
                                    <option value="{$val->agent_id}" {if in_array($val->agent_id , $agentIdArr)} selected="" {/if}>{$val->agent_name}</option>
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
                  
                  <input type="hidden" name="locjIdArr" id="locjIdArr" value="{$locjIdArr}" />
                  <input type="hidden" name="projectjIdArr" id="projectjIdArr" value="{$projectjIdArr}" />
                  <input type="hidden" name="agentjIdArr" id="agentjIdArr" value="{$agentjIdArr}" />
                  
                  <input type="hidden" name="dlocjIdArr" id="dlocjIdArr" value="" />
                  <input type="hidden" name="dprojectjIdArr" id="dprojectjIdArr" value="" />
                  <input type="hidden" name="dagentjIdArr" id="dagentjIdArr" value="" />
                  
                  <input type="hidden" name="sort" id="sort" value="{$sort}" />
                  <input type="hidden" name="page" id="page" value="{$page}" />
				  </td>
				</tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr class = "headingrowcolor" height="25">
                    <TD class=whiteTxt width=5% align="center">S NO</TD>
                    <TD class=whiteTxt width=15% align="left">Rule Name</TD>
                    <TD class=whiteTxt width=25% align="left">Locality(s)</TD>
                    <TD class=whiteTxt width=25% align="left">Project(s)</TD>
                    <TD class=whiteTxt width=25% align="left">Agent(s)</TD>
                    <TD class=whiteTxt width=25% align="left">Date</TD>
                    <TD class=whiteTxt width=25% align="left">Action</TD>
                </tr>
                {$count = 0}
                {foreach from = $ruleDataArr key = k item = value}
                      {$count = $count+1}
                      {if $count%2 == 0}
                              {$color = "bgcolor = '#FCFCFC'"} 
                      {else}
                              {$color = "bgcolor = '#F7F7F7'"}
                      {/if}	
                <TR {$color}>
                  
	             
                <TD align=center class=td-border>{$count}</TD>
                
                <TD align=left class=td-border>{if strlen($value['rule_name']) > 30} {$value['rule_name']|substr:0:30|cat:"..."} {else} {$value['rule_name']} {/if}  </TD>
                {$rcount = 0}
                {section name=waistsizes start=0 loop=$value['count'] step=1}
                    {if $smarty.section.waistsizes.index != 0}
                        {$rcount = $rcount+1}
                          {if $rcount%2 == 0}
                                  {$rcolor = "bgcolor = '#FCFCFC'"} 
                          {else}
                                  {$rcolor = "bgcolor = '#F7F7F7'"}
                          {/if}	
                        <TR {$rcolor}>
                        <TD align=center class=td-border></TD>
                        <TD align=left class=td-border></TD>
                    {/if}
                    
                    <TD align=center class=td-border>{$value['locality'][$smarty.section.waistsizes.index]}</TD>
                    <TD align=left class=td-border>{$value['project'][$smarty.section.waistsizes.index]}</TD>
                    <TD align=center class=td-border>{$value['agent'][$smarty.section.waistsizes.index]}</TD>
                    
                    {if $smarty.section.waistsizes.index == 0}
                        <TD align=left class=td-border>{$value['created_at']}</TD>
                        <TD align=left class="td-border">
        	                 <a href="ruleadd.php?ruleId={$value['id']}&mode=edit&page={$page}&sort={$sort}" title="{$value['rule_name']}">EDIT </a>
                          </TD>
                    {else}
                        <TD align=left class=td-border>&nbsp;</TD>
                        <TD align=left class="td-border">&nbsp;</TD>
                    {/if}
                    
                    </tr>
                {/section}
                </TR>
                {/foreach}
                {if $NumRows<=0}
                    <TR><TD colspan="9" class="td-border" align="left">Sorry, no records found.</TD></TR>
                {/if}
			      </div>
			    </form>
			    </TABLE>
{if $NumRows>1}
                  <table width="93%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="77%" height="25" align="center">
				            {$Pagginnation}
                              
                            </td>
                            <td align="right">&nbsp;</td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                  {/if}
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
           window.location.href = 'ruleadd.php'; 
        });
        
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
            
            return true;
                
        }); 
        
        jQuery('#broker_cmpny').change(function(){
            var valuesloc = jQuery(this).val();

            if(valuesloc != '' && valuesloc != undefined && typeof valuesloc != undefined)
            {
                var dataString = 'broker='+valuesloc;
                jQuery.ajax({
                    
                    type    : 'POST',
                    url     : 'fetchAgents.php',
                    data    : dataString,
                    success : function(data){
                        //alert(data);
//                        return;
                        if(data == '')
                        {
                            jQuery('#agent').html('');
                            return false;
                        }
                        jQuery('#agent').html('');
                        
                       // if(jQuery('#agentjIdArr').val()!= '' && jQuery('#agentjIdArr').val() != null)
//                        {
//                            jQuery('#dagentjIdArr').val(jQuery('#agentjIdArr').val());
//                            jQuery('#agentjIdArr').val('');    
//                        }
                        
                        var json = JSON.parse(data);
                        var appendData  = '<option value = ""> --- Select Agents --- </option>';
                        for(var key in json)
                        {
                            appendData += '<option value="' + key + '">' + json[key] + '</option>';
                        }
                        
//                        alert(appendData);
                        jQuery('#agent').append(appendData);
                    },
                    error   : function(){
                        alert("Something went wrong");
                        return false;
                    }
                });
            }
            else
            {
                jQuery('#agent').html('<option value = ""> --- Select Agents --- </option>');
            }
        });
        
        jQuery('#city_id').change(function(){
            var valuesloc = jQuery(this).val();

            if(valuesloc != '' && valuesloc != undefined && typeof valuesloc != undefined)
            {
                var dataString = 'city='+valuesloc;
                jQuery.ajax({
                    
                    type    : 'POST',
                    url     : 'fetchLocalityCity.php',
                    data    : dataString,
                    success : function(data){
                        //alert(data);
//                        return;
                        if(data == '')
                        {
                            jQuery('#locality').html('<option value = ""> --- Select Locality --- </option>');
                            return false;
                        }
                        jQuery('#locality').html('');
                       // if(jQuery('#locjIdArr').val() != '' && jQuery('#locjIdArr').val() != null)
//                        {
//                            jQuery('#dlocjIdArr').val(jQuery('#locjIdArr').val());
//                            jQuery('#locjIdArr').val('');
//                        }
//                        
//                        
//                        if(jQuery('#projectjIdArr').val() != '' && jQuery('#projectjIdArr').val() != null)
//                        {
//                            jQuery('#dprojectjIdArr').val(jQuery('#projectjIdArr').val());
//                            jQuery('#projectjIdArr').val('');
//                        }
                        
                        
                        jQuery('#project').html('<option value = ""> --- Select Project --- </option>');
                        var json = JSON.parse(data);
                        var appendData  = '<option value = ""> --- Select Locality --- </option>';
                        for(var key in json)
                        {
                            appendData += '<option value="' + key + '">' + json[key] + '</option>';
                        }
                        
//                        alert(appendData);
                        jQuery('#locality').append(appendData);
                    },
                    error   : function(){
                        alert("Something went wrong");
                        return false;
                    }
                });
            }
            else
            {
                jQuery('#locality').html('<option value = ""> --- Select Locality --- </option>');
                jQuery('#project').html('<option value = ""> --- Select Project --- </option>');
            }
        });
        
        jQuery('#locality').change(function(){
             var valuesloc = jQuery("#locality option:selected").map(function(){
                                return this.value;
                            }).get();

            if(valuesloc != '' && valuesloc != undefined && typeof valuesloc != undefined)
            {
                var dataString = 'locality='+valuesloc;
                jQuery.ajax({
                    
                    type    : 'POST',
                    url     : 'fetchProjectLocality.php',
                    data    : dataString,
                    success : function(data){
                        //alert(data);
//                        return;
                        if(data == '')
                        {
                            jQuery('#project').html('');
                            return false;
                        }
                        jQuery('#project').html('');
                        //if(jQuery('#locjIdArr').val() != '' && jQuery('#locjIdArr').val() != null)
//                        {
//                            jQuery('#dlocjIdArr').val(jQuery('#locjIdArr').val());
//                            jQuery('#locjIdArr').val('');
//                        }
//                        if(jQuery('#projectjIdArr').val() != '' && jQuery('#projectjIdArr').val() != null)
//                        {
//                            jQuery('#dprojectjIdArr').val(jQuery('#projectjIdArr').val());
//                            jQuery('#projectjIdArr').val('');    
//                        }
                        
                        var json = JSON.parse(data);
                        var appendData  = '<option value = ""> --- Select Project --- </option>';
                        for(var key in json)
                        {
                            appendData += '<option value="' + key + '">' + json[key] + '</option>';
                        }
                        
//                        alert(appendData);
                        jQuery('#project').append(appendData);
                    },
                    error   : function(){
                        alert("Something went wrong");
                        return false;
                    }
                });
            }
            else
            {
                jQuery('#project').html('<option value = ""> --- Select Project --- </option>');
            }
        });
        
        //jQuery('#project').change(function(){
//            
//            if(jQuery('#projectjIdArr').val() != '' && jQuery('#projectjIdArr').val() != null)
//            {
//                jQuery('#dprojectjIdArr').val(jQuery('#projectjIdArr').val());
//                jQuery('#projectjIdArr').val('');    
//            }
//            
//            
//        });
//        
//        jQuery('#agent').change(function(){
//            
//            if(jQuery('#agentjIdArr').val() != '' && jQuery('#agentjIdArr').val() != null)
//            {
//                jQuery('#dagentjIdArr').val(jQuery('#agentjIdArr').val());
//                jQuery('#agentjIdArr').val('');    
//            }
//            
//            
//        });
               
    });
    
    
 </script>
