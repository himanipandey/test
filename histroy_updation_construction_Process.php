 <?php
    if(isset($_POST['btnHistSave'])){
	  $hist_updation_flag = 0;
	  $updated_ids = array();
	  $error_id = '';
     // print "<pre>".print_r($arrHistory,1)."</pre>";
	 // print "<pre>".print_r($_POST,1)."</pre>";die;
	  
	 //checks for effective dates
	 submitted_date_validation();
	  
	//	array_count_values($array);
	 if(empty($hiserrorMsg)){
	  //arranged submitted-date with completion date
	  foreach($arrHistory as $key=>$val){
	    $posted_submitted_date = $_POST['hist_year_eff'][$key]."-".((strlen($_POST['hist_month_eff'][$key])==1)?"0".$_POST['hist_month_eff'][$key]:$_POST['hist_month_eff'][$key])."-"."01";
	    $posted_completion_date = $_POST['hist_year_comp'][$key]."-".((strlen($_POST['hist_month_comp'][$key])==1)?"0".$_POST['hist_month_comp'][$key]:$_POST['hist_month_comp'][$key])."-"."01";
	    $hist_submitted_date = substr($val['SUBMITTED_DATE'],0,10);
	    $hist_completion_date = substr($val['EXPECTED_COMPLETION_DATE'],0,10);
	    
	    //if completion date changed
	    if(($posted_submitted_date == $hist_submitted_date) && ($posted_completion_date != $hist_completion_date) || ($posted_submitted_date != $hist_submitted_date)){
			
	    //checks for completion dates
		$valid = history_validation($_POST['hist_month_comp'][$key],$_POST['hist_year_comp'][$key],$_POST['hist_month_eff'][$key],$_POST['hist_year_eff'][$key]);
		 		  
		  if(!$valid){	
			$error_id = $val['EXPECTED_COMPLETION_ID'];			
		    break;
		  }else{
		    //updation
		    $qry = "UPDATE ".RESI_PROJ_EXPECTED_COMPLETION."
                                SET	
                                    EXPECTED_COMPLETION_DATE = '".$posted_completion_date."',
                                    SUBMITTED_DATE = '".$posted_submitted_date."'
                                WHERE
                                    PROJECT_ID = '".$projectId."' 
                                AND
                                    phase_id = '".$phaseId."'
                                AND
                                EXPECTED_COMPLETION_ID  = '".$val['EXPECTED_COMPLETION_ID']."'";
             $success = mysql_query($qry);
             if($success){//maintaining Ascending Order
               $hist_updation_flag = 1;
               $updated_ids[]= $val['EXPECTED_COMPLETION_ID'];
			   //check for the lesser submitted date's completion date's value 
			   //if any one of them are greater then update those with the same values
			  $res = mysql_query("select EXPECTED_COMPLETION_ID from resi_proj_expected_completion 
										where project_id = '".$projectId."' and phase_id = '".$phaseId."' 
										 and DATE_FORMAT(SUBMITTED_DATE, '%Y-%m-%d') < '".$posted_submitted_date."' 
										 and DATE_FORMAT(EXPECTED_COMPLETION_DATE, '%Y-%m-%d') > '".$posted_completion_date."'");
			   
			   if(mysql_num_rows($res)){
				   while($rows = mysql_fetch_object($res)){
					 $updated_ids[] = $rows->EXPECTED_COMPLETION_ID;
				   }
				   mysql_query("UPDATE ".RESI_PROJ_EXPECTED_COMPLETION."
                                SET	
                                    EXPECTED_COMPLETION_DATE = '".$posted_completion_date."'
                                WHERE
                                    PROJECT_ID = '".$projectId."' 
                                AND
                                    phase_id = '".$phaseId."'
                                 AND DATE_FORMAT(SUBMITTED_DATE, '%Y-%m-%d') < '".$posted_submitted_date."'") or die(mysql_error());

			   }
				
			 }
		  }
				
		}
	  }
	 }	
	  if(count($hiserrorMsg)>0){	
		$smarty->assign('histerrorMsg',$hiserrorMsg);
		$smarty->assign('error_id',$error_id);
	  }
	  elseif($hist_updation_flag)
	    header("Location:add_project_construction.php?projectId=".$projectId."&phaseId=".$phaseId."&hist=1&updated_ids=".implode("-",$updated_ids));
	  else
	    header("Location:add_project_construction.php?projectId=".$projectId."&phaseId=".$phaseId);
	}
 
  function history_validation($month_expected_completion,$year_expected_completion,$month_effective_date,$year_effective_date){
	global $hiserrorMsg,$current_element;
	
	  
    if(strlen($month_expected_completion)==1)
      $month_expected_completion = "0".$month_expected_completion;
      
    $expectedCompletionDate  = $year_expected_completion."-".$month_expected_completion."-01";
        
    if($month_effective_date == '' && $year_effective_date == '')
      $effectiveDt = date('Y')."-".date('m')."-01";
    else
      $effectiveDt = $year_effective_date."-".$month_effective_date."-01";
    
    /********validation taken from project add/edit page*************/
    $launchDate = $_REQUEST['launchDate'];
    $pre_launch_date = $_REQUEST['pre_launch_date'];
    $expLaunchDate = explode("-",$launchDate);
                
    if($launchDate == '0000-00-00')
      $launchDate = '';
    if($expectedCompletionDate == '0000-00-00')
      $expectedCompletionDate = '';
    if($pre_launch_date == '0000-00-00')
      $pre_launch_date = '';
    if($expectedCompletionDate > $current_element['EXPECTED_COMPLETION_DATE'])
       $hiserrorMsg['CompletionDateGreater'] = "History Updation: Completion date($expectedCompletionDate) to be always less the latest completion date.";
    if($launchDate != '' && ($year_expected_completion < $expLaunchDate[0] 
          || ( $year_expected_completion == $expLaunchDate[0] && $month_expected_completion <= $expLaunchDate[1])) ){
      $hiserrorMsg['CompletionDateGreater'] = "History Updation: Completion date($expectedCompletionDate) to be always greater than launch date.";
    }
    if( $launchDate != '' && $expectedCompletionDate !='' ) {
      $retdt  = ((strtotime($expectedCompletionDate)-strtotime($launchDate))/(60*60*24));
      if( $retdt <= 180 ) {
        $hiserrorMsg['CompletionDateGreater'] = "History Updation: Completion date($expectedCompletionDate) to be always 6 month greater than launch date.";
      }
    }
    if( $fetch_projectDetail[0]['PROJECT_STATUS_ID'] == OCCUPIED_ID_3 || $fetch_projectDetail[0]['PROJECT_STATUS_ID'] == READY_FOR_POSSESSION_ID_4 ) {
      $yearExp = explode("-",$expectedCompletionDate);
      if($yearExp[0] == date("Y") ) {
         if( intval($yearExp[1]) > intval(date("m"))) {
            $hiserrorMsg['CompletionDateGreater'] = "History Updation: Completion date($expectedCompletionDate) cannot be greater current month.";
         }    
      } 
      else if (intval($yearExp[0]) > intval(date("Y")) ) {
          $hiserrorMsg['CompletionDateGreater'] = "History Updation: Completion date($expectedCompletionDate) cannot be greater current month.";
      }
    }
     
    if( $pre_launch_date != '' && $expectedCompletionDate !='') {
      $retdt  = ((strtotime($expectedCompletionDate) - strtotime($pre_launch_date)) / (60*60*24));
      if( $retdt <= 0 ) {
        $hiserrorMsg['CompletionDateGreater'] = "History Updation: Completion date($expectedCompletionDate) to be always greater than Pre Launch date.";
      }
    }
    if(count($hiserrorMsg)>0){		
	  return false;      
    }
    
    return true;
  }
  
  function submitted_date_validation(){
	  global $hiserrorMsg,$arrHistoryAll,$current_element;
	  $submitted_arr = array();
	  foreach($arrHistoryAll as $key=>$val){
	     $submitted_arr[] = $posted_submitted_date = $_POST['hist_year_eff'][$key]."-".((strlen($_POST['hist_month_eff'][$key])==1)?"0".$_POST['hist_month_eff'][$key]:$_POST['hist_month_eff'][$key])."-"."01";	
	     
	     if(($_POST['hist_month_eff'][$key] > date('m') && $_POST['hist_year_eff'][$key] == date('Y')) || $_POST['hist_year_eff'][$key] > date('Y')){
	        $hiserrorMsg['submitted_date'] = "History Updation: Submitted date($posted_submitted_date) can not be greater than the current month."; 
	        break;
	     }
	  }
	  $submitted_arr[] = substr($current_element['SUBMITTED_DATE'],0,10);
	  if(count($submitted_arr) !== count(array_unique($submitted_arr)))
	    $hiserrorMsg['submitted_date'] = "History Updation: Effective dates must be different.";
  }
