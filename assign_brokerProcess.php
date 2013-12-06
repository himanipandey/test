<?php 

     $accessBroker = '';
    if( $brokerAuth == false )
      die("No Access");
    
    $projectId    = $_REQUEST['projectId'];
    $projectName  = $_REQUEST['projectName'];
    $cityId       = $_REQUEST['cityId'];
    
    $smarty->assign("projectId", $projectId);
    $smarty->assign("projectName", $projectName);
    $firstTimeSearch = 0;
    if(isset($_REQUEST['exit'])){
       ?>
        <script type="text/javascript">
        window.close();
        </script>
        <?php
    }else if(isset($_REQUEST['submit'])){       
       $projectId    = $_REQUEST['projectId'];      
        include("dbConfig.php");
        if(count($_REQUEST)>3){
            $del = 1; //deleteAllBrokerOfProject($projectId);
            if($del){
                $qryIns = "INSERT INTO broker_project_mapping (ID,PROJECT_ID,BROKER_ID,ACTION_DATE)
                            VALUES ";
                $cnt = 1;
                $comma = ',';
                foreach($_REQUEST['broker'] as $k=>$v){
                    if($cnt == count($_REQUEST['broker']))
                        $comma = '';
                    $qryIns .= "('','".$projectId."','".$v."',now())$comma";
                    $cnt++;
                }
                error_log("ERRORLOG-$qryIns");
                $resIns = mysql_query($qryIns) or die(mysql_error());
                if($resIns){
                    ?>
                    <script type="text/javascript">
                     window.opener.location.reload(false);
                    window.close();
                    </script>
                    <?php
                  //  header("Location:secondary_price.php?projectId=$projectId");
                }
                else
                    echo "Problem in insertion!";
            }else{
                 echo "Problem in deletion!";
            }
        }else{
            ?>
            <script type="text/javascript">
            window.close();
            </script>
            <?php
        }
    }else if( isset($_REQUEST['search']) ) {
        $broker = $_REQUEST['broker'];
        $mobile = $_REQUEST['mobile'];
        $errorMsg = array();
        if( $mobile == '' && $broker == '' ) {
            $errorMsg['oneSelection'] = "<font color = 'red'>please select atleast one field</font>";
        }
        $arrAllActiveBrokerList = array();
        $allBrokerByProject = array();
        if( count($errorMsg) == 0 ) {
            $arrAllActiveBrokerList = getActiveBrokerList($cityId, $broker, $mobile);
        }
        include("dbConfig.php");
        $allBrokerByProject   = getBrokerByProject($projectId);
        
        $smarty->assign("errorMsg", $errorMsg);
        $smarty->assign("broker", $broker);
        $smarty->assign("mobile", $mobile);
        $smarty->assign("allBrokerByProject", $allBrokerByProject);
        $smarty->assign("arrAllActiveBrokerList", $arrAllActiveBrokerList);
        $firstTimeSearch = 1;
    }
    $smarty->assign("firstTimeSearch", $firstTimeSearch);
?>
