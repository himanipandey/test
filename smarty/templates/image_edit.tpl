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
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18> Edit/Delete Images({trim($ProjectDetail[0]['BUILDER_NAME'])} {$ProjectDetail[0]['PROJECT_NAME']}) <span id="loader" style="float:right; padding-right:100px; display:none;"><img src="../../images/ajax-loader1.gif" /></span></TD>
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
												{$date_cnt = 0}
												{section name=data loop=$ImageDataListingArr}
						
												<td class = "tdcls_{$cnt}" >
													<div  style="border:1px solid #c2c2c2;padding:4px;margin:4px;">
														
															<a class="pt_reqflrplan" href="{$imgDisplayPath}{$ImageDataListingArr[data].PLAN_IMAGE}
															
															" target="_blank">
																<img src="{$imgDisplayPath}{$ImageDataListingArr[data].PLAN_IMAGE}
																
																" height="70px" width="70px" title="{$ImageDataListingArr[data].PLAN_IMAGE}" alt="{$ImageDataListingArr[data].PLAN_IMAGE}" />
															</a>
															<br>
														Image Type:{$ImageDataListingArr[data].PLAN_IMAGE}<input type = "text" readonly name = "PType[{$cnt}]"
														value = "{$ImageDataListingArr[data].PLAN_TYPE}"
														STYLE="width: 165px;border:1px solid #c3c3c3;">

														<input type="hidden" value="{$path}{$ImageDataListingArr[data].PLAN_IMAGE}" name="property_image_path[{$cnt}]" /><br><br>
                                                        <input type="hidden" value="{$ImageDataListingArr[data].SERVICE_IMAGE_ID}" name="service_image_id[{$cnt}]" />
														Image Title:<font color = "red">*</font><input type="text" name="title[{$cnt}]" value = "{$ImageDataListingArr[data].TITLE}"  STYLE="width: 165px;border:1px solid #c3c3c3;"/><br><br>
														{if $ImageDataListingArr[data].PLAN_TYPE == 'Construction Status'}
														<div class="taggedDate">
															Tagged Date:<font color = "red">*</font>&nbsp;&nbsp;
															<select name="tagged_date[{$cnt}]" >
																<option value="0" >--Select Month--</option>
																{foreach from=$dateDiv key=keys item=datas}
																	<option value="{$keys}" {if ($ImageDataListingArr[data].tagged_month|strtotime|date_format:"%Y-%m") == $keys} selected {/if} >{$datas}</option>
																{/foreach}
															</select>
															<br><br>
															Tower:&nbsp;&nbsp;
															<select name= "txtTowerId[{$cnt}]" >
																<option value="0" >--Select Tower--</option>
																{section name=towerdata loop=$towerDetail}
																	<option value="{$towerDetail[towerdata].TOWER_ID}" {if $ImageDataListingArr[data].tower_id == $towerDetail[towerdata].TOWER_ID} selected {/if} >{$towerDetail[towerdata].TOWER_NAME}</option>
																{/section}
																	<option value="-1" {if $ImageDataListingArr[data].tower_id == null} selected {/if}>Other</option>
															</select>
														</div>
														{/if}
														{if $ImageDataListingArr[data].PLAN_TYPE == 'Project Image'}
															Display Order:&nbsp;&nbsp;
															<select name= "txtdisplay_order[{$cnt}]" >
																{foreach from=$display_order_div key=keyss item=datass}
																	<option value="{$keyss}"  {if $ImageDataListingArr[data].display_order == $keyss} selected {/if}>{$datass}</option>
																{/foreach}																	
															</select>
															<br/>
														{/if}
														<br/><br/>
														Delete:<input type="radio" name="chk_name[{$cnt}]" value="delete_img" id="chk_{$cnt}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														Edit:<input type="radio" name="chk_name[{$cnt}]"  value="edit_img"><br><br>
														New Image?:<input type="file" name="img[{$cnt}]"/>
														
														


													</div>
												</td>
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
								  <input type="submit" name="btnSave" id="btnSave" value="Submit" style = "font-size:16px;"  >
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
