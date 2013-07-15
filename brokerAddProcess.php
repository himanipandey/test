<?php
    
    $brokerId = $_REQUEST['brokerId'];
    $smarty->assign("brokerId", $brokerId);
    $brokerIdForMapping = '';
    
    if ($_POST['btnExit'] == "Exit")
    {
        header("Location:brokerList.php");
    }
    if ($_POST['btnSave'] == "Save"){
        $brokerName     =	trim($_POST['brokerName']);
        $contactPerson  =	trim($_POST['contactPerson']);
        $address        =	trim($_POST['address']);
        $mobile         =	trim($_POST['mobile']);
        $email          =	trim($_POST['email']);
        $hq             =	trim($_POST['hq']);
        $status         =	trim($_POST['status']);
        $smarty->assign("brokerName", $brokerName);
        $smarty->assign("contactPerson", $contactPerson);
        $smarty->assign("address", $address);
        $smarty->assign("mobile", $mobile);
        $smarty->assign("email", $email);
        $smarty->assign("hq", $hq);
        $smarty->assign("status", $status);
        if(!preg_match('/^[a-zA-z0-9 ]+$/', $brokerName)){
                $ErrorMsg["brokerName"] = "Special characters are not allowed";
         }
        if(count(checkBrokerByName($brokerName))>0 && $brokerId ==''){
            $ErrorMsg["brokerName"] = "Broker already exists!";
        }
        if( $brokerName == ''){
             $ErrorMsg["brokerName"] = "Please enter Broker name.";
         }
        if(!preg_match('/^[0-9 ]+$/', $mobile)){
             $ErrorMsg["mobile"] = "Please enter valid mobile number";
        }
        if(!$hq) {
             $ErrorMsg["hq"] = "Please select city.";
        }
        
        if(!empty($ErrorMsg)) {
                // Do Nothing
        } 
        else if (empty ($brokerId)){		
            $lastBrokerId = insertBroker($brokerName, $contactPerson, $address,$mobile,$email,$hq,$status);
            if($lastBrokerId != false) {
                $brokerIdFormapping = $lastBrokerId;
            }
            else{
                $ErrorMsg['dataInsertionError'] = "Please try again there is a problem";
            }	 
        }
        else {
            $rt = updateBroker($brokerName, $contactPerson, $address,$mobile,$email,$hq,$status,$brokerId);
            if($rt)
            {
                $brokerIdFormapping = $brokerId;
            }
            else{
                $ErrorMsg['dataInsertionError'] = "Please try again there is a problem in data updation";
            }   
        }

        if($brokerIdFormapping)
        {
            /**********project add code start here***********/
            $arrProjectListInValid = array();
             $arrProjectListValid = array();
            $flag = 0;
            include("dbConfig.php");
            foreach($_REQUEST['multiple_project'] as $k=>$v) {
                if($v !='') {  
                    $flag = 1;
                    $projectdetail = projectdetail($v);
                   if( count($projectdetail) != 0 ) {
                       $arrProjectListValid[] = $v;
                   }
                   else {
                       $arrProjectListInValid[] = $v; 
                   }
                }
            } 
            if($flag == 1) {
                $cnt = 1;
                $comma = ',';
                $qryIns = "INSERT IGNORE INTO BROKER_PROJECT_MAPPING (PROJECT_ID,BROKER_ID,ACTION_DATE) VALUES ";
                foreach($arrProjectListValid as $val) {
                    if($cnt == count($arrProjectListValid))
                        $comma = '';
                    $qryIns .= "($val,$brokerIdFormapping, now())$comma";
                    $cnt++;
                }
                $resIns = mysql_query($qryIns) or die(mysql_error());
                if($resIns)
                    $ErrorMsg['success'] = "Data has been inserted successfully!";
                if(count($arrProjectListInValid)>0) {
                    $str = implode(", ",$arrProjectListInValid);
                    $ErrorMsg['wrongPId'] = "You cant enter wrong project ids which are following: $str";
                }   
            }
        }

        if(count($ErrorMsg)>0) {
           $smarty->assign("ErrorMsg", $ErrorMsg);    
        }
        else {
            header("Location:brokerList.php?page=1&sort=all"); 
        }
        /**********end code project add******************/        
    }
    else {
        $brokerDetail = getBrokerDetailById($brokerId);
        $smarty->assign("brokerName", $brokerDetail[0]['BROKER_NAME']);
        $smarty->assign("contactPerson", $brokerDetail[0]['CONTACT_NAME']);
        $smarty->assign("address", $brokerDetail[0]['BROKER_ADDRESS']);
        $smarty->assign("mobile", $brokerDetail[0]['BROKER_MOBILE']);
        $smarty->assign("email", $brokerDetail[0]['BROKER_EMAIL']);
        $smarty->assign("hq", $brokerDetail[0]['HQ']);
        $smarty->assign("status", $brokerDetail[0]['STATUS']);
    }
?>
