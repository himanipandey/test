<?php

	include("smartyConfig.php");
	include("appWideConfig.php");
	include("../../dbConfig.php");
	include("/includes/configs/configs.php");

	 $ctid		=	$_REQUEST["ctid"];
	
    if($ctid != '')	
    {
    	$localityArr = Array();
		$sql = "SELECT A.BUILDER_NAME, A.BUILDER_ID FROM ".RESI_PROJECT." AS A WHERE A.CITY_ID = " . $ctid." GROUP BY A.BUILDER_NAME ORDER BY A.BUILDER_NAME ASC";
		
		$data = mysql_query($sql);
		while ($dataArr = mysql_fetch_array($data))
		 {
			array_push($localityArr, $dataArr);
		 }	
		 echo  "<select name = 'builder' id = 'builder'>";
		 echo  "<option value=''>Select Builder</option>";  	
		 foreach($localityArr as $val)
		 {
    		echo "<option value=".$val["BUILDER_ID"].">".$val["BUILDER_NAME"] . "</option>";
   		 }
		 echo  "</select>";
    }
	else
	{
		echo  "<select name = 'builder' id = 'builder'>";
		echo  "<option value=''>Select Builder</option>";  
		 echo  "</select>";
	}
?>