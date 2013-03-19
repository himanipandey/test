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
		
		{$c=0}
		{$n=3}
		{section name=data loop=$ImageDataArr}
		{if ($c % $n == 0)}
			{if $c != 0}
		</tr><tr>
			{/if}
		{/if}
		{$c=$c+1}
			<td width="30%" align="left">
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
						<option value =''>Select Type</option>
						<option value ='Project Image'>Project Image</option>
						<option value ='Location Plan'>Location Plan</option>
						<option value ='Layout Plan'>Layout Plan</option>
						<option value ='Site Plan'>Site Plan</option>
						<option value ='Master Plan'>Master Plan</option>
						<option value ='Cluster Plan'>Cluster Plan</option>
						<option value ='Construction Status'>Construction Status</option>
						<option value ='Payment Plan'>Payment Plan</option>
					</select>	
				</td>
				</div>
			
			
		{/section}	
		
	</tr>
</table>