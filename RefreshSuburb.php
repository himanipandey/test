<?php

	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");

	 $city_id		=	$_REQUEST["id"];
	 $suburb_id	   =	$_REQUEST["suburb_id"];//die("here");
	if($suburb_id == '')
	{
		if($city_id != '')
		{
			$suburbArr = Array();
			$sql = "SELECT A.SUBURB_ID, A.CITY_ID, A.LABEL FROM ".SUBURB." AS A WHERE A.CITY_ID = " . $city_id . "";

			$data = mysql_query($sql);

			while ($dataArr = mysql_fetch_array($data))
			 {
				array_push($suburbArr, $dataArr);
			 }
		 	echo "<option value=''>Select</option>";
		 	foreach($suburbArr as $val)
		 	{
    			echo "<option value=".$val["SUBURB_ID"].">".$val["LABEL"] . "</option>";
   			}
   		 }
		else
		{
			  echo "<option value=''>Select</option>"; 
		 
		}	
    }
    if($suburb_id != '')	
    {
    	$localityArr = Array();
		$sql = "SELECT A.LOCALITY_ID, A.SUBURB_ID, A.CITY_ID, A.LABEL FROM ".LOCALITY." AS A WHERE A.CITY_ID = " . $city_id." AND VISIBLE_IN_CMS = '1'";
		if ($suburb_id != null) {
		$sql .= " AND A.SUBURB_ID = " . $suburb_id;
		}
	
		$data = mysql_query($sql);
		while ($dataArr = mysql_fetch_array($data))
		 {
			array_push($localityArr, $dataArr);
		 }	
		 echo  "<option value=''>Select</option>";  	
		 foreach($localityArr as $val)
		 {
    		echo "<option value=".$val["LOCALITY_ID"].">".$val["LABEL"] . "</option>";
   		 }
    }		
?>