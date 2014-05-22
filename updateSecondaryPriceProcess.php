<?php 
    $projectId  = $_REQUEST['projectId'];
    if(isset($_REQUEST['btnExit'])){
        header("Location:secondary_price.php?projectId=$projectId");
    }
    $effectiveDt = '';
    $month = '';
    $year  = '';
    $phaseId = '';
    if(isset($_REQUEST['search'])){ //search for month, year and broker
         $brokerId   = $_REQUEST['brokerId'];
         $effectiveDt= $_REQUEST['year']."-".$_REQUEST['month']."-01 00:00:00";
         $year = $_REQUEST['year'];
         $month = $_REQUEST['month'];
         $phaseId = $_REQUEST['phaseSelect'];
         $arrBrokerPriceByProject = getBrokerLatestPriceByProject($projectId, $brokerId, $phaseId, $effectiveDt);
      
       
    }   // die;
    if(isset($_REQUEST['submit'])){ //code start for update price
    
		$minPrice = $_REQUEST['minPrice'];
        $maxPrice = $_REQUEST['maxPrice'];
        $brokerId  = $_REQUEST['brokerId'];
        $oldEffectiveDate  = $_REQUEST['year']."-".$_REQUEST['month']."-01 00:00:00";
        $year = $_REQUEST['year'];
        $month = $_REQUEST['month'];
        $phaseId = $_REQUEST['phaseSelect'];
        $flag = 0;
        $blank_error_flag = 1;
        $comma = ',';
        $cnt = 0;
        $arrMinPrice = array();
        $arrMaxPrice = array();
        $arrMeanPrice = array();
        foreach($_REQUEST['unitType'] as $key=>$val){
            $arrMinPrice[] = $_REQUEST['minPrice'][$key];
            $arrMaxPrice[] = $_REQUEST['maxPrice'][$key];
            
            $arrMeanPrice[] = ($_REQUEST['minPrice'][$key]+$_REQUEST['maxPrice'][$key])/2;
       
            if($_REQUEST['minPrice'][$key] != '' AND $_REQUEST['maxPrice'][$key] != ''){
				$blank_error_flag = 0;
				$percentDiff = ($_REQUEST['maxPrice'][$key] - $_REQUEST['minPrice'][$key]) /(($_REQUEST['maxPrice'][$key] + $_REQUEST['minPrice'][$key]) / 2) * 100;
		               
                if($_REQUEST['minPrice'][$key] > $_REQUEST['maxPrice'][$key]) {
                    $flag = 2;
                }elseif( $phaseID == -1){
					$flag=4;
					
				}else if($percentDiff > 20){
					$flag = 3;
				}
                else {
                    $minPrice = $_REQUEST['minPrice'][$key];
                    $maxPrice = $_REQUEST['maxPrice'][$key];
                    $typeName =   $val;
                
                    $attributes[]= array(
                        'project_id'=>$projectId, 
                        'phase_id' => $phaseId,
                        'broker_id'=>$brokerId, 
                        'unit_type'=>$typeName, 
                        'effective_date'=>$oldEffectiveDate,
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
					$errorPrice = "<font color = 'red'>Effective Date must be valid and greater than or equal to 2013-08-01 !</font>";
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
        $arrBrokerPriceByProject = getBrokerLatestPriceByProject($projectId, $brokerId, $phaseId, $oldEffectiveDate);
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
    $smarty->assign("phaseSelect",  $phaseId);
    //code for distinct unit for a project
    $arrProjectType = fetch_projectOptions($projectId);
    $arrPType = array(); 
    foreach($arrProjectType as $val){
        $exp = explode("-",$val);
        if(!in_array(trim($exp[0]),$arrPType))
            array_push($arrPType,trim($exp[0]));
    }

    $arrPType = fetch_projectTypes_by_phase($projectId,$phaseId);
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
?>
