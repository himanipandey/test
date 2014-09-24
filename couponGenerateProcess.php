<?php 




$redeemHours = array();
for($i=1; $i<=120; $i++){
	array_push($redeemHours, $i);
}

$smarty->assign("redeemHours", $redeemHours);

$catalogue = array();
$query = "select * from coupon_catalogue";
$res = mysql_query($query) or die(mysql_error());
while($data = mysql_fetch_assoc($res)){
	array_push($catalogue, $data);
}

$smarty->assign("catalogue", $catalogue);

$smarty->assign('url', TYPEAHEAD_API_URL);



?>