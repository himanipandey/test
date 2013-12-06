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

    $queryLessThenMaxLoc = "";
    $queryLessThenMaxLoc = " AND a.PRIORITY < ".MAX_PRIORITY;
    $queryLoc = "SELECT a.LOCALITY_ID, a.LABEL, a.PRIORITY 
                FROM ".LOCALITY." a
                inner join suburb b
                  on a.suburb_id = b.suburb_id
                inner join city c
                  on b.city_id = c.city_id
                WHERE
                  c.CITY_ID ='".$cityId ."'".$queryLessThenMaxLoc." ORDER BY a.LABEL ASC";
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
    if( $tablename == 'locality' ) {
        $sub = "select suburb_id from suburb where city_id = $cityID";
        $resSub = mysql_query($sub);
        while($subId = mysql_fetch_assoc($resSub)) {
            $loc = "select locality_id from locality where suburb_id = ".$subId['suburb_id'];
            $resLoc = mysql_query($loc);
            while($locId = mysql_fetch_assoc($resLoc)) {
                $query = "UPDATE ".$tablename." SET PRIORITY = (PRIORITY+1) WHERE PRIORITY >=".$priority." AND PRIORITY < 15";
            }
        }  
    }
    else {
       $query = "UPDATE ".$tablename." SET PRIORITY = (PRIORITY+1) WHERE CITY_ID='".$cityID."' AND PRIORITY >=".$priority." AND PRIORITY < 15"; 
    }
    mysql_query($query) or die(mysql_error());
}
function checkSubLocInCity($type,$cityId,$typeId){

	if($type == 'suburb')
	{
		$rs = mysql_fetch_object(mysql_query('select count(*) as cnt FROM '.SUBURB.' where CITY_ID="'.$cityId.'" and SUBURB_ID ="'.$typeId.'"'));
		if ($rs->cnt)
			return true;
			
	}elseif($type == 'locality')
	{
		$qry = 'select a.LABEL, a.PRIORITY, a.LOCALITY_ID 
            FROM '.LOCALITY.' a 
            inner join suburb b
               on a.suburb_id = b.suburb_id
            inner join city c
               on b.city_id = c.city_id
          where b.CITY_ID="'.$cityId.'" 
           AND a.LOCALITY_ID = "'. $typeId .'"';
	   $rs = mysql_query($qry) or die(mysql_error());
		if ($rs && mysql_num_rows($rs) )
			return true;
	}
	
	return false;
	
}
/* * ******suburb list with id************* */
function localityArr($cityId) {
    $qry = "SELECT a.LOCALITY_ID,a.LABEL FROM " . LOCALITY . " a
              inner join suburb b
                on a.suburb_id = b.suburb_id
              inner join city c
                on b.suburb_id = c.suburb_id
             WHERE 
                c.CITY_ID = '" . $cityId . "' 
            ORDER BY a.LABEL ASC";
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
           $locList = "select l.locality_id from locality l 
                        inner join suburb s on l.suburb_id = s.suburb_id
                        inner join city c on s.city_id = c.city_id 
                        where c.city_id = $Id";
            $LocId = mysql_query($locList);
            $listLoc = '';
            $comma = ',';
            $cnt = 1;
            $rowCount = mysql_num_rows($LocId);
            while($locList = mysql_fetch_assoc($LocId)) {
                if($cnt != $rowCount)
                    $listLoc .= $locList['locality_id'].$comma;
                else
                    $listLoc .= $locList['locality_id'];
                $comma = ',';
              $cnt++;
            }
            $queryLessThenMax = " AND rp.DISPLAY_ORDER > 0 AND rp.DISPLAY_ORDER < ".PROJECT_MAX_PRIORITY;
            $where = "rp.locality_id in ($listLoc)" .$queryLessThenMax;
            $orderby = "ORDER BY rp.DISPLAY_ORDER $orderBy, rp.PROJECT_NAME ASC";
            break;
        case "suburb":
            $locList = "select l.locality_id from locality l 
                        inner join suburb s on l.suburb_id = s.suburb_id
                        where s.suburb_id = $Id";
            $LocId = mysql_query($locList);
            $listLoc = '';
            $comma = ',';
            $cnt = 1;
            $rowCount = mysql_num_rows($LocId);
            while($locList = mysql_fetch_assoc($LocId)) {
                if($cnt != $rowCount)
                    $listLoc .= $locList['locality_id'].$comma;
                else
                    $listLoc .= $locList['locality_id'];
                $comma = ',';
             $cnt++;
            }
            $queryLessThenMax = " AND rp.DISPLAY_ORDER_SUBURB > 0 AND rp.DISPLAY_ORDER_SUBURB < ".PROJECT_MAX_PRIORITY;
            $where = "rp.locality_id in ($listLoc)" .$queryLessThenMax;
            $orderby = "ORDER BY rp.DISPLAY_ORDER_SUBURB $orderBy, rp.PROJECT_NAME ASC";
            break;
        case "locality":
            $queryLessThenMax = " AND rp.DISPLAY_ORDER_LOCALITY > 0 AND rp.DISPLAY_ORDER_LOCALITY < ".PROJECT_MAX_PRIORITY;
            $where = "rp.LOCALITY_ID = '" . $Id . "'" .$queryLessThenMax;
            $orderby = "ORDER BY rp.DISPLAY_ORDER_LOCALITY $orderBy, rp.PROJECT_NAME ASC";
            break;
    }
    $qry = "SELECT rp.PROJECT_NAME, rp.PROJECT_ID, c.CITY_ID, s.SUBURB_ID, l.LOCALITY_ID, rp.DISPLAY_ORDER, rp.DISPLAY_ORDER_LOCALITY, rp.DISPLAY_ORDER_SUBURB 
            FROM " . RESI_PROJECT . " rp 
            inner join locality l on rp.locality_id = l.locality_id
            inner join suburb s on l.suburb_id = s.suburb_id
            inner join city c on s.city_id = c.city_id
            WHERE ".$where." ".$orderby;
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
            $locList = "select l.locality_id from locality l 
                inner join suburb s on l.suburb_id = s.suburb_id
                inner join city c on s.city_id = c.city_id 
                where c.city_id = $modeid";
            $LocId = mysql_query($locList);
            $listLoc = '';
            $cnt = 1;
            $rowCount = mysql_num_rows($LocId);
            while($locList = mysql_fetch_assoc($LocId)) {
				$comma = ',';
                if($cnt != $rowCount)
                    $listLoc .= $locList['locality_id'].$comma;
                else
                    $listLoc .= $locList['locality_id'];
                
             $cnt++;
            }
            $where = "LOCALITY_ID in ($listLoc)";
            $update = "DISPLAY_ORDER = '$priority'";
            break;
        case "suburb":
            $locList = "select l.locality_id from locality l 
                        inner join suburb s on l.suburb_id = s.suburb_id
                        where s.suburb_id = $modeid";
            $LocId = mysql_query($locList);
            $listLoc = '';
            $cnt = 1;
            $rowCount = mysql_num_rows($LocId);
            while($locList = mysql_fetch_assoc($LocId)) {
				$comma = ',';
                if($cnt != $rowCount)
                    $listLoc .= $locList['locality_id'].$comma;
                else
                    $listLoc .= $locList['locality_id'];
                
             $cnt++;
            }
            $where = "LOCALITY_ID in ($listLoc)";
            $update = "DISPLAY_ORDER_SUBURB = '$priority'";
            break;
        case "locality":
            $where = "LOCALITY_ID = '" . $modeid . "'";
            $update = "DISPLAY_ORDER_LOCALITY = '$priority'";
            break;
    }
    $qry = "UPDATE " . RESI_PROJECT . " SET $update WHERE ".$where." AND PROJECT_ID = '".$projectId."'";
    mysql_query($qry);
    if(mysql_affected_rows()>0){
        echo "1";
    }
    else{
        echo "3";
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
            $locList = "select l.locality_id from locality l 
                inner join suburb s on l.suburb_id = s.suburb_id
                inner join city c on s.city_id = c.city_id 
                where c.city_id = $id";
            $LocId = mysql_query($locList);
            $listLoc = '';
            $cnt = 1;
            $rowCount = mysql_num_rows($LocId);
            while($locList = mysql_fetch_assoc($LocId)) {
                if($cnt != $rowCount)
                    $listLoc .= $locList['locality_id'].$comma;
                else
                    $listLoc .= $locList['locality_id'];
                $comma = ',';
             $cnt++;
            }
            $where = "LOCALITY_ID in ($listLoc) AND DISPLAY_ORDER >= ".$priority." AND DISPLAY_ORDER < ".PROJECT_MAX_VALID_PRIORITY;
            $update = "DISPLAY_ORDER = (DISPLAY_ORDER+1)";
            break;
        case "suburb":
            $locList = "select l.locality_id from locality l 
                        inner join suburb s on l.suburb_id = s.suburb_id
                        where s.suburb_id = $id";
            $LocId = mysql_query($locList);
            $listLoc = '';
            $cnt = 1;
            $rowCount = mysql_num_rows($LocId);
            while($locList = mysql_fetch_assoc($LocId)) {
                if($cnt != $rowCount)
                    $listLoc .= $locList['locality_id'].$comma;
                else
                    $listLoc .= $locList['locality_id'];
                $comma = ',';
             $cnt++;
            }
            $where = "LOCALITY_ID in ($listLoc) AND DISPLAY_ORDER_SUBURB >= ".$priority." AND DISPLAY_ORDER_SUBURB < ".PROJECT_MAX_VALID_PRIORITY;
            $update = "DISPLAY_ORDER_SUBURB = (DISPLAY_ORDER_SUBURB+1)";
            break;
        case "locality":
            $where = "LOCALITY_ID = '" . $id . "' AND DISPLAY_ORDER_LOCALITY >= ".$priority." AND DISPLAY_ORDER_LOCALITY < ".PROJECT_MAX_VALID_PRIORITY;
            $update = "DISPLAY_ORDER_LOCALITY = (DISPLAY_ORDER_LOCALITY+1)";
            break;
    }
    $qry = "UPDATE " . RESI_PROJECT . " SET $update WHERE ".$where;
    mysql_query($qry) or die(mysql_error());
}
function getProjectCount($Id, $type){
    switch($type)
    {
        case "city":
            $locList = "select l.locality_id from locality l 
                inner join suburb s on l.suburb_id = s.suburb_id
                inner join city c on s.city_id = c.city_id 
                where c.city_id = $Id";
            $LocId = mysql_query($locList);
            $listLoc = '';
            $cnt = 1;
            $rowCount = mysql_num_rows($LocId);
            while($locList = mysql_fetch_assoc($LocId)) {
                if($cnt != $rowCount)
                    $listLoc .= $locList['locality_id'].$comma;
                else
                    $listLoc .= $locList['locality_id'];
                $comma = ',';
             $cnt++;
            }
            $queryLessThenMax = " AND DISPLAY_ORDER > 0 AND DISPLAY_ORDER <= ".PROJECT_MAX_VALID_PRIORITY;
            $where = "LOCALITY_ID IN ($listLoc)";
            break;
        case "suburb":
            $locList = "select l.locality_id from locality l 
                        inner join suburb s on l.suburb_id = s.suburb_id
                        where s.suburb_id = $Id";
            $LocId = mysql_query($locList);
            $listLoc = '';
            $cnt = 1;
            $rowCount = mysql_num_rows($LocId);
            while($locList = mysql_fetch_assoc($LocId)) {
                if($cnt != $rowCount)
                    $listLoc .= $locList['locality_id'].$comma;
                else
                    $listLoc .= $locList['locality_id'];
                $comma = ',';
             $cnt++;
            }
            $queryLessThenMax = " AND DISPLAY_ORDER_SUBURB > 0 AND DISPLAY_ORDER_SUBURB < ".PROJECT_MAX_VALID_PRIORITY;
            $where = "LOCALITY_ID IN ($listLoc)";
            break;
        case "locality":
            $queryLessThenMax = " AND DISPLAY_ORDER_LOCALITY > 0 AND DISPLAY_ORDER_LOCALITY < ".PROJECT_MAX_VALID_PRIORITY;
            $where = "LOCALITY_ID = '" . $Id . "'";
            break;
    }
    $qry = "SELECT COUNT(*) AS CNT FROM " . RESI_PROJECT . " WHERE ".$where.$queryLessThenMax;
    $res = mysql_query($qry) or die(mysql_error());
    $data = mysql_fetch_assoc($res);
    return $data['CNT'];
}
function autoAdjustMaxCountProjPrio($id = null, $priority = null, $type = null)
{
    switch($type)
    {
        case "city":
            $locList = "select l.locality_id from locality l 
                inner join suburb s on l.suburb_id = s.suburb_id
                inner join city c on s.city_id = c.city_id 
                where c.city_id = $id";
            $LocId = mysql_query($locList);
            $listLoc = '';
            $cnt = 1;
            $rowCount = mysql_num_rows($LocId);
            while($locList = mysql_fetch_assoc($LocId)) {
                if($cnt != $rowCount)
                    $listLoc .= $locList['locality_id'].$comma;
                else
                    $listLoc .= $locList['locality_id'];
                $comma = ',';
             $cnt++;
            }
            $where = "LOCALITY_ID IN ($listLoc) AND DISPLAY_ORDER >= ".$priority." AND DISPLAY_ORDER < ".PROJECT_MAX_PRIORITY;
            $update = "DISPLAY_ORDER = ".PROJECT_MAX_PRIORITY;
            $orderby = " ORDER BY DISPLAY_ORDER DESC";
            break;
        case "suburb":
             $locList = "select l.locality_id from locality l 
                inner join suburb s on l.suburb_id = s.suburb_id
                where s.suburb_id = $id";
            $LocId = mysql_query($locList);
            $listLoc = '';
            $cnt = 1;
            $rowCount = mysql_num_rows($LocId);
            while($locList = mysql_fetch_assoc($LocId)) {
				$comma = ',';
                if($cnt != $rowCount)
                    $listLoc .= $locList['locality_id'].$comma;
                else
                    $listLoc .= $locList['locality_id'];
                
             $cnt++;
            }
            $where = "LOCALITY_ID in ($listLoc) AND DISPLAY_ORDER_SUBURB >= ".$priority." AND DISPLAY_ORDER_SUBURB < ".PROJECT_MAX_PRIORITY;
            $update = "DISPLAY_ORDER_SUBURB = ".PROJECT_MAX_PRIORITY;
            $orderby = " ORDER BY DISPLAY_ORDER_SUBURB DESC";
            break;
        case "locality":
            $where = "LOCALITY_ID = '" . $id . "' AND DISPLAY_ORDER_LOCALITY >= ".$priority." AND DISPLAY_ORDER_LOCALITY < ".PROJECT_MAX_PRIORITY;
            $update = "DISPLAY_ORDER_LOCALITY = ".PROJECT_MAX_PRIORITY;
            $orderby = " ORDER BY DISPLAY_ORDER_LOCALITY DESC";
            break;
    }
    $qry = "UPDATE " . RESI_PROJECT . " SET $update WHERE ".$where.$orderby." LIMIT 1";
    $res = mysql_query($qry) or die(mysql_error());
}
function checkProjAvail($projectId = null, $priority = null, $mode = null, $modeid = null)
{
    switch($mode)
    {
        case "city":
            $locList = "select l.locality_id from locality l 
                inner join suburb s on l.suburb_id = s.suburb_id
                inner join city c on s.city_id = c.city_id 
                where c.city_id = $modeid";
            $LocId = mysql_query($locList);
            $listLoc = '';
            $cnt = 1;
            $rowCount = mysql_num_rows($LocId);
            while($locList = mysql_fetch_assoc($LocId)) {
				$comma = ',';
                if($cnt != $rowCount)
                    $listLoc .= $locList['locality_id'].$comma;
                else
                    $listLoc .= $locList['locality_id'];
                
             $cnt++;
            }
            $where = "LOCALITY_ID in ($listLoc)";
            break;
        case "suburb":
             $locList = "select l.locality_id from locality l 
                inner join suburb s on l.suburb_id = s.suburb_id
                where s.suburb_id = $modeid";
            $LocId = mysql_query($locList);
            $listLoc = '';
           $cnt = 1;
            $rowCount = mysql_num_rows($LocId);
            while($locList = mysql_fetch_assoc($LocId)) {
                $comma = ',';
                if($cnt != $rowCount)
                    $listLoc .= $locList['locality_id'].$comma;
                else
                    $listLoc .= $locList['locality_id'];
                
             $cnt++;
            }
            $where = "LOCALITY_ID in ($listLoc)";
            break;
        case "locality":
            $where = "LOCALITY_ID = '" . $modeid . "'";
            break;
    }
    $qry = "SELECT COUNT(*) AS CNT FROM " . RESI_PROJECT . " WHERE ".$where." AND PROJECT_ID = '".$projectId."'";
    $res = mysql_query($qry);
    $data = mysql_fetch_assoc($res);

    return $data['CNT'];
}
?>
