<?php
include_once("smartyConfig.php");
include_once("appWideConfig.php");
include_once("dbConfig.php");
include_once("includes/configs/configs.php");
include_once("builder_function.php");
include_once("function/locality_functions.php");

AdminAuthentication();
require $_SERVER['DOCUMENT_ROOT'].'/dbConfig.php';
$subcityval = $_GET['subcityval'];
$localityvalNew = $_GET['localityval'];

$localityval = str_replace("@","&",$localityvalNew);

$id = $_GET['id'];
$cityid = $_GET['cityid'];
$sel_id = $_GET['id'];
$ins = 0;
$c = 0;

$deleteloc = $_GET['localitydelete'];
$deletect = $_GET['cid'];
$deletesub = $_GET['subiddelete'];

if($deleteloc != '')
{
    $qry = "DELETE FROM ".LOCALITY." WHERE LOCALITY_ID = '".$deleteloc."'";
    $res = mysql_query($qry) or die(mysql_error());
    if($res)
    {
        $selqry = "SELECT l.LOCALITY_ID,l.LABEL FROM ".LOCALITY." l
            inner join suburb s on l.suburb_id = s.suburb_id
            WHERE s.CITY_ID = '".$deletect."' AND l.SUBURB_ID = '".$deletesub."'  ORDER BY l.LABEL";
        $ressel = mysql_query($selqry);
        ?>
        <select name="localityId" id = "localityId" class="localityId" onchange="displocality(this.value,1);" STYLE="width: 150px">
        <option value =''>Select Locality</option>
    <?php
        while($data = mysql_fetch_array($ressel))
        {
        ?>
            <option  value ='<?php echo $data['LOCALITY_ID']; ?>'><?php echo $data['LABEL']; ?></option>
        <?php
        }
        ?>
    </select>
<?php
       }
}

else
{
   require $_SERVER['DOCUMENT_ROOT'].'/dbConfig.php';
    $qryCity = "SELECT LABEL FROM ".CITY." WHERE CITY_ID = $cityid";
    $resCity = mysql_query($qryCity);
    $dataCity = mysql_fetch_assoc($resCity);
    mysql_free_result($resCity);
    $localityval = trim($localityval);
    $url = "";
    
	if($subcityval!='' && $id!='')
	{		
            $url = createLocalityURL($localityval, $dataCity['LABEL'], $id, 'locality');
            $seldata = "UPDATE ".LOCALITY." SET LABEL = '".$localityval."', URL = '$url' WHERE LOCALITY_ID='".$id."' AND SUBURB_ID='".$subcityval."'";
            $resdata = mysql_query($seldata);
            $c = mysql_affected_rows();
	}

        $seldata = "SELECT l.LABEL FROM ".LOCALITY." l
            inner join suburb s on l.suburb_id = s.suburb_id
            WHERE s.CITY_ID = '".$deletect."' AND l.LABEL = '".$localityval."'";
	$resdata = mysql_query($seldata);
	$ins = mysql_num_rows($resdata);

	if($c==0 && $ins==0)
	{	
            $qry = "INSERT INTO ".LOCALITY." (LABEL,SUBURB_ID,status,updated_by)
                value('".$localityval."','".$subcityval."','Active','".$_SESSION['adminId']."')";
            $res = mysql_query($qry);
            $locId = mysql_insert_id();
            $sel_id = $ctid;

        $url = createLocalityURL($localityval, $dataCity['LABEL'], $locId, 'locality');
        $qry = "UPDATE ".LOCALITY." SET URL = '$url',updated_by = '".$_SESSION['adminId']."'
            WHERE LOCALITY_ID=".$locId;
        $res = mysql_query($qry) or die(mysql_error());
	}

	$selqry = "SELECT l.LABEL,l.locality_id FROM ".LOCALITY." l
            inner join suburb s on l.suburb_id = s.suburb_id
            WHERE s.CITY_ID = '".$cityid."' AND l.suburb_id = '".$subcityval."' ORDER BY LABEL";
        $ressel = mysql_query($selqry);
	?>
	<select name="localityId" id = "localityId" class="localityId" onchange="displocality(this.value,1);" STYLE="width: 150px">
	<option value =''>Select Locality</option>
	<?php
            while($data = mysql_fetch_array($ressel))
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
