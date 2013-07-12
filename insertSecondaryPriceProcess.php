<?php 
    $projectId  = $_REQUEST['projectId'];
    $brokerId   = $_REQUEST['brokerId'];
    $arrBrokerPriceByProject = getBrokerPriceByProject($projectId, $brokerId);
    if(isset($_REQUEST['btnExit'])){
        header("Location:secondary_price.php?projectId=$projectId");
    }
    if(isset($_REQUEST['submit'])){
        $minPrice = $_REQUEST['minPrice'];
        $maxPrice = $_REQUEST['maxPrice'];
        $brokerId  = $_REQUEST['brokerId'];
        $flag = 0;
        $qryStr = '';
        $comma = ',';
        $cnt = 0;
        $arrMinPrice = array();
        $arrMaxPrice = array();
        $arrMeanPrice = array();
        $effectiveDate = $_REQUEST['effectiveDate'];
        $exp = explode("-",$effectiveDate);
        $effMonthYear = $exp[0]."-".$exp[1]."-01";
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
                 $qryStr .= "('','".$projectId."','".$typeName."','".$brokerId."','".$minPrice."',
                             '".$maxPrice."','".$effMonthYear."','".$_SESSION['adminId']."',now())$comma";
            }
            else
                $flag = 1;        
        }
        $errorPrice = '';
        if($flag == 0){
            $ins = "INSERT INTO PROJECT_SECONDARY_PRICE 
                    (ID, PROJECT_ID, UNIT_TYPE, BROKER_ID, MIN_PRICE, MAX_PRICE, EFFECTIVE_DATE,
                        LAST_MODIFIED_BY, LAST_MODIFIED_DATE) VALUES $qryStr";
            $res = mysql_query($ins) or die(mysql_error());
            if($res)
                $errorPrice = "<font color = 'green'>Price has been inserted successfully!</font>";
            else
                $errorPrice = "<font color = 'red'>Problem in price insertion please try again!</font>";
        }else{
            $errorPrice = "<font color = 'red'>Min/Max price cant blank!</font>";
        }
        $smarty->assign("arrMinPrice",  $arrMinPrice);
        $smarty->assign("arrMaxPrice", $arrMaxPrice);
        $smarty->assign("arrMeanPrice", $arrMeanPrice);
        $smarty->assign("errorPrice", $errorPrice);
        $smarty->assign("effectiveDate", $effectiveDate);
    }
    $smarty->assign("brokerId", $brokerId);
    $smarty->assign("projectId", $projectId);
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
?>
