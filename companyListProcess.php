<?php

$compid= $_REQUEST['compid'];

$typeArr = Company::getCompanyType(); 
$smarty->assign("comptype", $typeArr);

$cityArray = City::CityArr();
$smarty->assign("cityArray", $cityArray);

if($compid){
	$compArr = Company::getAllCompany($compid);
}
else{
	$compArr = Company::getAllCompany();
}


///*

//get logo
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
//*/
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
