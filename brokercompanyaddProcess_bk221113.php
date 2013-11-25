<?php

//SELECT b.id,b.broker_name,b.status,b.description,b.pan,b.primary_email,b.active_since,bc.name AS CName,bc.contact_email AS CEmail FROM brokers AS b LEFT JOIN broker_contacts AS bc ON b.id = bc.broker_id
//WHERE b.id = '1'

/**
 * @author AKhan
 * @copyright 2013
 */

    $accessBroker = '';
    //if( $brokerAuth == false )
       //$accessBroker = "No Access";
    $smarty->assign("accessBroker",$accessBroker);
    
    $brokerCompanyId = '';
    if(!empty($_REQUEST['brokerCompanyId']))
        $brokerCompanyId = $_REQUEST['brokerCompanyId'];
    
    $smarty->assign("brokerCompanyId", $brokerCompanyId);
    
    $brokerIdForMapping = '';
    
    if ($_POST['btnExit'] == "Exit")
    {
        header("Location:BrokerCompanyList.php");
    }
    if ($_POST['addloc'] == "Add"){
        
        $citylocids = array();
        if(!empty($_POST))
        {
            $page = !empty($_REQUEST['page'])?$_REQUEST['page']:'';
            $sort = !empty($_REQUEST['sort'])?$_REQUEST['sort']:'';
            $rcitylocids = !empty($_REQUEST['citylocids'])?$_REQUEST['citylocids']:'';
            $contacts = !empty($_REQUEST['contacts'])?$_REQUEST['contacts']:'';
            $mode = !empty($_REQUEST['mode'])?$_REQUEST['mode']:'';
            
            
            //print'<pre>';
//            print_R($_POST);
//            die;
            $citylocids = !empty($_POST['addmorecity'])?json_decode(base64_decode($_POST['addmorecity'])):'';
            foreach($_POST as $key => $val)
            {
                if($key != "locations" && $key != "addloc" && $key != "addmorecity")
                {
                    $bcmpLocation = new BrokerCompanyLocation();
                    
                    $bcmpLocation->locality_id =  trim($key);
                    $bcmpLocation->address =  trim($val);
                    
                    $bcmpLocation->created_at =  date('Y-m-d H:i:s');
                    $bcmpLocation->updated_at =  '000-00-00 00:00:00';
                    $bcmpLocation->updated_by =  $_SESSION['adminId'];  
                    $bcmpLocation->broker_company_id = 0;
                    
                    $bcmpLocation->save();
            
                    if($bcmpLocation->id != false) {
                        $citylocids[] = $bcmpLocation->id; 
                    }
                    else{
                        $ErrorMsg['dataInsertionError'] = "Please try again there is a problem in data updation";
                    }   
                }
            }
            //print'<pre>';
//            print_r($citylocids);
//            
//            die;
            if(!empty($ErrorMsg)) 
            {
                     //Do Nothing
            }
            else
            {
                if(!empty($citylocids) && empty($brokerCompanyId))
                    header("Location:brokercompanyadd.php?citylocids=".base64_encode(json_encode($citylocids)));
                else if(!empty($citylocids) && !empty($brokerCompanyId))
                {
                    header("Location:brokercompanyadd.php?brokerCompanyId=$brokerCompanyId&citylocids=$rcitylocids&contacts=$contacts&mode=$mode&page=$page&sort=$sort&citylocids=".base64_encode(json_encode($citylocids)));
                }
                else
                    header("Location:brokercompanyadd.php");
            } 
            
        }
    }
    else if ($_POST['btnSave'] == "Save"){

        //print'<pre>';
//        print_r($_REQUEST);
        //print_r(json_decode(base64_decode($_REQUEST['xcp_ids'])));
//        print_r(json_decode(base64_decode($_REQUEST['xcp_phone1'])));
//        print_r(json_decode(base64_decode($_REQUEST['xcp_phone2'])));
//        print_r(json_decode(base64_decode($_REQUEST['xcp_email'])));
//        print_r(json_decode(base64_decode($_REQUEST['xcp_fax'])));
//        print_r(json_decode(base64_decode($_REQUEST['xcp_mobile'])));
//        print_r($ErrorMsg);
       // die;
        
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
        
        //foreach($cp_ids as $key=> $val)
//        {
//            if(empty($cp_name->$val)){
//                $ErrorMsg["cp_name"] = "Please enter Contact Person name.";
//            }
//            else if(empty($cp_phone1->$val)) {
//                 $ErrorMsg["cp_phone1"] = "Please enter phone number one.";
//            }
//            else if(!is_numeric($cp_phone1->$val)) {
//                 $ErrorMsg["cp_phone1"] = "Phone number must be numeric.";
//            }
//            else if(!preg_match("/^[0-9]{0,12}$/",$cp_phone1->$val)) {
//    			 $ErrorMsg["cp_phone1"] = "Please enter a valid phone number.";
//    		}
//            else if($cp_phone2->$val != '' && !is_numeric($cp_phone2->$val)) {
//                 $ErrorMsg["cp_phone2"] = "Phone number must be numeric.";
//            }
//            else if($cp_phone2->$val != '' && !preg_match("/^[0-9]{0,12}$/",$cp_phone2->$val)) {
//    			 $ErrorMsg["cp_phone2"] = "Please enter a valid phone number.";
//    		}
//            else if($cp_fax->$val != '' && !is_numeric($cp_fax->$val)) {
//                 $ErrorMsg["cp_fax"] = "Fax number must be numeric.";
//            }
//            else if($cp_fax->$val != '' && !preg_match("/^[0-9]{0,12}$/",$cp_fax->$val)) {
//    			 $ErrorMsg["cp_fax"] = "Please enter a valid fax number.";
//    		}
//            else if($cp_mobile->$val != '' && !is_numeric($cp_mobile->$val)) {
//                 $ErrorMsg["cp_mobile"] = "Mobile number must be numeric.";
//            }
//            else if($cp_mobile->$val != '' && !preg_match("/^[0-9]{10}$/",$cp_mobile->$val)) {
//    			 $ErrorMsg["cp_mobile"] = "Please enter a valid mobile number.";
//    		}
//            else if($cp_email->$val != '' && !filter_var($cp_email->$val, FILTER_VALIDATE_EMAIL)) {
//    			 $ErrorMsg["cp_email"] = "Please enter a valid email address.";
//    		}
//        }
        
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
            
            $brokerCommpany = new BrokerCompany();
            
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
            
            $brokerCommpany->created_at = date('Y-m-d H:i:s');
            $brokerCommpany->updated_at = '0000-00-00 00:00:00';
            $brokerCommpany->updated_by = $_SESSION['adminId'];
            $brokerCommpany->save();
            
            //$brokerCommpany = BrokerCompany::find_by_sql("SET foreign_key_checks = 0");    
            
            
            //$brokerCommpany->id = 1;
            
            if($brokerCommpany->id != false) {
                $brokerIdFormapping = $brokerCommpany->id;
                
                /** -- Primary Address Entry Start -- */
                /** -- Add the addresses in addresses table -- */
                $address = new BrokerCompanyLocation();
                
                $address->table_name = 'brokers';
                $address->table_id = $brokerIdFormapping;
                $address->address_line_1 = $addressline1;
                $address->address_line_2 = $addressline2;
                $address->city_id = $city_id;
                $address->pincode = $pincode;
                $address->created_at = date('Y-m-d H:i:s');
                $address->updated_by = $_SESSION['adminId'];
                $address->save();
                //$primary_addr_id = 1;
                
                if(!empty($address->id))
                    $primary_addr_id = $address->id;
                $primary_email_id = !empty($email)?$email:'';
                
                /** -- Primary Contact Entry in broker_contacts Table -- */
                $brkrcmpnyContct = new  BrokerCompanyContact();
                $brkrcmpnyContct->broker_id = $brokerIdFormapping;
                $brkrcmpnyContct->name = 'Headquarter';
                $brkrcmpnyContct->contact_email = !empty($email)?$email:'';
                $brkrcmpnyContct->created_at =  date('Y-m-d H:i:s');
                $brkrcmpnyContct->updated_by = $_SESSION['adminId'];
                $brkrcmpnyContct->save();
                
                //$brkrcmpnyContct->id = 1;
                $primary_broker_contact_id = '';
                if(!empty($brkrcmpnyContct->id))
                {
                    $contactID = '';
                    $primary_broker_contact_id = $brkrcmpnyContct->id;
                    
                    /** -- This will generate a row in contact_numbers table for phone1 -- */
                    $contctNumber = new ContactNumber();
                    $contctNumber->table_name = 'broker_contacts';
                    $contctNumber->table_id = $brkrcmpnyContct->id;
                    $contctNumber->type = 'phone1';
                    
                    if(!empty($phone1))
                        $contctNumber->contact_no = $phone1;
                    
                    $contctNumber->created_at =  date('Y-m-d H:i:s');
                    $contctNumber->updated_by = $_SESSION['adminId'];
                    $contctNumber->save();
                    
                    if(!empty($contctNumber->id))
                        $contactID = $contctNumber->id;
                    $primary_contact_number_id = !empty($contactID)?$contactID:'';
                    /** -- This will generate a row in contact_numbers table for phone2 -- */
                    $contctNumber = new  ContactNumber();
                    $contctNumber->table_name = 'broker_contacts';
                    $contctNumber->table_id = $brkrcmpnyContct->id;
                    $contctNumber->type = 'phone2';
                    if(!empty($phone2))
                        $contctNumber->contact_no = $phone2;
                    $contctNumber->created_at =  date('Y-m-d H:i:s');
                    $contctNumber->updated_by = $_SESSION['adminId'];
                    $contctNumber->save();
                        
                    /** -- This will generate a row in contact_numbers table for fax -- */ 
                    $contctNumber = new  ContactNumber();
                    $contctNumber->table_name = 'broker_contacts';
                    $contctNumber->table_id = $brkrcmpnyContct->id;
                    $contctNumber->type = 'fax';
                    if(!empty($fax))
                        $contctNumber->contact_no = $fax;
                    $contctNumber->created_at =  date('Y-m-d H:i:s');
                    $contctNumber->updated_at =  '000-00-00 00:00:00';
                    $contctNumber->updated_by = $_SESSION['adminId'];
                    $contctNumber->save();
                    
                    $fax_number_id = $contctNumber->id;
                    //$contactID = 1;
                    /** -- Update the broker_contacts table with the table->contact_numbers pkid->mobile -- */
                    $brkrcmpnyContct = BrokerCompanyContact::find_by_id($brkrcmpnyContct->id);
                    $brkrcmpnyContct->contact_number_id = $contactID;
                    $brkrcmpnyContct->updated_at =  date('Y-m-d H:i:s');
                    $brkrcmpnyContct->save();
                }
                
                
                /** -- Primary Address Entry End -- */
                
                /** 
                * After All Entries for Primary Address Detail
                * Update the brokers Table  
                */
                
                $brokerCommpany = BrokerCompany::find_by_id($brokerIdFormapping);
                $brokerCommpany->primary_address_id =  $primary_addr_id;
                $brokerCommpany->fax_number_id =  $fax_number_id;
                $brokerCommpany->primary_email =  $primary_email_id;
                $brokerCommpany->primary_broker_contact_id = $primary_broker_contact_id;
                $brokerCommpany->primary_contact_number_id = $primary_contact_number_id;
                $brokerCommpany->save();
                
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
                /** -- CC Entry Start -- */
               
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
                /** -- City-Location-Address Entry End -- */
            }
            else{
                $ErrorMsg['dataInsertionError'] = "Please try again there is a problem";
            }
            
            
            
            //print'<pre>';
//            print_r($_POST);
//            print_r($ErrorMsg);
//            die;
        	 
        }
        else {
           // print'<pre>';
//           print_r($_POST);
//            print_r($finaladdcitylocids);
//            print_r($finalcontacts);
//            print_r($cp_ids);
    //        print_r($rcontacts);
            //die;
            $brokerCommpany = BrokerCompany::find_by_id($brokerCompanyId);
            		
            $brokerCommpany->name =  $brokerCName;
            $brokerCommpany->pan =  $pan;
            $brokerCommpany->description =  $description;
            $brokerCommpany->status =  $status;
            $brokerCommpany->addressline1 =  $addressline1;
            $brokerCommpany->addressline2 =  $addressline2;
            $brokerCommpany->city_id =  $city_id;
            $brokerCommpany->pincode =  $pincode;
            $brokerCommpany->phone1 =  $phone1;
            $brokerCommpany->phone2 =  $phone2;
            $brokerCommpany->email =  $email;
            $brokerCommpany->fax =  $fax;
            
            if(!empty($active_since))
            {
                $active_since = explode("/" , $active_since);
                $active_since = $active_since[2]."-".$active_since[1]."-".$active_since[0];
                $brokerCommpany->active_since =  $active_since;    
            }
            else
            {
                $brokerCommpany->active_since =  '0000-00-00';
            }
            
            
            $brokerCommpany->cc_phone =  $cc_phone;
            $brokerCommpany->cc_email =  $cc_email;
            $brokerCommpany->cc_fax =  $cc_fax;
            $brokerCommpany->cc_mobile =  $cc_mobile;
            
            $brokerCommpany->updated_at =  date('Y-m-d H:i:s');
            $brokerCommpany->updated_by =  $_SESSION['adminId'];
            
            $brokerCommpany->save();
            
            if($brokerCommpany->id != false) {
                $brokerIdFormapping = $brokerCommpany->id;
                
                
                if(!empty($acontactids))
                foreach($acontactids as $key => $val)
                {
                    $contactDetail = new BrokerContactDetail();
                    
                    $contactDetail->name =  !empty($cp_name->$val)?$cp_name->$val:'';
                    $contactDetail->mobile =  !empty($cp_mobile->$val)?$cp_mobile->$val:'';
                    $contactDetail->phone1 =  !empty($cp_phone1->$val)?$cp_phone1->$val:'';
                    $contactDetail->phone2 =  !empty($cp_phone2->$val)?$cp_phone2->$val:'';
                    $contactDetail->fax =  !empty($cp_fax->$val)?$cp_fax->$val:'';
                    $contactDetail->email =  !empty($cp_email->$val)?$cp_email->$val:'';
                    $contactDetail->created_at =  date('Y-m-d H:i:s');
                    $contactDetail->updated_at =  '0000-00-00';
                    $contactDetail->updated_by = $_SESSION['adminId'];
                    $contactDetail->broker_company_id = $brokerCommpany->id;
                    
                    $contactDetail->save();
                }

                $cp_data = array();
                
                if(!empty($finalcontacts))
                foreach($finalcontacts as $key => $val)
                {
                    $contactDetail = BrokerContactDetail::find_by_id($val);
                    
                    if(empty($contactDetail))
                        continue;
                    
                    $contactDetail->name =  !empty($cp_name->$val)?$cp_name->$val:'';
                    $contactDetail->mobile =  !empty($cp_mobile->$val)?$cp_mobile->$val:'';
                    $contactDetail->phone1 =  !empty($cp_phone1->$val)?$cp_phone1->$val:'';
                    $contactDetail->phone2 =  !empty($cp_phone2->$val)?$cp_phone2->$val:'';
                    $contactDetail->fax =  !empty($cp_fax->$val)?$cp_fax->$val:'';
                    $contactDetail->email =  !empty($cp_email->$val)?$cp_email->$val:'';
                    //$contactDetail->created_at =  date('Y-m-d H:i:s');
                    $contactDetail->updated_at =  date('Y-m-d H:i:s');
                    $contactDetail->updated_by = $_SESSION['adminId'];
                    $contactDetail->broker_company_id = $brokerCommpany->id;
                    
                    $contactDetail->save();
                    if($contactDetail->id != false) {
                        $brokerContactIdFormapping = $contactDetail->id;
                        
                    }
                    else{
                        $ErrorMsg['dataInsertionError'] = "Please try again there is a problem";
                    }
                }
                
                if(!empty($rcontacts))
                    foreach($rcontacts as $key => $val)
                    {
                        $contactDetail = BrokerContactDetail::find_by_id($val);
                        
                        if(empty($contactDetail))
                            continue;
                        
                        $contactDetail->delete();
                    }
                
                if(!empty($remove_citylocids))
                    foreach($remove_citylocids as $key => $val)
                    {
                        $bcmpLocation = BrokerCompanyLocation::find_by_id($val);
                            
                        if(empty($bcmpLocation))
                            continue;

                        $bcmpLocation->delete();
                    }
                    
                //if(!empty($finaladdcitylocids))
//                    foreach($finaladdcitylocids as $key => $val)
//                    {
//                        $bcmpLocation = BrokerCompanyLocation::find_by_id($val);
//                        
//                        if(empty($bcmpLocation))
//                            continue;
//                            
//                        $bcmpLocation->broker_company_id =  $brokerCommpany->id;
//                        $bcmpLocation->save();       
//                    }
 
            }
            else{
                $ErrorMsg['dataInsertionError'] = "Please try again there is a problem in data updation";
            } 
        }

            
        if(count($ErrorMsg)>0) {
            $smarty->assign("ErrorMsg", $ErrorMsg);    
        }
        else {
            header("Location:BrokerCompanyList.php?page=1&sort=all"); 
        }
        /**********end code project add******************/        
    }
    //else {
//        
//        $brokerCompanyDetail = '';
//        
//        if(!empty($brokerCompanyId))
//            $brokerCompanyDetail = BrokerCompany::find('all' , array('conditions' => 'id = '.$brokerCompanyId));
//            
//        if(!empty($brokerCompanyDetail))
//        {
//            $active_since = '';
//            foreach($brokerCompanyDetail as $key => $val)
//            {
//                if(!empty($val->active_since))
//                    $active_since = date("d/m/Y" , strtotime($val->active_since));
//                
//                
//                $smarty->assign("brokerCompanyId", !empty($val->id)?$val->id:'');
//                $smarty->assign("name", !empty($val->name)?$val->broker_name:'');
//                $smarty->assign("pan", !empty($val->pan)?$val->pan:'');
//                $smarty->assign("description", !empty($val->description)?$val->description:'');
//                $smarty->assign("status", !empty($val->status)?$val->status:'');
//                
//                $smarty->assign("active_since", $active_since);
//                $smarty->assign("cc_phone", !empty($val->cc_phone)?$val->cc_phone:'');
//                $smarty->assign("cc_mobile", !empty($val->cc_mobile)?$val->cc_mobile:'');
//                $smarty->assign("cc_fax", !empty($val->cc_fax)?$val->cc_fax:'');
//                $smarty->assign("cc_email", !empty($val->cc_email)?$val->cc_email:'');
//            }
//        }
//        else
//        {
//            $smarty->assign("brokerCompanyId", '');
//            $smarty->assign("name", '');
//            $smarty->assign("pan", '');
//            $smarty->assign("description", '');
//            $smarty->assign("status", '');
//            $smarty->assign("addressline1", '');
//            $smarty->assign("addressline2", '');
//            $smarty->assign("city_id", '');
//            $smarty->assign("pincode", '');        
//            $smarty->assign("phone1", '');
//            $smarty->assign("phone2", '');
//            $smarty->assign("statfaxus", '');
//            $smarty->assign("email", '');
//            $smarty->assign("active_since", '');
//            $smarty->assign("cc_phone", '');
//            $smarty->assign("cc_mobile", '');
//            $smarty->assign("cc_fax", '');
//            $smarty->assign("cc_email", '');
//        }
//    }
?>

