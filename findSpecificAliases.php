<?php
error_reporting(1);
	ini_set('display_errors','1');
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
    include("modelsConfig.php");
	include("builder_function.php");
	AdminAuthentication();
//if($_GET['search'])
//{

	
	//$q=$_GET['query'];
	$city_id = $_GET['cityId'];
	$q=$_GET['name_startsWith'];
	$limit = $_GET['maxRows'];
	$returnArr = array();
	if($city_id){
		$query = "select id, concat(name, ' ', vicinity) as name from landmarks where name like '%$q%' and city_id={$city_id} order by name LIMIT $limit";
	}
	else	
		$query = "select id, concat(name, ' ', vicinity) as name from landmarks where name like '%$q%' order by name LIMIT $limit";
	//echo $query;
	$sql_res=mysql_query($query);
	while($row=mysql_fetch_array($sql_res))
	{
		$data = array();
		$data['table'] = 'landmarks';
		$data['id'] = $row['id'];
		$str = $row['name'];
		//$str = (strlen($str) > 50) ? substr($str,0,10).'...' : $str;
		$data['name'] = $str;
		array_push($returnArr, $data);

	}

	echo json_encode($returnArr);
	
?>