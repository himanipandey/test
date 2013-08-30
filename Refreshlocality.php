<?php

	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("/includes/configs/configs.php");
	 $ctid		=	$_REQUEST["ctid"];
    if($ctid != '')	
    {
    	$localityArr = Array();
		$sql = "SELECT A.LOCALITY_ID, A.LABEL FROM locality AS A WHERE A.CITY_ID = " . $ctid." AND VISIBLE_IN_CMS = '1' GROUP BY A.LABEL ORDER BY A.LABEL ASC";

		
		$data = mysql_query($sql) or die(mysql_error());

		while ($dataArr = mysql_fetch_array($data))
		 {
			array_push($localityArr, $dataArr);
		 }	
		 echo  "<select name = 'locality' id = 'locality'>";
		 echo  "<option value=''>Select locality</option>";  	
		 foreach($localityArr as $val)
		 {
    		echo "<option value=".$val["LOCALITY_ID"].">".$val["LABEL"] . "</option>";
   		 }
		 echo  "</select>";
    }
	else
	{
		echo  "<select name = 'locality' id = 'locality'>";
		echo  "<option value=''>Select locality</option>";  
		 echo  "</select>";
	}
?>