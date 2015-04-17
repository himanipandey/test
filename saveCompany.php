<?php

error_reporting(1);
ini_set('display_errors','1');
set_time_limit(0);
ini_set("memory_limit","256M");
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
//include("modelsConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
include("function/functions_priority.php");
include("common/function.php");
include("imageService/image_upload.php");
include_once("includes/send_mail_amazon.php");

AdminAuthentication();

//echo "here";
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

if($_POST['task']=='find_company_name'){
    $q = $_POST['query'];
    $arr = array();
    $query = "Select id, name from company where name like '%{$q}%'";
    $res = mysql_query($query);
    while($r = mysql_fetch_assoc($res)) {
        $tmp = array();
        $tmp['name'] = $r['name'];
        array_push($arr, $tmp);
    }

    $data = '{"data": '.json_encode($arr).'}';
    echo $data;
}

if($_POST['task']=='find_project_builder'){
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
        $query = "select l.locality_id, l.label as locLabel, c.label as cLabel, c.city_id from locality l inner join suburb s on s.suburb_id=l.suburb_id 
            inner join city c on s.city_id=c.city_id where l.locality_id='{$locId}'";
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

if($_POST['task']=='getCompanyLogo'){
    $objectId = $_POST['compId'];
    $arr = array('logo'=> array(), 'signUpForm'=>array());
    $objectType = "company";
    //$url = readFromImageService($objectType, $objectId);
    //print ""
    $url = ImageServiceUpload::$image_upload_url."?objectType=$objectType&objectId=".$objectId;
    $content = file_get_contents($url);
    $imgPath = json_decode($content);
    $data = array();
    foreach($imgPath->data as $k1=>$v1){
        if($k1==0){
            $arr['logo']['service_image_path'] = $v1->absolutePath;
            $arr['logo']['alt_text'] = $v1->altText;
            $arr['logo']['service_image_id'] = $v1->id;
        }
    }
    $url = ImageServiceUpload::$doc_upload_url."?objectType=$objectType&objectId=".$objectId."&documentType=companySignupForm";
    $content = file_get_contents($url);
    $imgPath = json_decode($content);
    $data = array();
    foreach($imgPath->data as $k1=>$v1){
        if($k1==0){
            $arr['signUpForm']['service_image_path'] = $v1->absoluteUrl;
            //$arr['signUpForm']['alt_text'] = $v1->altText;
            $arr['signUpForm']['service_image_id'] = $v1->id;
        }
    }



    echo json_encode($arr);
}

if($_POST['task']=='createComp'){ 


    $id = $_POST['id'];
    $type = $_POST['type'];
    $broker_info_type = $_POST['broker_info_type']; 
    //$des   = mysql_real_escape_string($_POST['des']);
    //$des = strip_tags(trim(preg_replace('/\s\s+/', ' ', $des))); 
    $des = preg_replace('!\s+!', ' ', $_POST['des']);
    $des   = mysql_real_escape_string($des);
 
    $name   = $_POST['name'];    
   
    $address = preg_replace('!\s+!', ' ', $_POST['address']);
    $address   = mysql_real_escape_string($address);
    $city   = $_POST['city'];
    $pin   = $_POST['pincode'];
    $compphone   = $_POST['compphone'];
    $web   = $_POST['web'];
    $compfax   = $_POST['compfax'];
    $email   = $_POST['email'];
    $image = $_POST['image'];
    $signUpForm = $_POST['signUpForm'];
//echo $image;
    $ipArr = $_POST['ipArr'];
    
    $pan   = $_POST['pan'];
    $status   = $_POST['status'];
    $mode =  $_POST['mode'];
    $altText = "company ".$name;

    //off_loc_data,coverage_data,contact_person_data,cust_care_data,broker_extra_fields

    $off_loc_data = $_POST['off_loc_data'];
    $coverage_data = $_POST['coverage_data'];
    $contact_person_data = $_POST['contact_person_data'];
    $cust_care_data = $_POST['cust_care_data'];
    $bef = $_POST['broker_extra_fields'];
    $bank_details = $_POST['bank_details'];



    //prepare params for image service
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

    if(isset($_POST['signUpForm']) && $signUpForm!=""){
        //print_r($_FILES[]);
        //$file = $_FILES

        $file =  $newImagePath."company/".$signUpForm;
      //var_dump($file);
      

        $finfo = finfo_open();
         
        $fileinfo = finfo_file($finfo, $file, FILEINFO_MIME);
         
        finfo_close($finfo);
        //var_dump($fileinfo);
        $imgtype = explode(";", $fileinfo);
        $imgParams = array();
        $imgParams['name'] = $signUpForm;
        $imgParams['type'] = $imgtype[0];

        $params = array(
                        "image_type" => "companySignupForm",
                        "folder" => "company/",
                        "image" => $signUpForm,
                        "title" => $name,
                        //"altText" => $altText,
                        
        );

        $dest       =   $newImagePath."company/".$signUpForm;
        $postArr = array();
        $signUpArr = array();
        $signUpArr['img'] = $imgParams;
        $signUpArr['objectType'] = "company";
        $signUpArr['newImagePath'] = $newImagePath;
        $signUpArr['params'] = $params;  
            

    }

           
    
    if($mode=='update' && $id!==null){      
        $imageId = $_POST['imageId'];
        $signupformId = $_POST['formId'];
        
        $sql_comp = mysql_query("select * from company where id='{$id}'") or die (mysql_error());
            
        if(mysql_num_rows($sql_comp)>0){
            
            if($status=='Inactive'){
                $query = "select count(*) as count from company c inner join company_users cu on cu.company_id=c.id
                    where c.id={$id} and c.type='Broker' and cu.status='Active'";
                $res = mysql_query($query) or die(mysql_error());
                $data = mysql_fetch_assoc($res);
                if($data['count'] > 0 ){
                    die("Can not make Broker Company Inactive as Active Agents are present.");
                }
            }

            $sql = "UPDATE company set type='{$type}', status='{$status}', name='{$name}', description='{$des}', primary_email='{$email}', pan='{$pan}', website='{$web}', company_info_type='{$broker_info_type}', updated_by='{$_SESSION['adminId']}', updated_at=NOW() where id='{$id}'";
            
            $res_sql = mysql_query($sql) or die(mysql_error());

            $query_check = mysql_query("select * from addresses where (table_name='company' and table_id='{$id}' and type='HQ')") or die (mysql_error());
            
            if(mysql_num_rows($query_check)>0){
                $query1 = "UPDATE addresses SET address_line_1='{$address}', city_id='{$city}', pincode='{$pin}', updated_by={$_SESSION['adminId']}, updated_at=NOW()  WHERE (table_name='company' and table_id='{$id}' and type='HQ')";
                //echo $query1; die();
                $res1 = mysql_query($query1) or die(mysql_error());                
            }
            else{
                $query1 = "INSERT INTO addresses (table_name, table_id, address_line_1, city_id, pincode, type, updated_by, created_at) values ('company', '{$id}', '{$address}', '{$city}', '{$pin}', 'HQ', {$_SESSION['adminId']}, NOW())";
                //echo $query1; die();
                $res1 = mysql_query($query1) or die(mysql_error());
            }

            

            $query_find_ips = "delete from company_ips where company_id={$id}";
            $res = mysql_query($query_find_ips) or die(mysql_error()); 
            
            if($ipArr){
                foreach ($ipArr as $k => $v) {
                    $query = "INSERT INTO company_ips (company_id, ip, created_by, created_at) values ('{$id}', '{$v}', {$_SESSION['adminId']}, NOW())";
                    $res = mysql_query($query) or die(mysql_error());
                }
            }

/***************************** address update **********************************************/
            $query = "delete from addresses WHERE (table_name='company' and table_id='{$id}' and type='Other')";
           
            $res = mysql_query($query) or die(mysql_error());
            if($off_loc_data){
                $offLocStr = '';
                foreach ($off_loc_data as $k => $v) {
                    $offLocStr .= " ('company', '{$id}', '{$v['address']}', '{$v['c_id']}', '{$v['loc_id']}', 'Other', {$_SESSION['adminId']}, NOW()), ";
                }
                $offLocStr = rtrim($offLocStr,', ');
                $query1 = "INSERT INTO addresses (table_name, table_id, address_line_1, city_id, locality_id, type, updated_by, created_at) values".$offLocStr;
                
                $res1 = mysql_query($query1) or die(mysql_error());
            }
/******************************* coverage update *************************************/            

            $query = "delete from company_coverage WHERE company_id='{$id}'";
            $res = mysql_query($query) or die(mysql_error());
            if($coverage_data){
                $coverageStr = '';
                foreach ($coverage_data as $k => $v) {
                    
                    if($v['type']=='all'){
                        $query2 = "SELECT  c.city_id from city c
                        inner join suburb s on s.city_id=c.city_id
                        inner join locality l on l.suburb_id=s.suburb_id WHERE l.locality_id='{$v['loc_id']}' ";
                    //echo $query2;
                        $res2 = mysql_query($query2) or die(mysql_error());
                        $dataArr = mysql_fetch_assoc($res2);
                        $c_id = $dataArr['city_id'];
                        $coverageStr .= " ('{$id}',  '{$c_id}', '{$v['loc_id']}', '0', '0', {$_SESSION['adminId']}, NOW()), ";
                    }
                    else if($v['type']=='project')
                        $coverageStr .= " ('{$id}',  '{$v['c_id']}', '{$v['loc_id']}', '0', '{$v['p_id']}', {$_SESSION['adminId']}, NOW()), ";
                    else if($v['type']=='builder')
                        $coverageStr .= " ('{$id}',  '{$v['c_id']}', '{$v['loc_id']}', '{$v['p_id']}', '0', {$_SESSION['adminId']}, NOW()), ";
                    else 
                        $coverageStr .= ''; 
                
                }
                $coverageStr = rtrim($coverageStr,', ');
                $query1 = "INSERT INTO company_coverage (company_id, city_id, locality_id, builder_id, project_id, updated_by, created_at) values".$coverageStr;
                //echo $query1;
                $res1 = mysql_query($query1) or die(mysql_error());
            }

/****************** save company contact details ************************************************/
            if($compphone){
                $query = "select id from contact_numbers where (table_name='company' and table_id={$id} and type='phone1')";
                $res = mysql_query($query);
                $data = mysql_fetch_assoc($res);
                if($data['id']){
                    $query3 = "UPDATE contact_numbers SET contact_no='{$compphone}', updated_by={$_SESSION['adminId']}, updated_at=NOW() WHERE (table_name='company' and table_id='{$id}' and type='phone1')";
            
                    $res3 = mysql_query($query3) or die(mysql_error());
                }
                else{
                     $query3 = "INSERT INTO contact_numbers (table_name, table_id, contry_code, contact_no, type, updated_by, created_at) values ('company', '{$id}', '+91', '{$compphone}', 'phone1', {$_SESSION['adminId']}, NOW())";
                      
                     $res3 = mysql_query($query3) or die(mysql_error());
                }
            }

            if($compfax){
                 $query = "select id from contact_numbers where (table_name='company' and table_id={$id} and type='fax')";
                $res = mysql_query($query);
                $data = mysql_fetch_assoc($res);
                if($data['id']){
                    $query3 = "UPDATE contact_numbers SET contact_no='{$compfax}', updated_by={$_SESSION['adminId']}, updated_at=NOW() WHERE (table_name='company' and table_id='{$id}' and type='fax')";
            
                    $res3 = mysql_query($query3) or die(mysql_error());
                }
                else{
                     $query3 = "INSERT INTO contact_numbers (table_name, table_id, contry_code, contact_no, type, updated_by, created_at) values ('company', '{$id}', '+91', '{$compfax}', 'fax', {$_SESSION['adminId']}, NOW())";
                      
                     $res3 = mysql_query($query3) or die(mysql_error());
                }
            }

/****************** save company customer care details*******************************************/        
            if($cust_care_data){  
                $query = "delete from contact_numbers  where (table_name='company' and table_id={$id} and (type='cc_fax' or type='cc_phone' or type='cc_mobile'))";
                $res = mysql_query($query) or die(mysql_error());

                $query3 = "INSERT INTO contact_numbers (table_name, table_id, contry_code, contact_no, type, updated_by, created_at) values ('company', '{$id}', '+91', '{$cust_care_data['phone']}', 'cc_phone', {$_SESSION['adminId']}, NOW()), ('company', '{$id}', '+91', '{$cust_care_data['mobile']}', 'cc_mobile', {$_SESSION['adminId']}, NOW()), ('company', '{$id}', '+91', '{$cust_care_data['fax']}', 'cc_fax', {$_SESSION['adminId']}, NOW())";
                  //echo $query3; die;
                 $res3 = mysql_query($query3) or die(mysql_error());
                 $cc_num_id = mysql_insert_id();
                 //var_dump($cc_num_id);
                 $query = "update company set cc_contact_id='{$cc_num_id}' where id='{$id}'";
                 //echo $query;
                 $res3 = mysql_query($query) or die(mysql_error());
            }
/****************** save contact persons details ************************************************/

            $query = "select id from broker_contacts  where broker_id={$id}";
            $res = mysql_query($query) or die(mysql_error());

            $querydel = "delete from broker_contacts where broker_id={$id}";  
            $res = mysql_query($querydel) or die(mysql_error());

            while($data = mysql_fetch_assoc($res)){
                $query = "delete from contact_numbers where (table_name='broker_contacts' and table_id={$data['id']} )"; //echo $query; die();
                $res = mysql_query($query) or die(mysql_error());
            }
            foreach ($contact_person_data as $k => $v) {
                $query2 = "INSERT INTO broker_contacts (broker_id, name, type, contact_email, updated_by, created_at, updated_at) values ('{$id}', '{$v['person']}', 'NAgent', '{$v['email']}', {$_SESSION['adminId']}, NOW(), NOW())";
                $res2 = mysql_query($query2) or die(mysql_error());
                if(mysql_affected_rows()>0){
                   
                    $broker_contacts_id = mysql_insert_id();
                   

                    $query4 = "INSERT INTO contact_numbers (table_name, table_id, contry_code, contact_no, type, updated_by, created_at) values ('broker_contacts', '{$broker_contacts_id}', '+91', '{$v['phone1']}', 'phone1', {$_SESSION['adminId']}, NOW()), ('broker_contacts', '{$broker_contacts_id}', '+91', '{$v['phone2']}', 'phone2', {$_SESSION['adminId']}, NOW()), ('broker_contacts', '{$broker_contacts_id}', '+91', '{$v['fax']}', 'fax', {$_SESSION['adminId']}, NOW()), ('broker_contacts', '{$broker_contacts_id}', '+91', '{$v['mobile']}', 'mobile', {$_SESSION['adminId']}, NOW())";
                   
                    $res4 = mysql_query($query4) or die(mysql_error());
                    $contact_no_id = mysql_insert_id();

                    $query = "update broker_contacts set contact_number_id='{$contact_no_id}' where id='{$broker_contacts_id}'";
                 
                     $res3 = mysql_query($query) or die(mysql_error());

                }
            }
/****************** save broker extra details ************************************************/
            if($type=="Broker" && isset($bef)){
                
                $query = "select id, broker_id from broker_details where broker_id={$id}";
                $res = mysql_query($query) or die(mysql_error());
                $data=mysql_fetch_assoc($res);
                if($data){
                    if ($bef['formSignUpDate']=='') 
                        $signup = "form_signup_date=null";
                    else 
                        $signup = "form_signup_date='{$bef['formSignUpDate']}'";
                    $query = "Update broker_details set legal_type='{$bef['legalType']}', rating= '{$bef['frating']}', service_tax_no='{$bef['stn']}', office_size= '{$bef['officeSize']}', employee_no='{$bef['employeeNo']}', pt_manager_id= '{$bef['ptManager']}', pt_relative_id= ".($bef['ptRelative'] == '' ? 'NULL' : $bef['ptRelative']).", ".$signup.",form_signup_branch=".($bef['ptRelative'] == '' ? 'NULL' : $bef['signUpBranch']).", updated_by= {$_SESSION['adminId']} where broker_id={$id}";
                    //die($query);
                    $res = mysql_query($query) or die(mysql_error());
                }
                else{
                    if ($bef['formSignUpDate']=='') 
                        $signup = "null";
                    else 
                        $signup = "'{$bef['formSignUpDate']}'";
                    $query = "INSERT INTO broker_details (broker_id, legal_type, rating, service_tax_no, office_size, employee_no, pt_manager_id, pt_relative_id, form_signup_date, form_signup_branch, updated_by, created_at) values('{$id}', '{$bef['legalType']}', '{$bef['frating']}', '{$bef['stn']}', '{$bef['officeSize']}', '{$bef['employeeNo']}', '{$bef['ptManager']}', ".($bef['ptRelative'] == '' ? 'NULL' : $bef['ptRelative']).", ".$signup.", ".($bef['signUpBranch'] == '' ? 'NULL' : $bef['signUpBranch']).",  {$_SESSION['adminId']}, NOW())";
                    //die($query);
                    $res = mysql_query($query) or die(mysql_error()); //die("hello");
                }

                $query = "update company set active_since='{$bef['since_op']}' where id='{$id}'";
                $res = mysql_query($query) or die(mysql_error());

                $query = "delete from broker_property_type_mappings where broker_id={$id}";
                $res = mysql_query($query) or die(mysql_error());
                if($bef['projectType']){
                    $projTypeStr = '';
                    foreach ($bef['projectType'] as $k => $v) {
                        $projTypeStr .= " ('{$v}',  '{$id}', {$_SESSION['adminId']}, NOW()), ";
                    }
                    $projTypeStr = rtrim($projTypeStr,', ');
                    $query = "insert into broker_property_type_mappings(broker_property_type_id, broker_id, updated_by, created_at) value".$projTypeStr;
                    $res = mysql_query($query) or die(mysql_error());
                }

                $query = "delete from transaction_type_mappings where (table_name='company' and  table_id={$id})"; //echo $query; die();
                $res = mysql_query($query) or die(mysql_error());
                if($bef['transactionType']){
                    $transacStr = '';
                    foreach ($bef['transactionType'] as $k => $v) {
                        $transacStr .= " ('company',  '{$id}', '{$v}', '{$_SESSION['adminId']}', NOW()), ";
                    }
                    $transacStr = rtrim($transacStr,', ');
                    $query = "insert into transaction_type_mappings(table_name, table_id, transaction_type_id, updated_by, created_at) value". $transacStr;
                    $res = mysql_query($query) or die(mysql_error());
                }

                $query = "delete from device_mappings where (table_name='company' and  table_id={$id})"; //echo $query; die();
                $res = mysql_query($query) or die(mysql_error());
                if($bef['device']){
                    $transacStr = '';
                    foreach ($bef['device'] as $k => $v) {
                        $transacStr .= " ('company',  '{$id}', '{$v}', '{$_SESSION['adminId']}', NOW()), ";
                    }
                    $transacStr = rtrim($transacStr,', ');
                    $query = "insert into device_mappings(table_name, table_id, device_id, updated_by, created_at) value". $transacStr;
                    $res = mysql_query($query) or die(mysql_error());
                }

                //update bank details
                if($bank_details!=''){
                    //$bankStr = '';
                    $query_to_check = "select * from bank_details where table_name='company' and table_id={$id}";
                    $res = mysql_query($query_to_check) or die(mysql_error());
                    $data=mysql_fetch_assoc($res);
                    if($data){
                        $query = "update bank_details set bank_id='{$bank_details['bankId']}' , account_no='{$bank_details['accountNo']}', account_type='{$bank_details['accountType']}', ifsc_code='{$bank_details['ifscCode']}' where table_name='company' and table_id={$id}";
                        $res = mysql_query($query) or die(mysql_error());
                    }
                    else{
                        $bankStr = " ('company',  '{$comp_id}', '{$bank_details['bankId']}', '{$bank_details['accountNo']}', '{$bank_details['accountType']}', '{$bank_details['ifscCode']}', {$_SESSION['adminId']}, NOW()) ";
                    
                        //$bankStr = rtrim($bankStr,', ');
                        $query = "insert into bank_details(table_name, table_id, bank_id, account_no, account_type, ifsc_code, updated_by, created_at) value". $bankStr;
                        $res = mysql_query($query) or die(mysql_error());

                    }

                    
                }
                
            }

/****************** save images to Image Service ************************************************/

               
                                  
                    $unitImageArr['objectId'] = $id;
                    $unitImageArr['params']['service_image_id'] = $imageId;
                    $unitImageArr['params']['update'] = "update";
                     
                if(isset($_POST['image']) && $image!=""){ 
                    array_push($postArr, $unitImageArr);
                }
                    $signUpArr['objectId'] = $id;
                    $signUpArr['params']['service_image_id'] = $signupformId;
                    $signUpArr['params']['update'] = "update";
                if(isset($_POST['signUpForm']) && $signUpForm!=""){ 
                    array_push($postArr, $signUpArr);
                }
                
                if(!empty($postArr)){         
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
        
        $query = "INSERT INTO company(type, status, name, description, primary_email, pan, website, company_info_type, created_at, updated_by) values ('{$type}', '{$status}','{$name}','{$des}', '{$email}', '{$pan}', '{$web}', '{$broker_info_type}', NOW(), {$_SESSION['adminId']})";
        
        $res = mysql_query($query) or mysql_error();
        if(mysql_affected_rows()>0){
            $comp_id = mysql_insert_id();

/****************** save address details ************************************************/
            $query1 = "INSERT INTO addresses (table_name, table_id, address_line_1, city_id, pincode, type, updated_by, created_at) values ('company', '{$comp_id}', '{$address}', '{$city}', '{$pin}', 'HQ', {$_SESSION['adminId']}, NOW())";
            $res1 = mysql_query($query1) or die(mysql_error());

            if($off_loc_data){
                $offLocStr = '';
                foreach ($off_loc_data as $k => $v) {
                    $offLocStr .= " ('company', '{$comp_id}', '{$v['address']}', '{$v['c_id']}', '{$v['loc_id']}', 'Other', {$_SESSION['adminId']}, NOW()), ";
                }
                $offLocStr = rtrim($offLocStr,', ');
                $query1 = "INSERT INTO addresses (table_name, table_id, address_line_1, city_id, locality_id, type, updated_by, created_at) values".$offLocStr;
                //echo $query1;
                $res1 = mysql_query($query1) or die(mysql_error());
            }

            if($coverage_data){
                $coverageStr = '';
                foreach ($coverage_data as $k => $v) {
                    if($v['type']=='all'){
                        $query2 = "SELECT  c.city_id from city c
                        inner join suburb s on s.city_id=c.city_id
                        inner join locality l on l.suburb_id=s.suburb_id WHERE l.locality_id='{$v['loc_id']}' ";
                    //echo $query2;
                        $res2 = mysql_query($query2) or die(mysql_error());
                        $dataArr = mysql_fetch_assoc($res2);
                        $c_id = $dataArr['city_id'];
                        $coverageStr .= " ('{$comp_id}',  '{$c_id}', '{$v['loc_id']}', '0', '0', {$_SESSION['adminId']}, NOW()), ";
                    }
                    else if($v['type']=='project')
                        $coverageStr .= " ('{$comp_id}',  '{$v['c_id']}', '{$v['loc_id']}', '0', '{$v['p_id']}', {$_SESSION['adminId']}, NOW()), ";
                    else if($v['type']=='builder')
                        $coverageStr .= " ('{$comp_id}',  '{$v['c_id']}', '{$v['loc_id']}', '{$v['p_id']}', '0', {$_SESSION['adminId']}, NOW()), ";
                    else 
                        $coverageStr .= ''; 
                }
                $coverageStr = rtrim($coverageStr,', ');
                $query1 = "INSERT INTO company_coverage (company_id, city_id, locality_id, builder_id, project_id, updated_by, created_at) values".$coverageStr;
                //echo $query1;
                $res1 = mysql_query($query1) or die(mysql_error());
            }

/****************** save company ip details ************************************************/
            if($ipArr){
                foreach ($ipArr as $k => $v) {
                    if($v != ''){
                    $query = "INSERT INTO company_ips (company_id, ip, created_by, created_at) values ('{$comp_id}', '{$v}', {$_SESSION['adminId']}, NOW())";
                    $res = mysql_query($query) or die(mysql_error());
                    }
                }
            }


/****************** save company contact details ************************************************/
            if($compphone){
                 $query3 = "INSERT INTO contact_numbers (table_name, table_id, contry_code, contact_no, type, updated_by, created_at) values ('company', '{$comp_id}', '+91', '{$compphone}', 'phone1', {$_SESSION['adminId']}, NOW())";
                  
                 $res3 = mysql_query($query3) or die(mysql_error());
            }

            if($compfax){
                 $query3 = "INSERT INTO contact_numbers (table_name, table_id, contry_code, contact_no, type, updated_by, created_at) values ('company', '{$comp_id}', '+91', '{$compfax}', 'fax', {$_SESSION['adminId']}, NOW())";
                  
                 $res3 = mysql_query($query3) or die(mysql_error());
            }

/****************** save company customer care details*******************************************/        if($cust_care_data){   
                 $query3 = "INSERT INTO contact_numbers (table_name, table_id, contry_code, contact_no, type, updated_by, created_at) values ('company', '{$comp_id}', '+91', '{$cust_care_data['phone']}', 'cc_phone', {$_SESSION['adminId']}, NOW()), ('company', '{$comp_id}', '+91', '{$cust_care_data['mobile']}', 'cc_mobile', {$_SESSION['adminId']}, NOW()), ('company', '{$comp_id}', '+91', '{$cust_care_data['fax']}', 'cc_fax', {$_SESSION['adminId']}, NOW())";
                  
                 $res3 = mysql_query($query3) or die(mysql_error());
                 $cc_num_id = mysql_insert_id();
                 //var_dump($cc_num_id);
                 $query = "update company set cc_contact_id='{$cc_num_id}' where id='{$comp_id}'";
                 //echo $query;
                 $res3 = mysql_query($query) or die(mysql_error());
            }
/****************** save contact persons details ************************************************/
             foreach ($contact_person_data as $k => $v) {
                
             
                $query2 = "INSERT INTO broker_contacts (broker_id, name, type, contact_email, updated_by, created_at, updated_at) values ('{$comp_id}', '{$v['person']}', 'NAgent', '{$v['email']}', {$_SESSION['adminId']}, NOW(), NOW())";
                $res2 = mysql_query($query2) or die(mysql_error());
                if(mysql_affected_rows()>0){
                   
                    $broker_contacts_id = mysql_insert_id();
                   

                    $query4 = "INSERT INTO contact_numbers (table_name, table_id, contry_code, contact_no, type, updated_by, created_at) values ('broker_contacts', '{$broker_contacts_id}', '+91', '{$v['phone1']}', 'phone1', {$_SESSION['adminId']}, NOW()), ('broker_contacts', '{$broker_contacts_id}', '+91', '{$v['phone2']}', 'phone2', {$_SESSION['adminId']}, NOW()), ('broker_contacts', '{$broker_contacts_id}', '+91', '{$v['fax']}', 'fax', {$_SESSION['adminId']}, NOW()), ('broker_contacts', '{$broker_contacts_id}', '+91', '{$v['mobile']}', 'mobile', {$_SESSION['adminId']}, NOW())";
                   
                    $res4 = mysql_query($query4) or die(mysql_error());
                    $contact_no_id = mysql_insert_id();

                    $query = "update broker_contacts set contact_number_id='{$contact_no_id}' where id='{$broker_contacts_id}'";
                 
                     $res3 = mysql_query($query) or die(mysql_error());

                }
            }
/****************** save broker extra details ************************************************/
            if($type=="Broker" && isset($bef)){
                if($bef['device']=='') 
                    $bef['device']=='NULL';
                if($bef['ptRelative']=='') 
                    $bef['ptRelative']=='NULL';
                if ($bef['formSignUpDate']=='') 
                    $signup = "null";
                else 
                    $signup = "'{$bef['formSignUpDate']}'";

                $query = "INSERT INTO broker_details (broker_id, legal_type, rating, service_tax_no, office_size, employee_no, pt_manager_id, pt_relative_id, form_signup_date, form_signup_branch, updated_by, created_at) values('{$comp_id}', '{$bef['legalType']}', '{$bef['frating']}', '{$bef['stn']}', '{$bef['officeSize']}', '{$bef['employeeNo']}', '{$bef['ptManager']}', ".($bef['ptRelative'] == '' ? 'NULL' : $bef['ptRelative']).", ".$signup.", ".($bef['signUpBranch'] == '' ? 'NULL' : $bef['signUpBranch']).", {$_SESSION['adminId']}, NOW())";
                $res = mysql_query($query) or die(mysql_error());
                
                $sqlCompCode = "SELECT id FROM company_code ORDER BY id DESC LIMIT 1";
                $resCompCode = mysql_query($sqlCompCode) or die(mysql_error());
                $dataCompCode= mysql_fetch_assoc($resCompCode);
                $compCodeId = $dataCompCode['id']+1;
                
                $insertCompCodeSqlStr = "INSERT INTO company_code(ID,COMPANY_ID) VALUES({$compCodeId},{$comp_id})";
                $resInsert = mysql_query($insertCompCodeSqlStr) or die(mysql_error());
                $channel_partner_id = mysql_insert_id();
                $channel_partner_id = str_pad($channel_partner_id, 4, '0', STR_PAD_LEFT);
                
                $sqlCity = "SELECT ABBREVIATION FROM city WHERE CITY_ID={$bef['signUpBranch']}";
                $resCity = mysql_query($sqlCity) or die(mysql_error());
                $dataCity= mysql_fetch_assoc($resCity);
                $channel_partner_code = $dataCity["ABBREVIATION"]."CP".$channel_partner_id."".date("my");

                $query = "update company set active_since='{$bef['since_op']}', unique_code='{$channel_partner_code}' where id='{$comp_id}'";
                $res = mysql_query($query) or die(mysql_error());

                if($bef['projectType']){
                    $projTypeStr = '';
                    foreach ($bef['projectType'] as $k => $v) {
                        $projTypeStr .= " ('{$v}',  '{$comp_id}', {$_SESSION['adminId']}, NOW()), ";
                    }
                    $projTypeStr = rtrim($projTypeStr,', ');
                    $query = "insert into broker_property_type_mappings(broker_property_type_id, broker_id, updated_by, created_at) value".$projTypeStr;
                    $res = mysql_query($query) or die(mysql_error());
                }

                if($bef['transactionType']){
                    $transacStr = '';
                    foreach ($bef['transactionType'] as $k => $v) {
                        $transacStr .= " ('company',  '{$comp_id}', '{$v}', {$_SESSION['adminId']}, NOW()), ";
                    }
                    $transacStr = rtrim($transacStr,', ');
                    $query = "insert into transaction_type_mappings(table_name, table_id, transaction_type_id, updated_by, created_at) value". $transacStr;
                    $res = mysql_query($query) or die(mysql_error());
                }


                if($bef['device']){
                    $transacStr = '';
                    foreach ($bef['device'] as $k => $v) {
                        $transacStr .= " ('company',  '{$comp_id}', '{$v}', '{$_SESSION['adminId']}', NOW()), ";
                    }
                    $transacStr = rtrim($transacStr,', ');
                    $query = "insert into device_mappings(table_name, table_id, device_id, updated_by, created_at) value". $transacStr;
                    $res = mysql_query($query) or die(mysql_error());
                }
                //save bank details

                if($bank_details!=''){
                    //$bankStr = '';
                    
                    $bankStr = " ('company',  '{$comp_id}', '{$bank_details['bankId']}', '{$bank_details['accountNo']}', '{$bank_details['accountType']}', '{$bank_details['ifscCode']}', {$_SESSION['adminId']}, NOW()) ";
                    
                    //$bankStr = rtrim($bankStr,', ');
                    $query = "insert into bank_details(table_name, table_id, bank_id, account_no, account_type, ifsc_code, updated_by, created_at) value". $bankStr;
                    $res = mysql_query($query) or die(mysql_error());
                }
                
                $sqlPtManager = "SELECT pa.ADMINEMAIL as pt_manager_email FROM proptiger.PROPTIGER_ADMIN pa WHERE pa.ADMINID={$bef['ptManager']}";
                $resPtManager = mysql_query($sqlPtManager) or die(mysql_error());
                $dataPtManager=mysql_fetch_assoc($resPtManager);
                $options = array('to'=>$email,'agent_id'=>$channel_partner_code, 'agent_name'=>$name, 'cc'=>$dataPtManager['pt_manager_email'], 'bcc'=>'channel-registration@proptiger.com');
                send_mail($options);
                
            }

/****************** save images to Image Service ************************************************/
            /*if(isset($_POST['image']) && $image!=""){
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
            }*/

        $unitImageArr['objectId'] = $comp_id;
        //$unitImageArr['params']['service_image_id'] = $imageId;
        //$unitImageArr['params']['update'] = "update";
             
        if(isset($_POST['image']) && $image!=""){ 
            array_push($postArr, $unitImageArr);
        }
            $signUpArr['objectId'] = $comp_id;
            //$signUpArr['params']['service_image_id'] = $signupformId;
            //$signUpArr['params']['update'] = "update";
        if(isset($_POST['signUpForm']) && $signUpForm!=""){ 
            array_push($postArr, $signUpArr);
        }
        
        if(!empty($postArr)){         
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





            echo "1";
        }
        else
            echo "3";
    }
       
}

function send_mail($options){
    $agent_name = ucwords($options['agent_name']);
    $options['subject'] = 'Welcome to PropTiger.com! Your Channel Partner Code Confirmation';
    $options['sender'] = 'no-reply@proptiger.com';
    $options['message'] = "<table width='938' border='1' style='width:562.5pt;border:solid #333333 1.0pt'>"
            . "<tr><td style='border:none;background:whitesmoke;padding:0in 0in 0in 0in'> "
            . "<table width='938' style='width:562.5pt'>"
            . "<tr><td width='656' style='width:393.75pt;padding:7.5pt 7.5pt 7.5pt 7.5pt'>"
            . "<p style='line-height:105%'><img width='190' height='65' src='".FORUM_INTERNET_IMAGE_PATH."agent_email_logo.jpg'></p></td>"            
            . "<td width='281' style='width:168.75pt;padding:0in 0in 0in 0in'><p style='line-height:105%'>"
            . "<img width='218' height='35' src='".FORUM_INTERNET_IMAGE_PATH."agent_email_url.jpg'></p></td></tr>"
            . "</table></td></tr><tr><td style='border:none;padding:7.5pt 7.5pt 7.5pt 7.5pt'>"
            . "Dear {$agent_name}, <br>"
            . "<p>We would like to thank you for choosing PropTiger.com. "
            . "We have received your signed channel partner signup form and "
            . "we are delighted to inform that you have been successfully empanelled with us. </p>"
            . "<p><b>Your Unique Channel Partner Code is “{$options['agent_id']}” </b> <br><br>"
            . "Please use this Unique Code for all future communications with PropTiger.com <br><br>"
            . "Our customer service team is there to assist you for any further query / support you need in this regard. "
            . "Feel free to contact us at +91 92788 92788 or email to customer.service@proptiger.com for any query / support.<br> </p>"
            . "Thanking you,<br>"
            . "Customer Service Team <br>"
            . "PropTiger.com<br>"
            . "<small>Note : This is system generated email, therefore; please do not reply <small>"
            . "</td></tr></table>";
    
    
    sendMailFromAmazon($options['to'], $options['subject'], $options['message'], $options['sender'], $options['cc'], $options['bcc'], false);
}

    

?>
