 <SCRIPT language=Javascript>
     
      function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 46 || charCode > 57))
            return false;

         return true;
      }
      
      
   /* function showHideDiv(divid,ctrl)
    {
      //alert(divid);
      //alert(ctrl);
        if(ctrl==1)
        {
            document.getElementById(divid).style.display = "";
        }
        else
        {
            document.getElementById(divid).style.display = "none";
        }
    }*/
     

	/*************Create function for project search**********************/
	 function projectcngfun()
	 {
	 		var pid = document.getElementById("projectcngf").value;
		//alert(pid);
		window.location = "projecttypeadd.php?projectid_type="+pid;
	 }
   </SCRIPT>



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
	   		{include file="{$OFFLINE_PROJECT_TEMPLATE_PATH}left.tpl"}
	  </TD>
         
          <TD class=border-rt vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>{if projectid_type == ''} Add New {else} Edit {/if} Project Type</TD>
                      <TD align=right ></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
		
		
		     
<!--			<fieldset class="field-border">
			  <legend><b>Message</b></legend>-->
			  <div style="overflow:auto;">
			  <TABLE cellSpacing=2 cellPadding=4 width="93%" align=center  style="border:1px solid #c2c2c2;">
			    <form method="post" enctype="multipart/form-data">
			      <div>
			     <tr>
				 
					<td colspan="15" nowrap="nowrap" width="10%" align="left"><font color = red>*</font>Project Name:
					
				  		  <select name = "projectId" STYLE="width: 350px;border:1px solid #c3c3c3;" id = projectcngf>
							<option value =''>Select Project</option>
							 {section name=data loop=$Project}
							 
								<option {if $projectId ==  $Project[data].PROPTIGER_PROJECT_ID} value = "{$projectId}" selected = 'selected' {else} value = "{$Project[data].PROPTIGER_PROJECT_ID}" {/if}>{$Project[data].BUILDER_NAME}-{$Project[data].PROPERTY_NAME}</option>



							 {/section}	
						</select>	
						<!--<input type = button name = "projectcng" value = "Search" onclick = "projectcngfun();"> -->
					</td>
				
					
				 
				 </tr>
			    <!--  {foreach from = $errormsg  key=k item = datafirst}					
					<tr onmouseover="showHideDiv('row_{$k}',1);" onmouseout="showHideDiv('row_{$k}',2);">
							<th colspan="15" align = left><font color="red">{if  $k == 0} First row errors {else if $k == 1} Second row errors {else if $k == 2} Third row errors
							{else if $k == 3} Fourth row errors {else if $k == 4} Fifth row errors {else if $k == 5} Sixth row errors {else if $k == 6} Seventh row errors
							{else if $k == 7} Eighth row errors {else if $k == 8} Ninth row errors {else if $k == 9} Tenth row errors {/if}</font></th>			      
			      
					</tr>
		 			
				<tr id="row_{$k}" style="display:none;"><td colspan="15"><font color="red">{$datafirst}</font></td></tr>
				
				
			      {/foreach} -->

			    <tr><td colspan="15"><font color="red">{$projecteror} {if $projectId != ''}{$ErrorMsg1}{/if}</font></td></tr>
				<tr class = "headingrowcolor" >
				  <td  nowrap="nowrap" width="1%" align="center" class=whiteTxt >SNo.</td>
				  
				  <td nowrap="nowrap" width="7%" align="left" class=whiteTxt><font color = red>*</font>Unit Name</td>
				  <td nowrap="nowrap" width="3%" align="left" class=whiteTxt><font color = red>*</font>Size</td>
				  <td nowrap="nowrap" width="6%" align="left" class=whiteTxt><font color = red>*</font>Price Per Unit Area</td>
				  <td nowrap="nowrap" width="6%" align="left" class=whiteTxt><font color = red>*</font>Price Per Unit Area DP</td>
				  <td nowrap="nowrap" width="6%" align="left" class=whiteTxt ><font color = red>*</font>Price Per Unit Area FP</td>
				  <td nowrap="nowrap" width="3%" align="left" class=whiteTxt><font color = red>*</font>Bedrooms</td>
				  <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>CLP Visible</td>
				  <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>DP Visible</td>
				  <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>FPV Visible</td>
				  <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>CLP Disclaimer</td>
				  <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>DP Disclaimer</td>
				  <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>FP Disclaimer</td>
				  <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>Bathrooms</td>	
				  <td nowrap="nowrap" width="3%" align="center" class=whiteTxt>Status</td>	
				</tr> 
				{$var = 0}
				
					{$looprange	=	30}
				
				{section name=foo start= 0 loop={$looprange} step=1}

				{$var	=$var+1}	

				{if $var%2 == 0}
                       			{$color = "bgcolor = '#F7F7F7'"}
                       		{else}
                       			{$color = "bgcolor = '#FCFCFC'"}	
                       		{/if}
				
					
						
				<tr {$color}>
				 <td align="center">
				  		 {($smarty.section.foo.index+1)}
				  </td>
				  {if $txtUnitNameval[{$smarty.section.foo.index}]=='2BHK'}
				 {$txtUnitNameval[{$smarty.section.foo.index}]='2BHK+2T'}	
				  {/if}

				  {if $txtUnitNameval[{$smarty.section.foo.index}]=='3BHK'}
				 {$txtUnitNameval[{$smarty.section.foo.index}]='3BHK+3T'}	
				  {/if}

				   {if $txtUnitNameval[{$smarty.section.foo.index}]=='4BHK'}
				 {$txtUnitNameval[{$smarty.section.foo.index}]='4BHK+4T'}	
				  {/if}
				  
				  <td  >
						
						<input type=text name=txtUnitName[] id=txtUnitName value="{$txtUnitNameval[{$smarty.section.foo.index}]}" style="width:100px;border:1px solid {if ({count($pid)} != 0)}{if ({count($pid)} >= {$var}) && ({$txtUnitNameval[{$smarty.section.foo.index}]} == '')}#FF0000  {else}#c3c3c3 {/if} {else}#c3c3c3 {/if};"  maxlength = "40">		  
				  
				  </td>
				  <td align="left" >
				  		<input onkeypress="return isNumberKey(event)" type=text name=txtSize[] id=txtSize value="{$txtSizeval[{$smarty.section.foo.index}]}" style="width:100px;border:1px solid {if ({count($pid)} != 0)}{if ({count($pid)} >= {$var}) && (({$txtSizeval[{$smarty.section.foo.index}]} == '') OR !is_numeric({$txtSizeval[{$smarty.section.foo.index}]}))}#FF0000  {else}#c3c3c3 {/if} {else}#c3c3c3 {/if};"  maxlength = "10">
				  </td>
				  <td  >
				  		<input onkeypress="return isNumberKey(event)" type=text name=txtPricePerUnitArea[] id=txtPricePerUnitArea value="{$txtPricePerUnitAreaval[{$smarty.section.foo.index}]}" style="width:100px;border:1px solid {if ({count($pid)} != 0)}{if ({count($pid)} >= {$var}) && (({$txtPricePerUnitAreaval[{$smarty.section.foo.index}]} == '') OR !is_numeric({$txtPricePerUnitAreaval[{$smarty.section.foo.index}]}))}#FF0000  {else}#c3c3c3 {/if} {else}#c3c3c3 {/if};"  maxlength = "10">
				  </td>
				  <td  >
				  		<input onkeypress="return isNumberKey(event)" type=text name=txtPricePerUnitAreaDp[] id=txtPricePerUnitAreaDp value="{$txtPricePerUnitAreaDpval[{$smarty.section.foo.index}]}" style="width:100px;border:1px solid {if ({count($pid)} != 0)}{if ({count($pid)} >= {$var}) && (({$txtPricePerUnitAreaDpval[{$smarty.section.foo.index}]} == '') OR !is_numeric({$txtPricePerUnitAreaDpval[{$smarty.section.foo.index}]}))}#FF0000  {else}#c3c3c3 {/if} {else}#c3c3c3 {/if};"  maxlength = "10">
				  </td>
				  <td   >
				  		<input onkeypress="return isNumberKey(event)" type=text name=txtPricePerUnitAreaFp[] id=txtPricePerUnitAreaFp value="{$txtPricePerUnitAreaFpval[{$smarty.section.foo.index}]}" style="width:100px;border:1px solid {if ({count($pid)} != 0)}{if ({count($pid)} >= {$var}) && (({$txtPricePerUnitAreaFpval[{$smarty.section.foo.index}]} == '') OR !is_numeric({$txtPricePerUnitAreaFpval[{$smarty.section.foo.index}]}))}#FF0000  {else}#c3c3c3 {/if} {else}#c3c3c3 {/if};"  maxlength = "10">
				  
				  </td>
				  <td align="center">
				  
				  		<select name = 'bed[]' style="width:100px;border:1px solid {if ({count($pid)} != 0)}{if ({count($pid)} >= {$var}) && (({$bedval[{$smarty.section.foo.index}]} == '') OR !is_numeric({$bedval[{$smarty.section.foo.index}]}))}#FF0000  {else}#c3c3c3 {/if} {else}#c3c3c3 {/if};">
							<option value = "">Select</option>
							<option {if $bedval[{$smarty.section.foo.index}] == '1'} value = "1" selected = 'selected' {else} value = "1" {/if}>1</option>
							<option {if $bedval[{$smarty.section.foo.index}] == '2'} value = "2" selected = 'selected' {else} value = "2" {/if}>2</option>
							<option {if $bedval[{$smarty.section.foo.index}] == '3'} value = "3" selected = 'selected' {else} value = "3" {/if}>3</option>
							<option {if $bedval[{$smarty.section.foo.index}] == '4'} value = "4" selected = 'selected' {else} value = "4" {/if}>4</option>
							<option {if $bedval[{$smarty.section.foo.index}] == '5'} value = "5" selected = 'selected' {else} value = "5" {/if}>5</option>
							<option {if $bedval[{$smarty.section.foo.index}] == '6'} value = "6" selected = 'selected' {else} value = "6" {/if}>6</option>
							<option {if $bedval[{$smarty.section.foo.index}] == '7'} value = "7" selected = 'selected' {else} value = "7" {/if}>7</option>
							<option {if $bedval[{$smarty.section.foo.index}] == '8'} value = "8" selected = 'selected' {else} value = "8" {/if}>8</option>
							<option {if $bedval[{$smarty.section.foo.index}] == '9'} value = "9" selected = 'selected' {else} value = "9" {/if}>9</option>
							<option {if $bedval[{$smarty.section.foo.index}] == '10'} value = "10" selected = 'selected' {else} value = "10" {/if}>10</option>

						</select>
				  
				  </td>
				  <td  align="center" >
				  		<input type = checkbox name = 'CLPV_{$smarty.section.foo.index}' checked value = 1 checked  >
				  </td>
				  <td align="center" >
				  		<input type = checkbox name = 'DPV_{$smarty.section.foo.index}'   value = 1 {if $DPVval[{$smarty.section.foo.index}] != '' AND $DPVval[{$smarty.section.foo.index}] != 0} checked {/if}  >
				  </td>
				  <td align="center" >
				  		<input type = checkbox name = 'FPV_{$smarty.section.foo.index}' value = 1 {if $FPVval[{$smarty.section.foo.index}] != '' AND $FPVval[{$smarty.section.foo.index}] != 0} checked {/if} >
				  </td>
				  <td  align="center" >
				  		<input type = checkbox name = 'CLPD_{$smarty.section.foo.index}' value = 1 {if $CLPDval[{$smarty.section.foo.index}] != '' AND $CLPDval[{$smarty.section.foo.index}] != 0} checked {/if} >
				  </td>
				  <td  align="center" >
				  		<input type = checkbox name = 'DPD_{$smarty.section.foo.index}'  value = 1 {if $DPDval[{$smarty.section.foo.index}] != '' AND $DPDval[{$smarty.section.foo.index}] != 0} checked {/if} >
				  </td>
				   <td  align="center" >
				  		<input type = checkbox name = 'FPD_{$smarty.section.foo.index}' value = 1 {if $FPDval[{$smarty.section.foo.index}] != '' AND $FPDval[{$smarty.section.foo.index}] != 0} checked {/if}>
				  </td>
				  <td>
						 <select name = bathrooms[] style="border:1px solid #c3c3c3;">
							<option value = "">Select</option>
							<option {if $bathroomsval[{$smarty.section.foo.index}] == '1'} value = "1" selected = 'selected' {else} value = "1" {/if}>1</option>
							<option {if $bathroomsval[{$smarty.section.foo.index}] == '2'} value = "2" selected = 'selected' {else} value = "2" {/if}>2</option>
							<option {if $bathroomsval[{$smarty.section.foo.index}] == '3'} value = "3" selected = 'selected' {else} value = "3" {/if}>3</option>
							<option {if $bathroomsval[{$smarty.section.foo.index}] == '4'} value = "4" selected = 'selected' {else} value = "4" {/if}>4</option>
							<option {if $bathroomsval[{$smarty.section.foo.index}] == '5'} value = "5" selected = 'selected' {else} value = "5" {/if}>5</option>
							<option {if $bathroomsval[{$smarty.section.foo.index}] == '6'} value = "6" selected = 'selected' {else} value = "6" {/if}>6</option>
							<option {if $bathroomsval[{$smarty.section.foo.index}] == '7'} value = "7" selected = 'selected' {else} value = "7" {/if}>7</option>
							<option {if $bathroomsval[{$smarty.section.foo.index}] == '8'} value = "8" selected = 'selected' {else} value = "8" {/if}>8</option>
							<option {if $bathroomsval[{$smarty.section.foo.index}] == '9'} value = "9" selected = 'selected' {else} value = "9" {/if}>9</option>
							<option {if $bathroomsval[{$smarty.section.foo.index}] == '10'} value = "10" selected = 'selected' {else} value = "10" {/if}>10</option>
						</select>	  
					  
				  </td>

				    <td>
						 <select name = status[] style="border:1px solid #c3c3c3;">
							<option value = "">Select</option>
							<option {if $statusval[{$smarty.section.foo.index}] == 'Available'} value = "Available" selected = 'selected' {else} value = "Available" {/if}>Available</option>
							<option {if $statusval[{$smarty.section.foo.index}] == 'Sold Out'} value = "Sold Out" selected = 'selected' {else} value = "Sold Out" {/if}>Sold Out</option>



						</select>		  
					  
				  </td>
				 
				</tr>   			  	         
				{/section}
				
			
				
				
				
				<tr class = "headingrowcolor">
				 
				  <td align="left"  colspan="6" >
				  
				  <input type="hidden" name="catid" value="<?php echo $catid ?>" />
				  <input type="submit" name="btnSave" id="btnSave" value="Save">
				  &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit">
				  </td>
				   <td align="right" colspan="9" >
				  
				  <input type="hidden" name="apartmentType" value="{$TYPE}" />
				   <input type="hidden" name="measure" value="sq ft" />

				 <input type="hidden" name="catid" value="<?php echo $catid ?>" />

				  <input type="submit" name="btnSave" id="btnSave" value="Save">
				  &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit">
				  </td>
				</tr>
			      </div>
			    </form>
			    </TABLE>
				</div>
<!--			</fieldset>-->
	   
       </TD>
            </TR>
          </TBODY></TABLE>
        </td></tr>
    </TBODY></TABLE>