<?php
	include("smartyConfig.php");
	include("appWideConfig.php");
	include("dbConfig.php");
	include("includes/configs/configs.php");
	include("includes/function.php"); 
	AdminAuthentication();

	$cityid		=	$_REQUEST['cityid'];
	$qrybid		=	"SELECT LOCALITY_ID,LABEL FROM ".LOCALITY." WHERE CITY_ID = '".$cityid."' AND VISIBLE_IN_CMS = '1' ORDER BY LABEL ASC";
	$resbid		=	mysql_query($qrybid) or die(mysql_error."Error in builder select query");
	$arr		=	array();
	while($data = mysql_fetch_assoc($resbid))
	{
			$arr[$data['LOCALITY_ID']]	 = $data['LABEL'];
	}
	
	?>
	
	<select name = "locality_id" id = "locality_id" class="button">
		<option value = "">Select Locality</option>
		<?php
			foreach($arr as $k=>$v)
			{
			?>
				<option value = "<?php echo $k; ?>"><?php echo $v; ?></option>
			<?php
			}
		?>

	</select>