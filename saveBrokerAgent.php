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
                $post = '{"userName":"'.$name.'", "email":"'.$email.'","contact":"'.$phone.'","password":"'.$pass.'","confirmPassword":"'.$pass.'","countryId":"+91"}';
                
                $url = USER_API_URL;
                //echo $post;
                $response = curl_request($post, 'POST', $url);
                if($response['statusCode']=="2XX")
                    $user_id = $response['id'];
                else die("error in user mapping : ".$response['error']);


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


        $query = "SELECT USER_ID FROM proptiger.FORUM_USER WHERE EMAIL='{$email}' and STATUS='1'";
        $res = mysql_query($query);
        $data = mysql_fetch_assoc($res);
        //print_r($data['USER_ID']); die;
        if(!$data['USER_ID']>0){
            $pass = randomPassword();
            $post = '{"userName":"'.$name.'", "email":"'.$email.'","contact":"'.$phone.'","password":"'.$pass.'","confirmPassword":"'.$pass.'","countryId":"+91"}';
            
            $url = USER_API_URL;
            //echo $post;
            $response = curl_request($post, 'POST', $url);
            if($response['statusCode']=="2XX")
                $user_id = $response['id'];
            //else die("error in user mapping : ".$response['error']);


        }
        else $user_id = $data['USER_ID']; 
        

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



    
function curl_request($post, $method, $url){
        //echo "curl-start:".microtime(true)."<br>";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                    
            'Content-Type: application/json',                                                                                
            'Content-Length: ' . strlen($post))                                                                       
        ); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,$method);
        if($method == "POST" || $method == "PUT")
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response= curl_exec($ch);
       
        $responseArr = json_decode($response);
        
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $response_header = substr($response, 0, $header_size);
        $response_body = json_decode(substr($response, $header_size));
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);
        //echo $response_body->statusCode; echo $response_body->data->id; echo $response_body->error->msg;
        //print("<pre>"); print_r($pos); echo $url;//echo "head:";var_dump($response_header); echo "body:"; var_dump($response_body);echo "status:"; var_dump($status);
        //die();
        //echo "curl-end:".microtime(true)."<br>";
        return array("header" => $response_header, "id" => $response_body->data->id, "statusCode" => $response_body->statusCode, "error"=> $response_body->error->msg);
        //return $response;
    }


function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}


?>