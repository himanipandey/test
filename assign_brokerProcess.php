<?php 
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
            $del = deleteAllBrokerOfProject($projectId);
            if($del){
                $qryIns = "INSERT INTO BROKER_PROJECT_MAPPING (ID,PROJECT_ID,BROKER_ID,ACTION_DATE)
                            VALUES ";
                $cnt = 1;
                $comma = ',';
                foreach($_REQUEST['broker'] as $k=>$v){
                    if($cnt == count($_REQUEST['broker']))
                        $comma = '';
                    $qryIns .= "('','".$projectId."','".$v."',now())$comma";
                    $cnt++;
                }
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
    }else{
        $projectId    = $_REQUEST['projectId'];
        $projectName  = $_REQUEST['projectName'];
        $arrAllActiveBrokerList = getActiveBrokerList();
        include("dbConfig.php");
        $allBrokerByProject   = getBrokerByProject($projectId);
        $smarty->assign("projectId", $projectId);
        $smarty->assign("allBrokerByProject", $allBrokerByProject);
        $smarty->assign("projectName", $projectName);
        $smarty->assign("arrAllActiveBrokerList", $arrAllActiveBrokerList);
    }

?>
