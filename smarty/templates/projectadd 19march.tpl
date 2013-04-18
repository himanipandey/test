<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../jscal/calendar.js"></script>
<script type="text/javascript" src="../jscal/lang/calendar-en.js"></script>
<script type="text/javascript" src="../jscal/calendar-setup.js"></script>
<script type="text/javascript" src="../jscal/datetimepicker.js" ></script>


<script type="text/javascript" src="tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="../fancybox/fancybox/jquery.fancybox-1.3.4.js"></script>
<link href="../fancybox/fancybox/jquery.fancybox-1.3.4.css" rel="stylesheet" type="text/css">





<script type="text/javascript">
var id = 0;
$(document).ready(function()
{

	var projectID=	$("#projectID").val();
	
	$("#formatEditor").click(function()
	{
		$.fancybox({
		//'autoScale'         : false,
					//'autoDimensions'  : true,
					'width'                :700,
					'height'               :530,
					//'hideOnContentClick': true,
					//'transitionIn'       : 'fade',
					//'transitionOut'     : 'none',
					'scrolling'            : 'no',
					'href'                 : 'tinytext.php?project_id='+projectID,
					'type'                : 'iframe'
			
		})
	});

	$(".cityId").change(function()
	{
		id=$(this).val();
		var dataString = 'id='+ id;
		$.ajax
		({
			type: "POST",
			url: "RefreshSuburb.php",
			data: dataString,
			cache: false,
			success: function(html)
			{
			$(".suburbId").html(html);
			}
		});
	});


});

$(document).ready(function()
{
$(".suburbId").change(function()
{

id=$(".cityId option:selected").val(); 


var suburb_id=$(this).val();
var dataString = 'suburb_id='+ suburb_id+"&id="+id;

$.ajax
({
type: "POST",
url: "RefreshSuburb.php",
data: dataString,
cache: false,
success: function(html)
{

$(".localityId").html(html);
}
});

});


});



	/*************Ajax code************/	
		function GetXmlHttpObject()
		{
			var xmlHttp=null;
			try
			{
					// Firefox, Opera 8.0+, Safari
					xmlHttp=new XMLHttpRequest();
			}
			catch (e)
			{
				//Internet Explorer
			try
			{
				xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e)
			{
				xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
		}
		return xmlHttp;
	}
	
	function imgref(buildid)
	{
			xmlHttp=GetXmlHttpObject()
		if (xmlHttp==null)
		{
			alert ("Browser does not support HTTP Request")
			return
		}
		var projectname	=	document.getElementById("txtProjectName").value;
		var url="RefreshImgpath.php?buildid="+buildid+"&projectname="+projectname;
		//alert(url);
		xmlHttp.onreadystatechange=stateChanged
		xmlHttp.open("GET",url,true)
		xmlHttp.send(null)
	}
	function stateChanged()
	{
	
		//document.getElementById("suburbId").innerHTML='<img src="images/loader.gif" />';
		if (xmlHttp.readyState==4)
		{
		
			document.getElementById('imgPathRefresh').innerHTML=xmlHttp.responseText;
		
		}
	}
	/*******************End Ajax Code*************/
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
	   		{include file="{$CRAWLER_TEMPLATE_PATH}left.tpl"}
	  </TD>
          <TD class=border-all vAlign=center align=middle width=10 bgColor=#f7f7f7>&nbsp;</TD>
          <TD class=border-rt vAlign=top align=middle width="100%" bgColor=#eeeeee height=400>
            <TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor=#b1b1b1 border=0><TBODY>
              <TR>
                <TD class=h1 align=left background=images/heading_bg.gif bgColor=#ffffff height=40>
                  <TABLE cellSpacing=0 cellPadding=0 width="99%" border=0><TBODY>
                    <TR>
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="images/arrow.gif" width=18>{if $projectid == ''} Add New {else} Edit {/if} Project</TD>
                      <TD align=right ></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
		
		
		     
<!--			<fieldset class="field-border">
			  <legend><b>Message</b></legend>-->
			  <TABLE cellSpacing=2 cellPadding=4 width="93%" align=center border=0>
			    <form method="post" enctype="multipart/form-data">
			      <div>
				<tr>
				  <td width="20%" align="right" >*Project Name : </td>
				  <td width="30%" align="left"><input type=text name=txtProjectName id=txtProjectName value="{$txtProjectName}" style="width:357px;"></td>
				 {if $ErrorMsg["txtProjectName"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtProjectName"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				
			<tr>
				  <td width="20%" align="right">*Builder Name : </td>
				  <td width="30%" align="left" >
						<select name = "builderId" class="builderId" onchange="imgref(this.value);">
							<option value =''>Select Builder</option>
							 {section name=data loop=$BuilderDataArr}
							 	<option {if $builderId == {$BuilderDataArr[data].BUILDER_ID}} value ='{$builderId}' selected="selected" {else} value ='{$BuilderDataArr[data].BUILDER_ID}'{/if} >{$BuilderDataArr[data].BUILDER_NAME}</option>
							 {/section}	
						</select>				  
				  <div id="imgPathRefresh"></div></td>
				   {if $ErrorMsg["builderId"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["builderId"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				   
				</tr>
				{if $builderId==''}
				<tr>
				  <td width="20%" align="right" >*Builder Name : </td>
				  <td width="30%" align="left" >{$builderName}</td>
				  {if $ErrorMsg["txtProjectLocation"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtProjectLocation"]}</font></td>{else} <td width="50%" align="left"><a href="builderadd.php?builderid={$txtProjectId}&mode=edit&page={$page}&sort={$sort}" title="{$BuilderDataArr[data].BUILDER_NAME}">Add Builder</a></td>{/if}
				 
				</tr>
				{/if}



				<tr>
				  <td width="20%" align="right" >*City : </td>
				  <td width="30%" align="left">
						<select name = "cityId" class="cityId">
							<option value =''>Select City</option>
							 {section name=data loop=$CityDataArr}
							 	<option {if $cityId == {$CityDataArr[data].CITY_ID}} value ='{$cityId}' selected="selected" {else} value ='{$CityDataArr[data].CITY_ID}' {/if}>{$CityDataArr[data].LABEL}</option>
							 {/section}	
						</select>				  
				  </td>
				  {if $ErrorMsg["cityId"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["cityId"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right">*Suburbs : </td>
				  <td width="30%" align="left"  >
				  			<select name="suburbId" class="suburbId" >
				  			<option value="">Select Suburb</option> 
				  			{if count($suburbSelect) gt 0} 
							
							{section name=data loop=$suburbSelect}
							
							<option {if $suburbId == {$suburbSelect[data].SUBURB_ID}} value = "{$suburbSelect[data].SUBURB_ID}" selected="selected" {else}  value = "{$suburbSelect[data].SUBURB_ID}" {/if}>{$suburbSelect[data].LABEL}</option>
  							{/section}	
  						   {/if}							 
 						</select> 		
						
				  </td>
				   {if $ErrorMsg["suburbId"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["suburbId"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right" >*Locality : </td>
				  <td width="30%" align="left"  >
				  
						<select name="localityId" class="localityId" >
				  			<option value="">Select Locality</option> 
				  			{if count($localitySelect) gt 0} 
							
							{section name=data loop=$localitySelect}
							
							<option {if $localityId == {$localitySelect[data].LOCALITY_ID}} value = "{$localityId}" selected="selected" {else}  value = "{$localitySelect[data].LOCALITY_ID}" {/if}>{$localitySelect[data].LABEL}</option>
  							{/section}	
  						   {/if}							 
 						</select> 
				 
				  </td>
				   {if $ErrorMsg["localityId"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["localityId"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right" valign="top">*Project Description :</td>
				  <td width="30%" align="left" >
				  	<textarea name="txtProjectDescription" rows="10" cols="45" id="projectDesc">{$txtProjectDescription}</textarea>
                  </td><td><a id="formatEditor" href="javascript:void(0);">FORMAT TEXT</a></td>
                {if $ErrorMsg["txtProjectDescription"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtProjectDescription"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right" >*Project Address : </td>
				  <td width="30%" align="left" ><input type=text name=txtProjectAddress id=txtProjectAddress value="{$txtAddress}" style="width:360px;"></td>
				   {if $ErrorMsg["txtAddress"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtAddress"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right">*Project Types : </td>
				  <td width="30%" align="left"><input type=text name=txtProjectTypes id=txtProjectTypes value="{$txtProjectTypes}" style="width:360px;"></td>
				  {if $ErrorMsg["txtProjectTypes"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtProjectTypes"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				{if $txtProjectSmallImg != ''}
				<tr>
				 <td width="20%" align="right">Current Image </td>
				  <td width="20%" align="left" >
				 
				  <img src="http://localhost/proptiger/images/{$txtProjectSmallImg}"> 
				  
				  </td>
				 
				  
				</tr>
				{/if}
				<!--<tr>
				  <td width="20%" align="right" >Project large Image : </td>
				  <td width="30%" align="left"><input type=file name='txtProjectSmallImg'  style="width:400px;"></td>
				   <td width="50%"></td>
				</tr>-->
				<tr>
				  <td width="20%" align="right" >*Project Location Desc : </td>
				  <td width="30%" align="left" ><input type=text name=txtProjectLocation id=txtProjectLocation value="{$txtProjectLocation}" style="width:360px;"></td>
				  {if $ErrorMsg["txtProjectLocation"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtProjectLocation"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right" >*Project Lattitude : </td>
				  <td width="30%" align="left"><input type=text name=txtProjectLattitude id=txtProjectLattitude value="{$txtProjectLattitude}" style="width:360px;"></td>
				    {if $ErrorMsg["txtProjectLattitude"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtProjectLattitude"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right" >*Project Longitude : </td>
				  <td width="30%" align="left" ><input type=text name=txtProjectLongitude id=txtProjectLongitude value="{$txtProjectLongitude}" style="width:360px;"></td>
				   {if $ErrorMsg["txtProjectLongitude"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtProjectLongitude"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right" >*Project Meta Title : </td>
				  <td width="30%" align="left" ><input type=text name=txtProjectMetaTitle id=txtProjectMetaTitle value="{$txtProjectMetaTitle}" style="width:360px;"></td>
				   {if $ErrorMsg["txtProjectMetaTitle"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtProjectMetaTitle"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right" valign="top">*Meta Keywords :</td>
				  <td width="30%" align="left" >
				  <textarea name="txtMetaKeywords" rows="10" cols="45">{$txtMetaKeywords}</textarea>
                  </td>
                  {if $ErrorMsg["txtMetaKeywords"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtMetaKeywords"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right" valign="top">*Meta Description :</td>
				  <td width="30%" align="left" >
				  <textarea name="txtMetaDescription" rows="10" cols="45">{$txtMetaDescription}</textarea>
                  </td>
                  {if $ErrorMsg["txtMetaDescription"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtMetaDescription"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right">*Display Order : </td>
				  <td width="30%" align="left" >
				  
				  
				 
						 <select name="DisplayOrder"  id="DisplayOrder" class="field">
						 
						 	<option value="">Select </option> 
  							{section name=foo start=1 loop=51 step=1}
  							<option {if $DisplayOrder == {$smarty.section.foo.index}} value="{$DisplayOrder}" selected = 'selected' {else} value="{$smarty.section.foo.index}"{/if} >{$smarty.section.foo.index}</option>
  							{/section}
  							 	
 						 </select>
				 
				  </td>
				  {if $ErrorMsg["DisplayOrder"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["DisplayOrder"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right">*Active : </td>
				  <td width="30%" align="left">
						 <select name="Active"  id="Active" class="field">
						 <option {if $Active == 0}  value="0" selected="selected" {else} value="0"{/if} >0</option> 
  							<option {if $Active == 1} value="1" selected="selected" {else} value="1" {/if} >1</option> 
  							
  							  	
 						 </select>
				 
				  </td>
				   {if $ErrorMsg["Active"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["Active"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right" >*Project Status : </td>
				  <td width="30%" align="left" >
						 <select name="Status"  id="Status" class="field">
						 <option value="">Select </option> 
  							<option {if $Status == 'Under Construction'} value="Under Construction" selected="selected" {else} value="Under Construction" {/if}>Under Construction</option>  
  							<option {if $Status == 'New Launch'} value="New Launch" selected="selected" {else} value="New Launch" {/if}>New Launch</option>
  							<option {if $Status == 'Sold Out'} value="Sold Out" selected="selected" {else} value="Sold Out" {/if}>Sold Out</option>    
  							  	
 						 </select>
				 
				  </td>
				   {if $ErrorMsg["Status"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["Status"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right" >*Project URL : </td>
				  <td width="30%" align="left" ><input type=text name=txtProjectURL id=txtProjectURL value="{$txtProjectURL}" style="width:360px;"></td>
				    {if $ErrorMsg["txtProjectURL"] != ''} <td width="50%" align="left"  ><font color = "red">{$ErrorMsg["txtProjectURL"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				 <td width="20%" align="right">URL: </td>
				<td  width="30%" align="left"> {$txtUrl}</td></tr>
				<tr>
				  <td width="20%" align="right">*Featured : </td>
				  <td width="30%" align="left">
						 <select name="Featured"  id="Featured" class="field">
  							<option {if $Featured == 0} value="0" selected="selected" {else} value="0" {/if}>0</option>  
  							<option {if $Featured == 1} value="1" selected="selected" {else} value="1" {/if}>1</option>
  							 							  	
 						 </select>
				 
				  </td>
				  {if $ErrorMsg["Featured"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["Featured"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
				<tr>
				  <td width="20%" align="right">Completion Date : </td>
				  <td width="30%" align="left" >
				  
				   <select name="Completion"  id="Completion" class="field">
				   <option value="">Select </option> 
						{section name=foo start=1 loop=100 step=1}
							<option {if $Completion == {2010+($smarty.section.foo.index)}} value="{$Completion} " selected = "selected"{else}  value="{2010+($smarty.section.foo.index)} " {/if}>{2010+($smarty.section.foo.index)}</option>  
  
						{/section}
					</select>
				  </td>
				   {if $ErrorMsg["Completion"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["Completion"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>
					<tr>
				  <td width="20%" align="right" valign="top">*Price Disclaimer :</td>
				  <td width="30%" align="left" >
				  <textarea name="txtDisclaimer" rows="10" cols="45">{$txtDisclaimer}</textarea>
                  </td>
                 {if $ErrorMsg["txtDisclaimer"] != ''} <td width="50%" align="left" ><font color = "red">{$ErrorMsg["txtDisclaimer"]}</font></td>{else} <td width="50%" align="left"></td>{/if}
				</tr>

				<tr>
				  <td width="20%" align="right" valign="top">Payment Plan :</td>
				  <td width="30%" align="left" >
				  <textarea name="PaymentPlan" rows="10" cols="45">{$PaymentPlan}</textarea>
                  </td>
                  <td width="50%" align="left" ></td>
				</tr>

				<tr>
				  <td width="20%" align="right" valign="top">No Of Towers :</td>
				  <td width="30%" align="left" >
				  
					 <select name="no_of_towers"  id="no_of_towers" class="field">
				   <option value="">Select </option> 
						{section name=foo start=1 loop=101 step=1}
							<option {if $no_of_towers == $smarty.section.foo.index} value="{$no_of_towers} " selected = "selected"{else}  value="{$smarty.section.foo.index} " {/if}>{$smarty.section.foo.index}</option>  
  
						{/section}
					</select>

                  </td>
                  <td width="50%" align="left" ></td>
				</tr>

				<tr>
				  <td width="20%" align="right" valign="top">No Of Flats :</td>
				  <td width="30%" align="left" >
					 <select name="no_of_flats"  id="no_of_flats" class="field">
						<option value="">Select </option> 
						{section name=foo start=1 loop=10001 step=1}
							<option {if $no_of_flats == $smarty.section.foo.index} value="{$no_of_flats} " selected = "selected"{else}  value="{$smarty.section.foo.index} " {/if}>{$smarty.section.foo.index}</option>  
  
						{/section}
                  </td>
                  <td width="50%" align="left" ></td>
				</tr>

				<tr>
				  <td width="20%" align="right" valign="top">Launch Date :</td>
				  <td width="30%" align="left" >
				 
						<input name="eff_date_to" value="{$eff_date_to}" type="text" class="formstyle2" id="f_date_c_to" readonly="1" value="" size="10">  <img src="images/cal_1.jpg" id="f_trigger_c_to" style="cursor: pointer; border: 1px solid red;" title="Date selector" onMouseOver="this.style.background='red';" onMouseOut="this.style.background=''" />

                  </td>
                  <td width="50%" align="left" ></td>
				</tr>
				


				<tr>
				  <td >&nbsp;</td>
				  <td align="left" style="padding-left:152px;" >
				  <input type="hidden" name="catid" value="<?php echo $catid ?>" />
				  <input type="hidden" name="projectID" id="projectID" value="{$txtProjectId}" />
				  <input type="submit" name="btnSave" id="btnSave" value="Save">
				  &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit">
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
   

