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
    $mode = !empty($_POST['mode'])?$_POST['mode']:'';
    $alloc = mysql_escape_string(trim($_POST['locality']));
    $alloc = explode("," , $alloc); 
    $data = array();
    $chkData = '';
    $city_id = '';
    
    if(!empty($alloc))
    {
        foreach($alloc as $key => $val)
        {
            $temp = explode("-" , $val);
            if($temp[0] == 'all')
            {
                $city_id = $temp[1];
                $data = array();
                $sql = '';
                if(!empty($mode) && $mode > 0)
                {
                    $sql = @mysql_query("SELECT resi_project.project_id , resi_project.project_name FROM resi_project 
                                        LEFT JOIN locality ON resi_project.locality_id = locality.locality_id
                                        LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id
                                        LEFT JOIN city ON suburb.city_id = city.city_id
                                        WHERE city.city_id = '".$city_id."'
                                        AND resi_project.project_id IN (SELECT project_id FROM rule_project_mappings)");
                }
                else
                {
                    $sql = @mysql_query("SELECT resi_project.project_id , resi_project.project_name FROM resi_project 
                                        LEFT JOIN locality ON resi_project.locality_id = locality.locality_id
                                        LEFT JOIN suburb ON locality.suburb_id = suburb.suburb_id
                                        LEFT JOIN city ON suburb.city_id = city.city_id
                                        WHERE city.city_id = '".$city_id."'
                                        AND resi_project.project_id NOT IN (SELECT project_id FROM rule_project_mappings)");    
                }
                
                while($row = @mysql_fetch_assoc($sql))
                {
                    $data[$row['project_id']] = $row['project_name'];    
                }
                
            }
            else
            {
                //$chkSql = @mysql_query("SELECT project_id FROM rule_project_mappings AS rpm LEFT JOIN rule_locality_mappings AS rlm ON rpm.rule_id = rlm.rule_locality_mapping_id WHERE rlm.locality_id = '".$val."'");
//                $resultQuery = @mysql_fetch_assoc($chkSql);
//                
//                if(isset($resultQuery['project_id']) && !empty($resultQuery['project_id']) && $resultQuery['project_id'] == '-1')
//                {
//                    $data = array();
//                }
                /** If the above conditions doesn't fulfills
                 * then the normal Execution continues
                 */
                $sql = '';
                if(!empty($mode) && $mode > 0)
                {
                    $sql = @mysql_query("SELECT resi_project.project_id , resi_project.project_name FROM resi_project WHERE locality_id = ".$val." AND resi_project.project_id IN (SELECT project_id FROM rule_project_mappings)");
                }
                else
                {
                    $sql = @mysql_query("SELECT resi_project.project_id , resi_project.project_name FROM resi_project WHERE locality_id = ".$val." AND resi_project.project_id NOT IN (SELECT project_id FROM rule_project_mappings)");    
                }
                
                while($row = @mysql_fetch_assoc($sql))
                {
                    $data[$row['project_id']] = $row['project_name'];    
                }    
            }
            
            
        }
    }
    echo json_encode($data);
    exit();
}

?>