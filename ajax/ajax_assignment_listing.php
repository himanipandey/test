<?php

include("../smartyConfig.php");
include("../appWideConfig.php");
include("../dbConfig.php");
include("../httpful.phar");
include("../includes/configs/configs.php");
include("../function/functions_assignments.php");

$page = filter_input(INPUT_GET, "page");
$size = filter_input(INPUT_GET, "size");
$projectId = filter_input(INPUT_GET, "project");
$listingId = filter_input(INPUT_GET, "listingId");
$cityId = filter_input(INPUT_GET, "city");
$search_term = filter_input(INPUT_GET, "search_term");
$search_value = filter_input(INPUT_GET, "search_value");
$search_range = filter_input(INPUT_GET, "search_range");
$range_from = filter_input(INPUT_GET, "range_from");
$range_to = filter_input(INPUT_GET, "range_to");
$gpid = filter_input(INPUT_GET, "gpid");
$current_user_role = filter_input(INPUT_GET, "current_user_role");
$date_filter['error_msg'] = filter_input(INPUT_GET, "error_msg");
$date_filter['frmdate'] = filter_input(INPUT_GET, "frmdate");
$date_filter['todate'] = filter_input(INPUT_GET, "todate");
$date_filter['date_type'] = filter_input(INPUT_GET, "date_type");
$current_user = filter_input(INPUT_GET, "current_user");
$download = filter_input(INPUT_GET, "download");


$filterArr = array();

$start = $page * $size;

$size = 200000; //default maximum


if (isset($cityId) && !empty($cityId) && ($cityId != "null") && ($cityId != "")) {
    $filterArr["and"][] = array("equal" => array("cityId" => $cityId));
}
if (isset($projectId) && !empty($projectId) && ($projectId != "null") && ($projectId != "")) {
    $filterArr["and"][] = array("equal" => array("projectId" => $projectId));
}
if (isset($listingId) && !empty($listingId) && ($listingId != "null") && ($listingId != "")) {
    $filterArr["and"][] = array("equal" => array("listingId" => $listingId));
}
if (isset($search_term) && !empty($search_term) && ($search_term != "null") && ($search_term != "")) {
    $filterArr["and"][] = array("equal" => array($search_term => $search_value));
}
if (isset($search_range) && !empty($search_range) && ($search_range != "null") && ($search_range != "")) {
    if ($range_from != "" || $range_to != "") {
        $tempRange["range"][$search_range]["from"] = ($range_from != "") ? (int) $range_from : 1;
    }
    if ($range_to != "") {
        $tempRange["range"][$search_range]["to"] = (int) $range_to;
    }
    $filterArr["and"][] = $tempRange;
}
$gpidFilter = "";
if (isset($gpid) && $gpid != "") {
    $gpidFilter = "gpid=" . $gpid . "&";
}
if (!$filterArr) {
    $admin_city_array = json_decode($_REQUEST['admin_cities']);
    $filterArr = array("and" => array(array("equal" => array("cityId" => $admin_city_array))));
}

$filter = json_encode($filterArr);
if (in_array($current_user_role, array('photoGrapher', 'reToucher'))) {
    $filter = '{}';
}


$sort = '"sort":{"field":"listingId","sortOrder":"DESC"}';
$fields = '"fields":["imageCount","verified","description","seller","id","fullName","currentListingPrice","pricePerUnitArea","price","otherCharges","property","project","locality","suburb","city","label","name","builder","unitName","size","unitType","createdAt","projectId","propertyId","phaseId","updatedBy","sellerId","jsonDump","remark","homeLoanBankId","flatNumber","noOfCarParks","negotiable","transferCharges","plc","listingAmenities","amenity","amenityMaster","masterAmenityIds","floor","latitude","longitude","amenityDisplayName","isDeleted","bedrooms","bathrooms","amenityId","imagesCount","listingId","bookingStatusId","facingId","towerId"]}';
$uriListing = RESALE_LISTING_API_V2_URL . '?' . $gpidFilter . 'selector={"paging":{"start":' . $start . ',"rows":' . $size . '},"filters":' . $filter . "," . $sort . "," . $fields . '}';
//die($uriListing);
$tbsorterArr = array();
try {
    $responseLists = \Httpful\Request::get($uriListing)->send();
    if ($responseLists->body->statusCode == "2XX") {
        $data = $responseLists->body->data;
        $tbsorterArr['total_rows'] = $responseLists->body->totalCount;
        $tbsorterArr['headers'] = array("Serial", "Listing Id", "City", "Broker Name", "Project", "Listing", "Price", "Created Date", "Photo", "Verified", "Save", "Delete");
        $tbsorterArr['rows'] = array();
        $listings_ids = array();
        //print_r($data);
        foreach ($data as $index => $row) {
            $listings_ids[] = $row->id;
            $brokerName = "";
            $row->sellerId->id = $row->sellerId;
            if ($row->sellerId) {
                list($row->seller->brokerId, $row->seller->brokerName) = getBroker($row->sellerId);
            }

            if ($current_user_role == 'reToucher') { //ReToucher
                $data_rows = array(
                    "<a href='http://cms.localhost.com/listing_img_add.php?listing_id=" . $row->id . "' ><img src='../images/upload_image.png' title='Upload Images' width=25/></a>",
                    $start + $index + 1,
                    $row->id,
                    $row->property->project->locality->suburb->city->label,
                    $row->property->project->locality->label,
                    "",
                    "",
                    "",
                    "",
                    "<input type='button' class='page-button' name='touchup-listing' onclick='touchup_listing(" . $row->id . ")' value='Complete'>"
                );
            } elseif ($current_user_role == 'photoGrapher') { //Photographer
                $data_rows = array(
                    "<input type='checkbox' value='" . $row->id . "' class='assign_check' name='" . $row->property->unitName . "-" . $row->property->size . "-" . $row->property->unitType . "' id='assign_check-" . $index . "'>",
                    $start + $index + 1,
                    $row->id,
                    $row->property->project->locality->suburb->city->label,
                    $row->property->project->locality->label,
                    "",
                    "",
                    "",
                    "",
                    "<input type='button' class='page-button' name='edit-listing' onclick='verify_scheduling(" . $row->id . ")' value='Edit'>"
                );
            } elseif ($current_user_role == 'fieldManager') { //Field Manager
                $data_rows = array(
                    "<input type='checkbox' value='" . $row->id . "' class='assign_check' name='" . $row->property->unitName . "-" . $row->property->size . "-" . $row->property->unitType . "' id='assign_check-" . $index . "'>",
                    $start + $index + 1,
                    $row->id,
                    $row->property->project->locality->suburb->city->label,
                    $row->property->project->locality->label,
                    "Not Assigned",
                    "",
                    "",
                    "",
                    "",
                    ""
                );
            } else { // RM & CRM
                $data_rows = array(
                    "<input type='checkbox' value='" . $row->id . "' class='schedule_check' name='schedule_check-" . $index . "' id='schedule_check-" . $index . "'>",
                    $start + $index + 1,
                    $row->id,
                    $row->property->project->locality->suburb->city->label,
                    $row->property->project->locality->label,
                    $row->seller->brokerName,
                    $row->property->project->name . ", " . $row->property->project->builder->name,
                    $row->property->unitName . "-" . $row->property->size . "-" . $row->property->unitType,
                    "Not Done",
                    "",
                    "",
                    "",
                    ""
                );
            }


            array_push($tbsorterArr['rows'], $data_rows);
        }
    }
} catch (Exception $ex) {
    die($ex->getMessage());
}
//print_r($tbsorterArr['rows']);
//print_r($listings_ids);
if ($listings_ids) {
    $all_records = append_listing_assignment_data($tbsorterArr['rows'], $listings_ids, $current_user_role, $date_filter, $current_user, $arrResaleStatus);
    // print_r($all_records);
    $arr = array(
        "draw" => 10,
        "recordsTotal" => count($all_records),
        "recordsFiltered" => count($all_records),
        "data" => $all_records
    );
} else {
    $arr = array(
        "draw" => 10,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => ''
    );
}

if ($download) {
    download_listing_data($all_records, $current_user_role);
} else {
    echo json_encode($arr);
}

function getBroker($seller_id) {
    if ($seller_id) {
        $Sql = "SELECT c.name, c.id FROM company c inner join company_users cu on c.id=cu.company_id WHERE cu.user_id=" . $seller_id . " and c.status = 'Active' and cu.status='Active' ";
        $ExecSql = mysql_query($Sql) or die(mysql_error() . ' Error in fetching data from company_users');
        if (mysql_num_rows($ExecSql) > 0) {
            $Res = mysql_fetch_assoc($ExecSql);
            return array($Res['id'], $Res['name']);
        }
        return array(null, null);
    }
}

/**
 * get_listing_assignment_data
 */
function append_listing_assignment_data($listings, $listings_ids, $current_user_role, $date_filter, $current_user, $arrResaleStatus) {

    $listings_appended = array();
    $lst_assignment_arr = array();
    $listings_ids = implode(",", $listings_ids);

    //date filter condtitions
    $date_condtitions = '';
    if ($date_filter['error_msg'] == '') {
        if ($date_filter['date_type'] == 'assigned-date') {
            $date_condtitions = " AND (DATE(ca.created_at) between '" . $date_filter['frmdate'] . "' AND '" . $date_filter['todate'] . "')";
        } elseif ($date_filter['date_type'] == 'visit-date') {
            $date_condtitions = " AND (DATE(lstsch.scheduled_date_time) between '" . $date_filter['frmdate'] . "' AND '" . $date_filter['todate'] . "')";
        }
    }

    //role conditions
    $role_conditions = '';
    if ($current_user_role == 'photoGrapher') {
        $role_conditions = " AND ca.assigned_to ='" . $current_user . "' AND ca.status = 'assignedToPhotoGrapher'";
    } elseif ($current_user_role == 'reToucher') {
        $role_conditions = " AND ca.status = 'readyToTouchUp'";
    }

    $lst_assignment_data = mysql_query("select lstsch.listing_id, lstsch.key_person_name, lstsch.key_person_contact, 
                        lstsch.scheduled_date_time, lstsch.photo_link, pa1.fname assigned_to, pa2.fname assigned_by, ca.created_at assigned_date,ca.status as assignment_status, lstsch.status as schedule_status, ca.remark
                        from listing_schedules lstsch
                        left join cms_assignments ca  on ca.id = lstsch.cms_assignment_id and ca.`assignment_type`= 'resale'
                        left join proptiger_admin pa1 on ca.assigned_to = pa1.adminid
                        left join proptiger_admin pa2 on ca.assigned_by = pa2.adminid
                        where 
                            lstsch.status = 'Active' and 
                            lstsch.listing_id in ($listings_ids) " . $date_condtitions . $role_conditions) or die(mysql_error());

    if (mysql_num_rows($lst_assignment_data)) {
        while ($row = mysql_fetch_object($lst_assignment_data)) {

            $lst_assignment_arr[$row->listing_id]['photo_link'] = LISTING_IMAGE_FOLDER_PATH . $row->photo_link;
            $lst_assignment_arr[$row->listing_id]['remark'] = $row->remark;
            $lst_assignment_arr[$row->listing_id]['assigned_to'] = $row->assigned_to;
            $lst_assignment_arr[$row->listing_id]['assigned_by'] = $row->assigned_by;
            $lst_assignment_arr[$row->listing_id]['assigned_date'] = ($row->assigned_date) ? date("d-M'y", strtotime($row->assigned_date)) : '';

            $lst_assignment_arr[$row->listing_id]['assignment_status'] = $row->assignment_status;
            $lst_assignment_arr[$row->listing_id]['verified_status'] = ($row->assignment_status == 'touchUpDone') ? 'Done' : 'Not Done';
            $lst_assignment_arr[$row->listing_id]['schedule_status'] = ($row->schedule_status) ? 'Done' : 'Not Done';
            $lst_assignment_arr[$row->listing_id]['key_person_name'] = $row->key_person_name;
            $lst_assignment_arr[$row->listing_id]['key_person_contact'] = $row->key_person_contact;
            $lst_assignment_arr[$row->listing_id]['scheduled_date_time'] = date("h:i a d-M'y", strtotime($row->scheduled_date_time));
        }
    }

    //appending the assignment data
    $count = 1;
    foreach ($listings as $key => $rows) {
        $listings_appended[$key] = $rows;
        if (isset($lst_assignment_arr[$rows[2]])) {


            if ($current_user_role == 'reToucher') { //Field Manager                               
                $listings_appended[$key][5] = $lst_assignment_arr[$rows[2]]['key_person_name'];
                $listings_appended[$key][6] = $lst_assignment_arr[$rows[2]]['key_person_contact'];
                $listings_appended[$key][7] = $lst_assignment_arr[$rows[2]]['scheduled_date_time'];
                $listings_appended[$key][8] = $lst_assignment_arr[$rows[2]]['photo_link'];
            } elseif ($current_user_role == 'photoGrapher') { //Field Manager                               
                $listings_appended[$key][5] = $lst_assignment_arr[$rows[2]]['key_person_name'];
                $listings_appended[$key][6] = $lst_assignment_arr[$rows[2]]['key_person_contact'];
                $listings_appended[$key][7] = $lst_assignment_arr[$rows[2]]['scheduled_date_time'];
                $listings_appended[$key][8] = $lst_assignment_arr[$rows[2]]['remark'];
            } elseif ($current_user_role == 'fieldManager') { //Field Manager  
                if ($lst_assignment_arr[$rows[2]]['assignment_status'] == 'touchUpDone') {
                    unset($listings_appended[$key]);
                } else {
                    //if listing is ready to touchup then disabled it for all actions
                    if ($lst_assignment_arr[$rows[2]]['assignment_status'] == 'readyToTouchUp') {
                        $listings_appended[$key][0] = '<input type="checkbox" disabled="true" title="Ready to Touchup">';
                    }
                    $listings_appended[$key][5] = $arrResaleStatus[$lst_assignment_arr[$rows[2]]['assignment_status']];
                    $listings_appended[$key][6] = $lst_assignment_arr[$rows[2]]['assigned_to'];
                    $listings_appended[$key][7] = $lst_assignment_arr[$rows[2]]['assigned_date'];
                    $listings_appended[$key][8] = $lst_assignment_arr[$rows[2]]['key_person_name'];
                    $listings_appended[$key][9] = $lst_assignment_arr[$rows[2]]['key_person_contact'];
                    $listings_appended[$key][10] = $lst_assignment_arr[$rows[2]]['scheduled_date_time'];
                }
            } else { //RM CRM
                //if listing is ready to touchup then disabled it for all actions
                if ($lst_assignment_arr[$rows[2]]['assignment_status'] == 'readyToTouchUp') {
                    $listings_appended[$key][0] = '<input type="checkbox" disabled="true" title="Ready to Touchup">';
                }
                
                $listings_appended[$key][8] = $lst_assignment_arr[$rows[2]]['verified_status'];
                
                if ($lst_assignment_arr[$rows[2]]['assignment_status'] != 'touchUpDone') {
                    $listings_appended[$key][9] = $lst_assignment_arr[$rows[2]]['schedule_status'];
                    $listings_appended[$key][10] = $lst_assignment_arr[$rows[2]]['key_person_name'];
                    $listings_appended[$key][11] = $lst_assignment_arr[$rows[2]]['key_person_contact'];
                    $listings_appended[$key][12] = $lst_assignment_arr[$rows[2]]['scheduled_date_time'];
                }
            }
        } elseif (in_array($current_user_role, array('fieldManager', 'photoGrapher', 'reToucher')) && !isset($lst_assignment_arr[$rows[2]])) {
            unset($listings_appended[$key]);
        }
        //resetiing the rows index
        if (isset($listings_appended[$key][2])) {
            $listings_appended[$key][1] = $count++;
        }
    }

    $listings_appended = array_values($listings_appended);

    //print_r($listings_appended);

    return $listings_appended;
}

/**
 * download_listing_data: to download current filterred data
 * @param type $data
 * @param type $current_user_role
 */
function download_listing_data($data, $current_user_role) {
    //defining headers on basis of roles
    if ($current_user_role == 'rm' || $current_user_role == 'crm') {
        $headers = array('Serial', 'Listing ID', 'City', 'Locality', 'Broker Name', 'Project', 'Listing', 'Touch Up', 'Scheduling', 'Key Person Name', 'Key Person Contact', 'Date & Time of Visit');
    } else if ($current_user_role == 'fieldManager') {
        $headers = array('Serial', 'Listing ID', 'City', 'Locality', 'Assignment Status', 'Assigned To', 'Assigned Date', 'Key Person Name', 'Key Person Contact', 'Date & Time of Visit');
    } else {
        $headers = array('Serial', 'Listing ID', 'City', 'Locality', 'Verified', 'Key Person Name', 'Key Person Contact', 'Date & Time of Visit', 'Remark');
    }

    //preparing headers
    $pdf_content = "<table cellspacing=1 bgcolor='' cellpadding=0 width='100%' style='font-size:11px;font-family:tahoma,arial,verdana;vertical-align:middle;text-align:center;'>    <tr bgcolor='#f2f2f2'>";

    foreach ($headers as $header) {
        $pdf_content .='<td>' . $header . '</td>';
    }
    $pdf_content .="</tr>";

    //preparing data rows
    foreach ($data as $row) {
        $pdf_content .= "<tr  bgcolor='#FFFFFF' valign='top'>";
        foreach ($row as $key => $col) {
            if ($key == 0)
                continue;

            $pdf_content .= "<td>" . $col . "</td>";
        }
        $pdf_content .= "</tr>";
    }

    $filename = "assignment-listings-" . date('YmdHis') . ".xls";
    header('Content-type: application/ms-excel');
    header('Content-Disposition: attachment; filename=' . $filename);
    echo $pdf_content;
}

?>