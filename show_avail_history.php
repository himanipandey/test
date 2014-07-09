<?php
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
include("modelsConfig.php");

$lid = mysql_real_escape_string($_REQUEST['lid']);

$sql = "select rpo.option_type,l.phase_id,pa.project_supply_id,pa.effective_month,pa.availability,ps.listing_id,ps.version 
      from project_supplies ps 
                inner join listings l on ps.listing_id = l.id
                inner join resi_project_options rpo on l.option_id = rpo.options_id
                inner join  project_availabilities pa on pa.project_supply_id = ps.id
                where 
                listing_id = '$lid' and l.status = 'Active' ";

//print $sql;

$result = mysql_query($sql);

if($result){
	$output = array();
	$dateArr = array();
	
	while($row = mysql_fetch_object($result)){		
		if($row->version == 'PreCms'){
			$output['PreCms'][$row->effective_month] = $row->availability;
			$dateArr[] = $row->effective_month;
		}
		if($row->version == 'Cms'){
			$output['Cms'][$row->effective_month] = $row->availability;
		}
			
	}	
}

//print "<pre>".print_r($output,1)."</pre>";
?>
<br/>
&nbsp;&nbsp;<h2>Inventory History For Listing : <?php print $lid ?></h2>
<link href="css/css.css" rel="stylesheet" type="text/css">
				<TABLE cellSpacing=1 cellPadding=4 width="97%" align=center border=0 style="text-align:center">
					<TBODY>
						 <TR class = "headingrowcolor">
								<TH class=whiteTxt width=1% align="center">SL</TH>
								<TH class=whiteTxt width=5% align="center">Version</TH>                          
								<?php foreach($dateArr as $key=>$val): ?>
									<TH class=whiteTxt width=5% align="center"><?php print $val ?></TH>
								<?php endforeach ?>
						  </TR>
						  
						  <?php if($output){
							 $count = 0;
						     foreach($output as $ver=>$avails){
								$count = $count+1;
								if ($count%2 == 0){
								  $color = "bgcolor = '#F7F7F7'";
								}else{                       			
								  $color = "bgcolor = '#FCFCFC'";
								}
								print '<TR '.$color.'>';
								print '<TD>'.$count .'</TD>';
								print '<TD>'.$ver.'</TD>';								
								foreach($dateArr as $key=>$val){									
									print '<TD>'.$avails[$val].'</TD>';							
								}
								print '</TR>';
							 }
						   } ?>
				    </TBODY>
				 </TABLE>










