<?php        
$BuilderDataArr	= ResiBuilder::BuilderEntityArr();
$CityDataArr = City::CityArr();
$ProjectTypeArr	= ResiProjectType::ProjectTypeArr();
$BankListArr = BankList::arrBank();
$projectStatus = ResiProject::projectStatusMaster();
$allTownships = Townships::getAllTownships();
$getPowerBackupTypes = PowerBackupTypes::getPowerBackupTypes();

include_once('./function/locality_functions.php');
$smarty->assign("BuilderDataArr",$BuilderDataArr);
$smarty->assign("CityDataArr",$CityDataArr);
$smarty->assign("ProjectTypeArr",$ProjectTypeArr);
$smarty->assign("BankListArr",$BankListArr);
$smarty->assign("projectStatus",$projectStatus);
$smarty->assign("allTownships",$allTownships);
$smarty->assign("getPowerBackupTypes",$getPowerBackupTypes);
$smarty->assign("display_order", 999);
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
      
if( isset($_POST['btnSave']) || isset($_POST['btnExit']) ) {
	if ( $_POST['btnSave'] == "Next" || $_POST['btnSave'] == "Save" ) {
	    $txtProjectName = trim($_POST['txtProjectName']);
            $builderId = trim($_POST['builderId']);
            $cityId = trim($_POST['cityId']);
            $suburbId =	trim($_POST['suburbId']);
            $localityId	= trim($_POST['localityId']);
            $txtProjectDescription = trim($_POST['txtProjectDesc']);
            $comments = trim($_POST['comments']);
            $txtProjectRemark =	trim($_POST['txtProjectRemark']);
            $txtProjectRemarkDisplay = trim($_POST['txtProjectRemarkDisplay']);
            $txtAddress	= trim($_POST['txtProjectAddress']);
            $txtProjectSource =	trim($_POST['txtProjectSource']);
            $project_type = trim($_POST['project_type']);
            $txtProjectLocation	= trim($_POST['txtProjectLocation']);
            $txtProjectLattitude = trim($_POST['txtProjectLattitude']);
            $txtProjectLongitude = trim($_POST['txtProjectLongitude']);
           
            $DisplayOrder = '';
            $Active = trim($_POST['Active']);
            $Status = trim($_POST['Status']);
            $txtProjectURL = trim($_POST['txtProjectURL']);
            $txtProjectURLOld =	trim($_POST['txtProjectURLOld']);
            $txtDisclaimer = trim($_POST['txtDisclaimer']);
           
            $pre_launch_date = trim($_POST['pre_launch_date']);
            $exp_launch_date = trim($_POST['exp_launch_date']);
	    $eff_date_to = trim($_POST['eff_date_to']);
            $display_order = PROJECT_MAX_PRIORITY;
	    $oldbuilderId = trim($_POST['oldbuilderId']);
	    $youtube_link = trim($_POST['youtube_link']);
            
            $application = trim($_POST['application']);
            $app_form =	trim($_POST['app_form']);
        
if(isset($_POST['btnSave']) || isset($_POST['btnExit']))
{
	if ($_POST['btnSave'] == "Next" || $_POST['btnSave'] == "Save")
	{

	    $txtProjectName				=	trim($_POST['txtProjectName']);
            $projectNameOld                             =       trim($_POST['projectNameOld']);
            $builderId					=	trim($_POST['builderId']);
            $cityId					=	trim($_POST['cityId']);
            $suburbId					=	trim($_POST['suburbId']);
            $localityId					=	trim($_POST['localityId']);
		$txtProjectDescription                  =	trim($_POST['txtProjectDescription']);
		$txtProjectRemark			=	trim($_POST['txtProjectRemark']);
                $txtProjectRemarkDisplay		=	trim($_POST['txtProjectRemarkDisplay']);
		$txtAddress				=	trim($_POST['txtProjectAddress']);
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
		$txtProjectURL				=	'';
		$txtProjectURLOld			=	trim($_POST['txtProjectURLOld']);
		$Featured					=	trim($_POST['Featured']);
		$txtDisclaimer				=	trim($_POST['txtDisclaimer']);
		$no_of_towers				=	trim($_POST['no_of_towers']);
		$no_of_flats				=	trim($_POST['no_of_flats']);
		$pre_launch_date            =	trim($_POST['pre_launch_date']);
        $exp_launch_date            =	trim($_POST['exp_launch_date']);
		$eff_date_to				=	trim($_POST['eff_date_to']);
		$special_offer				=	trim($_POST['special_offer']);
		//$display_order			=	trim($_POST['display_order']);
        $display_order				=   PROJECT_MAX_PRIORITY;
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

            if(isset($_POST['app_form_pdf']))
                $app_form_pdf = trim($_POST['app_form_pdf']);
            else
                $app_form_pdf = '';
            $approvals = trim($_POST['approvals']);
            $project_size = trim($_POST['project_size']);
            $powerBackup = trim($_POST['powerBackup']);
            $architect = trim($_POST['architect']);
            $power_backup_capacity = trim($_POST['power_backup_capacity']);
            $eff_date_to_prom =	trim($_POST['eff_date_to_prom']);
            $residential = (trim($_POST['residential']))?$_POST['residential']:'residential'; //setting up defualt value if empty
            $township =	trim($_POST['township']);
            $projName =	trim($_POST['txtProjectName']);
            $no_of_plot = trim($_POST['no_of_plot']);
            $open_space = trim($_POST['open_space']);
            $shouldDisplayPrice = trim($_POST['shouldDisplayPrice']);	
            $txtCallingRemark = trim($_POST['txtCallingRemark']);
            $txtCallingRemarkDisplay = trim($_POST['txtCallingRemarkDisplay']);
            $txtAuditRemark = trim($_POST['txtAuditRemark']);
            $txtAuditRemarkDisplay = trim($_POST['txtAuditRemarkDisplay']);
            $secondaryRemark = trim($_POST["secondaryRemark"]);
            $secondaryRemarkDisplay = trim($_POST["secondaryRemarkDisplay"]);
            $fieldSurveyRemark = trim($_POST["fieldSurveyRemark"]);
            $fieldSurveyRemarkDisplay = trim($_POST["fieldSurveyRemarkDisplay"]);
            
            /***************Query for suburb selected************/
            if( $_POST['cityId'] != '' ) {
               $suburbSelect = Suburb::SuburbArr($_POST['cityId']);
               $smarty->assign("suburbSelect", $suburbSelect);

               if($suburbId != '')
                  $suburbId  = $suburbId;
               else
                  $suburbId  = '';
               
                  $getLocalityBySuburb =  Locality::localityList($suburbId);
                  $smarty->assign("getLocalityBySuburb", $getLocalityBySuburb);
            }
            /***************end Query for Locality selected************/
            $smarty->assign("txtProjectName", $txtProjectName);
            $smarty->assign("builderId", $builderId);
            $smarty->assign("cityId", $cityId);
            $smarty->assign("suburbId", $suburbId);
            $smarty->assign("localityId", $localityId);
            $smarty->assign("txtProjectDescription", $txtProjectDescription);
            $smarty->assign("comments", $comments);
            $smarty->assign("txtProjectRemark", $txtProjectRemark);
            $smarty->assign("txtProjectRemarkDisplay", $txtProjectRemarkDisplay);
            $smarty->assign("txtAddress", $txtAddress);
            $smarty->assign("txtSourceofInfo", $txtProjectSource);
            $smarty->assign("project_type", $project_type);
            $smarty->assign("txtProjectLocation", $txtProjectLocation);
            $smarty->assign("txtProjectLattitude", $txtProjectLattitude);
            $smarty->assign("txtProjectLongitude", $txtProjectLongitude);
           
            $smarty->assign("DisplayOrder", $DisplayOrder);
            $smarty->assign("Active", $Active);
            $smarty->assign("Status", $Status);
            $smarty->assign("txtProjectURL", $txtProjectURL);
            $smarty->assign("txtProjectURLOld", $txtProjectURLOld);
            $smarty->assign("txtDisclaimer", $txtDisclaimer);
            $smarty->assign("pre_launch_date", $pre_launch_date);
            $smarty->assign("exp_launch_date", $exp_launch_date);
            $smarty->assign("eff_date_to", $eff_date_to);   
            $smarty->assign("display_order", $display_order);
            $smarty->assign("youtube_link", $youtube_link);

            if(isset($_POST['bank_list']))
               $smarty->assign("bank_arr", $_POST['bank_list']);
            else
               $smarty->assign("bank_arr", '');
            
            $smarty->assign("application", $application);
            $smarty->assign("app_form", $_POST['app_form']);
            $smarty->assign("approvals", $_POST['approvals']);
            $smarty->assign("project_size", $_POST['project_size']);
            $smarty->assign("powerBackup", $_POST['powerBackup']);
            $smarty->assign("architect", $_POST['architect']);
            $smarty->assign("power_backup_capacity", $_POST['power_backup_capacity']);
            $smarty->assign("eff_date_to_prom", $_POST['eff_date_to_prom']);
            $smarty->assign("residential", $_POST['residential']);
            $smarty->assign("township", $_POST['township']);
            $smarty->assign("open_space", $_POST['open_space']);
            $smarty->assign("shouldDisplayPrice", $_POST['shouldDisplayPrice']);
            $smarty->assign("txtCallingRemark", $_POST['txtCallingRemark']);
            $smarty->assign("txtCallingRemarkDisplay", $_POST['txtCallingRemarkDisplay']);
            $smarty->assign("txtAuditRemark", $_POST['txtAuditRemark']);
            $smarty->assign("txtAuditRemarkDisplay", $_POST['txtAuditRemarkDisplay']);
            $smarty->assign("launchedUnits", $_POST['launchedUnits']);
            $smarty->assign("secondaryRemark", $secondaryRemark);
            $smarty->assign("secondaryRemarkDisplay", $secondaryRemarkDisplay);
            $smarty->assign("fieldSurveyRemark", $fieldSurveyRemark);
            $smarty->assign("fieldSurveyRemarkDisplay", $fieldSurveyRemarkDisplay);

            /***********Folder name**********/
            if(!empty($builderId)){
	    	$builderDetail = ResiBuilder::getBuilderById($builderId);
            	$BuilderName = $builderDetail->builder_name;
	    }
	    if(!empty($localityId)){
            	$localityDetail = Locality::getLocalityById($localityId);
            	$localityName = $localityDetail->label;
            }
	    if(!empty($cityId)){
	    	$cityDetail = City::getCityById($cityId);
            	$cityName = $cityDetail->label;
	    }
            $ErrorMsg = array();
	    if(empty($txtProjectName)){
               $ErrorMsg["txtProjectName"] = "Project name must not be blank.";
            }elseif(!preg_match('/^[a-zA-Z0-9 ]+$/', $txtProjectName)){
               $ErrorMsg["txtProjectName"] = "Special characters are not allowed.";
            }
	    if(empty($builderId)){
               $ErrorMsg["txtBuilder"] = "Builder name must be selected.";
            }
	    if(empty($cityId)){
               $ErrorMsg["txtCity"] = "City must be selected.";
            }
	    if(empty($localityId)){
               $ErrorMsg["txtLocality"] = "Locality must be selected.";
            }
	    if(empty($suburbId)){
               $ErrorMsg["txtSuburbs"] = "Suburbs must be selected.";
            }
	    if(empty($comments)){
               $ErrorMsg["txtComments"] = "Please enter comment.";
            }
	    if(empty($txtAddress)){
               $ErrorMsg["txtAddress"] = "Please enter project address.";
            }
	    if(empty($txtProjectDescription)){
               $ErrorMsg["txtDesc"] = "Please enter Option description.";
            }
	    if(empty($txtProjectSource)){
		$ErrorMsg["txtSource"] = "Please enter project source of information.";
	    }
	    if(empty($project_type)){
		$ErrorMsg["txtProject_type"] = "Please select project type.";
	    }
	    if(empty($txtProjectLattitude)){
		$ErrorMsg["txtLattitude"] = "Please enter project lattitude.";
	    }
	    if(empty($txtProjectLongitude)){
		$ErrorMsg["txtLongitude"] = "Please enter project longitude.";
	    }
	    if(empty($Status)){
		$ErrorMsg["txtStatus"] = "Please select project status.";
	    }
	    if(!is_numeric($open_space) || $open_space > 100){
		$ErrorMsg["txtopen_space"] = "Open Space must be numeric and less than 100.";
	    }
	    if(!is_numeric($project_size) || $project_size > 500){
		$ErrorMsg["txtproject_size"] = "Project size must be numeric and less than 500.";
	    }
		$smarty->assign("txtProjectName", $txtProjectName);
                $smarty->assign("projectNameOld", $projectNameOld);
		$smarty->assign("builderId", $builderId);
		$smarty->assign("cityId", $cityId);
		$smarty->assign("suburbId", $suburbId);
		$smarty->assign("localityId", $localityId);
		$smarty->assign("txtProjectDescription", $txtProjectDescription);
		$smarty->assign("txtProjectRemark", $txtProjectRemark);
                $smarty->assign("txtProjectRemarkDisplay", $txtProjectRemarkDisplay);
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
                $smarty->assign("exp_launch_date", $exp_launch_date);
		$smarty->assign("eff_date_to", $eff_date_to);
		$smarty->assign("special_offer", $special_offer);
		$smarty->assign("display_order", $display_order);
		$smarty->assign("youtube_link", $youtube_link);
                
		if(isset($_POST['bank_list']))
			$smarty->assign("bank_arr", $_POST['bank_list']);
		else
			$smarty->assign("bank_arr", '');

            $projectChk = ResiProject::projectAlreadyExist($txtProjectName, $builderId, $localityId, $projectId);
            
            if(count($projectChk) >0)
            {
               $ErrorMsg["txtProjectName"] = "Project already exist.";
            }
           if( $txtProjectURL == '' )
		$ErrorMsg["txtProjectUrlDuplicate"] = "URL field must not be blank."; 
	   else{
              $projectUrlChk = ResiProject::projectUrlExist($txtProjectURL, $projectId);
              if( count($projectUrlChk)>0 ) {
                $ErrorMsg["txtProjectUrlDuplicate"] = "This URL already exist.";
              }
	    }
         if( $txtProjectURL!='' ) {
            if(!preg_match('/^p-[a-z0-9\-]+\.php$/',$txtProjectURL)){
               $ErrorMsg["txtProjectURL"] = "Please enter a valid url that contains only small characters, numerics & hyphen";
            }
         }
        $showTypeError = '';
        if( $specialAccessAuth == false ) {
            if($_REQUEST['project_type_hidden'] != '' && $_REQUEST['project_type_hidden'] != 0)
            {
                if($project_type != $_REQUEST['project_type_hidden'])
                {
                    $ErrorMsgType['showTypeError'] = 'You can not update project type!';
                    $ErrorMsg['showTypeError'] = 'error';
                }
                else
                {
                    $ErrorMsgType['showTypeError'] = ''; 
                }
            }
        }
        if( $exp_launch_date != '' && $exp_launch_date != '0000-00-00' ) {
             $retdt  = ((strtotime($exp_launch_date)-strtotime(date("Y-m-d")))/(60*60*24));
            if( $retdt <= 0 ) {
                $ErrorMsg['supplyDate'] = 'Expected supply date should be future date!';
            }
        }
        /**code for new launch and completion date diff and if In case PROJECT_STATUS = Pre Launch then Pre_launch_date cannot be empty In case PROJECT_STATUS = Occupied or Ready For Possession then  ****/  
        $launchDt = $eff_date_to;
        $promisedDt = $eff_date_to_prom;
        $preLaunchDt = $pre_launch_date;

        if( $launchDt == '0000-00-00' )    
            $launchDt = '';
        else {
            $exp = explode(" 00:",$launchDt);
            $launchDt = $exp[0];
        }
        if( $preLaunchDt == '0000-00-00' )
            $preLaunchDt = '';
        else {
            $exp = explode(" 00:",$preLaunchDt);
            $preLaunchDt = $exp[0];
        }
        if( $promisedDt == '0000-00-00' )
            $promisedDt = '';
        else {
            $exp = explode(" 00:",$promisedDt);
            $promisedDt = $exp[0];
        }
        if( $launchDt != '' && $promisedDt !='' ) {
            $retdt  = ((strtotime($promisedDt)-strtotime($launchDt))/(60*60*24));
            if( $retdt <= 0 ) {
                $ErrorMsg['CompletionDateGreater'] = 'Completion date to be always greater than launch date';
            }
        }
        if( $preLaunchDt != '' && $launchDt !='' ) {
            $retdt  = ((strtotime($launchDt) - strtotime($preLaunchDt)) / (60*60*24));
            if( $retdt <= 0 ) {
                $ErrorMsg['launchDateGreater'] = "Launch date to be always greater than Pre Launch date";
            }
        }
        if( $preLaunchDt != '' && $promisedDt !='' ) {
            $retdt  = ((strtotime($promisedDt) - strtotime($preLaunchDt)) / (60*60*24));
            if( $retdt <= 0 ) {
                $ErrorMsg['completionDateGreater'] = "Completion date to be always greater than Pre Launch date";
            }
       }

       if( $Status == 'Pre Launch' && $preLaunchDt == '' ) {
           $ErrorMsg['preLaunchDate'] = "Pre Launch date cant empty";
       }

       if( $Status == 'Pre Launch' && $launchDt != '' ) {
           $ErrorMsg['launchDate'] = "Launch date should be blank/zero";
       }

       if( $Status == 'Occupied' || $Status == 'Ready for Possession' ) {
           $yearExp = explode("-",$promisedDt);

           if( $yearExp[0] == date("Y") ) {
               if( intval($yearExp[1]) > intval(date("m"))) {
                 $ErrorMsg['CompletionDateGreater'] = "Completion date cannot be greater current month";
               }    
           } 
           else if (intval($yearExp[0]) > intval(date("Y")) ) {
               $ErrorMsg['CompletionDateGreater'] = "Completion date cannot be greater current month";
           }
       }

		/***********Folder name**********/
		$builderDetail	=	fetch_builderDetail($builderId);
		$BuilderName	=	$builderDetail['BUILDER_NAME'];
		
		$localityDetail	=	ViewLocalityDetails($localityId);
		$localityName   =   $localityDetail['LABEL'];
		
		$cityDetail	=	ViewCityDetails($cityId);
		$cityName   =   $cityDetail['LABEL'];
		$ErrorMsg = array();		
		if(!preg_match('/^[a-zA-Z0-9 ]+$/', $txtProjectName)){
			$ErrorMsg["txtProjectName"] = "Special characters are not allowed";
		}
                if( ($projectId == '') OR ( ( trim($projectNameOld) != trim($txtProjectName)) && $projectId != '' ) ) {
                    $qryprojectchk = "SELECT PROJECT_NAME FROM ".RESI_PROJECT." 
                        WHERE 
                            PROJECT_NAME = '".$txtProjectName."' 
                            AND BUILDER_ID = '".$builderId."' 
                            AND LOCALITY_ID = '".$localityId."' 
                            AND CITY_ID = '".$cityId."'";
                    $resprojectchk = mysql_query($qryprojectchk);
                    if(mysql_num_rows($resprojectchk) >0){
                         $ErrorMsg["txtProjectName"] = "Project with same name already exist.";
                    }
                }
	     /*if(empty($display_order) || $display_order < 1 || $display_order > 999 || ($display_order > 15 && $display_order < 101))
	     {
	         $ErrorMsg["display_order"] = "Please put in display order (1-15 for city page), (101-998 for locality page), 999 for default";
	     }
	     elseif ($display_order >= 1 && $display_order <= 15) {
	         $numProjects = getNumProjectsUnderDisplayOrder(15, $cityId, $projectId);
	         if ($numProjects >= 15) {
	             $ErrorMsg["display_order"] = "Already $numProjects projects have been assigned city page level editorial priority. Please edit them out of the range (1-15) first.";
	         }
	     }*/

       if( $Status == 'Under Construction' ) {
           $yearExp = explode("-",$launchDt);
           if( $yearExp[0] == date("Y") ) {
               if( intval($yearExp[1]) > intval(date("m"))) {
                 $ErrorMsg['launchDate'] = "Launch date should not be greater than current month in case of Under construction project.";
               }    
           } 
           else if (intval($yearExp[0]) > intval(date("Y")) ) {
               $ErrorMsg['launchDate'] = "Launch date should not be greater than current month in case of Under construction project.";
           }
       }
       if($township == '')
           $township = null;
       if($powerBackup == '')
           $powerBackup = null;
       $smarty->assign("projectTypeOld",$_REQUEST['project_type_hidden']);
       $smarty->assign("ErrorMsgType", $ErrorMsgType);
       $smarty->assign("ErrorMsg", $ErrorMsg);
       if(count($ErrorMsg)>0) {
            // Do Nothing
       }
       else {
            $app = '';
            if($application == 'app_form') {
               $app = $app_form;
            }
            else if($application == 'app_form_pdf') {
                $dir = "application_form/";
                $pdf_path = $dir.str_replace(" ","",$txtProjectName).$_FILES['app_pdf']['name'];
                $move = move_uploaded_file($_FILES['app_pdf']['tmp_name'],$pdf_path);
                if($move == TRUE) {
                    $app = $pdf_path;
                }
            }
           
            
           /*code for comment save in saperate comment table**/
            $arrCommentTypeValue = array();
            if( $txtProjectRemark != '' ) {
                $arrCommentTypeValue['Project'] = $txtProjectRemark;
            }
            if( $txtCallingRemark != '' ) {
                $arrCommentTypeValue['Calling'] = $txtCallingRemark;
            }
            if( $txtAuditRemark != '' ) {
                $arrCommentTypeValue['Audit'] = $txtAuditRemark;
            }
            if( $fieldSurveyRemark != '' ) {
                $arrCommentTypeValue['FieldSurvey'] = $fieldSurveyRemark;
            }
            if( $secondaryRemark != '' ) {
                $arrCommentTypeValue['Secondary'] = $secondaryRemark;
            }
             /*end code for comment save in saperate comment table*/
            $arrInsertUpdateProject = array();
            $arrInsertUpdateProject['project_id'] =$projectId;
            $arrInsertUpdateProject['project_name'] =$projName;
            $arrInsertUpdateProject['builder_id'] = $builderId;
            $arrInsertUpdateProject['locality_id'] = $localityId;
            $arrInsertUpdateProject['project_description'] = $txtProjectDescription;
            $arrInsertUpdateProject['comments'] = $comments;
            $arrInsertUpdateProject['project_address'] = $txtAddress;
            $arrInsertUpdateProject['latitude'] = $txtProjectLattitude;
            $arrInsertUpdateProject['longitude'] = $txtProjectLongitude;
            $arrInsertUpdateProject['display_order'] = $DisplayOrder;
            $arrInsertUpdateProject['status'] = $Active;
            $arrInsertUpdateProject['project_url'] = $txtProjectURL;
            $arrInsertUpdateProject['price_disclaimer'] = $txtDisclaimer;
            $arrInsertUpdateProject['pre_launch_date'] = $pre_launch_date;
            $arrInsertUpdateProject['launch_date'] = $eff_date_to;
            $arrInsertUpdateProject['source_of_information'] = $txtProjectSource;
            $arrInsertUpdateProject['youtube_video'] = $youtube_link;
            $arrInsertUpdateProject['application_form'] =  $app;
            $arrInsertUpdateProject['approvals'] = $approvals;
            $arrInsertUpdateProject['project_size'] = $project_size;
            $arrInsertUpdateProject['power_backup_type_id'] = $powerBackup;
            $arrInsertUpdateProject['project_type_id'] = $project_type;
            $arrInsertUpdateProject['architect_name'] = $architect;
            $arrInsertUpdateProject['power_backup_capacity'] = $power_backup_capacity;
            $arrInsertUpdateProject['promised_completion_date'] = $eff_date_to_prom;
            $arrInsertUpdateProject['residential_flag'] = $residential;
            $arrInsertUpdateProject['township_id'] = $township;
            $arrInsertUpdateProject['open_space'] = $open_space;
            $arrInsertUpdateProject['project_status_id'] = $Status;
            $arrInsertUpdateProject['should_display_price'] = $shouldDisplayPrice;
            $arrInsertUpdateProject['expected_supply_date'] = $exp_launch_date;
            $arrInsertUpdateProject['display_order'] = $display_order;
            $arrInsertUpdateProject['updated_by'] = $_SESSION['adminId'];
            $arrOx = array();
	    echo "<pre>";
            print_r($arrInsertUpdateProject);die;
           // $arrOx = 
           $returnProject = ResiProject::create_or_update($arrInsertUpdateProject);
           if( isset($_POST['bank_list']) ) {
               ProjectBanks::projectBankDeleteInsert($_POST['bank_list'],$returnProject->project_id);
           } 
           if ($projectId == '')
           {
               if( $returnProject->project_id ) {
                 CommentsHistory::insertUpdateComments($returnProject->project_id, $arrCommentTypeValue, 'newProject');
                 header("Location:project_img_add.php?projectId=".$returnProject->project_id);
               }
            }
            else
            {
                $ProjectDetail = ResiProject::virtual_find($projectId);
                CommentsHistory::insertUpdateComments($projectId, $arrCommentTypeValue, $ProjectDetail->project_stage_id);
                if( $txtProjectURL != $txtProjectURLOld && $txtProjectURLOld != '' ) {
                   insertUpdateInRedirectTbl($txtProjectURL,$txtProjectURLOld);
                }
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
elseif ($projectId!='') {
    $ProjectDetail = ResiProject::virtual_find($projectId,array('get_extra_scope'=>true));
    $smarty->assign("txtProjectName", stripslashes($ProjectDetail->project_name));
    $smarty->assign("txtAddress", stripslashes($ProjectDetail->project_address));
    $smarty->assign("txtProjectDescription", stripslashes($ProjectDetail->project_description));
    $smarty->assign("txtAddress", stripslashes($ProjectDetail->project_address));
    $smarty->assign("builderId", stripslashes($ProjectDetail->builder_id));

           }
           
           if( $Status == 'Pre Launch' && $preLaunchDt == '' ) {
               $ErrorMsg['preLaunchDate'] = "Pre Launch date cant empty";
           }
           
           if( $Status == 'Pre Launch' && $launchDt != '' ) {
               $ErrorMsg['launchDate'] = "Launch date should be blank/zero";
           }
          
           if( $Status == 'Occupied' || $Status == 'Ready for Possession' ) {
               $yearExp = explode("-",$promisedDt);
             
               if( $yearExp[0] == date("Y") ) {
                   if( intval($yearExp[1]) > intval(date("m"))) {
                     $ErrorMsg['CompletionDateGreater'] = "Completion date cannot be greater current month";
                   }    
               } 
               else if (intval($yearExp[0]) > intval(date("Y")) ) {
                   $ErrorMsg['CompletionDateGreater'] = "Completion date cannot be greater current month";
               }
           }
           
           if( $Status == 'Under Construction' ) {
               
               $yearExp = explode("-",$launchDt);
               if( $yearExp[0] == date("Y") ) {
                   if( intval($yearExp[1]) > intval(date("m"))) {
                     $ErrorMsg['launchDate'] = "Launch date should not be greater than current month in case of Under construction project.";
                   }    
               } 
               else if (intval($yearExp[0]) > intval(date("Y")) ) {
                   $ErrorMsg['launchDate'] = "Launch date should not be greater than current month in case of Under construction project.";
               }
           }
           
           $smarty->assign("projectTypeOld",$_REQUEST['project_type_hidden']);
           $smarty->assign("ErrorMsgType", $ErrorMsgType);
           $smarty->assign("ErrorMsg", $ErrorMsg);
           //die;
           
	   if(count($ErrorMsg)>0) {
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
                        $dir = "application_form/";
                        $pdf_path =	$dir.str_replace(" ","",$txtProjectName).$_FILES['app_pdf']['name'];
                        $move = move_uploaded_file($_FILES['app_pdf']['tmp_name'],$pdf_path);
                        if($move == TRUE)
                        {
                            $app = $pdf_path;
                        }
                    }

                    $price1 = '';
                    if($price_list_chk == 'price_list')
                    {
                            $price1 = $price_list;
                    }
                    else if($price_list_chk == 'price_list_pdf')
                    {
                        $dir = "price_list/";
                        $pdf_path = $dir.str_replace(" ","",$txtProjectName).$_FILES['price_list_pdf']['name'];
                        $move = move_uploaded_file($_FILES['price_list_pdf']['tmp_name'],$pdf_path);
                        if($move == TRUE)
                        {
                            $price1	= $pdf_path;
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
                        $dir = "payment_plan/";
                        $pdf_path = $dir.str_replace(" ","",$txtProjectName).$_FILES['payment_pdf']['name'];
                        $move = move_uploaded_file($_FILES['payment_pdf']['tmp_name'],$pdf_path);
                        if($move == TRUE)
                        {
                            $payment1 = $pdf_path;
                        }

                    }
		   /*code for comment save in saperate comment table**/
                    $arrCommentTypeValue = array();
                    if( $txtProjectRemark != '' ) {
                        $arrCommentTypeValue['Project'] = $txtProjectRemark;
                    }
                    if( $txtCallingRemark != '' ) {
                        $arrCommentTypeValue['Calling'] = $txtCallingRemark;
                    }
                    if( $txtAuditRemark != '' ) {
                        $arrCommentTypeValue['Audit'] = $txtAuditRemark;
                    }
                    if( $fieldSurveyRemark != '' ) {
                        $arrCommentTypeValue['FieldSurvey'] = $fieldSurveyRemark;
                    }
                    if( $secondaryRemark != '' ) {
                        $arrCommentTypeValue['Secondary'] = $secondaryRemark;
                    }
                     /*end code for comment save in saperate comment table*/
		   if ($projectId == '')
		   {
                        $projectId = InsertProject($projName, $builderId, $cityId,$suburbId,$localityId,$txtProjectDescription,$txtAddress,$txtProjectDesc,$txtProjectSource,$project_type,$txtProjectLocation,$txtProjectLattitude,$txtProjectLongitude,$txtProjectMetaTitle,$txtMetaKeywords,$txtMetaDescription,$DisplayOrder,$Active,$Status,$txtProjectURL,$Featured,$txtDisclaimer,$payment1,$no_of_towers,$no_of_flats,$pre_launch_date,$exp_launch_date,$eff_date_to,$special_offer,$display_order,$youtube_link,$bank_list,$price1,$app,$approvals,$project_size,$no_of_lift,$powerBackup,$architect,$offer_heading,$offer_desc,$BuilderName,$power_backup_capacity,$no_of_villa,$eff_date_to_prom,$residential,$township,$no_of_plot,$open_space,$Booking_Status,$shouldDisplayPrice,$launchedUnits,$reasonUnlaunchedUnits,$identifyTownShip);
                        
		                //create new project url
		                $txtProjectURL = createProjectURL($cityName, $localityName, $BuilderName, $txtProjectName, $projectId);
		                $updateQuery = 'UPDATE '.RESI_PROJECT.' set PROJECT_URL="'.$txtProjectURL.'" where PROJECT_ID='.$projectId;
		                $resUrl = mysql_query($updateQuery) or die(mysql_error());

                        CommentsHistory::insertUpdateComments($projectId, $arrCommentTypeValue, 'newProject');
                        
                        header("Location:project_img_add.php?projectId=".$projectId);
                    }
                    else
                    {
                        $txtProjectURL = createProjectURL($cityName, $localityName, $BuilderName, $txtProjectName, $projectId);
                        $projectId = UpdateProject($projName, $builderId, $cityId,$suburbId,$localityId,$txtProjectDescription,$txtAddress,$txtProjectDesc,$txtProjectSource,$project_type,$txtProjectLocation,$txtProjectLattitude,$txtProjectLongitude,$txtProjectMetaTitle,$txtMetaKeywords,$txtMetaDescription,$DisplayOrder,$Active,$Status,$txtProjectURL,$Featured,$txtDisclaimer,$payment1,$no_of_towers,$no_of_flats,$pre_launch_date,$exp_launch_date,$eff_date_to,$special_offer,$display_order,$youtube_link,$bank_list,$price1,$app,$approvals,$project_size,$no_of_lift,$powerBackup,$architect,$offer_heading,$offer_desc,$BuilderName,$power_backup_capacity,$no_of_villa,$eff_date_to_prom,$projectId,$residential,$township,$no_of_plot,$open_space,$Booking_Status,$shouldDisplayPrice,$launchedUnits,$reasonUnlaunchedUnits,$identifyTownShip);
                        
                        $ProjectDetail 	= ProjectDetail($projectId);
                        CommentsHistory::insertUpdateComments($projectId, $arrCommentTypeValue, $ProjectDetail[0]['PROJECT_STAGE']);
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
elseif ($projectId!='')
	{
		
		$ProjectDetail 	= ProjectDetail($projectId);
		 $smarty->assign("txtProjectName", stripslashes($ProjectDetail[0]['PROJECT_NAME']));
                 $smarty->assign("projectNameOld", stripslashes($ProjectDetail[0]['PROJECT_NAME']));
		 $smarty->assign("txtAddress", stripslashes($ProjectDetail[0]['PROJECT_ADDRESS']));
		 $smarty->assign("txtProjectDescription", stripslashes($ProjectDetail[0]['PROJECT_DESCRIPTION']));
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
		$smarty->assign("display_order", $ProjectDetail[0]['DISPLAY_ORDER']);
		$smarty->assign("youtube_link", stripslashes($ProjectDetail[0]['YOUTUBE_VIDEO']));
		$smarty->assign("price_list", stripslashes($ProjectDetail[0]['PRICE_LIST']));
		$smarty->assign("app_form", stripslashes($ProjectDetail[0]['APPLICATION_FORM']));
		$smarty->assign("txtProjectDesc", stripslashes($ProjectDetail[0]['OPTIONS_DESC']));
		$smarty->assign("txtSourceofInfo", stripslashes($ProjectDetail[0]['SOURCE_OF_INFORMATION']));
		$smarty->assign("project_type", stripslashes($ProjectDetail[0]['PROJECT_TYPE_ID']));
                $smarty->assign("projectTypeOld", stripslashes($ProjectDetail[0]['PROJECT_TYPE_ID']));
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
                $smarty->assign("exp_launch_date", stripslashes($ProjectDetail[0]['EXPECTED_SUPPLY_DATE']));
		$smarty->assign("no_of_villa", stripslashes($ProjectDetail[0]['NO_OF_VILLA']));
		$smarty->assign("no_of_plot", stripslashes($ProjectDetail[0]['NO_OF_PLOTS']));
		$smarty->assign("open_space", stripslashes($ProjectDetail[0]['OPEN_SPACE']));
		$smarty->assign("Booking_Status", stripslashes($ProjectDetail[0]['BOOKING_STATUS']));
		$smarty->assign("shouldDisplayPrice", stripslashes($ProjectDetail[0]['SHOULD_DISPLAY_PRICE']));
		$smarty->assign("launchedUnits", stripslashes($ProjectDetail[0]['LAUNCHED_UNITS']));
		$smarty->assign("reasonUnlaunchedUnits", stripslashes($ProjectDetail[0]['REASON_UNLAUNCHED_UNITS']));
                $smarty->assign("identifyTownShip", stripslashes($ProjectDetail[0]['SKIP_UPDATION_CYCLE']));

		if(isset($ProjectDetail[0]['BANK_LIST']))
		{
			$bank_arr = explode(",",$ProjectDetail[0]['BANK_LIST']);
		}
		else
		{
			$bank_arr = '';
		}

		$smarty->assign("bank_arr", $bank_arr);

		$suburbSelect = Suburb::SuburbArr($ProjectDetail[0]['CITY_ID'], $ProjectDetail[0]['LOCALITY_ID']);
		 $smarty->assign("suburbSelect", $suburbSelect);

    /****start city locality and suburb**********/
    $smarty->assign("localityId", $ProjectDetail[0]['LOCALITY_ID']);
    $localityDetail = Locality::getLocalityById($ProjectDetail[0]['LOCALITY_ID']); 
    $suburbDetail = Suburb::getSuburbById($localityDetail[0]->suburb_id);
    $suburbSelect =  Suburb::SuburbArr($suburbDetail[0]->city_id);
    $smarty->assign("suburbSelect", $suburbSelect);
    $smarty->assign("suburbId", $localityDetail[0]->suburb_id);
    $smarty->assign("cityId", $suburbDetail[0]->city_id);
    $localitySelect =  Locality::localityList($localityDetail[0]->suburb_id);
    $smarty->assign("getLocalityBySuburb", $localitySelect);
   /****end city locality and suburb**********/
    $smarty->assign("txtProjectLattitude", stripslashes($ProjectDetail[0]['LATITUDE']));
    $smarty->assign("txtProjectLongitude", stripslashes($ProjectDetail[0]['LONGITUDE']));
    $smarty->assign("DisplayOrder", stripslashes($ProjectDetail[0]['DISPLAY_ORDER']));
    $smarty->assign("Active", stripslashes($ProjectDetail[0]['STATUS']));
    $smarty->assign("Status", stripslashes($ProjectDetail[0]['PROJECT_STATUS_ID']));
    $smarty->assign("txtProjectURL", stripslashes($ProjectDetail[0]['PROJECT_URL']));
    $smarty->assign("txtProjectURLOld", stripslashes($ProjectDetail[0]['PROJECT_URL']));
    $smarty->assign("txtDisclaimer", stripslashes($ProjectDetail[0]['PRICE_DISCLAIMER']));
    $smarty->assign("eff_date_to", stripslashes($ProjectDetail[0]['LAUNCH_DATE']));
    $smarty->assign("display_order", $ProjectDetail[0]['DISPLAY_ORDER']);
    $smarty->assign("youtube_link", $ProjectDetail[0]['YOUTUBE_VIDEO']);
    $smarty->assign("app_form", $ProjectDetail[0]['APPLICATION_FORM']);
    $smarty->assign("txtSourceofInfo", $ProjectDetail[0]['SOURCE_OF_INFORMATION']);
    $smarty->assign("project_type", $ProjectDetail[0]['PROJECT_TYPE_ID']);
    $smarty->assign("projectTypeOld", $ProjectDetail[0]['PROJECT_TYPE_ID']);
    $smarty->assign("approvals", $ProjectDetail[0]['APPROVALS']);
    $smarty->assign("project_size", $ProjectDetail[0]['PROJECT_SIZE']);
    $smarty->assign("power_backup_capacity", $ProjectDetail[0]['POWER_BACKUP_CAPACITY']);
    $smarty->assign("powerBackup", $ProjectDetail[0]['POWER_BACKUP']);
    $smarty->assign("architect", $ProjectDetail[0]['ARCHITECT_NAME']);
    $smarty->assign("residential", $ProjectDetail[0]['RESIDENTIAL_FLAG']);
    $smarty->assign("township", $ProjectDetail[0]['TOWNSHIP_ID']);
    $smarty->assign("pre_launch_date", $ProjectDetail[0]['PRE_LAUNCH_DATE']);
    $smarty->assign("exp_launch_date", $ProjectDetail[0]['EXPECTED_SUPPY_DATE']);
    $smarty->assign("open_space", $ProjectDetail[0]['OPEN_SOURCE']);
    $smarty->assign("shouldDisplayPrice", $ProjectDetail[0]['SHOULD_DISPLAY_PRICE']);
    $smarty->assign("eff_date_to_prom", $ProjectDetail[0]['PROMISED_COMPLETION_DATE']);
    $smarty->assign("comments", $ProjectDetail[0]['COMMENTS']);
    $smarty->assign("txtProjectLocation", $txtProjectLocation);

  /*  if( isset($ProjectDetail['BANK_LIST']) )
        $bank_arr = explode(",",$ProjectDetail[0]['BANK_LIST']);
    else
        $bank_arr = '';*/
    $bank_arr = array(1,2);
    $smarty->assign("bank_arr", $bank_arr);
 }

function getNumProjectsUnderDisplayOrder($displayOrder, $cityId, $projectId) {
    $numProjects = 0;
    $qry = "SELECT count(*) as numProjects 
        FROM 
        resi_project 
        WHERE display_order <= $displayOrder and city_id = $cityId 
            and project_id != $projectId and version = 'cms'";
    $res = mysql_query($qry);
    while($data = mysql_fetch_array($res))
    {
        $numProjects = $data['numProjects'];
    }
    return $numProjects;
}
         
$userDepartment = $_SESSION['DEPARTMENT'];
$smarty->assign("userDepartment", $userDepartment);

if( $projectId != '' ) {
     /******code for project comment in seperate table*****/
    $ProjectDetail 	= ProjectDetail($projectId);
    $cycleId = $ProjectDetail[0]['PROJECT_STAGE'];
    $projectComments = CommentsHistory::getCommentHistoryByProjectIdCycleId($projectId, $cycleId);
    $smarty->assign("projectComments", $projectComments);
    $projectOldComments = CommentsHistory::getOldCommentHistoryByProjectId($projectId);
    $smarty->assign("projectOldComments", $projectOldComments);
    /******end code for project comment in seperate table*****/
}
         if( $projectId != '' ) {
              /******code for project comment in seperate table*****/
                $ProjectDetail 	= ProjectDetail($projectId);
                $cycleId = $ProjectDetail[0]['PROJECT_STAGE'];
                $projectComments = CommentsHistory::getCommentHistoryByProjectIdCycleId($projectId, $cycleId);
                $smarty->assign("projectComments", $projectComments);
                
                $projectOldComments = CommentsHistory::getOldCommentHistoryByProjectId($projectId);
                $smarty->assign("projectOldComments", $projectOldComments);
                /******end code for project comment in seperate table*****/
         }
?>
