<?php
include("../smartyConfig.php");
include("../dbConfig.php");
include("../modelsConfig.php");
include("../appWideConfig.php");
include("../builder_function.php");
require_once("../common/function.php");
include("../includes/configs/phaseStageConfig.php");
date_default_timezone_set('Asia/Kolkata');
//echo "<pre>";print_r($_POST);die;
$arrOtherCities = 
  array(
		"24"=>"Chandigarh",
		"23"=>"Lucknow",
		"13"=>"Indore",
		"33"=>"Bhopal",
		"35"=>"Nashik",
		"25"=>"Nagpur",
		"38"=>"Vadodara",
		"27"=>"Goa",
		"97"=>"Durgapur",
		"31"=>"Bhubaneswar",
		"30"=>"Kochi",
		"29"=>"Trivandrum",
		"45"=>"Trichy",
		"41"=>"Visakhapatnam",
		"90"=>"Sonepat",
		"98"=>"Panipat",
		"99"=>"Raigad",
		"26"=>"Coimbatore",
		"28"=>"Jaipur",
		"46"=>"Agra",
		"48"=>"Pondicherry",
		"61"=>"Vijayawada",
		"91"=>"Karnal"
  );

$dept = $_SESSION['DEPARTMENT'];

if(!isset($_POST['dwnld_projectId']))
	$_POST['dwnld_projectId'] = '';

if(!isset($_POST['dwnld_mode']))
	$_POST['dwnld_mode'] = '';

if(!isset($_POST['dwnld_search']))
	$_POST['dwnld_search'] = '';

if(!isset($_POST['dwnld_search']))
	$_POST['dwnld_search'] = '';

if(!isset($_POST['dwnld_city']))
	$_POST['dwnld_city'] = '';

if(!isset($_POST['dwnld_Residential']))
	$_POST['dwnld_Residential'] = '';

if(!isset($_POST['dwnld_skip_B2B']))
  $_POST['dwnld_skip_B2B'] = '';

if(!isset($_POST['dwnld_Active']))
	$_POST['dwnld_Active'] = '';

if(!isset($_POST['dwnld_Status']))
	$_POST['dwnld_Status'] = '';

if($_POST['dwnld_Active'] != '')
	$ActiveValue  = $_POST['dwnld_Active'];
else
	$ActiveValue = '';

if($_POST['dwnld_Status'] != '')
	$StatusValue  = $_POST['dwnld_Status'];
else
	$StatusValue = '';
	
if(!isset($_REQUEST['dwnld_exp_supply_date_from']))
  $_REQUEST['dwnld_exp_supply_date_from'] = '';
$exp_supply_date_from = $_REQUEST['dwnld_exp_supply_date_from'];

if(!isset($_REQUEST['dwnld_exp_supply_date_to']))
  $_REQUEST['dwnld_exp_supply_date_to'] = '';
$exp_supply_date_to = $_REQUEST['dwnld_exp_supply_date_to'];	

if($StatusValue!="") $StatusValue = "'".$StatusValue."'";

$projectDataArr = array();
$NumRows =  $city = $builder = $project_name = '';

$transfer = $_POST['dwnld_transfer'];
$search = $_POST['dwnld_search'];
$city =	$_POST['dwnld_city'];
/*if(in_array()$city == 'othercities'){
	$group_city_ids = array();
	foreach($arrOtherCities as $key => $value){
		$group_city_ids[] = $key;
	}
	$city= implode(",",$group_city_ids);
}
*/
$cityListVal = '';
$expCity = explode(",",$_POST['dwnld_city']);
if($expCity[0] != '')
{
       $city = array();
       $newCityArr = array();
       foreach ($expCity as $val){
           if($val != 'othercities')
               $newCityArr[] = $val;
       }
       if(in_array("othercities",$expCity)){
              $OtherCitiesKeys = array_keys($arrOtherCities);
              $city[] = $OtherCitiesKeys;
       }elseif(count($newCityArr)>0){

               $city[] = $newCityArr;
      }
      $cityListVal = implode(",",$city[0]);
}

$builder = $_POST['dwnld_builder'];
$authority = $_POST['dwnld_authority'];
$phase = $_POST['current_dwnld_phase'];
$arrPhaseTag = explode('|',$_POST['dwnld_stage']);
$stage = $_POST['current_dwnld_stage'];
$updation_cycle = $_POST['dwnld_updationCycle'];
$Status = $_POST['dwnld_Status'];
$Active = $_POST['dwnld_Active'];
$selectdata = $_POST['dwnld_selectdata'];

//print "<pre>".print_r($_POST,1)."</pre>"; die;
if($search != '' OR $transfer != '' OR $_POST['dwnld_projectId'] != '')
{

    $QueryMember1 = "SELECT RP.updation_cycle_id,RP.PROJECT_ID,RB.BUILDER_NAME,RP.PROJECT_NAME,PP.name as PROJECT_PHASE,
                PS.name as PROJECT_STAGE,ct.LABEL AS CITY_NAME, psm.project_status as 
                    PROJECT_STATUS,rpp.phase_type,rpp.booking_status_id,
                L.LABEL LOCALITY, PSH.DATE_TIME, PA.FNAME, UC.LABEL UPDATION_LABEL
                 FROM
                    resi_project RP
                 LEFT JOIN
                    updation_cycle UC ON RP.UPDATION_CYCLE_ID=UC.UPDATION_CYCLE_ID
                 LEFT JOIN
                     locality L ON RP.LOCALITY_ID = L.LOCALITY_ID
                 INNER JOIN
                     suburb sub ON L.SUBURB_ID = sub.SUBURB_ID
                 LEFT JOIN
                     city ct ON sub.CITY_ID = ct.CITY_ID    
                 LEFT JOIN
                     project_stage_history PSH ON RP.MOVEMENT_HISTORY_ID = PSH.HISTORY_ID
                 LEFT JOIN 
                     proptiger_admin PA ON PSH.ADMIN_ID = PA.ADMINID
                 INNER JOIN 
                     resi_builder RB on RP.BUILDER_ID = RB.BUILDER_ID
                 INNER JOIN
                     master_project_stages PS ON RP.project_stage_id = PS.id
                 INNER JOIN
                     master_project_phases PP on RP.project_phase_id = PP.id
                 INNER JOIN
                    project_status_master psm on RP.PROJECT_STATUS_ID = psm.id
                  INNER JOIN
                    resi_project_phase rpp on RP.PROJECT_ID = rpp.PROJECT_ID 
                  left join table_attributes ta 
                        on ta.table_id = RP.PROJECT_ID AND ta.table_name='resi_project' AND ta.attribute_name='HOUSING_AUTHORITY_ID'  
                    ";

    if ($_POST['dwnld_skip_B2B'] != ''){
//      $and = " WHERE RP.version='Cms' and (RP.updation_cycle_id != '15' OR RP.updation_cycle_id is null) and RP.SKIP_B2B='".$_POST['dwnld_skip_B2B'] ."' and ";
      $and = " WHERE RP.version='Cms' and RP.SKIP_B2B='".$_POST['dwnld_skip_B2B'] ."' and ";
    }
    else{
//      $and = " WHERE RP.version='Cms' and (RP.updation_cycle_id != '15' OR RP.updation_cycle_id is null) and ";
      $and = " WHERE RP.version='Cms' and ";
    }
    if($_POST['dwnld_projectId'] == '')
    {
        if($_REQUEST['dwnld_Availability'] != '')
        {
            $arrAvalibality = explode(",",$_REQUEST['dwnld_Availability']); 
            $QueryMember .= $and ." (1 = 0 ";
            if(in_array(1,$arrAvalibality))
            {
                    $QueryMember .=  " OR RP.D_AVAILABILITY = 0";
            }
            if(in_array(2,$arrAvalibality))
            {
                    $QueryMember .=  " OR RP.D_AVAILABILITY > 0";
            }
            if(in_array(3,$arrAvalibality))
            {
                    $QueryMember .=  " OR RP.D_AVAILABILITY IS NULL ";
            }
            $QueryMember .= ")";
            $and  = ' AND ';
        }

        if($_POST['dwnld_project_name'] != '')
        {
            $QueryMember .= $and." RP.PROJECT_NAME LIKE '%".$_POST['dwnld_project_name']."%'";
            $and  = ' AND ';
        }
        if($_POST['dwnld_Residential'] != '')
        {
            $QueryMember .=  $and." RP.RESIDENTIAL_FLAG = '".$_POST['dwnld_Residential']."'";
            $and  = ' AND ';
        }

        if($ActiveValue != '')
        {
			$ActiveValue = explode(",",$ActiveValue);
			$ActiveValue = implode("','",$ActiveValue);
		    $QueryMember .=  $and." RP.STATUS IN('".$ActiveValue."')";
            $and  = ' AND ';
        }

        if($StatusValue != '')
        {
            $QueryMember .=  $and." RP.PROJECT_STATUS_ID IN(".$StatusValue.")";
            $and  = ' AND ';
        }

        if($cityListVal != '')
        {
            $QueryMember .= $and." sub.CITY_ID in (".$cityListVal.")";
            $and  = ' AND ';
        }
        if($_POST['dwnld_builder'] != '')
        {
            $QueryMember .= $and." RP.BUILDER_ID = '".$_POST['dwnld_builder']."'";
            $and  = ' AND ';
        }

        if($_POST['dwnld_authority'] != '')
        {
            $QueryMember .= $and." ta.attribute_value = '".$_POST['dwnld_authority']."'";
            $and  = ' AND ';
        }
        if($_POST['current_dwnld_phase'] != '')
        {
            $phaseList = explode(",",$_POST['current_dwnld_phase']);
            $phsId = array();
            foreach($phaseList as $val){
                $getProjectPhase = ProjectStage::getStageByName($val);
                $phsId[] = $getProjectPhase[0]->id;
            }
            $phsList = implode(",",$phsId);
            $QueryMember .= $and." RP.PROJECT_STAGE_ID in($phsList)";
            $and  = ' AND ';
        }
        if($stage != '')
        {
            $stageList = explode(",",$stage);
            $stgId = array();
            foreach($stageList as $val){
                $getProjectStage = ProjectPhase::getPhaseByName($val);
                $stgId[] = $getProjectStage[0]->id;
            }
            $stgList = implode(",",$stgId);
            
            $QueryMember .= $and." RP.PROJECT_PHASE_ID in($stgList)";
            $and  = ' AND ';
        }
        if($updation_cycle != '')
        {
            $QueryMember .= $and." RP.UPDATION_CYCLE_ID = '".$updation_cycle."'";
            $and  = ' AND ';
        }
        if($exp_supply_date_to != '' && $exp_supply_date_from != '')
        {
            $QueryMember .= $and." EXPECTED_SUPPLY_DATE BETWEEN '".$exp_supply_date_from."' AND '".$exp_supply_date_to."'";
            $and  = ' AND ';
        }
        if($exp_supply_date_to != '' && $exp_supply_date_from == '')
        {
            $QueryMember .= $and." EXPECTED_SUPPLY_DATE <= '".$exp_supply_date_to."'";
            $and  = ' AND ';
        }
        if($exp_supply_date_to == '' && $exp_supply_date_from != '')
        {
            $QueryMember .= $and." EXPECTED_SUPPLY_DATE >= '".$exp_supply_date_from."'";
            $and  = ' AND ';
        }
    }
    else
    {
        $QueryMember .= $and. " RP.PROJECT_ID IN (".$_POST['dwnld_projectId'].")";
    }
}
$arrPropId = array();
$QueryMember1 = $QueryMember1 . $QueryMember." Group By rpp.PROJECT_ID";
//die;
$QueryExecute = mysql_query($QueryMember1) or die(mysql_error());

$NumRows = mysql_num_rows($QueryExecute);

$cnt = 1;
$arrAll = array();
$arrAll['CNT'] = '';
$arrAll['PROJECT ID'] = '';
$arrAll['BUILDER NAME'] = '';
$arrAll['PROJECT NAME'] = '';
$arrAll['CITY'] = '';
$arrAll['LOCALITY'] = '';
$arrAll['PROJECT STATUS'] = '';
$arrAll['ASSIGNED DEPARTMENT'] = '';
$arrAll['BOOKING STATUS'] = '';
$arrAll['PHASE'] = '';

$arrAll['STAGE'] = '';
$arrAll['STAGE MOVEMENT DATE'] = '';
$arrAll['STAGE MOVEMENT DONE BY'] = '';
$arrAll['UPDATION LABEL'] = '';

while($ob1 = mysql_fetch_assoc($QueryExecute))
{
	$stage = $ob1['PROJECT_STAGE'];
	$phase = $ob1['PROJECT_PHASE'];
	$builder = $ob1['BUILDER_NAME'];

	$projid = $ob1['PROJECT_ID'];
	$projname = $ob1['PROJECT_NAME'];
	$currentCycle = currentCycleOfProject($projid,$ob1['PROJECT_PHASE'],$ob1['PROJECT_STAGE']);
	$cityname = $ob1['CITY_NAME'];
        $date_time = $ob1['DATE_TIME'];
        $stage_move_by = $ob1['FNAME'];
        $localityname = $ob1['LOCALITY'];
	
	$proj_status = $ob1['PROJECT_STATUS'];
	
	$booking_status = ($ob1['phase_type'] == 'Logical')?$ob1['booking_status_id']:fetch_project_booking_status($projid);
	
	if ($booking_status > 0){
			if ($booking_status == 1) $booking_status = "Available";
			if ($booking_status == 2) $booking_status = "Sold out";
			if ($booking_status == 3) $booking_status = "On Hold";
	}
	else
		$booking_status = "-";
		
	$updation_label = $ob1['UPDATION_LABEL'];
	if($phase == 'NewProject') $phse = 'NewProject Audit';
        else $phse = $phase;

        $arrAll['CNT'][] = $cnt;
        $arrAll['PROJECT ID'][] = $projid;
        $arrAll['BUILDER NAME'][] = $builder;
        $arrAll['PROJECT NAME'][] = $projname;
        $arrAll['CITY'][] = $cityname;
        $arrAll['LOCALITY'][] = $localityname;
        $arrAll['PROJECT STATUS'][] = $proj_status;
        $arrAll['ASSIGNED DEPARTMENT'][] = $currentCycle;
        $arrAll['BOOKING STATUS'][] = $booking_status;
        $arrAll['PHASE'][] = $stage;

        $arrAll['STAGE'][] = $phse;
        $arrAll['STAGE MOVEMENT DATE'][] = $date_time;
        $arrAll['STAGE MOVEMENT DONE BY'][] = $stage_move_by;
        $arrAll['UPDATION LABEL'][] = $updation_label;

	$cnt++;

}
//echo "<pre>";print_r($arrAll['PROJECT NAME']);die;
    $filename ="excelreport-".date('YmdHis').".csv";
    header( 'Content-Type: text/csv' );
    header( "Content-Disposition: attachment;filename=$filename" );
    //echo $contents; exit;
     $trow = $arrAll;
 if($trow)
    echocsv( array_keys( $trow ) );

 $count = 0;
 foreach($arrAll['CNT'] as $k=>$v){
        $arrAllInner = array();
        $arrAllInner['CNT'] = $k+1;
        $arrAllInner['PROJECT ID'] = $arrAll['PROJECT ID'][$count];
        $arrAllInner['BUILDER NAME'] = $arrAll['BUILDER NAME'][$count];
        $arrAllInner['PROJECT NAME'] = $arrAll['PROJECT NAME'][$count];
        $arrAllInner['CITY'] = $arrAll['CITY'][$count];
        $arrAllInner['LOCALITY'] = $arrAll['LOCALITY'][$count];
        $arrAllInner['PROJECT STATUS'] = $arrAll['PROJECT STATUS'][$count];
        $arrAllInner['ASSIGNED DEPARTMENT'] = $arrAll['ASSIGNED DEPARTMENT'][$count];
        $arrAllInner['BOOKING STATUS'] = $arrAll['BOOKING STATUS'][$count];
        $arrAllInner['PHASE'] = $arrAll['PHASE'][$count];
        $arrAllInner['STAGE'] = $arrAll['STAGE'][$count];
        $arrAllInner['STAGE MOVEMENT DATE'] = $arrAll['STAGE MOVEMENT DATE'][$count];
        $arrAllInner['STAGE MOVEMENT DONE BY'] = $arrAll['STAGE MOVEMENT DONE BY'][$count];
        $arrAllInner['UPDATION LABEL'] = $arrAll['UPDATION LABEL'][$count];
         $count++;
        echocsv( $arrAllInner );
       
 }
 
 function echocsv( $fields )  {
    $separator = '';
    foreach ( $fields as $field )    {
      if ( preg_match( '/\\r|\\n|,|"/', $field ) )     {
        $field = '"' . str_replace( '"', '""', $field ) . '"';
      }
      echo $separator . $field;
      $separator = ',';
    }
    echo "\r\n";
  }
?>
