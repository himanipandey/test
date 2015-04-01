<?php

$BuilderDataArr = ResiBuilder::ProjectSearchBuilderEntityArr();
$CityDataArr = City::CityArr();

$BankListArr = BankList::arrBank();
$projectStatus = ResiProject::projectStatusMaster();
$allTownships = Townships::getAllTownships();
$allAuthorities = HousingAuthorities::getAllAuthorities();
$getPowerBackupTypes = PowerBackupTypes::getPowerBackupTypes();

//die("");
include_once('./function/locality_functions.php');
$smarty->assign("BuilderDataArr", $BuilderDataArr);
$smarty->assign("CityDataArr", $CityDataArr);

$smarty->assign("BankListArr", $BankListArr);
$smarty->assign("projectStatus", $projectStatus);
$smarty->assign("allTownships", $allTownships);
$smarty->assign("allAuthorities", $allAuthorities);
$smarty->assign("getPowerBackupTypes", $getPowerBackupTypes);
$smarty->assign("display_order", 999);
/* * ********************************** */
$sourcepath = array();
$destinationpath = array();
$flag = 0;
$projectFolderCreated = 0;
if (!isset($_REQUEST['projectId']))
    $_REQUEST['projectId'] = '';
$projectId = $_REQUEST['projectId'];
$smarty->assign("projectId", $projectId);
if (!isset($_REQUEST['preview']))
    $_REQUEST['preview'] = '';
$preview = $_REQUEST['preview'];
$smarty->assign("preview", $preview);
$bookingStatuses = ResiProject::find_by_sql("select * from master_booking_statuses");
$smarty->assign("bookingStatuses", $bookingStatuses);




if (isset($_POST['btnSave']) || isset($_POST['btnExit'])) {
    if ($_POST['btnSave'] == "Next" || $_POST['btnSave'] == "Save") {
        $txtProjectName = replaceSpaces(trim($_POST['txtProjectName']));
        $builderId = trim($_POST['builderId']);
        $cityId = trim($_POST['cityId']);
        $suburbId = trim($_POST['suburbId']);
        $localityId = trim($_POST['localityId']);
        $txtProjectDescription = trim($_POST['txtProjectDesc']);
        $txtProjectOldDescription = trim($_POST['txtProjectOldDesc']);
        $content_flag = trim($_POST['content_flag']);
        $comments = trim($_POST['comments']);
        $txtProjectRemark = trim($_POST['txtProjectRemark']);
        $txtProjectRemarkDisplay = trim($_POST['txtProjectRemarkDisplay']);
        $txtAddress = trim($_POST['txtProjectAddress']);
        $txtProjectSource = trim($_POST['txtProjectSource']);
        $project_type = trim($_POST['project_type']);
        $txtProjectLocation = trim($_POST['txtProjectLocation']);
        $txtProjectLattitude = trim($_POST['txtProjectLattitude']);
        $txtProjectLongitude = trim($_POST['txtProjectLongitude']);

        $DisplayOrder = '';
        $Active = trim($_POST['Active']);
        $Status = trim($_POST['Status']);
        $txtProjectURL = trim($_POST['txtProjectURLOld']);
        $txtProjectURLOld = trim($_POST['txtProjectURLOld']);
        $txtDisclaimer = trim($_POST['txtDisclaimer']);

        $pre_launch_date = trim($_POST['pre_launch_date']);
        $exp_launch_date = trim($_POST['exp_launch_date']);
        $eff_date_to = trim($_POST['eff_date_to']);
        $display_order = PROJECT_MAX_PRIORITY;
        $oldbuilderId = trim($_POST['oldbuilderId']);

        $application = trim($_POST['application']);
        $app_form = trim($_POST['app_form']);

        if (isset($_POST['app_form_pdf']))
            $app_form_pdf = trim($_POST['app_form_pdf']);
        else
            $app_form_pdf = '';
        $approvals = trim($_POST['approvals']);
        $project_size = trim($_POST['project_size']);
        $powerBackup = trim($_POST['powerBackup']);
        $architect = trim($_POST['architect']);
        $power_backup_capacity = trim($_POST['power_backup_capacity']);
        $eff_date_to_prom = trim($_POST['eff_date_to_prom']);

        $residential = (trim($_POST['residential'])) ? $_POST['residential'] : 'Residential'; //setting up defualt value if empty
        $township = trim($_POST['township']);
        $authority = trim($_POST['authority']);
        $projName = replaceSpaces(trim($_POST['txtProjectName']));
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
        $txtSecondaryAuditRemark = trim($_POST['txtSecondaryAuditRemark']);
        $txtSecondaryAuditRemarkDisplay = trim($_POST['txtSecondaryAuditRemarkDisplay']);
        $special_offer = trim($_POST["special_offer"]);
        $offer_heading = trim($_POST["offer_heading"]);
        $offer_desc = trim($_POST["offer_desc"]);
//        $skipUpdationCycle = $_POST["skipUpdationCycle"];
        $updationCycleIdOld = $_POST["updationCycleIdOld"];
        $numberOfTowers = $_POST["numberOfTowers"];
        $completionDate = $_POST["completionDate"];
        $redevelopmentProject = ($_POST["redevelopmentProject"]) ? 1 : 0;
        $govtAuthorityProject = ($_POST["govtAuthorityProject"]) ? 1 : 0;
        $txtSkipUpdationRemark = $_POST["txtSkipUpdationRemark"];

        /* jitendra pathak */

        $constructionContractor = $_POST["ConstructionContractor"];
        $maintenanceContractor = $_POST["MaintenanceContractor"];
        $landscapeArchitect = $_POST["LandscapeArchitect"];

        $constructionContractorId = $_POST["ConstructionContractorId"];
        $maintenanceContractorId = $_POST["MaintenanceContractorId"];
        $landscapeArchitectId = $_POST["LandscapeArchitectId"];

        /* END */


        $skip_b2b = $_REQUEST['skip_b2b'];
        /*         * *************Query for suburb selected*********** */
        if ($_POST['cityId'] != '') {
            $suburbSelect = Suburb::SuburbArr($_POST['cityId']);
            $smarty->assign("suburbSelect", $suburbSelect);

            if ($suburbId != '')
                $suburbId = $suburbId;
            else
                $suburbId = '';

            $localitySelect = Locality::getLocalityByCity($_POST['cityId']);
            foreach ($localitySelect as $value) {
                $getLocalityBySuburb[$value->locality_id] = $value->label;
            }
            $smarty->assign("getLocalityBySuburb", $getLocalityBySuburb);
        }
        /*         * *************end Query for Locality selected*********** */

        /* jitendra pathak */

        $smarty->assign("constructionContractor", $constructionContractor);
        $smarty->assign("constructionContractorId", $constructionContractorId);

        $smarty->assign("maintenanceContractor", $maintenanceContractor);
        $smarty->assign("maintenanceContractorId", $maintenanceContractorId);

        $smarty->assign("landscapeArchitect", $landscapeArchitect);
        $smarty->assign("landscapeArchitectId", $landscapeArchitectId);

        /* END */
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

        if (isset($_POST['bank_list']))
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
        $smarty->assign("authority", $_POST['authority']);
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

//        $smarty->assign("skipUpdationCycle", $skipUpdationCycle);
        $smarty->assign("updationCycleIdOld", $updationCycleIdOld);
        $smarty->assign("numberOfTowers", $numberOfTowers);
        $smarty->assign("completionDate", $completionDate);
        $smarty->assign("redevelopmentProject", $redevelopmentProject);
        $smarty->assign("govtAuthorityProject", $govtAuthorityProject);
        $smarty->assign("txtSkipUpdationRemark", $txtSkipUpdationRemark);
        $smarty->assign("skip_b2b", $skip_b2b);

        /*         * *********Folder name********* */
        if (!empty($builderId)) {
            $builderDetail = ResiBuilder::getBuilderById($builderId);
            $BuilderName = $builderDetail->builder_name;
        }
        if (!empty($localityId)) {
            $localityDetail = Locality::getLocalityById($localityId);
            $localityName = $localityDetail->label;
        }
        if (!empty($cityId)) {
            $cityDetail = City::getCityById($cityId);
            $cityName = $cityDetail->label;
        }
        $ErrorMsg = array();
        if (empty($txtProjectName)) {
            $ErrorMsg["txtProjectName"] = "Project name must not be blank.";
        } elseif (!preg_match('/^[a-zA-Z0-9 ]+$/', $txtProjectName)) {
            $ErrorMsg["txtProjectName"] = "Special characters are not allowed.";
        }
        if (empty($builderId)) {
            $ErrorMsg["txtBuilder"] = "Builder name must be selected.";
        }
        if (empty($cityId)) {
            $ErrorMsg["txtCity"] = "City must be selected.";
        }
        if (empty($localityId)) {
            $ErrorMsg["txtLocality"] = "Locality must be selected.";
        }
        if (empty($suburbId)) {
            $ErrorMsg["txtSuburbs"] = "Suburbs must be selected.";
        }
        if (empty($txtAddress)) {
            $ErrorMsg["txtAddress"] = "Please enter project address.";
        }
        if (empty($txtProjectDescription)) {
            $ErrorMsg["txtDesc"] = "Please enter Option description.";
        }
        if (empty($txtProjectSource)) {
            $ErrorMsg["txtSource"] = "Please enter project source of information.";
        }
        if (empty($project_type)) {
            $ErrorMsg["txtProject_type"] = "Please select project type.";
        }
        if (!empty($txtProjectLattitude) && ($txtProjectLattitude < 0 || $txtProjectLattitude > 90)) {
            $ErrorMsg["txtLattitude"] = "Lattitude range should be between 0 to 90.";
        }
        if (!empty($txtProjectLattitude) && !is_numeric($txtProjectLattitude)) {
            $ErrorMsg["txtLattitude"] = "Lattitude value should be numeric.";
        }
        if (!empty($txtProjectLongitude) && ($txtProjectLongitude < -180 || $txtProjectLongitude > 180)) {
            $ErrorMsg["txtLongitude"] = "Longitude range should be between -180 to 180.";
        }
        if (!empty($txtProjectLongitude) && !is_numeric($txtProjectLongitude)) {
            $ErrorMsg["txtLongitude"] = "Lattitude value should be numeric.";
        }
        if (empty($Status)) {
            $ErrorMsg["txtStatus"] = "Please select project status.";
        }
        if (!empty($open_space)) {
            if (!is_numeric($open_space) || $open_space > 100) {
                $ErrorMsg["txtopen_space"] = "Open Space must be numeric and less than 100.";
            }
        }
        if (!empty($project_size)) {
            if (!is_numeric($project_size) || $project_size > 500) {
                $ErrorMsg["txtproject_size"] = "Project size must be numeric and less than 500.";
            }
        }
        if (!empty($power_backup_capacity)) {
            if (!is_numeric($power_backup_capacity) || $power_backup_capacity > 10) {
                $ErrorMsg["txtpower_backup_capacity"] = "Power Backup Capacity must be numeric and less than 10.";
            }
        }
//echo $skipUpdationCycle."<br>".$txtSkipUpdationRemark;
        if ($_REQUEST['skip_b2b'] !=0 && empty($txtSkipUpdationRemark)) {
            $ErrorMsg["txtSkipUpdationCycle"] = "Please enter Remarks for skipping update cycle!.";
        }

        $projectChk = ResiProject::projectAlreadyExist($txtProjectName, $builderId, $localityId, $projectId);

        if (count($projectChk) > 0) {
            $ErrorMsg["txtProjectName"] = "Project already exist.";
        }

        if ($txtProjectName != '') {
            if (!preg_match('/^[a-zA-Z0-9 ]+$/', $txtProjectName)) {
                $ErrorMsg["txtProjectName"] = "Special characters are not allowed";
            }

            if (($projectId == '')) {
                $qryprojectchk = "SELECT rp.PROJECT_NAME FROM " . RESI_PROJECT . " rp
                        inner join locality l on rp.locality_id = l.locality_id
                        inner join suburb s on l.suburb_id = s.suburb_id
                        inner join city c on s.city_id = c.city_id
                    WHERE 
                        rp.PROJECT_NAME = '" . $txtProjectName . "' 
                        AND rp.BUILDER_ID = '" . $builderId . "' 
                        AND rp.LOCALITY_ID = '" . $localityId . "' 
                        AND c.CITY_ID = '" . $cityId . "'
			AND rp.version = 'Cms'";
                $resprojectchk = mysql_query($qryprojectchk);
                if (mysql_num_rows($resprojectchk) > 0) {
                    $ErrorMsg["txtProjectName"] = "Project with same name already exist.";
                }
            }
        }
        $showTypeError = '';
        if ($specialAccessAuth == false) {
            if ($_REQUEST['project_type_hidden'] != '' && $_REQUEST['project_type_hidden'] != 0) {
                if ($project_type != $_REQUEST['project_type_hidden']) {
                    $ErrorMsgType['showTypeError'] = 'You can not update project type!';
                    $ErrorMsg['showTypeError'] = 'error';
                } else {
                    $ErrorMsgType['showTypeError'] = '';
                }
            }
        }
        if ($projectId != '' && $ErrorMsg['showTypeError'] == '') {

            if ($_REQUEST['project_type_hidden'] != '' && $_REQUEST['project_type_hidden'] != 0) {
                if ($project_type != $_REQUEST['project_type_hidden']) {
                    $all_config = mysql_query("select count(options_id) as cnt from resi_project_options where project_id = '$projectId'");
                    if ($all_config) {
                        $all_config = mysql_fetch_object($all_config);
                        $all_config = ($all_config) ? $all_config->cnt : 0;
                        if ($all_config > 0) {
                            $ErrorMsgType['showTypeError'] = 'Before update the Project Type please delete all the config and config dependent data!';
                            $ErrorMsg['showTypeError'] = 'error';
                        } else
                            $ErrorMsgType['showTypeError'] = '';
                    }
                }
            }
        }
        if ($exp_launch_date != '' && $exp_launch_date != '0000-00-00') {
            $retdt = ((strtotime($exp_launch_date) - strtotime(date("Y-m-d"))) / (60 * 60 * 24));
            if ($retdt <= 0) {
                $ErrorMsg['supplyDate'] = 'Expected supply date should be future date!';
            }
        }
        /*         * code for new launch and completion date diff and if In case PROJECT_STATUS = Pre Launch then Pre_launch_date cannot be empty In case PROJECT_STATUS = Occupied or Ready For Possession then  *** */
        $launchDt = $eff_date_to;
        $preLaunchDt = $pre_launch_date;
        if ($launchDt == '0000-00-00')
            $launchDt = '';
        else {
            $exp = explode(" 00:", $launchDt);
            $launchDt = $exp[0];
        }
        if ($preLaunchDt == '0000-00-00')
            $preLaunchDt = '';
        else {
            $exp = explode(" 00:", $preLaunchDt);
            $preLaunchDt = $exp[0];
        }
        if ($eff_date_to_prom == '0000-00-00')
            $eff_date_to_prom = '';
        else {
            $exp = explode(" 00:", $eff_date_to_prom);
            $eff_date_to_prom = $exp[0];
        }
        if ($preLaunchDt != '' && $launchDt != '') {
            $retdt = ((strtotime($launchDt) - strtotime($preLaunchDt)) / (60 * 60 * 24));
            if ($retdt <= 0) {
                $ErrorMsg['launchDateGreater'] = "Launch date to be always greater than Pre Launch date";
            }
        }

        if ($Status == PRE_LAUNCHED_ID_8 && $preLaunchDt == '') {
            $ErrorMsg['preLaunchDate'] = "Pre Launch date cant empty";
        }

        if ($Status == PRE_LAUNCHED_ID_8 && $launchDt != '') {
            $ErrorMsg['launchDate'] = "Launch date should be blank/zero";
        }

        if ($Status == UNDER_CONSTRUCTION_ID_1) {
            $yearExp = explode("-", $launchDt);
            if ($yearExp[0] == date("Y")) {
                if (intval($yearExp[1]) > intval(date("m"))) {
                    $ErrorMsg['launchDate'] = "Launch date should not be greater than current month in case of Under construction project.";
                }
            } else if (intval($yearExp[0]) > intval(date("Y"))) {
                $ErrorMsg['launchDate'] = "Launch date should not be greater than current month in case of Under construction project.";
            }
        }

        if ($completionDate == '0000-00-00')
            $completionDate = '';
        if ($projectId != '' && ($completionDate != '' && $launchDt != '')) {
            $retdt = ((strtotime($completionDate) - strtotime($launchDt)) / (60 * 60 * 24));
            if ($retdt <= 180) {
                $ErrorMsg['launchDate'] = 'Launch date should be atleast 6 month less than completion date: ' . $completionDate;
            }
        }

        if ($launchDt != '' && $eff_date_to_prom != '') {
            $retdt = ((strtotime($eff_date_to_prom) - strtotime($launchDt)) / (60 * 60 * 24));
            if ($retdt <= 180) {
                $ErrorMsg['CompletionDateGreater'] = 'Completion date to be always 6 month greater than launch date';
            }
        }
        if ($preLaunchDt != '' && $eff_date_to_prom != '' && $projectId == '') {
            $retdt = ((strtotime($eff_date_to_prom) - strtotime($preLaunchDt)) / (60 * 60 * 24));
            if ($retdt <= 0) {
                $ErrorMsg['CompletionDateGreater'] = "Completion date to be always greater than Pre Launch date";
            }
        }

        if ($preLaunchDt != '') {
            $retdt = ((strtotime(date('Y-m-d')) - strtotime($preLaunchDt)) / (60 * 60 * 24));
            if ($retdt < 0) {
                $ErrorMsg['preLaunchDate'] = "Pre Launch date should be less or equal to current date";
            }
            /* if($projectId != ''){
              if(projectStageName($projectId)=="UpdationCycle" && (checkAvailablityDate($projectId, $preLaunchDt) || checkListingPricesDate($projectId, $preLaunchDt))) {
              $ErrorMsg['preLaunchDateAvailabilities'] = "Inventory or Prices with effective date before {$preLaunchDt} are present. So can not change the Pre Launch Date.";
              }
              } */
            /* if(checkListingPricesDate($projectId, $preLaunchDt)){
              $ErrorMsg['preLaunchDatePrices'] = "Inventory or Prices with effective date before {$preLaunchDt} are present. So can not change the Pre Launch Date.";
              } */
        }

        if ($launchDt != '') {
            $retdt = ((strtotime(date('Y-m-d')) - strtotime($launchDt)) / (60 * 60 * 24));
            if ($retdt < 0) {
                $ErrorMsg['launchDateGreater'] = "Launch date should be less or equal to current date";
            }
            /* if($projectId != ''){
              if($preLaunchDt == '' && projectStageName($projectId)=="UpdationCycle" && (checkAvailablityDate($projectId, $launchDt) || checkListingPricesDate($projectId, $launchDt) )) {
              $ErrorMsg['launchDateAvailabilities'] = "Inventory or Prices with effective date before {$launchDt} are present. So can not change the Launch Date.";
              }
              } */
            /* if(checkListingPricesDate($projectId, $launchDt)){
              $ErrorMsg['launchDatePrices'] = "Inventory or Prices with effective date before {$launchDt} are present. So can not change the Launch Date.";
              } */
        }
        if (($Status == OCCUPIED_ID_3 || $Status == READY_FOR_POSSESSION_ID_4) && $projectId == '' && $eff_date_to_prom != '') {
            $yearExp = explode("-", $eff_date_to_prom);
            if ($yearExp[0] == date("Y")) {
                if (intval($yearExp[1]) > intval(date("m"))) {
                    $ErrorMsg['CompletionDateGreater'] = "Completion date cannot be greater current month";
                }
            } else if (intval($yearExp[0]) > intval(date("Y"))) {
                $ErrorMsg['CompletionDateGreater'] = "Completion date cannot be greater current month";
            }
        }
        if ($Status == UNDER_CONSTRUCTION_ID_1 && $projectId == '' && $eff_date_to_prom != '') {
            $yearExp = explode("-", $eff_date_to_prom);
            if ($yearExp[0] == date("Y")) {
                if (intval($yearExp[1]) < intval(date("m"))) {
                    $ErrorMsg['CompletionDateGreater'] = "Completion date cannot be less than current month in case of Under Construction Project.";
                }
            } else if (intval($yearExp[0]) < intval(date("Y"))) {
                $ErrorMsg['CompletionDateGreater'] = "Completion date cannot be less than current month in case of Under Construction Project.";
            }
        }
        if ($Status == UNDER_CONSTRUCTION_ID_1 && $projectId != '' && $completionDate != '') {
            $yearExp = explode("-", $completionDate);
            if ($yearExp[0] == date("Y")) {
                if (intval($yearExp[1]) < intval(date("m"))) {
                    $ErrorMsg['CompletionDateGreater'] = "Completion date cannot be less than current month in case of Under Construction Project.";
                }
            } else if (intval($yearExp[0]) < intval(date("Y"))) {
                $ErrorMsg['CompletionDateGreater'] = "Completion date cannot be less than current month in case of Under Construction Project.";
            }
        }

        if (($Status == OCCUPIED_ID_3 || $Status == READY_FOR_POSSESSION_ID_4) && $projectId != '') {
            if ($completionDate == '0000-00-00')
                $completionDate = '';
            $yearExp = explode("-", $completionDate);
            if ($yearExp[0] == date("Y")) {
                if (intval($yearExp[1]) > intval(date("m"))) {
                    $ErrorMsg["CompletionDateGreater"] = "Completion date cannot be greater current month for this status";
                }
            } else if (intval($yearExp[0]) > intval(date("Y"))) {
                $ErrorMsg["CompletionDateGreater"] = "Completion date cannot be greater current month for this status";
            }
        }

        //print_r($ErrorMsg); die();
        //  echo $ErrorMsg['launchDate'];
        //echo $Status ."==". OCCUPIED_ID_3 ." or ". READY_FOR_POSSESSION_ID_4."==>$launchDt";die;
        if ($township == '')
            $township = null;
        if ($authority == '')
            $authority = "0";
        if ($powerBackup == '')
            $powerBackup = null;
        $smarty->assign("projectTypeOld", $_REQUEST['project_type_hidden']);
        $smarty->assign("ErrorMsgType", $ErrorMsgType);
        $smarty->assign("ErrorMsg", $ErrorMsg);
        if (count($ErrorMsg) > 0) {
            // Do Nothing
        } else {

            if ($projectId != '') {//uploading project brouchure
                if(isset($_POST['brochureDel'])){                    
                    $unitImageArr['upload_from_tmp'] = "yes";
                    $unitImageArr['method'] = "DELETE";
                    $unitImageArr['url'] = DOC_SERVICE_URL."/".$_POST['brochureDel'];                    
                    $postArr[0] = $unitImageArr;                    
                    $serviceResponse = writeToImageService($postArr);                    
                }
                if (isset($_FILES['project_brochure'])) {
                    $doc = array();
                    $doc['error'] = $_FILES["project_brochure"]["error"];
                    $doc['type'] = $_FILES["project_brochure"]["type"];
                    $doc['name'] = $_FILES["project_brochure"]["name"];
                    $doc['tmp_name'] = $_FILES["project_brochure"]["tmp_name"];
                    
                    $tmp = array();
                    $tmp['file'] = "@" . $_FILES["project_brochure"]["tmp_name"]. ';filename=' . $_FILES['project_brochure']['name']. ';type=' . $_FILES['project_brochure']['type'];
                    $tmp['objectId'] = $projectId;
                    $tmp['objectType'] = "project";
                    $tmp['documentType'] = "projectBrouchure";
                    $tmp['priority'] = 1;
                    $unitImageArr['upload_from_tmp'] = "yes";
                    $unitImageArr['method'] = "POST";
                    $unitImageArr['url'] = DOC_SERVICE_URL;
                    $unitImageArr['params'] = $tmp;
                    $postArr[0] = $unitImageArr;
                    $serviceResponse = writeToImageService($postArr);
                    
                }
                
                if (isset($serviceResponse[0]->error)) {
                    $ErrorMsg["projectBrouchureError"] = $serviceResponse[0]->error->msg;                    
                    $smarty->assign("ErrorMsg", $ErrorMsg);
                    return;
                }
            }

            $app = '';

            $dir = $applicationFormPath;
            $newpdfdir = $applicationFormPath . str_replace(" ", "", $txtProjectName);
            if ((!is_dir($newpdfdir))) {
                mkdir($newpdfdir, 0777);
            }
            $pdf_path = $dir . str_replace(" ", "", $txtProjectName) . "/" . time() . "_" . $_FILES['app_pdf']['name'];
            $move = move_uploaded_file($_FILES['app_pdf']['tmp_name'], $pdf_path);

            str_replace(" ", "", $txtProjectName);

            if ($move == TRUE)
                $app = $pdf_path;


            /* code for comment save in saperate comment table* */
            $arrCommentTypeValue = array();
            if ($txtProjectRemark != '') {
                $arrCommentTypeValue['Project'] = $txtProjectRemark;
            }
            if ($txtCallingRemark != '') {
                $arrCommentTypeValue['Calling'] = $txtCallingRemark;
            }
            if ($txtAuditRemark != '') {
                $arrCommentTypeValue['Audit'] = $txtAuditRemark;
            }
            if ($txtSecondaryAuditRemark != '') {
                $arrCommentTypeValue['SecondaryAudit'] = $txtSecondaryAuditRemark;
            }
            if ($fieldSurveyRemark != '') {
                $arrCommentTypeValue['FieldSurvey'] = $fieldSurveyRemark;
            }
            if ($secondaryRemark != '') {
                $arrCommentTypeValue['Secondary'] = $secondaryRemark;
            }
            if ($txtSkipUpdationRemark != '') {
                $arrCommentTypeValue['SkipUpdationCycle'] = $txtSkipUpdationRemark;
            }
            /* end code for comment save in saperate comment table */
            $arrInsertUpdateProject = array();
            $arrInsertUpdateProject['project_id'] = $projectId;
            $arrInsertUpdateProject['project_name'] = $projName;
            $arrInsertUpdateProject['builder_id'] = $builderId;
            $arrInsertUpdateProject['locality_id'] = $localityId;
            $arrInsertUpdateProject['project_description'] = $txtProjectDescription;
            $arrInsertUpdateProject['comments'] = $comments;
            $arrInsertUpdateProject['project_address'] = $txtAddress;
            if ($txtProjectLattitude == '')
                $txtProjectLattitude = null;
            $arrInsertUpdateProject['latitude'] = $txtProjectLattitude;
            if ($txtProjectLongitude == '')
                $txtProjectLongitude = null;
            $arrInsertUpdateProject['longitude'] = $txtProjectLongitude;
            $arrInsertUpdateProject['display_order'] = getDisplayOrder($projectId);
            $arrInsertUpdateProject['status'] = $Active;
            $arrInsertUpdateProject['project_url'] = $txtProjectURL;
            $arrInsertUpdateProject['pre_launch_date'] = $pre_launch_date;
            $arrInsertUpdateProject['launch_date'] = $eff_date_to;
            $arrInsertUpdateProject['source_of_information'] = $txtProjectSource;
            if ($application == 'pdf-new' || $application == 'pdf-del')
                $arrInsertUpdateProject['application_form'] = $app;
            $arrInsertUpdateProject['approvals'] = $approvals;
            $arrInsertUpdateProject['project_size'] = $project_size;
            $arrInsertUpdateProject['power_backup_type_id'] = $powerBackup;
            $arrInsertUpdateProject['project_type_id'] = $project_type;
            $arrInsertUpdateProject['architect_name'] = $architect;
            $arrInsertUpdateProject['power_backup_capacity'] = $power_backup_capacity;
            if ($projectId == '')
                $arrInsertUpdateProject['promised_completion_date'] = $eff_date_to_prom;
            $arrInsertUpdateProject['residential_flag'] = $residential;
            $arrInsertUpdateProject['township_id'] = $township;
            //$arrInsertUpdateProject['authority_id'] = $authority;

            $arrInsertUpdateProject['open_space'] = $open_space;
            $arrInsertUpdateProject['project_status_id'] = $Status;
            $arrInsertUpdateProject['should_display_price'] = $shouldDisplayPrice;
            $arrInsertUpdateProject['expected_supply_date'] = $exp_launch_date;
            $arrInsertUpdateProject['updated_by'] = $_SESSION['adminId'];
            $arrInsertUpdateProject['no_of_towers'] = $numberOfTowers;
            if (isset($_REQUEST['skip_b2b']))
                $arrInsertUpdateProject['skip_b2b'] = $skip_b2b;

//            if ($skipUpdationCycle == skipUpdationCycle_Id)
//                $arrInsertUpdateProject['updation_cycle_id'] = skipUpdationCycle_Id;
//            else if ($skipUpdationCycle == 0 && $updationCycleIdOld == skipUpdationCycle_Id)
//                $arrInsertUpdateProject['updation_cycle_id'] = null;

            $returnProject = ResiProject::create_or_update($arrInsertUpdateProject);

            if ($projectId && $Active == 'Inactive') { // when project status set to be inactive
                $pro_aliases = ProjectAliases::find('all', array('conditions' => array('original_project_id' => $projectId)));
                if ($pro_aliases)
                    $pro_aliases = ProjectAliases::find($pro_aliases[0]->id);
                else
                    $pro_aliases = new ProjectAliases();

                $pro_aliases->original_project_id = $projectId;
                if ($_POST['reason'] == 'duplicate') {
                    $pro_aliases->duplicate_project_id = $_POST['duplicate_pid'];
                    $pro_aliases->reason_text = '';
                }
                if ($_POST['reason'] == 'other_reason') {
                    $pro_aliases->reason_text = $_POST['other_reason_txt'];
                    $pro_aliases->duplicate_project_id = 0;
                }

                $pro_aliases->save();
            } elseif ($projectId && $Active != '' && $Active != 'Inactive') { // when project status set to be active delete entries in project aliases
                $pro_aliases = ProjectAliases::find('all', array('conditions' => array('original_project_id' => $projectId)));

                if ($pro_aliases) {
                    $pro_aliases = ProjectAliases::find($pro_aliases[0]->id);
                    $pro_aliases->delete();
                }
            }

            /* jitendra pathak */
            TableAttributes::delete_all(array('conditions' => array('table_id' => $returnProject->project_id, 'attribute_name' => 'ConstructionContractor', 'table_name' => 'resi_project')));
            if ($constructionContractorId) {
                $consContractor = new TableAttributes();
                $consContractor->table_name = 'resi_project';
                $consContractor->table_id = $returnProject->project_id;
                $consContractor->attribute_name = 'ConstructionContractor';
                $consContractor->attribute_value = $constructionContractorId;
                $consContractor->updated_by = $_SESSION['adminId'];
                $consContractor->save();
            }

            TableAttributes::delete_all(array('conditions' => array('table_id' => $returnProject->project_id, 'attribute_name' => 'MaintenanceContractor', 'table_name' => 'resi_project')));
            if ($maintenanceContractorId) {
                $maintContractor = new TableAttributes();
                $maintContractor->table_name = 'resi_project';
                $maintContractor->table_id = $returnProject->project_id;
                $maintContractor->attribute_name = 'MaintenanceContractor';
                $maintContractor->attribute_value = $maintenanceContractorId;
                $maintContractor->updated_by = $_SESSION['adminId'];
                $maintContractor->save();
            }


            TableAttributes::delete_all(array('conditions' => array('table_id' => $returnProject->project_id, 'attribute_name' => 'LandscapeArchitect', 'table_name' => 'resi_project')));
            if ($landscapeArchitectId) {
                $landscapeArchitect = new TableAttributes();
                $landscapeArchitect->table_name = 'resi_project';
                $landscapeArchitect->table_id = $returnProject->project_id;
                $landscapeArchitect->attribute_name = 'LandscapeArchitect';
                $landscapeArchitect->attribute_value = $landscapeArchitectId;
                $landscapeArchitect->updated_by = $_SESSION['adminId'];
                $landscapeArchitect->save();
            }


            /* END */

            $redev_pro = TableAttributes::find('all', array('conditions' => array('table_id' => $returnProject->project_id, 'attribute_name' => 'REDEVELOPMENT_PROJECT', 'table_name' => 'resi_project')));

            if ($redev_pro) {

                $redev_pro = TableAttributes::find($redev_pro[0]->id);
                $redev_pro->updated_by = $_SESSION['adminId'];
                $redev_pro->attribute_value = $redevelopmentProject;
                $redev_pro->save();
            } else {
                $redev_pro = new TableAttributes();
                $redev_pro->table_name = 'resi_project';
                $redev_pro->table_id = $returnProject->project_id;
                $redev_pro->attribute_name = 'REDEVELOPMENT_PROJECT';
                $redev_pro->attribute_value = $redevelopmentProject;
                $redev_pro->updated_by = $_SESSION['adminId'];
                $redev_pro->save();
            }

            $authority_table = TableAttributes::find('all', array('conditions' => array('table_id' => $returnProject->project_id, 'attribute_name' => 'HOUSING_AUTHORITY_ID', 'table_name' => 'resi_project')));

            if ($authority_table) {

                $authority_table = TableAttributes::find($authority_table[0]->id);
                $authority_table->updated_by = $_SESSION['adminId'];
                $authority_table->attribute_value = $authority;
                $authority_table->save();
            } else {
                $authority_table = new TableAttributes();
                $authority_table->table_name = 'resi_project';
                $authority_table->table_id = $returnProject->project_id;
                $authority_table->attribute_name = 'HOUSING_AUTHORITY_ID';
                $authority_table->attribute_value = $authority;
                $authority_table->updated_by = $_SESSION['adminId'];
                $authority_table->save();
            }



            ## - desccripion content flag handeling
            $cont_flag = TableAttributes::find('all', array('conditions' => array('table_id' => $returnProject->project_id, 'attribute_name' => 'DESC_CONTENT_FLAG', 'table_name' => 'resi_project')));

            if ($cont_flag) {
                $content_flag = ($_POST["content_flag"]) ? 1 : 0;
                if (is_numeric($content_flag)) {
                    $cont_flag = TableAttributes::find($cont_flag[0]->id);
                    $cont_flag->updated_by = $_SESSION['adminId'];
                    $cont_flag->attribute_value = $content_flag;
                    $cont_flag->save();
                }
            } else {
                //add mode by dataEntry
                $cont_flag = new TableAttributes();
                $cont_flag->table_name = 'resi_project';
                $cont_flag->table_id = $returnProject->project_id;
                $cont_flag->attribute_name = 'DESC_CONTENT_FLAG';
                $cont_flag->attribute_value = ($_POST["content_flag"]) ? 1 : 0;
                $cont_flag->updated_by = $_SESSION['adminId'];
                $cont_flag->save();
            }

            //echo $eff_date_to." heer";die;
            if (!ResiProjectPhase::find('all', array('conditions' => array('project_id' => $returnProject->project_id, 'phase_type' => 'Logical')))) {
                $phase = new ResiProjectPhase();
                $phase->project_id = $returnProject->project_id;
                $phase->phase_name = 'No Phase';
                $phase->phase_type = 'Logical';
                $phase->completion_date = $eff_date_to_prom;
                $phase->pre_launch_date = $pre_launch_date;
                $phase->launch_date = $eff_date_to;
                $phase->status = 'Active';
                $phase->booking_status_id = 1;
                $phase->construction_status = $Status;
                $phase->created_at = date('Y-m-d H:i:s');
                $phase->updated_at = date('Y-m-d H:i:s');
                $phase->updated_by = $_SESSION['adminId'];
                $phase->submitted_date = date('Y-m-d H:i:s');
                $phase->virtual_save();
            } else {
                $qryUpdatePhase = "update resi_project_phase 
                       set launch_date = '" . $eff_date_to . "',
                           updated_at = now(),
                           updated_by = " . $_SESSION['adminId'] . "
                       where project_id = $projectId and phase_name = 'No Phase' and version = 'Cms'";
                mysql_query($qryUpdatePhase);
            }

            //create new project url
            $localityDetail = Locality::getLocalityById($localityId);
            $cityDetail = City::getCityById($cityId);
            $builderDetail = ResiBuilder::getBuilderById($builderId);
            $txtProjectURL = createProjectURL($cityDetail[0]->label, $localityDetail[0]->label, $builderDetail[0]->builder_name, $txtProjectName, $returnProject->project_id);
            $updateQuery = "UPDATE " . RESI_PROJECT . " set PROJECT_URL='" . $txtProjectURL . "' 
                                where PROJECT_ID=$returnProject->project_id and version = 'Cms'";
            $resUrl = mysql_query($updateQuery) or die(mysql_error());
            $_POST['bank_list'] = array_values(array_filter($_POST['bank_list']));
            if (isset($_POST['bank_list'])) {
                ProjectBanks::projectBankDeleteInsert($_POST['bank_list'], $returnProject->project_id);
            }

            if ($projectId == '') {
                if ($returnProject->project_id) {
                    CommentsHistory::insertUpdateComments($returnProject->project_id, $arrCommentTypeValue, 'NewProject');
                    $qryPhaseSelect = "select phase_id from resi_project_phase where project_id = $returnProject->project_id";
                    $resPhaseSelect = mysql_query($qryPhaseSelect);
                    $phaseIdSelet = mysql_fetch_assoc($resPhaseSelect);
                    if ($eff_date_to_prom != '' && $eff_date_to_prom != '0000-00-00 00:00:00') {
                        //$eff_date_to_prom = '0000-00-00';
                        $effectiveDt = date('Y') . "-" . date('m') . "-01";
                        $qryCompletionDate = "insert into resi_proj_expected_completion 
                    set
                      project_id = $returnProject->project_id,
                      expected_completion_date = '" . $eff_date_to_prom . "',
                      submitted_date = '" . $effectiveDt . "',
                      phase_id = " . $phaseIdSelet['phase_id'];
                        mysql_query($qryCompletionDate);
                    }
                    header("Location:project_img_add.php?projectId=" . $returnProject->project_id);
                }
            } else {
                $ProjectDetail = ResiProject::virtual_find($projectId);
                $qryStg = "select * from master_project_stages where id = '" . $ProjectDetail->project_stage_id . "'";
                $resStg = mysql_query($qryStg) or die(mysql_error());
                $stageId = mysql_fetch_assoc($resStg);
                $projectUpdationCycleId = Resiproject::virtual_find($projectId);
                //print("<pre>");
                //print_r($arrCommentTypeValue); //die;
                CommentsHistory::insertUpdateComments($projectId, $arrCommentTypeValue, $stageId['name'], $projectUpdationCycleId->updation_cycle_id);
                //if( $txtProjectURL != $txtProjectURLOld && $txtProjectURLOld != '' ) {
                //  insertUpdateInRedirectTbl($txtProjectURL,$txtProjectURLOld);
                // }

                if ($preview == 'true')
                    header("Location:show_project_details.php?projectId=" . $projectId);
                else
                    header("Location:ProjectList.php?projectId=" . $projectId);
            }
        }
    }
    else if ($_POST['btnExit'] == "Exit") {
        if ($preview == 'true')
            header("Location:show_project_details.php?projectId=" . $projectId);
        else
            header("Location:ProjectList.php?projectId=" . $projectId);
    }
}
elseif ($projectId != '') {
    $ProjectDetail = ResiProject::virtual_find($projectId, array('get_extra_scope' => true));
//    echo "<pre>";
//    print_r($ProjectDetail);
//    die;

    $smarty->assign("txtProjectName", stripslashes($ProjectDetail->project_name));
    $smarty->assign("txtAddress", stripslashes($ProjectDetail->project_address));
    $smarty->assign("txtProjectDescription", stripslashes($ProjectDetail->project_description));
    $smarty->assign("txtAddress", stripslashes($ProjectDetail->project_address));
    $smarty->assign("builderId", stripslashes($ProjectDetail->builder_id));

    /*     * **start city locality and suburb********* */
    $smarty->assign("localityId", $ProjectDetail->locality_id);
    $localityDetail = Locality::getLocalityById($ProjectDetail->locality_id);
    $suburbDetail = Suburb::getSuburbById($localityDetail[0]->suburb_id);

    $suburbSelect = Suburb::SuburbArr($suburbDetail[0]->city_id);
    $smarty->assign("suburbSelect", $suburbSelect);

    $smarty->assign("suburbId", $localityDetail[0]->suburb_id);
    $smarty->assign("cityId", $suburbDetail[0]->city_id);

    $localitySelect = Locality::getLocalityByCity($suburbDetail[0]->city_id);
    foreach ($localitySelect as $value) {
        $getLocalityBySuburb[$value->locality_id] = $value->label;
    }
    $smarty->assign("getLocalityBySuburb", $getLocalityBySuburb);

    /*     * **end city locality and suburb********* */

    /*     * start code for fetch offer heading and desc from db* */
    $qryOfferFetch = "select * from project_offers where project_id = $projectId";
    $resOfferFetch = mysql_query($qryOfferFetch) or die(mysql_error());
    $dataOffer = mysql_fetch_assoc($resOfferFetch);

    /*     * end code for fetch offer heading and desc from db* */
    $smarty->assign("txtProjectLattitude", stripslashes($ProjectDetail->latitude));
    $smarty->assign("txtProjectLongitude", stripslashes($ProjectDetail->longitude));
    $smarty->assign("DisplayOrder", stripslashes($ProjectDetail->display_order));
    $smarty->assign("Active", stripslashes($ProjectDetail->status));
    $smarty->assign("Status", stripslashes($ProjectDetail->project_status_id));
    $smarty->assign("txtProjectURL", stripslashes($ProjectDetail->project_url));
    $smarty->assign("txtProjectURLOld", stripslashes($ProjectDetail->project_url));
    $smarty->assign("eff_date_to", stripslashes($ProjectDetail->launch_date));
    $smarty->assign("display_order", $ProjectDetail->display_order);

    $app_form = stripslashes($ProjectDetail->application_form);
    $app_form = explode("/", $app_form);
    $smarty->assign("app_form", $app_form[2]);

    $smarty->assign("txtSourceofInfo", stripslashes($ProjectDetail->source_of_information));
    $smarty->assign("project_type", stripslashes($ProjectDetail->project_type_id));
    $smarty->assign("projectTypeOld", stripslashes($ProjectDetail->project_type_id));
    $smarty->assign("approvals", stripslashes($ProjectDetail->approvals));
    $smarty->assign("project_size", stripslashes($ProjectDetail->project_size));
    $smarty->assign("power_backup_capacity", stripslashes($ProjectDetail->power_backup_capacity));
    $smarty->assign("powerBackup", stripslashes($ProjectDetail->power_backup_type_id));
    $smarty->assign("architect", stripslashes($ProjectDetail->architect_name));
    $smarty->assign("residential", stripslashes($ProjectDetail->residential_flag));
    $smarty->assign("township", stripslashes($ProjectDetail->township_id));
    //$smarty->assign("authority", stripslashes($ProjectDetail->authority_id ));
    $smarty->assign("pre_launch_date", stripslashes($ProjectDetail->pre_launch_date));
    $smarty->assign("exp_launch_date", stripslashes($ProjectDetail->expected_supply_date));
    $smarty->assign("open_space", stripslashes($ProjectDetail->open_space));
    $smarty->assign("shouldDisplayPrice", stripslashes($ProjectDetail->should_display_price));
    $smarty->assign("eff_date_to_prom", stripslashes($ProjectDetail->promised_completion_date));
    $smarty->assign("completionDate", stripslashes($ProjectDetail->promised_completion_date));
    $smarty->assign("comments", stripslashes($ProjectDetail->comments));
    $smarty->assign("skipUpdationCycle", $ProjectDetail->updation_cycle_id);
    $smarty->assign("updationCycleIdOld", $ProjectDetail->updation_cycle_id);
    $smarty->assign("txtProjectLocation", $txtProjectLocation);
    $smarty->assign("bank_arr", projectBankList($projectId));
    $smarty->assign("numberOfTowers", $ProjectDetail->no_of_towers);
    $smarty->assign("skip_b2b", $ProjectDetail->skip_b2b);

    $booking_status_id = ResiProjectPhase::find('all', array('conditions' => array('project_id' => $projectId, 'phase_type' => 'Logical'), 'select' =>
                'BOOKING_STATUS_ID'));
    $smarty->assign("bookingStatus", $booking_status_id[0]->booking_status_id);
    $redevelopmentProject = TableAttributes::find('all', array('conditions' => array('table_id' => $projectId, 'attribute_name' => 'REDEVELOPMENT_PROJECT', 'table_name' => 'resi_project')));
    $smarty->assign("redevelopmentProject", $redevelopmentProject[0]->attribute_value);

    $authority = TableAttributes::find('all', array('conditions' => array('table_id' => $projectId, 'attribute_name' => 'HOUSING_AUTHORITY_ID', 'table_name' => 'resi_project')));
    $smarty->assign("authority", $authority[0]->attribute_value);

    $contentFlag = TableAttributes::find('all', array('conditions' => array('table_id' => $projectId, 'attribute_name' => 'DESC_CONTENT_FLAG', 'table_name' => 'resi_project')));
    $smarty->assign("contentFlag", $contentFlag[0]->attribute_value);

    /* jitendra pathak */
    $cContractor = TableAttributes::find('all', array('conditions' => array('table_id' => $projectId, 'attribute_name' => 'ConstructionContractor', 'table_name' => 'resi_project')));
    $smarty->assign("constructionContractorId", $cContractor[0]->attribute_value);
    if ($cContractor[0]->attribute_value) {
        $companyTemp = Company::getCompanyById($cContractor[0]->attribute_value);
        $smarty->assign("constructionContractor", $companyTemp[0]->name);
    }

    $mContractor = TableAttributes::find('all', array('conditions' => array('table_id' => $projectId, 'attribute_name' => 'MaintenanceContractor', 'table_name' => 'resi_project')));
    if ($mContractor[0]->attribute_value) {
        $companyTemp = Company::getCompanyById($mContractor[0]->attribute_value);
        $smarty->assign("maintenanceContractor", $companyTemp[0]->name);
        $smarty->assign("maintenanceContractorId", $mContractor[0]->attribute_value);
    }

    $lArchitect = TableAttributes::find('all', array('conditions' => array('table_id' => $projectId, 'attribute_name' => 'LandscapeArchitect', 'table_name' => 'resi_project')));
    if ($lArchitect[0]->attribute_value) {
        $companyTemp = Company::getCompanyById($lArchitect[0]->attribute_value);
        $smarty->assign("landscapeArchitect", $companyTemp[0]->name);
        $smarty->assign("landscapeArchitectId", $lArchitect[0]->attribute_value);
    }
    /* END */

    $brochure = getProjectBrochure($projectId);
    $smarty->assign("projectBrochure", $brochure['projectBrouchure']['service_image_path']);
    $smarty->assign("oldProjectBrochure", $brochure['projectBrouchure']['service_image_id']);

    $smarty->assign("dept", $_SESSION['DEPARTMENT']);
}

function getNumProjectsUnderDisplayOrder($displayOrder, $cityId, $projectId) {
    $numProjects = 0;
    $qry = "SELECT count(*) as numProjects FROM resi_project WHERE display_order <= $displayOrder and city_id = $cityId and project_id != $projectId";
    $res = mysql_query($qry);
    while ($data = mysql_fetch_array($res)) {
        $numProjects = $data['numProjects'];
    }
    return $numProjects;
}

function getDisplayOrder($projectId) {
    $numProjects = 0;
    $qry = "SELECT display_order FROM resi_project WHERE project_id='" . $projectId . "'";
    $res = mysql_fetch_object(mysql_query($qry));

    return $res->display_order ? $res->display_order : 999;
}

$userDepartment = $_SESSION['DEPARTMENT'];
$smarty->assign("userDepartment", $userDepartment);

if ($projectId != '') {
    /*     * ****code for project comment in seperate table**** */
    $ProjectDetail = ProjectDetail($projectId);
    $cycleId = $ProjectDetail[0]['PROJECT_STAGE'];
    $projectComments = CommentsHistory::getCommentHistoryByProjectIdCycleId($projectId, $cycleId);
    $smarty->assign("projectComments", $projectComments);
    $projectOldComments = CommentsHistory::getOldCommentHistoryByProjectId($projectId);
    $smarty->assign("projectOldComments", $projectOldComments);
    /*     * ****end code for project comment in seperate table**** */
}
?>
