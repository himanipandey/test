<script type="text/javascript" src="javascript/jquery.js"></script>
<script type="text/javascript" src="javascript/apartmentConfiguration.js"></script>
<script type="text/javascript" src="../../scripts/fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="../../scripts/fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<script type="text/javascript" src="jscal/calendar.js"></script>
<script type="text/javascript" src="jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscal/calendar-setup.js"></script>
 
 <SCRIPT language=Javascript>
     
      function isNumberKey(evt)
	  {
		 var charCode = (evt.which) ? evt.which : event.keyCode;
		 if (charCode > 31 && (charCode < 46 || charCode > 57) || (charCode == 13))
		 {
			return false;
		}

		 return true;
	  }
      
     /*************Create function for project search**********************/
	 function projectcngfun()
	 {
	 		var pid = document.getElementById("projectcngf").value;
			window.location = "projecttypeadd.php?projectid_type="+pid;
	 }

	 function show_add(id)
	 {
		var id = "add_"+(id+1);
		document.getElementById(id).style.display = '';
	 }

	 function value_read_only(val)
	 {
		if(val == 'same_val')
		{
			var fst_val       = $("#same_diff_0").val();
			var fst_val_fp	  = $("#same_diff_fp_0").val();
			var fst_val_dp    = $("#same_diff_dp_0").val();
			$(".same_diff").attr("readonly","readonly");
			$(".same_diff1").attr("readonly","readonly");
			$(".same_diff2").attr("readonly","readonly");
		}
		else
		{
			var fst_val       = $("#same_diff_0").val();
			var fst_val_fp	  = $("#same_diff_fp_0").val();
			var fst_val_dp    = $("#same_diff_dp_0").val();
			$(".same_diff").attr("readonly","");
			$(".same_diff1").attr("readonly","");
			$(".same_diff2").attr("readonly","");
		}
	 }

	 function valueOnkeyUp(val)
	 {
		var selectedId = $("#same_diff").val();
		if(selectedId == 'same_val')
		{
			if(val == 'same_diff')
			{
				var fst_val       = $("#same_diff_0").val();
				$(".same_diff").val(fst_val);
			}
			else if(val == 'same_diff1')
			{
				var fst_val       = $("#same_diff_dp_0").val();
				$(".same_diff1").val(fst_val);
			}
			else if(val == 'same_diff2')
			{
				var fst_val       = $("#same_diff_fp_0").val();
				$(".same_diff2").val(fst_val);
			}
		}
	 }

	 function validation(cnt)
	 {

	 	/********code for price validation*************/
	 	for(var i=0;i<cnt;i++)
	 	{
	       var pricePerUnitArea     = "same_diff_"+i;
	       var pricePerUnitAreaVal  =  $("#"+pricePerUnitArea).val();

	       var pricePerUnitAreaDp   = "same_diff_dp_"+i;
	       var pricePerUnitAreaDpVal =  $("#"+pricePerUnitAreaDp).val();

	       var pricePerUnitAreaHigh = "same_diff_fp_"+i;
	       var pricePerUnitAreaHighVal =  $("#"+pricePerUnitAreaHigh).val();

	       if($("#cityId").val() != 18)
	       {
	          if(pricePerUnitAreaVal >20000 || pricePerUnitAreaDpVal >20000 || pricePerUnitAreaHighVal >20000)
	          {
	            alert("Price Per Unit Area/Price Per Unit Area DP/Price Per Unit Area FP can't greater than 20000");
	            $("#"+pricePerUnitArea).focus();
	            return false;
	          }
	       }
	       else
	       {
	          if(pricePerUnitAreaVal >=100000 || pricePerUnitAreaDpVal >=100000 || pricePerUnitAreaHighVal >=100000)
	          {
	            alert("Price to be always less than 100,000");
	            $("#"+pricePerUnitArea).focus();
	            return false;
	          }
	       }
	   }
	 	/********end code for price validation********/
	   if($("#f_date_c_to").val() == '')
	   {
			alert("Please Select Date Effective From!");
			$("#f_date_c_to").focus();
			return false;
	   }
		return true;
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
	   		{include file="{$PROJECT_ADD_TEMPLATE_PATH}left.tpl"}
	  </TD>
         
          <TD class=border-rt vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD nowrap class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>Update Project Configuration({$ProjectDetail[0]['BUILDER_NAME']} {$ProjectDetail[0]['PROJECT_NAME']})</TD>
                      <TD align=right ></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign="top" align="middle" class="backgorund-rt" height="450"><BR>
		
				<div id='roomCategory' style='display:none;' >
					<select name='roomCategory' >
					<option value=''>Select</option>
					{foreach from=$RoomCategoryArr key=k item=v} 
						<option value="{$k}">{$v}</option>
					{/foreach}
					</select>
				</div>

			  <div style="overflow:auto;">
			<form method="post" enctype="multipart/form-data">
			  <table cellSpacing=2 cellPadding=4 width="93%" align="center"  style="border:1px solid #c2c2c2;">
				<tr>
					<td align="left">
						<b>Select an Option : </b>&nbsp;&nbsp;<select name = "same_diff" id = "same_diff" onchange = "value_read_only(this.value);">
							<option value = "diff_val">Diffrent Rate</option>
							<option value = "same_val">Same Rate</option>
						</select>
						&nbsp;&nbsp;
						<b>Price Type : </b>&nbsp;&nbsp;<select name = "price_type" id = "price_type" onchange = "value_read_only(this.value);">
							<option value = "primary" {if $ProjectOptionDetail[0]['PRICE_TYPE'] == 'primary'} selected {/if}>Primary</option>
							<option value = "secondary" {if $ProjectOptionDetail[0]['PRICE_TYPE'] == 'secondary'} selected {/if}>Secondary</option>
						</select>
						&nbsp;&nbsp;
						<b>Select Date Effective From : </b>&nbsp;&nbsp;<input name="eff_date_to" value="{$eff_date_to}" type="text" class="formstyle2" id="f_date_c_to" value="" size="10"  style="width:100px;"/>  <img src="../images/cal_1.jpg" id="f_trigger_c_to" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />
						&nbsp;&nbsp;
						<br><br>
						<b>Last Updated Date : </b>&nbsp;&nbsp;<input name="lastUpdated" value="{$arrAudit['0']['ACTION_DATE']}" type="text" class="formstyle2" readonly="1" size="10"  style="width:150px;"/>
						&nbsp;<b>Effective From : </b>&nbsp;<input name="lastUpdated" value="{$ProjectOptionDetail['0']['CREATED_DATE']}" type="text" class="formstyle2" readonly="1" size="10"  style="width:150px;"/>
	
					</td>
				</tr>
			  </table>
			  
			  <TABLE cellSpacing=2 cellPadding=4 width="93%" align=center  style="border:1px solid #c2c2c2;">
			    
			      <div>
				<tr><td colspan="6"><font color="red">{$projecteror} {if $projectId != ''}{$ErrorMsg1}{/if}</font></td></tr>
				<tr class = "headingrowcolor" >
				  <td  nowrap="nowrap" width="1%" align="center" class="whiteTxt" >SNo.</td>
	
				  <td nowrap="nowrap" width="7%" align="left" class="whiteTxt">Unit Name</td>
				  <td nowrap="nowrap" width="3%" align="left" class="whiteTxt">Size</td>
				  <td nowrap="nowrap" width="6%" align="left" class="whiteTxt">Price Per Unit Area</td>
				  <td nowrap="nowrap" width="6%" align="left" class="whiteTxt">Price Per Unit DP</td>
				  <td nowrap="nowrap" width="6%" align="left" class="whiteTxt">Price Per Unit FP</td>
				 
				  <td nowrap="nowrap" width="6%" align="left" class=whiteTxt >Edit Reson </td>
				  <td width="20%" align="left"  class="whiteTxt"><b>Accurate Flag</b> </td>
				  <td nowrap="nowrap" width="3%" align="left" class="whiteTxt">Price Per Unit High</td>
				  <td nowrap="nowrap" width="3%" align="left" class="whiteTxt" >Price Per Unit Low</td>
				  <td nowrap="nowrap" width="3%" align="left" class="whiteTxt" >Source of Information</td>
					
				</tr> 
				{$var = 0}
				
					{$looprange	=	count($ProjectOptionDetail)}
				
				{section name=foo start= 0 loop={$looprange} step=1}

				{$var	=$var+1}	

				{if $var%2 == 0}
                       			{$color = "bgcolor = '#F7F7F7'"}
                       		{else}
                       			{$color = "bgcolor = '#FCFCFC'"}	
                       		{/if}
				
					
						
				<tr {$color} id="row_{($smarty.section.foo.index+1)}">
				 <td align="center">
				  		 {($smarty.section.foo.index+1)}
				  </td>
				  
				  
				  <td>
						  <input type='hidden' value='{$projectId}' name='projectId' />
						{$ProjectOptionDetail[$smarty.section.foo.index]['UNIT_NAME']}
						<input type="hidden" name = "option_id[]" value = "{$ProjectOptionDetail[$smarty.section.foo.index]['OPTIONS_ID']}">		  
				  
				  </td>
				 
				  <td>{$ProjectOptionDetail[$smarty.section.foo.index]['SIZE']}</td>
				  <td>
					<input type = "text" name = "price_per_unit_area[]" size = "10" value = "{$ProjectOptionDetail[$smarty.section.foo.index]['PRICE_PER_UNIT_AREA']}" {if $smarty.section.foo.index != 0}class ="same_diff" {/if} id = "same_diff_{$smarty.section.foo.index}" onkeyup = "valueOnkeyUp('same_diff');" onkeypress='return isNumberKey(event)'>
				  </td>
				  <td>
					<input type = "text" name = "price_per_unit_area_dp[]" size = "10" value = "{$ProjectOptionDetail[$smarty.section.foo.index]['PRICE_PER_UNIT_AREA_DP']}" {if $smarty.section.foo.index != 0}class ="same_diff1" {/if}  id = "same_diff_dp_{$smarty.section.foo.index}" onkeyup = "valueOnkeyUp('same_diff1');" onkeypress='return isNumberKey(event)'>
				  
				  </td>
				  <td>
					<input type = "text" name = "price_per_unit_area_fp[]" size = "10" value = "{$ProjectOptionDetail[$smarty.section.foo.index]['PRICE_PER_UNIT_AREA_FP']}" {if $smarty.section.foo.index != 0}class ="same_diff2" {/if}  id = "same_diff_fp_{$smarty.section.foo.index}"  onkeyup = "valueOnkeyUp('same_diff2');" onkeypress='return isNumberKey(event)'> 
				  
				  </td>
		
				   <td><input type = "text" name = "edit_reason[]" id = "edit_reason[]"></td>
					
				 <td width="30%" align="left">
								  
					  <select name="flats[]" class="flats">
						  <option value="">Choose atleast One</option>
						  <option value="0">Accurate</option>
						  <option value="1">Guessed</option>
					   </select>
				</td>

				<td nowrap="nowrap" width="3%" align="left" class=whiteTxt><input type = "text" size ="10" name = "price_per_unit_high[]" value = "{$ProjectOptionDetail[$smarty.section.foo.index]['PRICE_PER_UNIT_HIGH']}"></td>
				<td nowrap="nowrap" width="3%" align="left" class=whiteTxt ><input type = "text" size ="10" name = "price_per_unit_low[]"  value = "{$ProjectOptionDetail[$smarty.section.foo.index]['PRICE_PER_UNIT_LOW']}"></td>
				<td nowrap="nowrap" width="3%" align="left" class=whiteTxt >
					<select name="soi[]" class="soi">
						<option value="">Choose Source</option>
						{foreach from=$source_of_information key=k item=v}
						<option value = "{$v['SOURCE_NAME']}">{$v['SOURCE_NAME']}</option>
						{/foreach}
					</select>
				</td>
				</tr>   			  	         
				{/section}
				
				<tr class = "headingrowcolor">
				 
				  <td align="left"  colspan="11">
				  <input type = "hidden" name = "cityId" id = "cityId" value = "{$ProjectDetail[0]['CITY_ID']}">
				  <input type="submit" name="btnSave" id="btnSave" value="Submit" onclick = "return validation({count($source_of_information)});">
	
				  &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit">
				  </td>
				 
				</tr>
			      </div>
			    </form>

			    <div id='roomForm' ></div>
			    </TABLE>
				</div>
<!--			</fieldset>-->
	   
       </TD>
            </TR>
          </TBODY></TABLE>
        </td></tr>
    </TBODY></TABLE>

<script type="text/javascript">
   Calendar.setup({
   
    inputField     :    "f_date_c_to",     // id of the input field
	ifFormat       :    "%Y-%m-%d",      // format of the input field
    button         :    "f_trigger_c_to",  // trigger for the calendar (button ID)
    align          :    "Tl",           // alignment (defaults to "Bl")
    singleClick    :    true,
    showsTime		:	true
  });
</script>