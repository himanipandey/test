

Check/Uncheck all: <input type='checkbox'  id="hdnCheckUncheck" value='0' name='checkall' onclick='checkednewAll();'>
<br>
<form name="f1" id="f1" method="post" >
<table cellpadding=0 cellspacing=1>
<tr><td>
<table  cellpadding=0 cellspacing=1 bgcolor='#c2c2c2'>
<tr bgcolor='#ffffff'>
		<td>PROJECT IMAGE:</td>
{$cnt = 0}
		{section name=data loop=$ImageDataListingArr}
			
				<td class = "tdcls_{$cnt}" >
				<div  style="border:1px solid #c2c2c2;padding:4px;margin:4px;">
				<input type="checkbox" name="chk_name[]" id = "chk_{$cnt}" >
				<img src="images/{$ImageDataListingArr[data].FOLDER_NAME}/{$ImageDataListingArr[data].PROPERTY_IMAGE}" height="100" width="100" />

				<a class="pt_reqflrplan" href="images/{$ImageDataListingArr[data].FOLDER_NAME}/{$ImageDataListingArr[data].PROPERTY_IMAGE}" target="_blank"><br />View Big Image</a>
				

				 <select name = "property_type[]" STYLE="width: 200px;border:1px solid #c3c3c3;">
							
							
							 	<option  value ='Project Image' selected="selected"  >Project Image	</option>
								<option  value ='Master Plan'   >Master Plan	</option>
								<option  value ='Location Plan'   >Location Plan	</option>
								<option  value ='Layout Plan'   >Layout Plan	</option>
								<option  value ='Site Plan'   >Site Plan	</option>
								<option  value ='Cluster Plan'   >Cluster Plan	</option>
								

							

						</select>	

				<input type="hidden" value="images/{$ImageDataListingArr[data].FOLDER_NAME}/{$ImageDataListingArr[data].PROPERTY_IMAGE}" name="property_image_path[]" />
				Title:<input type="text" value="" name="property_title[]" />


				</div>
				</td>
		{$cnt = $cnt+1} 		
		{/section}
</tr>




</table>
</td></tr>
<tr><td>
{if count($ImageDataFloorArr)}
<table  cellpadding=0 cellspacing=1 bgcolor='#c2c2c2'>
<tr bgcolor='#ffffff'>


<td>FLOOR IMAGES:</td>
<td><table><tr>	
		{$cn = 0}
		
		{section name=data loop=$ImageDataFloorArr}	
		{if $cn%4==0}
		</tr><tr>
		{/if}
		<td class = "tdcls_{$cnt}">
		
				<div style="border:1px solid #c2c2c2;padding:4px;margin:4px;">
				{$filename ="{$OFFLINE_PROJECT_INTERNET_PATH}/admin_cms/offlineproject/images/{$ImageDataFloorArr[data].FOLDER_NAME}/{$ImageDataFloorArr[data].FLOOR_IMAGE}"}
				
				{$filenamenew=$filename|getimagesize}
				{$size=$filenamenew['0']}
				
				{if $size=='1'}
					<input type="checkbox" name="chk_name[]" id = "chk_{$cnt}">
					<img src="images/{$ImageDataFloorArr[data].FOLDER_NAME}/{$ImageDataFloorArr[data].FLOOR_IMAGE}.JPG" height="120" width="100" />
					<a class="pt_reqflrplan" href="images/{$ImageDataFloorArr[data].FOLDER_NAME}/{$ImageDataFloorArr[data].FLOOR_IMAGE}.JPG" target="_blank"><br />View Big Image</a>

					
					 <select name = "property_type[]" STYLE="width: 200px;border:1px solid #c3c3c3;">
							
							
							 	<option  value ='Project Image'>Project Image	</option>
								<option  value ='Master Plan' {if $ImageDataFloorArr[data].PLAN_TYPE =='Master Plan'}  selected="selected" {/if}  >Master Plan	</option>
								<option  value ='Location Plan' >Location Plan	</option>
								<option  value ='Layout Plan'>Layout Plan	</option>
								<option  value ='Site Plan'>Site Plan	</option>
								<option  value ='Cluster Plan'>Cluster Plan	</option>
								
								<option  value ='Floor Plan' {if $ImageDataFloorArr[data].PLAN_TYPE =='Floor Plan'}  selected="selected" {/if}>Floor Plan</option>

							

						</select>
						


					<input type="hidden" value="images/{$ImageDataFloorArr[data].FOLDER_NAME}/{$ImageDataFloorArr[data].FLOOR_IMAGE}.JPG" name="property_image_path[]" />
					Title:<input type="text" value="" name="property_title[]" />
					 
					{if $ImageDataFloorArr[data].PLAN_TYPE =='Floor Plan'}
					Project Type:
					 <select name = "projectTypeId[]" STYLE="width: 200px;border:1px solid #c3c3c3;">
							<option value =''>Select Type</option>
							 {section name=data1 loop=$Project}
							 	<option  value ='{$Project[data1].TYPE_ID}'  >
							 	{$Project[data1].BUILDER_NAME},
							 	{$Project[data1].PROJECT_NAME},
							 	{$Project[data1].UNIT_NAME},
							 	{$Project[data1].UNIT_TYPE},
							 	{$Project[data1].SIZE},
							 	{$Project[data1].MEASURE},
							 	{$Project[data1].PRICE_PER_UNIT_AREA}
							 	
							 	</option>
							 {/section}	

						</select>	
				  
					{/if}


				{else}
					<input type="checkbox" name="chk_name[]" id = "chk_{$cnt}">
					<img src="images/{$ImageDataFloorArr[data].FOLDER_NAME}/{$ImageDataFloorArr[data].FLOOR_IMAGE}" height="100" width="100" />
					<a class="pt_reqflrplan" href="images/{$ImageDataFloorArr[data].FOLDER_NAME}/{$ImageDataFloorArr[data].FLOOR_IMAGE}" target="_blank"><br />View Big Image</a>


					 <select name = "property_type[]" STYLE="width: 200px;border:1px solid #c3c3c3;">
							
							
							 	<option  value ='Project Image'>Project Image	</option>
								<option  value ='Master Plan' {if $ImageDataFloorArr[data].PLAN_TYPE =='Master Plan'}  selected="selected" {/if}  >Master Plan	</option>
								<option  value ='Location Plan' >Location Plan	</option>
								<option  value ='Layout Plan'>Layout Plan	</option>
								<option  value ='Site Plan'>Site Plan	</option>
								<option  value ='Cluster Plan'>Cluster Plan	</option>
								<option  value ='Floor Plan' {if $ImageDataFloorArr[data].PLAN_TYPE =='Floor Plan'}  selected="selected" {/if}>Floor Plan</option>

							

						</select>
					
					
					
					<input type="hidden" value="images/{$ImageDataFloorArr[data].FOLDER_NAME}/{$ImageDataFloorArr[data].FLOOR_IMAGE}" name="property_image_path[]" />
					Title:<input type="text" value="" name="property_title[]" />

					
					{if $ImageDataFloorArr[data].PLAN_TYPE =='Floor Plan'}
					Project Type:
					 <select name = "projectTypeId[]" STYLE="width: 200px;border:1px solid #c3c3c3;">
							<option value =''>Select Type</option>
							 {section name=data1 loop=$Project}
							 	<option value ='{$Project[data1].TYPE_ID}' >
							 	{$Project[data1].BUILDER_NAME},
							 	{$Project[data1].PROJECT_NAME},
							 	{$Project[data1].UNIT_NAME},
							 	{$Project[data1].UNIT_TYPE},
							 	{$Project[data1].SIZE},
							 	{$Project[data1].MEASURE},
							 	{$Project[data1].PRICE_PER_UNIT_AREA}
							 	
							 	</option>
							 {/section}	

						</select>	
				  
					{/if}

					{/if}

					
				
				</div>
			
			</td>
			{$cnt = $cnt+1}
			{$cn = $cn + 1}
		{/section}	
</tr></table>
</td>		
</tr>




</table>
{/if}
</td></tr>
<tr><td>
{if count($ImageDataLocationArr)}
<table  cellpadding=0 cellspacing=1 bgcolor='#c2c2c2'>
<tr bgcolor='#ffffff'>


<td>LOCATION IMAGES:</td>

		
		{section name=data loop=$ImageDataLocationArr}
			
<td class = "tdcls_{$cnt}">
			
		
			
				<div style="border:1px solid #c2c2c2;padding:4px;margin:4px;">
								
			{$filenames ="{$OFFLINE_PROJECT_INTERNET_PATH}/admin_cms/offlineproject/images/{$ImageDataLocationArr[data].FOLDER_NAME}/{$ImageDataLocationArr[data].LOCATION_IMAGE}.JPG"}
				
				{$filenames2 ="{$OFFLINE_PROJECT_INTERNET_PATH}/admin_cms/offlineproject/images/{$ImageDataLocationArr[data].FOLDER_NAME}/{$ImageDataLocationArr[data].LOCATION_IMAGE}.GIF"}
				{$filenamenews2 = $filenames2|getimagesize}
				{$size2=$filenamenews2['0']}

				{$filenamenews = $filenames|getimagesize}
				
				{$size1=$filenamenews['0']}
				
				{if $size1==1 && $size2!=1}
				
					<input type="checkbox" name="chk_name[]" id = "chk_{$cnt}">
					<img src="images/{$ImageDataLocationArr[data].FOLDER_NAME}/{$ImageDataLocationArr[data].LOCATION_IMAGE}.GIF" height="100" width="100" />
					<a class="pt_reqflrplan" href="images/{$ImageDataLocationArr[data].FOLDER_NAME}/{$ImageDataLocationArr[data].LOCATION_IMAGE}.GIF" target="_blank"><br />View Big Image</a>
					 

						 <select name = "property_type[]" STYLE="width: 200px;border:1px solid #c3c3c3;">
							
							
							 	<option  value ='Project Image'>Project Image	</option>
								<option  value ='Master Plan'   >Master Plan	</option>
								<option  value ='Location Plan'  selected="selected"  >Location Plan	</option>
								<option  value ='Layout Plan'>Layout Plan	</option>
								<option  value ='Site Plan'>Site Plan	</option>
								<option  value ='Cluster Plan'>Cluster Plan	</option>
								<option  value ='Floor Plan'>Floor Plan</option>

							

						</select>

					<input type="hidden" value="images/{$ImageDataLocationArr[data].FOLDER_NAME}/{$ImageDataLocationArr[data].LOCATION_IMAGE}.GIF" name="property_image_path[]" />
					Title:<input type="text" value="" name="property_title[]" />


				{else if $size2==1 && $size1!=1}
					<input type="checkbox" name="chk_name[]" id = "chk_{$cnt}">
					<img src="images/{$ImageDataLocationArr[data].FOLDER_NAME}/{$ImageDataLocationArr[data].LOCATION_IMAGE}.JPG" height="100" width="100" />
					<a class="pt_reqflrplan" href="images/{$ImageDataLocationArr[data].FOLDER_NAME}/{$ImageDataLocationArr[data].LOCATION_IMAGE}.JPG" target="_blank"><br />View Big Image</a>

					 <select name = "property_type[]" STYLE="width: 200px;border:1px solid #c3c3c3;">
							
							
							 	<option  value ='Project Image'>Project Image	</option>
								<option  value ='Master Plan'   >Master Plan	</option>
								<option  value ='Location Plan'  selected="selected"  >Location Plan	</option>
								<option  value ='Layout Plan'>Layout Plan	</option>
								<option  value ='Site Plan'>Site Plan	</option>
								<option  value ='Cluster Plan'>Cluster Plan	</option>
								<option  value ='Floor Plan'>Floor Plan</option>

							

						</select>
					<input type="hidden" value="images/{$ImageDataLocationArr[data].FOLDER_NAME}/{$ImageDataLocationArr[data].LOCATION_IMAGE}.JPG" name="property_image_path[]" />
					Title:<input type="text" value="" name="property_title[]" />


				{else}

				<input type="checkbox" name="chk_name[]"  id = "chk_{$cnt}">
					<img src="images/{$ImageDataLocationArr[data].FOLDER_NAME}/{$ImageDataLocationArr[data].LOCATION_IMAGE}" height="100" width="100" />
					<a class="pt_reqflrplan" href="images/{$ImageDataLocationArr[data].FOLDER_NAME}/{$ImageDataLocationArr[data].LOCATION_IMAGE}" target="_blank"><br />View Big Image</a>
					 <select name = "property_type[]" STYLE="width: 200px;border:1px solid #c3c3c3;">
							
							
							 	<option  value ='Project Image'>Project Image	</option>
								<option  value ='Master Plan'   >Master Plan	</option>
								<option  value ='Location Plan'  selected="selected"  >Location Plan	</option>
								<option  value ='Layout Plan'>Layout Plan	</option>
								<option  value ='Site Plan'>Site Plan	</option>
								<option  value ='Cluster Plan'>Cluster Plan	</option>
								<option  value ='Floor Plan'>Floor Plan</option>

							

						</select>

					<input type="hidden" value="images/{$ImageDataLocationArr[data].FOLDER_NAME}/{$ImageDataLocationArr[data].LOCATION_IMAGE}" name="property_image_path[]" />
					Title:<input type="text" value="" name="property_title[]" />
					{/if}

					
				
				</div>
			
			</td>
			 {$cnt = $cnt+1}
		{/section}	

		
</tr>





</table>

{/if}
{$cntt=0}
{if count($newImagesArr[0])}

<!--Check/Uncheck all: <input type='checkbox'  id="hdnCheckUncheck2" value='0' name='checkall' onclick='checkedAll();'>-->
<br />PROPTIGER IMAGES<br />
<table>
	<tr>
		
		{foreach item=value from=$newImagesArr[0]}
			<td class = "tdprocls_{$cntt}" >
			Delete<input type="checkbox" name="chk_propImage[]" id = "chkk_{$cntt}">
			
			<img src="http://localhost/proptiger/images/{$value.PLAN_IMAGE}"  height='100' width='100'/>
			{$value.PLAN_TYPE}
			
			<input type="hidden" value="{$value.PROJECT_PLAN_ID}" name="proptiger_planId[]" /></td>
			{$cntt=$cntt+1}
		{/foreach}
	</tr>
	{$cnt=0}

	<tr>
	{$cntt=$cntt}
	{if count($newImagesArr[1])}
		{foreach item=value from=$newImagesArr[1]}
		{if $cn%2==0}
		</tr><tr>
		{/if}
			<td class = "tdprocls_{$cntt}" >
			Delete<input type="checkbox" name="chk_propImage[]" id = "chkk_{$cntt}">
			
			<img src="http://localhost/proptiger/images/{$value.IMAGE_URL}"  height='100' width='100'/>
			Floor Plan
			<input type="hidden" value="{$value.FLOOR_PLAN_ID}" name="proptiger_floorplanId[]" /></td>

			{$cn = $cn + 1}
			{$cntt=$cntt+1}
		{/foreach}
		{/if}
	</tr>

</table>
{/if}
</td>
</tr>
<tr >
				  {if proptigerID!=0}
				   <input type="hidden" name="proptigerID" value="{$proptigerID}" />

				  {/if}
				  <td colspan = "2" align="right" style="padding-left:152px;" >
				  <span id = 'rowcount' style = 'display:none;'>{$count}</span>
				   <span id = 'rowPropCount' style = 'display:none;'>{$countPropImages}</span>
				  
				  <input type="hidden" name="projectId" value="{$project_id}" />
				  <input type="hidden" name="proptigerID" value="{$proptigerID}" />
				  <input type="submit" name="btnSave" id="btnSave" value="Save" style = "font-size:16px;"  onclick="return check();">
				  &nbsp;&nbsp;<input type="submit" name="btnExit" id="btnExit" value="Exit" style = "font-size:16px;">
				  </td>
				</tr>
</table>
</form>
 <script language='javascript'>
    
      function checkednewAll() {

		var rowCount = document.getElementById("rowcount").innerHTML;
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


  function checkedAll() {

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
	   alert ("Please check at least one checkbox from Offline data.");
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

jQuery(".pt_reqflrplan").fancybox();
    </script>