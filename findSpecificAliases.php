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
	$placeType = $_GET['placeType'];
	$placeTypeId = 0;
	$limit = $_GET['maxRows'];
	$returnArr = array();


	if($placeType){
		$query1 = "select id, name from landlmark_types where name like '%$placeType%'";
		$sql_res1=mysql_query($query1);
		while($row=mysql_fetch_array($sql_res1))
		{
			$placeTypeId = $row['id'];
		}
	}	


	$where = "";
	if($city_id) $where .= " and city_id={$city_id}  ";
	if($placeTypeId>0) $where .= " and place_type_id={$placeTypeId} ";

	
	$query = "select id, name as shortname, concat(name, ' ', vicinity) as name from landmarks where name like '%$q%' ".$where." and status='Active' order by name LIMIT $limit";
	

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
		$data['shortName'] = $row['shortname'];
		array_push($returnArr, $data);

	}

	echo json_encode($returnArr);
	
?>