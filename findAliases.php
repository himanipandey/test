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
if($_GET['search'])
{
	$q=$_GET['search'];
	$query = "select id, name from aliases where name like '%$q%' order by name LIMIT 15";
	//echo $query;
	$sql_res=mysql_query($query);
	$rows = array();
	while($row=mysql_fetch_array($sql_res))
	{


		$rows[] = $row;

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

	echo  json_encode($rows);
}
?>