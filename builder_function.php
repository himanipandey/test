<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/function/login.php');

function getDatesBetweeenTwoDates($fromDate, $toDate) {
    $dateMonthYearArr = array();
    $fromDateTS = strtotime($fromDate);
    $toDateTS = strtotime($toDate);

    for ($currentDateTS = $toDateTS; $currentDateTS >= $fromDateTS; $currentDateTS = $currentDateTS - (60 * 60 * 24)) {
        $currentDateStr = date("Y-m-d", $currentDateTS);
        $dateMonthYearArr[] = $currentDateStr;
    }
    return $dateMonthYearArr;
}

/**
 * ***********************************************
 * Function AdminDetail
 * ***********************************************
 * */
function AdminDetail($adminId) {
    $Sql = "SELECT USERNAME,ADMINEMAIL,MOBILE,CONCAT(FNAME,' ',LNAME) AS FNAME, DEPARTMENT FROM " . ADMIN . " WHERE ADMINID = '" . $adminId . "'";
    $ExecSql = mysql_query($Sql) or die(mysql_error() . ' Error in function AdminDetail()');
    if (mysql_num_rows($ExecSql) >= 1) {
        $Res = mysql_fetch_assoc($ExecSql);
        $ResDetails['userName'] = $Res['USERNAME'];
        $ResDetails['Email'] = $Res['ADMINEMAIL'];
        $ResDetails['name'] = $Res['FNAME'];
        $ResDetails['DEPARTMENT'] = $Res['DEPARTMENT'];
        $ResDetails['MOBILE'] = $Res['MOBILE'];
        return $ResDetails;
    } else {
        return 0;
    }
}

/**
 * ***********************************************
 * Function AdminDetail
 * ***********************************************
 * */
function UpdateAdmin($txtusername, $txtuserEmail, $txtFname, $userId) {
    $Sql = "UPDATE " . ADMIN . " SET
							USERNAME  	      	= '" . $txtusername . "',
							ADMINEMAIL 	      	= '" . $txtuserEmail . "',
							FNAME           		= '" . $txtFname . "'

							WHERE       ADMINID  =  '" . $userId . "'";
    $ExecSql = mysql_query($Sql) or die(mysql_error() . ' Error in function UpdateAdmin()');
    return 1;
}

/**
 * ***********************************************
 * Function UpdateAdminPssword
 * ***********************************************
 * */
function UpdateAdminPssword($adminpass, $oldpassword, $adminid) {
    $Sql = "UPDATE " . ADMIN . " SET
		ADMINPASSWORD = '" . md5($adminpass) . "'
		WHERE ADMINID = '" . $adminid . "' AND ADMINPASSWORD = '" . md5($oldpassword) . "'
		";
    $ExecSql = mysql_query($Sql) or die(mysql_error() . ' Error in function UpdateAdminPssword()');
    if (mysql_affected_rows()) {
        return 1;
    } else {
        return 2;
    }
}

/* * ***************builder detail********************* */

function BuilderDetail() {
    $qryBuilder = "SELECT * FROM " . RESI_BUILDER;
    $resBuilder = mysql_query($qryBuilder);
    $arrBuilder = array();
    while ($data = mysql_fetch_assoc($resBuilder)) {
        $arrBuilder['BUILDER_ID'][] = $data['BUILDER_ID'];
        $arrBuilder['BUILDER_NAME'][] = $data['BUILDER_NAME'];
        $arrBuilder['DESCRIPTION'][] = $data['DESCRIPTION'];
        $arrBuilder['AWARDS'][] = $data['AWARDS'];
        $arrBuilder['URL'][] = $data['URL'];
        $arrBuilder['BUILDER_IMAGE'][] = $data['BUILDER_IMAGE'];
        $arrBuilder['DISPLAY_ORDER'][] = $data['DISPLAY_ORDER'];
        $arrBuilder['META_TITLE'][] = $data['META_TITLE'];
        $arrBuilder['META_KEYWORDS'][] = $data['META_KEYWORDS'];
        $arrBuilder['META_DESCRIPTION'][] = $data['META_DESCRIPTION'];
        $arrBuilder['ENTITY'][] = $data['ENTITY'];
        $arrBuilder['ADDRESS'][] = $data['ADDRESS'];
        $arrBuilder['STREET'][] = $data['STREET'];
        $arrBuilder['LOCALITY'][] = $data['LOCALITY'];
        $arrBuilder['CITY'][] = $data['CITY'];
        $arrBuilder['PINCODE'][] = $data['PINCODE'];
        $arrBuilder['ESTABLISHED_DATE'][] = $data['ESTABLISHED_DATE'];
        $arrBuilder['CEO_MD_NAME'][] = $data['CEO_MD_NAME'];
        $arrBuilder['TOTAL_NO_OF_EMPL'][] = $data['TOTAL_NO_OF_EMPL'];
    }
    return $arrBuilder;
}

/* * ***************end builder detail********************* */

/* * ******builder list with id************* */

function BuilderArr() {
    $qryBuilder = "SELECT BUILDER_NAME,BUILDER_ID FROM " . RESI_BUILDER . " ORDER BY BUILDER_NAME ASC";
    $resBuilder = mysql_query($qryBuilder);
    $arrBuilder = array();
    while ($data = mysql_fetch_assoc($resBuilder)) {
        $arrBuilder[$data['BUILDER_ID']] = $data['BUILDER_NAME'];
    }
    return $arrBuilder;
}

/* * ********project insert************** */

function InsertProject($txtProjectName, $builderId, $cityId, $suburbId, $localityId, $txtProjectDescription, $txtAddress, $txtProjectDesc, $txtProjectSource, $project_type, $txtProjectLocation, $txtProjectLattitude, $txtProjectLongitude, $txtProjectMetaTitle, $txtMetaKeywords, $txtMetaDescription, $DisplayOrder, $Active, $Status, $txtProjectURL, $Featured, $txtDisclaimer, $payment, $no_of_towers, $no_of_flats, $pre_launch_date, $exp_launch_date, $eff_date_to, $special_offer, $display_order, $youtube_link, $bank_list, $price, $app, $approvals, $project_size, $no_of_lift, $powerBackup, $architect, $offer_heading, $offer_desc, $BuilderName, $power_backup_capacity, $no_of_villa, $eff_date_to_prom, $residential, $township, $plot, $open_space, $Booking_Status, $shouldDisplayPrice, $launchedUnits, $reasonUnlaunchedUnits, $identifyTownShip) {

    if ($project_type == '1') {
        $no_of_towers = $no_of_towers;
        $no_of_flats = $no_of_flats;
        $no_of_villa = '';
        $plot = '';
    } else if ($project_type == '2') {
        $no_of_towers = '';
        $no_of_flats = '';
        $no_of_villa = $no_of_villa;
        $plot = '';
    } else if ($project_type == '3') {
        $no_of_towers = $no_of_towers;
        $no_of_flats = $no_of_flats;
        $no_of_villa = $no_of_villa;
        $plot = '';
    } else if ($project_type == '4') {
        $no_of_towers = '';
        $no_of_flats = '';
        $no_of_villa = '';
        $plot = $plot;
    } else if ($project_type == '5') {
        $no_of_towers = '';
        $no_of_flats = '';
        $no_of_villa = $no_of_villa;
        $plot = $plot;
    } else if ($project_type == '6') {
        $no_of_towers = $no_of_towers;
        $no_of_flats = $no_of_flats;
        $no_of_villa = '';
        $plot = $plot;
    }

    $Completion = " Onwards";
    $Sql = "INSERT INTO " . RESI_PROJECT . " SET
        PROJECT_NAME  	      		= '" . d_($txtProjectName) . "',
        PROJECT_DESCRIPTION 	  	= '" . d_($txtProjectDescription) . "',
        PROJECT_ADDRESS	 	  		= '" . d_($txtAddress) . "',
        BUILDER_ID 	      			= '" . d_($builderId) . "',
        BUILDER_NAME 	      		= '" . d_($BuilderName) . "',
        CITY_ID	      				= '" . d_($cityId) . "',
        SUBURB_ID		 	      	= '" . d_($suburbId) . "',
        LOCALITY_ID		 	      	= '" . d_($localityId) . "',
        OPTIONS_DESC 	      		= '" . d_($txtProjectDesc) . "',
        PROJECT_TYPE_ID	      		= '" . d_($project_type) . "',
        LOCATION_DESC	 	      	= '" . d_($txtProjectLocation) . "',
        LATITUDE			 	    = '" . d_($txtProjectLattitude) . "',
        LONGITUDE		 	      	= '" . d_($txtProjectLongitude) . "',
        META_TITLE		 	      	= '" . d_($txtProjectMetaTitle) . "',
        META_KEYWORDS	 	      	= '" . d_($txtMetaKeywords) . "',
        META_DESCRIPTION 	      	= '" . d_($txtMetaDescription) . "',
        ACTIVE			 	      	= '" . d_($Active) . "',
        PROJECT_STATUS 	      		= '" . d_($Status) . "',
        PROJECT_URL		 	      	= '" . d_($txtProjectURL) . "',
        FEATURED			 	    = '" . d_($Featured) . "',
        COMPLETION_DATE	      		= '" . d_($Completion) . "',
        PAYMENT_PLAN				=	'" . $payment . "',
        NO_OF_TOWERS				=	'" . $no_of_towers . "',
        NO_OF_FLATS					=	'" . $no_of_flats . "',
        PRE_LAUNCH_DATE             =   '" . $pre_launch_date . "',
        EXPECTED_SUPPLY_DATE             =   '" . $exp_launch_date . "',
        LAUNCH_DATE					=	'" . $eff_date_to . "',
        BANK_LIST					=	'" . $bank_list . "',
        YOUTUBE_VIDEO				=	'" . $youtube_link . "',
        PRICE_LIST					=	'" . addslashes($price) . "',
        APPLICATION_FORM			=	'" . addslashes($app) . "',
        OFFER						=	'" . $special_offer . "',
        DISPLAY_ORDER				=	'" . $display_order . "',
        OFFER_HEADING				=	'" . $offer_heading . "',
        OFFER_DESC					=	'" . $offer_desc . "',
        APPROVALS					=	'" . $approvals . "',
        PROJECT_SIZE				=	'" . $project_size . "',
        NO_OF_LIFTS_PER_TOWER		=	'" . $no_of_lift . "',
        POWER_BACKUP				=	'" . $powerBackup . "',
        ARCHITECT_NAME				=	'" . $architect . "',
        POWER_BACKUP_CAPACITY		=	'" . $power_backup_capacity . "',
        NO_OF_VILLA					=	'" . $no_of_villa . "',
        PROMISED_COMPLETION_DATE	=	'" . $eff_date_to_prom . "',
        SOURCE_OF_INFORMATION		=	'" . $txtProjectSource . "',
        RESIDENTIAL					=	'" . $residential . "',
        TOWNSHIP					=	'" . $township . "',
        NO_OF_PLOTS					=	'" . $plot . "',
        OPEN_SPACE					=	'" . $open_space . "',
        BOOKING_STATUS 				=	'" . $Booking_Status . "',
        SHOULD_DISPLAY_PRICE        =     $shouldDisplayPrice,
        LAUNCHED_UNITS				=	'" . $launchedUnits . "',
        REASON_UNLAUNCHED_UNITS		=   '" . $reasonUnlaunchedUnits . "',
        SKIP_UPDATION_CYCLE  =  '$identifyTownShip',
        PROJECT_SMALL_IMAGE			=   '/on-request/sagar-kunj-apartments/defaultprojectsearchimage-small.png'";

    $ExecSql = mysql_query($Sql) or die(mysql_error() . ' Error in function InsertProject()');
    $pid = mysql_insert_id();

    audit_insert($pid, 'insert', 'resi_project', $pid);
    return $pid;

    /*     * ******************End Query for new project add in display order table************************************************ */
}

function d_($str) {
    return addslashes($str);
}

/* * ********audit insert********** */

function audit_insert($rowid, $action, $table, $projectId) {
    $qry_ins = "
			INSERT INTO audit
			SET
				DONE_BY			=	'" . $_SESSION['adminId'] . "',
				ACTION_DATE		=	now(),
				TABLE_NAME		=	'" . $table . "',
				ACTION			=	'" . $action . "',
				ROW_ID			=	'" . $rowid . "',
				PROJECT_ID      =   '" . $projectId . "'";
    $res_ins = mysql_query($qry_ins) OR DIE(mysql_error());
}

function AmenitiesList() {
    $qrAmenities = "SELECT * FROM " . AMENITIES_MASTER . " ORDER BY AMENITY_ID ASC";
    $resAmenities = mysql_query($qrAmenities) or die(mysql_error());
    $arrAmenities = array();
    while ($data = mysql_fetch_assoc($resAmenities)) {
        $arrAmenities[$data['AMENITY_ID']] = $data['AMENITY_NAME'];
    }
    return $arrAmenities;
}

/* * ******delete project*************** */

function DeleteProject($projectId) {
    $qryDel = "DELETE FROM " . RESI_PROJECT . " WHERE PROJECT_ID = '" . $projectId . "'";
    $res_Del = mysql_query($qryDel);
    if ($res_Del)
        return 1;
}

/* * *********function for fetch project detail************** */

function ProjectDetail($projectId) {
    $qrySel = "SELECT * FROM " . RESI_PROJECT . " 
               WHERE PROJECT_ID = '" . $projectId . "' and version = 'Cms'";
    $res_Sel = mysql_query($qrySel);
    $arrDetail = array();
    while ($data = mysql_fetch_assoc($res_Sel)) {
        array_push($arrDetail, $data);
    }
    return $arrDetail;
}


/* * *****************function for fetch project options detail by project id**************** */

function fetch_projectOptions($projectId) {
    $qryopt = "SELECT DISTINCT(BEDROOMS),OPTION_TYPE FROM " . RESI_PROJECT_OPTIONS . " 
        WHERE PROJECT_ID = '" . $projectId . "' and OPTION_CATEGORY = 'Actual'";
    $resopt = mysql_query($qryopt) or die(mysql_error());
    $arrOptions = array();
    while ($data = mysql_fetch_assoc($resopt)) {
        $bedroom = $data['OPTION_TYPE'] . "-" . $data['BEDROOMS'];
        array_push($arrOptions, $bedroom);
    }
    return $arrOptions;
}

function fetch_sourceofInformation() {
    $qryopt = "SELECT DISTINCT(SOURCE_NAME) FROM " . RESI_SOURCEOFINFORMATION . "";
    $resopt = mysql_query($qryopt) or die(mysql_error());
    $arrOptions = array();
    while ($data = mysql_fetch_assoc($resopt)) {
        array_push($arrOptions, $data);
    }
    return $arrOptions;
}

function insert_towerDetail($towerDetail, $projectId) {
    $qry_ins = "
				INSERT INTO " . RESI_PROJECT_TOWER_DETAILS . " 
								(TOWER_ID,PROJECT_ID,TOWER_NAME,NO_OF_FLOORS,REMARKS,STILT,NO_OF_FLATS,TOWER_FACING_DIRECTION,ACTUAL_COMPLETION_DATE)
								VALUES " . $towerDetail;

    $res_ins = mysql_query($qry_ins) OR DIE(mysql_error());
    if ($res_ins) {
        $last_id = mysql_insert_id();
        audit_insert($last_id, 'insert', 'resi_project_tower_details', $projectId);
        return 1;
    }
}

function insert_phase($projectId, $phasename, $launch_date, $completion_date, $remark, $phaseLaunched) {
    $qry_ins = "
                    INSERT INTO " . RESI_PROJECT_PHASE . "
                    SET
                        PROJECT_ID			=	'" . $projectId . "',
                        PHASE_NAME	        =	'" . $phasename . "',
                        LAUNCH_DATE  		=	'" . $launch_date . "',
                        COMPLETION_DATE  	=	'" . $completion_date . "',
                        REMARKS		        =	'" . $remark . "',
                        LAUNCHED		    =	'" . $phaseLaunched . "'";

    $res_ins = mysql_query($qry_ins) OR DIE(mysql_error());
    if ($res_ins) {
        $last_id = mysql_insert_id();
        audit_insert($last_id, 'insert', 'resi_project_phase', $projectId);
        return $last_id;
    }
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

function explodeBedroomSupplyLaunched($val) {
    $arr = array();
    $bedrooms = explode(',', $val);
    foreach ($bedrooms as $value) {
        $v = explode(':', $value);
        $arr[$v[0]] = array('supply'=>$v[1], 'launched'=>$v[2]);
    }
    return $arr;
}

function update_towers_for_project_and_phase($projectId, $phaseId, $tower_array) {
    $tower_ids = join(',', $tower_array);
    if ($tower_ids) {
        $qry_ins = "
				UPDATE " . RESI_PROJECT_TOWER_DETAILS . "
				SET
					PHASE_ID	=	COALESCE(
										CASE WHEN TOWER_ID IN (" . $tower_ids . ") THEN " . $phaseId . " ELSE NULL END,
										CASE WHEN PHASE_ID=" . $phaseId . " THEN NULL ELSE PHASE_ID END
									)
				WHERE
					PROJECT_ID	= '" . $projectId . "'";
    } else {
        $qry_ins = "
				UPDATE " . RESI_PROJECT_TOWER_DETAILS . "
				SET
					PHASE_ID	=	COALESCE(
										CASE WHEN PHASE_ID=" . $phaseId . " THEN NULL ELSE PHASE_ID END
									)
				WHERE
					PROJECT_ID	= '" . $projectId . "'";
    }

    $res_ins = mysql_query($qry_ins) OR DIE(mysql_error());
    if ($res_ins) {
        $last_id = mysql_insert_id();
        audit_insert($last_id, 'update', 'resi_project_tower_details', $projectId);
        return 1;
    }
}

function InsertProjectType($qrylast, $projectId) {
    $qry = "INSERT INTO " . PROJECT_OPTIONS . " (`PROJECT_ID`, `UNIT_NAME`, `UNIT_TYPE`, `SIZE`, `MEASURE`, `PRICE_PER_UNIT_AREA`, `PRICE_PER_UNIT_AREA_DP`,  `STATUS`, `BEDROOMS`, `CREATED_DATE`, `BATHROOMS` ,`PRICE_PER_UNIT_HIGH`,`PRICE_PER_UNIT_LOW`,`NO_OF_FLOORS`,`VILLA_PLOT_AREA`,`VILLA_NO_FLOORS`,`VILLA_TERRACE_AREA`,`VILLA_GARDEN_AREA`,`BALCONY`,`STUDY_ROOM`,`SERVANT_ROOM`,`POOJA_ROOM`,`LENGTH_OF_PLOT`,`BREADTH_OF_PLOT`,`TOTAL_PLOT_AREA`) values " . $qrylast; //die("here");
    
    $res = mysql_query($qry);
    $optionId = mysql_insert_id();
    audit_insert($optionId, 'insert', 'resi_project_options', $projectId);
    if ($res)
        return true;
    else
        return false;
    /*     * ***************End Query for price_history insertion******************** */
}

function RoomCategoryList() {
    $qrCategory = "SELECT * FROM " . ROOM_CATEGORY . " ORDER BY ROOM_CATEGORY_ID ASC";
    $resRoomcategory = mysql_query($qrCategory) or die(mysql_error());
    $arrroomCategory = array();
    while ($data = mysql_fetch_assoc($resRoomcategory)) {
        $arrroomCategory[$data['ROOM_CATEGORY_ID']] = $data['CATEGORY_NAME'];
    }
    return $arrroomCategory;
}

/* * *************function for insert specification********** */

function InsertSpecification($projectId, $master_bedroom_flooring, $other_bedroom_flooring, $living_room_flooring, $kitchen_flooring, $toilets_flooring, $balcony_flooring, $interior_walls, $exterior_walls, $kitchen_walls, $toilets_walls, $kitchen_fixtures, $toilets_fixtures, $main_doors, $internal_doors, $windows, $electrical_fitting, $others) {
    $Sql = "INSERT INTO " . RESI_PROJ_SPECIFICATION . " SET

					PROJECT_ID						=	'" . $projectId . "',
					FLOORING_MASTER_BEDROOM  	    = '" . d_($master_bedroom_flooring) . "',
					FLOORING_OTHER_BEDROOM 	  		= '" . d_($other_bedroom_flooring) . "',
					FLOORING_LIVING_DINING	 	  	= '" . d_($living_room_flooring) . "',
					FLOORING_KITCHEN 	      		= '" . d_($kitchen_flooring) . "',
					FLOORING_TOILETS 	      		= '" . d_($toilets_flooring) . "',
					FLOORING_BALCONY	      		= '" . d_($balcony_flooring) . "',
					WALLS_INTERIOR		 	      	= '" . d_($interior_walls) . "',
					WALLS_EXTERIOR		 	      	= '" . d_($exterior_walls) . "',
					WALLS_KITCHEN 	      			= '" . d_($kitchen_walls) . "',
					WALLS_TOILETS 	      			= '" . d_($toilets_walls) . "',
					DOORS_MAIN	      				= '" . d_($main_doors) . "',
					DOORS_INTERNAL	 	      		= '" . d_($internal_doors) . "',
					WINDOWS			 				= '" . d_($windows) . "',
					ELECTRICAL_FITTINGS		 	    = '" . d_($electrical_fitting) . "',
					FITTINGS_AND_FIXTURES_TOILETS	= '" . d_($toilets_fixtures) . "',
					FITTINGS_AND_FIXTURES_KITCHEN	= '" . d_($kitchen_fixtures) . "',
					OTHERS		 	      			= '" . d_($others) . "'";

    $ExecSql = mysql_query($Sql) or die(mysql_error() . ' Error in function InsertSpecification()');
    $pid = mysql_insert_id();

    audit_insert($pid, 'insert', 'resi_proj_specification', $projectId);
    return $pid;
}

/* * ***************** */

function ProjectBedroomDetail($projectId) {
    $qrySel = "SELECT OPTION_TYPE as UNIT_TYPE, GROUP_CONCAT(Distinct BEDROOMS) as BEDS FROM 
        " . RESI_PROJECT_OPTIONS . " WHERE PROJECT_ID = '" . $projectId . "' 
            GROUP BY OPTION_TYPE ORDER BY OPTION_TYPE";
    $res_Sel = mysql_query($qrySel);
    $sqlResults = array();
    while ($data = mysql_fetch_assoc($res_Sel)) {
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

function ProjectOptionDetail($projectId) {
    $columns = "P.OPTIONS_ID,P.PROJECT_ID,P.OPTION_NAME,P.OPTION_TYPE,P.SIZE,P.BEDROOMS,P.BATHROOMS,
        P.CREATED_AT,P.STUDY_ROOM,P.SERVANT_ROOM,P.BALCONY,P.POOJA_ROOM,P.VILLA_PLOT_AREA,
        P.VILLA_NO_FLOORS,P.VILLA_TERRACE_AREA,P.VILLA_GARDEN_AREA,P.CARPET_AREA,P.LENGTH_OF_PLOT,
        P.BREADTH_OF_PLOT";
    $qrySel = "SELECT
                    $columns,
                    GROUP_CONCAT(O.IMAGE_URL) FLOOR_IMAGES
                    FROM
                       " . RESI_PROJECT_OPTIONS . " P
                    LEFT JOIN " . RESI_FLOOR_PLANS . " O
                    ON
                            P.OPTIONS_ID = O.OPTION_ID
               WHERE P.PROJECT_ID = '" . $projectId . "'
               AND OPTION_CATEGORY = 'Actual'
               GROUP BY $columns
               ORDER BY P.SIZE ASC";
    $res_Sel = mysql_query($qrySel) or die(mysql_error());
    $arrDetail = array();
    while ($data = mysql_fetch_assoc($res_Sel)) {
        array_push($arrDetail, $data);
    }
    return $arrDetail;
}

function fetch_towerDetails($projectId) {
    $qrySel = "SELECT
                   t.TOWER_NAME,t.TOWER_ID,t.NO_OF_FLOORS,t.REMARKS,STILT,
                   t.NO_OF_FLATS,t.TOWER_FACING_DIRECTION,t.ACTUAL_COMPLETION_DATE,p.PHASE_ID,p.PHASE_NAME
                FROM
                   " . RESI_PROJECT_TOWER_DETAILS . " t 
                inner join phase_tower_mappings ptm
                on t.tower_id = ptm.tower_id
                inner join resi_project_phase p on ptm.phase_id = p.phase_id
                WHERE
                  t.PROJECT_ID = '" . $projectId . "' and p.version = 'Cms' 
                  and p.status = 'Active' ORDER BY t.TOWER_NAME ASC";
    $res_Sel = mysql_query($qrySel);
    $arrDetail = array();
    while ($data = mysql_fetch_assoc($res_Sel)) {
        array_push($arrDetail, $data);
    }
    return $arrDetail;
}

function fetch_towers_in_phase($projectId) {
    $qrySel = "SELECT TOWER_NAME,TOWER_ID FROM " . RESI_PROJECT_TOWER_DETAILS . "
        WHERE PROJECT_ID = '" . $projectId . "' 
            GROUP BY TOWER_NAME ORDER BY TOWER_NAME ASC";
    $res_Sel = mysql_query($qrySel);
    $arrDetail = array();
    while ($data = mysql_fetch_assoc($res_Sel)) {
        array_push($arrDetail, $data);
    }
    return $arrDetail;
}

function fetch_towerDetails_for_phase($projectId, $phaseId = 0) {
    // Returns towers that are available for phase to select. So, if tower1 is selected in phase1 and tower2 is selected by no other phase,
    // in that case - both tower1 and tower2 are available to phase1 to select from.

    $qrySel = "SELECT TOWER_NAME,a.TOWER_ID,PHASE_ID FROM " . RESI_PROJECT_TOWER_DETAILS . " a LEFT JOIN phase_tower_mappings b on (b.TOWER_ID = a.TOWER_ID and b.PHASE_ID = $phaseId) WHERE PROJECT_ID = $projectId ORDER BY TOWER_NAME";
    $res_Sel = mysql_query($qrySel);
    $arrDetail = array();
    while ($data = mysql_fetch_assoc($res_Sel)) {
        array_push($arrDetail, $data);
    }
    return $arrDetail;
}

function fetch_phaseDetails($projectId) {
    $qrySel = "SELECT PHASE_ID, PHASE_NAME FROM " . RESI_PROJECT_PHASE . "  WHERE 
        PROJECT_ID = '" . $projectId . "' and status = 'Active' and version = 'Cms' ORDER BY PHASE_NAME ASC";
    $res_Sel = mysql_query($qrySel);
    $arrDetail = array();
    while ($data = mysql_fetch_assoc($res_Sel)) {
        array_push($arrDetail, $data);
    }
    return $arrDetail;
}

function insert_towerconstructionStatus($towerId, $no_of_floors_completed, $remark, $expected_delivery_date, $effDt, $projectId) {
    $qry_ins = "
				INSERT INTO " . RESI_PROJ_TOWER_CONSTRUCTION_STATUS . "
				SET
					TOWER_ID				=	'" . $towerId . "',
					NO_OF_FLOORS_COMPLETED	=	'" . $no_of_floors_completed . "',
					GENERAL_REMARK			=	'" . $remark . "',
					EXPECTED_DELIVERY_DATE	=	'" . $expected_delivery_date . "',
					SUBMITTED_DATE   		=	'" . $effDt . "'";

    $res_ins = mysql_query($qry_ins) OR DIE(mysql_error());
    if ($res_ins) {
        $last_id = mysql_insert_id();
        audit_insert($last_id, 'insert', 'resi_proj_tower_construction_status', $projectId);
        return 1;
    }
}

/* * ********project insert************** */

function UpdateProject($txtProjectName, $builderId, $cityId, $suburbId, $localityId, $txtProjectDescription, $txtAddress, $txtProjectDesc, $txtProjectSource, $project_type, $txtProjectLocation, $txtProjectLattitude, $txtProjectLongitude, $txtProjectMetaTitle, $txtMetaKeywords, $txtMetaDescription, $DisplayOrder, $Active, $Status, $txtProjectURL, $Featured, $txtDisclaimer, $payment, $no_of_towers, $no_of_flats, $pre_launch_date, $exp_launch_date, $eff_date_to, $special_offer, $display_order, $youtube_link, $bank_list, $price, $app, $approvals, $project_size, $no_of_lift, $powerBackup, $architect, $offer_heading, $offer_desc, $BuilderName, $power_backup_capacity, $no_of_villa, $eff_date_to_prom, $ProjectId, $residential, $township, $plot, $open_space, $Booking_Status, $shouldDisplayPrice, $launchedUnits, $reasonUnlaunchedUnits, $identifyTownShip) {
    $Completion = " Onwards";
    $Sql = "UPDATE " . RESI_PROJECT . "
            SET
                PROJECT_NAME  	      		= '" . d_($txtProjectName) . "',
                PROJECT_DESCRIPTION 	  	= '" . d_($txtProjectDescription) . "',
                PROJECT_ADDRESS	 	  		= '" . d_($txtAddress) . "',
                BUILDER_ID 	      			= '" . d_($builderId) . "',
                BUILDER_NAME 	      		= '" . d_($BuilderName) . "',
                CITY_ID	      				= '" . d_($cityId) . "',
                SUBURB_ID		 	      	= '" . d_($suburbId) . "',
                LOCALITY_ID		 	      	= '" . d_($localityId) . "',
                OPTIONS_DESC 	      		= '" . d_($txtProjectDesc) . "',
                PROJECT_TYPE_ID	      		= '" . d_($project_type) . "',
                LOCATION_DESC	 	      	= '" . d_($txtProjectLocation) . "',
                LATITUDE			 	    = '" . d_($txtProjectLattitude) . "',
                LONGITUDE		 	      	= '" . d_($txtProjectLongitude) . "',
                META_TITLE		 	      	= '" . d_($txtProjectMetaTitle) . "',
                META_KEYWORDS	 	      	= '" . d_($txtMetaKeywords) . "',
                META_DESCRIPTION 	      	= '" . d_($txtMetaDescription) . "',
                ACTIVE			 	      	= '" . d_($Active) . "',
                PROJECT_STATUS 	      		= '" . d_($Status) . "',
                PROJECT_URL		 	      	= '" . d_($txtProjectURL) . "',
                FEATURED			 	    = '" . d_($Featured) . "',
                COMPLETION_DATE	      		= '" . d_($Completion) . "',
                PAYMENT_PLAN				=	'" . $payment . "',
                NO_OF_TOWERS				=	'" . $no_of_towers . "',
                NO_OF_FLATS					=	'" . $no_of_flats . "',
                PRE_LAUNCH_DATE                         =   '" . $pre_launch_date . "',
                EXPECTED_SUPPLY_DATE                    =   '" . $exp_launch_date . "',
                LAUNCH_DATE					=	'" . $eff_date_to . "',
                BANK_LIST					=	'" . $bank_list . "',
                YOUTUBE_VIDEO				=	'" . $youtube_link . "',
                PRICE_LIST					=	'" . addslashes($price) . "',
                APPLICATION_FORM			=	'" . addslashes($app) . "',
                OFFER						=	'" . $special_offer . "',
                OFFER_HEADING				=	'" . $offer_heading . "',
                OFFER_DESC					=	'" . $offer_desc . "',
                APPROVALS					=	'" . $approvals . "',
                PROJECT_SIZE				=	'" . $project_size . "',
                NO_OF_LIFTS_PER_TOWER		=	'" . $no_of_lift . "',
                POWER_BACKUP				=	'" . $powerBackup . "',
                ARCHITECT_NAME				=	'" . $architect . "',
                POWER_BACKUP_CAPACITY		=	'" . $power_backup_capacity . "',
                NO_OF_VILLA					=	'" . $no_of_villa . "',
                PROMISED_COMPLETION_DATE	=	'" . $eff_date_to_prom . "',
                SOURCE_OF_INFORMATION		=	'" . $txtProjectSource . "',
                RESIDENTIAL					=	'" . $residential . "',
                TOWNSHIP					=	'" . $township . "',
                NO_OF_PLOTS					=	'" . $plot . "',
                OPEN_SPACE					=	'" . $open_space . "',
                BOOKING_STATUS 				=	'" . $Booking_Status . "',
                SHOULD_DISPLAY_PRICE        =   '" . $shouldDisplayPrice . "',
                LAUNCHED_UNITS				=	'" . $launchedUnits . "',
                REASON_UNLAUNCHED_UNITS		=   '" . $reasonUnlaunchedUnits . "',
                SKIP_UPDATION_CYCLE  =  '$identifyTownShip'";
    $Sql.= " WHERE PROJECT_ID = '" . $ProjectId . "'";
    $ExecSql = mysql_query($Sql) or die(mysql_error() . ' Error in function UpdateProject()');

    audit_insert($ProjectId, 'update', 'resi_project', $ProjectId);
    return $ProjectId;

    /*     * ******************End Query for new project add in display order table************************************************ */
}

function specification($projectId) {
    $qrySel = "SELECT * FROM 
        seo_data WHERE table_id = '".$projectId ."' and table_name = 'resi_project'";
    $res_Sel = mysql_query($qrySel);
    $arrDetail = array();
    while ($data = mysql_fetch_assoc($res_Sel)) {
        array_push($arrDetail, $data);
    }
    if (count($arrDetail) == 0) {
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

$arrNotninty = array();
$arrDetail = array();
$arrninty = array();

function ProjectAmenities($projectId, &$arrNotninty, &$arrDetail, &$arrninty) {
    $qrySel = "SELECT * FROM " . RESI_PROJECT_AMENITIES . " WHERE PROJECT_ID = '" . $projectId . "'";
    $res_Sel = mysql_query($qrySel);
    $arrDetail = array();
    $cnt = 1;
    while ($data = mysql_fetch_assoc($res_Sel)) {
        array_push($arrDetail, $data);
        if ($data['AMENITY_ID'] != 99) {
            $arrNotninty[$data['AMENITY_ID']] = $data['AMENITY_DISPLAY_NAME'];
        } else {
            $arrninty[$cnt] = $data['AMENITY_DISPLAY_NAME'];
            $cnt++;
        }
    }
}

function deleteAmenities($projectId) {
    $qryDel = "DELETE FROM " . RESI_PROJECT_AMENITIES . " WHERE PROJECT_ID = '" . $projectId . "'";
    $res_Del = mysql_query($qryDel);
    if ($res_Del)
        return 1;
}

function deleteSpecification($projectId) {
    $qryDel = "DELETE FROM " . RESI_PROJ_SPECIFICATION . " WHERE PROJECT_ID = '" . $projectId . "'";
    $res_Del = mysql_query($qryDel);
    if ($res_Del)
        return 1;
}

function ProjectType($projectId) {
    global $arrProjectType_P;
    global $arrProjectType;
    global $arrProjectType_VA;

    $qry = "SELECT * FROM  " . RESI_PROJECT_OPTIONS . " WHERE PROJECT_ID = '" . $projectId . "' AND OPTION_CATEGORY = 'Actual'";
    $res = mysql_query($qry);

    while ($data = mysql_fetch_assoc($res)) {
        if ($data['OPTION_TYPE'] == 'Apartment') {
            $arrProjectType['OPTIONS_ID'][] = $data['OPTIONS_ID'];
            $arrProjectType['OPTION_NAME'][] = $data['OPTION_NAME'];
            $arrProjectType['OPTION_TYPE'][] = $data['OPTION_TYPE'];
            $arrProjectType['SIZE'][] = $data['SIZE'];
//            $arrProjectType['CARPET_AREA_INFO'][] = $data['CARPET_AREA_INFO'];
            $arrProjectType['BEDROOMS'][] = $data['BEDROOMS'];
            $arrProjectType['BATHROOMS'][] = $data['BATHROOMS'];
            $arrProjectType['CREATED_AT'][] = $data['CREATED_AT'];
            $arrProjectType['STUDY_ROOM'][] = $data['STUDY_ROOM'];
            $arrProjectType['SERVANT_ROOM'][] = $data['SERVANT_ROOM'];
            $arrProjectType['BALCONY'][] = $data['BALCONY'];
            $arrProjectType['POOJA_ROOM'][] = $data['POOJA_ROOM'];
            $arrProjectType['VILLA_PLOT_AREA'][] = $data['VILLA_PLOT_AREA'];
            $arrProjectType['VILLA_NO_FLOORS'][] = $data['VILLA_NO_FLOORS'];
            $arrProjectType['VILLA_TERRACE_AREA'][] = $data['VILLA_TERRACE_AREA'];
            $arrProjectType['VILLA_GARDEN_AREA'][] = $data['VILLA_GARDEN_AREA'];
            $arrProjectType['CARPET_AREA'][] = $data['CARPET_AREA'];
            $arrProjectType['DISPLAY_CARPET_AREA'][] = $data['DISPLAY_CARPET_AREA'];
        } else if ($data['OPTION_TYPE'] == 'Plot') {
            $arrProjectType_P['OPTIONS_ID'][] = $data['OPTIONS_ID'];
            $arrProjectType_P['OPTION_NAME'][] = $data['OPTION_NAME'];
            $arrProjectType_P['OPTION_TYPE'][] = $data['OPTION_TYPE'];
            $arrProjectType_P['SIZE'][] = $data['SIZE'];
            $arrProjectType_P['CREATED_AT'][] = $data['CREATED_AT'];
            $arrProjectType_P['LENGTH_OF_PLOT'][] = $data['LENGTH_OF_PLOT'];
            $arrProjectType_P['BREADTH_OF_PLOT'][] = $data['BREADTH_OF_PLOT'];
            $arrProjectType_P['STATUS'][] = $data['STATUS'];
        } else {
            $arrProjectType_VA['OPTIONS_ID'][] = $data['OPTIONS_ID'];
            $arrProjectType_VA['OPTION_NAME'][] = $data['OPTION_NAME'];
            $arrProjectType_VA['OPTION_TYPE'][] = $data['OPTION_TYPE'];
            $arrProjectType_VA['SIZE'][] = $data['SIZE'];
//            $arrProjectType_VA['CARPET_AREA_INFO'][] = $data['CARPET_AREA_INFO'];
            $arrProjectType_VA['BEDROOMS'][] = $data['BEDROOMS'];
            $arrProjectType_VA['BATHROOMS'][] = $data['BATHROOMS'];
            $arrProjectType_VA['CREATED_AT'][] = $data['CREATED_AT'];
            $arrProjectType_VA['STUDY_ROOM'][] = $data['STUDY_ROOM'];
            $arrProjectType_VA['SERVANT_ROOM'][] = $data['SERVANT_ROOM'];
            $arrProjectType_VA['BALCONY'][] = $data['BALCONY'];
            $arrProjectType_VA['POOJA_ROOM'][] = $data['POOJA_ROOM'];
            $arrProjectType_VA['VILLA_PLOT_AREA'][] = $data['VILLA_PLOT_AREA'];
            $arrProjectType_VA['VILLA_NO_FLOORS'][] = $data['VILLA_NO_FLOORS'];
            $arrProjectType_VA['VILLA_TERRACE_AREA'][] = $data['VILLA_TERRACE_AREA'];
            $arrProjectType_VA['VILLA_GARDEN_AREA'][] = $data['VILLA_GARDEN_AREA'];
            $arrProjectType_VA['CARPET_AREA'][] = $data['CARPET_AREA'];
            $arrProjectType_VA['DISPLAY_CARPET_AREA'][] = $data['DISPLAY_CARPET_AREA'];
        }
    }
}

function allProjectImages($projectId) {
    $sqlListingImages = "SELECT *  FROM " . PROJECT_PLAN_IMAGES . " WHERE  
        PROJECT_ID = " . $projectId . "";

    $data = mysql_query($sqlListingImages);
    $ImageDataListingArr = array();
    while ($dataListingArr = mysql_fetch_assoc($data)) {
		
		if($dataListingArr['tower_id']){
			$sql_towername = mysql_fetch_object(mysql_query("SELECT *  FROM " . RESI_PROJECT_TOWER_DETAILS . "
            WHERE
            TOWER_ID ='" . $dataListingArr['tower_id'] . "'")) or die(mysql_error());
            $dataListingArr['TOWER_NAME'] = $sql_towername->TOWER_NAME;
		}
        $ImageDataListingArr [] = $dataListingArr;
        
    }
    return $ImageDataListingArr;
}

/* * *****Fetch all floor plans images of a project***** */

function allProjectFloorImages($projectId) {
    $qryOpt = "SELECT OPTIONS_ID,OPTION_NAME as UNIT_NAME,SIZE,OPTION_TYPE as 
        UNIT_TYPE FROM " . RESI_PROJECT_OPTIONS . " WHERE PROJECT_ID = " . $projectId;
    $resOpt = mysql_query($qryOpt);

    $ImageDataListingArr = array();
    while ($dataOpt = mysql_fetch_assoc($resOpt)) {
        $sqlListingImages = "SELECT *  FROM " . RESI_FLOOR_PLANS . " WHERE  OPTION_ID ='" . $dataOpt['OPTIONS_ID'] . "'";
       
        $data = mysql_query($sqlListingImages);
        while ($dataListingArr = mysql_fetch_assoc($data)) {
            $dataListingArr['SIZE'] = $dataOpt['SIZE'];
            $dataListingArr['UNIT_NAME'] = $dataOpt['UNIT_NAME'];
            $dataListingArr['MEASURE'] = $dataOpt['MEASURE'];
            $dataListingArr['UNIT_TYPE'] = $dataOpt['UNIT_TYPE'];
            $ImageDataListingArr[] = $dataListingArr;
        }
    }
    
   return $ImageDataListingArr;
}

/* * *******search a tower exists or not in given array************** */

function searchTower($towerArray, $newTower) {
    $flg = 0;
    foreach ($towerArray as $k => $val) {
        if ($newTower == $val['TOWER_NAME']) {
            $flg = 1;
        }
    }
    if ($flg == 1)
        return 1;
    else
        return 0;
}

/* * *******search a phase exists or not in given array************** */

function searchPhase($phaseArray, $newPhaseName) {
    foreach ($phaseArray as $k => $val) {
        if (strtolower($newPhaseName) == strtolower($val['PHASE_NAME'])) {
            return $k;
        }
    }
    return -1;
}

/* * ********phase details with tower id************* */

function phaseDetailsForId($phaseId) {
    $sql = "SELECT * FROM " . RESI_PROJECT_PHASE . "
            WHERE
            PHASE_ID ='" . $phaseId . "' and version = 'Cms'";

    $data = mysql_query($sql);
    $arr = array();
    while ($dataarr = mysql_fetch_assoc($data)) {
        $arr [] = $dataarr;
    }
    return $arr;
}

/* * ********tower detail with tower id************* */

function towerDetailsForId($towerId) {
    $sql = "SELECT *  FROM " . RESI_PROJECT_TOWER_DETAILS . "
            WHERE
            TOWER_ID ='" . $towerId . "'";

    $data = mysql_query($sql);
    $arr = array();
    while ($dataarr = mysql_fetch_assoc($data)) {
        $arr [] = $dataarr;
    }
    return $arr;
}

/* * ******function for update phase detail********* */

function update_phase($projectId, $phaseId, $phasename, $launch_date, $completion_date, $remark, $phaseLaunched) {
    $qry_ins = "
                    UPDATE " . RESI_PROJECT_PHASE . "
                    SET
                    	PHASE_NAME  		  =	'" . $phasename . "',
                        LAUNCH_DATE  		  =	'" . $launch_date . "',
                        COMPLETION_DATE  	  =	'" . $completion_date . "',
                        REMARKS		          =	'" . $remark . "',
                        LAUNCHED			  =	'" . $phaseLaunched . "'		
                    WHERE
                        PROJECT_ID	= '" . $projectId . "'
                    AND
                        PHASE_ID   = '" . $phaseId . "'";

    $res_ins = mysql_query($qry_ins) OR DIE(mysql_error());
    if ($res_ins) {
        $last_id = mysql_insert_id();
        audit_insert($last_id, 'update', 'resi_project_phase', $projectId);
        return 1;
    }
}

/* * ******function for update tower detail********* */

function update_towerDetail($projectId, $TowerId, $no_of_floors, $stilt, $no_of_flats_per_floor, $towerface, $completion_date, $remark) {
    $qry_ins = "
				UPDATE " . RESI_PROJECT_TOWER_DETAILS . "
				SET
					NO_OF_FLOORS			=	'" . $no_of_floors . "',
					REMARKS					=	'" . $remark . "',
					STILT					=	'" . $stilt . "',
					NO_OF_FLATS			    =	'" . $no_of_flats_per_floor . "',
					TOWER_FACING_DIRECTION	=	'" . $towerface . "',
					ACTUAL_COMPLETION_DATE	=	'" . $completion_date . "'
				WHERE
					PROJECT_ID	= '" . $projectId . "'
				AND
					TOWER_ID   = '" . $TowerId . "'";

    $res_ins = mysql_query($qry_ins) OR DIE(mysql_error());
    if ($res_ins) {
        $last_id = mysql_insert_id();
        audit_insert($last_id, 'insert', 'resi_project_tower_details', $projectId);
        return 1;
    }
}

/* * *************tower of a project************ */

function towerDetail($towerId) {
    $sql = "SELECT *
                    FROM " . RESI_PROJ_TOWER_CONSTRUCTION_STATUS . "
            WHERE
                    TOWER_ID ='" . $towerId . "'  ORDER BY TOWER_CONST_STATUS_ID DESC LIMIT 1";

    $data = mysql_query($sql) or die(mysql_error());
    $arr = array();
    while ($dataarr = mysql_fetch_assoc($data)) {
        $arr [] = $dataarr;
    }
    return $arr;
}

/* * ***********FUNCTION FOR FETCH LATEST CONSTRUCTION STATUS************** */

function costructionDetail($projectId) {
   $qryPhase = "select * from resi_project_phase
   where project_id = $projectId and phase_type != 'Logical' and status = 'Active' and version = 'Cms' order by phase_id desc";
   $resPhase = mysql_query($qryPhase);
   $dataPhase = mysql_fetch_assoc($resPhase);
   if(mysql_num_rows($resPhase)>0) {
       $sql = "select * from resi_project_phase 
           where 
             phase_type != 'Logical'
           and 
             project_id = $projectId
           and status = 'Active' and version = 'Cms'
          ORDER BY completion_date desc LIMIT 1";
   }
   else{
        $sql = "select * from resi_project_phase 
           where 
             project_id = $projectId and status = 'Active' and version = 'Cms'";
   }   
    $data = mysql_query($sql) or die(mysql_error());
    $dataarr = mysql_fetch_assoc($data);
    return $dataarr;
}

/* * *********Builder management************* */

function InsertBuilder($txtBuilderName, $legalEntity, $txtBuilderDescription, $DisplayOrder, $address, $city, $pincode, $ceo, $employee, $date, $delivered_project, $area_delivered, $ongoing_project, $website, $revenue, $debt, $contactArr) {
  $Sql = "INSERT INTO " . RESI_BUILDER . " SET
        BUILDER_NAME  	   	     = '" . d_($txtBuilderName) . "',
        ENTITY  	   	     = '" . d_($legalEntity) . "',
        DESCRIPTION 	  	     = '" . d_($txtBuilderDescription) . "',
        DISPLAY_ORDER		     = '" . d_($DisplayOrder) . "',
        ADDRESS			     = '" . d_($address) . "',
        CITY_ID			     = '" . d_($city) . "',
        PINCODE			     = '" . d_($pincode) . "',
        CEO_MD_NAME                  = '" . d_($ceo) . "',
        TOTAL_NO_OF_EMPL             = '" . d_($employee) . "',
        TOTAL_NO_OF_DELIVERED_PROJECT= '" . $delivered_project . "',
        AREA_DELIVERED		     ='" . $area_delivered . "',
        ONGOING_PROJECTS	     = '" . $ongoing_project . "',
        WEBSITE			     ='" . $website . "',
        REVENUE			     ='" . $revenue . "',
        DEBT			     ='" . $debt . "',
        ESTABLISHED_DATE	     = '" . $date . "',
        updated_by                   = ".$_SESSION['adminId'].",
        created_at                   = now()";

    $ExecSql = mysql_query($Sql) or die(mysql_error() . ' Error in function InsertBuilder()');
    $lastId = mysql_insert_id();
    $list = '';

    $cnt = 0;
    foreach ($contactArr as $k => $v) {
        if ($v[$cnt] != '') {
            $name = $v[$cnt];
            $phone = $contactArr['Phone'][$cnt];
            $email = $contactArr['Email'][$cnt];
            $projects = $contactArr['Projects'][$cnt];

            $qry = "INSERT INTO " . BUILDER_CONTACT_INFO . "
                    SET
                            NAME			=	'" . $name . "',
                            BUILDER_ID		=	'" . $lastId . "',
                            PHONE			=	'" . $phone . "',
                            EMAIL			=	'" . $email . "',
                            SUBMITTED_DATE	=	now()";
            $res = mysql_query($qry) or die(mysql_error() . " Error in builder contact info");
            if($phone != '')
                $_SESSION['callerMessage'][] = file_get_contents(DND_SCUB.$phone);
        }
        $cnt++;
    }
    return $lastId;
}

/* * *****delete builders******** */

function DeleteBuilder($ID) {
    $Sql = "DELETE FROM " . RESI_BUILDER . " WHERE BUILDER_ID = '" . $ID . "'";

    $ExecSql = mysql_query($Sql) or die(mysql_error() . ' Error in function DeleteBuilder()');

    return 1;
}

/* * *****function for fetch last inserted or updated data in audit table ************ */

function AuditTblDataByTblName($tblName, $projectId) {
    $arcTable = $tblName . "_arc";

    $qry = "SELECT * FROM " . AUDIT . "
					WHERE
						(TABLE_NAME = '" . $tblName . "'
						OR
						TABLE_NAME = '" . $arcTable . "')
					AND
						 	PROJECT_ID = '" . $projectId . "'
					ORDER BY
						ACTION_DATE DESC LIMIT 1";
						
	$res = mysql_query($qry);
    $arrAudit = array();
    $data = mysql_fetch_assoc($res);
    array_push($arrAudit, $data);
    return $arrAudit;
}

/* * ******update builder if already exists************** */

function UpdateBuilder($txtBuilderName, $legalEntity, $txtBuilderDescription, $txtBuilderUrl, $DisplayOrder, $imgname, $builderid, $address, $city, $pincode, $ceo, $employee, $established, $delivered_project, $area_delivered, $ongoing_project, $website, $revenue, $debt, $contactArr, $oldbuilder, $image_id = 'NULL')
 {
	$Sql = "UPDATE " . RESI_BUILDER . " SET
				BUILDER_NAME  	   	     = '" . d_($txtBuilderName) . "',
                                ENTITY  	   	     = '" . d_($legalEntity) . "',				
                                DESCRIPTION 	  	     = '" . d_($txtBuilderDescription) . "',
				BUILDER_IMAGE 	   	     = '" . d_($imgname) . "',
				DISPLAY_ORDER		     = '" . d_($DisplayOrder) . "',
				ADDRESS			     = '" . d_($address) . "',
				CITY_ID			     = '" . d_($city) . "',
				PINCODE			     = '" . d_($pincode) . "',
				ESTABLISHED_DATE	     = '" . d_($established) . "',
				CEO_MD_NAME		     = '" . d_($ceo) . "',
				TOTAL_NO_OF_DELIVERED_PROJECT= '" . $delivered_project . "',
				AREA_DELIVERED		     ='" . $area_delivered . "',
				ONGOING_PROJECTS	     = '" . $ongoing_project . "',
				WEBSITE			     ='" . $website . "',
				REVENUE			     ='" . $revenue . "',
				DEBT			     ='" . $debt . "',
				TOTAL_NO_OF_EMPL	     = '" . d_($employee) . "'
				" . (($image_id == 'NULL') ? "" : ",SERVICE_IMAGE_ID                 = $image_id") . "
			WHERE	
				BUILDER_ID = '" . $builderid . "'"; //die("here");
          $qrySelect = "select * from project_builder_contact_mappings where builder_contact_id in (
        select id from builder_contacts where builder_id = $builderid )";
          $resSelect = mysql_query($qrySelect) or die(mysql_error());
          if( mysql_num_rows($resSelect) > 0) {
             $qrydel = "delete from project_builder_contact_mappings where builder_contact_id in (
             select id from builder_contacts where builder_id = $builderid)";
            mysql_query($qrydel) or die(mysql_error());
            $del = "DELETE from " . BUILDER_CONTACT_INFO . " WHERE BUILDER_ID = '" . $builderid . "'";
            mysql_query($del) or die(mysql_error());
          }
          $query_cont_ids = mysql_query("SELECT bc.ID FROM  " . BUILDER_CONTACT_INFO . "  bc INNER JOIN project_builder_contact_mappings bcm on bc.ID != bcm.builder_contact_id where builder_id = $builderid");
          
			while($row_ids = mysql_fetch_object($query_cont_ids)){
					mysql_query("delete from  " . BUILDER_CONTACT_INFO . " where ID = ".$row_ids->ID);
			}
          
    $cnt = 0;

    foreach ($contactArr['Name'] as $k => $v) {
        if ($v != '') {
            $name = $contactArr['Name'][$cnt];
            $phone = $contactArr['Phone'][$cnt];
            $email = $contactArr['Email'][$cnt];
            $projects = $contactArr['Projects'][$cnt];

            $qry = "INSERT INTO " . BUILDER_CONTACT_INFO . "
                    SET
                            NAME		=	'" . $name . "',
                            BUILDER_ID		=	'" . $builderid . "',
                            PHONE		=	'" . $phone . "',
                            EMAIL		=	'" . $email . "',
                            SUBMITTED_DATE	=	now()";
            mysql_query($qry) or die(mysql_error() . " Error in builder contact info");
            $lastId = mysql_insert_id();
            if($phone != '')
                $_SESSION['callerMessage'][] = file_get_contents(DND_SCUB.$phone);            
            $projectId = explode("#",$projects);
            
            if( count($projectId) >0 && !empty($projects)) {
				
                foreach($projectId as $val) {
					$qryIns = "insert into project_builder_contact_mappings(project_id,builder_contact_id) values($val,$lastId)";
                    mysql_query($qryIns) or die(mysql_error());
                }
            }
        }
        $cnt++;
    }

    $ExecSql = mysql_query($Sql) or die(mysql_error() . ' Error in function UpdateBuilder()');
    
    if( $ExecSql ) {
        if( $txtBuilderName != $oldbuilder ) { //code for update resi_project if builder name updates
            //  add entry to name change log
            addToNameChangeLog( 'builder', $builderid, $oldbuilder, $txtBuilderName );
            $qryProject ="UPDATE 
                            resi_project
                          SET
                            BUILDER_NAME = '".$txtBuilderName."' 
                          WHERE 
                            BUILDER_ID = $builderid";
            $resProject = mysql_query($qryProject);
        }
        return 1;
    }
    else 
       return 0;  
}

/*********************************/

function updateProjectPhase($pID, $phase, $stage = '', $revert = FALSE) {
    if($phase == phaseId_6)ProjectMigration::enqueProjectForMigration($pID, 'Genuine', $_SESSION['adminId']);
    
    mysql_query('begin');
    if ($phase != phaseId_6) {
        $Sql = "UPDATE " . RESI_PROJECT . " SET PROJECT_PHASE_ID = '" . $phase . "' 
            WHERE PROJECT_ID = '" . $pID . "' and version = 'Cms';";
    } else {
        $Sql = "UPDATE " . RESI_PROJECT . " SET PROJECT_PHASE_ID = '" . $phase . "', PROJECT_STAGE_ID = 1,
            UPDATION_CYCLE_ID = NULL WHERE PROJECT_ID = '" . $pID . "' and version = 'Cms';";
    }
    $ExecSql = mysql_query($Sql) or die(mysql_error() . ' Error in function updateProjectPhase()');
    if ($revert == TRUE)
        $phase = phaseId_8;

    $sql = "select max(HISTORY_ID) ID from project_stage_history where PROJECT_ID = $pID";
    $res = mysql_query($sql);
    $res = mysql_fetch_assoc($res);
    $last_hist_id = $res['ID'];
    
    if (!empty($last_hist_id)) {
        $ins = "
        INSERT INTO 
        project_stage_history 
        (HISTORY_ID,PROJECT_ID,PROJECT_PHASE_ID,PROJECT_STAGE_ID,DATE_TIME,ADMIN_ID, PREV_HISTORY_ID)
        VALUES 
        (NULL,'" . $pID . "','" . $phase . "','" . $stage . "',NOW(),'" . $_SESSION['adminId'] . "','" . $last_hist_id . "')";
    } else {
        $ins = "
        INSERT INTO 
        project_stage_history 
        (HISTORY_ID,PROJECT_ID,PROJECT_PHASE_ID,PROJECT_STAGE_ID,DATE_TIME,ADMIN_ID, PREV_HISTORY_ID)
        VALUES 
        (NULL,'" . $pID . "','" . $phase . "','" . $stage . "',NOW(),'" . $_SESSION['adminId'] . "', NULL)";
    }
   
    $r = mysql_query($ins);
    $sql = "update resi_project set MOVEMENT_HISTORY_ID = " . mysql_insert_id() . ", updated_by = ".$_SESSION['adminId']." 
        where PROJECT_ID = $pID and version = 'Cms';";
    mysql_query($sql) or die(mysql_error());
    mysql_query('commit');
    return 1;
}

function updationCycleTable() {
    $qry = "SELECT * FROM " . UPDATION_CYCLE . ";";
    $res = mysql_query($qry) or die(mysql_error() . ' Error in function UpdationCycleTable()');
    $labelArray = array();
    while ($data = mysql_fetch_assoc($res)) {
        array_push($labelArray, $data);
    }
    return $labelArray;
}

function changeLabel($pID, $val) {
    
}

/* * ************City management********************* */

function InsertCity($txtCityName, $txtCityUrl, $DisplayOrder, $status, $desc) {

    $Sql = "INSERT INTO " . CITY . " SET
			LABEL 	   			= '" . d_($txtCityName) . "',
			STATUS 	   			= '" . d_($status) . "',
			URL					= '" . d_($txtCityUrl) . "',
			DISPLAY_ORDER		= '" . d_($DisplayOrder) . "',
			DESCRIPTION			= '" . d_($desc) . "',
			updated_by			= '" .$_SESSION['adminId']."'";
    $ExecSql = mysql_query($Sql) or die(mysql_error() . ' Error in function InsertCity()');
    $lastId = mysql_insert_id();
    return $lastId;
}

function DeleteCity($ID) {
    $Sql = "DELETE FROM " . CITY . " WHERE CITY_ID = '" . $ID . "'";
    $ExecSql = mysql_query($Sql) or die(mysql_error() . ' Error in function DeleteCity()');

    return 1;
}

function ViewCityDetails($cityID) {
    $Sql = "SELECT * FROM " . CITY . " WHERE CITY_ID ='" . $cityID . "'";
    $ExecSql = mysql_query($Sql);

    if (mysql_num_rows($ExecSql) == 1) {

        $Res = mysql_fetch_assoc($ExecSql);
        $ResDetails['CITY_ID'] = $Res['CITY_ID'];
        $ResDetails['LABEL'] = $Res['LABEL'];
        $ResDetails['STATUS'] = $Res['STATUS'];
        $ResDetails['URL'] = $Res['URL'];
        $ResDetails['DISPLAY_ORDER'] = $Res['DISPLAY_ORDER'];
        $ResDetails['DESCRIPTION'] = $Res['DESCRIPTION'];
        return $ResDetails;
    } else {
        return 0;
    }
}

function getAllCities() {

    $allCities = "SELECT * FROM " . CITY . " WHERE 1 ORDER BY LABEL";
    $execQry = mysql_query($allCities);
    while ($cityArr = mysql_fetch_assoc($execQry)) {
        $allCityArr[] = $cityArr;
    }
    return $allCityArr;
}

function ViewSuburbDetails($suburbID) {
    $Sql = "SELECT * FROM " . SUBURB . " WHERE SUBURB_ID  ='" . $suburbID . "'";
    $ExecSql = mysql_query($Sql);

    if (mysql_num_rows($ExecSql) == 1) {

        $Res = mysql_fetch_assoc($ExecSql);
        $ResDetails['LOCALITY_ID'] = $Res['LOCALITY_ID'];
        $ResDetails['CITY_ID'] = $Res['CITY_ID'];
        $ResDetails['LABEL'] = $Res['LABEL'];
        $ResDetails['META_TITLE'] = $Res['META_TITLE'];
        $ResDetails['META_KEYWORDS'] = $Res['META_KEYWORDS'];
        $ResDetails['META_DESCRIPTION'] = $Res['META_DESCRIPTION'];
        $ResDetails['ACTIVE'] = $Res['STATUS'];
        $ResDetails['URL'] = $Res['URL'];
        $ResDetails['DESCRIPTION'] = $Res['DESCRIPTION'];
        $ResDetails['parent_suburb_id'] = $Res['parent_suburb_id'];
        return $ResDetails;
    } else {
        return 0;
    }
}

function DeleteBank($bank_id) {
    $sql = "DELETE FROM " . BANK_LIST . " WHERE BANK_ID = '" . $bank_id . "'";
    $execQry = mysql_query($sql) or die(mysql_error());
    return true;
}

function project_list($builderId) {
    $sql = "SELECT PROJECT_ID,PROJECT_NAME FROM " . RESI_PROJECT . " 
            WHERE 
                BUILDER_ID = '" . $builderId . "' 
                AND PROJECT_NAME != ''
                and version = 'cms'
                ORDER BY PROJECT_NAME ASC";
    $res = mysql_query($sql) or die(mysql_error());
    $arrBuilder = array();
    while ($data = mysql_fetch_assoc($res)) {
        array_push($arrBuilder, $data);
    }
    return $arrBuilder;
}

/* * ***********Query for insert other price********************** */

function InsertUpdateOtherPrice($arr,$projectId) {
    $arrInsertUpdateProject = array();
    $arrInsertUpdateProject['EDC_IDC'] = $arr['edc_idc_val1'];
    $arrInsertUpdateProject['EDC_IDC_TYPE'] = $arr['edc_idc'];
    $arrInsertUpdateProject['EDC_IDC_MEND_OPT'] = $arr['edc_idc_type1'];
    
    $arrInsertUpdateProject['LEASE_RENT'] = $arr['lease_rent_val1'];
    $arrInsertUpdateProject['LEASE_RENT_TYPE'] = $arr['lease_rent'];
    $arrInsertUpdateProject['LEASE_RENT_MEND_OPT'] = $arr['lease_rent_type1'];
    
    $arrInsertUpdateProject['OPEN_CAR_PARKING'] = $arr['open_car_parking1'];
    $arrInsertUpdateProject['OPEN_CAR_PARKING_TYPE'] = $arr['open_car_parking'];
    $arrInsertUpdateProject['OPEN_CAR_PARKING_MEND_OPT'] = $arr['open_car_parking_type1'];
    
    $arrInsertUpdateProject['CLOSE_CAR_PARKING_MEND_OPT'] = $arr['close_car_parking_type1'];
    $arrInsertUpdateProject['CLOSE_CAR_PARKING'] = $arr['close_car_parking1'];
    $arrInsertUpdateProject['CLOSE_CAR_PARKING_TYPE'] = $arr['close_car_parking'];
    
    $arrInsertUpdateProject['SEMI_CLOSE_CAR_PARKING'] = $arr['semi_close_car_parking1'];
    $arrInsertUpdateProject['SEMI_CLOSE_CAR_PARKING_TYPE'] = $arr['semi_close_car_parking'];
    $arrInsertUpdateProject['SEMI_CLOSE_CAR_PARKING_MEND_OPT'] = $arr['semi_close_car_parking_type1'];
    
    $arrInsertUpdateProject['CLUB_HOUSE'] = $arr['club_house1'];
    $arrInsertUpdateProject['CLUB_HOUSE_PSF_FIXED'] = $arr['club_house'];
    $arrInsertUpdateProject['CLUB_HOUSE_MEND_OPT'] = $arr['club_house_type1'];
    $arrInsertUpdateProject['IFMS'] = $arr['ifms1'];
    $arrInsertUpdateProject['IFMS_PSF_FIXED'] = $arr['ifms'];
    $arrInsertUpdateProject['IFMS_MEND_OPT'] = $arr['ifms_type1'];
    $arrInsertUpdateProject['POWER_BACKUP'] = $arr['power_backup1'];
    $arrInsertUpdateProject['POWER_BACKUP_PSF_FIXED'] = $arr['power_backup'];
    $arrInsertUpdateProject['POWER_BACKUP_MEND_OPT'] = $arr['power_backup_type1'];
    $arrInsertUpdateProject['LEGAL_FEES'] = $arr['legal_fees1'];
    $arrInsertUpdateProject['LEGAL_FEES_PSF_FIXED'] = $arr['legal_fees'];
    $arrInsertUpdateProject['LEGAL_FEES_MEND_OPT']  = $arr['legal_fees_type1'];
    $arrInsertUpdateProject['POWER_WATER'] = $arr['power_and_water1'];
    $arrInsertUpdateProject['POWER_WATER_PSF_FIXED'] = $arr['power_and_water'];
    $arrInsertUpdateProject['POWER_WATER_MEND_OPT'] = $arr['power_and_water_type1'];
    $arrInsertUpdateProject['MAINTENANCE_ADVANCE'] = $arr['maintenance_advance1'];
    $arrInsertUpdateProject['MAINTENANCE_ADVANCE_PSF_FIXED'] = $arr['maintenance_advance'];
    $arrInsertUpdateProject['MAINTENANCE_ADVANCE_MEND_OPT'] = $arr['maintenance_advance_type1'];
    $arrInsertUpdateProject['MAINTENANCE_ADVANCE_MONTHS'] = $arr['maintenance_advance_months'];
    $arrInsertUpdateProject['PLC'] = $arr['plc'];
    $arrInsertUpdateProject['FLOOR_RISE'] = $arr['floor_rise'];
    $arrInsertUpdateProject['OTHER_PRICING'] = trim($arr['other']);
    
    foreach($arrInsertUpdateProject as $key=>$val) {
        $select = "select attribute_name from table_attributes 
            where table_name = 'resi_project' and table_id = $projectId and attribute_name = '$key'";
        $qrySelect = mysql_query($select) or die(mysql_error());
        $insertUpdate = '';
        if(mysql_num_rows($qrySelect) > 0){
            $insertUpdate = "update table_attributes 
            set attribute_value = '$val' 
            where table_name = 'resi_project' and table_id = '$projectId' and attribute_name = '$key'";
        }
        else {
        $insertUpdate = "insert into table_attributes (table_name,table_id,attribute_name,attribute_value,
                updated_by) values ('resi_project', $projectId, '$key', '$val',".$_SESSION['adminId'].")";
        }
        $ins = mysql_query($insertUpdate) or die(mysql_error()." error in other pricing insert");
    }
    
    if($ins)
        return true;
    else
        return false;
}

/* * *****function to fetch project other pricing******* */

function fetch_other_price($projectId) {
    $qry = "SELECT * FROM 
        table_attributes WHERE table_id = '".$projectId ."' and table_name = 'resi_project'";
    $res = mysql_query($qry) or die(mysql_error());
    $arrOtherPrice = array();
    while ($data = mysql_fetch_assoc($res)) {
        $arrOtherPrice[0][$data['attribute_name']] = $data['attribute_value'];
    }
    return $arrOtherPrice;
}

/* * ***********Query for update other price********************** */

function UpdateOtherPrice($arr) {

    $Sql = "UPDATE " . RESI_PROJECT_OTHER_PRICING . "
				SET
					EDC_IDC 	  						= '" . d_($arr['edc_idc_val1']) . "',
					EDC_IDC_TYPE	 	  				= '" . d_($arr['edc_idc']) . "',
					EDC_IDC_MEND_OPT 					= '" . d_($arr['edc_idc_type1']) . "',
					LEASE_RENT							= '" . d_($arr['lease_rent_val1']) . "',
					LEASE_RENT_TYPE	 					= '" . d_($arr['lease_rent']) . "',
					LEASE_RENT_MEND_OPT	 				= '" . d_($arr['lease_rent_type1']) . "',
					OPEN_CAR_PARKING			 		= '" . d_($arr['open_car_parking1']) . "',
					OPEN_CAR_PARKING_TYPE			 	= '" . d_($arr['open_car_parking']) . "',
					OPEN_CAR_PARKING_MEND_OPT			= '" . d_($arr['open_car_parking_type1']) . "',
					CLOSE_CAR_PARKING					= '" . d_($arr['close_car_parking1']) . "',
					CLOSE_CAR_PARKING_TYPE				= '" . d_($arr['close_car_parking']) . "',
					CLOSE_CAR_PARKING_MEND_OPT			= '" . d_($arr['close_car_parking_type1']) . "',
					SEMI_CLOSE_CAR_PARKING				= '" . d_($arr['semi_close_car_parking1']) . "',
					SEMI_CLOSE_CAR_PARKING_TYPE			= '" . d_($arr['semi_close_car_parking']) . "',
					SEMI_CLOSE_CAR_PARKING_MEND_OPT		= '" . d_($arr['semi_close_car_parking_type1']) . "',
					CLUB_HOUSE							= '" . d_($arr['club_house1']) . "',
					CLUB_HOUSE_PSF_FIXED				= '" . d_($arr['club_house']) . "',
					CLUB_HOUSE_MEND_OPT					= '" . d_($arr['club_house_type1']) . "',
					IFMS								= '" . d_($arr['ifms1']) . "',
					IFMS_PSF_FIXED						= '" . d_($arr['ifms']) . "',
					IFMS_MEND_OPT						= '" . d_($arr['ifms_type1']) . "',
					POWER_BACKUP						= '" . d_($arr['power_backup1']) . "',
					POWER_BACKUP_PSF_FIXED				= '" . d_($arr['power_backup']) . "',
					POWER_BACKUP_MEND_OPT				= '" . d_($arr['power_backup_type1']) . "',
					LEGAL_FEES							= '" . d_($arr['legal_fees1']) . "',
					LEGAL_FEES_PSF_FIXED				= '" . d_($arr['legal_fees']) . "',
					LEGAL_FEES_MEND_OPT					= '" . d_($arr['legal_fees_type1']) . "',
					POWER_WATER							= '" . d_($arr['power_and_water1']) . "',
					POWER_WATER_PSF_FIXED				= '" . d_($arr['power_and_water']) . "',
					POWER_WATER_MEND_OPT				= '" . d_($arr['power_and_water_type1']) . "',
					MAINTENANCE_ADVANCE					= '" . d_($arr['maintenance_advance1']) . "',
					MAINTENANCE_ADVANCE_PSF_FIXED		= '" . d_($arr['maintenance_advance']) . "',
					MAINTENANCE_ADVANCE_MEND_OPT		= '" . d_($arr['maintenance_advance_type1']) . "',
					MAINTENANCE_ADVANCE_MONTHS			= '" . d_($arr['maintenance_advance_months']) . "',
					PLC									= '" . d_($arr['plc']) . "',
					FLOOR_RISE							= '" . d_($arr['floor_rise']) . "',
					OTHERS								= '" . d_($arr['other']) . "'
				
				WHERE
				
				PROJECT_ID  = '" . d_($arr['projectId']) . "'";

    $ExecSql = mysql_query($Sql) or die(mysql_error() . ' Error in function UpdateOtherPrice()');

    if ($ExecSql) {
        audit_insert($arr['row_id'], 'update', 'resi_project_other_pricing', $arr['projectId']);
        return 1;
    }
}

/* * *******function for chk tower already exists in a project******* */

function fetch_towerName($projectId) {
    $qrySel = "SELECT TOWER_NAME,TOWER_ID FROM " . RESI_PROJECT_TOWER_DETAILS . "  
					WHERE 
						PROJECT_ID = '" . $projectId . "'";
    $res_Sel = mysql_query($qrySel);
    $arrDetailTower = array();
    while ($data = mysql_fetch_assoc($res_Sel)) {
        $arrDetailTower[$data['TOWER_ID']] = $data['TOWER_NAME'];
    }
    return $arrDetailTower;
}

/* * ***********qry for delete tower detail*************** */

function deleteTowerDetail($projectId, $towerId) {
    $exp = explode(",", $towerId);
    $flgDel = 0;
    foreach ($exp as $val) {
        $qryDel = "DELETE FROM " . RESI_PROJECT_TOWER_DETAILS . " 
					WHERE
						PROJECT_ID = '" . $projectId . "'
					AND
						TOWER_ID   = '" . $val . "'";
        $resDel = mysql_query($qryDel) or die(mysql_error());
        if ($resDel) {
            audit_insert($val, 'delete', 'resi_project_tower_details', $projectId);
            $flgDel = 1;
        }
    }
    if ($flgDel == 1)
        return 1;
}

//function relaated to builder contact information fetch from builder contact onfo table
function BuilderContactInfo($builderid) {

    $qry_contact_info = "SELECT * FROM " . BUILDER_CONTACT_INFO . " WHERE BUILDER_ID = '" . $builderid . "'";
    $resContact = mysql_query($qry_contact_info);
    $arrContact = array();
    while ($dataContact = mysql_fetch_array($resContact)) {
		$qry = "select * from project_builder_contact_mappings 
             where builder_contact_id = '".$dataContact['ID']."'";
         $res = mysql_query($qry) or die(mysql_error());
		while($row_ids = mysql_fetch_object($res))
			$dataContact['PROJECTS'][] = $row_ids->project_id; 
		
        array_push($arrContact, $dataContact);
        
    }
            
    return $arrContact;
}

/* * ************function for calculate width and height of a image*********** */

function scaleDimensions($orig_width, $orig_height, $max_width, $max_height) {
    if ($orig_width < $max_width && $orig_height < $max_height) {
        return array($orig_width, $orig_height);
    }

    $ratiow = $max_width / $orig_width;
    $ratioh = $max_height / $orig_height;
    $ratio = min($ratiow, $ratioh);
    $width = intval($ratio * $orig_width);
    $height = intval($ratio * $orig_height);
    return array($width, $height);
}

/* * *******function for last upldated module date********* */
function lastUpdatedAuditDetail($projectId) {
                           
    $qry = "SELECT
                     b.DEPARTMENT, c.FNAME, a.updated_at
                    FROM
                       _t_resi_project a
                           JOIN
                       (SELECT
                            p.DEPARTMENT, MAX(a._t_transaction_id) as tid
                       FROM
                           _t_resi_project a
                       JOIN proptiger_admin p ON a.updated_by = p.ADMINID
                       WHERE
                           a.PROJECT_ID = $projectId
                       GROUP BY  p.DEPARTMENT) b ON (b.tid = a._t_transaction_id)
                           join
                       proptiger_admin c ON (c.ADMINID = a.updated_by)";
                       
    $result = mysql_query($qry);
    $arrData = array();
    $count = 0;
    while($res = mysql_fetch_object($result))
	{
		$arrData['resi_project'][$count]['name'] = $res->FNAME;
		$arrData['resi_project'][$count]['dept'] = $res->DEPARTMENT;
		$arrData['resi_project'][$count]['ACTION_DATE'] = $res->updated_at;
		
		$count++;
	}
                     
     $qry = "SELECT
                     b.DEPARTMENT, c.FNAME, a.updated_at
                    FROM
                       _t_resi_project_options a
                           JOIN
                       (SELECT
                            p.DEPARTMENT, MAX(a._t_transaction_id) as tid
                       FROM
                           _t_resi_project_options a
                       JOIN proptiger_admin p ON a.updated_by = p.ADMINID
                       WHERE
                           a.PROJECT_ID = $projectId
                       GROUP BY  p.DEPARTMENT) b ON (b.tid = a._t_transaction_id)
                           join
                       proptiger_admin c ON (c.ADMINID = a.updated_by)";
                 
    $result = mysql_query($qry);
	$count = 0;
    while($res = mysql_fetch_object($result))
	{
		$arrData['resi_project_options'][$count]['name'] = $res->FNAME;
		$arrData['resi_project_options'][$count]['dept'] = $res->DEPARTMENT;
		$arrData['resi_project_options'][$count]['ACTION_DATE'] = $res->updated_at;
		
		$count++;
	}
	
	 $qry = "SELECT
                     b.DEPARTMENT, c.FNAME, a.updated_at
                    FROM
                       _t_listing_prices a
                           JOIN
                       (SELECT
                            p.DEPARTMENT, MAX(lp._t_transaction_id) as tid
							FROM _t_listing_prices lp
							INNER JOIN listings lst
								 on lp.listing_id = lst.id 
							INNER JOIN resi_project_phase rpp
								 on lst.phase_id = rpp.phase_id
							JOIN proptiger_admin p ON lp.updated_by = p.ADMINID
							WHERE rpp.PROJECT_ID = $projectId
							GROUP BY  p.DEPARTMENT) b ON (b.tid = a._t_transaction_id)
                           join
                       proptiger_admin c ON (c.ADMINID = a.updated_by)";
                 
    $result = mysql_query($qry);
	$count = 0;
    while($res = mysql_fetch_object($result))
	{
		$arrData['listing_prices'][$count]['name'] = $res->FNAME;
		$arrData['listing_prices'][$count]['dept'] = $res->DEPARTMENT;
		$arrData['listing_prices'][$count]['ACTION_DATE'] = $res->updated_at;
		
		$count++;
	}     
	
	 $qry = "SELECT
                     b.DEPARTMENT, c.FNAME, a.updated_at
                    FROM
                       _t_project_availabilities a
                           JOIN
                       (SELECT p.DEPARTMENT , MAX(pal._t_transaction_id) as tid FROM _t_project_availabilities pal
						INNER JOIN project_supplies ps
							 on pal.project_supply_id = ps.id
						INNER JOIN listings lst
							 on ps.listing_id = lst.id 
						INNER JOIN resi_project_phase rpp
							 on lst.phase_id = rpp.phase_id
						JOIN proptiger_admin p ON pal.updated_by = p.ADMINID
						WHERE rpp.PROJECT_ID = $projectId
						GROUP BY  p.DEPARTMENT) b ON (b.tid = a._t_transaction_id)
                           join
                       proptiger_admin c ON (c.ADMINID = a.updated_by)";
                 
    $result = mysql_query($qry);
	$count = 0;
    while($res = mysql_fetch_object($result))
	{
		$arrData['project_availabilities'][$count]['name'] = $res->FNAME;
		$arrData['project_availabilities'][$count]['dept'] = $res->DEPARTMENT;
		$arrData['project_availabilities'][$count]['ACTION_DATE'] = $res->updated_at;
		
		$count++;
	}                        
	                   
     $qry = "SELECT
                     b.DEPARTMENT, c.FNAME, a.updated_at
                    FROM
                      _t_resi_project_tower a
                           JOIN
                       (SELECT
                            p.DEPARTMENT, MAX(a._t_transaction_id) as tid
                       FROM
                           _t_resi_project_tower a
                       JOIN proptiger_admin p ON a.updated_by = p.ADMINID
                       WHERE
                           a.PROJECT_ID = $projectId
                       GROUP BY  p.DEPARTMENT) b ON (b.tid = a._t_transaction_id)
                           join
                       proptiger_admin c ON (c.ADMINID = a.updated_by)";
                       
    $result = mysql_query($qry);
	$count = 0;
    while($res = mysql_fetch_object($result))
	{
		$arrData['resi_project_tower_details'][$count]['name'] = $res->FNAME;
		$arrData['resi_project_tower_details'][$count]['dept'] = $res->DEPARTMENT;
		$arrData['resi_project_tower_details'][$count]['ACTION_DATE'] = $res->updated_at;
		
		$count++;
	}
    
    $qry = " SELECT rp.updated_at, p.FNAME, p.DEPARTMENT                   
                    FROM
                       ".TABLE_ATTRIBUTES." rp
                          JOIN proptiger_admin p ON rp.updated_by = p.ADMINID
                       WHERE
                           rp.TABLE_ID = $projectId ORDER BY rp.updated_at Desc LIMIT 1";
                       
    $res = mysql_fetch_object(mysql_query($qry));
	if($res->FNAME){
		$arrData['resi_project_other_pricing']['name'] = $res->FNAME;
		$arrData['resi_project_other_pricing']['dept'] = $res->DEPARTMENT;
		$arrData['resi_project_other_pricing']['ACTION_DATE'] = $res->updated_at;
	}
    
     $qry = " SELECT rp.updated_at, p.FNAME, p.DEPARTMENT                   
                    FROM
                       _t_project_supplies rp
						  JOIN ".LISTINGS." lst ON rp.LISTING_ID = lst.ID
						  JOIN ".RESI_PROJECT_OPTIONS." rpo ON lst.OPTION_ID = rpo.OPTIONS_ID
                          JOIN proptiger_admin p ON rp.UPDATED_BY = p.ADMINID
                       WHERE
                           rpo.PROJECT_ID = $projectId ORDER BY rpo.UPDATED_AT Desc LIMIT 1";
                                             
    $result = mysql_query($qry);
	$count = 0;
    while($res = mysql_fetch_object($result))
	{
		$arrData['resi_proj_supply'][$count]['name'] = $res->FNAME;
		$arrData['resi_proj_supply'][$count]['dept'] = $res->DEPARTMENT;
		$arrData['resi_proj_supply'][$count]['ACTION_DATE'] = $res->updated_at;
		
		$count++;
	}
        
   return $arrData;
}

/* * ***********function for fetch fetch project calling links**************** */

function fetchProjectCallingLinks($projectId, $projectType, $audioLinkChk = '') {
    if ($audioLinkChk == '')
        $and = "AND d.AudioLink IS NOT NULL AND d.AudioLink != ''";
    else
        $and = "";
    $qry = "SELECT 
               d.AudioLink,a.FNAME,d.CampaignName,d.Remark,d.ContactNumber,d.StartTime,d.EndTime,p.BROKER_ID,p.CallId 
            FROM 
               (" . CALLDETAILS . " d LEFT JOIN " . CALLPROJECT . " p 
            ON
               d.CallId = p.CallId)
            LEFT JOIN
               " . ADMIN . " a
            ON 
               d.AgentId = a.ADMINID
            WHERE
               p.ProjectId = $projectId
            AND
               d.PROJECT_TYPE = '$projectType'";
    $res = mysql_query($qry) or die(mysql_error());
    $arrCallLink = array();
    if (mysql_num_rows($res) > 0) {
        while ($data = mysql_fetch_assoc($res)) {
            array_push($arrCallLink, $data);
        }
    }
    return $arrCallLink;
}

/* * ********Fetch history for all tables******** */
$arrProjectPriceAuditOld = array();
$$arrProjectAudit = array();
$arrProjectSupply = array();

function fetchColumnChanges($projectId, $stageName, $phasename, &$arrProjectPriceAuditOld, &$arrProjectAudit, &$arrProjectSupply) {
    $arrTblName = array("resi_project", "resi_project_options", "project_availabilities");
    $arrFields = array("_t_transaction_id", "_t_transaction_date", "_t_operation", "_t_user_id");

    foreach ($arrTblName as $table) {
        $auditTbl = "_t_$table";
        if ($auditTbl == '_t_project_availabilities') {
            $startTime = fetchStartTime($stageName, $phasename, $projectId);
            $inventoryEditHistory = ProjectAvailability::getProjectEditHistoryBeforeDate($projectId, $startTime);
            foreach ($inventoryEditHistory as $history) {
                if ($history['PHASE_NAME'] == '' || $history['PHASE_NAME'] == NULL)
                    $history['PHASE_NAME'] = 'noPhase';
                $arrProjectSupply[$history[PHASE_NAME]][$history[PROJECT_TYPE]][] = $history;

                if (trim($phasename) == 'newProject' AND trim($stageName) == 'newProject')
                    $startTime = NULL;
            }
        }
        else if ($auditTbl == '_t_resi_project_options') {
            $startTime = fetchStartTime($stageName, $phasename, $projectId);
            $fstDataOpt = array();
            $lstDataOpt = array();
            $selectOptions = "SELECT OPTIONS_ID 
							  FROM 
								 resi_project_options 
							  WHERE 
								 PROJECT_ID = $projectId";
            $resSelectOpt = mysql_query($selectOptions) or die(mysql_error());
            $arrOptId = array();
            while ($ids = mysql_fetch_assoc($resSelectOpt)) {
                array_push($arrOptId, $ids['OPTIONS_ID']);
            }

            foreach ($arrOptId as $val) {
                if ($startTime == NULL) {
                    $qryStartTime = "SELECT MIN(_t_transaction_date) as  _t_transaction_date
									FROM 
										$auditTbl 
									WHERE 
										PROJECT_ID = $projectId
									AND
										OPTIONS_ID = $val";
                    $resStartTime = mysql_query($qryStartTime) or die(mysql_error());
                    $dataStartTime = mysql_fetch_assoc($resStartTime);
                    $startTime = $dataStartTime['_t_transaction_date'];
                }

                $fstQryOpt = "SELECT OPTIONS_ID,UNIT_NAME,UNIT_TYPE,SIZE,PRICE_PER_UNIT_AREA,PRICE_PER_UNIT_AREA_DP,PRICE_PER_UNIT_AREA_FP,TOTAL_PLOT_AREA,NO_OF_FLOORS,VILLA_NO_FLOORS FROM $auditTbl
							WHERE
								PROJECT_ID = $projectId
							AND
								OPTIONS_ID = $val
							AND
								_t_transaction_date <= '$startTime'
							ORDER BY
								_t_transaction_id DESC LIMIT 1";
                $fstResOpt = mysql_query($fstQryOpt) or die(mysql_error());
                $fstDataOpt = mysql_fetch_assoc($fstResOpt);


                $lstQryOpt = "SELECT OPTIONS_ID,UNIT_NAME,UNIT_TYPE,SIZE,PRICE_PER_UNIT_AREA,PRICE_PER_UNIT_AREA_DP,PRICE_PER_UNIT_AREA_FP,TOTAL_PLOT_AREA,NO_OF_FLOORS,VILLA_NO_FLOORS FROM $auditTbl
							WHERE
								PROJECT_ID = $projectId
							AND
								OPTIONS_ID = $val
							AND
								_t_transaction_date < NOW()
							ORDER BY
								_t_transaction_id DESC LIMIT 1";
                $lstResOpt = mysql_query($lstQryOpt) or die(mysql_error());
                $lstDataOpt = mysql_fetch_assoc($lstResOpt);
                foreach ($lstDataOpt as $key => $val) {
                    $arrProjectPriceAuditOld[$key][] = trim($fstDataOpt[$key]);
                }
            }
        } else {
            $fstData = array();
            $lstData = array();
            $startTime = fetchStartTime($stageName, $phasename, $projectId);
            if ($startTime == NULL) {
                $qryStartTime = "SELECT MIN(_t_transaction_date) as  _t_transaction_date
								FROM
									$auditTbl
								WHERE
									PROJECT_ID = $projectId";
                $resStartTime = mysql_query($qryStartTime) or die(mysql_error());
                $dataStartTime = mysql_fetch_assoc($resStartTime);
                $startTime = $dataStartTime['_t_transaction_date'];
            }
            $fstQry = "SELECT * FROM $auditTbl 
					   WHERE
						 PROJECT_ID = $projectId
					   AND
						 _t_transaction_date <= '$startTime'
					   ORDER BY
						 _t_transaction_id DESC LIMIT 1";
            $fstRes = mysql_query($fstQry) or die(mysql_error());
            $fstData = mysql_fetch_assoc($fstRes);


            $lstQry = "SELECT * FROM $auditTbl
					   WHERE
						  PROJECT_ID = $projectId
					  AND
						 _t_transaction_date < NOW()
					  ORDER BY
						 _t_transaction_id DESC LIMIT 1";
            $lstRes = mysql_query($lstQry) or die(mysql_error());
            $lstData = mysql_fetch_assoc($lstRes);
            foreach ($lstData as $key => $val) {
                if ($val != $fstData[$key]) {
                    if (!in_array($key, $arrFields)) {
                        $arrProjectAudit[$table][$key]['new'] = trim($val);
                        $arrProjectAudit[$table][$key]['old'] = trim($fstData[$key]);
                    }
                }
            }
        }
    }
}

function fetchStartTime($stageName, $phasename, $projectId) {
    $whereClause = '';
    if (trim($phasename) == 'newProject' AND trim($stageName) == 'newProject') {
        $qryRevert = "SELECT * FROM project_stage_history
			WHERE
				PROJECT_ID = $projectId
			AND
				PROJECT_STAGE = 'newProject'
			AND
				PROJECT_PHASE = 'newProject'
			ORDER BY HISTORY_ID DESC";
        $resRevert = mysql_query($qryRevert);
        if (mysql_num_rows($resRevert) > 1) {
            $qryRevert = "SELECT DATE_TIME FROM project_stage_history
				WHERE
					HISTORY_ID< (SELECT HISTORY_ID FROM project_stage_history
									WHERE
										PROJECT_ID = $projectId
									AND
										PROJECT_STAGE = 'newProject'
									AND
										PROJECT_PHASE = 'newProject'
									ORDER BY HISTORY_ID DESC LIMIT 1)
				 ORDER BY HISTORY_ID DESC LIMIT 1";
            $resRevert = mysql_query($qryRevert);
            $dataStartTime = mysql_fetch_assoc($resRevert);
            $startTime = $dataStartTime['DATE_TIME'];
            return $startTime;
        }
        else
            return NULL;
    }
    elseif (trim($phasename) == 'audit1' AND trim($stageName) == 'newProject') {
        $whereClause = "(PROJECT_STAGE  = '$stageName' AND PROJECT_PHASE  = 'dcCallCenter')";
    } elseif (trim($phasename) == 'audit1' AND trim($stageName) == 'updationCycle') {
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
    $data = mysql_fetch_assoc($res);
    return $data['DATE_TIME'];
}

function addToNameChangeLog( $type, $id, $oldName, $newName ) {
    $checkQuery = "SELECT COUNT(*) AS PRESENT FROM `name_change_log` WHERE `$type` = '$id' AND `old_name` = '$oldName' AND `new_name` = '$newName'";
    $res = mysql_query( $checkQuery ) or die( mysql_error() );
    $count = mysql_fetch_assoc( $res );
    if ( $count['PRESENT'] == 0 ) {
        //  before adding delete entries will will create a loop in the table
        deleteLoop( $id, $oldName, $newName );

        //  add entry to database
        $insertQuery = "INSERT INTO `name_change_log` ($type, old_name, new_name, created_at) VALUES ('$id', '$oldName', '$newName', NOW())";
        mysql_query( $insertQuery ) or die( mysql_error() );
    }
}

function deleteLoop( $id, $oldName, $newName ) {
    $deleteQuery = "SELECT id FROM `name_change_log` WHERE id = '$id' AND old_name = '$newName' AND new_name = '$oldName'";
    $res = mysql_query( $deleteQuery );
    $idList = array();
    while( $__id = mysql_fetch_assoc( $res ) ) {
        $idList[] = $__id['id'];
    }
    $idList = implode( ', ', $idList );
    mysql_query( "DELETE FROM `name_change_log` WHERE id IN ( $idList )" );
}

/* * *****Insert update in redirect url table if update any url in builder,project,city,suburb and locality tables******** */

function insertUpdateInRedirectTbl($toUrl, $fromUrl) {
    $action = '';
    if ($fromUrl == $toUrl)
        return $action;
    $qrySel = "SELECT * FROM redirect_url_map WHERE FROM_URL = '$fromUrl'";
    $resSel = mysql_query($qrySel) or die(mysql_error() . " error");


    $qrySelTF = "SELECT * FROM redirect_url_map WHERE FROM_URL = '$toUrl' AND TO_URL = '$fromUrl'";
    $resSelTF = mysql_query($qrySelTF) or die(mysql_error() . " error");
    if (mysql_num_rows($resSelTF) > 0) {
        return $action;
    }

    $cyclicQryT = "SELECT * FROM redirect_url_map WHERE TO_URL = '$fromUrl'";
    $cyclicresT = mysql_query($cyclicQryT);

    if (mysql_num_rows($cyclicresT) > 0) {
        $qry = "UPDATE redirect_url_map
			SET
				TO_URL			=	'$toUrl',
				MODIFIIED_DATE	=	now(),
				MODIFIED_BY		=	" . $_SESSION['adminId'] . "
			WHERE
			   TO_URL = '$fromUrl'";
        $res = mysql_query($qry) or die(mysql_error());
    }

    if (mysql_num_rows($resSel) == 0) {
        $qry = "INSERT INTO redirect_url_map
				SET
					FROM_URL		=	'$fromUrl',
					TO_URL			=	'$toUrl',
					SUBMITTED_DATE	=	now(),
                                        modified_by	=	" . $_SESSION['adminId'].",
					SUBMITTED_BY	=	" . $_SESSION['adminId'];
        $action = 'Insertion';
    } else {
        $qry = "UPDATE redirect_url_map
			SET
				TO_URL			=	'$toUrl',
				MODIFIIED_DATE	=	now(),
				MODIFIED_BY		=	" . $_SESSION['adminId'] . "
			WHERE
				FROM_URL		=	'$fromUrl'";
		$action = 'Updation';
	}
			
	$res   = mysql_query($qry) or die(mysql_error());		
	return $action;
}

function urlCreaationDynamic($followStr,$name)
{
	$output = preg_replace('!\s+!', '-', $name);
	$url = strtolower($output);
	$url = $followStr.$url.'.php';
	return $url;
}

function updateProjectUrl($id,$tblName,$builderName)
{
	$where = '';
	$blder = '';
	if($tblName == 'builder')
	{
		$where .= " WHERE BUILDER_ID = '$id'";
		$blder = ", BUILDER_NAME = '".$builderName."'";
	}
	else if($tblName == 'locality')
	{
		$where .= " WHERE LOCALITY_ID = '$id'";
	}
	else if($tblName == 'city')
	{
		$where .= " WHERE CITY_ID = '$id'";
	}
	$qry = "SELECT PROJECT_ID,BUILDER_ID,CITY_ID,LOCALITY_ID,PROJECT_URL,PROJECT_NAME FROM ".RESI_PROJECT." $where";
	$res = mysql_query($qry) or die(mysql_error());
	while($data = mysql_fetch_assoc($res))
	{
		$builderDetail	=	fetch_builderDetail($data['BUILDER_ID']);
		$BuilderName	=	$builderDetail['BUILDER_NAME'];
		
		$localityDetail	=	ViewLocalityDetails($data['LOCALITY_ID']);
		$localityName   =   $localityDetail['LABEL'];
		
		$cityDetail	=	ViewCityDetails($data['CITY_ID']);
		$cityName   =   $cityDetail['LABEL'];
		
		$projectUrlText = $BuilderName." ".$data['PROJECT_NAME']." ".$localityName." ".$cityName;
		$url = urlCreaationDynamic('p-',$projectUrlText);
		
		$qryUp = "UPDATE ".RESI_PROJECT." SET PROJECT_URL = '".$url."'". $blder . $where."  AND PROJECT_ID = ".$data['PROJECT_ID'];
		$resUp = mysql_query($qryUp) or die(mysql_error());
		if($resUp)
		{
			insertUpdateInRedirectTbl($url,$data['PROJECT_URL']);
		}
	}
	return true;
}


function curlFetch($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_NOBODY, FALSE); // show the body 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
	$obj=curl_exec($ch);
	curl_close($ch);
	return $obj;
}

function getPrevMonthProjectData($projectId)
{
	global $analytics_credential;
	$usrn=$analytics_credential["username"];
	$psswd=$analytics_credential["password"];
	$tmstmp=time();

	$keytoken = hash_hmac ( 'sha1' , $tmstmp , $psswd );
    $url=$_SERVER['HTTP_HOST']."/app/v2/project-price-trend?username=".$usrn."&token=".$keytoken."&timestamp=".$tmstmp."&cached=false";
	$url=$url.'&project_ids[]='.$projectId;

	$obj=curlFetch($url);
	$json=json_decode($obj,true);
	$months=$json['prices'];
	return $months;
}

function getFlatAvailability($projectId)
{
	global $analytics_credential;
	$usrn=$analytics_credential["username"];
	$psswd=$analytics_credential["password"];
	$tmstmp=time();

	$keytoken = hash_hmac ( 'sha1' , $tmstmp , $psswd );
	
    $url=$_SERVER['HTTP_HOST']."/app/v2/project-inventory-trend?username=".$usrn."&token=".$keytoken."&timestamp=".$tmstmp."&cached=false";
	$url=$url.'&project_ids[]='.$projectId;

	$obj=curlFetch($url);
	$json=json_decode($obj,true);

	$months=$json['availability'];

	$final_list = array();

	foreach ($months as $mkey => $mvalue) {
		$mlist=array();
		foreach ($mvalue as $pkey => $pvalue) {
			foreach ($pvalue as $okey => $ovalue) {
				$mlist[$okey]=$ovalue;
			}
		}
		$final_list[$mkey]=$mlist;
	}
	return $final_list;
}
function projectDetailById($projectId){
    $qry = "SELECT * FROM ".RESI_PROJECT." WHERE PROJECT_ID = '".$projectId."' and version = 'Cms'";
    $res = mysql_query($qry) or die(mysql_error());
    $projectDetails = array();
    while ($data = mysql_fetch_array($res)) {
        $projectStage = $data['PROJECT_STAGE'];
        array_push($projectDetails, $data);
    }
    return $projectDetails;
}

/* * *****************function for fetch builder detail by builder id**************** */

function fetch_builderDetail($builderId) {
    $qrybuild = "SELECT * FROM " . RESI_BUILDER . " WHERE BUILDER_ID = '" . $builderId . "'";
    $resbuild = mysql_query($qrybuild) or die(mysql_error());
    $databuild = mysql_fetch_assoc($resbuild);
    return $databuild;
}
function ViewLocalityDetails($localityID) {
    $Sql = "SELECT l.locality_id, l.suburb_id, l.status,l.description,l.url,l.label,s.city_id,
            l.max_latitude,l.min_latitude,l.max_longitude,l.min_longitude
            FROM " . LOCALITY . " l inner join suburb s on l.suburb_id = s.suburb_id 
                WHERE LOCALITY_ID ='" . $localityID . "'";
    $ExecSql = mysql_query($Sql);

    if (mysql_num_rows($ExecSql) == 1) {
        $Res = mysql_fetch_assoc($ExecSql);
        $ResDetails['LOCALITY_ID'] = $Res['locality_id'];
        $ResDetails['CITY_ID'] = $Res['city_id'];
        $ResDetails['SUBURB_ID'] = $Res['suburb_id'];
        $ResDetails['LABEL'] = $Res['label'];
        $ResDetails['status'] = $Res['status'];
        $ResDetails['URL'] = $Res['url'];
        $ResDetails['DESCRIPTION'] = $Res['description'];
        $ResDetails['MAX_LATITUDE'] = $Res['max_latitude'];
        $ResDetails['MIN_LATITUDE'] = $Res['min_latitude'];
        $ResDetails['MAX_LONGITUDE'] = $Res['max_longitude'];
        $ResDetails['MIN_LONGITUDE'] = $Res['min_longitude'];
        return $ResDetails;
    } else {
        return 0;
    }
}
function projectBankList($projectId){
	$projectList = array();
	$Sql = "SELECT BANK_ID FROM " . PROJECT_BANKS . " WHERE PROJECT_ID = ".$projectId;
	$ExecSql = mysql_query($Sql);
	while($bank = mysql_fetch_array($ExecSql))
		$projectList[] = $bank['BANK_ID'];
	
	return  $projectList;	
}
function project_video_detail($projectId){
	
	$videoList = array();
	$Sql = "SELECT video_id, category, video_url FROM " . VIDEO_LINKS . " WHERE table_id = ".$projectId." ORDER BY video_id DESC";
	$ExecSql = mysql_query($Sql);
	while($video = mysql_fetch_array($ExecSql)){
		$videoList[$video['video_id']]['video_id'] = $video['video_id'];
		$videoList[$video['video_id']]['category'] = $video['category'];
		$videoList[$video['video_id']]['url'] = $video['video_url'];		
	}
	
	return  $videoList;	
}
function fetchProjectRedevelolpmentFlag($projectId){
	
	 $select = "select attribute_value from table_attributes 
            where table_name = 'resi_project' and table_id = $projectId and attribute_name = 'REDEVELOPMENT_PROJECT'";
     $qrySelect = mysql_query($select) or die(mysql_error());
     
     if($qrySelect)
		$flag = mysql_fetch_object($qrySelect);
     
     
     return ($flag->attribute_value)? "Yes" : "No";
	
}
function checkDuplicateVideoLink($projectId,$videoLinkUrl,$video_id=0){
	
	$condition = '';
	if($video_id)
		$condition = " AND video_id != '$video_id'";
	$videoLinkUrl = trim($videoLinkUrl);
	$Sql = "SELECT count(*) as cnt FROM " . VIDEO_LINKS . " WHERE table_id = '$projectId' AND video_url = '".$videoLinkUrl."' ".$condition;
	
	$qrySelect = mysql_query($Sql) or die(mysql_error());
     
     if($qrySelect)
		$vcount = mysql_fetch_object($qrySelect);
		
				
	return ($vcount->cnt)? $vcount->cnt : 0; 
}
function getLocalityAveragePrice($locId)
{
	global $analytics_credential;
	$usrn="cms";
	$psswd="Cms123!";
	$tmstmp=time();
	
	$locId = $locId - 50000;

	$keytoken = hash_hmac ( 'sha1' , $tmstmp , $psswd );
	$url = $_SERVER['HTTP_HOST']."/app/v1/locality-price-trend?username=".$usrn."&token=".$keytoken."&timestamp=".$tmstmp."&locality=".$locId."&duration=3&unittype[]=apartment&unittype[]=plot&unittype[]=villa&duration=1";
	
	$obj=curlFetch($url);
	$json=json_decode($obj,true);
	
	$average_price = 0;
	$total_price = 0;
	$cnt = 0;
	foreach($json['price_trend'] as $unit_type => $price_array){
		$count=0;
		foreach($price_array as $key=>$value){
			if($count == 0){
				$total_price += $value['mean'];
				$cnt++;
			}
			else
				continue;
		}
	}
	
	$average_price = round($total_price / $cnt);
	
	return $average_price ;
}
function checkDuplicateDisplayOrder($projectId,$display_order,$service_image_id=0, $currentPlanId =''){
	if($currentPlanId == '')
            $currentPlanId = '';
        else
            $currentPlanId = "and PROJECT_PLAN_ID != ".$currentPlanId;
	$condition = '';
	if($plan_id)
		$condition = " AND SERVICE_IMAGE_ID != '$service_image_id'";
	$display_order = mysql_real_escape_string($display_order);
	$Sql = "SELECT count(*) as cnt FROM " . PROJECT_PLAN_IMAGES . " WHERE 
            PROJECT_ID = '$projectId' AND display_order != '5' AND PLAN_TYPE = 'Project Image' 
                 $currentPlanId AND display_order = '".$display_order."' ".$condition;
	
	$qrySelect = mysql_query($Sql) or die(mysql_error());
	
	if($qrySelect)
		$vcount = mysql_fetch_object($qrySelect);
		
				
	return ($vcount->cnt)? $vcount->cnt : 0; 
}
function updateD_Availablitiy($projectId){
	
	$no_of_phases = 0;
	$condition = '';
	
	$phase_created = mysql_query("SELECT COUNT(*) as cnt FROM `resi_project_phase`  WHERE `resi_project_phase`.`version` = 'Cms' AND `resi_project_phase`.`PROJECT_ID` = '$projectId' AND `resi_project_phase`.`PHASE_TYPE` = 'Actual'  AND `resi_project_phase`.status = 'Active'") or die(mysql_error());
	
	if($phase_created)
		$no_of_phases = mysql_fetch_object($phase_created)->cnt;
	
	if($no_of_phases > 0)
		$condition = " AND (resi_project_phase.PHASE_TYPE = 'Actual')";
		
	$most_recent_updates = mysql_query("SELECT resi_project.PROJECT_ID, resi_project_phase.PHASE_ID, resi_project_phase.PHASE_TYPE, project_supplies.id as project_supply_id, project_availabilities.effective_month, project_availabilities.availability FROM `resi_project` INNER JOIN `resi_project_phase` ON `resi_project_phase`.`PROJECT_ID` = `resi_project`.`PROJECT_ID` AND (resi_project_phase.version ='Cms' and resi_project_phase.STATUS='Active') INNER JOIN `listings` ON `listings`.`phase_id` = `resi_project_phase`.`PHASE_ID` AND (listings.STATUS='Active') INNER JOIN `project_supplies` ON `project_supplies`.`listing_id` = `listings`.`id` AND `project_supplies`.`version` = 'Cms' left join project_availabilities on project_supplies.id=project_availabilities.project_supply_id WHERE `resi_project`.`version` = 'Cms' AND (resi_project.PROJECT_ID = '$projectId') ".$condition);
	
	$sql_max_effective_month =  mysql_query("SELECT max(project_availabilities.effective_month) as max_effective_month FROM `resi_project` INNER JOIN `resi_project_phase` ON `resi_project_phase`.`PROJECT_ID` = `resi_project`.`PROJECT_ID` AND (resi_project_phase.version ='Cms' and resi_project_phase.STATUS='Active') INNER JOIN `listings` ON `listings`.`phase_id` = `resi_project_phase`.`PHASE_ID` AND (listings.STATUS='Active') INNER JOIN `project_supplies` ON `project_supplies`.`listing_id` = `listings`.`id` AND `project_supplies`.`version` = 'Cms' left join project_availabilities on project_supplies.id=project_availabilities.project_supply_id WHERE `resi_project`.`version` = 'Cms' AND (resi_project.PROJECT_ID = '$projectId') ".$condition);
	
	$total_av = null;

 	if($most_recent_updates){
 		
		$max_effective_month = mysql_fetch_object($sql_max_effective_month)->max_effective_month;
	
		$count = 0;
		while($mysql_row = mysql_fetch_object($most_recent_updates)){
				
			if($mysql_row->effective_month == $max_effective_month && $mysql_row->availability !== null && $mysql_row->availability !== ''){	
				
				if($count == 0){
						$count++;
						$total_av = 0;
				}
				
				$total_av = $total_av + $mysql_row->availability;
			}
		}		
	}

	//update availability
	if(is_numeric($total_av)){
		mysql_query("UPDATE `resi_project` SET `resi_project`.`D_AVAILABILITY` = '$total_av' WHERE `resi_project`.`version` = 'Cms' AND `resi_project`.`PROJECT_ID` = '$projectId'");
	}else{
		mysql_query("UPDATE `resi_project` SET `resi_project`.`D_AVAILABILITY` = null WHERE `resi_project`.`version` = 'Cms' AND `resi_project`.`PROJECT_ID` = '$projectId'");
	}
    
	
	//update project booking status
	$booking_status = '';
	if($total_av === '' || $total_av === null || $total_av >0)
		$booking_status = 1;
	else
		$booking_status = 2;
			
	 mysql_query("UPDATE ".RESI_PROJECT_PHASE." SET BOOKING_STATUS_ID =".$booking_status." WHERE project_id = ".$projectId." and phase_type = 'Logical' and `version` = 'Cms'");
	 
	 //updating phase booking status
	 updatePhaseBookingStatus($projectId);
 

}
function updatePhaseBookingStatus($projectId){
	$sql_phases = mysql_query("select PHASE_ID from resi_project_phase where PROJECT_ID = '$projectId' and status = 'Active' and phase_type = 'Actual' and version = 'Cms'") or die(mysql_error());
	
	if($sql_phases){
		
		while($row_phase = mysql_fetch_object($sql_phases)){
		
				/////////////////////////////////updating phase's booking status////////////////////////////////////////////
				$sql_phase_avail = mysql_query("select project_supplies.launched, project_availabilities.availability, 
					project_availabilities.effective_month, project_availabilities.comment 
					FROM `project_supplies` 
					INNER JOIN `listings` ON `listings`.`id` = `project_supplies`.`listing_id` 
					AND `listings`.`status` = 'Active' 
					INNER JOIN `resi_project_phase` 
					ON `resi_project_phase`.`PHASE_ID` = `listings`.`phase_id` 
					AND `resi_project_phase`.`version` = 'Cms' 
					INNER JOIN `listings` `listings_project_supplies_join` 
					ON `listings_project_supplies_join`.`id` = `project_supplies`.`listing_id` 
					AND `listings_project_supplies_join`.`status` = 'Active' 
					INNER JOIN `resi_project_options` 
					ON `resi_project_options`.`OPTIONS_ID` = `listings_project_supplies_join`.`option_id` 
					left join project_availabilities 
					on project_supplies.id = project_availabilities.project_supply_id 
					WHERE `project_supplies`.`version` = 'Cms' 
					AND (resi_project_phase.PROJECT_ID = '$projectId' 
					and resi_project_phase.PHASE_ID = '$row_phase->PHASE_ID')
					order by project_availabilities.effective_month desc") or die(mysql_error());
					
				$max_effetcive_month = ''; $total_avail = null; $phase_booking_status = '';
				$count = 0;
				while($row_phase_avail = mysql_fetch_object($sql_phase_avail)){
					
					if(!$max_effetcive_month)
						$max_effetcive_month = $row_phase_avail->effective_month;
						
					if($max_effetcive_month == $row_phase_avail->effective_month && $row_phase_avail->availability !== null && $row_phase_avail->availability !== ''){
						if($count == 0){
								$count++;
								$total_av = 0;
						}
						$total_avail = $row_phase_avail->availability + $total_avail;
					}
					
										
				}
				
				if($total_avail === '' || $total_avail === null || $total_avail >0)
					$phase_booking_status = 1;
				else
					$phase_booking_status = 2;
						
				mysql_query("update resi_project_phase set booking_status_id = '$phase_booking_status' where phase_id = '$row_phase->PHASE_ID' and phase_type = 'Actual' and version = 'Cms'") or die (mysql_error());
	
			   ////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			
		}
		
	}
	
}
function fetch_project_booking_status($projectId){
		
		$booking_status = mysql_fetch_object(mysql_query("select booking_status_id from resi_project_phase where project_id = '$projectId' and phase_type = 'Logical' and version = 'Cms'"));
		
		return $booking_status->booking_status_id;
}
function project_aliases_detail($projectID){
	$project_alias = mysql_query("SELECT * FROM project_aliases WHERE original_project_id='$projectID'") or die(mysql_error());
	if($project_alias)
		$project_alias = mysql_fetch_object($project_alias);
	return ($project_alias)?$project_alias:0;
}

function ViewUserDetails($ID){
	$Sql = "SELECT ADMINID,EMP_CODE,FNAME,LNAME,USERNAME,ADMINPASSWORD,ADMINEMAIL
                ,MOBILE,ADMINADDDATE,ADMINLASTLOGIN,REGION,STATUS,DEPARTMENT
                ,ROLE,JOINING_DATE,RESIGNATION_DATE,CLOUDAGENT_ID FROM ".ADMIN." WHERE ADMINID ='".$ID."'";
	$ExecSql = mysql_query($Sql);

	if(mysql_num_rows($ExecSql)==1)	{

		$Res = mysql_fetch_assoc($ExecSql);
		$ResDetails['ADMINID'] 		 	=  $Res['ADMINID'];
		$ResDetails['EMP_CODE']			=  $Res['EMP_CODE'];
		$ResDetails['FNAME'] 		 	=  $Res['FNAME'];
		$ResDetails['LNAME'] 		 	=  $Res['LNAME'];
		$ResDetails['USERNAME']  		=  $Res['USERNAME'];
		$ResDetails['ADMINPASSWORD'] 	=  $Res['ADMINPASSWORD'];
		$ResDetails['ADMINEMAIL'] 		=  $Res['ADMINEMAIL'];
		$ResDetails['MOBILE']  			=  $Res['MOBILE'];
		$ResDetails['ADMINADDDATE'] 	=  $Res['ADMINADDDATE'];
		$ResDetails['ADMINLASTLOGIN'] 	=  $Res['ADMINLASTLOGIN'];
		$ResDetails['REGION'] 			=  $Res['REGION'];
		$ResDetails['STATUS'] 		 	=  $Res['STATUS'];
		$ResDetails['DEPARTMENT'] 		=  $Res['DEPARTMENT'];
		$ResDetails['ROLE'] 		=  $Res['ROLE'];
		$ResDetails['JOINING_DATE'] 	=  $Res['JOINING_DATE'];
		$ResDetails['RESIGNATION_DATE']	=  $Res['RESIGNATION_DATE'];
                $ResDetails['CLOUDAGENT_ID'] = $Res['CLOUDAGENT_ID'];
		return $ResDetails;
	}
	else
	{
		return 0;
	}
}
function update_remark_status($cid,$type="Audit2",$status="Read"){
	$sql = "UPDATE comments_history SET status = '$status' WHERE comment_id='$cid' AND comment_type='$type'";
	mysql_query($sql) or die(mysql_error());	
}
function fetch_project_BSP($projectId){
	$bspArr = array();
	$sqlResult = mysql_query("select city.max_price_per_unit, city.min_price_per_unit from city 
			  left join suburb on city.city_id = suburb.city_id
			  left join locality on suburb.suburb_id = locality.suburb_id
			  left join resi_project on locality.locality_id = resi_project.locality_id
			   where resi_project.project_id = '$projectId'");
	if($sqlResult){
		$bsp = mysql_fetch_object($sqlResult);
		$bspArr['min'] = $bsp->min_price_per_unit;
		$bspArr['max'] = $bsp->max_price_per_unit;		
	}
	return $bspArr;
}

function updateBuilderIdForRevenueAssurance($oldBuilderId, $newBuilderId) {
    $sql = "UPDATE proptiger.BUILDER_AGREEMENT SET BUILDER_ID = '".$newBuilderId."' WHERE BUILDER_ID = '".$oldBuilderId."'";
    mysql_query($sql);
}
?>

