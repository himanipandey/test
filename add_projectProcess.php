<?php        
$BuilderDataArr	= ResiBuilder::BuilderEntityArr();
$CityDataArr = City::CityArr();
$ProjectTypeArr	= ResiProjectType::ProjectTypeArr();
$BankListArr = BankList::arrBank();
$projectStatus = ResiProject::projectStatusMaster();
$allTownships = Townships::getAllTownships();
$getPowerBackupTypes = PowerBackupTypes::getPowerBackupTypes();

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
            $txtProjectDescription = trim($_POST['txtProjectDescription']);
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
               $ErrorMsg["txtProjectName"] = "Project name should not be blank.";
            }elseif(!preg_match('/^[a-zA-Z0-9 ]+$/', $txtProjectName)){
               $ErrorMsg["txtProjectName"] = "Special characters are not allowed";
            }
           
            $projectChk = ResiProject::projectAlreadyExist($txtProjectName, $builderId, $localityId);
            
            //if( $projectId == '' ) {
               if(count($projectChk) >0)
               {
                    $ErrorMsg["txtProjectName"] = "Project already exist.";
               }
           // }
            $projectUrlChk = ResiProject::projectUrlExist($txtProjectURL, $projectId);
            if( count($projectUrlChk)>0 ) {
              $ErrorMsg["txtProjectUrlDuplicate"] = "This URL already exist.";
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

    /****start city locality and suburb**********/
    $smarty->assign("localityId", $ProjectDetail->locality_id);
    $localityDetail = Locality::getLocalityById($ProjectDetail->locality_id); 
    $suburbDetail = Suburb::getSuburbById($localityDetail[0]->suburb_id);
    $suburbSelect =  Suburb::SuburbArr($suburbDetail[0]->city_id);
    $smarty->assign("suburbSelect", $suburbSelect);
    $smarty->assign("suburbId", $localityDetail[0]->suburb_id);
    $smarty->assign("cityId", $suburbDetail[0]->city_id);
    $localitySelect =  Locality::localityList($localityDetail[0]->suburb_id);
    $smarty->assign("getLocalityBySuburb", $localitySelect);
   /****end city locality and suburb**********/
    $smarty->assign("txtProjectLattitude", stripslashes($ProjectDetail->latitude));
    $smarty->assign("txtProjectLongitude", stripslashes($ProjectDetail->longitude));
    $smarty->assign("DisplayOrder", stripslashes($ProjectDetail->display_order));
    $smarty->assign("Active", stripslashes($ProjectDetail->status));
    $smarty->assign("Status", stripslashes($ProjectDetail->project_status_id));
    $smarty->assign("txtProjectURL", stripslashes($ProjectDetail->project_url));
    $smarty->assign("txtProjectURLOld", stripslashes($ProjectDetail->project_url));
    $smarty->assign("txtDisclaimer", stripslashes($ProjectDetail->price_disclaimer));
    $smarty->assign("eff_date_to", stripslashes($ProjectDetail->launch_date));
    $smarty->assign("display_order", $ProjectDetail->display_order);
    $smarty->assign("youtube_link", stripslashes($ProjectDetail->youtube_video));
    $smarty->assign("app_form", stripslashes($ProjectDetail->application_form));
    $smarty->assign("txtSourceofInfo", stripslashes($ProjectDetail->source_of_information));
    $smarty->assign("project_type", stripslashes($ProjectDetail->project_type_id));
    $smarty->assign("projectTypeOld", stripslashes($ProjectDetail->project_type_id));
    $smarty->assign("approvals", stripslashes($ProjectDetail->approvals));
    $smarty->assign("project_size", stripslashes($ProjectDetail->project_size));
    $smarty->assign("power_backup_capacity", stripslashes($ProjectDetail->power_backup_capacity));
    $smarty->assign("powerBackup", stripslashes($ProjectDetail->POWER_BACKUP));
    $smarty->assign("architect", stripslashes($ProjectDetail->architect_name));
    $smarty->assign("residential", stripslashes($ProjectDetail->residential_flag));
    $smarty->assign("township", stripslashes($ProjectDetail->township_id ));
    $smarty->assign("pre_launch_date", stripslashes($ProjectDetail->pre_launch_date));
    $smarty->assign("exp_launch_date", stripslashes($ProjectDetail->expected_supply_date));
    $smarty->assign("open_space", stripslashes($ProjectDetail->open_space));
    $smarty->assign("shouldDisplayPrice", stripslashes($ProjectDetail->should_display_price));
    $smarty->assign("eff_date_to_prom", stripslashes($ProjectDetail->promised_completion_date));
    $smarty->assign("comments", stripslashes($ProjectDetail->comments));
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
    $qry = "SELECT count(*) as numProjects FROM resi_project WHERE display_order <= $displayOrder and city_id = $cityId and project_id != $projectId";
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
?>
