<?php
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("function/locality_functions.php");
include("builder_function.php");
include_once("function/locality_functions.php");
AdminAuthentication();

$cityval = $_GET['cityval'];
$id = $_GET['id'];
$deleteCity = $_GET['ciddelete'];
$sel_id = $_GET['id'];
$ins = 0;
$c = 0;

/*******************Delete City******************/

if($deleteCity != '')
{
    $qry = "DELETE FROM ".CITY." WHERE CITY_ID = '".$deleteCity."'";
    $res = mysql_query($qry);
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
    $seldata = "SELECT LABEL FROM ".CITY." WHERE LABEL = '".trim($cityval)."'";
    $resdata = mysql_query($seldata);
    $ins = mysql_num_rows($resdata);

    if($cityval!='' && $id!='')
    {
        $seldata = "UPDATE ".CITY." 
        SET 
            LABEL = '".trim($cityval)."',
            updated_by = ".$_SESSION['adminId'].",
        WHERE CITY_ID='".$id."'";
        $resdata = mysql_query($seldata);
        $c = mysql_affected_rows();		
    }

    if($c==0 && $ins==0){
        $qry = "INSERT INTO ".CITY." 
            (LABEL, status, updated_by )
            value
            ('".$cityval."','Active',".$_SESSION['adminId'].")";
        $res = mysql_query($qry) or die(mysql_error());
        $ctid = mysql_insert_id();
        $sel_id = $ctid;
        $txtCityUrl = createLocalityURL($cityval,'', $ctid, 'city');
        $urlUpdate = "update city set url = '$txtCityUrl' where city_id = $ctid";
        $urlUp = mysql_query($urlUpdate) or die(mysql_error());
    }


    $selqry = "SELECT CITY_ID,LABEL FROM ".CITY." ORDER BY LABEL";
    $ressel = mysql_query($selqry);
    ?>
    <select name="cityId" id = "cityId" class="cityId" onchange="dispcity(this.value,1);" STYLE="width: 150px">
    <option value =''>Select City</option>
    <?php
    while($data	= mysql_fetch_array($ressel))
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
