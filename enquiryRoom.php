<?php
		include("smartyConfig.php");
		include("appWideConfig.php");
		include("dbConfig.php");
		include("includes/configs/configs.php");

		$count=$_POST['count'];
		$optionId= trim($_POST['optionId']);
		$rowId=$_POST['rowId'];
		
		//deleted old records
		$sql_del_old="DELETE FROM ".PROJECT_OPTIONS_ROOM_SIZE." WHERE `OPTIONS_ID` = '$optionId'";
		mysql_query($sql_del_old) or die(mysql_error());
		
		
		for($i=1;$i<=$count;$i++)
		{
		
				$roomCategory =$_POST['roomCategory_'.$i];
				$length =$_POST['length_'.$i];
				$breath =$_POST['breath_'.$i];

				if(($length=='' and $breath=='') || $roomCategory == '')
				{
					continue;
				}
				else
				{
					
					$sql="INSERT INTO ".PROJECT_OPTIONS_ROOM_SIZE." (`OPTIONS_ID`,`ROOM_CATEGORY_ID`,`ROOM_LENGTH`,`ROOM_BREATH`) VALUES ('$optionId','$roomCategory','$length','$breath')";						
					
					$row=mysql_query($sql) or die(mysql_error());
				}
				
  		
		}

		echo $rowId;

?>
