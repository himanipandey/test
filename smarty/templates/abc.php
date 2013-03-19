<?php
	if(isset($_REQUEST['submit']))
	{
		echo "Name:",$name = $_REQUEST['name']."<br>";
		echo $age = "Age:",$_REQUEST['age']."<br>";
		echo $cls = "Class:",$_REQUEST['cls']."<br>";


	}
?>