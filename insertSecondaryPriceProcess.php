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
        $arrMinPrice = array();
        $arrMaxPrice = array();
        $arrMeanPrice = array();
        $effectiveDate = $_REQUEST['effectiveDate'];
        $exp = explode("-",$effectiveDate);
        $effMonthYear = $exp[0]."-".$exp[1]."-01";
        foreach($_REQUEST['unitType'] as $key=>$val){
            $arrMinPrice[] = $_REQUEST['minPrice'][$key];
            $arrMaxPrice[] = $_REQUEST['maxPrice'][$key];
            
            $arrMeanPrice[] = ($_REQUEST['minPrice'][$key]+$_REQUEST['maxPrice'][$key])/2;
            if($_REQUEST['minPrice'][$key] != '' AND $_REQUEST['maxPrice'][$key] != ''){
               
                if($_REQUEST['minPrice'][$key] > $_REQUEST['maxPrice'][$key]) {
                    $flag = 2;
                }
                else {
                    $minPrice = $_REQUEST['minPrice'][$key];
                    $maxPrice = $_REQUEST['maxPrice'][$key];
                    $typeName =   $val;
                
                    $attributes= array(
                        'project_id'=>$projectId, 
                        'broker_id'=>$brokerId, 
                        'unit_type'=>$typeName, 
                        'effective_date'=>$effMonthYear,
                        'min_price'=>$minPrice, 
                        'max_price'=>$maxPrice,
                        'last_modified_by'=>$_SESSION['adminId'],
                        'last_modified_date'=>'NOW()'
                    );
                }
            }
            else
                $flag = 1;        
        }
        $errorPrice = '';
        if($flag == 0){
            $res = ProjectSecondaryPrice::insertUpdate($attributes);
            if($res)
                $errorPrice = "<font color = 'green'>Price has been inserted successfully!</font>";
            else
                $errorPrice = "<font color = 'red'>Problem in price insertion please try again!</font>";
        }else{
                if($flag == 2)
                    $errorPrice = "<font color = 'red'>Minimum price should be less them max price!</font>";
                else
                    $errorPrice = "<font color = 'red'>Min/Max price cant blank!</font>";
        }
        $arrBrokerPriceByProject = getBrokerPriceByProject($projectId, $brokerId);
         $smarty->assign("arrBrokerPriceByProject", $arrBrokerPriceByProject);
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
     $arrLatestMinPrice = '';
     $arrLatestMaxPrice = '';
     $arrEffectvDtLatest = $arrBrokerPriceByProject[0]['EFFECTIVE_DATE'];
     foreach($arrBrokerPriceByProject as $k=>$v) {
         
     }
     $smarty->assign("allBrokerByProject", $arrBrokerList);
     $projectDetails = array();
     $projectDetails = projectDetailById($projectId);
     $smarty->assign("projectDetails", $projectDetails);
     $smarty->assign("arrBrokerPriceByProject", $arrBrokerPriceByProject);
?>
