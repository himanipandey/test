<?php 


$agentId= $_REQUEST['agent_id'];

$brokerArr = Company::getAllCompany($arr=array('type'=>"Broker")); 
$smarty->assign("brokerArr", $brokerArr);

$adressArr = array();
foreach ($brokerArr as $k => $v) {
	$tmpArr = array('id'=>$v['id'], 'data'=> array($v['address'], $v['city'], $v['pin'], $v['compphone'] ));
	array_push($adressArr, $tmpArr);
}
$json = json_encode($adressArr); 
$smarty->assign("adressArr", $json);

$cityArray = City::CityArr();
$smarty->assign("cityArray", $cityArray);

if($agentId){
	$agentArr = BrokerAgent::getAllBrokerAgents($agentId);
}
else{
	$agentArr = BrokerAgent::getAllBrokerAgents();
}
//print("<pre>");print_r($agentArr);
$smarty->assign("agentArr", $agentArr);

$sellerQualification = array();
$query = "select * from academic_qualifications";
$res = mysql_query($query);
while($data=mysql_fetch_assoc($res)){

	$sellerQualification[$data['id']] = $data['qualification'];
}

$smarty->assign("sellerQualification", $sellerQualification);


?>