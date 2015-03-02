<?php

error_reporting(1);
ini_set('display_errors','1');
include('httpful.phar');
require_once("appWideConfig.php");
include("dbConfig.php");

if($_POST['task'] === 'get_tower')  {
   
    $Sql = "SELECT TOWER_ID,PROJECT_ID,TOWER_NAME FROM resi_project_tower_details WHERE PROJECT_ID =".$_POST['project_id']." ";
    $Tower = array();
    $ExecSql = mysql_query($Sql) or die();
    $cnt = 0;
    if (mysql_num_rows($ExecSql) > 0) {
        while($Res = mysql_fetch_assoc($ExecSql)) {
            $tmp = array();
            $tmp['tower_id'] = $Res['TOWER_ID'];
            $tmp['tower_name'] = $Res['TOWER_NAME'];
            if($Res['TOWER_ID']!='')
                array_push($Tower, $tmp);
            $cnt++;
            //$tower = $Res['TOWER_NAME'];
        }    
    }
    //echo $cnt;
   

    echo json_encode($Tower);
    //echo $tower;
    //echo "Finish";
    //$smarty->assign("sel",$Sel);

}

else if($_POST['task'] === 'get_seller')  {
    
    $Sql = "SELECT user_id, name FROM company_users  WHERE company_id=".$_POST['broker_id']." and status = 'Active' and user_id is not null";
    //$Sql = "SELECT user_id, name FROM company_users  WHERE company_id=".$_POST['broker_id']."";
    $Sel = array();
    $ExecSql = mysql_query($Sql) or die(mysql_error() . ' Error in fetching data from company_users');
    $cnt = 0;
    if (mysql_num_rows($ExecSql) > 0) {
        while($Res = mysql_fetch_assoc($ExecSql)) {
            
            $tmp = array();
            $tmp['user_id'] = $Res['user_id'];
            $tmp['name'] = $Res['name'];
            if($Res['user_id']!='')
                array_push($Sel, $tmp);
            $cnt++;
        }    
    }
    //echo $cnt;
   

    echo json_encode($Sel);
    //$smarty->assign("sel",$Sel);

}
else if($_POST['task'] === 'get_broker')  {
    $company_id='';
    $Sql = "SELECT c.id FROM company c inner join company_users cu on c.id=cu.company_id WHERE cu.user_id=".$_POST['seller_id']." and c.status = 'Active' and cu.status='Active' ";
    //echo $Sql;
    $Sel = array();
    $ExecSql = mysql_query($Sql) or die(mysql_error() . ' Error in fetching data from company_users');
    $cnt = 0;
    if (mysql_num_rows($ExecSql) > 0) {
        $Res = mysql_fetch_assoc($ExecSql);
        $broker_id = $Res['id'];
           
    }
    //echo $cnt;
   

    echo $broker_id;
    //$smarty->assign("sel",$Sel);

} 
else {
    //$listing_id = $_POST['listing_id'];
    $listing_id='';
    

    $dataArr = array();
    if($_POST['task']=='update' && $_POST['listing_id']!=''){
        $listing_id=$_POST['listing_id'];
        //$dataArr['listingId'] = $_POST['listing_id'];
    }
    $dataArr['sellerId'] = $_POST['seller_id'];//"1216008";//
    if(!empty($_POST['facing']))
        $dataArr['facing'] = $_POST['facing'];
    $dataArr['propertyId'] = $_POST['property_id'];
    $otherInfo = array(
        'size'=> $_POST['size'],
        'projectId'=> $_POST['project_id'],
        'bedrooms'=> $_POST['bedrooms'],
        'bathrooms'=> $_POST['bathrooms'], 

        'unitType'=>  $_POST['unit_type'],
        ); 

    if($_POST['penthouse'] != null) {
        $otherInfo['penthouse'] = $_POST['penthouse'];
    }
    if($_POST['studio'] != null) {
        $otherInfo['studio'] = $_POST['studio'];
    }

        
    $dataArr['otherInfo'] = $otherInfo;

    $dataArr['floor'] = $_POST['floor'];
    $jsonDump = array();
    $tower = $_POST['tower'];

/*    $total_floor = $_POST['total_floor'];
    $phaseId = $_POST['phase_id'];
    $owner_name = $_POST['owner_name'];
    $powner_email = $_POST['owner_email'];
    $study_room = $_POST['study_room'];
    $servant_room = $_POST['servant_room'];

/***  listing v2 values  ****************************************************/   
   /* if(isset($tower) && !empty($tower))
        $dataArr['towerId'] =$tower;
    if(isset($phaseId) && !empty($phaseId))
        $dataArr['phaseId'] =$phaseId;*/
/*** json dump values  ****************************************************/    
    /*if(isset($total_floor) && !empty($total_floor))
        $jsonDump['total_floor'] =$total_floor;
    if(isset($owner_name) && !empty($owner_name))
        $jsonDump['owner_name'] =$owner_name;
    if(isset($owner_email) && !empty($owner_email))
        $jsonDump['owner_email'] =$owner_email;
    if(isset($owner_number) && !empty($owner_number))
        $jsonDump['owner_number'] =$owner_number;
    if(isset($study_room) && !empty($study_room))
        $jsonDump['study_room'] =$study_room;
    if(isset($servant_room) && !empty($servant_room))
        $jsonDump['servant_room'] = $servant_room;*/

    




   /* echo "Tower ",$tower;
    echo "Total_floor",$total_floor;

    $owner_name = $_POST['owner_name'];
    $owner_email = $_POST['owner_email'];
    $owner_number = $_POST['owner_number'];
    if(isset($owner_name) && !empty($owner_name))
        $jsonDump['owner_name'] = $owner_name;
    if(isset($tower) && !empty($tower))
        $jsonDump['tower'] = $tower;
    if(isset($owner_email) && !empty($owner_email))
        $jsonDump['owner_email'] = $owner_email;
    if(isset($owner_number) && !empty($owner_number))
        $jsonDump['owner_number'] = $owner_number;*/
        

    $dataArr['jsonDump'] = json_encode($jsonDump);
    $dataArr['description'] =$_POST['description'];
    $dataArr['remark'] =$_POST['review'];


    $dataArr['flatNumber'] = $_POST['flat_number'];
    $dataArr['homeLoanBankId'] = $_POST['loan_bank'];
    $dataArr['noOfCarParks'] = $_POST['parking'];
    if($_POST['negotiable'] != null)  {
        $dataArr['negotiable'] = $_POST['negotiable'];    
    }
    
    $dataArr['transferCharges'] = $_POST['trancefer_rate']; 
    $dataArr['plc'] = $_POST['plc_val'];

    $masterAmenityIds = array(
        1,2,3,4
        );
    //$dataArr['masterAmenityIds'] = $masterAmenityIds;
    if($_POST['price_per_unit_area'] != NaN)
        $pricePerUnitArea = $_POST['price_per_unit_area'];
    else
        $pricePerUnitArea =0;
    if($_POST['price'] !=NaN){
        $price = $_POST['price'];
        $price = round($price, -2);
    }
    else
        $price =0;
    if($_POST['other_charges'] !=NaN){
        $other_charges = $_POST['other_charges'];
    }
    else
        $other_charges =0;
    
    
    if($pricePerUnitArea == '' || $pricePerUnitArea == null) {
        $pricePerUnitArea = null;
    }
    if($$price == '' || $$price == null) {
        $$price = null;
    }
    if($$other_charges == '' || $$other_charges == null) {
        $$other_charges = null;
    }
    $currentListingPrice = array(
        'pricePerUnitArea'=> $pricePerUnitArea,
        'price'=> $price,
        'otherCharges'=> $other_charges,
        'comment'=>''
        );
    if((isset($pricePerUnitArea) && $pricePerUnitArea!='') || (isset($price) && $price!=''))
        $dataArr['currentListingPrice'] = $currentListingPrice;


    /*"{"floor":"2","jsonDump":{"comment":"QA Marketplace Test Company"},"sellerId":null,"flatNumber":"3","homeLoanBankId":"select bank","noOfCarParks":"4","negotiable":"true","transferCharges":"","plc":"","otherInfo":{"size":"","projectId":"503095","bedrooms":null,"unitType":"Sq. Ft.","facing":"East"},"currentListingPrice":{"pricePerUnitArea":"100"}}"*/

    //'{"floor":{$x},"jsonDump":"{\"comment\":\"anubhav\"}","sellerId":"1216008","flatNumber":"D-12","homeLoanBankId":"1","noOfCarParks":"3","negotiable":"true","transferCharges":1000,"plc":200,"otherInfo":{"size":"100","projectId":"656368","bedrooms":"3","unitType":"Plot","penthouse":"true","studio":"true","facing":"North"},"masterAmenityIds":[1,2,3,4],"currentListingPrice":{"pricePerUnitArea":2000,}}'


//print("<pre>");
//print_r($dataArr); 
    $dataJson = json_encode($dataArr);
    //print("<pre>");
    //print_r($dataArr); die;
     //var_dump($dataJson);   


        $uri = LISTING_API_URL;
        $uriLogin = ADMIN_USER_LOGIN_API_URL;
        //$urlNew: $url + "?page="+page+ "&size="size;
        /*try{ 
            $response_login = \Httpful\Request::post($uri1)->sendIt();
            


            $response = \Httpful\Request::put($uri)->authenticateWith('admin-22550@proptiger.com', '1234')->sendsJson()->body($dataJson)->sendIt();    
            echo "This response has " . count($response); 
            echo $response,'\n';
            //echo $response1;
            //admin-22550@proptiger.com 
            //1234 


        } catch(Exception $e)  {
            print_R($e);
        }*/
        //echo "dhsjadfhsjdkdf";    
        $response_login = \Httpful\Request::post($uriLogin)                  // Build a PUT request...
        ->sendsJson()                               // tell it we're sending (Content-Type) JSON...
        ->body('')             // attach a body/payload...
        ->send(); 
        //var_dump($response_login);die();
        $header = $response_login->headers;
        $header = $header->toArray();
        $ck = $header['set-cookie'];
        
        $ck_new = "";
        for($i = 0; $i < strlen($ck); $i++)  {
            if($ck[$i] == ';')  {
                break;
            }
            $ck_new = $ck_new.$ck[$i];
        }
        //echo $ck_new;
        if($ck_new!='')
        {    
            $returnArr = array();
            if($listing_id!=''){
                $uri = $uri."/".$listing_id;
                $response = \Httpful\Request::put($uri)           
                ->sendsJson()                               
                ->body($dataJson)
                ->addHeader("COOKIE", $ck_new) 
                ->send(); 
                //echo "update";
                //var_dump($response);

                if($response->body->statusCode=="2XX"){
                   // echo "2";
                    $returnArr['code'] = "2";
                    $returnArr['msg'] = "update";
                    echo json_encode($returnArr);
                }
                else{
                     //echo $response->body->error->msg;
                    $returnArr['code'] = "0";
                    $returnArr['msg'] = $response->body->error->msg;
                    echo json_encode($returnArr);
                }
            }
            else{
                $response = \Httpful\Request::post($uri)           
                ->sendsJson()                               
                ->body($dataJson)
                ->addHeader("COOKIE", $ck_new) 
                ->send(); 
                //echo "create";
                //var_dump($response);
                if($response->body->statusCode=="2XX"){
                    $id = $response->body->data->id;

                    $returnArr['code'] = "1";
                    $returnArr['msg'] = $id;
                    echo json_encode($returnArr);
                }
                else{
                     //echo $response->body->error->msg;
                    $returnArr['code'] = "0";
                    $returnArr['msg'] = $response->body->error->msg;
                    echo json_encode($returnArr);
                }
            }


            //var_dump($response);

            
        }
        else{
            echo "Authentication Error.";
        }



        /*$ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL,$uri);             
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-length: ".strlen($dataJson))); 
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    //curl_setopt($ch, CURLOPT_VERBOSE, 1);
                    //curl_setopt($ch, CURLOPT_HEADER, 1);
                    curl_setopt($ch, CURLOPT_COOKIE, "JSESSIONID=".$cookies['JSESSIONID']);
                    $server_output = curl_exec($ch);
                    curl_close ($ch);
                    $output_array = json_decode($server_output,true);
                    print_r($output_array);*/
    }

?>
