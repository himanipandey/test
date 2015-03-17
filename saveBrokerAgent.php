<?php
//echo "here"; //die;
error_reporting(1);
ini_set('display_errors','1');
set_time_limit(0);
ini_set("memory_limit","256M");
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
include("function/functions_priority.php");
include("common/function.php");
include("imageService/image_upload.php");
include_once("includes/send_mail_amazon.php");

AdminAuthentication();

///echo "here"; die;
if($_POST['task']=='office_locations'){
    $cityId = $_POST['cityId'];
    //$locList = Locality::getLocalityByCity($cityId);
    $query = "select l.locality_id, l.label from locality l 
        inner join suburb s on s.suburb_id=l.suburb_id
    where s.city_id='{$cityId}'";
    $res = mysql_query($query) or die(mysql_error());
    
    $html =  "";
    while ($data = mysql_fetch_assoc($res)) {
        $html .= "<option value='".$data['locality_id']."' >".$data['label']."</option>";
     }

                                      
    echo $html;
}


if($_POST['task']=='createAgent'){
    $agentId = $_POST['id'];
    $userId = $_POST['userId'];
    $brokerId = $_POST['brokerId'];
    

    $name   = $_POST['name'];
    $address   = $_POST['address'];
    $address = preg_replace('!\s+!', ' ', $address);
    $city   = $_POST['city'];
    $pin   = $_POST['pincode'];
    $compphone   = $_POST['compphone'];
    //$image = $_POST['image'];
//echo $image;
    //$ipArr = $_POST['ipArr'];
    //$person   = $_POST['person'];
    $phone   = $_POST['phone'];
   // $web   = $_POST['web'];
    //$fax   = $_POST['fax'];
    $email   = $_POST['email'];
    $role = $_POST['agent_role'];
    $qualification = $_POST['qualification'];
    $active_since = $_POST['active_since'];
    //$pan   = $_POST['pan'];
    $status   = $_POST['status'];
    $mode =  $_POST['mode'];


    //$altText = "company".$name;
/*
    if(isset($_POST['image']) && $image!=""){
        //print_r($_FILES[]);
        //$file = $_FILES

      $file =  $newImagePath."company/".$image;
      //var_dump($file);
      

        $finfo = finfo_open();
         
        $fileinfo = finfo_file($finfo, $file, FILEINFO_MIME);
         
        finfo_close($finfo);
        //var_dump($fileinfo);
        $imgtype = explode(";", $fileinfo);
        $imgParams = array();
        $imgParams['name']= $image;
        $imgParams['type'] = $imgtype[0];

     $params = array(
                        "image_type" => "logo",
                        "folder" => "company/",
                        "image" => $image,
                        "title" => $name,
                        "altText" => $altText,

            );

            $dest       =   $newImagePath."company/".$image;
            $postArr = array();
            $unitImageArr = array();
            $unitImageArr['img'] = $imgParams;
            $unitImageArr['objectType'] = "company";
            $unitImageArr['newImagePath'] = $newImagePath;
            $unitImageArr['params'] = $params;  
            

    }

           
*/    

    //echo "hello";
    if($mode=='update' && $agentId!==null){
		 
		//$imageId = $_POST['imageId'];
        $query = "Select EMAIL FROM proptiger.FORUM_USER WHERE USER_ID={$userId}";
        $res = mysql_query($query);
        $data = mysql_fetch_assoc($res);
        if($data['EMAIL']!=$email){

            $query1 = "SELECT USER_ID FROM proptiger.FORUM_USER WHERE EMAIL='{$email}' and STATUS='1'";
            $res1 = mysql_query($query1);
            $data1 = mysql_fetch_assoc($res1);
            //print_r($data['USER_ID']); die;
            if(!$data1['USER_ID']>0){
               
                $pass = randomPassword();
                //$post = '{"userName":"'.$name.'", "email":"'.$email.'","contact":"'.$phone.'","password":"'.$pass.'","confirmPassword":"'.$pass.'","countryId":"+91"}';
                
                    $contactNumbers = array();
            $contact = array(
                        "contactNumber"=> $phone
                    );
            array_push($contactNumbers, $contact);

              $post = array(
                        "fullName"=>$name,
                        "email"=>$email,
                        "contactNumbers"=>$contactNumbers,
                        "password"=>$pass,
                        "confirmPassword"=>$pass,
                        "countryId"=>"+91"
                    );

                $url = USER_API_URL;
                //echo $post;
                $response = curl_request(json_encode($post), 'POST', $url);
                if($response['statusCode']=="2XX"){
                    $user_id = $response['id'];
                    $to = 'mohit.dargan@proptiger.com';
                    $subject= "New Broker User Account created!";
                      $email_message = "Hi,<br/><br/> New account has been created at Proptiger.com.<br/>
                      User = ".$email."<br/>"."Password = ".$pass."<br/><br/>Regards,<br/>Proptiger.com";
                      //$to = $email;
                
                      $sender = "no-reply@proptiger.com";
                      $cc = "karanvir.singh@proptiger.com";
                      //$headers  = 'MIME-Version: 1.0' . "\r\n";
                      //$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                      //$headers .= 'To: '.$email."\r\n";
                      //$headers .= 'From: '.$sender."\r\n";
                      sendMailFromAmazon($to, $subject, $email_message, $sender,$cc,null,false);
                }
                else  die("error in user mapping : ".$response['error']);


            }
            else $user_id = $data['USER_ID']; 
        }
        else $user_id = $userId;    
		
		$sql_comp = mysql_query("select * from company_users where id='{$agentId}'") or die (mysql_error());

       // (status, broker_id, academic_qualification_id, name, seller_type, active_since, email, created_at, updated_by) values ('{$status}', '{$brokerId}', '{$qualification}', '{$name}','{$role}', '{$active_since}', '{$email}', NOW(), '{$_SESSION['adminId']}')"
            
        if ($qualification=='') $qualification = "null";
        if ($user_id=='') $user_id = "null";   
        if(mysql_num_rows($sql_comp)>0){
			
			$sql = "UPDATE company_users set seller_type='{$role}', company_id={$brokerId}, academic_qualification_id={$qualification}, status='{$status}', name='{$name}', active_since='{$active_since}', email='{$email}', user_id='{$user_id}', updated_by='{$_SESSION['adminId']}', updated_at=NOW() where id='{$agentId}'";
			
			$res_sql = mysql_query($sql) or die(mysql_error());

            $query1 = "UPDATE addresses SET address_line_1='{$address}', city_id='{$city}', pincode='{$pin}', updated_by={$_SESSION['adminId']}, updated_at=NOW()  WHERE (table_name='company_users' and table_id='{$agentId}' )";
            $res1 = mysql_query($query1) or die(mysql_error());

           

                
                $query3 = "UPDATE contact_numbers SET contact_no='{$phone}', updated_by={$_SESSION['adminId']}, updated_at=NOW() WHERE (table_name='company_users' and table_id='{$agentId}' and type='mobile')";
            
                $res3 = mysql_query($query3) or die(mysql_error());

            

                $query5 = "UPDATE contact_numbers SET contact_no='{$compphone}', updated_by={$_SESSION['adminId']}, updated_at=NOW() WHERE (table_name='company_users' and table_id='{$agentId}' and type='phone1')";
                 
                $res5 = mysql_query($query5) or die(mysql_error());

                /*
                if(isset($_POST['image']) && $image!=""){ 					
					$unitImageArr['objectId'] = $id;
                    $unitImageArr['params']['service_image_id'] = $imageId;
                    $unitImageArr['params']['update'] = "update";
                    $postArr[] = $unitImageArr;         
                    $response   = writeToImageService($postArr);
                    //print_r($response); die;
                    foreach ($response as $k => $v) {            
                        if(empty($v->error->msg)){                           
                            $image_id = $v->data->id;
                            //echo $image_id;//$image_id = $image_id->id;
                        }
                        else {
                            
                            $Error = $v->error->msg;
                            echo $Error;
                        }
                    }
                }
                */
            //}

            echo "1";
        }
        else if (!mysql_error()) echo "2";
        else  echo "3";

    }
    if ($mode=='create'){
        //get user id if user already exist against agent email  or create a new user 

        $query = "SELECT count(*) as count FROM company c inner join company_users cu on cu.company_id=c.id
                    where c.id={$brokerId} and cu.status='Active'";
        $res = mysql_query($query) or die(mysql_error());
        $data = mysql_fetch_assoc($res);
        if($data['count'] > 0 ){
            die("Broker Company Can not have more than one Users.");
        }

        $query = "SELECT USER_ID FROM proptiger.FORUM_USER WHERE EMAIL='{$email}' and STATUS='1'";
        $res = mysql_query($query);
        $data = mysql_fetch_assoc($res);
        //print_r($data['USER_ID']); die;
        if(!$data['USER_ID']>0){
            $pass = randomPassword();
            //$post = '{"userName":"'.$name.'", "email":"'.$email.'","contact":"'.$phone.'","password":"'.$pass.'","confirmPassword":"'.$pass.'","countryId":"+91"}';

            $contactNumbers = array();
            $contact = array(
                        "contactNumber"=> $phone
                    );
            array_push($contactNumbers, $contact);

            
              $post = array(
                        "fullName"=>$name,
                        "email"=>$email,
                        "contactNumbers"=>$contactNumbers,
                        "password"=>$pass,
                        "confirmPassword"=>$pass,
                        "countryId"=>"+91"
                    );
            
            $url = USER_API_URL;
            //echo $post;
            $response = curl_request(json_encode($post), 'POST', $url);
            if($response['statusCode']=="2XX"){
              $user_id = $response['id'];

              //$query = "select pa.ADMINEMAIL from broker_details bd inner join proptiger.PROPTIGER_ADMIN pa on bd.pt_manager_id=pa.ADMINID where bd.broker_id={$brokerId}";
              //echo $query;
              //$res = mysql_query($query);
              //$data = mysql_fetch_assoc($res);
              //$pt_manager_email = $data['ADMINEMAIL'];


              $to = 'mohit.dargan@proptiger.com';
              $subject= "New Broker User Account created!";
              $email_message = "Hi,<br/><br/> New account has been created at Proptiger.com.<br/>
              User = ".$email."<br/>"."Password = ".$pass."<br/><br/>Regards,<br/>Proptiger.com";
              //$to = $email;
                
              $sender = "no-reply@proptiger.com";
              $cc = "karanvir.singh@proptiger.com";
              //$headers  = 'MIME-Version: 1.0' . "\r\n";
              //$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
              //$headers .= 'To: '.$email."\r\n";
              //$headers .= 'From: '.$sender."\r\n";
              sendMailFromAmazon($to, $subject, $email_message, $sender,$cc,null,false);
              //sendMailFromAmazon($pt_manager_email, $subject, $email_message, $sender,null,null,false);
              //sendMailFromAmazon($email, $subject, $email_message, $sender,null,null,false);
              //sendMailFromAmazon("manmohan.pandey@proptiger.com", $subject, $email_message, $sender,null,null,false);
              //echo $pt_manager_email; 
              //die($pt_manager_email);
            }
             
            else die("error in user mapping : ".$response['error']);


        }
        else $user_id = $data['USER_ID']; 
        //die("here");

       // if ($response['stat'])

        if ($qualification=='') $qualification = "null";
        if ($user_id=='') $user_id = "null";
        $query = "INSERT INTO company_users(status, company_id, academic_qualification_id, name, seller_type, active_since, email, user_id, created_at, updated_by) values ('{$status}', {$brokerId}, {$qualification}, '{$name}','{$role}', '{$active_since}', '{$email}', {$user_id}, NOW(), '{$_SESSION['adminId']}')";
      
        $res = mysql_query($query) or die(mysql_error());
        if(mysql_affected_rows()>0){
            $agent_id = mysql_insert_id();
            $query1 = "INSERT INTO addresses (table_name, table_id, address_line_1, city_id, pincode, updated_by, created_at) values ('company_users', '{$agent_id}', '{$address}', '{$city}', '{$pin}', {$_SESSION['adminId']}, NOW())";
            $res1 = mysql_query($query1) or die(mysql_error());

            $query2 = "UPDATE company_users set chkAddr='on' where id='{$agent_id}'";
            $res2 = mysql_query($query2) or die(mysql_error());


            $query4 = "INSERT INTO contact_numbers (table_name, table_id, contry_code, contact_no, type, updated_by, created_at) values ('company_users', '{$agent_id}', '+91', '{$phone}', 'mobile', {$_SESSION['adminId']}, NOW()), ('company_users', '{$agent_id}', '+91', '{$compphone}', 'phone1', {$_SESSION['adminId']}, NOW())";
           
            $res4 = mysql_query($query4) or die(mysql_error());

               
               /* 
                if(isset($_POST['image']) && $image!=""){
                    $unitImageArr['objectId'] = $comp_id;
                    $postArr[] = $unitImageArr;         
                    $response   = writeToImageService($postArr);
                    //print_r($response);
                    foreach ($response as $k => $v) {
            
                        if(empty($v->error->msg)){
                        
                            
                            $image_id = $v->data->id;
                            //echo $image_id;//$image_id = $image_id->id;
                        }
                        else {
                            
                            $Error = $v->error->msg;
                            echo $Error;
                        }
                    }
                }
                */
            

            echo "1";
        }
            
        else
            echo "3";
    }
        
}



    


?>