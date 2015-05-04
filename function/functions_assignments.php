<?php

/*
 * get_lot_entity_content : Get all the Lot Entities by Lot type
 */

function get_lot_entity_content($type, $entity_ids) {

    $entityContent = array();

    if ($type == 'project') {
        $allProjectSql = "SELECT resi_project.project_id, concat(resi_builder.entity, ' - ', resi_project.project_name) project_name, resi_project.project_description FROM " . RESI_PROJECT . "                     
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
    $lot_details_sql = "SELECT SUM(clc.status = 'active') revert_comments, cl.lot_type, loc.label as locality, city.label as lot_city, ca.status as lot_status, cld.id as content_id, cld.entity_id, cld.entity_name, cld.content, cld.updated_content, "
            . " cld.status as content_status, SUM( LENGTH(cld.content) - LENGTH(REPLACE(cld.content, ' ', ''))+1) as content_words_count, "
            . " SUM( LENGTH(cld.updated_content) - LENGTH(REPLACE(cld.updated_content, ' ', ''))+1) as updated_content_words_count, ca.completed_by, "
            . " DATE_FORMAT(ca.updated_at, '%d/%m/%Y') assignment_date, ca.assigned_to"
            . " FROM " . CONTENT_LOTS . " cl "
            . " LEFT JOIN " . CONTENT_LOT_DETAILS . " cld on cld.lot_id = cl.id"
            . " LEFT JOIN " . CONTENT_LOT_COMMENTS . " clc on clc.content_lot_id = cld.id"
            . " LEFT JOIN " . CMS_ASSIGNMENTS . " ca on ca.entity_id = cl.id"
            . " LEFT JOIN " . CITY . " city on city.city_id = cl.lot_city"
            . " LEFT JOIN resi_project rp on rp.project_id = cld.entity_id and cl.lot_type = 'project'"
            . " LEFT JOIN locality loc on loc.locality_id = rp.locality_id and cl.lot_type = 'project'"
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
            $content_lot_details['lot_status'] = ($row->lot_status) ? $row->lot_status : "unassigned";

            $content_lot_details['lot_contents'][$cnt]['content_id'] = $row->content_id;
            $content_lot_details['lot_contents'][$cnt]['locality'] = $row->locality;
            $content_lot_details['lot_contents'][$cnt]['entity_id'] = $row->entity_id;
            $content_lot_details['lot_contents'][$cnt]['entity_name'] = $row->entity_name;
            $content_lot_details['lot_contents'][$cnt]['content_id'] = $row->content_id;
            $content_lot_details['lot_contents'][$cnt]['content'] = substr($row->content, 0, 50);
            $content_lot_details['lot_contents'][$cnt]['updated_content'] = substr($row->updated_content, 0, 50);
            $content_lot_details['lot_contents'][$cnt]['content_status'] = $row->content_status;
            $content_lot_details['lot_contents'][$cnt]['content_words_count'] = $row->content_words_count;
            $content_lot_details['lot_contents'][$cnt]['revert_comments'] = $row->revert_comments;
                        
            $total_revert_comment = $total_revert_comment + $row->revert_comments;
            
            if(($row->content_status == 'revertComplete' || $row->content_status == 'revert') && $row->revert_comments)
                $reverted_articles[] = $cnt+1;

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
    if($lotStatus != null){
        if($lotStatus == 'created'){
            $dateCondition = " AND (DATE(cl.created_at) BETWEEN DATE('$frmDate') AND DATE('$toDate'))";
        }else if($lotStatus == 'assigned'){
            $dateCondition = " AND (DATE(ca.created_at) BETWEEN DATE('$frmDate') AND DATE('$toDate'))";
        }else{
            if($lotStatus == 'completed'){
                $lotStatus = "'completedByVendor','waitingApproval'";
            }else{
                $lotStatus = "'$lotStatus'";
            }
            $dateCondition = " AND (DATE(cl.updated_at) BETWEEN DATE('$frmDate') AND DATE('$toDate'))";
            $dateCondition .= " AND cl.lot_status in ($lotStatus)";
        }
    }    
    
    $content_lots = mysql_query("SELECT count(clc.id) revert_comments, admin.role, cl.id, cl.lot_type, cl.lot_status, cl.lot_city, admin.fname as assignedTo"
            . " FROM " . CONTENT_LOTS . " cl "
            . " LEFT JOIN " . CMS_ASSIGNMENTS . " ca on ca.entity_id = cl.id"
            . " LEFT JOIN " . CONTENT_LOT_DETAILS . " cld on cld.lot_id = cl.id"
            . " LEFT JOIN " . CONTENT_LOT_COMMENTS . " clc on clc.content_lot_id = cld.id"
            . " LEFT JOIN " . ADMIN . " admin on admin.adminid = ca.assigned_to"
            . " WHERE (cl.created_by = '" . $_SESSION['adminId'] . "' OR ca.assigned_to = '" . $_SESSION['adminId'] . "')"
            . $dateCondition            
            . " GROUP BY cl.id"
            . " ORDER BY cl.id DESC");
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
    if($lotStatus != null){        
        $dateCondition = " AND (DATE(ca.created_at) BETWEEN DATE('$frmDate') AND DATE('$toDate'))";        
    }
    $content_lots = mysql_query("SELECT SUM(cld.status = 'revert') revert_comments, cld.lot_id as id, count(cld.entity_id) articles, "
            . " SUM(IF(cld.status = 'complete' OR cld.status = 'revertComplete',1,0)) lot_completed_articles, "
            . " SUM( IF(cld.status = 'complete'  OR cld.status = 'revertComplete', LENGTH(cld.updated_content) - LENGTH(REPLACE(cld.updated_content, ' ', ''))+1, 0)) lot_completed_words, "
            . " SUM( LENGTH(cld.content) - LENGTH(REPLACE(cld.content, ' ', ''))+1) words,"
            . " cl.lot_type, ca.status, DATE_FORMAT(ca.updated_at, '%Y/%m/%d') updated_at"
            . " FROM " . CONTENT_LOTS . " cl "
            . " LEFT JOIN " . CONTENT_LOT_DETAILS . " cld on cld.lot_id = cl.id"
            . " LEFT JOIN " . CMS_ASSIGNMENTS . " ca on ca.entity_id = cl.id"
            . " LEFT JOIN " . CONTENT_LOT_COMMENTS . " clc on clc.content_lot_id = cld.id"
            . " LEFT JOIN " . ADMIN . " admin on admin.adminid = ca.assigned_to"
            . " WHERE (ca.assigned_to = '" . $_SESSION['adminId'] . "' AND ca.status in ('assigned', 'revertedToVendor')) "
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
        $lotData[$count]['date_old'] = floor((time() - strtotime($row->updated_at))/86400);        

        $count++;
    }
  
    return $lotData;
}

/*
 * fetch_lot_content_details : fetch the details of Lot content
 */

function fetch_lot_content_details($lot_content_id) {
    $lotContentDataSql = mysql_query("SELECT cld.id, cld.entity_id, cld.lot_id, cld.entity_name, cl.lot_type, loc.label as locality, city.label as lot_city,"
            . " cld.content, cld.updated_content  "
            . " FROM " . CONTENT_LOT_DETAILS . " cld"
            . " INNER JOIN " . CONTENT_LOTS . " cl on cld.lot_id = cl.id"
            . " LEFT JOIN " . CITY . " city on city.city_id = cl.lot_city"
            . " LEFT JOIN resi_project rp on rp.project_id = cld.entity_id and cl.lot_type = 'project'"
            . " LEFT JOIN locality loc on loc.locality_id = rp.locality_id and cl.lot_type = 'project'"
            . " WHERE cld.id = '" . $lot_content_id . "'") or die(mysql_error());

    $lotContentData = mysql_fetch_object($lotContentDataSql);

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
    
    $prevKey = null; $nextKey = null;
    if (($currentKey - 1) >= 0)
        $prevKey = $lotContentIds[$currentKey - 1];
    if (($currentKey + 1) <= (count($lotContentIds) - 1))
        $nextKey = $lotContentIds[$currentKey + 1];
   
    return array('prevKey' => $prevKey, 'nextKey' => $nextKey);
}

?>