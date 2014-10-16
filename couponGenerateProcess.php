<?php 




$redeemHours = array();
for($i=1; $i<=120; $i++){
	array_push($redeemHours, $i);
}

$smarty->assign("redeemHours", $redeemHours);

$catalogue = array();
$query = "select cc.*, rp.PROJECT_ID, rp.PROJECT_NAME, rp.PROJECT_ADDRESS, rb.BUILDER_NAME, rpo.OPTION_NAME, rpo.SIZE from coupon_catalogue cc inner join resi_project_options rpo on rpo.options_id=cc.option_id 
			inner join resi_project rp on rp.project_id=rpo.project_id 
			inner join resi_builder rb on rb.builder_id=rp.builder_id
			group by cc.option_id order by cc.id desc";
$res = mysql_query($query) or die(mysql_error());
while($data = mysql_fetch_assoc($res)){
	$data['coupon_price'] = moneyFormatIndia($data['coupon_price']);
	if($data['discount_type']=='SqFt'){
		
		$data['discount'] = moneyFormatIndia($data['discount']/$data['SIZE']);
	}
	else
	$data['discount'] = moneyFormatIndia($data['discount']);
	array_push($catalogue, $data);
}
//echo moneyFormatIndia(23301);
$smarty->assign("catalogue", $catalogue);

$smarty->assign('url', TYPEAHEAD_API_URL);


function moneyFormatIndia($n){
	
	list($n,$dec)	=	 explode('.',$n);
	$len			=	 strlen($n); //lenght of the no
	if($len<3)
		$num			=	 substr($n,0,3); //get the last 3 digits
	else
		$num			=	 substr($n,$len-3,3); //get the last 3 digits
	$n				=	 floor($n/1000); //omit the last 3 digits already stored in $num
	while($n > 0) //loop the process - further get digits 2 by 2
	{
		$len =	 strlen($n);
		$num =	 substr($n,$len-2,2).",".$num;
		$n	 =	 floor($n/100);
	}
	return $num;

}
?>