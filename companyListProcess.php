<?php
//print("<pre>");
$compid= $_REQUEST['compid'];

$typeArr = Company::getCompanyType(); 
$smarty->assign("comptype", $typeArr);

$cityArray = City::CityArr();
$smarty->assign("cityArray", $cityArray);

$res = City::find('all', array('conditions'=>array("IS_SERVING = '1' and status = 'Active'" ) ));
$ptBranchArray = array();
foreach($res as $value) {
    $ptBranchArray[$value->city_id] = $value->label;
}
$smarty->assign("ptBranchArray", $ptBranchArray);

if($compid){
	$compArr = Company::getAllCompany($arr=array('id'=>$compid));
}
else{
	$compArr = Company::getAllCompany();
}

$resiProjectType = BrokerPropertyType::PropertyTypeArr();
$smarty->assign('resiProjectType', $resiProjectType);

$transactionType = TransactionType::TransactionTypeArr();
$smarty->assign('transactionType', $transactionType);

$devices = Devices::getAllDevices();
$smarty->assign('devices', $devices);

$bankArray = BankList::arrBank();
$smarty->assign("bankArray",$bankArray);

$sql = "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'bank_details' AND COLUMN_NAME = 'account_type'";
$res = mysql_query($sql);
$bankAccountType = array();
while($data = mysql_fetch_assoc($res)){
    preg_match_all("/'([\w ]*)'/", $data['COLUMN_TYPE'], $values);
}
$smarty->assign('bankAccountType', $values[1]);








//print_r($transactionType);

$sql = "select ADMINID, FNAME, LNAME from proptiger.PROPTIGER_ADMIN where DEPARTMENT='SALES' order by FNAME ASC, LNAME ASC";
$res = mysql_query($sql);
$ptRelManager = array();
while($data = mysql_fetch_assoc($res)){
    $ptRelManager[$data['ADMINID']] = $data['FNAME']." ".$data['LNAME'];
}
$smarty->assign('ptRelManager', $ptRelManager);

$sql = "select ADMINID, FNAME, LNAME, DEPARTMENT from proptiger.PROPTIGER_ADMIN order by FNAME ASC, LNAME ASC, DEPARTMENT ASC";
$res = mysql_query($sql);
$ptRelative = array();
while($data = mysql_fetch_assoc($res)){
    $ptRelative[$data['ADMINID']] = $data['FNAME']." ".$data['LNAME']."     (".$data['DEPARTMENT'].")";
}
$smarty->assign('ptRelative', $ptRelative);


$smarty->assign('url', TYPEAHEAD_API_URL);

$namearr = array();
$namearr = Company::getCompanyNameByQuery('br');
//print_r($namearr);



//get company logo
/*foreach ($compArr as $k => $v) {
	# code...

	$objectId = $v['id'];
	$objectType = "company";
    //$url = readFromImageService($objectType, $objectId);
    //print ""
    $url = ImageServiceUpload::$image_upload_url."?objectType=$objectType&objectId=".$objectId;
    $content = file_get_contents($url);
    $imgPath = json_decode($content);
    $data = array();
    foreach($imgPath->data as $k1=>$v1){
        
        $compArr[$k]['service_image_path'] = $v1->absolutePath;
        $compArr[$k]['alt_text'] = $v1->altText;
        $compArr[$k]['image_id'] = $v1->id;
    }

}*/

$smarty->assign("compArr", $compArr);

//print("<pre>");
//print_r($compArr);
//$co = 
/*
$builderList = ResiBuilder::ProjectSearchBuilderEntityArr();
$smarty->assign("builderList", $builderList);



$arrSearchFields = array();
if( $_REQUEST['builder'] != '' ) 
      $arrSearchFields['builder_id'] = $_REQUEST['builder'];
$getSearchResult = ResiProject::getAllSearchResult($arrSearchFields);

*/
?>
