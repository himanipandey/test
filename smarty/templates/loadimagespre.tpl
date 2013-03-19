<table>
	<tr>
		<td width="20%" align="right" >PROJECTIMAGE : </td>
		<td width="30%" align="left">
		{$i=1}
		{section name=data loop=$ImageDataArr}
			{if $i==1}
				<div>
				<input type="checkbox" name="property_image_{$ImageDataArr[data].PROPERTY_ID}" >
				<img src="images/{$ImageDataArr[data].FOLDER_NAME}/{$ImageDataArr[data].PROPERTY_IMAGE}" height="100" width="100" />

				</div>
			{/if}
			{$i=$i+1}
		{/section}	
		</td>
		 <td width="20%" align="right" ><b>Image Type :</b><font color = "red">*</font></td>
				   <td width="30%" align="left" >
					<select name = "PType">
						
						<option value ='Project Image'>Project Image</option>
						
					</select>				  
				  </td>
	</tr>
		
	<tr>
		<td width="20%" align="right" >PLAN IMAGE : </td>
		
		<td width="30%" align="left">
		{section name=data loop=$ImageDataArr}
		
			
				<div>
				{$filename ="http://localhost/proptiger/admin_cms/offline-project/images/{$ImageDataArr[data].FOLDER_NAME}/{$ImageDataArr[data].PLAN_IMAGES}"}
				
				{$filenamenew=$filename|getimagesize}
				{$size=$filenamenew['0']}

				{if $size=='1'}
					<input type="checkbox" name="plan_image_{$ImageDataArr[data].IMAGE_ID}" >
					<img src="images/{$ImageDataArr[data].FOLDER_NAME}/{$ImageDataArr[data].PLAN_IMAGES}.JPG" height="100" width="100" />

				{else}
					<input type="checkbox" name="plan_image_{$ImageDataArr[data].IMAGE_ID}" >
					<img src="images/{$ImageDataArr[data].FOLDER_NAME}/{$ImageDataArr[data].PLAN_IMAGES}" height="100" width="100" />
					{/if}

					<select name = "PType_{$ImageDataArr[data].IMAGE_ID}">
											
						<option {if $ImageDataArr[data].PLAN_TYPE =='Location Plan'} selected="selected" {/if} value ='Location Plan'>Location Plan</option>
						<option  {if $ImageDataArr[data].PLAN_TYPE =='Floor Plan'} selected="selected" {/if} value ='Layout Plan'>Floor Plan</option>
						<option  {if $ImageDataArr[data].PLAN_TYPE =='Master Plan'} selected="selected" {/if} value ='Master Plan'>Master Plan</option>
						
					</select>	
				
				</div>
			
			
		{/section}	
		</td>
	</tr>
</table>