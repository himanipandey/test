<script type="text/javascript" src="js/jquery.js"></script>

<script type="text/javascript">

	function refreshother(ct)
	{   	

		$(".hiderow").hide();
		
		for(i=1;i<=ct;i++)
		{
			var id = 'other'+i;
		 document.getElementById(id).style.display='';
		}		
	}
</script>
  <TR>
    <TD class="white-bg paddingright10" vAlign=top align=middle bgColor=#ffffff>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TR>
          <TD width=224 height=25>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=866>&nbsp;</TD>
	</TR>
        <TR>
          <TD class=paddingltrt10 vAlign=top align=middle bgColor=#ffffff>
	   		{include file="{$PROJECT_ADD_TEMPLATE_PATH}left.tpl"}
	  </TD>
          <TD vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</TD>
          <TD vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0>
					<TBODY>
						<TR>
						  <TD nowrap class="h1" width="67%"><img height="18" hspace="5" src="../images/arrow.gif" width="18">{if $edit_project != ''} Edit {else}Add New{/if} Specifications and Amenities({$projectDetail[0]['BUILDER_NAME']} {$projectDetail[0]['PROJECT_NAME']})</TD>
						  <TD width="33%" align ="right"></TD>
					   
						</TR>
					</TBODY>
				  </TABLE>
				</TD>
	      </TR>
		  <tr></tr>
			<TD vAlign="top" align="middle" class="backgorund-rt" height="450"><BR>
			 	
				<table cellSpacing="1" cellPadding="4" width="67%" align="center" border="0">
					{if $ErrMsg != ''}
						<tr>
							<td colspan= "4"><font color = "red">{$ErrMsg}</font></td>
						</tr>
					{/if}
				 <form method="post"  action = ''>	
							   <tr>
								  <td align="left" colspan='4'><b>Aminities :</td>
								</tr>
							
							{foreach from=$AmenitiesArr key=k item=v} 
								{if $k != 99}
								<tr>
									 <td  align="right">{$v}</td>
									
									 {if array_key_exists($k,$arrNotninty)}
                                                                            <td  width="10%"><input type='radio' name="{$v}#{$k}" value='{$k}'   checked='checked'/> Yes </td>
                                                                            <td> <input type='radio' name="{$v}#{$k}" value='0'/> No   </td>
                                                                            <td> <input type= "text" name = "display_name_{$k}[]"  style="width:357px;" maxlength = "100" value = "{if !in_array($arrNotninty[$k],$AmenitiesArr)}{$arrNotninty[$k]}{/if}" ></td>	
									 {else}
                                                                            <td  width="10%"><input type='radio' name="{$v}#{$k}" value='{$k}'/> Yes </td>
                                                                            <td> <input type='radio' name="{$v}#{$k}" value='0'  checked='checked'/> No   </td>
                                                                            <td> <input type= "text" name = "display_name_{$k}[]"  style="width:357px;" maxlength = "100" ></td>	
									 {/if}
										 
								</tr>
								{/if}
							 {/foreach}
							 <tr>
								<td align = "left"  colspan='2'>How many other amenities you want to add?</td>
								<td  align = "left" colspan='2'>
									<select name="other" onchange="refreshother(this.value);">
										{section name=foo start=1 loop=20 step=1}
										  <option value="{$smarty.section.foo.index}" {if count($arrninty) == $smarty.section.foo.index} selected {/if}>{$smarty.section.foo.index}</option>
										{/section}
									</select>
								</td>
							 </tr>
							 {section name=nm start=1 loop=20 step=1}
										
								<tr class = "hiderow" id="other{$smarty.section.nm.index}" {if ($smarty.section.nm.index != 1) && (!array_key_exists($smarty.section.nm.index,$arrninty))} style = "display:none;"{/if}>
									<td >Other Amenitiy:</td>
									 <td colspan='3'><input type="text" name="newAmenity[]"  value="{$arrninty[$smarty.section.nm.index]}" style="width:357px;" /></td>  				  
								</tr>
							{/section}

							   <tr>
									<td width="20%" align="left" colspan='4'><b>Specifications :</b> </td>
							  </tr>
							  <tr>
									<td width="20%" align="left" colspan='4'>Flooring : </td>
							  </tr>
							  <tr>
									<td align="right" valign ="top">Master Bedroom</td><td colspan='3'>
									<textarea style="width:357px;height:50px;" name="master_bedroom_flooring">{$arrSpecification['FLOORING_MASTER_BEDROOM']}</textarea>
							   </tr>
							   <tr>
									<td align="right" valign ="top">Other Bedroom</td><td colspan='3'>
									<textarea style="width:357px;height:50px;" name="other_bedroom_flooring">{$arrSpecification['FLOORING_OTHER_BEDROOM']}</textarea>
									</td>		  
							   </tr>
							    <tr>
									<td align="right" valign ="top">Living/Dining</td><td colspan='3'>

									<textarea style="width:357px;height:50px;" name="living_room_flooring">{$arrSpecification['FLOORING_LIVING_DINING']}</textarea>
									</td>		  
							   </tr>
							   <tr>
									<td align="right" valign ="top">Kitchen</td><td colspan='3'>

									<textarea style="width:357px;height:50px;" name="kitchen_flooring">{$arrSpecification['FLOORING_KITCHEN']}</textarea>
									</td>		   
							   </tr>

							   <tr>
									<td align="right" valign ="top">Toilets</td><td colspan='3'>
									
									<textarea style="width:357px;height:50px;" name="toilets_flooring">{$arrSpecification['FLOORING_TOILETS']}</textarea>
									</td>		  
							   </tr>

							    <tr>
									<td align="right" valign ="top">Balcony</td><td colspan='3'>
										<textarea style="width:357px;height:50px;" name="balcony_flooring">{$arrSpecification['FLOORING_BALCONY']}</textarea>
									</td>		  
							    </tr>

							   <tr>
								  <td width="20%" align="left" colspan='4' valign ="top">Walls : </td>
							   </tr>

							   <tr>
									<td align="right" valign ="top">Interior</td><td colspan='3'>
										<textarea style="width:357px;height:50px;" name="interior_walls">{$arrSpecification['WALLS_INTERIOR']}</textarea>
									</td>						
								</tr>
							   <tr>
									<td align="right" valign ="top">Exterior</td><td colspan='3'>

										<textarea style="width:357px;height:50px;" name="exterior_walls">{$arrSpecification['WALLS_EXTERIOR']}</textarea>
									</td>		  
							   </tr>
							    
							  <tr>
									<td align="right" valign ="top">Kitchen</td><td colspan='3'>
										<textarea style="width:357px;height:50px;" name="kitchen_walls">{$arrSpecification['WALLS_KITCHEN']}</textarea>
									</td>		  
							  </tr>

							   <tr>
									<td align="right" valign ="top">Toilets</td><td colspan='3'>
										<textarea style="width:357px;height:50px;" name="toilets_walls">{$arrSpecification['WALLS_TOILETS']}</textarea>
									</td>		  
							  </tr>

							  <tr>
									<td width="20%" align="left" colspan='4'>Fittings and Fixtures : </td>
							  </tr>

							  <tr>
								<td align="right" valign ="top">Kitchen</td><td colspan='3'>
									<textarea style="width:357px;height:50px;" name="kitchen_fixtures">{$arrSpecification['FITTINGS_AND_FIXTURES_KITCHEN']}</textarea>
								</td>						
								 
							  </tr>
							  <tr>
									<td align="right" valign ="top">Toilets</td><td colspan='3'>
										<textarea style="width:357px;height:50px;" name="toilets_fixtures">{$arrSpecification['FITTINGS_AND_FIXTURES_TOILETS']}</textarea>
									
									</td>		  
							   </tr>
							   <tr>
									 <td width="20%" align="left" colspan='4'>Doors : </td>
							  </tr>
							  <tr>
									<td align="right" valign ="top">Main</td>
									<td colspan='3'>
										<textarea style="width:357px;height:50px;" name="main_doors">{$arrSpecification['DOORS_MAIN']}</textarea>
									</td>						
								 
							   </tr>
							   <tr>
									<td align="right" valign ="top">Internal</td>
									<td colspan='3'>
										<textarea style="width:357px;height:50px;" name="internal_doors">{$arrSpecification['DOORS_INTERNAL']}</textarea>
									</td> 
							  </tr>
							   <tr>
									<td width="20%" align="left"  valign ="top">Windows : </td>
									<td colspan='3'>
										<textarea style="width:357px;height:50px;" name="windows">{$arrSpecification['WINDOWS']}</textarea>
										
									</td>
							  </tr>
								
							  <tr>
								  <td width="20%" align="left"  valign ="top">Electrical Fitting : </td>
								  <td colspan='3'>
								  
									<textarea style="width:357px;height:50px;" name="electrical_fitting">{$arrSpecification['ELECTRICAL_FITTINGS']}</textarea>
								  </td>
							  </tr>

							   <tr>
								  <td width="20%" align="left"  valign ="top">Others : </td>
								  <td colspan='3'>
									<textarea style="width:357px;height:50px;" name="others">{$arrSpecification['OTHER_SPECIFICATIONS']}</textarea>
								  </td> 
							  </tr>
							   <tr>
			   					  <td colspan='4' align="center" 
									 <input type="hidden" name="projectId" value="{$projectId}" />
									 <input type="hidden" name="oldbuilderId" value="{$builderId}" />
									  <input type="hidden" name="preview" value="{$preview}" />
									 {if $edit_project == ''}
										
										<input type="submit" name="Skip" id="Skip" value="Skip" />
									 {/if}
									 <input type="submit" name="btnSave" id="btnSave" {if $edit_project == ''} value="Next" {else} value="Save" {/if} />
									 &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit" />
								  </td>
							   </tr>
							</div>
					 </form>
				</table>

			</TD>
		</TR>
 
	</TABLE>

