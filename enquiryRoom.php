<?php
		include("smartyConfig.php");
		include("appWideConfig.php");
		include("dbConfig.php");
		include("includes/configs/configs.php");



	if($_POST['task']=='newCategory'){
		$roomCategory = $_POST['rC'];
		$optionId = $_POST['optionId'];
		$query = "insert into room_category(CATEGORY_NAME) values('{$roomCategory}')";
		mysql_query($query) or die(mysql_error());
		$newCategoryId = mysql_insert_id();
		echo $newCategoryId;
	}

	else{

		$count=$_POST['count'];
		$optionId= trim($_POST['optionId']);
		$rowId=$_POST['rowId'];
		print("<pre>");
		print_r($_POST); //die();
		//deleted old records
		$sql_del_old="DELETE FROM ".PROJECT_OPTIONS_ROOM_SIZE." WHERE `OPTIONS_ID` = '$optionId'";
		mysql_query($sql_del_old) or die(mysql_error());
		
		
		for($i=1;$i<=$count;$i++)
		{
			
				$room_category_id = $_POST['room_category_id_'.$i];
				$length_ft = $_POST['length_ft_'.$i];
				$length_inch = $_POST['length_inch_'.$i];
				$breath_ft = $_POST['breath_ft_'.$i];
				$breath_inch = $_POST['breath_inch_'.$i];

				/*$roomCategory =$_POST['roomCategory_'.$i];
				$length =$_POST['length_'.$i];
				$breath =$_POST['breath_'.$i];*/

				if(($length_ft=='' and $breath_ft=='') || $room_category_id == '')
				{
					continue;
				}
				else
				{
					
					$sql="INSERT INTO ".PROJECT_OPTIONS_ROOM_SIZE." (`OPTIONS_ID`,`ROOM_CATEGORY_ID`,`ROOM_LENGTH`,`ROOM_LENGTH_INCH`,`ROOM_BREATH`,`ROOM_BREATH_INCH`) VALUES ('$optionId','$room_category_id','$length_ft','$length_inch','$breath_ft', '$breath_inch')";		
					//die($sql);				
					
					$row=mysql_query($sql) or die(mysql_error());
				}
				
  		
		}

		echo $rowId;
	}

?>
