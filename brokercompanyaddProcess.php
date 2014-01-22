<?php

/**
 * @author AKhan
 * @copyright 2013
 */

    $accessBroker = '';
    $image_id = '';
    $smarty->assign("accessBroker",$accessBroker);
    //print'<pre>';
//    print_r($_POST);
//    die;
    $brokerCompanyId = '';
    if(!empty($_REQUEST['brokerCompanyId']))
        $brokerCompanyId = $_REQUEST['brokerCompanyId'];
    
    $smarty->assign("brokerCompanyId", $brokerCompanyId);
    
    $brokerIdForMapping = '';
    
    if ($_POST['btnExit'] == "Exit")
    {
        header("Location:BrokerCompanyList.php");
    }
    if ($_POST['btnSave'] == "Save"){


//        //print_r(json_decode(base64_decode($_REQUEST['xcp_ids'])));
//        print_r(json_decode(base64_decode($_REQUEST['xcp_phone1'])));
//        print_r(json_decode(base64_decode($_REQUEST['xcp_phone2'])));
//        print_r(json_decode(base64_decode($_REQUEST['xcp_email'])));
//        print_r(json_decode(base64_decode($_REQUEST['xcp_fax'])));
//        print_r(json_decode(base64_decode($_REQUEST['xcp_mobile'])));
//        print_r($ErrorMsg);
  //      die;
        
        $brokerCName    =	trim($_POST['name']);
        $pan            =	trim($_POST['pan']);
        $description    =   trim($_POST['description']);
        $status         =	trim($_POST['status']);
        $addressline1   =	trim($_POST['addressline1']);
        $addressline2   =	trim($_POST['addressline2']);
        $city_id        =	trim($_POST['city_id']);
        $pincode        =	trim($_POST['pincode']);
        $phone1         =   trim($_POST['phone1']);
        $phone2         =   trim($_POST['phone2']);
        $email          =	trim($_POST['email']);
        $fax            =	trim($_POST['fax']);
        $active_since   =	trim($_POST['active_since']);
        $logo           =   $_FILES['logo']; 
        
        $primary_address_id = trim($_POST['primary_address_id']);
        $fax_number_id = trim($_POST['fax_number_id']);
        $primary_broker_contact_id = trim($_POST['primary_broker_contact_id']);
        $primary_contact_number_id = trim($_POST['primary_contact_number_id']);
                
        $cp_name        =   json_decode(base64_decode($_POST['xcp_name']));
        $cp_phone1      =   json_decode(base64_decode($_POST['xcp_phone1']));
        $cp_phone2      =   json_decode(base64_decode($_POST['xcp_phone2']));
        $cp_email       =	json_decode(base64_decode($_POST['xcp_email']));
        $cp_fax         =	json_decode(base64_decode($_POST['xcp_fax']));
        $cp_mobile      =	json_decode(base64_decode($_POST['xcp_mobile']));
        $cp_ids         =	json_decode(base64_decode($_POST['xcp_ids']));
        $acontactids    =   !empty($_POST['acontactids'])?json_decode(base64_decode($_POST['acontactids'])):array();
        $rcontacts      =   !empty($_POST['rcontactids'])?json_decode(base64_decode($_POST['rcontactids'])):array();
        
        if(empty($rcontacts))
            $finalcontacts  =  $cp_ids;
        else
            $finalcontacts  =  ($cp_ids && $rcontacts)?array_diff($cp_ids , $rcontacts):array();
        
        if(!empty($acontactids))    
            $finalcontacts  =   array_diff($finalcontacts , $acontactids);
        
        $cc_phone       =   trim($_POST['cc_phone']);
        $cc_email       =	trim($_POST['cc_email']);
        $cc_fax         =	trim($_POST['cc_fax']);
        $cc_mobile      =	trim($_POST['cc_mobile']);
        $citypkidArr    =   !empty($_POST['citypkidArr'])?json_decode(base64_decode($_POST['citypkidArr'])):array();
        $remove_citylocids = !empty($_POST['remove_citylocids'])?json_decode(base64_decode($_POST['remove_citylocids'])):array();
        //print'<pre>';
//        print_r($remove_citylocids);
//        die;
        $image_id       =  trim($_POST['imgid']);
        
        if(empty($remove_citylocids))
            $finaladdcitylocids = $citypkidArr;
        else
            $finaladdcitylocids = ($citypkidArr && $remove_citylocids)?array_diff($citypkidArr , $remove_citylocids):array();
        
        $smarty->assign("name", $brokerCName);
        $smarty->assign("pan", $pan);
        $smarty->assign("description", $description);
        $smarty->assign("status", $status);
        $smarty->assign("addressline1", $addressline1);
        $smarty->assign("addressline2", $addressline2);
        $smarty->assign("city_id", $city_id);
        $smarty->assign("pincode", $pincode);
        $smarty->assign("phone1", $phone1);
        $smarty->assign("phone2", $phone2);
        $smarty->assign("email", $email);
        $smarty->assign("fax", $fax);
        $smarty->assign("active_since", $active_since);
        
        $smarty->assign("cc_phone", $cc_phone);
        $smarty->assign("cc_email", $cc_email);
        $smarty->assign("cc_fax", $cc_fax);
        $smarty->assign("cc_mobile", $cc_mobile);
        
        $smarty->assign("image_id", $image_id);
        
        $brokerChk = BrokerCompany::chkName($brokerCName);
                
        if(!empty($brokerChk))
        {
            foreach($brokerChk->errors as $key => $val)
            {
                if($val->id != $brokerCompanyId && count($brokerChk)>0)
     			{
     			    $ErrorMsg["name"] = "Broker Company already exists!";
                    break;
     			}
                if(count($brokerChk)>0 && $brokerCompanyId =='')
                {
                    $ErrorMsg["name"] = "Broker Company already exists!";
                    break;    
                }
                        
            }
        }
        
        if(empty($brokerCName)){
             $ErrorMsg["name"] = "Please enter Broker Company name.";
        }
        else if(empty($description)) {
             $ErrorMsg["description"] = "Please enter description.";
        }
        
        /** --- OFFICE Addres Details Validations STARTS---*/
        if( empty($addressline1)){
             $ErrorMsg["addressline1"] = "Please enter Broker Company Address.";
         }
        else if(empty($phone1)) {
             $ErrorMsg["phone1"] = "Please enter phone number one.";
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
        else if($fax != '' && !is_numeric($fax)) {
             $ErrorMsg["fax"] = "Fax number must be numeric.";
        }
        else if($fax != '' && !preg_match("/^[0-9]{0,12}$/",$fax)) {
			 $ErrorMsg["fax"] = "Please enter a valid fax number.";
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
        else if($email != '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
			 $ErrorMsg["email"] = "Please enter a valid email address.";
		}
        
        /** --- OFFICE Address Details Validations ENDS---*/
        
        /** --- Contact Person Details Validations STARTS---*/
        /** --- Contact Person Details Validations ENDS---*/
        
        /** --- Customer Care Details Validations STARTS---*/
        
        if($cc_phone != '' && !is_numeric($cc_phone)) {
             $ErrorMsg["cc_phone"] = "Phone number must be numeric.";
        }
        else if($cc_phone != '' && !preg_match("/^[0-9]{0,12}$/",$cc_phone)) {
			 $ErrorMsg["cc_phone"] = "Please enter a valid phone number.";
		}
        else if($cc_fax != '' && !is_numeric($cc_fax)) {
             $ErrorMsg["cp_fax"] = "Fax number must be numeric.";
        }
        else if($cc_fax != '' && !preg_match("/^[0-9]{0,12}$/",$cc_fax)) {
			 $ErrorMsg["cc_fax"] = "Please enter a valid fax number.";
		}
        else if($cc_mobile != '' && !is_numeric($cc_mobile)) {
             $ErrorMsg["cc_mobile"] = "Mobile number must be numeric.";
        }
        else if($cc_mobile != '' && !preg_match("/^[0-9]{10}$/",$cc_mobile)) {
			 $ErrorMsg["cc_mobile"] = "Please enter a valid mobile number.";
		}
        else if($cc_email != '' && !filter_var($cc_email, FILTER_VALIDATE_EMAIL)) {
			 $ErrorMsg["cc_email"] = "Please enter a valid email address.";
		}
        
        /** --- Customer Care Details Validations ENDS---*/
        

        if(!empty($ErrorMsg)) {
                 //Do Nothing
        } 
        else if (empty($brokerCompanyId)){	
            
            ResiProject::transaction(function(){
                global $brokerCName,$pan,$description,$status,$addressline1,$addressline2,$city_id,$pincode,$phone1,$phone2,$email,$fax,$active_since,$primary_address_id,$fax_number_id,$primary_broker_contact_id,$primary_contact_number_id,$cp_name,$cp_phone1,$cp_phone2,$cp_email,$cp_fax,$cp_mobile,$cp_ids,$acontactids,$rcontacts,$finalcontacts,$cc_phone,$cc_email,$cc_fax,$cc_mobile,$citypkidArr,$remove_citylocids,$finaladdcitylocids,$logo,$newImagePath,$s3,$image_id;
            //print'<pre>';
//            print_r($_POST);
//            die;
            if(!empty($active_since))
            {
                $active_since = explode("/" , $active_since);
                $active_since = $active_since[2]."-".$active_since[1]."-".$active_since[0];    
            }
            
            $sql_broker_company = @mysql_query("INSERT INTO `brokers` SET 
                                            `broker_name` = '".$brokerCName."',
                                            `status` = '".$status."',
                                            `description` = '".$description."',
                                            `pan` = '".$pan."',
                                            `primary_email` = '".$email."',
                                            `active_since` = '".$active_since."',
                                            `created_at` = '".date('Y-m-d H:i:s')."',
                                            `updated_by` = '".$_SESSION['adminId']."'
                                    ")or die(mysql_error());            
            
            $broker_id = @mysql_insert_id();
            //$broker_id = 4;
            
            $primary_email = !empty($email)?$email:'';
            if($broker_id != false) {
                
                if(!empty($logo['name']))
                {
                    list($imgname , $extension) = explode("." , $logo['name']);
                    $newimgName = $newImagePath.time(). '.' .$extension; 
                    
                    $flag = move_uploaded_file($logo["tmp_name"], $newImagePath.time(). '.' .$extension);
                    
                    if($flag != '')
                    {
                        $s3upload = new ImageUpload($newimgName, array("s3" => $s3,
                                                "image_path" => str_replace($newImagePath, "", $newimgName),
                                                "object" => "brokerCompany", "object_type" => "brokerCompany",
                                                "object_id" => $broker_id, "image_type" => "logo"));
                       
                        $response = $s3upload->upload();
                        $image_id = $response["service"]->data();
                        $image_id = $image_id->id;
                        //print'<pre>';
//                        print_r($response);
//                        print_r($image_id);
//                        die;
                    }
                    //print'<pre>';
//                    print_r($response);
//                    print_r($image_id);
//                    die;
                }
                
                $brokerIdFormapping = $broker_id;
                
                /** -- Primary Address Entry Start -- */
                /** -- Add the addresses in addresses table -- */
                
                $sql_adresses = @mysql_query("INSERT INTO `addresses` SET 
                                            `table_name` = 'brokers',
                                            `table_id` = '".$brokerIdFormapping."',
                                            `address_line_1` = '".$addressline1."',
                                            `address_line_2` = '".$addressline2."',
                                            `city_id` = '".$city_id."',
                                            `pincode` = '".$pincode."',
                                            `created_at` = '".date('Y-m-d H:i:s')."',
                                            `updated_by` = '".$_SESSION['adminId']."'
                                    ")or die(mysql_error());   
                $primary_address_id = @mysql_insert_id();
                
                //$primary_address_id = 7;
                
                /** -- Primary Contact Entry in broker_contacts Table -- */
                
                $sql_broker_contact = @mysql_query("INSERT INTO `broker_contacts` SET 
                                            `broker_id` = '".$brokerIdFormapping."',
                                            `name` = 'Headquarter',
                                            `type` = 'NAgent',
                                            `contact_email` = '".$primary_email."',
                                            `created_at` = '".date('Y-m-d H:i:s')."',
                                            `updated_by` = '".$_SESSION['adminId']."'
                                    ")or die(mysql_error()); 
                
                $primary_broker_contact_id = @mysql_insert_id();
                //$primary_broker_contact_id = 4;
                
                if(!empty($primary_broker_contact_id))
                {
                    $contactID = '';
                    /** -- This will generate a row in contact_numbers table for phone1 -- */
                    $sql_contact_number = @mysql_query("INSERT INTO `contact_numbers` SET 
                                            `table_name` = 'broker_contacts',
                                            `table_id` = '".$primary_broker_contact_id."',
                                            `type` = 'phone1',
                                            `contact_no` = '".$phone1."',
                                            `created_at` = '".date('Y-m-d H:i:s')."',
                                            `updated_by` = '".$_SESSION['adminId']."'")or die(mysql_error()); 
                                    
                    $primary_contact_number_id = @mysql_insert_id();
                    //$primary_contact_number_id = 2;
                    
                    if(!empty($primary_contact_number_id))
                        $contactID = $primary_contact_number_id;
                    
                    /** -- This will generate a row in contact_numbers table for phone2 -- */
                    $sql_contact_number = @mysql_query("INSERT INTO `contact_numbers` SET 
                                            `table_name` = 'broker_contacts',
                                            `table_id` = '".$primary_broker_contact_id."',
                                            `type` = 'phone2',
                                            `contact_no` = '".$phone2."',
                                            `created_at` = '".date('Y-m-d H:i:s')."',
                                            `updated_by` = '".$_SESSION['adminId']."'
                                    ");
                    
                    /** -- This will generate a row in contact_numbers table for fax -- */ 
                    $sql_contact_number = @mysql_query("INSERT INTO `contact_numbers` SET 
                                            `table_name` = 'broker_contacts',
                                            `table_id` = '".$primary_broker_contact_id."',
                                            `type` = 'fax',
                                            `contact_no` = '".$fax."',
                                            `created_at` = '".date('Y-m-d H:i:s')."',
                                            `updated_by` = '".$_SESSION['adminId']."'
                                    "); 
                    
                    
                    $fax_number_id = @mysql_insert_id();
                    //$fax_number_id = 5;
                    
                    /** -- Update the broker_contacts table with the table->contact_numbers pkid->mobile -- */
                    $sql_broker_contact = @mysql_query("UPDATE `broker_contacts` SET 
                                            `contact_number_id` = '".$contactID."',
                                            `updated_at` = '".date('Y-m-d H:i:s')."',
                                            `updated_by` = '".$_SESSION['adminId']."' 
                                            WHERE id = '".$primary_broker_contact_id."'"); 
                    
                   
                }
                
                /** -- Primary Address Entry End -- */
                
                /** 
                * After All Entries for Primary Address Detail
                * Update the brokers Table  
                */
                
                $sql_broker_company = @mysql_query("UPDATE `brokers` SET 
                                            `primary_address_id` = '".$primary_address_id."',
                                            `fax_number_id` = '".$fax_number_id."',
                                            `primary_broker_contact_id` = '".$primary_broker_contact_id."',
                                            `primary_contact_number_id` = '".$primary_contact_number_id."'
                                            WHERE id = '".$broker_id."'");
                
                
                /** -- Broker Contacts Entry Start -- */
                $cp_data = array();
                $brkrIDArr = array();
                $contactID = '';
                $flg = 0;
                foreach($cp_ids as $key => $val)
                {
                    $brkrcmpnyContct = new  BrokerCompanyContact();
                    
                    $brkrcmpnyContct->broker_id = $brokerIdFormapping;
                    $brkrcmpnyContct->name = !empty($cp_name->$val)?$cp_name->$val:'';
                    $brkrcmpnyContct->contact_email = !empty($cp_email->$val)?$cp_email->$val:'';
                    $brkrcmpnyContct->type =  'NAgent';
                    $brkrcmpnyContct->created_at =  date('Y-m-d H:i:s');
                    $brkrcmpnyContct->updated_at =  '000-00-00 00:00:00';
                    $brkrcmpnyContct->updated_by = $_SESSION['adminId'];
                    $brkrcmpnyContct->save();
                    //$brkrcmpnyContct->id = 1;
                    
                    if(!empty($brkrcmpnyContct->id))
                    {
                        $brkrIDArr[] = $brkrcmpnyContct->id;
                    
                        /** -- This will generate a row in contact_numbers table for mobile -- */
                        $contctNumber = new ContactNumber();
                        $contctNumber->table_name = 'broker_contacts';
                        $contctNumber->table_id = $brkrcmpnyContct->id;
                        $contctNumber->type = 'mobile';
                        if(!empty($cp_mobile->$val))
                            $contctNumber->contact_no = $cp_mobile->$val ;
                        $contctNumber->created_at =  date('Y-m-d H:i:s');
                        $contctNumber->updated_by = $_SESSION['adminId'];
                        $contctNumber->save();
                        //$contactID = 1;
                        if(!empty($contctNumber->id))
                            $contactID = $contctNumber->id;
                        
                        /** -- This will generate a row in contact_numbers table for phone1 -- */
                        $contctNumber = new ContactNumber();
                        $contctNumber->table_name = 'broker_contacts';
                        $contctNumber->table_id = $brkrcmpnyContct->id;
                        $contctNumber->type = 'phone1';
                        if(!empty($cp_phone1->$val))
                            $contctNumber->contact_no = $cp_phone1->$val ;
                        $contctNumber->created_at =  date('Y-m-d H:i:s');
                        $contctNumber->updated_at =  '000-00-00 00:00:00';
                        $contctNumber->updated_by = $_SESSION['adminId'];
                        $contctNumber->save();
                        
                        /** -- This will generate a row in contact_numbers table for phone2 -- */
                        $contctNumber = new ContactNumber();
                        $contctNumber->table_name = 'broker_contacts';
                        $contctNumber->table_id = $brkrcmpnyContct->id;
                        $contctNumber->type = 'phone2';
                        if(!empty($cp_phone2->$val))
                            $contctNumber->contact_no = $cp_phone2->$val ;
                        $contctNumber->created_at =  date('Y-m-d H:i:s');
                        $contctNumber->updated_at =  '000-00-00 00:00:00';
                        $contctNumber->updated_by = $_SESSION['adminId'];
                        $contctNumber->save();
                        
                        /** -- This will generate a row in contact_numbers table for fax -- */ 
                        $contctNumber = new ContactNumber();
                        $contctNumber->table_name = 'broker_contacts';
                        $contctNumber->table_id = $brkrcmpnyContct->id;
                        $contctNumber->type = 'fax';
                        if(!empty($cp_fax->$val))
                            $contctNumber->contact_no = $cp_fax->$val ;
                        $contctNumber->created_at =  date('Y-m-d H:i:s');
                        $contctNumber->updated_at =  '000-00-00 00:00:00';
                        $contctNumber->updated_by = $_SESSION['adminId'];
                        $contctNumber->save();
                        
                        /** -- Update the broker_contacts table with the table->contact_numbers pkid->mobile -- */
                        $brkrcmpnyContct = BrokerCompanyContact::find_by_id($brkrcmpnyContct->id);
                        $brkrcmpnyContct->contact_number_id = $contactID;
                        $brkrcmpnyContct->updated_at =  date('Y-m-d H:i:s');
                        $brkrcmpnyContct->save();
                        
                    }
                }
                /** -- Broker Contacts Entry End -- */
                
                
                /** -- CC Entry Start -- */
                /** -- Insert values in broker_contacts for Customer Care -- */
                $brkrcmpnyContct = new  BrokerCompanyContact();
                    
                $brkrcmpnyContct->broker_id = $brokerIdFormapping;
                $brkrcmpnyContct->name = 'Customer Care';
                $brkrcmpnyContct->contact_email = !empty($cc_email)?$cc_email:'';
                $brkrcmpnyContct->created_at =  date('Y-m-d H:i:s');
                $brkrcmpnyContct->updated_at =  '000-00-00 00:00:00';
                $brkrcmpnyContct->updated_by = $_SESSION['adminId'];
                $brkrcmpnyContct->save();
                //$brkrcmpnyContct->id = 1;
                
                if(!empty($brkrcmpnyContct->id))
                {
                    /** -- This will generate a row in contact_numbers table for cc_phone -- */
                    $contctNumber = new  ContactNumber();
                    $contctNumber->table_name = 'broker_contacts';
                    $contctNumber->table_id = $brkrcmpnyContct->id;
                    $contctNumber->type = 'cc_phone';
                    if(!empty($cc_phone))
                        $contctNumber->contact_no = $cc_phone;
                    $contctNumber->created_at =  date('Y-m-d H:i:s');
                    $contctNumber->updated_at =  '000-00-00 00:00:00';
                    $contctNumber->updated_by = $_SESSION['adminId'];
                    $contctNumber->save();
                    
                    //$contactID = '1';
                    if(!empty($contctNumber->id))
                        $contactID = $contctNumber->id;
                        
                    /** -- This will generate a row in contact_numbers table for cc_mobile -- */
                    $contctNumber = new  ContactNumber();
                    $contctNumber->table_name = 'broker_contacts';
                    $contctNumber->table_id = $brkrcmpnyContct->id;
                    $contctNumber->type = 'cc_mobile';
                    if(!empty($cc_mobile))
                        $contctNumber->contact_no = $cc_mobile;
                    $contctNumber->created_at =  date('Y-m-d H:i:s');
                    $contctNumber->updated_at =  '000-00-00 00:00:00';
                    $contctNumber->updated_by = $_SESSION['adminId'];
                    $contctNumber->save();
                    
                    /** -- This will generate a row in contact_numbers table for cc_fax -- */
                    $contctNumber = new  ContactNumber();
                    $contctNumber->table_name = 'broker_contacts';
                    $contctNumber->table_id = $brkrcmpnyContct->id;
                    $contctNumber->type = 'cc_fax';
                    if(!empty($cc_fax))
                        $contctNumber->contact_no = $cc_fax;
                    $contctNumber->created_at =  date('Y-m-d H:i:s');
                    $contctNumber->updated_at =  '000-00-00 00:00:00';
                    $contctNumber->updated_by = $_SESSION['adminId'];
                    $contctNumber->save();
                    
                    /** -- Update the broker_contacts table with the table->contact_numbers pkid->mobile -- */
                    $brkrcmpnyContct = BrokerCompanyContact::find_by_id($brkrcmpnyContct->id);
                    $brkrcmpnyContct->contact_number_id = $contactID;
                    $brkrcmpnyContct->updated_at =  date('Y-m-d H:i:s');
                    $brkrcmpnyContct->save();
                }
                /** -- CC Entry End -- */
               
                /** -- City-Location-Address Entry Start -- */
                //print'<pre>';
                //print_r($finaladdcitylocids);
                //print_R($citypkidArr);
//                print_r($remove_citylocids);
                //die;
                if(!empty($finaladdcitylocids))
                {
                    foreach($finaladdcitylocids as $key => $val)
                    {
                        $bcmpLocation = BrokerCompanyLocation::find_by_id($val);
                        
                        if(empty($bcmpLocation))
                            continue;
                            
                        $bcmpLocation->table_id =  $brokerIdFormapping;
                        $bcmpLocation->save();       
                    }
                }
                //echo "herte";
//                die;
                /** -- City-Location-Address Entry End -- */
            }
            else{
                $ErrorMsg['dataInsertionError'] = "Please try again there is a problem";
            }
            
            });
            
            //print'<pre>';
//            print_r($_POST);
//            print_r($ErrorMsg);
//            die;
        	 
        }
        else {
            
           ResiProject::transaction(function(){
            global $brokerCName,$pan,$description,$status,$addressline1,$addressline2,$city_id,$pincode,$phone1,$phone2,$email,$fax,$active_since,$primary_address_id,$fax_number_id,$primary_broker_contact_id,$primary_contact_number_id,$cp_name,$cp_phone1,$cp_phone2,$cp_email,$cp_fax,$cp_mobile,$cp_ids,$acontactids,$rcontacts,$finalcontacts,$cc_phone,$cc_email,$cc_fax,$cc_mobile,$citypkidArr,$remove_citylocids,$finaladdcitylocids,$brokerCompanyId,$image_id,$logo,$newImagePath,$s3;
            
            $brokerCommpany = BrokerCompany::find_by_id($brokerCompanyId);
            
            $brokerCommpany->broker_name =  $brokerCName;
            $brokerCommpany->status =  $status;
            $brokerCommpany->description =  $description;
            $brokerCommpany->pan =  $pan;
            $brokerCommpany->primary_email = $email;
            
            if(!empty($active_since))
            {
                $active_since = explode("/" , $active_since);
                $active_since = $active_since[2]."-".$active_since[1]."-".$active_since[0];
                $brokerCommpany->active_since =  $active_since;    
            }
            
            $brokerCommpany->updated_by = $_SESSION['adminId'];
            $brokerCommpany->save();
            
            //if(!empty($brokerCommpany))
//            {
//                if(!empty($active_since))
//                {
//                    $active_since = explode("/" , $active_since);
//                    $active_since = $active_since[2]."-".$active_since[1]."-".$active_since[0];   
//                }
//                $sql_broker_company = @mysql_query("UPDATE `brokers` SET 
//                                            `broker_name` = '".$brokerCName."',
//                                            `status` = '".$status."',
//                                            `description` = '".$description."',
//                                            `pan` = '".$pan."',
//                                            `primary_email` = '".$email."',
//                                            `active_since` = '".$active_since."',
//                                            `updated_at` = '".date('Y-m-d H:i:s')."',
//                                            `updated_by` = '".$_SESSION['adminId']."'
//                                    ")or die(mysql_error());       
//            }
            if($brokerCommpany->id != false) {
                
                $brokerIdFormapping = $brokerCommpany->id;
                //echo $image_id;
                if(!empty($logo['name']))
                {
                    list($imgname , $extension) = explode("." , $logo['name']);
                    $newimgName = $newImagePath.time(). '.' .$extension; 
                    
                    //$s3upload = new ImageUpload(NULL, array("service_image_id" => $image_id));
//                    $response = $s3upload->delete();
//                    
//                    print'<pre>';
//                    print_r($response);
//                    die;
                    $flag = move_uploaded_file($logo["tmp_name"], $newImagePath.time(). '.' .$extension);
                    $response = '';
                    if($flag != '')
                    {
                        if(!empty($image_id))
                        {
                            $s3upload = new ImageUpload($newimgName, array("s3" => $s3,
                                            "image_path" => str_replace($newImagePath, "", $newimgName),
                                            "object" => "brokerCompany", "object_id" => $broker_id, 
                                            "image_type" => "logo", 
                                            "service_image_id" => $image_id
                                            ));
                            $response = $s3upload->update();    
                        }
                        else
                        {
                            $s3upload = new ImageUpload($newimgName, array("s3" => $s3,
                                                    "image_path" => str_replace($newImagePath, "", $newimgName),
                                                    "object" => "brokerCompany", "object_type" => "brokerCompany",
                                                    "object_id" => $broker_id, "image_type" => "logo"));
                           
                            $response = $s3upload->upload();
                        }
                        
                        $image_id = $response["service"]->data();
                        $image_id = $image_id->id;
                    }
                    //print'<pre>';
//                    print_r($response);
//                    print_r($image_id);
//                    die;
                }
                //die;
                /** -- Primary Contact Entry in broker_contacts Table -- */
                
                
                if(!empty($primary_address_id))
                {
                    $address = BrokerCompanyLocation::find_by_id($primary_address_id);
                
                    $address->address_line_1 = $addressline1;
                    $address->address_line_2 = $addressline2;
                    $address->city_id = $city_id;
                    $address->pincode = $pincode;
                    $address->save();
                    
                    if(!empty($primary_broker_contact_id))
                    {
                        /** -- Primary Contact Entry in broker_contacts Table -- */
                        $brkrcmpnyContct = BrokerCompanyContact::find_by_id($primary_broker_contact_id);
                        $brkrcmpnyContct->contact_email = !empty($email)?$email:'';
                        $brkrcmpnyContct->save();
                        
                        if(!empty($primary_contact_number_id))
                            $contctNumber = ContactNumber::find_by_id($primary_contact_number_id);
                        if(!empty($phone1))
                            $contctNumber->contact_no = $phone1;
                        $contctNumber->updated_by = $_SESSION['adminId'];
                        $contctNumber->save();
                        
                        $contctNumber1 = BrokerCompanyContact::ContactIDTypeArr($brokerCompanyId);
                        
                        if(!empty($contctNumber1))
                        {
                            foreach($contctNumber1 as $k => $v)
                            {
                                $contctNumber = ContactNumber::find_by_id($v['id']);
                                
                                if(!empty($contctNumber->type) && $contctNumber->type == 'mobile')
                                    $contctNumber->contact_no = $mobile;
                                else if(!empty($contctNumber->type) && $contctNumber->type == 'phone1')
                                    $contctNumber->contact_no = $phone1;
                                else if(!empty($contctNumber->type) && $contctNumber->type == 'phone2')
                                    $contctNumber->contact_no = $phone2;
                                else if(!empty($contctNumber->type) && $contctNumber->type == 'fax' && $v['id'] == $fax_number_id)
                                {
                                    $contctNumber->contact_no = $fax;
                                }
                                
                                $contctNumber->save();
                            }
                        }
                        
                        /** -- Update the broker_contacts table with the table->contact_numbers pkid->mobile -- */
                        $brkrcmpnyContct = BrokerCompanyContact::find_by_id($primary_broker_contact_id);
                        $brkrcmpnyContct->contact_number_id = $primary_contact_number_id;
                        $brkrcmpnyContct->save();
                        
                        //print'<pre>';
//                        print_r($brkrcmpnyContct->id);
//                        die;
                        /** -- Primary Address Entry End -- */
                        
                        /** 
                        * After All Entries for Primary Address Detail
                        * Update the brokers Table  
                        */
                        
                        $brokerCommpany = BrokerCompany::find_by_id($brokerIdFormapping);
                        $brokerCommpany->primary_address_id =  $primary_address_id;
                        $brokerCommpany->fax_number_id =  $fax_number_id;
                        $brokerCommpany->primary_email =  $primary_email_id;
                        $brokerCommpany->primary_broker_contact_id = $primary_broker_contact_id;
                        $brokerCommpany->primary_contact_number_id = $primary_contact_number_id;
                        $brokerCommpany->save();
                    }
                    
                }
                
                
                $cp_data = array();
                $brkrIDArr = array();
                $contactID = '';
                $flg = 0;
                if(!empty($acontactids))
                foreach($acontactids as $key => $val)
                {
                    /** -- Broker Contacts Entry Start -- */
                    
                    $brkrcmpnyContct = new  BrokerCompanyContact();
                    
                    $brkrcmpnyContct->broker_id = $brokerIdFormapping;
                    $brkrcmpnyContct->name = !empty($cp_name->$val)?$cp_name->$val:'';
                    $brkrcmpnyContct->contact_email = !empty($cp_email->$val)?$cp_email->$val:'';
                    $brkrcmpnyContct->created_at =  date('Y-m-d H:i:s');
                    $brkrcmpnyContct->updated_by = $_SESSION['adminId'];
                    $brkrcmpnyContct->save();
                    
                    if(!empty($brkrcmpnyContct->id))
                    {
                        $brkrIDArr[] = $brkrcmpnyContct->id;
                    
                        /** -- This will generate a row in contact_numbers table for mobile -- */
                        $contctNumber = new ContactNumber();
                        $contctNumber->table_name = 'broker_contacts';
                        $contctNumber->table_id = $brkrcmpnyContct->id;
                        $contctNumber->type = 'mobile';
                        if(!empty($cp_mobile->$val))
                            $contctNumber->contact_no = $cp_mobile->$val ;
                        $contctNumber->created_at =  date('Y-m-d H:i:s');
                        $contctNumber->updated_by = $_SESSION['adminId'];
                        $contctNumber->save();
                        $contactID = 1;
                        if(!empty($contctNumber->id))
                            $contactID = $contctNumber->id;
                        
                        /** -- This will generate a row in contact_numbers table for phone1 -- */
                        $contctNumber = new ContactNumber();
                        $contctNumber->table_name = 'broker_contacts';
                        $contctNumber->table_id = $brkrcmpnyContct->id;
                        $contctNumber->type = 'phone1';
                        if(!empty($cp_phone1->$val))
                            $contctNumber->contact_no = $cp_phone1->$val ;
                        $contctNumber->created_at =  date('Y-m-d H:i:s');
                        $contctNumber->updated_at =  '000-00-00 00:00:00';
                        $contctNumber->updated_by = $_SESSION['adminId'];
                        $contctNumber->save();
                        
                        /** -- This will generate a row in contact_numbers table for phone2 -- */
                        $contctNumber = new ContactNumber();
                        $contctNumber->table_name = 'broker_contacts';
                        $contctNumber->table_id = $brkrcmpnyContct->id;
                        $contctNumber->type = 'phone2';
                        if(!empty($cp_phone2->$val))
                            $contctNumber->contact_no = $cp_phone2->$val ;
                        $contctNumber->created_at =  date('Y-m-d H:i:s');
                        $contctNumber->updated_at =  '000-00-00 00:00:00';
                        $contctNumber->updated_by = $_SESSION['adminId'];
                        $contctNumber->save();
                        
                        /** -- This will generate a row in contact_numbers table for fax -- */ 
                        $contctNumber = new ContactNumber();
                        $contctNumber->table_name = 'broker_contacts';
                        $contctNumber->table_id = $brkrcmpnyContct->id;
                        $contctNumber->type = 'fax';
                        if(!empty($cp_fax->$val))
                            $contctNumber->contact_no = $cp_fax->$val ;
                        $contctNumber->created_at =  date('Y-m-d H:i:s');
                        $contctNumber->updated_at =  '000-00-00 00:00:00';
                        $contctNumber->updated_by = $_SESSION['adminId'];
                        $contctNumber->save();
                        
                        /** -- Update the broker_contacts table with the table->contact_numbers pkid->mobile -- */
                        $brkrcmpnyContct = BrokerCompanyContact::find_by_id($brkrcmpnyContct->id);
                        $brkrcmpnyContct->contact_number_id = $contactID;
                        $brkrcmpnyContct->updated_at =  date('Y-m-d H:i:s');
                        $brkrcmpnyContct->save();
                        
                    }
                    /** -- Broker Contacts Entry End -- */
                }

                //print'<pre>';
//                print_r($cp_mobile);
//                die;
                
                if(!empty($finalcontacts))
                    foreach($finalcontacts as $key => $val)
                    {
                        $brkrcmpnyContctNum = BrokerCompanyContact::ContactBrkIDFrmCnt($val);
                        
                        if(!empty($brkrcmpnyContctNum))
                        {
                            foreach($brkrcmpnyContctNum as $k => $v)
                            {
                                $contctNumber = ContactNumber::find_by_id($v['id']);
                                if($v['type'] == 'mobile')
                                {
                                    $contctNumber->contact_no = $cp_mobile->$val ;    
                                }
                                else if($v['type'] == 'phone1')
                                {
                                    $contctNumber->contact_no = $cp_phone1->$val ;
                                }
                                else if($v['type'] == 'phone2')
                                {
                                    $contctNumber->contact_no = $cp_phone2->$val ;    
                                }
                                else if($v['type'] == 'fax')
                                {
                                    $contctNumber->contact_no = $cp_fax->$val ;    
                                }
                                $contctNumber->save();
                            }
                            
                            $brkrcmpnyContct = BrokerCompanyContact::find_by_id($val);
                            $brkrcmpnyContct->name = !empty($cp_name->$val)?$cp_name->$val:'';
                            $brkrcmpnyContct->contact_email = !empty($cp_email->$val)?$cp_email->$val:'';
                            
                            $brkrcmpnyContct->save();
                        }
                        
                        
                        
                        
                    }
                    
                
                if(!empty($rcontacts))
                    foreach($rcontacts as $key => $val)
                    {
                        $contctNumber = BrokerCompanyContact::find_by_id($val);
                        if(empty($contctNumber))
                            continue;
                        $contctNumber->delete();
                    }
                
                /** -- CC Entry Start -- */
                /** -- Insert values in broker_contacts for Customer Care -- */
                $brkrcmpnyContct = BrokerCompanyContact::find('all' , array('conditions' => " name = 'Customer Care' AND broker_id = ".$brokerIdFormapping));
                $cc_id = '';
                $contact_number_id = '';
                if(!empty($brkrcmpnyContct))
                {
                    foreach($brkrcmpnyContct as $key => $val)
                    {
                        if($val->id)
                            $cc_id = $val->id;
                        
                        if($val->contact_number_id)
                            $contact_number_id  = $val->contact_number_id;
                    }
                }
                
                
                
                
                if(!empty($cc_id))
                {
                    $brkrcmpnyContct1 = BrokerCompanyContact::find_by_id($cc_id);
                    $brkrcmpnyContct1->contact_email = !empty($cc_email)?$cc_email:'';
                    $brkrcmpnyContct1->updated_by = $_SESSION['adminId'];
                    $brkrcmpnyContct1->save();
                    $brkrcmpnyContctNum = BrokerCompanyContact::ContactBrkIDFrmCnt($cc_id);
                    
                    if(!empty($brkrcmpnyContctNum))
                        foreach($brkrcmpnyContctNum as $k => $v)
                        {
                            $contctNumber = ContactNumber::find_by_id($v['id']);
                            if($v['type'] == 'mobile')
                            {
                                $contctNumber->contact_no = $cc_mobile->$val ;    
                            }
                            else if($v['type'] == 'phone')
                            {
                                $contctNumber->contact_no = $cc_phone->$val ;
                            }
                            else if($v['type'] == 'fax')
                            {
                                $contctNumber->contact_no = $cc_fax->$val ;    
                            }
                            $contctNumber->save();
                        }
                }
                    
                    
                        
                
                if(!empty($remove_citylocids))
                    foreach($remove_citylocids as $key => $val)
                    {
                        $bcmpLocation = BrokerCompanyLocation::find_by_id($val);
                            
                        if(empty($bcmpLocation))
                            continue;

                        $bcmpLocation->delete();
                    }
                
                if(!empty($finaladdcitylocids))
                    foreach($finaladdcitylocids as $key => $val)
                    {
                        $bcmpLocation = BrokerCompanyLocation::find_by_id($val);
                        
                        if(empty($bcmpLocation))
                            continue;
                            
                        $bcmpLocation->table_id =  $brokerCommpany->id;
                        $bcmpLocation->save();       
                    }
                    
                //print'<pre>';
//                print_r($remove_citylocids);
//                print_r($finaladdcitylocids);
//                die;
 
            }
            else{
                $ErrorMsg['dataInsertionError'] = "Please try again there is a problem in data updation";
            } 
            
            });
        }
         
            
        if(count($ErrorMsg)>0) {
            $smarty->assign("ErrorMsg", $ErrorMsg);    
        }
        else {
            header("Location:BrokerCompanyList.php?page=1&sort=all"); 
        }
        /**********end code project add******************/        
    }
    
?>

