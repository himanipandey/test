<?php
		include("smartyConfig.php");
		include("appWideConfig.php");
		include("dbConfig.php");
		include("includes/configs/configs.php");

		$count=$_POST['count'];
		$optionId=$_POST['optionId'];
		$rowId=$_POST['rowId'];

		for($i=1;$i<=$count;$i++)
		{
	
				$roomCategory =$_POST['roomCategory_'.$i];
				$length =$_POST['length_'.$i];
				$breath =$_POST['breath_'.$i];

				if($length=='' and $breath=='')
				{
					continue;
				}
				else
				{

					$sql="INSERT INTO resi_proj_options_room_size (`OPTIONS_ID`,`ROOM_CATEGORY_ID`,`ROOM_LENGTH`,`ROOM_BREATH`) VALUES ('$optionId','$roomCategory','$length','$breath')";
				}
				$row=mysql_query($sql);

			

				
		}

		echo $rowId;

?>