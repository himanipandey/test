<?php
include_once("smartyConfig.php");
include_once("appWideConfig.php");
include_once("dbConfig.php");
include_once("includes/configs/configs.php");
include_once("builder_function.php");
include_once("function/locality_functions.php");

AdminAuthentication();

$subcityval = $_GET['subcityval'];
$localityval = $_GET['localityval'];

$localityval = str_replace("@","&",$localityval);

$id = $_GET['id'];
$cityid = $_GET['cityid'];
$sel_id = $_GET['id'];
$ins = 0;
$c = 0;

$deleteloc		=	$_GET['localitydelete'];
$deletect		=	$_GET['cid'];
$deletesub		=	$_GET['subiddelete'];

if($deleteloc != '')
{
	$qry	=	"DELETE FROM ".LOCALITY." WHERE LOCALITY_ID = '".$deleteloc."'";
		$res	=	mysql_query($qry);
		if($res)
		{
			
			 $selqry = "SELECT LOCALITY_ID,LABEL FROM ".LOCALITY." WHERE CITY_ID = '".$deletect."' AND SUBURB_ID = '".$deletesub."'  ORDER BY LABEL";//die("here");
			$ressel = mysql_query($selqry);
			?>
			<select name="localityId" id = "localityId" class="localityId" onchange="dispcity(this.value,1);" STYLE="width: 150px">
			<option value =''>Select Locality</option>s
			<?php
				while($data	=	mysql_fetch_array($ressel))
				{
				?>
				<option  value ='<?php echo $data['LOCALITY_ID']; ?>' ><?php echo $data['LABEL']; ?></option>
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
    $localityval = trim($localityval);
    $url = createLocalityURL($localityval, $dataCity['LABEL']);
    
	if($subcityval!='' && $id!='')
	{		
		$seldata = "UPDATE ".LOCALITY." SET LABEL = '".$localityval."', URL = '$url' WHERE LOCALITY_ID='".$id."' AND SUBURB_ID='".$subcityval."'";
		$resdata = mysql_query($seldata);
		$c = mysql_affected_rows();
	}

	$seldata = "SELECT LABEL FROM ".LOCALITY." WHERE LABEL = '".$localityval."' AND CITY_ID='".$cityid."' AND SUBURB_ID='".$subcityval."'";
	$resdata = mysql_query($seldata);
	$ins = mysql_num_rows($resdata);

	if($c==0 && $ins==0)
	{	
		$qry = "INSERT INTO ".LOCALITY." (LABEL,CITY_ID,SUBURB_ID,ACTIVE,URL,PRIORITY) value('".$localityval."','".$cityid."','".$subcityval."','1', '$url',100)";
		$res = mysql_query($qry);
		$ctid = mysql_insert_id();
		$sel_id = $ctid;
	}


	$selqry = "SELECT LOCALITY_ID,LABEL FROM ".LOCALITY." WHERE CITY_ID='".$cityid."' AND  SUBURB_ID='".$subcityval."' ORDER BY LABEL";
	$ressel = mysql_query($selqry);
	?>
	<select name="localityId" id = "localityId" class="localityId" onchange="displocality(this.value,1);" STYLE="width: 150px">
	<option value =''>Select Locality</option>
	<?php
		while($data	=	mysql_fetch_array($ressel))
		{
		?>
		<option  value ='<?php echo $data['LOCALITY_ID']; ?>' <?php if( $data['LOCALITY_ID'] == $sel_id ) echo "selected='selected'"; ?>><?php echo $data['LABEL']; ?></option>
		<?php
		}
		?>
	</select>
<?php
}
?>