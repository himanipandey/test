<?php
//calling function for all the cities
include("appWideConfig.php");
include("dbConfig.php");

$page =  $_REQUEST['page'];
$limit = $_REQUEST['size'];
$offset = $page*$limit;
$tbsorterArr = array();
$tbsorterArr['total_rows'] = 1000;
$tbsorterArr['headers'] = array("Serial", "City", "Broker-Name", "Project", "Listing", "Price", "Save");
$tbsorterArr['rows'] = array();

$sql= "select * from city limit {$limit} offset {$offset} ";

$r = mysql_query($sql) or die(mysql_error());
while($d = mysql_fetch_assoc($r)){

$rows = array(
                                "Serial" => 1,
                                "City" => $d['LABEL'],
                                "BrokerName" => "AFG",
                                "Project" => "Kabol",
                                "Listing" => 1780000,
                                "Price" => 100,
                                "Save" => "save"
                                );


array_push($tbsorterArr['rows'], $rows);
}

echo json_encode($tbsorterArr);
// echo '{
//   "total_rows": 2,

//   "headers" : [
//     "ID", "Name", "Country Code", "District", "Population"
//   ],

//   "rows" : [{
//     "ID": 1,
//     "Name": "Kabul",
//     "CountryCode": "AFG",
//     "District": "Kabol",
//     "Population": 1780000
//   }, {
//     "ID": 2,
//     "Name": "Qandahar",
//     "CountryCode": "AFG",
//     "District": "Qandahar",
//     "Population": 237500
//   }]
// }'


?>