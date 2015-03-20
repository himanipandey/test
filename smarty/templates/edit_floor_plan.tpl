<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.js"></script>
<link href="fancybox/fancybox/jquery.fancybox-1.3.4.css" rel="stylesheet" type="text/css">
<script type="text/javascript">

function checkednewAll() 
{
	var rowCount = document.getElementById("rowcount").innerHTML;
	//alert(rowCount+" HHHH");
	var j=0;
	for(j=0;j<rowCount;j++)
	{
		var chkId = "chk_"+j;			
		if(document.getElementById("hdnCheckUncheck").checked == true)
		{
			document.getElementById(chkId).checked = true;
		}
		else
		{
			document.getElementById(chkId).checked = false;
		}
	}        
 }


function checkedAll()
{
	var rowPropCount = document.getElementById("rowPropCount").innerHTML;
	var k=0;
	for(k=0;k<rowPropCount;k++)
	{
		var chkId2 = "chkk_"+k;		
		if(document.getElementById("hdnCheckUncheck2").checked == true)
		{
			document.getElementById(chkId2).checked = true;
		}
		else
		{
			document.getElementById(chkId2).checked = false;
		}
	}
}

function check()
{

	var rowCount = document.getElementById("rowcount").innerHTML;
	var rowPropCount = document.getElementById("rowPropCount").innerHTML;
	var onechk_flg = 0;
	var i = 0;
	for(i=0;i<rowCount;i++)
	{
		var chkId = "chk_"+i;		
		if(document.getElementById(chkId).checked == true)
		{	
			onechk_flg = 1;
		}	
	}

	if (onechk_flg == 0)
	{
	   alert ("Please check at least one checkbox from list data.");
	   return false;	  
	}
	else
	{
		var i = 0;
		for(i=0;i<rowCount;i++)
		{
			var chkId = "chk_"+i;
			var className	= "tdcls_"+i;
			if(document.getElementById(chkId).checked == false)
			{
				
				$("."+className).remove();
			}				
		}

		for(l=0;l<rowPropCount;l++)
		{
			var chkId2 = "chkk_"+l;
			var className2	= "tdprocls_"+l;
			if(document.getElementById(chkId2).checked == false)
			{			
				$("."+className2).remove();
			}					
		}
		return true;
	}	
}

function onSelectOption(c){

      	$("#floor_name_"+c+" option").each(function() {
		    $(this).remove();
		});
      	value = $("#options_"+c+" option:selected").text().trim();
      	//alert(value);
      	//$("#floor_name_"+c).text(value);
      	//imgName1 = value+" 1";
      	//imgName2 = value+" 2";
      	//alert(value);
      	if(value == "Floor Plan" || value == "Simplex"){
      		$('<option>').val(value).text(value).appendTo('#floor_name_'+c);
      	}
      	else if(value == "Basement Floor" || value == "Stilt Floor" || value == "First Floor" || value == "Second Floor"|| value == "Third Floor" || value == "Terrace Floor" )
      		$('<option>').val(value+" Plan").text(value+" Plan").appendTo('#floor_name_'+c);
      	else if(value== "Duplex"){
      		{foreach from=$duplex item=data}
				$('<option>').val("{$data}").text("{$data}").appendTo('#floor_name_'+c);
			{/foreach}
      	}
      	else if(value== "Triplex"){
      		{foreach from=$triplex item=data}
				$('<option>').val("{$data}").text("{$data}").appendTo('#floor_name_'+c);
			{/foreach}
      	}
      	else if(value== "Penthouse"){
      		{foreach from=$penthouse item=data}
				$('<option>').val("{$data}").text("{$data}").appendTo('#floor_name_'+c);
			{/foreach}
      	}
      	else if(value== "Ground Floor"){
      		{foreach from=$ground_floor item=data}
				$('<option>').val("{$data}").text("{$data}").appendTo('#floor_name_'+c);
			{/foreach}
      	}
      	
      	//$('<option>').val(imgName1).text(imgName1).appendTo('#floor_name_'+c);
      	//$('<option>').val(imgName2).text(imgName2).appendTo('#floor_name_'+c);
    }


$(".pt_reqflrplan").fancybox();

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
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor="#b1b1b1" border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18> Edit/Delete Floor Plan Images({trim($ProjectDetail[0]['BUILDER_NAME'])} {$ProjectDetail[0]['PROJECT_NAME']}) <span id="loader" style="float:right; padding-right:100px; display:none;"><img src="../../images/ajax-loader1.gif" /></span></TD>
                      <TD align=right ></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
				  <TABLE cellSpacing=2 cellPadding=2 width="100%" align=center border=1 style = "border:1px solid;">
					<form method="post" enctype="multipart/form-data">
					<tr>
					<td  align = "center" colspan = "2">
						{if count($ErrorMsg)>0}
					   {foreach from=$ErrorMsg item=data}
					   <font color = "red">{$data}</font><br>
					   {/foreach}
					{/if}
					</td>
				</tr>
					<tr>
						<td width="100%" align="left" >
				  
						<div id="imagesDiv">
						Delete all: <input type='checkbox'  id="hdnCheckUncheck" value='0' name='checkall' onclick='checkednewAll();'>
							<br>
						<form name="f1" id="f1" method="post" action ="" enctype = "multipart/form-data">
							<table cellpadding=0 cellspacing=1>
								<tr>
									<td>
										<table  cellpadding=0 cellspacing=1 bgcolor='#c2c2c2'>
											<tr bgcolor='#ffffff'>
									
												{$cnt = 0}
												{section name=data loop=$ImageDataListingArr}
						
												<td class = "tdcls_{$cnt}" >
													
													<div  style="border:1px solid #c2c2c2;padding:4px;margin:4px;">

															<b>{$ImageDataListingArr[data].UNIT_NAME}( {$ImageDataListingArr[data].SIZE} {if $ImageDataListingArr[data].CARPET_AREA != '' && $ImageDataListingArr[data].SIZE != ''} , {$ImageDataListingArr[data].CARPET_AREA}(Carpet Area){/if} {if $ImageDataListingArr[data].CARPET_AREA != '' && $ImageDataListingArr[data].SIZE == ''} {$ImageDataListingArr[data].CARPET_AREA}(Carpet Area){/if} /{$ImageDataListingArr[data].MEASURE} )</b>
															<br><br>
															{if $ImageDataListingArr[data].DOCUMENT_TYPE == 'yes'}
																<a class="pt_reqflrplan" href="{$ImageDataListingArr[data].IMAGE_URL}														
															
															" target="_blank"> Download document
																
															</a>
															{else}
																<a class="pt_reqflrplan" href="{$ImageDataListingArr[data].IMAGE_URL}														
																
																" target="_blank">
																	<img src="{$ImageDataListingArr[data].IMAGE_URL}?width=130&height=100" height="70px" width="70px" title = "{$ImageDataListingArr[data].IMAGE_URL}" alt ="{$ImageDataListingArr[data].alt_text}" />
																</a>
															{/if}
															
															
															<br>
														<input type = "hidden" readonly value = "{$ImageDataListingArr[data].FLOOR_PLAN_ID}"
														name = "plan_id[{$cnt}]">

														<input type = "hidden" readonly value = "{$ImageDataListingArr[data].OPTION_ID}"
														name = "option_id[{$cnt}]">

                                                        <input type="hidden" value="{$ImageDataListingArr[data].SERVICE_IMAGE_ID}" name="service_image_id[{$cnt}]" />

                                                        <input type="hidden" value="{$ImageDataListingArr[data].PLAN_TYPE}" name="plan_type[{$cnt}]" />

														<input type="hidden" value="{$imgDisplayPath}{$ImageDataListingArr[data].IMAGE_URL}" name="property_image_path[{$cnt}]" /><br><br>
														
														<b>Image Title:<font color = "red">*</font></b><input type="text" name="title[{$cnt}]" value = "{$ImageDataListingArr[data].NAME}" readonly="readonly" STYLE="width: 165px;border:1px solid #c3c3c3;"/><br><br>
														
														<b>Delete:</b><input type="checkbox" name="chk_name[{$cnt}]" id = "chk_{$cnt}" ><br><br>
														


													</div>
												</td>
												{if $cnt%4 == 0 AND $cnt != 0}
													</tr><tr bgcolor='#ffffff'>
												{/if}
												{$cnt = $cnt+1} 		
												{/section}
											</tr>
										</table>
									</td>
								</tr>
					
								<tr>
								 
								  <td colspan = "2" align="right" style="padding-left:152px;" >
								  <span id = 'rowcount' style = 'display:none;'>{$count}</span>
								   <span id = 'rowPropCount' style = 'display:none;'>{$countPropImages}</span>
								  
								  <input type="hidden" name="projectId" value="{$projectId}" />
								   <input type="hidden" name="preview" value="{$preview}" />
								  <input type="submit" name="btnSave" id="btnSave" value="Submit" style = "font-size:16px;"  onclick="return check();">
								  &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit" style = "font-size:16px;">
								  </td>
								</tr>
								</form>
						</table>
			

				</div>
				</td>
				</tr>
<!--			</fieldset>-->
	   
          </td>
		  </tr>
		</TABLE>
	      </TD>
            </TR>
          </TBODY></TABLE>
        </TD>
      </TR>
