<?php
 $accessUserManage = '';
 if( $userManagement == false )
   $accessUserManage = "No Access";
 $smarty->assign("accessUserManage",$accessUserManage);

$userid = $_REQUEST['userid'];
$smarty->assign("userid", $userid);


if ($_POST['btnSave'] == "Save") { 
	$txt_empcode	=	trim($_POST['txt_empcode']);
	$txt_name		=	trim($_POST['txt_name']);
	$txt_email		=	trim($_POST['txt_email']);
	$txt_mobile		=	trim($_POST['txt_mobile']);
	$passwordFlag	=	trim($_POST['radiopass']);
	$txt_username	=	trim($_POST['txt_username']);
	$txt_password	=	trim($_POST['txt_password']);
	$region			=	trim($_POST['region']);
	$branch			=	trim($_POST['branch']);
	$department		=	trim($_POST['dept']);
	$designation	=	trim($_POST['department']); //designation
	$radioForStatus	=	$_POST['active'];
	$joiningdate	= $_POST['joiningdate'];
	$resignationdate = $_POST['resignationdate'];
        
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
                        RESIGNATION_DATE = '".$resignationdate."'";
		$DataInsert = mysql_query($sql) or die(mysql_error());
                if($DataInsert)
                    header("Location:userList.php");
               }
	}
	else
	{
             $sql = "UPDATE ".ADMIN." SET 
                    EMP_CODE = '".$txt_empcode."' ,
                    FNAME	= '".$txt_name."',
                    USERNAME = '".$txt_username."', 		
                    ADMINEMAIL = '".$txt_email."',
                    MOBILE = '".$txt_mobile	."',
                    ADMINADDDATE = '".date("Y-m-d")."',
                    BRANCH_LOCATION	= '".$branch."',
                    REGION = '".$region."',
                    STATUS	= '".$radioForStatus."',
                    DEPARTMENT ='".$department."',
                    ROLE = '".$designation."',
                    JOINING_DATE = '".$joiningdate."',
                    RESIGNATION_DATE = '".$resignationdate."'";

            $sql .= " WHERE ADMINID='".$userid."'";
            $DataUpdate = mysql_query($sql) or die(mysql_error()." update user");
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

}


if($_POST['btnExit'] == "Exit") {
      header("Location:userList.php?page=1&sort=all");
} 
?>
