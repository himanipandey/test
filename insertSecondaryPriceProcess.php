<?php 
    $projectId  = $_REQUEST['projectId'];
    $brokerId   = $_REQUEST['brokerId'];
    $arrBrokerPriceByProject = getBrokerLatestPriceByProject($projectId, $brokerId);
    if(isset($_REQUEST['btnExit'])){
        header("Location:secondary_price.php?projectId=$projectId");
    }
    if(isset($_REQUEST['submit'])){
	    $minPrice = $_REQUEST['minPrice'];
        $maxPrice = $_REQUEST['maxPrice'];
        $brokerId  = $_REQUEST['brokerId'];
        $flag = 0;
        $blank_error_flag = 1;
        $arrMinPrice = array();
        $arrMaxPrice = array();
        $arrMeanPrice = array();
        $effectiveDate = $_REQUEST['effectiveDate'];
         $phaseID = $_REQUEST['phaseSelect'];
        $exp = explode("-",$effectiveDate);
        $effMonthYear = $exp[0]."-".$exp[1]."-01";
        
        foreach($_REQUEST['unitType'] as $key=>$val){
            $arrMinPrice[] = $_REQUEST['minPrice'][$key];
            $arrMaxPrice[] = $_REQUEST['maxPrice'][$key];
            
            $arrMeanPrice[] = ($_REQUEST['minPrice'][$key]+$_REQUEST['maxPrice'][$key])/2;
       
            if($_REQUEST['minPrice'][$key] != '' AND $_REQUEST['maxPrice'][$key] != ''){
				$blank_error_flag = 0;
			
				$price_diff = ($_REQUEST['minPrice'][$key]*20/100);
		               
                if($_REQUEST['minPrice'][$key] > $_REQUEST['maxPrice'][$key]) {
                    $flag = 2;
                }elseif( $phaseID == -1){
					$flag=4;
					
				}else if($_REQUEST['maxPrice'][$key] > ($price_diff+$_REQUEST['minPrice'][$key])){
					$flag = 3;
				}
                else {
                    $minPrice = $_REQUEST['minPrice'][$key];
                    $maxPrice = $_REQUEST['maxPrice'][$key];
                    $typeName =   $val;
                
                    $attributes[]= array(
                        'project_id'=>$projectId, 
                        'phase_id' => $phaseID,
                        'broker_id'=>$brokerId, 
                        'unit_type'=>$typeName, 
                        'effective_date'=>$effMonthYear,
                        'min_price'=>$minPrice, 
                        'max_price'=>$maxPrice,
                        'last_modified_by'=>$_SESSION['adminId'],
                        'LAST_MODIFIED_DATE'=>'now()'
                    );
                }
            }
            
        }
        if($blank_error_flag == 1)
			$flag = 1;
        $errorPrice = '';
        if($flag == 0){
			ProjectSecondaryPrice::transaction(function(){
				global $attributes,$errorPrice;
				foreach($attributes as $key=>$attribute){
					$res = ProjectSecondaryPrice::insertUpdate($attribute);
				}
				if($res)
					$errorPrice = "<font color = 'green'>Price has been inserted successfully!</font>";
				else
					$errorPrice = "<font color = 'red'>Problem in price insertion please try again!</font>";
			});
			
        }else{
                if($flag == 2)
                    $errorPrice = "<font color = 'red'>Minimum price should be less them max price!</font>";
                elseif($flag == 3)
                    $errorPrice = "<font color = 'red'>The difference between Max price and Min Price must be within 20%.</font>";
                elseif($flag == 4)
                    $errorPrice = "<font color = 'red'>Please select Phase.</font>";
                else
                    $errorPrice = "<font color = 'red'>Min/Max price cant blank!</font>";
        }

        $arrBrokerPriceByProject = getBrokerLatestPriceByProject($projectId, $brokerId);
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
    $phase_id = ($arrBrokerPriceByProject[0]['PHASE_ID'])?$arrBrokerPriceByProject[0]['PHASE_ID']:$_REQUEST['phaseId'];
    $arrPType = fetch_projectTypes_by_phase($projectId,$phase_id);
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
    $phaseDetail = array();
	$phases = ResiProjectPhase::find("all", array("conditions" => array("project_id" => $projectId, "status" => 'Active'), "order" => "phase_name asc"));
	foreach($phases as $p){
		array_push($phaseDetail, $p->to_custom_array());
	}
	$phases = Array();
	$old_phase_name = '';
	foreach ($phaseDetail as $k => $val) {
		$p = Array();
		$p['id'] = $val['PHASE_ID'];
		$p['name'] = $val['PHASE_NAME'];
		array_push($phases, $p);
	}
	$smarty->assign("phases", $phases);
	
	 if(isset($_GET['phaseId'])) { 
		$smarty->assign("currPhaseId", $_GET['phaseId']);	
	 }
	
     $smarty->assign("allBrokerByProject", $arrBrokerList);
     $projectDetails = array();
     $projectDetails = projectDetailById($projectId);
     $smarty->assign("projectDetails", $projectDetails);
     $smarty->assign("arrBrokerPriceByProject", $arrBrokerPriceByProject);
?>
