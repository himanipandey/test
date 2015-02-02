<?php

  $error_flag='';

  $cityArray = City::CityArr();
  $smarty->assign("cityArray", $cityArray);

  //validate companyID if exist
  $compId = mysql_real_escape_string($_REQUEST['c']);
  if($compId){	  
    $comp_details = Company::getCompanyById($compId);	  
    if($comp_details){
	  $smarty->assign('txtCompName',$comp_details[0]->name);
	  $smarty->assign('txtCompId',$compId);
	}
  }
  
if((isset($_REQUEST['o']) && $_REQUEST['page'] == 'view') || isset($_REQUEST['o']) && $_REQUEST['page'] == 'edit'){


}

  if($_POST['btnSave'] == 'Save'){
	$order_id = '';  
	$txtCompId = trim($_POST['txtCompId']);
        $orderName = trim($_POST['orderName']);
	$txtCompName = trim($_POST['txtCompName']);
	$txtSalesPerson = trim($_POST['txtSalesPerson']);
	$txtOrderDate = trim($_POST['txtOrderDate']);
	$orderType = trim($_POST['orderType']);
	$txtOrderDur = trim($_POST['txtOrderDur']);
	$txtExpiryTrialOrderDate = trim($_POST['txtExpiryTrialOrderDate']);
	$txtOrderAmt = trim($_POST['txtOrderAmt']);
	$txtExpiryOrderDate = trim($_POST['txtExpiryOrderDate']);
	$txtPaymentMethod = $_POST['txtPaymentMethod'];
	$txtPaymentInstNo = $_POST['txtPaymentInstNo'];
	$txtPaymentAmt = $_POST['txtPaymentAmt'];
	$txtPaymentDate = $_POST['txtPaymentDate'];
	$gAccess = trim($_POST['gAccess']);
	$cities = $_POST['cities'];
	$locs_cities = trim($_POST['locs_cities']);
	$dash_access = $_POST['dash_access'];
	$demand_access = $_POST['demand_access'];
	$supply_access = $_POST['supply_access'];
	$catch_access = $_POST['catch_access'];
	$builder_access = $_POST['builder_access'];
	$noLicen = trim($_POST['noLicen']);
	$txtSubsUserName = $_POST['txtSubsUserName'];
	$txtSubsUserEmail = $_POST['txtSubsUserEmail'];
	$txtSubsUserCont = $_POST['txtSubsUserCont'];
	$txtSubsUserOtp = $_POST['txtSubsUserOtp'];
	//print_r($txtSubsUserOtp);

	$pmtNo = trim($_POST['pmtNo']);
	$userNo = trim($_POST['userNo']);
	$all_locs = trim($_POST['all_locs']);	
	$all_locs = ($all_locs)?explode(",",$all_locs):'';	
	
	CompanyOrder::transaction(function(){
	  	
	  global $order_id,$txtCompId,$orderName,$txtCompName,$txtSalesPerson,$txtOrderDate,$orderType,$txtOrderDur,$txtExpiryTrialOrderDate,$txtOrderAmt,$txtExpiryOrderDate,$txtPaymentMethod,$txtPaymentInstNo,$txtPaymentAmt,$txtPaymentDate,$gAccess,$cities,$locs_cities,$dash_access,$builder_access,$catch_access,$demand_access,$supply_access,$noLicen,$txtSubsUserName, $txtSubsUserEmail,$txtSubsUserCont,$txtSubsUserGroup,$txtSubsUserOtp,$pmtNo,$userNo,$all_locs,$error_flag,$cityArray;
	
	  try{
		  
		  $expiry_date='';
		  if($orderType == 'trial')
		    $expiry_date = $txtExpiryTrialOrderDate;
		  else
		    $expiry_date = $txtExpiryOrderDate;
		  
		  
		  #Company Subcription
		  $res = mysql_query("INSERT INTO `proptiger`.`company_subscriptions` (`id`,`company_id`, `created_by`, `expiry_time`, `created_at`, `updated_at`) VALUES (NULL, '".$txtCompId."', '".$_SESSION['adminId']."', '".$expiry_date."', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')");
		  
		  $subs_id = mysql_insert_id();
		  
		  #company Orders
		  $company_order = new CompanyOrder();
		  $company_order->company_id = $txtCompId;
                  $company_order->order_name = $orderName;
		  $company_order->subscription_id = $subs_id;
		  $company_order->sales_persion_id = $txtSalesPerson;
		  $company_order->order_type = $orderType;
		  $company_order->order_date = $txtOrderDate;
		  if($orderType == 'trial'){
			 $company_order->order_expiry_date = $txtExpiryTrialOrderDate; 
			 $company_order->trial_duration =  $txtOrderDur;
			 $company_order->order_amount =  '';
		  }
		  if($orderType == 'paid'){
			 $company_order->order_expiry_date = $txtExpiryOrderDate;
			 $company_order->order_amount =  $txtOrderAmt;
			 $company_order->trial_duration =  ''; 
			 //insert payment details
		  }		  
		  $company_order->updated_by = $_SESSION['adminId'];
		  $company_order->updated_at = date('Y-m-d H:i:s');
		  $company_order->created_at = date('Y-m-d H:i:s');
		  $company_order->save();
		  
		  $order_id = $company_order->id;
		  
		  #Company Orders Payment
		  if($orderType == 'paid'){
			 $cnt = 0;
			 while($cnt < $pmtNo){
			   $company_order_payments = new CompanyOrderPayment();
			   $company_order_payments->company_order_id = $company_order->id;
			   $company_order_payments->payment_method = $txtPaymentMethod[$cnt];
			   $company_order_payments->payment_instrument_no = $txtPaymentInstNo[$cnt];
			   $company_order_payments->payment_amount = $txtPaymentAmt[$cnt];
			   $company_order_payments->payment_date = $txtPaymentDate[$cnt];
			   $company_order_payments->updated_by = $_SESSION['adminId'];
			   $company_order_payments->updated_at = date('Y-m-d H:i:s');
			   $company_order_payments->created_at = date('Y-m-d H:i:s');
			   $company_order_payments->save();
			   $cnt++;	 
			 } 
		  }
		  		  
		  		  
		  #subcription sections
		  if($dash_access)
		    mysql_query("INSERT INTO `proptiger`.`subscription_sections` (`id`, `subscription_id`, `section`, `created_by`, `created_at`) VALUES (NULL, '".$subs_id."', '".$dash_access."', '".$_SESSION['adminId']."', '".date('Y-m-d H:i:s')."')");
		  if($builder_access && $orderType == 'paid')
		    mysql_query("INSERT INTO `proptiger`.`subscription_sections` (`id`, `subscription_id`, `section`, `created_by`, `created_at`) VALUES (NULL, '".$subs_id."', '".$builder_access."', '".$_SESSION['adminId']."', '".date('Y-m-d H:i:s')."')");
		  if($catch_access)
		    mysql_query("INSERT INTO `proptiger`.`subscription_sections` (`id`, `subscription_id`, `section`, `created_by`, `created_at`) VALUES (NULL, '".$subs_id."', '".$catch_access."', '".$_SESSION['adminId']."', '".date('Y-m-d H:i:s')."')");
		  
		  #subscription column
		  if($demand_access)
		    mysql_query("INSERT INTO `proptiger`.`subscription_columns` (`id`, `subscription_id`, `column_group`, `created_by`, `created_at`) VALUES (NULL,'".$subs_id."', '".$demand_access."', '".$_SESSION['adminId']."', '".date('Y-m-d H:i:s')."');");
		  if($supply_access)
		    mysql_query("INSERT INTO `proptiger`.`subscription_columns` (`id`, `subscription_id`, `column_group`, `created_by`, `created_at`) VALUES (NULL,'".$subs_id."', '".$supply_access."', '".$_SESSION['adminId']."', '".date('Y-m-d H:i:s')."');");
		  
		   #- permission
		   $perm_data = array();
		   $perm_data = $all_locs;		   		   
		   if(!empty($perm_data)){				  
			 foreach($perm_data as $k=>$v){  //51049 
			   if($v<1000){
				 $objectType = 6; 
			   }elseif($v < 50000 && $v>1000){
			     $objectType = 7;			     
			   }elseif($v > 50000){
			     $objectType = 4;			     
			   }	  			     
			   $find_perm_sql = mysql_query("SELECT id FROM  `proptiger`.`permissions` WHERE `object_type_id`='".$objectType."' AND  `object_id`='".$v."' AND `access_level`='Read'");
			   if(mysql_num_rows($find_perm_sql)){
			     $perm_id = mysql_fetch_object($find_perm_sql);
			     $perm_id = $perm_id->id;			     
			   }else{
			     mysql_query("INSERT INTO `proptiger`.`permissions` (`id`, `object_type_id`, `object_id`, `access_level`) VALUES (NULL, '".$objectType."', '".$v."', 'Read')");
			     $perm_id = mysql_insert_id();
			   }
			   #- Subcription Permission
			   mysql_query("INSERT INTO `proptiger`.`subscription_permissions` (`id`, `subscription_id`, `permission_id`, `created_by`, `created_at`) VALUES (NULL, '".$subs_id."', '".$perm_id."', '".$_SESSION['adminId']."', '".date('Y-m-d H:i:s')."');");
			}
		  }	
		   
		  
		  #- User Subscription Mapping
		  $cnt = 0;
		  while($cnt < $userNo){
		  	//die("hello".$txtSubsUserOtp[$cnt]);
		  	//die("hello1");
			//need to fetch id on basis of email id from forum_user 
			$name = trim(mysql_real_escape_string($txtSubsUserName[$cnt]));
			$email = trim(mysql_real_escape_string($txtSubsUserEmail[$cnt]));
			$phone = trim(mysql_real_escape_string($txtSubsUserCont[$cnt]));
				
			$sql_user = mysql_query("SELECT `USER_ID` FROM `proptiger`.`FORUM_USER` WHERE `EMAIL`='".addslashes($email)."'");
			if(mysql_num_rows($sql_user)){
			  $userId = mysql_fetch_object($sql_user);	
			  $res = mysql_query("INSERT INTO `proptiger`.`user_subscription_mappings` (`id`, `subscription_id`, `user_id`, `created_by`, `created_at`) VALUES (NULL, '".$subs_id."', '".$userId->USER_ID."', '".$_SESSION['adminId']."', '".date('Y-m-d H:i:s')."')");
			}else{
			  $compArr = Company::getAllCompany($arr=array('id'=>$txtCompId));
			  $pwd = time();
			  //create user in forum table			
			  //mysql_query("INSERT INTO `proptiger`.`FORUM_USER` (`USER_ID`, `USERNAME`, `EMAIL`, `CONTACT`, `PROVIDERID`, `PROVIDER`, `FB_IMAGE_URL`, `IMAGE`, `PASSWORD`, `CITY`, `COUNTRY_ID`, `UNIQUE_USER_ID`, `CREATED_DATE`, `STATUS`, `IS_SUBSCRIBED`, `UNSUBSCRIBED_AT`) VALUES (NULL, '', '".$email."','".$phone."', '0', '', '', ' ', '".md5($pwd)."', '".$cityArray[$compArr[0]['city']]."', '1', '', '".date('Y-m-d H:i:s')."', '1', 0, '".date('Y-m-d H:i:s')."');") or die(mysql_error());
			
			  
			
            //$post = '{"userName":"'.$email.'", "email":"'.$email.'","contact":"'.$phone.'","password":"'.$pwd.'","confirmPassword":"'.$pwd.'","countryId":"+91"}';
            
            $contactNumbers = array();
			$contact = array(
						"contactNumber"=> $phone
					);
			array_push($contactNumbers, $contact);
            
            $post = array(
            			"fullName"=>$name,
            			"email"=>$email,
            			"contactNumbers"=>$contactNumbers,
            			"password"=>$pwd,
            			"confirmPassword"=>$pwd,
            			"countryId"=>"+91"
            		);

            $url = USER_API_URL;
            //echo $post;
            $response = curl_request(json_encode($post), 'POST', $url);
            if($response['statusCode']=="2XX"){
            	//echo "here";
              $userId = $response['id'];
              $url = USER_ATTRIBUTES_API_URL."/{$userId}/attribute";

              if($txtSubsUserOtp[$cnt]=="TRUE")
              	$disable_otp = 'TRUE';
              else
              	$disable_otp = 'FALSE';
              

              $post = array(
            			"attributeName"=>'OTP_DISABLE',
            			"attributeValue"=>$disable_otp,
            		);
              $response_otp = curl_request(json_encode($post), 'POST', $url);
              var_dump($response_otp); //die("here1");
            }
             
            else die("error in user mapping : ".$response['error']);



			  //$userId = mysql_insert_id();
			   $res = mysql_query("INSERT INTO `proptiger`.`user_subscription_mappings` (`id`, `subscription_id`, `user_id`, `created_by`, `created_at`) VALUES (NULL, '".$subs_id."', '".$userId."', '".$_SESSION['adminId']."', '".date('Y-m-d H:i:s')."')");
			   //sending email to newly created user
			  //$email = "kuldeep.patel_c@proptiger.com";
			  $subject= "Your User Account has been created!";
			  $email_message = "Hi,<br/><br/> Your account has been created at Proptiger.com.<br/>
			  User = ".$email."<br/>"."Password = ".$pwd."<br/><br/>Regards,<br/>Proptiger.com";
			  //$to = $email;
                          $to = 'dl.login@proptiger.com';
			  $sender = "no-reply@proptiger.com";
			  $headers  = 'MIME-Version: 1.0' . "\r\n";
			  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			  $headers .= 'To: '.$email."\r\n";
			  $headers .= 'From: '.$sender."\r\n";
			  sendMailFromAmazon($to, $subject, $email_message, $sender,null,null,false);
			}
			$cnt++;	 
		  } 		  
		  		  
	  }catch(Exception $e){	
		  $error_flag = "Some error occurs in Company Order Saving! Try Again";	  
		  return false;	  
	  }

		  
	});
	  
	if($error_flag == ''){
	  //sending email on placing an order
	  $email = "kuldeep.patel_c@proptiger.com";
	  $subject= "New Order[".$order_id."] Placed!";
	  $email_message = "New order[order ID : ".$order_id."] has been created!";
	  $to = $email;
	  $sender = "no-reply@proptiger.com";
	  $headers  = 'MIME-Version: 1.0' . "\r\n";
	  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	  $headers .= 'To: '.$email."\r\n";
	  $headers .= 'From: '.$sender."\r\n";
          sendMailFromAmazon($to, $subject, $email_message, $sender,null,null,false);		
	  header("Location:companyOrdersList.php?compId=".$txtCompId); 
	}
	  
  }
  
  if($_POST['btnEditSave'] == 'Update'){
	$orderId = $_POST['orderId'];
	$subs_id = $_POST['subsId'];  
	  
	$txtCompId = trim($_POST['txtCompId']);
        $orderName = trim($_POST['orderName']);
	$txtCompName = trim($_POST['txtCompName']);
	$txtSalesPerson = trim($_POST['txtSalesPerson']);
	$txtOrderDate = trim($_POST['txtOrderDate']);
	$orderType = trim($_POST['orderType']);
	$txtOrderDur = trim($_POST['txtOrderDur']);
	$txtExpiryTrialOrderDate = trim($_POST['txtExpiryTrialOrderDate']);
	$txtOrderAmt = trim($_POST['txtOrderAmt']);
	$txtExpiryOrderDate = trim($_POST['txtExpiryOrderDate']);
	
	$txtPaymentId = $_POST['txtPaymentId'];
	$txtPaymentMethod = $_POST['txtPaymentMethod'];
	$txtPaymentInstNo = $_POST['txtPaymentInstNo'];
	$txtPaymentAmt = $_POST['txtPaymentAmt'];
	$txtPaymentDate = $_POST['txtPaymentDate'];
	
	$gAccess = trim($_POST['gAccess']);
	$cities = $_POST['cities'];
	$locs_cities = trim($_POST['locs_cities']);
	$dash_access = $_POST['dash_access'];
	$demand_access = $_POST['demand_access'];
	$supply_access = $_POST['supply_access'];
	$catch_access = $_POST['catch_access'];
	$builder_access = $_POST['builder_access'];
	$noLicen = trim($_POST['noLicen']);
	$txtSubsUserName = $_POST['txtSubsUserName'];
	$txtSubsUserEmail = $_POST['txtSubsUserEmail'];
	$txtSubsUserCont = $_POST['txtSubsUserCont'];
	$txtSubsUserGroup = $_POST['txtSubsUserGroup'];
	$txtSubsUserOtp = $_POST['txtSubsUserOtp'];
	$pmtNo = trim($_POST['pmtNo']);
	$userNo = trim($_POST['userNo']);
	$all_locs = trim($_POST['all_locs']);	
	$all_locs = ($all_locs)?explode(",",$all_locs):'';	
	
	CompanyOrder::transaction(function(){
	  	
	  global $orderId,$orderName,$subs_id,$txtCompId,$txtCompName,$txtSalesPerson,$txtOrderDate,$orderType,$txtOrderDur,$txtExpiryTrialOrderDate,$txtOrderAmt,$txtExpiryOrderDate,$txtPaymentId,$txtPaymentMethod,$txtPaymentInstNo,$txtPaymentAmt,$txtPaymentDate,$gAccess,$cities,$locs_cities,$dash_access,$builder_access,$catch_access,$demand_access,$supply_access,$noLicen, $txtSubsUserName,$txtSubsUserEmail,$txtSubsUserCont,$txtSubsUserGroup, $txtSubsUserOtp, $pmtNo,$userNo,$all_locs,$error_flag,$cityArray;
	
	  try{
		  $expiry_date='';
		  if($orderType == 'trial')
		    $expiry_date = $txtExpiryTrialOrderDate;
		  else
		    $expiry_date = $txtExpiryOrderDate;
		    
		    #Company Subcription
		  $res = mysql_query("UPDATE `proptiger`.`company_subscriptions` SET `expiry_time`='".$expiry_date."' WHERE id='".$subs_id."'");
		  		    
		  #company Orders
		  $company_order = CompanyOrder::find($orderId);
		  $company_order->company_id = $txtCompId;
                  $company_order->order_name = $orderName;
		  $company_order->subscription_id = $subs_id;
		  $company_order->sales_persion_id = $txtSalesPerson;
		  $company_order->order_type = $orderType;
		  $company_order->order_date = $txtOrderDate;
		  if($orderType == 'trial'){
			 $company_order->order_expiry_date = $txtExpiryTrialOrderDate; 
			 $company_order->trial_duration =  $txtOrderDur;
			 $company_order->order_amount =  '';
		  }
		  if($orderType == 'paid'){
			 $company_order->order_expiry_date = $txtExpiryOrderDate;
			 $company_order->order_amount =  $txtOrderAmt;
			 $company_order->trial_duration =  ''; 
			 //insert payment details
		  }		  
		  $company_order->updated_by = $_SESSION['adminId'];
		  $company_order->updated_at = date('Y-m-d H:i:s');
		  $company_order->created_at = date('Y-m-d H:i:s');
		  $company_order->save();
		  
		  #Company Orders Payment
		  if($orderType == 'trial'){
			 CompanyOrderPayment::delete_all(array('conditions'=>array("company_order_id  in (".$orderId.")")));
		  }
		  if($orderType == 'paid'){
			 $cnt = 0;
			 if($txtPaymentId)
			     CompanyOrderPayment::delete_all(array('conditions'=>array("id not in (".implode($txtPaymentId).")")));	 
			 while($cnt < $pmtNo){
			   
			   $company_order_payments = CompanyOrderPayment::find($txtPaymentId[$cnt]);
			   if(!$company_order_payments)
			   $company_order_payments = new CompanyOrderPayment();
			   $company_order_payments->company_order_id = $company_order->id;
			   $company_order_payments->payment_method = $txtPaymentMethod[$cnt];
			   $company_order_payments->payment_instrument_no = $txtPaymentInstNo[$cnt];
			   $company_order_payments->payment_amount = $txtPaymentAmt[$cnt];
			   $company_order_payments->payment_date = $txtPaymentDate[$cnt];
			   $company_order_payments->updated_by = $_SESSION['adminId'];
			   $company_order_payments->updated_at = date('Y-m-d H:i:s');
			   $company_order_payments->created_at = date('Y-m-d H:i:s');
			   $company_order_payments->save();
			   $cnt++;	 
			 } 
		  }	  
		  	    
		  
		  #subcription sections
		  mysql_query("DELETE FROM `proptiger`.`subscription_sections` WHERE `subscription_id`='".$subs_id."'") ;
		  if($dash_access)
		    mysql_query("INSERT INTO `proptiger`.`subscription_sections` (`id`, `subscription_id`, `section`, `created_by`, `created_at`) VALUES (NULL, '".$subs_id."', '".$dash_access."', '".$_SESSION['adminId']."', '".date('Y-m-d H:i:s')."')");
		  if($builder_access && $orderType == 'paid')
		    mysql_query("INSERT INTO `proptiger`.`subscription_sections` (`id`, `subscription_id`, `section`, `created_by`, `created_at`) VALUES (NULL, '".$subs_id."', '".$builder_access."', '".$_SESSION['adminId']."', '".date('Y-m-d H:i:s')."')");
		  if($catch_access)
		    mysql_query("INSERT INTO `proptiger`.`subscription_sections` (`id`, `subscription_id`, `section`, `created_by`, `created_at`) VALUES (NULL, '".$subs_id."', '".$catch_access."', '".$_SESSION['adminId']."', '".date('Y-m-d H:i:s')."')");
		  
		  #subscription column
		   mysql_query("DELETE FROM `proptiger`.`subscription_columns` WHERE `subscription_id`='".$subs_id."'");
		  if($demand_access)
		    mysql_query("INSERT INTO `proptiger`.`subscription_columns` (`id`, `subscription_id`, `column_group`, `created_by`, `created_at`) VALUES (NULL,'".$subs_id."', '".$demand_access."', '".$_SESSION['adminId']."', '".date('Y-m-d H:i:s')."');");
		  if($supply_access)
		    mysql_query("INSERT INTO `proptiger`.`subscription_columns` (`id`, `subscription_id`, `column_group`, `created_by`, `created_at`) VALUES (NULL,'".$subs_id."', '".$supply_access."', '".$_SESSION['adminId']."', '".date('Y-m-d H:i:s')."');");  
		   
		    
		  
		   #- permission
		   mysql_query("DELETE FROM `proptiger`.`subscription_permissions` WHERE subscription_id='".$subs_id."'");
		   $perm_data = array();
		   $perm_data = $all_locs;
		   if(!empty($perm_data)){				  
			 foreach($perm_data as $k=>$v){  //51049 
			   if($v<1000){
				 $objectType = 6; 
			   }elseif($v < 50000 && $v>1000){
			     $objectType = 7;			     
			   }elseif($v > 50000){
			     $objectType = 4;			     
			   }	 	    
			    
			   $find_perm_sql = mysql_query("SELECT id FROM  `proptiger`.`permissions` WHERE `object_type_id`='".$objectType."' AND  `object_id`='".$v."' AND `access_level`='Read'") ;
			   if(mysql_num_rows($find_perm_sql)){
			     $perm_id = mysql_fetch_object($find_perm_sql);
			     $perm_id = $perm_id->id;			     
			   }else{
			     mysql_query("INSERT INTO `proptiger`.`permissions` (`id`, `object_type_id`, `object_id`, `access_level`) VALUES (NULL, '".$objectType."', '".$v."', 'Read')") or die(mysql_error());
			     $perm_id = mysql_insert_id();
			   }		   
			   
			   #- Subcription Permission
			   mysql_query("INSERT INTO `proptiger`.`subscription_permissions` (`id`, `subscription_id`, `permission_id`, `created_by`, `created_at`) VALUES (NULL, '".$subs_id."', '".$perm_id."', '".$_SESSION['adminId']."', '".date('Y-m-d H:i:s')."');");
			}
		  }	
		   
		  
		  #- User Subscription Mapping
		  mysql_query("DELETE FROM `proptiger`.`user_subscription_mappings` WHERE subscription_id='".$subs_id."'");
		  $cnt = 0;
		  while($cnt < $userNo){
		  	//die($txtSubsUserOtp[$cnt]);
			//need to fetch id on basis of email id from forum_user 
			$name = trim(mysql_real_escape_string($txtSubsUserName[$cnt]));
			$email = trim(mysql_real_escape_string($txtSubsUserEmail[$cnt]));
			$phone = trim(mysql_real_escape_string($txtSubsUserCont[$cnt]));			
			$sql_user = mysql_query("SELECT `USER_ID` FROM `proptiger`.`FORUM_USER` WHERE `EMAIL`='".addslashes($email)."'") or die(mysql_error()); 
			if(mysql_num_rows($sql_user)){  
			  $userId = mysql_fetch_object($sql_user);	 
			  $res = mysql_query("INSERT INTO `proptiger`.`user_subscription_mappings` (`id`, `subscription_id`, `user_id`, `created_by`, `created_at`) VALUES (NULL, '".$subs_id."', '".$userId->USER_ID."', '".$_SESSION['adminId']."', '".date('Y-m-d H:i:s')."')") or die(mysql_error()); 
			  //update disable_otp field
			  
			  $url = USER_DETAILS_API_URL."/?userId=".$userId->USER_ID; 
			  //$url = USER_DETAILS_API_URL; 
			  //$url = $url."/?userId=".$userId;
			  //die($url);
			    $response = file_get_contents($url);

			    $otp_disable = array();
			    
				
				$response = json_decode($response);		
			    if($response->statusCode=="2XX"){
			    	$data = $response->data;
			    	foreach ($data as $k => $d) {
			    		$attributes = array();
			    		$attributes = $d->attributes;
			    		foreach ($attributes as $k => $v) {
			    			if($v->attributeName=='OTP_DISABLE')
			    				$attributeId = $v->id;
			    		}
			    	}


			    }
			    var_dump($response);
	    		if($attributeId){
	    			$url = USER_ATTRIBUTES_API_URL."/{$userId->USER_ID}/attribute/{$attributeId}";

	              if($txtSubsUserOtp[$cnt]=="TRUE")
	              	$disable_otp = 'TRUE';
	              else
	              	$disable_otp = 'FALSE';
	              

	              $post = array(
	            			"attributeName"=>'OTP_DISABLE',
	            			"attributeValue"=>$disable_otp,
	            		);
	              $response_otp = curl_request(json_encode($post), 'PUT', $url);
	              var_dump($response_otp);die("herererrrew");
	    		}
	    		else{
	    			$url = USER_ATTRIBUTES_API_URL."/{$userId->USER_ID}/attribute";

	              if($txtSubsUserOtp[$cnt]=="TRUE")
	              	$disable_otp = 'TRUE';
	              else
	              	$disable_otp = 'FALSE';
	              

	              $post = array(
	            			"attributeName"=>'OTP_DISABLE',
	            			"attributeValue"=>$disable_otp,
	            		);
	              $response_otp = curl_request(json_encode($post), 'POST', $url);
	              print("<pre>");
	              var_dump($url); var_dump(json_encode($post));
	              var_dump($response_otp);die("herererrrew");
	    		}

			  
			}else{
				//die("here2");
			  $compArr = Company::getAllCompany($arr=array('id'=>$txtCompId));		
			  $pwd = time(); 
			  //create user in forum table			
			  //mysql_query("INSERT INTO `proptiger`.`FORUM_USER` (`USER_ID`, `USERNAME`, `EMAIL`, `CONTACT`, `PROVIDERID`, `PROVIDER`, `FB_IMAGE_URL`, `IMAGE`, `PASSWORD`, `CITY`, `COUNTRY_ID`, `UNIQUE_USER_ID`, `CREATED_DATE`, `STATUS`, `IS_SUBSCRIBED`, `UNSUBSCRIBED_AT`) VALUES (NULL, '', '".$email."','".$phone."', '0', '', '', ' ', '".md5($pwd)."', '".$cityArray[$compArr[0]['city']]."', '1', '', '".date('Y-m-d H:i:s')."', '1', 0, '".date('Y-m-d H:i:s')."');") or die(mysql_error());
			  //$userId = mysql_insert_id();

			 //$post = '{"userName":"'.$email.'", "email":"'.$email.'","contact":"'.$phone.'","password":"'.$pwd.'","confirmPassword":"'.$pwd.'","countryId":"+91"}';

			$contactNumbers = array();
			$contact = array(
						"contactNumber"=> $phone
					);
			array_push($contactNumbers, $contact);

			
            
			  $post = array(
            			"fullName"=>$name,
            			"email"=>$email,
            			"contactNumbers"=>$contactNumbers,
            			"password"=>$pwd,
            			"confirmPassword"=>$pwd,
            			"countryId"=>"+91"
            		);
            
            $url = USER_API_URL;
            //echo $post;
            $response = curl_request(json_encode($post), 'POST', $url);
            if($response['statusCode']=="2XX"){
              $userId = $response['id'];
              $url = USER_ATTRIBUTES_API_URL."/{$userId}/attribute";

              if($txtSubsUserOtp[$cnt]=="TRUE")
              	$disable_otp = 'TRUE';
              else
              	$disable_otp = 'FALSE';
              

              $post = array(
            			"attributeName"=>'OTP_DISABLE',
            			"attributeValue"=>$disable_otp,
            		);
              $response_otp = curl_request(json_encode($post), 'POST', $url);
              var_dump($response_otp); //die("here dfdsadf");
            }
            else die("error in user mapping : ".$response['error']);

			   $res = mysql_query("INSERT INTO `proptiger`.`user_subscription_mappings` (`id`, `subscription_id`, `user_id`, `created_by`, `created_at`) VALUES (NULL, '".$subs_id."', '".$userId."', '".$_SESSION['adminId']."', '".date('Y-m-d H:i:s')."')");
			   //sending email to newly created user
			  //$email = "kuldeep.patel_c@proptiger.com";
			  $subject= "Your User Account has been created!";
			  $email_message = "Hi,<br/><br/> Your account has been created at Proptiger.com.<br/>
			  User = ".$email."<br/>"."Password = ".$pwd."<br/><br/>Regards,<br/>Proptiger.com";
			  //$to = $email;
                          $to = 'dl.login@proptiger.com';
			  $sender = "no-reply@proptiger.com";
			  $headers  = 'MIME-Version: 1.0' . "\r\n";
			  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			  $headers .= 'To: '.$email."\r\n";
			  $headers .= 'From: '.$sender."\r\n";
			  sendMailFromAmazon($to, $subject, $email_message, $sender,null,null,false);
			}
			$cnt++;	 
		  }		  
		   		  
		  		  
	  }catch(Exception $e){	
		  $error_flag = "Some error occurs in Company Order Saving! Try Again";	 	  
		  return false;	  
	  }
		  
	});	 
	if($error_flag == '')
	   header("Location:companyOrdersList.php?compId=".$txtCompId); 	  
	  
  }

  if((isset($_REQUEST['o']) && $_REQUEST['page'] == 'view') || isset($_REQUEST['o']) && $_REQUEST['page'] == 'edit'){
	 $page = mysql_real_escape_string($_REQUEST['page']);
	 $orderId = mysql_real_escape_string($_REQUEST['o']);
	 
	 $order_details = CompanyOrder::getOrderDetails($orderId);
	 
	 //basic
	 $smarty->assign('txtCompId',$order_details['client_id']);
         $smarty->assign('orderName',$order_details['order_name']);
	 $smarty->assign('txtCompName',$order_details['company_name']);
	 $smarty->assign('txtSalesPerson',$order_details['sales_person_id']);
	 $smarty->assign('txtOrderDate',$order_details['order_date']);
	 $smarty->assign('orderType',strtolower($order_details['order_type']));
	 $smarty->assign('txtOrderDur',$order_details['order_duration']);
	 $smarty->assign('txtExpiryTrialOrderDate',$order_details['order_expiry_date']);
	 $smarty->assign('txtExpiryOrderDate',$order_details['order_expiry_date']);
	 $smarty->assign('txtOrderAmt',$order_details['order_amount']);
	
	 //payment
	 $smarty->assign('txtPaymentDetails',$order_details['payment_details']);	 
	 $smarty->assign('pmtNo',$order_details['pmtNo']);
	 
	 //section access and data access
	 $smarty->assign('section_access',$order_details['sections']);
	 $smarty->assign('data_access',$order_details['data_access']);
	 
	 $smarty->assign('gAccess',$order_details['gAccess']);
	 $smarty->assign('gAccess_ids',json_encode($order_details['gAccess_ids']));

	 $userIds = $order_details['user_ids'];
	 print("<pre>");
	 print_r($order_details);
    $url = USER_DETAILS_API_URL."/?userId=".implode($userIds, ",");
    $response = file_get_contents($url);
    $full_name = array();
    $user_emails = array();
    $contact_no = array();
    $otp_disable = array();
    $user_nos = 0;
   echo $url; 
	var_dump($response); 
	
	$response = json_decode($response);
    if($response->statusCode=="2XX"){
    	$data = $response->data;
    	foreach ($data as $k => $d) {
    		array_push($full_name, $d->fullName);
    		array_push($user_emails, $d->email);
    		$user_nos++;
    		$contact_nos = $d->contactNumbers;
    		array_push($contact_no, $contact_nos[0]->contactNumber);
    		$attributes = array();
    		$attributes = $d->attributes;
    		foreach ($attributes as $k => $v) {
    			if($v->attributeName=='OTP_DISABLE'){
    				array_push($otp_disable, $v->attributeValue); 
    			}

    		}
    	}


    }
    


    $smarty->assign('txtSubsUserName',$full_name);
	 //$smarty->assign('txtSubsUserEmail',$order_details['user_emails']);
   	 $smarty->assign('txtSubsUserEmail',$user_emails);
	 $smarty->assign('txtSubsUserId',$order_details['user_ids']);
	 //$smarty->assign('userNo',$order_details['userNo']);
	 $smarty->assign('userNo',$user_nos);
	 $smarty->assign('txtSubsUserCont',$contact_no);
	 $smarty->assign('txtSubsUserOtp',$otp_disable);
		
     $smarty->assign("subsId",$order_details['subscription_id']);
	 $smarty->assign("page",$page);
	 $smarty->assign("orderId",$orderId);
  }
  
  if($_POST['btnExit'] == 'Exit'){
	header("Location:companyOrdersList.php");  
  }

  /////////// Initial Values
  $orderDur = array("1week"=>"1 week","2weeks"=>"2 weeks","3weeks"=>"3 weeks","4weeks"=>"4 weeks");
  $smarty->assign("orderDur",$orderDur);
  $paymentMhd = array("BankAccountTrnasfer"=>"Bank Account Transfer","BankDraft" => "Bank Draft","Other" => "Other");
  $smarty->assign("paymentMhd",$paymentMhd);
  $paymentNoDetails = 1; //by defualt
  $smarty->assign('paymentNoDetails',$paymentNoDetails);
  $cityArray = City::CityArr();
  $smarty->assign("cityArray", $cityArray);
  #$locsArray = Locality::localityList();
  #$smarty->assign("locsArray", $locsArray);
  $subsUsrDetails = 1; //by defualt
  $smarty->assign('subsUsrDetails',$subsUsrDetails);
  #populate sales persons
  $sales_pers = fetch_sales_persons();
  $smarty->assign('sales_pers',$sales_pers);
  #$trial_expiry_date = date("Y-m-d",strtotime("+ 1week"));
  #$smarty->assign('txtExpiryTrialOrderDate',$trial_expiry_date);
  $smarty->assign('error_flag',$error_flag);

 
?>
