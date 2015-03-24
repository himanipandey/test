  <script type="text/javascript" src="js/jquery.js"></script>

   <script type="text/javascript" src="js/apartmentConfiguration.js"></script>

   <script type="text/javascript" src="../../scripts/fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>

<link rel="stylesheet" type="text/css" href="../../scripts/fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
 
 <SCRIPT language=Javascript>
     


      function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode;
         if(charCode == 99 || charCode == 118)
        	 return true;
         if (charCode > 31 && (charCode < 46 || charCode > 57))
            return false;

         return true;
      }
      
    function onSelectOption(c){
      	$("#floor_name_"+c+" option").each(function() {
		    $(this).remove();
		});
      	value = $("#options_"+c+" option:selected").text().trim();
      	
      	$('<option>').val("0").text("Select Floor Plan Name").appendTo('#floor_name_'+c);
      	/*if(value == "Floor Plan" || value == "Simplex"){
      		$('<option>').val(value).text(value).appendTo('#floor_name_'+c);
      	}
      	else*/ 
        if(value == "Basement Floor" || value == "Stilt Floor" || value == "First Floor" || value == "Second Floor"|| value == "Third Floor" || value == "Terrace Floor" )
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
        else if(value == "Floor Plan" || value == "Floor Plan" || value == "3D Floor Plan" || value == "Panorama"){
      		$('<option>').val(value).text(value).appendTo('#floor_name_'+c);
      	}

      	$("#floor_name_"+c+" option").each(function() {
      		var str = $("#uploaded_"+c).val();
      		var str1 = $("#uploaded3D_"+c).val();

      		console.log("str="+str);
		console.log("str1="+str1);

      		if (str.indexOf($(this).text()) >= 0 && $(this).text()!="3D Floor Plan")
		    	$(this).attr("disabled", true);
		    else if (str1.indexOf($(this).text()) >= 0   && $(this).text()!="Floor Plan")
		    	$(this).attr("disabled", true);
		});
      	
      	//$('<option>').val(imgName1).text(imgName1).appendTo('#floor_name_'+c);
      	//$('<option>').val(imgName2).text(imgName2).appendTo('#floor_name_'+c);
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

	 function show_add(id)
	 {
		var id = "add_"+(id+1);
		document.getElementById(id).style.display = '';
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
                      <TD class=h1 width="67%"><IMG height=18 hspace=5 src="../images/arrow.gif" width=18>{if $projecttypeid == ''} Add New {else} Edit {/if} Floor Plan({$ProjectDetail[0]['BUILDER_NAME']} {$ProjectDetail[0]['PROJECT_NAME']})</TD>
                      <TD align=right ></TD>
                    </TR>
		  </TBODY></TABLE>
		</TD>
	      </TR>
              <TR>
                <TD vAlign=top align=middle class="backgorund-rt" height=450><BR>
		
		<div id='roomCategory' style='display:none;' >
				<select name='roomCategory' >
				<option value=''>Select</option>
				{foreach from=$RoomCategoryArr key=k item=v} 
					<option value="{$k}">{$v}</option>
				{/foreach}
				</select>
				</div>

		     
<!--			<fieldset class="field-border">
			  <legend><b>Message</b></legend>-->
			  <div style="overflow:auto;">
			  <TABLE cellSpacing=2 cellPadding=4 width="93%" align=center  style="border:1px solid #c2c2c2;">
			    <form method="post" enctype="multipart/form-data">
			      <div>
				<tr><td colspan="6"><font color="red">{if $projectId != ''}{$ErrorMsg1}{/if}</font></td></tr>
				<tr class = "headingrowcolor" >
				  <td  nowrap="nowrap" width="1%" align="center" class=whiteTxt >SNo.</td>
	
				  <td nowrap="nowrap" width="7%" align="left" class=whiteTxt>Unit Name</td>
				  <td nowrap="nowrap" width="3%" align="left" class=whiteTxt>Size/Carpet Area</td>
				  <td nowrap="nowrap" width="6%" align="left" class=whiteTxt>Unit Type</td>
				  <td nowrap="nowrap" width="6%" align="left" class=whiteTxt>Floor Plan Options</td>
				  <td nowrap="nowrap" width="6%" align="left" class=whiteTxt><font color="red">*</font>Floor Plan Name</td>
			
				   <td nowrap="nowrap" width="3%" align="left" class=whiteTxt><font color="red">*</font>Image <span style = "font-size:10px">(image name must content floor-plan)</span></td>
					
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
						<input type="hidden" name = "option_id[]" value = "{$ProjectOptionDetail[$smarty.section.foo.index]['OPTION_ID']}">		  
				  
				  </td>
				 
				  <td>{if $ProjectOptionDetail[$smarty.section.foo.index]['SIZE'] == ''}- {/if}{$ProjectOptionDetail[$smarty.section.foo.index]['SIZE']}/{if $ProjectOptionDetail[$smarty.section.foo.index]['CARPET_AREA'] == ''} -{/if}
{$ProjectOptionDetail[$smarty.section.foo.index]['CARPET_AREA']}
</td>
				  <td>{$ProjectOptionDetail[$smarty.section.foo.index]['UNIT_TYPE']}</td>
				  {if $villApartment[$smarty.section.foo.index] == 'yes'} 
				  <td><select id="options_{($smarty.section.foo.index+1)}" onchange="return onSelectOption({($smarty.section.foo.index+1)});">
				  	<Option value="0">Select Floor Plan Options</Option>
				  	
					   {foreach from=$floorPlanOptionsArr[$smarty.section.foo.index] item=data}
					   		<Option >{$data}</Option>
					   {/foreach}
				  </select></td>
				  
				  	<td><select name = "floor_name[]" id = "floor_name_{($smarty.section.foo.index+1)}" width="60px">
				  		<Option value="0">Select Floor Plan Name</Option>
				  	</select></td>
				  	<input type = "hidden" id = "uploaded_{($smarty.section.foo.index+1)}" value="{$uploadedStr[$smarty.section.foo.index]}">
				  	<input type = "hidden" id = "uploaded3D_{($smarty.section.foo.index+1)}" value="{$uploadedStr3D[$smarty.section.foo.index]}">

				  {else}
				  <td></td>
				  	<td><input type = "text" name = "floor_name[]"   {if $plot[$smarty.section.foo.index] == 'yes'} 
				  		{elseif $commercial[$smarty.section.foo.index] == 'yes'} {/if}>
				  	</td>
				  {/if}
				  
					
				  <td><input type = "file" name = "imgurl[]"></td> 

				</tr>   			  	         
				{/section}
				
				<tr class = "headingrowcolor">
				 
				  <td align="left"  colspan="6" >
				  
				  <input type="hidden" name="edit_projct" value="{$edit_projct}" />
				  <input type="submit" name="Next" id="more" value="Add More" style = "font-size:16px;">
				  {if $edit_projct ==''}
					 <input type="submit" name="btnSave" id="btnSave" value="Next">
					 <input type="submit" name="Skip" id="Skip" value="Skip">
				  {else}
					 <input type="submit" name="btnSave" id="btnSave" value="Submit">
				  {/if}
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
