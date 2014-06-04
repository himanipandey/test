<?php

$accessImageAssignmentLead = '';
if( $processAssignmentForConstImg == false && $constructionLead == false)
   $accessImageAssignmentLead = "No Access";
$smarty->assign("accessImageAssignmentLead",$accessImageAssignmentLead);
require_once "$_SERVER[DOCUMENT_ROOT]/datacollection/functions.php";

/*if(!(($_SESSION['ROLE'] === 'teamLeader') && ($_SESSION['DEPARTMENT'] === 'CALLCENTER' 
     || $_SESSION['DEPARTMENT'] === 'SURVEY'))){
    header("Location: project_desktop.php");
}*/

if(isset($_POST['cityId']) && !empty($_POST['cityId'])){
    unset($_SESSION['project-status']);
    $_SESSION['project-status']['city'] = $_POST['cityId'];
    $_SESSION['project-status']['suburb'] = $_POST['suburbId'];
    if(isset($_POST['localityId']) && $_POST['localityId'] != '')
        $_SESSION['project-status']['locality'] = $_POST['localityId'];
}
elseif(isset($_REQUEST['executive']) && !empty($_REQUEST['executive'])){
    unset($_SESSION['project-status']);
    $_SESSION['project-status']['executive'] = $_REQUEST['executive'];
}
elseif(isset($_POST['projectIds']) && !empty($_POST['projectIds'])){
    unset($_SESSION['project-status']);
    $_SESSION['project-status']['projectIds'] = $_REQUEST['projectIds'];
}

if(isset($_SESSION['project-status']['city']) && !empty($_SESSION['project-status']['city'])){
    if(isset($_SESSION['project-status']['locality']) && !empty($_SESSION['project-status']['locality'])){ 
        $projectsfromDB = getProjectConstListForManagers($_SESSION['project-status']['city'], $_SESSION['project-status']['suburb'], $_SESSION['project-status']['locality']);
    }else {
        $projectsfromDB = getProjectConstListForManagers($_SESSION['project-status']['city'], 
            $_SESSION['project-status']['suburb']);
    }    

    $suburbDataArr = Suburb::SuburbArr($_SESSION['project-status']['city']);
    if(isset($_REQUEST['suburbId']) && $_REQUEST['suburbId'] != '')
        $localityDataArr = Locality::localityList($_SESSION['project-status']['suburb']);
    
}elseif(isset($_SESSION['project-status']['executive']) && !empty($_SESSION['project-status']['executive'])){
    $projectsAssignedToExec = getAssignedProjectsForConst($_SESSION['project-status']['executive']);
    $pIdsList = array();
    foreach($projectsAssignedToExec as $pList){
        $pIdsList[] = $pList['PROJECT_ID'];
    }
    if(count($pIdsList) != 0)
       $projectsfromDB = getAssignedProjectsFromConstPIDs($pIdsList);
}elseif(isset($_SESSION['project-status']['projectIds']) && !empty($_SESSION['project-status']['projectIds'])){
    $projectIds = extractPIDs($_SESSION['project-status']['projectIds']);
    $projectsfromDB = getAssignedProjectsFromConstPIDs($projectIds);
    //$projectList = prepareDisplayData($projectsfromDB);
}
$arrReDefine = array();
foreach($projectsfromDB as $k=>$v) {
    
    $userNameVal = explode("|",$v['username']);
    
    if(!isset($_SESSION['project-status']['executive']) && !empty($_SESSION['project-status']['executive'])){
        
        $assignedIdVal = explode("|",$v['assigned_to']);
       if($assignedIdVal[0] == $_SESSION['project-status']['executive']){
            $arrReDefine[$k]['PROJECT_ID'] = $v['PROJECT_ID'];
            $arrReDefine[$k]['PROJECT_NAME'] = $v['PROJECT_NAME'];
            $arrReDefine[$k]['BUILDER_NAME'] = $v['BUILDER_NAME'];
            $arrReDefine[$k]['CITY'] = $v['CITY'];
            $arrReDefine[$k]['LOCALITY'] = $v['LOCALITY'];
            $arrReDefine[$k]['LAST_WORKED_AT'] = $v['LAST_WORKED_AT'];
            $assignedVal = explode("|",$v['ASSIGNED_AT']);

            if(count($assignedVal) == 1) {
                $arrReDefine[$k]['assigned_curr'] = $assignedVal[0];
            $arrReDefine[$k]['assigned_last'] = '';
            }
            else{
                $arrReDefine[$k]['assigned_curr'] = $assignedVal[0];
                $arrReDefine[$k]['assigned_last'] = $assignedVal[1];
            }

            if(count($userNameVal) == 1){
                $arrReDefine[$k]['userName_curr'] = $userNameVal[0];
                $arrReDefine[$k]['userName_last'] = '';
            }  else {
                $arrReDefine[$k]['userName_curr'] = $userNameVal[0];
                $arrReDefine[$k]['userName_last'] = $userNameVal[1];
            }
            $statusVal = explode("|",$v['STATUS']);
            if(count($statusVal) == 1){
                $arrReDefine[$k]['status_curr'] = $statusVal[0];
                $arrReDefine[$k]['status_last'] = '';
            }
            else{
                $arrReDefine[$k]['status_curr'] = $statusVal[0];
                $arrReDefine[$k]['status_last'] = $statusVal[1];
            }


            if(count($assignedIdVal) == 1){
                $arrReDefine[$k]['assignedId_curr'] = $assignedIdVal[0];
                $arrReDefine[$k]['assignedId_last'] = '';
            }
            else{
                $arrReDefine[$k]['assignedId_curr'] = $assignedIdVal[0];
                $arrReDefine[$k]['assignedId_last'] = $assignedIdVal[1];
            }


            $remarkVal = explode("|",$v['REMARK']);

            if(count($remarkVal) == 1) {
                $arrReDefine[$k]['remark_curr'] = $remarkVal[0];
                $arrReDefine[$k]['remark_last'] = '';
            }
            else{
                $arrReDefine[$k]['remark_curr'] = $remarkVal[0];
                $arrReDefine[$k]['remark_last'] = $remarkVal[1];
            }


            $sourceVal = explode("|",$v['source']);
            if(count($sourceVal) == 1) {
                 $arrReDefine[$k]['source_curr'] = $sourceVal[0];
                 $arrReDefine[$k]['source_last'] = '';
            }
            else{
                 $arrReDefine[$k]['source_curr'] = $sourceVal[0];
                 $arrReDefine[$k]['source_last'] = $sourceVal[1];
            }
       }
    }else 
        $arrReDefine[$k]['PROJECT_ID'] = $v['PROJECT_ID'];
        $arrReDefine[$k]['PROJECT_NAME'] = $v['PROJECT_NAME'];
        $arrReDefine[$k]['BUILDER_NAME'] = $v['BUILDER_NAME'];
        $arrReDefine[$k]['CITY'] = $v['CITY'];
        $arrReDefine[$k]['LOCALITY'] = $v['LOCALITY'];
        $assignedVal = explode("|",$v['ASSIGNED_AT']);
        $arrReDefine[$k]['assigned_curr'] = $assignedVal[0];
        $arrReDefine[$k]['assigned_last'] = $assignedVal[1];

         $arrReDefine[$k]['userName_curr'] = $userNameVal[0];
        $arrReDefine[$k]['userName_last'] = $userNameVal[1];
        $statusVal = explode("|",$v['STATUS']);
        $arrReDefine[$k]['status_curr'] = $statusVal[0];
        $arrReDefine[$k]['status_last'] = $statusVal[1];

        $assignedIdVal = explode("|",$v['assigned_to']);
        $arrReDefine[$k]['assignedId_curr'] = $assignedIdVal[0];
        $arrReDefine[$k]['assignedId_last'] = $assignedIdVal[1];
        $remarkVal = explode("|",$v['REMARK']);
        $arrReDefine[$k]['remark_curr'] = $remarkVal[0];
        $arrReDefine[$k]['remark_last'] = $remarkVal[1];

        $sourceVal = explode("|",$v['source']);
        $arrReDefine[$k]['source_curr'] = $sourceVal[0];
        $arrReDefine[$k]['source_last'] = $sourceVal[1];
}
$smarty->assign('projectsfromDB',$arrReDefine);
$projectList = array();
foreach($arrReDefine as $p){
    array_push($projectList, $p);
}
if(isset($_SESSION['project-status']['assignmentError'])){
    if(empty($_SESSION['project-status']['assignmentError'])){
        $msg['type'] = 'success';
        $msg['content'] = 'All Projects Assigned Successfully';
    }
    else {
        $msg['type'] = 'error';
        $msg['content'] = "ProjetId " . implode(', ', $_SESSION['project-status']['assignmentError']) ." couldn't be assigned.";
    }
    unset($_SESSION['project-status']['assignmentError']);
}
$CityDataArr = City::CityArr();
$executiveList = getDataEntryExecutive();   //todo
$smarty->assign("CityDataArr", $CityDataArr);

$smarty->assign("executiveList", $executiveList);
$smarty->assign("projectList", $projectList);
$smarty->assign("projectPageURL", '/show_project_details.php?projectId=');
$smarty->assign("selectedCity", $_SESSION['project-status']['city']);
$smarty->assign("selectedSuburb", $_SESSION['project-status']['suburb']);
$smarty->assign("selectedLocality", $_SESSION['project-status']['locality']);
$smarty->assign("selectedExecutive", $_SESSION['project-status']['executive']);
$smarty->assign("selectedProjectIds", $_SESSION['project-status']['projectIds']);
$smarty->assign("SuburbDataArr", $suburbDataArr);
$smarty->assign("LocalityDataArr", $localityDataArr);
$smarty->assign("message", $msg);

if(isset($projectList) && $_REQUEST['download'] == 'true'){
    download_xls_file($projectList);
}
function download_xls_file($projectList){
   // echo "<pre>";print_r($_REQUEST);die;

    $filename = "/tmp/data_collection_".time().".xls";
    $callCenterArr = array();
    $cntCall = 0;
    foreach ($projectList as $pkey => $project){
        //code for field data download
        
            $callCenterArr[$cntCall]['PROJECT_ID'] = $projectList[$pkey]["PROJECT_ID"];
            $callCenterArr[$cntCall]['PROJECT_NAME'] = $projectList[$pkey]["PROJECT_NAME"];
            $callCenterArr[$cntCall]['BUILDER_NAME'] = $projectList[$pkey]["BUILDER_NAME"];
            $callCenterArr[$cntCall]['CITY'] = $projectList[$pkey]["CITY"];
            $callCenterArr[$cntCall]['LOCALITY'] = $projectList[$pkey]["LOCALITY"];

            $callCenterArr[$cntCall]["ASSIGNED_TO"] = $projectList[$pkey]["userName_curr"];
            $callCenterArr[$cntCall]["ASSIGNED_AT"] = $projectList[$pkey]["assigned_curr"];
            $callCenterArr[$cntCall]["STATUS"] = $projectList[$pkey]["status_curr"];
            $callCenterArr[$cntCall]["REMARK"] = $projectList[$pkey]["remark_curr"];
            
            $callCenterArr[$cntCall]["SOURCE"] = $projectList[$pkey]["source_curr"];
            $callCenterArr[$cntCall]["ASSIGNED_AT"] = $projectList[$pkey]["assigned_last"];
            $callCenterArr[$cntCall]["STATUS LAST"] = $projectList[$pkey]["status_last"];
            $callCenterArr[$cntCall]["REMARK LAST"] = $projectList[$pkey]["remark_last"];
            $callCenterArr[$cntCall]["SOURCE"] = $projectList[$pkey]["source"];

            $cntCall++;
    };
    excel_file_download($callCenterArr, $filename);
}

function extractPIDs($pidString){
    
    $result = array();
    $pidArr = explode(',', $pidString);
    foreach ($pidArr as $value) {
        $result[] = trim($value);
    }
    return $result;
}
?>
