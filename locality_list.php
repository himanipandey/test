<?php
	error_reporting(1);
	ini_set('display_errors','1');
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("builder_function.php");
	AdminAuthentication();
	 $city_id	=	$_REQUEST['city_id'];
	 
	 $qryOrd	=	"SELECT LOCALITY_ID,LABEL FROM ".LOCALITY." WHERE CITY_ID = '".$city_id."' ORDER BY LABEL ASC";
	$res		=	mysql_query($qryOrd);
	?>
		<select id="loc_id" name="loc_id" onchange = "save_loc_id(this.value);">	
	<?php

		while($data = mysql_fetch_assoc($res))
		{
			?>
				<option value = "<?php echo $data['LOCALITY_ID']; ?>"><?php echo $data['LABEL']; ?></option>
			<?php
		}
	?>
		</select>