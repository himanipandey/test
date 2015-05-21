<?php

include("../smartyConfig.php");
include("../appWideConfig.php");
include("../modelsConfig.php");
include("../dbConfig.php");
include("../httpful.phar");
include("../includes/configs/configs.php");
include("../function/functions_assignments.php");

$page = filter_input(INPUT_GET, "page");
$size = filter_input(INPUT_GET, "size");
$projectId = filter_input(INPUT_GET, "project");
$listingId = filter_input(INPUT_GET, "listingId");
$phaseId = filter_input(INPUT_GET, "phaseId");
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
$readOnly = filter_input(INPUT_GET, "readOnly");


$filterArr = array();

$start = $page * $size;

$size = 200000; //default maximum

$empty_flag = false; //decide if api request required or not

if (isset($cityId) && !empty($cityId) && ($cityId != "null") && ($cityId != "")) {
    $filterArr["and"][] = array("equal" => array("cityId" => $cityId));
}
if (isset($projectId) && !empty($projectId) && ($projectId != "null") && ($projectId != "")) {
    $filterArr["and"][] = array("equal" => array("projectId" => $projectId));
}
if (isset($listingId) && !empty($listingId) && ($listingId != "null") && ($listingId != "")) {
    $filterArr["and"][] = array("equal" => array("listingId" => $listingId));
}else{
    $listing_ids = getting_listingIds_to_fetch($current_user, $current_user_role);
    
    if(empty($listing_ids)){
        $empty_flag = true;
    }elseif($listing_ids != 1){
        $filterArr["and"][] = array("equal" => array("listingId" => $listing_ids));
    }    
    
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
//if (in_array($current_user_role, array('photoGrapher', 'reToucher'))) {
//    $filter = '{}';
//}


$sort = '"sort":{"field":"listingId","sortOrder":"DESC"}';
$fields = '"fields":["imageCount","verified","description","seller","id","fullName","currentListingPrice","pricePerUnitArea","price","otherCharges","property","project","locality","suburb","city","label","name","builder","unitName","size","unitType","createdAt","projectId","propertyId","phaseId","updatedBy","sellerId","jsonDump","remark","homeLoanBankId","flatNumber","noOfCarParks","negotiable","transferCharges","plc","listingAmenities","amenity","amenityMaster","masterAmenityIds","floor","latitude","longitude","amenityDisplayName","isDeleted","bedrooms","bathrooms","amenityId","imagesCount","listingId","bookingStatusId","facingId","towerId"]}';
$uriListing = RESALE_LISTING_API_V2_URL . '?' . $gpidFilter . 'selector={"paging":{"start":' . $start . ',"rows":' . $size . '},"filters":' . $filter . "," . $sort . "," . $fields . '}';
//die($uriListing);

if ($readOnly == 1) { //show specific listing
    try {
        $responseLists = \Httpful\Request::get($uriListing)->send();
        if ($responseLists->body->statusCode == "2XX") {
            $data = $responseLists->body->data;
            view_listing($data[0], phase_detail);
        } else {
            echo "Some Error occurred!";
        }
    } catch (Exception $ex) {
        die($ex->getMessage());
    }
} elseif($empty_flag == false){ // other operations
   
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
} else {
        $arr = array(
            "draw" => 10,
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => ''
        );
        echo json_encode($arr);
    }

?>