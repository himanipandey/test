<?php
include_once("smartyConfig.php");
include_once("appWideConfig.php");
include_once("dbConfig.php");
include_once("includes/configs/configs.php");
include_once("builder_function.php");
include_once("function/locality_functions.php");

AdminAuthentication();

$subcityval = $_GET['subcityval'];
$subcityval = str_replace("@","&",$subcityval);

$id = $_GET['id'];
$cityid = $_GET['cityid'];
$sel_id = $_GET['id'];
$ins = 0;
$c = 0;
$deletesubcity	=	$_GET['deletesubcity'];
$cid			=	$_GET['cid'];
if($deletesubcity != '')
{
		$qry	=	"DELETE FROM ".SUBURB." WHERE SUBURB_ID = '".$deletesubcity."'";
		$res	=	mysql_query($qry);
		if($res)
		{
			
			$selqry = "SELECT SUBURB_ID,LABEL FROM ".SUBURB."  WHERE CITY_ID = '".$cid."' ORDER BY LABEL";
			$ressel = mysql_query($selqry);
			?>
			<select name="suburbId" id = "suburbId" class="suburbId" onchange="dispcity(this.value,1);" STYLE="width: 150px">
			<option value =''>Select Suburb</option>
			<?php
				while($data	=	mysql_fetch_array($ressel))
				{
				?>
				<option  value ='<?php echo $data['SUBURB_ID']; ?>'><?php echo $data['LABEL']; ?></option>
				<?php
				}
				?>
			</select>

<?php
		}
}

else
{
    $qryCity = "SELECT LABEL FROM ".CITY." WHERE CITY_ID = $cityid";
    $resCity = mysql_query($qryCity);
    $dataCity = mysql_fetch_assoc($resCity);
    mysql_free_result($resCity);
    $subcityval = trim($subcityval);
    $url = createLocalityURL($subcityval, $dataCity['LABEL']);
    var_dump($dataCity);
    if($subcityval!='' && $id!='')
	{		
		$seldata = "UPDATE ".SUBURB." SET LABEL = '".$subcityval."', URL = '$url'  WHERE SUBURB_ID='".$id."'";
		$resdata = mysql_query($seldata);
		$c = mysql_affected_rows();
	}

	$seldata = "SELECT LABEL FROM ".SUBURB." WHERE LABEL = '".$subcityval."' AND CITY_ID='".$cityid."'";
	$resdata = mysql_query($seldata);
	$ins = mysql_num_rows($resdata);

	if($c==0 && $ins==0)
	{	
		
		$qry = "INSERT INTO ".SUBURB." (LABEL,CITY_ID,ACTIVE,URL) value('".$subcityval."','".$cityid."','1', '$url')";
		$res = mysql_query($qry);
		$ctid = mysql_insert_id();
		$sel_id = $ctid;
	}


	$selqry = "SELECT SUBURB_ID,LABEL FROM ".SUBURB." WHERE CITY_ID='".$cityid."' ORDER BY LABEL";
	$ressel = mysql_query($selqry);
	?>
	<select name="suburbId" id = "suburbId" class="suburbId" onchange="dispsubcity(this.value,1);" STYLE="width: 150px">
	<option value =''>Select Suburb</option>
	<?php
		while($data	=	mysql_fetch_array($ressel))
		{
		?>
		<option  value ='<?php echo $data['SUBURB_ID']; ?>' <?php if( $data['SUBURB_ID'] == $sel_id ) echo "selected='selected'"; ?>><?php echo $data['LABEL']; ?></option>
		<?php
		}
		?>
	</select>
<?php
}
?>