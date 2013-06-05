<?php
	$BuilderDataArr	=	BuilderArr();
	$CityDataArr	=	CityArr();
	$ProjectTypeArr	=	ProjectTypeArr();
	$BankListArr	=	BankList();
	$enum_value		=	enum_value();

	$smarty->assign("BuilderDataArr",$BuilderDataArr);
	$smarty->assign("CityDataArr",$CityDataArr);
	$smarty->assign("ProjectTypeArr",$ProjectTypeArr);
	$smarty->assign("BankListArr",$BankListArr);
	$smarty->assign("enum_value",$enum_value);
	//echo "<pre>";
			//print_r($_REQUEST);
	//echo "</pre>";//die();
	/*************************************/
	$sourcepath=array();
	$destinationpath=array();
	$flag=0;
	$projectFolderCreated=0;
	if(!isset($_REQUEST['projectId']))
		$_REQUEST['projectId'] = '';
	$projectId = $_REQUEST['projectId'];
	$smarty->assign("projectId", $projectId);
	
	if(!isset($_REQUEST['preview']))
		$_REQUEST['preview'] = '';
	$preview = $_REQUEST['preview'];
	$smarty->assign("preview", $preview);

if(isset($_POST['btnSave']) || isset($_POST['btnExit']))
{
	if ($_POST['btnSave'] == "Next" || $_POST['btnSave'] == "Save")
	{

		$txtProjectName				=	trim($_POST['txtProjectName']);
		$builderId					=	trim($_POST['builderId']);
		$cityId						=	trim($_POST['cityId']);
		$suburbId					=	trim($_POST['suburbId']);
		$localityId					=	trim($_POST['localityId']);
		$txtProjectDescription		=	trim($_POST['txtProjectDescription']);
		$txtProjectRemark			=	trim($_POST['txtProjectRemark']);
		$txtAddress					=	trim($_POST['txtProjectAddress']);
		$txtProjectDesc				=	trim($_POST['txtProjectDesc']);
		$txtProjectSource			=	trim($_POST['txtProjectSource']);
		$project_type				=	trim($_POST['project_type']);
		$txtProjectLocation			=	trim($_POST['txtProjectLocation']);
		$txtProjectLattitude		=	trim($_POST['txtProjectLattitude']);
		$txtProjectLongitude		=	trim($_POST['txtProjectLongitude']);
		$txtProjectMetaTitle		=	trim($_POST['txtProjectMetaTitle']);
		$txtMetaKeywords			=	trim($_POST['txtMetaKeywords']);
		$txtMetaDescription			=	trim($_POST['txtMetaDescription']);
		$DisplayOrder				=	'';
		$Active						=	trim($_POST['Active']);
		$Status						=	trim($_POST['Status']);
		$txtProjectURL				=	trim($_POST['txtProjectURL']);
		$txtProjectURLOld			=	trim($_POST['txtProjectURLOld']);
		$Featured					=	trim($_POST['Featured']);
		$txtDisclaimer				=	trim($_POST['txtDisclaimer']);
		$no_of_towers				=	trim($_POST['no_of_towers']);
		$no_of_flats				=	trim($_POST['no_of_flats']);
		$pre_launch_date    		=	trim($_POST['pre_launch_date']);
		$eff_date_to				=	trim($_POST['eff_date_to']);
		$special_offer				=	trim($_POST['special_offer']);
		$display_order				=	trim($_POST['display_order']);
		$oldbuilderId				=	trim($_POST['oldbuilderId']);
		$youtube_link				=	trim($_POST['youtube_link']);

		$price_list_chk				=	trim($_POST['price_list_chk']);
		$price_list					=	trim($_POST['price_list']);
		
		if(isset($_POST['price_list_pdf']))
			$price_list_pdf				=	trim($_POST['price_list_pdf']);
		else
			$price_list_pdf				=	'';

		$application				=	trim($_POST['application']);
		$app_form					=	trim($_POST['app_form']);
		
		if(isset($_POST['app_form_pdf']))
			$app_form_pdf				=	trim($_POST['app_form_pdf']);
		else
			$app_form_pdf				=	'';
		$payment_chk				=	trim($_POST['payment_chk']);
		$payment					=	trim($_POST['payment']);
		
		if(isset($_POST['payment_pdf']))
			$payment_pdf				=	trim($_POST['payment_pdf']);
		else
			$payment_pdf				=	'';

		$approvals					=	trim($_POST['approvals']);
		$project_size				=	trim($_POST['project_size']);
		$no_of_lift					=	trim($_POST['no_of_lift']);
		$powerBackup				=	trim($_POST['powerBackup']);
		$architect					=	trim($_POST['architect']);
		$offer_heading				=	trim($_POST['offer_heading']);
		$offer_desc					=	trim($_POST['offer_desc']);
		$power_backup_capacity		=	trim($_POST['power_backup_capacity']);
		$no_of_villa				=	trim($_POST['no_of_villa']);
		$eff_date_to_prom			=	trim($_POST['eff_date_to_prom']);
		$residential				=	trim($_POST['residential']);
		$township					=	trim($_POST['township']);
		$projName					=	trim($_POST['txtProjectName']);
		$no_of_plot                 =	trim($_POST['no_of_plot']);
		$open_space                 =	trim($_POST['open_space']);
		$Booking_Status             =	trim($_POST['Booking_Status']);
		$shouldDisplayPrice         =   trim($_POST['shouldDisplayPrice']);	
		$txtCallingRemark         	=   trim($_POST['txtCallingRemark']);
		$txtAuditRemark         	=   trim($_POST['txtAuditRemark']);
		$launchedUnits         		=   trim($_POST['launchedUnits']);
		$reasonUnlaunchedUnits     	=   trim($_POST['reasonUnlaunchedUnits']);
		
		if(isset($_POST['bank_list']))
			$bank_list = implode(",",$_POST['bank_list']);//die("here");
		else
			$bank_list = '';
		/***************Query for suburb selected************/
		if($_POST['cityId'] != '')
		{
			$suburbSelect = SuburbArr($_POST['cityId']);
			$smarty->assign("suburbSelect", $suburbSelect);

			 if($suburbId != '')
                $suburbId  = $suburbId;
			else
				$suburbId  = '';

			$localitySelect = localityList($_POST['cityId'],$suburbId);
			$smarty->assign("localitySelect", $localitySelect);
		}
		/***************end Query for Locality selected************/

		$smarty->assign("txtProjectName", $txtProjectName);
		$smarty->assign("builderId", $builderId);
		$smarty->assign("cityId", $cityId);
		$smarty->assign("suburbId", $suburbId);
		$smarty->assign("localityId", $localityId);
		$smarty->assign("txtProjectDescription", $txtProjectDescription);
		$smarty->assign("txtProjectRemark", $txtProjectRemark);
		$smarty->assign("txtAddress", $txtAddress);
		$smarty->assign("txtProjectDesc", $txtProjectDesc);
		$smarty->assign("txtSourceofInfo", $txtProjectSource);
		$smarty->assign("project_type", $project_type);
		$smarty->assign("txtProjectLocation", $txtProjectLocation);
		$smarty->assign("txtProjectLattitude", $txtProjectLattitude);
		$smarty->assign("txtProjectLongitude", $txtProjectLongitude);
		$smarty->assign("txtProjectMetaTitle", $txtProjectMetaTitle);
		$smarty->assign("txtMetaKeywords", $txtMetaKeywords);
		$smarty->assign("txtMetaDescription", $txtMetaDescription);
		$smarty->assign("DisplayOrder", $DisplayOrder);
		$smarty->assign("Active", $Active);
		$smarty->assign("Status", $Status);
		$smarty->assign("txtProjectURL", $txtProjectURL);
		$smarty->assign("txtProjectURLOld", $txtProjectURLOld);
		$smarty->assign("Featured", $Featured);
		$smarty->assign("txtDisclaimer", $txtDisclaimer);
		$smarty->assign("no_of_towers", $no_of_towers);
		$smarty->assign("no_of_flats", $no_of_flats);
		$smarty->assign("pre_launch_date", $pre_launch_date);
		$smarty->assign("eff_date_to", $eff_date_to);
		$smarty->assign("special_offer", $special_offer);
		$smarty->assign("display_order", $display_order);
		$smarty->assign("youtube_link", $youtube_link);
		if(isset($_POST['bank_list']))
			$smarty->assign("bank_arr", $_POST['bank_list']);
		else
			$smarty->assign("bank_arr", '');

		$smarty->assign("price_list_chk", $price_list_chk);
		$smarty->assign("price_list", $_POST['price_list']);

		$smarty->assign("application", $application);
		$smarty->assign("app_form", $_POST['app_form']);

		$smarty->assign("payment_chk", $payment_chk);
		$smarty->assign("payment", $_POST['payment']);

		$smarty->assign("approvals", $_POST['approvals']);
		$smarty->assign("project_size", $_POST['project_size']);
		$smarty->assign("no_of_lift", $_POST['no_of_lift']);
		$smarty->assign("powerBackup", $_POST['powerBackup']);
		$smarty->assign("architect", $_POST['architect']);
		$smarty->assign("offer_heading", $_POST['offer_heading']);
		$smarty->assign("offer_desc", $_POST['offer_desc']);
		$smarty->assign("power_backup_capacity", $_POST['power_backup_capacity']);
		$smarty->assign("no_of_villa", $_POST['no_of_villa']);
		$smarty->assign("eff_date_to_prom", $_POST['eff_date_to_prom']);
		$smarty->assign("residential", $_POST['residential']);
		$smarty->assign("township", $_POST['township']);
		$smarty->assign("open_space", $_POST['open_space']);
		$smarty->assign("Booking_Status", $_POST['Booking_Status']);
		$smarty->assign("shouldDisplayPrice", $_POST['shouldDisplayPrice']);
		
		$smarty->assign("txtCallingRemark", $_POST['txtCallingRemark']);
		$smarty->assign("txtAuditRemark", $_POST['txtAuditRemark']);
		$smarty->assign("launchedUnits", $_POST['launchedUnits']);
		$smarty->assign("reasonUnlaunchedUnits", $_POST['reasonUnlaunchedUnits']);

		/***********Folder name**********/
		$builderDetail	=	fetch_builderDetail($builderId);
		$BuilderName	=	$builderDetail['BUILDER_NAME'];
		
		$localityDetail	=	ViewLocalityDetails($localityId);
		$localityName   =   $localityDetail['LABEL'];
		
		$cityDetail	=	ViewCityDetails($cityId);
		$cityName   =   $cityDetail['LABEL'];
				
		if(!preg_match('/^[a-zA-z0-9 ]+$/', $txtProjectName)){
			$ErrorMsg["txtProjectName"] = "Special characters are not allowed";
		}
		
		$qryprojectchk	=	"SELECT PROJECT_NAME,PROJECT_SMALL_IMAGE FROM ".RESI_PROJECT." WHERE PROJECT_NAME = '".$txtProjectName."' AND BUILDER_ID = '".$builderId."' AND LOCALITY_ID = '".$localityId."' AND CITY_ID = '".$cityId."'";
		$resprojectchk	=	mysql_query($qryprojectchk);
		
		if($projectId=='')
		 {
		   if(mysql_num_rows($resprojectchk) >0)
		   {
			   $ErrorMsg["txtProjectName"] = "Project already exist.";
		   }
	     }
	     
	     $qryUrl = "SELECT * FROM ".RESI_PROJECT." WHERE PROJECT_URL = '".$txtProjectURL."'";
	     $resUrl = mysql_query($qryUrl) or die(mysql_error());
	     if(mysql_num_rows($resUrl)>0)
	     {
	     	$ErrorMsg["txtProjectUrlDuplicate"] = "This URL already exist.";
	     }

	     if(empty($display_order) || $display_order < 1 || $display_order > 999 || ($display_order > 15 && $display_order < 101))
	     {
	         $ErrorMsg["display_order"] = "Please put in display order (1-15 for city page), (101-998 for locality page), 999 for default";
	     }
	     elseif ($display_order >= 1 && $display_order <= 15) {
	         $numProjects = getNumProjectsUnderDisplayOrder(15, $cityId);
	         if ($numProjects >= 15) {
	             $ErrorMsg["display_order"] = "Already $numProjects assigned city page level editorial priority. Please edit projects out of the range (1-15) first.";
	         }
	     }

	     if($txtProjectURL!='')
	     {
	     	if(!preg_match('/^p-[a-z0-9\-]+\.php$/',$txtProjectURL)){
	     		$ErrorMsg["txtProjectURL"] = "Please enter a valid url that contains only small characters, numerics & hyphen";
	     	}
	     }
	   $smarty->assign("ErrorMsg", $ErrorMsg);
	   if(is_array($ErrorMsg)) {
		// Do Nothing
	   }
	   else
		{
			$app = '';
			if($application == 'app_form')
			{
				$app = $app_form;
			}
			else if($application == 'app_form_pdf')
			{
					$dir		=	"application_form/";
					$pdf_path	=	$dir.str_replace(" ","",$txtProjectName).$_FILES['app_pdf']['name'];
					$move		=	move_uploaded_file($_FILES['app_pdf']['tmp_name'],$pdf_path);
					if($move == TRUE)
					{
						$app	=   $pdf_path;
					}
			}

			$price1 = '';
			if($price_list_chk == 'price_list')
			{
				$price1 = $price_list;
			}
			else if($price_list_chk == 'price_list_pdf')
			{
					$dir		=	"price_list/";
					$pdf_path	=	$dir.str_replace(" ","",$txtProjectName).$_FILES['price_list_pdf']['name'];
					$move		=	move_uploaded_file($_FILES['price_list_pdf']['tmp_name'],$pdf_path);
					if($move == TRUE)
					{
						$price1	=   $pdf_path;
					}
			}

			$payment1 = '';
			//echo $payment_chk;
			if($payment_chk == 'payment')
			{
				echo"",$payment1 = $payment;
			}
			else if($payment_chk == 'payment_pdf')
			{
				$dir		=	"payment_plan/";
				$pdf_path	=	$dir.str_replace(" ","",$txtProjectName).$_FILES['payment_pdf']['name'];
				$move		=	move_uploaded_file($_FILES['payment_pdf']['tmp_name'],$pdf_path);
				if($move == TRUE)
				{
					$payment1	=   $pdf_path;
				}

			}
			
		   if ($projectId == '')
		   {
		   		
				$projectId = InsertProject($projName, $builderId, $cityId,$suburbId,$localityId,$txtProjectDescription,$txtProjectRemark,$txtAddress,$txtProjectDesc,$txtProjectSource,$project_type,$txtProjectLocation,$txtProjectLattitude,$txtProjectLongitude,$txtProjectMetaTitle,$txtMetaKeywords,$txtMetaDescription,$DisplayOrder,$Active,$Status,$txtProjectURL,$Featured,$txtDisclaimer,$payment1,$no_of_towers,$no_of_flats,$pre_launch_date,$eff_date_to,$special_offer,$display_order,$youtube_link,$bank_list,$price1,$app,$approvals,$project_size,$no_of_lift,$powerBackup,$architect,$offer_heading,$offer_desc,$BuilderName,$power_backup_capacity,$no_of_villa,$eff_date_to_prom,$residential,$township,$no_of_plot,$open_space,$Booking_Status,$shouldDisplayPrice,$txtCallingRemark,$txtAuditRemark,$launchedUnits,$reasonUnlaunchedUnits);
				header("Location:project_img_add.php?projectId=".$projectId);
			}
			else
			{
				//echo $price1."==".$payment1;
				$projectId = UpdateProject($projName, $builderId, $cityId,$suburbId,$localityId,$txtProjectDescription,$txtProjectRemark,$txtAddress,$txtProjectDesc,$txtProjectSource,$project_type,$txtProjectLocation,$txtProjectLattitude,$txtProjectLongitude,$txtProjectMetaTitle,$txtMetaKeywords,$txtMetaDescription,$DisplayOrder,$Active,$Status,$txtProjectURL,$Featured,$txtDisclaimer,$payment1,$no_of_towers,$no_of_flats,$pre_launch_date,$eff_date_to,$special_offer,$display_order,$youtube_link,$bank_list,$price1,$app,$approvals,$project_size,$no_of_lift,$powerBackup,$architect,$offer_heading,$offer_desc,$BuilderName,$power_backup_capacity,$no_of_villa,$eff_date_to_prom,$projectId,$residential,$township,$no_of_plot,$open_space,$Booking_Status,$shouldDisplayPrice,$txtCallingRemark,$txtAuditRemark,$launchedUnits,$reasonUnlaunchedUnits);
				if($txtProjectURL != $txtProjectURLOld)
					insertUpdateInRedirectTbl($txtProjectURL,$txtProjectURLOld);
				if($preview == 'true')
					header("Location:show_project_details.php?projectId=".$projectId);
				else
					header("Location:ProjectList.php?projectId=".$projectId);
				
			}
		}
	}
	else if($_POST['btnExit'] == "Exit")
	{
		if($preview == 'true')
			header("Location:show_project_details.php?projectId=".$projectId);
		else
			header("Location:ProjectList.php?projectId=".$projectId);

	}
}
	if ($projectId!='')
	{
		
		$ProjectDetail 	= ProjectDetail($projectId);
		//print_r($ProjectDetail);die("here");
		 $smarty->assign("txtProjectName", stripslashes($ProjectDetail[0]['PROJECT_NAME']));
		 $smarty->assign("txtAddress", stripslashes($ProjectDetail[0]['PROJECT_ADDRESS']));
		 $smarty->assign("txtProjectDescription", stripslashes($ProjectDetail[0]['PROJECT_DESCRIPTION']));
		 $smarty->assign("txtProjectRemark", stripslashes($ProjectDetail[0]['PROJECT_REMARK']));
		 $smarty->assign("txtAddress", stripslashes($ProjectDetail[0]['PROJECT_ADDRESS']));
		 $smarty->assign("builderId", stripslashes($ProjectDetail[0]['BUILDER_ID']));
		 $smarty->assign("cityId", stripslashes($ProjectDetail[0]['CITY_ID']));
		 $smarty->assign("suburbId", stripslashes($ProjectDetail[0]['SUBURB_ID']));
		 $smarty->assign("localityId", stripslashes($ProjectDetail[0]['LOCALITY_ID']));
		 $smarty->assign("BUILDER_NAME", stripslashes($ProjectDetail[0]['BUILDER_NAME']));
		 $smarty->assign("txtProjectLocation", stripslashes($ProjectDetail[0]['LOCATION_DESC']));
		 $smarty->assign("txtProjectLattitude", stripslashes($ProjectDetail[0]['LATITUDE']));
		 $smarty->assign("txtProjectLongitude", stripslashes($ProjectDetail[0]['LONGITUDE']));
		 $smarty->assign("txtProjectMetaTitle", stripslashes($ProjectDetail[0]['META_TITLE']));
		 $smarty->assign("txtMetaKeywords", stripslashes($ProjectDetail[0]['META_KEYWORDS']));
		 $smarty->assign("txtMetaDescription", stripslashes($ProjectDetail[0]['META_DESCRIPTION']));
		 $smarty->assign("DisplayOrder", stripslashes($ProjectDetail[0]['DISPLAY_ORDER']));
		 $smarty->assign("Active", stripslashes($ProjectDetail[0]['ACTIVE']));
		 $smarty->assign("Status", stripslashes($ProjectDetail[0]['PROJECT_STATUS']));
		 $smarty->assign("txtProjectURL", stripslashes($ProjectDetail[0]['PROJECT_URL']));
		 $smarty->assign("txtProjectURLOld", stripslashes($ProjectDetail[0]['PROJECT_URL']));
		 $smarty->assign("Featured", stripslashes($ProjectDetail[0]['FEATURED']));
		 $smarty->assign("txtDisclaimer", stripslashes($ProjectDetail[0]['PRICE_DISCLAIMER']));
		$smarty->assign("payment", stripslashes($ProjectDetail[0]['PAYMENT_PLAN']));
		$smarty->assign("no_of_towers", stripslashes($ProjectDetail[0]['NO_OF_TOWERS']));
		$smarty->assign("no_of_flats", stripslashes($ProjectDetail[0]['NO_OF_FLATS']));
		$smarty->assign("eff_date_to", stripslashes($ProjectDetail[0]['LAUNCH_DATE']));
		$smarty->assign("special_offer", stripslashes($ProjectDetail[0]['OFFER']));
		$smarty->assign("display_order", stripslashes($ProjectDetail[0]['DISPLAY_ORDER']));
		$smarty->assign("youtube_link", stripslashes($ProjectDetail[0]['YOUTUBE_VIDEO']));
		$smarty->assign("price_list", stripslashes($ProjectDetail[0]['PRICE_LIST']));
		$smarty->assign("app_form", stripslashes($ProjectDetail[0]['APPLICATION_FORM']));
		$smarty->assign("txtProjectDesc", stripslashes($ProjectDetail[0]['OPTIONS_DESC']));
		$smarty->assign("txtSourceofInfo", stripslashes($ProjectDetail[0]['SOURCE_OF_INFORMATION']));
		$smarty->assign("project_type", stripslashes($ProjectDetail[0]['PROJECT_TYPE_ID']));
		$smarty->assign("approvals", stripslashes($ProjectDetail[0]['APPROVALS']));
		$smarty->assign("project_size", stripslashes($ProjectDetail[0]['PROJECT_SIZE']));
		$smarty->assign("no_of_lift", stripslashes($ProjectDetail[0]['NO_OF_LIFTS_PER_TOWER']));
		$smarty->assign("power_backup_capacity", stripslashes($ProjectDetail[0]['POWER_BACKUP_CAPACITY']));
		$smarty->assign("powerBackup", stripslashes($ProjectDetail[0]['POWER_BACKUP']));
		$smarty->assign("architect", stripslashes($ProjectDetail[0]['ARCHITECT_NAME']));
		$smarty->assign("offer_heading", stripslashes($ProjectDetail[0]['OFFER_HEADING']));
		$smarty->assign("offer_desc", stripslashes($ProjectDetail[0]['OFFER_DESC']));
		$smarty->assign("eff_date_to_prom", stripslashes($ProjectDetail[0]['PROMISED_COMPLETION_DATE']));
		$smarty->assign("residential", stripslashes($ProjectDetail[0]['RESIDENTIAL']));
		$smarty->assign("township", stripslashes($ProjectDetail[0]['TOWNSHIP']));
		$smarty->assign("pre_launch_date", stripslashes($ProjectDetail[0]['PRE_LAUNCH_DATE']));
		$smarty->assign("no_of_villa", stripslashes($ProjectDetail[0]['NO_OF_VILLA']));
		$smarty->assign("no_of_plot", stripslashes($ProjectDetail[0]['NO_OF_PLOTS']));
		$smarty->assign("open_space", stripslashes($ProjectDetail[0]['OPEN_SPACE']));
		$smarty->assign("Booking_Status", stripslashes($ProjectDetail[0]['BOOKING_STATUS']));
		$smarty->assign("shouldDisplayPrice", stripslashes($ProjectDetail[0]['SHOULD_DISPLAY_PRICE']));
		$smarty->assign("txtCallingRemark", stripslashes($ProjectDetail[0]['CALLING_REMARK']));
		$smarty->assign("txtAuditRemark", stripslashes($ProjectDetail[0]['AUDIT_REMARK']));
		$smarty->assign("launchedUnits", stripslashes($ProjectDetail[0]['LAUNCHED_UNITS']));
		$smarty->assign("reasonUnlaunchedUnits", stripslashes($ProjectDetail[0]['REASON_UNLAUNCHED_UNITS']));

		if(isset($ProjectDetail[0]['BANK_LIST']))
		{
			$bank_arr = explode(",",$ProjectDetail[0]['BANK_LIST']);
		}
		else
		{
			$bank_arr = '';
		}

		$smarty->assign("bank_arr", $bank_arr);

		$suburbSelect = SuburbArr($ProjectDetail[0]['CITY_ID']);
		 $smarty->assign("suburbSelect", $suburbSelect);

		if($ProjectDetail[0]['SUBURB_ID'] != null)
			$suburbId  = $ProjectDetail[0]['SUBURB_ID'];
		else
			$suburbId  = '';

		$localitySelect = localityList($ProjectDetail[0]['CITY_ID'],$suburbId);
		$smarty->assign("localitySelect", $localitySelect);
		//echo "<pre>";
		//	print_r($localitySelect);
		//echo "</pre>";
		$CityDataArr = CityArr();
		$BuilderDataArr = BuilderArr();
		$smarty->assign("BuilderDataArr", $BuilderDataArr);
		$BankDataArr = BankList();
	 }

	 function getNumProjectsUnderDisplayOrder($displayOrder, $cityId) {
	     $numProjects = 0;
	     $qry = "SELECT count(*) as numProjects FROM resi_project WHERE display_order <= $displayOrder and city_id = $cityId";
	     $res = mysql_query($qry);
	     while($data = mysql_fetch_array($res))
	     {
	         $numProjects = $data['numProjects'];
	     }
	      
	     return $numProjects;
	 }

?>