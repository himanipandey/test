<?php 




$redeemHours = array();
for($i=1; $i<=120; $i++){
	array_push($redeemHours, $i);
}

$smarty->assign("redeemHours", $redeemHours);

$catalogue = array();
$query = "select cc.*, rp.PROJECT_ID, rp.PROJECT_NAME, rpo.OPTION_NAME, rpo.SIZE from coupon_catalogue cc inner join resi_project_options rpo on rpo.options_id=cc.option_id 
			inner join resi_project rp on rp.project_id=rpo.project_id group by cc.option_id";
$res = mysql_query($query) or die(mysql_error());
while($data = mysql_fetch_assoc($res)){
	array_push($catalogue, $data);
}

$smarty->assign("catalogue", $catalogue);

$smarty->assign('url', TYPEAHEAD_API_URL);



?>