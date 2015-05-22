<?php

/*
 * get_lot_entity_content : Get all the Lot Entities by Lot type
 */

function get_lot_entity_content($type, $entity_ids) {

    $entityContent = array();

    if ($type == 'project') {
        $allProjectSql = "SELECT resi_project.project_id, concat(resi_builder.builder_name, ' ', resi_project.project_name) project_name, resi_project.project_description FROM " . RESI_PROJECT . "                     
                            LEFT JOIN resi_builder  on resi_project.builder_id = resi_builder.builder_id and resi_builder.builder_status = 0
                             WHERE resi_project.project_id in ($entity_ids)  
                                    and resi_project.status in ('Active','ActiveInCms')
                                    and  resi_project.version = 'Cms'";
        $allProjects = mysql_query($allProjectSql) or die(mysql_error());

        while ($row = mysql_fetch_object($allProjects)) {
            $entityContent[$row->project_id]['content'] = $row->project_description;
            $entityContent[$row->project_id]['entity_name'] = $row->project_name;
        }
    } elseif ($type == 'locality') {
        $allLocSql = "SELECT locality.locality_id, locality.label, locality.description
                        FROM " . LOCALITY . "                     
                        WHERE 
                            locality.locality_id in ($entity_ids)                              
                            and locality.status = 'Active'";

        $allLocs = mysql_query($allLocSql) or die(mysql_error());

        while ($row = mysql_fetch_object($allLocs)) {
            $entityContent[$row->locality_id]['content'] = $row->description;
            $entityContent[$row->locality_id]['entity_name'] = $row->label;
        }
    } elseif ($type == 'builder') {
        $allBuilderSql = "select resi_builder.builder_id, resi_builder.builder_name, resi_builder.description
                        FROM " . RESI_BUILDER . "                        
                        where resi_builder.builder_status = 0 
                            and resi_builder.builder_id in ($entity_ids)";

        $allBuilders = mysql_query($allBuilderSql) or die(mysql_error());
        while ($row = mysql_fetch_object($allBuilders)) {
            $entityContent[$row->builder_id]['content'] = $row->description;
            $entityContent[$row->builder_id]['entity_name'] = $row->builder_name;
        }
    } elseif ($type == 'city') {
        $allCitySql = "SELECT city_id, label,description  from " . CITY . " where status = 'Active' and city_id in ($entity_ids)";
        $allCities = mysql_query($allCitySql) or die(mysql_error());
        while ($row = mysql_fetch_object($allCities)) {
            $entityContent[$row->city_id]['content'] = $row->description;
            $entityContent[$row->city_id]['entity_name'] = $row->label;
        }
    }

    return $entityContent;
}

/*
 * fetch_assignTo_editors : get the Content Editors Managed by Current Users
 */

function fetch_assignTo_editors() {

    $assignTo = ProptigerAdmin::find('all', array('select' => 'adminid, fname',
                'conditions' => array('department' => 'content',
                    'Role' => array('contentEditor'),
                    'status' => 'Y',
                    'manager_id' => $_SESSION['adminId'])));

    return $assignTo;
}

function fetch_assignTo_users() {

    $assignTo = ProptigerAdmin::find('all', array('select' => 'adminid, fname',
                'conditions' => array('department' => 'content',
                    'Role' => array('contentVendor'),
                    'status' => 'Y',
                    'manager_id' => $_SESSION['adminId'])));

    $assignToUsersArr = array();
    foreach ($assignTo as $obj) {
        $assignToUsersArr[$obj->adminid] = $obj->fname;
    }

    return $assignToUsersArr;
}

/*
 * fetch_lot_details : get Content Lot Details by Lot ID
 */

function fetch_lot_details($lot_id) {
    $content_lot_details = array();


    $lot_details_sql = "SELECT admin.role, SUM(clc.status = 'active') revert_comments, rp.project_name, "
            . " IF(rb.builder_name IS NOT NULL, rb.builder_name, rb2.builder_name) builder_name, cl.lot_type,"
            . " IF(loc.label IS NOT NULL, loc.label, loc2.label) locality, city.label as lot_city,"
            . " ca.status as lot_status, cld.id as content_id, cld.entity_id, cld.content, cld.updated_content, "
            . " cld.status as content_status, ( LENGTH(cld.content) - LENGTH(REPLACE(cld.content, ' ', ''))+1) as content_words_count, "
            . " ( LENGTH(cld.updated_content) - LENGTH(REPLACE(cld.updated_content, ' ', ''))+1) as updated_content_words_count, ca.completed_by, "
            . " DATE_FORMAT(ca.updated_at, '%d/%m/%Y') assignment_date, ca.assigned_to"
            . " FROM " . CONTENT_LOTS . " cl "
            . " LEFT JOIN " . CONTENT_LOT_DETAILS . " cld on cld.lot_id = cl.id"
            . " LEFT JOIN " . CONTENT_LOT_COMMENTS . " clc on clc.content_lot_id = cld.id"
            . " LEFT JOIN " . CMS_ASSIGNMENTS . " ca on ca.entity_id = cl.id AND ca.assignment_type = 'content_lots'"
            . " LEFT JOIN " . CITY . " city on (city.city_id = cl.lot_city or city.city_id = cld.entity_id)"
            . " LEFT JOIN " . RESI_PROJECT . " rp on rp.project_id = cld.entity_id and cl.lot_type = 'project'"
            . " LEFT JOIN " . RESI_BUILDER . " rb on rp.builder_id = rb.builder_id and (cl.lot_type = 'project' OR cl.lot_type = 'builder')"
            . " LEFT JOIN " . LOCALITY . " loc on loc.locality_id = rp.locality_id"
            . " LEFT JOIN " . LOCALITY . " loc2 on loc2.locality_id = cld.entity_id and cl.lot_type = 'locality'"
            . " LEFT JOIN " . RESI_BUILDER . " rb2 on rb2.builder_id = cld.entity_id and cl.lot_type = 'builder'"
            . " LEFT JOIN " . ADMIN . " admin on admin.adminid = ca.assigned_to"
            . " WHERE cl.id = '$lot_id'"
            . " GROUP BY cld.entity_id "
            . " ORDER BY cld.id";



    $lot_details = mysql_query($lot_details_sql) or die(mysql_error());

    if (mysql_num_rows($lot_details) > 0) {
        $cnt = 0;
        $lot_words_count = 0;
        $lot_updated_words_count = 0;
        $lots_updated_content_count = 0;
        $total_revert_comment = 0;
        $reverted_articles = array();
        while ($row = mysql_fetch_object($lot_details)) {
            $content_lot_details['lot_id'] = $lot_id;
            $content_lot_details['assignment_date'] = $row->assignment_date;
            $content_lot_details['assigned_to'] = $row->assigned_to;
            $content_lot_details['completed_by'] = $row->completed_by;
            $content_lot_details['lot_type'] = $row->lot_type;
            $content_lot_details['lot_city'] = $row->lot_city;
            $content_lot_details['role'] = $row->role;
            $content_lot_details['lot_status'] = ($row->lot_status) ? $row->lot_status : "unassigned";

            $content_lot_details['lot_contents'][$cnt]['content_id'] = $row->content_id;
            $content_lot_details['lot_contents'][$cnt]['locality'] = $row->locality;
            $content_lot_details['lot_contents'][$cnt]['entity_id'] = $row->entity_id;

            if ($row->lot_type == 'project')
                $entity_name = $row->builder_name . " " . $row->project_name;
            elseif ($row->lot_type == 'locality')
                $entity_name = $row->locality;
            elseif ($row->lot_type == 'builder')
                $entity_name = $row->builder_name;
            elseif ($row->lot_type == 'city')
                $entity_name = $row->lot_city;

            $content_lot_details['lot_contents'][$cnt]['entity_name'] = $entity_name;


            $content_lot_details['lot_contents'][$cnt]['content_id'] = $row->content_id;
            $content_lot_details['lot_contents'][$cnt]['content'] = substr($row->content, 0, 50);
            $content_lot_details['lot_contents'][$cnt]['updated_content'] = substr($row->updated_content, 0, 50);
            $content_lot_details['lot_contents'][$cnt]['content_status'] = $row->content_status;
            $content_lot_details['lot_contents'][$cnt]['content_words_count'] = $row->content_words_count;
            $content_lot_details['lot_contents'][$cnt]['revert_comments'] = $row->revert_comments;

            $total_revert_comment = $total_revert_comment + $row->revert_comments;

            if (($row->content_status == 'revertComplete' || $row->content_status == 'revert') && $row->revert_comments)
                $reverted_articles[] = $cnt + 1;

            $lot_words_count = $lot_words_count + $row->content_words_count;
            $lot_updated_words_count = $lot_updated_words_count + $row->updated_content_words_count;
            $lot_completed_articles = $lot_completed_articles + (($row->content_status == 'complete' || $row->content_status == 'revertComplete') ? 1 : 0);

            $cnt++;
        }
        $content_lot_details['reverted_articles'] = implode(", ", $reverted_articles);
        $content_lot_details['lot_articles'] = $cnt;
        $content_lot_details['lot_completed_articles'] = $lot_completed_articles;
        $content_lot_details['lot_words_count'] = $lot_words_count;
        $content_lot_details['lot_updated_words_count'] = $lot_updated_words_count;
        $content_lot_details['total_revert_comment'] = $total_revert_comment;
    }

    //print "<pre>".print_r($content_lot_details,1)."</pre>";

    return $content_lot_details;
}

/*
 * fetch_lots : get all the content lots created by user
 */

function fetch_lots($frmDate = null, $toDate = null, $lotStatus = null) {
    $lotData = array();

    $dateCondition = "";
    if ($lotStatus != null) {
        if ($lotStatus == 'created') {
            $dateCondition = " AND (DATE(ca.created_at) BETWEEN DATE('$frmDate') AND DATE('$toDate'))";
        } else {
            if ($lotStatus == 'completed') {
                $lotStatus = "'completedByVendor','waitingApproval'";
            } elseif ($lotStatus == 'reverted') {
                $lotStatus = "'reverted','revertedToVendor'";
            } else {
                $lotStatus = "'$lotStatus'";
            }
            $dateCondition = " AND (DATE(ca.updated_at) BETWEEN DATE('$frmDate') AND DATE('$toDate'))";
            $dateCondition .= " AND ca.status in ($lotStatus)";
        }
    }


    $content_lots = mysql_query("SELECT count(clc.id) revert_comments, admin.role, cl.id, cl.lot_type, cl.lot_status, cl.lot_city, admin.fname as assignedTo"
            . " FROM " . CONTENT_LOTS . " cl "
            . " LEFT JOIN " . CMS_ASSIGNMENTS . " ca on ca.entity_id = cl.id  AND ca.assignment_type = 'content_lots'"
            . " LEFT JOIN " . CONTENT_LOT_DETAILS . " cld on cld.lot_id = cl.id"
            . " LEFT JOIN " . CONTENT_LOT_COMMENTS . " clc on clc.content_lot_id = cld.id"
            . " LEFT JOIN " . ADMIN . " admin on admin.adminid = ca.assigned_to"
            . " WHERE (cl.created_by = '" . $_SESSION['adminId'] . "' OR ca.assigned_to = '" . $_SESSION['adminId'] . "')"
            . $dateCondition
            . " GROUP BY cl.id"
            . " ORDER BY cl.id DESC");

    //die;
    $count = 0;
    while ($row = mysql_fetch_object($content_lots)) {
        $lotData[$count]['lot_id'] = $row->id;
        $lotData[$count]['lot_type'] = $row->lot_type;
        $lotData[$count]['lot_status'] = $row->lot_status;
        $lotData[$count]['lot_city'] = $row->lot_city;
        $lotData[$count]['assignedTo'] = $row->assignedTo;
        $lotData[$count]['role'] = $row->role;
        $lotData[$count]['revert_comments'] = $row->revert_comments;
        $count++;
    }
    return $lotData;
}

/*
 * fetch_assigned_lots : get all the assigned lots to current user
 */

function fetch_assigned_lots($frmDate = null, $toDate = null, $lotStatus = null) {
    $lotData = array();
    $dateCondition = "";
    if ($lotStatus != null) {
        $dateCondition = " AND (DATE(ca.created_at) BETWEEN DATE('$frmDate') AND DATE('$toDate'))";
    }
    $content_lots = mysql_query("SELECT SUM(cld.status = 'revert') revert_comments, cld.lot_id as id, count(cld.entity_id) articles, "
            . " SUM(IF(cld.status = 'complete' OR cld.status = 'revertComplete'  OR (admin.role != 'contentVendor'),1,0)) lot_completed_articles, "
            . " SUM( IF(cld.status = 'complete'  OR cld.status = 'revertComplete' OR (admin.role != 'contentVendor'), LENGTH(cld.updated_content) - LENGTH(REPLACE(cld.updated_content, ' ', ''))+1, 0)) lot_completed_words, "
            . " SUM( LENGTH(cld.content) - LENGTH(REPLACE(cld.content, ' ', ''))+1) words,"
            . " cl.lot_type, ca.status, DATE_FORMAT(ca.updated_at, '%Y/%m/%d') updated_at"
            . " FROM " . CONTENT_LOTS . " cl "
            . " LEFT JOIN " . CONTENT_LOT_DETAILS . " cld on cld.lot_id = cl.id"
            . " LEFT JOIN " . CMS_ASSIGNMENTS . " ca on ca.entity_id = cl.id  AND ca.assignment_type = 'content_lots'"
            . " LEFT JOIN " . CONTENT_LOT_COMMENTS . " clc on clc.content_lot_id = cld.id"
            . " LEFT JOIN " . ADMIN . " admin on admin.adminid = ca.assigned_to"
            . " WHERE (ca.assigned_to = '" . $_SESSION['adminId'] . "' AND ca.status in ('assigned', 'revertedToVendor', 'reverted')) "
            . $dateCondition
            . " GROUP BY cld.lot_id"
            . " ORDER BY cl.id DESC");
    $count = 0;
    while ($row = mysql_fetch_object($content_lots)) {
        $lotData[$count]['lot_id'] = $row->id;
        $lotData[$count]['lot_type'] = $row->lot_type;
        $lotData[$count]['lot_status'] = $row->status;
        $lotData[$count]['articles'] = $row->articles;
        $lotData[$count]['lot_completed_articles'] = $row->lot_completed_articles;
        $lotData[$count]['lot_completed_words'] = $row->lot_completed_words;
        $lotData[$count]['words'] = $row->words;
        $lotData[$count]['revert_comments'] = $row->revert_comments;
        $lotData[$count]['date_old'] = floor((time() - strtotime($row->updated_at)) / 86400);

        $count++;
    }

    return $lotData;
}

/*
 * fetch_lot_content_details : fetch the details of Lot content
 */

function fetch_lot_content_details($lot_content_id) {
    $lotContentDataSql = mysql_query("SELECT cld.id, cld.entity_id, cld.lot_id, cl.lot_type, IF(loc.label IS NOT NULL, loc.label, loc2.label) locality, city.label as lot_city,"
            . " cld.content, cld.updated_content, IF(rb.builder_name IS NOT NULL, rb.builder_name, rb2.builder_name) builder_name, rp.project_name  "
            . " FROM " . CONTENT_LOT_DETAILS . " cld"
            . " INNER JOIN " . CONTENT_LOTS . " cl on cld.lot_id = cl.id"
            . " LEFT JOIN " . CITY . " city on (city.city_id = cl.lot_city OR city.city_id = cld.entity_id)"
            . " LEFT JOIN " . RESI_PROJECT . " rp on rp.project_id = cld.entity_id and cl.lot_type = 'project'"
            . " LEFT JOIN " . RESI_BUILDER . " rb on rp.builder_id = rb.builder_id and (cl.lot_type = 'project' OR cl.lot_type = 'builder')"
            . " LEFT JOIN " . LOCALITY . " loc on loc.locality_id = rp.locality_id"
            . " LEFT JOIN " . LOCALITY . " loc2 on loc2.locality_id = cld.entity_id and cl.lot_type = 'locality'"
            . " LEFT JOIN " . RESI_BUILDER . " rb2 on rb2.builder_id = cld.entity_id and cl.lot_type = 'builder'"
            . " WHERE cld.id = '" . $lot_content_id . "'"
            . " GROUP BY cld.entity_id") or die(mysql_error());

    $lotContentData = array();
    if (mysql_num_rows($lotContentDataSql) > 0) {
        $row = mysql_fetch_object($lotContentDataSql);
        $lotContentData ['id'] = $row->id;
        $lotContentData ['entity_id'] = $row->entity_id;
        $lotContentData ['lot_id'] = $row->lot_id;
        $lotContentData ['lot_type'] = $row->lot_type;
        $lotContentData ['locality'] = $row->locality;
        $lotContentData ['lot_city'] = $row->lot_city;
        $lotContentData ['content'] = $row->content;
        $lotContentData ['updated_content'] = $row->updated_content;

        if ($row->lot_type == 'project')
            $entity_name = $row->builder_name . " " . $row->project_name;
        elseif ($row->lot_type == 'locality')
            $entity_name = $row->locality;
        elseif ($row->lot_type == 'builder')
            $entity_name = $row->builder_name;
        elseif ($row->lot_type == 'city')
            $entity_name = $row->lot_city;

        $lotContentData ['entity_name'] = $entity_name;
    }

    return $lotContentData;
}

/**
 * fetch_pagination_ids : fetch previous and next lot_content_id
 * @param type $lot_id
 * @param type $lot_content_id
 */
function fetch_pagination_ids($lot_id, $lot_content_id) {
    $lotContentIDsSql = mysql_query("SELECT id FROM " . CONTENT_LOT_DETAILS . " "
            . " WHERE lot_id = '" . $lot_id . "'") or die(mysql_error());

    $lotContentIds = array();

    while ($row = mysql_fetch_object($lotContentIDsSql)) {
        $lotContentIds[] = $row->id;
    }
    $currentKey = array_search($lot_content_id, $lotContentIds);

    $prevKey = null;
    $nextKey = null;
    if (($currentKey - 1) >= 0)
        $prevKey = $lotContentIds[$currentKey - 1];
    if (($currentKey + 1) <= (count($lotContentIds) - 1))
        $nextKey = $lotContentIds[$currentKey + 1];

    return array('prevKey' => $prevKey, 'nextKey' => $nextKey);
}

/**
 * content_lot_send_mail : send mail based on provided data
 */
function content_lot_send_mail($vendorId, $action, $extra) {

    $vendorInfo = ProptigerAdmin::getUserInfoByID($vendorId);
    $teamLeadInfo = ProptigerAdmin::getUserInfoByID($vendorInfo->manager_id);

    if ($action == 'assigned') {

        $to = $vendorInfo->adminemail;
        $sender = PROPTIGER_CONTENT_TEAM_EMAIL_ID;
        $cc = $teamLeadInfo->adminemail;

        $subject = "A new lot (# " . $extra['lot_id'] . ") has been assigned";

        $email_message = 'Dear ' . $vendorInfo->fname . '.<br/><br/>

                        A new lot (# ' . $extra['lot_id'] . ') has been assigned to you by ' . $teamLeadInfo->fname . '.<br/>

                        Please login to our portal and we will look forward to hear from your side.<br/><br/>

                        Regards,<br/>

                        PropTiger Content Team<br/>

                        ' . $sender;
    } elseif ($action == 'completedByVendor') {

        $to = $teamLeadInfo->adminemail;
        //$sender = $vendorInfo->adminemail;
        $sender = PROPTIGER_CONTENT_TEAM_EMAIL_ID;
        $cc = $vendorInfo->adminemail;

        $subject = "Lot (# " . $extra['lot_id'] . ") has been completed";

        $email_message = 'Dear ' . $teamLeadInfo->fname . '.<br/><br/>

                            Lot (# ' . $extra['lot_id'] . ') has been completed by ' . $vendorInfo->fname . '.<br/>

                            Please login to our portal for more details.<br/><br/>

                            Regards,<br/>

                        ' . $vendorInfo->fname;
    } elseif ($action == 'revertedToVendor') {
        $to = $vendorInfo->adminemail;
        $sender = PROPTIGER_CONTENT_TEAM_EMAIL_ID;
        $cc = $teamLeadInfo->adminemail;

        $subject = "Lot (# " . $extra['lot_id'] . ") has been reverted";

        $email_message = 'Dear ' . $vendorInfo->fname . '.<br/><br/>

                            Lot (# ' . $extra['lot_id'] . ') has been reverted to you by ' . $teamLeadInfo->fname . '.<br/>

                            Please login to our portal for more details.<br/><br/>

                            Regards,<br/>

                            PropTiger Content Team<br/>

                            ' . $sender;
    }

    //echo $to, $subject, $email_message, $sender, $cc; die;

    $headers = 'MIME-Version: 1.0' . "<br/>";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "<br/>";
    $headers .= 'To: ' . $to . "<br/>";
    $headers .= 'From: ' . $sender . "<br/>";

    sendMailFromAmazon($to, $subject, $email_message, $sender, $cc, null, false);
}

/**
 * prepare_display : prepare display to listing assignment report section
 * @param type $pgf_work_sql
 * @return type
 */
function prepare_display($pgf_work_sql) {
    $report_data = array();
    while ($row = mysql_fetch_object($pgf_work_sql)) {

        $report_data[$row->adminid]['data'][] = array(
            "pgf_name" => $row->pgf_name,
            "assigned" => $row->assigned,
            "pending" => ($row->assigned - $row->complete),
            "complete" => $row->complete
        );
        $report_data[$row->adminid]['admin'] = $row->admin;
        $report_data[$row->adminid]['total_assigned'] = $row->assigned + $report_data[$row->adminid]['total_assigned'];
        $report_data[$row->adminid]['total_complete'] = $row->complete + $report_data[$row->adminid]['total_complete'];
        $report_data[$row->adminid]['total_pending'] = ($report_data[$row->adminid]['total_assigned'] - $report_data[$row->adminid]['total_complete']);

        //grand total
        $report_data['grand']['admin'] = "Grand Total";
        $report_data['grand']['total_assigned'] = $row->assigned + $report_data['grand']['total_assigned'];
        $report_data['grand']['total_complete'] = $row->complete + $report_data['grand']['total_complete'];
        $report_data['grand']['total_pending'] = ($report_data['grand']['total_assigned'] - $report_data['grand']['total_pending']);
    }

    krsort($report_data);

    return $report_data;
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
            $listings_appended[$key][2] = "<a href='javascript:void(0)' onclick='view_listing(" . $rows[2] . ")'>" . $rows[2] . "</a>";
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

/**
 * view_listing : show the specific listing
 * @param type $listing_data
 */
function view_listing($listing_data, $phase_api_url) {

    $jsonDump = (array) json_decode($listing_data->jsonDump);

    //phase details
    $phase_name = '';
    $responsePhase = \Httpful\Request::get($phase_api_url . $listing_data->property->project->projectId . "/phase/" . $listing_data->phaseId)->send();
    if ($responsePhase->body->statusCode == "2XX") {
        $phaseData = $responsePhase->body->data;
        $phase_name = $phaseData->phaseName;
    }

    if ($listing_data->currentListingPrice->pricePerUnitArea != 0) {
        $price = $listing_data->currentListingPrice->pricePerUnitArea;
    } else {
        $price = $listing_data->currentListingPrice->price;
    }
    if ($listing_data->currentListingPrice->otherCharges != 0) {
        $other_price = $listing_data->currentListingPrice->otherCharges;
    }

    //booking status
    $booking_status = '';
    $bookingStatusData = BookingStatuses::find("all", array('select' => 'display_name', 'conditions' => array('id' => $listing_data->bookingStatusId)));
    if ($bookingStatusData) {
        $booking_status = $bookingStatusData[0]->display_name;
    }

    //bank details
    $bank = '';
    $bankData = BankList::find('all', array('select' => 'bank_name', 'conditions' => array('bank_id' => $listing_data->homeLoanBankId)));
    if ($bankData) {
        $bank = $bankData[0]->bank_name;
    }

    //directions
    $direction = '';
    $directionData = MasterDirections::find('all', array('select' => 'direction', 'conditions' => array('id' => $listing_data->facingId)));
    if ($directionData) {
        $direction = $directionData[0]->direction;
    }

    //tower details
    $tower = '';
    $tower_data = ResiProjectTowerDetails::find('all', array('select' => 'tower_name', 'conditions' => array('tower_id' => $listing_data->towerId)));
    if ($tower_data) {
        $tower = $tower_data[0]->tower_name;
    }

    $negotiable = '';
    if (isset($listing_data->negotiable)) {
        if ($listing_data->negotiable == true) {
            $negotiable = 'Yes';
        } else {
            $negotiable = 'No';
        }
    }

    //print "<pre>" . print_r($listing_data, 1);
    //die;

    if ($listing_data->sellerId) {
        list($listing_data->seller->brokerId, $listing_data->seller->brokerName) = getBroker($listing_data->sellerId);
    }

    $content = '<div style="padding:5px"><table style="width:800px" cellspacing="2" cellpadding="4" border="0" align="left">';
    $content .= '<tr style="background-color: #666666; color:#fff; text-align:left">
                    <th colspan="4">
                        Listing : #' . $listing_data->id . '
                    </th>
                </tr>';
    $content .= '<tr style="background-color: #666666; color:#fff">
                    <td colspan="4"></th>
                </tr>';
    $content .= '<tr>
                    <td><b>City:</b></td>
                    <td>' . $listing_data->property->project->locality->suburb->city->label . '</td>
                    <td></td>
                    <td></td> 
                </tr>';

    $content .= '<tr>
                    <td><b>Broker Name:</b></td>
                    <td>' . $listing_data->seller->brokerName . '</td>                     
                    <td><b>Seller Name:</b></td>
                    <td>' . $listing_data->seller->fullName . '</td>
                </tr>';

    $content .= '<tr style="border-top: 1px solid rgb(204, 204, 204);">
                    <td><b>Owner Name:</b></td>
                    <td>' . $jsonDump['owner_name'] . '</td>
                    <td><b>Contact Number:</b></td>
                    <td>' . $jsonDump['owner_number'] . '</td> 
                </tr>';

    $content .= '<tr>
                    <td><b>Email:</b></td>
                    <td>' . $jsonDump['owner_email'] . '</td>
                    <td><b>Alternate Contact Number:</b></td>
                    <td>' . $jsonDump['alt_owner_number'] . '</td> 
                </tr>';

    $content .= '<tr style="border-top: 1px solid rgb(204, 204, 204);">
                    <td><b>Project:</b></td>
                    <td>' . $listing_data->property->project->name . '</td>
                    <td><b>Project ID:</b></td>
                    <td>' . $listing_data->property->project->projectId . '</td> 
                </tr>';

    $content .= '<tr>
                    <td><b>BHK:</b></td>
                    <td>' . $listing_data->property->unitName . "-" . $listing_data->property->size . "-" . $listing_data->property->unitType . '</td>
                    <td></td>
                    <td></td> 
                </tr>';

    $content .= '<tr style="border-top: 1px solid rgb(204, 204, 204);">
                    <td><b>Phase:</b></td>
                    <td>' . $phase_name . '</td>
                    <td><b>Tower:</b></td>
                    <td>' . $tower . '</td>
                </tr>';
    $content .= '<tr>
                    <td><b>Flat Number :</b></td>
                    <td>' . $listing_data->flatNumber . '</td>
                    <td><b>Floor:</b>' . $listing_data->floor . '</td>
                    <td><b>Total Floor: </b>'.$jsonDump['total_floor'].'</td>
                </tr>';
    $content .= '<tr>
                    <td><b>Car Parks :</b></td>
                    <td>' . $listing_data->noOfCarParks . '</td>
                    <td><b>Negotiable:</b></td>
                    <td>' . $negotiable . '</td>
                </tr>';
    $content .= '<tr>
                    <td><b>Facing:</b></td>
                    <td>' . $direction . '</td>
                    <td></td>
                    <td></td>
                </tr>';
    $content .= '<tr style="border-top: 1px solid rgb(204, 204, 204);">
                    <td><b>Price:</b></td>
                    <td>' . $price . '</td>
                    <td><b>Other Charges:</b></td>
                    <td>' . $other_price . '</td>
                </tr>';
    $content .= '<tr>
                    <td><b>Transfer Rate:</b></td>
                    <td>' . $listing_data->transferCharges . '</td>
                    <td></td>
                    <td></td>
                </tr>';
    $content .= '<tr>
                    <td><b>Home Loan Bank:</b></td>
                    <td>' . $bank . '</td>
                    <td><b>PLC :</b></td>
                    <td>' . $listing_data->plc . '</td>
                </tr>';
    $content .= '<tr style="border-top: 1px solid rgb(204, 204, 204);">
                    <td><b>Description :</b></td>
                    <td>' . $listing_data->description . '</td>
                    <td><b>Remark:</b></td>
                    <td>' . $listing_data->remark . '</td>
                </tr>';
    $content .= '<tr>
                    <td><b>Booking Status:</b></td>
                    <td>' . $booking_status . '</td>
                    <td></td>
                    <td></td>
                </tr>';

    $content .= '</table></div>';

    print $content;
}

function getting_listingIds_to_fetch($current_user, $current_user_role) {
    $list_array = array();
    $sql_list_sql = '';
    if ($current_user_role == 'fieldManager') {
        /*$sql_list_sql = "select lstch.listing_id as entity_id from listing_schedules lstch "
                . " inner join proptiger_admin pa on (pa.adminid = lstch.created_by OR pa.adminid = lstch.updated_by)"
                . " inner join proptiger_admin fm on fm.manager_id = pa.adminid "
                . " and fm.adminid = '".$current_user."' where lstch.status = 'Active'";*/
        $sql_list_sql = "select lstch.listing_id as entity_id from listing_schedules lstch where lstch.status = 'Active'";
    }elseif ($current_user_role == 'photoGrapher') {
        $sql_list_sql = "SELECT entity_id FROM  cms_assignments where "
                . " assigned_to = '" . $current_user . "' "
                . " AND status = 'assignedToPhotoGrapher'";
    } elseif ($current_user_role == 'reToucher') {
        $sql_list_sql = "SELECT entity_id FROM  cms_assignments where "               
                ." status = 'readyToTouchUp'";
    }
    if ($sql_list_sql) {
        $sql_list = mysql_query($sql_list_sql) or die(mysql_error());
        if (mysql_num_rows($sql_list)) {
            while ($row = mysql_fetch_object($sql_list)) {
                $list_array[] = $row->entity_id;
            }
        }
    }
    
    if(in_array($current_user_role, array('rm', 'crm'))){
        return 1;
    }


    return $list_array;
}

?>