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
            $where = "rp.version = 'Cms' and rp.locality_id in ($listLoc)" .$queryLessThenMax;
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
            $where = "rp.version = 'Cms' and rp.locality_id in ($listLoc)" .$queryLessThenMax;
            $orderby = "ORDER BY rp.DISPLAY_ORDER_SUBURB $orderBy, rp.PROJECT_NAME ASC";
            break;
        case "locality":
            $queryLessThenMax = " AND rp.DISPLAY_ORDER_LOCALITY > 0 AND rp.DISPLAY_ORDER_LOCALITY < ".PROJECT_MAX_PRIORITY;
            $where = "rp.version = 'Cms' and rp.LOCALITY_ID = '" . $Id . "'" .$queryLessThenMax;
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



function getNearPlacesArrfromCity($cityId, $order, $placeType=0)
{
    global $orderBy;
    $orderBy = $order; 
    if($placeType==0)
         $where = " np.city_id = $cityId";
    else
        $where = "np.city_id = $cityId and  np.place_type_id = $placeType"; //.$queryLessThenMax;
    $orderby = " ORDER BY np.priority $orderBy, np.place_type_id, np.name ASC";
    $qry = "SELECT np.name, np.id, np.city_id, np.latitude, np.longitude, np.vicinity, np.status, npt.display_name, np.priority
            FROM " . locality_near_places. " np 
            inner join near_place_types npt on npt.id = np.place_type_id
            WHERE ".$where." ". $orderby;

    $res = mysql_query($qry) or die(mysql_error());
    //print_r($res)
    $arr = array();
     while ($data = mysql_fetch_assoc($res)) {
        //echo $data;
        array_push($arr, $data);
    }
    return $arr;

}

function getNearPlacesArr($cityId, $localityId ,$type, $order, $placeType=0)
{
    global $orderBy;
    $orderBy = $order;
    $queryLessThenMax = "";
    
    switch($type)
    {
        case "city":
           /*$locList = "select l.locality_id from locality l 
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
            //$queryLessThenMax = " AND np.DISPLAY_ORDER > 0 AND np.DISPLAY_ORDER < ".NEAR_PLACES_MAX_PRIORITY;
            */
            
            break;
        /*case "suburb":
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
            //$queryLessThenMax = "  rp.DISPLAY_ORDER_SUBURB > 0 AND rp.DISPLAY_ORDER_SUBURB < ".PROJECT_MAX_PRIORITY;
            //$where = "np.locality_id in ($listLoc)".$queryLessThenMax;
            if($placeType==0)
                $where = "np.locality_id in ($listLoc)";
            else
                $where = "np.locality_id in ($listLoc) and np.place_type_id = $placeType";
            $orderby = "ORDER BY np.priority $orderBy, np.place_type_id, np.name ASC";
            break;*/
        case "locality":
            //print_r("here1");
            //$queryLessThenMax = " AND rp.DISPLAY_ORDER_LOCALITY > 0 AND rp.DISPLAY_ORDER_LOCALITY < ".PROJECT_MAX_PRIORITY;
            //$where = "$Id ="; 
            $results = mysql_query ("SELECT LATITUDE, LONGITUDE FROM locality l WHERE l.LOCALITY_ID = $localityId");
            $row = mysql_fetch_assoc($results);
            $lat = $row['LATITUDE'];
            $lon = $row['LONGITUDE'];
            /*$results1 = mysql_query("SELECT np.id, np.latitude, np.longitude FROM locality_near_places np WHERE np.city_id = $cityId");
           
            $NearPlacesArr = array();
            while ($row1 = mysql_fetch_assoc($results1)) 
            {
                if(getDistance($row['LATITUDE'], $row['LONGITUDE'], $row1['latitude'], $row1['longitude']) < 5)
                {
                    array_push($NearPlacesArr, $row1['id']);
                    //echo ($row1['id']);
                }
                    
                
            }*/

            //print_r($NearPlacesArr);
            //die("here");
            if($placeType!=0)
                $where = " and np.place_type_id = $placeType";
            else $where = "";
                

           
            $orderby = "ORDER BY np.priority $orderBy, np.place_type_id, np.name ASC";
            
            $qry = "SELECT np.name, np.city_id, np.id, np.latitude, np.longitude, np.vicinity, np.status, npt.display_name, np.priority
            FROM " . locality_near_places. " np 
                
            inner join near_place_types npt on npt.id = np.place_type_id
            where get_distance_in_kms_between_geo_locations($lat, $lon, np.latitude, np.longitude) < 5"
            .$where." ".$orderby;

             //print_r($qry);
            break;
    }
    $res = mysql_query($qry) or die(mysql_error());
    //print_r($res)
    $arr = array();
    while ($data = mysql_fetch_assoc($res)) {
        //echo $data;
        array_push($arr, $data);
    }
    return $arr;
}


function getDistance($lat, $lon, $lat2, $lon2)
{
    
//echo $lat."  ".$lon."  ".$lat2."  ".$lon2."  "; 
    //distance in km  
   /* 
    if (cos(deg2rad($lat2))*cos(deg2rad(lat))*cos(deg2rad(long)-deg2rad($long2))+sin(deg2rad($lat2))*sin(deg2rad(lat)) <= 1.0)
    {
        if(cos(deg2rad($lat2))*cos(deg2rad(lat))*cos(deg2rad(long)-deg2rad($long2))+sin(deg2rad($lat2))*sin(deg2rad(lat)) < -1.0)
            $distance = ((3959*acos(-1))*1.609344);
        else
            $distance = ((3959*acos(cos(deg2rad($lat2))*cos(deg2rad(lat))*cos(deg2rad(long)-deg2rad($long2))+sin(deg2rad($lat2))*sin(deg2rad(lat)) ))*1.609344);
    }
    else
        $distance = ((3959*acos(1))*1.609344);
    //echo abs($distance)." ";
    */


 $distance =((acos(sin($lat * pi() / 180) * sin($lat2 * pi() / 180) + cos($lat * pi() / 180) * cos($lat2 * pi() / 180) * cos(($lon - $lon2) * pi() / 180)) * 180 / pi()) * 60 * 1.1515)*1.609344;

//echo  $distance;
//$distance1 = (3958*3.1415926*sqrt(($lat2-$lat1)*($lat2-$lat1) + cos($lat2/57.29578)*cos($lat1/57.29578)*($lon2-$lon1)*($lon2-$lon1))/180);

    return abs($distance);


    /*((3959*Acos(
        CASE 
        WHEN
          cos(deg2rad(".$lat2."))*cos(deg2rad(lat))*cos(deg2rad(lon)-deg2rad(".$lon2."))+sin(deg2rad(".$lat2."))*sin(deg2rad(lat)) <= 1.0  THEN  
                CASE 
                  WHEN cos(deg2rad(".$lat2."))*cos(deg2rad(lat))*cos(deg2rad(lon)-deg2rad(".$lon2."))+sin(deg2rad(".$lat2."))*sin(deg2rad(lat)) < -1.0  THEN -1 
                   ELSE cos(deg2rad(".$lat2."))*cos(deg2rad(lat))*cos(deg2rad(lon)-deg2rad(".$lon2."))+sin(deg2rad(".$lat2."))*sin(deg2rad(lat))  
                   END 
        ELSE 1 
        END 
        ))*1.609344);*/
    

}


function updateNearPlace($nearPlaceId, $priority, $status, $mode = null, $modeid = null)
{
    /*switch($mode)
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
            $where = "locality_id in ($listLoc)";
            $update = "priority = '$priority'";
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
            $where = "locality_id in ($listLoc)";
            $update = "priority = '$priority'";
            break;
        case "locality":
            $where = "locality_id = '" . $modeid . "'";
            $update = "priority = '$priority'";
            break;
    }*/
    if($priority>0 && $priority<=5)
    {
        $update = " priority = '$priority', status = '$status'"; //die($status);
    }
        
    else 
    {
        $update = " status = '$status'"; //die("hello1");
    }
       
    $qry = "UPDATE " . locality_near_places . " SET $update WHERE id = '".$nearPlaceId."'";
    //die($qry);
    mysql_query($qry);
    if(mysql_affected_rows()>0){
        echo "1";
    }
    else{
        echo "3";
    }
}


function checkNearPlaceAvail($nearPlaceId = null, $priority = null, $mode = null, $modeid = null)
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
            $where = "locality_id in ($listLoc)";
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
            $where = "locality_id in ($listLoc)";
            break;
        case "locality":
            $where = "locality_id = '" . $modeid . "'";
            break;
    }
    $qry = "SELECT COUNT(*) AS CNT FROM " . locality_near_places . " WHERE ".$where." AND id = '".$nearPlaceId."'";
    $res = mysql_query($qry);
    $data = mysql_fetch_assoc($res);

    return $data['CNT'];
}
?>




