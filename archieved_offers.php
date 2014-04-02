<?php
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
include("modelsConfig.php");

global $arrOfferTypes;

$projectId			=	$_REQUEST['projectId'];
$offerDetails = ProjectOffers::find('all',array('conditions'=>array('project_id'=>$projectId)));
?>
<link href="css/css.css" rel="stylesheet" type="text/css">
				<TABLE cellSpacing=1 cellPadding=4 width="97%" align=center border=0>
					<TBODY>
						 <TR class = "headingrowcolor">
								<TH class=whiteTxt width=1% align="center">SL</TH>
								 <TH class=whiteTxt width=5% align="center">Offer Type</TH>                          
								<TH class=whiteTxt width=23% align="left">Offer Desc</TH>
								<TH class=whiteTxt width=5% align="left">Start Date</TH>
								<TH class=whiteTxt width=5% align="left">End Date</TH>
								<TH class=whiteTxt width=5% align="center">Status</TH>
						  </TR>
						  <?php if($offerDetails){
							  $count = 0;
							foreach ($offerDetails as $key=>$data){
								$count = $count+1;
								if ($count%2 == 0){
								  $color = "bgcolor = '#F7F7F7'";
								}else{                       			
								  $color = "bgcolor = '#FCFCFC'";
								}
							 print '<TR '.$color.'>
									<TD>'.$count.'</TD>
									<TD>'.$arrOfferTypes[$data->offer].'</TD>
									<TD>'.$data->offer_desc.'</TD>
									<TD>'.date('d-m-Y',strtotime(trim(substr($data->created_at,0,11)))).'</TD>
									<TD>'.date('d-m-Y',strtotime(trim(substr($data->offer_end_date,0,11)))).'</TD>
									<TD>'.$data->status.'</TD>
								</TR>';
							}
						 }else{
						   print '<tr>
							<td colspan=4>No Record Found.</td>
						   </tr>';
						} ?>
					</TBODY>
				</TABLE>
