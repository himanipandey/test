<?php
error_reporting(1);
ini_set('display_errors','1');
include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("includes/configs/configs.php");
include("builder_function.php");
AdminAuthentication();

if ( !isset($_REQUEST['term']) )
    exit;
$data = array();
if($_REQUEST['type'] == 'suburb')
{
    $rs = mysql_query('select LABEL, PRIORITY, SUBURB_ID FROM '.SUBURB.' where CITY_ID="'.$_REQUEST["cityId"].'" AND (LABEL like "'. mysql_real_escape_string($_REQUEST['term']) .'%" OR SUBURB_ID like "'. mysql_real_escape_string($_REQUEST['term']) .'%") order by LABEL ASC limit 0,10');
    if ($rs && mysql_num_rows($rs) )
    {
        while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
        {
            $data[] = array(
                'label' => $row['LABEL'] .' - '. $row['SUBURB_ID'],
                'value' => $row['SUBURB_ID']
            );
        }
    }
}
else if($_REQUEST['type'] == 'locality')
{
    $qry = 'select a.LABEL, a.PRIORITY, a.LOCALITY_ID 
            FROM '.LOCALITY.' a 
            inner join city c
               on a.city_id = c.city_id
          where a.CITY_ID="'.$_REQUEST["cityId"].'" 
           AND (a.LABEL like "'. mysql_real_escape_string($_REQUEST['term']) .'%"  
           OR a.LOCALITY_ID like "'. mysql_real_escape_string($_REQUEST['term']) .'%")  
          order by a.LABEL ASC limit 0,10';
   $rs = mysql_query($qry) or die(mysql_error());
    if ($rs && mysql_num_rows($rs) )
    {
        while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
        {
            $data[] = array(
                'label' => $row['LABEL'] .' - '. $row['LOCALITY_ID'],
                'value' => $row['LOCALITY_ID']
            );
        }
    }
}
else if($_REQUEST['type'] == 'project')
{
    if($_GET['mode'] == 'city'){
        $locList = "select l.locality_id from locality l 
            inner join city c on l.city_id = c.city_id 
            where c.city_id = ".$_REQUEST['id'];
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
        $where = "LOCALITY_ID in ($listLoc) AND ";
    }else if($_GET['mode'] == 'suburb'){
        $locList = "select lsm.locality_id from locality_suburb_mappings lsm 
            where lsm.suburb_id = ".$_REQUEST['id'];
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
        $where = "LOCALITY_ID in ($listLoc) AND ";
    }else if($_GET['mode'] == 'locality'){
        $where = "LOCALITY_ID=".$_REQUEST['id']." AND ";
    }
    
    $rs = mysql_query("select PROJECT_NAME, PROJECT_ID FROM ".RESI_PROJECT." where ".$where." (PROJECT_NAME like '". mysql_real_escape_string($_REQUEST['term']) ."%'  OR PROJECT_ID like '". mysql_real_escape_string($_REQUEST['term']) ."%') and version = 'Cms'  order by PROJECT_NAME ASC limit 0,10");
    if ($rs && mysql_num_rows($rs) )
    {
        while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
        {
            $data[] = array(
                'label' => $row['PROJECT_NAME'] .' - '. $row['PROJECT_ID'],
                'value' => $row['PROJECT_ID']
            );
        }
    }
}else if($_REQUEST['type'] == 'broker')
{
	$rs = mysql_query('select BROKER_NAME, BROKER_ID FROM '.BROKER_LIST.' where BROKER_NAME like "'. mysql_real_escape_string($_REQUEST['term']) .'%"  order by BROKER_NAME ASC limit 0,10');
    if ($rs && mysql_num_rows($rs) )
    {
        while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
        {
            $data[] = array(
                'label' =>  $row['BROKER_NAME'],
                'value' => $row['BROKER_NAME']
            );
        }
    }
 }else if($_REQUEST['type'] == 'townships')
{
	$rs = mysql_query('select township_name FROM townships where township_name like "'. mysql_real_escape_string($_REQUEST['term']) .'%"  order by township_name ASC limit 0,10');
    if ($rs && mysql_num_rows($rs) )
    {
        while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
        {
            $data[] = array(
                'label' =>  $row['township_name'],
                'value' => $row['township_name']
            );
        }
    }
 }else if($_REQUEST['type'] == 'forumUser')
{
	$rs = mysql_query("SELECT `EMAIL` FROM `proptiger`.`FORUM_USER` WHERE `EMAIL` LIKE '".mysql_real_escape_string($_REQUEST['term'])."%' AND STATUS='1' ORDER BY `EMAIL` LIMIT 0,10");
    if ($rs && mysql_num_rows($rs) )
    {
        while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
        {
            $data[] = array(
                'label' =>  $row['EMAIL'],
                'value' => $row['EMAIL']
            );
        }
    }
 }
echo json_encode($data);
flush();
?>  
