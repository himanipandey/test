<?php
error_reporting(1);
ini_set('display_errors','1');
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
AdminAuthentication();

if($_REQUEST['part']=='userstatus')
{
    $userID = $_REQUEST['userId'];
    if($userID!=''){

        $qry	=	"UPDATE ".ADMIN." SET STATUS = CASE WHEN STATUS = 'Y' THEN 'N' ELSE 'Y' END WHERE ADMINID  = '".$userID."'";
        $res	=	mysql_query($qry);
        if($res)
        {
            $qry	=	"SELECT STATUS FROM ".ADMIN." WHERE ADMINID = '".$userID."'";
            $res	=	mysql_query($qry);
            $data	=	mysql_fetch_array($res);
            ?>
            <span id="statusRefresh"<?php echo $userID; ?> >
                <a href = "javascript:void(0); onclick= statuschange(<?php echo $userID; ?>)">
                  <?php 
                      if ($data['STATUS'] == 'Y')
                         echo "Active";
                      else
                         echo "Deactive";
                        ?></a>

            </span>	
            <?php
        }
    }
}
?>
