<?php
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
        include("builder_function.php");
	include("/includes/configs/configs.php");
	$ctid	= $_REQUEST["ctid"];
        $ctName = ViewCityDetails($ctid);
    if($ctid != '')	
    {
    	$localityArr = Array();
		$sql = "SELECT A.ENTITY, A.BUILDER_ID FROM ".RESI_BUILDER." AS A WHERE A.CITY = '" . $ctName['LABEL']."' GROUP BY A.BUILDER_NAME ORDER BY A.ENTITY ASC";	
		$data = mysql_query($sql);
		while ($dataArr = mysql_fetch_array($data))
		 {
			array_push($localityArr, $dataArr);
		 }	
		 echo  "<select name = 'builder' id = 'builder' onchange = 'selectedBuilderValue(this.value);'>";
		 echo  "<option value=''>Select Builder</option>";  	
		 foreach($localityArr as $val)
		 {
    		echo "<option value=".$val["BUILDER_ID"].">".$val["ENTITY"] . "</option>";
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