<?php
if(isset($_POST['Submit_x']))
{
	$AdminUserName = $_POST['AdminUserName'];
	$AdminPassword = $_POST['AdminPassword'];

	if(ChkAdminLogin($AdminUserName,$AdminPassword)==FALSE)
	{
		$LoginError = 'Username or Password is invalid.';
		$smarty->assign("LoginError", $LoginError);
	}
	else
	{
		$AdminDetail 				= AdminLoginDetail($AdminUserName);
		$_SESSION['adminId'] 		= $AdminDetail['adminId'];
		$_SESSION['AdminUserName'] 	= $AdminDetail['userName'];
		$_SESSION['AdminLogin'] 	= "Y";
		$_SESSION['ACCESS_LEVEL'] 			= $AdminDetail['ACCESS_LEVEL'];
		$_SESSION['LAST_LOGIN_DATE'] 		= $AdminDetail['LAST_LOGIN_DATE'];
		$_SESSION['LAST_LOGIN_IP'] 			= $AdminDetail['LAST_LOGIN_IP'];
		$_SESSION['DEPARTMENT'] 			= $AdminDetail['DEPARTMENT'];
                $_SESSION['ROLE']                               = $AdminDetail['ROLE'];
		
		/************update admin table for last login*******************/
		$qryUpDate	=	"UPDATE ".ADMIN." SET LAST_LOGIN_DATE = now(),LAST_LOGIN_IP = '".$_SERVER['REMOTE_ADDR']."' WHERE ADMINID = '".$_SESSION['adminId']."'";
		$resUpdate	=	mysql_query($qryUpDate);
		header("Location:project_desktop.php"); 
	}
}
?>