<?php

/**
 * @author AKhan
 * @copyright 2013
 */

    $accessSeller = '';
    
    $smarty->assign("accessSeller",$accessSeller);
    
    $sellerCompanyId = '';
    if(!empty($_REQUEST['sellerCompanyId']))
        $sellerCompanyId = $_REQUEST['sellerCompanyId'];
    
    $smarty->assign("sellerCompanyId", $sellerCompanyId);
    
    $sellerIdForMapping = '';
    
    if ($_POST['btnExit'] == "Exit")
    {
        header("Location:SellerCompanyList.php");
    }
    if ($_POST['btnSave'] == "Save"){

        
        @extract($_POST);
        $smarty->assign("seller_cmpny", $seller_cmpny);
        $smarty->assign("seller_name", $seller_name);
        $smarty->assign("type", $type);
        $smarty->assign("status", $status);
        $smarty->assign("addressline1", $addressline1);
        $smarty->assign("addressline2", !empty($addressline2)?$addressline2:'');
        $smarty->assign("city_id", $city_id);
        $smarty->assign("pincode", $pincode);
        $smarty->assign("phone1", $phone1);
        $smarty->assign("phone2", $phone2);
        $smarty->assign("mobile", $mobile);
        $smarty->assign("email", $email);
        $smarty->assign("fax", $fax);
        $smarty->assign("rating", $rating);
        $smarty->assign("auto", $auto);
        $smarty->assign("rate", $rate);
        $smarty->assign("rate", $rate);
        $smarty->assign("qualification", $qualification);
        $smarty->assign("sellerCompanyId", $sellerCompanyId);
        $smarty->assign("cityhiddenArr", $cityhiddenArr);
        $smarty->assign("brokerhiddenArr", $brokerhiddenArr);
        $smarty->assign("active_since", $active_since);
        $smarty->assign("addressid", $addressid);
        $smarty->assign("brkr_cntct_id", $brkr_cntct_id);
        
        $sellerChk = SellerCompany::chkName($seller_name);
        //print'<pre>';
//        print_r($_POST);
//        
//        print_R($sellerChk);
//        die;
        if(!empty($sellerChk))
        {
            foreach($sellerChk as $key => $val)
            {
                break;
                if($val['id'] != $sellerCompanyId && count($sellerChk)>0)
     			{
     			    $ErrorMsg["seller_name"] = "Seller Company already exists!";
                    break;
     			}
                if(count($sellerChk)>0 && $sellerCompanyId =='')
                {
                    $ErrorMsg["seller_name"] = "Seller Company already exists!";
                    break;    
                }
                        
            }
        }
        
        if(empty($seller_cmpny)) {
             $ErrorMsg["seller_cmpny"] = "Please enter Company name.";
        }
        else if(empty($seller_name)){
             $ErrorMsg["seller_name"] = "Please enter Seller name.";
        }
        
        /** --- OFFICE Addres Details Validations STARTS---*/
        
        if(!empty($copy) && $copy == 'off')
        {
            if( empty($addressline1)){
                 $ErrorMsg["addressline1"] = "Please enter Address.";
            }
            else if(!is_numeric($phone1)) {
                 $ErrorMsg["phone1"] = "Phone number must be numeric.";
            }
            else if(!preg_match("/^[0-9]{0,12}$/",$phone1)) {
    			 $ErrorMsg["phone1"] = "Please enter a valid phone number.";
    		}
            else if($phone2 != '' && !is_numeric($phone2)) {
                 $ErrorMsg["phone2"] = "Phone number must be numeric.";
            }
            else if($phone2 != '' && !preg_match("/^[0-9]{0,12}$/",$phone2)) {
    			 $ErrorMsg["phone2"] = "Please enter a valid phone number.";
    		}
            else if(empty($city_id)) {
                 $ErrorMsg["city_id"] = "Please select city.";
            }
            else if($pincode != '' && !is_numeric($pincode)) {
                 $ErrorMsg["pincode"] = "Pin code must be numeric.";
            }
            else if($pincode != '' && !preg_match("/^[0-9]{0,12}$/",$pincode)) {
    			 $ErrorMsg["pincode"] = "Please enter a valid pin code.";
    		}
            
        }
        
        if(empty($mobile)) {
             $ErrorMsg["mobile"] = "Please enter mobile number.";
        }
        else if($mobile != '' && !preg_match("/^[0-9]{0,10}$/",$mobile)) {
    			 $ErrorMsg["mobile"] = "Please enter a valid mobile number.";
        }
        else if($email != '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
			 $ErrorMsg["email"] = "Please enter a valid email address.";
		}
        
        /** --- OFFICE Address Details Validations ENDS---*/
        
       
        //print'<pre>';
//        print_r($_POST);
//        print_r($ErrorMsg);
//        die;

        if(!empty($ErrorMsg)) {
                 //Do Nothing
        } 
        else if (empty($sellerCompanyId)){	
            $final_rating = '';
            $rateoption = '';
            if(!empty($rating))
            {
                $final_rating = $rating;
                $rateoption = 'auto';
            }
            else
            {
                $final_rating = $rate;
                $rateoption = 'forced';
            }    
            
            ResiProject::transaction(function(){
                
                global $seller_cmpny , $seller_name , $type , $status , $addressline1 , $addressline2 ,$city_id,$pincode,$phone1,$phone2,$mobile,$email,$active_since,$qualification,$final_rating;
               
            if(!empty($active_since))
            {
                $active_since = explode("/" , $active_since);
                $active_since = $active_since[2]."-".$active_since[1]."-".$active_since[0];    
            }
                                    
            $sql_seller_company = @mysql_query("INSERT INTO `agents` SET
                                                `status` = '".$status."',
                                                `broker_id` = ".$seller_cmpny.",
                                                `academic_qualification_id` = ".$qualification.",
                                                `chkAddr` = ".$copy.",
                                                `rating` = ".$final_rating.",
                                                `rateoption` = '$rateoption',
                                                `seller_type` = '$type',
                                                `active_since` = '$active_since',
                                                `updated_by` = '".$_SESSION['adminId']."',
                                                `created_at` = '".date('Y-m-d H:i:s')."'"
                                            );
            $seller_id = mysql_insert_id();          
             
            
            //$seller_id = 4;
            if($seller_id != false) {
                $sellerIdFormapping = $seller_id;
                
                /** -- Primary Address Entry Start -- */
                /** -- Add the addresses in addresses table -- */
                    
                    $sql_address = @mysql_query("INSERT INTO `addresses` SET 
                                                            `table_name` = 'agents',
                                                            `table_id` = ".$sellerIdFormapping.",
                                                            `address_line_1` = '$addressline1',
                                                            `address_line_2` = '$addressline2',
                                                            `city_id` = ".$city_id.",
                                                            `pincode` = $pincode,
                                                            `updated_by` = ".$_SESSION['adminId'].",
                                                            `created_at` = '".date('Y-m-d')."'");
                    
                    
                    
                    $address_id = mysql_insert_id();
                    
                    
                    $sql_broker_contact = @mysql_query("INSERT INTO `broker_contacts` SET 
                                                            `broker_id` = ".$seller_id.",
                                                            `name` = '".$seller_name."',
                                                            `contact_email` = '$email',
                                                            `type` = 'Agent',
                                                            `updated_by` = ".$_SESSION['adminId'].",
                                                            `created_at` = '".date('Y-m-d')."'");
                    
                    
                    
                    $broker_contact_id = mysql_insert_id();
                    
                    if(!empty($broker_contact_id))
                    {
                        /** -- Insert values for contact_numbers table type=>phone1 --  */
                        $sql_contact_number = @mysql_query("INSERT INTO `contact_numbers` SET
                                                            `table_name` = 'broker_contacts',
                                                            `table_id` = $broker_contact_id,
                                                            `contact_no` = $phone1,
                                                            `type` = 'phone1',
                                                            `created_at` = '".date('Y-m-d')."',
                                                            `updated_by` = ".$_SESSION['adminId']);
                        
                        /** -- Insert values for contact_numbers table type=>phone2 --  */
                        $sql_contact_number = @mysql_query("INSERT INTO `contact_numbers` SET
                                                            `table_name` = 'broker_contacts',
                                                            `table_id` = $broker_contact_id,
                                                            `contact_no` = $phone2,
                                                            `type` = 'phone2',
                                                            `created_at` = '".date('Y-m-d')."',
                                                            `updated_by` = ".$_SESSION['adminId']);
                        
                        
                        /** -- Insert values for contact_numbers table type=>mobile --  */
                        
                        $sql_contact_number = @mysql_query("INSERT INTO `contact_numbers` SET
                                                            `table_name` = 'broker_contacts',
                                                            `table_id` = $broker_contact_id,
                                                            `contact_no` = $mobile,
                                                            `type` = 'mobile',
                                                            `created_at` = '".date('Y-m-d')."',
                                                            `updated_by` = ".$_SESSION['adminId']);
                        $contact_number_id = mysql_insert_id();
                        /** -- Update broker_contacts table by contact_number_id --  */
                        
                        $sql_broker_contact = @mysql_query("UPDATE `broker_contacts` SET 
                                                            `contact_number_id` = ".$contact_number_id." WHERE id = ".$broker_contact_id);
                    }
            }
            else{
                $ErrorMsg['dataInsertionError'] = "Please try again there is a problem";
            }
            
            });
            
            
        	 
        }
        else {
            
            $final_rating = '';
            $rateoption = '';
            if(!empty($rating))
            {
                $final_rating = $rating;
                $rateoption = 'auto';
            }
            else
            {
                $final_rating = $rate;
                $rateoption = 'forced';
            }    
            
            //print'<pre>';
//            print_r($_POST);
//            die;
            
            ResiProject::transaction(function(){
                
                global $seller_cmpny , $seller_name , $type , $status , $addressline1 , $addressline2 ,$city_id,$pincode,$phone1,$phone2,$mobile,$email,$active_since,$qualification,$final_rating,$sellerCompanyId,$addressid,$brkr_cntct_id,$rateoption;
               
            if(!empty($active_since))
            {
                $active_since = explode("/" , $active_since);
                $active_since = $active_since[2]."-".$active_since[1]."-".$active_since[0];    
            }
            $copy = !empty($copy)?$copy:'off';
            
            
            $sql_seller_company = @mysql_query("UPDATE `agents` SET
                                                `status` = '".$status."',
                                                `broker_id` = ".$seller_cmpny.",
                                                `academic_qualification_id` = ".$qualification.",
                                                `chkAddr` = '".$copy."',
                                                `rating` = '".$final_rating."',
                                                `rateoption` = '$rateoption',
                                                `seller_type` = '$type',
                                                `active_since` = '$active_since',
                                                `updated_by` = '".$_SESSION['adminId']."'
                                                 WHERE id=".$sellerCompanyId);
            
            $seller_id = $sellerCompanyId;          
             
            
            //$seller_id = 4;
            if($seller_id != false) {
                $sellerIdFormapping = $seller_id;
                
                /** -- Primary Address Entry Start -- */
                /** -- Add the addresses in addresses table -- */
                    
                    $sql_address = @mysql_query("UPDATE `addresses` SET 
                                                            `address_line_1` = '$addressline1',
                                                            `address_line_2` = '$addressline2',
                                                            `city_id` = ".$city_id.",
                                                            `pincode` = $pincode,
                                                            `updated_by` = ".$_SESSION['adminId']."
                                                             WHERE id=".$addressid);
                    
                    
                   
                    $sql_broker_contact = @mysql_query("UPDATE `broker_contacts` SET 
                                                            `broker_id` = ".$seller_id.",
                                                            `name` = '".$seller_name."',
                                                            `contact_email` = '$email',
                                                            `type` = 'Agent',
                                                            `updated_by` = ".$_SESSION['adminId']."
                                                             WHERE id=".$brkr_cntct_id);
                    
                    
                    
                    $broker_contact_id = $brkr_cntct_id;
                    
                    if(!empty($broker_contact_id))
                    {
                        $contacts = array();
                        $sql2 = @mysql_query("SELECT * FROM contact_numbers AS cn WHERE cn.table_name = 'broker_contacts' AND cn.table_id = '".$row['brkr_cntct_id']."'");
                
                        if(@mysql_num_rows($sql2) > 0)
                        {
                            while($row1 = @mysql_fetch_assoc($sql2))
                            {
                                /** -- Insert values for contact_numbers table type=>phone1 --  */
                                if($row1['type'] == "phone1") 
                                {
                                    $sql_contact_number = @mysql_query("UPDATE `contact_numbers` SET
                                                            `contact_no` = $phone1,
                                                            `updated_by` = ".$_SESSION['adminId']." WHERE id=".$row1['id']);
                                }/** -- Insert values for contact_numbers table type=>phone2 --  */
                                else if($row1['type'] == "phone2")
                                {
                                    $sql_contact_number = @mysql_query("UPDATE `contact_numbers` SET
                                                            `contact_no` = $phone2,
                                                            `updated_by` = ".$_SESSION['adminId']." WHERE id=".$row1['id']);
                                }/** -- Insert values for contact_numbers table type=>mobile --  */
                                else if($row1['type'] == "mobile")
                                {
                                    $sql_contact_number = @mysql_query("UPDATE `contact_numbers` SET
                                                            `contact_no` = $mobile,
                                                            `updated_by` = ".$_SESSION['adminId']." WHERE id=".$row1['id']);
                                    $contact_number_id = $row1['id'];
                                }
                            }
                        }
                    }
            }
            else{
                $ErrorMsg['dataInsertionError'] = "Please try again there is a problem";
            }
            
            });
        }
         
            
        if(count($ErrorMsg)>0) {
            $smarty->assign("ErrorMsg", $ErrorMsg);    
        }
        else {
            header("Location:SellerCompanyList.php?page=1&sort=all"); 
        }
        /**********end code project add******************/        
    }
    else
    {
        
    }
?>

