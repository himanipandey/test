<?php
 $accessUserManage = '';
 if( $userManagement == false )
   $accessUserManage = "No Access";
 $smarty->assign("accessUserManage",$accessUserManage);

$userid = $_REQUEST['userid'];
$smarty->assign("userid", $userid);
$smarty->assign("arrOtherCities", $arrOtherCities);

/****active city list********/
$qry = "select city_id,label from city where status = 'Active' order by label";
$res = mysql_query($qry) or die(mysql_error());
$arrCity = array();
while($data = mysql_fetch_assoc($res)) {
    $arrCity[$data['city_id']] = $data['label'];
}
$smarty->assign("arrCity", $arrCity);

if ($_POST['btnSave'] == "Save") { 
	$txt_empcode	=	trim($_POST['txt_empcode']);
        $txt_name	=	trim($_POST['txt_name']);
        $txt_email	=	trim($_POST['txt_email']);
        $txt_mobile	=	trim($_POST['txt_mobile']);
	$passwordFlag	=	trim($_POST['radiopass']);
	$txt_username	=	trim($_POST['txt_username']);
	$txt_password	=	trim($_POST['txt_password']);
	$region		=	trim($_POST['region']);
	$branch		=	trim($_POST['branch']);
	$department	=	trim($_POST['dept']);
	$designation	=	trim($_POST['department']); //designation
	$radioForStatus	=	$_POST['active'];
	$joiningdate	= $_POST['joiningdate'];
	$resignationdate = $_POST['resignationdate'];
        $cloudAgentId = $_POST['cloudAgentId'];
        $city = $_POST['city'];
	$smarty->assign("txtadminid", $userid);
	$smarty->assign("txtempcode", $txt_empcode);	
	$smarty->assign("txtfname", $txt_name);
	$smarty->assign("txtusername", $txt_username);	
	$smarty->assign("txtadminpassword", $txt_password);
	$smarty->assign("adminemail", $txt_email);
	$smarty->assign("mobile", $txt_mobile);	
	$smarty->assign("branchlocation",$branch);	
	$smarty->assign("txtregion",$region);
	$smarty->assign("txtdesignation", $designation);
	$smarty->assign("txtdepartment", $department);
	$smarty->assign("status",$radioForStatus);
	$smarty->assign("joiningdate",$joiningdate);
	$smarty->assign("resignationdate",$resignationdate);
        $smarty->assign("cloudAgentId",$cloudAgentId);
        $smarty->assign("arrExistingCity",$city);
	
	if($txt_empcode == '') 	{
		$ErrorMsg["EmpCodeErr"] = "Please enter employee code.";
	}

	if( $txt_name == '')   {
		$ErrorMsg["ContactNameErr"] = "Please enter user name.";
	}
	if( $txt_email == '')  {
		$ErrorMsg["EmailErr"] = "Please enter email address.";
	}
	if( $txt_mobile == '')   {
		$ErrorMsg["MobileNoErr"] = "Please enter mobile number.";
	}
	if( $txt_username == '')   {
		$ErrorMsg["UserNameErr"] = "Please enter user name.";
	}	   
	
	if( $txt_password == "" && $_GET['userid']=='')   {
		$ErrorMsg["UserPasswordErr"] = "Please enter password.";
	} 

	if( $txt_password == "" && $_GET['userid']!="" && $_POST['radiopass']=="1")   {
		$ErrorMsg["UserEditPasswordErr"] = "Please edit password.";
	} 

	if( $region == '')    {
		$ErrorMsg["SelectRegionErr"] = "Please select a region.";
	}
	
	if( $department == '')   {
		$ErrorMsg["selectDepartmentErr"] = "Please select a department.";
	}
	
	$smarty->assign("ErrorMsg", $ErrorMsg);
	if(is_array($ErrorMsg)) {
		// Do Nothing
	}else if ($userid == ''){
	      /*******code for duplicate check username email and emp code*****/
               $qryChk = "select * from ".ADMIN." where EMP_CODE = '".$txt_empcode."'
                            or USERNAME = '".$txt_username."'
                            or ADMINEMAIL = '".$txt_email."'";
               $resChk = mysql_query($qryChk) or die(mysql_error());
               if(mysql_num_rows($resChk)>0) {
                   $dataChk = mysql_fetch_assoc($resChk);
                   if($dataChk['EMP_CODE'] == $txt_empcode)
                        $ErrorMsg["EmpCodeErr"] = "Duplicate EmpCode.";
                   if($dataChk['USERNAME'] == $txt_username)
                        $ErrorMsg["UserNameErr"] = "Duplicate UserName.";
                   if($dataChk['ADMINEMAIL'] == $txt_email)
                        $ErrorMsg["EmailErr"] = "Duplicate EmailId.";
                   $smarty->assign("ErrorMsg", $ErrorMsg);
               }
               else {
		//insert user info 
		$sql = "INSERT INTO ".ADMIN." SET 
                        EMP_CODE = '".$txt_empcode."' ,
                        FNAME = '".$txt_name."',
                        USERNAME = '".$txt_username."', 
                        ADMINPASSWORD =	'".md5($txt_password)."',
                        ADMINEMAIL = '".$txt_email."',
                        MOBILE	= '".$txt_mobile	."',
                        ADMINADDDATE = '".date("Y-m-d")."',
                        REGION	= '".$region."',
                        STATUS	= 'Y',
                        DEPARTMENT = '".$department."',
                        ROLE = '".$designation."',
                        JOINING_DATE = '".$joiningdate."',
                        CLOUDAGENT_ID =  '".$cloudAgentId."',    
                        RESIGNATION_DATE = '".$resignationdate."'";
		$DataInsert = mysql_query($sql) or die(mysql_error());
                $lastId = mysql_insert_id();
                //code for insert data in proptiger admin city
                //echo "<pre>";
               // print_r($_REQUEST['city']);//die("here");
                if($_REQUEST['city'][0] == '')
                        unset($_REQUEST['city'][0]);
                if(count($_REQUEST['city'])>0) {
                    
                    $cityQry = "insert into proptiger_admin_city (admin_id,city_id) values ";
                    $comma = ',';
                    $cityData = '';
                    $flg = 0;
                    $arrCtList = array();
                    foreach($_REQUEST['city'] as $k=>$val){
                        if($val != 'other') {
                            $arrCtList[] = $val;
                            if($k == count($_REQUEST['city']))
                                $comma = ' ';
                            $cityData .=  "($lastId,$val)$comma";
                        }
                        else{
                            $flg = 1;
                        }
                    }
                    if($flg == 1) {
                        $cnt = 1;
                        foreach($arrOtherCities as $k=>$v){
                                if($cnt == count($arrOtherCities))
                                    $comma = ' ';
                               if(!in_array($k,$arrCtList))
                                $cityData .=  "($lastId,$k) $comma ";
                               $cnt++;
                           }
                    }
                    $finalStr = $cityQry.$cityData;
                    $resCity = mysql_query($finalStr) or die(mysql_error());
                }
                if($DataInsert)
                    header("Location:userList.php");
               }
	}
	else
	{
            if($_REQUEST['oldPass'] != $txt_password){
                $passUpdate = md5($txt_password);
            }
            else{
                $passUpdate = $txt_password;
            }
                 $sql = "UPDATE ".ADMIN." SET 
                    EMP_CODE = '".$txt_empcode."' ,
                    FNAME	= '".$txt_name."',
                    USERNAME = '".$txt_username."',
                    ADMINPASSWORD = '".$passUpdate."',    
                    ADMINEMAIL = '".$txt_email."',
                    MOBILE = '".$txt_mobile	."',
                    BRANCH_LOCATION	= '".$branch."',
                    REGION = '".$region."',
                    STATUS	= '".$radioForStatus."',
                    DEPARTMENT ='".$department."',
                    ROLE = '".$designation."',
                    JOINING_DATE = '".$joiningdate."',
                    CLOUDAGENT_ID =  '".$cloudAgentId."',    
                    RESIGNATION_DATE = '".$resignationdate."'";

            $sql .= " WHERE ADMINID='".$userid."'";
            $DataUpdate = mysql_query($sql) or die(mysql_error()." update user");
            if($_REQUEST['city'][0] == '')
                 unset($_REQUEST['city'][0]); 
            if(count($_REQUEST['city']) == 0) {
                $qryDel = "delete from proptiger_admin_city where admin_id = $userid";
                $resDel = mysql_query($qryDel) or die(mysql_error());
            }
            $qryCityData = "select city_id from proptiger_admin_city where admin_id = $userid";
            $resCityData = mysql_query($qryCityData) or ide(mysql_error());
            $arrCityList = array();
            while($cityDataFetch = mysql_fetch_assoc($resCityData)) {
                $arrCityList[$cityDataFetch['city_id']] = $cityDataFetch;
            }
                       
            if(count($_REQUEST['city'])>0 && $department == 'SURVEY') {
                  //delete data if deselect
                    foreach($arrCityList as $k=>$v) {
                        if(!in_array($k,$_REQUEST['city'])) {
                            $qryDel = "delete from proptiger_admin_city where admin_id = $userid and city_id = $k";
                            $resDel = mysql_query($qryDel) or die(mysql_error());
                        }
                    }
                    //end delete image if deselect
                    $cityQry = "insert into proptiger_admin_city (admin_id,city_id) values ";
                    $cityData = '';
                    $arrCtList = array();
                    $flg = 0;
                    foreach($_REQUEST['city'] as $k=>$val){
                        if($val != 'other') {
                            $arrCtList[] = $val;
                            if(!array_key_exists($val,$arrCityList))
                                $cityData .=  "($userid,$val) , ";
                       }else{
                           $flg = 1;
                       }
                    }
                    if($flg == 1) {
                        foreach($arrOtherCities as $k=>$v){
                               if(!in_array($k,$arrCtList))
                                $cityData .=  "($userid,$k) , ";
                           }
                    }
                    $expComma = explode(" , ",$cityData);
                    array_pop($expComma);
                    if(count($expComma)>0) {
                        $finalStr = implode(",",$expComma);
                        $finalStr = $cityQry.$finalStr;
                        $resCity = mysql_query($finalStr) or die(mysql_error());
                    }
                    
                    /**************/
                    /***entry for other cities******/
                    
                }else{
                    $qryDel = "delete from proptiger_admin_city where admin_id = $userid";
                    $resDel = mysql_query($qryDel) or die(mysql_error());
                }
            if($DataUpdate)
                    header("Location:userList.php");
        }	
}

else if ($_GET['userid']!='') {
 		
 	 $UserDetail = ViewUserDetails($userid);
	 $smarty->assign("txtadminid", stripslashes($UserDetail['ADMINID']));
	 $smarty->assign("txtempcode", stripslashes($UserDetail['EMP_CODE']));	
	 $smarty->assign("txtfname", stripslashes($UserDetail['FNAME']));
	 $smarty->assign("txtlname", stripslashes($UserDetail['LNAME']));                                 
	 $smarty->assign("txtusername", stripslashes($UserDetail['USERNAME']));	
	 $smarty->assign("txtadminpassword", stripslashes($UserDetail['ADMINPASSWORD']));
         $smarty->assign("txtadminpasswordOld", stripslashes($UserDetail['ADMINPASSWORD']));
	 $smarty->assign("adminemail", stripslashes($UserDetail['ADMINEMAIL']));
	 $smarty->assign("mobile", stripslashes($UserDetail['MOBILE']));	
	 $smarty->assign("datecreated", stripslashes($UserDetail['ADMINADDDATE']));
	 $smarty->assign("lastlogin", stripslashes($UserDetail['ADMINLASTLOGIN']));
	 $smarty->assign("txtregion", stripslashes($UserDetail['REGION']));
	 $smarty->assign("status", stripslashes($UserDetail['STATUS']));
	 $smarty->assign("txtdepartment", stripslashes($UserDetail['DEPARTMENT']));
	 $smarty->assign("txtdesignation", stripslashes($UserDetail['ROLE']));
	 $smarty->assign("joiningdate",stripslashes($UserDetail['JOINING_DATE']));
	 $smarty->assign("resignationdate",stripslashes($UserDetail['RESIGNATION_DATE']));
         $smarty->assign("cloudAgentId", stripslashes($UserDetail['CLOUDAGENT_ID']));
         
         /********fetch data from proptiger_admin_city***********/
         $qryCityAdmin = "select city_id from proptiger_admin_city where admin_id = $userid";
         $resCityAdmin = mysql_query($qryCityAdmin) or die(mysql_error());
         $arrExistingCity = array();
         $otherCityChk = 0;
         while($dataExisting = mysql_fetch_assoc($resCityAdmin)) {
             if(array_key_exists($dataExisting['city_id'],$arrOtherCities))
                     $otherCityChk = 1;
             $arrExistingCity[] = $dataExisting['city_id']; 
         }
         $smarty->assign("arrExistingCity", $arrExistingCity);
         $smarty->assign("otherCityChk", $otherCityChk);
}


if($_POST['btnExit'] == "Exit") {
      header("Location:userList.php?page=1&sort=all");
} 
?>
