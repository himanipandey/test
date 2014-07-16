<?php
//echo "here";
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

//echo "here";
if($_POST['task']=='office_locations'){
    $cityId = $_POST['cityId'];
    //$locList = Locality::getLocalityByCity($cityId);
    $query = "select locality_id, label from locality l where l.city_id='{$cityId}'";
    $res = mysql_query($query) or die(mysql_error());
    
    $html =  "";
    while ($data = mysql_fetch_assoc($res)) {
        $html .= "<option value='".$data['locality_id']."' >".$data['label']."</option>";
     }

                                      
    echo $html;
}


if($_POST['task']=='createAgent'){
    $agentId = $_POST['id'];
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
		
		$sql_comp = mysql_query("select * from agents where id='{$agentId}'") or die (mysql_error());

       // (status, broker_id, academic_qualification_id, name, seller_type, active_since, email, created_at, updated_by) values ('{$status}', '{$brokerId}', '{$qualification}', '{$name}','{$role}', '{$active_since}', '{$email}', NOW(), '{$_SESSION['adminId']}')"
            
        if(mysql_num_rows($sql_comp)>0){
			
			$sql = "UPDATE agents set seller_type='{$role}', broker_id='{$brokerId}', academic_qualification_id='{$qualification}', status='{$status}', name='{$name}', active_since='{$active_since}', email='{$email}', updated_by='{$_SESSION['adminId']}', updated_at=NOW() where id='{$agentId}'";
			
			$res_sql = mysql_query($sql) or die(mysql_error());

            $query1 = "UPDATE addresses SET address_line_1='{$address}', city_id='{$city}', pincode='{$pin}', updated_by={$_SESSION['adminId']}, updated_at=NOW()  WHERE (table_name='agents' and table_id='{$agentId}' )";
            $res1 = mysql_query($query1) or die(mysql_error());

           

                
                $query3 = "UPDATE contact_numbers SET contact_no='{$phone}', updated_by={$_SESSION['adminId']}, updated_at=NOW() WHERE (table_name='agents' and table_id='{$agentId}' and type='mobile')";
            
                $res3 = mysql_query($query3) or die(mysql_error());

            

                $query5 = "UPDATE contact_numbers SET contact_no='{$compphone}', updated_by={$_SESSION['adminId']}, updated_at=NOW() WHERE (table_name='agents' and table_id='{$agentId}' and type='phone1')";
                 
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
        
        $query = "INSERT INTO agents(status, broker_id, academic_qualification_id, name, seller_type, active_since, email, created_at, updated_by) values ('{$status}', '{$brokerId}', '{$qualification}', '{$name}','{$role}', '{$active_since}', '{$email}', NOW(), '{$_SESSION['adminId']}')";
      
        $res = mysql_query($query) or die(mysql_error());
        if(mysql_affected_rows()>0){
            $agent_id = mysql_insert_id();
            $query1 = "INSERT INTO addresses (table_name, table_id, address_line_1, city_id, pincode, updated_by, created_at) values ('agents', '{$agent_id}', '{$address}', '{$city}', '{$pin}', {$_SESSION['adminId']}, NOW())";
            $res1 = mysql_query($query1) or die(mysql_error());

            $query2 = "insert into agents(chkAddr) value('on') where id='{$agent_id}'";
            $res2 = mysql_query($query2) or die(mysql_error());


            $query4 = "INSERT INTO contact_numbers (table_name, table_id, contry_code, contact_no, type, updated_by, created_at) values ('agents', '{$agent_id}', '+91', '{$phone}', 'mobile', {$_SESSION['adminId']}, NOW()), ('agents', '{$agent_id}', '+91', '{$compphone}', 'phone1', {$_SESSION['adminId']}, NOW())";
           
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
