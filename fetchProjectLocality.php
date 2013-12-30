<?php

/**
 * @author AKhan
 * @copyright 2013
 */

include("smartyConfig.php");
include("appWideConfig.php");
include("dbConfig.php");
include("modelsConfig.php"); 
include("includes/configs/configs.php");
//print'<pre>';
//print_r($_POST);
//die;
//

if(!empty($_POST['locality']))
{
	$cityId = mysql_escape_string(trim($_POST['cityId']));
    $alloc = mysql_escape_string(trim($_POST['locality']));
    $alloc = explode("," , $alloc); 
    $data = array();
    $chkData = '';
    
    if($_POST['locality'] == 'all'){
	
		//fetching all localtiy in city
		$sql_loc = "SELECT locality.locality_id , locality.label FROM locality LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id LEFT JOIN city ON suburb.city_id = city.city_id  WHERE city.city_id = ".$cityId." AND locality.locality_id NOT IN (SELECT locality_id FROM rule_locality_mappings)";
                       $res_loc = mysql_query($sql_loc);
        $loc_arr = array();
        while($row_loc = mysql_fetch_object($res_loc))
			$loc_arr[] = $row_loc->locality_id;
			
		$all_locs = implode(",",$loc_arr);
		
		if(!empty($all_locs)){
			
			$sql = @mysql_query("SELECT resi_project.id , resi_project.project_name FROM resi_project WHERE locality_id in (".$all_locs.") AND resi_project.id NOT IN (SELECT project_id FROM rule_project_mappings)");
            while($row = @mysql_fetch_assoc($sql))
            {
                $data[$row['id']] = $row['project_name'];    
            }
		}
		
	}elseif(!empty($alloc))
    {
        foreach($alloc as $key => $val)
        {
            $sql = @mysql_query("SELECT resi_project.id , resi_project.project_name FROM resi_project WHERE locality_id = ".$val." AND resi_project.id NOT IN (SELECT project_id FROM rule_project_mappings)");
            while($row = @mysql_fetch_assoc($sql))
            {
                $data[$row['id']] = $row['project_name'];    
            }
        }
    }
    echo json_encode($data);
    exit();
}

?>
