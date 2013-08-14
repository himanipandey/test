<?php
$orderBy = "ASC";
function cmp($value1, $value2)
{
    global $orderBy;
    if($orderBy == 'ASC')
    {
        if($value1['PRIORITY'] == $value2['PRIORITY'])
            return 0;
        else if( ($value1['PRIORITY'] < $value2['PRIORITY'] && $value1['PRIORITY'] > 0) || $value2['PRIORITY'] < 1)
            return -1;
        else
            return 1;
    }
    if($orderBy == 'DESC')
    {
        if($value1['PRIORITY'] == $value2['PRIORITY'])
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
    $querySub = "SELECT SUBURB_ID, LABEL, PRIORITY FROM ".SUBURB." WHERE CITY_ID ='".$cityId ."' AND PRIORITY < ".MAX_PRIORITY." ORDER BY LABEL ASC";
    $queryExecuteSub 	= mysql_query($querySub) or die(mysql_error());
    while ($row = mysql_fetch_assoc($queryExecuteSub)) {
        $row['ID'] = $row['SUBURB_ID'];
        array_push($arraySubLoc, $row);
    }

    $queryLoc = "SELECT LOCALITY_ID, LABEL, PRIORITY FROM ".LOCALITY." WHERE CITY_ID ='".$cityId ."' AND PRIORITY < ".MAX_PRIORITY." ORDER BY LABEL ASC";
    $queryExecuteLoc 	= mysql_query($queryLoc) or die(mysql_error());
    while ($row1 = mysql_fetch_assoc($queryExecuteLoc)) {
        $row1['ID'] = $row1['LOCALITY_ID'];
        array_push($arraySubLoc, $row1);
    }
    uasort($arraySubLoc, "cmp");
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
?>
