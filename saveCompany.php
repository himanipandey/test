<?php

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

if($_POST['task']='find_project_builder'){
    //echo "hello";
    $locality = $_POST['locality'];
    //$arr = explode("-", $locality);
    $locId = $locality;//$arr[2];
    $q = $_POST['query'];
    $option = $_POST['option'];
    if($option =='all' || $option=='' || $option==='undefined')
        $option = "project";
    $data = array();
    //$locList = Locality::getLocalityByCity($cityId);
    if($locId){
        $query = "select l.locality_id, l.label as locLabel, c.label as cLabel, c.city_id from locality l inner join city c on l.city_id=c.city_id where l.locality_id='{$locId}'";
        //echo $query;
        $res = mysql_query($query) or die(mysql_error());
        $locdata = mysql_fetch_assoc($res);
        
    
        if($option=='project'){
            $query = "select rp.project_id, rp.project_name from resi_project rp where (rp.locality_id='{$locId}' and rp.project_name like '%$q%' and rp.version='Cms') ORDER BY
  CASE
    WHEN rp.project_name LIKE 'q%' THEN 1
    WHEN rp.project_name LIKE '%q' THEN 3
    ELSE 2
  END";
            $result = mysql_query($query) or die(mysql_error());
            //echo $query;
            while($res=mysql_fetch_assoc($result)){
                $response = array();
                
                $response['locId'] = $locdata['locality_id'];
                $response['locName'] = $locdata['locLabel'];
                $response['cId'] = $locdata['city_id'];
                $response['cName'] = $locdata['cLabel'];
                $response['pId'] = $res['project_id'];
                $response['pName'] = $res['project_name'];
                $response['ptype'] = 'project';
                array_push($data, $response);
                //print_r($res);
                
            }

        }
        else if($option=='builder'){
            $query = "select rp.builder_id, rb.builder_name from resi_project rp 
            inner join resi_builder rb on rb.builder_id=rp.builder_id 
            where (rp.locality_id='{$locId}' and rb.builder_name like '%$q%') group by rp.builder_id";
            $result = mysql_query($query) or die(mysql_error());
            while($res=mysql_fetch_assoc($result)){
                $response = array();
                $response['locId'] = $locdata['locality_id'];
                $response['locName'] = $locdata['locLabel'];
                $response['cId'] = $locdata['city_id'];
                $response['cName'] = $locdata['cLabel'];
                $response['pId'] = $res['builder_id'];
                $response['pName'] = $res['builder_name'];
                $response['ptype'] = 'builder';
                array_push($data, $response);
            }
        }
    }
    else{
        array_push($data, "Please Select a locality first");
    }
   
    $data =  '{"data": '.  json_encode($data) . '}';
    echo $data;                               
    //echo $data['locality_id'];
}

if($_POST['task']=='createComp'){
    $id = $_POST['id'];
    $type = $_POST['type'];
   $des   = mysql_real_escape_string($_POST['des']);

    $name   = $_POST['name'];
    $address   = mysql_real_escape_string($_POST['address']);
    $address = preg_replace('!\s+!', ' ', $address);
    $city   = $_POST['city'];
    $pin   = $_POST['pincode'];
    $compphone   = $_POST['compphone'];
    $image = $_POST['image'];
//echo $image;
    $ipArr = $_POST['ipArr'];
    $person   = $_POST['person'];
    $phone   = $_POST['phone'];
    $web   = $_POST['web'];
    $fax   = $_POST['fax'];
    $email   = $_POST['email'];
    $pan   = $_POST['pan'];
    $status   = $_POST['status'];
    $mode =  $_POST['mode'];
    $altText = "company".$name;

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

           
    
    if($mode=='update' && $id!==null){
		
		$imageId = $_POST['imageId'];
		
		$sql_comp = mysql_query("select * from company where id='{$id}'") or die (mysql_error());
            
        if(mysql_num_rows($sql_comp)>0){
			
			$sql = "UPDATE company set type='{$type}', status='{$status}', name='{$name}', description='{$des}', primary_email='{$email}', pan='{$pan}', updated_by='{$_SESSION['adminId']}', updated_at=NOW() where id='{$id}'";
			
			$res_sql = mysql_query($sql) or die(mysql_error());

            $query1 = "UPDATE addresses SET address_line_1='{$address}', city_id='{$city}', pincode='{$pin}', updated_by={$_SESSION['adminId']}, updated_at=NOW()  WHERE (table_name='company' and table_id='{$id}' )";
            $res1 = mysql_query($query1) or die(mysql_error());

            $query_find_ips = "delete from company_ips where company_id={$id}";//echo $query_find_ips;
            $res = mysql_query($query_find_ips) or die(mysql_error());
            //$old_no = mysql_num_rows($res);
            foreach ($ipArr as $k => $v) {
                $query = "INSERT INTO company_ips (company_id, ip, created_by, created_at) values ('{$id}', '{$v}', {$_SESSION['adminId']}, NOW())";
                $res = mysql_query($query) or die(mysql_error());
            }


            $query2 = "UPDATE broker_contacts SET name='{$person}', contact_email='{$email}', updated_by={$_SESSION['adminId']}, updated_at=NOW()  WHERE (broker_id='{$id}' and type='NAgent' )";
            
            $res2 = mysql_query($query2) or die(mysql_error());
            //if(mysql_affected_rows()>0){

                $query2 = "SELECT id from broker_contacts WHERE (broker_id='{$id}' and type='NAgent' )";
                //echo $query2;
                $res2 = mysql_query($query2) or die(mysql_error());
                $dataArr = mysql_fetch_assoc($res2);
                $broker_contacts_id = $dataArr['id'];
                //var_dump($dataArr);
                $query3 = "UPDATE contact_numbers SET contact_no='{$compphone}', updated_by={$_SESSION['adminId']}, updated_at=NOW() WHERE (table_name='company' and table_id='{$id}' and type='cc_phone')";
            
                $res3 = mysql_query($query3) or die(mysql_error());

                $query4 = "UPDATE contact_numbers SET contact_no='{$fax}', updated_by={$_SESSION['adminId']}, updated_at=NOW() WHERE (table_name='broker_contacts' and table_id='{$broker_contacts_id}' and type='fax')";
                 
                $res4 = mysql_query($query4) or die(mysql_error());

                $query5 = "UPDATE contact_numbers SET contact_no='{$phone}', updated_by={$_SESSION['adminId']}, updated_at=NOW() WHERE (table_name='broker_contacts' and table_id='{$broker_contacts_id}' and type='phone1')";
                 
                $res5 = mysql_query($query5) or die(mysql_error());

                
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
            //}

            echo "1";
        }
        else if (!mysql_error()) echo "2";
        else  echo "3";

    }
    if ($mode=='create'){
        
        $query = "INSERT INTO company(type, status, name, description, primary_email, pan, created_at, updated_by) values ('{$type}', '{$status}','{$name}','{$des}', '{$email}', '{$pan}', NOW(), {$_SESSION['adminId']})";
        
        $res = mysql_query($query) or mysql_error();
        if(mysql_affected_rows()>0){
            $comp_id = mysql_insert_id();
            $query1 = "INSERT INTO addresses (table_name, table_id, address_line_1, city_id, pincode, updated_by, created_at) values ('company', '{$comp_id}', '{$address}', '{$city}', '{$pin}', {$_SESSION['adminId']}, NOW())";
            $res1 = mysql_query($query1) or die(mysql_error());

            foreach ($ipArr as $k => $v) {
                $query = "INSERT INTO company_ips (company_id, ip, created_by, created_at) values ('{$comp_id}', '{$v}', {$_SESSION['adminId']}, NOW())";
                $res = mysql_query($query) or die(mysql_error());
            }
            
             $query3 = "INSERT INTO contact_numbers (table_name, table_id, contry_code, contact_no, type, updated_by, created_at) values ('company', '{$comp_id}', '+91', '{$compphone}', 'cc_phone', {$_SESSION['adminId']}, NOW())";
              
             $res3 = mysql_query($query3) or die(mysql_error());


            $query2 = "INSERT INTO broker_contacts (broker_id, name, type, contact_email, updated_by, created_at, updated_at) values ('{$comp_id}', '{$person}', 'NAgent', '{$email}', {$_SESSION['adminId']}, NOW(), NOW())";
            $res2 = mysql_query($query2) or die(mysql_error());
            if(mysql_affected_rows()>0){
               
                $broker_contacts_id = mysql_insert_id();
               

                $query4 = "INSERT INTO contact_numbers (table_name, table_id, contry_code, contact_no, type, updated_by, created_at) values ('broker_contacts', '{$broker_contacts_id}', '+91', '{$fax}', 'fax', {$_SESSION['adminId']}, NOW())";
               
                $res4 = mysql_query($query4) or die(mysql_error());

                $query5 = "INSERT INTO contact_numbers (table_name, table_id, contry_code, contact_no, type, updated_by, created_at) values ('broker_contacts', '{$broker_contacts_id}', '+91', '{$phone}', 'phone1', {$_SESSION['adminId']}, NOW())";

                $res5 = mysql_query($query5) or die(mysql_error());

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
            }

            echo "1";
        }
            
        else
            echo "3";
    }
        
}

    

?>
