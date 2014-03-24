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
	$q=$_GET['query'];
	$query = "select id, name from aliases where name like '%$q%' order by name LIMIT 15";
	//echo $query;
	$sql_res=mysql_query($query);
	$rows = array();
	$suggestions = array();
	$data = array();
	$rows[query] = $q;
	while($row=mysql_fetch_array($sql_res))
	{


		$suggestions[] = $row['name'];
		$data[] = $row['id'];

		/*
		//$name=$row['name'];
		//echo $name;
		//die("here");
		?>
		<div class="show" align="left">
		<span class="name"><?php echo $name; ?></span>
		</div>
		<?php  */
	}
	$rows[suggestions] = $suggestions;
	$rows[data] = $data;

	echo json_encode($rows);
    //echo "{query:"e", suggestions:["apple","coffee","delhi","delhi 3","delhi1","delhi4","delhi5","delhi6","delhi7","hello","hello1","hello2"], data:["4","6","9","11","10","12","13","14","15","1","2","3"]}";

//}
?>