<?php

$compid= $_REQUEST['compid'];

$typeArr = Company::getCompanyType(); 
$smarty->assign("comptype", $typeArr);

$cityArray = City::CityArr();
$smarty->assign("cityArray", $cityArray);

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

$sql = "select ADMINID, FNAME, LNAME from proptiger.PROPTIGER_ADMIN where DEPARTMENT='SALES'";
$res = mysql_query($sql);
$ptRelManager = array();
while($data = mysql_fetch_assoc($res)){
    $ptRelManager[$data['ADMINID']] = $data['FNAME']." ".$data['LNAME'];
}
$smarty->assign('ptRelManager', $ptRelManager);

$smarty->assign('url', TYPEAHEAD_API_URL);




/*

//get company logo
foreach ($compArr as $k => $v) {
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

}
*/
$smarty->assign("compArr", $compArr);

print("<pre>");
print_r($compArr);
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
