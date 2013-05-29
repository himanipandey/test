<?php
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
AdminAuthentication();


$cityval = $_GET['cityval'];
$id = $_GET['id'];
$deleteCity	=	$_GET['ciddelete'];
$sel_id = $_GET['id'];
$ins = 0;
$c = 0;

	/*******************Delete City******************/

if($deleteCity != '')
{
		$qry	=	"DELETE FROM ".CITY." WHERE CITY_ID = '".$deleteCity."'";
		$res	=	mysql_query($qry);
		if($res)
		{
			
			$selqry = "SELECT CITY_ID,LABEL FROM ".CITY." ORDER BY LABEL";
			$ressel = mysql_query($selqry);
			?>
			<select name="cityId" id = "cityId" class="cityId" onchange="dispcity(this.value,1);" STYLE="width: 150px">
			<option value =''>Select City</option>
			<?php
				while($data	=	mysql_fetch_array($ressel))
				{
				?>
				<option  value ='<?php echo $data['CITY_ID']; ?>' <?php if( $data['CITY_ID'] == $sel_id ) echo "selected='selected'"; ?>><?php echo $data['LABEL']; ?></option>
				<?php
				}
				?>
			</select>

<?php
		}
}

else
{
	$seldata		=	"SELECT LABEL FROM ".CITY." WHERE LABEL = '".trim($cityval)."'";
	$resdata		=	mysql_query($seldata);
	$ins = mysql_num_rows($resdata);
	
	if($cityval!='' && $id!='')
	{
		$url = urlCreaationDynamic('property-in-',$cityval);
		
		$qryOld = "SELECT URL FROM ".CITY." WHERE CITY_ID='".$id."'";
		$resOld = mysql_query($qryOld);
		$oldUrl = mysql_fetch_assoc($resOld);
		
		$seldata = "UPDATE ".CITY." SET LABEL = '".trim($cityval)."',URL = '".$url."' WHERE CITY_ID='".$id."'";
		$resdata = mysql_query($seldata);
		$c = mysql_affected_rows();
		
		if($url != $oldUrl['URL'])
			updateProjectUrl($id,'city','');
		
		insertUpdateInRedirectTbl($url,$oldUrl['URL']);
		
	}

	if($c==0 && $ins==0)
	{	
		$cityurl =  preg_replace('!\s+!', '-', strtolower($cityval));
		$url = "property-in-".$cityurl."-real-estate.php";
		$qry = "INSERT INTO ".CITY." (LABEL,ACTIVE,URL) value('".$cityval."','0','$url')";
		$res = mysql_query($qry);
		$ctid = mysql_insert_id();
		$sel_id = $ctid;
	}


	$selqry = "SELECT CITY_ID,LABEL FROM ".CITY." ORDER BY LABEL";
	$ressel = mysql_query($selqry);
	?>
	<select name="cityId" id = "cityId" class="cityId" onchange="dispcity(this.value,1);" STYLE="width: 150px">
	<option value =''>Select City</option>
	<?php
		while($data	=	mysql_fetch_array($ressel))
		{
		?>
		<option  value ='<?php echo $data['CITY_ID']; ?>' <?php if( $data['CITY_ID'] == $sel_id ) echo "selected='selected'"; ?>><?php echo $data['LABEL']; ?></option>
		<?php
		}
		?>
	</select>
<?php
}
?>
