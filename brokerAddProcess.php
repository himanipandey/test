<?php
    
    $brokerId = $_REQUEST['brokerId'];
    $smarty->assign("brokerId", $brokerId);
    
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
            if( $hq == ''){
                 $ErrorMsg["hq"] = "Please select city.";
            }
            if(is_array($ErrorMsg)) {
                    // Do Nothing
            } 	 
            else if ($brokerId == ''){		
                $rt = insertBroker($brokerName, $contactPerson, $address,$mobile,$email,$hq,$status);
                if($rt)
                {
                    header("Location:brokerList.php?page=1&sort=all");
                }
                else{
                    $ErrorMsg['dataInsertionError'] = "Please try again there is a problem";
                    header("Location:BuilderList.php");
                }	
                
        }
        else if($brokerId	!= ''){
            $rt = updateBroker($brokerName, $contactPerson, $address,$mobile,$email,$hq,$status,$brokerId);
            if($rt)
            {
                header("Location:brokerList.php?page=1&sort=all");
            }
            else{
                $ErrorMsg['dataInsertionError'] = "Please try again there is a problem in data updation";
                header("Location:BuilderList.php");
            }   
        }
        $smarty->assign("ErrorMsg", $ErrorMsg);
    }else{
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
