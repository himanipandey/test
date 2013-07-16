<?php 
    $projectId  = $_REQUEST['projectId'];
    if(isset($_REQUEST['btnExit'])){
        header("Location:secondary_price.php?projectId=$projectId");
    }
    $effectiveDt = '';
    $month = '';
    $year  = '';
    if(isset($_REQUEST['search'])){ //search for month, year and broker
         $brokerId   = $_REQUEST['brokerId'];
         $effectiveDt= $_REQUEST['year']."-".$_REQUEST['month']."-01 00:00:00";
         $year = $_REQUEST['year'];
         $month = $_REQUEST['month'];
         $arrBrokerPriceByProject = getBrokerPriceByProject($projectId, $brokerId, $effectiveDt);
    }   // die;
    if(isset($_REQUEST['submit'])){ //code start for update price
        $minPrice = $_REQUEST['minPrice'];
        $maxPrice = $_REQUEST['maxPrice'];
        $brokerId  = $_REQUEST['brokerId'];
        $oldEffectiveDate  = $_REQUEST['year']."-".$_REQUEST['month']."-01 00:00:00";
        $year = $_REQUEST['year'];
        $month = $_REQUEST['month'];
        $flag = 0;
        $comma = ',';
        $cnt = 0;
        $arrMinPrice = array();
        $arrMaxPrice = array();
        $arrMeanPrice = array();
        foreach($_REQUEST['unitType'] as $key=>$val){
            $cnt++;
            if($cnt == count($_REQUEST['unitType']))
                $comma = ';';
            $arrMinPrice[] = $_REQUEST['minPrice'][$key];
            $arrMaxPrice[] = $_REQUEST['maxPrice'][$key];
            $arrMeanPrice[] = ($_REQUEST['minPrice'][$key]+$_REQUEST['maxPrice'][$key])/2;
            if($_REQUEST['minPrice'][$key] != '' AND $_REQUEST['maxPrice'][$key] != ''){
                 $minPrice = $_REQUEST['minPrice'][$key];
                 $maxPrice = $_REQUEST['maxPrice'][$key];
                 $typeName =   $val;
                 $qryUp = "UPDATE project_secondary_price
                           SET 
                              MIN_PRICE           =   '".$minPrice."',
                              MAX_PRICE           =   '".$maxPrice."',
                              LAST_MODIFIED_BY    = '".$_SESSION['adminId']."',
                              LAST_MODIFIED_DATE  = now()
                            WHERE
                              PROJECT_ID = '".$projectId."'
                            AND
                              BROKER_ID  = '".$brokerId."'
                            AND
                              UNIT_TYPE  = '".$typeName."'
                            AND
                              EFFECTIVE_DATE = '".$oldEffectiveDate."'";
               $resUp  = mysql_query($qryUp) or die(mysql_error());
               if($resUp)
                   $flag = 0;
               else
                   $flag = 1;
            }
            else
                $flag = 1;     
        }
        $errorPrice = '';
        if($flag == 0){
           
                $errorPrice = "<font color = 'green'>Price has been updated successfully!</font>";
            
        }else{
            $errorPrice = "<font color = 'red'>Min/Max price cant blank!</font>";
        }
        $arrBrokerPriceByProject = getBrokerPriceByProject($projectId, $brokerId, $effectiveDt);
        $smarty->assign("arrMinPrice",  $arrMinPrice);
        $smarty->assign("arrMaxPrice", $arrMaxPrice);
        $smarty->assign("arrMeanPrice", $arrMeanPrice);
        $smarty->assign("errorPrice", $errorPrice);
    }
    $smarty->assign("brokerId", $brokerId);
    $smarty->assign("effectiveDt", $effectiveDt);
    $smarty->assign("projectId", $projectId);
    $smarty->assign("year",  $year);
    $smarty->assign("month",  $month);
    //code for distinct unit for a project
    $arrProjectType = fetch_projectOptions($projectId);
    $arrPType = array(); 
    foreach($arrProjectType as $val){
        $exp = explode("-",$val);
        if(!in_array(trim($exp[0]),$arrPType))
            array_push($arrPType,trim($exp[0]));
    }
    $smarty->assign("arrPType", $arrPType);
    $allBrokerByProject   = getBrokerByProject($projectId);
    $arrBrokerList = array();
     foreach($allBrokerByProject as $key=>$val){
         $brikerList = getBrokerDetailById($key);
         $arrBrokerList[$key] = $brikerList;
     }
     $smarty->assign("allBrokerByProject", $arrBrokerList);
     $projectDetails = array();
     $projectDetails = projectDetailById($projectId);
     $smarty->assign("projectDetails", $projectDetails);
     $smarty->assign("arrBrokerPriceByProject", $arrBrokerPriceByProject);

     $currentYear= date('Y');
     $startYear  = $currentYear-2;
     $endYear    = $currentYear+10;
     $smarty->assign("startYear", $startYear);
     $smarty->assign("endYear", $endYear);
?>
