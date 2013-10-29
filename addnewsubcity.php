<?php
include_once("smartyConfig.php");
include_once("appWideConfig.php");
include_once("dbConfig.php");
include_once("includes/configs/configs.php");
include_once("builder_function.php");
include_once("function/locality_functions.php");

AdminAuthentication();
require $_SERVER['DOCUMENT_ROOT'].'/dbConfig.php';
$subcityvalnew = $_GET['subcityval'];
$subcityval = str_replace("@","&",$subcityvalnew);

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
    require $_SERVER['DOCUMENT_ROOT'].'/dbConfig.php';
    $qryCity = "SELECT LABEL FROM ".CITY." WHERE CITY_ID = $cityid";
    $resCity = mysql_query($qryCity) or die(mysql_error());
    $dataCity = mysql_fetch_assoc($resCity);
    $subcityval = trim($subcityval);
    $url = "";
    if($subcityval!='' && $id!='')
    {	
    $url = createLocalityURL($subcityval, $dataCity['LABEL'], $id, 'suburb'); 
    $seldata = "UPDATE ".SUBURB." 
              SET LABEL = '".$subcityval."', URL = '$url',updated_by = '".$_SESSION['adminId']."'  
                  WHERE SUBURB_ID='".$id."'";
            $resdata = mysql_query($seldata) or die(mysql_error()." update");
            $c = mysql_affected_rows();
    }

    $seldata = "SELECT LABEL FROM ".SUBURB." WHERE LABEL = '".$subcityval."' AND CITY_ID='".$cityid."'";
    $resdata = mysql_query($seldata) or die(mysql_error());
    $ins = mysql_num_rows($resdata);

    if($c==0 && $ins==0)
    {	

           $qry = "INSERT INTO ".SUBURB." (LABEL,CITY_ID,status,updated_by) 
               value('".$subcityval."','".$cityid."','Active','".$_SESSION['adminId']."')";
            $res = mysql_query($qry) or die(mysql_error()." insert");
            $ctid = mysql_insert_id();
            $sel_id = $ctid;
            $url = createLocalityURL($subcityval, $dataCity['LABEL'], $ctid, 'suburb'); 
            $seldata = "UPDATE ".SUBURB." 
                SET URL = '$url',updated_by = '".$_SESSION['adminId']."'
                    WHERE SUBURB_ID='".$ctid."'";
            $resdata = mysql_query($seldata);
            $c = mysql_affected_rows();

    $url = createLocalityURL($subcityval, $dataCity['LABEL'], $ctid, 'suburb');
    $seldata = "UPDATE ".SUBURB." 
        SET URL = '$url',updated_by = '".$_SESSION['adminId']."' WHERE SUBURB_ID='".$ctid."'";
    $resdata = mysql_query($seldata);
    }


   $selqry = "SELECT SUBURB_ID,LABEL FROM ".SUBURB." WHERE CITY_ID='".$cityid."' ORDER BY LABEL";
    $ressel = mysql_query($selqry) or die(mysql_error()." select");
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
