<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
    tinyMCE.init({
        //mode : "textareas",
        mode : "specific_textareas",
        editor_selector : "myTextEditor",
        theme : "advanced"
    });
    
    function isNumberKey(evt)
    {


       var charCode = (evt.which) ? evt.which : event.keyCode;
               if(charCode == 99 || charCode == 118)
               return true;
       if (charCode > 31 && (charCode < 46 || charCode > 57))
          return false;

       return true;
    }
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
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>Microsite Management</TD>
                      <TD align=right ></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
		
		{if $micrositeFlgExec == 1}
		     
<!--			<fieldset class="field-border">
			  <legend><b>Message</b></legend>-->
			  <TABLE cellSpacing=2 cellPadding=4 width="93%" align=center border=0>
			     <tr>
                                <form method = "post">
                              
                                <td align = "left" colspan="2">
                                    <fieldset>
                                    <b>Project Id</b>:&nbsp;
                                    <input type="text" name = "projectId" value="{$projectId}">
                                    &nbsp;
				    <b>Slider Images Count</b>:&nbsp;
                                    <select name = "sliderImgCnt">
					{$cntSlder = 1}
                                          {section name=sliderImgCnt loop=20 step=1}
					  	<option value = "{$cntSlder}" {if $cntSlder == $sliderImgCnt}selected{/if}>{$cntSlder}</option>
					   {$cntSlder = $cntSlder+1}
					  {/section}
				    </select>
                                    &nbsp;
                                    <input type = "submit" name = "searchProject" value="Get Project Detail">
                                     </fieldset>
                                </td>
                                </form>
                            
                            </tr>  
                            {if $succesMsg != ''}
                                <tr><td colspan="2">{$succesMsg}</td></tr>
                                <tr><td colspan="2"><a rel="nofollow" href="micrositeDownload.php?download_file=microsite.json">Download Microsite Data here</a></td></tr>
                            {else}
                             <form method="post" enctype="multipart/form-data">    
                             <tr>
                                    <td  align = "center" colspan = "2">
                                       <font color = "red" style="font-size:17px;">{$ErrorMsg2}</font><br>
                                    </td>
				</tr>

                                <tr>
                                    <td width="20%" align="right" ><font color = "red">*</font><b>Project Name :</b> </td>
                                    <input type=hidden name="projectNameOld" id="projectNameOld" value="{$projectName}" style="width:357px;">
                                    <td width="30%" align="left"><input type=text name=projectName id=projectName value="{$projectName}" style="width:357px;"></td>
                                    {if $ErrorMsg["projectName"] != ''}
                                    <td width="50%" align="left" nowrap><font color = "red">{$ErrorMsg["projectName"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
                                <tr>
                                    <td width="20%" align="right" ><font color = "red">*</font><b>Builder Name :</b> </td>
                                    <td width="30%" align="left"><input type=text name="builderName" id="builderName" value="{$builderName}" style="width:357px;"></td>
                                    {if $ErrorMsg["builderName"] != ''}
                                    <td width="50%" align="left" nowrap><font color = "red">{$ErrorMsg["builderName"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right" valign="top"><font color = "red">*</font><b>City :</b></td>
				  <td width="30%" align="left" ><input type=text name="cityName" id="cityName" value="{$cityName}" style="width:357px;">
                                      {if $ErrorMsg["cityName"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["cityName"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
                                <tr>
				  <td width="20%" align="right" valign="top"><font color = "red">*</font><b>Locality :</b></td>
				  <td width="30%" align="left" ><input type=text name="localityName" id="localityName" value="{$localityName}" style="width:357px;">
                                      {if $ErrorMsg["localityName"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["localityName"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
                                <tr>
				  <td width="20%" align="right" valign="top"><font color = "red">*</font><b>Contact :</b></td>
				  <td width="30%" align="left" ><input type=text name="contactNumber" onkeypress="return isNumberKey(event)" id="contactNumber" value="{$contactNumber}" style="width:357px;">
                                      {if $ErrorMsg["contactNumber"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["contactNumber"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
                                
                                <tr>
                                    <td width="20%" align="right" valign="top"><b><b><font color ="red">*</font><b>Project Description :</b> </td>
                                    <td width="30%" align="left">
                                           <textarea name="projectDesc" rows="20" cols="90"  class ="myTextEditor" id = "projectDesc">{$projectDesc}</textarea>
                                          
                                    </td>
                                    <td width="50%" align="left" valign = "top">
                                            <font color="red">{if $ErrorMsg["projectDesc"] != ''} {$ErrorMsg["projectDesc"]} {/if}<span id = "err_project_bhk" style = "display:none;">Please enter Project Description!</span></font>
                                   </td>
                               </tr>
                               
                              
				<tr>
				  <td width="20%" align="right" > <b><font color = "red">*</font>Project Meta Title : </b></td>
				  <td width="30%" align="left" ><input type=text name=metaTitle id=metaTitle value="{$metaTitle}" style="width:360px;"></td>
				  <td width="50%" align="left" valign = "top">
                                            <font color="red">{if $ErrorMsg["metaTitle"] != ''} {$ErrorMsg["metaTitle"]} {/if}<span id = "err_project_bhk" style = "display:none;">Please enter meta title!</span></font>
                                   </td>
				</tr>
				<tr>
				  <td width="20%" align="right" valign="top"><b>Project Meta Keywords :</b></td>
				  <td width="30%" align="left" >
                                    <textarea name="metaKeywords" rows="10" cols="45">{$metaKeywords}</textarea>
                                  </td>
                                  <td width="50%" align="left" valign = "top"> &nbsp; </td>
				</tr>
				<tr>
				  <td width="20%" align="right" valign="top" nowrap><b>Project Meta Description :</b></td>
				  <td width="30%" align="left" >
                                    <textarea name="metaDescription" rows="10" cols="45">{$metaDescription}</textarea>
				  </td>
                                  <td width="50%" align="left" valign = "top">&nbsp;</td>
				</tr>
                                <tr>
				  <td width="20%" align="right" nowrap> <b><font color = "red">*</font>Specification Meta Title : </b></td>
				  <td width="30%" align="left" ><input type=text name=metaTitleSpecification id=metaTitleSpecification value="{$metaTitleSpecification}" style="width:360px;"></td>
				  <td width="50%" align="left" valign = "top"><font color="red">{if $ErrorMsg["metaTitleSpecification"] != ''} {$ErrorMsg["metaTitleSpecification"]} {/if}<span id = "err_project_bhk" style = "display:none;">Please enter specification meta title!</span></font></td>
				</tr>
                                 <tr>
				  <td width="20%" align="right" nowrap> <b><font color = "red">*</font>Floor Plan Meta Title : </b></td>
				  <td width="30%" align="left" ><input type=text name=metaTitleFloorPlan id=metaTitleFloorPlan value="{$metaTitleFloorPlan}" style="width:360px;"></td>
				  <td width="50%" align="left" valign = "top"><font color="red">{if $ErrorMsg["metaTitleFloorPlan"] != ''} {$ErrorMsg["metaTitleFloorPlan"]} {/if}<span id = "err_project_bhk" style = "display:none;">Please enter floor plan meta title!</span></font></td>
				</tr>
                                <tr>
				  <td width="20%" align="right" nowrap> <b><font color = "red">*</font>Payment Plan Meta Title : </b></td>
				  <td width="30%" align="left" ><input type=text name=metaTitlePaymentPlan id=metaTitlePaymentPlan value="{$metaTitlePaymentPlan}" style="width:360px;"></td>
				  <td width="50%" align="left" valign = "top"><font color="red">{if $ErrorMsg["metaTitlePaymentPlan"] != ''} {$ErrorMsg["metaTitlePaymentPlan"]} {/if}<span id = "err_project_bhk" style = "display:none;">{$ErrorMsg["metaTitlePaymentPlan"]}</span></font></td>
				</tr>
                                <tr>
				  <td width="20%" align="right" > <b><font color = "red">*</font>Price List Meta Title : </b></td>
				  <td width="30%" align="left" ><input type=text name=metaTitlePriceList id=metaTitlePriceList value="{$metaTitlePriceList}" style="width:360px;"></td>
				  <td width="50%" align="left" valign = "top"><font color="red">{if $ErrorMsg["metaTitlePriceList"] != ''} {$ErrorMsg["metaTitlePriceList"]} {/if}<span id = "err_project_bhk" style = "display:none;">{$ErrorMsg["metaTitlePriceList"]}</span></font></td>
				</tr>
                                <tr>
				  <td width="20%" align="right" nowrap> <b><font color = "red">*</font>Site Plan Meta Title : </b></td>
				  <td width="30%" align="left" ><input type=text name=metaTitleSitePlan id=metaTitleSitePlan value="{$metaTitleSitePlan}" style="width:360px;"></td>
				  <td width="50%" align="left" valign = "top"><font color="red">{if $ErrorMsg["metaTitleSitePlan"] != ''} {$ErrorMsg["metaTitleSitePlan"]} {/if}<span id = "err_project_bhk" style = "display:none;">{$ErrorMsg["metaTitleSitePlan"]}</span></font></td>
				</tr>
                                <tr>
				  <td width="20%" align="right" nowrap> <b><font color = "red">*</font>Location Map Meta Title : </b></td>
				  <td width="30%" align="left" nowrap><input type=text name=metaTitleLocationMap id=metaTitleLocationMap value="{$metaTitleLocationMap}" style="width:360px;"></td>
				  <td width="50%" align="left" valign = "top"><font color="red">{if $ErrorMsg["metaTitleLocationMap"] != ''} {$ErrorMsg["metaTitleLocationMap"]} {/if}<span id = "err_project_bhk" style = "display:none;">{$ErrorMsg["metaTitleLocationMap"]}</span></font></td>
				</tr>
                                <tr>
				  <td width="20%" align="right" nowrap> <b><font color = "red">*</font>Contactus Meta Title : </b></td>
				  <td width="30%" align="left" ><input type=text name=metaTitleContactus id=metaTitleContactus value="{$metaTitleContactus}" style="width:360px;"></td>
				  <td width="50%" align="left" valign = "top"><font color="red">{if $ErrorMsg["metaTitleContactus"] != ''} {$ErrorMsg["metaTitleContactus"]} {/if}<span id = "err_project_bhk" style = "display:none;">{$ErrorMsg["metaTitleContactus"]}</span></font></td>
				</tr>
                                
                                <tr>
                                    <td width="20%" align="left"  valign ="top"><b>GA CODE :</b> </td>
                                    <td width="30%" align="left" >
                                          <textarea name="gaCode" rows="10" cols="45">{$gaCode}</textarea>
                                    </td> 
                                     <td width="50%" align="left" valign = "top">&nbsp;</td>
                                  </tr>
                                <tr>
                                    <td   align="left"  valign ="top" colspan = "3"><b>Price :</b> </td>
                                </tr>
                                  
                                  <tr>
                                      <td width="100%" align="left"  valign ="top" colspan="2" style = "padding-left: 60px;">
                                          <table width = 40% align = "left" style = "border :1px solid; color: #677788;">
                                              {if $ErrorMsg['configName'] != ''}
                                                  <tr><td align = "left" colspan="4"><font color = "red">{$ErrorMsg['configName']}</font></td></tr>
                                              {/if}
                                              <tr><td align = "left"><b>Unit Name</b></td>
                                                  <td align = "left"><b>Price Per Unit Area</b></td>
                                                  <td align = "left"><b>Size per sq ft</b></td>
                                              </tr>
                                              {$cnt = 0}
                                              {if $projectId != ''}
                                                {foreach from = $arrProjectConfig key = key item = item}
                                                <tr>
                                                <input type = "hidden" name = "configId[]" value = "{$key}">
                                                    <td align = "center"><input type="text" name="price_unitName[]" value="{$item['price_unitName']}"></td>
                                                    <td align = "center"><input onkeypress="return isNumberKey(event)" type="text" name="price_PerUnitArea[]" value="{$item['price_PerUnitArea']}"></td>
                                                    <td align = "center"><input onkeypress="return isNumberKey(event)" type="text" name="price_size[]" value="{$item['price_size']}"></td>
                                                </tr>

                                                {$cnt = $key}
                                                {/foreach} 
                                              {/if}
                                              {if $cnt == 0 && $projectId == ''}{$key = 1}{else}{$key = $cnt+1}{/if}
                                              <tr>
                                              <input type = "hidden" name = "configId[]" value = "{$key}">
                                                  <td align = "center"><input type="text" name="price_unitName[]" value="{$arrProjectConfig[$key]['price_unitName']}"></td>
                                                  <td align = "center"><input onkeypress="return isNumberKey(event)" type="text" name="price_PerUnitArea[]" value="{$arrProjectConfig[$key]['price_PerUnitArea']}"></td>
                                                  <td align = "center"><input onkeypress="return isNumberKey(event)" type="text" name="price_size[]" value="{$arrProjectConfig[$key]['price_size']}"></td>
                                              </tr>
                                              {if $cnt == 0 && $projectId == ''}{$key = 2}{else}{$key = $cnt+2}{/if}
                                              <tr>
                                              <input type = "hidden" name = "configId[]" value = "{$key}">
                                                  <td align = "center"><input type="text" name="price_unitName[]" value="{$arrProjectConfig[$key]['price_unitName']}"></td>
                                                  <td align = "center"><input onkeypress="return isNumberKey(event)" type="text" name="price_PerUnitArea[]" value="{$arrProjectConfig[$key]['price_PerUnitArea']}"></td>
                                                  <td align = "center"><input onkeypress="return isNumberKey(event)" type="text" name="price_size[]" value="{$arrProjectConfig[$key]['price_size']}"></td>
                                              </tr>
                                              {if $cnt == 0 && $projectId == ''}{$key = 3}{else}{$key = $cnt+3}{/if}
                                              <tr>
                                              <input type = "hidden" name = "configId[]" value = "{$key}">
                                                  <td align = "center"><input type="text" name="price_unitName[]" value="{$arrProjectConfig[$key]['price_unitName']}"></td>
                                                  <td align = "center"><input onkeypress="return isNumberKey(event)" type="text" name="price_PerUnitArea[]" value="{$arrProjectConfig[$key]['price_PerUnitArea']}"></td>
                                                  <td align = "center"><input onkeypress="return isNumberKey(event)" type="text" name="price_size[]" value="{$arrProjectConfig[$key]['price_size']}"></td>
                                              </tr>
                                              {if $cnt == 0 && $projectId == ''}{$key = 4}{else}{$key = $cnt+4}{/if}
                                              <tr>
                                              <input type = "hidden" name = "configId[]" value = "{$key}">
                                                  <td align = "center"><input type="text" name="price_unitName[]" value="{$arrProjectConfig[$key]['price_unitName']}"></td>
                                                  <td align = "center"><input onkeypress="return isNumberKey(event)" type="text" name="price_PerUnitArea[]" value="{$arrProjectConfig[$key]['price_PerUnitArea']}"></td>
                                                  <td align = "center"><input onkeypress="return isNumberKey(event)" type="text" name="price_size[]" value="{$arrProjectConfig[$key]['price_size']}"></td>
                                              </tr>
                                          </table>
                                      
                                      </td>
                                  </tr>
                                  
                                  <!--code for image title name and alt tag-->
                                  <tr>
                                    <td   align="left"  valign ="top" colspan = "3"><b>Slider Images :</b> </td>
                                </tr>
                                
                                  <tr>
                                      <td width="100%" align="left"  valign ="top" colspan="3" style = "padding-left: 60px;">
                                          <table width = 30% align = "left" style = "border :1px solid; color: #677788;">
                                              {if $ErrorMsg['imgTitleName'] != ''}
                                                  <tr><td align = "left" colspan="3"><font color = "red">{$ErrorMsg['imgTitleName']}</font></td></tr>
                                              {/if}
                                              <tr><td align = "left"><b>Name</b></td>
                                                  <td align = "left"><b>Title</b></td>
                                                  <td align = "left"><b>Alt Tag</b></td>
                                              </tr>
                                              {$cnt = 1}
                                              {section name=imageDetail loop=$sliderImgCnt step=0}
                                              <tr>
                                                  <td align = "center"><input type="text" name="imageName[]" value="{$arrImage[{$smarty.section.imageDetail.index}]['imageName']}" readonly></td>
                                                  <td align = "center"><input type="text" name="imageTitle[]" value="{$arrImage[{$smarty.section.imageDetail.index}]['imageTitle']}"></td>
                                                  <td align = "center"><input type="text" name="imageAlt[]" value="{$arrImage[{$smarty.section.imageDetail.index}]['imageAlt']}"></td>
                                              </tr>
                                              {$cnt = $cnt+1}
                                             {/section}
                                              
                                          </table>
                                      
                                      </td>
                                  </tr>
                                  <!--end code for images-->
                                  <tr>
                                    <td   align="left"  valign ="top" colspan = "3">&nbsp;</td>
                                  </tr>
                                  
                                 <tr>
                                                <td width="20%" align="left" colspan='4'><b>Specifications :</b> </td>
                                  </tr>
                                  <tr>
                                                <td width="20%" align="left" colspan='4'>Flooring : </td>
                                  </tr>
                                  <tr>
                                            <td align="right" valign ="top">Master Bedroom</td><td>
                                                <textarea style="width:357px;height:50px;" name="master_bedroom_flooring">{$master_bedroom_flooring}</textarea></td>
                                            <td width="50%" align="left">
                                                 <font color="red">{if $ErrorMsg["master_bedroom_flooring"] != ''} {$ErrorMsg["master_bedroom_flooring"]} {/if}</font>
                                           </td>
                                  </tr>
                                  <tr>
                                        <td align="right" valign ="top">Other Bedroom</td><td>
                                        <textarea style="width:357px;height:50px;" name="other_bedroom_flooring">{$other_bedroom_flooring}</textarea>
                                        </td>	
                                        <td width="50%" align="left">
                                                 <font color="red">{if $ErrorMsg["other_bedroom_flooring"] != ''} {$ErrorMsg["other_bedroom_flooring"]} {/if}</font>
                                        </td>
                                  </tr>
                                  <tr>
                                        <td align="right" valign ="top">Living/Dining</td><td>
                                        <textarea style="width:357px;height:50px;" name="living_room_flooring">{$living_room_flooring}</textarea>
                                        </td>	
                                        <td width="50%" align="left">
                                            <font color="red">{if $ErrorMsg["living_room_flooring"] != ''} {$ErrorMsg["living_room_flooring"]} {/if}</font>
                                       </td>
                                 </tr>
                                 <tr>
                                        <td align="right" valign ="top">Kitchen</td><td>
                                        <textarea style="width:357px;height:50px;" name="kitchen_flooring">{$kitchen_flooring}</textarea>
                                        </td>
                                        <td width="50%" align="left">
                                            <font color="red">{if $ErrorMsg["kitchen_flooring"] != ''} {$ErrorMsg["kitchen_flooring"]} {/if}</font>
                                       </td>
                                 </tr>
                                 <tr>
                                        <td align="right" valign ="top">Toilets</td><td>
                                        <textarea style="width:357px;height:50px;" name="toilets_flooring">{$toilets_flooring}</textarea>
                                        </td>
                                        <td width="50%" align="left">
                                            <font color="red">{if $ErrorMsg["toilets_flooring"] != ''} {$ErrorMsg["toilets_flooring"]} {/if}</font>
                                       </td>
                                 </tr>

                                 <tr>
                                        <td align="right" valign ="top">Balcony</td><td>
                                            <textarea style="width:357px;height:50px;" name="balcony_flooring">{$balcony_flooring}</textarea>
                                        </td>	
                                        <td width="50%" align="left">
                                            <font color="red">{if $ErrorMsg["balcony_flooring"] != ''} {$ErrorMsg["balcony_flooring"]} {/if}</font>
                                       </td>
                                 </tr>

                                 <tr>
                                      <td width="20%" align="left" colspan='4' valign ="top">Walls : </td>
                                 </tr>

                                 <tr>
                                        <td align="right" valign ="top">Interior</td><td>
                                                <textarea style="width:357px;height:50px;" name="interior_walls">{$interior_walls}</textarea>
                                        </td>	
                                        <td width="50%" align="left">
                                            <font color="red">{if $ErrorMsg["interior_walls"] != ''} {$ErrorMsg["interior_walls"]} {/if}</font>
                                       </td>
                                 </tr>
                                 <tr>
                                        <td align="right" valign ="top">Exterior</td><td>
                                            <textarea style="width:357px;height:50px;" name="exterior_walls">{$exterior_walls}</textarea>
                                        </td>
                                        <td width="50%" align="left">
                                            <font color="red">{if $ErrorMsg["exterior_walls"] != ''} {$ErrorMsg["exterior_walls"]} {/if}</font>
                                       </td>
                                </tr>

                                <tr>
                                        <td align="right" valign ="top">Kitchen</td><td>
                                             <textarea style="width:357px;height:50px;" name="kitchen_walls">{$kitchen_walls}</textarea>
                                        </td>	
                                        <td width="50%" align="left">
                                            <font color="red">{if $ErrorMsg["kitchen_walls"] != ''} {$ErrorMsg["kitchen_walls"]} {/if}</font>
                                       </td>
                                </tr>

                                <tr>
                                    <td align="right" valign ="top">Toilets</td><td>
                                         <textarea style="width:357px;height:50px;" name="toilets_walls">{$toilets_walls}</textarea>
                                    </td>
                                    <td width="50%" align="left">
                                            <font color="red">{if $ErrorMsg["toilets_walls"] != ''} {$ErrorMsg["toilets_walls"]} {/if}</font>
                                    </td>
                                </tr>

                                <tr>
                                        <td width="20%" align="left" colspan='4'>Fittings and Fixtures : </td>
                                </tr>

                                <tr>
                                    <td align="right" valign ="top">Kitchen</td><td>
                                            <textarea style="width:357px;height:50px;" name="kitchen_fixtures">{$kitchen_fixtures}</textarea>
                                    </td>
                                    <td width="50%" align="left">
                                         <font color="red">{if $ErrorMsg["kitchen_fixtures"] != ''} {$ErrorMsg["kitchen_fixtures"]} {/if}</font>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" valign ="top">Toilets</td><td>
                                            <textarea style="width:357px;height:50px;" name="toilets_fixtures">{$toilets_fixtures}</textarea>
                                    </td>
                                    <td width="50%" align="left">
                                         <font color="red">{if $ErrorMsg["toilets_fixtures"] != ''} {$ErrorMsg["toilets_fixtures"]} {/if}</font>
                                    </td>
                                </tr>
                                <tr>
                                        <td width="20%" align="left" colspan='4'>Doors : </td>
                                </tr>
                                <tr>
                                        <td align="right" valign ="top">Main</td>
                                        <td>
                                            <textarea style="width:357px;height:50px;" name="main_doors">{$main_doors}</textarea>
                                        </td>
                                        <td width="50%" align="left">
                                            <font color="red">{if $ErrorMsg["main_doors"] != ''} {$ErrorMsg["main_doors"]} {/if}</font>
                                        </td>
                                </tr>
                                <tr>
                                        <td align="right" valign ="top">Internal</td>
                                        <td>
                                             <textarea style="width:357px;height:50px;" name="internal_doors">{$internal_doors}</textarea>
                                        </td> 
                                        <td width="50%" align="left">
                                            <font color="red">{if $ErrorMsg["internal_doors"] != ''} {$ErrorMsg["internal_doors"]} {/if}</font>
                                        </td>
                                </tr>
                                <tr>
                                        <td width="20%" align="left"  valign ="top">Windows : </td>
                                        <td>
                                                <textarea style="width:357px;height:50px;" name="Windows">{$Windows}</textarea>
                                        </td>
                                        <td width="50%" align="left">
                                            <font color="red">{if $ErrorMsg["Windows"] != ''} {$ErrorMsg["Windows"]} {/if}</font>
                                        </td>
                                </tr>
                                <tr>
                                        <td width="20%" align="left"  valign ="top">Electrical Fitting : </td>
                                        <td>
                                              <textarea style="width:357px;height:50px;" name="electrical_fitting">{$electrical_fitting}</textarea>
                                        </td>
                                        <td width="50%" align="left">
                                          <font color="red">{if $ErrorMsg["electrical_fitting"] != ''} {$ErrorMsg["electrical_fitting"]} {/if}</font>
                                        </td>
                                </tr>
                                <tr>
                                          <td width="20%" align="left"  valign ="top">Others : </td>
                                          <td>
                                                <textarea style="width:357px;height:50px;" name="others">{$others}</textarea>
                                          </td> 
                                          <td width="50%" align="left">
                                            <font color="red">{if $ErrorMsg["electrical_fitting"] != ''} {$ErrorMsg["electrical_fitting"]} {/if}</font>
                                          </td>
                                </tr>
                                <input type = "hidden" name = "projectId" value = "{$projectId}">
				<input type = "hidden" name = "sliderImgCnt" value = "{$sliderImgCnt}">
                                <tr style ="border:1px solid #c2c2c2;height:30px;width:70px;background:#999999;color:#fff;font-weight:bold;cursor:hand;pointer:hand;">
				  <td colspan = "2" align="right" style="padding-left:152px;" >
				  <input type="submit" name="generateMicrosite" id="generateMicrosite" value="Generate Microsite Code">
				  </td>
				</tr>
                                </form>
                                {/if}
			    </TABLE>
<!--			</fieldset>-->
	            </td>
		  </tr>
		</TABLE>
                                                         
                {else}
                    <font color="red">No Access</font>
                {/if}
	      </TD>
            </TR>
          </TBODY></TABLE>
        </TD>
      </TR>
    </TBODY></TABLE>
  </TD>
</TR>
