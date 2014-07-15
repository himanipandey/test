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

$smarty->assign("agentArr", $agentArr);

$smarty->assign("sellerQualification", $sellerQualification);


?>