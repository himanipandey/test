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
/*	
	$query = "select id, name from aliases where name like '%$q%' order by name LIMIT $limit";
	//echo $query;
	$sql_res=mysql_query($query);
	
	
	while($row=mysql_fetch_array($sql_res))
	{
		$data = array();
		$data['table'] = 'aliases';
		$data['id'] = $row['id'];
		
		$str = $row['name'];
		$str = (strlen($str) > 50) ? substr($str,0,10).'...' : $str;
		$data['name'] = $str;
		array_push($returnArr, $data);
		//echo json_encode($returnArr);


	}
*/	
	$query = "select id, concat(name, ' ', vicinity) as name from landmarks where name like '%$q%' and city_id={$city_id} order by name LIMIT $limit";
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
/*	$query = "select SUBURB_ID, LABEL from suburb where LABEL like '%$q%' order by LABEL LIMIT $limit";
	$sql_res=mysql_query($query);
	while($row=mysql_fetch_array($sql_res))
	{
		$data = array();
		$data['table'] = 'suburb';
		$data['id'] = $row['SUBURB_ID'];
		$str = $row['LABEL'];
		$str = (strlen($str) > 50) ? substr($str,0,10).'...' : $str;
		$data['name'] = $str;
		array_push($returnArr, $data);

	}
*/
	echo json_encode($returnArr);
	//echo "{'results':".json_encode($returnArr)."}";
    //echo "{query:"e", suggestions:["apple","coffee","delhi","delhi 3","delhi1","delhi4","delhi5","delhi6","delhi7","hello","hello1","hello2"], data:["4","6","9","11","10","12","13","14","15","1","2","3"]}";

//}
?>