<?php

    function checkEmail($check) {
        $expression = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$/";
        if (preg_match($expression, $check)) {
            return true;
        } else {
            return false;
        } 
    } 
    function checkBrokerByName($brokerName){
        
        $qry = "SELECT * FROM ".BROKER_LIST." WHERE BROKER_NAME = '".$brokerName."'";
        $res = mysql_query($qry) or die(mysql_error()." error in broker fectch by name");
        $arrBrokerByName = array();
        if(mysql_num_rows($res)>0)
            $arrBrokerByName[] = mysql_fetch_assoc($res);
        return $arrBrokerByName;
    }
    function getBrokerDetailById($brokerId){
        $qry = "SELECT * FROM ".BROKER_LIST." WHERE BROKER_ID = '".$brokerId."'";
        $res = mysql_query($qry) or die(mysql_error()." error in broker fectch");
        $arrBrokerByName = array();
        if(mysql_num_rows($res)>0){
            while($data = mysql_fetch_assoc($res)){
                    array_push($arrBrokerByName,$data);
            }
        }
        return $arrBrokerByName;     
    }
    function insertBroker($brokerName, $contactPerson, $address,$mobile,$email,$hq,$status){
        $qryIns =  "INSERT
                        INTO ".BROKER_LIST." 
                    SET
                        BROKER_NAME   = '".$brokerName."',
                        BROKER_ADDRESS= '".$address."',
                        CONTACT_NAME  = '".$contactPerson."',
                        BROKER_MOBILE = '".$mobile."',
                        BROKER_EMAIL  = '".$email."',
                        HQ            = '".$hq."',
                        STATUS        = '".$status."',
                        CREATION_DATE = now()";
        $resIns = mysql_query($qryIns) or die(mysql_error()." error in insert broker");
        if($resIns)
            return mysql_insert_id();
        else
            return false;
    }
    function updateBroker($brokerName, $contactPerson, $address,$mobile,$email,$hq,$status,$brokerId){
        $qryUp =  "UPDATE ".BROKER_LIST." 
                    SET
                        BROKER_NAME   = '".$brokerName."',
                        BROKER_ADDRESS= '".$address."',
                        CONTACT_NAME  = '".$contactPerson."',
                        BROKER_MOBILE = '".$mobile."',
                        BROKER_EMAIL  = '".$email."',
                        HQ            = '".$hq."',
                        STATUS        = '".$status."',
                        CREATION_DATE = now()
                    WHERE 
                        BROKER_ID = $brokerId";
        $resUp = mysql_query($qryUp) or die(mysql_error()." error in update broker");
        if($resUp)
            return true;
        else
            return false;
    }
    function getActiveBrokerList($cityId='', $broker='', $mobile=''){
        $city = '';
        $brokerName = '';
        $mobileNumber = '';
        if( $cityId !='' )
            $city = " AND HQ = $cityId";
        if( $broker != '' )
            $brokerName = " AND BROKER_NAME LIKE '%$broker%'";
        if( $mobile != '' ){
            $mobile = ltrim($mobile,"0");
            $mobileNumber = " AND BROKER_MOBILE LIKE '%$mobile%'";
        }
 
        $qry = "SELECT * FROM ".BROKER_LIST." WHERE STATUS = '1' $city $brokerName $mobileNumber ORDER BY BROKER_NAME ASC";
        $res = mysql_query($qry) or die(mysql_error()." error in active broker list");
        $arrActiveBrokerlist = array();
        if(mysql_num_rows($res)>0){
            while($data = mysql_fetch_assoc($res)){
                    array_push($arrActiveBrokerlist,$data);
            }
        }
        return $arrActiveBrokerlist;
    }
    function getBrokerByProject($projectId){
       $qry = "SELECT * FROM broker_project_mapping 
                WHERE PROJECT_ID = '".$projectId."'";
        $res = mysql_query($qry) or die(mysql_error()." error in  broker list by project id");
        $arrBrokerByProject = array();
        if(mysql_num_rows($res)>0){
            while($data = mysql_fetch_assoc($res)){
                    $arrBrokerByProject[$data['BROKER_ID']] = $data;
            }
        }
        return $arrBrokerByProject;
    }
    function deleteAllBrokerOfProject($projectId){
        $del = "DELETE FROM broker_project_mapping WHERE PROJECT_ID = '".$projectId."'";
        $res = mysql_query($del) or die(mysql_error());
        if($res)
            return true;
        else
            return false;
    }

    function getProjectByBroker($brokerId){
       $qry = "SELECT a.PROJECT_ID,a.BROKER_ID,b.PROJECT_NAME 
                FROM
                    broker_project_mapping a
                LEFT JOIN
                    ".RESI_PROJECT." b
                ON
                    a.PROJECT_ID = b.PROJECT_ID
                WHERE a.BROKER_ID = '".$brokerId."' and b.version = 'Cms'
                ORDER BY b.PROJECT_NAME ASC";
        $res = mysql_query($qry) or die(mysql_error()." error in  project list by broker id");
        $arrProjectByBroker = array();
        if(mysql_num_rows($res)>0){
            while($data = mysql_fetch_assoc($res)){
                    $arrProjectByBroker[] = $data;
            }
        }
        return $arrProjectByBroker;
    }
    
    function getBrokerPriceByProject($projectId){
		
		global $brokerIdList,$maxEffectiveDtAll;
		
		$max_eff_date  = '';
		
		$sql_max_eff_date = mysql_query("select max(EFFECTIVE_DATE) as max_eff_date from project_secondary_price where project_id = '$projectId'");
		if($sql_max_eff_date){
			$sql_max_eff_date = mysql_fetch_object($sql_max_eff_date);
			$max_eff_date =  $sql_max_eff_date->max_eff_date;
		}
		
		$maxEffectiveDtAll = $max_eff_date;
	
		$sql_price_phases = mysql_query("select distinct(rpp.phase_name),psp.phase_id from project_secondary_price psp 
								left join resi_project_phase rpp on psp.phase_id = rpp.phase_id 
											where psp.project_id = '$projectId' and rpp.status = 'Active' and rpp.version='Cms';");
											
											
											
		while($row_price_phases = mysql_fetch_object($sql_price_phases)){
			$sql_all_phase_prices = ""; 
			$phase_price_detail[$row_price_phases->phase_name] = getPhasePriceByProject($projectId,$row_price_phases->phase_id);
		}
		
		
		 $phase_prices = array();
		 
		foreach($phase_price_detail as $key => $arrBrokerPriceByProject){
			$maxEffectiveDt = $arrBrokerPriceByProject[0]['EFFECTIVE_DATE'];
			$minMaxSum = array();
			$latestMonthAllBrokerPrice = array();
			$oneMonthAgoPrice = array();
			$twoMonthAgoPrice = array();
					
			 $dateBreak = explode("-",$maxEffectiveDt );
			 $oneMonthAgo = mktime(0, 0, 0, $dateBreak[1]-1, 1, $dateBreak[0]);
			 $oneMonthAgoDt = date('Y-m-d',$oneMonthAgo);
			 $twoMonthAgo = mktime(0, 0, 0, $dateBreak[1]-2, 1, $dateBreak[0]);
			 $twoMonthAgoDt = date('Y-m-d',$twoMonthAgo);
			 $arrPType = fetch_projectTypes_by_phase($arrBrokerPriceByProject[0]['PROJECT_ID'],$arrBrokerPriceByProject[0]['PHASE_ID']);	
			 
			 foreach($arrBrokerPriceByProject as $k=>$v) {
				if(in_array($v['UNIT_TYPE'],$arrPType)){
					
				 if ($maxEffectiveDt == $v['EFFECTIVE_DATE']) {
					$minMaxSum[$v['UNIT_TYPE']]['minPrice'][] = $v['MIN_PRICE'];
					$minMaxSum[$v['UNIT_TYPE']]['maxPrice'][] = $v['MAX_PRICE'];
					if(count($latestMonthAllBrokerPrice[$v['UNIT_TYPE']][$v['BROKER_ID']]['minPrice']) == 0) {
						
						$latestMonthAllBrokerPrice[$v['UNIT_TYPE']][$v['BROKER_ID']]['minPrice'] = $v['MIN_PRICE'];
						$latestMonthAllBrokerPrice[$v['UNIT_TYPE']][$v['BROKER_ID']]['maxPrice'] = $v['MAX_PRICE'];
					}
					if (!in_array($v['BROKER_ID'],$brokerIdList)) {
						$brokerIdList[] = $v['BROKER_ID'];
					}
					
				 }
				
				 if($oneMonthAgoDt == $v['EFFECTIVE_DATE']){
					$oneMonthAgoPrice[$v['UNIT_TYPE']]['minPrice'][] = $v['MIN_PRICE'];
					$oneMonthAgoPrice[$v['UNIT_TYPE']]['maxPrice'][] = $v['MAX_PRICE'];
				 }
				 
				 if($twoMonthAgoDt == $v['EFFECTIVE_DATE']){
					$twoMonthAgoPrice[$v['UNIT_TYPE']]['minPrice'][] = $v['MIN_PRICE'];
					$twoMonthAgoPrice[$v['UNIT_TYPE']]['maxPrice'][] = $v['MAX_PRICE'];
				 }
				}
			
			 }
			 $phase_prices[$key]['minMaxSum'] = $minMaxSum;
			 $phase_prices[$key]['latestMonthAllBrokerPrice'] = $latestMonthAllBrokerPrice;
			 $phase_prices[$key]['oneMonthAgoPrice'] = $oneMonthAgoPrice;
			 $phase_prices[$key]['twoMonthAgoPrice'] = $twoMonthAgoPrice;
			 $phase_prices[$key]['brokerIdList'] = $brokerIdList;
			 
		}
		
		
		//	die;
		
		return $phase_prices;
	
    }
  
  function getBrokerLatestPriceByProject($projectId, $brokerId = null, $phaseId = null, $effectiveDt = null){
    
    $brokercond1 = ''; $brokercond2 = '';
    $phasecond1 = ''; $phasecond2 = '';
    $effectiveDtcond1 = ''; $effectiveDtcond2 = '';
    $phaseId = (isset($_GET['phaseId']))? mysql_real_escape_string($_GET['phaseId']) : $phaseId;
    if($phaseId) { 
        $phasecond1 = " and phase_id='".$phaseId."'";
        $phasecond2 = " and psp1.phase_id='".$phaseId."'";
    }
    
    if($effectiveDt) { 
        $effectiveDtcond1 = " and EFFECTIVE_DATE='".$effectiveDt."'";
        $effectiveDtcond2 = " and psp1.EFFECTIVE_DATE='".$effectiveDt."'";
    }
    
    if($brokerId){
		$brokercond1 = " and broker_id='".$brokerId."'";
        $brokercond2 = " and psp1.broker_id='".$brokerId."'";
	}

      $qry = "select * from project_secondary_price psp1 
    join (select UNIT_TYPE,max(ID) as ID from project_secondary_price where project_id = '$projectId' ".$phasecond1." ".$effectiveDtcond1." ".$brokercond1." group by UNIT_TYPE) as psp2 on psp1.ID = psp2.ID 
    left join resi_project_phase rpp on rpp.phase_id  = psp1.phase_id
    where psp1.project_id = '$projectId' and rpp.status='active' ".$phasecond2."  ".$effectiveDtcond1." ".$brokercond1." and rpp.version='Cms' order by psp1.UNIT_TYPE ASC";

        $res = mysql_query($qry) or die(mysql_error());
        $arrBrokerPriceByProject = array();

        while($data = mysql_fetch_assoc($res)){
            $arrBrokerPriceByProject[$data['UNIT_TYPE']]= $data;
        }

        return $arrBrokerPriceByProject;
    }
    function getPhasePriceByProject($projectId,$phase_id){
		$qry = "SELECT * FROM 
                    project_secondary_price   
                WHERE
                    PROJECT_ID = $projectId AND PHASE_ID = $phase_id 
                      ORDER BY EFFECTIVE_DATE DESC, ID DESC";

        $res = mysql_query($qry) or die(mysql_error());
        $arrBrokerPriceByProject = array();

        while($data = mysql_fetch_assoc($res)){
            array_push($arrBrokerPriceByProject,$data);
        }
	
		return $arrBrokerPriceByProject;
	}
	function fetch_projectTypes_by_phase($projectId,$phaseId=null) {
		$condPhase = '';
	if($phaseId)
		$condPhase =" and phase_id ='$phaseId' ";
		
    $qryopt = "select distinct(rpp.option_type) from resi_project_options rpp
  inner join listings lst where lst.option_id = rpp.options_id
    and rpp.project_id = '$projectId' $condPhase and lst.status = 'Active' order by rpp.option_type";
    
    $resopt = mysql_query($qryopt) or die(mysql_error());
    $arrOptions = array();
    while ($data = mysql_fetch_object($resopt)) {
        $arrOptions[]=$data->option_type;
    }
    
    return $arrOptions;
}
?>
