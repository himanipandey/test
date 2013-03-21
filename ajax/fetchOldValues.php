<?php
	include("../dbConfig.php");
	include("../appWideConfig.php");
	include("../builder_function.php");
	
	$arrTableName = array("resi_project"=>"Project","resi_project_options"=>"project Configuration","resi_proj_supply"=>"Project Supply");
	$stageName	=	$_REQUEST['stageName'];
	$phasename	=	$_REQUEST['phasename'];
	$projectId	=	$_REQUEST['projectId'];
	$phaseId    =	$_REQUEST['phaseId'];
	
	//$endtime   = '2013-03-16';
	$changedValueArr = fetchColumnChanges($projectId, $stageName, $phasename, $phaseId);
	
	//echo "<pre>";
	//print_r($changedValueArr);
	//echo "</pre>";
?>
	<table style = "border:1px solid #c2c2c2;" align = "left" width ="60%"> 
	<?php 
	foreach($changedValueArr as $keyTbl=>$val)
	{
	?>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td align ="left" nowrap><b>Old Value for <?php echo $arrTableName[$keyTbl];  ?></b></td>
		</tr>
		
		<tr class="headingrowcolor" height="30px;">
			 <td  nowrap="nowrap" width="10%" align="left" class=whiteTxt>Field Name</td>
			 <td nowrap="nowrap" width="25%" align="left" class=whiteTxt>New Value</td>
			 <td nowrap="nowrap" width="25%" align="left" class=whiteTxt>Old Value</td>	
		</tr>
	
	<?php 
		$cnt =0;
		foreach($val as $fieldName=>$values)
		{
			if(($cnt+1)%2 == 0)
			{
				$color = "bgcolor='#F7F8E0'";
			}
			else
				$color = "bgcolor='#f2f2f2'";
			
		?>
			<tr <?php  echo $color; ?> height="25px;">
				<td width="10%" align="left"><?php echo ucwords(strtolower(str_replace("_"," ",$fieldName)));?></td>
				<td width="10%" align="left">
					<?php 
						if($values['new'] != '')
							echo $values['new']; 
						else
							echo "--";
					?>
				</td>
				<td width="10%" align="left">
					<?php 
						if($values['old'] != '')
							echo $values['old']; 
						else
							echo "--";
					?>
				</td>
			</tr>
		<?php 
			$cnt++;
		}
	}
?>
	</table>