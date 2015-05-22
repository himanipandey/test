<?php
//calling function for all the cities
include("appWideConfig.php");
include("dbConfig.php");
include("modelsConfig.php");
include("listing_function.php");
//die("here");
include("function/functions_listing.php");
include("httpful.phar");

$page =  $_REQUEST['page'];
$size = $_REQUEST['size'];
//$size = 1;
$start = $page*$size;

$name = $_REQUEST['name'];
//$name = "mmp";
$compType = $_REQUEST['compType'];
$compType = str_replace(' ', '', $compType);
//$compType="brok";
//$compType = "";
$typeArr = Company::getCompanyType(); //var_dump($typeArr);
foreach ($typeArr as $key => $value) {
    if (strpos(strtolower($value),$compType) !== false) {
        $compType = $value;
    }
}
$status = $_REQUEST['status'];
$status = str_replace(' ', '', $status);
//$status = "inacti";
$statusArr = array("Active", "Inactive");
foreach ($statusArr as $key => $value) {
    if (strpos(strtolower($value), $status) !== false) {
        $status = $value;
    }
}

$compid= $_REQUEST['compid'];

$tbsorterArr = array();

$tbsorterArr['serialNo'] = $page*$size+1;
$tbsorterArr['headers'] = array("Serial", "Type", "Name", "Address", "Contact Person", "Status", "Edit");
            
$tbsorterArr['rows'] = array();

$conditionStr = "";
$conditionStr .= $status == '' ? '' : " status = '$status' and ";
$conditionStr .= $name == '' ? '' : " name like '%{$name}%' and ";
$conditionStr .= $compType == '' ? '' : " type = '$compType' and ";
$conditionStr .= $compid == '' ? '' : " id = '$compid' and ";
$conditionStr = substr($conditionStr, 0, -4);
//var_dump($compid);

if($status != '' || $name != '' || $compType != ''){
	//$start = 0;
}


$companyCount = Company::find('all', array('conditions'=>array($conditionStr)));
$totalCount = count($companyCount);
$tbsorterArr['total_rows'] = $totalCount;

$companyDetail = Company::find('all', array('conditions'=>array($conditionStr), 'limit'=>$size, 'offset'=>$start ));
$returnArr = Company::getCompanyOtherDetails($companyDetail);
$tbsorterArr['rows'] = $returnArr['table_rows'];

echo json_encode($tbsorterArr);

 ?>