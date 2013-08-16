<?php
$orderBy = "ASC";
function cmp($value1, $value2)
{
    global $orderBy;
    if($orderBy == 'ASC')
    {
        if($value1['PRIORITY'] == $value2['PRIORITY'] && $value1['LABEL'] < $value2['LABEL'])
            return 0;
        else if( ($value1['PRIORITY'] < $value2['PRIORITY'] && $value1['PRIORITY'] > 0) || $value2['PRIORITY'] < 1)
            return -1;
        else
            return 1;
    }
    if($orderBy == 'DESC')
    {
        if($value1['PRIORITY'] == $value2['PRIORITY']  && $value1['LABEL'] < $value2['LABEL'])
            return 0;
        else if( ($value1['PRIORITY'] > $value2['PRIORITY'] && $value1['PRIORITY'] > 0) || $value2['PRIORITY'] < 1)
            return -1;
        else
            return 1;    
    }
}

function getSubLocData($cityId, $order) {
    global $orderBy;
    $orderBy = $order;
    $arraySubLoc = array();
    $queryLessThenMax = "";
    $queryLessThenMax = " AND PRIORITY < ".MAX_PRIORITY;
    $querySub = "SELECT SUBURB_ID, LABEL, PRIORITY FROM ".SUBURB." WHERE CITY_ID ='".$cityId ."'".$queryLessThenMax." ORDER BY LABEL ASC";
    $queryExecuteSub 	= mysql_query($querySub) or die(mysql_error());
    while ($row = mysql_fetch_assoc($queryExecuteSub)) {
        $row['ID'] = $row['SUBURB_ID'];
        array_push($arraySubLoc, $row);
    }

    $queryLoc = "SELECT LOCALITY_ID, LABEL, PRIORITY FROM ".LOCALITY." WHERE CITY_ID ='".$cityId ."'".$queryLessThenMax." ORDER BY LABEL ASC";
    $queryExecuteLoc 	= mysql_query($queryLoc) or die(mysql_error());
    while ($row1 = mysql_fetch_assoc($queryExecuteLoc)) {
        $row1['ID'] = $row1['LOCALITY_ID'];
        array_push($arraySubLoc, $row1);
    }
    //echo "<pre>";print_r($arraySubLoc);    
    uasort($arraySubLoc, "cmp");
    //echo "<pre>";print_r($arraySubLoc);
    return $arraySubLoc;
}
function getLastValidPriority($arraySubLoc = array()){
    $highPrioDefault = 1;
    $highPrio = 0;
    foreach ($arraySubLoc as $k => $v) {
        if($v['PRIORITY'] < 100){
            $highPrio = $v['PRIORITY'];
        }
        else if($v['PRIORITY'] == 100){
            break;
        }
    }
    if($highPrioDefault == $highPrio){
        $highPrio = $highPrioDefault+1;        
    }elseif($highPrioDefault < $highPrio){
        $highPrio = $highPrio+1;     
    }else{
        $highPrio = 1;
    }
    return $highPrio;
}
function getAvaiValidPriority($arraySubLoc = array())
{
    $priorities = array();
    $highPriorityLeft = 1;
    foreach ($arraySubLoc as $k => $v) {
        if($v['PRIORITY'] < 100){
            $priorities[] = $v['PRIORITY'];
        }
        else if($v['PRIORITY'] == 100){
            break;
        }
    }
    $priorities = array_flip($priorities);
    for($i = 1; $i < 100; $i++)
    {
        if (!array_key_exists($i, $priorities)) {
            $highPriorityLeft = $i;
            break;
        }
    }
    return $highPriorityLeft;    
}
function getAvaiHighPriority($cityId){
    $arraySubLoc = array();
    $arraySubLoc = getSubLocData($cityId);
    //return getLastValidPriority($arraySubLoc);
    return getAvaiValidPriority($arraySubLoc);
}
function updateSuburb($subID = null , $priority = null)
{
    $querySub = "UPDATE ".SUBURB." SET PRIORITY = '".$priority."' WHERE SUBURB_ID ='".$subID ."'";
    mysql_query($querySub) or die(mysql_error());
    echo 1;
}
function updateLocality($locID = null, $priority = null)
{
    $queryLoc = "UPDATE ".LOCALITY." SET PRIORITY = '".$priority."' WHERE LOCALITY_ID ='".$locID ."'";
    mysql_query($queryLoc) or die(mysql_error());
    echo 1;
}
function autoAdjustPrio($tablename, $cityID = null, $priority = null)
{
    $query = "UPDATE ".$tablename." SET PRIORITY = (PRIORITY+1) WHERE CITY_ID='".$cityID."' AND PRIORITY >=".$priority." AND PRIORITY <".MAX_PRIORITY;
    mysql_query($query) or die(mysql_error());
}
/* * ******suburb list with id************* */
function localityArr($cityId) {
    $qry = "SELECT LOCALITY_ID,LABEL FROM " . LOCALITY . " WHERE CITY_ID = '" . $cityId . "' ORDER BY LABEL ASC";
    $res = mysql_query($qry);
    $arrCity = array();
    while ($data = mysql_fetch_assoc($res)) {
        $arrCity[$data['LOCALITY_ID']] = $data['LABEL'];
    }
    return $arrCity;
}
function getProjectArr($Id, $type, $order){
    global $orderBy;
    $orderBy = $order;
    $queryLessThenMax = "";
    switch($type)
    {
        case "city":
            $queryLessThenMax = " AND DISPLAY_ORDER > 0 AND DISPLAY_ORDER < ".PROJECT_MAX_PRIORITY;
            $where = "CITY_ID = '" . $Id . "'" .$queryLessThenMax;
            $orderby = "ORDER BY DISPLAY_ORDER $orderBy, PROJECT_NAME ASC";
            break;
        case "suburb":
            $queryLessThenMax = " AND DISPLAY_ORDER_SUBURB > 0 AND DISPLAY_ORDER_SUBURB < ".PROJECT_MAX_PRIORITY;
            $where = "SUBURB_ID = '" . $Id . "'" .$queryLessThenMax;
            $orderby = "ORDER BY DISPLAY_ORDER_SUBURB $orderBy, PROJECT_NAME ASC";
            break;
        case "locality":
            $queryLessThenMax = " AND DISPLAY_ORDER_LOCALITY > 0 AND DISPLAY_ORDER_LOCALITY < ".PROJECT_MAX_PRIORITY;
            $where = "LOCALITY_ID = '" . $Id . "'" .$queryLessThenMax;
            $orderby = "ORDER BY DISPLAY_ORDER_LOCALITY $orderBy, PROJECT_NAME ASC";
            break;
    }
    $qry = "SELECT PROJECT_NAME, PROJECT_ID, CITY_ID, SUBURB_ID, LOCALITY_ID, DISPLAY_ORDER, DISPLAY_ORDER_LOCALITY, DISPLAY_ORDER_SUBURB FROM " . RESI_PROJECT . " WHERE ".$where." ".$orderby;
    $res = mysql_query($qry) or die(mysql_error());
    $arr = array();
    while ($data = mysql_fetch_assoc($res)) {
        array_push($arr, $data);
    }
    return $arr;
}
function updateProj($projectId = null, $priority = null, $mode = null, $modeid = null)
{
    switch($mode)
    {
        case "city":
            $where = "CITY_ID = '" . $modeid . "'";
            $update = "DISPLAY_ORDER = '$priority'";
            break;
        case "suburb":
            $where = "SUBURB_ID = '" . $modeid . "'";
            $update = "DISPLAY_ORDER_SUBURB = '$priority'";
            break;
        case "locality":
            $where = "LOCALITY_ID = '" . $modeid . "'";
            $update = "DISPLAY_ORDER_LOCALITY = '$priority'";
            break;
    }
    $qry = "UPDATE " . RESI_PROJECT . " SET $update WHERE ".$where." AND PROJECT_ID = '".$projectId."'";
    $res = mysql_query($qry);
    if($res > 0){
        echo "1";
    }
}
function getAvaiHighProjectPriority($cityId = null, $localityid = null, $suburbid = null)
{
    global $orderBy;
    $arr = array();
    $reversed = array();
    if(!empty($suburbid)){
       $arr = getProjectArr($suburbid, 'suburb', $orderBy);
       $reversed = array_reverse($arr);
       return $reversed['0']['DISPLAY_ORDER_SUBURB'];
    }else if(!empty($localityid)){
       $arr = getProjectArr($localityid, 'locality', $orderBy);
       $reversed = array_reverse($arr);
       return $reversed['0']['DISPLAY_ORDER_LOCALITY'];
    }else if(!empty($cityId)){
       $arr = getProjectArr($cityId, 'city', $orderBy);
       $reversed = array_reverse($arr);
       return $reversed['0']['DISPLAY_ORDER'];
    }
}
function autoAdjustProjPrio($id = null, $priority = null, $type = null)
{
    switch($type)
    {
        case "city":
            $where = "CITY_ID = '" . $id . "' AND DISPLAY_ORDER >= ".$priority." AND DISPLAY_ORDER < ".PROJECT_MAX_VALID_PRIORITY;
            $update = "DISPLAY_ORDER = (DISPLAY_ORDER+1)";
            break;
        case "suburb":
            $where = "SUBURB_ID = '" . $id . "' AND DISPLAY_ORDER_SUBURB >= ".$priority." AND DISPLAY_ORDER_SUBURB < ".PROJECT_MAX_VALID_PRIORITY;
            $update = "DISPLAY_ORDER_SUBURB = (DISPLAY_ORDER_SUBURB+1)";
            break;
        case "locality":
            $where = "LOCALITY_ID = '" . $id . "' AND DISPLAY_ORDER_LOCALITY >= ".$priority." AND DISPLAY_ORDER_LOCALITY < ".PROJECT_MAX_VALID_PRIORITY;
            $update = "DISPLAY_ORDER_LOCALITY = (DISPLAY_ORDER_LOCALITY+1)";
            break;
    }
    $qry = "UPDATE " . RESI_PROJECT . " SET $update WHERE ".$where;
    $res = mysql_query($qry) or die(mysql_error());
}
function getProjectCount($Id, $type){
    switch($type)
    {
        case "city":
            $queryLessThenMax = " AND DISPLAY_ORDER > 0 AND DISPLAY_ORDER <= ".PROJECT_MAX_VALID_PRIORITY;
            $where = "CITY_ID = '" . $Id . "'";
            break;
        case "suburb":
            $queryLessThenMax = " AND DISPLAY_ORDER_SUBURB > 0 AND DISPLAY_ORDER_SUBURB < ".PROJECT_MAX_VALID_PRIORITY;
            $where = "SUBURB_ID = '" . $Id . "'";
            break;
        case "locality":
            $queryLessThenMax = " AND DISPLAY_ORDER_LOCALITY > 0 AND DISPLAY_ORDER_LOCALITY < ".PROJECT_MAX_VALID_PRIORITY;
            $where = "LOCALITY_ID = '" . $Id . "'";
            break;
    }
    $qry = "SELECT COUNT(*) AS CNT FROM " . RESI_PROJECT . " WHERE ".$where;
    $res = mysql_query($qry) or die(mysql_error());
    $data = mysql_fetch_assoc($res);
    return $data['CNT'];
}
function autoAdjustMaxCountProjPrio($id = null, $priority = null, $type = null)
{
    switch($type)
    {
        case "city":
            $where = "CITY_ID = '" . $id . "' AND DISPLAY_ORDER >= ".PROJECT_MAX_VALID_PRIORITY." AND DISPLAY_ORDER < ".PROJECT_MAX_PRIORITY;
            $update = "DISPLAY_ORDER = ".PROJECT_MAX_PRIORITY;
            break;
        case "suburb":
            $where = "SUBURB_ID = '" . $id . "' AND DISPLAY_ORDER_SUBURB >= ".PROJECT_MAX_VALID_PRIORITY." AND DISPLAY_ORDER_SUBURB < ".PROJECT_MAX_PRIORITY;
            $update = "DISPLAY_ORDER_SUBURB = ".PROJECT_MAX_PRIORITY;
            break;
        case "locality":
            $where = "LOCALITY_ID = '" . $id . "' AND DISPLAY_ORDER_LOCALITY >= ".PROJECT_MAX_VALID_PRIORITY." AND DISPLAY_ORDER_LOCALITY < ".PROJECT_MAX_PRIORITY;
            $update = "DISPLAY_ORDER_LOCALITY = ".PROJECT_MAX_PRIORITY;
            break;
    }
    $qry = "UPDATE " . RESI_PROJECT . " SET $update WHERE ".$where." LIMIT 1";
    $res = mysql_query($qry) or die(mysql_error());
}
?>
