<?php

error_reporting(1);
ini_set('display_errors','1');
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
include("function/functions_priority.php");
AdminAuthentication();


if($_POST['task']=='createComp'){
    $id = $_POST['id'];
    $type = $_POST['type'];
   $des   = $_POST['des'];

    $name   = $_POST['name'];
    $address   = $_POST['address'];
    $address = preg_replace('!\s+!', ' ', $address);
    $city   = $_POST['city'];
    $pin   = $_POST['pincode'];
    $person   = $_POST['person'];
    $phone   = $_POST['phone'];
    $web   = $_POST['web'];
    $fax   = $_POST['fax'];
    $email   = $_POST['email'];
    $pan   = $_POST['pan'];
    $status   = $_POST['status'];
    $mode =  $_POST['mode'];

           
    
    if($mode=='update' && $id!==null){
        
        $sql = "UPDATE company set type='{$type}', status='{$status}', name='{$name}', description='{$des}', primary_email='{$email}', pan='{$pan}', updated_by='{$_SESSION['adminId']}', updated_at=NOW() where id='{$id}'";
        
        $res_sql = mysql_query($sql);
        if(mysql_affected_rows()>0){

            $query1 = "UPDATE addresses SET address_line_1='{$address}', city_id='{$city}', pincode='{$pin}', updated_by={$_SESSION['adminId']}, updated_at=NOW()  WHERE (table_name='company' and table_id='{$id}' )";
            $res1 = mysql_query($query1);

            $query2 = "UPDATE broker_contacts SET name='{$person}', contact_email='{$email}', updated_by={$_SESSION['adminId']}, updated_at=NOW()  WHERE (broker_id='{$id}' and type='NAgent' )";
            
            $res2 = mysql_query($query2);
            if(mysql_affected_rows()>0){

                $query2 = "SELECT id from broker_contacts WHERE (broker_id='{$id}' and type='NAgent' )";
                //echo $query2;
                $res2 = mysql_query($query2);
                $dataArr = mysql_fetch_assoc($res2);
                $broker_contacts_id = $dataArr['id'];
                //var_dump($dataArr);
                $query3 = "UPDATE contact_numbers SET contact_no='{$phone}', updated_by={$_SESSION['adminId']}, updated_at=NOW() WHERE (table_name='broker_contacts' and table_id='{$broker_contacts_id}' and type='cc_phone')";
            
                $res3 = mysql_query($query3);

                $query4 = "UPDATE contact_numbers SET contact_no='{$fax}', updated_by={$_SESSION['adminId']}, updated_at=NOW() WHERE (table_name='broker_contacts' and table_id='{$broker_contacts_id}' and type='fax')";
                 
                $res4 = mysql_query($query4);
            }

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
            $res1 = mysql_query($query1);

            $query2 = "INSERT INTO broker_contacts (broker_id, name, type, contact_email, updated_by, created_at, updated_at) values ('{$comp_id}', '{$person}', 'NAgent', '{$email}', {$_SESSION['adminId']}, NOW(), NOW())";
            $res2 = mysql_query($query2);
            if(mysql_affected_rows()>0){
               
                $broker_contacts_id = mysql_insert_id();
                $query3 = "INSERT INTO contact_numbers (table_name, table_id, contry_code, contact_no, type, updated_by, created_at) values ('broker_contacts', '{$broker_contacts_id}', '+91', '{$phone}', 'cc_phone', {$_SESSION['adminId']}, NOW())";
              
                $res3 = mysql_query($query3);

                $query4 = "INSERT INTO contact_numbers (table_name, table_id, contry_code, contact_no, type, updated_by, created_at) values ('broker_contacts', '{$broker_contacts_id}', '+91', '{$fax}', 'fax', {$_SESSION['adminId']}, NOW())";
               
                $res4 = mysql_query($query4);
            }

            echo "1";
        }
            
        else
            echo "3";
    }
        
}

    

?>