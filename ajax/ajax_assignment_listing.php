<?php

include("../appWideConfig.php");
include("../dbConfig.php");
include("../httpful.phar");
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
        foreach ($data as $index => $row) {
            $listings_ids[] = $row->id;
            $brokerName = "";
            $row->sellerId->id = $row->sellerId;
            if ($row->sellerId) {
                list($row->seller->brokerId, $row->seller->brokerName) = getBroker($row->sellerId);
            }

            $data_rows = array(
                "<input type='checkbox' value='" . $row->id . "' class='schedule_check' name='schedule_check-" . $index . "' id='schedule_check-" . $index . "'>",
                $start + $index + 1,
                $row->id,
                $row->property->project->locality->suburb->city->label,
                $row->property->project->locality->label,
                $row->seller->brokerName,
                $row->property->project->name . ", " . $row->property->project->builder->name,
                $row->property->unitName . "-" . $row->property->size . "-" . $row->property->unitType,
                "",
                "",
                "",
                "",
                ""
            );
            array_push($tbsorterArr['rows'], $data_rows);
        }
    }
} catch (Exception $ex) {
    die($ex->getMessage());
}

if ($listings_ids) {

    $arr = array(
        "draw" => 10,
        "recordsTotal" => $tbsorterArr['total_rows'],
        "recordsFiltered" => $tbsorterArr['total_rows'],
        "data" => append_listing_assignment_data($tbsorterArr['rows'], $listings_ids)
    );
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
function append_listing_assignment_data($listings, $listings_ids) {
    $listings_appended = array();
    $lst_assignment_arr = array();
    $listings_ids = implode(",", $listings_ids);
    $lst_assignment_data = mysql_query("select lstsch.listing_id, lstsch.key_person_name, lstsch.key_person_contact, 
                        lstsch.scheduled_date_time, ca.status as assignment_status, lstsch.status as schedule_status
                        from listing_schedules lstsch
                        left join cms_assignments ca  on ca.entity_id = lstsch.listing_id and ca.`assignment_type`= 'resale'
                        where 
                            lstsch.status = 'Active' and lstsch.listing_id in ($listings_ids)") or die(mysql_error());

    if (mysql_num_rows($lst_assignment_data)) {
        while ($row = mysql_fetch_object($lst_assignment_data)) {

            $lst_assignment_arr[$row->listing_id]['assignment_status'] = ($row->assignment_status) ? $row->assignment_status : "Not Assigned";
            $lst_assignment_arr[$row->listing_id]['schedule_status'] = ($row->schedule_status) ? 'Done' : 'Not Done';
            $lst_assignment_arr[$row->listing_id]['key_person_name'] = $row->key_person_name;
            $lst_assignment_arr[$row->listing_id]['key_person_contact'] = $row->key_person_contact;
            $lst_assignment_arr[$row->listing_id]['scheduled_date_time'] = date("Y-m-d h:i a",strtotime($row->scheduled_date_time));
        }
    }

    //appending the assignment data
    foreach ($listings as $key => $rows) {
        $listings_appended[$key] = $rows;
        if (isset($lst_assignment_arr[$rows[2]])) {

            $listings_appended[$key][8] = $lst_assignment_arr[$rows[2]]['assignment_status'];
            $listings_appended[$key][9] = $lst_assignment_arr[$rows[2]]['schedule_status'];
            $listings_appended[$key][10] = $lst_assignment_arr[$rows[2]]['key_person_name'];
            $listings_appended[$key][11] = $lst_assignment_arr[$rows[2]]['key_person_contact'];
            $listings_appended[$key][12] = $lst_assignment_arr[$rows[2]]['scheduled_date_time'];
        }
    }

    return $listings_appended;
}

?>